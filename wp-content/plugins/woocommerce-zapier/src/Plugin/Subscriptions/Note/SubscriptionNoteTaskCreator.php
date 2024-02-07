<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Subscriptions\Note;

use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionsTaskCreator;

defined( 'ABSPATH' ) || exit;

/**
 * Subscription Note Task Creator.
 *
 * @since 2.9.0
 */
class SubscriptionNoteTaskCreator extends SubscriptionsTaskCreator {

	/**
	 * {@inheritDoc}
	 */
	public static function child_type() {
		return 'subscription_note';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function child_name() {
		return __( 'Subscription Note', 'woocommerce-zapier' );
	}
}
