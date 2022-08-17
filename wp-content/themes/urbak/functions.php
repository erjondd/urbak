<?php

add_action('wp_enqueue_scripts', 'urbak_scripts');
function urbak_scripts()
{
    wp_enqueue_style('urbak-style', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_script('urbak-js', get_template_directory_uri() . '/js/bundle.min.js', '', '', true);
}


/*enable svg upload*/
function add_svg_to_upload_mimes($upload_mimes)
{
    $upload_mimes['svg'] = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';
    return $upload_mimes;
}

add_filter('upload_mimes', 'add_svg_to_upload_mimes', 10, 1);


//Menu

register_nav_menus(array(
    'primary' => __('Primary Menu'),
    'footer' => __('Footer Menu'),
));


//Add options page
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page();

}

add_filter( 'body_class', 'wc_product_cats_css_body_class' );
  
function wc_product_cats_css_body_class( $classes ){
  if ( is_singular( 'product' ) ) {
    $current_product = wc_get_product();
    $custom_terms = get_the_terms( $current_product->get_id(), 'product_cat' );
    if ( $custom_terms ) {
      foreach ( $custom_terms as $custom_term ) {
        $classes[] = 'product_cat_' . $custom_term->slug;
      }
    }
  }
  return $classes;
}

function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );
