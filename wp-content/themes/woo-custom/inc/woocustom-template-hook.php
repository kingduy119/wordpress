<?php

add_action('woocustom_sidebar', 'woocustom_get_sidebar', 5);

/**
 * Header
 */
add_action('woocustom_header', 'woocustom_head', 2);
add_action('woocustom_header', 'woocustom_header_body_open', 4);


add_action('woocustom_breadcrumbs', 'woocustom_breadcrumbs', 35);