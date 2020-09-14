<?php

namespace AutomateWoo;

defined( 'ABSPATH' ) || exit;

/**
 * Variable_Abstract_Product_Display class.
 */
abstract class Variable_Abstract_Product_Display extends Variable {

	/**
	 * Declare limit field support.
	 *
	 * @var boolean
	 */
	public $support_limit_field = false;

	/**
	 * Declare order table support.
	 *
	 * @var boolean
	 */
	public $supports_order_table = false;

	/**
	 * Declare cart table support.
	 *
	 * @var boolean
	 */
	public $supports_cart_table = false;

	/**
	 * Temproary template args.
	 *
	 * @var array
	 */
	protected $temp_template_args = [];

	/**
	 * Load admin details.
	 */
	public function load_admin_details() {

		$templates = apply_filters(
			'automatewoo/variables/product_templates',
			[
				''                     => __( 'Product Grid - 2 Column', 'automatewoo' ),
				'product-grid-3-col'   => __( 'Product Grid - 3 Column', 'automatewoo' ),
				'product-rows'         => __( 'Product Rows', 'automatewoo' ),
				'cart-table'           => __( 'Cart Table', 'automatewoo' ),
				'order-table'          => __( 'Order Table', 'automatewoo' ),
				'list-comma-separated' => __( 'List - Comma Separated', 'automatewoo' ),
				'review-grid-2-col'    => __( 'Review Product Grid - 2 Column', 'automatewoo' ),
				'review-grid-3-col'    => __( 'Review Product Grid - 3 Column', 'automatewoo' ),
				'review-rows'          => __( 'Review Product Rows', 'automatewoo' ),
			]
		);

		if ( ! $this->supports_cart_table ) {
			unset( $templates['cart-table'] );
		}

		if ( ! $this->supports_order_table ) {
			unset( $templates['order-table'] );
		}

		$this->add_parameter_select_field( 'template', __( "Select which template will be used to display the products. The default is 'Product Grid - 2 Column'. For information on creating custom templates please refer to the documentation.", 'automatewoo' ), $templates );

		$this->add_parameter_text_field( 'url_append', __( "Add a string to the end of each product URL. For example, using '#tab-reviews' can link customers directly to the review tab on a product page.", 'automatewoo' ), false );

		if ( $this->support_limit_field ) {
			$this->add_parameter_text_field( 'limit', __( 'Set the maximum number of products that will be displayed.', 'automatewoo' ), false, 8 );
		}
	}

	/**
	 * Prepare products.
	 *
	 * @deprecated in 4.9.0 - Use aw_get_products() with self::get_default_product_query_args() instead
	 *
	 * @param array  $product_ids
	 * @param string $orderby
	 * @param string $order
	 * @param int    $limit
	 * @return array
	 */
	public function prepare_products( $product_ids, $orderby = 'date', $order = 'DESC', $limit = 8 ) {
		wc_deprecated_function( __METHOD__, '4.9.0' );

		if ( empty( $product_ids ) ) {
			return [];
		}

		$product_ids = array_filter( $product_ids );

		$args = [
			'post_type'           => 'product',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'posts_per_page'      => $limit,
			'post__in'            => $product_ids,
			'fields'              => 'ids',
			'orderby'             => $orderby,
			'order'               => $order,
			'tax_query'           => $this->get_taxonomy_query(),
			'meta_query'          => WC()->query->get_meta_query(),
		];

		if ( $orderby === 'popularity' ) {
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
		}

		$query = new \WP_Query( $args );

		return array_map( 'wc_get_product', $query->posts );
	}


	/**
	 * Get taxonomy query.
	 *
	 * @deprecated in 4.9.0
	 *
	 * @param array $taxonomy_query
	 * @return array
	 */
	protected function get_taxonomy_query( $taxonomy_query = [] ) {
		wc_deprecated_function( __METHOD__, '4.9.0' );

		$product_visibility_not_in = [];
		$product_visibility_terms  = wc_get_product_visibility_term_ids();

		if ( ! apply_filters( 'automatewoo/variables/product_query/show_excluded_from_catalog', false, $this ) ) {
			$product_visibility_not_in[] = $product_visibility_terms['exclude-from-catalog'];
		}

		// Hide out of stock products.
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$product_visibility_not_in[] = $product_visibility_terms['outofstock'];
		}

		if ( ! empty( $product_visibility_not_in ) ) {
			$taxonomy_query[] = [
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_not_in,
				'operator' => 'NOT IN',
			];
		}

		return $taxonomy_query;
	}


	/**
	 * Get default product template arguments.
	 *
	 * @param Workflow $workflow
	 * @param array    $parameters
	 * @return array
	 */
	public function get_default_product_template_args( $workflow, $parameters = [] ) {
		return [
			'workflow'      => $workflow,
			'variable_name' => $this->get_name(),
			'data_type'     => $this->get_data_type(),
			'data_field'    => $this->get_data_field(),
			'url_append'    => isset( $parameters['url_append'] ) ? trim( Clean::string( $parameters['url_append'] ) ) : '',
		];
	}


	/**
	 * Get product html to display.
	 *
	 * @param string $template
	 * @param array  $args
	 *
	 * @return string
	 */
	public function get_product_display_html( $template, $args = [] ) {

		ob_start();

		if ( $template ) {
			$template = sanitize_file_name( $template );

			if ( ! pathinfo( $template, PATHINFO_EXTENSION ) ) {
				$template .= '.php';
			}
		} else {
			$template = 'product-grid-2-col.php';
		}

		$this->temp_template_args = $args;
		add_filter( 'post_type_link', [ $this, 'filter_product_links' ], 100, 2 );

		aw_get_template( 'email/' . $template, $args );

		remove_filter( 'post_type_link', [ $this, 'filter_product_links' ] );
		$this->temp_template_args = [];

		return ob_get_clean();
	}


	/**
	 * Filter product links.
	 *
	 * @param string   $link
	 * @param \WP_Post $post
	 * @return string
	 */
	public function filter_product_links( $link, $post ) {

		if ( $post->post_type !== 'product' ) {
			return $link;
		}

		if ( isset( $this->temp_template_args['url_append'] ) ) {
			$link .= $this->temp_template_args['url_append'];
		}
		return $link;
	}

	/**
	 * Get default product query args.
	 *
	 * These args are intentionally ignored for some variables, e.g. order.items, cart.items.
	 *
	 * - Published products only
	 * - Sets visibility to catalog which hides hidden products
	 * - Hides out of stock products according to the global setting
	 *
	 * @since 4.8.1
	 *
	 * @return array
	 */
	protected function get_default_product_query_args() {
		$args = [
			'status'     => 'publish',
			'visibility' => 'catalog',
		];

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$args['stock_status'] = 'instock';
		}

		return $args;
	}

}
