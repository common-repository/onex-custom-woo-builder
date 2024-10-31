<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Custom_Woo_Builder_Settings' ) ) {

	class Custom_Woo_Builder_Settings {

		private static $instance = null;

		public $key = 'custom-woo-builder-settings';

		public $builder  = null;

		public $settings = null;

		public $global_available_widgets = array();

		public $single_product_available_widgets = array();

		public $archive_product_available_widgets = array();

		public $archive_category_available_widgets = array();

		public $shop_product_available_widgets = array();

		public function init() {

			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'init', array( $this, 'save' ), 40 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );

			foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/global/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->global_available_widgets[ $slug] = $data['name'];
			}

			foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/single-product/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->single_product_available_widgets[ $slug] = $data['name'];
			}

			foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/archive-product/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->archive_product_available_widgets[ $slug] = $data['name'];
			}

			foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/archive-category/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->archive_category_available_widgets[ $slug] = $data['name'];
			}

			foreach ( glob( custom_woo_builder()->plugin_path( 'includes/widgets/shop/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->shop_product_available_widgets[ $slug] = $data['name'];
			}

		}

		public function init_builder() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			$builder_data = custom_woo_builder()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			$this->builder = new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

		}

		public function saved_notice() {

			if ( ! isset( $_GET['settings-saved'] ) ) {
				return false;
			}

			$message = esc_html__( 'Settings saved', 'custom-woo-builder' );

			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );

			return true;

		}

		public function save() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			if ( ! isset( $_REQUEST['action'] ) || 'save-settings' !== $_REQUEST['action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$current = get_option( $this->key, array() );
			$data    = $_REQUEST;

			unset( $data['action'] );

			foreach ( $data as $key => $value ) {
				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
			}

			update_option( $this->key, $current );

			$redirect = add_query_arg(
				array( 'dialog-saved' => true ),
				$this->get_settings_page_link()
			);

			wp_redirect( $redirect );
			die();

		}

		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->key,
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		public function register_page() {

			add_submenu_page(
				'elementor',
				esc_html__( 'Custom Woo Builder Settings', 'custom-woo-builder' ),
				esc_html__( 'Custom Woo Builder Settings', 'custom-woo-builder' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' )
			);

		}

		public function render_page() {

			foreach ( $this->global_available_widgets as $key => $value ) {
				$default_global_available_widgets[ $key ] = 'true';
			}

			foreach ( $this->single_product_available_widgets as $key => $value ) {
				$default_single_available_widgets[ $key ] = 'true';
			}

			foreach ( $this->archive_product_available_widgets as $key => $value ) {
				$default_archive_available_widgets[ $key ] = 'true';
			}

			foreach ( $this->archive_category_available_widgets as $key => $value ) {
				$default_archive_category_available_widgets[ $key ] = 'true';
			}

			foreach ( $this->shop_product_available_widgets as $key => $value ) {
				$default_shop_available_widgets[ $key ] = 'true';
			}

			$this->builder->register_section(
				array(
					'custom_woo_builder_settings' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'Custom Woo Builder Settings', 'custom-woo-builder' ),
					),
				)
			);

			$this->builder->register_form(
				array(
					'custom_woo_builder_settings_form' => array(
						'type'   => 'form',
						'parent' => 'custom_woo_builder_settings',
						'action' => add_query_arg(
							array( 'page' => $this->key, 'action' => 'save-settings' ),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$this->builder->register_settings(
				array(
					'settings_top' => array(
						'type'   => 'settings',
						'parent' => 'custom_woo_builder_settings_form',
					),
					'settings_bottom' => array(
						'type'   => 'settings',
						'parent' => 'custom_woo_builder_settings_form',
					),
				)
			);

			$this->builder->register_component(
				array(
					'custom_woo_builder_tab_vertical' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'settings_top',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'available_widgets_options' => array(
						'parent'      => 'custom_woo_builder_tab_vertical',
						'title'       => esc_html__( 'Available Widgets', 'custom-woo-builder' ),
					),
				)
			);

			$this->builder->register_control(
				array(
					'global_available_widgets' => array(
						'type'        => 'checkbox',
						'id'          => 'global_available_widgets',
						'name'        => 'global_available_widgets',
						'parent'      => 'available_widgets_options',
						'value'       => $this->get( 'global_available_widgets', $default_global_available_widgets ),
						'options'     => $this->global_available_widgets,
						'title'       => esc_html__( 'Global Available Widgets', 'custom-woo-builder' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the page', 'custom-woo-builder' ),
						'class'       => 'custom_woo_builder_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_control(
				array(
					'single_product_available_widgets' => array(
						'type'        => 'checkbox',
						'id'          => 'single_product_available_widgets',
						'name'        => 'single_product_available_widgets',
						'parent'      => 'available_widgets_options',
						'value'       => $this->get( 'single_product_available_widgets', $default_single_available_widgets ),
						'options'     => $this->single_product_available_widgets,
						'title'       => esc_html__( 'Single Product Available Widgets', 'custom-woo-builder' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the single product template', 'custom-woo-builder' ),
						'class'       => 'custom_woo_builder_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_control(
				array(
					'archive_product_available_widgets' => array(
						'type'        => 'checkbox',
						'id'          => 'archive_product_available_widgets',
						'name'        => 'archive_product_available_widgets',
						'parent'      => 'available_widgets_options',
						'value'       => $this->get( 'archive_product_available_widgets', $default_archive_available_widgets ),
						'options'     => $this->archive_product_available_widgets,
						'title'       => esc_html__( 'Archive Product Available Widgets', 'custom-woo-builder' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the archive product template', 'custom-woo-builder' ),
						'class'       => 'custom_woo_builder_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_control(
				array(
					'archive_category_available_widgets' => array(
						'type'        => 'checkbox',
						'id'          => 'archive_category_available_widgets',
						'name'        => 'archive_category_available_widgets',
						'parent'      => 'available_widgets_options',
						'value'       => $this->get( 'archive_category_available_widgets', $default_archive_category_available_widgets ),
						'options'     => $this->archive_category_available_widgets,
						'title'       => esc_html__( 'Archive Category Available Widgets', 'custom-woo-builder' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the archive category template', 'custom-woo-builder' ),
						'class'       => 'custom_woo_builder_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_control(
				array(
					'shop_product_available_widgets' => array(
						'type'        => 'checkbox',
						'id'          => 'shop_product_available_widgets',
						'name'        => 'shop_product_available_widgets',
						'parent'      => 'available_widgets_options',
						'value'       => $this->get( 'shop_product_available_widgets', $default_shop_available_widgets ),
						'options'     => $this->shop_product_available_widgets,
						'title'       => esc_html__( 'Shop Product Available Widgets', 'custom-woo-builder' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the archive product template', 'custom-woo-builder' ),
						'class'       => 'custom_woo_builder_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_settings(
				array(
					'product_thumb_effect_options' => array(
						'parent' => 'custom_woo_builder_tab_vertical',
						'title'  => esc_html__( 'Product Thumb Effect', 'custom-woo-builder' ),
					),
				)
			);

			$this->builder->register_control(
				array(
					'enable_product_thumb_effect' => array(
						'type'        => 'switcher',
						'id'          => 'enable_product_thumb_effect',
						'name'        => 'enable_product_thumb_effect',
						'parent'      => 'product_thumb_effect_options',
						'title'       => esc_html__( 'Enable Thumbnails Effect', 'custom-woo-builder' ),
						'description' => esc_html__( 'Enable thumbnails switch on hover', 'custom-woo-builder' ),
						'value'       => $this->get( 'enable_product_thumb_effect' ),
						'toggle'      => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
					),
				)
			);

			$this->builder->register_control(
				array(
					'product_thumb_effect' => array(
						'type'    => 'select',
						'id'      => 'product_thumb_effect',
						'name'    => 'product_thumb_effect',
						'parent'  => 'product_thumb_effect_options',
						'value'   => $this->get( 'product_thumb_effect', 'slide-left' ),
						'options' => array(
							'slide-left'     => esc_html__( 'Slide Left', 'custom-woo-builder' ),
							'slide-right'    => esc_html__( 'Slide Right', 'custom-woo-builder' ),
							'slide-top'      => esc_html__( 'Slide Top', 'custom-woo-builder' ),
							'slide-bottom'   => esc_html__( 'Slide Bottom', 'custom-woo-builder' ),
							'fade'           => esc_html__( 'Fade', 'custom-woo-builder' ),
							'fade-with-zoom' => esc_html__( 'Fade With Zoom', 'custom-woo-builder' ),
						),
						'title'   => esc_html__( 'Thumbnails Effect:', 'custom-woo-builder' ),
					)
				)
			);

			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cx-component dialog-save',
						'html'   => '<button type="submit" class="button button-primary">' . esc_html__( 'Save', 'custom-woo-builder' ) . '</button>',
					),
				)
			);

			echo '<div class="custom-woo-builder-settings-page">';
				$this->builder->render();
			echo '</div>';
		}

		public static function get_instance() {
			
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

function custom_woo_builder_settings() {
	return Custom_Woo_Builder_Settings::get_instance();
}
