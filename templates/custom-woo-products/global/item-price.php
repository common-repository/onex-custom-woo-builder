<?php
/**
 * Loop item price
 */

$price = custom_woo_builder_template_functions()->get_product_price();

if ( 'yes' !== $this->get_attr( 'show_price' ) || '' === $price ) {
	return;
}
?>

<div class="custom-woo-product-price"><?php echo $price; ?></div>