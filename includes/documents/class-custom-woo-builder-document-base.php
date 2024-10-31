<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Custom_Woo_Builder_Document_Base extends Elementor\Core\Base\Document {

	public $first_product = null;
	public $first_category = null;

	public function get_name() {
		return 'custom-woo-builder-archive-document';
	}

	public function query_first_product() {

		if ( null !== $this->first_product ) {
			return $this->first_product;
		}

		$args = array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'pending', 'draft', 'future' ),
			'posts_per_page' => 1,
		);

		$sample_product = get_post_meta( $this->get_main_id(), '_sample_product', true );

		if ( $sample_product ) {
			$args['p'] = $sample_product;
		}

		$wp_query = new WP_Query( $args );

		if ( ! $wp_query->have_posts() ) {
			return false;
		}

		$post = $wp_query->posts;

		return $this->first_product = $post[0]->ID;

	}

	public function query_first_category() {

		if ( null !== $this->first_category ) {
			return $this->first_category;
		}

		$product_categories = get_categories( array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'pad_counts'   => false,
			'hierarchical' => 1,
			'hide_empty'   => false
		) );


		if ( ! empty( $product_categories ) ) {
			$product_category = $product_categories[0];
		}


		return $this->first_category = $product_category->term_id;

	}

	public function save_template_item_to_meta( $post_id ) {

		$content = Elementor\Plugin::instance()->frontend->get_builder_content( $post_id, false );
		$content = preg_replace( '/<style>.*?<\/style>/', '', $content );

		update_post_meta( $post_id, '_custom_woo_builder_content', $content );

	}

	public function save_archive_templates( $data = [] ){
		if ( ! $this->is_editable_by_current_user() || empty( $data ) ) {
			return false;
		}

		if ( ! empty( $data['settings'] ) ) {
			if ( Elementor\DB::STATUS_AUTOSAVE === $data['settings']['post_status'] ) {
				if ( ! defined( 'DOING_AUTOSAVE' ) ) {
					define( 'DOING_AUTOSAVE', true );
				}
			}

			$this->save_settings( $data['settings'] );

			$this->post = get_post( $this->post->ID );
		}

		if ( ! empty( $data['elements'] ) ) {
			$this->save_elements( $data['elements'] );
		}

		$this->save_template_type();

		$this->save_version();

		$this->save_template_item_to_meta( $this->post->ID );

		$post_css = new Elementor\Core\Files\CSS\Post( $this->post->ID );
		$post_css->update();

		return true;
	}

	public function get_elements_raw_data( $data = null, $with_html_content = false ) {
		custom_woo_builder()->documents->switch_to_preview_query();

		$editor_data = parent::get_elements_raw_data( $data, $with_html_content );

		custom_woo_builder()->documents->restore_current_query();

		return $editor_data;
	}

	public function render_element( $data ) {

		custom_woo_builder()->documents->switch_to_preview_query();

		$render_html = parent::render_element( $data );

		custom_woo_builder()->documents->restore_current_query();

		return $render_html;

	}

	public function get_elements_data( $status = 'publish' ) {

		if ( ! isset( $_GET[ custom_woo_builder_post_type()->slug() ] ) || ! isset( $_GET['preview'] ) ) {
			return parent::get_elements_data( $status );
		}

		custom_woo_builder()->documents->switch_to_preview_query();

		$elements = parent::get_elements_data( $status );

		custom_woo_builder()->documents->restore_current_query();

		return $elements;
	}

}