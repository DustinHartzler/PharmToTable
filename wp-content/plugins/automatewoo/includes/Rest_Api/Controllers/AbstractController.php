<?php


namespace AutomateWoo\Rest_Api\Controllers;

use AutomateWoo\Rest_Api\Utilities\Controller_Namespace;
use Closure;
use Exception;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract AutomateWoo REST controller.
 *
 * @since 4.9.0
 */
abstract class AbstractController extends WP_REST_Controller {

	use Controller_Namespace;

	/**
	 * Determine whether the request has the given parameter.
	 *
	 * This is a polyfill for the WP_REST_Request::has_param() method, which was added in
	 * WordPress 5.3. If the request object has the has_param() method, we will use it directly.
	 * Otherwise, we'll use a custom closure to obtain the same information from the object.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @param string          $param   The parameter name.
	 *
	 * @return mixed
	 */
	protected function request_has_param( WP_REST_Request $request, $param ) {
		if ( method_exists( $request, 'has_param' ) ) {
			return $request->has_param( $param );
		} else {
			$closure = $this->get_bound_closure( $request );
			return $closure( $param );
		}
	}

	/**
	 * Get a closure bound to a request object.
	 *
	 * @param WP_REST_Request $request The current request object.
	 *
	 * @return Closure A closure bound to the given request object.
	 */
	private function get_bound_closure( WP_REST_Request $request ) {
		return Closure::bind( $this->get_request_closure(), $request, $request );
	}

	/**
	 * Get a closure that can be bound to a WP_REST_Request object.
	 *
	 * @return Closure
	 */
	private function get_request_closure() {
		return function( $key ) {
			$order = $this->get_parameter_order();

			foreach ( $order as $type ) {
				if ( array_key_exists( $key, $this->params[ $type ] ) ) {
					return true;
				}
			}

			return false;
		};
	}

	/**
	 * Get rest error from an exception.
	 *
	 * @since 5.0.0
	 *
	 * @param Exception $exception
	 *
	 * @return WP_Error
	 */
	protected function get_rest_error_from_exception( Exception $exception ) {
		return new WP_Error( 'rest_error', $exception->getMessage() ?: __( 'Unknown error.', 'automatewoo' ) );
	}

}
