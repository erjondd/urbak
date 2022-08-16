<?php

get_header();

/*

Template Name: Acheter Template

*/

?>

<section class="acheter">
    <div class="container">
        <div class="lights-and-tech">

            <div class="apple-watch"><img src="<?php echo get_bloginfo('template_url') ?>/images/apple-watch.svg" /></div>
            <div class="iphone"><img src="<?php echo get_bloginfo('template_url') ?>/images/iphone.svg" /></div>
        </div>
        <div class="acheter-center-text">Acheter</div>

    </div>
    <div class="scroll-down" id="scroll-down"><img src="<?php echo get_bloginfo('template_url') ?>/images/scroll-down.png" /></div>
</section>


<section class="acheter-smartphones-category">
    <div class="container">
        <div class="reparation-smartphones-category-title">
            Category
        </div>
        <div class="r-s-c-content">
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
                    if ($product_category->parent == 46) {
                        // echo '<h4><a href="' . get_term_link($product_category) . '">' . $product_category->name . '</a></h4>';
                        $cat_thumb_id = get_woocommerce_term_meta($product_category->term_id, 'thumbnail_id', true);
                        $cat_thumb_url = wp_get_attachment_thumb_url($cat_thumb_id);


                        echo "<a class='category-smartphone' href='" . get_home_url()   . "/product-category/" .  $product_category->slug . "'><img src=" . $cat_thumb_url . "></a>";
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
    </div>
</section>
<?php
get_footer();
?>