<?php
/**
 * Main class for Affiliates Dashboard
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @version     1.5.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Dashboard' ) ) {

	/**
	 * Main class for Affiliates Dashboard
	 */
	class AFWC_Admin_Dashboard {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_dashboard_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_dashboard_styles' ) );
			add_action( 'wp_ajax_afwc_dashboard_controller', array( $this, 'request_handler' ) );
			add_action( 'admin_print_scripts', array( $this, 'remove_admin_notices' ) );
		}

		/**
		 * Function to remove admin notices from affiliate dashboard page.
		 */
		public function remove_admin_notices() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			if ( strpos( $screen_id, '_affiliate-for-woocommerce' ) !== false ) {
				remove_all_actions( 'admin_notices' );
			}
		}

		/**
		 * Function to register required scripts for admin dashboard.
		 */
		public function register_admin_dashboard_scripts() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			if ( strpos( $screen_id, '_affiliate-for-woocommerce' ) !== false ) {
				$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
				$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				// Dashboard scripts.
				wp_register_script( 'mithril', AFWC_PLUGIN_URL . '/assets/js/mithril/mithril.min.js', array(), $plugin_data['Version'], true );
				wp_register_script( 'afwc-admin-dashboard-styles', AFWC_PLUGIN_URL . '/assets/js/styles.js', array( 'mithril' ), $plugin_data['Version'], true );
				wp_register_script( 'afwc-admin-dashboard', AFWC_PLUGIN_URL . '/assets/js/admin.js', array( 'afwc-admin-dashboard-styles' ), $plugin_data['Version'], true );
				if ( ! wp_script_is( 'selectWoo', 'registered' ) ) {
					wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );
				}
				wp_enqueue_script( 'selectWoo' );
				wp_enqueue_editor();
				wp_enqueue_media();
			}
		}

		/**
		 * Function to register required styles for admin dashboard.
		 */
		public function register_admin_dashboard_styles() {
			$plugin_data = Affiliate_For_WooCommerce::get_plugin_data();
			$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_register_style( 'tailwind', AFWC_PLUGIN_URL . '/assets/css/styles.css', array(), $plugin_data['Version'] );
			wp_register_style( 'afwc-admin-dashboard-css', AFWC_PLUGIN_URL . '/assets/css/afwc-admin-dashboard.css', array(), $plugin_data['Version'] );
			wp_enqueue_style( 'selectWoo', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION );
		}

		/**
		 * Function to show admin dashboard.
		 */
		public static function afwc_dashboard_page() {

			if ( ! wp_script_is( 'afwc-admin-dashboard' ) ) {
				wp_enqueue_script( 'afwc-admin-dashboard' );
			}

			if ( ! wp_style_is( 'tailwind' ) ) {
				wp_enqueue_style( 'tailwind' );
			}

			if ( ! wp_style_is( 'afwc-admin-dashboard-css' ) ) {
				wp_enqueue_style( 'afwc-admin-dashboard-css' );
			}

			if ( ! wp_script_is( 'selectWoo' ) ) {
				wp_enqueue_script( 'selectWoo' );
			}

			$settings_link = add_query_arg(
				array(
					'page' => 'wc-settings',
					'tab'  => 'affiliate-for-woocommerce-settings',
				),
				admin_url( 'admin.php' )
			);

			$paypal            = AFWC_Paypal::get_instance();
			$status            = $paypal->get_api_setting_status();
			$is_paypal_enabled = ( ! empty( $status['value'] ) && 'yes' === $status['value'] ) ? true : false;

			$afwc_filters                                = array();
			$afwc_filters['affiliate_status']['pending'] = __( 'Awaiting Approval', 'affiliate-for-woocommerce' );
			$afwc_filters['affiliate_status']['yes']     = __( 'Active', 'affiliate-for-woocommerce' );
			$afwc_filters['affiliate_status']['no']      = __( 'Rejected', 'affiliate-for-woocommerce' );

			$afwc_filters['order_status']['unpaid']   = __( 'Unpaid', 'affiliate-for-woocommerce' );
			$afwc_filters['order_status']['paid']     = __( 'Paid', 'affiliate-for-woocommerce' );
			$afwc_filters['order_status']['rejected'] = __( 'Rejected', 'affiliate-for-woocommerce' );

			$afwc_filters['tags']                      = get_afwc_user_tags_id_name_map(); // TODO:: get top 10 tags and pass.
			$afwc_filters['tags']                      = ( ! empty( $afwc_filters['tags'] ) ) ? array_slice( $afwc_filters['tags'], 0, 10, true ) : $afwc_filters['tags'];
			$afwc_filters['date_filter']['this_month'] = __( 'This Month', 'affiliate-for-woocommerce' );
			$afwc_filters['date_filter']['last_month'] = __( 'Last Month', 'affiliate-for-woocommerce' );
			$afwc_filters['date_filter']['this_year']  = __( 'This Year', 'affiliate-for-woocommerce' );
			$plan_dashboard_data                       = array();
			$registry                                  = AFWC_Registry::get_registry();

			$commission_rules = ! empty( $registry['rule'] ) ? $registry['rule'] : array();
			$plan_data        = array();
			foreach ( $commission_rules as $context_key => $class_name ) {
				$props                     = array();
				$class_obj                 = new $class_name( $props );
				$plan                      = array();
				$category                  = $class_obj->get_category();
				$plan['category']          = $category;
				$plan['possibleOperators'] = $class_obj->get_possible_operators();
				if ( empty( $plan_data[ $category ] ) ) {
					$plan_data[ $category ] = array();
				}
				$plan_data[ $category ][ $context_key ] = $plan;

			}

			$plan_dashboard_data['plan_rule_data']                   = $plan_data;
			$plan_dashboard_data['apply_to']['all']                  = __( 'all matching products in the order', 'affiliate-for-woocommerce' );
			$plan_dashboard_data['apply_to']['first']                = __( 'only the first matching product', 'affiliate-for-woocommerce' );
			$plan_dashboard_data['action_for_remaining']['continue'] = __( 'continue matching commission plans', 'affiliate-for-woocommerce' );
			$plan_dashboard_data['action_for_remaining']['default']  = __( 'use default commission', 'affiliate-for-woocommerce' );
			$plan_dashboard_data['action_for_remaining']['zero']     = __( 'apply zero commission', 'affiliate-for-woocommerce' );

			$can_ask_for_feedback = self::show_feedback();

			$review_link = 'https://woocommerce.com/products/affiliate-for-woocommerce/#comments';
			wp_localize_script(
				'afwc-admin-dashboard',
				'afwcDashboardParams',
				array(
					'security'             => wp_create_nonce( AFWC_AJAX_SECURITY ),
					'settingsLink'         => $settings_link,
					'currencySymbol'       => AFWC_CURRENCY,
					'isPayPalEnabled'      => $is_paypal_enabled,
					'ajaxurl'              => admin_url( 'admin-ajax.php' ),
					'home_url'             => home_url(),
					'afwc_filters'         => $afwc_filters,
					'plan_dashboard_data'  => $plan_dashboard_data,
					'can_ask_for_feedback' => $can_ask_for_feedback,
					'review_link'          => $review_link,
				)
			);

			?>
				<style type="text/css">
					#wpcontent { 
						padding-left: 0 !important;
					}
				</style>
				<div id="afw-admin-dasboard" class="afw-admin-dasboard"></div>
			<?php
		}

		/**
		 * Function to show feedback
		 */
		public static function show_feedback() {
			$feedback_option_review = get_option( 'afwc_feedback_option_review', false );
			if ( ! empty( $feedback_option_review ) ) {
				return false;
			}
			$current_date         = gmdate( 'Y-m-d H:i:s', Affiliate_For_WooCommerce::get_offset_timestamp() );
			$feedback_start_date  = get_option( 'afwc_feedback_start_date', false );
			$feedback_close_date  = get_option( 'afwc_feedback_close_date', false );
			$diff                 = ceil( abs( strtotime( $current_date ) - strtotime( $feedback_start_date ) ) / 86400 );
			$can_ask_for_feedback = ( $diff > 15 ) ? true : false;
			$close_diff           = ! empty( $feedback_close_date ) ? ceil( abs( strtotime( $feedback_close_date ) - strtotime( $current_date ) ) / 86400 ) : 0;
			$can_ask_for_feedback = ( ! empty( $close_diff ) && $close_diff < 15 ) ? false : $can_ask_for_feedback;
			return $can_ask_for_feedback;
		}

		/**
		 * Function to handle all ajax request
		 */
		public function request_handler() {

			if ( empty( $_REQUEST ) || empty( $_REQUEST['cmd'] ) ) {
				return;
			}

			check_ajax_referer( AFWC_AJAX_SECURITY, 'security' );

			$params = array_map(
				function ( $request_param ) {
					return trim( wc_clean( wp_unslash( $request_param ) ) );
				},
				$_REQUEST
			);

			$func_nm = $params['cmd'];

			// To fix the delay in admin dashboard issue.
			$params['from_date'] = isset( $params['from_date'] ) ? $params['from_date'] . ' 00:00:00' : '';
			$params['to_date']   = isset( $params['to_date'] ) ? $params['to_date'] . ' 23:59:59' : '';

			$params['from'] = gmdate( 'Y-m-d', Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( ( ! empty( $params['from_date'] ) ) ? $params['from_date'] : '' ) ) );
			$params['to']   = gmdate( 'Y-m-d', Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( ( ! empty( $params['to_date'] ) ) ? $params['to_date'] : '' ) ) );

			$params['affiliate_id'] = isset( $params['affiliate_id'] ) ? $params['affiliate_id'] : ''; // phpcs:ignore
			$params['page'] = isset( $params['page'] ) ? $params['page'] : 1; // phpcs:ignore

			if ( is_callable( array( $this, $func_nm ) ) ) {
				$this->$func_nm( $params );
			}
		}

		/**
		 * Function to change commission status
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function update_commission_status( $params = array() ) {

			if ( empty( $params['order_ids'] ) || empty( $params['status'] ) ) {
				wp_send_json(
					array(
						'ACK'   => 'Error',
						'error' => __( 'Required params missing', 'affiliate-for-woocommerce' ),
					)
				);
			}

			global $wpdb;

			$current_user_id = get_current_user_id();
			if ( 0 !== $current_user_id ) {

				$temp_db_key = 'afwc_change_commission_status_order_ids_' . $current_user_id;

				// Store order ids temporarily in table.
				update_option( $temp_db_key, implode( ',', json_decode( $params['order_ids'], true ) ), 'no' );

				$records = $wpdb->query( // phpcs:ignore
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}afwc_referrals SET status = %s WHERE FIND_IN_SET ( post_id, ( SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s ) )", // phpcs:ignore
						$params['status'],
						$temp_db_key
					)
				); // phpcs:ignore

				delete_option( $temp_db_key );

				if ( false === $records ) {
					wp_send_json( array( 'error' => __( 'Query failed.', 'affiliate-for-woocommerce' ) ) );
				} else {
					// translators: Number of records updated in referrals table.
					wp_send_json(
						array(
							'ACK'     => 'Success',
							'message' => sprintf( // translators: Number of records updated in referrals table.
								__( '%d records updated.', 'affiliate-for-woocommerce' ),
								$records
							),
						)
					);
				}
			}
		}

		/**
		 * Handler for AJAX request for processing affiliate payouts
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function process_payout( $params = array() ) {

			if ( empty( $params['affiliates'] ) || empty( $params['selected_orders'] ) || empty( $params['method'] ) ) {
				wp_send_json(
					array(
						'ACK'   => 'Error',
						'error' => __( 'Required params missing', 'affiliate-for-woocommerce' ),
					)
				);
			}

			global $wpdb;

			$store_currency  = get_woocommerce_currency();
			$affiliates      = ( ! empty( $params['affiliates'] ) ) ? json_decode( $params['affiliates'], true ) : array();
			$selected_orders = ( ! empty( $params['selected_orders'] ) ) ? json_decode( $params['selected_orders'], true ) : array();
			$note            = ( ! empty( $params['note'] ) ) ? $params['note'] : '';
			$currency        = ( ! empty( $params['currency'] ) ) ? get_woocommerce_currency( $params['currency'] ) : '';

			// For now, only checking for 1st Affiliate, Multiple Affiliates Payout is not yet implemented.
			if ( 'paypal' === $params['method'] && ! empty( $affiliates[0]['email'] ) && ! empty( $affiliates[0]['amount'] ) ) {
				$paypal                = AFWC_Paypal::get_instance();
				$affiliates[0]['note'] = $note;
				$currency              = in_array( get_woocommerce_currency( $params['currency'] ), AFWC_Paypal::$paypal_supported_currency, true ) ? get_woocommerce_currency( $params['currency'] ) : $store_currency;
				$result                = $paypal->process_paypal_mass_payment( $affiliates, $currency );

				if ( 'Success' !== $result['ACK'] ) {
					/* translators: PayPal response message */
					Affiliate_For_WooCommerce::get_instance()->log( 'error', sprintf( __( 'PayPal payout failed. Response: %s.', 'affiliate-for-woocommerce' ), print_r( $result, true ) ) ); // phpcs:ignore

					wp_send_json(
						array(
							'ACK'   => 'Error',
							'error' => __( 'PayPal payout failed', 'affiliate-for-woocommerce' ),
						)
					);
				}
			}

			// Code for updating status in db.
			$order_ids = array_map(
				function( $obj ) {
					if ( ! empty( $obj['order_id'] ) ) {
						return $obj['order_id'];
					}
				},
				$selected_orders
			);

			$current_user_id = get_current_user_id();
			if ( 0 !== $current_user_id ) {
				$temp_db_key = 'afwc_make_payment_order_ids_' . $current_user_id;

				// Store order ids temporarily in table.
				update_option( $temp_db_key, implode( ',', $order_ids ), 'no' );

				$wpdb->query( // phpcs:ignore
							$wpdb->prepare(  // phpcs:ignore
								"UPDATE {$wpdb->prefix}afwc_referrals
											SET status = %s
											WHERE FIND_IN_SET ( post_id, ( SELECT option_value
																			FROM {$wpdb->prefix}options
																			WHERE option_name = %s ) )",
								AFWC_REFERRAL_STATUS_PAID,
								$temp_db_key
							)
						); // phpcs:ignore

				delete_option( $temp_db_key );

				// Code for updating the payouts table.
				$payout_details = array(
					'affiliate_id'    => $affiliates[0]['id'],
					'datetime'        => gmdate( 'Y-m-d H:i:s', Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( ! empty( $params['date'] ) ? $params['date'] : '' ) ) ),
					'amount'          => floatval( ( ! empty( $affiliates[0]['amount'] ) ) ? $affiliates[0]['amount'] : 0.00 ),
					'currency'        => $currency,
					'payout_notes'    => $note,
					'payment_gateway' => ( ! empty( $params['method'] ) ) ? $params['method'] : 'other',
					'receiver'        => ( ! empty( $affiliates[0]['email'] ) ) ? $affiliates[0]['email'] : '',
					'type'            => '',
				);

				$records = $wpdb->query( // phpcs:ignore
										$wpdb->prepare( // phpcs:ignore
											"INSERT INTO {$wpdb->prefix}afwc_payouts(`affiliate_id`, `datetime`, `amount`,  `currency`, `payout_notes`, `payment_gateway`, `receiver`, `type`)
														VALUES(%d, %s, %f, %s, %s, %s, %s, %s)",
											$payout_details
										)
				);

				if ( false === $records ) {
					wp_send_json(
						array(
							'ACK'   => 'Error',
							'error' => __( 'Payout entry failed', 'affiliate-for-woocommerce' ),
						)
					);
				} else {
					$inserted_payout_id = $wpdb->insert_id;

					// Code to update the payout_orders table.
					$values               = array();
					$selected_order_dates = array(
						'from' => '',
						'to'   => '',
					);

					$payout_orders_table = get_afwc_tablename( 'payout_orders' );
					foreach ( $selected_orders as $order ) {
						if ( empty( $selected_order_dates['from'] ) ) {
							$selected_order_dates['from'] = $order['date'];
						} elseif ( strtotime( $selected_order_dates['from'] ) > strtotime( $order['date'] ) ) {
							$selected_order_dates['from'] = $order['date'];
						}

						if ( empty( $selected_order_dates['to'] ) ) {
							$selected_order_dates['to'] = $order['date'];
						} elseif ( strtotime( $selected_order_dates['to'] ) < strtotime( $order['date'] ) ) {
							$selected_order_dates['to'] = $order['date'];
						}

						$wpdb->insert(
							$payout_orders_table,
							array(
								'payout_id' => $inserted_payout_id,
								'post_id'   => $order['order_id'],
								'amount'    => $order['commission'],
							)
						); // WPCS: db call ok.
					}

					// Send commission paid email to affiliate if enabled.
					$mailer = WC()->mailer();
					if ( $mailer->emails['AFWC_Commission_Paid_Email']->is_enabled() ) {
						// Prepare args.
						$args = array(
							'affiliate_id'          => $affiliates[0]['id'],
							'amount'                => floatval( ( ! empty( $affiliates[0]['amount'] ) ) ? $affiliates[0]['amount'] : 0.00 ),
							'currency_id'           => $currency,
							'from_date'             => $selected_order_dates['from'],
							'to_date'               => $selected_order_dates['to'],
							'total_orders'          => count( array_column( $selected_orders, 'order_id' ) ),
							'payout_notes'          => $note,
							'payment_gateway'       => ( ! empty( $params['method'] ) ) ? $params['method'] : 'other',
							'paypal_receiver_email' => ( ! empty( $affiliates[0]['email'] ) ) ? $affiliates[0]['email'] : '', // For PayPal mass payout else empty.
						);
						// Trigger email.
						do_action( 'afwc_commission_paid_email', $args );
					}

					$added_payout = array(
						'datetime'     => gmdate( 'd-M-Y', strtotime( $payout_details['datetime'] ) ),
						'amount'       => $payout_details['amount'],
						'order_count'  => count( $selected_orders ),
						'from_date'    => $selected_order_dates['from'],
						'to_date'      => $selected_order_dates['to'],
						'method'       => $payout_details['payment_gateway'],
						'payout_notes' => $payout_details['payout_notes'],
					);
					wp_send_json(
						array(
							'ACK'                    => 'Success',
							'last_added_payout_id'   => $inserted_payout_id,
							'last_added_payout_data' => $added_payout,
						)
					);
				}
			}
		}

		/**
		 * Handler for AJAX request for getting affiliate dashboard KPI + Lists data
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function dashboard_data( $params = array() ) {

			$affiliates              = $this->affiliates_list( $params );
			$affiliate_ids           = array_map(
				function( $affiliates ) {
					return $affiliates['affiliate_id'];
				},
				$affiliates
			);
			$params['affiliate_ids'] = $affiliate_ids;
			$kpi                     = $this->kpi_data( $params );

			wp_send_json(
				array(
					'affiliateList' => $affiliates,
					'kpi'           => $kpi,
				)
			);
		}

		/**
		 * Handler for AJAX request for getting affiliate dashboard KPI data
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function kpi_data( $params = array() ) {
			global $wpdb;

			// all time data.
			$aa_all_time = new AFWC_Admin_Affiliates();

			// current data as per date filters.
			$affiliate_ids                  = ! empty( $params['affiliate_ids'] ) ? $params['affiliate_ids'] : array();
			$aa_filtered                    = new AFWC_Admin_Affiliates( $affiliate_ids, $params['from'], $params['to'] );
			$aa_filtered->affiliates_orders = $aa_filtered->get_affiliates_orders();
			$aa_filtered->affiliates_refund = $aa_filtered->get_affiliates_refund();
			$aa_filtered->affiliates_sales  = $aa_filtered->get_affiliates_sales();
			$aggregated                     = $aa_filtered->get_commissions_customers();
			$total_sales                    = $aa_filtered->get_storewide_sales();
			$net_sales                      = $aa_filtered->get_net_affiliates_sales();
			$visitor_count                  = $aa_filtered->get_visitors_count();
			$customers_count                = $aggregated['customers_count'];

			$afwc = array(
				'all_time_total_sales' => $aa_all_time->get_storewide_sales(),
				'net_affiliates_sales' => afwc_format_price( $net_sales ),
				'total_sales'          => $total_sales,
				'paid_commissions'     => afwc_format_price( $aggregated['paid_commissions'] ),
				'unpaid_commissions'   => afwc_format_price( $aggregated['unpaid_commissions'] ),
				'paid_commissions'     => afwc_format_price( $aggregated['paid_commissions'] - $aggregated['unpaid_commissions'] ),
				'unpaid_affiliates'    => afwc_format_price( $aggregated['unpaid_affiliates'], 0 ),
				'customers_count'      => afwc_format_price( $customers_count, 0 ),
				'visitors_count'       => afwc_format_price( $visitor_count, 0 ),
				'all_customers_count'  => afwc_format_price( apply_filters( 'afwc_all_customer_ids', 0, $params['from'], $params['to'] ), 0 ),
			);

			$afwc['percent_of_total_sales'] = afwc_format_price( ( ( $total_sales > 0 ) ? ( $net_sales * 100 ) / $total_sales : 0 ) );
			$afwc['conversion_rate']        = afwc_format_price( ( ( $visitor_count > 0 ) ? $customers_count * 100 / $visitor_count : 0 ) );

			return $afwc;
		}

		/**
		 * Handler for AJAX request for getting affiliate's list
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function affiliates_list( $params = array() ) {
			global $wpdb;

			$afwc_filters = ( ! empty( $params['filters'] ) ) ? json_decode( $params['filters'], true ) : array();

			$affiliate_ids           = array();
			$affiliates              = array();
			$params['limit']         = 150;
			$params['status']        = '';
			$params['affiliate_ids'] = array();

			$params['is_export'] = ! empty( $params['is_export'] ) ? $params['is_export'] : false;

			$default_filters['affiliate_status'] = array( 'yes', 'pending', 'default' );
			$default_filters['order_status']     = array();
			$default_filters['tags']             = array();

			$search_term = ! empty( $params['q'] ) ? $params['q'] : '';

			foreach ( $default_filters as $filter => $filter_val ) {
				if ( ! empty( $afwc_filters[ $filter ] ) ) {
					$default_filters[ $filter ] = $afwc_filters[ $filter ];
				}
			}
			$afwc_filters = $default_filters;

			$valid_affiliates               = array();
			$params['affiliate_ids_option'] = 'afwc_affiliate_user_ids_' . uniqid();
			$params['affiliate_count']      = count( $valid_affiliates );

			foreach ( $afwc_filters as $filter => $filter_val ) {
				$params[ $filter ] = $filter_val;
				$func_nm           = 'get_affiliate_by_' . $filter;
				if ( ! empty( $filter_val ) && is_callable( array( $this, $func_nm ) ) ) {
					$valid_affiliates = $this->$func_nm( $params );
				}

				$params['affiliate_count'] = count( $valid_affiliates );
				$params['affiliate_ids']   = array_map(
					function( $valid_affiliates ) {
											return $valid_affiliates;
					},
					$valid_affiliates
				);
				update_option( $params['affiliate_ids_option'], implode( ',', $params['affiliate_ids'] ), 'no' );

			}

			// if valid affiliate null then return.
			if ( empty( $valid_affiliates ) ) {
				delete_option( $params['affiliate_ids_option'] );
				return $affiliates;
			}

			// fetch earned commision and customer_count.
			$valid_affiliates_with_commision = $this->get_affiliate_from_referral( $valid_affiliates, $params );

			foreach ( $valid_affiliates as $affiliate_id ) {
				// Check if user exists.
				$user = get_user_by( 'ID', $affiliate_id );
				if ( $user ) {
					$affiliate_ids[]        = $affiliate_id;
					$earned_commissions     = ( ! empty( $valid_affiliates_with_commision ) && ! empty( $valid_affiliates_with_commision[ $affiliate_id ] ) ) ? $valid_affiliates_with_commision[ $affiliate_id ]['earned_commissions'] : 0;
					$unpaid_commissions     = ( ! empty( $valid_affiliates_with_commision ) && ! empty( $valid_affiliates_with_commision[ $affiliate_id ] ) ) ? $valid_affiliates_with_commision[ $affiliate_id ]['unpaid_commissions'] : 0;
					$currency               = ( ! empty( $valid_affiliates_with_commision ) && ! empty( $valid_affiliates_with_commision[ $affiliate_id ] ) ) ? $valid_affiliates_with_commision[ $affiliate_id ]['currency'] : get_woocommerce_currency();
					$customers_count        = ( ! empty( $valid_affiliates_with_commision ) && ! empty( $valid_affiliates_with_commision[ $affiliate_id ] ) ) ? $valid_affiliates_with_commision[ $affiliate_id ]['customers_count'] : 0;
					$total_order            = ( ! empty( $valid_affiliates_with_commision ) && ! empty( $valid_affiliates_with_commision[ $affiliate_id ] ) ) ? $valid_affiliates_with_commision[ $affiliate_id ]['total_order'] : 0;
					$is_affiliate           = get_user_meta( $affiliate_id, 'afwc_is_affiliate', true );
					$afwc_user_paypal_email = get_user_meta( $affiliate_id, 'afwc_paypal_email', true );
					$affiliates[]           = array(
						'affiliate_id'       => $affiliate_id,
						'earned_commissions' => afwc_format_price( $earned_commissions ),
						'unpaid_commissions' => afwc_format_price( $unpaid_commissions ),
						'currency'           => $currency,
						'customers_count'    => $customers_count,
						'total_order'        => $total_order,
						'pending'            => ( 'pending' === $is_affiliate ) ? 1 : 0,
						'paypal_email'       => ( ! empty( $afwc_user_paypal_email ) ) ? $afwc_user_paypal_email : '',
					);
				}
			}

			if ( '' !== $search_term ) {
				$affiliate_names_results = $wpdb->get_results( // phpcs:ignore
																$wpdb->prepare( // phpcs:ignore
																	"SELECT ID AS affiliate_id,
																						display_name AS display_name
																				FROM {$wpdb->users}
																				WHERE FIND_IN_SET ( ID, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) AND ( user_nicename LIKE %s OR display_name LIKE %s OR user_email LIKE %s )",
																	$params['affiliate_ids_option'],
																	'%' . $wpdb->esc_like( $search_term ) . '%',
																	'%' . $wpdb->esc_like( $search_term ) . '%',
																	'%' . $wpdb->esc_like( $search_term ) . '%'
																),
					'ARRAY_A'
				);

			} else {
				$affiliate_names_results = $wpdb->get_results( // phpcs:ignore
																$wpdb->prepare( // phpcs:ignore
																	"SELECT ID AS affiliate_id,
																						display_name AS display_name,
																						user_email AS email
																				FROM {$wpdb->users}
																				WHERE FIND_IN_SET ( ID,  ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) ",
																	$params['affiliate_ids_option']
																),
					'ARRAY_A'
				);
			}

			if ( count( $affiliate_names_results ) > 0 ) {
				foreach ( $affiliate_names_results as $result ) {
					$affiliate_names[ $result['affiliate_id'] ]['display_name'] = $result['display_name'];
					$affiliate_names[ $result['affiliate_id'] ]['email']        = $result['email'];
				}

				foreach ( $affiliates as $key => $affiliate ) {
					if ( ! empty( $affiliate_names[ $affiliate['affiliate_id'] ] ) ) {
						$affiliates[ $key ]['name']  = $affiliate_names[ $affiliate['affiliate_id'] ]['display_name'];
						$affiliates[ $key ]['email'] = $affiliate_names[ $affiliate['affiliate_id'] ]['email'];
					} else {
						unset( $affiliates[ $key ] );
					}
				}
			}

			delete_option( $params['affiliate_ids_option'] );
			array_multisort( array_column( $affiliates, 'earned_commissions' ), SORT_DESC, $affiliates );
			return $affiliates;

		}

		/**
		 * Function for getting affiliate's by affiliates status
		 *
		 * @param array $params Params from the AJAX request.
		 * @return array $valid_affiliates
		 */
		public function get_affiliate_by_affiliate_status( $params ) {

			$valid_affiliates = $this->get_affiliate_from_users( $params );
			$affiliate_status = $params['affiliate_status'];
			if ( ! empty( $affiliate_status ) && ( in_array( 'yes', $affiliate_status, true ) || in_array( 'default', $affiliate_status, true ) ) ) {
				$params['affiliate_ids'] = array_map(
					function( $valid_affiliates ) {
											return $valid_affiliates;
					},
					$valid_affiliates
				);
				update_option( $params['affiliate_ids_option'], implode( ',', $params['affiliate_ids'] ), 'no' );
				$params['affiliate_count'] = count( $valid_affiliates );
				$valid_affiliates          = array_merge( $valid_affiliates, $this->get_affiliate_by_user_roles( $params ) );
			}
			return $valid_affiliates;

		}

		/**
		 * Function for getting affiliate's referral commision
		 *
		 * @param array $valid_affiliates valid affiliates.
		 * @param array $params Params from the AJAX request.
		 * @return array $valid_affiliates
		 */
		public function get_affiliate_from_referral( $valid_affiliates, $params ) {
			global $wpdb;
			$valid_affiliates_with_commision = array();
			$results = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT affiliate_id,
																			IFNULL(SUM( CASE WHEN datetime BETWEEN %s AND %s THEN amount ELSE 0 END), 0) as earned_commissions,
																			IFNULL(SUM( CASE WHEN datetime BETWEEN %s AND %s AND status = 'unpaid' THEN amount ELSE 0 END), 0) as unpaid_commissions,
																			currency_id as currency,
																			IFNULL(SUM( CASE WHEN datetime BETWEEN %s AND %s THEN 1 ELSE 0 END), 0) as total_order,
																			IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																	FROM {$wpdb->prefix}afwc_referrals WHERE FIND_IN_SET( affiliate_id, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )
																	GROUP BY affiliate_id
																	ORDER BY earned_commissions DESC, customers_count DESC",
														$params['from'] . ' 00:00:00',
														$params['to'] . ' 23:59:59',
														$params['from'] . ' 00:00:00',
														$params['to'] . ' 23:59:59',
														$params['from'] . ' 00:00:00',
														$params['to'] . ' 23:59:59',
														$params['affiliate_ids_option']
													),
				'ARRAY_A'
			);
			foreach ( $results as $value ) {
				$valid_affiliates_with_commision[ $value['affiliate_id'] ] = $value;
			}

			return $valid_affiliates_with_commision;
		}

		/**
		 * Function for getting affiliate's from users
		 *
		 * @param array $params Params from the AJAX request.
		 * @return array $valid_affiliates
		 */
		public function get_affiliate_from_users( $params ) {
			global $wpdb;
			$afwc_is_affiliate = implode( ',', $params['affiliate_status'] );
			$is_export         = $params['is_export'];

			// Code to fetch all users that have 'is_affiliate' checked.
			if ( ! empty( $is_export ) ) {
				// remove limit if export.
				$valid_affiliates = $wpdb->get_col( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"SELECT u.ID AS affiliate_id
								FROM {$wpdb->users} as u
									JOIN {$wpdb->usermeta} as um
										ON(um.user_id = u.ID 
										AND um.meta_key = 'afwc_is_affiliate' )
								WHERE FIND_IN_SET (um.meta_value, %s )",
						$afwc_is_affiliate
					)
				);
			} else {
				$valid_affiliates = $wpdb->get_col( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"SELECT u.ID AS affiliate_id
								FROM {$wpdb->users} as u
									JOIN {$wpdb->usermeta} as um
										ON(um.user_id = u.ID 
										AND um.meta_key = 'afwc_is_affiliate' )
								WHERE FIND_IN_SET (um.meta_value, %s ) 
								LIMIT 0,%d",
						$afwc_is_affiliate,
						$params['limit']
					)
				);
			}

			return $valid_affiliates;
		}

		/**
		 * Function for getting affiliate's from user roles
		 *
		 * @param array $params Params from the AJAX request.
		 * @return array $valid_affiliates
		 */
		public function get_affiliate_by_user_roles( $params ) {
			global $wpdb;

			$affiliate_user_roles       = get_option( 'affiliate_users_roles', array() );
			$affiliate_user_role_ids    = array();
			$affiliate_counts           = $params['affiliate_count'];
			$invalid_affiliate_ids      = array();
			$params['affiliate_status'] = array( 'no' );
			$invalid_affiliate          = $this->get_affiliate_from_users( $params );
			$affiliate_ids_option       = $params['affiliate_ids_option'];
			$is_export                  = $params['is_export'];

			if ( ! empty( $invalid_affiliate ) ) {
				$invalid_affiliate_ids = array_map(
					function( $invalid_affiliate ) {
												return $invalid_affiliate;
					},
					$invalid_affiliate
				);
				if ( ! empty( $invalid_affiliate_ids ) ) {
					$params['affiliate_ids'] = array_merge( $params['affiliate_ids'], $invalid_affiliate_ids );
				}

				update_option( $affiliate_ids_option, implode( ',', $params['affiliate_ids'] ), 'no' );
			}

			if ( ! empty( $affiliate_user_roles ) ) {
				if ( ( $affiliate_counts < $params['limit'] ) || ! empty( $is_export ) ) {
					foreach ( $affiliate_user_roles as $key => $user_role ) {

						$ids = $wpdb->get_col( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT user_id as affiliate_id
																FROM {$wpdb->usermeta}
																WHERE meta_key = '{$wpdb->prefix}capabilities'
																	AND meta_value LIKE %s
																	AND NOT FIND_IN_SET ( user_id, ( SELECT option_value
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )",
													'%' . $wpdb->esc_like( $user_role ) . '%',
													$affiliate_ids_option
												)
						);

						$affiliate_user_role_ids = array_merge( $affiliate_user_role_ids, $ids );
					}
				}
			}
			return $affiliate_user_role_ids;
		}

		/**
		 * Function for getting affiliate's from order status
		 *
		 * @param array $params Params from the AJAX request.
		 * @return array $valid_affiliates
		 */
		public function get_affiliate_by_order_status( $params ) {
			global $wpdb;
			$order_status         = implode( ',', $params['order_status'] );
			$affiliate_ids_option = $params['affiliate_ids_option'];
			$valid_affiliates = $wpdb->get_col( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT affiliate_id as affiliate_id
																	FROM {$wpdb->prefix}afwc_referrals WHERE FIND_IN_SET (status, %s ) AND FIND_IN_SET ( affiliate_id,( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ))
																	GROUP BY affiliate_id",
														$order_status,
														$affiliate_ids_option
													)
			);
			return $valid_affiliates;
		}

		/**
		 * Function for getting affiliate's from user tags
		 *
		 * @param array $params Params from the AJAX request.
		 * @return array $valid_affiliates
		 */
		public function get_affiliate_by_tags( $params ) {
			global $wpdb;

			$affiliate_tags       = implode( ',', $params['tags'] );
			$affiliate_ids_option = $params['affiliate_ids_option'];
			$valid_affiliates = $wpdb->get_col( // phpcs:ignore
											$wpdb->prepare( // phpcs:ignore
												"SELECT object_id AS affiliate_id
														FROM {$wpdb->term_relationships} 
														WHERE FIND_IN_SET (term_taxonomy_id, %s ) AND FIND_IN_SET ( object_id, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ))",
												$affiliate_tags,
												$affiliate_ids_option
											)
			);
			return $valid_affiliates;
		}

		/**
		 * Function to generate and export the CSV data
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function export_affiliates( $params ) {
			global $wpdb;

			$affiliates_data     = array();
			$params['is_export'] = true;
			$affiliates_data     = $this->affiliates_list( $params );
			$type                = ! empty( $params['type'] ) ? $params['type'] : 'standard';

			$total_visitors_id_map = array();

			$affiliate_ids = array_map(
				function( $affiliate ) {
						return absint( $affiliate['affiliate_id'] );
				},
				$affiliates_data
			);

			update_option( 'afwc_temp_user_ids', implode( ',', $affiliate_ids ) );

			$result = $wpdb->get_results( // phpcs:ignore
						$wpdb->prepare(
							"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0) as total_visitors, affiliate_id
								FROM {$wpdb->prefix}afwc_hits
									WHERE FIND_IN_SET ( affiliate_id,  ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = 'afwc_temp_user_ids' )  )

									AND datetime BETWEEN %s AND %s GROUP BY affiliate_id",
							$params['from'],
							$params['to']
						),
				'ARRAY_A'
			);
			foreach ( $result as $res ) {
				$total_visitors_id_map[ $res['affiliate_id'] ] = $res['total_visitors'];
			}

			foreach ( $affiliates_data as $k => $affiliate ) {
				$affiliates_data[ $k ]['total_visitors'] = ! empty( $total_visitors_id_map[ $affiliate['affiliate_id'] ] ) ? $total_visitors_id_map[ $affiliate['affiliate_id'] ] : 0;
			}
			$path     = wp_upload_dir();
			$filename = sanitize_title( get_bloginfo( 'name' ) ) . '_' . $type . '_affiliates_' . gmdate( 'd-M-Y' ) . '.csv';
			// open raw memory as file so no temp files needed, you might run out of memory though.
			$f = fopen( $path['path'] . '/' . $filename , 'w+' );// phpcs:ignore
			// loop over the input array.
			if ( 'standard' === $type ) {
				$headers = array( 'Name', 'Email', 'Earned Commissions', 'Unpaid Commissions', 'Total Order', 'Total Visitors' );
			} elseif ( 'mass_payment' === $type ) {
				$headers = array( 'Emails', 'Unpaid Commissions', 'Currency' );
			}
			fwrite( $f, implode( ',', $headers ) . PHP_EOL );// phpcs:ignore

			foreach ( $affiliates_data as $row ) {
				// format array.
				$line = array();
				if ( 'standard' === $type ) {
					$line['name']               = $row['name'];
					$line['email']              = $row['email'];
					$line['earned_commissions'] = $row['earned_commissions'];
					$line['unpaid_commissions'] = $row['unpaid_commissions'];
					$line['total_order']        = $row['total_order'];
					$line['total_visitors']     = $row['total_visitors'];
				} elseif ( 'mass_payment' === $type ) {
					$line['email']              = ! empty( $row['paypal_email'] ) ? $row['paypal_email'] : $row['email'];
					$line['unpaid_commissions'] = $row['unpaid_commissions'];
					$line['currency']           = $row['currency'];
				}
				// generate csv lines from the inner arrays.
				fwrite( $f, implode( ',', $line ) . PHP_EOL ); // phpcs:ignore
			}
			// reset the file pointer to the start of the file.
			fseek( $f, 0 );
			// tell the browser it's going to be a csv file.
			header( 'Content-type: text/x-csv; charset=UTF-8' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );
			// make php send the generated csv lines to the browser.
			fpassthru( $f );
			// Delete file from uploads.
			unlink( $path['path'] . '/' . $filename );
			exit();
		}


		/**
		 * Handler for AJAX request for getting affiliate order details
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function order_details( $params = array() ) {
			$affiliate_id = isset( $params['affiliate_id'] ) ? $params['affiliate_id'] : ''; // phpcs:ignore
			$current_data = new AFWC_Admin_Affiliates( $affiliate_id, $params['from'], $params['to'], intval( $params['page'] ) );
			wp_send_json( $current_data->get_affiliates_order_details() );
		}

		/**
		 * Handler for AJAX request for getting affiliate payout details
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function payout_details( $params = array() ) {
			$affiliate_id = isset( $params['affiliate_id'] ) ? $params['affiliate_id'] : ''; // phpcs:ignore
			$current_data = new AFWC_Admin_Affiliates( $affiliate_id, $params['from'], $params['to'], intval( $params['page'] ) );
			wp_send_json( $current_data->get_affiliates_payout_history() );
		}

		/**
		 * Handler for AJAX request for getting affiliate details
		 *
		 * @param array $params Params from the AJAX request.
		 */
		public function affiliate_details( $params = array() ) {

			global $wpdb;

			$affiliate_id = isset( $params['affiliate_id'] ) ? $params['affiliate_id'] : ''; // phpcs:ignore
			$is_affiliate = '';

			if ( ! empty( $affiliate_id ) ) {
				$is_affiliate = get_user_meta( $affiliate_id, 'afwc_is_affiliate', true );
			}

			if ( 'pending' === $is_affiliate ) {
				$current_data      = new AFWC_Admin_Affiliates( $affiliate_id, $params['from'], $params['to'] );
				$details           = $current_data->get_affiliates_details();
				$affiliate_details = array(
					'name'         => $details[ $affiliate_id ]['name'],
					'affiliate_id' => $affiliate_id,
					'email'        => $details[ $affiliate_id ]['email'],
					'edit_url'     => admin_url( 'user-edit.php?user_id=' . $affiliate_id ) . '#afwc-settings',
					'avatar_url'   => $this->get_avatar_url( get_avatar( $affiliate_id, 32 ) ),
					'pending'      => true,
				);
				wp_send_json( $affiliate_details );
			}

			$pname = get_option( 'afwc_pname' );
			$pname = ( ! empty( $pname ) ) ? $pname : 'ref';

			$paypal = AFWC_Paypal::get_instance();
			$status = $paypal->get_api_setting_status();

			$is_payable = ( ! empty( $status['value'] ) && 'yes' === $status['value'] ) ? true : false;

			$affiliate_id = get_affiliate_id_based_on_user_id( $affiliate_id );

			$all_time_data                    = new AFWC_Admin_Affiliates( $affiliate_id );
			$all_time_data->affiliates_orders = $all_time_data->get_affiliates_orders();
			$all_time_data->affiliates_refund = $all_time_data->get_affiliates_refund();
			$all_time_data->affiliates_sales  = $all_time_data->get_affiliates_sales();
			$all_time_commisions_customers    = $all_time_data->get_commissions_customers();
			$all_time_visitor_count           = $all_time_data->get_visitors_count();
			$all_time_paid_commissions        = floatval( ( ! empty( $all_time_commisions_customers['paid_commissions'] ) ) ? $all_time_commisions_customers['paid_commissions'] : 0 );
			$all_time_unpaid_commissions      = floatval( ( ! empty( $all_time_commisions_customers['unpaid_commissions'] ) ) ? $all_time_commisions_customers['unpaid_commissions'] : 0 );

			$current_data = new AFWC_Admin_Affiliates( $affiliate_id, $params['from'], $params['to'] );
			$current_data->get_all_data();

			$afwc_allow_custom_affiliate_identifier = get_option( 'afwc_allow_custom_affiliate_identifier', 'yes' );

			$afwc_ref_url_id = get_user_meta( $affiliate_id, 'afwc_ref_url_id', true );
			$afwc_ref_url_id = ( 'yes' === $afwc_allow_custom_affiliate_identifier && ! empty( $afwc_ref_url_id ) ) ? $afwc_ref_url_id : $affiliate_id;
			$referral_url    = add_query_arg( $pname, $afwc_ref_url_id, trailingslashit( home_url() ) );

			wp_send_json(
				array(
					'name'                      => $current_data->affiliates_details[ $affiliate_id ]['name'],
					'affiliate_id'              => $affiliate_id,
					'email'                     => $current_data->affiliates_details[ $affiliate_id ]['email'],
					'edit_url'                  => admin_url( 'user-edit.php?user_id=' . $affiliate_id ) . '#afwc-settings',
					'referral_url'              => $referral_url,
					'paypal_email'              => ( true === $is_payable ) ? get_user_meta( $affiliate_id, 'afwc_paypal_email', true ) : '',
					'avatar_url'                => $this->get_avatar_url( get_avatar( $affiliate_id, 32 ) ),
					'last_payout_details'       => $current_data->get_last_payout_details(),
					'formatted_join_duration'   => $current_data->get_formatted_join_duration(),
					'stats'                     => array(
						'current' => array(
							'net_affiliates_sales' => afwc_format_price( $current_data->net_affiliates_sales ),
							'unpaid_commissions'   => afwc_format_price( $current_data->unpaid_commissions ),
							'paid_commissions'     => afwc_format_price( $current_data->earned_commissions - $current_data->unpaid_commissions ),
							'visitors_count'       => afwc_format_price( $current_data->visitors_count, 0 ),
							'customers_count'      => afwc_format_price( $current_data->customers_count, 0 ),
							'conversion_rate'      => afwc_format_price( ( ( $current_data->visitors_count > 0 ) ? $current_data->customers_count * 100 / $current_data->visitors_count : 0 ) ),
							'affiliates_refund'    => afwc_format_price( $current_data->affiliates_refund ),
							'earned_commissions'   => afwc_format_price( $current_data->earned_commissions ),
						),
						'allTime' => array(
							'net_affiliates_sales' => afwc_format_price( $all_time_data->get_net_affiliates_sales() ),
							'unpaid_commissions'   => afwc_format_price( $all_time_unpaid_commissions ),
							'paid_commissions'     => afwc_format_price( $all_time_paid_commissions ),
							'visitors_count'       => afwc_format_price( $all_time_visitor_count, 0 ),
							'customers_count'      => afwc_format_price( ( ( ! empty( $all_time_commisions_customers['customers_count'] ) ) ? $all_time_commisions_customers['customers_count'] : 0 ), 0 ),
							'conversion_rate'      => afwc_format_price( ( ( $all_time_visitor_count > 0 ) ? $all_time_commisions_customers['customers_count'] * 100 / $all_time_visitor_count : 0 ) ),
							'affiliates_refund'    => afwc_format_price( $all_time_data->affiliates_refund ),
							'earned_commissions'   => afwc_format_price( floatval( $all_time_paid_commissions + $all_time_unpaid_commissions ) ),
						),
					),
					'orders_details'            => $current_data->get_affiliates_order_details(),
					'payout_history'            => $current_data->get_affiliates_payout_history(),
					'tags'                      => $current_data->get_affiliates_tags(),
					'coupons'                   => $current_data->get_affiliates_coupons(),
					'top_products'              => $current_data->get_affiliates_top_products(),
					'is_referral_coupon_enable' => get_option( 'afwc_use_referral_coupons', 'no' ),
				)
			);
		}

		/**
		 * Function to get avatar url
		 *
		 * @param string $get_avatar URL string containing avatar URL.
		 * @return string $matches matched string
		 */
		public function get_avatar_url( $get_avatar = '' ) {
			preg_match( "/src='(.*?)'/i", $get_avatar, $matches );
			if ( ! empty( $matches ) ) {
				return $matches[1];
			}
		}

		/**
		 * Update feedback option
		 *
		 * @param string $params mixed.
		 */
		public function update_feedback( $params ) {
			$update_action = ! empty( $params['update_action'] ) ? $params['update_action'] : '';
			if ( ! empty( $update_action ) ) {
				if ( 'close' === $update_action ) {
					$current_date = gmdate( 'Y-m-d', Affiliate_For_WooCommerce::get_offset_timestamp() );
					update_option( 'afwc_feedback_close_date', $current_date, 'no' );
				} else {
					update_option( 'afwc_feedback_option_' . $update_action, true, 'no' );
				}
				wp_send_json(
					array(
						'ACK' => 'Success',
						'msg' => __( 'Feedback option updated', 'affiliate-for-woocommerce' ),
					)
				);
			}
		}

	}

}

return new AFWC_Admin_Dashboard();
