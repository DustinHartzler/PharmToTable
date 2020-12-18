<?php

namespace AutomateWoo\Jobs;

use AutomateWoo\Exceptions\Exception as ExceptionInterface;

defined( 'ABSPATH' ) || exit;

/**
 * JobException class.
 *
 * @since 5.1.0
 */
class JobException extends \Exception implements ExceptionInterface {

	/**
	 * Create a new exception when a job does not exist.
	 *
	 * @param string $name
	 *
	 * @return static
	 */
	public static function job_does_not_exist( string $name ): JobException {
		return new static( sprintf( 'The job named "%s" does not exist.', $name ) );
	}

}
