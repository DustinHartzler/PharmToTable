<?php

namespace AutomateWoo\Jobs;

use AutomateWoo\Jobs\Traits\Batching;
use AutomateWoo\Jobs\Traits\ItemDeletionDate;

/**
 * Job that deletes expired coupons after a specified amount of time.
 *
 * @since   5.0.0
 * @package AutomateWoo\Jobs
 */
class DeleteExpiredCoupons implements JobInterface {

	use Batching, ItemDeletionDate;

	/**
	 * Get the name of the job.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'delete_expired_coupons';
	}

	/**
	 * Init the job.
	 */
	public function init() {
		$this->init_batch_schedule( 'automatewoo_four_hourly_worker' );
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
	 * Get a new batch of items.
	 *
	 * @return array
	 */
	protected function get_batch() {
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
	 * @param int $coupon_id
	 */
	protected function handle_item( $coupon_id ) {
		wp_delete_post( $coupon_id, true );
	}
}
