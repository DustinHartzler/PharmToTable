<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Memberships\User;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Memberships\User\UserMembershipsTaskCreator;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use SkyVerge\WooCommerce\Memberships\API\v3\User_Memberships;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * Exposes WooCommerce Memberships' REST API v3 User Membership endpoint via the WooCommerce Zapier endpoint namespace.
 *
 * @since 2.10.0
 */
class Controller extends User_Memberships {

	use APIListenerTrait {
		delete_item as delete_item_listener;
	}

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'user_membership';


	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'members';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * UserMembershipsTaskCreator instance.
	 *
	 * @var UserMembershipsTaskCreator
	 */
	protected $task_creator;

	/**
	 * Constructor.
	 *
	 * @param  Logger                     $logger       Logger instance.
	 * @param  UserMembershipsTaskCreator $task_creator TaskCreator instance.
	 */
	public function __construct( Logger $logger, UserMembershipsTaskCreator $task_creator ) {
		parent::__construct();
		$this->logger       = $logger;
		$this->task_creator = $task_creator;
		$this->namespace    = 'wc-zapier/v1/memberships';

		add_filter( 'rest_post_dispatch', array( $this, 'rest_post_dispatch' ), 10, 3 );
		$this->add_filter_to_check_for_request_validation_error();
	}

	/**
	 * Add our `wcz_meta_v1` information to OPTIONS requests.
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
		$key = \array_search( 'GET', $result->data['methods'], true );
		if ( false === $key ) {
			return $result;
		}

		if ( $result->get_matched_route() === '/' . $this->namespace . '/' . $this->rest_base ) {
			// Overwrite Label for the `product` parameter to match with Order Id field.
			// @phpstan-ignore-next-line Schema structure comes from WordPress.
			$result->data['endpoints'][ $key ]['args']['product']['wcz_meta_v1']['field_properties']['label'] = 'Product Id';

			// @phpstan-ignore-next-line Schema structure comes from WordPress.
			if ( isset( $result->data['endpoints'][ $key ]['args']['subscription'] ) ) {
				// Overwrite Label for the `subscription` parameter to match with Order Id field.
				// @phpstan-ignore-next-line Schema structure comes from WordPress.
				$result->data['endpoints'][ $key ]['args']['subscription']['wcz_meta_v1']['field_properties']['label'] = 'Subscription Id';
			}
		}

		return $result;
	}

	/**
	 * Get the query params for User Membership list/search.
	 *
	 * - Rename `order` to `order_id` to avoid conflict with the sort order parameter.
	 * - Adds in missing `order`/`orderby` parameters to make it consistent with other resources.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		// Replace the `order` with the `order_id` array key, maintaining the order of the array keys.
		$order_data  = $params['order'];
		$order_index = array_search( 'order', array_keys( $params ), true );
		if ( false !== $order_index ) {
			$params = \array_merge(
				\array_slice( $params, 0, $order_index, true ),
				array( 'order_id' => $order_data ),
				\array_slice( $params, $order_index + 1, null, true )
			);
		}

		// WooCommerce Memberships supports the `order` and `orderby` parameters, but doesn't document them in the schema.
		$params['order']   = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array( 'asc', 'desc' ),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['orderby'] = array(
			'description'       => __( 'Sort collection by object attribute.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'default'           => 'date',
			'enum'              => array(
				'date',
				'id',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Implement the search functionality, overriding default WP_Query arguments.
	 *
	 * @param  array           $prepared_args  Prepared arguments.
	 * @param  WP_REST_Request $request  Request object.
	 *
	 * @return array          $query_args
	 */
	protected function prepare_items_query( $prepared_args = array(), $request = \null ) {
		$query_args = parent::prepare_items_query( $prepared_args, $request );

		/**
		 * Search by order ID.
		 * Memberships expects to use the `order` parameter for this, but this controller uses the `order_id` parameter.
		 *
		 * @see User_Memberships_Controller::prepare_items_query_args()
		 */
		if ( ! empty( $request['order_id'] ) ) {
			if ( ! isset( $query_args['meta_query'] ) ) {
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				$query_args['meta_query'] = array();
			}

			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$query_args['meta_query'][] = array(
				'key'   => '_order_id',
				'value' => (int) $request['order_id'],
				'type'  => 'numeric',
			);
			$query_args['order']        = $request['order'];
		}
		return $query_args;
	}

	/**
	 * Deletes a memberships item upon REST API request.
	 *
	 * @see \SkyVerge\WooCommerce\Memberships\API\Controller::delete_item()
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_Error|\WP_REST_Response Response object or error object
	 */
	public function delete_item( $request ) {
		$response = $this->delete_item_listener( $request );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$response_data = $response->get_data();
		// @phpstan-ignore-next-line The $response_data is an array.
		$response->set_data( $response_data['previous'] );
		return $response;
	}
}
