<?php
/**
 * Tabs template
 */
echo '<div class="custom-single-tabs__wrap">';
	printf( '<div class="custom-single-tabs__loading">%s</div>', __( 'Loading...', 'custom-woo-builder' ) );
	woocommerce_output_product_data_tabs();
echo '</div>';
