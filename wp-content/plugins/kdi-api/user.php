<?php
add_action('rest_api_init', function () {
    register_rest_route(API_URL, '/register', [
        'methods' => 'POST',
        'callback' => 'kdi_register_user',
        'permission_callback' => '__return_true', // ⚠️ In production you should not leave this fully open
    ]);

    // List users
    register_rest_route(API_URL, '/users', [
        'methods' => 'GET',
        'callback' => 'kdi_list_users',
        'permission_callback' => function () {
            return current_user_can('list_users');
        },
    ]);

    // User detail
    register_rest_route(API_URL, '/users/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'kdi_get_user',
        'permission_callback' => function () {
            return current_user_can('list_users');
        },
    ]);

    register_rest_route(API_URL, '/users/username/(?P<username>[a-zA-Z0-9_-]+)', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_user_by_username',
    ]);

    // Update user
    register_rest_route(API_URL, '/users/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'kdi_update_user',
        'permission_callback' => function (WP_REST_Request $request) {
            $id = (int) $request['id'];
            return current_user_can('edit_users') || get_current_user_id() === $id;
        },
    ]);

    // Delete user
    register_rest_route(API_URL, '/users/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'kdi_delete_user',
        'permission_callback' => function () {
            return current_user_can('delete_users');
        },
    ]);
});

// 🔹 Format WP_User for response (allow filter for extensions)
function kdi_format_user($user, $expose_email = false)
{
    $data = [
        // Core fields from wp_users
        'id'                  => $user->ID,
        'username'          => $user->user_login,
        //'user_pass'         => $user->user_pass, // ❌ Không nên public
        'user_nicename'       => $user->user_nicename,
        'user_email'          => $expose_email ? $user->user_email : '',
        'user_url'            => $user->user_url,
        'user_registered'     => $user->user_registered,
        'user_activation_key' => $user->user_activation_key,
        'user_status'         => $user->user_status,
        'display_name'        => $user->display_name,

        // Usermeta fields
        'roles'       => $user->roles,
        'first_name'  => get_user_meta($user->ID, 'first_name', true),
        'last_name'   => get_user_meta($user->ID, 'last_name', true),
        'nickname'    => get_user_meta($user->ID, 'nickname', true),
        'description' => get_user_meta($user->ID, 'description', true),
        'admin_color' => get_user_meta($user->ID, 'admin_color', true),
        // 'avatar_url' => get_user_meta($user->ID, 'avatar', true),
    ];


    if ($expose_email && current_user_can('list_users')) {
        $data['email'] = $user->user_email;
    }

    $avatar = get_user_meta($user->ID, '_simple_local_avatar', true);
    if (!empty($avatar['full'])) {
        $data['avatar'] = esc_url($avatar['full']);
    } else {
        $data['avatar'] = get_avatar_url($user->ID);
    }

    return apply_filters('kdi_format_user', $data, $user);
}

function kdi_register_user(WP_REST_Request $request)
{
    $username   = sanitize_user($request['username']);
    $email      = sanitize_email($request['email']);
    $password   = $request['password'];
    $first_name = sanitize_text_field($request['first_name'] ?? '');
    $last_name  = sanitize_text_field($request['last_name'] ?? '');
    $nickname   = sanitize_text_field($request['nickname'] ?? $username);
    $bio        = sanitize_textarea_field($request['description'] ?? '');
    $role       = sanitize_text_field($request['role'] ?? 'subscriber');

    // Restrict roles to safe values
    $allowed_roles = ['subscriber', 'customer'];
    if (!in_array($role, $allowed_roles)) {
        $role = 'subscriber';
    }

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        return new WP_Error('missing_fields', 'Please provide username, email, and password', ['status' => 400]);
    }

    if (!is_email($email)) {
        return new WP_Error('invalid_email', 'Invalid email address', ['status' => 400]);
    }

    if (username_exists($username) || email_exists($email)) {
        return new WP_Error('user_exists', 'Username or email already exists', ['status' => 400]);
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        return $user_id;
    }

    // Update profile
    wp_update_user([
        'ID'           => $user_id,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'nickname'     => $nickname,
        'display_name' => trim("$first_name $last_name") ?: $nickname,
        'description'  => $bio,
    ]);

    // Assign role
    $user = new WP_User($user_id);
    $user->set_role($role);

    // Return response
    return new WP_REST_Response([
        'id'           => $user_id,
        'username'     => $username,
        'email'        => $email,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'nickname'     => $nickname,
        'display_name' => $user->display_name,
        'bio'          => $bio,
        'role'         => $role,
        'message'      => 'Registration successful',
    ], 201);
}

// 🔹 GET /users
function kdi_list_users(WP_REST_Request $request)
{
    $args = [
        'number' => $request->get_param('per_page') ?: 10,
        'paged'  => $request->get_param('page') ?: 1,
    ];

    $user_query = new WP_User_Query($args);
    $users = array_map(function ($u) {
        return kdi_format_user($u, true);
    }, $user_query->get_results());

    return new WP_REST_Response([
        'users'    => $users,
        'total'    => $user_query->get_total(),
        'page'     => (int) $args['paged'],
        'per_page' => (int) $args['number'],
    ], 200);
}


// 🔹 GET /users/{id}
function kdi_get_user(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $user = get_userdata($id);

    if (!$user) {
        return new WP_Error('not_found', 'User not found', ['status' => 404]);
    }

    return new WP_REST_Response(kdi_format_user($user, true), 200);
}

// 🔹 GET /users/username/{username}
function kdi_get_user_by_username(WP_REST_Request $request)
{
    $username = sanitize_user($request['username']);
    $user = get_user_by('login', $username);

    if (!$user) {
        return new WP_Error('not_found', 'User not found', ['status' => 404]);
    }

    return new WP_REST_Response(kdi_format_user($user, true), 200);
}

// 🔹 PUT /users/{id}
function kdi_update_user(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $user = get_userdata($id);

    if (!$user) {
        return new WP_Error('not_found', 'User not found', ['status' => 404]);
    }

    // 🔹 Validate email
    $new_email = sanitize_email($request['user_email'] ?? $user->user_email);
    if ($new_email !== $user->user_email && email_exists($new_email)) {
        return new WP_Error('email_exists', 'Email already in use', ['status' => 400]);
    }

    // 🔹 Prevent role updates unless admin
    if (isset($request['roles']) && !current_user_can('edit_users')) {
        return new WP_Error('role_change_not_allowed', 'Only admins can change roles', ['status' => 403]);
    }

    // 🔹 Prepare update data
    $data = [
        'ID'           => $id,
        'user_email'   => $new_email,
        'first_name'   => sanitize_text_field($request['first_name'] ?? get_user_meta($id, 'first_name', true)),
        'last_name'    => sanitize_text_field($request['last_name'] ?? get_user_meta($id, 'last_name', true)),
        'nickname'     => sanitize_text_field($request['nickname'] ?? $user->nickname),
        'display_name' => sanitize_text_field($request['display_name'] ?? $user->display_name),
        'user_url'     => esc_url_raw($request['user_url'] ?? $user->user_url),
        'description'  => sanitize_textarea_field($request['description'] ?? get_user_meta($id, 'description', true)),
    ];

    // 🔹 Update main user fields
    $result = wp_update_user($data);

    if (is_wp_error($result)) {
        return new WP_Error('update_failed', $result->get_error_message(), ['status' => 400]);
    }

    // 🔹 Extra meta fields
    if (isset($request['admin_color'])) {
        update_user_meta($id, 'admin_color', sanitize_text_field($request['admin_color']));
    }
    if (isset($request['avatar'])) {
        // update_user_meta($id, 'avatar', esc_url_raw($request['avatar']));
        update_user_meta($id, '_simple_local_avatar', [
            'full' => esc_url_raw($request['avatar'])
        ]);
    }

    return new WP_REST_Response(kdi_format_user(get_userdata($id), true), 200);
}


// 🔹 DELETE /users/{id}
function kdi_delete_user(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $user = get_userdata($id);

    if (!$user) {
        return new WP_Error('not_found', 'User not found', ['status' => 404]);
    }

    require_once(ABSPATH . 'wp-admin/includes/user.php');

    // 🔹 Get a real admin to reassign posts
    $admins = get_users(['role' => 'administrator', 'number' => 1]);
    $admin_id = $admins ? $admins[0]->ID : null;

    $deleted_user = kdi_format_user($user, true);
    $result = wp_delete_user($id, $admin_id);

    if (!$result) {
        return new WP_Error('delete_failed', 'Failed to delete user', ['status' => 500]);
    }

    // 🔹 Hook for logging/notifications
    do_action('kdi_user_deleted', $id);

    return new WP_REST_Response([
        'message' => 'User deleted successfully',
        'user'    => $deleted_user,
    ], 200);
}
