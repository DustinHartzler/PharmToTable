<?php
/**
 * Main class for WooCommerce Subscription Compatibility
 *
 * @since       1.0.0
 * @version     1.0.1
 *
 * @package     affiliate-for-woocommerce/includes/integration/woocommerce/compat/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WCS_AFWC_Compatibility' ) ) {

	/**
	 *  Compatibility class for WooCommerce Subscription 2.0+
	 */
	class WCS_AFWC_Compatibility {

		/**
		 * Variable to hold instance of WCS_AFWC_Compatibility
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		public function __construct() {

			add_filter( 'afwc_admin_settings', array( $this, 'add_settings' ) );
			add_filter( 'afwc_endpoint_account_settings_after_key', array( $this, 'endpoint_account_settings_after_key' ), 10, 2 );
		}

		/**
		 * Get single instance of WCS_AFWC_Compatibility
		 *
		 * @return WCS_AFWC_Compatibility Singleton object of WCS_AFWC_Compatibility
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Function to check WooCommerce Subscription version is greater than 2.0 or not
		 *
		 * @return boolean
		 */
		public static function is_wcs_gte_20() {
			return self::is_wcs_gte( '2.0.0' );
		}

		/**
		 * Function to do a version compare
		 *
		 * @param  string $version The version number.
		 * @return boolean
		 */
		public static function is_wcs_gte( $version = null ) {
			if ( null === $version ) {
				return false;
			}
			if ( ! class_exists( 'WC_Subscriptions' ) || empty( WC_Subscriptions::$version ) ) {
				return false;
			}
			return version_compare( WC_Subscriptions::$version, $version, '>=' );
		}

		/**
		 * Function to add subscription specific settings
		 *
		 * @param  array $settings Existing settings.
		 * @return array  $settings
		 */
		public function add_settings( $settings = array() ) {

			$wc_subscriptions_options = array(
				array(
					'name'          => __( 'Issue recurring commission?', 'affiliate-for-woocommerce' ),
					'desc'          => __( 'Enable this to give affiliate commissions for subscription recurring orders also', 'affiliate-for-woocommerce' ),
					'id'            => 'is_recurring_commission',
					'type'          => 'checkbox',
					'default'       => 'no',
					'checkboxgroup' => 'start',
					'autoload'      => false,
				),
			);

			array_splice( $settings, ( count( $settings ) - 1 ), 0, $wc_subscriptions_options );

			return $settings;

		}

		/**
		 * Return field key after which the setting should appear
		 *
		 * @param  string $after_key The field key.
		 * @param  array  $args      Additional arguments.
		 * @return string
		 */
		public function endpoint_account_settings_after_key( $after_key = '', $args = array() ) {
			return 'woocommerce_myaccount_subscription_payment_method_endpoint';
		}
	}

}

WCS_AFWC_Compatibility::get_instance();
