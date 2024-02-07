<?php

namespace OM4\WooCommerceZapier\Webhook;

use OM4\WooCommerceZapier\Helper\HTTPHeaders;
use OM4\WooCommerceZapier\Webhook\ZapierWebhook;

defined( 'ABSPATH' ) || exit;

/**
 * Improvements to WooCommerce Core's webhook delivery mechanism:
 *
 * Sends an `X-WordPress-GMT-Offset header so that triggers can interpret dates correctly.
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
	 * Constructor.
	 *
	 * @param HTTPHeaders $http_headers HTTPHeaders instance.
	 */
	public function __construct( HTTPHeaders $http_headers ) {
		$this->http_headers = $http_headers;
	}

	/**
	 * Initialise our functionality by hooking into the relevant WooCommerce hooks/filters.
	 *
	 * @return void
	 */
	public function initialise() {
		add_filter( 'woocommerce_webhook_http_args', array( $this, 'woocommerce_webhook_http_args' ), 10, 3 );
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
		if ( \is_scalar( $arg ) ) {
			$http_args['headers']['X-WC-Webhook-Resource-ID'] = \strval( $arg );
		}
		return $http_args;
	}
}
