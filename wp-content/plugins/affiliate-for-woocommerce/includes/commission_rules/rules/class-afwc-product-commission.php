<?php
/**
 * Class for product commissions rules
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/
 * @since       2.6.0
 * @version     1.2.1
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
		 * Method to filter the products IDs for comparison.
		 *
		 * @param array $product_ids The product IDs.
		 *
		 * @return array Return the product IDs.
		 */
		public function filter_values( $product_ids = array() ) {
			// Return if there is not any product IDs to filter.
			if ( empty( $product_ids ) ) {
				return array();
			}

			/*
			* The below function will return the variation IDs as well as the parent variable ID for the variable product type. It may give issues when introducing the '=' operator.
			*
			* @since 6.34.0
			*/
			return afwc_get_variable_variation_product_ids( $product_ids );
		}
	}
}
