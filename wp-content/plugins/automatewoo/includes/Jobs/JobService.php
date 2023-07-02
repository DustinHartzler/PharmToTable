<?php

namespace AutomateWoo\Jobs;

use AutomateWoo\Exceptions\InvalidArgument;
use AutomateWoo\Exceptions\InvalidClass;
use AutomateWoo\Traits\ArrayValidator;

defined( 'ABSPATH' ) || exit;

/**
 * JobService class.
 *
 * @version 5.1.0
 */
class JobService {

	use ArrayValidator;

	// Job intervals for using in the recurrent Jobs
	const ONE_MINUTE_INTERVAL     = 60;
	const TWO_MINUTE_INTERVAL     = self::ONE_MINUTE_INTERVAL * 2;
	const FIVE_MINUTE_INTERVAL    = self::ONE_MINUTE_INTERVAL * 5;
	const FIFTEEN_MINUTE_INTERVAL = self::ONE_MINUTE_INTERVAL * 15;
	const THIRTY_MINUTE_INTERVAL  = self::ONE_MINUTE_INTERVAL * 30;
	const ONE_HOUR_INTERVAL       = self::ONE_MINUTE_INTERVAL * 60;
	const FOUR_HOURS_INTERVAL     = self::ONE_HOUR_INTERVAL * 4;
	const ONE_DAY_INTERVAL        = self::ONE_HOUR_INTERVAL * 24;
	const TWO_DAY_INTERVAL        = self::ONE_DAY_INTERVAL * 2;
	const WEEKLY_INTERVAL         = self::ONE_DAY_INTERVAL * 7;

	/**
	 * @var JobRegistryInterface
	 */
	protected $registry;

	/**
	 * JobService constructor.
	 *
	 * @param JobRegistryInterface $registry
	 */
	public function __construct( JobRegistryInterface $registry ) {
		$this->registry = $registry;
	}

	/**
	 * Initialize all jobs.
	 *
	 * @throws InvalidClass|InvalidArgument When there is an error loading jobs.
	 */
	public function init_jobs() {
		foreach ( $this->registry->list() as $job ) {
			$job->init();

			if ( $job instanceof StartOnHookInterface ) {
				add_action( $job->get_start_hook(), [ $job, 'start' ], 10, 0 );
			}

			if ( $job instanceof RecurringJobInterface ) {
				// AuctionScheduler loads its tables on "init" action
				add_action( 'init', [ $job, 'schedule_recurring' ], 10, 0 );

				// Cancel the recurring action on deactivation
				register_deactivation_hook( AUTOMATEWOO_FILE, [ $job, 'cancel_recurring' ] );
			}
		}
	}

	/**
	 * Get a job by name.
	 *
	 * @param string $name The job name.
	 *
	 * @return JobInterface
	 *
	 * @throws JobException If the job is not found.
	 * @throws InvalidClass|InvalidArgument When there is an invalid job class.
	 */
	public function get_job( string $name ): JobInterface {
		return $this->registry->get( $name );
	}

}
