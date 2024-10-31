<?php
/**
 * Loop item tags
 */

$settings = $this->get_settings();

if( isset( $settings['show_compare'] ) ){
	if ( 'yes' === $settings['show_compare'] ) {
		do_action( 'custom-woo-builder/templates/custom-woo-products/compare-button', $settings );
	}
}
