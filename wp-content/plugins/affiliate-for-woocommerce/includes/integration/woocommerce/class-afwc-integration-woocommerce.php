<?php
/**
 * Main class for Affiliate For WooCommerce Integration
 *
 * @since       1.0.0
 * @version     1.2.3
 *
 * @package     affiliate-for-woocommerce/includes/integration/woocommerce/
 */

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
			if ( afwc_is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
				if ( WCS_AFWC_Compatibility::is_wcs_gte_20() ) {
					add_filter( 'wcs_renewal_order_items', array( $this, 'afwc_modify_wcs_renewal_order' ), 10, 3 );
				} else {
					add_filter( 'woocommerce_subscriptions_renewal_order_items', array( $this, 'afwc_modify_renewal_order' ), 10, 5 );
				}
			}
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

			$prefixed_statuses   = afwc_get_prefixed_order_statuses();
			$option_order_status = 'afwc_order_status_' . uniqid();
			update_option( $option_order_status, implode( ',', $prefixed_statuses ), 'no' );

			if ( ! empty( $post_ids ) ) {
				$option_nm = 'afwc_woo_storewise_sales_post_ids_' . uniqid();
				update_option( $option_nm, implode( ',', $post_ids ), 'no' );

				$woocommerce_sales = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(SUM( meta_value ), 0) AS order_total 
											                        FROM {$wpdb->posts} AS posts 
											                       JOIN {$wpdb->postmeta} AS postmeta 
											                            ON ( posts.ID = postmeta.post_id 
											                            	AND postmeta.meta_key = %s ) 
											                        WHERE posts.post_type = %s 
											                        	AND FIND_IN_SET ( posts.post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )
											                        	AND FIND_IN_SET ( post_id, ( SELECT option_value
																									FROM {$wpdb->prefix}options
																									WHERE option_name = %s ) )",
														'_order_total',
														'shop_order',
														$option_order_status,
														$option_nm
													)
				);

				delete_option( $option_nm );
			} else {
				$woocommerce_sales = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(SUM( postmeta.meta_value ), 0) AS order_total 
											                        FROM {$wpdb->posts} AS posts 
											                        JOIN {$wpdb->postmeta} AS postmeta 
											                            ON ( posts.ID = postmeta.post_id 
											                            	AND postmeta.meta_key = %s ) 
											                        WHERE posts.post_type = %s 
											                        	AND FIND_IN_SET ( posts.post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
														'_order_total',
														'shop_order',
														$option_order_status
													)
				);
			}

			delete_option( $option_order_status );

			if ( ! empty( $woocommerce_sales ) ) {
				$storewide_sales = $storewide_sales + $woocommerce_sales;
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

				$woocommerce_refunds = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT SUM( postmeta.meta_value ) AS order_total 
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

				delete_option( $option_nm );
			} else {
				$woocommerce_refunds = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT SUM( postmeta.meta_value ) AS order_total 
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
		 * @param  string $from The from datetime.
		 * @param  string $to The to datetime.
		 * @return array $all_customer_ids
		 */
		public function woocommerce_all_customer_ids( $from = '', $to = '' ) {

			global $wpdb;

			$prefixed_statuses   = afwc_get_prefixed_order_statuses();
			$option_order_status = 'afwc_order_stat_' . uniqid();
			update_option( $option_order_status, implode( ',', $prefixed_statuses ), 'no' );

			if ( ! empty( $from ) && ! empty( $to ) ) {
				$all_customer_ids = $wpdb->get_var( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT IFNULL(COUNT( DISTINCT postmeta.meta_value ), 0) AS customer_ids 
												                        FROM {$wpdb->postmeta} AS postmeta
												                        	JOIN {$wpdb->posts} AS posts 
												                        		ON ( posts.ID = postmeta.post_id 
												                        			AND postmeta.meta_key = %s 
												                        			AND postmeta.meta_value > 0) 
												                        WHERE posts.post_type = %s 
												                        	AND FIND_IN_SET ( posts.post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )
												                        	AND posts.post_date BETWEEN %s AND %s",
														'_customer_user',
														'shop_order',
														$option_order_status,
														$from . ' 00:00:00',
														$to . ' 23:59:59'
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
												                        	AND FIND_IN_SET ( posts.post_status, ( SELECT option_value
																										FROM {$wpdb->prefix}options
																										WHERE option_name = %s ) )",
														'_customer_user',
														'shop_order',
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

					delete_option( $option_nm );

					if ( function_exists( 'wc_get_order_statuses' ) ) {
						$order_statuses = wc_get_order_statuses();
					}

					$order_id_to_status = array();
					foreach ( $orders  as $order ) {
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

		/**
		 * Modify renewal order
		 *
		 * @param  mixed           $order_items Order items.
		 * @param  WC_Order        $renewal_order The renewal order.
		 * @param  WC_Subscription $subscription The subscription.
		 * @return mixed
		 */
		public function afwc_modify_wcs_renewal_order( $order_items = null, $renewal_order = null, $subscription = null ) {
			$original_order_id = ( ! empty( $subscription->get_parent() ) && is_object( $subscription->get_parent() ) && is_callable( array( $subscription->get_parent(), 'get_id' ) ) ) ? $subscription->get_parent()->get_id() : 0;
			$renewal_order_id  = ( ! empty( $renewal_order ) && is_object( $renewal_order ) && is_callable( array( $renewal_order, 'get_id' ) ) ) ? $renewal_order->get_id() : 0;
			$order_items       = $this->afwc_modify_renewal_order( $order_items, $original_order_id, $renewal_order_id );
			return $order_items;
		}

		/**
		 * Modify renewal order
		 *
		 * @param  mixed   $order_items Order items.
		 * @param  integer $original_order_id The original order id.
		 * @param  integer $renewal_order_id The renewal order id.
		 * @param  integer $product_id The product id.
		 * @param  string  $new_order_role The order role.
		 * @return mixed
		 */
		public function afwc_modify_renewal_order( $order_items = null, $original_order_id = null, $renewal_order_id = null, $product_id = null, $new_order_role = null ) {
			if ( ! empty( $renewal_order_id ) ) {
				$is_commission_recorded = get_post_meta( $renewal_order_id, 'is_commission_recorded', true );
				if ( 'yes' === $is_commission_recorded ) {
					if ( get_option( 'is_recurring_commission' ) === 'yes' ) {
						update_post_meta( $renewal_order_id, 'is_commission_recorded', 'no' );
					}
				}
			}
			return $order_items;
		}

	}

}

new AFWC_Integration_WooCommerce();
