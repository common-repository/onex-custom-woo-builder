<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_Template_Functions' ) ) {

	class Custom_Woo_Builder_Template_Functions {

		private static $instance = null;

		public function get_product_sale_flash( $badge_text = '' ) {
			global $product;

			if ( $product->is_on_sale() ) {
				return sprintf( '<div class="custom-woo-product-badge custom-woo-product-badge__sale">%s</div>', $badge_text );
			}

		}

		public function get_product_stock_status() {
			global $product;

			return wc_get_stock_html( $product );

		}

		public function get_product_thumbnail( $image_size = 'thumbnail_size', $use_thumb_effect = false, $attr = '' ) {
			global $product;

			$thumbnail_id        = get_post_thumbnail_id( $product->get_id() );
			$enable_thumb_effect = filter_var( custom_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN );
			$placeholder_src     = Elementor\Utils::get_placeholder_image_src();

			if ( empty( $thumbnail_id ) ) {
				return sprintf( '<img src="%s" alt="">', $placeholder_src );
			}

			$html = wp_get_attachment_image( $thumbnail_id, $image_size, false, $attr );

			if ( $use_thumb_effect && $enable_thumb_effect ) {
				$html = $this->add_thumb_effect( $html, $product, $image_size, $attr );
			}

			return apply_filters( 'custom-woo-builder/template-functions/product-thumbnail', $html );
		}

		public function add_thumb_effect( $html, $product, $image_size, $attr ) {
			$thumb_effect   = custom_woo_builder_settings()->get( 'product_thumb_effect' );
			$attachment_ids = $product->get_gallery_image_ids();

			if ( empty( $attachment_ids[0] ) ) {
				return $html;
			}

			if ( empty( $thumb_effect ) ) {
				$thumb_effect = 'slide-left';
			}

			$effect         = $thumb_effect;
			$additional_id  = $attachment_ids[0];
			$additional_img = wp_get_attachment_image( $additional_id, $image_size, false, $attr );

			$html = sprintf(
				'<div class="custom-woo-product-thumbs effect-%3$s"><div class="custom-woo-product-thumbs__inner">%1$s%2$s</div></div>',
				$html, $additional_img, $effect
			);

			return $html;
		}

		public function get_category_thumbnail( $category_id, $image_size = 'thumbnail_size' ) {
			$thumbnail_id    = get_term_meta( $category_id, 'thumbnail_id', true );
			$placeholder_src = Elementor\Utils::get_placeholder_image_src();

			if ( empty( $thumbnail_id ) ) {
				return sprintf( '<img src="%s" alt="">', $placeholder_src );
			}

			$html = wp_get_attachment_image( $thumbnail_id, $image_size, false );

			return apply_filters( 'custom-woo-builder/template-functions/category-thumbnail', $html );
		}

		public function get_product_sku() {
			global $product;

			if ( ! $product ) {
				return '';
			}

			if ( $product->get_sku() ) {
				$sku = sprintf(
					'<span class="sku">%s</span>',
					$product->get_sku()
				);

				return apply_filters( 'custom-woo-builder/template-functions/sku', $sku );

			}

		}

		public function get_product_title() {
			global $product;

			return get_the_title( $product->get_id() );
		}

		public function get_product_title_link() {
			global $product;

			return esc_url( get_permalink() );
		}

		public function get_product_rating() {
			global $product;

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				return;
			}

			$format = apply_filters(
				'custom-woo-builder/template-functions/product-rating',
				'<span class="product-rating__stars">%s</span>'
			);

			$rating = $product->get_average_rating();
			$count  = 0;
			$html   = 0 < $rating ? sprintf( $format, wc_get_star_rating_html( $rating, $count ) ) : '';

			return $html;

		}

		public function get_product_custom_rating( $icon = 'fa fa-star' ) {
			global $product;

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				return false;
			}

			$rating = $product->get_average_rating();

			if ( $rating > 0 ){
				$html   = '<span class="product-rating__content">';

				for ( $i = 1; $i <= 5; $i ++ ) {
					$is_active_class = ( $i <= $rating ) ? 'active' : '';
					$html            .= sprintf( '<span class="product-rating__icon %s %s"></span>', $icon, $is_active_class );
				}

				$html .= '</span>';

				return $html;
			} else {
				return false;
			}

		}

		public function get_product_price() {
			global $product;

			$price_html = $product->get_price_html();

			return apply_filters( 'custom-woo-builder/template-functions/product-price', $price_html );
		}

		public function get_product_excerpt() {
			global $product;

			if ( ! $product->get_short_description() ) {
				return;
			}

			return apply_filters( 'custom-woo-builder/template-functions/product-excerpt', get_the_excerpt( $product->get_id() ) );
		}

		public function get_product_add_to_cart_button( $classes = array() ) {
			global $product;

			$args = array();
			$ajax_add_to_cart_enabled = 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? true : false;

			if ( $product ) {
				$defaults = apply_filters(
					'custom-woo-builder/template-functions/product-add-to-cart-settings',
					array(
						'quantity'   => 1,
						'class'      => implode( ' ', array_filter( array(
							'button',
							$classes,
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() && $ajax_add_to_cart_enabled ? 'ajax_add_to_cart' : '',
						) ) ),
						'attributes' => array(
							'data-product_id'  => $product->get_id(),
							'data-product_sku' => $product->get_sku(),
							'aria-label'       => $product->add_to_cart_description(),
							'rel'              => 'nofollow',
						),
					)
				);

				$args = wp_parse_args( $args, $defaults );

				wc_get_template( 'loop/add-to-cart.php', $args );
			}
		}

		public function get_product_categories_list() {
			global $product;

			$separator = '<span class="separator">&#44;&nbsp;</span></li><li>';
			$before    = '<ul><li>';
			$after     = '</li></ul>';

			return get_the_term_list( $product->get_id(), 'product_cat', $before, $separator, $after );
		}

		public function get_product_tags_list() {
			global $product;

			$separator = '<span class="separator">&#44;&nbsp;</span></li><li>';
			$before    = '<ul><li>';
			$after     = '</li></ul>';

			return get_the_term_list( $product->get_id(), 'product_tag', $before, $separator, $after );
		}

		public static function get_instance( $shortcodes = array() ) {

			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}

			return self::$instance;
		}

	}

}

function custom_woo_builder_template_functions() {
	return Custom_Woo_Builder_Template_Functions::get_instance();
}