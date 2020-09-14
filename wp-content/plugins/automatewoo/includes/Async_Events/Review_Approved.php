<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Events;
use AutomateWoo\Review;

defined( 'ABSPATH' ) || exit;

/**
 * Class Review_Approved
 *
 * @since   4.8.0
 * @package AutomateWoo
 */
class Review_Approved extends Abstract_Async_Event {

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'automatewoo/review/posted', [ $this, 'schedule_event' ] );
	}

	/**
	 * Schedule async event.
	 *
	 * @param Review $review
	 */
	public function schedule_event( $review ) {
		Events::schedule_async_event( 'automatewoo/review/posted_async', [ $review->get_id() ] );
	}

}
