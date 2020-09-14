<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Clean;
use AutomateWoo\Events;

defined( 'ABSPATH' ) || exit;

/**
 * Class Order_Created
 *
 * @since 4.8.0
 * @package AutomateWoo
 */
class Order_Created extends Abstract_Async_Event {

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'woocommerce_new_order', [ $this, 'order_created' ], 100 );
		add_action( 'woocommerce_api_create_order', [ $this, 'order_created' ], 100 );
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'order_created' ], 100 );
		add_filter( 'wcs_renewal_order_created', [ $this, 'filter_renewal_orders' ], 100 );

		if ( is_admin() ) {
			add_action( 'transition_post_status', [ $this, 'transition_post_status' ], 50, 3 );
		}

		add_action( 'automatewoo/async/maybe_order_created', [ $this, 'maybe_do_order_created_action' ] );
	}

	/**
	 * Hook into subscription renewal order created filter.
	 *
	 * @param \WC_Order $order
	 *
	 * @return \WC_Order
	 */
	public function filter_renewal_orders( $order ) {
		$this->order_created( $order->get_id() );
		return $order;
	}

	/**
	 * Handle post status transition.
	 *
	 * @param string   $new_status
	 * @param string   $old_status
	 * @param \WP_Post $post
	 */
	public function transition_post_status( $new_status, $old_status, $post ) {
		if ( $post->post_type !== 'shop_order' ) {
			return;
		}

		$draft_statuses = aw_get_draft_post_statuses();

		// because WP has multiple draft status, ensure that the old status IS a draft status and
		// the new status IS NOT a draft status
		if ( in_array( $old_status, $draft_statuses, true ) && ! in_array( $new_status, $draft_statuses, true ) ) {
			$this->order_created( $post->ID );
		}
	}

	/**
	 * An order was created.
	 *
	 * @param int $order_id
	 */
	public function order_created( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		// note this event could be scheduled multiple times which is ok
		// because before the event runs a check happens to prevent multiple runs
		// we do check this async rather than now to avoid plugin conflicts
		Events::schedule_async_event( 'automatewoo/async/maybe_order_created', [ (int) $order_id ], true );
	}

	/**
	 * Handles async order created event.
	 *
	 * Prevents duplicate events from running with a meta check.
	 *
	 * @param int $order_id
	 */
	public function maybe_do_order_created_action( $order_id ) {
		$order = wc_get_order( Clean::id( $order_id ) );

		if ( ! $order || $order->get_meta( '_automatewoo_order_created' ) ) {
			return;
		}

		$order->update_meta_data( '_automatewoo_order_created', true );
		$order->save();

		// do real async order created action
		do_action( 'automatewoo/async/order_created', $order_id );
	}

}
