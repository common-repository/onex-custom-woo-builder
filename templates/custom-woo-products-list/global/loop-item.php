<?php
/**
 * Products list loop item template
 */

global $product;

$product = wc_get_product();
$product_id = $product->get_id();

?>
<li class="custom-woo-products-list__item custom-woo-builder-product" data-product-id="<?php echo $product_id ?>">
	<div class="custom-woo-products-list__inner-box">
		<div class="custom-woo-products-list__item-img"><?php  include $this->get_template( 'item-thumb' ); ?></div>
	 <div class="custom-woo-products-list__item-content"><?php
	   include $this->get_template( 'item-categories' );
	   include $this->get_template( 'item-sku' );
	   include $this->get_template( 'item-title' );
	   include $this->get_template( 'item-price' );
	   include $this->get_template( 'item-button' );
	   include $this->get_template( 'item-rating' );
	   include $this->get_template( 'item-compare' );
	   include $this->get_template( 'item-wishlist' );
	   include $this->get_template( 'item-quick-view' );
	   ?></div>
	</div>
</li>