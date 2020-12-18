<?php
// phpcs:ignoreFile

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Logger;
use AutomateWoo\Trigger;
use AutomateWoo\Trigger_Background_Processed_Abstract;
use AutomateWoo\Clean;
use AutomateWoo\Trigger_Customer_Before_Saved_Card_Expiry;
use AutomateWoo\Trigger_Customer_Win_Back;
use AutomateWoo\Trigger_Subscription_Before_Renewal;
use AutomateWoo\Trigger_Wishlist_Reminder;
use AutomateWoo\Triggers\BatchedWorkflowInterface;
use AutomateWoo\Workflow;
use AutomateWoo\Workflows\Factory;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor for any workflows.
 *
 * It's better not to have a separate process for each workflow since there doesn't
 * appear to be any restriction on different processes running at the same time.
 *
 * Triggers that use this background process must extend Trigger_Background_Processed_Abstract.
 *
 * @deprecated in 5.1.0 and replaced with an ActionScheduler job. Temporarily kept for backwards compatibility.
 *
 * @since 3.7
 */
class Workflows extends Base {

	/** @var string  */
	public $action = 'workflows';


	/**
	 * @param array $data
	 * @return mixed
	 */
	protected function task( $data ) {
		$workflow = isset( $data['workflow_id'] ) ? Factory::get( $data['workflow_id'] ) : false;
		$workflow_data = isset( $data['workflow_data'] ) ? Clean::recursive( $data['workflow_data'] ) : [];

		if ( ! $workflow ) {
			return false;
		}

		if ( ! $trigger = $workflow->get_trigger() ) {
			return false;
		}

		if ( $trigger instanceof Trigger_Background_Processed_Abstract ) {
			$trigger->handle_background_task( $workflow, $workflow_data );
		}

		$this->maybe_handle_legacy_pending_task( $trigger, $workflow, $workflow_data );

		return false;
	}

	/**
	 * Smoothly handles legacy background processor tasks by mapping them to an upgraded trigger.
	 *
	 * @param Trigger  $trigger
	 * @param Workflow $workflow
	 * @param array    $item
	 *
	 * @since 5.1
	 */
	private function maybe_handle_legacy_pending_task( $trigger, $workflow, $item ) {
		if ( ! $trigger instanceof BatchedWorkflowInterface ) {
			// Bail if not an upgraded trigger
			return;
		}

		// We have dropped the `_id` suffix in some of the triggers updated in 5.1...
		if ( $trigger instanceof Trigger_Customer_Before_Saved_Card_Expiry ) {
			$item['token'] = $item['token_id'];
		}
		if ( $trigger instanceof Trigger_Customer_Win_Back ) {
			$item['customer'] = $item['customer_id'];
		}
		if ( $trigger instanceof Trigger_Subscription_Before_Renewal ) {
			// also handles subscription before end
			$item['subscription'] = $item['subscription_id'];
		}
		if ( $trigger instanceof Trigger_Wishlist_Reminder ) {
			$item['wishlist'] = $item['wishlist_id'];
		}

		try {
			$trigger->process_item_for_workflow( $workflow, $item );
		} catch ( \Exception $e ) {
			Logger::error(
				'legacy-background-process',
				sprintf( 'The item: %s failed with exception message: %s', print_r( $item, true ), $e->getMessage() )
			);
		}

	}

}
