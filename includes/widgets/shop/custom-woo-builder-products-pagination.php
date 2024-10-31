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

class Custom_Woo_Builder_Products_Pagination extends Custom_Woo_Builder_Base {

	public function get_name() {
		return 'custom-woo-builder-products-pagination';
	}

	public function get_title() {
		return esc_html__( 'Products Pagination', 'custom-woo-builder' );
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
		$arrows_prev_list = array( '' => esc_html__( 'None', 'custom-woo-builder' ) ) + custom_woo_builder_tools()->get_available_prev_arrows_list();
		$arrows_next_list = array( '' => esc_html__( 'None', 'custom-woo-builder' ) ) + custom_woo_builder_tools()->get_available_next_arrows_list();

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Items', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'info_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Works only with main Query object.', 'custom-woo-builder' )
			)
		);
		$this->add_control(
			'prev_next',
			array(
				'label'        => esc_html__( 'Add the previous and next page links.', 'custom-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'prev_text',
			array(
				'label'       => esc_html__( 'The previous page link text', 'custom-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Previous', 'custom-woo-builder' ),
				'condition'   => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->add_control(
			'prev_icon',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'The next page link icon', 'custom-woo-builder' ),
				'default'   => 'fa fa-angle-left',
				'options'   => $arrows_prev_list,
				'condition' => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->add_control(
			'next_text',
			array(
				'label'       => esc_html__( 'The next page text', 'custom-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Next', 'custom-woo-builder' ),
				'condition'   => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->add_control(
			'next_icon',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'The next page link icon', 'custom-woo-builder' ),
				'default'   => 'fa fa-angle-right',
				'options'   => $arrows_next_list,
				'condition' => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'general_style',
			array(
				'label'      => esc_html__( 'General', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->add_control(
			'general_background_color',
			array(
				'label' => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'general_border',
				'label'       => esc_html__( 'Border', 'custom-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .custom-woo-builder-shop-pagination',
			)
		);
		$this->add_control(
			'general_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'general_shadow',
				'selector' => '{{WRAPPER}} .custom-woo-builder-shop-pagination',
			)
		);
		$this->add_responsive_control(
			'general_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'general_margin',
			array(
				'label'      => esc_html__( 'Margin', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'items_style',
			array(
				'label'      => esc_html__( 'Items', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->add_control(
			'items_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'custom-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination' => 'justify-content: {{VALUE}}',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_items_style' );
		$this->start_controls_tab(
			'items_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'items_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'items_hover',
			array(
				'label' => esc_html__( 'Hover', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'items_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'items_active',
			array(
				'label' => esc_html__( 'Current', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'items_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination span.page-numbers.current' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination span.page-numbers.current' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination span.page-numbers.current' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'items_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers',
				'exclude'  => array(
					'text_decoration'
				)
			)
		);
		$this->add_responsive_control(
			'items_min_width',
			array(
				'label'      => esc_html__( 'Item Min Width', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'items_margin',
			array(
				'label'       => esc_html__( 'Gap Between Items', 'custom-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 4,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers' => 'margin-left: calc( {{SIZE}}px / 2 ); margin-right: calc( {{SIZE}}px / 2 );',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'items_border',
				'label'       => esc_html__( 'Border', 'custom-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers:not(.dots)',
			)
		);
		$this->add_responsive_control(
			'items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		$this->section_prev_next_styles();

		$this->start_controls_section(
			'icons_style',
			array(
				'label'      => esc_html__( 'Prev/Next Icons', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->start_controls_tabs( 'tabs_icons_style' );
		$this->start_controls_tab(
			'icons_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'icons_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .custom-woo-builder-shop-pagination__arrow' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'icons_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .custom-woo-builder-shop-pagination__arrow' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'icons_hover',
			array(
				'label' => esc_html__( 'Hover', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'icons_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers:hover .custom-woo-builder-shop-pagination__arrow' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'icons_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers:hover .custom-woo-builder-shop-pagination__arrow' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'icons_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers:hover .custom-woo-builder-shop-pagination__arrow' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'items_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers .custom-woo-builder-shop-pagination__arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'icons_box_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 18,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers .custom-woo-builder-shop-pagination__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icons_border',
				'label'       => esc_html__( 'Border', 'custom-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .custom-woo-builder-shop-pagination .custom-woo-builder-shop-pagination__arrow',
			)
		);
		$this->add_responsive_control(
			'icons_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .custom-woo-builder-shop-pagination__arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap Between Text and Icon', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers .custom-woo-builder-shop-pagination__arrow.custom-arrow-prev' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers .custom-woo-builder-shop-pagination__arrow.custom-arrow-next' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return false;
		}

		$settings = $this->get_settings();

		$prev_next = isset( $settings['prev_next'] ) ? $settings['prev_next'] : '';
		$prev_next = filter_var( $prev_next, FILTER_VALIDATE_BOOLEAN );
		$prev_text = isset( $settings['prev_text'] ) ? $settings['prev_text'] : '';
		$next_text = isset( $settings['next_text'] ) ? $settings['next_text'] : '';
		$total     = wc_get_loop_prop( 'total_pages' );
		$current   = wc_get_loop_prop( 'current_page' );
		$base      = esc_url_raw( add_query_arg( 'product-page', '%#%', false ) );
		$format    = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );

		if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
			$format = '';
			$base   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
		}

		if ( $total <= 1 ) {
			return false;
		}

		$this->__context = 'render';

		$this->__open_wrap();

		if ( ! empty( $settings['prev_icon'] ) ) {
			$prev_text = $this->get_pagination_arrow( $settings['prev_icon'], 'prev' ) . $prev_text;
		}
		if ( ! empty( $settings['next_icon'] ) ) {
			$next_text .= $this->get_pagination_arrow( $settings['next_icon'], 'next' );
		}

		echo '<nav class="custom-woo-builder-shop-pagination">';
		echo paginate_links( array(
			'base'      => $base,
			'format'    => $format,
			'prev_next' => $prev_next,
			'prev_text' => $prev_text,
			'next_text' => $next_text,
			'current'   => max( 1, $current ),
			'total'     => $total,
			'type'      => 'plain',
			'end_size'  => 3,
			'mid_size'  => 3,
		) );
		echo '</nav>';

		$this->__close_wrap();

	}

	/**
	 * Return html for arrows in pagination
	 *
	 * @param string $icon
	 * @param string $arrow
	 *
	 * @return string
	 */
	public function get_pagination_arrow( $icon = '', $arrow = 'next' ) {

		$format = apply_filters(
			'custom-woo-builder/shop-pagination/arrows-format',
			'<i class="%1$s custom-arrow-%2$s custom-woo-builder-shop-pagination__arrow"></i>'
		);

		return sprintf( $format, $icon, $arrow );

	}

	public function section_prev_next_styles(){

		$this->start_controls_section(
			'prev_next_style',
			array(
				'label'      => esc_html__( 'Prev/Next', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->start_controls_tabs( 'tabs_prev_next_style' );
		$this->start_controls_tab(
			'prev_next_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'prev_next_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.next' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'prev_next_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.prev' => 'color: {{VALUE}}',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.next' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'prev_next_hover',
			array(
				'label' => esc_html__( 'Hover', 'custom-woo-builder' ),
			)
		);
		$this->add_control(
			'prev_next_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.prev:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.next:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'prev_next_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.next:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.prev:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'prev_next_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'prev_next_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.prev:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination a.page-numbers.next:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'prev_next_min_width',
			array(
				'label'      => esc_html__( 'Item Min Width', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.prev' => 'min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.next' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'prev_next_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'prev_next_border',
				'label'       => esc_html__( 'Border', 'custom-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.prev,' . '{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.next',
			)
		);
		$this->add_responsive_control(
			'prev_next_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .custom-woo-builder-shop-pagination .page-numbers.next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

	}
}
