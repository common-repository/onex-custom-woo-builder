<?php
/**
 * Archive category item template
 */
?>
<li <?php wc_product_cat_class( '', $category ); ?>><?php
	$template = custom_woo_builder_integration_woocommerce()->get_current_archive_category_template();
	echo custom_woo_builder()->parser->get_template_content( $template );
?></li>