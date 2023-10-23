<?php
/**
 * Class for product commissions rules
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/
 * @since       2.6.0
 * @version     1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Product_Commission' ) ) {

	/**
	 * Class for commission rules of Affiliate For WooCommerce
	 */
	class AFWC_Product_Commission extends AFWC_Rule_Number_Commission {

		/**
		 * Method to get current context key.
		 *
		 * @return string
		 */
		protected function get_context_key() {
			return 'product_id';
		}

		/**
		 * Method to get current category.
		 *
		 * @return string
		 */
		public function get_category() {
			return 'product';
		}

		/**
		 * Method to get rule title.
		 *
		 * @return string
		 */
		public function get_title() {
			return __( 'Product', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to get placeholder for the rule.
		 *
		 * @return string
		 */
		public function get_placeholder() {
			return _x( 'Search for a product', 'commission rule placeholder', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to return possible operators.
		 *
		 * @return array $possible_operators
		 */
		public function get_possible_operators() {
			$list = array( 'gt', 'gte', 'lt', 'eq', 'lte', 'neq' );
			$this->exclude_operators( $list );
			return $this->possible_operators;
		}

		/**
		 * Method to filter the products ids for comparison.
		 *
		 * @param array $product_ids The product Ids.
		 *
		 * @return array Return the product ids.
		 */
		public function filter_values( $product_ids = array() ) {
			// Return if there is not any product ids to filter.
			if ( empty( $product_ids ) ) {
				return array();
			}

			$ids = array();

			foreach ( $product_ids as $product_id ) {

				$product = wc_get_product( intval( $product_id ) );

				// Continue the loop, if the instance is not a WooCommerce product.
				if ( ! $product instanceof WC_Product ) {
					continue;
				}

				if ( is_callable( array( $product, 'is_type' ) ) && $product->is_type( 'variable' ) ) {
					// Push variation Ids if the product type is variable.
					$ids = array_merge(
						$ids,
						is_callable( array( $product, 'get_children' ) ) ? $product->get_children() : array()
					);
				} else {
					// Push the product id.
					$ids[] = intval( $product_id );
				}
			}

			return array_unique( $ids );
		}
	}
}
