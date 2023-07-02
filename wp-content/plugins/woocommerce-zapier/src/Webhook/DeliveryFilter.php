<?php

namespace OM4\WooCommerceZapier\Webhook;

use OM4\WooCommerceZapier\Helper\HTTPHeaders;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Webhook\ZapierWebhook;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Improvements to WooCommerce Core's webhook delivery mechanism:
 *
 * Sends an `X-WordPress-GMT-Offset header so that triggers can interpret dates correctly.
 * Logs the delivery and payload when the output is an error.
 *
 * @since 2.0.0
 */
class DeliveryFilter {

	/**
	 * HTTPHeaders instance.
	 *
	 * @var HTTPHeaders
	 */
	protected $http_headers;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor.
	 *
	 * @param HTTPHeaders $http_headers HTTPHeaders instance.
	 * @param Logger      $logger       Logger instance.
	 */
	public function __construct( HTTPHeaders $http_headers, Logger $logger ) {
		$this->http_headers = $http_headers;
		$this->logger       = $logger;
	}

	/**
	 * Initialise our functionality by hooking into the relevant WooCommerce hooks/filters.
	 *
	 * @return void
	 */
	public function initialise() {
		add_filter( 'woocommerce_webhook_http_args', array( $this, 'woocommerce_webhook_http_args' ), 10, 3 );
		add_filter( 'woocommerce_webhook_payload', array( $this, 'woocommerce_webhook_payload' ), 11, 4 );
	}

	/**
	 * For all WooCommerce Zapier webhook deliveries to Zapier, include our HTTP headers.
	 *
	 * @param array $http_args HTTP request args.
	 * @param mixed $arg Webhook arg (usually the resource ID).
	 * @param int   $webhook_id Webhook ID.
	 *
	 * @return array
	 */
	public function woocommerce_webhook_http_args( $http_args, $arg, $webhook_id ) {
		$webhook = new ZapierWebhook( $webhook_id );
		if ( ! $webhook->is_zapier_webhook() ) {
			return $http_args;
		}
		foreach ( $this->http_headers->get_headers() as $header_name => $header_value ) {
			$http_args['headers'][ $header_name ] = $header_value;
		}
		return $http_args;
	}

	/**
	 * Log our webhook payloads Debug level for successful, error level for unsuccessful.
	 *
	 * @since 2.4.2
	 *
	 * @param array|WP_Error $payload      Data to be sent out by the webhook.
	 * @param string         $resource_key Type/name of the resource.
	 * @param integer        $resource_id  ID of the resource.
	 * @param integer        $webhook_id   ID of the webhook.
	 *
	 * @return array|WP_Error
	 */
	public function woocommerce_webhook_payload( $payload, $resource_key, $resource_id, $webhook_id ) {
		$webhook = new ZapierWebhook( $webhook_id );
		if ( $webhook->is_zapier_webhook() ) {
			if ( $payload instanceof WP_Error ||
			( is_array( $payload ) && isset( $payload['code'] ) && isset( $payload['message'] ) && isset( $payload['data']['status'] ) )
			) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
				$this->logger->error( 'Unexpected WP_Error payload for Webhook ID: %d - %s ID: %d - Content: %s', array( $webhook_id, \ucfirst( $resource_key ), $resource_id, var_export( $payload, true ) ) );
			} else {
				$this->logger->debug( 'Webhook delivery attempt for Webhook ID: %d - %s ID: %d', array( $webhook_id, \ucfirst( $resource_key ), $resource_id ) );
			}
		}
		return $payload;
	}
}
