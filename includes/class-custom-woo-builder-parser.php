<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_Parser' ) ) {

	class Custom_Woo_Builder_Parser {

		public $processed_documents = array();
		private $custom_engine_object  = null;

		public function macros_regex() {
			return '/\%\%([a-z-_]+)(\:\:(.*?))?\%\%/';
		}

		public function get_template_content( $template_id ) {

			$this->set_custom_engine_object();

			$render_method = apply_filters(
				'custom-woo-builder/get-template-content/render-method',
				custom_woo_builder_shop_settings()->get( 'widgets_render_method', 'macros' )
			);

			if ( 'elementor' === $render_method ) {
				return $this->render_elementor_content( $template_id );
			}

			$content = get_post_meta( $template_id, '_custom_woo_builder_content', true );

			if ( ! $content ) {
				return;
			}

			$this->set_elementor_data( $template_id );

			$parsed = $this->parse_content( $content );

			$this->reset_custom_engine_object();

			return $parsed;

		}

		public function set_custom_engine_object() {

			if ( ! function_exists( 'custom_engine' ) ) {
				return;
			}

			if ( null === $this->custom_engine_object ) {
				$this->custom_engine_object = custom_engine()->listings->data->get_current_object();
			}

			global $post;

			custom_engine()->listings->data->set_current_object( $post );

		}

		public function reset_custom_engine_object() {

			if ( ! function_exists( 'custom_engine' ) ) {
				return;
			}

			custom_engine()->listings->data->set_current_object( $this->custom_engine_object );
		}

		public function render_elementor_content( $template_id ) {
			return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
		}

		public function parse_content( $content ) {

			$parsed = preg_replace_callback( $this->macros_regex(), array( $this, 'replace_callback' ), $content );

			return $parsed;
		}

		public function replace_callback( $matches ) {

			if ( empty( $matches[1] ) ) {
				return $matches[0];
			}

			$widget = $this->get_widget_from_macros( $matches[1] );

			if ( ! $widget ) {
				return $matches[0];
			}

			if ( ! is_callable( array( $widget, 'render_callback' ) ) ) {
				return $matches[0];
			}

			$settings = array();

			if ( ! empty( $matches[3] ) ) {
				$settings = $this->get_parsed_settings( $matches[3] );
			}

			$args = apply_filters( 'custom-woo-builder/render-callback/custom-args', array() );

			ob_start();
			call_user_func( array( $widget, 'render_callback' ), $settings, $args );
			$content = ob_get_clean();

			return $content;

		}

		public function get_parsed_settings( $settings_string ) {

			$settings_string = str_replace( '::', '', $settings_string );
			$raw             = explode( '&&', $settings_string );

			if ( empty( $raw ) ) {
				return array();
			}

			$settings = array();

			foreach ( $raw as $setting ) {

				$setting                 = explode( '="', $setting );
				$settings[ $setting[0] ] = rtrim( $setting[1], '"' );

			}

			return $settings;

		}

		public function get_macros_string( $macros = '', $settings = array() ) {

			$settings_string = '';
			$sep             = '';

			if ( ! empty( $settings ) ) {

				foreach ( $settings as $key => $value ) {

					$settings_string .= '::';

					if ( is_array( $value ) ) {
						$value = implode( '|', $value );
					}

					$settings_string .= sprintf( '%3$s%1$s="%2$s"', $key, $value, $sep );

					$sep = '&&';
				}

			}

			return sprintf( '%%%%%1$s%2$s%%%%', $macros, $settings_string );

		}

		public function get_widget_from_macros( $macros ) {

			$class_name = str_replace( array( '-', '_' ), ' ', $macros );
			$class_name = ucwords( $class_name );
			$class_name = str_replace( ' ', '_', $class_name );
			$class_name = 'Elementor\\' . $class_name;

			if ( ! class_exists( $class_name ) ) {

				$file =  glob( custom_woo_builder()->plugin_path( 'includes/widgets/' ) . 'archive-*/' . $macros . '.php' );

				if ( ! file_exists( $file[0] ) ) {
					return false;
				} else {
					require $file[0];
				}

			}

			return $class_name;

		}

		public function set_elementor_data( $post_id ) {
			if ( in_array( $post_id, $this->processed_documents ) ) {
				return;
			}

			$document = Elementor\Plugin::$instance->documents->get_doc_for_frontend( $post_id );

			Elementor\Plugin::$instance->documents->switch_to_document( $document );

			if ( $document->is_editable_by_current_user() ) {
				$this->admin_bar_edit_documents[ $document->get_main_id() ] = $document;
			}

			if ( $document->is_autosave() ) {
				$css_file = new Elementor\Core\Files\CSS\Post_Preview( $document->get_post()->ID );
			} else {
				$css_file = new Elementor\Core\Files\CSS\Post( $post_id );
			}

			$css_meta = $css_file->get_meta();

			if ( 'inline' === $css_meta['status'] ) {
				printf( '<style id="elementor-post-%1$s">%2$s</style>', $css_file->get_post_id(), $css_meta['css'] ); // XSS ok.
			} else {
				$css_file->enqueue();
			}

			$this->maybe_print_css_directly( $css_file );

			Elementor\Plugin::$instance->documents->restore_document();

			$this->processed_documents[] = $post_id;

		}

		public function maybe_print_css_directly( $css_file ) {

			$plugin = Elementor\Plugin::instance();

			if ( $plugin->editor->is_edit_mode() ) {
				printf( '<link rel="stylesheet" type="text/css" href="%s">', $css_file->get_url() );
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

/*function class_name() {
	return Custom_Woo_Builder_Parser::get_instance();
}*/
