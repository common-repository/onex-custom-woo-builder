<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_DB_Upgrader' ) ) {

	class Custom_Woo_Builder_DB_Upgrader {

		private static $instance = null;

		public $key = null;
		public $shop_key = null;

		public function init() {
			$this->key      = custom_woo_builder_settings()->key;
			$this->shop_key = custom_woo_builder_shop_settings()->options_key;
			$this->init_upgrader();
		}

		public function init_upgrader() {
			new CX_Db_Updater( array(
					'slug'      => 'custom-woo-builder',
					'version'   => '1.3.0',
					'callbacks' => array(
						'1.2.0' => array(
							array( $this, 'update_db_1_2_0' ),
						),
						'1.3.0' => array(
							array( $this, 'update_db_1_3_0' ),
						),
					),
				)
			);
		}

		public function update_db_1_2_0() {
			$current_version_settings      = get_option( $this->key, false );
			$current_version_settings_shop = get_option( $this->shop_key, false );

			if ( $current_version_settings_shop ) {
				if ( ! isset( $current_version_settings_shop['custom_archive_page'] ) ) {
					$current_version_settings_shop['custom_archive_page'] = 'no';
				}
				if ( ! isset( $current_version_settings_shop['archive_template'] ) ) {
					$current_version_settings_shop['archive_template'] = 'default';
				}
				if ( ! isset( $current_version_settings_shop['shortcode_template'] ) ) {
					$current_version_settings_shop['shortcode_template'] = 'default';
				}
				if ( ! isset( $current_version_settings_shop['search_template'] ) ) {
					$current_version_settings_shop['search_template'] = 'default';
				}
				if ( ! isset( $current_version_settings_shop['cross_sells_template'] ) ) {
					$current_version_settings_shop['cross_sells_template'] = 'default';
				}
				if ( ! isset( $current_version_settings_shop['related_template'] ) ) {
					$current_version_settings_shop['related_template'] = 'default';
				}
				if ( ! isset( $current_version_settings_shop['related_products_per_page'] ) ) {
					$current_version_settings_shop['related_products_per_page'] = 3;
				}
				if ( ! isset( $current_version_settings_shop['up_sells_products_per_page'] ) ) {
					$current_version_settings_shop['up_sells_products_per_page'] = 3;
				}
				if ( ! isset( $current_version_settings_shop['cross_sells_products_per_page'] ) ) {
					$current_version_settings_shop['cross_sells_products_per_page'] = 3;
				}
				update_option( $this->shop_key, $current_version_settings_shop );
			}

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['archive_product_available_widgets'] ) ) {
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-add-to-cart'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-add-to-cart'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-cats'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-cats'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-excerpt'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-excerpt'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-price'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-price'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-rating'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-rating'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-thumbnail'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-thumbnail'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-title'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-product-title'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-sale-badge'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-sale-badge'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-stock-status'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-stock-status'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-tags'] ) ) {
						$current_version_settings['archive_product_available_widgets']['custom-woo-builder-archive-tags'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}
			}
		}

		public function update_db_1_3_0() {

			$current_version_settings      = get_option( $this->key, false );
			$current_version_settings_shop = get_option( $this->shop_key, false );

			if ( $current_version_settings_shop ) {
				if ( ! isset( $current_version_settings_shop['custom_shop_page'] ) ) {
					$current_version_settings_shop['custom_shop_page'] = 'no';
				}
				if ( ! isset( $current_version_settings_shop['shop_template'] ) ) {
					$current_version_settings_shop['shop_template'] = 'default';
				}
				if ( ! isset( $current_version_settings_shop['custom_archive_category_page'] ) ) {
					$current_version_settings_shop['custom_archive_category_page'] = 'no';
				}
				if ( ! isset( $current_version_settings_shop['category_template'] ) ) {
					$current_version_settings_shop['category_template'] = 'default';
				}
				update_option( $this->shop_key, $current_version_settings_shop );
			}

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['archive_category_available_widgets'] ) ) {
					if ( ! isset( $current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-count'] ) ) {
						$current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-count'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-description'] ) ) {
						$current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-description'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-thumbnail'] ) ) {
						$current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-thumbnail'] = 'true';
					}
					if ( ! isset( $current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-title'] ) ) {
						$current_version_settings['archive_category_available_widgets']['custom-woo-builder-archive-category-title'] = 'true';
					}
				}
				if ( isset( $current_version_settings['shop_product_available_widgets'] ) ) {
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-description'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-description'] = 'true';
					}
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-loop'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-loop'] = 'true';
					}
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-navigation'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-navigation'] = 'true';
					}
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-notices'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-notices'] = 'true';
					}
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-ordering'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-ordering'] = 'true';
					}
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-page-title'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-page-title'] = 'true';
					}
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-pagination'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-pagination'] = 'true';
					}
					if ( ! isset( $current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-result-count'] ) ) {
						$current_version_settings['shop_product_available_widgets']['custom-woo-builder-products-result-count'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}

			}
		}

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

	}

}

function custom_woo_builder_db_upgrader() {
	return Custom_Woo_Builder_DB_Upgrader::get_instance();
}