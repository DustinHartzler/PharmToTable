<?php
/**
 * Main class for Affiliate For WooCommerce Referral
 *
 * @package  affiliate-for-woocommerce/includes/
 * @since    1.10.0
 * @version  1.9.4
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
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'track_conversion' ) );
			// Support for WooCommerce checkout block.
			add_action( 'woocommerce_store_api_checkout_update_order_from_request', array( $this, 'track_conversion' ) );

			if ( class_exists( 'WC_Subscriptions_Core_Plugin' ) || class_exists( 'WC_Subscriptions' ) ) {
				add_filter( 'wcs_renewal_order_created', array( $this, 'handle_renewal_order_created' ), 10, 2 );
				if ( WCS_AFWC_Compatibility::get_instance()->is_wcs_core_gte( '2.5.0' ) ) {
					add_filter( 'wc_subscriptions_renewal_order_data', array( $this, 'do_not_copy_affiliate_meta' ) );
				} else {
					add_filter( 'wcs_renewal_order_meta_query', array( $this, 'do_not_copy_meta' ), 10, 3 );
				}
			}

			// Update referral when order status changes.
			add_action( 'woocommerce_order_status_changed', array( $this, 'update_referral_status' ), 11, 3 );

			add_filter( 'afwc_conversion_data', array( $this, 'get_conversion_data' ) );
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
		 *
		 * @return int Return the id of new visitor record if successfully tracked otherwise 0.
		 */
		public function track_visitor( $affiliate_id = 0, $visitor_id = 0, $source = 'link', $params = array() ) {

			if ( empty( $affiliate_id ) ) {
				return 0;
			}

			global $wpdb;

			// prepare vars.
			$current_user_id = get_current_user_id();

			// check type of referral.
			if ( function_exists( 'WC' ) ) {
				$cart = WC()->cart;
				if ( is_object( $cart ) && is_callable( array( $cart, 'is_empty' ) ) && ! $cart->is_empty() ) {
					$afwc         = Affiliate_For_WooCommerce::get_instance();
					$used_coupons = ( is_callable( array( $cart, 'get_applied_coupons' ) ) ) ? $cart->get_applied_coupons() : array();
					if ( ! empty( $used_coupons ) && is_callable( array( $afwc, 'get_referral_type' ) ) ) {
						$source = $afwc->get_referral_type( $affiliate_id, $used_coupons );
					}
				}
			}

			// Get IP address.
			$ip_address = ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) ? wc_clean( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : ''; // phpcs:ignore
			$ip_int     = ip2long( $ip_address );
			$ip_int     = ( PHP_INT_SIZE > 8 ) ? $ip_int : sprintf( '%u', $ip_int );
			$ip_int     = ( ! empty( $ip_int ) ) ? $ip_int : 0;

			$user_agent = wc_get_user_agent();

			$uri  = ! empty( $_SERVER['REQUEST_URI'] ) ? wc_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; // phpcs:ignore
			$host = ! empty( $_SERVER['HTTP_HOST'] ) ? wc_clean( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : ''; // phpcs:ignore
			$url  = ! empty( $_SERVER['REQUEST_SCHEME'] ) ? wc_clean( wp_unslash( $_SERVER['REQUEST_SCHEME'] ) ) . '://' . $host . $uri : ''; // phpcs:ignore

			$wpdb->insert( // phpcs:ignore
				$wpdb->prefix . 'afwc_hits',
				array(
					'affiliate_id' => intval( $affiliate_id ),
					'datetime'     => gmdate( 'Y-m-d H:i:s' ),
					'ip'           => $ip_int,
					'user_id'      => ! empty( $current_user_id ) ? $current_user_id : 0,
					'type'         => $source,
					'campaign_id'  => ! empty( $params['campaign_id'] ) ? intval( $params['campaign_id'] ) : 0,
					'user_agent'   => ! empty( $user_agent ) ? $user_agent : '',
					'url'          => $url,
				),
				array( '%d', '%s', '%s', '%d', '%s', '%d', '%s', '%s' )
			);

			return ! empty( $wpdb->insert_id ) ? $wpdb->insert_id : 0;
		}

		/**
		 * Function to track conversion (referral)
		 *
		 * @param integer|WC_Order $order           WooCommerce order object or order ID.
		 * @param integer          $affiliate_id    The affiliate ID.
		 * @param string           $type            The type of conversion e.g order, pageview etc.
		 * @param mixed            $params          Extra params to override default params.
		 */
		public function track_conversion( $order = 0, $affiliate_id = 0, $type = 'order', $params = array() ) {

			global $wpdb;

			$oid = 0;

			if ( is_object( $order ) && $order instanceof WC_Order ) {
				$oid = is_callable( array( $order, 'get_id' ) ) ? intval( $order->get_id() ) : 0;
			} elseif ( is_numeric( $order ) ) {
				$oid = intval( $order );
			}

			if ( 0 !== $oid ) {

				$customer = get_current_user_id();

				if ( empty( $customer ) ) {
					$customer = $order instanceof WC_Order && is_callable( array( $order, 'get_billing_email' ) ) ? $order->get_billing_email() : '';
				}

				$conversion_data['affiliate_id'] = apply_filters(
					'afwc_id_for_order',
					! empty( $affiliate_id ) ? $affiliate_id : afwc_get_referrer_id( $customer ),
					array(
						'order_id' => $oid,
						'source'   => $this,
					)
				);

				$conversion_data['oid']         = $oid;
				$conversion_data['datetime']    = gmdate( 'Y-m-d H:i:s' );
				$conversion_data['description'] = ! empty( $params['description'] ) ? $params['description'] : '';
				$ip_address                      = ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) ? wc_clean( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : ''; // phpcs:ignore
				$ip_int                         = ip2long( $ip_address );
				$ip_int                         = ( PHP_INT_SIZE > 8 ) ? $ip_int : sprintf( '%u', $ip_int );
				$ip_int                         = ( ! empty( $ip_int ) ) ? $ip_int : 0;
				$conversion_data['ip']          = ! empty( $params['ip'] ) ? $params['ip'] : $ip_int;
				$conversion_data['params']      = $params;

				$is_valid_for_tracking = $this->is_eligible_for_commission( $oid, $conversion_data['affiliate_id'], $params );
				if ( empty( $is_valid_for_tracking ) ) {
					return;
				}

				$conversion_data = apply_filters( 'afwc_conversion_data', $conversion_data );

				// Return if the affiliate id is empty.
				if ( empty( $conversion_data['affiliate_id'] ) ) {
					return;
				}

				$affiliate = new AFWC_Affiliate( $conversion_data['affiliate_id'] );
				// Check for valid affiliate.
				if ( $affiliate->is_valid() ) {

					// Link the customer for lifetime commission.
					if ( ! is_admin() && ! empty( $customer ) && 'yes' === get_option( 'afwc_enable_lifetime_commissions', 'no' ) ) {
						$affiliate_obj = new AFWC_Affiliate( $conversion_data['affiliate_id'] );
						if ( is_callable( array( $affiliate_obj, 'add_ltc_customer' ) ) ) {
							$affiliate_obj->add_ltc_customer( $customer );
						}
					}

					$values = array(
						'affiliate_id' => intval( $conversion_data['affiliate_id'] ),
						'post_id'      => $conversion_data['oid'],
						'datetime'     => $conversion_data['datetime'],
						'description'  => ! empty( $conversion_data['description'] ) ? $conversion_data['description'] : '',
						'ip'           => $ip_int,
						'user_id'      => $conversion_data['user_id'],
						'amount'       => $conversion_data['amount'],
						'currency_id'  => $conversion_data['currency_id'],
						'data'         => ! empty( $conversion_data['data'] ) ? $conversion_data['data'] : '',
						'status'       => $conversion_data['status'],
						'type'         => $conversion_data['type'],
						'reference'    => ! empty( $conversion_data['reference'] ) ? $conversion_data['reference'] : '',
						'campaign_id'  => $conversion_data['campaign_id'],
						'hit_id'       => ! empty( $conversion_data['hit_id'] ) ? intval( $conversion_data['hit_id'] ) : 0,
					);

					$placeholders = array( '%d', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d' );

					$referral_added = $wpdb->insert( $wpdb->prefix . 'afwc_referrals', $values, $placeholders );  // phpcs:ignore

					$main_referral_id = ! empty( $wpdb->insert_id ) ? intval( $wpdb->insert_id ) : 0;

					// track parent commissions.
					if ( ! empty( $conversion_data['commissions'] ) ) {
						foreach ( $conversion_data['commissions'] as $affiliate_chain_id => $commission_amt ) {
							$values['affiliate_id'] = $affiliate_chain_id;
							$values['amount']       = $commission_amt;
							$values['reference']    = $main_referral_id;
							$referral_added = $wpdb->insert( $wpdb->prefix . 'afwc_referrals', $values, $placeholders ); // phpcs:ignore
						}
					}

					if ( ! empty( $referral_added ) ) {
						$order = wc_get_order( $conversion_data['oid'] );
						if ( $order instanceof WC_Order ) {
							$order->update_meta_data( 'is_commission_recorded', 'yes' );
							$order->save();
						}

						// Send new conversion email to affiliate if enabled.
						if ( true === AFWC_Emails::is_afwc_mailer_enabled( 'afwc_email_new_conversion_received' ) ) {
							// Trigger email.
							do_action(
								'afwc_email_new_conversion_received',
								array(
									'affiliate_id' => $conversion_data['affiliate_id'],
									'order_commission_amount' => $conversion_data['amount'],
									'currency_id'  => $conversion_data['currency_id'],
									'order_id'     => $conversion_data['oid'],
								)
							);
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
		private function track_commission( $affiliate_id = 0, $amount = 0, $params = array() ) {
			global $wpdb;

			$now = gmdate( 'Y-m-d H:i:s' );

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
		public function calculate_commission( $order_id = 0, $affiliate_id = 0 ) {
			global $affiliate_for_woocommerce;
			$set_commission       = array();
			$remaining_items      = array();
			$ordered_plans        = array();
			$context              = array();
			$amount_to_remove     = 0;
			$order                = wc_get_order( $order_id );
			$amount               = null;
			$total_for_commission = 0;
			$plan_id_detail_map   = array();

			$default_plan_details     = afwc_get_default_plan_details();
			$storewide_commission_amt = ! empty( $default_plan_details ) ? floatval( $default_plan_details['amount'] ) : 0;
			$storewide_plan_type      = ! empty( $default_plan_details ) ? $default_plan_details['type'] : 'Percentage';
			$default_plan_id          = $default_plan_details['id'];

			$items                = $order->get_items();
			$item_total_map       = array();
			$category_prod_id_map = array();
			$item_quantity_map    = array();
			$valid_plan_ids       = array();
			foreach ( $items as $item ) {
				$product_id                    = ( ! empty( $item->get_variation_id() ) ) ? $item->get_variation_id() : $item->get_product_id();
				$item_total_map[ $product_id ] = empty( $item_total_map[ $product_id ] ) ? $item['line_total'] : $item_total_map[ $product_id ] + $item['line_total'];
				if ( ! empty( $item['quantity'] ) && $item['quantity'] > 1 ) {
					$item_quantity_map[ $product_id ] = empty( $item_quantity_map[ $product_id ] ) ? $item['quantity'] : ( $item_quantity_map[ $product_id ] + $item['quantity'] );
				}

				$prod_categories = wc_get_product_cat_ids( $item->get_product_id() );
				foreach ( $prod_categories as $cat ) {
					$category_prod_id_map[ $cat ][] = $product_id;
				}
			}
			$product_cat_ids = array_keys( $category_prod_id_map );

			$affiliate_obj = new AFWC_Affiliate( $affiliate_id );
			$affiliate_tag = is_callable( array( $affiliate_obj, 'get_tags' ) ) ? $affiliate_obj->get_tags() : array();

			$afwc_excluded_products = get_option( 'afwc_storewide_excluded_products' );

			$used_coupons = ( is_callable( array( $order, 'get_coupon_codes' ) ) ) ? $order->get_coupon_codes() : array();

			// build the context for rule validation for this order.
			$context['affiliate_id']         = $affiliate_id;
			$context['product_id']           = array_keys( $item_total_map );
			$context['affiliate_tag']        = ! empty( $affiliate_tag ) ? array_keys( $affiliate_tag ) : array();
			$context['product_category']     = $product_cat_ids;
			$context['category_prod_id_map'] = $category_prod_id_map;
			$context['referral_medium']      = ( is_callable( array( $affiliate_for_woocommerce, 'get_referral_type' ) ) ) ? $affiliate_for_woocommerce->get_referral_type( $affiliate_id, $used_coupons ) : '';

			// set commission for already excluded.
			if ( ! empty( $afwc_excluded_products ) ) {
				foreach ( $afwc_excluded_products as $id ) {
					$set_commission[ $id ] = 0;
				}
			}
			$afwc_plans = afwc_get_commission_plans( 'Active' );

			if ( ! class_exists( 'AFWC_Commission_Dashboard' ) ) {
				include_once AFWC_PLUGIN_DIRPATH . '/includes/admin/class-afwc-commission-dashboard.php';
			}

			$afwc_commission = is_callable( array( 'AFWC_Commission_Dashboard', 'get_instance' ) ) ? AFWC_Commission_Dashboard::get_instance() : null;
			$plan_order      = is_callable( array( $afwc_commission, 'get_commission_plans_order' ) ) ? $afwc_commission->get_commission_plans_order() : array();

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
				// remove storewide plan.
				$ordered_plans = array_filter(
					$ordered_plans,
					function( $p ) use ( $default_plan_id ) {
						if ( $p['id'] !== $default_plan_id ) {
							return $p;
						}
					}
				);
				foreach ( $ordered_plans as $plan ) {

					// Break the loop if there are no remaining products to set the commission amount.
					if ( empty( array_diff( $context['product_id'], array_keys( $set_commission ) ) ) ) {
						break;
					}

					$plan_context         = new AFWC_Rule_Context( $context );
					$props                = json_decode( $plan['rules'], true );
					$props['amount']      = ! empty( $plan['amount'] ) ? floatval( $plan['amount'] ) : 0;
					$props['type']        = ! empty( $plan['type'] ) ? $plan['type'] : 'Percentage';
					$plan_object          = new AFWC_Plan( $props );
					$action_for_remaining = ( ! empty( $plan['action_for_remaining'] ) ) ? $plan['action_for_remaining'] : 'continue';
					$apply_to             = ( ! empty( $plan['apply_to'] ) ) ? $plan['apply_to'] : 'all';
					$plan_total           = 0;
					if ( $plan_object->validate( $plan_context ) ) {
						$valid_plan_ids[] = $plan['id'];
						$valid_item_ids   = $plan_context->get_valid_product_ids();
						$valid_item_ids   = ( 'first' === $apply_to ) ? array( reset( $valid_item_ids ) ) : $valid_item_ids;
						$plan_type        = ! empty( $plan['type'] ) ? $plan['type'] : 'Percentage';
						$product_quantity = 0;
						$validate_items   = array();
						foreach ( $valid_item_ids as $id ) {
							if ( ! isset( $set_commission[ $id ] ) ) {
								if ( ! empty( $plan['amount'] ) ) {
									if ( 'Percentage' === $plan_type ) {
										$amount = ! empty( $item_total_map[ $id ] ) ? ( $item_total_map[ $id ] * $plan['amount'] ) / 100 : 0;
									} elseif ( 'Flat' === $plan_type ) {
										$amount = floatval( $plan['amount'] );
										if ( ! empty( $item_quantity_map[ $id ] ) && 'all' === $apply_to ) {
											$amount = $amount * $item_quantity_map[ $id ];
										}
									}
								}
								$validate_items[ $id ] = array(
									'quantity'   => ! empty( $item_quantity_map[ $id ] ) ? $item_quantity_map[ $id ] : 1,
									'line_total' => ! empty( $item_total_map[ $id ] ) ? floatval( $item_total_map[ $id ] ) : 0,
								);
								$plan_total            = ! empty( $item_total_map[ $id ] ) ? ( $plan_total + $item_total_map[ $id ] ) : $plan_total;
								$set_commission[ $id ] = ! empty( $amount ) ? $amount : 0;
							}
						}

						$plan_id_detail_map[ $plan['id'] ]['total']          = $plan_total;
						$plan_id_detail_map[ $plan['id'] ]['type']           = $plan_type;
						$plan_id_detail_map[ $plan['id'] ]['distribution']   = ! empty( $plan['distribution'] ) ? $plan['distribution'] : '';
						$plan_id_detail_map[ $plan['id'] ]['no_of_tiers']    = ! empty( $plan['no_of_tiers'] ) ? intval( $plan['no_of_tiers'] ) : 1;
						$plan_id_detail_map[ $plan['id'] ]['apply_to']       = $apply_to;
						$plan_id_detail_map[ $plan['id'] ]['validate_items'] = $validate_items;

						// Calculate for remaining items.
						if ( 'default' === $action_for_remaining ) {
							break;
						} elseif ( 'zero' === $action_for_remaining ) {
							$remaining_items = array_diff( $context['product_id'], array_keys( $set_commission ) );

							if ( ! empty( $remaining_items ) ) {
								foreach ( $remaining_items as $id ) {
									$set_commission[ $id ] = 0;
								}
							}
						}
					}
				}
			}

			// set default commission to remaining itmes.
			$remaining_items = array_diff( $context['product_id'], array_keys( $set_commission ) );
			$plan_total      = 0;

			if ( ! empty( $remaining_items ) && 'Percentage' === $storewide_plan_type ) {
				foreach ( $remaining_items as $id ) {
					$set_commission[ $id ] = ! empty( $item_total_map[ $id ] ) ? ( $item_total_map[ $id ] * $storewide_commission_amt ) / 100 : 0;
					$plan_total            = ! empty( $item_total_map[ $id ] ) ? ( $plan_total + $item_total_map[ $id ] ) : $plan_total;
				}
				$valid_plan_ids[]                                       = $default_plan_id;
				$plan_id_detail_map[ $default_plan_id ]['total']        = $plan_total;
				$plan_id_detail_map[ $default_plan_id ]['type']         = 'Percentage';
				$plan_id_detail_map[ $default_plan_id ]['distribution'] = ! empty( $default_plan_details['distribution'] ) ? $default_plan_details['distribution'] : '';
				$plan_id_detail_map[ $default_plan_id ]['no_of_tiers']  = ! empty( $default_plan_details['no_of_tiers'] ) ? intval( $default_plan_details['no_of_tiers'] ) : 1;
				$plan_id_detail_map[ $default_plan_id ]['is_default_plan'] = 'yes';
			}

			// calculate aggregated sum of commission.
			$amount = array_sum( $set_commission );

			// if remaining_items and storewide_plan_type is flat then add it to amount.
			if ( ! empty( $remaining_items ) && 'Flat' === $storewide_plan_type ) {
				foreach ( $remaining_items as $id ) {
					$set_commission[ $id ] = 0;
				}
				$amount           = $amount + $storewide_commission_amt;
				$valid_plan_ids[] = $default_plan_id;
				$plan_id_detail_map[ $default_plan_id ]['total']           = 0;
				$plan_id_detail_map[ $default_plan_id ]['type']            = 'Flat';
				$plan_id_detail_map[ $default_plan_id ]['distribution']    = ! empty( $default_plan_details['distribution'] ) ? $default_plan_details['distribution'] : '';
				$plan_id_detail_map[ $default_plan_id ]['no_of_tiers']     = ! empty( $default_plan_details['no_of_tiers'] ) ? intval( $default_plan_details['no_of_tiers'] ) : 1;
				$plan_id_detail_map[ $default_plan_id ]['is_default_plan'] = 'yes';
			}

			// save valid plan ids in order meta.
			$valid_plan_ids = ! empty( $valid_plan_ids ) ? $valid_plan_ids : array( $default_plan_id );
			$order->update_meta_data( 'afwc_order_valid_plans', $valid_plan_ids );

			// Fallback to storewide commission if rule based commission is not calculated.
			if ( empty( $amount ) && empty( $valid_item_ids ) ) {
				$total_for_commission = $order->get_subtotal() - $order->get_total_discount();

				// remove excluded item price from total.
				if ( ! empty( $set_commission ) ) {
					foreach ( $set_commission as $id => $val ) {
						$amount_to_remove += ! empty( $item_total_map[ $id ] ) ? $item_total_map[ $id ] : 0;
					}
					$total_for_commission = $total_for_commission - $amount_to_remove;
				}
				$amount = ( $total_for_commission * $storewide_commission_amt ) / 100;
			}
			$afwc_parent_commissions = $this->track_multi_tier_commissions( $affiliate_id, $plan_id_detail_map, $order_id, $order );

			// fetch the entire chain of affiliate parent here and send it in commissions array.
			$commissions = ! empty( $afwc_parent_commissions ) ? $afwc_parent_commissions : array();
			// send current and parent affiliates commissions.
			$commissions['amount'] = $amount;

			// set all plan meta data of calculations.
			$afwc_plan_meta['product_commissions'] = ! empty( $set_commission ) ? $set_commission : array();
			$afwc_plan_meta['commissions_chain']   = ! empty( $commissions ) ? $commissions : array();
			$order->add_meta_data( 'afwc_set_commission', $afwc_plan_meta );
			$order->save();

			return $commissions;

		}

		/**
		 * Record referral when renewal order created
		 *
		 * @param  integer $affiliate_id child affiliate id.
		 * @param  array   $plan_id_detail_map valid plan id and total to calculate commission on.
		 * @param  integer $order_id order id.
		 * @param  object  $order The Order object.
		 */
		public function track_multi_tier_commissions( $affiliate_id = 0, $plan_id_detail_map = array(), $order_id = 0, $order = null ) {
			// fetch details for multi-tier.
			$afwc         = Affiliate_For_WooCommerce::get_instance();
			$parent_chain = $afwc->afwc_get_parents_for_commissions( $affiliate_id );
			if ( ! empty( $parent_chain ) && ! empty( $plan_id_detail_map ) ) {

				if ( empty( $order ) && ! empty( $order_id ) ) {
					$order = wc_get_order( $order_id );
				}

				$parent_commissions = $order->get_meta( 'afwc_parent_commissions', true );
				$parent_commissions = ! empty( $parent_commissions ) ? $parent_commissions : array();

				// loop through the plan_id_detail_map.
				foreach ( $plan_id_detail_map as $plan_id => $plan_details ) {
					$current_type  = ! empty( $plan_details['type'] ) ? $plan_details['type'] : 'Percentage';
					$current_total = ! empty( $plan_details['total'] ) ? floatval( $plan_details['total'] ) : 0;

					if ( 'Percentage' === $current_type && empty( $current_total ) ) {
						continue;
					}

					$current_no_of_tiers      = ! empty( $plan_details['no_of_tiers'] ) ? intval( $plan_details['no_of_tiers'] ) : 1;
					$current_distribution     = ( ! empty( $plan_details['distribution'] ) && $current_no_of_tiers > 1 ) ? $plan_details['distribution'] : '';
					$current_distribution_arr = ( ! empty( $current_distribution ) ) ? explode( '|', $current_distribution ) : array();
					$validate_items           = ( ! empty( $plan_details['validate_items'] ) ) ? $plan_details['validate_items'] : array();
					$is_default_plan          = ( ! empty( $plan_details['is_default_plan'] ) ) ? $plan_details['is_default_plan'] : 'no';

					foreach ( $parent_chain as $key => $affiliate_chain_id ) {
						// Get the commission amount for current tier.
						$current_commission_amt = ! empty( $current_distribution_arr[ $key ] ) ? floatval( $current_distribution_arr[ $key ] ) : 0;
						$commission_amt         = 0;

						if ( 'yes' === $is_default_plan ) {
							if ( 'Flat' === $current_type ) {
								$commission_amt = $current_commission_amt;
							} elseif ( 'Percentage' === $current_type ) {
								$commission_amt = ! empty( $current_commission_amt ) ? ( floatval( $current_total ) * floatval( $current_commission_amt ) ) / 100 : 0;
							}
						} elseif ( ! empty( $validate_items ) ) {
							foreach ( $validate_items as $product_id => $product_details ) {
								if ( 'Flat' === $current_type ) {
									// Multiply the flat price with quantity of the product if configured to apply the commission to all.
									$commission_amt += ( ( ! empty( $product_details['quantity'] ) && $product_details['quantity'] > 1 && 'all' === ( ! empty( $plan_details['apply_to'] ) ? $plan_details['apply_to'] : 'all' ) ) ? ( $current_commission_amt * $product_details['quantity'] ) : $current_commission_amt );
								} elseif ( 'Percentage' === $current_type ) {
									$commission_amt += ( ! empty( $product_details['line_total'] ) ? ( floatval( $product_details['line_total'] ) * $current_commission_amt ) / 100 : 0 );
								}
							}
						}

						if ( ! empty( $commission_amt ) ) {
							// assign commission to parent.
							$parent_commissions[ $affiliate_chain_id ] = ( ! empty( $parent_commissions[ $affiliate_chain_id ] ) ) ? $commission_amt + floatval( $parent_commissions[ $affiliate_chain_id ] ) : $commission_amt;
						}
					}
				}

				// update parent commissions in meta but do not call save.
				$order->update_meta_data( 'afwc_parent_commissions', $parent_commissions );

				// send back parent commissions.
				return $parent_commissions;
			}
		}

		/**
		 * Record referral when renewal order created
		 *
		 * @param  WC_Order        $renewal_order The renewal order.
		 * @param  WC_Subscription $subscription  The subscription.
		 * @return WC_Order
		 */
		public function handle_renewal_order_created( $renewal_order = null, $subscription = null ) {
			$this->track_conversion( $renewal_order );
			return $renewal_order;
		}

		/**
		 * Record referral
		 *
		 * @param mixed $conversion_data .
		 */
		public function get_conversion_data( $conversion_data ) {
			global $wpdb;

			$order_id = ( ! empty( $conversion_data['oid'] ) ) ? $conversion_data['oid'] : 0;
			if ( empty( $order_id ) ) {
				return $conversion_data;
			}

			$affiliate_id = ( ! empty( $conversion_data['affiliate_id'] ) ) ? $conversion_data['affiliate_id'] : 0;

			// Assign referer's id if affiliate id is empty and order should not be renewal.
			if ( empty( $affiliate_id ) && false === $this->is_wc_subscriptions_renewal_order( $order_id ) ) {
				$affiliate_id = afwc_get_referrer_id();
			}

			// Return if affiliate id is not exists.
			if ( empty( $affiliate_id ) ) {
				return $conversion_data;
			}

			$campaign_id = afwc_get_campaign_id();

			$commissions = $this->calculate_commission( $order_id, $affiliate_id );
			if ( false === $commissions ) {
				// set conversion data affiliate id 0 if commission already recorded.
				$conversion_data['affiliate_id'] = 0;
				return $conversion_data;
			}

			$amount = ( ! empty( $commissions ) && ! empty( $commissions['amount'] ) ) ? $commissions['amount'] : 0;
			unset( $commissions['amount'] );

			$description = '';
			$data        = '';
			$type        = '';
			$reference   = '';

			if ( $affiliate_id ) {
				$order = wc_get_order( $order_id );
				if ( ! $order instanceof WC_Order ) {
					return $conversion_data;
				}

				$currency_id  = ( is_callable( array( $order, 'get_currency' ) ) ) ? $order->get_currency() : get_woocommerce_currency();
				$user_id      = ( is_callable( array( $order, 'get_customer_id' ) ) ) ? $order->get_customer_id() : 0;
				$afwc         = Affiliate_For_WooCommerce::get_instance();
				$used_coupons = ( is_callable( array( $order, 'get_coupon_codes' ) ) ) ? $order->get_coupon_codes() : array();
				$type         = $afwc->get_referral_type( $affiliate_id, $used_coupons );

				// prepare conversion_data.
				$conversion_data['user_id']      = ! empty( $user_id ) ? $user_id : 0;
				$conversion_data['amount']       = $amount;
				$conversion_data['type']         = $type;
				$conversion_data['status']       = AFWC_REFERRAL_STATUS_DRAFT;
				$conversion_data['reference']    = $reference;
				$conversion_data['data']         = $data;
				$conversion_data['currency_id']  = $currency_id;
				$conversion_data['affiliate_id'] = $affiliate_id;
				$conversion_data['campaign_id']  = $campaign_id;
				$conversion_data['hit_id']       = ! is_admin() ? afwc_get_hit_id() : 0; // To prevent hit_id incorrectly set when admin is manually assigning/unassigning an order.
				$conversion_data['commissions']  = $commissions;
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
		public function update_referral_status( $order_id = 0, $old_status = '', $new_status = '' ) {
			if ( empty( $order_id ) ) {
				return;
			}

			global $wpdb;

			$order_status_updates = false;
			$wc_paid_statuses     = afwc_get_paid_order_status();
			$reject_statuses      = afwc_get_reject_order_status();

			$new_status = ( strpos( $new_status, 'wc-' ) === false ) ? 'wc-' . $new_status : $new_status;
			$old_status = ( strpos( $old_status, 'wc-' ) === false ) ? 'wc-' . $old_status : $old_status;

			// if order status goes from rejected to paid then create new entry in referral.
			if ( ( ! empty( $wc_paid_statuses ) && is_array( $wc_paid_statuses ) && in_array( $new_status, $wc_paid_statuses, true ) ) && ( ! empty( $reject_statuses ) && is_array( $reject_statuses ) && in_array( $old_status, $reject_statuses, true ) ) ) {
				// check if order is recorded in referral and if that is rejected.
				$affiliate_id =  $wpdb->get_var( $wpdb->prepare( "SELECT affiliate_id FROM {$wpdb->prefix}afwc_referrals WHERE post_id = %d AND status = %s ORDER BY referral_id", $order_id, AFWC_REFERRAL_STATUS_REJECTED ) ); // phpcs:ignore
				if ( ! empty( $affiliate_id ) ) {
					// track commission for order.
					$params                 = array();
					$params['force_record'] = true;
					$this->track_conversion( $order_id, $affiliate_id, 'order', $params );
				}
			}

			// update referral if not paid or rejected.
			if ( ! empty( $wc_paid_statuses ) && is_array( $wc_paid_statuses ) && in_array( $new_status, $wc_paid_statuses, true ) ) {

				$status = apply_filters(
					'afwc_commission_status_for_paid_orders',
					AFWC_REFERRAL_STATUS_UNPAID,
					array(
						'order_id' => $order_id,
						'source'   => $this,
					)
				);

				$wpdb->query( // phpcs:ignore
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}afwc_referrals
						SET status = %s, order_status = %s
						WHERE post_id = %d AND status NOT IN (%s, %s)",
						$status,
						$new_status,
						$order_id,
						AFWC_REFERRAL_STATUS_PAID,
						AFWC_REFERRAL_STATUS_REJECTED
					)
				);
				$order_status_updates = true;
			} elseif ( ! empty( $reject_statuses ) && is_array( $reject_statuses ) && in_array( $new_status, $reject_statuses, true ) ) {
				// reject referral if not paid.
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET status = %s, order_status = %s WHERE post_id = %d AND status NOT IN (%s)", AFWC_REFERRAL_STATUS_REJECTED, $new_status, $order_id, AFWC_REFERRAL_STATUS_PAID ) ); // phpcs:ignore
				$order_status_updates = true;
			}

			if ( ! $order_status_updates ) {
				// set new order status in referral table.
				$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET order_status = %s WHERE post_id = %d", $new_status, $order_id ) ); // phpcs:ignore
			}
		}

		/**
		 * Do not copy few affiliate meta to renewal order.
		 *
		 * @param  mixed $order_meta Order meta.
		 * @return mixed $order_meta
		 */
		public function do_not_copy_affiliate_meta( $order_meta = array() ) {
			if ( isset( $order_meta['is_commission_recorded'] ) ) {
				unset( $order_meta['is_commission_recorded'] );
			}
			if ( isset( $order_meta['afwc_order_valid_plans'] ) ) {
				unset( $order_meta['afwc_order_valid_plans'] );
			}
			if ( isset( $order_meta['afwc_set_commission'] ) ) {
				unset( $order_meta['afwc_set_commission'] );
			}
			if ( isset( $order_meta['afwc_parent_commissions'] ) ) {
				unset( $order_meta['afwc_parent_commissions'] );
			}

			return $order_meta;
		}

		/**
		 * Do not copy few affiliate meta to renewal order.
		 *
		 * @param  mixed   $order_meta_query Order items.
		 * @param  integer $to_order The original order id.
		 * @param  integer $from_order The renewal order id.
		 * @return mixed $order_meta_query
		 */
		public function do_not_copy_meta( $order_meta_query, $to_order, $from_order ) {
			$order_meta_query .= " AND `meta_key` NOT IN ('is_commission_recorded', 'afwc_order_valid_plans', 'afwc_set_commission', 'afwc_parent_commissions')";
			return $order_meta_query;
		}

		/**
		 * Return if the order is a WooCommerce Subscriptions renewal order by WooCommerce order id.
		 *
		 * @param  integer $order_id Order id.
		 *
		 * @return boolean $is_renewal_order
		 */
		public function is_wc_subscriptions_renewal_order( $order_id = 0 ) {
			if ( empty( $order_id ) ) {
				return false;
			}

			// Initialize renewal order.
			$is_renewal_order = false;

			// Check if WooCommerce Subscription plugin is active.
			if ( class_exists( 'WC_Subscriptions_Core_Plugin' ) || class_exists( 'WC_Subscriptions' ) ) {
				$renewal_order    = wc_get_order( $order_id );
				$renewal_order_id = ( is_object( $renewal_order ) && is_callable( array( $renewal_order, 'get_id' ) ) ) ? $renewal_order->get_id() : 0;

				// Check if the order is wc subscriptions renewal order.
				$is_renewal_order = wcs_order_contains_renewal( $renewal_order_id );
			}

			return $is_renewal_order;
		}

		/**
		 * Check if order is valid for affiliate ID.
		 *
		 * @param  integer $order_id     The Order ID.
		 * @param  integer $affiliate_id The original affiliate ID.
		 * @param  mixed   $params       The additional params.
		 * @return boolean $is_valid_order flag
		 */
		public function afwc_is_valid_order( $order_id, $affiliate_id, $params ) {

			$is_valid_order = true;

			if ( empty( $order_id ) ) {
				return false;
			}

			$force_record = ! empty( $params['force_record'] ) ? $params['force_record'] : false;
			if ( true === $force_record ) {
				return true;
			}

			$order = wc_get_order( $order_id );
			if ( ! $order instanceof WC_Order ) {
				return false;
			}

			$is_commission_recorded = $order->get_meta( 'is_commission_recorded', true );
			if ( 'yes' === $is_commission_recorded ) {
				$is_valid_order = false;
			} else {
				global $wpdb;

				// check if commission is already recorded in table but not updated in postmeta.
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
					$is_valid_order = false;
				}
			}

			return $is_valid_order;

		}

		/**
		 * Return if the commission allowed self-refer.
		 *
		 * @param int   $order_id     The Order id.
		 * @param int   $affiliate_id The Affiliate id.
		 * @param array $params       The additional params.
		 *
		 * @return bool Return true if the order is eligible for self-refer otherwise false.
		 */
		public function allow_self_order_for_order( $order_id = 0, $affiliate_id = 0, $params = array() ) {
			if ( empty( $order_id ) || empty( $affiliate_id ) ) {
				return false;
			}

			// set true if forced affiliate to be eligible or self-refer is allowed.
			if ( ( ! empty( $params['is_affiliate_eligible'] ) && true === $params['is_affiliate_eligible'] ) || true === afwc_allow_self_refer() ) {
				return true;
			}

			$order = wc_get_order( $order_id );
			if ( $order instanceof WC_Order && is_callable( array( $order, 'get_user_id' ) ) ) {
				$customer_id = $order->get_user_id();
				if ( ! empty( $customer_id ) && intval( $customer_id ) === intval( $affiliate_id ) ) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Return if the order is eligible for commission.
		 *
		 * @param int   $order_id The Order id.
		 * @param int   $affiliate_id The Affiliate id.
		 * @param array $params The Params.
		 *
		 * @return bool Return true whether the order is eligible for commission otherwise false.
		 */
		public function is_eligible_for_commission( $order_id = 0, $affiliate_id = 0, $params = array() ) {
			$is_eligible = false;

			if ( empty( $order_id ) || empty( $affiliate_id ) ) {
				return $is_eligible;
			}

			// TODO: Simplify this code if in the future we want to add more checks.
			if ( true === $this->afwc_is_valid_order( $order_id, $affiliate_id, $params ) && true === $this->allow_self_order_for_order( $order_id, $affiliate_id, $params ) ) {
				$is_eligible = true;
			}

			/**
			 * Filter for whether order is eligible for commission.
			 *
			 * @param bool  $is_eligible whether eligible for the commission or not.
			 * @param array The params
			 */
			return apply_filters(
				'afwc_is_eligible_for_commission',
				$is_eligible,
				array(
					'order_id'     => $order_id,
					'affiliate_id' => $affiliate_id,
					'source'       => $this,
				)
			);
		}

		/**
		 * Function to get affiliate data based on order_id.
		 *
		 * @param int    $order_id The Order ID.
		 * @param string $data     Whether to get all or selected records.
		 *
		 * @return array Return The array of affiliate id and status of the linked affiliate.
		 */
		public function get_affiliate_by_order( $order_id = 0, $data = '' ) {

			if ( empty( $order_id ) ) {
				return array();
			}

			global $wpdb;

			if ( empty( $data ) ) {
				$affiliate_details = $wpdb->get_row( // phpcs:ignore
					$wpdb->prepare(
						"SELECT affiliate_id, status
							FROM {$wpdb->prefix}afwc_referrals
							WHERE post_id = %d AND reference = ''",
						$order_id
					),
					'ARRAY_A'
				);
			} elseif ( 'all' === $data ) {
				$affiliate_details = $wpdb->get_row( // phpcs:ignore
					$wpdb->prepare(
						"SELECT *
							FROM {$wpdb->prefix}afwc_referrals
							WHERE post_id = %d AND reference = ''",
						$order_id
					),
					'ARRAY_A'
				);
			}

			return ! empty( $affiliate_details ) ? $affiliate_details : array();
		}

	}
}

AFWC_API::get_instance();
