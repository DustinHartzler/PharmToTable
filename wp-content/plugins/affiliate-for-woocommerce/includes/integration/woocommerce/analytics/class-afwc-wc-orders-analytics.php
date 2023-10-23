<?php
/**
 * Main class for WooCommerce Orders Analytics integration
 *
 * @package   affiliate-for-woocommerce/includes/integration/woocommerce/analytics/
 * @since     6.20.0
 * @version   1.0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_WC_Orders_Analytics' ) ) {

	/**
	 * Affiliate For WooCommerce integration with WooCommerce Orders Analytics.
	 */
	class AFWC_WC_Orders_Analytics {

		/**
		 * Variable to hold instance of AFWC_WC_Orders_Analytics
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of AFWC_WC_Orders_Analytics.
		 *
		 * @return AFWC_WC_Orders_Analytics Singleton object of AFWC_WC_Orders_Analytics.
		 */
		public static function get_instance() {
			// Check if instance already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			add_filter( 'woocommerce_analytics_orders_select_query', array( $this, 'analytics_orders_result' ) );
			add_filter( 'woocommerce_report_orders_export_columns', array( $this, 'add_column_header_in_export' ) );
			add_filter( 'woocommerce_report_orders_prepare_export_item', array( $this, 'map_affiliate_details_in_export' ), 10, 2 );

			// Add necessary scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_analytics_scripts' ) );
		}

		/**
		 * Method to append the affiliate details in WooCommerce Order Analytics data.
		 *
		 * @param  array $results The array of results to render the WooCommerce order Analytics table.
		 *
		 * @return array          Modified array of results.
		 */
		public function analytics_orders_result( $results = array() ) {
			if ( empty( $results ) || empty( $results->data ) || ! is_array( $results->data ) ) {
				return $results;
			}

			foreach ( $results->data as $key => $result ) {
				if ( empty( $result['order_id'] ) ) {
					continue;
				}

				$order_id = intval( $result['order_id'] );

				$order                  = wc_get_order( $order_id );
				$is_commission_recorded = is_callable( array( $order, 'get_meta' ) ) ? $order->get_meta( 'is_commission_recorded', true ) : 'no';

				if ( 'yes' !== $is_commission_recorded ) {
					continue;
				}

				$affiliate_details = $this->arrange_affiliate_details_for_report( $order_id );

				$results->data[ $key ]['affiliate'] = ! empty( $affiliate_details['affiliate'] ) ? $affiliate_details['affiliate'] : '';
			}

			return $results;
		}

		/**
		 * Add column header in export.
		 *
		 * @param  array $export_columns Array of export columns.
		 * @return array                 Modified array of export columns.
		 */
		public function add_column_header_in_export( $export_columns = array() ) {
			$export_columns['affiliate'] = _x( 'Affiliate', 'Column header for affiliate in WooCommerce order report', 'affiliate-for-woocommerce' );
			return $export_columns;
		}

		/**
		 * Map affiliate details in export item.
		 *
		 * @param  array $export_item Export item array.
		 * @param  array $item        Item array.
		 * @return array              Modified export item array.
		 */
		public function map_affiliate_details_in_export( $export_item = array(), $item = array() ) {
			$export_item['affiliate'] = ! empty( $item['affiliate'] ) ? $item['affiliate'] : '';
			return $export_item;
		}

		/**
		 * Register required scripts.
		 */
		public function admin_analytics_scripts() {
			// Only add scripts if we are on WC Analytics page.
			$current_screen    = is_callable( 'get_current_screen' ) ? get_current_screen() : null;
			$current_screen_id = ( ! empty( $current_screen ) && $current_screen instanceof WP_Screen && ! empty( $current_screen->id ) ) ? $current_screen->id : '';

			if ( empty( $current_screen_id ) ) {
				return;
			}

			if ( 'woocommerce_page_wc-admin' !== $current_screen_id ) {
				return;
			}

			// Proceed only if we are on correct menu in WC Analytics.
			$get_page = ( ! empty( $_GET['page'] ) ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore
			$get_path = ( ! empty( $_GET['path'] ) ) ? wc_clean( wp_unslash( $_GET['path'] ) ) : ''; // phpcs:ignore
			if ( empty( $get_page ) || empty( $get_path ) ) {
				return;
			}
			if ( 'wc-admin' !== $get_page && ( '/analytics/orders' !== $get_path || strpos( $get_path, 'orders' ) === false ) ) {
				return;
			}

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_register_script( 'afwc-wc-analytics-extends', AFWC_PLUGIN_URL . '/assets/js/wc-analytics/afwc-wc-analytics-extends.js', array( 'wp-i18n' ), $plugin_data['Version'], true );
			wp_enqueue_script( 'afwc-wc-analytics-extends' );
		}

		/**
		 * Arrange affiliate details for the report.
		 *
		 * @param  int $order_id Order ID.
		 * @return array           Array of affiliate details.
		 */
		public function arrange_affiliate_details_for_report( $order_id = 0 ) {
			if ( empty( $order_id ) ) {
				return array();
			}

			$afwc_api      = AFWC_API::get_instance();
			$referral_data = is_callable( array( $afwc_api, 'get_affiliate_by_order' ) )
				? $afwc_api->get_affiliate_by_order( $order_id )
				: array();

			if ( empty( $referral_data ) || ! is_array( $referral_data ) || empty( $referral_data['affiliate_id'] ) ) {
				return array();
			}

			$affiliate_id = ! empty( $referral_data['affiliate_id'] ) ? intval( $referral_data['affiliate_id'] ) : 0;

			if ( empty( $affiliate_id ) ) {
				return array();
			}

			$user_string = '';
			$affiliate   = new AFWC_Affiliate( $affiliate_id );

			if ( is_object( $affiliate ) && $affiliate instanceof AFWC_Affiliate && ! empty( $affiliate->display_name ) ) {
				$user_string = sprintf(
					/* translators: 1: user display name 2: */
					esc_html__( '%1$s (#%2$s)', 'affiliate-for-woocommerce' ),
					$affiliate->display_name,
					absint( $affiliate_id )
				);
			}

			return array(
				'affiliate' => $user_string,
			);
		}
	}
}

AFWC_WC_Orders_Analytics::get_instance();
