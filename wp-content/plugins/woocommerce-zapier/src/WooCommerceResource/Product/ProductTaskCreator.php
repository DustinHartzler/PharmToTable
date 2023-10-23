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
	public function get_resource_type() {
		return 'product';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_resource_name() {
		return __( 'Product', 'woocommerce-zapier' );
	}
	/**
	 * {@inheritDoc}
	 */
	public function get_child_type() {
		return 'product_variation';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_child_name() {
		return __( 'Product Variation', 'woocommerce-zapier' );
	}
}
