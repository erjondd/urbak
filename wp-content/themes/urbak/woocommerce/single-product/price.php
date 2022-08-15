<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p></div>
<?php
$description_infos = get_field("description_infos");
$count = 0;
if (have_rows('description_infos')) {
	while (have_rows('description_infos')) : the_row();
		$title = get_sub_field('title');
		$text = get_sub_field('text');
		
?>
		<div class="info-desc info_desc_<?=  $count  ?>" >
			<div class="title-desc"	tab-id="<?=  $count  ?>"><?= $title; ?></div>
			<div class="text-desc"><?= $text; ?></div>
		</div>
<?php 
$count++ ;
	endwhile;
}
?>
