<?php
/**
 * Main class for Affiliate For WooCommerce
 *
 * @since       1.0.0
 * @version     1.4.2
 *
 * @package     affiliate-for-woocommerce/includes/
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
			add_action( 'init', array( $this, 'afwc_register_user_tags_taxonomy' ) );
			$this->includes();

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_afwc_admin_menu' ), 20 );
				add_action( 'admin_head', array( $this, 'add_afwc_remove_submenu' ) );
			}

			add_filter( 'query_vars', array( $this, 'afwc_query_vars' ), 10000 );
			add_action( 'parse_request', array( $this, 'afwc_parse_request' ) );

			add_action( 'valid-paypal-standard-ipn-request', array( $this, 'handle_ipn_request' ) );

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

			$afwc_currency_symbol = get_woocommerce_currency_symbol();

			define( 'AFWC_AFFILIATES_COOKIE_NAME', 'affiliate_for_woocommerce' );
			define( 'AFWC_TABLE_PREFIX', 'afwc_' );

			define( 'AFWC_PLUGIN_BASENAME', plugin_basename( AFWC_PLUGIN_FILE ) );
			define( 'AFWC_PLUGIN_DIR', dirname( plugin_basename( AFWC_PLUGIN_FILE ) ) );
			define( 'AFWC_PLUGIN_URL', plugins_url( AFWC_PLUGIN_DIR ) );

			define( 'AFWC_PNAME', get_option( 'afwc_pname' ) );

			define( 'AFWC_COOKIE_TIMEOUT_BASE', 86400 );
			define( 'AFWC_REGEX_PATTERN', 'affiliates/([^/]+)/?$' );

			define( 'AFWC_CURRENCY', $afwc_currency_symbol );
			define( 'AFWC_DEFAULT_COMMISSION_STATUS', get_option( 'afwc_default_commission_status' ) );
			define( 'AFWC_AJAX_SECURITY', 'affiliate_for_woocommerce_ajax_call' );

			define( 'AFWC_REFERRAL_STATUS_PENDING', 'pending' );
			define( 'AFWC_REFERRAL_STATUS_PAID', 'paid' );
			define( 'AFWC_REFERRAL_STATUS_UNPAID', 'unpaid' );
			define( 'AFWC_REFERRAL_STATUS_REJECTED', 'rejected' );

		}

		/**
		 * Function to register affiliate tags taxonomy
		 */
		public function afwc_register_user_tags_taxonomy() {
			register_taxonomy(
				'afwc_user_tags', // taxonomy name.
				'user', // object for which the taxonomy is created.
				array( // taxonomy details.
					'public'                => true,
					'labels'                => array(
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
					'show_in_menu'          => false,
					'update_count_callback' => function() {
						return;
					},
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

			if ( afwc_is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
				include_once 'integration/woocommerce/compat/class-wcs-afwc-compatibility.php';
			}
			include_once 'gateway/paypal/class-afwc-paypal.php';
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
				add_submenu_page( 'woocommerce', sprintf( __( 'Docs & Support', 'affiliate-for-woocommerce' ), '&rsaquo;' ), __( 'Docs & Support', 'affiliate-for-woocommerce' ), 'manage_woocommerce', 'affiliate-for-woocommerce-documentation', 'AFWC_Admin_Docs::afwc_docs' );
			}
		}

		/**
		 * Remove Affiliate For WooCommerce's unnecessary submenus.
		 */
		public function add_afwc_remove_submenu() {
			remove_submenu_page( 'woocommerce', 'affiliate-for-woocommerce-documentation' );
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
			$afwc_pname = ( defined( 'AFWC_PNAME' ) ) ? AFWC_PNAME : 'ref';
			$pname      = get_option( 'afwc_pname', $afwc_pname );

			$affiliates_pname = ( defined( 'AFFILIATES_PNAME' ) ) ? AFFILIATES_PNAME : 'affiliates';
			$migrated_pname   = get_option( 'afwc_migrated_pname', $affiliates_pname );

			$pname = ( empty( $wp->query_vars[ $pname ] ) && ! empty( $wp->query_vars['ref'] ) ) ? 'ref' : $pname;

			if ( empty( $wp->query_vars[ $pname ] ) ) {
				return;
			}

			// Handle Older affiliates link through migrated pname.
			if ( isset( $wp->query_vars[ $migrated_pname ] ) ) {
				$id           = trim( $wp->query_vars[ $migrated_pname ] );
				$affiliate_id = get_user_id_based_on_affiliate_id( $id );
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
						$campaign_id  = ( ! empty( $utm_campaign ) ) ? get_campaign_id_by_slug( $utm_campaign ) : 0;
						setcookie(
							'afwc_campaign',
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
			$limit        = apply_filters( 'afwc_my_account_referrals_per_page', get_option( 'afwc_my_account_referrals_per_page', 5 ) );
			$offset       = ( ! empty( $args['offset'] ) ) ? $args['offset'] : 0;

			$args['limit']  = ( ! empty( $args['limit'] ) ) ? $args['limit'] : $limit;
			$args['offset'] = $offset;

			$num_row = count(
				array(
					'_product_id',
					'_variation_id',
					'_qty',
					'_line_total',
				)
			);

			$limit  *= $num_row;
			$offset *= $num_row;

			if ( ! empty( $from ) && ! empty( $to ) ) {

				$products_result = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT woim.order_item_id,
																				woim.meta_key,
																				woim.meta_value
																		FROM {$wpdb->prefix}woocommerce_order_items AS woi
																			JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																				ON (woi.order_id = afwcr.post_id
																					AND woi.order_item_type = %s)
																			JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim
																				ON (woim.order_item_id = woi.order_item_id )
																		WHERE afwcr.affiliate_id = %d
																			AND woim.meta_key IN (%s,%s,%s,%s)
																			AND (afwcr.datetime BETWEEN %s AND %s)
																		LIMIT %d OFFSET %d",
															'line_item',
															$affiliate_id,
															'_product_id',
															'_variation_id',
															'_qty',
															'_line_total',
															$from,
															$to,
															$limit,
															$offset
														),
					'ARRAY_A'
				);

				$products_total_count = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT COUNT(*)
																		FROM {$wpdb->prefix}woocommerce_order_items AS woi
																			JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																				ON (woi.order_id = afwcr.post_id
																					AND woi.order_item_type = %s)
																			JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim
																				ON (woim.order_item_id = woi.order_item_id )
																		WHERE afwcr.affiliate_id = %d
																			AND woim.meta_key IN (%s,%s,%s,%s)
																			AND (afwcr.datetime BETWEEN %s AND %s)",
															'line_item',
															$affiliate_id,
															'_product_id',
															'_variation_id',
															'_qty',
															'_line_total',
															$from,
															$to
														)
				);
			} else {

				$products_result = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT woim.order_item_id,
																			woim.meta_key,
																			woim.meta_value
																	FROM {$wpdb->prefix}woocommerce_order_items AS woi
																		JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																			ON (woi.order_id = afwcr.post_id
																				AND woi.order_item_type = %s)
																		JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim
																			ON (woim.order_item_id = woi.order_item_id )
																	WHERE afwcr.affiliate_id = %d
																		AND woim.meta_key IN (%s,%s,%s,%s)
																	LIMIT %d OFFSET %d",
														'line_item',
														$affiliate_id,
														'_product_id',
														'_variation_id',
														'_qty',
														'_line_total',
														$limit,
														$offset
													),
					'ARRAY_A'
				);

				$products_total_count = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT COUNT(*)
																		FROM {$wpdb->prefix}woocommerce_order_items AS woi
																			JOIN {$wpdb->prefix}afwc_referrals AS afwcr
																				ON (woi.order_id = afwcr.post_id
																					AND woi.order_item_type = %s)
																			JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim
																				ON (woim.order_item_id = woi.order_item_id )
																		WHERE afwcr.affiliate_id = %d
																			AND woim.meta_key IN (%s,%s,%s,%s)",
															'line_item',
															$affiliate_id,
															'_product_id',
															'_variation_id',
															'_qty',
															'_line_total'
														)
				);
			}
			$products = array();
			if ( ! empty( $products_result ) ) {
				$rows = array();
				foreach ( $products_result as $result ) {
					if ( empty( $rows[ $result['order_item_id'] ] ) || ! is_array( $rows[ $result['order_item_id'] ] ) ) {
						$rows[ $result['order_item_id'] ] = array();
					}
					$rows[ $result['order_item_id'] ][ $result['meta_key'] ] = $result['meta_value']; // phpcs:ignore
				}
				if ( ! empty( $rows ) ) {
					$_rows = array();
					foreach ( $rows as $item_id => $item ) {
						$index = $item['_product_id'] . '_' . $item['_variation_id'];
						if ( empty( $_rows[ $index ] ) || ! is_array( $_rows[ $index ] ) ) {
							$_rows[ $index ] = array();
						}
						if ( empty( $_rows[ $index ]['product'] ) ) {
							$product_name = get_the_title( $item['_product_id'] );
							if ( ! empty( $item['_variation_id'] ) ) {
								$product_name = get_the_title( $item['_variation_id'] );
							}
							$_rows[ $index ]['product'] = $product_name;
						}
						if ( ! empty( $_rows[ $index ]['qty'] ) ) {
							$_rows[ $index ]['qty'] += $item['_qty'];
						} else {
							$_rows[ $index ]['qty'] = $item['_qty'];
						}
						if ( ! empty( $_rows[ $index ]['sales'] ) ) {
							$_rows[ $index ]['sales'] += $item['_line_total'];
						} else {
							$_rows[ $index ]['sales'] = $item['_line_total'];
						}
					}
					$sorted_rows = wp_list_sort( $_rows, 'sales', 'DESC', true );
				}

				$products = array(
					'rows'        => $sorted_rows,
					'total_count' => $products_total_count,
				);
			}
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
                                                                                                            DATE_FORMAT( payouts.datetime, %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id = %d
																									AND payouts.datetime BETWEEN %s AND %s
																								ORDER BY payouts.datetime DESC
																								LIMIT %d,%d",
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
                                                                                                            DATE_FORMAT( payouts.datetime, %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id = %d
																								ORDER BY payouts.datetime DESC
																								LIMIT %d,%d",
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
                                                                                                            DATE_FORMAT( payouts.datetime, %s ) as datetime,
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
	                                                                                                        DATE_FORMAT( payouts.datetime, %s ) as datetime,
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
                                                                                                            DATE_FORMAT( payouts.datetime, %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id != %d
																									AND payouts.datetime BETWEEN %s AND %s
																								ORDER BY payouts.datetime DESC,
																								LIMIT %d,%d",
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
                                                                                                            DATE_FORMAT( payouts.datetime, %s ) as datetime,
																											payouts.amount AS amount,
																											payouts.currency AS currency,
																											payouts.payment_gateway AS method,
																											payouts.payout_notes
																								FROM {$wpdb->prefix}afwc_payouts AS payouts
																								WHERE payouts.affiliate_id != %d
																								ORDER BY payouts.datetime DESC
																								LIMIT %d,%d",
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
																				AND (afwc_payouts.datetime BETWEEN %s AND %s)",
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
								'currency'    => ( ! empty( $detail['currency'] ) ) ? html_entity_decode( get_woocommerce_currency_symbol( $detail['currency'] ) ) : get_woocommerce_currency(),
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



	}

}
