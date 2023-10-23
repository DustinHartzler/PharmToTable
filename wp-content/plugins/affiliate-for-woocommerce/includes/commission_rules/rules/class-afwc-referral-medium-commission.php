<?php
/**
 * Class for referral medium commissions rules
 *
 * @package   affiliate-for-woocommerce/includes/commission_rules/rules/
 * @since     6.12.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Referral_Medium_Commission' ) ) {

	/**
	 * Class for referral medium commission rules of Affiliate For WooCommerce
	 */
	class AFWC_Referral_Medium_Commission extends AFWC_Rule_String_Commission {

		/**
		 * Method to get current context key.
		 *
		 * @return string
		 */
		protected function get_context_key() {
			return 'referral_medium';
		}

		/**
		 * Method to get current category.
		 *
		 * @return string
		 */
		public function get_category() {
			return 'medium';
		}

		/**
		 * Method to get rule title.
		 *
		 * @return string
		 */
		public function get_title() {
			return _x( 'Referral Medium', 'Title for referral medium commission rule', 'affiliate-for-woocommerce' );
		}

		/**
		 * Method to get placeholder for the rule.
		 *
		 * @return string
		 */
		public function get_placeholder() {
			return _x( 'Select a medium', 'Placeholder for referral medium commission rule', 'affiliate-for-woocommerce' );
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
		 * Method to return possible options.
		 *
		 * @return array Return the pre-defined options for the rule.
		 */
		public function get_options() {
			return array(
				'link'   => _x( 'Link', 'Referral medium option name for link', 'affiliate-for-woocommerce' ),
				'coupon' => _x( 'Coupon', 'Referral medium option name for coupon', 'affiliate-for-woocommerce' ),
			);
		}

		/**
		 * Method to filter values for comparison.
		 *
		 * @param array $mediums The mediums to filter.
		 *
		 * @return array Return the mediums.
		 */
		public function filter_values( $mediums = array() ) {
			return array_filter( $mediums );
		}
	}

}
