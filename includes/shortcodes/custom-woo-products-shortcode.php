<?php

class Custom_Woo_Products_Shortcode extends Custom_Woo_Builder_Shortcode_Base {

	public function get_tag() {
		return 'custom-woo-products';
	}

	public function get_atts() {

		$columns = custom_woo_builder_tools()->get_select_range( 6 );

		return apply_filters( 'custom-woo-builder/shortcodes/custom-woo-products/atts', array(
			'presets'               => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Product Presets', 'custom-woo-builder' ),
				'default' => 'preset-1',
				'options' => array(
					'preset-1'  => esc_html__( 'Preset 1', 'custom-woo-builder' ),
					'preset-2'  => esc_html__( 'Preset 2', 'custom-woo-builder' ),
					'preset-3'  => esc_html__( 'Preset 3', 'custom-woo-builder' ),
					'preset-4'  => esc_html__( 'Preset 4', 'custom-woo-builder' ),
					'preset-5'  => esc_html__( 'Preset 5', 'custom-woo-builder' ),
					'preset-6'  => esc_html__( 'Preset 6', 'custom-woo-builder' ),
					'preset-7'  => esc_html__( 'Preset 7 ', 'custom-woo-builder' ),
					'preset-8'  => esc_html__( 'Preset 8 ', 'custom-woo-builder' ),
					'preset-9'  => esc_html__( 'Preset 9 ', 'custom-woo-builder' ),
					'preset-10' => esc_html__( 'Preset 10', 'custom-woo-builder' ),
				),
			),
			'columns'               => array(
				'type'       => 'select',
				'responsive' => true,
				'label'      => esc_html__( 'Columns', 'custom-woo-builder' ),
				'default'    => 3,
				'options'    => $columns,
			),
			'columns_tablet'        => array(
				'default' => 2,
			),
			'columns_mobile'        => array(
				'default' => 1,
			),
			'equal_height_cols'     => array(
				'label'        => esc_html__( 'Equal Columns Height', 'custom-woo-builder' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'true',
				'default'      => '',
			),
			'columns_gap'           => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between columns', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'rows_gap'              => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between rows', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'use_current_query'     => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Use Current Query', 'custom-woo-builder' ),
				'description'  => esc_html__( 'This option works only on the shop archive page, and allows you to display products for current categories, tags and taxonomies.', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator' => 'before'
			),
			'number'                => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Products Number', 'custom-woo-builder' ),
				'default'   => 3,
				'min'       => - 1,
				'max'       => 1000,
				'step'      => 1,
			),
			'products_query'        => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Query products by', 'custom-woo-builder' ),
				'default' => 'all',
				'options' => $this->get_products_query_type(),
				'condition' => array(
					'use_current_query!' => 'yes'
				)
			),
			'products_ids'          => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Set comma separated IDs list (10, 22, 19 etc.)', 'custom-woo-builder' ),
				'label_block'=> true,
				'default'   => '',
				'condition' => array(
					'products_query' => array( 'ids' ),
					'use_current_query!' => 'yes'
				),
			),
			'products_cat'          => array(
				'type'      => 'select2',
				'label'     => esc_html__( 'Category', 'custom-woo-builder' ),
				'default'   => '',
				'multiple'  => true,
				'options'   => $this->get_product_categories(),
				'condition' => array(
					'products_query' => array( 'category' ),
					'use_current_query!' => 'yes'
				),
			),
			'products_tag'          => array(
				'type'      => 'select2',
				'label'     => esc_html__( 'Tag', 'custom-woo-builder' ),
				'default'   => '',
				'multiple'  => true,
				'options'   => $this->get_product_tags(),
				'condition' => array(
					'products_query' => array( 'tag' ),
					'use_current_query!' => 'yes'
				),
			),
			'products_order'        => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Order by', 'custom-woo-builder' ),
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Date', 'custom-woo-builder' ),
					'price'   => esc_html__( 'Price', 'custom-woo-builder' ),
					'rand'    => esc_html__( 'Random', 'custom-woo-builder' ),
					'sales'   => esc_html__( 'Sales', 'custom-woo-builder' ),
					'rated'   => esc_html__( 'Top rated', 'custom-woo-builder' ),
					'current' => esc_html__( 'Current', 'custom-woo-builder' ),
				),
				'condition' => array(
					'use_current_query!' => 'yes'
				)
			),
			'show_title'            => array(
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
			'title_length'          => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Title Words/Letters Count', 'custom-woo-builder' ),
				'min'       => 1,
				'default'   => 10,
				'condition' => array(
					'show_title' => array( 'yes' )
				)
			),
			'thumb_size'            => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Featured Image Size', 'custom-woo-builder' ),
				'default' => 'woocommerce_thumbnail',
				'options' => custom_woo_builder_tools()->get_image_sizes(),
			),
			'show_badges'           => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Badges', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'sale_badge_text'       => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Set sale badge text', 'custom-woo-builder' ),
				'default'   => esc_html__( 'Sale!', 'custom-woo-builder' ),
				'condition' => array(
					'show_badges' => array( 'yes' ),
				),
			),
			'show_excerpt'          => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Excerpt', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'presets!' => array( 'preset-4' )
				)
			),
			'excerpt_trim_type' => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Excerpt Trim Type', 'custom-woo-builder' ),
				'default'   => 'word',
				'options'   => array(
					'word'    => 'Words',
					'letters' => 'Letters',
				),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			),
			'excerpt_length'        => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Excerpt Words/Letters Count', 'custom-woo-builder' ),
				'min'       => 1,
				'default'   => 10,
				'condition' => array(
					'show_excerpt' => array( 'yes' )
				)
			),
			'show_cat'              => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Categories', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_tag'              => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Tags', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_price'            => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Price', 'custom-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'custom-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'custom-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_rating'           => array(
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
			'show_button'           => array(
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
				'condition'    => array(
					'show_button' => array( 'yes' )
				)
			)
		) );

	}

	/**
	 * Return list query types
	 *
	 * @return array
	 */
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

	public function get_product_preset_template() {
		return custom_woo_builder()->get_template( $this->get_tag() . '/global/presets/' . $this->get_attr( 'presets' ) . '.php' );
	}

	public function query() {

		$defaults = apply_filters( 'custom-woo-builder/shortcodes/custom-woo-products/query-args', array(
			'post_status'   => 'publish',
			'post_type'     => 'product',
			'no_found_rows' => 1,
			'meta_query'    => array(),
			'tax_query'     => array(
				'relation' => 'AND',
			)
		), $this );

		if ( 'yes' === $this->get_attr( 'use_current_query' ) ) {

			if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
				global $wp_query;

				$wp_query->set( 'custom_use_current_query', 'yes' );
				$wp_query->set( 'posts_per_page', intval( $this->get_attr( 'number' ) ) );

				$default_query = array(
					'post_type'      => $wp_query->get( 'post_type' ),
					'wc_query'       => $wp_query->get( 'wc_query' ),
					'tax_query'      => $wp_query->get( 'tax_query' ),
					'orderby'        => $wp_query->get( 'orderby' ),
					'posts_per_page' => intval( $this->get_attr( 'number' ) ),
					'paged'          => $wp_query->get( 'paged' )
				);

				if ( $wp_query->get( 'taxonomy' ) ) {
					$default_query['taxonomy'] = $wp_query->get( 'taxonomy' );
					$default_query['term']     = $wp_query->get( 'term' );
				}

				$query_args = wp_parse_args( $defaults, $wp_query->query_vars );

				$defaults = apply_filters( 'custom-woo-builder/shortcodes/custom-woo-products/query-args', $query_args, $this );

				$query_args = $this->get_wc_catalog_ordering_args( $query_args );

				return new WP_Query( $query_args );

			}

		}

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
			case 'current':

				$query_args = $this->get_wc_catalog_ordering_args( $query_args );

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

	public function get_wc_catalog_ordering_args( $query_args ) {

		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
		$orderby_current = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; 
		if ( false !== $orderby_current ) {
			$wc_query               = new WC_Query();
			$ordering               = $wc_query->get_catalog_ordering_args();
			$query_args['orderby']  = $ordering['orderby'];
			$query_args['order']    = $ordering['order'];
			$query_args['meta_key'] = $ordering['meta_key'];
		}

		return $query_args;

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

		do_action( 'custom-woo-builder/shortcodes/custom-woo-products/loop-start' );

		include $loop_start;

		while ( $query->have_posts() ) {

			$query->the_post();
			$post = $query->post;

			setup_postdata( $post );

			do_action( 'custom-woo-builder/shortcodes/custom-woo-products/loop-item-start' );

			include $loop_item;

			do_action( 'custom-woo-builder/shortcodes/custom-woo-products/loop-item-end' );

		}

		include $loop_end;

		do_action( 'custom-woo-builder/shortcodes/custom-woo-products/loop-end' );

		wp_reset_postdata();

		return ob_get_clean();

	}

}
