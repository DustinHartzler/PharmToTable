<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\TaskHistory\Task;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Task\CreatorDefinition;
use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use OM4\WooCommerceZapier\TaskHistory\Task\TaskDataStore;
use Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract class to save a record to the Task History table.
 *
 * @since 2.8.0
 */
abstract class CreatorBase implements CreatorDefinition {
	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $data_store;

	/**
	 * Constructor.
	 *
	 * @param Logger        $logger     Logger.
	 * @param TaskDataStore $data_store TaskDataStore instance.
	 */
	public function __construct( Logger $logger, TaskDataStore $data_store ) {
		$this->logger     = $logger;
		$this->data_store = $data_store;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 2.10.0 Changed $child_id to be nullable.
	 *
	 * @param  Event    $event       Event instance.
	 * @param  int      $resource_id Resource ID.
	 * @param  int|null $child_id    Child ID.
	 * @param  int      $webhook_id  Webhook ID.
	 *
	 * @return Task Task instance.
	 */
	public function record( $event, $resource_id, $child_id = null, $webhook_id = 0 ) {
		$id   = \is_null( $child_id ) ? $resource_id : $child_id;
		$name = \is_null( $child_id ) ? static::resource_name() : static::child_name();
		if ( 'action' === $event->type ) {
			// Action.
			if ( \is_null( $event->error ) ) {
				// Successful Action.
				$message = sprintf(
					// Translators: 1. Action word, 2. Resource type, 3. Resource ID, 4. Event name.
					__( '%1$s %2$s #%3$s via <strong>%4$s</strong> action', 'woocommerce-zapier' ),
					$event->action_word,
					$name,
					$id,
					$event->name
				);
			} else {
				// Unsuccessful Action.
				if ( $id > 0 ) {
					$message = sprintf(
						// Translators: 1. Action word, 2. Resource type, 3. Resource ID, 4. Event name. 5. Error Message.
						__( 'Error %1$s %2$s #%3$s via <strong>%4$s</strong> action.<br />%5$s', 'woocommerce-zapier' ),
						$event->action_word,
						$name,
						$id,
						$event->name,
						$event->error->get_error_message()
					);
				} else {
					$message = sprintf(
					// Translators: 1. Action word, 2. Resource type, 3. Event name. 5. Error Message.
						__( 'Error %1$s %2$s via <strong>%3$s</strong> action.<br />%4$s', 'woocommerce-zapier' ),
						$event->action_word,
						$name,
						$event->name,
						$event->error->get_error_message()
					);
				}
			}
		} else {
			// Trigger.
			if ( \is_null( $event->error ) ) {
				// Successful Trigger.
				$message = sprintf(
					// Translators: 1. Resource type, 2. Resource ID, 3. Event name.
					__( 'Sent %1$s #%2$s successfully via <strong>%3$s</strong> trigger', 'woocommerce-zapier' ),
					$name,
					$id,
					$event->name
				);
			} else {
				// Unsuccessful Trigger.
				$message = sprintf(
					// Translators: 1. Resource type, 2. Resource ID, 3. Event name. 4. Error Message.
					__( 'Error sending %1$s #%2$s via <strong>%3$s</strong> trigger.<br />%4$s', 'woocommerce-zapier' ),
					$name,
					$id,
					$event->name,
					$event->error->get_error_message()
				);
			}
		}
		$task = $this->data_store->new_task();
		$task->set_status( \is_null( $event->error ) ? Task::STATUS_SUCCESS : (string) $event->error->get_error_code() );
		$task->set_webhook_id( $webhook_id );
		$task->set_resource_id( $resource_id );
		$task->set_resource_type( static::resource_type() );
		$task->set_child_id( \is_null( $child_id ) ? 0 : $child_id );
		if ( ! \is_null( $child_id ) ) {
			$task->set_child_type( static::child_type() );
		}
		$task->set_event_type( $event->type );
		$task->set_event_topic( $event->topic );
		$task->set_message( $message );

		if ( 0 === $task->save() ) {
			$this->logger->critical(
				'Error creating task history record. Data: %s',
				(string) \wp_json_encode( $task->get_data() )
			);
		}

		return $task;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function child_type() {
		return '';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function child_name() {
		return '';
	}
}
