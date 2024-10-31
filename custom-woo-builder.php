<?php
/**
 * Plugin Name: Onex Custom Woo Builder
 * Description: Your perfect asset in creating WooCommerce page templates using loads of special widgets & stylish page layouts
 * Version:     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder' ) ) {

	class Custom_Woo_Builder {

		private static $instance = null;

		private $plugin_url = null;

		private $version = '1.0.0';
		public $framework;
		public $documents;
		public $parser;
		public $macros;

		private $plugin_path = null;

		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'load_framework' ), - 20 );
			add_action( 'init', array( $this, 'init' ), - 999 );
		}

		public function get_version() {
			return $this->version;
		}
		public function load_framework() {

			require $this->plugin_path( 'framework/loader.php' );

			$this->framework = new Custom_Woo_Builder_CX_Loader(
				array(
					$this->plugin_path( 'framework/interface-builder/cherry-x-interface-builder.php' ),
					$this->plugin_path( 'framework/post-meta/cherry-x-post-meta.php' ),
					$this->plugin_path( 'framework/db-updater/cherry-x-db-updater.php' )
				)
			);

		}
		public function init() {

			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
				return;
			}
			add_action( 'plugins_loaded', array( $this, 'woocommerce_loaded' ) );

			if ( class_exists( 'WooCommerce' ) ) {

				$this->load_files();

				custom_woo_builder_assets()->init();
				custom_woo_builder_integration()->init();
				custom_woo_builder_integration_woocommerce()->init();
				custom_woo_builder_post_type()->init();

				custom_woo_builder_settings()->init();
				custom_woo_builder_shortocdes()->init();
				custom_woo_builder_shop_settings()->init();
				custom_woo_builder_compatibility()->init();

				$this->documents = new Custom_Woo_Builder_Documents();
				$this->parser    = new Custom_Woo_Builder_Parser();
				$this->macros    = new Custom_Woo_Builder_Macros();

				if ( is_admin() ) {
					require $this->plugin_path( 'includes/class-custom-woo-builder-db-upgrader.php' );
					custom_woo_builder_db_upgrader()->init();
				}
			}
		}

		function woocommerce_loaded() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_woocommerce_plugin' ] );
				return;
			}
		}

		public function admin_notice_missing_main_plugin() {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			$elementor_link = sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url() . 'plugin-install.php?s=elementor&tab=search&type=term',
				'<strong>' . esc_html__( 'Elementor', 'custom-woo-builder' ) . '</strong>'
			);
			$message = sprintf(
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'custom-woo-builder' ),
				'<strong>' . esc_html__( 'Custom Woo Builder', 'custom-woo-builder' ) . '</strong>',
				$elementor_link
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			if ( ! class_exists( 'WooCommerce' ) ) {
				$woocommerce_link = sprintf(
					'<a href="%1$s">%2$s</a>',
					admin_url() . 'plugin-install.php?s=woocommerce&tab=search&type=term',
					'<strong>' . esc_html__( 'WooCommerce', 'custom-woo-builder' ) . '</strong>'
				);
				$message = sprintf(
					esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'custom-woo-builder' ),
					'<strong>' . esc_html__( 'Custom Woo Builder', 'custom-woo-builder' ) . '</strong>',
					$woocommerce_link
				);
				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}
		}
	
		public function admin_notice_missing_woocommerce_plugin() {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			$woocommerce_link = sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url() . 'plugin-install.php?s=woocommerce&tab=search&type=term',
				'<strong>' . esc_html__( 'WooCommerce', 'custom-woo-builder' ) . '</strong>'
			);
			$message = sprintf(
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'custom-woo-builder' ),
				'<strong>' . esc_html__( 'Custom Woo Builder', 'custom-woo-builder' ) . '</strong>',
				$woocommerce_link
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		public function utility() {
			$utility = $this->get_core()->modules['cherry-utility'];

			return $utility->utility;
		}

		public function load_files() {
			require $this->plugin_path( 'includes/class-custom-woo-builder-assets.php' );
			require $this->plugin_path( 'includes/class-custom-woo-builder-tools.php' );
			require $this->plugin_path( 'includes/class-custom-woo-builder-post-type.php' );
			require $this->plugin_path( 'includes/class-custom-woo-builder-documents.php' );
			require $this->plugin_path( 'includes/class-custom-woo-builder-parser.php' );
			require $this->plugin_path( 'includes/class-custom-woo-builder-macros.php' );

			require $this->plugin_path( 'includes/integrations/base/class-custom-woo-builder-integration.php' );
			require $this->plugin_path( 'includes/integrations/base/class-custom-woo-builder-integration-woocommerce.php' );

			require $this->plugin_path( 'includes/class-custom-woo-builder-template-functions.php' );
			require $this->plugin_path( 'includes/class-custom-woo-builder-shortcodes.php' );

			require $this->plugin_path( 'includes/settings/class-custom-woo-builder-settings.php' );
			require $this->plugin_path( 'includes/settings/class-custom-woo-builder-shop-settings.php' );

			require $this->plugin_path( 'includes/lib/compatibility/class-custom-woo-builder-compatibility.php' );
		}

		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}

		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		public function template_path() {
			return apply_filters( 'custom-woo-builder/template-path', 'custom-woo-builder/' );
		}

		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		public static function wc_version_check( $version = '3.6' ) {
			if ( class_exists( 'WooCommerce' ) ) {
				global $woocommerce;
				if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
					return true;
				}
			}
			return false;
		}

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}

if ( ! function_exists( 'custom_woo_builder' ) ) {

	function custom_woo_builder() {
		return Custom_Woo_Builder::get_instance();
	}
}

custom_woo_builder();
