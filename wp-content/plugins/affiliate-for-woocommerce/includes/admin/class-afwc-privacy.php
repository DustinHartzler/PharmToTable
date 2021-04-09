<?php
/**
 * Affiliate Privacy Class
 *
 * @package     affiliate-for-woocommerce/includes/admin
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Abstract_Privacy' ) ) {
	return;
}

if ( ! class_exists( 'AFWC_Privacy' ) ) {

	/**
	 * Main class for handling privacy for Affiliate For WooCommerce plugin
	 */
	class AFWC_Privacy extends WC_Abstract_Privacy {

		/**
		 * Constructor
		 */
		public function __construct() {
			// To show this plugin's privacy message in Privacy Policy Guide page on your admin dashboard.
			parent::__construct( __( 'Affiliate For WooCommerce', 'affiliate-for-woocommerce' ) );

			// GDPR - Register Data Exporter.
			add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'afwc_register_exporter' ), 10 );

			// GDPR - Register Data Eraser.
			add_filter( 'wp_privacy_personal_data_erasers', array( $this, 'afwc_register_eraser' ), 1 );

			add_action( 'woocommerce_privacy_before_remove_order_personal_data', array( $this, 'afwc_remove_order_personal_data' ) );
		}

		/**
		 * Gets the message of the privacy to display.
		 */
		public function get_privacy_message() {
			$content = '<strong>' . __( 'What and where we store:', 'affiliate-for-woocommerce' ) . '</strong>
						<ul>
							<li>' . __( 'If you are an affiliate, we store a cookie with your affiliate ID and map it to the user accessing our site with your link.', 'affiliate-for-woocommerce' ) . '</li>
							<li>' . __( 'If you are a visitor accessing our site with the affiliate link, we store your IP address. And if you place an order, then your order details are stored along with your IP address.', 'affiliate-for-woocommerce' ) . '</li>
						</ul>
						<strong>' . __( 'Export/Delete personal data', 'affiliate-for-woocommerce' ) . '</strong>
						<p>' . __( 'Exporting personal data is available for both - affiliate and visitor.', 'affiliate-for-woocommerce' ) . '</p>
						<p>' . __( 'Export/delete of personal data will be processed only after receiving confirmation about it.', 'affiliate-for-woocommerce' ) . '</p>
						<p>' . __( 'For deleting cookie data, a visitor can simply delete cookies from their browser.', 'affiliate-for-woocommerce' ) . '</p>';

			return wpautop( $content );
		}

		/**
		 * Find user_id from email_address.
		 *
		 * @param string $email_address The user email address.
		 * @return int User_ID.
		 */
		public function get_user_id( $email_address ) {
			if ( empty( $email_address ) ) {
				return;
			}

			$wc_customer = get_user_by( 'email', $email_address );
			$user_id     = $wc_customer->ID;

			return $user_id;
		}

		/**
		 * Find if a user is affiliate from email_address
		 *
		 * @param string $email_address The user email address.
		 * @return string if user is affiliate.
		 */
		public function is_user_affiliate( $email_address ) {
			if ( empty( $email_address ) ) {
				return;
			}
			$is_affiliate = 'no';

			$user_id = $this->get_user_id( $email_address );
			if ( ! empty( $user_id ) ) {
				$is_affiliate = get_user_meta( $user_id, 'afwc_is_affiliate', true );
			}

			return $is_affiliate;
		}

		/**
		 * Function to register callback for data exporter
		 *
		 * @param  array $exporters Exporters.
		 * @return array $exporters Exporters with Affiliate Privacy data exporter
		 */
		public function afwc_register_exporter( $exporters = array() ) {
			$exporters['affiliate-for-woocommerce'] = array(
				'exporter_friendly_name' => __( 'Affiliate - Customer', 'affiliate-for-woocommerce' ),
				'callback'               => array( $this, 'afwc_data_exporter' ),
			);

			return $exporters;
		}

		/**
		 * Finds and exports affiliate data of customers by email address.
		 *
		 * @param string $email_address The user email address.
		 * @param int    $page  Page.
		 * @return array An array of personal data in name value pairs
		 */
		public function afwc_data_exporter( $email_address, $page ) {

			$data_to_export = array();

			$user_id      = $this->get_user_id( $email_address );
			$is_affiliate = $this->is_user_affiliate( $email_address );

			if ( 'yes' === $is_affiliate ) {
				$affiliate_user_data = $this->get_affiliate_user_data( $user_id );
			} else {
				$non_affiliate_user_data = $this->get_non_affiliate_user_data( $user_id );
			}

			if ( ! empty( $affiliate_user_data ) ) {
				$data_to_export[] = array(
					'group_id'    => 'afwc_data',
					'group_label' => __( 'Affiliate information', 'affiliate-for-woocommerce' ),
					'item_id'     => '',
					'data'        => $affiliate_user_data,
				);
			} elseif ( ! empty( $non_affiliate_user_data ) ) {
				$data_to_export[] = array(
					'group_id'    => 'afwc_data',
					'group_label' => __( 'User\'s affiliate information', 'affiliate-for-woocommerce' ),
					'item_id'     => '',
					'data'        => $non_affiliate_user_data,
				);
			}

			return array(
				'data' => $data_to_export,
				'done' => true,
			);
		}

		/**
		 * Finds and return affiliate data from user_id.
		 *
		 * @param int $user_id The user ID.
		 * @return array An array of personal data in name value pairs
		 */
		public function get_affiliate_user_data( $user_id ) {
			global $wpdb;

			$personal_data            = array();
			$data_to_export_from_hits = array(
				'affiliate_id' => __( 'Affiliate ID', 'affiliate-for-woocommerce' ),
				'count'        => __( 'Hits count', 'affiliate-for-woocommerce' ),
			);

			$data_to_export_from_payouts = array(
				'datetime'        => __( 'Affiliate payout date time', 'affiliate-for-woocommerce' ),
				'amount'          => __( 'Affiliate earned payout amount', 'affiliate-for-woocommerce' ),
				'currency'        => __( 'Affiliate payout currency', 'affiliate-for-woocommerce' ),
				'payout_notes'    => __( 'Affiliate payout notes', 'affiliate-for-woocommerce' ),
				'payment_gateway' => __( 'Affiliate payout gateway', 'affiliate-for-woocommerce' ),
				'receiver'        => __( 'Affiliate receiver', 'affiliate-for-woocommerce' ),
				'type'            => __( 'Type', 'affiliate-for-woocommerce' ),
			);

			$data_to_export_from_referrals = array(
				'amount'      => __( 'Paid commission amount', 'affiliate-for-woocommerce' ),
				'currency_id' => __( 'Currency', 'affiliate-for-woocommerce' ),
				'status'      => __( 'Payout status', 'affiliate-for-woocommerce' ),
			);

			$hits_data     = $wpdb->get_row( // phpcs:ignore
											$wpdb->prepare( // phpcs:ignore
												"SELECT affiliate_id, SUM(count) as count 
															FROM {$wpdb->prefix}afwc_hits 
															WHERE affiliate_id = %s",
												$user_id
											),
				'ARRAY_A'
			);

			$payouts_data     = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT * 
																	FROM {$wpdb->prefix}afwc_payouts 
																	WHERE affiliate_id = %s",
														$user_id
													),
				'ARRAY_A'
			);

			$referrals_data   = $wpdb->get_results( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT referral_id, affiliate_id, amount, currency_id, status
																	FROM {$wpdb->prefix}afwc_referrals
																	WHERE affiliate_id = %s",
														$user_id
													),
				'ARRAY_A'
			);

			if ( ! empty( $hits_data ) ) {
				foreach ( $data_to_export_from_hits as $key => $name ) {
					if ( array_key_exists( $key, $hits_data ) ) {
						$value = $hits_data[ $key ];
					}
					if ( $value ) {
						$personal_data[] = array(
							'name'  => $name,
							'value' => $value,
						);
					}
				}
			}

			if ( ! empty( $payouts_data ) ) {
				foreach ( $data_to_export_from_payouts as $key => $name ) {
					foreach ( $payouts_data as $payout_data ) {
						if ( array_key_exists( $key, $payout_data ) ) {
							$value = $payout_data[ $key ];
						}
						if ( $value ) {
							$personal_data[] = array(
								'name'  => $name,
								'value' => $value,
							);
						}
					}
				}
			}

			if ( ! empty( $referrals_data ) ) {
				foreach ( $data_to_export_from_referrals as $key => $name ) {
					foreach ( $referrals_data as $referral_data ) {
						if ( array_key_exists( $key, $referral_data ) ) {
							$value = $referral_data[ $key ];
						}
						if ( $value ) {
							$personal_data[] = array(
								'name'  => $name,
								'value' => $value,
							);
						}
					}
				}
			}

			return $personal_data;
		}

		/**
		 * Finds and return non-affiliate's data from user_id.
		 *
		 * @param int $user_id The user ID.
		 * @return array An array of personal data in name value pairs
		 */
		public function get_non_affiliate_user_data( $user_id ) {
			global $wpdb;

			$personal_data            = array();
			$data_to_export_from_hits = array(
				'datetime' => __( 'Hit dateTime', 'affiliate-for-woocommerce' ),
				'ip'       => __( 'IP address', 'affiliate-for-woocommerce' ),
				'count'    => __( 'Hits count', 'affiliate-for-woocommerce' ),
			);

			$data_to_export_from_referrals = array(
				'post_id'  => __( 'Order ID', 'affiliate-for-woocommerce' ),
				'datetime' => __( 'Date time', 'affiliate-for-woocommerce' ),
				'ip'       => __( 'IP address', 'affiliate-for-woocommerce' ),
			);

			$hits_data     = $wpdb->get_results( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"SELECT datetime, ip, user_id, count 
																FROM {$wpdb->prefix}afwc_hits 
																WHERE user_id = %s",
													$user_id
												),
				'ARRAY_A'
			);

			$referrals_data     = $wpdb->get_results( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT post_id, datetime, ip, user_id 
															FROM {$wpdb->prefix}afwc_referrals 
															WHERE user_id = %s",
															$user_id
														),
				'ARRAY_A'
			);

			if ( ! empty( $hits_data ) ) {
				foreach ( $data_to_export_from_hits as $key => $name ) {
					foreach ( $hits_data as $hits ) {
						if ( array_key_exists( $key, $hits ) ) {
							if ( 'ip' === $key ) {
								$ip_address   = long2ip( $hits[ $key ] ); // since we are storing ip with ip2long.
								$hits[ $key ] = $ip_address;
							}

							$value = $hits[ $key ];
						}
						if ( $value ) {
							$personal_data[] = array(
								'name'  => $name,
								'value' => $value,
							);
						}
					}
				}
			}

			if ( ! empty( $referrals_data ) ) {
				foreach ( $data_to_export_from_referrals as $key => $name ) {
					foreach ( $referrals_data as $referral_data ) {
						if ( array_key_exists( $key, $referral_data ) ) {
							if ( 'ip' === $key ) {
								$ip_address            = long2ip( $referral_data[ $key ] ); // since we are storing ip with ip2long.
								$referral_data[ $key ] = $ip_address;
							}

							$value = $referral_data[ $key ];
						}
						if ( $value ) {
							$personal_data[] = array(
								'name'  => $name,
								'value' => $value,
							);
						}
					}
				}
			}

			return $personal_data;
		}

		/**
		 * Function to register callback for data eraser
		 *
		 * @param  array $exporters Eraser.
		 * @return array $exporters Eraser with Affiliate For WooCommerce Privacy data eraser.
		 */
		public function afwc_register_eraser( $exporters = array() ) {
			$exporters['affiliate-for-woocommerce'] = array(
				'eraser_friendly_name' => __( 'Affiliate - Customer', 'affiliate-for-woocommerce' ),
				'callback'             => array( $this, 'afwc_data_eraser' ),
			);

			return $exporters;
		}

		/**
		 * Find and anonymize IP address of customers by email address
		 *
		 * @param string $email_address The user email address.
		 * @param int    $page  Page.
		 * @return array An array of personal data in name value pairs
		 */
		public function afwc_data_eraser( $email_address, $page ) {

			global $wpdb;

			$response = array(
				'items_removed'  => false,
				'items_retained' => false,
				'messages'       => array(),
				'done'           => true,
			);

			$user_id      = $this->get_user_id( $email_address );
			$is_affiliate = $this->is_user_affiliate( $email_address );

			if ( 'no' === $is_affiliate ) {
				// Given an user, search for IP in afwc_hits & afwc_referrals to anonymize them.
				$success_response = $this->anonymize_ip_in_afwc_hits_referrals( $user_id );

				if ( true === $success_response ) {
					$response['items_removed'] = true;
					/* translators: Email address. */
					$response['messages'][] = sprintf( __( 'Anonymized IP for the user "%s"', 'affiliate-for-woocommerce' ), $email_address );
				}
			}

			return $response;

		}

		/**
		 * Find and anonymize IP address of customers by email address.
		 *
		 * @param int $user_id The user id.
		 * @return boolean true or not.
		 */
		public function anonymize_ip_in_afwc_hits_referrals( $user_id ) {

			if ( empty( $user_id ) ) {
				return true;
			}
			global $wpdb;

			// Anonymize IP in afwc_hits.
			$ip_addresses_hits   = $wpdb->get_col( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"SELECT ip 
																	FROM {$wpdb->prefix}afwc_hits
																	WHERE user_id = %s",
														$user_id
													)
			);

			foreach ( $ip_addresses_hits as $ip_addresses_hit ) {
				$original_ip_address = $ip_addresses_hit;
				$update_ip_address   = $this->create_case_query( $ip_addresses_hit );

				$res = $wpdb->query( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"UPDATE {$wpdb->prefix}afwc_hits
							 						SET ip = 
							 						( CASE 
							 							WHEN ip = %d THEN %d
							 						  ELSE ip
							 						  END ) 
							 					  WHERE user_id = %d",
						$original_ip_address,
						$update_ip_address,
						$user_id
					)
				);
			}

			// Anonymize IP in afwc_referrals.
			$ip_addresses_referrals   = $wpdb->get_col( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT ip
																		FROM {$wpdb->prefix}afwc_referrals
																		WHERE user_id = %s",
															$user_id
														)
			);

			foreach ( $ip_addresses_referrals as $ip_addresses_referral ) {
				$original_ip_address = $ip_addresses_referral;
				$update_ip_address   = $this->create_case_query( $ip_addresses_referral );

				$query = $wpdb->query( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"UPDATE {$wpdb->prefix}afwc_referrals
							 						SET ip = 
							 						( CASE 
							 							WHEN ip = %d THEN %d
							 						  ELSE ip
							 						  END ) 
							 					  WHERE user_id = %d",
						$original_ip_address,
						$update_ip_address,
						$user_id
					)
				);
			}

			if ( ! empty( $res ) || ! empty( $query ) ) {
				return true;
			}

		}

		/**
		 * Given an IP address, anonymize it and return.
		 *
		 * @param string $ip_address Original IP Address.
		 * @return string Anonymized IP Address.
		 */
		public function create_case_query( $ip_address ) {

			$converted_ip_address = '';
			if ( empty( $ip_address ) ) {
				return $converted_ip_address;
			}

			$default_ip_address   = long2ip( $ip_address );
			$anonymize_ip_address = preg_replace( array( '/\.\d*$/', '/[\da-f]*:[\da-f]*$/' ), array( '.0' ), $default_ip_address );
			$converted_ip_address = ip2long( $anonymize_ip_address );

			return $converted_ip_address;

		}

		/**
		 * Remove Affiliate order personal data
		 *
		 * @param  WC_Order $order The order object.
		 */
		public function afwc_remove_order_personal_data( $order ) {
			$created_date = ( is_object( $order ) && is_callable( array( $order, 'get_date_created' ) ) ) ? $order->get_date_created() : '';
			$args         = array(
				'post_id'      => ( is_object( $order ) && is_callable( array( $order, 'get_id' ) ) ) ? $order->get_id() : 0,
				'created_date' => $created_date,
			);
			$result       = $this->afwc_maybe_handle_order_data( $args );
		}

		/**
		 * Handle Anonymize of IP Address from order.
		 *
		 * @param array $order The order data.
		 * @return array
		 */
		public function afwc_maybe_handle_order_data( $order ) {
			if ( empty( $order ) ) {
				return array( false, false, array() );
			}

			global $wpdb;

			// Anonymize IP in afwc_referrals.
			$ip_addresses_referrals = $wpdb->get_col( // phpcs:ignore
														$wpdb->prepare( // phpcs:ignore
															"SELECT ip 
																		FROM {$wpdb->prefix}afwc_referrals
																		WHERE post_id = %s",
															$order['post_id']
														)
			);

			foreach ( $ip_addresses_referrals as $ip_addresses_referral ) {
				$original_ip_address = $ip_addresses_referral;
				$update_ip_address   = $this->create_case_query( $ip_addresses_referral );

				$query = $wpdb->query( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"UPDATE {$wpdb->prefix}afwc_referrals
							 						SET ip = 
							 						( CASE 
							 							WHEN ip = %d THEN %d
							 						  ELSE ip
							 						  END ) 
							 					  WHERE post_id = %d",
						$original_ip_address,
						$update_ip_address,
						$order['post_id']
					)
				);
			}

			return array( true, false, array( '<strong>' . __( 'Anonymized IP Address', 'affiliate-for-woocommerce' ) . '</strong> - ' . __( 'Removed Order Personal Data', 'affiliate-for-woocommerce' ) ) );
		}

	}

}

new AFWC_Privacy();
