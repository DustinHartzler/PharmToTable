<?php

namespace OM4\WooCommerceZapier\TaskHistory\Listener;

use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * Improves create/update REST API requests so that they are added to our task history,
 * and also logged if there is an error with the request.
 *
 * Delete API requests aren't currently supported in the Zapier App, so if a delete request
 * occurs then log it.
 *
 * @since 2.0.0
 */
trait APIListenerTrait {

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
			return $response;
		}

		// @phpstan-ignore-next-line Structure comes from WooCommerce.
		$this->task_creator->record( Event::action_update( $this->resource_type ), $response->data['id'] );
		return $response;
	}


	/**
	 * Log a REST API response error.
	 *
	 * @param WP_REST_Request $request REST API Request.
	 * @param WP_Error        $error REST API Error Response.
	 *
	 * @return void
	 */
	protected function log_error_response( $request, $error ) {
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
}
