<?php
class Bootstrap_NavWalker extends Walker_Nav_Menu {
  public function start_lvl( &$output, $depth = 0, $args = null ) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
  }

  public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes[] = 'nav-item';
    if (in_array('menu-item-has-children', $classes)) {
      $classes[] = 'dropdown';
    }

    $class_names = join(' ', array_filter($classes));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $output .= "<li{$class_names}>";

    $attributes = 'class="nav-link"';
    $item_output = '';

    if (in_array('menu-item-has-children', $classes)) {
      $attributes = 'class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"';
    }

    $item_output .= '<a ' . $attributes . ' href="' . esc_attr($item->url) . '">';
    $item_output .= apply_filters('the_title', $item->title, $item->ID);
    $item_output .= '</a>';

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}
