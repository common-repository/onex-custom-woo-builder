<?php
/**
 * Loop item tags
 */

$settings = $this->get_settings();

if( isset( $settings['show_quickview'] ) ){
	if ( 'yes' === $settings['show_quickview'] ) {
		do_action( 'custom-woo-builder/templates/custom-woo-products-list/quickview-button', $settings );
	}
}
