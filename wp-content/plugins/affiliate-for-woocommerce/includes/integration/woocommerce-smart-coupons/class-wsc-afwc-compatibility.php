<?php
/**
 * Main class for WooCommerce Smart Coupons Compatibility
 *
 * @package     affiliate-for-woocommerce/includes/integration/woocommerce-smart-coupons/
 * @since       4.12.0
 * @version     1.0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WSC_AFWC_Compatibility' ) ) {

	/**
	 * Compatibility class for WooCommerce Smart Coupons.
	 */
	class WSC_AFWC_Compatibility {

		/**
		 * Variable to hold instance of WSC_AFWC_Compatibility
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		private function __construct() {
			// Export headers.
			add_filter( 'wc_smart_coupons_export_headers', array( $this, 'export_headers' ) );
			add_filter( 'wc_sc_export_coupon_meta', array( $this, 'export_coupon_meta_data' ), 10, 2 );

			add_filter( 'smart_coupons_parser_postmeta_defaults', array( $this, 'postmeta_defaults' ) );

			// Bulk Generate.
			add_filter( 'sc_generate_coupon_meta', array( $this, 'generate_coupon_meta' ), 10, 2 );

			// Import.
			add_filter( 'wc_sc_process_coupon_meta_value_for_import', array( $this, 'process_coupon_meta_value_for_import' ), 10, 2 );
		}

		/**
		 * Get single instance of WSC_AFWC_Compatibility
		 *
		 * @return WSC_AFWC_Compatibility Singleton object of WSC_AFWC_Compatibility
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add meta in export headers.
		 *
		 * @param  array $headers Existing headers.
		 * @return array
		 */
		public function export_headers( $headers = array() ) {
			$headers['afwc_referral_coupon_of'] = _x( 'Assign to affiliate', 'Coupon meta name for assigning a coupon to an affiliate during export', 'affiliate-for-woocommerce' );

			return $headers;
		}

		/**
		 * Function to handle coupon meta data during export of existing coupons.
		 *
		 * @param  mixed $meta_value The meta value.
		 * @param  array $args       Additional arguments.
		 * @return string Processed meta value
		 */
		public function export_coupon_meta_data( $meta_value = '', $args = array() ) {
			if ( empty( $args['meta_key'] ) ) {
				return $meta_value;
			}

			if ( 'afwc_referral_coupon_of' !== $args['meta_key'] ) {
				return $meta_value;
			}

			if ( ! isset( $args['meta_value'] ) || empty( $args['meta_value'] ) ) {
				return $meta_value;
			}

			$affiliate_id = stripslashes( $args['meta_value'] );
			if ( empty( $affiliate_id ) ) {
				return $meta_value;
			}

			$meta_value = intval( $affiliate_id );

			return $meta_value;
		}

		/**
		 * Post meta defaults for referral coupon meta
		 *
		 * @param  array $defaults Existing postmeta defaults.
		 * @return array
		 */
		public function postmeta_defaults( $defaults = array() ) {
			$defaults['afwc_referral_coupon_of'] = '';

			return $defaults;
		}

		/**
		 * Add referral coupon meta with value - affiliate id - in coupon meta.
		 *
		 * @param  array $data The row data.
		 * @param  array $post The POST values.
		 * @return array Modified data
		 */
		public function generate_coupon_meta( $data = array(), $post = array() ) {
			if ( ! empty( $post['afwc_referral_coupon_of'] ) ) {
				$data['afwc_referral_coupon_of'] = ( ! empty( $post['afwc_referral_coupon_of'] ) ) ? intval( wc_clean( wp_unslash( $post['afwc_referral_coupon_of'] ) ) ) : '';
			}

			return $data;
		}

		/**
		 * Process coupon meta value for import.
		 *
		 * @param  mixed $meta_value The meta value.
		 * @param  array $args       Additional Arguments.
		 * @return mixed $meta_value
		 */
		public function process_coupon_meta_value_for_import( $meta_value = null, $args = array() ) {
			if ( empty( $args['meta_key'] ) ) {
				return $meta_value;
			}

			if ( 'afwc_referral_coupon_of' !== $args['meta_key'] ) {
				return $meta_value;
			}

			$meta_value = ( ! empty( $args['postmeta']['afwc_referral_coupon_of'] ) ) ? intval( wc_clean( wp_unslash( $args['postmeta']['afwc_referral_coupon_of'] ) ) ) : 0;
			if ( empty( $meta_value ) ) {
				return '';
			}

			// Check if user_id/meta_value have a valid affiliate status.
			$is_affiliate = afwc_is_user_affiliate( $meta_value );
			if ( 'not_registered' === $is_affiliate ) {
				// Passing empty instead of 0 to not fill meta_value during import.
				return '';
			}

			return $meta_value;
		}

	}
}

WSC_AFWC_Compatibility::get_instance();
