<?php
/**
 * Products loop item layout 4
 */
?>
<div class="custom-woo-products__thumb-wrap"><?php include $this->get_template( 'item-thumb' ); ?>
    <div class="custom-woo-products-cqw-wrapper cqw-horizontal-view ">
        <?php include $this->get_template( 'item-compare' ); ?>
        <?php include $this->get_template( 'item-wishlist' ); ?>
        <?php include $this->get_template( 'item-quick-view' ); ?>
    </div>
</div>
<div class="custom-woo-products__item-content"><?php
	include $this->get_template( 'item-categories' );
	include $this->get_template( 'item-sku' );
	include $this->get_template( 'item-title' );
	include $this->get_template( 'item-price' );
	?>
	<div class="hovered-content"><?php
	  include $this->get_template( 'item-content' );
	  include $this->get_template( 'item-button' );
	  include $this->get_template( 'item-rating' );
	  include $this->get_template( 'item-tags' );
	  ?></div>
</div>