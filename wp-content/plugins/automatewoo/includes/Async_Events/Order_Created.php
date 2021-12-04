<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Clean;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Class Order_Created
 *
 * @since 4.8.0
 */
class Order_Created extends Abstract_Async_Event {

	use UniqueEventsForRequestHelper;

	const MAYBE_ORDER_CREATED_HOOK = 'automatewoo/async/maybe_order_created';
	const ORDER_CREATED_META_KEY   = '_automatewoo_order_created';

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'woocommerce_new_order', [ $this, 'enqueue_maybe_order_created_async_event' ], 100 );
		add_action( 'transition_post_status', [ $this, 'handle_transition_post_status' ], 50, 3 );
		add_action( self::MAYBE_ORDER_CREATED_HOOK, [ $this, 'maybe_do_order_created_action' ] );
	}

	/**
	 * Handle post status transition.
	 *
	 * @param string  $new_status
	 * @param string  $old_status
	 * @param WP_Post $post
	 */
	public function handle_transition_post_status( string $new_status, string $old_status, WP_Post $post ) {
		if ( $post->post_type !== 'shop_order' ) {
			return;
		}

		$draft_statuses = aw_get_draft_post_statuses();

		// ensure that the old status IS a draft status and the new status IS NOT a draft status
		if ( in_array( $old_status, $draft_statuses, true ) && ! in_array( $new_status, $draft_statuses, true ) ) {
			$this->enqueue_maybe_order_created_async_event( $post->ID );
		}
	}

	/**
	 * An order was created.
	 *
	 * @param int|string $order_id
	 */
	public function enqueue_maybe_order_created_async_event( $order_id ) {
		$order_id = Clean::id( $order_id );
		if ( ! $order_id ) {
			return;
		}

		// Creating a draft order triggers woocommerce_new_order, but we don't want it to trigger this workflow.
		if ( in_array( get_post_status( $order_id ), aw_get_draft_post_statuses(), true ) ) {
			return;
		}
		// Due to the variety of order created hooks, protect against adding multiple events for the same order_id
		if ( $this->check_item_is_unique_for_event( $order_id ) ) {
			return;
		}

		$this->record_event_added_for_item( $order_id );

		// Enqueue the async action on shutdown to ensure the event doesn't happen before the order is fully created
		// (the 'transition_post_status' action happens quite early)
		$this->action_scheduler->enqueue_async_action_on_shutdown( self::MAYBE_ORDER_CREATED_HOOK, [ $order_id ] );
	}

	/**
	 * Handles async order created event.
	 *
	 * Prevents duplicate events from running with a meta check.
	 *
	 * @param int $order_id
	 */
	public function maybe_do_order_created_action( int $order_id ) {
		$order = wc_get_order( Clean::id( $order_id ) );
		if ( ! $order || $order->get_meta( self::ORDER_CREATED_META_KEY ) ) {
			return;
		}

		$order->update_meta_data( self::ORDER_CREATED_META_KEY, true );
		$order->save();

		// do real async order created action
		do_action( $this->get_hook_name(), $order_id );
	}

	/**
	 * @param string  $new_status
	 * @param string  $old_status
	 * @param WP_Post $post
	 *
	 * @deprecated use \AutomateWoo\Async_Events\Order_Created::handle_transition_post_status()
	 */
	public function transition_post_status( $new_status, $old_status, $post ) {
		wc_deprecated_function( __METHOD__, '5.2.0', '\AutomateWoo\Async_Events\Order_Created::handle_transition_post_status' );
		$this->handle_transition_post_status( $new_status, $old_status, $post );
	}

	/**
	 * @param int|string $order_id
	 *
	 * @deprecated use \AutomateWoo\Async_Events\Order_Created::enqueue_maybe_order_created_async_event()
	 */
	public function order_created( $order_id ) {
		wc_deprecated_function( __METHOD__, '5.2.0', '\AutomateWoo\Async_Events\Order_Created::enqueue_maybe_order_created_async_event' );
		$this->enqueue_maybe_order_created_async_event( $order_id );
	}

}
