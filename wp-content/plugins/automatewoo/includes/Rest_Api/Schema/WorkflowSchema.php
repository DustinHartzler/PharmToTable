<?php

namespace AutomateWoo\Rest_Api\Schema;

use AutomateWoo\Workflow;
use AutomateWoo\Workflows;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Schema for a Workflow object in the REST API.
 *
 * @since   4.9.0
 *
 * @package AutomateWoo\Rest_Api\Schema
 */
trait WorkflowSchema {

	/**
	 * Retrieves the item's schema, conforming to JSON Schema.
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		$schema = [
			'$schema'    => 'https://json-schema.org/draft-04/schema#',
			'title'      => 'links',
			'type'       => 'object',
			'properties' => [
				'id'                             => [
					'description' => __( 'Unique identifier for the object.', 'automatewoo' ),
					'type'        => 'integer',
					'context'     => Context::VIEW_ONLY,
					'readonly'    => true,
				],
				'title'                          => [
					'description' => __( 'Workflow name', 'automatewoo' ),
					'type'        => 'string',
					'context'     => Context::VIEW_ONLY,
				],
				'status'                         => [
					'description' => __( 'Whether the Workflow is active or disabled.', 'automatewoo' ),
					'type'        => 'string',
					'enum'        => [ 'disabled', 'active' ],
					'context'     => Context::VIEW_ONLY,
				],
				'type'                           => [
					'description' => __( 'The workflow type.', 'automatewoo' ),
					'type'        => 'string',
					'enum'        => array_keys( Workflows::get_types() ),
					'context'     => Context::VIEW_ONLY,
				],
				'trigger'                        => [
					'description' => __( 'The type of event that triggers the workflow to run.', 'automatewoo' ),
					'type'        => 'array',
					'context'     => Context::VIEW_ONLY,
					'properties'  => [
						'name'    => [
							'description' => __( 'The name of the trigger for the workflow.', 'automatewoo' ),
							'type'        => 'string',
						],
						'options' => [
							'description' => __( 'The options for the workflow trigger.', 'automatewoo' ),
							'type'        => 'array',
						],
					],
				],
				'timing_type'                    => [
					'description' => __( 'When the workflow will run in relation to the trigger.', 'automatewoo' ),
					'type'        => 'string',
					'enum'        => [ 'immediately', 'delayed', 'scheduled', 'datetime' ],
					'context'     => Context::VIEW_ONLY,
				],
				'is_tracking_enabled'            => [
					'description' => __( 'Whether tracking is enabled for the Workflow.', 'automatewoo' ),
					'type'        => 'boolean',
					'context'     => Context::VIEW_ONLY,
				],
				'is_conversion_tracking_enabled' => [
					'description' => __( 'Whether conversion tracking is enabled for the Workflow.', 'automatewoo' ),
					'type'        => 'boolean',
					'context'     => Context::VIEW_ONLY,
				],
			],
		];

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Prepare the workflow for the REST response.
	 *
	 * @param Workflow        $workflow
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|mixed
	 */
	public function prepare_item_for_response( $workflow, $request ) {
		$data = [
			'id'                             => $workflow->get_id(),
			'title'                          => $workflow->get_title(),
			'status'                         => $workflow->get_status(),
			'type'                           => $workflow->get_type(),
			'trigger'                        => [
				'name'    => $workflow->get_trigger_name(),
				'options' => $workflow->get_trigger_options(),
			],
			'timing_type'                    => $workflow->get_timing_type(),
			'is_tracking_enabled'            => $workflow->is_tracking_enabled(),
			'is_conversion_tracking_enabled' => $workflow->is_conversion_tracking_enabled(),
		];

		return rest_ensure_response( $data );
	}

	/**
	 * Adds the schema from additional fields to a schema array.
	 *
	 * The type of object is inferred from the passed schema.
	 *
	 * @param array $schema Schema array.
	 *
	 * @return array Modified Schema array.
	 */
	abstract protected function add_additional_fields_schema( $schema );
}
