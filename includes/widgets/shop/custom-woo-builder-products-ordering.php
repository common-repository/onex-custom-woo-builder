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

class Custom_Woo_Builder_Products_Ordering extends Custom_Woo_Builder_Base {

	public function get_name() {
		return 'custom-woo-builder-products-ordering';
	}

	public function get_title() {
		return esc_html__( 'Products Ordering', 'custom-woo-builder' );
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

		$css_scheme     = apply_filters(
			'custom-woo-builder/products-ordering/css-scheme',
			array(
				'wrapper'          => '.elementor-custom-woo-builder-products-ordering',
				'ordering_wrapper' => '.elementor-custom-woo-builder-products-ordering .woocommerce-ordering',
				'select'           => '.elementor-custom-woo-builder-products-ordering .woocommerce-ordering select',
				'ordering_arrow'   => '.elementor-custom-woo-builder-products-ordering .woocommerce-ordering:before',
			)
		);
		$ordering_arrow = array( '' => esc_html__( 'None', 'custom-woo-builder' ) ) + $this->get_available_down_arrows_list();

		$this->start_controls_section(
			'section_ordering_select_style',
			array(
				'label' => esc_html__( 'Ordering Select', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ordering_select_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_responsive_control(
			'ordering_select_input_width',
			array(
				'label'      => esc_html__( 'Input Width', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
					'px',
				),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 50,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_wrapper'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_ordering_select_style' );

		$this->start_controls_tab(
			'tab_ordering_select_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'ordering_select_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ordering_select_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_ordering_select_focus',
			array(
				'label' => esc_html__( 'Focus', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'ordering_select_focus_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ordering_select_focus_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ordering_select_focus_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'ordering_select_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['select'],
				'separator'   => 'before'

			)
		);

		$this->add_control(
			'ordering_select_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'ordering_select_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_responsive_control(
			'ordering_select_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->add_responsive_control(
			'ordering_select_margin',
			array(
				'label'      => esc_html__( 'Margin', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ordering_select_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'custom-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrapper'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ordering_arrow_style',
			array(
				'label'      => esc_html__( 'Ordering Arrow', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'enable_ordering_arrow',
			array(
				'label'        => esc_html__( 'Show arrow in select', 'custom-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'ordering_arrow_icon',
			array(
				'type'         => Controls_Manager::SELECT,
				'label'        => esc_html__( 'Ordering Arrow', 'custom-woo-builder' ),
				'default'      => 'angle',
				'options'      => $ordering_arrow,
				'condition'    => array(
					'enable_ordering_arrow' => 'yes',
				),
				'prefix_class' => 'ordering-select-icon-'
			)
		);
		$this->add_control(
			'ordering_arrow_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'ordering_arrow_size',
			array(
				'label'      => esc_html__( 'Arrow Size', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ordering_arrow_top_gap',
			array(
				'label'      => esc_html__( 'Top Offset', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 9,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ordering_arrow_right_gap',
			array(
				'label'      => esc_html__( 'Right Offset', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

	}

	/**
	 * Return availbale arrows list
	 * @return [type] [description]
	 */
	public function get_available_down_arrows_list() {

		return apply_filters(
			'custom-woo-builder/product-ordering/select-arrow/icons',
			array(
				'angle'          => __( 'Angle', 'custom-woo-builder' ),
				'chevron'        => __( 'Chevron', 'custom-woo-builder' ),
				'angle-double'   => __( 'Angle Double', 'custom-woo-builder' ),
				'arrow'          => __( 'Arrow', 'custom-woo-builder' ),
				'caret'          => __( 'Caret', 'custom-woo-builder' ),
				'arrow-circle'   => __( 'Arrow Circle', 'custom-woo-builder' ),
				'chevron-circle' => __( 'Chevron Circle', 'custom-woo-builder' ),
				'caret-square'   => __( 'Caret Square', 'custom-woo-builder' ),
			)
		);

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		woocommerce_catalog_ordering();

		$this->__close_wrap();

	}
}
