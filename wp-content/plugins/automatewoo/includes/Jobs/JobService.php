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
