<?php
/**
 * Main class for Admin bar Menu.
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       6.36.0
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Bar_Menu' ) ) {

	/**
	 * Admin bar menu class.
	 */
	class AFWC_Admin_Bar_Menu {

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Admin_Bar_Menu Singleton object of this class
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), 99 );
		}

		/**
		 * Method to register the Affiliates dashboard link in WordPress admin menu bar.
		 *
		 * @param WP_Admin_Bar $wp_admin_bar The instance of WP_Admin_Bar.
		 *
		 * @return void.
		 */
		public function add_admin_bar_menu( $wp_admin_bar = null ) {

			if ( empty( $wp_admin_bar ) || ! $wp_admin_bar instanceof WP_Admin_Bar || ! is_callable( array( $wp_admin_bar, 'add_node' ) ) || ! afwc_current_user_can_manage_affiliate() ) {
				return;
			}

			$wp_admin_bar->add_node(
				array(
					'id'    => 'afwc-admin-bar-button',
					'title' => sprintf(
						/* translators: Affiliate For WooCommerce icon */
						_x(
							'%s Affiliates',
							'label in the admin bar',
							'affiliate-for-woocommerce'
						),
						'<span class="ab-icon">
						    <img style="padding:auto 2px;" class="ab-icon" src="' . AFWC_PLUGIN_URL . '/assets/images/afwc-admin-bar-icon-17.svg" />
						</span>'
					),
					'href'  => admin_url( 'admin.php?page=affiliate-for-woocommerce' ),
					'meta'  => array(
						'title' => esc_html_x(
							'Access affiliates dashboard',
							'Meta title of the admin bar',
							'affiliate-for-woocommerce'
						),
					),
				)
			);
		}
	}
}

return AFWC_Admin_Bar_Menu::get_instance();
