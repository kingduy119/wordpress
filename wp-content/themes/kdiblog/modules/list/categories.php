<?php
    $categories = get_categories( array(
        'orderby'   => 'name',
        'order'     => 'ASC',
        'parent'    => '0',
    ) );

    echo '<ul id="list-categories">';
    foreach( $categories as $cat ) {
        $slug = get_term_link( $cat->slug, 'category' );
        $class = 'text-decoration-none';
        echo sprintf('<li><a class="%s" href="%s">%s</a></li>', $class, $slug, $cat->name );
    }
    echo '</ul>';
?>