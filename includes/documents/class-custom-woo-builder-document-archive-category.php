<?php

use Elementor\Controls_Manager;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Custom_Woo_Builder_Archive_Document_Category extends Custom_Woo_Builder_Document_Base {

	public function get_name() {
		return 'custom-woo-builder-category';
	}

	public static function get_title() {
		return __( 'Custom Woo Category Template', 'custom-woo-builder' );
	}

	protected function _register_controls() {

		parent::_register_controls();

		$this->start_controls_section(
			'section_template_category_settings',
			array(
				'label'      => esc_html__( 'Template Settings', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_SETTINGS,
				'show_label' => false,
			)
		);

		$this->add_control(
			'use_custom_template_category_columns',
			array(
				'label'        => esc_html__( 'Use custom columns count', 'custom-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_responsive_control(
			'template_category_columns_count',
			array(
				'label'           => esc_html__( 'Template Columns', 'custom-woo-builder' ),
				'type'            => Controls_Manager::NUMBER,
				'min'             => 1,
				'max'             => 6,
				'step'            => 1,
				'condition'       => array(
					'use_custom_template_category_columns' => 'yes'
				)
			)
		);

		$this->add_responsive_control(
			'template_category_columns_horizontal_gutter',
			array(
				'label'      => esc_html__( 'Template Columns Horizontal Gutter (px)', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'.woocommerce {{WRAPPER}} ' . '.products.custom-woo-builder-categories--columns .product.product-category' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'.woocommerce {{WRAPPER}} ' . '.products.custom-woo-builder-categories--columns'                         => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
				),
				'condition'  => array(
					'use_custom_template_category_columns' => 'yes'
				)
			)
		);

		$this->add_responsive_control(
			'template_category_columns_vertical_gutter',
			array(
				'label'      => esc_html__( 'Template Columns Vertical Gutter (px)', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'.woocommerce ' . '.products.custom-woo-builder-categories--columns .product.product-category' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				),
				'condition'  => array(
					'use_custom_template_category_columns' => 'yes'
				)
			)
		);

		$this->end_controls_section();

	}

	public function save( $data = [] ) {
		return $this->save_archive_templates( $data );
	}

	public function get_wp_preview_url() {

		$main_post_id = $this->get_main_id();

		$product_category = $this->query_first_category();

		return add_query_arg(
			array(
				'preview_nonce'    => wp_create_nonce( 'post_preview_' . $main_post_id ),
				'custom_woo_template' => $main_post_id,
			),
			esc_url( get_category_link( $product_category ) )
		);

	}

	public function get_preview_as_query_args() {

		custom_woo_builder()->documents->set_current_type( $this->get_name() );

		$args = array();

		$product_category = $this->query_first_category();

		if ( ! empty( $product_category ) ) {

			$args = array(
				'posts_per_page' => - 1,
				'post_type'      => 'product',
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $product_category
					)
				)
			);

		}

		return $args;
	}

}