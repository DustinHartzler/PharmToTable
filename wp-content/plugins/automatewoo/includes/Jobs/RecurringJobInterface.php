<?php

namespace AutomateWoo\Jobs;

defined( 'ABSPATH' ) || exit;

/**
 * Interface RecurrentJobInterface.
 *
 * Jobs that implement this interface will run recurrently based on an interval.
 *
 * @since 5.8.1
 */
interface RecurringJobInterface extends StartOnHookInterface {

	/**
	 * Return the recurring job's interval in seconds.
	 *
	 * @return int The interval for the action
	 */
	public function get_interval(): int;

	/**
	 * Init the job recurrence.
	 */
	public function schedule_recurring();

	/**
	 * Cancels the job recurrence.
	 */
	public function cancel_recurring();

}
