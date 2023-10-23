<?php
/**
 * Main class for Affiliate Emails functionality
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       2.3.0
 * @version     1.1.2
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

			// Filter to register email classes from this plugin.
			add_filter( 'woocommerce_email_classes', array( $this, 'register_email_classes' ) );
		}

		/**
		 * Register email classes from this plugin to WooCommerce's emails class list.
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
		 * Check whether an email is enabled or not based on the given action.
		 *
		 * @param string $action The email action name.
		 * @return bool Return true whether the email is enabled otherwise false.
		 */
		public static function is_afwc_mailer_enabled( $action = '' ) {
			if ( empty( $action ) ) {
				return false;
			}

			$action_without_prefix = str_replace( 'afwc_', '', $action );

			$class_name = ( ! empty( $action_without_prefix ) ) ? sprintf( 'AFWC_%1$s', ucwords( $action_without_prefix, '_' ) ) : '';

			// Return false if the class name is not found.
			if ( empty( $class_name ) ) {
				return false;
			}

			$wc_mailer = is_callable( array( WC(), 'mailer' ) ) ? WC()->mailer() : null;

			if ( $wc_mailer instanceof WC_Emails && ! empty( $wc_mailer->emails[ $class_name ] ) && is_callable( array( $wc_mailer->emails[ $class_name ], 'is_enabled' ) ) && $wc_mailer->emails[ $class_name ]->is_enabled() ) {
				return true;
			}

			return false;
		}
	}

}

return new AFWC_Emails();
