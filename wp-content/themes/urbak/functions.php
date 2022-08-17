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
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page();

}

function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


