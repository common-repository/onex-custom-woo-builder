<?php
/**
 * Images template
 */
echo '<div class="custom-single-images__wrap">';
	printf( '<div class="custom-single-images__loading">%s</div>', __( 'Loading...', 'custom-woo-builder' ) );
	woocommerce_show_product_images();
echo '</div>';
