<?php
/**
 * Main class for Affiliates Admin Settings
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       1.0.0
 * @version     1.4.2
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
			add_action( 'woocommerce_admin_field_afwc_ltc_excludes_list', array( $this, 'render_ltc_exclude_list_input' ) );
			add_filter( 'woocommerce_admin_settings_sanitize_option_afwc_lifetime_commissions_excludes', array( $this, 'sanitize_ltc_exclude_list' ) );

			// Ajax actions.
			add_action( 'wp_ajax_afwc_search_ltc_excludes_list', array( $this, 'afwc_json_search_exclude_ltc_list' ) );
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

			$afwc_paypal         = AFWC_PayPal_API::get_instance();
			$paypal_api_settings = $afwc_paypal->get_api_setting_status();

			$pname = afwc_get_pname();

			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			wp_enqueue_script( 'afwc-setting-js', AFWC_PLUGIN_URL . '/assets/js/afwc-settings.js', array( 'jquery', 'wp-i18n' ), $plugin_data['Version'], true );

			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'afwc-setting-js', 'affiliate-for-woocommerce' );
			}

			wp_localize_script(
				'afwc-setting-js',
				'afwcSettingParams',
				array(
					'oldPname' => $pname,
					'ajaxURL'  => admin_url( 'admin-ajax.php' ),
					'security' => array(
						'searchExcludeLTC' => wp_create_nonce( 'afwc-search-exclude-ltc-list' ),
					),
				)
			);

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			if ( ! wp_script_is( 'selectWoo', 'registered' ) ) {
				wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );
			}
			wp_enqueue_script( 'selectWoo' );
			wp_enqueue_style( 'selectWoo', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION );

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
				/* translators: %1$s: Opening strong tag %2$s: Opening a tag for affiliate manage tag page link %3$s: closing strong tag %4$s: closing a tag for affiliate manage tag page link */
				$affiliate_tags_desc = sprintf( esc_html__( '%1$s%2$sManage affiliate tags%3$s%4$s', 'affiliate-for-woocommerce' ), '<strong>', '<a target="_blank" href="' . esc_url( $affiliate_manage_tags_link ) . '">', '</a>', '</strong>' );
			}

			$default_affiliate_link = trailingslashit( home_url() ) . '?' . $pname . '={user_id}';
			$pretty_affiliate_link  = trailingslashit( home_url() ) . $pname . '/{user_id}';
			$affiliate_link         = trailingslashit( home_url() ) . ( ( 'yes' === get_option( 'afwc_use_pretty_referral_links', 'no' ) ) ? '<span id="afwc_pname_span">' . $pname . '</span>/{user_id}' : '?<span id="afwc_pname_span">' . $pname . '</span>={user_id}' );

			$plan_dashboard_link = admin_url( 'admin.php?page=affiliate-for-woocommerce#!/plans' );
			$default_plan        = afwc_get_default_plan_details();
			$default_plan_name   = ( ! empty( $default_plan ) && is_array( $default_plan ) && ! empty( $default_plan['name'] ) ) ? $default_plan['name'] : '';
			if ( ! empty( $plan_dashboard_link ) ) {
				/* translators: Link to the plan back link */
				$plan_backlink_desc = sprintf( esc_html__( 'Default commission plan: %s', 'affiliate-for-woocommerce' ), '<strong><a target="_blank" href="' . esc_url( $plan_dashboard_link ) . '">' . esc_attr( $default_plan_name ) . '</a></strong>' );
			}

			$referral_in_admin_email_description = apply_filters( 'afwc_add_referral_in_admin_emails_setting_description', _x( 'Include affiliate referral details in the WooCommerce New order email (if enabled)', 'Admin setting description', 'affiliate-for-woocommerce' ), array( 'source' => $this ) );

			$afwc_admin_settings = array(
				array(
					'title' => _x( 'Affiliate For WooCommerce Settings', 'Plugin setting tab name', 'affiliate-for-woocommerce' ),
					'desc'  => _x( 'Use these options to configure the way plugin works.', 'setting tab description', 'affiliate-for-woocommerce' ),
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
					'name'     => _x( 'Pretty affiliate links', 'setting name', 'affiliate-for-woocommerce' ),
					'desc'     => _x( 'Automatically convert default affiliate referral links to beautiful links.', 'setting description', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_use_pretty_referral_links',
					'type'     => 'checkbox',
					'default'  => 'no',
					/* translators: %1$s: Pretty affiliate link %2$s: Default affiliate link */
					'desc_tip' => sprintf( _x( 'When enabled, the affiliate links will look like <strong>%1$s</strong> instead of %2$s', 'setting description tip', 'affiliate-for-woocommerce' ), $pretty_affiliate_link, $default_affiliate_link ),
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
					'name'    => _x( 'Credit first/last affiliate', 'Admin setting name to credit first or last affiliate during referral', 'affiliate-for-woocommerce' ),
					'id'      => 'afwc_credit_affiliate',
					'type'    => 'radio',
					'options' => array(
						'first' => _x( 'First - Credit the first affiliate who referred the customer.', 'Admin setting first option to credit affiliate', 'affiliate-for-woocommerce' ),
						'last'  => _x( 'Last - Credit the last/latest affiliate who referred the customer.', 'Admin setting last option to credit affiliate', 'affiliate-for-woocommerce' ),
					),
					'default' => 'last',
				),
				array(
					'name'     => _x( 'Lifetime commissions', 'Admin setting name for lifetime commissions', 'affiliate-for-woocommerce' ),
					'desc'     => _x( 'Allow affiliates to receive lifetime commissions', 'Admin setting description for lifetime commissions', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_enable_lifetime_commissions',
					'type'     => 'checkbox',
					'default'  => 'no',
					'desc_tip' => _x( 'Affiliates will receive commissions for every sale made by the same customer linked to this affiliate - without using referral link or coupon.', 'Admin setting description tooltip for lifetime commissions', 'affiliate-for-woocommerce' ),
				),
				array(
					'name'         => _x( 'Lifetime commissions exclude affiliates', 'Admin setting name for lifetime commissions excludes', 'affiliate-for-woocommerce' ),
					'desc'         => _x( 'Exclude the affiliates either by individual affiliates or affiliate tags to not give them lifetime commissions.', 'Admin setting description for affiliates to exclude for lifetime commissions', 'affiliate-for-woocommerce' ),
					'id'           => 'afwc_lifetime_commissions_excludes',
					'type'         => 'afwc_ltc_excludes_list',
					'class'        => 'afwc-lifetime-commission-excludes-search wc-enhanced-select',
					'placeholder'  => _x( 'Search by affiliates or affiliate tags', 'Admin setting placeholder for lifetime commissions excludes', 'affiliate-for-woocommerce' ),
					'options'      => get_option( 'afwc_lifetime_commissions_excludes', array() ),
					'afwc_show_if' => 'afwc_enable_lifetime_commissions',
				),
				array(
					'name'     => _x( 'Affiliate self-refer', 'Admin setting name', 'affiliate-for-woocommerce' ),
					'desc'     => _x( 'Allow affiliates to earn commissions on their own orders', 'Admin setting description', 'affiliate-for-woocommerce' ),
					'desc_tip' => _x( 'Disabling this will not record a commission if an affiliate uses their own referral link/coupons during orders', 'Admin setting description tip', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_allow_self_refer',
					'type'     => 'checkbox',
					'default'  => 'yes',
					'autoload' => false,
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
				array(
					'name'     => _x( 'Send referral details to admin', 'Admin setting name', 'affiliate-for-woocommerce' ),
					'desc'     => $referral_in_admin_email_description,
					'desc_tip' => _x( 'Disabling this will not include affiliate referral details in the email to admin', 'Admin setting description tip', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_add_referral_in_admin_emails',
					'type'     => 'checkbox',
					'default'  => 'no',
					'autoload' => false,
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
					'name'     => _x( 'PayPal email address', 'setting name', 'affiliate-for-woocommerce' ),
					'desc'     => _x( 'Allow affiliates to enter their PayPal email address from their My Account > Affiliates > Profile for PayPal payouts', 'setting description', 'affiliate-for-woocommerce' ),
					'desc_tip' => _x( 'Disabling this will not show PayPal email address in My Account > Affiliates > Profile & WordPress Admin > Users > User profile.', 'setting description tip', 'affiliate-for-woocommerce' ),
					'id'       => 'afwc_allow_paypal_email',
					'type'     => 'checkbox',
					'default'  => 'no',
					'autoload' => false,
				),
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

		/**
		 * Ajax callback function to search the affiliates and affiliate tag.
		 */
		public function afwc_json_search_exclude_ltc_list() {

			check_admin_referer( 'afwc-search-exclude-ltc-list', 'security' );

			$term = ( ! empty( $_GET['term'] ) ) ? (string) urldecode( wp_strip_all_tags( wp_unslash( $_GET ['term'] ) ) ) : '';

			if ( empty( $term ) ) {
				wp_die();
			}

			$searched_list = $this->get_excluded_ltc_list( $term, array( 'affiliates', 'tags' ), true );

			if ( empty( $searched_list ) ) {
				wp_die();
			}

			$data = array();

			if ( ! empty( $searched_list['affiliates'] ) ) {
				$data[] = array(
					'title'    => _x( 'Affiliates', 'The group name for lifetime commission affiliates excluded list', 'affiliate-for-woocommerce' ),
					'group'    => 'affiliates',
					'children' => $searched_list['affiliates'],
				);
			}

			if ( ! empty( $searched_list['tags'] ) ) {
				$data[] = array(
					'title'    => _x( 'Affiliate Tags', 'The group name for lifetime commission affiliate tags excluded list', 'affiliate-for-woocommerce' ),
					'group'    => 'tags',
					'children' => $searched_list['tags'],
				);
			}

			wp_send_json( $data );
		}

		/**
		 * Method to get the formatted lifetime commission exclude list.
		 *
		 * @param string|array $term The value.
		 * @param array        $group The group name.
		 * @param bool         $for_search Whether the method will be used for searching or fetching the details by id.
		 *
		 * @return array.
		 */
		public function get_excluded_ltc_list( $term = '', $group = array(), $for_search = false ) {

			if ( empty( $term ) ) {
				return array();
			}

			global $affiliate_for_woocommerce;

			$values = array();

			if ( ! is_array( $group ) ) {
				$group = (array) $group;
			}

			if ( true === in_array( 'affiliates', $group, true ) ) {
				if ( true === $for_search ) {
					$affiliate_search = array(
						'search'         => '*' . $term . '*',
						'search_columns' => array( 'ID', 'user_nicename', 'user_login', 'user_email' ),
					);
				} else {
					$affiliate_search = array(
						'include' => ! is_array( $term ) ? (array) $term : $term,
					);
				}

				$values['affiliates'] = is_callable( array( $affiliate_for_woocommerce, 'get_affiliates' ) ) ? $affiliate_for_woocommerce->get_affiliates( $affiliate_search ) : array();
			}

			if ( true === in_array( 'tags', $group, true ) ) {
				$tag_search = array(
					'taxonomy'   => 'afwc_user_tags', // taxonomy name.
					'hide_empty' => false,
					'fields'     => 'id=>name',
				);
				if ( true === $for_search ) {
					$tag_search['search'] = $term;
				} else {
					$tag_search['include'] = $term;
				}

				$tags = get_terms( $tag_search );

				if ( ! empty( $tags ) ) {
					$values['tags'] = $tags;
				}
			}

			return $values;

		}

		/**
		 * Method to rendering the exclude list input field.
		 *
		 * @param array $value The value.
		 *
		 * @return void.
		 */
		public function render_ltc_exclude_list_input( $value = array() ) {

			if ( empty( $value ) ) {
				return;
			}

			$id                = ! empty( $value['id'] ) ? $value['id'] : '';
			$options           = ! empty( $value['options'] ) ? $value['options'] : array();
			$field_description = is_callable( array( 'WC_Admin_Settings', 'get_field_description' ) ) ? WC_Admin_Settings::get_field_description( $value ) : array();
			$description       = ! empty( $field_description['description'] ) ? $field_description['description'] : '';
			$is_hide           = ! empty( $value['afwc_show_if'] ) ? ( esc_attr( 'data-hide' ) . '=' . esc_attr( $value['afwc_show_if'] ) ) : '';
			?>	
				<tr valign="top" <?php echo wp_kses_post( $is_hide ); ?>>
					<th scope="row" class="titledesc"> 
						<label for="<?php echo esc_attr( $id ); ?>"> <?php echo ( ! empty( $value['title'] ) ? esc_html( $value['title'] ) : '' ); ?> </label>
					</th>
					<td class="forminp">
						<select
							name="<?php echo esc_attr( ! empty( $value['field_name'] ) ? $value['field_name'] : $id ); ?>[]"
							id="<?php echo esc_attr( $id ); ?>"
							style="<?php echo ! empty( $value['css'] ) ? esc_attr( $value['css'] ) : ''; ?>"
							class="<?php echo ! empty( $value['class'] ) ? esc_attr( $value['class'] ) : ''; ?>"
							data-placeholder="<?php echo ! empty( $value['placeholder'] ) ? esc_attr( $value['placeholder'] ) : ''; ?>"
							multiple="multiple"
						>
						<?php
						foreach ( $options as $group => $ids ) {
							if ( 'affiliates' === $group ) {
								$group_title = _x( 'Affiliates', 'The group name for lifetime commission affiliates excluded list', 'affiliate-for-woocommerce' );
							} elseif ( 'tags' === $group ) {
								$group_title = _x( 'Affiliate Tags', 'The group name for lifetime commission affiliate tags excluded list', 'affiliate-for-woocommerce' );
							} else {
								$group_title = $group;
							}

							$exclude_list = $this->get_excluded_ltc_list( $ids, (array) $group );
							$current_list = ! empty( $exclude_list ) && ! empty( $exclude_list[ $group ] ) ? $exclude_list[ $group ] : array();

							if ( ! empty( $current_list ) ) {
								?>
								<optgroup label=<?php echo esc_attr( $group_title ); ?>>
								<?php
								foreach ( $current_list as $id => $text ) {
									?>
									<option
										value="<?php echo esc_attr( $group . '-' . $id ); ?>"
										selected='selected'
									><?php echo ! empty( $text ) ? esc_html( $text ) : ''; ?></option>
									<?php
								}
							}
							?>
							</optgroup>
							<?php
						}
						?>
						</select> <?php echo ! empty( $field_description['description'] ) ? wp_kses_post( $field_description['description'] ) : ''; ?>
					</td>
				</tr>
			<?php
		}

		/**
		 * Method to sanitize and format the value for ltc exclude list.
		 *
		 * @param array $value The value.
		 *
		 * @return array.
		 */
		public function sanitize_ltc_exclude_list( $value = array() ) {

			// Return empty array if the value is empty.
			if ( empty( $value ) ) {
				return array();
			}

			$list = array();

			foreach ( $value as $list_id ) {
				// Separate the group name and id.
				$list_id_parts = explode( '-', $list_id, 2 );
				if ( ! empty( $list_id_parts ) ) {
					// Get the group name from the first place.
					$group = current( $list_id_parts );

					// Get the id from the last place.
					$id = end( $list_id_parts );

					// Add the ids to the each group for formatting the value to store in DB.
					$list[ $group ][] = absint( $id );
				} else {
					$list[] = $id;
				}
			}

			return $list;
		}

	}

}

return new AFWC_Admin_Settings();
