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

add_filter('woocommerce_checkout_fields', 'njengah_override_checkout_fields');

function njengah_override_checkout_fields($fields)

{
  //  $fields['billing']['billing_company']['placeholder'] = 'Business Name';
  //  $fields['billing']['billing_company']['label'] = 'Business Name';

  $fields['billing']['billing_first_name']['placeholder'] = 'Nom';
  $fields['billing']['billing_city']['placeholder'] = 'Ville';
  $fields['billing']['billing_address_1']['placeholder'] = 'Addresse';

  $fields['shipping']['shipping_first_name']['placeholder'] = 'Nom';

  $fields['shipping']['shipping_last_name']['placeholder'] = 'Prénom';

  $fields['shipping']['shipping_company']['placeholder'] = 'Company Name';

  $fields['billing']['billing_last_name']['placeholder'] = 'Prénom';

  $fields['billing']['billing_email']['placeholder'] = 'E-mail ';

  $fields['billing']['billing_phone']['placeholder'] = 'Téléphone ';



  return $fields;
}


//Menu

register_nav_menus(array(
  'primary' => __('Primary Menu'),
  'footer' => __('Footer Menu'),
));


//Add options page
if (function_exists('acf_add_options_page')) {

  acf_add_options_page();
}

add_filter('body_class', 'wc_product_cats_css_body_class');

function wc_product_cats_css_body_class($classes)
{
  if (is_singular('product')) {
    $current_product = wc_get_product();
    $custom_terms = get_the_terms($current_product->get_id(), 'product_cat');
    if ($custom_terms) {
      foreach ($custom_terms as $custom_term) {
        $classes[] = 'product_cat_' . $custom_term->slug;
      }
    }
  }
  return $classes;
}

add_filter('body_class', 'product_category_slug_to_body_class', 99, 1);
function product_category_slug_to_body_class($classes)
{
  $parent_id = get_queried_object()->parent;
  $parent_term = get_term_by('id', $parent_id, 'product_cat', 'ARRAY_A');
  $parent_name = $parent_term["slug"];
  if (is_product_category()) {
    $classes[] = "category_parent_" . $parent_name;
  }
  return $classes;
}

function mytheme_add_woocommerce_support()
{
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

add_action('wp_head', 'custom_ajax_spinner', 1000);
function custom_ajax_spinner()
{
?>
  <style>
    .woocommerce .blockUI.blockOverlay:before,
    .woocommerce .loader:before {
      height: 3em;
      width: 3em;
      position: absolute;
      top: 50%;
      left: 50%;
      margin-left: -.5em;
      margin-top: -.5em;
      display: block;
      content: "";
      -webkit-animation: none;
      -moz-animation: none;
      animation: none;
      background-image: url('<?php echo get_stylesheet_directory_uri() . "/images/circle_loading.gif"; ?>') !important;
      background-position: center center;
      background-size: cover;
      line-height: 1;
      text-align: center;
      font-size: 2em;
    }
  </style>
<?php
}
