<?php
/**
 * Main class for Affiliates Admin
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @version     1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Affiliates' ) ) {

	/**
	 * Main class for Affiliates Admin
	 */
	class AFWC_Admin_Affiliates {

		/**
		 * Variable to hold affiliate ids
		 *
		 * @var array $affiliate_ids
		 */
		public $affiliate_ids = array();

		/**
		 * From date
		 *
		 * @var string $from
		 */
		public $from = '';

		/**
		 * To date
		 *
		 * @var string $to
		 */
		public $to = '';

		/**
		 * Sales post types
		 *
		 * @var array $sales_post_types
		 */
		public $sales_post_types = array();

		/**
		 * Storewide sales
		 *
		 * @var float $storewide_sales
		 */
		public $storewide_sales = 0;

		/**
		 * Affiliates sales
		 *
		 * @var float $affiliates_sales
		 */
		public $affiliates_sales = 0;

		/**
		 * Net affiliates sales
		 *
		 * @var float $net_affiliates_sales
		 */
		public $net_affiliates_sales = 0;

		/**
		 * Unpaid commissions
		 *
		 * @var float $unpaid_commissions
		 */
		public $unpaid_commissions = 0;

		/**
		 * Visitors count
		 *
		 * @var int $visitors_count
		 */
		public $visitors_count = 0;

		/**
		 * Customers count
		 *
		 * @var int $customers_count
		 */
		public $customers_count = 0;

		/**
		 * Affiliates refund
		 *
		 * @var float $affiliates_refund
		 */
		public $affiliates_refund = 0;

		/**
		 * Paid commissions
		 *
		 * @var float $paid_commissions
		 */
		public $paid_commissions = 0;

		/**
		 * Commissions earned
		 *
		 * @var float $earned_commissions
		 */
		public $earned_commissions = 0;

		/**
		 * Formatted join duration
		 *
		 * @var string $formatted_join_duration
		 */
		public $formatted_join_duration = '';

		/**
		 * Affiliates orders
		 *
		 * @var array $affiliates_orders
		 */
		public $affiliates_orders = array();

		/**
		 * Last payout details
		 *
		 * @var array $last_payout_details
		 */
		public $last_payout_details = array();

		/**
		 * Affiliates display names
		 *
		 * @var array $affiliates_display_names
		 */
		public $affiliates_display_names = array();

		/**
		 * Batch limit
		 *
		 * @var int $batch_limit
		 */
		public $batch_limit = 5;

		/**
		 *  Constructor
		 *
		 * @param  array  $affiliate_ids Affiliates ids.
		 * @param  string $from From date.
		 * @param  string $to To date.
		 * @param  int    $page Current page for batch.
		 */
		public function __construct( $affiliate_ids = array(), $from = '', $to = '', $page = 1 ) {
			$this->affiliate_ids    = ( ! is_array( $affiliate_ids ) ) ? array( $affiliate_ids ) : $affiliate_ids;
			$this->from             = ( ! empty( $from ) ) ? gmdate( 'Y-m-d', strtotime( $from ) ) : gmdate( 'Y-m-d', strtotime( '-30 days' ) );
			$this->to               = ( ! empty( $to ) ) ? gmdate( 'Y-m-d', strtotime( $to ) ) : gmdate( 'Y-m-d' );
			$this->sales_post_types = apply_filters( 'afwc_sales_post_types', array( 'shop_order' ) );
			$this->start_limit      = ( ! empty( $page ) ) ? ( intval( $page ) - 1 ) * $this->batch_limit : 0;
		}

		/**
		 * Function to call all functions to get all data
		 *
		 * @return void
		 */
		public function get_all_data() {

			$this->storewide_sales         = $this->get_storewide_sales();
			$this->affiliates_orders       = $this->get_affiliates_orders();
			$this->affiliates_refund       = $this->get_affiliates_refund();
			$this->affiliates_sales        = $this->get_affiliates_sales();
			$this->net_affiliates_sales    = $this->get_net_affiliates_sales();
			$aggregated                    = $this->get_commissions_customers();
			$this->paid_commissions        = floatval( ( ! empty( $aggregated['paid_commissions'] ) ) ? $aggregated['paid_commissions'] : 0 );
			$this->unpaid_commissions      = floatval( ( ! empty( $aggregated['unpaid_commissions'] ) ) ? $aggregated['unpaid_commissions'] : 0 );
			$this->customers_count         = intval( ( ! empty( $aggregated['customers_count'] ) ) ? $aggregated['customers_count'] : 0 );
			$this->visitors_count          = $this->get_visitors_count();
			$this->earned_commissions      = $this->get_earned_commissions();
			$this->formatted_join_duration = $this->get_formatted_join_duration();
			$this->last_payout_details     = $this->get_last_payout_details();
			$this->affiliates_details      = $this->get_affiliates_details();

		}

		/**
		 * Function to get storewide sales
		 *
		 * @return float $storewide_sales storewide sales
		 */
		public function get_storewide_sales() {
			global $wpdb;

			$prefixed_statuses   = afwc_get_prefixed_order_statuses();
			$option_order_status = 'afwc_order_statuses_' . uniqid();
			update_option( $option_order_status, implode( ',', $prefixed_statuses ), 'no' );

			if ( ! empty( $this->sales_post_types ) ) {
				if ( 1 === count( $this->sales_post_types ) ) {
					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$post_ids = $wpdb->get_col( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT DISTINCT ID
																	FROM {$wpdb->posts}
																	WHERE post_type = %s
																		AND FIND_IN_SET ( post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )
																		AND post_date BETWEEN %s AND %s",
														current( $this->sales_post_types ),
														$option_order_status,
														$this->from . ' 00:00:00',
														$this->to . ' 23:59:59'
													)
						);
					} else {
						$post_ids = $wpdb->get_col( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore 
														"SELECT DISTINCT ID 
																		FROM {$wpdb->posts} 
																		WHERE post_type = %s
																		AND FIND_IN_SET ( post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
														current( $this->sales_post_types ),
														$option_order_status
													)
						);
					}
				} else {

					$option_nm = 'afwc_storewide_sales_post_types_' . uniqid();
					update_option( $option_nm, implode( ',', $this->sales_post_types ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$post_ids = $wpdb->get_col(  // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT DISTINCT ID 
																	FROM {$wpdb->posts}
																	WHERE FIND_IN_SET ( post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )
																		AND post_date BETWEEN %s AND %s
																		AND FIND_IN_SET ( post_type, ( SELECT option_value
																							FROM {$wpdb->prefix}options
																							WHERE option_name = %s ) )",
														$option_order_status,
														$this->from . ' 00:00:00',
														$this->to . ' 23:59:59',
														$option_nm
													)
						);
					} else {
						$post_ids = $wpdb->get_col( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT DISTINCT ID 
																	FROM {$wpdb->posts} 
																	WHERE FIND_IN_SET ( post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )
																		AND FIND_IN_SET ( post_type, ( SELECT option_value
																							FROM {$wpdb->prefix}options
																							WHERE option_name = %s ) )",
														$option_order_status,
														$option_nm
													)
						);
					}

					delete_option( $option_nm );
				}
			} else {
				if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
					$post_ids = $wpdb->get_col( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT DISTINCT ID 
																FROM {$wpdb->posts} 
																WHERE FIND_IN_SET ( post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )
																	AND post_date BETWEEN %s AND %s",
													$option_order_status,
													$this->from . ' 00:00:00',
													$this->to . ' 23:59:59'
												)
					); // phpcs:ignore
				} else {
					$post_ids = $wpdb->get_col( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT DISTINCT ID 
																FROM {$wpdb->posts} 
																WHERE FIND_IN_SET ( post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
													$option_order_status
												)
					);
				}
			}

			delete_option( $option_order_status );

			$storewide_sales = 0;
			if ( ! empty( $post_ids ) ) {

				// is this line needed?
				$storewide_post_id_query = "SELECT DISTINCT ID FROM {$wpdb->posts} WHERE 1"; // phpcs:ignore

				// Let 3rd party plugin developers to calculate storewide sales for their custom post type.
				// Remember to add sales to $storewide_sales.
				$storewide_sales = apply_filters( 'afwc_storewide_sales', $storewide_sales, $post_ids );
			}

			return floatval( $storewide_sales );
		}

		/**
		 * Function to get affiliates sales
		 *
		 * @return float $$affiliates_sales affiliates sales
		 */
		public function get_affiliates_sales() {
			global $wpdb;

			$post_ids = $this->affiliates_orders;

			$affiliates_sales           = 0;
			$completed_affiliates_sales = 0;

			if ( ! empty( $post_ids ) ) {

				// Let 3rd party plugin developers to calculate affiliates sales for their custom post type.
				$completed_affiliates_sales = apply_filters( 'afwc_completed_affiliates_sales', $completed_affiliates_sales, $post_ids );

				$refunded_affiliates_sales = $this->affiliates_refund;

				$affiliates_sales = $completed_affiliates_sales + $refunded_affiliates_sales;
			}

			return floatval( $affiliates_sales );
		}

		/**
		 * Function to get net affiliates sales
		 *
		 * @return float $net_affiliates_sales net affiliates sales
		 */
		public function get_net_affiliates_sales() {
			global $wpdb;

			$net_affiliates_sales = $this->affiliates_sales - $this->affiliates_refund;

			return floatval( $net_affiliates_sales );
		}

		/**
		 * Function to get visitors count
		 *
		 * @return int $visitors_count visitors count
		 */
		public function get_visitors_count() {
			global $wpdb;

			// If no affiliates, get total visitors count from all affiliates
			// If more than one affiliates, get total visitors count from all those affiliates.
			if ( ! empty( $this->affiliate_ids ) ) {
				if ( 1 === count( $this->affiliate_ids ) ) {

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$visitors_count = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																	FROM {$wpdb->prefix}afwc_hits
																	WHERE affiliate_id = %d
																		AND datetime BETWEEN %s AND %s",
														current( $this->affiliate_ids ),
														$this->from . ' 00:00:00',
														$this->to . ' 23:59:59'
													)
						);
					} else {
						$visitors_count = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																	FROM {$wpdb->prefix}afwc_hits
																	WHERE affiliate_id = %d",
														current( $this->affiliate_ids )
													)
						);
					}
				} else {
					$option_nm = 'afwc_hits_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $this->affiliate_ids ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$visitors_count = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																	FROM {$wpdb->prefix}afwc_hits
																	WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																									FROM {$wpdb->prefix}options
																									WHERE option_name = %s ) )
																		AND datetime BETWEEN %s AND %s",
														$option_nm,
														$this->from . ' 00:00:00',
														$this->to . ' 23:59:59'
													)
						);
					} else {
						$visitors_count = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																	FROM {$wpdb->prefix}afwc_hits
																	WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																									FROM {$wpdb->prefix}options
																									WHERE option_name = %s ) )",
														$option_nm
													)
						);
					}

					delete_option( $option_nm );
				}
			} else {

				if ( ! empty( $this->from ) && ! empty( $this->to ) ) {

					$visitors_count = $wpdb->get_var( // phpcs:ignore 
												$wpdb->prepare( // phpcs:ignore
													"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																FROM {$wpdb->prefix}afwc_hits
																WHERE affiliate_id != %d
																	AND datetime BETWEEN %s AND %s",
													0,
													$this->from . ' 00:00:00',
													$this->to . ' 23:59:59'
												)
					);
				} else {
					$visitors_count = $wpdb->get_var( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT IFNULL(COUNT( DISTINCT CONCAT_WS( ':', ip, user_id ) ), 0)
																FROM {$wpdb->prefix}afwc_hits
																WHERE affiliate_id != %d",
													0
												)
					); // phpcs:ignore
				}
			}

			return intval( $visitors_count );
		}

		/**
		 * Function to get affiliates refund
		 *
		 * @return float affiliates refund
		 */
		public function get_affiliates_refund() {
			global $wpdb;

			$post_ids = $this->affiliates_orders;

			$affiliates_refund = 0;

			if ( ! empty( $post_ids ) ) {

				// Let 3rd party plugin developers to calculate affiliates sales for their custom post type.
				$affiliates_refund = apply_filters( 'afwc_affiliates_refund', $affiliates_refund, $post_ids );
			}

			return floatval( $affiliates_refund );
		}

		/**
		 * Function to get paid commissions, unpaid commissions & customer count
		 *
		 * @param boolean $group_by_affiliate Flag for grouping the results by affiliate id or not.
		 * @return array $aggregated paid commissions, unpaid commisions, customer count
		 */
		public function get_commissions_customers( $group_by_affiliate = false ) {

			global $wpdb;

			$aggregated = array();

			$temp_option_key     = 'afwc_order_status_' . uniqid();
			$paid_order_statuses = get_afwc_paid_order_status();
			update_option( $temp_option_key, implode( ',', $paid_order_statuses ), 'no' );

			if ( ! empty( $this->affiliate_ids ) ) {
				if ( 1 === count( $this->affiliate_ids ) ) {

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$aggregated = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT IFNULL(SUM( amount ), 0) as earned_commissions,
																					IFNULL(SUM( CASE WHEN status = 'paid' THEN amount END ), 0) as paid_commissions,
																					IFNULL(SUM( CASE WHEN status = 'unpaid' AND  FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN amount END ), 0) as unpaid_commissions,
																					IFNULL(COUNT( DISTINCT(CASE WHEN status = 'unpaid' AND FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN affiliate_id END) ), 0) as unpaid_affiliates,
																					IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE affiliate_id = %d
																				AND datetime BETWEEN %s AND %s AND status != %s",
																$temp_option_key,
																$temp_option_key,
																current( $this->affiliate_ids ),
																$this->from . ' 00:00:00',
																$this->to . ' 23:59:59',
																'draft'
															),
							'ARRAY_A'
						);
					} else {
						$aggregated = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT IFNULL(SUM( amount ), 0) as earned_commissions,
																					IFNULL(SUM( CASE WHEN status = 'paid' THEN amount END ), 0) as paid_commissions,
																					IFNULL(SUM( CASE WHEN status = 'unpaid' AND FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN amount END ), 0) as unpaid_commissions,
																					IFNULL(COUNT( DISTINCT(CASE WHEN status = 'unpaid' AND FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN affiliate_id END) ), 0) as unpaid_affiliates,
																					IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE affiliate_id = %d  AND status != %s",
																$temp_option_key,
																$temp_option_key,
																current( $this->affiliate_ids ),
																'draft'
															),
							'ARRAY_A'
						); // phpcs:ignore
					}
				} else {

					$option_nm = 'afwc_commission_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $this->affiliate_ids ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$aggregated = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT IFNULL(SUM( amount ), 0) as earned_commissions,
																					IFNULL(SUM( CASE WHEN status = 'paid' THEN amount END ), 0) as paid_commissions,
																					IFNULL(SUM( CASE WHEN status = 'unpaid' AND FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN amount END ), 0) as unpaid_commissions,
																					IFNULL(COUNT( DISTINCT(CASE WHEN status = 'unpaid'AND FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN affiliate_id END) ), 0) as unpaid_affiliates,
																					IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )
																				AND datetime BETWEEN %s AND %s  AND status != %s",
																$temp_option_key,
																$temp_option_key,
																$option_nm,
																$this->from . ' 00:00:00',
																$this->to . ' 23:59:59',
																'draft'
															),
							'ARRAY_A'
						);
					} else {
						$aggregated = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT IFNULL(SUM( amount ), 0) as earned_commissions,
																					IFNULL(SUM( CASE WHEN status = 'paid' THEN amount END ), 0) as paid_commissions,
																					IFNULL(SUM( CASE WHEN status = 'unpaid' AND FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN amount END ), 0) as unpaid_commissions,
																					IFNULL(COUNT( DISTINCT(CASE WHEN status = 'unpaid' AND FIND_IN_SET ( order_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s )  ) THEN affiliate_id END) ), 0) as unpaid_affiliates,
																					IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )  AND status != %s",
																$temp_option_key,
																$temp_option_key,
																$option_nm,
																'draft'
															),
							'ARRAY_A'
						);
					}

					delete_option( $option_nm );
				}
			} else {
				if ( ! empty( $this->from ) && ! empty( $this->to ) ) {

					$aggregated  = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( amount ), 0) as earned_commissions,
																				IFNULL(SUM( CASE WHEN status = 'paid' THEN amount END ), 0) as paid_commissions,
																				IFNULL(SUM( CASE WHEN status = 'unpaid' THEN amount END ), 0) as unpaid_commissions,
																				IFNULL(COUNT( DISTINCT(CASE WHEN status = 'unpaid' THEN affiliate_id END) ), 0) as unpaid_affiliates,
																				IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																		FROM {$wpdb->prefix}afwc_referrals
																		WHERE affiliate_id != %d
																			AND datetime BETWEEN %s AND %s",
															0,
															$this->from . ' 00:00:00',
															$this->to . ' 23:59:59'
														),
						'ARRAY_A'
					);
				} else {
					$aggregated = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( amount ), 0) as earned_commissions,
																				IFNULL(SUM( CASE WHEN status = 'paid' THEN amount END ), 0) as paid_commissions,
																				IFNULL(SUM( CASE WHEN status = 'unpaid' THEN amount END ), 0) as unpaid_commissions,
																				IFNULL(COUNT( DISTINCT(CASE WHEN status = 'unpaid' THEN affiliate_id END) ), 0) as unpaid_affiliates,
																				IFNULL(COUNT( DISTINCT IF( user_id > 0, user_id, CONCAT_WS( ':', ip, user_id ) ) ), 0) as customers_count
																		FROM {$wpdb->prefix}afwc_referrals
																		WHERE affiliate_id != %d",
															0
														),
						'ARRAY_A'
					);
				}
			}
			delete_option( $temp_option_key );
			return ( ( $group_by_affiliate ) ? $aggregated : ( ! empty( $aggregated[0] ) ? $aggregated[0] : array() ) );

		}

		/**
		 * Function to get commissions earned
		 *
		 * @return float $earned_commissions commissions earned
		 */
		public function get_earned_commissions() {
			global $wpdb;

			$earned_commissions = $this->paid_commissions + $this->unpaid_commissions;

			return floatval( $earned_commissions );
		}

		/**
		 * Function to get formatted join duration
		 *
		 * @return string $formatted_join_duration formatted join duration
		 */
		public function get_formatted_join_duration() {
			global $wpdb;

			// Return affiliate join duration in human readable format
			// only when count of $affiliate_ids is one
			// Return empty string otherwise.
			if ( ! empty( $this->affiliate_ids ) && 1 === count( $this->affiliate_ids ) ) {
				$affiliate               = get_userdata( $this->affiliate_ids[0] );
				$from                    = Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $affiliate->user_registered ) );
				$to                      = Affiliate_For_WooCommerce::get_offset_timestamp();
				$formatted_join_duration = human_time_diff( $from, $to );
			} else {
				$formatted_join_duration = '';
			}

			return $formatted_join_duration;
		}

		/**
		 * Function to get affiliates orders
		 *
		 * @return array $affiliates_orders affiliates order ids
		 */
		public function get_affiliates_orders() {
			global $wpdb;

			// If no affiliates get orders of all affiliates
			// If more than one affiliate, get orders of all those affiliates.
			if ( ! empty( $this->affiliate_ids ) ) {
				if ( 1 === count( $this->affiliate_ids ) ) {

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$affiliates_orders = $wpdb->get_col( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT DISTINCT post_id
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE affiliate_id = %d
																				AND datetime BETWEEN %s AND %s",
																current( $this->affiliate_ids ),
																$this->from . ' 00:00:00',
																$this->to . ' 23:59:59'
															)
						);
					} else {
						$affiliates_orders = $wpdb->get_col( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT DISTINCT post_id
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE affiliate_id = %d",
																current( $this->affiliate_ids )
															)
						);
					}
				} else {

					$option_nm = 'afwc_orders_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $this->affiliate_ids ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$affiliates_orders = $wpdb->get_col( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT DISTINCT post_id
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )
																				AND datetime BETWEEN %s AND %s",
																$option_nm,
																$this->from . ' 00:00:00',
																$this->to . ' 23:59:59'
															)
						); // phpcs:ignore
					} else {
						$affiliates_orders = $wpdb->get_col( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT DISTINCT post_id
																			FROM {$wpdb->prefix}afwc_referrals
																			WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )",
																$option_nm
															)
						); // phpcs:ignore
					}

					delete_option( $option_nm );
				}
			} else {

				if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
					$affiliates_orders = $wpdb->get_col( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT DISTINCT post_id
																		FROM {$wpdb->prefix}afwc_referrals
																		WHERE affiliate_id != %d
																			AND datetime BETWEEN %s AND %s",
															0,
															$this->from . ' 00:00:00',
															$this->to . ' 23:59:59'
														)
					);
				} else {
					$affiliates_orders = $wpdb->get_col( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT DISTINCT post_id
																		FROM {$wpdb->prefix}afwc_referrals
																		WHERE affiliate_id != %d",
															0
														)
					);
				}
			}
			return $affiliates_orders;
		}

		/**
		 * Function to get affiliates order details
		 *
		 * @return array $affiliates_order_details affiliates order details
		 */
		public function get_affiliates_order_details() {
			global $wpdb;

			$order_ids = $this->affiliates_orders;

			$affiliates_order_details = array();

			if ( ! empty( $order_ids ) ) {
				if ( 1 === count( $order_ids ) ) {

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
											                                                            IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
											                                                            IFNULL( referrals.amount, 0.00 ) AS commission,
								                                                           				referrals.status,
											                                                            referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE referrals.post_id = %d
																									AND referrals.datetime BETWEEN %s AND %s
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					current( $order_ids ),
																					$this->from . ' 00:00:00',
																					$this->to . ' 23:59:59',
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);
					} else {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE referrals.post_id = %d
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					current( $order_ids ),
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);
					}
				} else {

					$option_nm = 'afwc_orders_details_order_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $order_ids ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE FIND_IN_SET ( referrals.post_id, ( SELECT option_value
																																FROM {$wpdb->prefix}options
																																WHERE option_name = %s ) )
																									AND referrals.datetime BETWEEN %s AND %s
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					$option_nm,
																					$this->from . ' 00:00:00',
																					$this->to . ' 23:59:59',
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);

					} else {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE FIND_IN_SET ( referrals.post_id, ( SELECT option_value
																																FROM {$wpdb->prefix}options
																																WHERE option_name = %s ) )
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					$option_nm,
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);
					}

					delete_option( $option_nm );
				}
			} elseif ( ! empty( $this->affiliate_ids ) ) {
				if ( 1 === count( $this->affiliate_ids ) ) {

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$affiliates_order_details_results  = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE referrals.affiliate_id = %d
																									AND referrals.datetime BETWEEN %s AND %s
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					current( $this->affiliate_ids ),
																					$this->from . ' 00:00:00',
																					$this->to . ' 23:59:59',
																					$this->start_limit,
																					$this->batch_limit
																				),
						'ARRAY_A'); // phpcs:ignore

					} else {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE referrals.affiliate_id = %d
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					current( $this->affiliate_ids ),
																					$this->start_limit,
																					$this->batch_limit
																				),
						'ARRAY_A'); // phpcs:ignore
					}
				} else {

					$option_nm = 'afwc_orders_details_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $this->affiliate_ids ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE FIND_IN_SET ( referrals.affiliate_id, ( SELECT option_value
																																FROM {$wpdb->prefix}options
																																WHERE option_name = %s ) )
																									AND referrals.datetime BETWEEN %s AND %s
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					$option_nm,
																					$this->from . ' 00:00:00',
																					$this->to . ' 23:59:59',
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);
					} else {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE FIND_IN_SET ( referrals.affiliate_id, ( SELECT option_value
																																FROM {$wpdb->prefix}options
																																WHERE option_name = %s ) )
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					$option_nm,
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);
					}

					delete_option( $option_nm );
				}
			} else {
				if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE referrals.affiliate_id != %d
																									AND referrals.datetime BETWEEN %s AND %s
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					0,
																					$this->from . ' 00:00:00',
																					$this->to . ' 23:59:59',
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);
				} else {
						$affiliates_order_details_results = $wpdb->get_results( // phpcs:ignore
																				$wpdb->prepare( // phpcs:ignore
																					"SELECT DISTINCT referrals.post_id AS order_id, 
																										DATE_FORMAT( referrals.datetime, %s ) AS datetime,
																										IFNULL( postmeta.meta_value, 0.00 ) AS order_total,
																										IFNULL( referrals.amount, 0.00 ) AS commission,
																										referrals.status,
																										referrals.type AS referral_type
																		  						FROM {$wpdb->prefix}afwc_referrals AS referrals
																									LEFT JOIN {$wpdb->postmeta} AS postmeta
																										ON ( postmeta.post_id = referrals.post_id 
																											AND postmeta.meta_key = '_order_total' )
																								WHERE referrals.affiliate_id != %d
																								ORDER BY referrals.datetime DESC
																								LIMIT %d,%d",
																					'%d-%b-%Y',
																					0,
																					$this->start_limit,
																					$this->batch_limit
																				),
							'ARRAY_A'
						);
				}
			}

			if ( ! empty( $affiliates_order_details_results ) ) {
				foreach ( $affiliates_order_details_results as $result ) {
					$order_ids[]                = $result['order_id'];
					$result['referral_type']    = ucwords( ( empty( $result['referral_type'] ) ) ? 'link' : $result['referral_type'] );
					$result['order_url']        = admin_url( 'post.php?post=' . $result['order_id'] . '&action=edit' );
					$affiliates_order_details[] = $result;
				}
			}

			if ( ! empty( $order_ids ) ) {
				$option_nm = 'afwc_orders_details_affiliate_ids_' . uniqid();
				update_option( $option_nm, implode( ',', array_unique( $order_ids ) ), 'no' );

				$results = $wpdb->get_results( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT post_id AS order_id,
																		GROUP_CONCAT(CASE WHEN meta_key IN ('_billing_first_name', '_billing_last_name') THEN meta_value END SEPARATOR ' ') AS billing_name,
																		GROUP_CONCAT(CASE WHEN meta_key = '_billing_email' THEN meta_value END) as billing_email,
																		GROUP_CONCAT(CASE WHEN meta_key = '_customer_user' THEN meta_value END) as customer_user,
																		GROUP_CONCAT(CASE WHEN meta_key = '_order_currency' THEN meta_value END) as currency
																FROM {$wpdb->postmeta} AS postmeta
																WHERE meta_key IN ('_billing_first_name', '_billing_last_name', '_billing_email', '_customer_user', '_order_currency')
																	AND FIND_IN_SET ( post_id, ( SELECT option_value
																								FROM {$wpdb->prefix}options
																								WHERE option_name = %s ) )
																GROUP BY order_id",
													$option_nm
												),
					'ARRAY_A'
				);

				if ( ! empty( $results ) ) {
					foreach ( $results as $detail ) {
						$orders_billing_name[ $detail['order_id'] ]['billing_name']        = $detail['billing_name'];
						$orders_billing_name[ $detail['order_id'] ]['customer_orders_url'] = add_query_arg( ( ( empty( $detail['customer_user'] ) ) ? array( 's' => $detail['billing_email'] ) : array( '_customer_user' => $detail['customer_user'] ) ), admin_url( 'edit.php?post_type=shop_order' ) );
						$orders_billing_name[ $detail['order_id'] ]['currency']            = html_entity_decode( get_woocommerce_currency_symbol( $detail['currency'] ) );
					}

					foreach ( $affiliates_order_details as $key => $detail ) {
						$affiliates_order_details[ $key ] = ( ! empty( $orders_billing_name[ $detail['order_id'] ] ) ) ? array_merge( $affiliates_order_details[ $key ], $orders_billing_name[ $detail['order_id'] ] ) : $affiliates_order_details[ $key ];
					}
				}
				delete_option( $option_nm );
			}

			// Let 3rd party developers to add additional details in orders details.
			$affiliates_order_details = apply_filters( 'afwc_order_details', $affiliates_order_details, $order_ids );

			return $affiliates_order_details;
		}

		/**
		 * Function to get affiliates payout history
		 *
		 * @return array affiliates payout history
		 */
		public function get_affiliates_payout_history() {

			$affiliates_payout_history = array();
			$args                      = array();
			$args['affiliate_ids']     = $this->affiliate_ids;
			$args['from']              = $this->from;
			$args['to']                = $this->to;
			$args['start_limit']       = $this->start_limit;
			$args['batch_limit']       = $this->batch_limit;
			$args['with_total']        = false;
			$affiliates_payout_history = Affiliate_For_WooCommerce::get_affiliates_payout_history( $args );
			return $affiliates_payout_history;

		}

		/**
		 * Function to get last payout details
		 *
		 * @param  bool $amount Flag for adding amount in last payout details.
		 * @param  bool $date Flag for adding date in last payout details.
		 * @param  bool $gateway Flag for adding gateway in last payout details.
		 * @return array $last_payout_details last payout details
		 */
		public function get_last_payout_details( $amount = true, $date = true, $gateway = true ) {
			global $wpdb;

			$last_payout_details = array(
				'amount'  => '',
				'date'    => '',
				'gateway' => '',
			);

			// If no affiliate find out last payout details from payout to all affiliates
			// If more than one affiliate find out last payout details from payout to all those affiliates.
			if ( ! empty( $this->affiliate_ids ) ) {
				if ( 1 === count( $this->affiliate_ids ) ) {

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$payout_details = $wpdb->get_results( // phpcs:ignore
																$wpdb->prepare( // phpcs:ignore
																	"SELECT *
																				FROM {$wpdb->prefix}afwc_payouts
																				WHERE affiliate_id = %d
																					AND datetime BETWEEN %s AND %s
																				ORDER BY payout_id DESC",
																	current( $this->affiliate_ids ),
																	$this->from . ' 00:00:00',
																	$this->to . ' 23:59:59'
																),
							'ARRAY_A'
						);

					} else {
						$payout_details      = $wpdb->get_results( // phpcs:ignore
																	$wpdb->prepare( // phpcs:ignore
																		"SELECT *
																					FROM {$wpdb->prefix}afwc_payouts
																					WHERE affiliate_id = %d
																					ORDER BY payout_id DESC",
																		current( $this->affiliate_ids )
																	),
							'ARRAY_A'
						);
					}
				} else {

					$option_nm = 'afwc_last_payout_details_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $this->affiliate_ids ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {

						$payout_details = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT *
																			FROM {$wpdb->prefix}afwc_payouts
																			WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )
																				AND datetime BETWEEN %s AND %s
																			ORDER BY payout_id DESC",
																$option_nm,
																$this->from . ' 00:00:00',
																$this->to . ' 23:59:59'
															),
						'ARRAY_A' ); // phpcs:ignore
					} else {
						$payout_details = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT *
																			FROM {$wpdb->prefix}afwc_payouts
																			WHERE FIND_IN_SET ( affiliate_id, ( SELECT option_value
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )
																			ORDER BY payout_id DESC",
																$option_nm
															),
							'ARRAY_A'
						);
					}

					delete_option( $option_nm );
				}
			} else {
				if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$payout_details = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT *
																			FROM {$wpdb->prefix}afwc_payouts
																			WHERE affiliate_id != %d
																				AND datetime BETWEEN %s AND %s
																			ORDER BY payout_id DESC",
																0,
																$this->from . ' 00:00:00',
																$this->to . ' 23:59:59'
															),
							'ARRAY_A'
						);
				} else {
						$payout_details = $wpdb->get_results( // phpcs:ignore
															$wpdb->prepare( // phpcs:ignore
																"SELECT *
																			FROM {$wpdb->prefix}afwc_payouts
																			WHERE affiliate_id != %d
																			ORDER BY payout_id DESC",
																0
															),
							'ARRAY_A'
						);
				}
			}

			// Return only amount, date & gateway only when asked.
			if ( $amount ) {
				$last_payout_details['amount'] = ( ! empty( $payout_details['amount'] ) ) ? $payout_details['amount'] : '';
			}

			if ( $date ) {
				$last_payout_details['date'] = ( ! empty( $payout_details['datetime'] ) ) ? gmdate( 'd-M-Y', Affiliate_For_WooCommerce::get_offset_timestamp( strtotime( $payout_details['datetime'] ) ) ) : '';
			}

			if ( $gateway ) {
				$last_payout_details['gateway'] = ( ! empty( $payout_details['payment_gateway'] ) ) ? $payout_details['payment_gateway'] : '';
			}

			// Hook to add more details about last payout.
			$last_payout_details = apply_filters( 'afwc_last_payout_details', $last_payout_details, $payout_details );

			return $last_payout_details;
		}

		/**
		 * Function to get affiliate's display_name
		 *
		 * @return array where key is user id & value is their display name
		 */
		public function get_affiliates_details() {
			global $wpdb;

			$affiliates_details = array();

			if ( ! empty( $this->affiliate_ids ) ) {

				if ( 1 === count( $this->affiliate_ids ) ) {

					$results = $wpdb->get_results( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT ID, display_name, user_email
																FROM {$wpdb->users}
																WHERE ID = %d",
													current( $this->affiliate_ids )
												),
						'ARRAY_A'
					);

				} else {

					$option_nm = 'afwc_display_names_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $this->affiliate_ids ), 'no' );

					$results      = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT ID, display_name, user_email
																		FROM {$wpdb->users}
																		WHERE FIND_IN_SET ( ID, ( SELECT option_value
																									FROM {$wpdb->prefix}options
																									WHERE option_name = %s ) )",
															$option_nm
														),
						'ARRAY_A'
					);

					delete_option( $option_nm );
				}
			}

			if ( $results ) {

				foreach ( $results as $result ) {
					$affiliates_details[ $result['ID'] ] = array(
						'name'  => $result['display_name'],
						'email' => $result['user_email'],
					);
				}
			}

			return $affiliates_details;
		}

		/**
		 * Function to get affiliate's coupons
		 *
		 * @return array referral_coupons
		 */
		public function get_affiliates_coupons() {
			$referral_coupons     = array();
			$use_referral_coupons = get_option( 'afwc_use_referral_coupons', 'no' );
			if ( ! empty( $this->affiliate_ids ) && 'yes' === $use_referral_coupons ) {
				$afwc_coupon      = AFWC_Coupon::get_instance();
				$referral_coupons = $afwc_coupon->get_referral_coupon( array( 'user_id' => $this->affiliate_ids ) );
			}
			return $referral_coupons;
		}

		/**
		 * Function to get affiliate's tags
		 *
		 * @return array $user_tags
		 */
		public function get_affiliates_tags() {
			$user_tags = array();
			if ( ! empty( $this->affiliate_ids ) ) {
				$user_tags = wp_get_object_terms( $this->affiliate_ids, 'afwc_user_tags', array( 'fields' => 'id=>name' ) );
			}
			return $user_tags;
		}

		/**
		 * Function to get affiliate's top products
		 *
		 * @return array $products
		 */
		public function get_affiliates_top_products() {
			$args['affiliate_id'] = current( $this->affiliate_ids );
			$args['limit']        = 5;
			$args['from']         = $this->from;
			$args['to']           = $this->to;
			$products             = Affiliate_For_WooCommerce::get_products_data( $args );
			if ( ! empty( $products['rows'] ) ) {
				foreach ( $products['rows'] as $id => $product ) {
					$products['rows'][ $id ]['sales']   = afwc_format_price( $product['sales'] );
					$products['rows'][ $id ]['product'] = html_entity_decode( $product['product'] );

				}
			}
			return $products;
		}


	}

}
