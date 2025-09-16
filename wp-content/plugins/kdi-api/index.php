<?php

/**
 * Plugin Name: KDI Custom API
 * Description: REST API endpoints for WooCommerce products and categories
 * Version: 1.0
 * Author: Duy Hoang
 */
define('API_URL', 'kdi/v1');

require_once plugin_dir_path(__FILE__) . 'user.php';

// post
require_once plugin_dir_path(__FILE__) . 'post-create.php';
require_once plugin_dir_path(__FILE__) . 'post-get.php';
require_once plugin_dir_path(__FILE__) . 'post-update.php';
require_once plugin_dir_path(__FILE__) . 'post-delete.php';

// page
require_once plugin_dir_path(__FILE__) . 'page-get.php';

// taxonomies
require_once plugin_dir_path(__FILE__) . 'post-category.php';
require_once plugin_dir_path(__FILE__) . 'post-tag.php';

require_once plugin_dir_path(__FILE__) . 'product.php';
require_once plugin_dir_path(__FILE__) . 'product-category.php';

// Allow application passwords to get api access
// if (function_exists('wp_is_application_passwords_available')) {
//     add_filter('wp_is_application_passwords_available', '__return_true');
// }


if (! defined('JWT_AUTH_SECRET_KEY')) {
    define('JWT_AUTH_SECRET_KEY', 'my_custom_secret_key_123456789');
    define('JWT_AUTH_CORS_ENABLE', true);
}

// Avatar
add_filter('get_avatar', 'kdi_local_avatar_filter', 10, 6);
function kdi_local_avatar_filter($avatar, $id_or_email, $size, $default, $alt, $args)
{
    $user = false;

    if (is_numeric($id_or_email)) {
        $user = get_user_by('id', absint($id_or_email));
    } elseif (is_object($id_or_email) && !empty($id_or_email->user_id)) {
        $user = get_user_by('id', (int) $id_or_email->user_id);
    }

    if ($user) {
        $local_avatar = get_user_meta($user->ID, '_kdi_local_avatar', true);
        if (!empty($local_avatar) && !empty($local_avatar['full'])) {
            $avatar = '<img alt="' . esc_attr($alt) . '" src="' . esc_url($local_avatar['full']) . '" class="avatar avatar-' . esc_attr($size) . ' photo" height="' . esc_attr($size) . '" width="' . esc_attr($size) . '" />';
        }
    }

    return $avatar;
}

add_action('show_user_profile', 'kdi_local_avatar_profile_field');
add_action('edit_user_profile', 'kdi_local_avatar_profile_field');

function kdi_local_avatar_profile_field($user)
{
    $local_avatar = get_user_meta($user->ID, '_kdi_local_avatar', true);
?>
    <h3>Local Avatar</h3>
    <table class="form-table">
        <tr>
            <th><label for="kdi-local-avatar">Upload Avatar</label></th>
            <td>
                <?php if (!empty($local_avatar['full'])): ?>
                    <img src="<?php echo esc_url($local_avatar['full']); ?>"
                        alt="avatar" width="96" style="border-radius:50%;"><br>
                <?php endif; ?>
                <input type="file" name="kdi-local-avatar" id="kdi-local-avatar" /><br>
                <span class="description">Upload an image for your avatar.</span>
            </td>
        </tr>
    </table>
<?php
}
