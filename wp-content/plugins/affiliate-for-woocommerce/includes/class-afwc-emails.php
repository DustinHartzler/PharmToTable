<?php
/**
 * Main class for Affiliate Emails functionality
 *
 * @package     affiliate-for-woocommerce/includes/
 * @version     1.0.1
 * @since       2.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Emails' ) ) {

	/**
	 * Main class for Affiliate Emails functionality
	 */
	class AFWC_Emails {

		/**
		 * Variable to hold instance of AFWC_Emails
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Emails Singleton object of this class
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			// Filter to register Affiliates email classes.
			add_filter( 'woocommerce_email_classes', array( $this, 'register_email_classes' ) );

		}

		/**
		 * Register Affiliates email classes to WooCommerce's emails class list
		 *
		 * @param array $email_classes available email classes list.
		 * @return array $email_classes modified email classes list
		 */
		public function register_email_classes( $email_classes = array() ) {

			$afwc_email_classes = glob( AFWC_PLUGIN_DIRPATH . '/includes/emails/*.php' );

			foreach ( $afwc_email_classes as $email_class ) {
				if ( is_file( $email_class ) ) {
					include_once $email_class;
					$classes = get_declared_classes();
					$class   = end( $classes );
					// Add the email class to the list of email classes that WooCommerce loads.
					$email_classes[ $class ] = new $class();
				}
			}

			return $email_classes;

		}

		/**
		 * Function to get template base directory for Smart Coupons' email templates
		 *
		 * @param  string $template_name Template name.
		 * @return string $template_base_dir Base directory for Smart Coupons' email templates.
		 */
		public function get_template_base_dir( $template_name = '' ) {

			$template_base_dir = '';
			$plugin_base_dir   = substr( plugin_basename( AFWC_PLUGIN_FILE ), 0, strpos( plugin_basename( AFWC_PLUGIN_FILE ), '/' ) + 1 );
			$afwc_base_dir     = 'woocommerce/' . $plugin_base_dir;

			// First locate the template in woocommerce/affiliate-for-woocommerce folder of active theme.
			$template = locate_template(
				array(
					$afwc_base_dir . $template_name,
				)
			);

			if ( ! empty( $template ) ) {
				$template_base_dir = $afwc_base_dir;
			} else {
				// If not found then locate the template in affiliate-for-woocommerce folder of active theme.
				$template = locate_template(
					array(
						$plugin_base_dir . $template_name,
					)
				);

				if ( ! empty( $template ) ) {
					$template_base_dir = $plugin_base_dir;
				}
			}

			$template_base_dir = apply_filters( 'afwc_template_base_dir', $template_base_dir, $template_name );

			return $template_base_dir;
		}

	}

}

return new AFWC_Emails();
