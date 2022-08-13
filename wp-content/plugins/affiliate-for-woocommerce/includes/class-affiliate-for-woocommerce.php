<?php
/**
 * Main class for Affiliate For WooCommerce
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       1.0.0
 * @version     1.5.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Affiliate_For_WooCommerce' ) ) {

	/**
	 * Main class for Affiliate For WooCommerce
	 */
	final class Affiliate_For_WooCommerce {

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return Affiliate_For_WooCommerce Singleton object of this class
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

			$this->constants();
			add_action( 'init', array( $this, 'init_afwc' ) );
			$this->includes();
			// save affiliate form initial fields.
			$this->save_afwc_reg_form_settings();

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_afwc_admin_menu' ), 20 );
				add_action( 'admin_head', array( $this, 'add_afwc_remove_submenu' ) );
			}

			add_filter( 'query_vars', array( $this, 'afwc_query_vars' ), 10000 );
			add_action( 'parse_request', array( $this, 'afwc_parse_request' ) );

			add_action( 'valid-paypal-standard-ipn-request', array( $this, 'handle_ipn_request' ) );

			// Update update referral entry when order is trashed/untrashed/deleted.
			add_action( 'trashed_post', array( $this, 'afwc_update_referral_on_trash_delete_untrash' ), 9, 1 );
			add_action( 'untrashed_post', array( $this, 'afwc_update_referral_on_trash_delete_untrash' ), 9, 1 );
			add_action( 'delete_post', array( $this, 'afwc_update_referral_on_trash_delete_untrash' ), 9, 1 );

		}

		/**
		 * Function to handle WC compatibility related function call from appropriate class
		 *
		 * @param string $function_name Function to call.
		 * @param array  $arguments     Array of arguments passed while calling $function_name.
		 * @return mixed Result of function call.
		 */
		public function __call( $function_name, $arguments = array() ) {

			if ( ! is_callable( 'SA_WC_Compatibility_3_9', $function_name ) ) {
				return;
			}

			if ( ! empty( $arguments ) ) {
				return call_user_func_array( 'SA_WC_Compatibility_3_9::' . $function_name, $arguments );
			} else {
				return call_user_func( 'SA_WC_Compatibility_3_9::' . $function_name );
			}

		}

		/**
		 * Function to define constants
		 */
		public function constants() {

			global $wpdb;

			if ( ! defined( 'AFWC_AFFILIATES_COOKIE_NAME' ) ) {
				define( 'AFWC_AFFILIATES_COOKIE_NAME', 'affiliate_for_woocommerce' );
			}
			if ( ! defined( 'AFWC_CAMPAIGN_COOKIE_NAME' ) ) {
				define( 'AFWC_CAMPAIGN_COOKIE_NAME', 'afwc_campaign' );
			}
			if ( ! defined( 'AFWC_TABLE_PREFIX' ) ) {
				define( 'AFWC_TABLE_PREFIX', 'afwc_' );
			}
			if ( ! defined( 'AFWC_PLUGIN_BASENAME' ) ) {
				define( 'AFWC_PLUGIN_BASENAME', plugin_basename( dirname( AFWC_PLUGIN_FILE ) ) );
			}
			if ( ! defined( 'AFWC_PLUGIN_DIR' ) ) {
				define( 'AFWC_PLUGIN_DIR', dirname( plugin_basename( AFWC_PLUGIN_FILE ) ) );
			}
			if ( ! defined( 'AFWC_PLUGIN_URL' ) ) {
				define( 'AFWC_PLUGIN_URL', plugins_url( AFWC_PLUGIN_DIR ) );
			}
			if ( ! defined( 'AFWC_PLUGIN_DIR_PATH' ) ) {
				define( 'AFWC_PLUGIN_DIR_PATH', plugin_dir_path( AFWC_PLUGIN_FILE ) );
			}
			if ( ! defined( 'AFWC_CURRENCY' ) ) {
				define( 'AFWC_CURRENCY', get_woocommerce_currency_symbol() );
			}
			if ( ! defined( 'AFWC_PNAME' ) ) {
				define( 'AFWC_PNAME', get_option( 'afwc_pname', 'ref' ) );
			}
			if ( ! defined( 'AFWC_COOKIE_TIMEOUT_BASE' ) ) {
				define( 'AFWC_COOKIE_TIMEOUT_BASE', 86400 );
			}
			if ( ! defined( 'AFWC_REGEX_PATTERN' ) ) {
				define( 'AFWC_REGEX_PATTERN', 'affiliates/([^/]+)/?$' );
			}
			if ( ! defined( 'AFWC_DEFAULT_COMMISSION_STATUS' ) ) {
				define( 'AFWC_DEFAULT_COMMISSION_STATUS', get_option( 'afwc_default_commission_status' ) );
			}
			if ( ! defined( 'AFWC_AJAX_SECURITY' ) ) {
				define( 'AFWC_AJAX_SECURITY', 'affiliate_for_woocommerce_ajax_call' );
			}
			if ( ! defined( 'AFWC_REFERRAL_STATUS_PENDING' ) ) {
				define( 'AFWC_REFERRAL_STATUS_PENDING', 'pending' );
			}
			if ( ! defined( 'AFWC_REFERRAL_STATUS_DRAFT' ) ) {
				define( 'AFWC_REFERRAL_STATUS_DRAFT', 'draft' );
			}
			if ( ! defined( 'AFWC_REFERRAL_STATUS_PAID' ) ) {
				define( 'AFWC_REFERRAL_STATUS_PAID', 'paid' );
			}
			if ( ! defined( 'AFWC_REFERRAL_STATUS_UNPAID' ) ) {
				define( 'AFWC_REFERRAL_STATUS_UNPAID', 'unpaid' );
			}
			if ( ! defined( 'AFWC_REFERRAL_STATUS_REJECTED' ) ) {
				define( 'AFWC_REFERRAL_STATUS_REJECTED', 'rejected' );
			}

			// My account - default limit to load records.
			if ( ! defined( 'AFWC_MY_ACCOUNT_DEFAULT_BATCH_LIMIT' ) ) {
				define( 'AFWC_MY_ACCOUNT_DEFAULT_BATCH_LIMIT', 5 );
			}

			// Admin - default limit to load orders and payouts.
			if ( ! defined( 'AFWC_ADMIN_DASHBOARD_DEFAULT_BATCH_LIMIT' ) ) {
				define( 'AFWC_ADMIN_DASHBOARD_DEFAULT_BATCH_LIMIT', 50 );
			}

			if ( ! defined( 'AFWC_TIMEZONE_STR' ) ) {
				$offset       = get_option( 'gmt_offset' );
				$timezone_str = sprintf( '%+02d:%02d', (int) $offset, ( $offset - floor( $offset ) ) * 60 );
				define( 'AFWC_TIMEZONE_STR', $timezone_str );
			}

			if ( ! defined( 'AFWC_OPTION_NAME_COLLATION' ) ) {
					// Code to get the 'option_name' collation.
				$results = $wpdb->get_row( // phpcs:ignore
					$wpdb->prepare(
						"SHOW FULL COLUMNS FROM {$wpdb->prefix}options LIKE %s",
						'option_name'
					),
					ARRAY_A
				);

				$collation = ( ! empty( $results['Collation'] ) ) ? $results['Collation'] : $wpdb->collate;
				define( 'AFWC_OPTION_NAME_COLLATION', $collation );
			}

		}

		/**
		 * Init Affiliate for WooCommerce functions when WordPress Initializes.
		 */
		public function init_afwc() {
			$this->load_plugin_textdomain();
			$this->register_user_tags_taxonomy();
			$this->set_payout_method();
		}

		/**
		 * Load plugin Localization files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 *
		 * Locales found in:
		 *      - WP_LANG_DIR/affiliate-for-woocommerce/affiliate-for-woocommerce-LOCALE.mo
		 *      - WP_LANG_DIR/plugins/affiliate-for-woocommerce-LOCALE.mo
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', determine_locale(), 'affiliate-for-woocommerce' );

			unload_textdomain( 'affiliate-for-woocommerce' );
			load_textdomain( 'affiliate-for-woocommerce', WP_LANG_DIR . '/affiliate-for-woocommerce/affiliate-for-woocommerce-' . $locale . '.mo' );
			load_plugin_textdomain( 'affiliate-for-woocommerce', false, AFWC_PLUGIN_BASENAME . '/languages' );
		}

		/**
		 * Function to register affiliate tags taxonomy
		 */
		public function register_user_tags_taxonomy() {
			register_taxonomy(
				'afwc_user_tags', // taxonomy name.
				'user', // object for which the taxonomy is created.
				array( // taxonomy details.
					'public'       => true,
					'labels'       => array(
						'name'          => __( 'Affiliate Tags', 'affiliate-for-woocommerce' ),
						'singular_name' => __( 'Affiliate Tag', 'affiliate-for-woocommerce' ),
						'menu_name'     => __( 'Affiliate Tags', 'affiliate-for-woocommerce' ),
						'search_items'  => __( 'Search Affiliate Tag', 'affiliate-for-woocommerce' ),
						'popular_items' => __( 'Popular Affiliate Tags', 'affiliate-for-woocommerce' ),
						'all_items'     => __( 'All Affiliate Tags', 'affiliate-for-woocommerce' ),
						'edit_item'     => __( 'Edit Affiliate Tag', 'affiliate-for-woocommerce' ),
						'update_item'   => __( 'Update Affiliate Tag', 'affiliate-for-woocommerce' ),
						'add_new_item'  => __( 'Add New Affiliate Tag', 'affiliate-for-woocommerce' ),
						'new_item_name' => __( 'New Affiliate Tag Name', 'affiliate-for-woocommerce' ),
						'not_found'     => __( 'No Affiliate Tags found', 'affiliate-for-woocommerce' ),
					),
					'show_in_menu' => false,
					'hierarchical' => true,
				)
			);

			$default_affiliate_tags    = array( 'Gold', 'Silver', 'Bronze', 'Platinum', 'Dormant', 'Active', 'Promoter', 'Influencer' );
			$afwc_default_tags_created = get_option( 'afwc_default_tags_created', false );
			if ( ! $afwc_default_tags_created ) {
				foreach ( $default_affiliate_tags  as $value ) {
					wp_insert_term( $value, 'afwc_user_tags' );
				}
				update_option( 'afwc_default_tags_created', true, 'no' );
			}
		}

		/**
		 * Includes
		 */
		public function includes() {
			include_once 'affiliate-for-woocommerce-functions.php';

			// start.
			include_once 'commission_rules/class-afwc-rule-context.php';
			include_once 'commission_rules/class-afwc-registry.php';
			include_once 'commission_rules/class-afwc-rule.php';

			$afwc_base_rule_classes = glob( AFWC_PLUGIN_DIRPATH . '/includes/commission_rules/rules/base_rules/*.php' );
			$afwc_rule_classes      = glob( AFWC_PLUGIN_DIRPATH . '/includes/commission_rules/rules/*.php' );
			$afwc_base_rule_classes = array_merge( $afwc_base_rule_classes, $afwc_rule_classes );
			foreach ( $afwc_base_rule_classes as $rule_class ) {
				if ( is_file( $rule_class ) ) {
					include_once $rule_class;
				}
			}
			include_once 'commission_rules/class-afwc-rule-group.php';
			include_once 'commission_rules/class-afwc-plan.php';
			// end.
			if ( is_admin() ) {
				include_once 'migration/class-afwc-migrate-affiliates.php';
				include_once 'admin/class-afwc-admin-settings.php';
				include_once 'admin/class-afwc-admin-affiliates.php';
				include_once 'admin/class-afwc-admin-dashboard.php';
				include_once 'admin/class-afwc-campaign-dashboard.php';
				include_once 'admin/class-afwc-commission-dashboard.php';
				include_once 'admin/class-afwc-admin-affiliate.php';
				include_once 'admin/class-afwc-admin-docs.php';
				include_once 'admin/class-afwc-privacy.php';
				include_once 'admin/class-afwc-admin-notifications.php';
				include_once 'admin/class-afwc-admin-affiliate-users.php';
				include_once 'admin/class-afwc-admin-link-unlink-in-order.php';
			}

			include_once 'class-afwc-db-background-process.php';

			if ( afwc_is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
				include_once 'integration/woocommerce/compat/class-wcs-afwc-compatibility.php';
			}
			if ( afwc_is_plugin_active( 'woocommerce-smart-coupons/woocommerce-smart-coupons.php' ) && 'yes' === get_option( 'afwc_use_referral_coupons', 'yes' ) ) {
				include_once 'integration/woocommerce-smart-coupons/class-wsc-afwc-compatibility.php';
			}
			include_once 'gateway/paypal/class-afwc-paypal-api.php'; // TODO: remove usage from my account and then move this file to include under only admin.
			include_once 'integration/woocommerce/class-afwc-integration-woocommerce.php';
			include_once 'class-afwc-affiliate.php';
			include_once 'class-afwc-api.php';
			include_once 'integration/woocommerce/compat/class-sa-wc-compatibility-3-5.php';
			include_once 'integration/woocommerce/compat/class-sa-wc-compatibility-3-6.php';
			include_once 'integration/woocommerce/compat/class-sa-wc-compatibility-3-7.php';
			include_once 'integration/woocommerce/compat/class-sa-wc-compatibility-3-8.php';
			include_once 'integration/woocommerce/compat/class-sa-wc-compatibility-3-9.php';

			include_once 'class-afwc-coupon.php';

			include_once 'frontend/class-afwc-my-account.php';
			include_once 'frontend/class-afwc-registration-form.php';

			include_once 'class-afwc-db-upgrade.php';

			include_once 'class-afwc-emails.php';
		}

		/**
		 * Function to log messages generated by Affiliate plugin
		 *
		 * @param  string $level   Message type. Valid values: debug, info, notice, warning, error, critical, alert, emergency.
		 * @param  string $message The message to log.
		 */
		public function log( $level = 'notice', $message = '' ) {
			if ( empty( $message ) ) {
				return;
			}

			if ( function_exists( 'wc_get_logger' ) ) {
				$logger  = wc_get_logger();
				$context = array( 'source' => 'affiliate-for-woocommerce' );
				$logger->log( $level, $message, $context );
			} else {
				include_once plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/class-wc-logger.php';
				$logger = new WC_Logger();
				$logger->add( 'affiliate-for-woocommerce', $message );
			}
		}

		/**
		 * Admin menus
		 */
		public function add_afwc_admin_menu() {
			/* translators: A small arrow */
			add_submenu_page( 'woocommerce', __( 'Affiliates Dashboard', 'affiliate-for-woocommerce' ), __( 'Affiliates', 'affiliate-for-woocommerce' ), 'manage_woocommerce', 'affiliate-for-woocommerce', 'AFWC_Admin_Dashboard::afwc_dashboard_page' );

			$get_page = ( ! empty( $_GET['page'] ) ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore

			if ( empty( $get_page ) ) {
				return;
			}

			if ( 'affiliate-for-woocommerce-documentation' === $get_page ) {
				add_submenu_page( 'woocommerce', sprintf( __( 'Getting Started', 'affiliate-for-woocommerce' ), '&rsaquo;' ), __( 'Getting Started', 'affiliate-for-woocommerce' ), 'manage_woocommerce', 'affiliate-for-woocommerce-documentation', 'AFWC_Admin_Docs::afwc_docs' );
			}
			add_submenu_page( 'woocommerce', sprintf( __( 'Affiliate Form Settings', 'affiliate-for-woocommerce' ), '&rsaquo;' ), __( 'Affiliate Form Settings', 'affiliate-for-woocommerce' ), 'manage_woocommerce', 'affiliate-form-settings', 'AFWC_Registration_Form::reg_form_settings' );

		}

		/**
		 * Remove Affiliate For WooCommerce's unnecessary submenus.
		 */
		public function add_afwc_remove_submenu() {
			remove_submenu_page( 'woocommerce', 'affiliate-for-woocommerce-documentation' );
			remove_submenu_page( 'woocommerce', 'affiliate-form-settings' );
		}

		/**
		 * Handle query vars
		 *
		 * @param  mixed $query_vars The query_vars.
		 * @return mixed
		 */
		public function afwc_query_vars( $query_vars ) {
			$afwc_pname = ( defined( 'AFWC_PNAME' ) ) ? AFWC_PNAME : 'ref';
			$pname      = get_option( 'afwc_pname', $afwc_pname );

			$affiliates_pname = ( defined( 'AFFILIATES_PNAME' ) ) ? AFFILIATES_PNAME : 'affiliates';
			$migrated_pname   = get_option( 'afwc_migrated_pname', $affiliates_pname );
			$query_vars[]     = $pname;
			if ( 'ref' !== $pname ) {
				// backward compatibility to support url with 'ref'.
				$query_vars[] = 'ref';
			}
			$query_vars[] = $migrated_pname;
			return $query_vars;
		}

		/**
		 * Function to parse affiliates url & check for valid requests
		 *
		 * @param object $wp The WP object.
		 */
		public function afwc_parse_request( &$wp ) {
			$afwc_pname       = ( defined( 'AFWC_PNAME' ) ) ? AFWC_PNAME : 'ref';
			$pname            = get_option( 'afwc_pname', $afwc_pname );
			$affiliates_pname = ( defined( 'AFFILIATES_PNAME' ) ) ? AFFILIATES_PNAME : 'affiliates';
			$migrated_pname   = get_option( 'afwc_migrated_pname', $affiliates_pname );

			$pname = ( empty( $wp->query_vars[ $pname ] ) && ! empty( $wp->query_vars['ref'] ) ) ? 'ref' : $pname;

			if ( empty( $wp->query_vars[ $pname ] ) ) {
				return;
			}

			// Handle Older affiliates link through migrated pname.
			if ( isset( $wp->query_vars[ $migrated_pname ] ) ) {
				$id           = trim( $wp->query_vars[ $migrated_pname ] );
				$affiliate_id = afwc_get_user_id_based_on_affiliate_id( $id );
			} elseif ( isset( $wp->query_vars[ $pname ] ) ) {
				$affiliate_id = trim( $wp->query_vars[ $pname ] );
			} elseif ( isset( $wp->query_vars['ref'] ) ) {
				$affiliate_id = trim( $wp->query_vars['ref'] );
			} else {
				$affiliate_id = 0;
			}

			// check if affiliate id is non-numeric and if so get the value from user meta.
			if ( ! is_numeric( $affiliate_id ) ) {
				$user         = get_users(
					array(
						'meta_key'    => 'afwc_ref_url_id', // phpcs:ignore
						'meta_value'  => $affiliate_id, // phpcs:ignore
						'number'      => 1,
						'count_total' => false,
						'fields'      => 'ids',
					)
				);
				$affiliate_id = reset( $user );
			}

			if ( 0 !== $affiliate_id ) {
				$this->handle_hit( $affiliate_id );

				unset( $wp->query_vars[ $pname ] ); // we use this to avoid ending up on the blog listing page.
				if ( isset( $_GET ) && isset( $_GET[ $pname ] ) ) { // phpcs:ignore
					$current_url = add_query_arg( $_GET, home_url( $wp->request ) ); // phpcs:ignore
					$current_url = remove_query_arg( $pname, $current_url );
					// note that we must use delimiters other than / as these are used in AFFILIATES_REGEX_PATTERN.
					$current_url = preg_replace( '#' . str_replace( AFWC_PNAME, $pname, AFWC_REGEX_PATTERN ) . '#', '', $current_url );
					$status      = apply_filters( 'affiliates_redirect_status_code', 302 );
					$status      = intval( $status );
					switch ( $status ) {
						case 300:
						case 301:
						case 302:
						case 303:
						case 304:
						case 305:
						case 306:
						case 307:
							break;
						default:
							$status = 302;
					}
					wp_safe_redirect( $current_url, $status );
					exit;
				}
			}
		}

		/**
		 * Handle hits by referral
		 *
		 * @param integer $affiliate_id The affiliate id.
		 */
		public function handle_hit( $affiliate_id = 0 ) {

			if ( ! empty( $affiliate_id ) ) {
				$affiliate = new AFWC_Affiliate( $affiliate_id );
				if ( $affiliate instanceof AFWC_Affiliate && 0 !== $affiliate->ID ) {
					$is_valid_affiliate = $affiliate->is_valid();

					if ( $is_valid_affiliate ) {
						$encoded_id = afwc_encode_affiliate_id( $affiliate_id );
						$days       = get_option( 'afwc_cookie_expiration', 30 );
						if ( $days > 0 ) {
							$expire = time() + AFWC_COOKIE_TIMEOUT_BASE * $days;
						} else {
							$expire = 0;
						}
						setcookie(
							AFWC_AFFILIATES_COOKIE_NAME,
							$encoded_id,
							$expire,
							COOKIEPATH ? COOKIEPATH : '/',
							COOKIE_DOMAIN
						);
						// check for campaign.
						$utm_campaign = ( ! empty( $_REQUEST ) && ! empty( $_REQUEST['utm_campaign'] ) ) ? wc_clean( wp_unslash( $_REQUEST['utm_campaign'] ) ) : '';// phpcs:ignore
						$campaign_id  = ( ! empty( $utm_campaign ) ) ? afwc_get_campaign_id_by_slug( $utm_campaign ) : 0;
						setcookie(
							AFWC_CAMPAIGN_COOKIE_NAME,
							$campaign_id,
							$expire,
							COOKIEPATH ? COOKIEPATH : '/',
							COOKIE_DOMAIN
						);
						$affiliate_api         = AFWC_API::get_instance();
						$params['campaign_id'] = $campaign_id;
						$affiliate_api->track_visitor( $affiliate_id, 0, 'link', $params );
					}
				}
			}

		}

		/**
		 * Get referral type
		 *
		 * @param  integer $affiliate_id The affiliate id.
		 * @param  array   $used_coupons The used coupons.
		 * @return string
		 */
		public function get_referral_type( $affiliate_id = 0, $used_coupons = array() ) {
			if ( ! empty( $affiliate_id ) && ! empty( $used_coupons ) ) {
				$afwc_coupon      = AFWC_Coupon::get_instance();
				$referral_coupons = $afwc_coupon->get_referral_coupon( array( 'user_id' => $affiliate_id ) );
				if ( ! empty( $referral_coupons ) && is_array( $referral_coupons ) ) {
					foreach ( $referral_coupons as $coupon_id => $coupon_code ) {
						$referral_coupon = wc_strtolower( $coupon_code );
						if ( ! empty( $referral_coupon ) && in_array( $referral_coupon, array_map( 'wc_strtolower', $used_coupons ), true ) ) {
							return 'coupon';
						}
					}
				}
			}
			return 'link';
		}

		/**
		 * Handle IPN requests
		 *
		 * Used to save transaction ID of the commission payout
		 *
		 * @param array $posted The posted data.
		 */
		public function handle_ipn_request( $posted = array() ) {

			if ( empty( $posted )
				|| empty( $posted['ipn_track_id'] )
				|| empty( $posted['masspay_txn_id_1'] )
				|| empty( $posted['txn_type'] ) || 'masspay' !== $posted['txn_type']
				|| empty( $posted['unique_id_1'] ) || 'afwc_mass_payment' !== $posted['unique_id_1']
			) {
				return;
			}

			global $wpdb;

			$correlation_id = $posted['ipn_track_id'];
			$transaction_id = $posted['masspay_txn_id_1'];

			$search  = 'CorrelationID:' . $correlation_id;
			$replace = 'TransactionID:' . $transaction_id;

			// phpcs:disable
			$result = $wpdb->query(
									$wpdb->prepare("UPDATE {$wpdb->prefix}afwc_payouts
													SET payout_notes = REPLACE( payout_notes, %s, %s )",
													$search,
													$replace
												)
			);
			// phpcs:enable

		}

		/**
		 * Insert a setting or an array of settings after another specific setting by its ID.
		 *
		 * @since 1.2.1
		 * @param array  $settings                The original list of settings.
		 * @param string $insert_after_setting_id The setting id to insert the new setting after.
		 * @param array  $new_setting             The new setting to insert. Can be a single setting or an array of settings.
		 * @param string $insert_type             The type of insert to perform. Can be 'single_setting' or 'multiple_settings'. Optional. Defaults to a single setting insert.
		 *
		 * @credit: WooCommerce Subscriptions
		 */
		public static function insert_setting_after( &$settings, $insert_after_setting_id, $new_setting, $insert_type = 'single_setting' ) {
			if ( ! is_array( $settings ) ) {
				return;
			}

			$original_settings = $settings;
			$settings          = array();

			foreach ( $original_settings as $setting ) {
				$settings[] = $setting;

				if ( isset( $setting['id'] ) && $insert_after_setting_id === $setting['id'] ) {
					if ( 'single_setting' === $insert_type ) {
						$settings[] = $new_setting;
					} else {
						$settings = array_merge( $settings, $new_setting );
					}
				}
			}
		}

		/**
		 * Generate a unique string
		 *
		 * @param  string $prefix The prefix.
		 * @return string
		 */
		public static function uniqid( $prefix = null ) {
			$uniqid = self::number_to_alphabet( gmdate( 'dmyHis', self::get_offset_timestamp() ) );
			if ( ! empty( $prefix ) ) {
				$uniqid = $prefix . $uniqid;
			}
			return $uniqid;
		}

		/**
		 * Convert number to alphabet
		 *
		 * @param  string $number The number to convert.
		 * @return string
		 */
		public static function number_to_alphabet( $number = null ) {
			if ( ! is_null( $number ) ) {
				$alphabets     = range( 'a', 'z' );
				$absint_number = absint( $number );
				$length        = strlen( $number );
				if ( 2 < $length || 25 < $absint_number ) {
					$numbers = str_split( strval( $number ), 2 );
				} else {
					$numbers = str_split( strval( $number ), 1 );
				}
				$string = '';
				foreach ( $numbers as $num ) {
					if ( ( 1 < strlen( $num ) && 10 > absint( $num ) ) || 25 < absint( $num ) ) {
						$nums = str_split( $num, 1 );
						foreach ( $nums as $_num ) { // This foreach loop will run for maximum 2 iterations.
							$string .= $alphabets[ $_num ];
						}
					} else {
						$string .= $alphabets[ $num ];
					}
				}
				return $string;
			}
			return '';
		}

		/**
		 * Get offset timestamp
		 *
		 * @param  integer $timestamp The timestamp.
		 * @return integer
		 */
		public static function get_offset_timestamp( $timestamp = 0 ) {
			if ( empty( $timestamp ) ) {
				$timestamp = time();
			}
			return $timestamp + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		}

		/**
		 * Get plugins data
		 *
		 * @return array
		 */
		public static function get_plugin_data() {

			if ( ! function_exists( 'get_plugin_data' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return get_plugin_data( AFWC_PLUGIN_FILE );
		}

		/**
		 * Function to get products data for my account page.
		 *
		 * @param array $args arguments.
		 * @return array products data
		 */
		public static function get_my_account_products( $args = array() ) {
			$args['limit'] = apply_filters( 'afwc_my_account_referrals_per_page', ( ! empty( $args['limit'] ) ) ? $args['limit'] : get_option( 'afwc_my_account_referrals_per_page', AFWC_MY_ACCOUNT_DEFAULT_BATCH_LIMIT ) );
			return self::get_products_data( $args );
		}

		/**
		 * Function to get products data
		 *
		 * @param array $args arguments.
		 * @return array $products products data
		 */
		public static function get_products_data( $args = array() ) {
			global $wpdb;

			$from         = ( ! empty( $args['from'] ) ) ? $args['from'] : '';
			$to           = ( ! empty( $args['to'] ) ) ? $args['to'] : '';
			$affiliate_id = ( ! empty( $args['affiliate_id'] ) ) ? $args['affiliate_id'] : 0;

			$args['limit']  = ( ! empty( $args['limit'] ) ) ? $args['limit'] : AFWC_MY_ACCOUNT_DEFAULT_BATCH_LIMIT;
			$args['offset'] = ( ! empty( $args['offset'] ) ) ? $args['offset'] : 0;

			$afwc_excluded_products = get_option( 'afwc_storewide_excluded_products', array() );

			$prefixed_statuses   = afwc_get_prefixed_order_statuses();
			$option_order_status = 'afwc_order_statuses_' . uniqid();
			update_option( $option_order_status, implode( ',', $prefixed_statuses ), 'no' );

			// TODO:: Need to check query for limits and get top products properly.
			if ( ! empty( $from ) && ! empty( $to ) ) {
				$products_result = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT CONCAT(fpid,'_',fvid) as p_vid,
															fpid as pid,
															fvid as vid,
															IFNULL(SUM(fqty), 0) as tot_qty,
															IFNULL(SUM(ftot), 0) as tot_sales
														FROM
														(SELECT 
														CASE WHEN @order_item_id != order_item_id THEN @pid := -1 END,
														CASE WHEN @order_item_id != order_item_id THEN @vid := -1 END,
														CASE WHEN @order_item_id != order_item_id THEN @qty := -1 END,
														CASE WHEN @order_item_id != order_item_id THEN @tot := -1 END,
														@order_item_id := order_item_id as foid,
														@pid := CASE WHEN pid > -1 THEN pid ELSE @pid END as fpid,
														@vid := CASE WHEN vid > -1 THEN vid ELSE @vid END as fvid,
														@qty := CASE WHEN qty > -1 THEN qty ELSE @qty END as fqty,
														@tot := CASE WHEN tot > -1 THEN tot ELSE @tot END as ftot
														FROM(
																SELECT woim.order_item_id as order_item_id,
																IFNULL(CASE WHEN woim.meta_key = '_product_id' THEN woim.meta_value END, -1) as pid,
																IFNULL(CASE WHEN woim.meta_key = '_variation_id' THEN woim.meta_value END, -1) as vid,
																IFNULL(CASE WHEN woim.meta_key = '_line_total' THEN woim.meta_value END, -1) as tot,
																IFNULL(CASE WHEN woim.meta_key = '_qty' THEN woim.meta_value END, -1) as qty
																FROM {$wpdb->prefix}woocommerce_order_items AS woi
																	JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																		ON (woi.order_id = afwcr.post_id
																			AND woi.order_item_type = 'line_item'
																			AND afwcr.affiliate_id = %d AND afwcr.status != %s)
																	JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woim
																		ON(woim.order_item_id = woi.order_item_id
																			AND woim.meta_key IN ('_product_id', '_variation_id', '_line_total', '_qty'))
																WHERE (afwcr.datetime BETWEEN %s AND %s ) AND FIND_IN_SET( afwcr.order_status COLLATE %s, (SELECT option_value COLLATE %s FROM {$wpdb->prefix}options WHERE option_name = %s ))
														) as temp,
														(SELECT @order_item_id := 0,
																@pid := 0,
																@vid := 0,
																@qty := 0,
																@tot := 0
															) as temp_variable
														) as t1
														WHERE fpid > -1 
															AND fvid > -1
															AND fqty > -1
															AND ftot > -1
														GROUP BY p_vid
														ORDER BY tot_sales DESC, tot_qty DESC
														LIMIT %d OFFSET %d",
															$affiliate_id,
															'draft',
															$from,
															$to,
															AFWC_OPTION_NAME_COLLATION,
															AFWC_OPTION_NAME_COLLATION,
															$option_order_status,
															$args['limit'],
															$args['offset']
														),
					'ARRAY_A'
				);
				$products_total_count = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT COUNT( DISTINCT(CONCAT(fpid,'_',fvid))) as prod_count
														FROM
														(SELECT 
														CASE WHEN @order_item_id != order_item_id THEN @pid := -1 END,
														CASE WHEN @order_item_id != order_item_id THEN @vid := -1 END,
														CASE WHEN @order_item_id != order_item_id THEN @qty := -1 END,
														CASE WHEN @order_item_id != order_item_id THEN @tot := -1 END,
														@order_item_id := order_item_id as foid,
														@pid := CASE WHEN pid > -1 THEN pid ELSE @pid END as fpid,
														@vid := CASE WHEN vid > -1 THEN vid ELSE @vid END as fvid,
														@qty := CASE WHEN qty > -1 THEN qty ELSE @qty END as fqty,
														@tot := CASE WHEN tot > -1 THEN tot ELSE @tot END as ftot
														FROM(
																SELECT woim.order_item_id as order_item_id,
																IFNULL(CASE WHEN woim.meta_key = '_product_id' THEN woim.meta_value END, -1) as pid,
																IFNULL(CASE WHEN woim.meta_key = '_variation_id' THEN woim.meta_value END, -1) as vid,
																IFNULL(CASE WHEN woim.meta_key = '_line_total' THEN woim.meta_value END, -1) as tot,
																IFNULL(CASE WHEN woim.meta_key = '_qty' THEN woim.meta_value END, -1) as qty
																FROM {$wpdb->prefix}woocommerce_order_items AS woi
																	JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																		ON (woi.order_id = afwcr.post_id
																			AND woi.order_item_type = 'line_item'
																			AND afwcr.affiliate_id = %d AND afwcr.status != %s)
																	JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woim
																		ON(woim.order_item_id = woi.order_item_id
																			AND woim.meta_key IN ('_product_id', '_variation_id', '_line_total', '_qty'))
																WHERE (afwcr.datetime BETWEEN %s AND %s ) AND FIND_IN_SET( afwcr.order_status COLLATE %s, (SELECT option_value COLLATE %s FROM {$wpdb->prefix}options WHERE option_name = %s ))
														) as temp,
														(SELECT @order_item_id := 0,
																@pid := 0,
																@vid := 0,
																@qty := 0,
																@tot := 0
															) as temp_variable
														) as t1
														WHERE fpid > -1 
															AND fvid > -1
															AND fqty > -1
															AND ftot > -1",
															$affiliate_id,
															'draft',
															$from,
															$to,
															AFWC_OPTION_NAME_COLLATION,
															AFWC_OPTION_NAME_COLLATION,
															$option_order_status
														)
				);
			} else {
				$products_result = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"SELECT CONCAT(fpid,'_',fvid) as p_vid,
						fpid as pid,
						fvid as vid,
						IFNULL(SUM(fqty), 0) as tot_qty,
						IFNULL(SUM(ftot), 0) as tot_sales
					FROM
					(SELECT 
					CASE WHEN @order_item_id != order_item_id THEN @pid := -1 END,
					CASE WHEN @order_item_id != order_item_id THEN @vid := -1 END,
					CASE WHEN @order_item_id != order_item_id THEN @qty := -1 END,
					CASE WHEN @order_item_id != order_item_id THEN @tot := -1 END,
					@order_item_id := order_item_id as foid,
					@pid := CASE WHEN pid > -1 THEN pid ELSE @pid END as fpid,
					@vid := CASE WHEN vid > -1 THEN vid ELSE @vid END as fvid,
					@qty := CASE WHEN qty > -1 THEN qty ELSE @qty END as fqty,
					@tot := CASE WHEN tot > -1 THEN tot ELSE @tot END as ftot
					FROM(
							SELECT woim.order_item_id as order_item_id,
							IFNULL(CASE WHEN woim.meta_key = '_product_id' THEN woim.meta_value END, -1) as pid,
							IFNULL(CASE WHEN woim.meta_key = '_variation_id' THEN woim.meta_value END, -1) as vid,
							IFNULL(CASE WHEN woim.meta_key = '_line_total' THEN woim.meta_value END, -1) as tot,
							IFNULL(CASE WHEN woim.meta_key = '_qty' THEN woim.meta_value END, -1) as qty
							FROM {$wpdb->prefix}woocommerce_order_items AS woi
								JOIN {$wpdb->prefix}afwc_referrals AS afwcr
									ON (woi.order_id = afwcr.post_id
										AND woi.order_item_type = 'line_item'
										AND afwcr.affiliate_id = %d AND afwcr.status != %s)
								JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woim
									ON(woim.order_item_id = woi.order_item_id
										AND woim.meta_key IN ('_product_id', '_variation_id', '_line_total', '_qty'))
							WHERE FIND_IN_SET( afwcr.order_status COLLATE %s, (SELECT option_value COLLATE %s FROM {$wpdb->prefix}options WHERE option_name = %s ))
					) as temp,
					(SELECT @order_item_id := 0,
							@pid := 0,
							@vid := 0,
							@qty := 0,
							@tot := 0
						) as temp_variable
					) as t1
					WHERE fpid > -1 
						AND fvid > -1
						AND fqty > -1
						AND ftot > -1
					GROUP BY p_vid
					ORDER BY tot_sales DESC, tot_qty DESC
					LIMIT %d OFFSET %d",
						$affiliate_id,
						'draft',
						AFWC_OPTION_NAME_COLLATION,
						AFWC_OPTION_NAME_COLLATION,
						$option_order_status,
						$args['limit'],
						$args['offset']
					),
					'ARRAY_A'
				);

				$products_total_count = $wpdb->get_var( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"SELECT COUNT( DISTINCT(CONCAT(fpid,'_',fvid))) as prod_count
						FROM
						(SELECT 
						CASE WHEN @order_item_id != order_item_id THEN @pid := -1 END,
						CASE WHEN @order_item_id != order_item_id THEN @vid := -1 END,
						CASE WHEN @order_item_id != order_item_id THEN @qty := -1 END,
						CASE WHEN @order_item_id != order_item_id THEN @tot := -1 END,
						@order_item_id := order_item_id as foid,
						@pid := CASE WHEN pid > -1 THEN pid ELSE @pid END as fpid,
						@vid := CASE WHEN vid > -1 THEN vid ELSE @vid END as fvid,
						@qty := CASE WHEN qty > -1 THEN qty ELSE @qty END as fqty,
						@tot := CASE WHEN tot > -1 THEN tot ELSE @tot END as ftot
						FROM(
								SELECT woim.order_item_id as order_item_id,
								IFNULL(CASE WHEN woim.meta_key = '_product_id' THEN woim.meta_value END, -1) as pid,
								IFNULL(CASE WHEN woim.meta_key = '_variation_id' THEN woim.meta_value END, -1) as vid,
								IFNULL(CASE WHEN woim.meta_key = '_line_total' THEN woim.meta_value END, -1) as tot,
								IFNULL(CASE WHEN woim.meta_key = '_qty' THEN woim.meta_value END, -1) as qty
								FROM {$wpdb->prefix}woocommerce_order_items AS woi
									JOIN {$wpdb->prefix}afwc_referrals AS afwcr
										ON (woi.order_id = afwcr.post_id
											AND woi.order_item_type = 'line_item'
											AND afwcr.affiliate_id = %d AND afwcr.status != %s)
									JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woim
										ON(woim.order_item_id = woi.order_item_id
											AND woim.meta_key IN ('_product_id', '_variation_id', '_line_total', '_qty'))
								WHERE FIND_IN_SET( afwcr.order_status COLLATE %s, (SELECT option_value COLLATE %s FROM {$wpdb->prefix}options WHERE option_name = %s ))
						) as temp,
						(SELECT @order_item_id := 0,
								@pid := 0,
								@vid := 0,
								@qty := 0,
								@tot := 0
							) as temp_variable
						) as t1
						WHERE fpid > -1 
							AND fvid > -1
							AND fqty > -1
							AND ftot > -1",
						$affiliate_id,
						'draft',
						AFWC_OPTION_NAME_COLLATION,
						AFWC_OPTION_NAME_COLLATION,
						$option_order_status
					)
				);
			}
			$products    = array();
			$product_ids = array();
			if ( ! empty( $products_result ) ) {
				// get the product id name map.
				$product_ids = array_map(
					function( $res ) {
							$product_id = ! empty( $res['vid'] ) ? $res['vid'] : $res['pid'];
							return $product_id;
					},
					$products_result
				);

				$option_prod_ids = 'afwc_prod_ids_' . uniqid();
				update_option( $option_prod_ids, implode( ',', $product_ids ), 'no' );
				$prod_res = $wpdb->get_results(// phpcs:ignore
						$wpdb->prepare( // phpcs:ignore
							"SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE FIND_IN_SET( ID, (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s ))",
							$option_prod_ids
						),
					'ARRAY_A'
				);
				foreach ( $prod_res as $res ) {
					$prod_id_name_map[ $res['ID'] ] = $res['post_title'];
				}
				$rows = array();
				foreach ( $products_result as $result ) {
					if ( in_array( $result['pid'], $afwc_excluded_products, true ) || in_array( $result['vid'], $afwc_excluded_products, true ) ) {
						continue;
					}
					$product_name                        = ( ! empty( $prod_id_name_map[ $result['vid'] ] ) ) ? $prod_id_name_map[ $result['vid'] ] : $prod_id_name_map[ $result['pid'] ];
					$rows[ $result['p_vid'] ]['product'] = $product_name;
					$rows[ $result['p_vid'] ]['qty']     = $result['tot_qty'];
					$rows[ $result['p_vid'] ]['sales']   = $result['tot_sales'];
				}
				$products = array(
					'rows'        => $rows,
					'total_count' => $products_total_count,
				);
				delete_option( $option_prod_ids );
			}

			delete_option( $option_order_status );

			return apply_filters( 'afwc_my_account_products_result', $products, $args );
		}

		/**
		 * Function to get affiliates payout history
		 *
		 * @param array $args arguments.
		 * @return array affiliates payout history
		 */
		public static function get_affiliates_payout_history( $args = array() ) {
			global $wpdb;
			$affiliate_ids = ! empty( $args['affiliate_ids'] ) ? $args['affiliate_ids'] : '';
			$affiliate_ids = ! empty( $args['affiliate_id'] ) ? array( $args['affiliate_id'] ) : $args['affiliate_ids'];
			$from          = ! empty( $args['from'] ) ? $args['from'] : '';
			$to            = ! empty( $args['to'] ) ? $args['to'] : '';
			$start_limit   = ! empty( $args['start_limit'] ) ? $args['start_limit'] : 0;
			$batch_limit   = ! empty( $args['batch_limit'] ) ? $args['batch_limit'] : 5;
			$with_total    = isset( $args['with_total'] ) ? $args['with_total'] : true;

			$affiliates_payout_history = array();

			if ( ! empty( $affiliate_ids ) ) {
				if ( 1 === count( $affiliate_ids ) ) {

					if ( ! empty( $from ) && ! empty( $to ) ) {
						$affiliates_payout_history_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT payouts.payout_id,
                                                                                                            DATE_FORMAT( CONVERT_TZ( payouts.datetime, '+00:00', %s ), %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id = %d
																									AND payouts.datetime BETWEEN %s AND %s 
																								ORDER BY payouts.datetime DESC
																								LIMIT %d,%d",
																					AFWC_TIMEZONE_STR,
																					'%d-%b-%Y',
																					current( $affiliate_ids ),
																					$from . ' 00:00:00',
																					$to . ' 23:59:59',
																					$start_limit,
																					$batch_limit
																				),
							'ARRAY_A'
						);

					} else {
						$affiliates_payout_history_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT payouts.payout_id,
                                                                                                            DATE_FORMAT( CONVERT_TZ( payouts.datetime, '+00:00', %s ), %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id = %d
																								ORDER BY payouts.datetime DESC
																								LIMIT %d,%d",
																					AFWC_TIMEZONE_STR,
																					'%d-%b-%Y',
																					current( $affiliate_ids ),
																					$start_limit,
																					$batch_limit
																				),
							'ARRAY_A'
						);
					}
				} else {

					$option_nm = 'afwc_payout_history_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $affiliate_ids ), 'no' );

					if ( ! empty( $from ) && ! empty( $to ) ) {

						$affiliates_payout_history_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT payouts.payout_id,
                                                                                                            DATE_FORMAT( CONVERT_TZ( payouts.datetime, '+00:00', %s ), %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE FIND_IN_SET ( payouts.affiliate_id, ( SELECT option_value
																												FROM {$wpdb->prefix}options
																												WHERE option_name = %s ) )
																									AND payouts.datetime BETWEEN %s AND %s 
																								ORDER BY payouts.datetime DESC
																								LIMIT %d,%d",
																					AFWC_TIMEZONE_STR,
																					'%d-%b-%Y',
																					$option_nm,
																					$from . ' 00:00:00',
																					$to . ' 23:59:59',
																					$start_limit,
																					$batch_limit
																				),
							'ARRAY_A'
						);
					} else {
						$affiliates_payout_history_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT payouts.payout_id,
	                                                                                                        DATE_FORMAT( CONVERT_TZ( payouts.datetime, '+00:00', %s ), %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE FIND_IN_SET ( payouts.affiliate_id, ( SELECT option_value
																												FROM {$wpdb->prefix}options
																												WHERE option_name = %s ) )
																								ORDER BY payouts.datetime DESC,
																								LIMIT %d,%d",
																					AFWC_TIMEZONE_STR,
																					'%d-%b-%Y',
																					$option_nm,
																					$start_limit,
																					$batch_limit
																				),
							'ARRAY_A'
						);
					}

					delete_option( $option_nm );
				}
			} else {
				if ( ! empty( $from ) && ! empty( $to ) ) {
						$affiliates_payout_history_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT payouts.payout_id,
                                                                                                            DATE_FORMAT( CONVERT_TZ( payouts.datetime, '+00:00', %s ), %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id != %d
																									AND payouts.datetime BETWEEN %s AND %s 
																								ORDER BY payouts.datetime DESC,
																								LIMIT %d,%d",
																					AFWC_TIMEZONE_STR,
																					'%d-%b-%Y',
																					0,
																					$from . ' 00:00:00',
																					$to . ' 23:59:59',
																					$start_limit,
																					$batch_limit
																				),
							'ARRAY_A'
						);
				} else {
						$affiliates_payout_history_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT payouts.payout_id,
                                                                                                            DATE_FORMAT( CONVERT_TZ( payouts.datetime, '+00:00', %s ), %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id != %d
																								ORDER BY payouts.datetime DESC
																								LIMIT %d,%d",
																					AFWC_TIMEZONE_STR,
																					'%d-%b-%Y',
																					0,
																					$start_limit,
																					$batch_limit
																				),
							'ARRAY_A'
						);
				}
			}

			$payout_ids           = array();
			$payout_order_details = array();
			if ( ! empty( $affiliates_payout_history_results ) ) {

				if ( $with_total ) {

					if ( ! empty( $from ) && ! empty( $to ) ) {
						$payout_total_count = $wpdb->get_var( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT COUNT(*)
																			FROM {$wpdb->prefix}afwc_payouts AS afwc_payouts
																			WHERE afwc_payouts.affiliate_id = %d
																				AND (afwc_payouts.datetime BETWEEN %s AND %s )",
																current( $affiliate_ids ),
																$from,
																$to
															)
						);
					} else {
						$payout_total_count = $wpdb->get_var( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT COUNT(*)
																			FROM {$wpdb->prefix}afwc_payouts AS afwc_payouts
																			WHERE afwc_payouts.affiliate_id = %d",
																current( $affiliate_ids )
															)
						);
					}
				}
				foreach ( $affiliates_payout_history_results as $result ) {
					$result['amount']            = $result['amount'];
					$affiliates_payout_history[] = $result;
					$payout_ids[]                = $result['payout_id'];
				}

				if ( ! empty( $payout_ids ) ) {
					$results = $wpdb->get_results( // phpcs:ignore 
												$wpdb->prepare( // phpcs:ignore
													"SELECT po.payout_id,
																		IFNULL( COUNT( po.post_id ), 0 ) AS order_count,
																		DATE_FORMAT( MIN( p.post_date ), '%%d-%%b-%%Y' ) AS from_date,
																		DATE_FORMAT( MAX( p.post_date ), '%%d-%%b-%%Y' ) AS to_date
																FROM {$wpdb->prefix}afwc_payout_orders AS po
																	JOIN {$wpdb->prefix}posts AS p
																		ON(p.ID = po.post_id
																			AND p.post_type = 'shop_order')
																WHERE po.payout_id IN (" . implode( ',', array_fill( 0, count( $payout_ids ), '%d' ) ) . ') 
																GROUP BY po.payout_id',
													$payout_ids
												),
						'ARRAY_A'
					);

					if ( ! empty( $results ) ) {
						foreach ( $results as $detail ) {
							$payout_order_details[ $detail['payout_id'] ] = array(
								'order_count' => $detail['order_count'],
								'from_date'   => $detail['from_date'],
								'to_date'     => $detail['to_date'],
							);
						}
					}

					foreach ( $affiliates_payout_history as $key => $payout ) {
						$affiliates_payout_history[ $key ] = ( ! empty( $payout_order_details[ $payout['payout_id'] ] ) && is_array( $payout_order_details[ $payout['payout_id'] ] ) ) ? array_merge( $affiliates_payout_history[ $key ], $payout_order_details[ $payout['payout_id'] ] ) : $affiliates_payout_history_results[ $key ];
					}
				}
			}

			// Let 3rd party developers to add additional details in payout history.
			$affiliates_payout_history = apply_filters( 'afwc_payout_history', $affiliates_payout_history, $payout_order_details );
			if ( $with_total ) {
				$res['payouts']     = ! empty( $affiliates_payout_history ) ? $affiliates_payout_history : array();
				$res['total_count'] = ! empty( $payout_total_count ) ? $payout_total_count : 0;
				return $res;
			} else {
				return $affiliates_payout_history;
			}
		}


		/**
		 * Function to update referral entry when order is trashed/untrashed/deleted.
		 *
		 * @param int $trashed_order_id Order ID being trashed/untrashed/deleted.
		 */
		public function afwc_update_referral_on_trash_delete_untrash( $trashed_order_id ) {
			global $wpdb;

			if ( empty( $trashed_order_id ) ) {
				return;
			}
			$order = wc_get_order( $trashed_order_id );
			if ( ! $order instanceof WC_Order ) {
				return;
			}

			$current_action = current_action();
			if ( 'delete_post' === $current_action ) {
				$affected_row = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}afwc_referrals WHERE post_id = %d", $trashed_order_id ));// phpcs:ignore
				if ( 1 === $affected_row ) {
					delete_post_meta( $trashed_order_id, 'is_commission_recorded' );
				}
			} elseif ( 'untrashed_post' === $current_action || 'trashed_post' === $current_action ) {
				$afwc_status = ( 'trashed_post' === $current_action ) ? 'deleted' : $order->get_status();
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET order_status = %s WHERE post_id = %d", $afwc_status, $trashed_order_id ) ); // phpcs:ignore
				if ( 'untrashed_post' === $current_action ) {
					update_post_meta( $trashed_order_id, 'is_commission_recorded', 'yes' );
				}
			}

		}


		/**
		 * Get registration for settings
		 */
		public function save_afwc_reg_form_settings() {
			$form_fields = array();
			$form_fields = array(
				'afwc_reg_email'            => array(
					'type'     => 'email',
					'required' => 'required',
					'show'     => true,
					'label'    => __( 'Email', 'affiliate-for-woocommerce' ),
				),
				'afwc_reg_first_name'       => array(
					'type'     => 'text',
					'required' => '',
					'show'     => true,
					'label'    => __( 'First Name', 'affiliate-for-woocommerce' ),
					'class'    => 'afwc_is_half',
				),
				'afwc_reg_last_name'        => array(
					'type'     => 'text',
					'required' => '',
					'show'     => true,
					'label'    => __( 'Last Name', 'affiliate-for-woocommerce' ),
					'class'    => 'afwc_is_half',
				),
				'afwc_reg_contact'          => array(
					'type'     => 'text',
					'required' => '',
					'show'     => true,
					'label'    => __( 'Phone Number / Skype ID / Best method to talk to you', 'affiliate-for-woocommerce' ),
				),
				'afwc_reg_website'          => array(
					'type'     => 'text',
					'required' => '',
					'show'     => true,
					'label'    => __( 'Website', 'affiliate-for-woocommerce' ),
				),
				'afwc_reg_password'         => array(
					'type'     => 'password',
					'required' => 'required',
					'show'     => true,
					'label'    => __( 'Password', 'affiliate-for-woocommerce' ),
					'class'    => 'afwc_is_half',
				),
				'afwc_reg_confirm_password' => array(
					'type'     => 'password',
					'required' => 'required',
					'show'     => true,
					'label'    => __( 'Confirm Password', 'affiliate-for-woocommerce' ),
					'class'    => 'afwc_is_half',
				),
				'afwc_reg_desc'             => array(
					'type'     => 'textarea',
					'required' => 'required',
					'show'     => true,
					'label'    => __( 'Tell us more about yourself and why you\'d like to partner with us (please include your social media handles, experience promoting others, tell us about your audience etc)', 'affiliate-for-woocommerce' ),
				),
				'afwc_reg_terms'            => array(
					'type'     => 'checkbox',
					'required' => 'required',
					'show'     => true,
					'label'    => __( 'I accept all the terms of this program', 'affiliate-for-woocommerce' ),
				),

			);
			$form_fields = apply_filters( 'afwc_registration_form_fields', $form_fields );
			add_option( 'afwc_form_fields', $form_fields );
		}

		/**
		 * Get affiliate users.
		 *
		 * @param string $params Arguments of WP_User_Query.
		 * @return array
		 */
		public function get_affiliates( $params = array() ) {

			$args = array_merge(
				$params,
				array(
					'meta_key'   => 'afwc_is_affiliate', // phpcs:ignore
					'meta_value' => 'yes', // phpcs:ignore
				)
			);

			$affiliate_users = get_users( $args );
			// Get assigned affiliate roles.
			$affiliate_user_roles = get_option( 'affiliate_users_roles', '' );

			if ( ! empty( $affiliate_user_roles ) ) {
				$args = array_merge(
					$params,
					array(
						'role__in' => $affiliate_user_roles,
					)
				);
				// Get users by assigned affiliate user roles.
				$affiliate_role_users = get_users( $args );
				if ( ! empty( $affiliate_role_users ) ) {
					// Merge users of affiliate and users in affiliate user role.
					$affiliate_users = array_merge( $affiliate_users, $affiliate_role_users );
				}
			}

			$users = array();
			if ( ! empty( $affiliate_users ) ) {
				foreach ( $affiliate_users as $user ) {
					$user_data = ! empty( $user->data ) ? $user->data : null;
					if ( ! empty( $user_data ) && isset( $user_data->ID ) && isset( $user_data->user_email ) ) {
						$users[ $user_data->ID ] = sprintf(
							'%1$s (#%2$d &ndash; %3$s)',
							isset( $user_data->display_name ) ? $user_data->display_name : '',
							absint( $user_data->ID ),
							$user_data->user_email
						);
					}
				}
			}

			return apply_filters( 'afwc_get_affiliates', $users );
		}

		/**
		 * Get affiliate parents for commissions.
		 *
		 * @param int $user_id User id.
		 * @return array.
		 */
		public function afwc_get_parents_for_commissions( $user_id = 0 ) {
			global $wpdb;
			$parents = afwc_get_parent_chain( $user_id );
			if ( empty( $parents ) ) {
				return array();
			}

			$args = array(
				'include' => $parents,
			);

			$af_parents = $this->get_affiliates( $args );

			if ( empty( $af_parents ) ) {
				return array();
			}

			$af_parents             = is_array( $af_parents ) ? array_filter( array_keys( $af_parents ), 'intval' ) : array();
			$parents_for_commisions = array();

			foreach ( $parents as $parent ) {
				// Break if the parents are not in parent chain.
				if ( ! in_array( intval( $parent ), $af_parents, true ) ) {
					break;
				}
				$parents_for_commisions[] = $parent;
			}

			return ! empty( $parents_for_commisions ) ? $parents_for_commisions : array();
		}

		/**
		 * Function to get template base directory for Affiliate For WooCommerce' templates
		 *
		 * @param  string $template_name Template name.
		 * @return string $template_base_dir Base directory for Affiliate For WooCommerce' templates.
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

		/**
		 * Set payout method if not exist.
		 * To set method on plugin upgrade/activation for commission payouts
		 *
		 * @return void.
		 */
		public function set_payout_method() {
			if ( 'no' === get_option( 'afwc_is_set_commission_payout_method', 'no' ) ) {
				$afwc_paypal = is_callable( array( 'AFWC_PayPal_API', 'get_instance' ) ) ? AFWC_PayPal_API::get_instance() : null;

				if ( ! empty( $afwc_paypal ) && is_callable( array( $afwc_paypal, 'get_payout_method' ) ) ) {
					$afwc_paypal->get_payout_method( true );
				}

				update_option( 'afwc_is_set_commission_payout_method', 'yes', 'no' );
			} elseif ( empty( get_option( 'afwc_commission_payout_method' ) ) ) {
				$afwc_paypal = is_callable( array( 'AFWC_PayPal_API', 'get_instance' ) ) ? AFWC_PayPal_API::get_instance() : null;

				if ( ! empty( $afwc_paypal ) && is_callable( array( $afwc_paypal, 'check_for_paypal_payout' ) ) ) {
					$afwc_paypal->check_for_paypal_payout();
				}
			}
		}

	}

}
