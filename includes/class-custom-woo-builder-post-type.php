<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_Post_Type' ) ) {

	class Custom_Woo_Builder_Post_Type {

		private static $instance = null;

		protected $post_type = 'custom-woo-builder';
		protected $meta_key  = 'custom-woo-builder-item';

		public function init() {

			$this->register_post_type();
			$this->init_meta();

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_templates_page' ), 90 );
			}

			add_filter( 'option_elementor_cpt_support', array( $this, 'set_option_support' ) );
			add_filter( 'default_option_elementor_cpt_support', array( $this, 'set_option_support' ) );

			add_filter( 'body_class', array( $this, 'set_body_class' ) );
			add_filter( 'post_class', array( $this, 'set_post_class' ) );

			add_filter( 'the_content', array( $this, 'add_product_wrapper' ), 1000000 );

			add_action( 'init', array( $this, 'fix_documents_types' ), 99 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_templates_popup' ) );
			add_action( 'admin_action_custom_woo_new_template', array( $this, 'create_template' ) );

			add_filter( 'post_row_actions', array( $this, 'remove_view_action' ), 10, 2 );

			add_filter( 'get_sample_permalink_html', array( $this, 'remove_permalink_action' ), 10, 5 );

		}

		public function remove_permalink_action( $return, $post_id, $new_title, $new_slug, $post ) {

			if ( $this->post_type === $post->post_type ) {
				return '';
			}

			return $return;
		}

		public function remove_view_action( $actions, $post ) {

			if ( $this->post_type === $post->post_type ) {
				unset( $actions['view'] );
			}

			return $actions;

		}

		public function create_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die(
					esc_html__( 'You don\'t have permissions to do this', 'custom-woo-builder' ),
					esc_html__( 'Error', 'custom-woo-builder' )
				);
			}

			$doc_types     = custom_woo_builder()->documents->get_document_types();
			$default_type  = $doc_types['single']['slug'];
			$type 		   = sanitize_key($_REQUEST['template_type']);
			$type          = isset( $type ) ? $type : $default_type;
			$name 		   = sanitize_key($_REQUEST['template_name']);
			$name          = isset( $name ) ? $name : '';
			$documents     = Elementor\Plugin::instance()->documents;
			$doc_type      = $documents->get_document_type( $type );
			$template_data = '';
			$templates = array();


			if ( $type === $doc_types['single']['slug'] ) {
				$template = sanitize_key($_REQUEST['template_single']);
				$template = isset( $template ) ? $template : '';
				$templates     = $this->predesigned_single_templates();
			} else if( $type === $doc_types['archive']['slug'] ) {
				$template = sanitize_key($_REQUEST['template_archive']);
				$template = isset( $template ) ? $template : '';
				$templates     = $this->predesigned_archive_templates();
			} else if( $type === $doc_types['category']['slug'] ) {
				$template = sanitize_key($_REQUEST['template_category']);
				$template = isset( $template ) ? $template : '';
				$templates     = $this->predesigned_category_templates();
			} else if( $type === $doc_types['shop']['slug'] ) {
				$template = sanitize_key($_REQUEST['template_shop']);
				$template = isset( $template ) ? $template : '';
				$templates     = $this->predesigned_shop_templates();
			}

			if ( $template ) {
				if ( ! isset( $templates[ $template ] ) ) {
					wp_die(
						esc_html__( 'This template not registered', 'custom-woo-builder' ),
						esc_html__( 'Error', 'custom-woo-builder' )
					);
				}

				$data    = $templates[ $template ];
				$content = $data['content'];

				ob_start();
				include $content;
				$template_data = ob_get_clean();

			}

			$meta_input = array(
				'_elementor_edit_mode'   => 'builder',
				$doc_type::TYPE_META_KEY => esc_attr( $type ),
			);

			if ( ! empty( $template_data ) ) {
				$meta_input['_elementor_data'] = wp_slash( $template_data );
			}

			$post_data = array(
				'post_type'  => $this->slug(),
				'meta_input' => $meta_input,
			);

			if ( $name ) {
				$post_data['post_title'] = esc_attr( $name );
			}

			$template_id = wp_insert_post( $post_data );

			if ( ! $template_id ) {
				wp_die(
					esc_html__( 'Can\'t create template. Please try again', 'custom-woo-builder' ),
					esc_html__( 'Error', 'custom-woo-builder' )
				);
			}

			wp_redirect( Elementor\Utils::get_edit_link( $template_id ) );
			die();

		}

		public function enqueue_templates_popup( $hook ) {

			if ( 'edit.php' !== $hook ) {
				return;
			}

			if ( ! isset( $_GET['post_type'] ) || $this->slug() !== $_GET['post_type'] ) {
				return;
			}

			wp_enqueue_style(
				'custom-woo-builder-template-popup',
				custom_woo_builder()->plugin_url( 'assets/css/template-popup.css' )
			);

			wp_enqueue_script(
				'custom-woo-builder-template-popup',
				custom_woo_builder()->plugin_url( 'assets/js/template-popup.js' ),
				array( 'jquery' ),
				custom_woo_builder()->get_version(),
				true
			);

			wp_localize_script( 'custom-woo-builder-template-popup', 'CustomWooPopupSettings', array(
				'button' => '<a href="#" class="page-title-action custom-woo-new-template">' . __( 'Create from predesigned template', 'custom-woo-builder' ) . '</a>',
			) );

			add_action( 'admin_footer', array( $this, 'template_popup' ) );

		}

		public function predesigned_single_templates() {

			$base_url = custom_woo_builder()->plugin_url( 'includes/templates/single/' );
			$base_dir = custom_woo_builder()->plugin_path( 'includes/templates/single/' );

			return apply_filters( 'custom-woo-builder/predesigned-single-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
				'layout-5' => array(
					'content' => $base_dir . 'layout-5/template.json',
					'thumb'   => $base_url . 'layout-5/thumbnail.png',
				),
				'layout-6' => array(
					'content' => $base_dir . 'layout-6/template.json',
					'thumb'   => $base_url . 'layout-6/thumbnail.png',
				),
				'layout-7' => array(
					'content' => $base_dir . 'layout-7/template.json',
					'thumb'   => $base_url . 'layout-7/thumbnail.png',
				),
				'layout-8' => array(
					'content' => $base_dir . 'layout-8/template.json',
					'thumb'   => $base_url . 'layout-8/thumbnail.png',
				),
			) );
		}

		public function predesigned_archive_templates() {

			$base_url = custom_woo_builder()->plugin_url( 'includes/templates/archive/' );
			$base_dir = custom_woo_builder()->plugin_path( 'includes/templates/archive/' );

			return apply_filters( 'custom-woo-builder/predesigned-archive-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
			) );
		}

		public function predesigned_category_templates() {

			$base_url = custom_woo_builder()->plugin_url( 'includes/templates/category/' );
			$base_dir = custom_woo_builder()->plugin_path( 'includes/templates/category/' );

			return apply_filters( 'custom-woo-builder/predesigned-category-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
			) );
		}

		public function predesigned_shop_templates() {

			$base_url = custom_woo_builder()->plugin_url( 'includes/templates/shop/' );
			$base_dir = custom_woo_builder()->plugin_path( 'includes/templates/shop/' );

			return apply_filters( 'custom-woo-builder/predesigned-shop-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
			) );
		}

		public function template_popup() {

			$action = add_query_arg(
				array(
					'action' => 'custom_woo_new_template',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			include custom_woo_builder()->get_template( 'template-popup.php' );

		}

		public function fix_documents_types() {

			if ( ! isset( $_GET['fix_custom_woo_templates'] ) ) {
				return;
			}

			$args = array(
				'post_type'      => $this->slug(),
				'post_status'    => array( 'publish', 'pending', 'draft', 'future' ),
				'posts_per_page' => -1,
			);

			$wp_query  = new WP_Query( $args );
			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( $this->slug() );

			if ( ! $wp_query->have_posts() ) {
				return false;
			}

			foreach ( $wp_query->posts as $post ) {
				update_post_meta( $post->ID, $doc_type::TYPE_META_KEY, $this->slug() );
			}

		}

		public function add_product_wrapper( $content ) {

			if ( is_singular( $this->slug() ) && isset( $_GET['elementor-preview'] ) ) {
				$content = sprintf( '<div class="product">%s</div>', $content );
			}

			return $content;
		}

		public function set_body_class( $classes ) {

			if ( is_singular( $this->slug() ) ) {
				$classes[] = 'single-product woocommerce';
			}

			return $classes;
		}

		public function set_post_class( $classes ) {

			if ( is_singular( $this->slug() ) ) {
				$classes[] = 'product';
			}

			return $classes;
		}

		public function add_templates_page() {

			add_submenu_page(
				'woocommerce',
				esc_html__( 'Custom Woo Templates', 'custom-woo-builder' ),
				esc_html__( 'Custom Woo Templates', 'custom-woo-builder' ),
				'edit_pages',
				'edit.php?post_type=' . $this->slug()
			);

		}

		public function slug() {
			return $this->post_type;
		}

		public function meta_key() {
			return $this->meta_key;
		}

		public function set_option_support( $value ) {

			if ( empty( $value ) ) {
				$value = array();
			}

			return array_merge( $value, array( $this->slug() ) );
		}

		public function register_post_type() {
			$labels = array(
				'name'          => esc_html__( 'Custom Woo Templates', 'custom-woo-builder' ),
				'singular_name' => esc_html__( 'Custom Woo Template', 'custom-woo-builder' ),
				'add_new'       => esc_html__( 'Add New Template', 'custom-woo-builder' ),
				'add_new_item'  => esc_html__( 'Add New Template', 'custom-woo-builder' ),
				'edit_item'     => esc_html__( 'Edit Template', 'custom-woo-builder' ),
				'menu_name'     => esc_html__( 'Custom Woo Templates', 'custom-woo-builder' ),
			);

			$args = array(
				'labels'              => $labels,
				'hierarchical'        => false,
				'description'         => 'description',
				'taxonomies'          => array(),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => null,
				'menu_icon'           => null,
				'show_in_nav_menus'   => false,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'supports'            => array( 'title' ),
			);

			register_post_type( $this->slug(), $args );

		}

		public function init_meta() {

			new Cherry_X_Post_Meta( array(
				'id'            => 'template-settings',
				'title'         => esc_html__( 'Template Settings', 'custom-woo-builder' ),
				'page'          => array( $this->slug() ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder' ),
				'fields'        => array(
					'_sample_product' => array(
						'type'              => 'select',
						'element'           => 'control',
						'options'           => false,
						'options_callback'  => array( $this, 'get_products' ),
						'label'             => esc_html__( 'Sample Product for Editing (if not selected - will be used latest added)', 'custom-woo-builder' ),
						'sanitize_callback' => 'esc_attr',
					),
				),
			) );

		}

		public function get_products() {

			$products = get_posts( array(
				'post_type'      => 'product',
				'post_status'    => array( 'publish', 'pending', 'draft', 'future' ),
				'posts_per_page' => 100,
			) );

			$default = array(
				'' => __( 'Select Product...', 'custom-woo-builder' ),
			);

			if ( empty( $products ) ) {
				return $default;
			}

			$products = wp_list_pluck( $products, 'post_title', 'ID' );

			return $default + $products;
		}

		public function get_builder() {

			$builder_data = custom_woo_builder()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);
		}

		public function get_templates_list( $type = 'all' ) {

			$args = array(
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'post_type'      => $this->slug(),
			);

			if ( 'all' !== $type ) {

				$doc_types     = custom_woo_builder()->documents->get_document_types();
				$default_type  = $doc_types['single']['slug'];
				$type          = isset( $doc_types[ $type ] ) ? $doc_types[ $type ]['slug'] : $default_type;
				$documents     = Elementor\Plugin::instance()->documents;
				$doc_type      = $documents->get_document_type( $type );

				$args['meta_query'] = array(
					array(
						'key'   => $doc_type::TYPE_META_KEY,
						'value' => $type,
					),
				);
			}

			$templates = get_posts( $args );

			return $templates;

		}

		public function get_templates_list_for_options( $type = 'all' ) {

			$templates = $this->get_templates_list( $type );

			$default = array(
				'' => esc_html__( 'Select Template...', 'custom-woo-builder' ),
			);

			if ( empty( $templates ) ) {
				return $default;
			}

			return $default + wp_list_pluck( $templates, 'post_title', 'ID' );

		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

function custom_woo_builder_post_type() {
	return Custom_Woo_Builder_Post_Type::get_instance();
}
