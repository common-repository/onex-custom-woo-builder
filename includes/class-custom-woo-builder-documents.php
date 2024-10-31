<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Custom_Woo_Builder_Documents' ) ) {

	class Custom_Woo_Builder_Documents {

		protected $current_type = null;

		function __construct() {

			add_action( 'elementor/documents/register', array( $this, 'register_elementor_document_types' ) );

			if ( ! class_exists( 'Custom_Theme_Core' ) && ! class_exists( 'Custom_Engine' ) ) {
				add_action( 'elementor/dynamic_tags/before_render', array( $this, 'switch_to_preview_query' ) );
				add_action( 'elementor/dynamic_tags/after_render', array( $this, 'restore_current_query' ) );
			}

			add_filter( 'admin_body_class', array( $this, 'set_admin_body_class' ) );

		}

		function set_admin_body_class( $classes ) {

			if ( is_admin() ) {
				$document  = Elementor\Plugin::instance()->documents->get( get_the_ID() );

				if ( $document ){
					$classes .= ' ' . $document->get_name() . '-document';
				}
			}

			return $classes;

		}

		public function set_current_type( $type ) {
			$this->current_type = $type;
		}

		public function get_current_type() {
			return $this->current_type;
		}

		public function is_document_type( $type = 'single' ){
			$doc_types = $this->get_document_types();

			if( $doc_types[ $type ]['slug'] === $this->get_current_type() ){
				return true;
			}

			return false;
		}

		public function switch_to_preview_query() {

			$current_post_id = get_the_ID();
			$document        = Elementor\Plugin::instance()->documents->get_doc_or_auto_save( $current_post_id );

			if ( ! is_object( $document ) || ! method_exists( $document, 'get_preview_as_query_args' ) ) {
				return;
			}

			$new_query_vars = $document->get_preview_as_query_args();

			if ( empty( $new_query_vars ) ) {
				return;
			}

			Elementor\Plugin::instance()->db->switch_to_query( $new_query_vars );

		}

		public function restore_current_query() {
			Elementor\Plugin::instance()->db->restore_current_query();
		}

		public function get_document_types() {

			return array(
				'single'   => array(
					'slug'  => custom_woo_builder_post_type()->slug(),
					'name'  => __( 'Single', 'custom-woo-builder' ),
					'file'  => 'includes/documents/class-custom-woo-builder-document-single.php',
					'class' => 'Custom_Woo_Builder_Document',
				),
				'archive'  => array(
					'slug'  => custom_woo_builder_post_type()->slug() . '-archive',
					'name'  => __( 'Archive', 'custom-woo-builder' ),
					'file'  => 'includes/documents/class-custom-woo-builder-document-archive-product.php',
					'class' => 'Custom_Woo_Builder_Archive_Document_Product',
				),
				'category' => array(
					'slug'  => custom_woo_builder_post_type()->slug() . '-category',
					'name'  => __( 'Category', 'custom-woo-builder' ),
					'file'  => 'includes/documents/class-custom-woo-builder-document-archive-category.php',
					'class' => 'Custom_Woo_Builder_Archive_Document_Category',
				),
				'shop'     => array(
					'slug'  => custom_woo_builder_post_type()->slug() . '-shop',
					'name'  => __( 'Shop', 'custom-woo-builder' ),
					'file'  => 'includes/documents/class-custom-woo-builder-document-archive.php',
					'class' => 'Custom_Woo_Builder_Shop_Document',
				),
			);

		}

		public function register_elementor_document_types( $documents_manager ) {

			require custom_woo_builder()->plugin_path( 'includes/documents/class-custom-woo-builder-document-base.php' );

			$document_types = $this->get_document_types();

			foreach ( $document_types as $type => $data ) {
				require custom_woo_builder()->plugin_path( $data['file'] );
				$documents_manager->register_document_type( $data['slug'], $data['class'] );
			}
		}

	}

}
