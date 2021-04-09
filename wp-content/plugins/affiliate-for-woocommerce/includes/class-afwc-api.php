<?php
/**
 * Main class for Affiliate For WooCommerce Referral
 *
 * @since       1.10.0
 * @version     1.4.0
 *
 * @package     affiliate-for-woocommerce/includes/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_API' ) ) {

	/**
	 * Affiliate For WooCommerce Referral
	 */
	class AFWC_API {

		/**
		 * Variable to hold instance of AFWC_API
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		public function __construct() {
			/*
			 * Used "woocommerce_checkout_update_order_meta" action instead of "woocommerce_new_order" hook. Because don't get the whole
			 * order data on "woocommerce_new_order" hook.
			 *
			 * Checked woocommerce "includes/class-wc-checkout.php" file and then after use this hook
			 *
			 * Track referral before completion of Order with status "Pending"
			 * When Order Complets, Change referral status from Pending to Unpaid
			 */
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'track_conversion' ), 10, 1 );

			if ( afwc_is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
				if ( WCS_AFWC_Compatibility::is_wcs_gte_20() ) {
					add_filter( 'wcs_renewal_order_created', array( $this, 'handle_renewal_order_created' ), 10, 2 );
				} else {
					add_action( 'woocommerce_subscriptions_renewal_order_created', array( $this, 'handle_subscription' ), 10, 4 );
				}
			}

			// Update referral when order status changes.
			add_action( 'woocommerce_order_status_changed', array( $this, 'update_referral_status' ), 11, 3 );

			add_filter( 'afwc_conversion_data', array( $this, 'handle_order_complete' ) );
		}

		/**
		 * Get single instance of AFWC_API
		 *
		 * @return AFWC_API Singleton object of AFWC_API
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Function to track visitor
		 *
		 * @param integer $affiliate_id The affiliate id.
		 * @param integer $visitor_id The visitor_id.
		 * @param string  $source The source of hit.
		 * @param mixed   $params extra params to override default params.
		 */
		public function track_visitor( $affiliate_id, $visitor_id = 0, $source = 'link', $params = array() ) {
			global $wpdb;

			if ( 0 !== $affiliate_id ) {
				// prepare vars.
				$current_user_id = get_current_user_id();
				$visitor_id      = ( 0 !== $visitor_id ) ? $visitor_id : $current_user_id;
				$now             = time();
				$date            = gmdate( 'Y-m-d', Affiliate_For_WooCommerce::get_offset_timestamp( $now ) );
				$time            = gmdate( 'H:i:s', Affiliate_For_WooCommerce::get_offset_timestamp( $now ) );
				$datetime        = gmdate( 'Y-m-d H:i:s', Affiliate_For_WooCommerce::get_offset_timestamp( $now ) );

				// check type of refarral.
				if ( function_exists( 'WC' ) ) {
					$cart = WC()->cart;
					if ( is_object( $cart ) && is_callable( array( $cart, 'is_empty' ) ) && ! $cart->is_empty() ) {
						$afwc         = Affiliate_For_WooCommerce::get_instance();
						$used_coupons = ( is_callable( array( $cart, 'get_applied_coupons' ) ) ) ? $cart->get_applied_coupons() : array();
						if ( ! empty( $affiliate_id ) && ! empty( $used_coupons ) ) {
							$type = $afwc->get_referral_type( $affiliate_id, $used_coupons );
						}
					}
				}

				// Get IP address.
				$ip_address = ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) ? wc_clean( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : ''; // phpcs:ignore
				$ip_int     = ip2long( $ip_address );
				$ip_int     = ( PHP_INT_SIZE > 8 ) ? $ip_int : sprintf( '%u', $ip_int );
				$ip_int     = ( ! empty( $ip_int ) ) ? $ip_int : 0;
				$type       = ! empty( $type ) ? $type : $source;

				$values = array( $affiliate_id, $datetime, $ip_int, $current_user_id, $type, $params['campaign_id'] );

				$wpdb->query( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"INSERT INTO {$wpdb->prefix}afwc_hits ( affiliate_id, datetime, ip, user_id, type, campaign_id ) VALUES ( %d, %s, %d, %d, %s, %d ) ON DUPLICATE KEY
									UPDATE count = count + 1",
						$values
					)
				);

			}
		}

		/**
		 * Function to track conversion (referral)
		 *
		 * @param integer $oid object id for which converion recorder like orderid, pageid etc.
		 * @param integer $affiliate_id The affiliate id.
		 * @param string  $type The type of conversion e.g order, pageview etc.
		 * @param mixed   $params extra params to override default params.
		 */
		public function track_conversion( $oid, $affiliate_id = 0, $type = 'order', $params = array() ) {

			global $wpdb;

			if ( 0 !== $oid ) {
				$conversion_data['affiliate_id'] = $affiliate_id;
				$conversion_data['oid']          = $oid;
				$now                             = gmdate( 'Y-m-d H:i:s', Affiliate_For_WooCommerce::get_offset_timestamp() );
				$conversion_data['datetime']     = $now;
				$conversion_data['description']  = ! empty( $params['description'] ) ? $params['description'] : '';
				$ip_address                      = ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) ? wc_clean( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : ''; // phpcs:ignore
				$ip_int                          = ip2long( $ip_address );
				$ip_int                          = ( PHP_INT_SIZE > 8 ) ? $ip_int : sprintf( '%u', $ip_int );
				$ip_int                          = ( ! empty( $ip_int ) ) ? $ip_int : 0;
				$conversion_data['ip']           = ! empty( $params['ip'] ) ? $params['ip'] : $ip_int;
				$conversion_data['params']       = $params;

				$conversion_data = apply_filters( 'afwc_conversion_data', $conversion_data );
				if ( $conversion_data['affiliate_id'] ) {
					$values = array( $conversion_data['affiliate_id'], $conversion_data['oid'], $conversion_data['datetime'], $conversion_data['description'], $conversion_data['ip'], $conversion_data['user_id'], $conversion_data['amount'], $conversion_data['currency_id'], $conversion_data['data'], $conversion_data['status'], $conversion_data['type'], $conversion_data['reference'], $conversion_data['campaign_id'] );
					// track in the db.
					$referral_added = $wpdb->query( // phpcs:ignore
							$wpdb->prepare( // phpcs:ignore
								"INSERT INTO {$wpdb->prefix}afwc_referrals ( affiliate_id, post_id, datetime, description, ip, user_id, amount, currency_id, data, status, type, reference, campaign_id ) VALUES ( %d, %d, %s, %s, %d, %s, %s, %s, %s, %s, %s, %s, %d )",
								$values
							)
						); // phpcs:ignore
					if ( false !== $referral_added ) { // phpcs:ignore
						update_post_meta( $conversion_data['oid'], 'is_commission_recorded', 'yes' );

						// Send new conversion email to affiliate if enabled.
						$mailer = WC()->mailer();
						if ( $mailer->emails['AFWC_New_Conversion_Email']->is_enabled() ) {
							// Prepare args.
							$args = array(
								'affiliate_id'            => $conversion_data['affiliate_id'],
								'order_commission_amount' => $conversion_data['amount'],
								'currency_id'             => $conversion_data['currency_id'],
								'order_id'                => $conversion_data['oid'],
							);
							// Trigger email.
							do_action( 'afwc_new_conversion_received_email', $args );
						}
					}
				}
			}

		}

		/**
		 * Function to track commision
		 *
		 * @param integer $affiliate_id The affiliate id.
		 * @param integer $amount amount to be add/remove for commision.
		 * @param mixed   $params extra params to override default params.
		 */
		private function track_commission( $affiliate_id, $amount, $params ) {
			global $wpdb;

			$now = gmdate( 'Y-m-d H:i:s', Affiliate_For_WooCommerce::get_offset_timestamp() );

			$commission_added = $wpdb->query( // phpcs:ignore
				$wpdb->prepare(
						"UPDATE {$wpdb->prefix}afwc_referrals SET amount = %d, datetime = %s WHERE affiliate_id = %d", // phpcs:ignore
					$amount,
					$now,
					$affiliate_id
				)
				); // phpcs:ignore

		}

		/**
		 * Function to calculate commission
		 *
		 * @param integer $order_id The order id.
		 * @param integer $affiliate_id The affiliate id.
		 * @return integer $amount  The amount after calculation.
		 */
		public function calculate_commission( $order_id, $affiliate_id ) {
			global $wpdb;

			$set_commission         = array();
			$remaining_items        = array();
			$ordered_plans          = array();
			$amount_to_remove       = 0;
			$order                  = wc_get_order( $order_id );
			$order_user_id          = get_post_meta( $order_id, '_customer_user', true );
			$is_commission_recorded = get_post_meta( $order_id, 'is_commission_recorded', true );
			$amount                 = null;

			$storewide_percentage = get_option( 'afwc_storewide_commission', 0 );
			$storewide_percentage = ( ! empty( $storewide_percentage ) ) ? floatval( $storewide_percentage ) : 0;

			if ( 'yes' === $is_commission_recorded ) {
				return false;
			} else {
				// check if commission already recorded in table but not updated in postmeta.
				$order_count = $wpdb->get_var( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"SELECT COUNT(post_id)
									FROM {$wpdb->prefix}afwc_referrals
									WHERE post_id = %d AND affiliate_id = %d",
						$order_id,
						$affiliate_id
					)
				);
				if ( $order_count > 0 ) {
					return false;
				}
			}

			$items                = $order->get_items();
			$item_total_map       = array();
			$category_prod_id_map = array();
			$item_quantity_map    = array();
			foreach ( $items as $item ) {
				$product_id                    = ( ! empty( $item->get_variation_id() ) ) ? $item->get_variation_id() : $item->get_product_id();
				$item_total_map[ $product_id ] = $item['line_total'];
				if ( ! empty( $item['quantity'] ) && $item['quantity'] > 1 ) {
					$item_quantity_map[ $product_id ] = $item['quantity'];
				}
				$prod_categories = wc_get_product_cat_ids( $item->get_product_id() );
				foreach ( $prod_categories as $cat ) {
					$category_prod_id_map[ $cat ][] = $product_id;
				}
			}
			$product_cat_ids        = array_keys( $category_prod_id_map );
			$affiliate_tag          = wp_get_object_terms( $affiliate_id, 'afwc_user_tags', array( 'fields' => 'id=>name' ) );
			$afwc_excluded_products = get_option( 'afwc_storewide_excluded_products' );

			// build the context for rule validation for this order.
			$context['affiliate_id']         = $affiliate_id;
			$context['product_id']           = array_keys( $item_total_map );
			$context['affiliate_tag']        = array_keys( $affiliate_tag );
			$context['product_category']     = $product_cat_ids;
			$context['category_prod_id_map'] = $category_prod_id_map;

			// set commission for already excluded.
			if ( ! empty( $afwc_excluded_products ) ) {
				foreach ( $afwc_excluded_products as $id ) {
					$set_commission[ $id ] = 0;
				}
			}
			$afwc_plans = get_commission_plans( 'Active' );
			// reorder as per option save.
			$plan_order = get_option( 'afwc_plan_order', array() );
			if ( ! empty( $plan_order ) ) {

				foreach ( $plan_order as $k ) {
					$current_plan = array_filter(
						$afwc_plans,
						function( $x ) use ( $k ) {
							$k       = absint( $k );
							$x['id'] = absint( $x['id'] );
							return $x['id'] === $k;
						}
					);
					if ( count( $current_plan ) > 0 ) {
						$current_plan = reset( $current_plan );
					}
					if ( ! empty( $current_plan ) ) {
						$ordered_plans[] = $current_plan;
					}
				}
			} else {
				$ordered_plans = $afwc_plans;
			}

			if ( ! empty( $ordered_plans ) ) {
				foreach ( $ordered_plans as $plan ) {
					$plan_context         = new AFWC_Rule_Context( $context );
					$props                = json_decode( $plan['rules'], true );
					$props['amount']      = $plan['amount'];
					$props['type']        = $plan['type'];
					$plan_object          = new AFWC_Plan( $props );
					$action_for_remaining = ( ! empty( $plan['action_for_remaining'] ) ) ? $plan['action_for_remaining'] : 'continue';

					if ( $plan_object->validate( $plan_context ) ) {
						$valid_item_ids = $plan_context->get_valid_product_ids();
						$valid_item_ids = ( ! empty( $plan['apply_to'] ) && 'first' === $plan['apply_to'] ) ? array( reset( $valid_item_ids ) ) : $valid_item_ids;
						foreach ( $valid_item_ids as $id ) {

							if ( ! isset( $set_commission[ $id ] ) ) {
								if ( ! empty( $plan['amount'] ) ) {
									if ( 'Percentage' === $plan['type'] ) {
										$amount = ( $item_total_map[ $id ] * $plan['amount'] ) / 100;
									} elseif ( 'Flat' === $plan['type'] ) {
										$amount = $plan['amount'];
										if ( ! empty( $item_quantity_map[ $id ] ) ) {
											$amount = $amount * $item_quantity_map[ $id ];
										}
									}
								}
								$set_commission[ $id ] = ! empty( $amount ) ? $amount : 0;
							}
						}
						// intersection check.
						$remaining_items = array_diff( $context['product_id'], array_keys( $set_commission ) );
						if ( 0 === count( $remaining_items ) ) {
							break;
						}

						if ( 'continue' === $action_for_remaining ) {
							if ( end( $ordered_plans ) === $plan ) {
								foreach ( $remaining_items as $id ) {
									$set_commission[ $id ] = ( $item_total_map[ $id ] * $storewide_percentage ) / 100;
								}
							}
						} else {
							foreach ( $remaining_items as $id ) {
								if ( 'default' === $action_for_remaining ) {
									$set_commission[ $id ] = ( $item_total_map[ $id ] * $storewide_percentage ) / 100;
								} elseif ( 'zero' === $action_for_remaining ) {
									$set_commission[ $id ] = 0;
								}
							}
						}
					}
				}
			}

			$amount = array_sum( $set_commission );

			// Fallback to storewide commission if rule based commission is not calculated.
			if ( empty( $amount ) ) {
				$total_for_commission = $order->get_subtotal() - $order->get_total_discount();

				// remove excluded item price from total.
				if ( ! empty( $set_commission ) ) {
					foreach ( $set_commission as $id => $val ) {
						$amount_to_remove += ! empty( $item_total_map[ $id ] ) ? $item_total_map[ $id ] : 0;
					}
					$total_for_commission = $total_for_commission - $amount_to_remove;
				}
				$amount = ( $total_for_commission * $storewide_percentage ) / 100;
			}

			// check if none of the plan matches.
			return $amount;

		}

		/**
		 * Record referral when renewal order created
		 *
		 * @param  WC_Order        $renewal_order The renewal order.
		 * @param  WC_Subscription $subscription  The subscription.
		 * @return WC_Order
		 */
		public function handle_renewal_order_created( $renewal_order = null, $subscription = null ) {
			$this->handle_subscription( $renewal_order );
			return $renewal_order;
		}

		/**
		 * Record referral when subscription is created
		 *
		 * @param  WC_Order $renewal_order  The renewal order.
		 * @param  WC_Order $original_order The original order.
		 * @param  integer  $product_id     The product id.
		 * @param  string   $new_order_role The new order role.
		 */
		public function handle_subscription( $renewal_order = null, $original_order = null, $product_id = null, $new_order_role = null ) {
			$order_id = ( is_object( $renewal_order ) && is_callable( array( $renewal_order, 'get_id' ) ) ) ? $renewal_order->get_id() : 0;
			$this->track_conversion( $order_id );
		}

		/**
		 * Record referral
		 *
		 * @param mixed $conversion_data .
		 */
		public function handle_order_complete( $conversion_data ) {
			global $wpdb;

			$order_id = ( ! empty( $conversion_data['oid'] ) ) ? $conversion_data['oid'] : 0;

			if ( 0 !== $order_id ) {
				$affiliate_id = ( ! empty( $conversion_data['affiliate_id'] ) ) ? $conversion_data['affiliate_id'] : get_referrer_id();
				$campaign_id  = get_campaign_id();
				$amount       = $this->calculate_commission( $order_id, $affiliate_id );
				$currency_id  = get_post_meta( $order_id, '_order_currency', true );

				$status      = AFWC_REFERRAL_STATUS_DRAFT;
				$description = '';
				$data        = '';
				$type        = '';
				$reference   = '';

				// Handle Subscription.
				if ( afwc_is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) && get_option( 'is_recurring_commission' ) === 'yes' ) {
					$renewal_order    = wc_get_order( $order_id );
					$renewal_order_id = ( is_object( $renewal_order ) && is_callable( array( $renewal_order, 'get_id' ) ) ) ? $renewal_order->get_id() : 0;
					if ( WCS_AFWC_Compatibility::is_wcs_gte_20() ) {
						$is_renewal_order = wcs_order_contains_renewal( $renewal_order_id );
					} else {
						$is_renewal_order = WC_Subscriptions_Renewal_Order::is_renewal( $renewal_order_id );
					}
					if ( $is_renewal_order ) {
						if ( WCS_AFWC_Compatibility::is_wcs_gte_20() ) {
							$subscription = wcs_get_subscriptions_for_renewal_order( $renewal_order );
							if ( ! empty( $subscription ) ) {
								reset( $subscription );
								$subscription    = current( $subscription );
								$parent_order_id = ( is_object( $subscription->get_parent() ) && is_callable( array( $subscription->get_parent(), 'get_id' ) ) ) ? $subscription->get_parent()->get_id() : 0;
							}
						} else {
							$parent_order_id = WC_Subscriptions_Renewal_Order::get_parent_order_id( $renewal_order );
						}
						if ( ! empty( $parent_order_id ) ) {
							$affiliate_id = $wpdb->get_var( $wpdb->prepare( "SELECT affiliate_id FROM {$wpdb->prefix}afwc_referrals WHERE post_id = %d", $parent_order_id ) ); // phpcs:ignore
						}
					}
				}
				if ( $affiliate_id ) {
					$afwc         = Affiliate_For_WooCommerce::get_instance();
					$order        = wc_get_order( $order_id );
					$used_coupons = array();
					if ( is_object( $order ) ) {
						if ( $afwc->is_wc_gte_37() ) {
							$used_coupons = is_callable( array( $order, 'get_coupon_codes' ) ) ? $order->get_coupon_codes() : array();
						} else {
							$used_coupons = is_callable( array( $order, 'get_used_coupons' ) ) ? $order->get_used_coupons() : array();
						}
					}
					$type = $afwc->get_referral_type( $affiliate_id, $used_coupons );

					// prepare conersion_data.
					$user_id                         = get_post_meta( $order_id, '_customer_user', true );
					$conversion_data['user_id']      = ! empty( $user_id ) ? $user_id : 0;
					$conversion_data['amount']       = $amount;
					$conversion_data['type']         = $type;
					$conversion_data['status']       = $status;
					$conversion_data['reference']    = $reference;
					$conversion_data['data']         = $data;
					$conversion_data['currency_id']  = $currency_id;
					$conversion_data['affiliate_id'] = $affiliate_id;
					$conversion_data['campaign_id']  = $campaign_id;
				}
			}

			return $conversion_data;
		}

		/**
		 * Update referral payout status.
		 *
		 * @param int    $order_id The order id.
		 * @param string $old_status Old order status.
		 * @param string $new_status New order status.
		 */
		public function update_referral_status( $order_id, $old_status = '', $new_status = '' ) {
			if ( empty( $order_id ) ) {
				return;
			}

			global $wpdb;

			$order_status_updates = false;
			$wc_paid_statuses     = get_afwc_paid_order_status();
			$reject_statuses      = get_afwc_reject_order_status();

			$new_status = ( strpos( $new_status, 'wc-' ) === false ) ? 'wc-' . $new_status : $new_status;

			$order  = wc_get_order( $order_id );
			$status = ( $order->get_total() > 0 ) ? AFWC_REFERRAL_STATUS_UNPAID : AFWC_REFERRAL_STATUS_PAID;

			// update referral if not paid or rejected.
			if ( in_array( $new_status, $wc_paid_statuses, true ) ) {
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET status = %s, order_status = %s WHERE post_id = %d AND status NOT IN (%s, %s)", $status, $new_status, $order_id, AFWC_REFERRAL_STATUS_PAID, AFWC_REFERRAL_STATUS_REJECTED ) ); // phpcs:ignore
				$order_status_updates = true;
			} elseif ( in_array( $new_status, $reject_statuses, true ) ) {
				// reject referral if not paid.
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET status = %s, order_status = %s WHERE post_id = %d AND status NOT IN (%s)", AFWC_REFERRAL_STATUS_REJECTED, $new_status, $order_id, AFWC_REFERRAL_STATUS_PAID ) ); // phpcs:ignore
				$order_status_updates = true;
			}

			if ( ! $order_status_updates ) {
				// set new order status in referral table.
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET order_status = %s WHERE post_id = %d", $new_status, $order_id ) ); // phpcs:ignore
			}

		}

	}
}

AFWC_API::get_instance();
