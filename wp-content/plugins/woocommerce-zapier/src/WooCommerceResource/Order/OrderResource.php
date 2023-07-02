<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Order;

use Automattic\WooCommerce\Blocks\Domain\Services\DraftOrders;
use OM4\WooCommerceZapier\Webhook\Trigger\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;
use WC_Order;

defined( 'ABSPATH' ) || exit;


/**
 * Definition of the Order resource type.
 *
 * @since 2.1.0
 */
class OrderResource extends CustomPostTypeResource {

	/**
	 * {@inheritDoc}
	 */
	public function __construct() {
		$this->key                 = 'order';
		$this->name                = __( 'Order', 'woocommerce-zapier' );
		$this->metabox_screen_name = 'shop_order';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array_merge(
			array(
				new Trigger(
					'order.status_changed',
					__( 'Order status changed (any status)', 'woocommerce-zapier' ),
					array( 'woocommerce_order_status_changed' )
				),
				// Order paid (previously New Order).
				new Trigger(
					'order.paid',
					__( 'Order paid', 'woocommerce-zapier' ),
					array( 'woocommerce_payment_complete' )
				),
			),
			$this->get_status_changed_dynamic_triggers()
		);
	}

	/**
	 * Dynamically create an "Order Status Changed to ..." Trigger Rule,
	 * one for each registered WooCommerce order status.
	 *
	 * @return Trigger[]
	 */
	protected function get_status_changed_dynamic_triggers() {
		$triggers = array();
		foreach ( $this->get_statuses() as $status => $status_label ) {
			$status_key = str_replace( '-', '_', sanitize_title_with_dashes( $status ) );
			$triggers[] = new Trigger(
				"order.status_changed_to_{$status_key}",
				// Translators: Order Status Name/Label.
				sprintf( __( 'Order status changed to %s', 'woocommerce-zapier' ), $status_label ),
				array( "woocommerce_order_status_{$status}" )
			);
		}
		return $triggers;
	}

	/**
	 * Get a list of all registered WooCommerce order statuses.
	 *
	 * This list excludes the following internal statuses:
	 * - The default order status (pending).
	 * - WooCommerce Blocks' "checkout-draft" order status.
	 *
	 * @return array<string, string> Status key excludes the 'wc-' prefix.
	 */
	protected function get_statuses() {
		$statuses = array();

		// List of statuses that should be excluded.
		$excluded_statuses = array(
			// The default order status (pending) because "Order created" is used for that.
			( new WC_Order() )->get_status(),

			/*
			 * WooCommerce Blocks' internal "checkout-draft" order status because
			 * these orders are not visible in the admin.
			 * Link: https://developer.woocommerce.com/2020/11/23/introducing-a-new-order-status-checkout-draft/
			 */
			DraftOrders::STATUS,
		);

		/**
		 * List of default WooCommerce order statuses.
		 * This list is used to exclude statuses that are not built into WooCommerce.
		 *
		 * @see wc_get_order_statuses()
		 */
		$default_statuses = array(
			'wc-pending',
			'wc-processing',
			'wc-on-hold',
			'wc-completed',
			'wc-cancelled',
			'wc-refunded',
			'wc-failed',
		);

		foreach ( \wc_get_order_statuses() as $status => $status_label ) {
			if ( ! in_array( $status, $default_statuses, true ) ) {
				// Status is not a default one built into WooCommerce.
				continue;
			}
			// Use the status without wc- internal prefix.
			$status = 'wc-' === substr( $status, 0, 3 ) ? substr( $status, 3 ) : $status;

			if ( ! in_array( $status, $excluded_statuses, true ) ) {
				$statuses[ $status ] = $status_label;
			}
		}
		return $statuses;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id  Resource ID.
	 * @param int $variation_id Variation ID.
	 *
	 * @return string|null
	 */
	public function get_description( $resource_id, $variation_id = 0 ) {
		$object = \wc_get_order( $resource_id );
		if ( ! is_bool( $object ) && is_a( $object, 'WC_Order' ) && 'trash' !== $object->get_status() ) {
			return \sprintf(
				/* translators: 1: Order ID, 2: Order Formatted Full Billing Name */
				__( 'Order #%1$d (%2$s)', 'woocommerce-zapier' ),
				$object->get_order_number(),
				\trim( $object->get_formatted_billing_full_name() )
			);
		}
		return null;
	}
}
