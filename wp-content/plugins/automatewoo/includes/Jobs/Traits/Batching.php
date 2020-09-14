<?php

namespace AutomateWoo\Jobs\Traits;

use AutomateWoo\Events;

/**
 * Trait Batching.
 *
 * Enables a job to be processed in recurring scheduled batches.
 *
 * @since   5.0.0
 * @package AutomateWoo\Jobs
 */
trait Batching {

	/**
	 * Get the name of the job.
	 *
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Get a new batch of items.
	 *
	 * @return array
	 */
	abstract protected function get_batch();

	/**
	 * Handle a single item.
	 *
	 * @param mixed $item
	 */
	abstract protected function handle_item( $item );

	/**
	 * Init the batch schedule for the job.
	 *
	 * The job name is used to generate the schedule event name.
	 *
	 * @param string $starting_cron_hook The cron hook to start the job.
	 */
	protected function init_batch_schedule( $starting_cron_hook ) {
		// To avoid cron timeouts we schedule an event delayed by 1 minute rather than handling the job immediately.
		add_action( $starting_cron_hook, [ $this, 'schedule_batch' ] );
		add_action( $this->get_schedule_event_name(), [ $this, 'handle_batch' ] );
	}

	/**
	 * Schedules the job for 1 minute's time.
	 *
	 * This method can also be used if the job needs to be rescheduled after a batch.
	 */
	public function schedule_batch() {
		Events::schedule_event( gmdate( 'U' ) + 60, $this->get_schedule_event_name() );
	}

	/**
	 * Get the event name for the schedule.
	 *
	 * @return string
	 */
	protected function get_schedule_event_name() {
		return 'automatewoo/jobs/' . $this->get_name();
	}

	/**
	 * Get job batch size.
	 *
	 * Applies filters to the job batch size.
	 *
	 * @param int $size Default is 15.
	 *
	 * @return int
	 */
	protected function get_batch_size( $size = 15 ) {
		return absint( apply_filters( 'automatewoo/jobs/batch_size', $size, $this ) );
	}

	/**
	 * Handle job batch.
	 *
	 * Schedules a new event if there are more items to process.
	 */
	public function handle_batch() {
		$items = $this->get_batch();
		if ( ! $items ) {
			return;
		}

		foreach ( $items as $item ) {
			$this->handle_item( $item );
		}

		// Schedule another batch if there could be more items
		if ( count( $items ) === $this->get_batch_size() ) {
			$this->schedule_batch();
		}
	}

}
