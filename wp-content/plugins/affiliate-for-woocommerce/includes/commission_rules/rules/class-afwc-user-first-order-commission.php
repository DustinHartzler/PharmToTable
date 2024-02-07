<?php
/**
 * Class for user first order commissions rules
 *
 * @package   affiliate-for-woocommerce/includes/commission_rules/rules/
 * @since     7.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_User_First_Order_Commission' ) ) {

	/**
	 * Class for user first order commission rules of Affiliate For WooCommerce
	 */
	class AFWC_User_First_Order_Commission extends AFWC_Rule_Boolean_Commission {

		/**
		 * Method to get current context key.
		 *
		 * @return string
		 */
		protected function get_context_key() {
			return 'user_first_order';
		}

		/**
		 * Method to get current category.
		 *
		 * @return string
		 */
		public function get_category() {
			return 'user';
		}

		/**
		 * Method to get rule title.
		 *
		 * @return string
		 */
		public function get_title() {
			return _x( 'First Order', 'Title for user first order commission rule', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to get placeholder for the rule.
		 *
		 * @return string
		 */
		public function get_placeholder() {
			return _x( 'Select yes/no', 'Placeholder for user first order commission rule', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to return possible operators.
		 *
		 * @return array $possible_operators
		 */
		public function get_possible_operators() {
			return $this->possible_operators;
		}

		/**
		 * Method to return possible options.
		 *
		 * @return array Return the pre-defined options for the rule.
		 */
		public function get_options() {
			return array(
				'yes' => _x( 'Yes', 'Positive value title for user first order commission rule', 'affiliate-for-woocommerce' ),
				'no'  => _x( 'No', 'Negative value title for user first order commission rule', 'affiliate-for-woocommerce' ),
			);
		}
	}
}
