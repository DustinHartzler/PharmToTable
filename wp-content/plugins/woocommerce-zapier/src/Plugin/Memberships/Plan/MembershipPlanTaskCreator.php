<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Memberships\Plan;

use OM4\WooCommerceZapier\TaskHistory\Task\CreatorBase;

defined( 'ABSPATH' ) || exit;

/**
 * Membership Plan Task Creator.
 *
 * @since 2.10.0
 */
class MembershipPlanTaskCreator extends CreatorBase {

	/**
	 * {@inheritDoc}
	 */
	public static function resource_type() {
		return 'membership_plan';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function resource_name() {
		return __( 'Membership Plan', 'woocommerce-zapier' );
	}
}
