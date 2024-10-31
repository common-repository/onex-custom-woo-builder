<?php
/**
 * Loop item content
 */

$excerpt = custom_woo_builder_template_functions()->get_product_excerpt();
$excerpt = custom_woo_builder_tools()->trim_text(
	$excerpt,
	$this->get_attr( 'excerpt_length' ),
	$this->get_attr( 'excerpt_trim_type' ),
	'...'
);

if ( 'yes' !== $this->get_attr( 'show_excerpt' ) || null === $excerpt ) {
	return;
}
?>

<div class="custom-woo-product-excerpt"><?php echo $excerpt; ?></div>