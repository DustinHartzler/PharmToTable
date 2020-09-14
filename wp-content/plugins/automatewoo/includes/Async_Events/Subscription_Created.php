<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Events;

defined( 'ABSPATH' ) || exit;

/**
 * Class Subscription_Created
 *
 * @since 4.8.0
 * @package AutomateWoo
 */
class Subscription_Created extends Abstract_Async_Event {

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'woocommerce_checkout_subscription_created', [ $this, 'handle_subscription_created' ], 20 );
		add_action( 'wcs_api_subscription_created', [ $this, 'handle_subscription_created' ], 20 );
		// Note: this action was added in WCS 2.4.1
		add_action( 'woocommerce_admin_created_subscription', [ $this, 'handle_subscription_created' ], 20 );
	}

	/**
	 * Handle subscription created.
	 *
	 * @param \WC_Subscription|int $subscription
	 */
	public function handle_subscription_created( $subscription ) {
		$subscription = wcs_get_subscription( $subscription );

		if ( ! $subscription ) {
			return;
		}

		Events::schedule_async_event( 'automatewoo/async/subscription_created', [ $subscription->get_id() ], true );
	}

}
