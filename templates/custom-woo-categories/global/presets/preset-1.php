<?php
/**
 * Categories loop item layout 1
 */

?>

<div class="custom-woo-categories-thumbnail__wrap"><?php include $this->get_template( 'item-thumb' );?>
	<div class="custom-woo-category-count__wrap"><?php include $this->get_template( 'item-count' ); ?></div>
</div>
<div class="custom-woo-categories-content"><?php
	include $this->get_template( 'item-title' );
	include $this->get_template( 'item-description' );
	?></div>