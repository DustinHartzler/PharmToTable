<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\TaskHistory\Task;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use OM4\WooCommerceZapier\TaskHistory\Task\TaskDataStore;
use OM4\WooCommerceZapier\TaskHistory\Task\CreatorDefinition;

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
	 * @param  Event $event       Event instance.
	 * @param  int   $resource_id Resource ID.
	 * @param  int   $child_id    Child ID.
	 * @param  int   $webhook_id  Webhook ID.
	 *
	 * @return Task Task instance.
	 */
	public function record( $event, $resource_id, $child_id = 0, $webhook_id = 0 ) {
		$id   = 0 === $child_id ? $resource_id : $child_id;
		$name = 0 === $child_id ? $this->get_resource_name() : $this->get_child_name();
		if ( 'action' === $event->type ) {
			$message = sprintf(
				// Translators: 1. Action word, 2. Resource type, 3. Resource ID, 4. Event name.
				__( '%1$s %2$s #%3$s via <strong>%4$s</strong> action', 'woocommerce-zapier' ),
				$event->action_word,
				$name,
				$id,
				$event->name
			);
		} else {
			$message = sprintf(
				// Translators: 1. Resource type, 2. Resource ID, 3. Event name.
				__( 'Sent %1$s #%2$s successfully via <strong>%3$s</strong> trigger', 'woocommerce-zapier' ),
				$name,
				$id,
				$event->name
			);
		}
		$task = $this->data_store->new_task();
		$task->set_webhook_id( $webhook_id );
		$task->set_resource_id( $resource_id );
		$task->set_resource_type( $this->get_resource_type() );
		$task->set_child_id( $child_id );
		if ( 0 !== $child_id ) {
			$task->set_child_type( $this->get_child_type() );
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
	public function get_child_type() {
		return '';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_child_name() {
		return '';
	}
}
