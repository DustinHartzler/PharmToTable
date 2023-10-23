<?php

namespace OM4\WooCommerceZapier\TaskHistory\Listener;

use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\Exception\InvalidImplementationException;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Bookings\BookingsTaskCreator;
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
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Listener to detect when WooCommerce delivers data to Zapier via our Webhooks,
 * and record the event to our Task History.
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
		};

		if ( is_wp_error( $response ) ) {
			// Webhook delivery failed.
			return;
		}

		$resource_id   = \absint( $arg );
		$child_id      = 0;
		$resource_type = $webhook->get_resource();

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
		$task_creator->record( Event::trigger( $webhook->get_topic(), $topic_name ), $resource_id, $child_id, $webhook_id );
	}
}
