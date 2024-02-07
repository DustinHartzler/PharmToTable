<?php

namespace OM4\WooCommerceZapier\TaskHistory\Task;

use OM4\WooCommerceZapier\Exception\InvalidTaskException;
use OM4\WooCommerceZapier\Helper\WordPressDB;
use OM4\WooCommerceZapier\TaskHistory\Task\Task;
use WC_Data;
use WC_Object_Data_Store_Interface;

defined( 'ABSPATH' ) || exit;

/**
 * Task Data Store.
 *
 * Responsible for creating, reading, updating and deleting Task objects and records
 * to and from the custom database table.
 *
 * @since 2.0.0
 */
class TaskDataStore implements WC_Object_Data_Store_Interface {

	/**
	 * Database table name for storing task history records.
	 */
	const TASK_HISTORY_TABLE = 'wc_zapier_history';

	/**
	 * WordPressDB instance.
	 *
	 * @var WordPressDB
	 */
	protected $wp_db;

	/**
	 * Constructor
	 *
	 * @param WordPressDB $wpdb WordPressDB instance.
	 */
	public function __construct( WordPressDB $wpdb ) {
		$this->wp_db = $wpdb;
	}

	/**
	 * Get the database table name where Zapier Tasks are stored.
	 *
	 * @return string
	 */
	public function get_table_name() {
		return $this->wp_db->prefix . self::TASK_HISTORY_TABLE;
	}

	/**
	 * Create a new Task record.
	 *
	 * @param Task $task Task object.
	 *
	 * @return void
	 */
	public function create( &$task ) {
		$this->wp_db->insert(
			$this->get_table_name(),
			$this->map_to_db_fields_for_edit( $task ),
			$this->get_db_field_formats()
		);

		$task_id = $this->wp_db->insert_id;
		$task->set_id( $task_id );
		$task->apply_changes();
	}

	/**
	 * Method to read a task record from the database
	 *
	 * @param Task $task Task object.
	 *
	 * @throws InvalidTaskException If the specified task doesn't exist.
	 *
	 * @return void
	 */
	public function read( &$task ) {
		$task->set_defaults();

		if ( 0 === $task->get_id() ) {
			// TODO: log?
			throw new InvalidTaskException( __( 'Invalid task history: no ID.', 'woocommerce-zapier' ) );
		}

		$query = $this->wp_db->prepare(
			'SELECT * FROM ' . $this->get_table_name() . ' WHERE history_id = %d LIMIT 1;',
			$task->get_id()
		);
		if ( ! is_string( $query ) ) {
			return;
		}
		$data = $this->wp_db->get_row( $query, ARRAY_A );

		if ( ! is_array( $data ) ) {
			// TODO: log?
			throw new InvalidTaskException( __( 'Invalid task history record: not found.', 'woocommerce-zapier' ) );
		}

		$task->set_props(
			array(
				'id'            => $data['history_id'],
				'date_time'     => '0000-00-00 00:00:00' === $data['date_time'] ? null : $data['date_time'],
				'status'        => $data['status'],
				'webhook_id'    => $data['webhook_id'],
				'resource_type' => $data['resource_type'],
				'resource_id'   => $data['resource_id'],
				'child_type'    => $data['child_type'],
				'child_id'      => $data['child_id'],
				'message'       => $data['message'],
				'event_type'    => $data['event_type'],
				'event_topic'   => $data['event_topic'],
			)
		);
		$task->set_object_read( true );
	}

	/**
	 * Updates a record in the database.
	 *
	 * @param Task $task Task object.
	 *
	 * @return void
	 */
	public function update( &$task ) {

		$data = $this->map_to_db_fields_for_edit( $task );
		$this->wp_db->update(
			$this->get_table_name(),
			$data,
			array(
				'history_id' => $task->get_id(),
			),
			$this->get_db_field_formats(),
			'%d'
		);

		$task->apply_changes();
	}

	/**
	 * Deletes a record from the database.
	 *
	 * @param Task  $task Data object.
	 * @param array $args Array of args to pass to the delete method.
	 *
	 * @return bool result
	 */
	public function delete( &$task, $args = array() ) {
		$result = $this->wp_db->delete(
			$this->get_table_name(),
			array(
				'history_id' => $task->get_id(),
			),
			'%d'
		);

		return ( false !== $result ) ? true : false;
	}

	/**
	 * Map a Task object to an array that represents a database row.
	 *
	 * Excludes the primary key.
	 *
	 * @param Task $task The Task record.
	 *
	 * @see self::get_db_field_formats() which needs to use the same field order.
	 *
	 * @return array
	 */
	protected function map_to_db_fields_for_edit( $task ) {
		$data = array(
			// Convert WC_DateTime to a MySQL date/time or string to store in the DB.
			'date_time'     => $task->get_date_time( 'edit' ) ? $task->get_date_time( 'edit' )->date( 'Y-m-d H:i:s' ) : '',
			'status'        => $task->get_status( 'edit' ),
			'webhook_id'    => $task->get_webhook_id( 'edit' ),
			'resource_type' => $task->get_resource_type( 'edit' ),
			'resource_id'   => $task->get_resource_id( 'edit' ),
			'child_type'    => $task->get_child_type( 'edit' ),
			'child_id'      => $task->get_child_id( 'edit' ),
			'message'       => $task->get_message( 'edit' ),
			'event_type'    => $task->get_event_type( 'edit' ),
			'event_topic'   => $task->get_event_topic( 'edit' ),
		);

		return $data;
	}

	/**
	 * Get the total number of task records matching the specified criteria.
	 *
	 * @param array $args Search criteria.
	 *
	 * @return int Number of tasks matching the search criteria.
	 */
	public function get_tasks_count( $args = array() ) {
		return absint( $this->get_tasks_matching_criteria( array_merge( array( 'count' => true ), $args ) ) );
	}


	/**
	 * Get Task records matching the specified criteria.
	 *
	 * @param array $args Search criteria.
	 *
	 * @return Task[]
	 */
	public function get_tasks( $args = array() ) {
		$tasks = $this->get_tasks_matching_criteria( $args );
		if ( ! is_array( $tasks ) ) {
			return array();
		}
		return $tasks;
	}

	/**
	 * Get Task records matching the specified criteria.
	 *
	 * @since 2.8.0 Added support for specifying `resource_type` as an array.
	 * @since 2.10.0 Added `status` search criteria.
	 * @since 2.10.0 Added `status_not` search criteria.
	 * @since 2.10.0 Added `search` search criteria.
	 *
	 * @param array $args Search criteria.
	 *
	 * @return Task[]|int
	 */
	protected function get_tasks_matching_criteria( $args = array() ) {
		$default_args = array(
			'status'        => null,
			'status_not'    => null,
			// Searches the message and status fields.
			'search'        => null,
			'resource_id'   => null,
			'child_id'      => null,
			'resource_type' => null,
			'orderby'       => 'history_id',
			'order'         => 'DESC',
			'limit'         => 20,
			'offset'        => 0,
			'count'         => false,
		);

		$args = wp_parse_args( $args, $default_args );

		$query = 'SELECT * FROM ' . $this->get_table_name() . ' WHERE 1=1';

		if ( ! empty( $args['resource_id'] ) ) {
			$query .= $this->wp_db->prepare( ' AND resource_id = %d', absint( $args['resource_id'] ) );
		}
		if ( ! empty( $args['status'] ) ) {
			$query .= $this->wp_db->prepare( ' AND status = %s', $args['status'] );
		}
		if ( ! empty( $args['status_not'] ) ) {
			$query .= $this->wp_db->prepare( ' AND status <> %s', $args['status_not'] );
		}
		if ( ! empty( $args['search'] ) ) {
			$query .= $this->wp_db->prepare(
				' AND ( message LIKE %s OR status LIKE %s )',
				'%' . $this->wp_db->esc_like( $args['search'] ) . '%',
				'%' . $this->wp_db->esc_like( $args['search'] ) . '%'
			);
		}
		if ( ! empty( $args['resource_type'] ) ) {
			if ( \is_string( $args['resource_type'] ) ) {
				// A single resource type.
				$query .= $this->wp_db->prepare( ' AND resource_type = %s', $args['resource_type'] );
			} elseif ( \is_array( $args['resource_type'] ) ) {
				/**
				 * List of resource types.
				 *
				 * @var string[] $resource_types
				 */
				$resource_types = array_map( 'esc_sql', $args['resource_type'] );
				$query         .= ' AND resource_type IN (\'' . implode( '\', \'', $resource_types ) . '\')';
			}
		}
		if ( ! is_null( $args['child_id'] ) ) {
			$query .= $this->wp_db->prepare( ' AND child_id = %d', absint( $args['child_id'] ) );
		}

		if ( true === $args['count'] ) {
			$query_count = str_replace( 'SELECT * FROM', 'SELECT count(history_id) FROM', $query );
			return absint( $this->wp_db->get_var( $query_count ) );
		}

		// Sanity checks.
		if ( $args['limit'] > 200 ) {
			$args['limit'] = $default_args['limit'];
		}
		$query .= $this->wp_db->prepare( ' ORDER BY ' . sanitize_sql_orderby( $args['orderby'] . ' ' . $args['order'] ) . ' LIMIT %d, %d', absint( $args['offset'] ), absint( $args['limit'] ) );

		$items  = array();
		$result = $this->wp_db->get_results( $query, ARRAY_A );
		if ( ! is_array( $result ) ) {
			return array();
		}
		foreach ( $result as $item ) {
			$items[] = new Task( $this, $item );
		}
		return $items;
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	*/

	/**
	 * Factory for creating new task.
	 *
	 * @param int|Task|array $task Task ID to load from the DB (optional) or already queried data.

	 * @return Task
	 */
	public function new_task( $task = 0 ) {
		return new Task( $this, $task );
	}

	/**
	 * Get the database field formats for each field
	 *
	 * Excludes the primary key.
	 *
	 * @see self::map_to_db_fields_for_edit() for the field order.
	 *
	 * @return array
	 */
	protected function get_db_field_formats() {
		return array(
			'%s',
			'%s',
			'%d',
			'%s',
			'%d',
			'%s',
			'%d',
			'%s',
			'%s',
			'%s',
		);
	}

	/**
	 * Get the total number of Trigger-related task records for each webhook ID.
	 *
	 * These counts are for trigger-related tasks only.
	 *
	 * @return array Associative array with `webhook_id`, `resource_type` and `count` values.
	 */
	public function get_trigger_task_count() {
		$results = $this->wp_db->get_results(
			'SELECT webhook_id, resource_type, count(*) AS count FROM ' . $this->get_table_name() . " WHERE event_type='trigger' GROUP BY webhook_id, resource_type ORDER BY webhook_id DESC",
			ARRAY_A
		);

		if ( ! is_array( $results ) ) {
			return array();
		}
		foreach ( $results as $key => $result ) {
			$results[ $key ]['count']      = intval( $results[ $key ]['count'] );
			$results[ $key ]['webhook_id'] = intval( $results[ $key ]['webhook_id'] );
		}
		return $results;
	}

	/**
	 * Get the total number of Action-related task records for each resource.
	 *
	 * These counts are for action-related tasks only.
	 *
	 * @return array Associative array with `resource_type` and `count` values.
	 */
	public function get_action_task_counts() {
		$results = $this->wp_db->get_results(
			'SELECT resource_type, count(*) AS count FROM ' . $this->get_table_name() . " WHERE event_type='action' GROUP BY resource_type ORDER BY resource_type ASC",
			ARRAY_A
		);

		if ( ! is_array( $results ) ) {
			return array();
		}
		foreach ( $results as $key => $result ) {
			$results[ $key ]['count'] = intval( $results[ $key ]['count'] );
		}
		return $results;
	}

	/*
	|--------------------------------------------------------------------------
	| Unused methods (required by WC_Object_Data_Store_Interface but not used)
	|--------------------------------------------------------------------------
	*/

	/**
	 * Returns an array of meta for an object.
	 *
	 * @internal Do not use.
	 * @codeCoverageIgnore
	 *
	 * @param WC_Data $data Data object.
	 *
	 * @return array
	 */
	public function read_meta( &$data ) {
		return array();
	}

	/**
	 * Deletes meta based on meta ID.
	 *
	 * @internal Do not use.
	 * @codeCoverageIgnore
	 *
	 * @param WC_Data $data Data object.
	 * @param object  $meta Meta object (containing at least ->id).
	 *
	 * @return array
	 */
	public function delete_meta( &$data, $meta ) {
		return array();
	}

	/**
	 * Add new piece of meta.
	 *
	 * @internal Do not use.
	 * @codeCoverageIgnore
	 *
	 * @param WC_Data $data Data object.
	 * @param object  $meta Meta object (containing ->key and ->value).
	 *
	 * @return int meta ID
	 */
	public function add_meta( &$data, $meta ) {
		return 0;
	}

	/**
	 * Update meta.
	 *
	 * @internal Do not use.
	 * @codeCoverageIgnore
	 *
	 * @param WC_Data $data Data object.
	 * @param object  $meta Meta object (containing ->id, ->key and ->value).
	 *
	 * @return void
	 */
	public function update_meta( &$data, $meta ) {
	}
}
