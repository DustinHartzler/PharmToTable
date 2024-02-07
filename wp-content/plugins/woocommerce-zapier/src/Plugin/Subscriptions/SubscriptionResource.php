<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Subscriptions;

use Automattic\WooCommerce\Utilities\OrderUtil;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionsTaskCreator;
use OM4\WooCommerceZapier\Plugin\Subscriptions\V1Controller;
use OM4\WooCommerceZapier\Webhook\Payload;
use OM4\WooCommerceZapier\Webhook\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\Base;
use WC_REST_Subscriptions_Controller;
use WC_REST_Subscriptions_V1_Controller;
use WC_Subscription;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Subscription resource type.
 *
 * This resource is only enabled if Woo Subscriptions is available.
 *
 * Woo Subscriptions has webhook payload, topic and delivery functionality built-in,
 * so this class extends the built-in trigger rules.
 *
 * @since 2.2.0
 */
class SubscriptionResource extends Base {

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * Controller instance.
	 *
	 * @var V1Controller
	 */
	protected $controller;

	/**
	 * {@inheritDoc}
	 *
	 * @since 2.7.0 Added $controller parameter.
	 *
	 * @param FeatureChecker $checker    FeatureChecker instance.
	 * @param V1Controller   $controller V1Controller instance.
	 */
	public function __construct( FeatureChecker $checker, V1Controller $controller ) {
		$this->checker    = $checker;
		$this->controller = $controller;
		$this->key        = SubscriptionsTaskCreator::resource_type();
		$this->name       = SubscriptionsTaskCreator::resource_name();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_controller_name() {
		return V1Controller::class;
	}

	/**
	 * Get the Subscriptions REST API controller's REST API version.
	 *
	 * Subscriptions uses a REST API v1 payload.
	 *
	 * This is because the Subscriptions endpoint is a REST API v1 controller, we need to always deliver a v1 payload
	 * and not a v3 payload that is introduced in Subscriptions v3.1.
	 *
	 * @inheritDoc
	 */
	public function get_controller_rest_api_version() {
		return 1;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array_merge(
			array(
				new Trigger(
					'subscription.status_changed',
					__( 'Subscription status changed (any status)', 'woocommerce-zapier' ),
					// `woocommerce_subscription_status_updated` hook with our own prefix/handler to convert the arg from a WC_Subscription object to a subscription ID.
					array( 'wc_zapier_woocommerce_subscription_status_updated' )
				),
				new Trigger(
					'subscription.renewed',
					__( 'Subscription renewed', 'woocommerce-zapier' ),
					// `woocommerce_subscription_renewal_payment_complete` hook with our own prefix/handler to convert the arg from a WC_Subscription object to a subscription ID.
					array( 'wc_zapier_woocommerce_subscription_renewal_payment_complete' )
				),
				new Trigger(
					'subscription.renewal_failed',
					__( 'Subscription renewal failed', 'woocommerce-zapier' ),
					// `woocommerce_subscription_renewal_payment_failed` hook with our own prefix/handler to convert the arg from a WC_Subscription object to a subscription ID.
					array( 'wc_zapier_woocommerce_subscription_renewal_payment_failed' )
				),
			),
			$this->get_status_changed_dynamic_triggers()
		);
	}

	/**
	 * Dynamically create a "Subscription Status Changed to ..." Trigger Rule,
	 * one for each registered Woo Subscriptions status.
	 *
	 * @return Trigger[]
	 */
	protected function get_status_changed_dynamic_triggers() {
		$triggers = array();
		foreach ( $this->get_statuses() as $status => $status_label ) {
			$status_key = str_replace( '-', '_', sanitize_title_with_dashes( $status ) );
			$triggers[] = new Trigger(
				"subscription.status_changed_to_{$status_key}",
				// Translators: Subscription Status Name/Label.
				sprintf( __( 'Subscription status changed to %s', 'woocommerce-zapier' ), $status_label ),
				// `woocommerce_subscription_status_*` hook with our own prefix/handler to convert the arg from a WC_Subscription object to a subscription ID.
				array( "wc_zapier_woocommerce_subscription_status_{$status}" )
			);
		}
		return $triggers;
	}

	/**
	 * Get a list of all registered Woo Subscriptions statuses.
	 * This list excludes the following internal statuses:
	 * - The default subscription status (pending).
	 * - The "switched" status because it is no longer used in Woo Subscriptions v2.0 and newer.
	 *
	 * @return array<string, string> Status key excludes the 'wc-' prefix.
	 */
	protected function get_statuses() {
		$statuses = array();
		// List of statuses that should be excluded.
		$excluded_statuses = array(
			// The default subscription status (pending) because "Subscription created" is used for that.
			( new WC_Subscription( 0 ) )->get_status(),

			/*
			 * Exclude the "switched" status because it was only used Subscriptions earlier than 2.0.
			 * Link: https://woo.com/document/subscriptions/statuses/#section-7
			 */
			'switched',
		);
		foreach ( \wcs_get_subscription_statuses() as $status => $status_label ) {
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
	 */
	public function get_webhook_payload() {
		return new Payload( $this->key, $this->controller );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 2.7.0
	 *
	 * @param int $resource_id Resource ID.
	 */
	public function get_admin_url( $resource_id ) {
		if ( $this->checker->is_hpos_enabled() ) {
			return OrderUtil::get_order_admin_edit_url( $resource_id );
		}
		return \admin_url( "post.php?post={$resource_id}&action=edit" );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 2.7.0
	 */
	public function get_metabox_screen_name() {
		if ( $this->checker->is_hpos_enabled() ) {
			/**
			 * HPOS enabled screen name.
			 *
			 * @see \wc_get_page_screen_id()
			 *
			 * We don't use the above function because it is only available in an admin context.
			 */
			return 'woocommerce_page_wc-orders--shop_subscription';
		}
		// HPOS disabled screen name.
		return 'shop_subscription';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param WC_Subscription|WP_Post $object Subscription instance.
	 *
	 * @return int
	 * @since 2.7.1
	 */
	public function get_resource_id_from_object( $object ) {
		return is_callable( array( $object, 'get_id' ) ) ? $object->get_id() : $object->ID;
	}
}
