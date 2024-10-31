<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_Macros' ) ) {

	class Custom_Woo_Builder_Macros {

		public function get_all() {
			return apply_filters( 'custom-woo-builder/macros/macros-list', array(
				'percentage_sale' => array( $this, 'get_percentage_sale' ),
				'numeric_sale'    => array( $this, 'get_numeric_sale' ),
			) );
		}

		public function verbose_macros_list() {

			$macros = $this->get_all();
			$result = '';
			$sep    = '';

			foreach ( $macros as $key => $data ) {
				$result .= $sep . '%' . $key . '%';
				$sep    = ', ';
			}

			return $result;

		}

		public function get_percentage_sale( $field_value, $precision = 0 ) {
			global $product;
			$percentage_sale = '';

			if ( 'variable' === $product->get_type() ) {

				$prices = $this->sort_variation_price( $product );

				if ( $prices['regular_min'] > 0 && $prices['regular_max'] > 0 ) {
					$percentage_min  = round( 100 - ( $prices['sale_min'] / $prices['regular_min'] * 100 ), $precision );
					$percentage_max  = round( 100 - ( $prices['sale_max'] / $prices['regular_max'] * 100 ), $precision );
					$percentage_sale = ( ( $percentage_min != $percentage_max ) ? ( $percentage_min . '&#37;' . '-' . $percentage_max . '&#37;' ) : $percentage_max . '&#37;' );
				}

			} else {

				$regular_price = (float) $product->get_regular_price();
				$sale_price    = (float) $product->get_price();

				if ( $sale_price > 0 && $regular_price > 0 ) {
					$percentage_sale = round( 100 - ( $sale_price / $regular_price * 100 ), $precision ) . '&#37;';
				}

			}

			return 0 < $percentage_sale ? $percentage_sale : '';

		}

		public function get_numeric_sale() {

			global $product;
			$numeric_sale = '';

			if ( 'variable' === $product->get_type() ) {
				$prices = $this->sort_variation_price( $product );

				if ( $prices['regular_min'] > 0 && $prices['regular_max'] > 0 ) {
					$numeric_min  = wc_price( $prices['regular_min'] - $prices['sale_min'] );
					$numeric_max  = wc_price( $prices['regular_max'] - $prices['sale_max'] );
					$numeric_sale = ( ( $numeric_min != $numeric_max ) ? ( $numeric_min . '-' . $numeric_max ) : $numeric_max );
				}

			} else {
				$regular_price = (float) $product->get_regular_price();
				$sale_price    = (float) $product->get_price();

				$numeric_sale = wc_price( $regular_price - $sale_price );
			}

			return $numeric_sale;

		}

		public function sort_variation_price( $product ) {

			$prices = $product->get_variation_prices( true );

			foreach ( $prices['price'] as $product_id => $price ) {
				$product_obj                    = wc_get_product( $product_id );
				$prices['price'][ $product_id ] = wc_get_price_to_display( $product_obj );
			}

			asort( $prices['price'] );
			asort( $prices['regular_price'] );

			return array(
				'sale_min'    => current( $prices['price'] ),
				'sale_max'    => end( $prices['price'] ),
				'regular_min' => current( $prices['regular_price'] ),
				'regular_max' => end( $prices['regular_price'] ),
			);

		}

		public function do_macros( $string, $field_value = null ) {

			$macros = $this->get_all();

			return preg_replace_callback(
				'/%([a-z_-]+)(\|[a-z0-9_-]+)?%/',
				function ( $matches ) use ( $macros, $field_value ) {

					$found = $matches[1];

					if ( ! isset( $macros[ $found ] ) ) {
						return $matches[0];
					}

					$cb = $macros[ $found ];

					if ( ! is_callable( $cb ) ) {
						return $matches[0];
					}

					$args = isset( $matches[2] ) ? ltrim( $matches[2], '|' ) : false;

					return call_user_func( $cb, $field_value, $args );

				}, $string
			);

		}

	}

}