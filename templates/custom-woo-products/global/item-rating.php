<?php
/**
 * Loop item price
 */

$rating = custom_woo_builder_template_functions()->get_product_rating();

if ( 'yes' !== $this->get_attr( 'show_rating' ) || '' === $rating ) {
	return;
}
?>

<div class="custom-woo-product-rating"><?php echo $rating; ?></div>