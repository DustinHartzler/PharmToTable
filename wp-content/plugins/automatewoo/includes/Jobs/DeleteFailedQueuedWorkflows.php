<?php

namespace AutomateWoo\Jobs;

use AutomateWoo\Jobs\Traits\Batching;
use AutomateWoo\Jobs\Traits\ItemDeletionDate;
use AutomateWoo\Queue_Query;
use AutomateWoo\Queued_Event;

defined( 'ABSPATH' ) || exit;

/**
 * Job that deletes failed queued workflows after a specified amount of time.
 *
 * @since   5.0.0
 * @package AutomateWoo\Jobs
 */
class DeleteFailedQueuedWorkflows implements JobInterface {

	use Batching, ItemDeletionDate;

	/**
	 * Inits the job.
	 */
	public function init() {
		$this->init_batch_schedule( 'automatewoo_four_hourly_worker' );
	}

	/**
	 * Get the name of the job.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'delete_failed_queued_workflows';
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
	 * @return Queued_Event[]
	 */
	protected function get_batch() {
		$deletion_date = $this->get_deletion_date();
		if ( ! $deletion_date ) {
			return [];
		}

		return ( new Queue_Query() )
			->set_limit( $this->get_batch_size() )
			->set_ordering( 'date', 'ASC' )
			->where_date_due( $deletion_date, '<' )
			->where_failed( true )
			->get_results();
	}

	/**
	 * Handle a single item.
	 *
	 * @param Queued_Event $item
	 */
	protected function handle_item( $item ) {
		$item->delete();
	}

}
