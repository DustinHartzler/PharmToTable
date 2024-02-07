<?php
/**
 * Main class for Onboarding.
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       6.30.0
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Onboarding' ) ) {

	/**
	 * Main class for Onboarding.
	 */
	class AFWC_Onboarding {

		/**
		 * The Ajax events.
		 *
		 * @var array $ajax_events
		 */
		private $ajax_events = array(
			'setup_affiliates',
			'setup_basic_settings',
			'setup_commissions',
			'setup_emails',
		);

		/**
		 * Variable to hold instance of AFWC_Onboarding.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Onboarding Singleton object of this class.
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_afwc_onboarding_controller', array( $this, 'request_handler' ) );
		}

		/**
		 * Function to handle all ajax request.
		 */
		public function request_handler() {

			if ( empty( $_REQUEST ) || empty( wc_clean( wp_unslash( $_REQUEST['cmd'] ) ) ) ) { // phpcs:ignore
				return;
			}

			$params = array();

			$params = array_map(
				function ( $request_param ) {
					return wc_clean( wp_unslash( $request_param ) );
				},
				$_REQUEST // phpcs:ignore
			);

			$func_nm = ! empty( $params['cmd'] ) ? $params['cmd'] : '';

			if ( empty( $func_nm ) || empty( $this->ajax_events ) || ! is_array( $this->ajax_events ) || ! in_array( $func_nm, $this->ajax_events, true ) ) {
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			if ( is_callable( array( $this, $func_nm ) ) ) {
				$this->$func_nm( $params );
			}
		}

		/**
		 * Method to setup affiliates.
		 *
		 * @param array $params The params.
		 */
		public function setup_affiliates( $params = array() ) {
			check_admin_referer( 'afwc-admin-onboarding-setup-affiliates', 'security' );

			if ( ! afwc_current_user_can_manage_affiliate() ) {
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			if ( ! empty( $params['affiliate_roles'] ) ) {

				$roles = json_decode( $params['affiliate_roles'], true );

				if ( ! empty( $roles ) && is_array( $roles ) ) {
					update_option( 'affiliate_users_roles', $roles, 'no' );
				}
			}

			if ( ! empty( $params['enable_affiliate'] ) && 'yes' === $params['enable_affiliate'] ) {
				$current_user_id = get_current_user_id();
				if ( ! empty( $current_user_id ) ) {
					update_user_meta( $current_user_id, 'afwc_is_affiliate', 'yes' );
				}
			}

			if ( ! empty( $params['publish_registration_page'] ) && 'yes' === $params['publish_registration_page'] ) {
				$registration_page = get_page_by_path( 'affiliates' );
				if ( ! empty( $registration_page ) && $registration_page instanceof WP_Post ) {
					wp_publish_post( $registration_page );
				}
			}

			wp_send_json_success();
		}

		/**
		 * Method to setup basic settings.
		 *
		 * @param array $params The params.
		 */
		public function setup_basic_settings( $params = array() ) {
			check_admin_referer( 'afwc-admin-onboarding-setup-basic-settings', 'security' );

			if ( ! afwc_current_user_can_manage_affiliate() ) {
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			if ( ! empty( $params['settings'] ) ) {

				$settings = json_decode( $params['settings'], true );

				if ( ! empty( $settings ) && is_array( $settings ) ) {
					foreach ( $settings as $key => $value ) {
						update_option( $key, $value, 'no' );
					}
				}
			}

			wp_send_json_success();
		}

		/**
		 * Method to setup commissions.
		 *
		 * @param array $params The params.
		 */
		public function setup_commissions( $params = array() ) {
			check_admin_referer( 'afwc-admin-onboarding-setup-commissions', 'security' );

			if ( ! afwc_current_user_can_manage_affiliate() ) {
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			global $wpdb;

			$commission = array(
				'amount' => 0,
				'type'   => 'Percentage',
			);

			$updated_commissions = $commission;

			if ( ! empty( $params['commission_amount'] ) ) {
				$updated_commissions['amount'] = floatval( $params['commission_amount'] );
			}

			if ( ! empty( $params['commission_type'] ) ) {
				$updated_commissions['type'] = $params['commission_type'];
			}

			if ( $commission !== $updated_commissions ) {

				$default_plan_id = afwc_get_default_commission_plan_id();

				if ( ! empty( $default_plan_id ) ) {
					$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
						$wpdb->prefix . 'afwc_commission_plans',
						$updated_commissions,
						array( 'id' => $default_plan_id ),
						array( '%s', '%s' ),
						array( '%s' )
					);
				}
			}

			wp_send_json_success();
		}

		/**
		 * Method to setup emails.
		 *
		 * @param array $params The params.
		 */
		public function setup_emails( $params = array() ) {
			check_admin_referer( 'afwc-admin-onboarding-setup-emails', 'security' );

			if ( ! afwc_current_user_can_manage_affiliate() ) {
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			$emails = ! empty( $params['email_setup'] ) ? json_decode( $params['email_setup'], true ) : array();

			if ( ! empty( $emails ) && is_array( $emails ) ) {
				update_option( 'afwc_onboarding_emails_setup', $emails, 'no' );
			}

			wp_send_json_success();
		}
	}
}

AFWC_Onboarding::get_instance();
