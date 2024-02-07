<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Subscriptions;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Base;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\SubscriptionNoteResource;
use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionResource;
use WC_Subscription;
use WC_Subscriptions;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality that is enabled when the Woo Subscriptions plugin is active.
 *
 * @since 2.0.0
 */
class Plugin extends Base {

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * ContainerService instance.
	 *
	 * @var ContainerService
	 */
	protected $container;

	/**
	 * Name of the third party plugin.
	 */
	const PLUGIN_NAME = 'Woo Subscriptions';

	/**
	 * The minimum Woo Subscriptions version that this plugin supports.
	 */
	const MINIMUM_SUPPORTED_VERSION = '4.2.0';

	/**
	 * Constructor.
	 *
	 * @param FeatureChecker   $checker FeatureChecker instance.
	 * @param Logger           $logger Logger instance.
	 * @param ContainerService $container ContainerService instance.
	 */
	public function __construct( FeatureChecker $checker, Logger $logger, ContainerService $container ) {
		$this->checker     = $checker;
		$this->logger      = $logger;
		$this->container   = $container;
		$this->resources[] = SubscriptionResource::class;
		$this->resources[] = SubscriptionNoteResource::class;
	}

	/**
	 * Instructs the Subscriptions functionality to initialise itself.
	 *
	 * @return bool
	 */
	public function initialise() {
		if ( ! parent::initialise() ) {
			return false;
		}

		add_filter(
			'wc_zapier_variable_product_types_to_variation_types',
			array( $this, 'add_variable_product_types_to_variation_types' )
		);

		foreach ( $this->container->get( SubscriptionResource::class )->get_webhook_triggers() as $trigger ) {
			foreach ( $trigger->get_actions() as $action ) {
				if ( 0 === strpos( $action, 'wc_zapier_' ) ) {
					$action = str_replace( 'wc_zapier_', '', $action );
					add_action( $action, array( $this, 'convert_arg_to_subscription_id_then_execute' ) );
				}
			}
		}
		return true;
	}

	/**
	 * Add the variable subscription product and variation type to the list of supported product types.
	 *
	 * Executed via the `wc_zapier_variable_product_types_to_variation_types` filter.
	 *
	 * @see \WC_Product_Variable_Subscription::get_type()
	 * @see \WC_Product_Subscription_Variation::get_type()
	 * @since 2.5.1
	 *
	 * @param array<string, string> $types The allowed product variation types.
	 *
	 * @return array<string, string> The key is the variable product type, the value is the variation type.
	 */
	public function add_variable_product_types_to_variation_types( $types ) {
		$types['variable-subscription'] = 'subscription_variation';
		return $types;
	}

	/**
	 * Get the Woo Subscriptions version number.
	 *
	 * @return string
	 */
	public function get_plugin_version() {
		return WC_Subscriptions::$version;
	}

	/**
	 * Whenever a relevant Woo Subscriptions built-in action/event occurs,
	 * convert the args WC_Subscription object into a numerical subscription ID,
	 * and then trigger our own built-in action which then queues the webhook for delivery.
	 *
	 * @param WC_Subscription $arg Subscription object.
	 *
	 * @return void
	 */
	public function convert_arg_to_subscription_id_then_execute( $arg ) {
		if ( ! is_a( $arg, WC_Subscription::class ) ) {
			return;
		}
		$arg = $arg->get_id();
		/**
		 * Execute the WooCommerce Zapier handler for this hook/action.
		 *
		 * @internal
		 * @since 2.0.4
		 *
		 * @param int $arg Subscription ID.
		 */
		do_action( 'wc_zapier_' . current_action(), $arg );
	}

	/**
	 * Remove Subscriptions endpoints that are not required by WooCommerce Zapier, including:
	 *
	 * - /wc-zapier/v1/subscriptions/(?P<id>[\d]+)/orders
	 * - /wc-zapier/v1/subscriptions/statuses
	 *
	 * @param array $endpoints Registered WP REST API endpoints.
	 *
	 * @return array
	 */
	public function filter_rest_endpoints( $endpoints ) {
		foreach ( $endpoints as $route => $endpoint ) {
			if ( 0 === strpos( $route, sprintf( '/%s/subscriptions/', API::REST_NAMESPACE ) ) ) {
				if (
					false !== strpos( $route, '/(?P<id>[\d]+)/orders' ) ||
					false !== strpos( $route, '/statuses' )
				) {
					unset( $endpoints[ $route ] );
				}
			}
			if ( 0 === strpos( $route, sprintf( '/%s/orders/(?P<id>[\d]+)/subscriptions', API::REST_NAMESPACE ) ) ) {
				// Added in Woo Subscriptions v5.7.0.
				unset( $endpoints[ $route ] );
			}
		}
		return $endpoints;
	}

	/**
	 * Whether the user has the Woo Subscriptions plugin active.
	 *
	 * @return bool
	 */
	protected function is_active() {
		return $this->checker->class_exists( '\WC_Subscriptions' ) &&
			\is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' );
	}
}
