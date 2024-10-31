<?php
/**
 * Loop item thumbnail
 */

$size       = $this->get_attr( 'thumb_size' );
$badge_text = custom_woo_builder()->macros->do_macros( $this->get_attr( 'sale_badge_text' ) );
$thumbnail  = custom_woo_builder_template_functions()->get_product_thumbnail( $size, true );
$sale_badge = custom_woo_builder_template_functions()->get_product_sale_flash( $badge_text );

if ( null === $thumbnail ) {
	return;
}
?>
<div class="custom-woo-product-thumbnail">
	<a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark"><?php echo $thumbnail; ?></a>
	<div class="custom-woo-product-img-overlay"></div><?php
		if ( null != $sale_badge && 'yes' === $this->get_attr( 'show_badges' ) ) {
			echo sprintf( '<div class="custom-woo-product-badges">%s</div>', $sale_badge );
		}
	?></div>