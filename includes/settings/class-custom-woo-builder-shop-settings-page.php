<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Woo_Builder_Shop_Settings_Page extends WC_Settings_Page {

	public function __construct() {

		$this->id    = custom_woo_builder_shop_settings()->key;
		$this->label = __( 'Custom Woo Builder', 'custom-woo-builder' );

		parent::__construct();
	}

	public function get_sections() {
		$sections = array(
			'' => __( 'General', 'custom-woo-builder' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	public function output() {
		global $current_section;
		$settings = $this->get_settings( $current_section );

		WC_Admin_Settings::output_fields( $settings );
	}

	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

		WC_Admin_Settings::save_fields( $settings );

	}

	public function get_settings( $current_section = '' ) {
		global $current_section;

		$settings = array(
			array(
				'title' => __( 'General', 'custom-woo-builder' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'general_options',
			),

			array(
				'title'    => __( 'Archive widgets render method', 'custom-woo-builder' ),
				'desc'     => __( 'Select widgets render method for archive product and archive category templates', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[widgets_render_method]',
				'default'  => 'macros',
				'type'     => 'custom_woo_select_render_method_field',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'general_options',
			),

			array(
				'title' => __( 'Shop Page', 'custom-woo-builder' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'shop_options',
			),

			array(
				'title'   => __( 'Custom Shop Page', 'custom-woo-builder' ),
				'desc'    => __( 'Enable custom product shop page', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[custom_shop_page]',
				'default' => '',
				'type'    => 'checkbox',
			),

			array(
				'title'    => __( 'Products Shop Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global products shop template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[shop_template]',
				'doc_type' => 'shop',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'shop_options',
			),

			array(
				'title' => __( 'Single Product', 'custom-woo-builder' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'single_options',
			),

			array(
				'title'   => __( 'Custom Single Product', 'custom-woo-builder' ),
				'desc'    => __( 'Enable custom single product page', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[custom_single_page]',
				'default' => '',
				'type'    => 'checkbox',
			),

			array(
				'title'    => __( 'Single Product Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global single product template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[single_template]',
				'doc_type' => 'single',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'single_options',
			),

			array(
				'title' => __( 'Products Archive', 'custom-woo-builder' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'archive_options',
			),

			array(
				'title'   => __( 'Custom Products Archive', 'custom-woo-builder' ),
				'desc'    => __( 'Enable custom product archive page', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[custom_archive_page]',
				'default' => '',
				'type'    => 'checkbox',
			),

			array(
				'title'    => __( 'Products Archive Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global products archive template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[archive_template]',
				'doc_type' => 'archive',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'title'    => __( 'Search Page Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global cross search page template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[search_template]',
				'doc_type' => 'archive',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'title'    => __( 'Products Shortcode Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global products shortcode template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[shortcode_template]',
				'doc_type' => 'archive',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'title'    => __( 'Related and Up Sells Products Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global related products template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[related_template]',
				'doc_type' => 'archive',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'title'    => __( 'Cross Sells Products Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global cross sells products template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[cross_sells_template]',
				'doc_type' => 'archive',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'archive_options',
			),

			array(
				'title' => __( 'Categories Archive', 'custom-woo-builder' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'categories_options',
			),

			array(
				'title'   => __( 'Custom Categories Archive', 'custom-woo-builder' ),
				'desc'    => __( 'Enable custom categories archive page', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[custom_archive_category_page]',
				'default' => '',
				'type'    => 'checkbox',
			),

			array(
				'title'    => __( 'Categories Archive Template', 'custom-woo-builder' ),
				'desc'     => __( 'Select template to use it as global categories archive template', 'custom-woo-builder' ),
				'id'       => custom_woo_builder_shop_settings()->options_key . '[category_template]',
				'doc_type' => 'category',
				'default'  => '',
				'type'     => 'custom_woo_select_template',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'categories_options',
			),

			array(
				'title' => __( 'Other Options', 'custom-woo-builder' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'other_options',
			),

			array(
				'title'   => __( 'Use Native Templates', 'custom-woo-builder' ),
				'desc'    => __( 'Force use native WooCommerce templates instead of rewritten in theme', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[use_native_templates]',
				'default' => '',
				'type'    => 'checkbox',
			),

			array(
				'title'   => __( 'Number related products to show', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[related_products_per_page]',
				'type'    => 'number',
				'default' => 4,
				'step'    => 1,
				'min'     => 1,
				'max'     => '',
				'std'     => 10,
			),

			array(
				'title'   => __( 'Number up sells products to show', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[up_sells_products_per_page]',
				'type'    => 'number',
				'default' => 4,
				'step'    => 1,
				'min'     => 1,
				'max'     => '',
				'std'     => 10,
			),

			array(
				'title'   => __( 'Number cross sells products to show', 'custom-woo-builder' ),
				'id'      => custom_woo_builder_shop_settings()->options_key . '[cross_sells_products_per_page]',
				'type'    => 'number',
				'default' => 4,
				'step'    => 1,
				'min'     => 1,
				'max'     => '',
				'std'     => 10,
			),

			array(
				'type' => 'sectionend',
				'id'   => 'other_options',
			),
		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}
}
