<?php

namespace OM4\WooCommerceZapier\TaskHistory\Task;

use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use OM4\WooCommerceZapier\TaskHistory\Task\Task;

defined( 'ABSPATH' ) || exit;

/**
 * Interface to save a record to the Task History table.
 *
 * @since 2.0.0
 */
interface CreatorDefinition {

	/**
	 * Create, fill and save a Task History record.
	 *
	 * Logs when the task could not be created.
	 *
	 * @since 2.10.0 Changed $child_id to be nullable.
	 *
	 * @param  Event    $event       Event instance.
	 * @param  int      $resource_id Resource ID.
	 * @param  int|null $child_id    Child ID.
	 * @param  int      $webhook_id  Webhook ID.
	 *
	 * @since 2.8.0
	 * @return Task
	 */
	public function record( $event, $resource_id, $child_id = null, $webhook_id = 0 );

	/**
	 * Get the resource machine readable Type (e.g. 'order').
	 *
	 * @since 2.8.0
	 * @return string
	 */
	public static function resource_type();

	/**
	 * Get the resource human readable name (e.g. 'Order').
	 *
	 * @since 2.8.0
	 * @return string
	 */
	public static function resource_name();

	/**
	 * Get the child machine readable Type (e.g. 'order_notes').
	 *
	 * @since 2.8.0
	 * @return string
	 */
	public static function child_type();

	/**
	 * Get the child human readable name (e.g. 'Order Notes').
	 *
	 * @since 2.8.0
	 * @return string
	 */
	public static function child_name();
}
