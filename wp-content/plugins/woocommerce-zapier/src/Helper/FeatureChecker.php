<?php

namespace OM4\WooCommerceZapier\Helper;

use Automattic\WooCommerce\Internal\DataStores\Orders\DataSynchronizer;
use Automattic\WooCommerce\Utilities\OrderUtil;

defined( 'ABSPATH' ) || exit;

/**
 * Check Feature/plugin/class availability.
 *
 * @since 2.0.0
 */
class FeatureChecker {

	/**
	 * Check class is available
	 *
	 * @param string $class_name Name of the class to looking for. Preferably FQCN.
	 *
	 * @return boolean
	 */
	public function class_exists( $class_name ) {
		return \class_exists( $class_name );
	}

	/**
	 * Function is available
	 *
	 * @param string $function_name Name of the function to looking for.
	 *
	 * @return boolean
	 */
	public function function_exists( $function_name ) {
		return \function_exists( $function_name );
	}

	/**
	 * Check coupon is enabled
	 *
	 * @return boolean
	 */
	public function is_coupon_enabled() {
		return \wc_coupons_enabled();
	}

	/**
	 * Check if High-Performance Order Storage (HPOS) is enabled in WooCommerce,
	 * and HPOS is the authoritative source of order data.
	 *
	 * @since 2.7.0
	 *
	 * @return boolean
	 */
	public function is_hpos_enabled() {
		// OrderUtil class is only available in WooCommerce 6.9+.
		return class_exists( OrderUtil::class ) && OrderUtil::custom_orders_table_usage_is_enabled();
	}

	/**
	 * Check if High-Performance Order Storage (HPOS) synchronisation is enabled in WooCommerce,
	 *
	 * @since 2.7.0
	 *
	 * @return boolean
	 */
	public function is_hpos_in_sync() {
		// OrderUtil class is only available in WooCommerce 6.9+.
		return $this->is_hpos_enabled() && OrderUtil::is_custom_order_tables_in_sync();
	}

	/**
	 * Returns the HPOS default placeholder post type, or an empty string.
	 *
	 * @since 2.7.0
	 *
	 * @return string
	 */
	public function hpos_placeholder_order_post_type() {
		return defined( DataSynchronizer::class . '::PLACEHOLDER_ORDER_POST_TYPE' ) ?
			DataSynchronizer::PLACEHOLDER_ORDER_POST_TYPE :
			'';
	}
}
