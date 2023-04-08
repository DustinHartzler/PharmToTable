<?php
/**
 * Main class for Affiliate Details.
 *
 * @since       1.0.0
 * @version     1.2.1
 *
 * @package     affiliate-for-woocommerce/includes/
 */

// Exit if accessed directly.
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
		 * @return bool Return true if valid, otherwise false.
		 */
		public function is_valid() {
			return 'yes' === afwc_is_user_affiliate( $this );
		}

		/**
		 * Get the Linked customers for Lifetime commissions.
		 *
		 * @return array Array of linked customers.
		 */
		public function get_ltc_customers() {

			if ( empty( $this->ID ) ) {
				return array();
			}

			$customers = get_user_meta( $this->ID, 'afwc_ltc_customers', true );
			return ! empty( $customers ) ? array_filter( explode( ',', $customers ) ) : array();
		}

		/**
		 * Link the customer to the affiliate for Lifetime commissions.
		 *
		 * @param string|int $customer The customer email address or customer's user ID.
		 *
		 * @return bool Whether the customer is updated or not.
		 */
		public function add_ltc_customer( $customer = '' ) {
			if ( empty( $customer ) || empty( $this->ID ) ) {
				return false;
			}

			if ( ! $this->is_ltc_enabled() || afwc_get_ltc_affiliate_by_customer( $customer ) ) {
				return false;
			}

			$ltc_customers = $this->get_ltc_customers();

			$ltc_customers = ! empty( $ltc_customers ) ? $ltc_customers : array();

			$ltc_customers[] = $customer;

			return true === update_user_meta( $this->ID, 'afwc_ltc_customers', implode( ',', array_filter( $ltc_customers ) ) );
		}

		/**
		 * Unlink the customer from the Lifetime commission linked list.
		 *
		 * @param string|int $customer The customer email address or customer's user ID.
		 *
		 * @return bool Return true if successfully removed otherwise false.
		 */
		public function remove_ltc_customer( $customer = '' ) {

			if ( empty( $customer ) || empty( $this->ID ) ) {
				return false;
			}

			$ltc_customers = $this->get_ltc_customers();

			if ( empty( $ltc_customers ) || ! is_array( $ltc_customers ) ) {
				return false;
			}

			$key = array_search( $customer, $ltc_customers, true );

			if ( false !== $key ) {
				unset( $ltc_customers[ $key ] );
			}

			$value = ! empty( $ltc_customers ) ? ( implode( ',', array_filter( $ltc_customers ) ) ) : '';

			return true === update_user_meta( $this->ID, 'afwc_ltc_customers', $value );
		}


		/**
		 * Check whether Lifetime commission feature is enabled of the affiliate.
		 *
		 * @return bool Return true if enabled otherwise false.
		 */
		public function is_ltc_enabled() {

			if ( empty( $this->ID ) ) {
				return false;
			}

			if ( 'no' === get_option( 'afwc_enable_lifetime_commissions', 'no' ) ) {
				return false;
			}

			$ltc_excluded_affiliates = get_option( 'afwc_lifetime_commissions_excludes', array() );

			// Check whether the affiliate id is selected for the lifetime commission exclude list.
			if ( ! empty( $ltc_excluded_affiliates['affiliates'] ) && in_array( intval( $this->ID ), $ltc_excluded_affiliates['affiliates'], true ) ) {
				return false;
			}

			// Check whether the affiliate tag is selected for the lifetime commission exclude list.
			if ( ! empty( $ltc_excluded_affiliates['tags'] ) ) {
				$tags = $this->get_tags();
				if ( ! empty( $tags ) && count( array_intersect( array_keys( $tags ), $ltc_excluded_affiliates['tags'] ) ) > 0 ) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Get the assigned tags to the affiliate.
		 *
		 * @return array Array of tags having Id as key and tag name as value.
		 */
		public function get_tags() {

			if ( empty( $this->ID ) ) {
				return array();
			}

			$tags = wp_get_object_terms( $this->ID, 'afwc_user_tags', array( 'fields' => 'id=>name' ) );
			return ! empty( $tags ) && ! is_wp_error( $tags ) ? $tags : array();
		}

	}
}
