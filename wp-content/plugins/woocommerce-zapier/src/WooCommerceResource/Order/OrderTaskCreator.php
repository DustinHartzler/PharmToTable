<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\WooCommerceResource\Order;

use OM4\WooCommerceZapier\TaskHistory\Task\CreatorBase;

defined( 'ABSPATH' ) || exit;

/**
 * Order Task Creator.
 *
 * @since 2.8.0
 */
class OrderTaskCreator extends CreatorBase {

	/**
	 * {@inheritDoc}
	 */
	public function get_resource_type() {
		return 'order';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_resource_name() {
		return __( 'Order', 'woocommerce-zapier' );
	}
}
