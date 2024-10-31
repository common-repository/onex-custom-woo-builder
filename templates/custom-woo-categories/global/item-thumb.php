<?php
/**
 * Loop item thumbnail
 */

$size      = $this->get_attr( 'thumb_size' );
$thumbnail = custom_woo_builder_template_functions()->get_category_thumbnail( $category->term_id, $this->get_attr( 'thumb_size' ) );

if ( null === $thumbnail ) {
	return;
}
?>
<div class="custom-woo-category-thumbnail">
	<a href="<?php echo esc_url( get_category_link( $category->term_id ) ) ?>" rel="bookmark"><?php echo $thumbnail; ?></a>
	<div class="custom-woo-category-img-overlay"></div>
	<div class="custom-woo-category-img-overlay__hover"></div>
</div>