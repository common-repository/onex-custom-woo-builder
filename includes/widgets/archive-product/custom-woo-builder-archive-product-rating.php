<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Custom_Woo_Builder_Archive_Product_Rating extends Widget_Base {

	private $source = false;

	public function get_name() {
		return 'custom-woo-builder-archive-product-rating';
	}

	public function get_title() {
		return esc_html__( 'Rating', 'custom-woo-builder' );
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
			'custom-woo-builder/custom-single-rating/css-scheme',
			array(
				'rating' => '.custom-woo-product-rating',
				'stars'  => '.product-rating__content',
			)
		);

		$this->start_controls_section(
			'section_archive_rating_styles',
			array(
				'label'      => esc_html__( 'Rating', 'custom-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'archive_rating_icon',
			array(
				'label'   => esc_html__( 'Rating Icon', 'custom-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'customwoo-front-icon-rating-1',
				'options' => custom_woo_builder_tools()->get_available_rating_icons_list(),
			)
		);

		$this->add_responsive_control(
			'archive_stars_font_size',
			array(
				'label'      => esc_html__( 'Font Size (px)', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' .product-rating__icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_archive_stars_styles' );

		$this->start_controls_tab(
			'tab_archive_stars_all',
			array(
				'label' => esc_html__( 'All', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'stars_archive_color_all',
			array(
				'label'     => esc_html__( 'Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#a1a2a4',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' .product-rating__icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_archive_stars_rated',
			array(
				'label' => esc_html__( 'Rated', 'custom-woo-builder' ),
			)
		);

		$this->add_control(
			'archive_stars_color_rated',
			array(
				'label'     => esc_html__( 'Color', 'custom-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fdbc32',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' > .product-rating__icon.active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'archive_stars_space_between',
			array(
				'label'      => esc_html__( 'Space Between Stars (px)', 'custom-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' .product-rating__icon + .product-rating__icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'archive_stars_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'custom-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} ' . $css_scheme['rating'] => 'text-align: {{VALUE}};',
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

	public static function render_callback( $settings = array() ) {

		if ( ! isset( $settings['archive_rating_icon'] ) ) {
			$settings['archive_rating_icon'] = 'customwoo-front-icon-rating-1';
		}

		$rating = custom_woo_builder_template_functions()->get_product_custom_rating( $settings['archive_rating_icon'] );

		if ( false !== $rating ) {
			echo '<div class="custom-woo-builder-archive-product-rating">';
			echo '<div class="custom-woo-product-rating">';
			echo $rating;
			echo '</div>';
			echo '</div>';
		}
	}

	protected function render() {

		$settings = $this->get_settings();

		$macros_settings = array(
			'archive_rating_icon' => $settings['archive_rating_icon'],
		);

		if ( custom_woo_builder_tools()->is_builder_content_save() ) {
			echo custom_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings );
		}

	}

}
