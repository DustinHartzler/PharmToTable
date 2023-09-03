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
		add_filter( 'automatewoo_email_template_product_permalink', [ $this, 'add_attributes_to_permalink' ], 10, 1 );
		add_filter( 'automatewoo_email_template_product_name', [ $this, 'add_attributes_to_product_name' ], 10, 1 );

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
	 * Add product attributes to the product's permalink if it is a \WC_Order_Item_Product.
	 *
	 * Return an array containing the product object and its permalink because if the
	 * passed product arg is a \WC_Order_Item_Product we should get \WC_Product from it and
	 * returns back to the caller as the caller would use other methods from \WC_Product.
	 *
	 * @param \WC_Product|\WC_Order_Item_Product $product
	 *
	 * @since 6.0.3
	 *
	 * @return array
	 */
	public function add_attributes_to_permalink( $product ) {
		if ( is_a( $product, 'WC_Order_Item_Product' ) ) {
			$item         = $product;
			$product      = $item->get_product();
			$product_meta = $item->get_formatted_meta_data();
			$permalink    = $product->get_permalink( [ 'item_meta_array' => $product_meta ] );
		} else {
			$permalink = $product->get_permalink();
		}

		return [
			'product'   => $product,
			'permalink' => $permalink,
		];
	}

	/**
	 * Add product attributes to the product's name.
	 *
	 * Return an array containing the product object and its product name because if the
	 * passed product arg is a \WC_Order_Item_Product we should get \WC_Product from it and
	 * returns back to the caller as the caller would use other methods from \WC_Product.
	 *
	 * @param \WC_Product|\WC_Order_Item_Product $product
	 *
	 * @since 6.0.3
	 *
	 * @return array
	 */
	public function add_attributes_to_product_name( $product ) {
		if ( is_a( $product, 'WC_Order_Item_Product' ) ) {
			$item         = $product;
			$product      = $item->get_product();
			$product_name = $product->get_name();
			$product_meta = $item->get_formatted_meta_data();

			// Part of the logic of generating product name refers to:
			// https://github.com/woocommerce/woocommerce/blob/462c690d613e1f5af3be9459b2aac8409a4587dc/plugins/woocommerce/includes/data-stores/class-wc-product-variation-data-store-cpt.php#L291-L313

			// Do not include attributes if the product has 3+ attributes.
			$should_include_attributes = apply_filters( 'woocommerce_product_variation_title_include_attributes', count( $product_meta ) < 3, $product );
			$separator                 = apply_filters( 'woocommerce_product_variation_title_attributes_separator', ' - ', $product );

			if ( $should_include_attributes ) {
				$title_suffix = [];

				foreach ( $product_meta as $meta ) {
					$value = $meta->value;
					if ( ! wc_is_attribute_in_product_name( $value, $product_name ) ) {
						$title_suffix[] = $value;
					}
				}

				if ( ! empty( $title_suffix ) ) {
					$product_name = $product_name . $separator . implode( ', ', $title_suffix );
				}
			}
		} else {
			$product_name = $product->get_name();
		}

		return [
			'product'      => $product,
			'product_name' => $product_name,
		];
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
