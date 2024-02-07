<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\WooCommerceResource\Coupon;

use OM4\WooCommerceZapier\TaskHistory\Task\CreatorBase;

defined( 'ABSPATH' ) || exit;

/**
 * Coupon Task Creator.
 *
 * @since 2.8.0
 */
class CouponTaskCreator extends CreatorBase {

	/**
	 * {@inheritDoc}
	 */
	public static function resource_type() {
		return 'coupon';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function resource_name() {
		return __( 'Coupon', 'woocommerce-zapier' );
	}
}
