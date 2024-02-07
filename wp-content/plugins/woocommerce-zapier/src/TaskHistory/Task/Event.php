<?php

namespace OM4\WooCommerceZapier\TaskHistory\Task;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Represents an individual Event for the Task History Task record.
 *
 * @since 2.8.0
 */
class Event {

	/**
	 * Type of the event.
	 *
	 * @var 'action'|'trigger'
	 */
	public $type;

	/**
	 * Machine-readable event topic 'order.created' or 'create.order' etc.
	 *
	 * @var string
	 */
	public $topic;

	/**
	 * Human-readable event name 'Order Created' or 'Create Order' etc.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Action word in the message 'Created', 'Updated', 'Creating', 'Updating' etc.
	 *
	 * @var string
	 */
	public $action_word = '';

	/**
	 * Error object (if the event was not a success).
	 *
	 * @since 2.10.0
	 *
	 * @var WP_Error|null
	 */
	public $error;

	/**
	 * Create a new Event instance for a create action.
	 *
	 * @param  string        $resource_type  Machine-readable resource type (e.g. 'order_note').
	 * @param  WP_Error|null $error  Error object (if the event was not a success).
	 *
	 * @return Event
	 */
	public static function action_create( $resource_type, $error = null ) {
		$event              = new self();
		$event->type        = 'action';
		$event->topic       = $resource_type . '.create';
		$event->name        = self::name( 'Create', $resource_type );
		$event->error       = $error;
		$event->action_word = $event->is_successful() ?
			__( 'Created', 'woocommerce-zapier' ) :
			__( 'creating', 'woocommerce-zapier' );
		return $event;
	}

	/**
	 * Create a new Event instance for an update action.
	 *
	 * @param  string        $resource_type  Resource type.
	 * @param  WP_Error|null $error  Error object (if the event was not a success).
	 *
	 * @return Event
	 */
	public static function action_update( $resource_type, $error = null ) {
		$event              = new self();
		$event->type        = 'action';
		$event->topic       = $resource_type . '.update';
		$event->name        = self::name( 'Update', $resource_type );
		$event->error       = $error;
		$event->action_word = $event->is_successful() ?
			__( 'Updated', 'woocommerce-zapier' ) :
			__( 'updating', 'woocommerce-zapier' );
		return $event;
	}

	/**
	 * Create a new Event instance for any trigger.
	 *
	 * @param string        $webhook_topic Machine-readable topic (e.g. 'order.created').
	 * @param string        $topic_name   Human-readable topic name (e.g. 'Order Created').
	 * @param WP_Error|null $error  Error object (if the event was not a success).
	 *
	 * @return Event
	 */
	public static function trigger( $webhook_topic, $topic_name, $error = null ) {
		$event        = new self();
		$event->type  = 'trigger';
		$event->topic = $webhook_topic;
		$event->name  = $topic_name;
		$event->error = $error;
		return $event;
	}

	/**
	 * Assemble event name from action and resource type.
	 *
	 * @param string $action The action (e.g. 'Create').
	 * @param string $resource_type Machine-readable resource type (e.g. 'order_note').
	 * @return string
	 */
	protected static function name( $action, $resource_type ) {
		return sprintf(
			// Translators: 1. Action (e.g. 'Create'), 2. Resource type (e.g. 'Order Note').
			__( '%1$s %2$s', 'woocommerce-zapier' ),
			$action,
			ucwords( str_replace( '_', ' ', $resource_type ) )
		);
	}

	/**
	 * Whether this event was successful or not.
	 *
	 * @since 2.10.0
	 *
	 * @return bool
	 */
	public function is_successful() {
		return is_null( $this->error );
	}
}
