<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Events;

defined( 'ABSPATH' ) || exit;

/**
 * Class Subscription_Status_Changed
 *
 * @since 4.8.0
 * @package AutomateWoo
 */
class Subscription_Status_Changed extends Abstract_Async_Event {

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'automatewoo/subscription/status_changed', [ $this, 'schedule_event' ], 10, 3 );
	}

	/**
	 * Schedule async event.
	 *
	 * @param int    $subscription_id
	 * @param string $new_status
	 * @param string $old_status
	 */
	public static function schedule_event( $subscription_id, $new_status, $old_status ) {
		Events::schedule_async_event(
			'automatewoo/subscription/status_changed_async',
			[
				$subscription_id,
				$new_status,
				$old_status,
			]
		);
	}

}
