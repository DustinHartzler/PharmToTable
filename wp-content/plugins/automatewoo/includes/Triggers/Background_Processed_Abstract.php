<?php

namespace AutomateWoo;

use AutomateWoo\Triggers\AbstractBatchedDailyTrigger;
use AutomateWoo\Triggers\Utilities\CustomTimeOfDay;

defined( 'ABSPATH' ) || exit;

aw_deprecated_class( Trigger_Background_Processed_Abstract::class, '5.1.0', AbstractBatchedDailyTrigger::class );

/**
 * Class Trigger_Background_Process_Abstract
 *
 * @deprecated in 5.1.0 use AutomateWoo\Triggers\AbstractBatchedDailyTrigger instead.
 *
 * @since 4.5
 * @package AutomateWoo
 */
abstract class Trigger_Background_Processed_Abstract extends Trigger {

	use CustomTimeOfDay;

	/**
	 * Set that the trigger supports customer time of day functions
	 */
	const SUPPORTS_CUSTOM_TIME_OF_DAY = true;

	/**
	 * Method that the 'workflows' background processor will pass data back to when processing.
	 *
	 * @param \AutomateWoo\Workflow $workflow
	 * @param array                 $data
	 */
	abstract public function handle_background_task( $workflow, $data );

	/**
	 * Should return an array of tasks to be background processed.
	 *
	 * @param \AutomateWoo\Workflow $workflow
	 * @param int                   $limit The limit to use when querying tasks.
	 * @param int                   $offset The offset to use when querying tasks.
	 *
	 * @return array
	 */
	abstract public function get_background_tasks( $workflow, $limit, $offset = 0 );



}
