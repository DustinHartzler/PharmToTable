<?php
/**
 * Main class for Affiliates Admin Settings
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @version     1.2.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Settings' ) ) {

	/**
	 * Main class for Affiliate Admin Settings
	 */
	class AFWC_Admin_Settings {

		/**
		 * Affiliate For WooCommerce settings tab name
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $tab_slug = 'affiliate-for-woocommerce-settings';

		/**
		 *  Constructor
		 */
		public function __construct() {

			// Actions and Filters for Affiliate For WooCommerce Settings' tab.
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
			add_action( 'woocommerce_settings_' . $this->tab_slug, array( $this, 'display_settings_tab' ) );
			add_action( 'woocommerce_update_options_' . $this->tab_slug, array( $this, 'save_admin_settings' ) );
		}

		/**
		 * Function to add setting tab for Affiliate For WooCommerce
		 *
		 * @param array $settings_tabs Existing tabs.
		 * @return array $settings_tabs New settings tabs.
		 */
		public function add_settings_tab( $settings_tabs = array() ) {

			$settings_tabs[ $this->tab_slug ] = __( 'Affiliate', 'affiliate-for-woocommerce' );

			return $settings_tabs;
		}

		/**
		 * Function to display Affiliate For WooCommerce settings' tab
		 */
		public function display_settings_tab() {

			$afwc_admin_settings = $this->get_settings();
			if ( ! is_array( $afwc_admin_settings ) || empty( $afwc_admin_settings ) ) {
				return;
			}

			woocommerce_admin_fields( $afwc_admin_settings );
			wp_nonce_field( 'afwc_admin_settings_security', 'afwc_admin_settings_security', false );
		}

		/**
		 * Function to get Affiliate For WooCommerce admin settings
		 *
		 * @return array $afwc_admin_settings Affiliate For WooCommerce admin settings.
		 */
		public function get_settings() {
			global $wp_roles;

			$all_product_ids    = get_posts(
				array(
					'post_type'   => array( 'product', 'product_variation' ),
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'      => 'ids',
				)
			);
			$product_id_to_name = array();
			foreach ( $all_product_ids as $key => $value ) {
				$product_id_to_name[ $value ] = get_the_title( $value );
			}

			$afwc_paypal         = AFWC_Paypal::get_instance();
			$paypal_api_settings = $afwc_paypal->get_api_setting_status();

			$pname = get_option( 'afwc_pname', 'ref' );
			$pname = ( ! empty( $pname ) ) ? $pname : 'ref';

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_enqueue_script( 'afwc-setting-js', AFWC_PLUGIN_URL . '/assets/js/afwc-settings.js', array( 'jquery' ), $plugin_data['Version'], true );
			$afwc_settings_pre_data['old_pname']   = $pname;
			$afwc_settings_pre_data['confirm_msg'] = __( 'Changing affiliate slug will stop tracking the existing URL with the current slug. Are you sure you want to continue?', 'affiliate-for-woocommerce' );
			wp_localize_script( 'afwc-setting-js', 'afwc_settings_pre_data', $afwc_settings_pre_data );

			$affiliate_registration_page_link      = ! empty( get_permalink( get_page_by_path( 'afwc_registration_form' ) ) ) ? get_permalink( get_page_by_path( 'afwc_registration_form' ) ) : get_permalink( get_page_by_path( 'affiliates' ) );
			$affiliate_registration_edit_form_link = admin_url( 'admin.php?page=affiliate-form-settings' );
			$affiliate_form_desc                   = '';
			if ( ! empty( $affiliate_registration_page_link ) ) {
				/* translators: Link to the affiliate registration form page */
				$affiliate_form_desc = sprintf( esc_html__( '%s | ', 'affiliate-for-woocommerce' ), '<strong><a target="_blank" href="' . esc_url( $affiliate_registration_page_link ) . '">' . esc_html__( 'Review and publish form', 'affiliate-for-woocommerce' ) . '</a></strong>' );
			}
			/* translators: Link to the affiliate registration form edit page */
			$affiliate_form_desc .= sprintf( esc_html__( '%s | ', 'affiliate-for-woocommerce' ), '<strong><a target="_blank" href="' . esc_url( $affiliate_registration_edit_form_link ) . '">' . esc_html__( 'Edit form', 'affiliate-for-woocommerce' ) . '</a></strong>' );
			/* translators: shortcode for affiliate registration form */
			$affiliate_form_desc .= sprintf( esc_html__( 'use %s shortcode on any page.', 'affiliate-for-woocommerce' ), '<code>[afwc_registration_form]</code>' );

			$affiliate_tags_desc        = '';
			$affiliate_manage_tags_link = admin_url( 'edit-tags.php?taxonomy=afwc_user_tags' );
			if ( ! empty( $affiliate_manage_tags_link ) ) {
				/* translators: Link to the affiliate tags */
				$affiliate_tags_desc = sprintf( esc_html__( '%s.', 'affiliate-for-woocommerce' ), '<strong><a target="_blank" href="' . esc_url( $affiliate_manage_tags_link ) . '">' . esc_html__( 'Manage affiliate tags', 'affiliate-for-woocommerce' ) . '</a></strong>' );
			}

			$affiliate_link = trailingslashit( home_url() ) . '?<span id="afwc_pname_span">' . $pname . '</span>={user_id}';

			$plan_dashboard_link = admin_url( 'admin.php?page=affiliate-for-woocommerce#!/plans' );
			$default_plan        = afwc_get_default_plan_details();
			$default_plan_name   = $default_plan['name'];
			if ( ! empty( $plan_dashboard_link ) ) {
				/* translators: Link to the plan back link */
				$plan_backlink_desc = sprintf( esc_html__( 'Default commission plan: %s', 'affiliate-for-woocommerce' ), '<strong><a target="_blank" href="' . esc_url( $plan_dashboard_link ) . '">' . esc_attr( $default_plan_name ) . '</a></strong>' );
			}
			$afwc_admin_settings = array(
				array(
					'title' => __( 'Affiliate For WooCommerce Settings', 'affiliate-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'afwc_admin_settings',
				),
				array(
					'name'     => __( 'Registration form', 'affiliate-for-woocommerce' ),
					'desc'     => $affiliate_form_desc,
					'id'       => 'affiliate_reg_form',
					'type'     => 'text',
					'autoload' => false,
				),
				array(
					'name'     => __( 'Approval method', 'affiliate-for-woocommerce' ),
					'desc'     => __( 'Automatically approve all submissions via Affiliate Registration Form - no manual review needed.', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_auto_add_affiliate',
					'type'     => 'checkbox',
					'default'  => 'no',
					'desc_tip' => __( 'Disabling this will require you to review and approve affiliates yourself. They won\'t become affiliates until you approve.', 'affiliate-for-woocommerce' ),
					'autoload' => false,
				),
				array(
					'name'     => __( 'Affiliate users roles', 'affiliate-for-woocommerce' ),
					'desc'     => __( 'Users with these roles automatically become affiliates.', 'affiliate-for-woocommerce' ),
					'id'       => 'affiliate_users_roles',
					'type'     => 'multiselect',
					'class'    => 'wc-enhanced-select',
					'desc_tip' => false,
					'options'  => $wp_roles->role_names,
					'autoload' => false,
				),
				array(
					'name'     => __( 'Referral commission', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_storewide_commission',
					'type'     => 'text',
					'desc'     => $plan_backlink_desc,
					'autoload' => false,
				),
				array(
					'name'     => __( 'Excluded products', 'affiliate-for-woocommerce' ),
					'desc'     => __( 'All products are eligible for affiliate commission by default. If you want to exclude some, list them here.', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_storewide_excluded_products',
					'type'     => 'multiselect',
					'class'    => 'wc-product-search',
					'desc_tip' => false,
					'options'  => $product_id_to_name,
					'autoload' => false,
				),
				array(
					'name'     => __( 'Affiliate tags', 'affiliate-for-woocommerce' ),
					'desc'     => $affiliate_tags_desc,
					'id'       => 'affiliate_tags',
					'type'     => 'text',
					'autoload' => false,
				),
				array(
					'name'        => __( 'Tracking param name', 'affiliate-for-woocommerce' ),
					'desc'        => $affiliate_link,
					'id'          => 'afwc_pname',
					'type'        => 'text',
					'placeholder' => __( 'Leaving this blank will use default value ref', 'affiliate-for-woocommerce' ),
					'autoload'    => false,
				),
				array(
					'name'     => __( 'Personalize affiliate identifier', 'affiliate-for-woocommerce' ),
					'desc'     => __( 'Allow affiliates to use something other than {user_id} as referral identifier.', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_allow_custom_affiliate_identifier',
					'type'     => 'checkbox',
					'default'  => 'yes',
					'desc_tip' => __( 'Good idea to keep this on. This allows "friendly" looking links - because people can use their brand name instead of {user_id}.', 'affiliate-for-woocommerce' ),
					'autoload' => false,
				),
				array(
					'title'    => __( 'Coupons for referral', 'affiliate-for-woocommerce' ),
					'desc'     => __( 'Use coupons for referral - along with affiliated links', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_use_referral_coupons',
					'type'     => 'checkbox',
					'default'  => 'yes',
					'desc_tip' => __( 'Use the <code>Assign to affiliate</code> option while creating a coupon to link the coupon with an affiliate. Whenever that coupon is used, specified affiliate will be credited for the sale.', 'affiliate-for-woocommerce' ),
					'autoload' => false,
				),
				array(
					'name'              => __( 'Cookie duration (in days)', 'affiliate-for-woocommerce' ),
					'desc'              => __( 'Use 0 for "session only" referrals. Use 36500 for 100 year / lifetime referrals. If someone makes a purchase within these many days of their first referred visit, affiliate will be credited for the sale.', 'affiliate-for-woocommerce' ),
					'id'                => 'afwc_cookie_expiration',
					'type'              => 'number',
					'default'           => 60,
					'autoload'          => false,
					'desc_tip'          => false,
					'custom_attributes' => array(
						'min' => 0,
					),
				),
				array(
					'name'        => __( 'Affiliate manager email', 'affiliate-for-woocommerce' ),
					'desc'        => __( 'Affiliates will see a link to contact you in their dashboard - and the link will point to this email address. Leave this field blank to hide the contact link.', 'affiliate-for-woocommerce' ),
					'id'          => 'afwc_contact_admin_email_address',
					'type'        => 'text',
					'placeholder' => __( 'Enter email address', 'affiliate-for-woocommerce' ),
					'autoload'    => false,
					'desc_tip'    => false,
				),
				// phpcs:disable
				// array(
				//  'title'    => __( 'Approve commission', 'affiliate-for-woocommerce' ),
				//  'id'       => 'afwc_approve_commissions',
				//  'default'  => 'instant',
				//  'type'     => 'radio',
				//  'options'  => array(
				//      'instant' => __( 'Immediately after order completes', 'affiliate-for-woocommerce' ),
				//  ),
				//  'autoload' => false,
				// ),
				// array(
				//  'title'    => __( 'Minimum commission balance requirement', 'affiliate-for-woocommerce' ),
				//  'id'       => 'afwc_min_commissions_balance',
				//  'default'  => 'no',
				//  'type'     => 'radio',
				//  'options'  => array(
				//      'no' => __( 'Not required', 'affiliate-for-woocommerce' ),
				//  ),
				//  'autoload' => false,
				// ),
				// phpcs:enable
				array(
					'title'             => __( 'Payout via PayPal', 'affiliate-for-woocommerce' ),
					'type'              => 'checkbox',
					'default'           => 'no',
					'autoload'          => false,
					'value'             => $paypal_api_settings['value'],
					'desc'              => $paypal_api_settings['desc'],
					'desc_tip'          => $paypal_api_settings['desc_tip'],
					'id'                => 'afwc_paypal_payout',
					'custom_attributes' => array(
						'disabled' => 'disabled',
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'afwc_admin_settings',
				),
			);

			return apply_filters( 'afwc_admin_settings', $afwc_admin_settings );

		}

		/**
		 * Function for saving settings for Affiliate For WooCommerce
		 */
		public function save_admin_settings() {
			if ( ! isset( $_POST['afwc_admin_settings_security'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['afwc_admin_settings_security'] ) ), 'afwc_admin_settings_security' )  ) { // phpcs:ignore
				return;
			}

			$afwc_admin_settings = $this->get_settings();
			if ( ! is_array( $afwc_admin_settings ) || empty( $afwc_admin_settings ) ) {
				return;
			}

			woocommerce_update_options( $afwc_admin_settings );
		}

	}

}

return new AFWC_Admin_Settings();
