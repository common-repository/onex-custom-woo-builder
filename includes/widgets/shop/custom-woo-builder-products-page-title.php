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

class Custom_Woo_Builder_Products_Page_Title extends Custom_Woo_Builder_Base {

	public function get_name() {
		return 'custom-woo-builder-products-page-title';
	}

	public function get_title() {
		return esc_html__( 'Products Page Title', 'custom-woo-builder' );
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
			'custom-woo-builder/products-page-title/css-scheme',
			array(
				'page_title' => '.woocommerce-products-header__title.page-title',
			)
		);

		$this->start_controls_section(
			'section_page_title_content',
			array(
				'label' => __( 'Page Title', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'page_title_tag',
			array(
				'label'   => esc_html__( 'Tag', 'custom-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => array(
					'div'  => esc_html__( 'DIV', 'custom-woo-builder' ),
					'h1'   => esc_html__( 'H1', 'custom-woo-builder' ),
					'h2'   => esc_html__( 'H2', 'custom-woo-builder' ),
					'h3'   => esc_html__( 'H3', 'custom-woo-builder' ),
					'h4'   => esc_html__( 'H4', 'custom-woo-builder' ),
					'h5'   => esc_html__( 'H5', 'custom-woo-builder' ),
					'h6'   => esc_html__( 'H6', 'custom-woo-builder' ),
					'span' => esc_html__( 'SPAN', 'custom-woo-builder' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_page_title_style',
			array(
				'label' => __( 'Page Title', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'page_title_text_color',
			array(
				'label'     => __( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['page_title'] => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'page_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['page_title'],
			)
		);
		$this->add_responsive_control(
			'page_title_align',
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
					'{{WRAPPER}} ' . $css_scheme['page_title'] => 'text-align: {{VALUE}};',
				],
			)
		);

	}

	protected function render() {

		$this->__context = 'render';

		$settings = $this->get_settings();

		$tag = $settings['page_title_tag'];

		$this->__open_wrap();

		echo '<' . $tag . ' class="woocommerce-products-header__title page-title">';
		woocommerce_page_title();
		echo '</' . $tag . '>';

		$this->__close_wrap();

	}
}
