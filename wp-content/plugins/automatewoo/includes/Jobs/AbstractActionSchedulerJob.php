<?php

namespace AutomateWoo\Jobs;

use AutomateWoo\ActionScheduler\ActionSchedulerInterface;

defined( 'ABSPATH' ) || exit;

/**
 * AbstractActionSchedulerJob class.
 *
 * Abstract class for jobs that use ActionScheduler.
 *
 * @since 5.2.0
 */
abstract class AbstractActionSchedulerJob implements ActionSchedulerJobInterface {

	/**
	 * @var ActionSchedulerInterface
	 */
	protected $action_scheduler;

	/**
	 * @var ActionSchedulerJobMonitor
	 */
	protected $monitor;

	/**
	 * AbstractActionSchedulerJob constructor.
	 *
	 * @param ActionSchedulerInterface  $action_scheduler
	 * @param ActionSchedulerJobMonitor $monitor
	 */
	public function __construct( ActionSchedulerInterface $action_scheduler, ActionSchedulerJobMonitor $monitor ) {
		$this->action_scheduler = $action_scheduler;
		$this->monitor          = $monitor;
	}

	/**
	 * Get the base name for the job's scheduled actions.
	 *
	 * @return string
	 */
	protected function get_hook_base_name() {
		return 'automatewoo/jobs/' . $this->get_name() . '/';
	}

	/**
	 * Get the hook name for the "process item" action.
	 *
	 * This method is required by the job monitor.
	 *
	 * @return string
	 */
	public function get_process_item_hook() {
		return $this->get_hook_base_name() . 'process_item';
	}
}
