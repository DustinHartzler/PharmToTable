<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Events;

defined( 'ABSPATH' ) || exit;

/**
 * Class Subscription_Renewal_Payment_Complete
 *
 * @since 4.8.0
 * @package AutomateWoo
 */
class Subscription_Renewal_Payment_Complete extends Abstract_Async_Event {

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'woocommerce_subscription_renewal_payment_complete', [ $this, 'schedule_event' ], 20, 2 );
	}

	/**
	 * Schedule event.
	 *
	 * @param \WC_Subscription $subscription
	 * @param \WC_Order        $order
	 */
	public function schedule_event( $subscription, $order ) {
		Events::schedule_async_event(
			'automatewoo/subscription/renewal_payment_complete_async',
			[
				$subscription->get_id(),
				$order->get_id(),
			]
		);
	}

}
