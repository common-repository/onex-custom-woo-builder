<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Custom_Woo_Builder_Base extends Widget_Base {

	public $__context         = 'render';
	public $__processed_item  = false;
	public $__processed_index = 0;
	public $__temp_query      = null;
	public $__product_data    = false;

	public function __get_global_template( $name = null ) {

		$template = call_user_func( array( $this, sprintf( '__get_%s_template', $this->__context ) ) );

		if ( ! $template ) {
			$template = custom_woo_builder()->get_template( $this->get_name() . '/global/' . $name . '.php' );
		}

		return $template;
	}

	public function __get_render_template( $name = null ) {
		return custom_woo_builder()->get_template( $this->get_name() . '/render/' . $name . '.php' );
	}

	public function __get_edit_template( $name = null ) {
		return custom_woo_builder()->get_template( $this->get_name() . '/edit/' . $name . '.php' );
	}

	public function __get_global_looped_template( $name = null, $setting = null ) {

		$templates = array(
			'start' => $this->__get_global_template( $name . '-loop-start' ),
			'loop'  => $this->__get_global_template( $name . '-loop-item' ),
			'end'   => $this->__get_global_template( $name . '-loop-end' ),
		);

		call_user_func(
			array( $this, sprintf( '__get_%s_looped_template', $this->__context ) ), $templates, $setting
		);

	}

	public function __get_render_looped_template( $templates = array(), $setting = null ) {

		$loop = $this->get_settings( $setting );

		if ( empty( $loop ) ) {
			return;
		}

		if ( ! empty( $templates['start'] ) ) {
			include $templates['start'];
		}

		foreach ( $loop as $item ) {

			$this->__processed_item = $item;
			if ( ! empty( $templates['start'] ) ) {
				include $templates['loop'];
			}
			$this->__processed_index++;
		}

		$this->__processed_item = false;
		$this->__processed_index = 0;

		if ( ! empty( $templates['end'] ) ) {
			include $templates['end'];
		}

	}

	public function __get_edit_looped_template( $templates = array(), $setting = null ) {
		?>
		<# if ( settings.<?php echo $setting; ?> ) { #>
		<?php
			if ( ! empty( $templates['start'] ) ) {
				include $templates['start'];
			}
		?>
			<# _.each( settings.<?php echo $setting; ?>, function( item ) { #>
			<?php
				if ( ! empty( $templates['loop'] ) ) {
					include $templates['loop'];
				}
			?>
			<# } ); #>
		<?php
			if ( ! empty( $templates['end'] ) ) {
				include $templates['end'];
			}
		?>
		<# } #>
		<?php
	}

	public function __loop_item( $keys = array(), $format = '%s' ) {

		return call_user_func( array( $this, sprintf( '__%s_loop_item', $this->__context ) ), $keys, $format );

	}

	public function __edit_loop_item( $keys = array(), $format = '%s' ) {

		$settings = $keys[0];

		if ( isset( $keys[1] ) ) {
			$settings .= '.' . $keys[1];
		}

		ob_start();

		echo '<# if ( item.' . $settings . ' ) { #>';
		printf( $format, '{{{ item.' . $settings . ' }}}' );
		echo '<# } #>';

		return ob_get_clean();
	}

	public function __render_loop_item( $keys = array(), $format = '%s' ) {

		$item = $this->__processed_item;

		$key        = $keys[0];
		$nested_key = isset( $keys[1] ) ? $keys[1] : false;

		if ( empty( $item ) || ! isset( $item[ $key ] ) ) {
			return false;
		}

		if ( false === $nested_key || ! is_array( $item[ $key ] ) ) {
			$value = $item[ $key ];
		} else {
			$value = isset( $item[ $key ][ $nested_key ] ) ? $item[ $key ][ $nested_key ] : false;
		}

		if ( ! empty( $value ) ) {
			return sprintf( $format, $value );
		}

	}

	public function __glob_inc_if( $name = null, $settings = array() ) {

		$template = $this->__get_global_template( $name );

		call_user_func( array( $this, sprintf( '__%s_inc_if', $this->__context ) ), $template, $settings );

	}

	public function __render_inc_if( $file = null, $settings = array() ) {

		foreach ( $settings as $setting ) {
			$val = $this->get_settings( $setting );

			if ( ! empty( $val ) ) {
				include $file;
				return;
			}

		}

	}

	public function __edit_inc_if( $file = null, $settings = array() ) {

		$condition = null;
		$sep       = null;

		foreach ( $settings as $setting ) {
			$condition .= $sep . 'settings.' . $setting;
			$sep = ' || ';
		}

		?>

		<# if ( <?php echo $condition; ?> ) { #>

			<?php include $file; ?>

		<# } #>

		<?php
	}

	public function __open_wrap() {
		printf( '<div class="elementor-%s custom-woo-builder">', $this->get_name() );
	}

	public function __close_wrap() {
		echo '</div>';
	}

	public function __html( $setting = null, $format = '%s' ) {

		call_user_func( array( $this, sprintf( '__%s_html', $this->__context ) ), $setting, $format );

	}

	public function __get_html( $setting = null, $format = '%s' ) {

		ob_start();
		$this->__html( $setting, $format );
		return ob_get_clean();

	}

	public function __render_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$key     = $setting[1];
			$setting = $setting[0];
		}

		$val = $this->get_settings( $setting );

		if ( ! is_array( $val ) && '0' === $val ) {
			printf( $format, $val );
		}

		if ( is_array( $val ) && empty( $val[ $key ] ) ) {
			return '';
		}

		if ( ! is_array( $val ) && empty( $val ) ) {
			return '';
		}

		if ( is_array( $val ) ) {
			printf( $format, $val[ $key ] );
		} else {
			printf( $format, $val );
		}

	}

	public function __edit_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$setting = $setting[0] . '.' . $setting[1];
		}

		echo '<# if ( settings.' . $setting . ' ) { #>';
		printf( $format, '{{{ settings.' . $setting . ' }}}' );
		echo '<# } #>';
	}

	public function __set_editor_product() {

		if ( ! custom_woo_builder_integration()->in_elementor() && ! wp_doing_ajax() ) {
			return true;
		}

		global $post, $wp_query, $product;

		$this->__temp_query = $wp_query;


		if ( ! empty( $this->__product_data ) ) {
			$wp_query = $this->__product_data['query'];
			$post     = $this->__product_data['post'];
			$product  = $this->__product_data['product'];

			return true;

		}

		if ( 'product' === $post->post_type ) {

			$product = wc_get_product( $post );

			$this->__product_data = array(
				'query'   => $wp_query,
				'post'    => $post,
				'product' => $product,
			);

			custom_woo_builder_integration()->set_current_product( $this->__product_data );

			return true;

		}

		$sample_product = get_post_meta( $post->ID, '_sample_product', true );

		$args = array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'pending', 'draft', 'future' ),
			'posts_per_page' => 1,
		);

		if ( ! empty( $sample_product ) ) {
			$args['p'] = $sample_product;
		}

		$wp_query = new \WP_Query( $args );

		if ( $wp_query->have_posts() ) {
			foreach ( $wp_query->posts as $post ) {
				setup_postdata( $post );
				$product = wc_get_product( $post );
			}

			$this->__product_data = array(
				'query'   => $wp_query,
				'post'    => $post,
				'product' => $product,
			);

			custom_woo_builder_integration()->set_current_product( $this->__product_data );

			return true;

		} else {
			esc_html_e( 'Please add at least one product with "publish", "pending", "draft" or "future" status', 'custom-woo-builder' );
			return false;
		}

	}


	public function __reset_editor_product() {

		if ( ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) || wp_doing_ajax() ) {
			global $wp_query;
			$wp_query = $this->__temp_query;
			wp_reset_postdata();
		}

	}

}
