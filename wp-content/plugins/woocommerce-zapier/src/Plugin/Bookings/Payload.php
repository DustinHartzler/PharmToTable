<?php

namespace OM4\WooCommerceZapier\Plugin\Bookings;

use OM4\WooCommerceZapier\Plugin\Bookings\V1Controller;
use OM4\WooCommerceZapier\Webhook\Payload\Definition;
use WC_Webhook;
use WP_Error;
use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

/**
 * Implements an individual REST API based Payload definition.
 *
 * Payload builds output for `woocommerce_webhook_payload` filter.
 *
 * @since 2.2.0
 */
class Payload implements Definition {

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
	 * @var V1Controller
	 */
	protected $controller;

	/**
	 * Payload constructor.
	 *
	 * @param string       $key        Resource Key.
	 * @param V1Controller $controller V1Controller instance.
	 */
	public function __construct( $key, $controller ) {
		$this->key        = $key;
		$this->controller = $controller;
	}

	/**
	 * Build payload upon webhook delivery.
	 *
	 * @param array|WP_Error $payload       Data to be sent out by the webhook.
	 * @param string         $resource_type Type/name of the resource.
	 * @param integer        $resource_id   ID of the resource.
	 * @param integer        $webhook_id    ID of the webhook.
	 *
	 * @return array|WP_Error
	 */
	public function build( $payload, $resource_type, $resource_id, $webhook_id ) {
		if ( $this->key === $resource_type && empty( $payload ) && get_wc_booking( $resource_id ) ) {
			// Force apply `woocommerce_webhook_event` filter.
			$webhook = new WC_Webhook( $webhook_id );
			$event   = $webhook->get_event();

			// switch user.
			$current_user = get_current_user_id();
			wp_set_current_user( $webhook->get_user_id() );

			$request = new WP_REST_Request( 'GET' );
			$request->set_param( 'id', $resource_id );
			$result = $this->controller->get_item( $request );

			// Handle API error.
			if ( $result instanceof WP_Error ) {
				return $result;
			}

			/**
			 * Build payload.
			 *
			 * @var array $payload
			 */
			$payload = 'deleted' === $event ? array( 'id' => $resource_id ) : $result->data;

			// Restore current user.
			wp_set_current_user( $current_user );
		}
		return $payload;
	}
}
