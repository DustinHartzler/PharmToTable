<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Events;

defined( 'ABSPATH' ) || exit;

/**
 * Class Order_Status_Changed
 *
 * @since 4.8.0
 * @package AutomateWoo
 */
class Order_Status_Changed extends Abstract_Async_Event {

	/**
	 * Init order status changed async event.
	 */
	public function init() {
		add_action( 'woocommerce_order_status_changed', [ $this, 'schedule_event' ], 50, 3 );
	}

	/**
	 * Schedule event.
	 *
	 * @param int    $order_id
	 * @param string $old_status
	 * @param string $new_status
	 */
	public function schedule_event( $order_id, $old_status, $new_status ) {
		Events::schedule_async_event(
			'automatewoo/order/status_changed_async',
			[
				$order_id,
				$old_status,
				$new_status,
			]
		);
	}

}
