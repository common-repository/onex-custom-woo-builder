<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Custom_Woo_Builder_Compatibility' ) ) {

	class Custom_Woo_Builder_Compatibility {

		private static $instance = null;

		public function init() {
			if ( defined( 'WPML_ST_VERSION' ) ) {
				add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'add_translatable_nodes' ) );
			}
		}

		public function load_files() {
		}

		public function add_translatable_nodes( $nodes_to_translate ) {

			$nodes_to_translate[ 'custom-woo-products' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-products' ),
				'fields'     => array(
					array(
						'field'       => 'sale_badge_text',
						'type'        => esc_html__( 'Custom Woo Products Grid: Set sale badge text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-categories' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-categories' ),
				'fields'     => array(
					array(
						'field'       => 'count_before_text',
						'type'        => esc_html__( 'Custom Woo Categories Grid: Count Before Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'count_after_text',
						'type'        => esc_html__( 'Custom Woo Categories Grid: Count After Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'desc_after_text',
						'type'        => esc_html__( 'Custom Woo Categories Grid: Trimmed After Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-taxonomy-tiles' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-taxonomy-tiles' ),
				'fields'     => array(
					array(
						'field'       => 'count_before_text',
						'type'        => esc_html__( 'Custom Woo Taxonomy Tiles: Count Before Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'count_after_text',
						'type'        => esc_html__( 'Custom Woo Taxonomy Tiles: Count After Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-single-attributes' ] = array(
				'conditions' => array( 'widgetType' => 'custom-single-attributes' ),
				'fields'     => array(
					array(
						'field'       => 'block_title',
						'type'        => esc_html__( 'Custom Woo Single Attributes: Title Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-builder-archive-sale-badge' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-builder-archive-sale-badge' ),
				'fields'     => array(
					array(
						'field'       => 'block_title',
						'type'        => esc_html__( 'Custom Woo Archive Sale Badge: Sale Badge Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-builder-archive-category-count' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-builder-archive-category-count' ),
				'fields'     => array(
					array(
						'field'       => 'archive_category_count_before_text',
						'type'        => esc_html__( 'Custom Woo Archive Category Count: Count Before Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-builder-archive-category-count' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-builder-archive-category-count' ),
				'fields'     => array(
					array(
						'field'       => 'archive_category_count_after_text',
						'type'        => esc_html__( 'Custom Woo Archive Category Count: Count After Text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-builder-products-navigation' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-builder-products-navigation' ),
				'fields'     => array(
					array(
						'field'       => 'prev_text',
						'type'        => esc_html__( 'Custom Woo Shop Products Navigation: The previous page link text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-builder-products-navigation' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-builder-products-navigation' ),
				'fields'     => array(
					array(
						'field'       => 'next_text',
						'type'        => esc_html__( 'Custom Woo Shop Products Navigation: The next page link text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-builder-products-pagination' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-builder-products-pagination' ),
				'fields'     => array(
					array(
						'field'       => 'prev_text',
						'type'        => esc_html__( 'Custom Woo Shop Products Pagination: The previous page link text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'custom-woo-builder-products-pagination' ] = array(
				'conditions' => array( 'widgetType' => 'custom-woo-builder-products-pagination' ),
				'fields'     => array(
					array(
						'field'       => 'next_text',
						'type'        => esc_html__( 'Custom Woo Shop Products Pagination: The next page link text', 'custom-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			return $nodes_to_translate;
		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

function custom_woo_builder_compatibility() {
	return Custom_Woo_Builder_Compatibility::get_instance();
}
