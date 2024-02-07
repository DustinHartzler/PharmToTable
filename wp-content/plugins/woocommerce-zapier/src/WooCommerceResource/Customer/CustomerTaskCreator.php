<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\WooCommerceResource\Customer;

use OM4\WooCommerceZapier\TaskHistory\Task\CreatorBase;

defined( 'ABSPATH' ) || exit;

/**
 * Customer Task Creator.
 *
 * @since 2.8.0
 */
class CustomerTaskCreator extends CreatorBase {

	/**
	 * {@inheritDoc}
	 */
	public static function resource_type() {
		return 'customer';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function resource_name() {
		return __( 'Customer', 'woocommerce-zapier' );
	}
}
