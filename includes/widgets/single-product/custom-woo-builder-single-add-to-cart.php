<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Custom_Woo_Builder_Single_Add_To_Cart extends Custom_Woo_Builder_Base {

	public function get_name() {
		return 'custom-single-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Single Add to Cart', 'custom-woo-builder' );
	}

	public function get_icon() {
		return 'eicon-wordpress';
	}

	public function get_script_depends() {
		return array( 'wc-add-to-cart', 'wc-add-to-cart-variation', 'wc-single-product' );
	}

	public function get_categories() {
		return array( 'custom-woo-builder' );
	}

	public function show_in_panel() {
		return custom_woo_builder()->documents->is_document_type( 'single' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'custom-woo-builder/custom-single-add-to-cart/css-scheme',
			array(
				'button'          => '.custom-woo-builder .single_add_to_cart_button.button',
				'qty'             => '.custom-woo-builder .qty',
				'qty_input'       => '.elementor-custom-single-add-to-cart .cart .quantity',
				'description'     => '.elementor-custom-single-add-to-cart .woocommerce-variation-description',
				'price'           => '.elementor-custom-single-add-to-cart .woocommerce-variation-price .price',
				'currency'        => '.elementor-custom-single-add-to-cart .woocommerce-Price-currencySymbol',
				'in_stock'        => '.elementor-custom-single-add-to-cart .in-stock',
				'out_stock'       => '.elementor-custom-single-add-to-cart .out-of-stock',
				'availability'    => '.elementor-custom-single-add-to-cart .stock',
				'reset_button'    => '.elementor-custom-single-add-to-cart .reset_variations',
				'variations_wrap' => '.elementor-custom-single-add-to-cart .variations',
				'select'          => '.elementor-custom-single-add-to-cart .variations select',
				'label'           => '.elementor-custom-single-add-to-cart .variations .label label'
			)
		);

		$this->start_controls_section(
			'section_add_to_cart_style',
			array(
				'label' => esc_html__( 'Button', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'add_to_cart_typography',
				'scheme'      => Scheme_Typography::TYPOGRAPHY_4,
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
				'placeholder' => '1px',
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => esc_html__( 'Button Width', 'custom-woo-builder' ),
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
					'size' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_add_to_cart_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'add_to_cart_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}};',
				),

			)
		);

		$this->add_control(
			'add_to_cart_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_hover',
			array(
				'label' => esc_html__( 'Hover', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'add_to_cart_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'add_to_cart_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'add_to_cart_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_disabled',
			array(
				'label' => esc_html__( 'Disabled', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'add_to_cart_disabled_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '.disabled' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_background_disabled_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '.disabled' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_to_cart_disabled_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'add_to_cart_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . '.disabled'       => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['button'] . '.disabled:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'add_to_cart_disabled_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . '.disabled',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'add_to_cart_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
				'separator'   => 'before'

			)
		);

		$this->add_control(
			'add_to_cart_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'add_to_cart_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
			array(
				'label'      => esc_html__( 'Margin', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_qty_style',
			array(
				'label' => esc_html__( 'Quantity Input', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qty_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['qty'],
			)
		);

		$this->add_control(
			'qty_display',
			array(
				'label'     => esc_html__( 'Quantity Input Display', 'custom-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'inline-block' => esc_html__( 'Inline', 'custom-woo-builder' ),
					'block'        => esc_html__( 'Block', 'custom-woo-builder' ),
				),
				'default'   => 'inline-block',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty_input'] => 'display: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['button']    => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'qty_input_width',
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
					'size' => 70,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty_input'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_qty_style' );

		$this->start_controls_tab(
			'tab_qty_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'qty_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qty_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_qty_focus',
			array(
				'label' => esc_html__( 'Focus', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'qty_focus_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] . ':focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qty_background_focus_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] . ':focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qty_focus_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] . ':focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'qty_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['qty'],
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'qty_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'qty_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['qty'],
			)
		);

		$this->add_responsive_control(
			'qty_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'qty_margin',
			array(
				'label'      => esc_html__( 'Margin', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty_input'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description_style',
			array(
				'label' => esc_html__( 'Description', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['description'],
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'description_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'description_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['description'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price_style',
			array(
				'label' => esc_html__( 'Price', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['price'],
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'price_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'price_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['price'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_currency_style',
			array(
				'label'     => esc_html__( 'Currency Symbol', 'custom-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'price_currency_color',
			array(
				'label'     => esc_html__( 'Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['currency'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_currency_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['currency'],
			)
		);

		$this->add_control(
			'price_currency_vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'custom-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'baseline'    => esc_html__( 'Baseline', 'custom-woo-builder' ),
					'top'         => esc_html__( 'Top', 'custom-woo-builder' ),
					'middle'      => esc_html__( 'Middle', 'custom-woo-builder' ),
					'bottom'      => esc_html__( 'Bottom', 'custom-woo-builder' ),
					'sub'         => esc_html__( 'Sub', 'custom-woo-builder' ),
					'super'       => esc_html__( 'Super', 'custom-woo-builder' ),
					'text-top'    => esc_html__( 'Text Top', 'custom-woo-builder' ),
					'text-bottom' => esc_html__( 'Text Bottom', 'custom-woo-builder' ),
				),
				'default'   => 'baseline',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['currency'] => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_availability_style',
			array(
				'label' => esc_html__( 'Availability', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_availability' );

		$this->start_controls_tab(
			'tab_availability_in_stock',
			array(
				'label' => esc_html__( 'In Stock', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'availability_in_stock_color',
			array(
				'label'     => esc_html__( 'Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['in_stock'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_availability_out_of_stock',
			array(
				'label' => esc_html__( 'Out of Stock', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'availability_out_of_stock_color',
			array(
				'label'     => esc_html__( 'Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['out_stock'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'availability_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['availability'],
			)
		);

		$this->add_responsive_control(
			'availability_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['availability'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'availability_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['availability'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_reset_button_style',
			array(
				'label' => esc_html__( 'Reset Button', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'reset_button_typography',
				'scheme'      => Scheme_Typography::TYPOGRAPHY_4,
				'selector'    => '{{WRAPPER}} ' . $css_scheme['reset_button'],
				'placeholder' => '1px',
			)
		);

		$this->start_controls_tabs( 'tabs_reset_button_style' );

		$this->start_controls_tab(
			'tab_reset_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'reset_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] => 'color: {{VALUE}};',
				),

			)
		);

		$this->add_control(
			'reset_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_reset_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'reset_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'reset_button_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'reset_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'reset_button_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'reset_button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['reset_button'],
				'separator'   => 'before'

			)
		);

		$this->add_control(
			'reset_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'reset_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->add_responsive_control(
			'reset_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['reset_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_variation_select_style',
			array(
				'label' => esc_html__( 'Variations Select', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_select_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_responsive_control(
			'variation_select_input_width',
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
					'size' => 70,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['variations_wrap'] => 'max-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_variation_select_style' );

		$this->start_controls_tab(
			'tab_variation_select_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'variation_select_color',
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
			'variation_select_background_color',
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
			'tab_variation_select_focus',
			array(
				'label' => esc_html__( 'Focus', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'variation_select_focus_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_select_focus_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_select_focus_border_color',
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
				'name'        => 'variation_select_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['select'],
				'separator'   => 'before'

			)
		);

		$this->add_control(
			'variation_select_border_radius',
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
				'name'     => 'variation_select_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_responsive_control(
			'variation_select_padding',
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
			'variation_select_margin',
			array(
				'label'      => esc_html__( 'Margin', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_variation_title_style',
			array(
				'label' => esc_html__( 'Variations Title', 'custom-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
			)
		);

		$this->add_control(
			'variation_title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'variation_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'variation_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {

		$this->__context = 'render';

		if ( true === $this->__set_editor_product() ) {
			$this->__open_wrap();
			include $this->__get_global_template( 'index' );
			$this->__close_wrap();
			$this->__reset_editor_product();
		}

	}
}
