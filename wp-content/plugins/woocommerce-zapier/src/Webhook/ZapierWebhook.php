<?php

namespace OM4\WooCommerceZapier\Webhook;

use WC_Webhook;

defined( 'ABSPATH' ) || exit;

/**
 * Represents a single WooCommerce Webhook instance, with helper methods to determine
 * whether the Webhook is one that was created by Zapier.
 *
 * @since 2.0.0
 */
class ZapierWebhook extends WC_Webhook {

	/**
	 * Whether or not the specified WooCommerce webhook is one that was created
	 * by the WooCommerce Zapier integration.
	 *
	 * @return boolean
	 */
	public function is_zapier_webhook() {
		if ( false === strpos( $this->get_name(), 'Zapier #' ) ) {
			return false;
		}
		if ( false === strpos( $this->get_delivery_url(), 'hooks.zapier.com' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get a Zap ID from the specified webhook.
	 * The webhook name contains the Zap number (ID) in the format Zapier #{123}
	 *
	 * @return string
	 */
	public function get_zap_id() {
		preg_match( '/^Zapier #([0-9]+)/', $this->get_name(), $matches );
		if ( isset( $matches[1] ) ) {
			return $matches[1];
		}
		return '';
	}

	/**
	 * Get the URL to the Zap that the specified Webhook uses.
	 *
	 * @return string
	 */
	public function get_zap_url() {
		$id = $this->get_zap_id();
		if ( strlen( $id ) > 0 ) {
			return "https://zapier.com/app/editor/$id";
		}
		return '';
	}

}
