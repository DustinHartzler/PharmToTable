<?php
/**
 * Class for Affiliate For WooCommerce Admin Welcome page.
 *
 * @package   affiliate-for-woocommerce/includes/admin/
 * @since     1.0.0
 * @version   1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Docs' ) ) {

	/**
	 * Affiliate For WooCommerce Admin Docs
	 */
	class AFWC_Admin_Docs {

		/**
		 * Include About file.
		 */
		public static function afwc_docs() {
			global $wpdb;
			include 'about-affiliate-for-woocommerce.php';
		}
	}


}

return new AFWC_Admin_Docs();
