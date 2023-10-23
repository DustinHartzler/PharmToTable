<?php
/**
 * Main class for Other Payout method (Could be via bank transfer or other method).
 *
 * @package    affiliate-for-woocommerce/includes/payouts/
 * @since      6.28.0
 * @version    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Other_Payout_Method' ) ) {

	/**
	 * Affiliate Other Payout method class.
	 */
	class AFWC_Other_Payout_Method {

		/**
		 * Main method to process payouts.
		 *
		 * @param array $params Params for executing the payout.
		 *
		 * @return array|WP_Error The response.
		 */
		public function execute_payout( $params = array() ) {
			$records = ! empty( $params['referrals'] ) ? $params['referrals'] : array();

			if ( empty( $records ) ) {
				return new WP_Error( 'afwc-other-payout-error', _x( 'Referral records are required for executing the payout', 'Other payout failed message', 'affiliate-for-woocommerce' ) );
			}

			// Return success without doing anything as this is only for record the data after any external action.
			return array(
				'success' => true,
				'amount'  => ! empty( $records['amount'] ) ? floatval( $records['amount'] ) : 0,
			);
		}
	}
}
