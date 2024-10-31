<?php
/**
 * Categories loop item layout 5
 */

?>

<div class="custom-woo-categories-thumbnail__wrap"><?php include $this->get_template( 'item-thumb' ); ?></div>
<div class="custom-woo-categories-content">
	<div class="custom-woo-category-content__inner">
	  <?php
	  include $this->get_template( 'item-title' );
	  include $this->get_template( 'item-description' );
	  ?>
	</div>
	<div class="custom-woo-category-count__wrap"><?php include $this->get_template( 'item-count' ); ?></div>
</div>