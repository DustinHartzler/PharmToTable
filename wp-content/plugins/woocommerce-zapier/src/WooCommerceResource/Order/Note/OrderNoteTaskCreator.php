<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\WooCommerceResource\Order\Note;

use OM4\WooCommerceZapier\WooCommerceResource\Order\OrderTaskCreator;

defined( 'ABSPATH' ) || exit;

/**
 * Order Note Task Creator.
 *
 * @since 2.8.0
 */
class OrderNoteTaskCreator extends OrderTaskCreator {

	/**
	 * {@inheritDoc}
	 */
	public function get_child_type() {
		return 'order_note';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_child_name() {
		return __( 'Order Note', 'woocommerce-zapier' );
	}
}
