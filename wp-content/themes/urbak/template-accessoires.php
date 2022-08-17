<?php

get_header();

/*

Template Name: Accessoires Template

*/

?>

<section class="accessoires">
    <div class="container">
        <div class="phone-and-airpods">
            <div class="phone">
                <img src="<?php echo get_bloginfo('template_url') ?>/images/accessoires-phone.png" />
            </div>
            <div class="airpods">
                <img src="<?php echo get_bloginfo('template_url') ?>/images/accessoires-airpods.png" />
            </div>
        </div>
        <div class="accessoires-center-text">
            Accessoires
        </div>

    </div>
    <div class="scroll-down" id="scroll-down">
        <img src="<?php echo get_bloginfo('template_url') ?>/images/scroll-down.png" />
    </div>
</section>
<section class="accessoires-smartphones-category">
    <div class="container">
        <div class="accesoires-title">
            Accessoires
        </div>
        <div class="reparation-smartphones-category-title">
            Accessoires smartphone
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
                    if ($product_category->parent == 55) {
                        // echo '<h4><a href="' . get_term_link($product_category) . '">' . $product_category->name . '</a></h4>';
                        $cat_thumb_id = get_woocommerce_term_meta($product_category->term_id, 'thumbnail_id', true);
                        $cat_thumb_url = wp_get_attachment_thumb_url($cat_thumb_id);

                        echo "<div class='slide-product'><a href='" . get_home_url()   . "/product-category/" .  $product_category->slug . "'><img src=" . $cat_thumb_url . "></a></div>";
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
<section class="accessoires-smartphones-category">
    <div class="container">

        <div class="reparation-smartphones-category-title">
            Accessoires PC
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
                    if ($product_category->parent == 56) {
                        // echo '<h4><a href="' . get_term_link($product_category) . '">' . $product_category->name . '</a></h4>';
                        $cat_thumb_id = get_woocommerce_term_meta($product_category->term_id, 'thumbnail_id', true);
                        $cat_thumb_url = wp_get_attachment_thumb_url($cat_thumb_id);

                        echo "<div class='slide-product'><a href='" . get_home_url()   . "/product-category/" .  $product_category->slug . "'><img src=" . $cat_thumb_url . "></a></div>";
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
<section class="accessoires-smartphones-category">
    <div class="container">

        <div class="reparation-smartphones-category-title">
            Accessoires Macbook
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
                    if ($product_category->parent == 57) {
                        // echo '<h4><a href="' . get_term_link($product_category) . '">' . $product_category->name . '</a></h4>';
                        $cat_thumb_id = get_woocommerce_term_meta($product_category->term_id, 'thumbnail_id', true);
                        $cat_thumb_url = wp_get_attachment_thumb_url($cat_thumb_id);

                        echo "<div class='slide-product'><a href='" . get_home_url()   . "/product-category/" .  $product_category->slug . "'><img src=" . $cat_thumb_url . "></a></div>";
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