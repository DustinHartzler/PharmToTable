<?php

namespace AutomateWoo\Jobs;

use AutomateWoo\Cron;
use AutomateWoo\Jobs\Traits\ItemDeletionDate;
use AutomateWoo\Jobs\Traits\ValidateItemAsIntegerId;
use AutomateWoo\Queue_Query;
use AutomateWoo\Queued_Event_Factory;

defined( 'ABSPATH' ) || exit;

/**
 * Job that deletes failed queued workflows after a specified amount of time.
 *
 * @since   5.0.0
 * @package AutomateWoo\Jobs
 */
class DeleteFailedQueuedWorkflows extends AbstractBatchedActionSchedulerJob implements StartOnHookInterface {

	use ItemDeletionDate, ValidateItemAsIntegerId;

	/**
	 * Get the name of the job.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'delete_failed_queued_workflows';
	}

	/**
	 * Get the name of an action to attach the job's start method to.
	 *
	 * @return string
	 */
	public function get_start_hook() {
		return Cron::FOUR_HOUR_WORKER;
	}

	/**
	 * Get the number of days before queued workflows are deleted.
	 *
	 * @return int
	 */
	public function get_deletion_period() {
		return absint( apply_filters( 'automatewoo_failed_events_delete_after', 30 ) );
	}

	/**
	 * Get a batch of items to be deleted.
	 *
	 * @param int   $batch_number The batch number increments for each new batch in the job cycle.
	 * @param array $args         The args for this instance of the job.
	 *
	 * @return int[]
	 */
	protected function get_batch( int $batch_number, array $args ) {
		$deletion_date = $this->get_deletion_date();
		if ( ! $deletion_date ) {
			return [];
		}

		return ( new Queue_Query() )
			->set_limit( $this->get_batch_size() )
			->set_ordering( 'date', 'ASC' )
			->where_date_due( $deletion_date, '<' )
			->where_failed( true )
			->get_results_as_ids();
	}

	/**
	 * Process a single item.
	 *
	 * @param int   $item
	 * @param array $args The args for this instance of the job.
	 *
	 * @throws JobException If item can't be found.
	 */
	protected function process_item( $item, array $args ) {
		$queued_workflow = Queued_Event_Factory::get( $item );

		if ( ! $queued_workflow ) {
			throw JobException::item_not_found();
		}

		$queued_workflow->delete();
	}

}
