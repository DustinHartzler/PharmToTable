<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductTaskCreator;
use OM4\WooCommerceZapier\WooCommerceResource\Product\VariationTypesTrait;
use OM4\WooCommerceZapier\WooCommerceResource\Product\WCVariationController;
use WC_Data;
use WC_Product;
use WC_REST_Products_Controller;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * REST API endpoints for managing Products and Product Variations.
 *
 * @since 2.0.0
 * @since 2.6.0 Modified to also include product variations (not just top level products).
 */
class Controller extends WC_REST_Products_Controller {

	use APIListenerTrait;
	use VariationTypesTrait;

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = API::REST_NAMESPACE;

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'product';

	/**
	 * Temporarily store the parent type for use in the filter_get_objects method.
	 *
	 * Set if the incoming request specifies a `type`.
	 *
	 * @var ?string
	 */
	protected $parent_type = null;

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
	 * VariationController instance.
	 *
	 * @var WCVariationController instance.
	 */
	protected $variation_controller;

	/**
	 * Constructor.
	 *
	 * @param  Logger                $logger               Logger instance.
	 * @param  ProductTaskCreator    $task_creator         ProductTaskCreator instance.
	 * @param  WCVariationController $variation_controller WCVariationController instance.
	 */
	public function __construct( Logger $logger, ProductTaskCreator $task_creator, WCVariationController $variation_controller ) {
		$this->logger               = $logger;
		$this->task_creator         = $task_creator;
		$this->variation_controller = $variation_controller;

		add_filter( 'rest_post_dispatch', array( $this, 'rest_post_dispatch' ), 10, 3 );
		$this->add_filter_to_check_for_request_validation_error();
		parent::__construct();
	}

	/**
	 * Check if a given request has access to read products and variations.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		return $this->permission_check( $request, 'get_items_permissions_check' );
	}

	/**
	 * Check if a given request has access to create a product and variation.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		return $this->permission_check( $request, 'create_item_permissions_check' );
	}

	/**
	 * Check if a given request has access to read a product and variation.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		return $this->permission_check( $request, 'get_item_permissions_check' );
	}

	/**
	 * Check if a given request has access to update a product or variation.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		return $this->permission_check( $request, 'update_item_permissions_check' );
	}

	/**
	 * Check if a given request has access to delete a product and variation.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return bool|WP_Error
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->permission_check( $request, 'delete_item_permissions_check' );
	}

	/**
	 * Check the specified permissions for both the `product` and `product_variation` post types.
	 *
	 * @param WP_REST_Request $request  Full details about the request.
	 * @param string          $permissions_method The name of the permissions method to call on the parent class.
	 *
	 * @return bool|WP_Error
	 */
	protected function permission_check( WP_REST_Request $request, $permissions_method ) {
		// Check product permissions.
		$result = parent::$permissions_method( $request );
		if ( \is_wp_error( $result ) ) {
			return $result;
		}

		// Check variation permissions.
		$this->post_type = 'product_variation';
		$result          = parent::$permissions_method( $request );
		$this->post_type = 'product';

		return $result;
	}

	/**
	 * Add our `wcz_meta_v1` information to OPTIONS requests for the Create and Update Product endpoints.
	 *
	 * Executed during the `rest_post_dispatch` filter because if the metadata is added via
	 * get_endpoint_args_for_item_schema(), it is removed by WordPress.
	 *
	 * @param WP_REST_Response $result  Result to send to the client.
	 * @param WP_REST_Server   $server  Server instance.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 *
	 * @return WP_HTTP_Response
	 */
	public function rest_post_dispatch( $result, $server, $request ) {
		if ( 'OPTIONS' !== $request->get_method() || 0 !== \strpos( $result->get_matched_route(), '/' . $this->namespace . '/' . $this->rest_base ) ) {
			return $result;
		}

		// @phpstan-ignore-next-line Structure comes from WordPress.
		$key = \array_search( 'POST', $result->data['methods'], true );
		if ( false === $key ) {
			return $result;
		}

		// Generate labels and/or help text for fields when a variation type is being created/updated.
		$parent_id_required_mappings             = array();
		$type_help_text_mappings                 = array();
		$attributes_options_label_mappings       = array();
		$attributes_options_help_text_mappings   = array();
		$attribute_not_applicable_label_mappings = array();
		foreach ( $this->get_product_variation_types() as $type ) {
			$parent_id_required_mappings[ $type ] = true;
			// translators: %s The product variation type.
			$type_help_text_mappings[ $type ]           = \sprintf( __( 'Product Type. To create a %s, the Parent ID must be provided below.', 'woocommerce-zapier' ), $type );
			$attributes_options_label_mappings[ $type ] = __( 'Option', 'woocommerce-zapier' );
			// translators: %s The product variation type.
			$attributes_options_help_text_mappings[ $type ]   = \sprintf( __( 'The attribute term name that should apply to this %s.', 'woocommerce-zapier' ), $type );
			$attribute_not_applicable_label_mappings[ $type ] = $type;
		}

		if ( $result->get_matched_route() === '/' . $this->namespace . '/' . $this->rest_base ) {
			// Create Product.
			// @phpstan-ignore-next-line Schema structure comes from WordPress.
			$result->data['endpoints'][ $key ]['args']['type']['wcz_meta_v1'] = array(
				'field_properties' => array(
					'alters_dynamic_fields' => true,
				),
				'depends_on'       => array(
					array(
						'field'   => 'type',
						'changes' => array(
							array(
								'property' => 'help_text',
								'mapping'  => $type_help_text_mappings,
							),
						),
					),
				),
			);

			// @phpstan-ignore-next-line Schema structure comes from WordPress.
			$result->data['endpoints'][ $key ]['args']['parent_id']['wcz_meta_v1'] = array(
				'depends_on' => array(
					array(
						'field'   => 'type',
						'changes' => array(
							array(
								'property' => 'required',
								'mapping'  => $parent_id_required_mappings,
							),
						),
					),
				),
			);
		}

		// Create or Update Product or Variation.
		// @phpstan-ignore-next-line Schema structure comes from WordPress.
		$result->data['endpoints'][ $key ]['args']['attributes']['items']['properties']['options']['wcz_meta_v1'] = array(
			'depends_on' => array(
				array(
					'field'   => 'type',
					'changes' => array(
						array(
							'property' => 'label',
							'mapping'  => $attributes_options_label_mappings,
						),
						array(
							'property' => 'help_text',
							'mapping'  => $attributes_options_help_text_mappings,
						),
					),
				),
			),
		);

		// Mark attribute fields as not applicable for certain variation types.
		foreach ( array( 'id', 'position', 'visible', 'variation' ) as $property ) {
			// @phpstan-ignore-next-line Schema structure comes from WordPress.
			$result->data['endpoints'][ $key ]['args']['attributes']['items']['properties'][ $property ]['wcz_meta_v1'] = array(
				'depends_on' => array(
					array(
						'field'   => 'type',
						'changes' => array(
							array(
								'property' => 'label',
								'mapping'  => \array_map(
									function ( $item ) use ( $property ) {
										// translators: 1: Property name, 2: Variation type.
										return \sprintf( __( '%1$s (Not Applicable for \'%2$s\' type)', 'woocommerce-zapier' ), \ucfirst( $property ), $item );
									},
									$attribute_not_applicable_label_mappings
								),
							),
						),
					),
				),
			);
		}

		return $result;
	}

	/**
	 * Get the Product's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = parent::get_item_schema();
		if ( isset( $schema['properties']['permalink_template'] ) ) {
			unset( $schema['properties']['permalink_template'] );
		}
		if ( isset( $schema['properties']['generated_slug'] ) ) {
			unset( $schema['properties']['generated_slug'] );
		}
		\array_push( $schema['properties']['type']['enum'], ...$this->get_product_variation_types() );
		return $schema;
	}

	/**
	 * Get the query params for list/search endpoint.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();
		array_push( $params['type']['enum'], ...$this->get_product_variation_types() );
		return $params;
	}

	/**
	 * Prepare the WP_Query arguments for search/list requests.
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args              = parent::prepare_objects_query( $request );
		$args['post_type'] = array( 'product', 'product_variation' );

		if ( empty( $request['type'] ) ) {
			return $args;
		}

		if ( ! in_array( $request['type'], $this->get_product_variation_types(), true ) ) {
			return $args;
		}

		// Remove the product_type (taxonomy) filter from the query.
		foreach ( $args['tax_query'] as $key => $value ) {
			if ( 'product_type' === $value['taxonomy'] ) {
				unset( $args['tax_query'][ $key ] );
			}
		}

		// Store request type for use in filter_get_objects().
		$key = \array_search( $request['type'], $this->get_variable_product_types_to_variation_types(), true );
		if ( $key ) {
			$this->parent_type = $key;
		}

		return $args;
	}

	/**
	 * Get objects for search/list endpoints.
	 *
	 * Implements requests that filter by `type.`
	 *
	 * @param array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		// Add filters for search criteria in based on parent product type.
		if ( ! is_null( $this->parent_type ) ) {
			add_filter( 'posts_join', array( $this, 'add_parent_product_type_search_criteria_to_wp_query_join' ) );
			add_filter( 'posts_where', array( $this, 'add_parent_product_type_search_criteria_to_wp_query_where' ) );
		}

		$result = parent::get_objects( $query_args );

		// Remove filters for search criteria in based on parent product type.
		if ( ! is_null( $this->parent_type ) ) {
			remove_filter( 'posts_join', array( $this, 'add_parent_product_type_search_criteria_to_wp_query_join' ) );
			remove_filter( 'posts_where', array( $this, 'add_parent_product_type_search_criteria_to_wp_query_where' ) );

			$this->parent_type = null;
		}
		return $result;
	}

	/**
	 *
	 * Ensures the `total_sales` property type is consistent for products and variations.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $context Context of request, can be `view` or `edit`.
	 *
	 * @return integer
	 */
	protected function api_get_total_sales( $product, $context ) {
		return \absint( $product->get_total_sales( $context ) );
	}

	/**
	 *
	 * Ensures the `average_rating` property type is consistent for products and variations.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $context Context of request, can be `view` or `edit`.
	 *
	 * @return string
	 */
	protected function api_get_average_rating( $product, $context ) {
		return \wc_format_decimal( $product->get_average_rating( $context ) );
	}

	/**
	 * Delete a single item.
	 *
	 * Calls the WooCommerce Variation Controller's delete_item() to perform the deletion,
	 * and then return a full product payload by using the Product Controller's prepare_object_for_response() method.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function delete_item( $request ) {
		$id       = (int) $request['id'];
		$object   = \wc_get_product( $id );
		$response = $this->variation_controller->delete_item( $request );
		if ( \is_wp_error( $response ) ) {
			return $response;
		}
		// @phpstan-ignore-next-line: If $response is not a WP_Error, then it is definitely a WC_Product_Variation.
		return $this->prepare_object_for_response( $object, $request );
	}

	/**
	 * Add a join clause for restricting the result to match the specified product type.
	 *
	 * @param string $join Join clause used to search posts.
	 * @return string
	 */
	public function add_parent_product_type_search_criteria_to_wp_query_join( $join ) {
		global $wpdb;
		if ( ! strstr( $join, 'wcz_parent_product' ) ) {
			$join .= " LEFT JOIN $wpdb->posts wcz_parent_product
						ON $wpdb->posts.post_parent = wcz_parent_product.ID ";
			$join .= " LEFT JOIN $wpdb->term_relationships wcz_term_relationships
						ON wcz_parent_product.ID = wcz_term_relationships.object_id ";
			$join .= " LEFT JOIN $wpdb->term_taxonomy wcz_term_taxonomy
						ON wcz_term_relationships.term_taxonomy_id = wcz_term_taxonomy.term_taxonomy_id ";
			$join .= " LEFT JOIN $wpdb->terms wcz_terms
						ON wcz_term_taxonomy.term_id = wcz_terms.term_id ";
		}
		return $join;
	}

	/**
	 * Add a where clause for restricting the result to match the specified product type.
	 *
	 * @param string $where Where clause used to search posts.
	 * @return string
	 */
	public function add_parent_product_type_search_criteria_to_wp_query_where( $where ) {
		global $wpdb;
		$where .= ' AND wcz_term_taxonomy.taxonomy = "product_type"';
		$where .= ' AND ' . $wpdb->prepare( 'wcz_terms.slug = %s', $this->parent_type );
		return $where;
	}

	/**
	 * Prepare a single product or variation for create or update.
	 *
	 * Validates the incoming request if creating/updating a variation.
	 *
	 * @param  WP_REST_Request $request Request object.
	 * @param  bool            $creating true if create, false otherwise.
	 * @return WP_Error|WC_Data
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		// Validate required POST fields.
		$type = null;
		if ( $creating ) {
			$type = $request['type'];
		} else {
			/**
			 * Product is always found because self::update_item() returns a
			 * `woocommerce_rest_product_invalid_id` 404 error for non-existent products.
			 *
			 * @var WC_Product $product
			 */
			$product = \wc_get_product( \absint( $request['id'] ) );
			$type    = $product->get_type();
		}

		if ( ! in_array( $type, $this->get_product_variation_types(), true ) ) {
			// Creating/updating a simple/variable/external/grouped product.
			return parent::prepare_object_for_database( $request, $creating );
		}

		if ( $creating ) {
			// Creating a variation.
			$parent = \wc_get_product( \absint( $request['parent_id'] ) );
			if ( ! $parent ) {
				return new WP_Error( 'woocommerce_rest_invalid_parent_id', sprintf( __( 'The parent ID invalid or empty', 'woocommerce-zapier' ), 'parent_id' ), array( 'status' => 400 ) );
			}

			if ( ! in_array( $parent->get_type(), $this->get_variable_product_types(), true ) ) {
				return new WP_Error(
					'woocommerce_rest_not_supported_parent_id',
					__( 'Parent product does not support variations', 'woocommerce-zapier' ),
					array( 'status' => 400 )
				);
			}
			$parent_id = $parent->get_id();
		} else {
			// Updating a variation.
			$parent_id = $product->get_parent_id();

			// Prevent re-parenting a variation.
			if ( $request['parent_id'] && $request['parent_id'] !== $parent_id ) {
				return new WP_Error(
					'woocommerce_rest_parent_id_cannot_be_updated',
					// translators: %s: variation type.
					\sprintf( __( 'Parent ID cannot be changed on a %s', 'woocommerce-zapier' ), $type ),
					array( 'status' => 400 )
				);
			}
		}

		// map product fields to variation.
		$request->set_param( 'product_id', $parent_id );

		// Map the incoming Product attributes to the Product Variation attribute structure.
		if ( isset( $request['attributes'] ) ) {
			$attributes = $request['attributes'];
			foreach ( $attributes as &$attribute ) {
				// Options array needs to be an `option` string.
				if ( isset( $attribute['options'] ) ) {
					$attribute['option'] = $attribute['options'][0];
					unset( $attribute['options'] );
				}
			}
			$request->set_param( 'attributes', $attributes );
		}

		// Variations only support one image, whereas Products support multiple.
		if ( isset( $request['images'] ) && isset( $request['images'][0] ) ) {
			$request->set_param( 'image', $request['images'][0] );
			$request->set_param( 'images', null );
		}

		return $this->variation_controller->prepare_object_for_database( $request, $creating );
	}

	/**
	 * Item Create.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response REST API Response.
	 */
	public function create_item( $request ) {
		$response = parent::create_item( $request );
		if ( \is_wp_error( $response ) ) {
			$this->log_error_response( $request, $response );
			$resource_id = 0;
			$child_id    = null;
			if ( isset( $request['parent_id'] ) && false !== wc_get_product( (int) $request['parent_id'] ) ) {
				// parent_id has been specified and is a valid product.
				$resource_id = (int) $request['parent_id'];
				$child_id    = 0;
			}
			$this->task_creator->record(
				Event::action_create( $this->resource_type, $response ),
				$resource_id,
				$child_id
			);
			return $response;
		}

		/**
		 * \WP_REST_Response::$data is array.
		 *
		 * @var array $data
		 */
		$data = $response->data;
		if ( \in_array( $data['type'], $this->get_product_variation_types(), true ) ) {
			$id       = $data['parent_id'];
			$child_id = $data['id'];
		} else {
			$id       = $data['id'];
			$child_id = null;
		}
		$this->task_creator->record( Event::action_create( $this->resource_type ), $id, $child_id );
		return $response;
	}

	/**
	 * Item update.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$response = parent::update_item( $request );
		if ( \is_wp_error( $response ) ) {
			$this->log_error_response( $request, $response );

			$resource_id = 0;
			$child_id    = null;
			if ( isset( $request['id'] ) ) {
				$product = wc_get_product( (int) $request['id'] );
				if ( $product && in_array( $product->get_type(), $this->get_variable_product_types(), true ) ) {
					// Updating a top level variable product.
					$resource_id = (int) $request['id'];
				} elseif ( $product && in_array( $product->get_type(), $this->get_product_variation_types(), true ) ) {
					// Updating a variation.
					$resource_id = $product->get_parent_id();
					$child_id    = $product->get_id();
				}
			}

			$this->task_creator->record(
				Event::action_update( $this->resource_type, $response ),
				$resource_id,
				$child_id
			);
			return $response;
		}

		/**
		 * \WP_REST_Response::$data is array.
		 *
		 * @var array $data
		 */
		$data = $response->data;
		if ( \in_array( $data['type'], $this->get_product_variation_types(), true ) ) {
			$id       = $data['parent_id'];
			$child_id = $data['id'];
		} else {
			$id       = $data['id'];
			$child_id = null;
		}
		$this->task_creator->record( Event::action_update( $this->resource_type ), $id, $child_id );
		return $response;
	}
}
