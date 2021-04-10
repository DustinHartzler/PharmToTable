<?php
// phpcs:ignoreFile

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Event_Factory
 * @since 3.4.0
 *
 * @deprecated in 5.2.0 use AW()->action_scheduler() instead.
 */
class Event_Factory extends Factory {

	static $model = 'AutomateWoo\Event';


	/**
	 * @param int $id
	 * @return Event|bool
	 */
	static function get( $id ) {
		return parent::get( $id );
	}


}
