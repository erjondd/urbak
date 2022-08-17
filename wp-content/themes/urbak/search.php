<?php
global $wp_query;
$the_keys = preg_split('/\s+/', str_replace('-', ' ', get_query_var('s')), -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
$total_keys = count($the_keys);
$the_query = new WP_Query(array('post_type' => 'nothing'));
if ($total_keys > 1) {
    for ($i = 0; $i <= $total_keys; $i++) {
        $the_query_mask = new WP_Query(array('s' => $the_keys[$i]));
        $the_query->post_count = count($the_query->posts);
        $the_query->posts = array_merge($the_query->posts, $the_query_mask->posts);
    }
} else {
    $the_query = new WP_Query(array('s' => get_query_var('s')));
}

get_header();
$has_links = false;
$has_products = false;
?>
<section class="search-section">
    <div class="container">
        <h3>Links</h3>
        <div class="search-links">
            <div class="search-link">
                <?php
                foreach ($the_query->posts as $post) {
                    if ($post->post_type === "page") {
                        $has_links = true;
                ?>
                        <div class="search-link">
                            <div class="search-found-content">
                                <a href="<?= the_permalink() ?>"><?= $post->post_title ?></a>
                            </div>
                        </div>

                <?php
                    }
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php if (!$has_links) { ?>
            <div class="no-data">No links found</div>
        <?php } ?>
    </div>
</section>

<section class="search-section">
    <div class="container">
        <h3>Products</h3>
        <div class="search-products">
            <?php
            foreach ($the_query->posts as $post) {
                if ($post->post_type === "product") {
                    $has_products = true;
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
            ?>
                    <div class="search-product">
                        <a href="<?= the_permalink() ?>">
                            <div class="search-product-image">
                                <img src="<?= $image[0] ?>" />
                            </div>
                            <div class="search-found-content">
                                <span><?= $post->post_title ?></span>
                                <span><img src="<?= get_template_directory_uri() ?>/images/arrow-right.svg" /></span>
                                 
                            </div>
                        </a>
                    </div>

            <?php
                }
            }
            wp_reset_postdata();
            ?>
        </div>
        <?php if (!$has_products) { ?>
            <div class="no-data">No products found</div>
        <?php } ?>
    </div>
</section>

<?php

get_footer();

?>