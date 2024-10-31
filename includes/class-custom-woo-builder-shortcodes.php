<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_Shortcodes' ) ) {

	class Custom_Woo_Builder_Shortcodes {

		private static $instance = null;

		private $shortocdes = array();

		public function init() {
			add_action( 'init', array( $this, 'register_shortcodes' ), 30 );
		}

		public function register_shortcodes() {

			require custom_woo_builder()->plugin_path( 'includes/base/class-custom-woo-builder-shortcode-base.php' );

			foreach ( glob( custom_woo_builder()->plugin_path( 'includes/shortcodes/' ) . '*.php' ) as $file ) {
				$this->register_shortcode( $file );
			}

		}

		public function register_shortcode( $file ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );

			require $file;

			if ( ! class_exists( $class ) ) {
				return;
			}

			$shortcode = new $class;

			$this->shortocdes[ $shortcode->get_tag() ] = $shortcode;

		}

		public function get_shortcode( $tag ) {
			return isset( $this->shortocdes[ $tag ] ) ? $this->shortocdes[ $tag ] : false;
		}

		public static function get_instance( $shortcodes = array() ) {

			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

function custom_woo_builder_shortocdes() {
	return Custom_Woo_Builder_Shortcodes::get_instance();
}
