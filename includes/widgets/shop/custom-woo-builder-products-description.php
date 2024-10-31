<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Custom_Woo_Builder_Products_Description extends Custom_Woo_Builder_Base {

	public function get_name() {
		return 'custom-woo-builder-products-description';
	}

	public function get_title() {
		return esc_html__( 'Products Description', 'custom-woo-builder' );
	}

	public function get_icon() {
		return 'eicon-wordpress';
	}

	public function get_script_depends() {
		return array();
	}

	public function get_categories() {
		return array( 'custom-woo-builder' );
	}

	public function show_in_panel() {
		return custom_woo_builder()->documents->is_document_type( 'shop' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'custom-woo-builder/products-result-count/css-scheme',
			array(
				'term_description'    => '.elementor-custom-woo-builder-products-description .term-description',
				'archive_description' => '.elementor-custom-woo-builder-products-description .page-description',
			)
		);

		$this->start_controls_section(
			'section_products_description_style',
			array(
				'label' => __( 'Products Description', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'products_description_text_color',
			array(
				'label'     => __( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['term_description']    => 'color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['archive_description'] => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'products_description_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['term_description'] . ',' . '{{WRAPPER}} ' . $css_scheme['archive_description'],
			)
		);
		$this->add_responsive_control(
			'products_description_align',
			array(
				'label'     => __( 'Alignment', 'custom-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => [
					'{{WRAPPER}} ' . $css_scheme['term_description']    => 'text-align: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['archive_description'] => 'text-align: {{VALUE}};',
				],
			)
		);

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		woocommerce_taxonomy_archive_description();
		woocommerce_product_archive_description();

		$this->__close_wrap();

	}
}
