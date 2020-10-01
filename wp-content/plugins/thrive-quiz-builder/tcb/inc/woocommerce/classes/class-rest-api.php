<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\Integrations\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Rest_Api
 *
 * @package Thrive\Theme\Integrations\WooCommerce
 */
class Rest_Api {
	public static $namespace = 'tcb/v1';
	public static $route = '/woo';

	public static function register_routes() {
		register_rest_route( self::$namespace, self::$route, array(
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( __CLASS__, 'render' ),
				'permission_callback' => array( __CLASS__, 'route_permission' ),
			),
		) );
	}

	/**
	 * Render the WooCommerce shop
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_REST_Response
	 */
	public static function render( $request ) {

		$content = '';
		$fn      = $request->get_param( 'fn' );

		if ( $fn === 'woocommerce_content' ) {
			$args = $request->get_param( 'args' );

			Main::init_frontend_woo_functionality();

			$content = Shortcodes\Shop\Main::render( $args );
		}

		return new \WP_REST_Response( array( 'content' => $content ), 200 );
	}

	/**
	 * Check if a given request has access to this route
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|bool
	 */
	public static function route_permission( $request ) {
		return \TCB_Product::has_external_access();
	}
}
