<?php

namespace AutomateWoo\Jobs;

use RuntimeException;
use AutomateWoo\Exceptions\UserFacingException;

defined( 'ABSPATH' ) || exit;

/**
 * BatchedJobException class.
 *
 * @since 5.1.0
 */
class BatchException extends RuntimeException implements UserFacingException {

	/**
	 * Create a new exception instance for when a batch encounters a missing item.
	 *
	 * @return static
	 */
	public static function item_not_found() {
		return new static( __( 'Item not found.', 'automatewoo' ) );
	}

	/**
	 * Create a new exception instance for when a job is stopped due to a high failure rate.
	 *
	 * @param string $job_name
	 *
	 * @return static
	 */
	public static function stopped_due_to_high_failure_rate( string $job_name ) {
		return new static(
			sprintf(
				__( 'The "%s" job was stopped because it\'s failure rate is above the allowed threshold.', 'automatewoo' ),
				$job_name
			)
		);
	}

}
