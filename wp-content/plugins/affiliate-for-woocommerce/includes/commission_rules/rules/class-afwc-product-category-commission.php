<?php
/**
 * Class for product category commissions rules
 *
 * @since       2.7.1
 * @version     1.0.0
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Product_Category_Commission' ) ) {

	/**
	 * Class for commission rules of Affiliate For WooCommerce
	 */
	class AFWC_Product_Category_Commission extends AFWC_Rule_Number_Commission {

		/**
		 * Function to get current context key
		 * string
		 */
		protected function get_context_key() {
			return 'product_category';
		}

		/**
		 * Function to get current category
		 *
		 * @return string
		 */
		public function get_category() {
			return 'product';
		}

		/**
		 * Function to return possible operator
		 *
		 * @return $possible_operators
		 */
		public function get_possible_operators() {
			$list = array( 'gt', 'gte', 'lt', 'eq', 'lte', 'neq' );
			$this->exclude_operators( $list );
			return $this->possible_operators;
		}
	}
}

