<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\TaskHistory\Task;

use OM4\WooCommerceZapier\Exception\InvalidTaskException;
use OM4\WooCommerceZapier\TaskHistory\Task\TaskDataStore;
use WC_Data;
use WC_DateTime;

defined( 'ABSPATH' ) || exit;

/**
 * Represents as single Task History Task record.
 *
 * @since 2.0.0
 * @since 2.8.0 Moved from OM4\WooCommerceZapier\TaskHistory\Task to OM4\WooCommerceZapier\TaskHistory\Task\Task.
 */
class Task extends WC_Data {

	/**
	 * Task status: successful.
	 *
	 * @since 2.10.0
	 */
	const STATUS_SUCCESS = 'success';

	/**
	 * Stores Task data.
	 *
	 * @var array
	 */
	protected $data = array(
		'date_time'     => null,
		'status'        => '',
		'webhook_id'    => null,
		'resource_id'   => null,
		'resource_type' => null,
		'child_id'      => 0,
		'child_type'    => '',
		'message'       => '',
		'event_type'    => '',
		'event_topic'   => '',
	);

	/**
	 * Name of this data type.
	 *
	 * Used by WooCommerce core, which executes WordPress filters during save/updates.
	 *
	 * @var string
	 */
	protected $object_type = 'zapier_task';

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $data_store;

	/**
	 * Constructor. Creates a new Task or loads and existing Task if specified.
	 *
	 * @param TaskDataStore  $data_store TaskDataStore instance.
	 * @param int|Task|array $task       Task ID to load from the DB (optional) or already queried data.
	 */
	public function __construct( TaskDataStore $data_store, $task = 0 ) {
		$this->data_store = $data_store;
		parent::__construct( $task );

		if ( $task instanceof Task ) {
			$this->set_id( $task->get_id() );
		} elseif ( is_numeric( $task ) ) {
			$this->set_id( $task );
		} elseif ( is_array( $task ) ) {
			// Populate with the supplied data.
			$this->set_id( $task['history_id'] );
			$this->set_props( $task );
			$this->set_object_read( true );
			return;
		}

		// If we have an ID, load the task from the DB.
		if ( 0 !== $this->get_id() ) {
			try {
				$this->data_store->read( $this );
			} catch ( InvalidTaskException $e ) {
				$this->set_id( 0 );
				$this->set_object_read( true );
			}
		} else {
			// Creating a brand new Task.
			$this->set_date_time( (string) new WC_DateTime() );
			$this->set_object_read( true );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get date/time.
	 *
	 * @param  string $context Get context.
	 *
	 * @return WC_DateTime|null DateTime object or null if not set.
	 */
	public function get_date_time( $context = 'view' ) {
		$date_time = $this->get_prop( 'date_time', $context );
		return $date_time instanceof WC_DateTime ? $date_time : null;
	}

	/**
	 * Get status (`success` or an error code).
	 *
	 * @since 2.10.0
	 *
	 * @param  string $context Get context.
	 *
	 * @see self::STATUS_SUCCESS
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		$status = $this->get_prop( 'status', $context );
		return \is_scalar( $status ) ? \strval( $status ) : '';
	}

	/**
	 * Get webhook id.
	 *
	 * @param  string $context Get context.
	 *
	 * @return integer
	 */
	public function get_webhook_id( $context = 'view' ) {
		return absint( $this->get_prop( 'webhook_id', $context ) );
	}

	/**
	 * Get resource type.
	 *
	 * @param  string $context Get context.
	 *
	 * @return string
	 */
	public function get_resource_type( $context = 'view' ) {
		$resource_type = $this->get_prop( 'resource_type', $context );
		return \is_scalar( $resource_type ) ? \strval( $resource_type ) : '';
	}

	/**
	 * Get resource id.
	 *
	 * @param  string $context Get context.
	 *
	 * @return integer
	 */
	public function get_resource_id( $context = 'view' ) {
		return absint( $this->get_prop( 'resource_id', $context ) );
	}

	/**
	 * Get child id.
	 *
	 * @param  string $context Get context.
	 *
	 * @since 2.8.0 Renamed get_variation_id() to get_child_id().
	 *
	 * @return integer
	 */
	public function get_child_id( $context = 'view' ) {
		return absint( $this->get_prop( 'child_id', $context ) );
	}

	/**
	 * Get child type (eg variation, order_notes, etc).
	 *
	 * @param  string $context Get context.
	 *
	 * @since 2.8.0
	 *
	 * @return string
	 */
	public function get_child_type( $context = 'view' ) {
		$child_type = $this->get_prop( 'child_type', $context );
		return \is_scalar( $child_type ) ? \strval( $child_type ) : '';
	}

	/**
	 * Get message.
	 *
	 * @param  string $context Get context.
	 *
	 * @return string
	 */
	public function get_message( $context = 'view' ) {
		$message = $this->get_prop( 'message', $context );
		return \is_scalar( $message ) ? \strval( $message ) : '';
	}

	/**
	 * Get event_type (action or trigger).
	 *
	 * @param  string $context Get context.
	 *
	 * @since 2.8.0 Renamed get_type() to get_event_type().
	 *
	 * @return string
	 */
	public function get_event_type( $context = 'view' ) {
		$event_type = $this->get_prop( 'event_type', $context );
		return \is_scalar( $event_type ) ? \strval( $event_type ) : '';
	}

	/**
	 * Get event_topic (eg Resource updated, Create Resource,etc).
	 *
	 * @param  string $context Get context.
	 *
	 * @since 2.8.0
	 *
	 * @return string
	 */
	public function get_event_topic( $context = 'view' ) {
		$event_topic = $this->get_prop( 'event_topic', $context );
		return \is_scalar( $event_topic ) ? \strval( $event_topic ) : '';
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set date/time.
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if there is no date.
	 *
	 * @return void
	 */
	public function set_date_time( $date = null ) {
		if ( is_null( $date ) ) {
			$date = '';
		}
		$this->set_date_prop( 'date_time', $date );
	}

	/**
	 * Set status.
	 *
	 * @since 2.10.0
	 *
	 * @param string $value Value to set (eg `success`, or an error code).
	 *
	 * @see self::STATUS_SUCCESS
	 *
	 * @return void
	 */
	public function set_status( $value ) {
		$this->set_prop( 'status', $value );
	}

	/**
	 * Set webhook id.
	 *
	 * @param int $value Value to set.
	 *
	 * @return void
	 */
	public function set_webhook_id( $value ) {
		$this->set_prop( 'webhook_id', absint( $value ) );
	}

	/**
	 * Set resource type (eg product, order, customer, etc).
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_resource_type( $value ) {
		$this->set_prop( 'resource_type', $value );
	}

	/**
	 * Set resource id.
	 *
	 * @param int $value Value to set.
	 *
	 * @return void
	 */
	public function set_resource_id( $value ) {
		$this->set_prop( 'resource_id', absint( $value ) );
	}

	/**
	 * Set child id.
	 *
	 * @param int $value Value to set.
	 *
	 * @since 2.8.0 Renamed set_variation_id() to set_child_id().
	 *
	 * @return void
	 */
	public function set_child_id( $value ) {
		$this->set_prop( 'child_id', absint( $value ) );
	}

	/**
	 * Set child type (eg variation, order_notes, etc).
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_child_type( $value ) {
		$this->set_prop( 'child_type', $value );
	}

	/**
	 * Set message.
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_message( $value ) {
		$this->set_prop( 'message', $value );
	}

	/**
	 * Set event_type.
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_event_type( $value ) {
		$this->set_prop( 'event_type', $value );
	}

	/**
	 * Set event_topic.
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_event_topic( $value ) {
		$this->set_prop( 'event_topic', $value );
	}
}
