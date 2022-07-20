<?php

get_header();


 $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 9999,
        "product_cat"=> "hoodies"
    );

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post();
        global $product;
        echo $product;
        echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
    endwhile;

get_footer();
