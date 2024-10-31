<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Custom_Woo_Builder_Integration' ) ) {

	class Custom_Woo_Builder_Integration {

		private static $instance = null;

		private $is_elementor_ajax = false;

		private $current_product = false;

		public function init() {

			add_action( 'elementor/init', array( $this, 'register_category' ) );

			add_action( 'elementor/widgets/widgets_registered', array( $this, 'include_wc_hooks' ), 0 );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ), 10 );

			add_action( 'wp_ajax_elementor_render_widget', array( $this, 'set_elementor_ajax' ), 10, - 1 );
			add_action( 'elementor/page_templates/canvas/before_content', array( $this, 'open_canvas_wrap' ) );
			add_action( 'elementor/page_templates/canvas/after_content', array( $this, 'close_canvas_wrap' ) );

			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );

			add_action( 'template_redirect', array( $this, 'set_track_product_view' ), 20 );

			add_filter( 'post_class', array( $this, 'add_product_post_class' ), 20 );

		}

		public function set_current_product( $product_data = array() ) {
			$this->current_product = $product_data;
		}

		public function get_current_product() {
			return $this->current_product;
		}

		public function reset_current_product() {
			return $this->current_product = false;
		}

		public function editor_styles() {
			
			wp_enqueue_style(
				'custom-woo-builder-editor',
				custom_woo_builder()->plugin_url( 'assets/css/editor.css' ),
				array(),
				custom_woo_builder()->get_version()
			);

		}

		public function include_wc_hooks() {

			$elementor    = Elementor\Plugin::instance();
			$is_edit_mode = $elementor->editor->is_edit_mode();

			if ( ! $is_edit_mode ) {
				return;
			}

			if ( ! defined( 'WC_ABSPATH' ) ) {
				return;
			}

			if ( ! file_exists( WC_ABSPATH . 'includes/wc-template-hooks.php' ) ) {
				return;
			}

			$rewrite = apply_filters( 'custom-woo-builder/integration/rewrite-frontend-hooks', false );

			if ( ! $rewrite ) {
				include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
			}

			remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
		}


		public function add_product_post_class( $classes ) {
			if (
				is_post_type_archive( 'product' ) ||
				'related' === wc_get_loop_prop( 'name' ) ||
				'up-sells' === wc_get_loop_prop( 'name' ) ||
				'cross-sells' === wc_get_loop_prop( 'name' )
			) {
				if ( filter_var( custom_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN ) ) {
					$classes[] = 'custom-woo-thumb-with-effect';
				}
			}

			return $classes;
		}

		public function set_track_product_view() {
			if ( ! is_singular( 'product' ) ) {
				return;
			}

			global $post;

			if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
				$viewed_products = array();
			} else {
				$viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
			}

			if ( ! in_array( $post->ID, $viewed_products ) ) {
				$viewed_products[] = $post->ID;
			}

			if ( sizeof( $viewed_products ) > 30 ) {
				array_shift( $viewed_products );
			}

			wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
		}

		

		public function open_canvas_wrap() {

			if ( ! is_singular( custom_woo_builder_post_type()->slug() ) ) {
				return;
			}

			echo '<div class="product">';
		}

		public function close_canvas_wrap() {

			if ( ! is_singular( custom_woo_builder_post_type()->slug() ) ) {
				return;
			}

			echo '</div>';
		}

		public function set_elementor_ajax() {
			$this->is_elementor_ajax = true;
		}

		public function in_elementor() {

			$result = false;

			if ( wp_doing_ajax() ) {
				$result = $this->is_elementor_ajax;
			} elseif ( Elementor\Plugin::instance()->editor->is_edit_mode()
			           || Elementor\Plugin::instance()->preview->is_preview_mode() ) {
				$result = true;
			}

			return apply_filters( 'custom-woo-builder/in-elementor', $result );
		}

		public function register_widgets( $widgets_manager ) {
			$global_available_widgets           = custom_woo_builder_settings()->get( 'global_available_widgets' );
			$single_available_widgets           = custom_woo_builder_settings()->get( 'single_product_available_widgets' );
			$archive_available_widgets          = custom_woo_builder_settings()->get( 'archive_product_available_widgets' );
			$archive_category_available_widgets = custom_woo_builder_settings()->get( 'archive_category_available_widgets' );
			$shop_available_widgets             = custom_woo_builder_settings()->get( 'shop_product_available_widgets' );

			require_once custom_woo_builder()->plugin_path( 'includes/base/class-custom-woo-builder-base.php' );

			foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/global/' ) . '*.php' ) as $file ) {
				$slug    = basename( $file, '.php' );
				$enabled = isset( $global_available_widgets[ $slug ] ) ? $global_available_widgets[ $slug ] : '';
				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $global_available_widgets ) {
					$this->register_widget( $file, $widgets_manager );
				}
			}

			$doc_type = custom_woo_builder()->documents->get_current_type();

			if ( ! $doc_type ) {
				if ( get_post_type() === custom_woo_builder_post_type()->slug() ) {
					$doc_type = get_post_meta( get_the_ID(), '_elementor_template_type', true );
				}
			}

			$doc_type = apply_filters( 'custom-woo-builder/integration/doc-type', $doc_type );
			$doc_types    = custom_woo_builder()->documents->get_document_types();

			if ( $this->is_setting_enabled( 'custom_single_page' ) || $doc_types['single']['slug'] === $doc_type ) {
				foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/single-product/' ) . '*.php' ) as $file ) {
					$slug    = basename( $file, '.php' );
					$enabled = isset( $single_available_widgets[ $slug ] ) ? $single_available_widgets[ $slug ] : '';
					if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $single_available_widgets ) {
						$this->register_widget( $file, $widgets_manager );
					}
				}
			}

			if ( $this->is_setting_enabled( 'custom_shop_page' ) || $doc_types['shop']['slug'] === $doc_type ) {
				foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/shop/' ) . '*.php' ) as $file ) {
					$slug    = basename( $file, '.php' );
					$enabled = isset( $shop_available_widgets[ $slug ] ) ? $shop_available_widgets[ $slug ] : '';
					if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $shop_available_widgets ) {
						$this->register_widget( $file, $widgets_manager );
					}
				}
			}

			if ( $this->is_setting_enabled( 'custom_archive_page' ) || $doc_types['archive']['slug'] === $doc_type ) {
				foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/archive-product/' ) . '*.php' ) as $file ) {
					$slug    = basename( $file, '.php' );
					$enabled = isset( $archive_available_widgets[ $slug ] ) ? $archive_available_widgets[ $slug ] : '';
					if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $archive_available_widgets ) {
						$this->register_widget( $file, $widgets_manager );
					}
				}
			}

			if ( $this->is_setting_enabled( 'custom_archive_category_page' ) || $doc_types['category']['slug'] === $doc_type ) {
				foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/archive-category/' ) . '*.php' ) as $file ) {
					$slug    = basename( $file, '.php' );
					$enabled = isset( $archive_category_available_widgets[ $slug ] ) ? $archive_category_available_widgets[ $slug ] : '';
					if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $archive_category_available_widgets ) {
						$this->register_widget( $file, $widgets_manager );
					}
				}
			}

		}

		public function is_setting_enabled( $type = 'custom_single_page' ) {

			return filter_var( custom_woo_builder_shop_settings()->get( $type ), FILTER_VALIDATE_BOOLEAN );

		}

		public function register_widget( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\%s', $class );

			require_once $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}

		}

		public function register_category() {

			$elements_manager    = Elementor\Plugin::instance()->elements_manager;
			$custom_woo_builder_cat = 'custom-woo-builder';

			$elements_manager->add_category(
				$custom_woo_builder_cat,
				array(
					'title' => esc_html__( 'Custom Woo Builder', 'custom-woo-builder' ),
					'icon'  => 'font',
				),
				1
			);
		}

		public function add_controls( $controls_manager ) {

			$grouped = array(
				'custom-woo-box-style' => 'Custom_Woo_Group_Control_Box_Style',
			);

			foreach ( $grouped as $control_id => $class_name ) {
				if ( $this->include_control( $class_name, true ) ) {
					$controls_manager->add_group_control( $control_id, new $class_name() );
				}
			}

		}

		public function include_control( $class_name, $grouped = false ) {

			$filename = sprintf(
				'includes/controls/%2$sclass-%1$s.php',
				str_replace( '_', '-', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( custom_woo_builder()->plugin_path( $filename ) ) ) {
				return false;
			}

			require custom_woo_builder()->plugin_path( $filename );

			return true;
		}

		public static function get_instance( $shortcodes = array() ) {

			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}

			return self::$instance;
		}
	}

}

function custom_woo_builder_integration() {
	return Custom_Woo_Builder_Integration::get_instance();
}