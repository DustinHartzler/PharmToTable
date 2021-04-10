<?php

namespace AutomateWoo\Async_Events;

defined( 'ABSPATH' ) || exit;

/**
 * Class BookingStatusChanged
 *
 * @since 5.3.0
 * @package AutomateWoo
 */
class BookingStatusChanged extends Abstract_Async_Event {

	const NAME = 'booking_status_changed';

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'woocommerce_booking_status_changed', [ $this, 'schedule_event' ], 30, 3 );
	}

	/**
	 * Schedule bookings status change event for consumption by triggers.
	 *
	 * Doesn't dispatch for 'was-in-cart' status changes because this status isn't a real booking status and essentially
	 * functions as a 'trash' status. The was in cart is used when a booking cart item is removed from the cart.
	 *
	 * @param string $from       Previous status.
	 * @param string $to         New (current) status.
	 * @param int    $booking_id Booking id.
	 */
	public function schedule_event( string $from, string $to, int $booking_id ) {
		$was_in_cart = 'was-in-cart';
		if ( $to === $was_in_cart || $from === $was_in_cart ) {
			// Don't dispatch an event for 'was-in-cart' status changes
			return;
		}

		$this->create_async_event(
			[
				$booking_id,
				$from,
				$to,
			]
		);
	}

}
