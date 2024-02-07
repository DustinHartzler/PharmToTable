<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product\Price;

use Automattic\WooCommerce\Utilities\NumberUtil;
use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductTaskCreator;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductUpdatesTrait;
use OM4\WooCommerceZapier\WooCommerceResource\Product\VariationTypesTrait;
use WC_Product;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * REST API controller class for the Update Product Price action functionality.
 *
 * @since 2.6.0
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
	protected $rest_base = 'products/prices';

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
	 * ProductTaskCreator instance.
	 *
	 * @var ProductTaskCreator
	 */
	protected $task_creator;

	/**
	 * Constructor.
	 *
	 * @param Logger             $logger       Logger instance.
	 * @param ProductTaskCreator $task_creator OrderTaskCreator instance.
	 */
	public function __construct( Logger $logger, ProductTaskCreator $task_creator ) {
		$this->logger       = $logger;
		$this->task_creator = $task_creator;
		$this->add_filter_to_check_for_request_validation_error();
	}

	/**
	 * Get the Product Price schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		list( $product_identifier, $product_value ) = $this->get_common_schema_fields();

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'product_price',
			'type'       => 'object',
			'properties' => array(
				'product_identifier' => $product_identifier,
				'product_value'      => $product_value,
				'adjustment_type'    => array(
					'description' => __( "Choose how and what to modify the product's price", 'woocommerce-zapier' ),
					'type'        => 'string',
					'enum'        => array(
						'set_regular_price_to',
						'increase_regular_price_by',
						'decrease_regular_price_by',
						'set_sale_price_to',
						'increase_sale_price_by',
						'decrease_sale_price_by',
						'decrease_sale_price_from_regular_by',
					),
					'context'     => array( 'edit' ),
					'required'    => true,
					'wcz_meta_v1' => array(
						'field_properties' => array(
							'alters_dynamic_fields' => true,
							'default'               => 'set_regular_price_to',
						),
						'enum_labels'      => array(
							'set_regular_price_to'      => __( 'Set Regular Price To', 'woocommerce-zapier' ),
							'increase_regular_price_by' => __( 'Increase Regular Price By (fixed amount or percentage)', 'woocommerce-zapier' ),
							'decrease_regular_price_by' => __( 'Decrease Regular Price By (fixed amount or percentage)', 'woocommerce-zapier' ),
							'set_sale_price_to'         => __( 'Set Sale Price To', 'woocommerce-zapier' ),
							'increase_sale_price_by'    => __( 'Increase Sale Price By (fixed amount or percentage)', 'woocommerce-zapier' ),
							'decrease_sale_price_by'    => __( 'Decrease Sale Price By (fixed amount or percentage)', 'woocommerce-zapier' ),
							'decrease_sale_price_from_regular_by' => __( 'Set Sale Price to Regular Price Decreased By (fixed amount or percentage)', 'woocommerce-zapier' ),
						),
					),
				),
				'price_value'        => array(
					'description' => __( 'Enter a value to set, increase, or decrease the current price (fixed or %)', 'woocommerce-zapier' ),
					'type'        => 'string',
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
											'set_regular_price_to'      => __( 'Value to Set Regular Price To', 'woocommerce-zapier' ),
											'increase_regular_price_by' => __( 'Value to Increase Regular Price By (fixed or %)', 'woocommerce-zapier' ),
											'decrease_regular_price_by' => __( 'Value to Decrease Regular Price By (fixed or %)', 'woocommerce-zapier' ),
											'set_sale_price_to'         => __( 'Value to Set Sale Price To', 'woocommerce-zapier' ),
											'increase_sale_price_by'    => __( 'Value to Increase Sale Price By (fixed or %)', 'woocommerce-zapier' ),
											'decrease_sale_price_by'    => __( 'Value to Decrease Sale Price By (fixed or %)', 'woocommerce-zapier' ),
											'decrease_sale_price_from_regular_by'    => __( 'Sales Price Reduced from Regular Price By (fixed or %)', 'woocommerce-zapier' ),
										),
									),
									array(
										'property' => 'help_text',
										'mapping'  => array(
											'set_regular_price_to'      => __( 'Enter the new regular price.', 'woocommerce-zapier' ),
											'increase_regular_price_by' => __( 'Enter an amount or percentage (including % symbol). For example, `9.99` or `25%`.', 'woocommerce-zapier' ),
											'decrease_regular_price_by' => __( 'Enter an amount or percentage (including % symbol). For example, `9.99` or `25%`.', 'woocommerce-zapier' ),
											'set_sale_price_to'         => __( 'Enter the new sale price.', 'woocommerce-zapier' ),
											'increase_sale_price_by'    => __( 'Enter an amount or percentage (including % symbol). For example, `9.99` or `25%`.', 'woocommerce-zapier' ),
											'decrease_sale_price_by'    => __( 'Enter an amount or percentage (including % symbol). For example, `9.99` or `25%`.', 'woocommerce-zapier' ),
											'decrease_sale_price_from_regular_by'    => __( 'Enter an amount or percentage (including % symbol). For example, `9.99` or `25%`.  Set to `0` to remove sales price.', 'woocommerce-zapier' ),
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
	 * Update the price or date of an existing product.
	 *
	 * @param WP_REST_Request $request The incoming request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$product = $this->get_product( $request );
		if ( is_wp_error( $product ) ) {
			$this->log_error_response( $request, $product );
			$this->task_creator->record(
				$this->modify_event( Event::action_update( $this->resource_type, $product ) ),
				0
			);
			return $product;
		}

		if ( ! $product ) {
			$response = new WP_Error( 'woocommerce_rest_product_invalid_id', __( 'Invalid product ID or SKU.', 'woocommerce-zapier' ), array( 'status' => 404 ) );
			$this->log_error_response( $request, $response );
			$this->task_creator->record(
				$this->modify_event( Event::action_update( $this->resource_type, $response ) ),
				0
			);
			return $response;
		}

		$id       = 0 === $product->get_parent_id() ? $product->get_id() : $product->get_parent_id();
		$child_id = 0 === $product->get_parent_id() ? null : $product->get_id();

		/**
		 * WP/WC already validated and sanitised the request data.
		 * wc_clean() is returning string, because the input is not array.
		 *
		 * @var string $price_value
		 */
		$price_value = \wc_clean( $request['price_value'] );
		switch ( $request['adjustment_type'] ) {
			case 'set_regular_price_to':
				$product->set_regular_price( $price_value );
				break;
			case 'increase_regular_price_by':
				$this->adjust_price( $product, 'regular_price', '+', $price_value );
				break;
			case 'decrease_regular_price_by':
				$this->adjust_price( $product, 'regular_price', '-', $price_value );
				break;
			case 'set_sale_price_to':
				$product->set_sale_price( $price_value );
				break;
			case 'increase_sale_price_by':
				$this->adjust_price( $product, 'sale_price', '+', $price_value );
				break;
			case 'decrease_sale_price_by':
				$this->adjust_price( $product, 'sale_price', '-', $price_value );
				break;
			case 'decrease_sale_price_from_regular_by':
					$field_value = \floatval( $product->get_regular_price( 'edit' ) );
					$this->adjust_price( $product, 'sale_price', '-', $price_value, $field_value );
				break;
			default:
				$response = new WP_Error(
					'woocommerce_rest_product_invalid_adjustment_type',
					__( 'Invalid adjustment type.', 'woocommerce-zapier' ),
					array( 'status' => 400 )
				);
				$this->log_error_response( $request, $response );
				$this->task_creator->record(
					$this->modify_event( Event::action_update( $this->resource_type, $response ) ),
					$id,
					$child_id
				);
				return $response;
		}

		$product->save();

		$this->task_creator->record(
			$this->modify_event( Event::action_update( $this->resource_type ) ),
			$id,
			$child_id
		);

		$response = array(
			'id'            => $product->get_id(),
			'sku'           => $product->get_sku( 'edit' ),
			'price'         => $product->get_price( 'edit' ),
			'regular_price' => $product->get_regular_price( 'edit' ),
			'sale_price'    => $product->get_sale_price( 'edit' ),
			'on_sale'       => $product->is_on_sale( 'edit' ),
		);
		return rest_ensure_response( $response );
	}

	/**
	 * Set Price helper.
	 *
	 * Arithmetic logic is taken from WC_AJAX::variation_bulk_adjust_price.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/7.7.0/plugins/woocommerce/includes/class-wc-ajax.php#L2680
	 *
	 * @param WC_Product $product The product to modify.
	 * @param string     $field price being adjusted regular_price or sale_price.
	 * @param string     $operator +s or -.
	 * @param string     $value Price or Percent.
	 * @param float|null $field_value  Base value or null to lad dynamically.
	 *
	 * @return void
	 */
	protected static function adjust_price( $product, $field, $operator, $value, $field_value = null ) {
		if ( ! $field_value ) {
			$field_value = \floatval( $product->{"get_$field"}( 'edit' ) );
		}
		if ( '%' === substr( $value, -1 ) ) {
			$percent      = \wc_format_decimal( substr( $value, 0, -1 ) );
			$field_value += ( NumberUtil::round( ( $field_value / 100 ) * \floatval( $percent ), \wc_get_price_decimals() ) * \intval( "{$operator}1" ) );
		} else {
			$field_value += ( \floatval( $value ) * \intval( "{$operator}1" ) );
		}

		$product->{"set_$field"}( \strval( $field_value ) );
	}

	/**
	 * Modify the event object for this controller.
	 *
	 * Ensures that the action is Update Product Price (not Update Product).
	 *
	 * @since 2.10.0
	 *
	 * @param  Event $event The event object instance.
	 *
	 * @return Event
	 */
	protected function modify_event( $event ) {
		$event->topic = 'product.update_price';
		$event->name  = __( 'Update Product Price', 'woocommerce-zapier' );
		return $event;
	}
}
