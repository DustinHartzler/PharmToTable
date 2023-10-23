<?php
/**
 * Main class for Affiliate For WooCommerce Integration
 *
 * @package     affiliate-for-woocommerce/includes/integration/woocommerce/
 * @since       1.0.0
 * @version     1.4.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Integration_WooCommerce' ) ) {

	/**
	 * Affiliate For WooCommerce Integration
	 */
	class AFWC_Integration_WooCommerce {

		/**
		 * Constructor
		 */
		public function __construct() {

			add_filter( 'afwc_storewide_sales', array( $this, 'woocommerce_storewide_sales' ), 10, 2 );
			add_filter( 'afwc_completed_affiliates_sales', array( $this, 'woocommerce_affiliates_sales' ), 10, 2 );
			add_filter( 'afwc_affiliates_refund', array( $this, 'woocommerce_affiliates_refund' ), 10, 2 );
			add_filter( 'afwc_all_customer_ids', array( $this, 'woocommerce_all_customer_ids' ), 10, 2 );
			add_filter( 'afwc_order_details', array( $this, 'woocommerce_order_details' ), 10, 2 );
		}

		/**
		 * Get WooCommerce Storewide sales
		 *
		 * @param  float $storewide_sales Storewide sales.
		 * @param  array $post_ids The order ids.
		 * @return float
		 */
		public function woocommerce_storewide_sales( $storewide_sales = 0, $post_ids = array() ) {

			global $wpdb;

			if ( ! empty( $post_ids ) ) {
				$option_nm = 'afwc_woo_storewise_sales_post_ids_' . uniqid();
				update_option( $option_nm, implode( ',', $post_ids ), 'no' );

				if ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
					$woocommerce_sales = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( total_amount ), 0) AS order_total 
												                        FROM {$wpdb->prefix}wc_orders
												                        WHERE type = %s
												                        AND FIND_IN_SET ( id, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
															'shop_order',
															$option_nm
														)
					);
				} else {
					$woocommerce_sales = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( meta_value ), 0) AS order_total 
												                        FROM {$wpdb->posts} AS posts
												                       JOIN {$wpdb->postmeta} AS postmeta
												                            ON ( posts.ID = postmeta.post_id 
												                            	AND postmeta.meta_key = %s 
																				AND posts.post_type = %s) 
												                        WHERE FIND_IN_SET ( post_id, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
															'_order_total',
															'shop_order',
															$option_nm
														)
					);
				}

				delete_option( $option_nm );
			} elseif ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
					$woocommerce_sales = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( total_amount ), 0) AS order_total 
												                        FROM {$wpdb->prefix}wc_orders
												                        WHERE type = %s",
															'shop_order'
														)
					);
			} else {
				$woocommerce_sales = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(SUM( postmeta.meta_value ), 0) AS order_total 
												                        FROM {$wpdb->posts} AS posts 
												                        JOIN {$wpdb->postmeta} AS postmeta 
												                            ON ( posts.ID = postmeta.post_id 
												                            	AND postmeta.meta_key = %s 
																				posts.post_type = %s )",
														'_order_total',
														'shop_order'
													)
				);
			}

			if ( ! empty( $woocommerce_sales ) ) {
				$storewide_sales = floatval( $storewide_sales ) + floatval( $woocommerce_sales );
			}

			return $storewide_sales;
		}

		/**
		 * Get affiliates sales
		 *
		 * @param  float $affiliates_sales Affiliate sales.
		 * @param  array $post_ids The order ids.
		 * @return float
		 */
		public function woocommerce_affiliates_sales( $affiliates_sales = 0, $post_ids = array() ) {
			// Calling storewide_sales because post ids are already filtered order ids via affiliates.
			return $this->woocommerce_storewide_sales( $affiliates_sales, $post_ids );
		}

		/**
		 * Get affiliate refunds
		 *
		 * @param  float $affiliates_refund Affiliate refunds.
		 * @param  array $post_ids The order ids.
		 * @return float
		 */
		public function woocommerce_affiliates_refund( $affiliates_refund = 0, $post_ids = array() ) {

			global $wpdb;

			if ( ! empty( $post_ids ) ) {

				$option_nm = 'afwc_woo_storewise_refunds_post_ids_' . uniqid();
				update_option( $option_nm, implode( ',', $post_ids ), 'no' );

				if ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
					$woocommerce_refunds = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( total_amount ), 0) AS order_total 
				                                                    FROM {$wpdb->prefix}wc_orders AS wco
												                        WHERE wco.type = %s
												                        	AND wco.status = %s
												                        	AND FIND_IN_SET ( wco.id, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
															'shop_order',
															'wc-refunded',
															$option_nm
														)
					);
				} else {
					$woocommerce_refunds = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( postmeta.meta_value ), 0) AS order_total 
				                                                    FROM {$wpdb->posts} AS posts 
				                                                    	LEFT JOIN {$wpdb->postmeta} AS postmeta 
												                            ON ( posts.ID = postmeta.post_id 
												                            	AND postmeta.meta_key = %s ) 
												                        WHERE posts.post_type = %s 
												                        	AND posts.post_status = %s
												                        	AND FIND_IN_SET ( posts.ID, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
															'_order_total',
															'shop_order',
															'wc-refunded',
															$option_nm
														)
					);
				}

				delete_option( $option_nm );
			} elseif ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
					$woocommerce_refunds = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(SUM( total_amount ), 0) AS order_total 
												                        FROM {$wpdb->prefix}wc_orders AS wco
												                        WHERE wco.type = %s 
												                        	AND wco.status = %s",
															'shop_order',
															'wc-refunded'
														)
					);
			} else {
				$woocommerce_refunds = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(SUM( postmeta.meta_value ), 0) AS order_total 
												                        FROM {$wpdb->posts} AS posts 
												                        LEFT JOIN {$wpdb->postmeta} AS postmeta 
												                            ON ( posts.ID = postmeta.post_id 
												                            	AND postmeta.meta_key = %s ) 
												                        WHERE posts.post_type = %s 
												                        	AND posts.post_status = %s",
														'_order_total',
														'shop_order',
														'wc-refunded'
													)
				);
			}

			if ( ! empty( $woocommerce_refunds ) ) {
				$affiliates_refund = $affiliates_refund + $woocommerce_refunds;
			}

			return $affiliates_refund;
		}

		/**
		 * Get all customer ids
		 *
		 * @param  array $all_customer_ids customer ids.
		 * @param  array $args extra arguments.
		 * @return array $all_customer_ids
		 */
		public function woocommerce_all_customer_ids( $all_customer_ids, $args ) {

			global $wpdb;
			$from                = ( ! empty( $args['from_date'] ) ) ? $args['from_date'] : '';
			$to                  = ( ! empty( $args['to_date'] ) ) ? $args['to_date'] : '';
			$prefixed_statuses   = afwc_get_prefixed_order_statuses();
			$option_order_status = 'afwc_order_stat_' . uniqid();
			update_option( $option_order_status, implode( ',', $prefixed_statuses ), 'no' );

			if ( ! empty( $from ) && ! empty( $to ) ) {
				if ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
					$all_customer_ids = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(COUNT( DISTINCT customer_id ), 0) AS customer_ids 
													                        FROM {$wpdb->prefix}wc_orders
													                        WHERE ( type = %s
													                        		AND customer_id > 0
													                        		AND FIND_IN_SET ( CONVERT( status USING %s ) COLLATE %s, ( SELECT CONVERT( option_value USING %s ) COLLATE %s
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )
													                        		AND date_created_gmt BETWEEN %s AND %s )",
															'shop_order',
															AFWC_SQL_CHARSET,
															AFWC_SQL_COLLATION,
															AFWC_SQL_CHARSET,
															AFWC_SQL_COLLATION,
															$option_order_status,
															$from,
															$to
														)
					);
				} else {
					$all_customer_ids = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(COUNT( DISTINCT postmeta.meta_value ), 0) AS customer_ids 
													                        FROM {$wpdb->postmeta} AS postmeta
													                        	JOIN {$wpdb->posts} AS posts 
													                        		ON ( posts.ID = postmeta.post_id 
													                        			AND postmeta.meta_key = %s 
													                        			AND postmeta.meta_value > 0) 
													                        WHERE posts.post_type = %s 
													                        	AND FIND_IN_SET ( CONVERT(posts.post_status USING %s) COLLATE %s, ( SELECT CONVERT(option_value USING %s) COLLATE %s
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )
													                        	AND posts.post_date BETWEEN %s AND %s",
															'_customer_user',
															'shop_order',
															AFWC_SQL_CHARSET,
															AFWC_SQL_COLLATION,
															AFWC_SQL_CHARSET,
															AFWC_SQL_COLLATION,
															$option_order_status,
															$from,
															$to
														)
					);
				}
			} elseif ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
					$all_customer_ids = $wpdb->get_var( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT IFNULL(COUNT( DISTINCT customer_id ), 0) AS customer_ids 
													                        FROM {$wpdb->prefix}wc_orders AS wco
													                        WHERE ( type = %s
													                        		AND customer_id > 0
													                        		AND FIND_IN_SET ( CONVERT ( status USING %s ) COLLATE %s, ( SELECT CONVERT( option_value USING %s ) COLLATE %s
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) ) )",
															'shop_order',
															AFWC_SQL_CHARSET,
															AFWC_SQL_COLLATION,
															AFWC_SQL_CHARSET,
															AFWC_SQL_COLLATION,
															$option_order_status
														)
					);
			} else {
				$all_customer_ids = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(COUNT( DISTINCT postmeta.meta_value ), 0) AS customer_ids 
													                        FROM {$wpdb->postmeta} AS postmeta
													                        	JOIN {$wpdb->posts} AS posts 
													                        		ON ( posts.ID = postmeta.post_id 
													                        			AND postmeta.meta_key = %s
													                        			AND postmeta.meta_value > 0 ) 
													                        WHERE posts.post_type = %s 
													                        	AND FIND_IN_SET ( CONVERT(posts.post_status USING %s) COLLATE %s, ( SELECT CONVERT(option_value USING %s) COLLATE %s
																											FROM {$wpdb->prefix}options
																											WHERE option_name = %s ) )",
														'_customer_user',
														'shop_order',
														AFWC_SQL_CHARSET,
														AFWC_SQL_COLLATION,
														AFWC_SQL_CHARSET,
														AFWC_SQL_COLLATION,
														$option_order_status
													)
				);
			}

			delete_option( $option_order_status );
			return intval( $all_customer_ids );
		}

		/**
		 * WooCommerce order details
		 *
		 * @param  array $affiliates_order_details Affiliates order details.
		 * @param  array $order_ids                Order ids.
		 * @return array $affiliates_order_details
		 */
		public function woocommerce_order_details( $affiliates_order_details = array(), $order_ids = array() ) {
			global $wpdb;
			if ( ! empty( $affiliates_order_details ) ) {

				if ( count( $order_ids ) > 0 ) {

					$option_nm = 'afwc_woo_order_details_order_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $order_ids ), 'no' );

					if ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
						$orders = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT id as ID, status as post_status
																FROM {$wpdb->prefix}wc_orders
																WHERE FIND_IN_SET ( id, ( SELECT option_value
																							FROM {$wpdb->prefix}options
																							WHERE option_name = %s ) )",
														$option_nm
													),
							'ARRAY_A'
						);
					} else {
						$orders = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT ID, post_status 
																	FROM {$wpdb->posts}
																	WHERE FIND_IN_SET ( ID, ( SELECT option_value
																							FROM {$wpdb->prefix}options
																							WHERE option_name = %s ) )",
														$option_nm
													),
							'ARRAY_A'
						);
					}

					delete_option( $option_nm );

					if ( function_exists( 'wc_get_order_statuses' ) ) {
						$order_statuses = wc_get_order_statuses();
					}

					$order_id_to_status = array();
					foreach ( $orders as $order ) {
						$order_id_to_status[ $order['ID'] ] = ( ! empty( $order_statuses[ $order['post_status'] ] ) ) ? $order_statuses[ $order['post_status'] ] : $order['post_status'];
					}
				}

				foreach ( $affiliates_order_details as $order_id => $order_details ) {
					$id = $order_details['order_id'];
					$affiliates_order_details[ $order_id ]['order_status'] = isset( $order_id_to_status[ $id ] ) ? $order_id_to_status[ $id ] : 'wc-deleted';
				}
			}

			return $affiliates_order_details;
		}
	}

}

new AFWC_Integration_WooCommerce();
