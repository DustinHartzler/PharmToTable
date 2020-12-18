<?php

namespace AutomateWoo;

use AutomateWoo\Rest_Api\Controllers\WorkflowPresets as WorkflowPresetsController;
use AutomateWoo\Rest_Api\Controllers\Workflows as WorkflowsController;
use AutomateWoo\Rest_Api\Controllers\ManualWorkflowRunner as ManualWorkflowRunnerController;
use AutomateWoo\Rest_Api\Utilities\Controller_Namespace;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Rest_Api
 *
 * @since 4.9.0
 */
final class Rest_Api {

	use Controller_Namespace;

	/**
	 * Init AutomateWoo's Rest API.
	 */
	public function init() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ], 15 );
		add_filter( 'rest_namespace_index', [ $this, 'filter_namespace_index' ], 10, 2 );
	}

	/**
	 * Register REST API routes.
	 */
	public function register_routes() {
		foreach ( $this->get_controllers() as $controller ) {
			$controller->register_routes();
		}
	}

	/**
	 * Get REST API controller objects.
	 *
	 * @return WP_REST_Controller[]
	 * @throws Exception When a class doesn't implement the correct interface.
	 */
	private function get_controllers() {
		$classes = [
			WorkflowsController::class,
			ManualWorkflowRunnerController::class,
			WorkflowPresetsController::class,
		];

		$controllers = [];
		foreach ( $classes as $class ) {
			$object = new $class();
			if ( ! $object instanceof WP_REST_Controller ) {
				throw new Exception(
					sprintf( '%s must implement %s', get_class( $object ), WP_REST_Controller::class )
				);
			}

			$controllers[] = $object;
		}

		return $controllers;
	}

	/**
	 * Filter the index response for our namespace.
	 *
	 * @param WP_REST_Response $response The response object for the given request.
	 * @param WP_REST_Request  $request  The current REST Request object.
	 *
	 * @return WP_REST_Response The filtered response object.
	 */
	public function filter_namespace_index( $response, $request ) {
		if ( $this->namespace === $request['namespace'] ) {
			$response->data['info'] = __( 'AutomateWoo API endpoints are still under development and subject to change.', 'automatewoo' );
		}

		return $response;
	}
}
