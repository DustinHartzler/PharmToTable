<?php

namespace OM4\WooCommerceZapier\Plugin\Subscriptions;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionsTaskCreator;
use OM4\WooCommerceZapier\Plugin\Subscriptions\WCSV1Controller;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use WC_REST_Subscriptions_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * Exposes Woo Subscriptions' REST API v1 Subscriptions endpoint via the WooCommerce Zapier endpoint namespace.
 *
 * Uses Subscriptions' V3 REST API functionality for database interactions to ensure compatibility with HPOS,
 * but uses the V1 schema and response structures to ensure compatibility with existing Zaps.
 *
 * @since 2.1.0
 */
class V1Controller extends WC_REST_Subscriptions_Controller {

	use APIListenerTrait {
		delete_item as delete_item_with_logging;
	}

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
	protected $resource_type = 'subscription';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * SubscriptionsTaskCreator instance.
	 *
	 * @var SubscriptionsTaskCreator
	 */
	protected $task_creator;

	/**
	 * WCSV1Controller instance.
	 *
	 * @var WCSV1Controller
	 */
	protected $wcs_v1_controller;

	/**
	 * Constructor.
	 *
	 * @since 2.7.0 added $wcs_v1_controller parameter.
	 *
	 * @param  Logger                   $logger            Logger instance.
	 * @param  SubscriptionsTaskCreator $task_creator      TaskCreator instance.
	 * @param  WCSV1Controller          $wcs_v1_controller WCSV1Controller instance.
	 */
	public function __construct( Logger $logger, SubscriptionsTaskCreator $task_creator, WCSV1Controller $wcs_v1_controller ) {
		$this->logger            = $logger;
		$this->task_creator      = $task_creator;
		$this->wcs_v1_controller = $wcs_v1_controller;
		$this->add_filter_to_check_for_request_validation_error();
	}

	/**
	 * Get a single subscription.
	 *
	 * @since 2.7.1
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$id   = (int) $request['id'];
		$post = wcs_get_subscription( $id );

		if ( ! $post ) {
			return new WP_Error(
				'woocommerce_rest_shop_subscription_invalid_id',
				__( 'Invalid ID.', 'woocommerce-zapier' ),
				array( 'status' => 404 )
			);
		}

		$response = $this->get_v1_subscription_data( $id, $request );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		if ( $this->public ) {
			$response->link_header( 'alternate', (string) get_permalink( $id ), array( 'type' => 'text/html' ) );
		}

		return $response;
	}

	/**
	 * Get a collection of subscriptions.
	 *
	 * @since 2.7.1
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$response = parent::get_items( $request );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		/**
		 * Array of subscriptions (each in V3 structure).
		 *
		 * @var array[] $data
		 */
		$data = $response->get_data();
		foreach ( $data as & $subscription ) {
			$r = $this->get_v1_subscription_data( $subscription['id'], $request );
			if ( is_wp_error( $r ) ) {
				return $r;
			}
			$subscription = $r->get_data();
		}
		$response->set_data( $data );
		return $response;
	}

	/**
	 * Load V1 schema for an individual Subscription object.
	 *
	 * @since 2.7.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		return $this->wcs_v1_controller->get_item_schema();
	}

	/**
	 * Load V1 schema for list/search.
	 *
	 * @since 2.7.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = $this->wcs_v1_controller->get_collection_params();
		if ( isset( $params['filter'] ) ) {
			/**
			 * Remove the `filter` parameter that is added by WooCommerce core.
			 * Description: "Use WP Query arguments to modify the response" filter parameter.
			 * This parameter has not existed previously because the namespace has never been `wc/v1`.
			 *
			 * @see \WC_REST_Posts_Controller::get_collection_params()
			 */
			unset( $params['filter'] );
		}
		return $params;
	}

	/**
	 * Create a single item.
	 *
	 * @since  2.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		// Create a subscription using the V1 controller.
		$response = $this->wcs_v1_controller->create_item( $request );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->add_subscription_fields_to_order( $response, $request );
	}

	/**
	 * Update a single item.
	 *
	 * @since  2.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		// Update the subscription using the V1 controller.
		$response = $this->wcs_v1_controller->update_item( $request );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->add_subscription_fields_to_order( $response, $request );
	}

	/**
	 * Delete a single subscription.
	 *
	 * @since  2.7.1
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		// Get a V1 copy of the subscription before it is deleted.
		$response = $this->get_item( $request );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$data = $response->get_data();

		$response = $this->delete_item_with_logging( $request );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$response->set_data( $data );
		return $response;
	}

	/**
	 * Prepare objects query.
	 *
	 * Translates an incoming V1 GET request into a V3 request.
	 *
	 * Executed for list/search/get requests.
	 *
	 * @since 2.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$request->set_param( 'status', array( $request['status'] ) );
		return parent::prepare_objects_query( $request );
	}

	/**
	 * Add subscription fields an existing response (which is an order).
	 *
	 * @param WP_REST_Response $response Response object.
	 * @param  WP_REST_Request  $request  Request object.
	 *
	 * @return WP_REST_Response
	 */
	private function add_subscription_fields_to_order( $response, WP_REST_Request $request ) {
		/**
		 * Response data is incorrect when HPOS is enabled - it is an order object with no extra subscription fields.
		 * This is because the `woocommerce_rest_prepare_shop_subscription` filter is not applied.
		 * Manually apply the filter to fix the response data.
		 */
		// @phpstan-ignore-next-line Structure comes from WooCommerce.
		$subscription_id = $response->data['id'];
		$post            = new \WP_Post(
			(object) array(
				'ID'        => $subscription_id,
				'post_type' => 'shop_subscription',
			)
		);

		/**
		 * REST API Response.
		 *
		 * @var WP_REST_Response $response
		 */
		$response = $this->wcs_v1_controller->filter_get_subscription_response( $response, $post, $request );
		return $response;
	}

	/**
	 * Gets a V1 subscription response that includes a V1 subscription object.
	 *
	 * @param  int             $subscription_id  Subscription ID.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	private function get_v1_subscription_data( $subscription_id, WP_REST_Request $request ) {
		// @phpstan-ignore-next-line  WC_REST_Orders_V1_Controller::prepare_item_for_response() also accepts an int.
		$data     = $this->wcs_v1_controller->prepare_item_for_response( $subscription_id, $request );
		$response = rest_ensure_response( $data );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->add_subscription_fields_to_order( $response, $request );
	}
}
