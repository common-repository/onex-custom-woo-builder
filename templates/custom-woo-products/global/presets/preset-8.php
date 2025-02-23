<?php
/**
 * Products loop item layout 8
 */
?>
<div class="custom-woo-products__thumb-wrap">
	<?php include $this->get_template( 'item-thumb' ); ?>
	<div class="hovered-content"><?php
        include $this->get_template( 'item-button' );
		 include $this->get_template( 'item-compare' );
		 include $this->get_template( 'item-wishlist' ); ?>
    </div>
</div><?php
	include $this->get_template( 'item-categories' );
	include $this->get_template( 'item-title' );
	include $this->get_template( 'item-sku' );
	include $this->get_template( 'item-price' );
	include $this->get_template( 'item-content' );
	include $this->get_template( 'item-rating' );
	include $this->get_template( 'item-tags' );
?>