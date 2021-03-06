<?php

get_header();

/*

Template Name: Smartphone Categories Template

*/

?>
<section class="reparation-smartphones-category">
    <div class="container">
        <?php


        $args = array(
            'number'     => $number,
            'orderby'    => 'title',
            'order'      => 'ASC',
            'hide_empty' => $hide_empty,
            'include'    => $ids
        );
        $product_categories = get_terms('product_cat', $args);
        $count = count($product_categories);
        if ($count > 0) {
            foreach ($product_categories as $product_category) {
                if ($product_category->parent == 25) {
                    echo '<h4><a href="' . get_term_link($product_category) . '">' . $product_category->name . '</a></h4>';
                    $cat_thumb_id = get_woocommerce_term_meta($product_category->term_id, 'thumbnail_id', true);
                    $cat_thumb_url = wp_get_attachment_thumb_url($cat_thumb_id);

                    echo "<img src=" . $cat_thumb_url . ">";
                }


                // $args = array(
                //     'posts_per_page' => -1,
                //     'tax_query' => array(
                //         'relation' => 'AND',
                //         array(
                //             'taxonomy' => 'product_cat',
                //             'field' => 'slug',
                //             // 'terms' => 'white-wines'
                //             'terms' => $product_category->slug
                //         )
                //     ),
                //     'post_type' => 'product',
                //     'orderby' => 'title,'
                // );
                // $products = new WP_Query( $args );
                // echo "<ul>";
                // while ( $products->have_posts() ) {
                //     $products->the_post();
                //     
        ?>

        <?php
                // }
                // echo "</ul>";
            }
        }

        ?>
    </div>
</section>


<?php
get_footer();
?>