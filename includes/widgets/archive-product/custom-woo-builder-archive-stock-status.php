<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Custom_Woo_Builder_Archive_Stock_Status extends Widget_Base {

	private $source = false;

	public function get_name() {
		return 'custom-woo-builder-archive-stock-status';
	}

	public function get_title() {
		return esc_html__( 'Stock Status', 'custom-woo-builder' );
	}

	public function get_icon() {
		return 'eicon-wordpress';
	}

	public function get_categories() {
		return array( 'custom-woo-builder' );
	}

	public function show_in_panel() {
		return custom_woo_builder()->documents->is_document_type( 'archive' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'custom-woo-builder/custom-archive-stock-status/css-scheme',
			array(
				'stock'        => '.custom-woo-builder-archive-product-stock-status .stock',
				'in_stock'     => '.custom-woo-builder-archive-product-stock-status .in-stock',
				'out_of_stock' => '.custom-woo-builder-archive-product-stock-status .out-of-stock',
			)
		);

		$this->start_controls_section(
			'section_stock_style',
			array(
				'label'      => esc_html__( 'Stock Status', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stock_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['stock'],
			)
		);

		$this->start_controls_tabs( 'stock_style_tabs' );

		$this->start_controls_tab(
			'in_stock_styles',
			array(
				'label' => esc_html__( 'In Stock', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'in_stock_color',
			array(
				'label' => esc_html__( 'In Stock Color', 'custom-woo-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['in_stock'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'out_of_stock_styles',
			array(
				'label' => esc_html__( 'Out Of Stock', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'out_of_stock_color',
			array(
				'label' => esc_html__( 'Out Of Stock Color', 'custom-woo-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['out_of_stock'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'stock_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'custom-woo-builder' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'custom-woo-builder' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['stock'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Returns CSS selector for nested element
	 *
	 * @param  [type] $el [description]
	 *
	 * @return [type]     [description]
	 */
	public function css_selector( $el = null ) {
		return sprintf( '{{WRAPPER}} .%1$s %2$s', $this->get_name(), $el );
	}

	public static function render_callback() {

		echo '<div class="custom-woo-builder-archive-product-stock-status">';
		echo custom_woo_builder_template_functions()->get_product_stock_status();
		echo '</div>';

	}

	protected function render() {

		if ( custom_woo_builder_tools()->is_builder_content_save() ) {
			echo custom_woo_builder()->parser->get_macros_string( $this->get_name() );
		} else {
			echo self::render_callback();
		}

	}

}
