<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_Assets' ) ) {

	class Custom_Woo_Builder_Assets {

		private static $instance = null;

		public function init() {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'elementor/frontend/after_enqueue_scripts', array(
				'WC_Frontend_Scripts',
				'localize_printed_scripts'
			), 5 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		}

		public function enqueue_admin_assets(){
			$screen = get_current_screen();

			if ( 'woocommerce_page_wc-settings' === $screen->base ){
				wp_enqueue_style(
					'custom-woo-builder-admin',
					custom_woo_builder()->plugin_url( 'assets/css/admin.css' ),
					false,
					custom_woo_builder()->get_version()
				);
			}

		}

		public function enqueue_styles() {
			$font_path = WC()->plugin_url() . '/assets/fonts/';
			$inline_font = '@font-face {
			font-family: "WooCommerce";
			src: url("' . $font_path . 'WooCommerce.eot");
			src: url("' . $font_path . 'WooCommerce.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'WooCommerce.woff") format("woff"),
				url("' . $font_path . 'WooCommerce.ttf") format("truetype"),
				url("' . $font_path . 'WooCommerce.svg#WooCommerce") format("svg");
			font-weight: normal;
			font-style: normal;
			}';

			wp_enqueue_style(
				'custom-woo-builder',
				custom_woo_builder()->plugin_url( 'assets/css/custom-woo-builder.css' ),
				false,
				custom_woo_builder()->get_version()
			);

			wp_add_inline_style(
				'custom-woo-builder',
				$inline_font
			);

		}

		public function enqueue_scripts() {

			wp_enqueue_script(
				'custom-woo-builder',
				custom_woo_builder()->plugin_url( 'assets/js/custom-woo-builder' . $this->suffix() . '.js' ),
				array( 'jquery', 'elementor-frontend' ),
				custom_woo_builder()->get_version(),
				true
			);

			wp_localize_script(
				'custom-woo-builder',
				'customWooBuilderData',
				apply_filters( 'custom-woo-builder/frontend/localize-data', array() )
			);

		}

		public function suffix() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}

}

function custom_woo_builder_assets() {
	return Custom_Woo_Builder_Assets::get_instance();
}
