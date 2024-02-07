<?php

namespace OM4\WooCommerceZapier\TaskHistory\Listener;

use MongoDB\Driver\Exception\InvalidArgumentException;
use OM4\WooCommerceZapier\Exception\InvalidImplementationException;
use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * Improves create/update REST API requests so that they are recorded in our task history,
 * and also recorded and logged if there is an error with the request.
 *
 * Delete API requests aren't currently supported in the Zapier App, so if a delete request
 * occurs then log it.
 *
 * @since 2.0.0
 */
trait APIListenerTrait {

	/**
	 * Add a filter to check for request validation errors.
	 *
	 * @since 2.10.0
	 *
	 * @return void
	 */
	protected function add_filter_to_check_for_request_validation_error() {
		\add_filter(
			'rest_post_dispatch',
			array( $this, 'rest_post_dispatch_check_for_request_validation_error' ),
			10,
			3
		);
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
			$this->task_creator->record(
				Event::action_create( $this->resource_type, $response ),
				0
			);
			return $response;
		}

		// @phpstan-ignore-next-line Structure comes from WooCommerce.
		$this->task_creator->record( Event::action_create( $this->resource_type ), $response->data['id'] );
		return $response;
	}

	/**
	 * Item Delete.
	 *
	 * @uses WP_REST_Controller::delete_item() as parent::delete_item() Delete a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		/**
		 * Deletes an item.
		 *
		 * Return type differs from docblock. Despite the docblock indicating that
		 * the return type might be a boolean, the actual implementation in
		 * WP_REST_Controller::delete_item() does not ever return a boolean.
		 *
		 * @var WP_Error|WP_REST_Response $response
		 */
		$response = parent::delete_item( $request );
		if ( \is_wp_error( $response ) ) {
			$this->log_error_response( $request, $response );
			return $response;
		}
		$this->logger->critical(
			'Unsupported REST API access on resource_id %d, resource_type %s, message: %s',
			array(
				$request['id'],
				$this->resource_type,
				__( 'Deleted via Zapier', 'woocommerce-zapier' ),
			)
		);
		return $response;
	}

	/**
	 * Item update.
	 *
	 * @uses WP_REST_Controller::update_item() as parent::update_item() Update a single item.

	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$response = parent::update_item( $request );
		if ( \is_wp_error( $response ) ) {
			$this->log_error_response( $request, $response );
			$this->task_creator->record( Event::action_update( $this->resource_type, $response ), (int) $request['id'] );
			return $response;
		}

		// @phpstan-ignore-next-line Structure comes from WooCommerce.
		$this->task_creator->record( Event::action_update( $this->resource_type ), $response->data['id'] );
		return $response;
	}

	/**
	 * Log a REST API response error.
	 *
	 * @param WP_REST_Request           $request REST API Request.
	 * @param WP_Error|WP_REST_Response $error REST API Error Response.
	 *
	 * @return void
	 *
	 * @throws InvalidImplementationException If the response is not a WP_Error instance or an array with the expected WP_Error structure.
	 */
	protected function log_error_response( $request, $error ) {
		if ( ! \is_wp_error( $error ) ) {
			$data = $error->get_data();
			if (
				is_array( $data ) &&
				isset( $data['code'] ) &&
				isset( $data['message'] ) &&
				isset( $data['data']['status'] )
			) {
				// A WP_Error style response.
				$error = new WP_Error(
					$data['code'],
					$data['message']
				);
			} else {
				// Should not happen.
				throw new InvalidImplementationException( 'Invalid error response type' );
			}
		}

		$this->logger->error(
			'REST API Error Response for Request Route: %s. Request Method: %s. Resource Type: %s. Error Code: %s. Error Message: %s',
			array(
				$request->get_route(),
				$request->get_method(),
				$this->resource_type,
				$error->get_error_code(),
				$error->get_error_message(),
			)
		);
	}

	/**
	 * Check if the REST API response is an error, and if so, record it in the task history.
	 *
	 * This is necessary for API request schema validation errors, which are performed by WordPress
	 * without our controller create_item() or update_item() method(s) being called.
	 *
	 * Executed during the `rest_post_dispatch` filter.
	 *
	 * @param WP_REST_Response $response  Result to send to the client. Usually a `WP_REST_Response`.
	 * @param WP_REST_Server   $server  Server instance.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 *
	 * @return WP_REST_Response
	 * @see WP_REST_Request::has_valid_params() Which is where the request validation errors are generated.
	 */
	public function rest_post_dispatch_check_for_request_validation_error( $response, $server, $request ) {
		if ( ! $response->is_error() ) {
			// A "success" response, not an error.
			return $response;
		}

		if ( 0 !== \strpos( $response->get_matched_route(), '/' . $this->namespace . '/' . $this->rest_base ) ) {
			// Request URL doesn't match this controller.
			return $response;
		}

		$handler = $response->get_matched_handler();
		if (
			is_array( $handler ) &&
			isset( $handler['callback'][0] ) &&
			! ( $handler['callback'][0] instanceof $this )
		) {
			// A request for another Controller (not this controller).
			// Likely has endpoint URL(s) nested under this controller.
			return $response;
		}

		$data = $response->get_data();
		if (
			is_array( $data ) &&
			isset( $data['code'] ) &&
			isset( $data['message'] ) &&
			isset( $data['data']['status'] )
		) {
			if ( ! \is_string( $data['code'] ) || ! \is_string( $data['message'] ) ) {
				// An unexpected WP_Error style response.
				return $response;
			}

			// Only act on WP_Error codes such as `rest_invalid_param`.
			// Other errors codes are not related to WordPress' request validation,
			// and are handled by the controller itself.
			if ( strpos( $data['code'], 'rest_' ) !== 0 ) {
				return $response;
			}

			$event       = null;
			$resource_id = 0;
			$child_id    = null;
			switch ( $request->get_method() ) {
				case 'POST':
					// A Create Action.
					$event = Event::action_create(
						$this->resource_type,
						new WP_Error( $data['code'], $data['message'] )
					);
					break;
				case 'PUT':
					// An Update Action.
					$event = $this->modify_event(
						Event::action_update(
							$this->resource_type,
							new WP_Error( $data['code'], $data['message'] )
						)
					);
					if ( isset( $request['id'] ) ) {
						$resource_id = (int) $request['id'];
					}
					break;
			}
			if ( \is_null( $event ) ) {
				return $response;
			}
			$this->task_creator->record(
				$event,
				$this->modify_resource_id( $resource_id, $request, $response ),
				$this->modify_child_id( $child_id, $request, $response )
			);
			$this->log_error_response( $request, $response );
		}
		return $response;
	}

	/**
	 * Modify the event object for this controller.
	 *
	 * This method can be overridden by each controller to modify the event object before the unsuccessful
	 * Task History record is created in rest_post_dispatch_check_for_request_validation_error().
	 *
	 * @since 2.10.0
	 *
	 * @see rest_post_dispatch_check_for_request_validation_error();
	 *
	 * @param  Event $event The event object instance.
	 *
	 * @return Event
	 */
	protected function modify_event( $event ) {
		return $event;
	}

	/**
	 * Modify the resource ID that is used when creating an unsuccessful Task History record.
	 *
	 * This method can be overridden by each controller to modify the resource ID if required.
	 *
	 * @param int              $resource_id  The resource ID.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 * @param WP_REST_Response $response  Result to send to the client.
	 *
	 * @return int
	 * @since 2.10.0
	 */
	protected function modify_resource_id( $resource_id, $request, $response ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return $resource_id;
	}

	/**
	 * Modify the child ID that is used when creating an unsuccessful Task History record.
	 *
	 * This method can be overridden by each controller to modify the resource ID if required.
	 *
	 * @param ?int             $child_id  The resource ID.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 * @param WP_REST_Response $response  Result to send to the client.
	 *
	 * @return ?int
	 * @since 2.10.0
	 */
	protected function modify_child_id( $child_id, $request, $response ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return $child_id;
	}
}
