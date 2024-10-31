<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Custom_Woo_Builder_Shop_Settings' ) ) {

	class Custom_Woo_Builder_Shop_Settings extends Custom_Woo_Builder_Settings {

		private static $instance = null;

		public $options_key = 'custom_woo_builder';

		public function init() {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'register_woo_settings_page' ) );
			add_action( 'woocommerce_admin_field_custom_woo_select_template', array( $this, 'select_template_field' ) );
			add_action( 'woocommerce_admin_field_custom_woo_select_render_method_field', array(
				$this,
				'select_render_method_field'
			) );
		}

		public function get( $name, $default = false ) {

			if ( empty( $this->settings ) ) {
				$this->settings = get_option( $this->options_key, array() );
			}

			return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : $default;

		}

		public function select_template_field( $value ) {

			$doc_type       = isset( $value['doc_type'] ) ? $value['doc_type'] : 'single';
			$templates      = custom_woo_builder_post_type()->get_templates_list( $doc_type );
			$options        = get_option( $this->options_key );
			$current_option = str_replace( array( $this->options_key, '[', ']' ), '', $value['id'] );

			?>
					<tr valign="top" class="single_select_page">
					<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ); ?></th>
					<td class="forminp">
						<select style="<?php echo $value['css']; ?>" id="<?php echo $value['id'] ?>" name="<?php echo $value['id'] ?>" class="<?php echo $value['class']; ?>" data-placeholder="<?php esc_attr_e( 'Select a template&hellip;', 'custom-woo-builder' ); ?>">
							<option value="default"><?php echo __( 'Default', 'custom-woo-builder' ) ?></option><?php
				foreach ( $templates as $template ) {
					printf( '<option value="%1$s" ' . selected( $options[ $current_option ], $template->ID, true ) . '>%2$s</option>', $template->ID, $template->post_title );
				}
				?></select><?php printf( '<br><span class="description">%s</span>', $value['desc'] ); ?></td>
			<?php
		}

		public function select_render_method_field( $value ) {

			$options        = get_option( $this->options_key );
			$current_option = str_replace( array( $this->options_key, '[', ']' ), '', $value['id'] );
			$default        = isset( $value['default'] ) ? $value['default'] : '';
			$option_val     = isset( $options[ $current_option ] ) ? $options[ $current_option ] : $default;

			?>
					<tr valign="top" class="render_method">
					<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ); ?></th>
					<td class="forminp">
						<select style="<?php echo $value['css']; ?>" id="<?php echo $value['id'] ?>" name="<?php echo $value['id'] ?>" class="<?php echo $value['class']; ?>" data-placeholder="<?php esc_attr_e( 'Select render method', 'custom-woo-builder' ); ?>">
							<option value="macros" <?php selected( $option_val, 'macros', true ) ?> ><?php echo __( 'Macros', 'custom-woo-builder' ) ?></option>
							<option value="elementor"<?php selected( $option_val, 'elementor', true ) ?>><?php echo __( 'Elementor Default', 'custom-woo-builder' ) ?></option><?php
				?></select><?php printf( '<br><span class="description">%s</span>', $value['desc'] ); ?></td>
			<?php
		}

		public function register_woo_settings_page( $settings ) {

			require custom_woo_builder()->plugin_path( 'includes/settings/class-custom-woo-builder-shop-settings-page.php' );
			$settings[ $this->key ] = new Custom_Woo_Builder_Shop_Settings_Page();

			return $settings;

		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}

}

function custom_woo_builder_shop_settings() {
	return Custom_Woo_Builder_Shop_Settings::get_instance();
}
