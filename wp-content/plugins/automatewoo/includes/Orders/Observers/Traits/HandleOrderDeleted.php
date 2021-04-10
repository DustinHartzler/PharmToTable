<?php

namespace AutomateWoo\Orders\Observers\Traits;

use WC_Order;

/**
 * Trait HandleOrderDeleted
 *
 * @since 5.2.0
 */
trait HandleOrderDeleted {

	/**
	 * Handle before order is deleted or trashed.
	 *
	 * @param WC_Order $order
	 */
	abstract protected function handle_order_deleted( WC_Order $order );

	/**
	 * Add hooks.
	 */
	protected function add_handle_order_deleted_hooks() {
		add_action( 'delete_post', [ $this, 'handle_post_trashed_or_deleted' ] );
		add_action( 'wp_trash_post', [ $this, 'handle_post_trashed_or_deleted' ] );
	}

	/**
	 * Handle initial post trash and deletion.
	 *
	 * @param int $post_id
	 */
	public function handle_post_trashed_or_deleted( int $post_id ) {
		if ( 'shop_order' !== get_post_type( $post_id ) ) {
			return;
		}

		$order = wc_get_order( $post_id );
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$this->handle_order_deleted( $order );
	}

}
