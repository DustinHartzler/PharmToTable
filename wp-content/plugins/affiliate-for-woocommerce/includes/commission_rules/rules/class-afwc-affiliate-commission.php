<?php
/**
 * Class for user commissions rules
 *
 * @package     affiliate-for-woocommerce/includes/commission_rules/
 * @since       2.5.0
 * @version     1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Affiliate_Commission' ) ) {

	/**
	 * Class for commission rules of Affiliate For WooCommerce
	 */
	class AFWC_Affiliate_Commission extends AFWC_Rule_Number_Commission {

		/**
		 * Method to get current context key.
		 *
		 * @return string
		 */
		protected function get_context_key() {
			return 'affiliate_id';
		}

		/**
		 * Method to get current category
		 *
		 * @return string
		 */
		public function get_category() {
			return 'affiliate';
		}

		/**
		 * Method to get rule title.
		 *
		 * @return string
		 */
		public function get_title() {
			return __( 'Affiliate', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to get placeholder for the rule.
		 *
		 * @return string
		 */
		public function get_placeholder() {
			return _x( 'Search for an affiliate', 'commission rule placeholder', 'affiliate-for-woocommerce' );
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
	}
}

