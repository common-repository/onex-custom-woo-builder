<?php
if ( ! class_exists( 'Custom_Woo_Builder_Shortcode_Base' ) ) {

	abstract class Custom_Woo_Builder_Shortcode_Base {

		public $info = array();

		public $settings = array();

		public $atts = array();

		public function __construct() {
			add_shortcode( $this->get_tag(), array( $this, 'do_shortcode' ) );
		}

		public function get_tag() {
		}

		public function get_atts() {
			return array();
		}

		public function get_attr( $name = null ) {

			if ( isset( $this->atts[ $name ] ) ) {
				return $this->atts[ $name ];
			}

			$allowed = $this->get_atts();

			if ( isset( $allowed[ $name ] ) && isset( $allowed[ $name ]['default'] ) ) {
				return $allowed[ $name ]['default'];
			} else {
				return false;
			}

		}

		public function _hidden_atts() {
			return array(
				'_element_id' => '',
			);
		}

		public function get_settings(){
			return $this->settings;
		}

		public function set_settings( $settings = array() ){
			return $this->settings = $settings;
		}

		public function _shortcode( $content = null ) {
		}

		public function html( $text = null, $format = '%s', $args = array(), $echo = true ) {

			if ( empty( $text ) ) {
				return '';
			}

			$args   = array_merge( array( $text ), $args );
			$result = vsprintf( $format, $args );

			if ( $echo ) {
				echo $result;
			} else {
				return $result;
			}

		}

		public function default_atts() {

			$result = array();

			foreach ( $this->get_atts() as $attr => $data ) {
				$result[ $attr ] = isset( $data['default'] ) ? $data['default'] : false;
			}

			foreach ( $this->_hidden_atts() as $attr => $default ) {
				$result[ $attr ] = $default;
			}

			return $result;
		}

		public function do_shortcode( $atts = array(), $content = null ) {

			$atts              = shortcode_atts( $this->default_atts(), $atts, $this->get_tag() );
			$this->css_classes = array();

			if ( null !== $content ) {
				$content = do_shortcode( $content );
			}

			$this->atts = $atts;

			return $this->_shortcode( $content );
		}

		public function get_template( $name ) {
			return custom_woo_builder()->get_template( $this->get_tag() . '/global/' . $name . '.php' );
		}

	}
}
