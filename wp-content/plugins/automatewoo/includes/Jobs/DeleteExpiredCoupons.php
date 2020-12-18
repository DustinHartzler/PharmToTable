<?php

namespace AutomateWoo\Jobs;

use AutomateWoo\ActionScheduler\ActionSchedulerInterface;
use AutomateWoo\Cron;
use AutomateWoo\Exceptions\InvalidArgument;
use AutomateWoo\Jobs\Traits\ItemDeletionDate;
use AutomateWoo\Jobs\Traits\ValidateItemAsIntegerId;
use AutomateWoo\OptionsStore;

defined( 'ABSPATH' ) || exit;

/**
 * Job that deletes expired coupons after a specified amount of time.
 *
 * @since   5.0.0
 * @package AutomateWoo\Jobs
 */
class DeleteExpiredCoupons extends AbstractBatchedJob implements StartOnHookInterface {

	use ItemDeletionDate, ValidateItemAsIntegerId;

	/**
	 * @var OptionsStore $options_store
	 */
	protected $options_store;

	/**
	 * AbstractBatchedJob constructor.
	 *
	 * @param ActionSchedulerInterface $action_scheduler
	 * @param BatchedJobMonitor        $monitor
	 * @param OptionsStore             $options_store
	 */
	public function __construct( ActionSchedulerInterface $action_scheduler, BatchedJobMonitor $monitor, OptionsStore $options_store ) {
		$this->options_store = $options_store;
		parent::__construct( $action_scheduler, $monitor );
	}

	/**
	 * Get the name of the job.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'delete_expired_coupons';
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
	 * Get the number of days before expired coupons are deleted.
	 *
	 * @return int
	 */
	public function get_deletion_period() {
		return absint( apply_filters( 'automatewoo/coupons/days_to_keep_expired', 14 ) );
	}

	/**
	 * Can the job start.
	 *
	 * @return bool Returns true if the job can start.
	 *
	 * @throws InvalidArgument If option value is invalid.
	 */
	protected function can_start(): bool {
		if ( ! $this->options_store->get_clean_expired_coupons_enabled() ) {
			return false;
		}

		return parent::can_start();
	}

	/**
	 * Get a new batch of items.
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

		$query_args = [
			'fields'         => 'ids',
			'post_type'      => 'shop_coupon',
			'post_status'    => 'any',
			'posts_per_page' => $this->get_batch_size(),
			'orderby'        => 'date',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'meta_query'     => [
				[
					'key'   => '_is_aw_coupon',
					'value' => true,
				],
				[
					'key'     => 'date_expires',
					'value'   => $deletion_date->getTimestamp(),
					'compare' => '<',
				],
			],
		];

		$query = new \WP_Query( $query_args );

		return $query->posts;
	}

	/**
	 * Handle a single item.
	 *
	 * @param int   $coupon_id
	 * @param array $args The args for this instance of the job.
	 */
	protected function process_item( $coupon_id, array $args ) {
		wp_delete_post( $coupon_id, true );
	}
}
