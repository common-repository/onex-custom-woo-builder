<?php
/**
 * Loop item description
 */

$description = custom_woo_builder_tools()->trim_text( $category->description, $this->get_attr( 'desc_length' ), 'word', $this->get_attr( 'desc_after_text' ) );

if ( '' === $description ) {
	return;
}
?>

<div class="custom-woo-category-excerpt"><?php echo $description; ?></div>