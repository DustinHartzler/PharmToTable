<?php
/**
 * Main class for WooCommerce Subscription Compatibility.
 *
 * @package     affiliate-for-woocommerce/includes/integration/woocommerce-subscriptions/
 * @since       6.1.0
 * @version     1.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCS_AFWC_Compatibility' ) ) {

	/**
	 *  Compatibility class for WooCommerce Subscription plugin.
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
		private function __construct() {
			add_filter( 'afwc_admin_settings', array( $this, 'add_settings' ) );
			add_filter( 'afwc_endpoint_account_settings_after_key', array( $this, 'endpoint_account_settings_after_key' ) );
			add_filter( 'afwc_id_for_order', array( $this, 'get_affiliate_id_for_subscription_order' ), 9, 2 );
			add_filter( 'afwc_add_referral_in_admin_emails_setting_description', array( $this, 'referral_in_admin_emails_setting_description' ), 10, 1 );
			add_filter( 'afwc_allowed_emails_for_referral_details', array( $this, 'allowed_subscription_email' ) );
			add_filter( 'afwc_referral_order_context', array( $this, 'referral_order_contexts' ), 9, 2 );
			// Add the subscription commission rule.
			add_filter( 'afwc_commission_rule_group_title', array( $this, 'commission_rule_group_title' ), 9 );
			add_filter( 'afwc_commission_rule_registry', array( $this, 'commission_rule_registry' ), 9 );
		}

		/**
		 * Get single instance of WCS_AFWC_Compatibility.
		 *
		 * @return WCS_AFWC_Compatibility Singleton object of WCS_AFWC_Compatibility.
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Function to do version compare on WooCommerce Subscriptions Core.
		 *
		 * @param  string $version The version number.
		 * @return boolean
		 */
		public static function is_wcs_core_gte( $version = '' ) {
			if ( empty( $version ) || ! class_exists( 'WC_Subscriptions_Core_Plugin' ) || ! is_callable( array( 'WC_Subscriptions_Core_Plugin', 'instance' ) ) ) {
				return false;
			}

			$wcs_core         = WC_Subscriptions_Core_Plugin::instance();
			$wcs_core_version = is_callable( array( $wcs_core, 'get_library_version' ) ) ? $wcs_core->get_library_version() : 0;

			if ( empty( $wcs_core_version ) ) {
				return false;
			}

			return version_compare( $wcs_core_version, $version, '>=' );
		}

		/**
		 * Function to do version compare on WooCommerce Subscriptions plugin version.
		 *
		 * @param  string $version The version number.
		 * @return boolean
		 */
		public static function is_wcs_gte( $version = '' ) {
			if ( empty( $version ) || ! class_exists( 'WC_Subscriptions_Plugin' ) || ! is_callable( array( 'WC_Subscriptions_Plugin', 'instance' ) ) ) {
				return false;
			}

			$wcs_plugin         = WC_Subscriptions_Plugin::instance();
			$wcs_plugin_version = is_callable( array( $wcs_plugin, 'get_plugin_version' ) ) ? $wcs_plugin->get_plugin_version() : 0;

			if ( empty( $wcs_plugin_version ) ) {
				return false;
			}

			return version_compare( $wcs_plugin_version, $version, '>=' );
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
					'name'              => _x( 'Issue recurring commission?', 'recurring commission setting title', 'affiliate-for-woocommerce' ),
					'desc'              => _x( 'Enable this to give affiliate commissions for subscription recurring/renewal orders', 'recurring commission setting description', 'affiliate-for-woocommerce' ),
					'desc_tip'          => 'no' === get_option( 'is_recurring_commission', 'no' ) ?
						_x(
							"We have deprecated this setting. Since you had it disabled, we have automatically created a new plan for you: 'Do not issue recurring commission as Issue recurring commission? is disabled'. Please review the plan for more details.",
							'recurring commission setting description tip',
							'affiliate-for-woocommerce'
						)
						: _x(
							'We have deprecated this setting. To stop recurring/renewal commissions, create a new commission plan and add a rule: Renewal >= 0 and set the commission = 0.',
							'recurring commission setting description tip',
							'affiliate-for-woocommerce'
						),
					'id'                => 'is_recurring_commission',
					'type'              => 'checkbox',
					'default'           => 'no',
					'checkboxgroup'     => 'start',
					'autoload'          => false,
					'custom_attributes' => array(
						'disabled' => 'disabled',
					),
				),
			);

			array_splice( $settings, ( count( $settings ) - 1 ), 0, $wc_subscriptions_options );

			return $settings;
		}

		/**
		 * Return field key after which the setting should appear
		 *
		 * @return string
		 */
		public function endpoint_account_settings_after_key() {
			return 'woocommerce_myaccount_subscription_payment_method_endpoint';
		}

		/**
		 * Return affiliate ID for subscription order.
		 *
		 * @param int   $affiliate_id The affiliate ID.
		 * @param array $args The arguments.
		 *
		 * @return int Return the affiliate ID from the parent subscription if the order type is renewal otherwise default.
		 */
		public function get_affiliate_id_for_subscription_order( $affiliate_id = 0, $args = array() ) {
			if ( empty( $args ) || empty( $args['order_id'] ) ) {
				return $affiliate_id;
			}

			$order_id = intval( $args['order_id'] );

			$sub_types = array( 'renewal' );

			if ( ! wcs_order_contains_subscription( $order_id, $sub_types ) ) {
				return $affiliate_id;
			}

			$subscriptions = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => $sub_types ) );

			if ( ! empty( $subscriptions ) ) {
				$subscription = is_array( $subscriptions ) ? end( $subscriptions ) : $subscriptions;
				$parent_id    = $subscription instanceof WC_Subscription && is_callable( array( $subscription, 'get_parent_id' ) ) ? $subscription->get_parent_id() : 0;

				if ( empty( $parent_id ) ) {
					return $affiliate_id;
				}

				$afwc_api          = ( ! empty( $args['source'] ) ) ? $args['source'] : AFWC_API::get_instance();
				$affiliate_details = is_callable( array( $afwc_api, 'get_affiliate_by_order' ) ) ? $afwc_api->get_affiliate_by_order( intval( $parent_id ) ) : array();
				$affiliate_id      = ( ! empty( $affiliate_details ) && ! empty( $affiliate_details['affiliate_id'] ) ) ? intval( $affiliate_details['affiliate_id'] ) : 0;
			}

			return $affiliate_id;
		}

		/**
		 * Return updated setting description.
		 *
		 * @param string $description The setting description.
		 *
		 * @return string The updated setting description.
		 */
		public function referral_in_admin_emails_setting_description( $description = '' ) {
			if ( empty( $description ) ) {
				return '';
			}

			return _x( 'Include affiliate referral details in the WooCommerce New order and WooCommerce Subscriptions New Renewal Order', 'Admin setting description', 'affiliate-for-woocommerce' );
		}

		/**
		 * Return new renewal order email key if recurring commission is enabled.
		 *
		 * @param array $emails The allowed emails.
		 *
		 * @return array The allowed emails.
		 */
		public function allowed_subscription_email( $emails = array() ) {

			if ( is_array( $emails ) ) {
				array_push( $emails, 'new_renewal_order', 'new_switch_order' );
			}

			return $emails;
		}

		/**
		 * Return the commission plan contexts subscription.
		 *
		 * @param array $contexts The array of contexts.
		 * @param array $args The arguments.
		 *
		 * @return array The modified array of contexts.
		 */
		public function referral_order_contexts( $contexts = array(), $args = array() ) {
			$order_id = ! empty( $args['order_id'] ) ? intval( $args['order_id'] ) : 0;

			if ( empty( $order_id ) || ! is_array( $contexts ) ) {
				return $contexts;
			}

			$contexts['subscription_parent'] = wcs_order_contains_subscription( $order_id, array( 'parent' ) ) ? 'yes' : 'no';

			$subscription_count = -1;
			if ( wcs_order_contains_renewal( $order_id ) ) {

				$subscriptions = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => array( 'renewal' ) ) );

				if ( ! empty( $subscriptions ) ) {
					// Get the last subscription form the order.
					$subscription      = is_array( $subscriptions ) ? end( $subscriptions ) : $subscriptions;
					$renewal_order_ids = $subscription instanceof WC_Subscription && is_callable( array( $subscription, 'get_related_orders' ) ) ? $subscription->get_related_orders( 'ids', array( 'renewal' ) ) : 0;

					if ( ! empty( $renewal_order_ids ) && is_array( $renewal_order_ids ) ) {
						$subscription_count = count( $renewal_order_ids );
					}
				}
			}

			$contexts['subscription_renewal'] = $subscription_count;

			return $contexts;
		}

		/**
		 * Return the commission rule group title for subscription.
		 *
		 * @param array $titles The array of titles.
		 *
		 * @return array The modified array of titles.
		 */
		public function commission_rule_group_title( $titles = array() ) {
			if ( ! is_array( $titles ) ) {
				return $titles;
			}

			$titles['subscription'] = _x( 'Subscription', 'Commission group title for subscription rules', 'affiliate-for-woocommerce' );
			return $titles;
		}

		/**
		 * Return the commission rule registries.
		 *
		 * @param array $registries The array of registries.
		 *
		 * @return array The modified array of registries.
		 */
		public function commission_rule_registry( $registries = array() ) {
			if ( ! is_array( $registries ) || ! is_array( $registries['rule'] ) ) {
				return $registries;
			}

			$registries['rule']['subscription_parent']  = 'AFWC_Subscription_Parent_Commission';
			$registries['rule']['subscription_renewal'] = 'AFWC_Subscription_Renewal_Commission';
			return $registries;
		}

		/**
		 * Method to get subscription related description in the plans sidebar.
		 *
		 * @return string Return the description.
		 */
		public static function plan_description() {
			return _x(
				'To stop recurring commissions on subscription renewals, create a new commission plan and add a rule: Renewal >= 0 and set the commission = 0. Set this plan at the top to give priority over other plans.',
				'Plan description for renewal commission',
				'affiliate-for-woocommerce'
			);
		}

		/**
		 * Method to get subscription related admin notice of plan dashboard.
		 *
		 * @return string Return the notice text.
		 */
		public static function plan_admin_notice() {

			if ( 'no' === get_option( 'afwc_show_subscription_admin_dashboard_notice', 'no' ) ) {
				return '';
			}

			return _x(
				"We have deprecated the Issue recurring commission setting. Since you had the setting disabled, we have automatically created a new plan for you 'Do not issue recurring commission as Issue recurring commission? is disabled'. Please review the plan for more details.",
				'Admin notice for Issue recurring commission deprecated',
				'affiliate-for-woocommerce'
			);
		}
	}

}

WCS_AFWC_Compatibility::get_instance();
