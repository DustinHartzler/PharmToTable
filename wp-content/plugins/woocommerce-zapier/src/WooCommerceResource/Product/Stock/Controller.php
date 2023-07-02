<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product\Stock;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductUpdatesTrait;
use OM4\WooCommerceZapier\WooCommerceResource\Product\VariationTypesTrait;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * REST API controller class for the Update Product Stock Quantity action functionality.
 *
 * @since 2.5.0
 */
class Controller extends WC_REST_Controller {

	use APIListenerTrait;
	use ProductUpdatesTrait;
	use VariationTypesTrait;

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = API::REST_NAMESPACE;

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'products/stocks';

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'product';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $data_store;

	/**
	 * Constructor.
	 *
	 * @param Logger        $logger     Logger instance.
	 * @param TaskDataStore $data_store TaskDataStore instance.
	 */
	public function __construct( Logger $logger, TaskDataStore $data_store ) {
		$this->logger     = $logger;
		$this->data_store = $data_store;
	}

	/**
	 * Get the Product Stock Quantity schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		list( $product_identifier, $product_value ) = $this->get_common_schema_fields();

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'product_stock',
			'type'       => 'object',
			'properties' => array(
				'product_identifier' => $product_identifier,
				'product_value'      => $product_value,
				'adjustment_type'    => array(
					'description' => __( "Choose how to modify the product's stock quantity", 'woocommerce-zapier' ),
					'type'        => 'string',
					'enum'        => array(
						'change_to',
						'increase_by',
						'reduce_by',
					),
					'context'     => array( 'edit' ),
					'required'    => true,
					'wcz_meta_v1' => array(
						'field_properties' => array(
							'alters_dynamic_fields' => true,
							'default'               => 'change_to',
						),
						'enum_labels'      => array(
							'change_to'   => __( 'Set Stock Quantity To', 'woocommerce-zapier' ),
							'increase_by' => __( 'Increase Stock Quantity By', 'woocommerce-zapier' ),
							'reduce_by'   => __( 'Reduce Stock Quantity By', 'woocommerce-zapier' ),
						),
					),
				),
				'adjustment_value'   => array(
					'description' => __( 'Enter a numeric value to set, increase, or reduce the current stock quantity', 'woocommerce-zapier' ),
					'type'        => 'integer',
					'context'     => array( 'edit' ),
					'required'    => true,
					'wcz_meta_v1' => array(
						'depends_on' => array(
							array(
								'field'   => 'adjustment_type',
								'changes' => array(
									array(
										'property' => 'label',
										'mapping'  => array(
											'change_to'   => __( 'Value to Set Stock Quantity To', 'woocommerce-zapier' ),
											'increase_by' => __( 'Value to Increase Stock Quantity By', 'woocommerce-zapier' ),
											'reduce_by'   => __( 'Value to Reduce Stock Quantity By', 'woocommerce-zapier' ),
										),
									),
									array(
										'property' => 'help_text',
										'mapping'  => array(
											'change_to'   => __( 'Enter a numeric value to set the current stock quantity to', 'woocommerce-zapier' ),
											'increase_by' => __( 'Enter a numeric value to increase the current stock quantity by', 'woocommerce-zapier' ),
											'reduce_by'   => __( 'Enter a numeric value to reduce the current stock quantity by', 'woocommerce-zapier' ),
										),
									),
								),
							),
						),
					),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Update the stock quantity of an existing product.
	 *
	 * @param WP_REST_Request $request The incoming request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$product = $this->get_product( $request );
		if ( is_wp_error( $product ) ) {
			$this->log_error_response( $request, $product );
			return $product;
		}

		if ( ! $product ) {
			$response = new WP_Error( 'woocommerce_rest_product_invalid_id', __( 'Invalid product ID or SKU.', 'woocommerce-zapier' ), array( 'status' => 404 ) );
			$this->log_error_response( $request, $response );
			return $response;
		}

		if ( ! $product->get_manage_stock() ) {
			$response = new WP_Error( 'woocommerce_rest_product_stock_management_disabled', __( 'Product does not have stock management enabled.', 'woocommerce-zapier' ), array( 'status' => 400 ) );
			$this->log_error_response( $request, $response );
			return $response;
		}

		$original_quantity = $product->get_stock_quantity( 'edit' );
		switch ( $request['adjustment_type'] ) {
			case 'change_to':
				$new_quantity = $request['adjustment_value'];
				break;
			case 'reduce_by':
				$new_quantity = $original_quantity - $request['adjustment_value'];
				break;
			case 'increase_by':
				$new_quantity = $original_quantity + $request['adjustment_value'];
				break;
			default:
				$response = new WP_Error( 'woocommerce_rest_product_invalid_adjustment_type', __( 'Invalid adjustment type.', 'woocommerce-zapier' ), array( 'status' => 400 ) );
				$this->log_error_response( $request, $response );
				return $response;
		}

		$product->set_stock_quantity( $new_quantity );
		$product->save();

		$response = array(
			'id'             => $product->get_id(),
			'sku'            => $product->get_sku( 'edit' ),
			'stock_quantity' => $product->get_stock_quantity( 'edit' ),
			'stock_status'   => $product->get_stock_status( 'edit' ),
		);
		$message  = __( 'Updated stock quantity via Zapier', 'woocommerce-zapier' );
		if ( $product->get_parent_id() === 0 ) {
			$this->create_task( $product->get_id(), $message );
		} else {
			$this->create_task( $product->get_parent_id(), $message, $product->get_id() );
		}
		return rest_ensure_response( $response );
	}
}
