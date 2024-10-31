<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Custom_Woo_Builder_Document extends Custom_Woo_Builder_Document_Base {

	public function get_name() {
		return 'custom-woo-builder';
	}

	public static function get_title() {
		return __( 'Custom Woo Template Single', 'custom-woo-builder' );
	}

	public function get_wp_preview_url() {

		$main_post_id   = $this->get_main_id();
		$sample_product = get_post_meta( $main_post_id, '_sample_product', true );

		if ( ! $sample_product ) {
			$sample_product = $this->query_first_product();
		}

		$product_id = $sample_product;

		return add_query_arg(
			array(
				'preview_nonce'    => wp_create_nonce( 'post_preview_' . $main_post_id ),
				'custom_woo_template' => $main_post_id,
			),
			get_permalink( $product_id )
		);

	}

	public function get_preview_as_query_args() {

		custom_woo_builder()->documents->set_current_type( $this->get_name() );

		$args    = array();
		$product = $this->query_first_product();

		if ( ! empty( $product ) ) {

			$args = array(
				'post_type' => 'product',
				'p'         => $product,
			);

		}

		return $args;

	}

}
