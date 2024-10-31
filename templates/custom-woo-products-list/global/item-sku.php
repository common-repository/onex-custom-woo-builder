<?php
/**
 * Loop item title
 */

$sku = custom_woo_builder_template_functions()->get_product_sku();

if ( 'yes' !== $this->get_attr( 'show_sku' ) || '' === $sku ) {
	return;
}
?>

<div class="custom-woo-product-sku"><?php echo $sku; ?></div>