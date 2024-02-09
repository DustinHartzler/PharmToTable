<?php
/**
 * WooCommerce Compatibility Class
 *
 * @package     WC-compat
 * @since       7.0.0
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SA_WC_Compatibility' ) ) {

	/**
	 * Class to check WooCommerce compatibility.
	 */
	class SA_WC_Compatibility {

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 6.4.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_64() {
			return self::is_wc_greater_than( '6.3.1' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 6.0.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_60() {
			return self::is_wc_greater_than( '5.9.1' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.9.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_39() {
			return self::is_wc_greater_than( '3.8.3' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.8.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_38() {
			return self::is_wc_greater_than( '3.7.3' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.7.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_37() {
			return self::is_wc_greater_than( '3.6.7' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.6.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_36() {
			return self::is_wc_greater_than( '3.5.10' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.5.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_35() {
			return self::is_wc_greater_than( '3.4.8' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.4.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_34() {
			return self::is_wc_greater_than( '3.3.6' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.3.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_33() {
			return self::is_wc_greater_than( '3.2.6' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.2.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_32() {
			return self::is_wc_greater_than( '3.1.2' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.1.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_31() {
			return self::is_wc_greater_than( '3.0.9' );
		}

		/**
		 * Method to check if WooCommerce is Greater Than And Equal To 3.0.0
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_30() {
			return self::is_wc_greater_than( '2.6.14' );
		}

		/**
		 * Method to check if WooCommerce version is greater than and equal to 2.6
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_26() {
			return self::is_wc_greater_than( '2.5.5' );
		}

		/**
		 * Method to check if WooCommerce version is greater than and equal To 2.5
		 *
		 * @return boolean
		 */
		public static function is_wc_gte_25() {
			return self::is_wc_greater_than( '2.4.13' );
		}

		/**
		 * WooCommerce Current WooCommerce Version.
		 *
		 * @return string woocommerce version
		 */
		public static function get_wc_version() {
			if ( defined( 'WC_VERSION' ) && WC_VERSION ) {
				return WC_VERSION;
			}
			if ( defined( 'WOOCOMMERCE_VERSION' ) && WOOCOMMERCE_VERSION ) {
				return WOOCOMMERCE_VERSION;
			}
			return null;
		}

		/**
		 * Compare passed version with woocommerce current version
		 *
		 * @param string $version Version to compare with.
		 *
		 * @return boolean
		 */
		public static function is_wc_greater_than( $version = '' ) {
			return ! empty( $version ) ? version_compare( self::get_wc_version(), $version, '>' ) : false;
		}
	}
}
