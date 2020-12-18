<?php

namespace AutomateWoo\Workflows;

use AutomateWoo\Clean;
use AutomateWoo\Entity\Workflow as WorkflowEntity;
use AutomateWoo\Entity\WorkflowTiming;
use AutomateWoo\Entity\WorkflowTimingDelayed;
use AutomateWoo\Entity\WorkflowTimingFixed;
use AutomateWoo\Entity\WorkflowTimingImmediate;
use AutomateWoo\Entity\WorkflowTimingScheduled;
use AutomateWoo\Entity\WorkflowTimingVariable;
use AutomateWoo\Exceptions\InvalidWorkflow;
use AutomateWoo\Workflow;
use WP_Error;

/**
 * @since 3.9
 */
class Factory {

	/**
	 * Get a Workflow object by its ID.
	 *
	 * @param int $id The workflow ID.
	 *
	 * @return Workflow|false The workflow object, or false when the ID is invalid or the object can't be retrieved.
	 */
	public static function get( $id ) {
		$id = (int) $id;
		if ( $id <= 0 ) {
			return false;
		}

		$id = Clean::id( $id );
		if ( ! $id ) {
			return false;
		}

		$workflow = new Workflow( $id );
		if ( ! $workflow->exists ) {
			return false;
		}

		return $workflow;
	}

	/**
	 * Create a workflow given an Entity.
	 *
	 * @since 5.1.0
	 *
	 * @param WorkflowEntity $entity The entity to use for Workflow creation.
	 *
	 * @return Workflow
	 *
	 * @throws InvalidWorkflow When the workflow already exists or there is an issue creating the workflow.
	 */
	public static function create( WorkflowEntity $entity ) {
		$workflow = self::get( $entity->get_id() );
		if ( $workflow ) {
			throw InvalidWorkflow::workflow_exists( $workflow->get_id() );
		}

		// Some options are stored together.
		$options = array_merge(
			[
				'click_tracking'      => $entity->is_tracking_enabled(),
				'conversion_tracking' => $entity->is_conversion_tracking_enabled(),
				'ga_link_tracking'    => $entity->get_ga_link_tracking(),
			],
			self::get_timing_options_data( $entity->get_timing() )
		);

		// Main data for the workflow.
		$data = [
			'title'            => $entity->get_title(),
			'status'           => $entity->get_status(),
			'type'             => $entity->get_type(),
			'is_transactional' => $entity->is_transactional(),
			'origin'           => $entity->get_origin(),
			'options'          => $options,
			'trigger'          => $entity->get_trigger() ? $entity->get_trigger()->to_array() : [],
			'actions'          => [],
			'rules'            => [],
		];

		foreach ( $entity->get_actions() as $action ) {
			$data['actions'][] = $action->to_array();
		}

		foreach ( $entity->get_rule_groups() as $rule_group ) {
			$data['rules'][] = $rule_group->to_array();
		}

		return self::create_from_array( $data );
	}

	/**
	 * Create a workflow from an array of data.
	 *
	 * @since 5.1.0
	 *
	 * @param array $data The array of workflow data.
	 *
	 * @return Workflow
	 * @throws InvalidWorkflow When there is an issue creating the workflow.
	 */
	public static function create_from_array( $data = [] ) {
		$data = array_replace_recursive(
			[
				'title'            => '',
				'status'           => new Status( Status::DISABLED ),
				'type'             => 'automatic',
				'is_transactional' => false,
				'origin'           => WorkflowEntity::ORIGIN_MANUALLY_CREATED,
				'options'          => [
					'when_to_run' => 'immediately',
				],
				'trigger'          => [
					'name'    => '',
					'options' => [],
				],
				'rules'            => [],
				'actions'          => [],
			],
			$data
		);

		$post_id = self::create_post( $data );

		$workflow = new Workflow( $post_id );
		$workflow->set_trigger_data( $data['trigger']['name'], $data['trigger']['options'] );
		$workflow->set_type( $data['type'] );

		if ( ! empty( $data['rules'] ) ) {
			$workflow->set_rule_data( $data['rules'] );
		}

		if ( ! empty( $data['actions'] ) ) {
			$workflow->set_actions_data( self::maybe_convert_to_legacy_action_data( $data['actions'] ) );
		}

		$workflow->update_meta( 'workflow_options', $data['options'] );
		$workflow->update_meta( 'is_transactional', $data['is_transactional'] );
		$workflow->update_meta( 'origin', $data['origin'] );

		do_action( 'automatewoo/workflow/created', $workflow->get_id() );

		return $workflow;
	}

	/**
	 * Create a post object from an array of workflow data.
	 *
	 * The 'status' property within the $data array should NOT be the post type equivalent. This
	 * method will handle converting to the post type version of the status.
	 *
	 * @param array $data The array of workflow data.
	 *
	 * @return int The created post ID.
	 * @throws InvalidWorkflow When there is a problem creating the post.
	 */
	private static function create_post( $data = [] ) {
		if ( isset( $data['status'] ) ) {
			$data['status'] = $data['status'] instanceof Status
				? $data['status']->get_post_status()
				: ( new Status( $data['status'] ) )->get_post_status();
		}

		$post_keys = [
			'title'  => 'post_title',
			'status' => 'post_status',
		];

		$post_data = [ 'post_type' => Workflow::POST_TYPE ];
		foreach ( array_intersect_key( $data, $post_keys ) as $key => $value ) {
			$post_data[ $post_keys[ $key ] ] = $value;
		}

		$post_id = wp_insert_post( $post_data, true );
		if ( $post_id instanceof WP_Error ) {
			throw InvalidWorkflow::error_creating_workflow( $post_id->get_error_message() );
		}

		return $post_id;
	}

	/**
	 * Get data based on a Workflow timing object.
	 *
	 * @since 5.1.0
	 *
	 * @param WorkflowTiming $timing The timing object.
	 *
	 * @return array Normalized data from the timing object.
	 */
	private static function get_timing_options_data( WorkflowTiming $timing ): array {
		$data = [
			'when_to_run' => $timing->get_type(),
		];

		switch ( get_class( $timing ) ) {
			/** @noinspection PhpMissingBreakStatementInspection */
			case WorkflowTimingScheduled::class:
				/** @var WorkflowTimingScheduled $timing */
				$data['scheduled_time'] = $timing->get_scheduled_time();
				$data['scheduled_day']  = $timing->get_scheduled_day();
				// Deliberately skip break to ensure delay value and unit are set in the next clause.

			case WorkflowTimingDelayed::class:
				/** @var WorkflowTimingDelayed|WorkflowTimingScheduled $timing */
				$data['run_delay_value'] = $timing->get_delay_value();
				$data['run_delay_unit']  = $timing->get_delay_unit();
				break;

			case WorkflowTimingFixed::class:
				/** @var WorkflowTimingFixed $timing */
				$datetime           = $timing->get_fixed_datetime()->convert_to_site_time();
				$data['fixed_date'] = $datetime->format( 'Y-m-d' );
				$data['fixed_time'] = [
					$datetime->format( 'H' ),
					$datetime->format( 'i' ),
				];
				break;

			case WorkflowTimingVariable::class:
				/** @var WorkflowTimingVariable $timing */
				$data['queue_datetime'] = $timing->get_variable();
				break;

			case WorkflowTimingImmediate::class:
			default:
				// nothing to do here.
				break;
		}

		return $data;
	}

	/**
	 * Convert action data structure to legacy data structure.
	 *
	 * @since 5.1.0
	 *
	 * @param array $actions
	 *
	 * @return array
	 */
	private static function maybe_convert_to_legacy_action_data( array $actions ): array {
		$converted = [];
		foreach ( $actions as $action ) {
			if ( isset( $action['action_name'] ) ) {
				$converted[] = $action;
				continue;
			}

			if ( isset( $action['name'], $action['options'] ) ) {
				$converted[] = array_merge(
					[ 'action_name' => $action['name'] ],
					$action['options']
				);
			}
		}

		return $converted;
	}
}
