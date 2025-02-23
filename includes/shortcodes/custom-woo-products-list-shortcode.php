<?php

class Custom_Woo_Products_List_Shortcode extends Custom_Woo_Builder_Shortcode_Base {

	public function get_tag() {
		return 'custom-woo-products-list';
	}

	public function get_atts() {

		return apply_filters( 'custom-woo-builder/shortcodes/custom-woo-products-list/atts', array(
			'products_layout' => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Layout', 'custom-woo-builder' ),
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Image Left', 'custom-woo-builder' ),
					'right' => esc_html__( 'Image Right', 'custom-woo-builder' ),
					'top'   => esc_html__( 'Image Top', 'custom-woo-builder' ),
				),
			),
			'number'          => array(
				'type'    => 'number',
				'label'   => esc_html__( 'Products Number', 'custom-woo-builder' ),
				'default' => 3,
				'min'     => 1,
				'max'     => 1000,
				'step'    => 1,
			),
			'products_query'  => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Query products by', 'custom-woo-builder' ),
				'default' => 'all',
				'options' => $this->get_products_query_type()
			),
			'products_ids'    => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Set comma separated IDs list (10, 22, 19 etc.)', 'custom-woo-builder' ),
				'label_block'=> true,
				'default'   => '',
				'condition' => array(
					'products_query' => array( 'ids' ),
				),
			),
			'products_cat'    => array(
				'type'      => 'select2',
				'label'     => esc_html__( 'Category', 'custom-woo-builder' ),
				'default'   => '',
				'multiple'  => true,
				'options'   => $this->get_product_categories(),
				'condition' => array(
					'products_query' => array( 'category' ),
				),
			),
			'products_tag'    => array(
				'type'      => 'select2',
				'label'     => esc_html__( 'Tag', 'custom-woo-builder' ),
				'default'   => '',
				'multiple'  => true,
				'options'   => $this->get_product_tags(),
				'condition' => array(
					'products_query' => array( 'tag' ),
				),
			),
			'products_order'  => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Order by', 'custom-woo-builder' ),
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Date', 'custom-woo-builder' ),
					'price'   => esc_html__( 'Price', 'custom-woo-builder' ),
					'rand'    => esc_html__( 'Random', 'custom-woo-builder' ),
					'sales'   => esc_html__( 'Sales', 'custom-woo-builder' ),
					'rated'   => esc_html__( 'Top Rated', 'custom-woo-builder' ),
				),
			),
			'show_title'      => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Products Title', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			),
			'title_trim_type' => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Title Trim Type', 'custom-woo-builder' ),
				'default'   => 'word',
				'options'   => array(
					'word'    => 'Words',
					'letters' => 'Letters',
				),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			),
			'title_length'        => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Title Words/Letters Count', 'custom-woo-builder' ),
				'min' => 1,
				'default'   => 10,
				'condition'    => array(
					'show_title' => array( 'yes' )
				)
			),
			'show_image'      => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Products Featured Image', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'thumb_size'      => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Featured Image Size', 'custom-woo-builder' ),
				'default'   => 'woocommerce_thumbnail',
				'options'   => custom_woo_builder_tools()->get_image_sizes(),
				'condition' => array(
					'show_image' => array( 'yes' ),
				),
			),
			'show_cat'        => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Categories', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_price'      => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Price', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_rating'     => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Rating', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_sku'           => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show SKU', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			),
			'show_button'     => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Add To Cart Button', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'button_use_ajax_style' => array(
				'label'        => esc_html__( 'Use default ajax add to cart styles', 'custom-woo-builder' ),
				'description'  => esc_html__( 'This option enables default WooCommerce styles for \'Add to Cart\' ajax button (\'Loading\' and \'Added\' statements)', 'custom-woo-builder' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition' => array(
					'show_button' => array( 'yes' )
				)
			)
		) );

	}

	public function get_products_query_type() {
		$args = array(
			'all'      => esc_html__( 'All', 'custom-woo-builder' ),
			'featured' => esc_html__( 'Featured', 'custom-woo-builder' ),
			'sale'     => esc_html__( 'Sale', 'custom-woo-builder' ),
			'tag'      => esc_html__( 'Tag', 'custom-woo-builder' ),
			'category' => esc_html__( 'Category', 'custom-woo-builder' ),
			'ids'      => esc_html__( 'Specific IDs', 'custom-woo-builder' ),
			'viewed'   => esc_html__( 'Recently Viewed', 'custom-woo-builder' ),
		);

		$single_product_args = array(
			'related'     => esc_html__( 'Related', 'custom-woo-builder' ),
			'up-sells'    => esc_html__( 'Up Sells', 'custom-woo-builder' ),
			'cross-sells' => esc_html__( 'Cross Sells', 'custom-woo-builder' ),
		);

		if ( is_product() ) {
			$args = wp_parse_args( $single_product_args, $args );
		}

		return $args;
	}

	public function get_product_categories() {

		$categories = get_terms( 'product_cat' );

		if ( empty( $categories ) || ! is_array( $categories ) ) {
			return array();
		}

		return wp_list_pluck( $categories, 'name', 'term_id' );

	}

	public function get_product_tags() {

		$tags = get_terms( 'product_tag' );

		if ( empty( $tags ) || ! is_array( $tags ) ) {
			return array();
		}

		return wp_list_pluck( $tags, 'name', 'term_id' );

	}

	public function query() {
		$defaults = apply_filters( 'custom-woo-builder/shortcodes/custom-woo-products-list/query-args', array(
			'post_status'   => 'publish',
			'post_type'     => 'product',
			'no_found_rows' => 1,
			'meta_query'    => array(),
			'tax_query'     => array(
				'relation' => 'AND',
			)
		), $this );

		$query_type                   = $this->get_attr( 'products_query' );
		$query_order                  = $this->get_attr( 'products_order' );
		$query_args['posts_per_page'] = intval( $this->get_attr( 'number' ) );
		$product_visibility_term_ids  = wc_get_product_visibility_term_ids();
		$viewed_products              = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
		$viewed_products              = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

		if ( ( 'viewed' === $query_type ) && empty( $viewed_products ) ) {
			return false;
		}

		if ( $this->is_single_linked_products( $query_type ) ) {
			global $product;
			$product = wc_get_product();

			if ( ! $product ) {
				return false;
			}

			switch ( $query_type ) {
				case 'related':
					$query_args['post__in'] = wc_get_related_products( $product->get_id(), $query_args['posts_per_page'], $product->get_upsell_ids() );
					$query_args['orderby']  = 'post__in';
					break;
				case 'up-sells':
					$query_args['post__in'] = $product->get_upsell_ids();
					$query_args['orderby']  = 'post__in';
					break;
				case 'cross-sells':
					$query_args['post__in'] = $product->get_cross_sell_ids();
					$query_args['orderby']  = 'post__in';
					break;
			}

			if ( empty( $query_args['post__in'] ) ) {
				return false;
			}
		}

		switch ( $query_type ) {
			case 'category':
				if ( '' !== $this->get_attr( 'products_cat' ) ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => explode( ',', $this->get_attr( 'products_cat' ) ),
						'operator' => 'IN',
					);
				}
				break;
			case 'tag':
				if ( '' !== $this->get_attr( 'products_tag' ) ) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => explode( ',', $this->get_attr( 'products_tag' ) ),
						'operator' => 'IN',
					);
				}
				break;
			case 'ids':
				if ( '' !== $this->get_attr( 'products_ids' ) ) {
					$query_args['post__in'] = explode(
						',',
						str_replace( ' ', '', $this->get_attr( 'products_ids' ) )
					);
				}
				break;
			case 'featured':
				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['featured'],
				);
				break;
			case 'sale':
				$product_ids_on_sale    = wc_get_product_ids_on_sale();
				$product_ids_on_sale[]  = 0;
				$query_args['post__in'] = $product_ids_on_sale;
				break;
			case 'viewed':
				$query_args['post__in'] = $viewed_products;
				$query_args['orderby']  = 'post__in';
		}

		switch ( $query_order ) {
			case 'price' :
				$query_args['meta_key'] = '_price';
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rand' :
				$query_args['orderby'] = 'rand';
				break;
			case 'sales' :
				$query_args['meta_key'] = 'total_sales';
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rated':
				$query_args['meta_key'] = '_wc_average_rating';
				$query_args['orderby']  = 'meta_value_num';
				break;
			default :
				$query_args['orderby'] = 'date';
		}

		$query_args = wp_parse_args( $query_args, $defaults );

		return new WP_Query( $query_args );
	}

	public function is_single_linked_products( $query_type ) {

		if ( 'related' === $query_type || 'up-sells' === $query_type || 'cross-sells' === $query_type ) {
			return true;
		}

		return false;

	}

	public function _shortcode( $content = null ) {
		$query = $this->query();

		if ( false === $query ) {
			return;
		}

		if ( empty( $query ) || is_wp_error( $query ) ) {
			echo sprintf( '<h3 class="custom-woo-products__not-found">%s</h3>', esc_html__( 'Products not found', 'custom-woo-builder' ) );

			return false;
		}

		$loop_start = $this->get_template( 'loop-start' );
		$loop_item  = $this->get_template( 'loop-item' );
		$loop_end   = $this->get_template( 'loop-end' );

		global $post;

		ob_start();

		do_action( 'custom-woo-builder/shortcodes/custom-woo-products-list/loop-start' );

		include $loop_start;

		while ( $query->have_posts() ) {

			$query->the_post();
			$post = $query->post;

			setup_postdata( $post );

			do_action( 'custom-woo-builder/shortcodes/custom-woo-products-list/loop-item-start' );

			include $loop_item;

			do_action( 'custom-woo-builder/shortcodes/custom-woo-products-list/loop-item-end' );

		}

		include $loop_end;

		do_action( 'custom-woo-builder/shortcodes/custom-woo-products-list/loop-end' );

		wp_reset_postdata();

		return ob_get_clean();

	}

}
