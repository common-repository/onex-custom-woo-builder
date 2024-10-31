<?php

namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Custom_Woo_Builder_Products_Notices extends Custom_Woo_Builder_Base {

	public function get_name() {
		return 'custom-woo-builder-products-notices';
	}

	public function get_title() {
		return esc_html__( 'Products Notices', 'custom-woo-builder' );
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

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		if ( !custom_woo_builder_integration()->in_elementor() ) {
			wc_print_notices();
		}

		$this->__close_wrap();

	}
}
