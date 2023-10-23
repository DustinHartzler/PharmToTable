<?php
/**
 * Affiliate For WooCommerce Admin Notifications
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       1.3.4
 * @version     1.0.10
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Notifications' ) ) {

	/**
	 * Class for handling admin notifications of Affiliate For WooCommerce
	 */
	class AFWC_Admin_Notifications {

		/**
		 * Variable to hold instance of AFWC_Admin_Notifications
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		private function __construct() {

			// Filter to add Settings link on Plugins page.
			add_filter( 'plugin_action_links_' . plugin_basename( AFWC_PLUGIN_FILE ), array( $this, 'plugin_action_links' ) );

			// To update footer text on AFW screens.
			add_filter( 'admin_footer_text', array( $this, 'afwc_footer_text' ) );
			add_filter( 'update_footer', array( $this, 'afwc_update_footer_text' ), 99 );

			// To show admin notifications.
			add_action( 'admin_init', array( $this, 'afw_dismiss_admin_notice' ) );
		}

		/**
		 * Get single instance of AFWC_Admin_Notifications
		 *
		 * @return AFWC_Admin_Notifications Singleton object of AFWC_Admin_Notifications
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Function to add more action on plugins page
		 *
		 * @param array $links Existing links.
		 * @return array $links
		 */
		public function plugin_action_links( $links ) {

			$settings_link = add_query_arg(
				array(
					'page' => 'wc-settings',
					'tab'  => 'affiliate-for-woocommerce-settings',
				),
				admin_url( 'admin.php' )
			);

			$getting_started_link = add_query_arg( array( 'page' => 'affiliate-for-woocommerce-documentation' ), admin_url( 'admin.php' ) );

			$action_links = array(
				'getting-started' => '<a href="' . esc_url( $getting_started_link ) . '">' . esc_html( __( 'Getting started', 'affiliate-for-woocommerce' ) ) . '</a>',
				'settings'        => '<a href="' . esc_url( $settings_link ) . '">' . esc_html( __( 'Settings', 'affiliate-for-woocommerce' ) ) . '</a>',
				'docs'            => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/document/affiliate-for-woocommerce/' ) . '">' . __( 'Docs', 'affiliate-for-woocommerce' ) . '</a>',
				'support'         => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/my-account/create-a-ticket/' ) . '">' . __( 'Support', 'affiliate-for-woocommerce' ) . '</a>',
				'review'          => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/products/affiliate-for-woocommerce/#reviews' ) . '">' . __( 'Review', 'affiliate-for-woocommerce' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Function to ask to review the plugin in footer
		 *
		 * @param  string $afw_rating_text Text in footer (left).
		 * @return string $afw_rating_text
		 */
		public function afwc_footer_text( $afw_rating_text ) {

			global $pagenow;

			if ( empty( $pagenow ) ) {
				return $afw_rating_text;
			}

			$get_page  = ( ! empty( $_GET['page'] ) ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore
			$get_tab   = ( ! empty( $_GET['tab'] ) ) ? wc_clean( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore
			$afw_pages = array( 'affiliate-for-woocommerce-documentation', 'affiliate-for-woocommerce' );

			if ( in_array( $get_page, $afw_pages, true ) || 'affiliate-for-woocommerce-settings' === $get_tab ) {
				?>
				<style type="text/css">
					#wpfooter {
						display: block !important;
					}
				</style>
				<?php
				/* translators: %1$s: Opening strong tag for plugin title %2$s: Closing strong tag for plugin title %3$s: link to review Affiliate For WooCommerce */
				$afw_rating_text = wp_kses_post( sprintf( _x( 'If you like %1$sAffiliate For WooCommerce%2$s, please give us %3$s. A huge thanks from WooCommerce & StoreApps in advance!', 'text for review request', 'affiliate-for-woocommerce' ), '<strong>', '</strong>', '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/products/affiliate-for-woocommerce/?review' ) . '" style="color: #5850EC;">5-star rating</a>' ) );
			}

			return $afw_rating_text;
		}

		/**
		 * Function to ask to leave an idea on WC ideaboard
		 *
		 * @param  string $afw_text Text in footer (right).
		 * @return string $afw_text
		 */
		public function afwc_update_footer_text( $afw_text ) {

			global $pagenow;

			if ( empty( $pagenow ) ) {
				return $afw_text;
			}

			$get_page  = ( ! empty( $_GET['page'] ) ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore
			$get_tab   = ( ! empty( $_GET['tab'] ) ) ? wc_clean( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore
			$afw_pages = array( 'affiliate-for-woocommerce-documentation', 'affiliate-for-woocommerce' );

			if ( in_array( $get_page, $afw_pages, true ) || 'affiliate-for-woocommerce-settings' === $get_tab ) {
				?>
				<style type="text/css">
					#wpfooter {
						display: block !important;
					}
				</style>
				<?php
				$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
				/* translators: %1$s: Plugin version number %2$s: link to submit idea for Affiliate For WooCommerce on WooCommerce idea board */
				$afw_text = sprintf( _x( 'v%1$s | Suggest a feature request or an enhancement from  %2$s.', 'text for feature request submission on WooCommerce idea board', 'affiliate-for-woocommerce' ), $plugin_data['Version'], '<a href="' . esc_url( 'https://woocommerce.com/feature-requests/affiliate-for-woocommerce/' ) . '" target="_blank" style="color: #5850EC;">here</a>' );
			}

			return $afw_text;
		}

		/**
		 * Function to dismiss any admin notice
		 */
		public function afw_dismiss_admin_notice() {

			$afw_dismiss_admin_notice = ( ! empty( $_GET['afw_dismiss_admin_notice'] ) ) ? wc_clean( wp_unslash( $_GET['afw_dismiss_admin_notice'] ) ) : ''; // phpcs:ignore
			$afw_option_name          = ( ! empty( $_GET['option_name'] ) ) ? wc_clean( wp_unslash( $_GET['option_name'] ) ) : ''; // phpcs:ignore

			if ( ! empty( $afw_dismiss_admin_notice ) && '1' === $afw_dismiss_admin_notice && ! empty( $afw_option_name ) ) {
				update_option( $afw_option_name . '_affiliate_wc', 'no', 'no' );
				$referer = wp_get_referer();
				wp_safe_redirect( $referer );
				exit();
			}
		}
	}

}

AFWC_Admin_Notifications::get_instance();
