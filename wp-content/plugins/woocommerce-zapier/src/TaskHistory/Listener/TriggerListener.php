<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\TaskHistory\Listener;

use ActionScheduler_Logger;
use ActionScheduler_Store;
use Exception;
use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\Exception\InvalidImplementationException;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Bookings\BookingsTaskCreator;
use OM4\WooCommerceZapier\Plugin\Memberships\Plan\MembershipPlanTaskCreator;
use OM4\WooCommerceZapier\Plugin\Memberships\User\UserMembershipsTaskCreator;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\SubscriptionNote;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\SubscriptionNoteTaskCreator;
use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionsTaskCreator;
use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use OM4\WooCommerceZapier\TaskHistory\Task\TaskDataStore;
use OM4\WooCommerceZapier\Webhook\Resources;
use OM4\WooCommerceZapier\Webhook\ZapierWebhook;
use OM4\WooCommerceZapier\WooCommerceResource\Coupon\CouponTaskCreator;
use OM4\WooCommerceZapier\WooCommerceResource\Customer\CustomerTaskCreator;
use OM4\WooCommerceZapier\WooCommerceResource\Order\Note\OrderNote;
use OM4\WooCommerceZapier\WooCommerceResource\Order\Note\OrderNoteTaskCreator;
use OM4\WooCommerceZapier\WooCommerceResource\Order\OrderTaskCreator;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductTaskCreator;
use WC_Webhook;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Listener to detect when WooCommerce delivers data to Zapier via our Webhooks,
 * and record the event to our Task History.
 *
 * Also detect when Action Scheduler actions fail, and record the event to our Task History
 * if the failed action relates to a Zapier Webhook send.
 *
 * @since 2.0.0
 */
class TriggerListener {
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
	 * Resources instance.
	 *
	 * @var Resources
	 */
	protected $webhook_resources;

	/**
	 * ContainerService instance.
	 *
	 * @var ContainerService
	 */
	protected $container;

	/**
	 * TriggerListener constructor.
	 *
	 * @param Logger           $logger            Logger.
	 * @param TaskDataStore    $data_store        TaskDataStore instance.
	 * @param Resources        $webhook_resources Webhook Topics.
	 * @param ContainerService $container         ContainerService instance.
	 *
	 * @return void
	 */
	public function __construct(
		Logger $logger,
		TaskDataStore $data_store,
		Resources $webhook_resources,
		ContainerService $container
	) {
		$this->logger            = $logger;
		$this->data_store        = $data_store;
		$this->webhook_resources = $webhook_resources;
		$this->container         = $container;
	}

	/**
	 * Instructs the functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action( 'woocommerce_webhook_delivery', array( $this, 'woocommerce_webhook_delivery' ), 10, 5 );
		add_action( 'action_scheduler_failed_execution', array( $this, 'action_scheduler_failed_execution' ), 10, 2 );
		// Priority 11 to ensure this runs after Action Scheduler logs the timeout.
		add_action( 'action_scheduler_failed_action', array( $this, 'action_scheduler_failed_action' ), 11 );
	}

	/**
	 * Whenever WooCommerce successfully delivers a payload to a WC Zapier webhook, add the event to our task history.
	 *
	 * Executed by the `woocommerce_webhook_delivery` hook (which occurs for all Webhooks not just Zapier Webhooks)
	 *
	 * @param array          $http_args HTTP request arguments.
	 * @param WP_Error|array $response HTTP response or WP_Error on webhook delivery failure.
	 * @param float          $duration Delivery duration (in microseconds).
	 * @param mixed          $arg Usually the resource ID.
	 * @param int            $webhook_id ID Webhook ID.
	 *
	 * @return void
	 * @throws InvalidImplementationException If an unknown resource type is encountered.
	 */
	public function woocommerce_webhook_delivery( $http_args, $response, $duration, $arg, $webhook_id ) {
		$webhook = new ZapierWebhook( $webhook_id );
		if ( 0 === $webhook->get_id() ) {
			// Webhook doesn't exist.
			return;
		}

		if ( ! $webhook->is_zapier_webhook() ) {
			return;
		}

		$resource_id   = \absint( $arg );
		$resource_type = $webhook->get_resource();

		list($resource_id, $child_id, $task_creator, $topic_name) = $this->prepare_task_creator_data( $resource_id, $resource_type, $webhook );

		$is_successful = true;

		$payload = \json_decode( $http_args['body'], true );
		if ( is_array( $payload ) && isset( $payload['code'] ) && isset( $payload['message'] ) && isset( $payload['data']['status'] ) ) {
			// The payload (data) sent to Zapier was a WP_Error.
			// Record the error in the task history as a failed task.
			// Zapier will also throw an error for this Task, but we want to record the error in our task history too.
			$event = Event::trigger(
				$webhook->get_topic(),
				$topic_name,
				// Give the original error message more context.
				new WP_Error(
					$payload['code'],
					sprintf(
						// translators: 1. Trigger payload error message.
						__( 'Unexpected trigger payload: %s', 'woocommerce-zapier' ),
						$payload['message']
					)
				)
			);
			$task_creator->record( $event, $resource_id, $child_id, $webhook_id );
			$this->logger->error(
				'Webhook delivery error for Webhook ID %d (%s) - %s ID: %d. Error Code: %s. Error Message: %s.',
				array(
					$webhook_id,
					$webhook->get_topic(),
					\ucfirst( $resource_type ),
					$resource_id,
					// @phpstan-ignore-next-line The error property is always set here.
					$event->error->get_error_code(),
					// @phpstan-ignore-next-line The error property is always set here.
					$event->error->get_error_message(),
				)
			);
			$is_successful = false;
			// Fall through, just in case there is an additional error in the response.
		}

		$response_status_code = intval( wp_remote_retrieve_response_code( $response ) );

		if ( is_wp_error( $response ) ) {
			// Some kind of HTTP error when sending the data to Zapier.
			$event = Event::trigger(
				$webhook->get_topic(),
				$topic_name,
				// Give the original error message more context.
				new WP_Error(
					$response->get_error_code(),
					sprintf(
						// translators: 1. HTTP Response error message.
						__( 'Communication error with zapier.com: %s', 'woocommerce-zapier' ),
						$response->get_error_message()
					)
				)
			);
			$task_creator->record( $event, $resource_id, $child_id, $webhook_id );
			$this->logger->error(
				'Webhook delivery error for Webhook ID %d (%s) - %s ID: %d. Error Code: %s. Error Message: %s.',
				array(
					$webhook_id,
					$webhook->get_topic(),
					\ucfirst( $resource_type ),
					$resource_id,
					// @phpstan-ignore-next-line The error property is always set here.
					$event->error->get_error_code(),
					// @phpstan-ignore-next-line The error property is always set here.
					$event->error->get_error_message(),
				)
			);
			$is_successful = false;
		} elseif ( ! ( $response_status_code >= 200 && $response_status_code < 303 ) ) {
			/**
			 * The HTTP request was successful, but the response from Zapier was an error.
			 *
			 * @see WC_Webhook::log_delivery()
			 */
			$response_message = wp_remote_retrieve_response_message( $response );
			$event            = Event::trigger(
				$webhook->get_topic(),
				$topic_name,
				new WP_Error(
					'trigger_error_response',
					sprintf(
						// translators: 1. HTTP Response Code. 2. HTTP Response Message.
						__( 'Zapier.com returned an unexpected HTTP status code: %1$d (%2$s)', 'woocommerce-zapier' ),
						$response_status_code,
						$response_message
					)
				)
			);
			$task_creator->record( $event, $resource_id, $child_id, $webhook_id );
			$this->logger->error(
				'Webhook delivery error for Webhook ID %d (%s) - %s ID: %d. Error Code: %s. Error Message: %s. Webhook Failure Count: %d.',
				array(
					$webhook_id,
					$webhook->get_topic(),
					\ucfirst( $resource_type ),
					$resource_id,
					// @phpstan-ignore-next-line The error property is always set here.
					$event->error->get_error_code(),
					// @phpstan-ignore-next-line The error property is always set here.
					$event->error->get_error_message(),
					$webhook->get_failure_count(),
				)
			);
			$is_successful = false;
		} else {
			$event = Event::trigger( $webhook->get_topic(), $topic_name );
		}

		if ( $is_successful ) {
			/**
			 * Log the successful delivery (debug level).
			 *
			 * This replaces the previously implemented
			 * OM4\WooCommerceZapier\Webhook\DeliveryFilter::woocommerce_webhook_payload(),
			 * which ran during the 'woocommerce_webhook_payload' filter.
			 */
			$this->logger->debug(
				'Webhook delivery successful for Webhook ID %d (%s) - %s ID: %d',
				array(
					$webhook_id,
					$webhook->get_topic(),
					\ucfirst( $resource_type ),
					$resource_id,
				)
			);
			$task_creator->record( $event, $resource_id, $child_id, $webhook_id );
		}
	}


	/**
	 * Whenever an Action Scheduler action execution fails, if the failed action execution relates to a Zapier webhook delivery,
	 * then record the failure in our Task History.
	 *
	 * Executed during the `action_scheduler_failed_execution` hook which occurs for all failed Action Scheduler actions
	 * (not just Webhooks).
	 *
	 * @since 2.10.0
	 *
	 * @see wc_webhook_execute_queue()
	 *
	 * @param int       $action_id Action Scheduler Action ID.
	 * @param Exception $e         Exception instance.
	 *
	 * @return void
	 */
	public function action_scheduler_failed_execution( $action_id, $e ) {
		$this->action_scheduler_failure( $action_id, $e );
	}

	/**
	 * Whenever an Action Scheduler action execution fails via a timeout, if the failed action execution relates to a
	 * Zapier webhook delivery, then record the failure in our Task History.
	 *
	 * Executed during the `action_scheduler_failed_action` hook which occurs for all time out Action Scheduler actions.
	 *
	 * @since 2.10.0
	 *
	 * @param int $action_id Action Scheduler Action ID.
	 *
	 * @return void
	 */
	public function action_scheduler_failed_action( $action_id ) {
		$this->action_scheduler_failure( $action_id );
	}

	/**
	 * Prepare the task creator data for recording in the Task History.
	 *
	 * @since 2.10.0
	 *
	 * @param int           $resource_id Resource ID.
	 * @param  string        $resource_type Resource type.
	 * @param  ZapierWebhook $webhook Zapier Webhook.
	 *
	 * @return array
	 * @throws InvalidImplementationException If an unknown resource type is encountered.
	 */
	protected function prepare_task_creator_data( $resource_id, $resource_type, ZapierWebhook $webhook ) {
		$child_id = null;
		switch ( $resource_type ) {
			case 'booking':
				$task_creator = $this->container->get( BookingsTaskCreator::class );
				break;
			case 'coupon':
				$task_creator = $this->container->get( CouponTaskCreator::class );
				break;
			case 'customer':
				$task_creator = $this->container->get( CustomerTaskCreator::class );
				break;
			case 'membership_plan':
				$task_creator = $this->container->get( MembershipPlanTaskCreator::class );
				break;
			case 'user_membership':
				$task_creator = $this->container->get( UserMembershipsTaskCreator::class );
				break;
			case 'order':
				$task_creator = $this->container->get( OrderTaskCreator::class );
				break;
			case 'order_note':
				$task_creator = $this->container->get( OrderNoteTaskCreator::class );
				$note         = OrderNote::find( $resource_id );
				if ( $note ) {
					// An existing Order Note.
					// Record the note ID, but use the parent order ID as the resource ID.
					$child_id    = $note->id;
					$resource_id = $note->order_id;
				}
				break;
			case 'product':
				$task_creator = $this->container->get( ProductTaskCreator::class );
				$product      = \wc_get_product( $resource_id );
				if ( $product && $product->get_parent_id() > 0 ) {
					// A product variation was sent.
					// Record the variation ID, but use the parent product ID as the resource ID.
					$child_id    = $product->get_id();
					$resource_id = $product->get_parent_id();
				}
				break;
			case 'subscription':
				$task_creator = $this->container->get( SubscriptionsTaskCreator::class );
				break;
			case 'subscription_note':
				$task_creator = $this->container->get( SubscriptionNoteTaskCreator::class );
				$note         = SubscriptionNote::find( $resource_id );
				if ( $note ) {
					// An existing Subscription Note.
					// Record the note ID, but use the parent order ID as the resource ID.
					$child_id    = $note->id;
					$resource_id = $note->subscription_id;
				}
				break;
			default:
				throw new InvalidImplementationException( 'Unknown resource type: ' . $resource_type );
		}

		if ( false !== strpos( $webhook->get_topic(), '.deleted' ) ) {
			$parent_id = \absint( \get_transient( "wc_zapier_{$resource_type}_{$resource_id}_parent_id" ) );
			if ( $parent_id ) {
				$child_id    = $resource_id;
				$resource_id = $parent_id;
				\delete_transient( "wc_zapier_{$resource_type}_{$resource_id}_parent_id" );
			}
		}

		$topics     = $this->webhook_resources->get_topics();
		$topic_name = isset( $topics[ $webhook->get_topic() ] ) ? $topics[ $webhook->get_topic() ] : $webhook->get_topic();

		return array( $resource_id, $child_id, $task_creator, $topic_name );
	}

	/**
	 * Whenever an Action Scheduler action fails (with an error message or timeout), if the failed action relates to
	 * a Zapier webhook delivery, then record the failure in our Task History.
	 *
	 * @since 2.10.0
	 *
	 * @param  int        $action_id Action Scheduler Action ID.
	 * @param  ?Exception $e Exception instance (optional). If not provided, the last Action Scheduler log message will be used as the error message.
	 *
	 * @return void
	 */
	protected function action_scheduler_failure( $action_id, $e = null ): void {
		$store  = ActionScheduler_Store::instance();
		$action = $store->fetch_action( (string) $action_id );

		if ( 'woocommerce_deliver_webhook_async' !== $action->get_hook() ) {
			// Another Action Scheduler action (unrelated to webhooks).
			return;
		}

		$args    = $action->get_args();
		$webhook = new ZapierWebhook( $args['webhook_id'] );
		if ( ! $webhook->is_zapier_webhook() ) {
			// A non-Zapier webhook.
			return;
		}

		$resource_type = $webhook->get_resource();

		list(
			$resource_id, $child_id, $task_creator, $topic_name
			) =
			$this->prepare_task_creator_data(
				\absint( $args['arg'] ),
				$resource_type,
				$webhook
			);

		$error_message = __( 'Unknown error.', 'woocommerce-zapier' );
		if ( is_null( $e ) ) {
			/**
			 * Use the last action scheduler log message as the error message.
			 *
			 * This message should be in the format:
			 * `action marked as failed after x seconds. Unknown error occurred. Check server, PHP and database error logs to diagnose cause.`
			 *
			 * @see ActionScheduler_Logger::log_timed_out_action()
			 */
			$logger = ActionScheduler_Logger::instance();
			$logs   = $logger->get_logs( (string) $action_id );
			if ( ! empty( $logs ) ) {
				$error_message = end( $logs );
				$error_message = $error_message->get_message();
				if ( str_ends_with( $error_message, '.' ) ) {
					// Remove the trailing full stop.
					$error_message = substr( $error_message, 0, -1 );
				}
			}
		} else {
			$error_message = $e->getMessage();
		}

		$event = Event::trigger(
			$webhook->get_topic(),
			$topic_name,
			// Give the original error message more context.
			new WP_Error(
				'action_scheduler_failure',
				sprintf(
					// translators: 1. Error Message. 2. Action ID.
					__( 'Action Scheduler Failure: %1$s. Action ID: %2$d', 'woocommerce-zapier' ),
					$error_message,
					$action_id
				)
			)
		);
		$task_creator->record( $event, $resource_id, $child_id, $webhook->get_id() );
		$this->logger->error(
			'Webhook delivery error for Webhook ID %d (%s) - %s ID: %d. Error Code: %s. Error Message: %s. Action ID: %d.',
			array(
				$webhook->get_id(),
				$webhook->get_topic(),
				\ucfirst( $resource_type ),
				$resource_id,
				// @phpstan-ignore-next-line The error property is always set here.
				$event->error->get_error_code(),
				// @phpstan-ignore-next-line The error property is always set here.
				$event->error->get_error_message(),
				$action_id,
			)
		);
	}
}
