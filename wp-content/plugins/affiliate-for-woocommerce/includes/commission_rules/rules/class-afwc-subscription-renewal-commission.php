<?php
/**
 * Class for subscriptions renewal commissions rules
 *
 * @package   affiliate-for-woocommerce/includes/commission_rules/rules/
 * @since     7.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Subscription_Renewal_Commission' ) ) {

	/**
	 * Class for renewal subscriptions commission rules of Affiliate For WooCommerce
	 */
	class AFWC_Subscription_Renewal_Commission extends AFWC_Rule_Number_Commission {

		/**
		 * Method to get current context key.
		 *
		 * @return string
		 */
		protected function get_context_key() {
			return 'subscription_renewal';
		}

		/**
		 * Method to get current category.
		 *
		 * @return string
		 */
		public function get_category() {
			return 'subscription';
		}

		/**
		 * Method to get rule title.
		 *
		 * @return string
		 */
		public function get_title() {
			return _x( 'Renewal', 'Title for renewal subscription commission rule', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to get placeholder for the rule.
		 *
		 * @return string
		 */
		public function get_placeholder() {
			return _x( 'Set renewal order number', 'Placeholder for renewal subscription commission rule', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to return possible operators.
		 *
		 * @return array Return the possible operators.
		 */
		public function get_possible_operators() {
			// Exclude the operators for this rule.
			$this->exclude_operators( array( 'in', 'nin', 'eq', 'neq' ) );

			// Re-merge the eq and neq operator to change the operator label for this rule.
			return array_merge(
				$this->possible_operators,
				array(
					array(
						'op'    => 'eq',
						'label' => _x( '=', 'Label for equal to operator of renewal subscription rule', 'affiliate-for-woocommerce' ),
						'type'  => 'single',
					),
					array(
						'op'    => 'neq',
						'label' => _x( '!=', 'Label for not equal to operator of renewal subscription rule', 'affiliate-for-woocommerce' ),
						'type'  => 'single',
					),
				)
			);
		}
	}

}
