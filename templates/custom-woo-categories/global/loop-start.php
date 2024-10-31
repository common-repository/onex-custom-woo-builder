<?php
/**
 * Categories loop start template
 */

$classes = array(
	'custom-woo-categories',
	'custom-woo-categories--' . $this->get_attr( 'presets' ),
	'col-row',
	custom_woo_builder_tools()->gap_classes( $this->get_attr( 'columns_gap' ), $this->get_attr( 'rows_gap' ) ),
);

$equal = $this->get_attr( 'equal_height_cols' );

if ( $equal ) {
	$classes[] = 'custom-equal-cols';
}
?>

<div class="<?php echo implode( ' ', $classes ); ?>">