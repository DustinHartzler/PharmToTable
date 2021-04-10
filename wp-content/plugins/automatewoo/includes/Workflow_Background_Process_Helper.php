<?php
// phpcs:ignoreFile

namespace AutomateWoo;

use AutomateWoo\Jobs\BatchedWorkflows;
use AutomateWoo\Triggers\BatchedWorkflowInterface;
use AutomateWoo\Workflows\Factory;

defined( 'ABSPATH' ) || exit;


/**
 * Class to manage triggers that are initiated in the background.
 *
 * TODO Most of the functionality in this class can be removed once the Trigger_Background_Processed_Abstract is removed.
 *
 * @class Workflow_Background_Process_Helper
 * @since 3.8
 */
class Workflow_Background_Process_Helper {


	/**
	 * Trigger must extend Trigger_Background_Processed_Abstract
	 *
	 * NOTE: A site could update to version 5.1 (ActionScheduler conversion) while a legacy background process is
	 * running. Currently backwards compatibility is maintained for the legacy process. However, it's also possible that
	 * a currently running process uses a trigger that has been switched to use the new jobs system and now implements
	 * BatchedWorkflowInterface. In this case the job will restart because the $offset argument is ignored by the new
	 * system. This is actually works well because it lowers the chance that items will be missed on the day of updating
	 * to 5.1 and all our background process triggers already have a protection to avoid duplicates.
	 *
	 * @param int $workflow_id
	 * @param int $offset The DB query offset for the trigger.
	 */
	static function init_process( $workflow_id, $offset = 0 ) {
		$workflow = Factory::get( $workflow_id );
		$offset = absint( $offset );

		if ( ! $workflow || ! $workflow->is_active() ) {
			return;
		}

		$trigger = $workflow->get_trigger();

		if ( $trigger instanceof BatchedWorkflowInterface ) {
			self::start_batched_workflow_job( $workflow );
			return;
		}

		if ( ! $trigger instanceof Trigger_Background_Processed_Abstract ) {
			return;
		}

		$limit = self::get_background_process_batch_size( $workflow );

		/** @var Background_Processes\Workflows $process */
		$process = Background_Processes::get('workflows');
		$tasks = $trigger->get_background_tasks( $workflow, $limit, $offset );

		foreach( $tasks as $task ) {
			$process->push_to_queue( $task );
		}

		self::log_process_activity( $workflow, count( $tasks ), $offset );

		// If the workflow has tasks, schedule another batch
		if ( ! empty( $tasks ) ) {
			self::schedule_next_batch( $workflow_id, $offset + $limit );
		}

		if ( $tasks ) {
			add_action( 'shutdown', [ __CLASS__, 'start_workflow_background_process' ] );
		}
	}


	/**
	 * Start a batched workflow job.
	 *
	 * @since 5.1.0
	 * @param Workflow $workflow
	 */
	protected static function start_batched_workflow_job( $workflow ) {
		try {
			/** @var BatchedWorkflows $job */
			$job = AW()->job_service()->get_job( 'batched_workflows' );
			$job->start( [ 'workflow' => $workflow->get_id() ] );
		} catch ( \Exception $e ) {
			Logger::error(
				'jobs',
				sprintf(
					'Exception thrown when attempting to start the batched workflow (#%d): %s',
					$workflow->get_id(),
					$e->getMessage()
				)
			);
		}
	}


	/**
	 * Schedules a follow up event, one minute from now that will init another batch of tasks.
	 *
	 * @param int $workflow_id
	 * @param int $new_offset
	 */
	private static function schedule_next_batch( $workflow_id, $new_offset ) {
		if ( ! $new_offset ) {
			// offset should be greater than 0
			return;
		}

		AW()->action_scheduler()->schedule_single(
			gmdate( 'U' ),
			'automatewoo/custom_time_of_day_workflow',
			[
				$workflow_id, $new_offset
			]
		);
	}


	/**
	 * Used to start the background processor on shutdown
	 */
	static function start_workflow_background_process() {
		$process = Background_Processes::get('workflows');
		$process->start();
	}


	/**
	 * Get the batch size for workflows that use the background processor.
	 *
	 * This is the max number of items that will be passed to the background processor at once.
	 *
	 * @since 4.5
	 *
	 * @param Workflow $workflow
	 *
	 * @return int
	 */
	private static function get_background_process_batch_size( $workflow ) {
		return apply_filters( 'automatewoo/workflows/background_process_batch_size', 50, $workflow );
	}

	/**
	 * Log the background trigger activity.
	 *
	 * @since 4.6
	 *
	 * @param Workflow $workflow
	 * @param int $task_count
	 * @param int $offset
	 */
	private static function log_process_activity( $workflow, $task_count, $offset ) {
		// If no offset then the process is starting
		if ( $offset === 0 ) {
			if ( $task_count ) {
				Logger::info( 'background-trigger', sprintf( 'Workflow #%d - Started - %d items added to processor', $workflow->get_id(), $task_count ) );
			}
			else {
				Logger::info( 'background-trigger', sprintf( 'Workflow #%d - Started - No items need processing', $workflow->get_id() ) );
			}
		}
		else {
			if ( $task_count ) {
				Logger::info( 'background-trigger', sprintf( 'Workflow #%d - Continued - %d items added to processor - Offset is %d', $workflow->get_id(), $task_count, $offset ) );
			}
			else {
				Logger::info( 'background-trigger', sprintf( 'Workflow #%d - Finished - No items remaining - Offset is %d', $workflow->get_id(), $offset ) );
			}
		}
	}


}
