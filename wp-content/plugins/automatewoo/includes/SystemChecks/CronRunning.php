<?php
// phpcs:ignoreFile

namespace AutomateWoo\SystemChecks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CronRunning system check class.
 *
 * @package AutomateWoo\SystemChecks
 */
class CronRunning extends AbstractSystemCheck {

	/**
	 * AW_System_Check_Cron_Running constructor.
	 */
	function __construct() {
		$this->title = __( 'WP Cron', 'automatewoo' );
		$this->description = __( 'Checks the dates of scheduled events to see if they are processing.', 'automatewoo' );
		$this->high_priority = true;
	}


	/**
	 * Perform the check
	 */
	function run() {
		$failed = 0;
		$two_hours_ago = time() - ( HOUR_IN_SECONDS * 2 );

		$crons = [
			wp_next_scheduled('automatewoo_events_worker'),
			wp_next_scheduled('automatewoo_two_minute_worker'),
			wp_next_scheduled('automatewoo_hourly_worker'),
			wp_next_scheduled('automatewoo_five_minute_worker'),
		];

		foreach ( $crons as $time ) {
			if ( ! $time ) {
				continue;
			}

			if ( $two_hours_ago > $time ) {
				$failed++;
			}
		}

		if ( $failed > 2 ) {
			return $this->error( __( "WP Cron does not appear to be running. This function is heavily relied upon by AutomateWoo. Please contact your hosting provider to resolve the issue.", 'automatewoo' ) );
		}

		return $this->success();
	}

}
