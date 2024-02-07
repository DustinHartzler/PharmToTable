<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\WooCommerceResource\Product;

use OM4\WooCommerceZapier\TaskHistory\Task\CreatorBase;

defined( 'ABSPATH' ) || exit;

/**
 * Product Task Creator.
 *
 * @since 2.8.0
 */
class ProductTaskCreator extends CreatorBase {

	/**
	 * {@inheritDoc}
	 */
	public static function resource_type() {
		return 'product';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function resource_name() {
		return __( 'Product', 'woocommerce-zapier' );
	}
	/**
	 * {@inheritDoc}
	 */
	public static function child_type() {
		return 'product_variation';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function child_name() {
		return __( 'Product Variation', 'woocommerce-zapier' );
	}
}
