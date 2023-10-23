<?php

namespace OM4\WooCommerceZapier\Webhook;

use OM4\WooCommerceZapier\Webhook\ZapierWebhook;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

/**
 * Represents an individual REST API based Payload.
 *
 * Payload builds output for `woocommerce_webhook_payload` filter.
 *
 * @since 2.2.0
 * @since 2.8.0 Consolidated all payload classes
 */
class Payload {

	/**
	 * Resource's key (internal name/type).
	 *
	 * Must be a-z lowercase characters only, and in singular (non plural) form.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Controller instance.
	 *
	 * @var WC_REST_Controller
	 */
	protected $controller;

	/**
	 * Payload constructor.
	 *
	 * @param string             $key        Resource Key.
	 * @param WC_REST_Controller $controller Controller instance.
	 */
	public function __construct( $key, $controller ) {
		$this->key        = $key;
		$this->controller = $controller;
	}

	/**
	 * Build payload upon webhook delivery.
	 *
	 * Compatible with `woocommerce_webhook_payload` filter.
	 *
	 * @param array|WP_Error $payload       Data to be sent out by the webhook.
	 * @param string         $resource_type Type/name of the resource.
	 * @param integer        $resource_id   ID of the resource.
	 * @param integer        $webhook_id    ID of the webhook.
	 *
	 * @return array|WP_Error
	 */
	public function build( $payload, $resource_type, $resource_id, $webhook_id ) {
		if ( ! empty( $payload ) ) {
			// Payload already built.
			return $payload;
		}

		$webhook = new ZapierWebhook( $webhook_id );
		if ( ! $webhook->is_zapier_webhook() ) {
			return $payload;
		}

		if ( 'deleted' === $webhook->get_event() ) {
			return array( 'id' => $resource_id );
		}

		// Switch user.
		$current_user = get_current_user_id();
		// phpcs:ignore Generic.PHP.ForbiddenFunctions.Discouraged
		wp_set_current_user( $webhook->get_user_id() );

		$request = new WP_REST_Request( 'GET' );
		$request->set_param( 'id', $resource_id );

		// Permissions check.
		$result = $this->controller->get_item_permissions_check( $request );
		if ( $result instanceof WP_Error ) {
			// Restore current user.
			// phpcs:ignore Generic.PHP.ForbiddenFunctions.Discouraged
			wp_set_current_user( $current_user );
			return $result;
		}

		// Build payload.
		$result = $this->controller->get_item( $request );

		if ( $result instanceof WP_Error ) {
			// Restore current user.
			// phpcs:ignore Generic.PHP.ForbiddenFunctions.Discouraged
			wp_set_current_user( $current_user );
			return $result;
		}

		/**
		 * Build payload.
		 *
		 * @var array $payload
		 */
		$payload = $result->data;

		// Restore current user.
		// phpcs:ignore Generic.PHP.ForbiddenFunctions.Discouraged
		wp_set_current_user( $current_user );

		return $payload;
	}
}
