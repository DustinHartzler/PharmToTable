<?php
/**
 * Main class for Affiliate For WooCommerce Admin Docs
 *
 * @since       1.0.0
 * @version     1.0.1
 *
 * @package     affiliate-for-woocommerce/includes/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Affiliate' ) ) {

	/**
	 * Class to handle affiliate
	 */
	class AFWC_Affiliate extends WP_User {

		/**
		 * Checks if an affiliate id is from a currently valid affiliate.
		 *
		 * @return returns the affiliate id if valid, otherwise FALSE
		 */
		public function is_valid() {
			$is_affiliate = 'no';
			$is_affiliate = afwc_is_user_affiliate( $this );
			$is_valid     = ( 'yes' === $is_affiliate ) ? true : false;
			return $is_valid;
		}
	}
}
