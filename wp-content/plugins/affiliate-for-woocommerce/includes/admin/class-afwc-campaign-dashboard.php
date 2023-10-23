<?php
/**
 * Main class for Campaigns Dashboard
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @version     1.2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Campaign_Dashboard' ) ) {

	/**
	 * Main class for Campaigns Dashboard
	 */
	class AFWC_Campaign_Dashboard {

		/**
		 * The Ajax events.
		 *
		 * @var array $ajax_events
		 */
		private $ajax_events = array(
			'save_campaign',
			'delete_campaign',
			'fetch_dashboard_data',
			'fetch_rule_data',
			'search_rule_details',
		);

		/**
		 * Variable to hold instance of AFWC_Campaign_Dashboard
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Campaign_Dashboard Singleton object of this class
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
		public function __construct() {
			add_action( 'wp_ajax_afwc_campaign_controller', array( $this, 'request_handler' ) );
		}

		/**
		 * Function to handle all ajax request
		 */
		public function request_handler() {

			if ( empty( $_REQUEST ) || empty( wc_clean( wp_unslash( $_REQUEST['cmd'] ) ) ) ) { // phpcs:ignore
				return;
			}

			$params = array();

			foreach ( $_REQUEST as $key => $value ) { // phpcs:ignore
				if ( 'campaign' === $key ) {
					$params[ $key ] = wp_unslash( $value );
				} else {
					$params[ $key ] = wc_clean( wp_unslash( $value ) );
				}
			}
			$func_nm = ! empty( $params['cmd'] ) ? $params['cmd'] : '';

			if ( empty( $func_nm ) || ! in_array( $func_nm, $this->ajax_events, true ) ) {
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			if ( is_callable( array( $this, $func_nm ) ) ) {
				$this->$func_nm( $params );
			}
		}

		/**
		 * Function to handle save campaign
		 *
		 * @throws RuntimeException Data Exception.
		 * @param array $params save campaign params.
		 */
		public function save_campaign( $params = array() ) {
			check_admin_referer( 'afwc-admin-save-campaign', 'security' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			global $wpdb;

			$response = array( 'ACK' => 'Failed' );
			if ( ! empty( $params['campaign'] ) ) {
				$campaign = json_decode( $params['campaign'], true );
				$values   = array();

				$campaign_id                 = ! empty( $campaign['campaignId'] ) ? intval( $campaign['campaignId'] ) : '';
				$values['title']             = ! empty( $campaign['title'] ) ? $campaign['title'] : '';
				$values['slug']              = ! empty( $campaign['slug'] ) ? $campaign['slug'] : sanitize_title_with_dashes( $values['title'] );
				$values['target_link']       = ! empty( $campaign['targetLink'] ) ? $campaign['targetLink'] : home_url();
				$values['short_description'] = ! empty( $campaign['shortDescription'] ) ? $campaign['shortDescription'] : '';
				$values['body']              = ! empty( $campaign['body'] ) ? $campaign['body'] : '';
				$values['status']            = ! empty( $campaign['status'] ) ? $campaign['status'] : 'Draft';
				$values['rules']             = ! empty( $campaign['rules'] ) ? maybe_serialize( $campaign['rules'] ) : '';
				$values['meta_data']         = ! empty( $campaign['metaData'] ) ? maybe_serialize( $campaign['metaData'] ) : '';

				$result = false;

				if ( $campaign_id > 0 ) {
					$values['campaign_id'] = $campaign_id;
					$result                = $wpdb->query( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"UPDATE {$wpdb->prefix}afwc_campaigns SET title = %s, slug = %s, target_link = %s, short_description = %s, body = %s, status = %s, rules = %s, meta_data = %s WHERE id = %s",
														$values
													)
					);
				} else {
					$result       = $wpdb->query( // phpcs:ignore
										$wpdb->prepare( // phpcs:ignore
											"INSERT INTO {$wpdb->prefix}afwc_campaigns ( title, slug, target_link, short_description, body, status, rules, meta_data ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s )",
											$values
										)
					);
					$lastid = ! empty( $wpdb->insert_id ) ? $wpdb->insert_id : 0;
				}

				if ( false === $result ) {
					throw new RuntimeException( esc_html_x( 'Unable to save campaign. Database error.', 'campaign data save error message', 'affiliate-for-woocommerce' ) );
				}

				$response                     = array( 'ACK' => 'Success' );
				$response['last_inserted_id'] = ! empty( $lastid ) ? $lastid : 0;
			}

			wp_send_json( $response );
		}

		/**
		 * Function to handle delete campaign
		 *
		 * @param array $params delete campaign params.
		 */
		public function delete_campaign( $params = array() ) {
			check_admin_referer( 'afwc-admin-delete-campaign', 'security' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			global $wpdb;

			$response = array( 'ACK' => 'Failed' );
			if ( ! empty( $params['campaign_id'] ) ) {
				$result = $wpdb->query( // phpcs:ignore
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}afwc_campaigns WHERE id = %d",
						$params['campaign_id']
					)
				);
				if ( false === $result ) {
					wp_send_json(
						array(
							'ACK' => 'Error',
							'msg' => _x( 'Failed to delete campaign', 'campaign delete error message', 'affiliate-for-woocommerce' ),
						)
					);
				} else {
					wp_send_json(
						array(
							'ACK' => 'Success',
							'msg' => _x( 'Campaign deleted successfully', 'campaign deleted success message', 'affiliate-for-woocommerce' ),
						)
					);
				}
			}
		}

		/**
		 * Function to handle fetch data
		 *
		 * @param array $params fetch campaign dashboard data params.
		 */
		public function fetch_dashboard_data( $params = array() ) {

			$security = ( ! empty( $_POST['security'] ) ) ? wc_clean( wp_unslash( $_POST['security'] ) ) : ''; // phpcs:ignore

			if ( empty( $security ) ) {
				return;
			}

			$access = false;

			// Check for admin nonce.
			if ( current_user_can( 'manage_woocommerce' ) && wp_verify_nonce( $security, 'afwc-admin-campaign-dashboard-data' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
				$access = true;
			}

			// Check for affiliates account nonce.
			if ( ! $access ) {
				if ( ! wp_verify_nonce( $security, 'afwc-fetch-campaign' ) ) {
					return wp_send_json(
						array(
							'ACK' => 'Failed',
							'msg' => _x( 'You do not have permission to fetch the campaign details.', 'campaign fetching error message', 'affiliate-for-woocommerce' ),
						)
					);
				}

				$params['affiliate_id'] = get_current_user_id();
				$params['check_rules']  = true;
			}

			$result['kpi']       = $this->fetch_kpi();
			$result['campaigns'] = $this->fetch_campaigns( $params );

			if ( ! empty( $result ) ) {
				wp_send_json(
					array(
						'ACK'    => 'Success',
						'result' => $result,
					)
				);
			} else {
				wp_send_json(
					array(
						'ACK' => 'Success',
						'msg' => _x( 'No campaigns found', 'campaigns not found message', 'affiliate-for-woocommerce' ),
					)
				);
			}
		}

		/**
		 * Ajax callback method to return the extra rule data.
		 *
		 * @param array $params The params from API request.
		 */
		public function fetch_rule_data( $params = array() ) {

			check_admin_referer( 'afwc-admin-campaign-rule-data', 'security' );

			if ( empty( $params['rules'] ) ) {
				wp_send_json(
					array(
						'ACK' => 'Failed',
						'msg' => _x( 'Required parameters missing', 'Error message for fetching the extra rule data of campaign', 'affiliate-for-woocommerce' ),
					)
				);
			}

			$rule_data = json_decode( $params['rules'], true );

			wp_send_json(
				array(
					'ACK'    => 'Success',
					'result' => $this->get_rule_details( $rule_data, array( 'affiliates', 'affiliate_tags' ) ),
				)
			);
		}

		/**
		 * Ajax callback method to search the rule details.
		 *
		 * @param array $params The params from API request.
		 */
		public function search_rule_details( $params = array() ) {

			check_admin_referer( 'afwc-admin-campaign-search-rule-details', 'security' );

			if ( empty( $params['term'] ) ) {
				wp_die();
			}

			wp_send_json( $this->get_rule_details( $params['term'], array( 'affiliates', 'affiliate_tags' ), true ) );
		}

		/**
		 * Function to handle fetch campaigns
		 *
		 * @param array $params fetch campaign params.
		 * @return array $campaigns
		 */
		public function fetch_campaigns( $params = array() ) {
			global $wpdb;
			$campaigns = array();

			if ( ! empty( $params['campaign_status'] ) ) {
				$afwc_campaigns = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}afwc_campaigns WHERE status = %s ORDER BY id DESC", $params['campaign_status'] ),
					'ARRAY_A'
				);
			} else {
				$afwc_campaigns = $wpdb->get_results( // phpcs:ignore
					"SELECT * FROM {$wpdb->prefix}afwc_campaigns ORDER BY id DESC",
					'ARRAY_A'
				);
			}

			if ( ! empty( $afwc_campaigns ) ) {
				foreach ( $afwc_campaigns as $afwc_campaign ) {
					$campaign['campaignId']       = ! empty( $afwc_campaign['id'] ) ? $afwc_campaign['id'] : '';
					$campaign['title']            = ! empty( $afwc_campaign['title'] ) ? $afwc_campaign['title'] : '';
					$campaign['slug']             = ! empty( $afwc_campaign['slug'] ) ? $afwc_campaign['slug'] : '';
					$campaign['targetLink']       = ! empty( $afwc_campaign['target_link'] ) ? $afwc_campaign['target_link'] : home_url();
					$campaign['shortDescription'] = ! empty( $afwc_campaign['short_description'] ) ? $afwc_campaign['short_description'] : '';
					$campaign['body']             = ! empty( $afwc_campaign['body'] ) ? $afwc_campaign['body'] : '';
					$campaign['status']           = ! empty( $afwc_campaign['status'] ) ? $afwc_campaign['status'] : '';
					$campaign['rules']            = ! empty( $afwc_campaign['rules'] ) ? maybe_unserialize( $afwc_campaign['rules'] ) : '';
					$campaign['metaData']         = ! empty( $afwc_campaign['meta_data'] ) ? maybe_unserialize( $afwc_campaign['meta_data'] ) : '';
					$campaigns[]                  = $campaign;
				}
			}

			return $this->filter_campaigns( $campaigns, $params );
		}

		/**
		 * Method to filter the campaigns
		 *
		 * @param array $campaigns Array of campaigns.
		 * @param array $args      The arguments.
		 *
		 * @return array $campaigns
		 */
		public function filter_campaigns( $campaigns = array(), $args = array() ) {
			if ( empty( $campaigns ) ) {
				return array();
			}

			if ( empty( $args['check_rules'] ) ) {
				// No need to filter if the `check_rules` is disabled.
				return $campaigns;
			}

			$affiliate_id = ! empty( $args['affiliate_id'] ) ? intval( $args['affiliate_id'] ) : 0;
			if ( empty( $affiliate_id ) ) {
				return array();
			}

			$filtered_campaigns = array();

			foreach ( $campaigns as $campaign ) {
				if ( empty( $campaign['rules'] ) ) {
					$filtered_campaigns[] = $campaign; // Skip the validation with rule if there is no rule.
				} elseif ( $this->validate_campaign( $campaign['rules'], $affiliate_id ) ) {
					$filtered_campaigns[] = $campaign;
				}
			}

			return $filtered_campaigns;
		}

		/**
		 * Method to validate the campaign if any of one rule is satisfied.
		 *
		 * @param array $rules The rules.
		 * @param int   $affiliate_id The affiliate id.
		 *
		 * @return bool Return true if any one rule is validated otherwise false.
		 */
		public function validate_campaign( $rules = array(), $affiliate_id = 0 ) {
			if ( empty( $affiliate_id ) || empty( $rules ) ) {
				return false;
			}

			$affiliate = new AFWC_Affiliate( $affiliate_id );

			foreach ( $rules as $rule_key => $ids ) {
				if ( 'affiliates' === $rule_key ) {
					if ( in_array( $affiliate_id, $ids, true ) ) {
						return true;
					}
				} elseif ( 'affiliate_tags' === $rule_key ) {
					$tags = is_callable( array( $affiliate, 'get_tags' ) ) ? $affiliate->get_tags() : array();

					if ( ! empty( $tags ) && count( array_intersect( array_keys( $tags ), $ids ) ) > 0 ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Function to get campaign KIPs
		 *
		 * @return array $kpi
		 */
		public function fetch_kpi() {
			global $wpdb;
			$kpi          = array();
			$total_hits   = $wpdb->get_var( // phpcs:ignore
				"SELECT count(*) from {$wpdb->prefix}afwc_hits WHERE campaign_id != 0"
			);
			$total_orders = $wpdb->get_var( // phpcs:ignore
				"SELECT count(*) from {$wpdb->prefix}afwc_referrals WHERE campaign_id != 0"
			);

			$kpi                 = array();
			$kpi['total_hits']   = ! empty( $total_hits ) ? $total_hits : 0;
			$kpi['total_orders'] = ! empty( $total_orders ) ? $total_orders : 0;

			$kpi['conversion'] = ( $kpi['total_hits'] > 0 ) ? round( ( ( $kpi['total_orders'] * 100 ) / $kpi['total_hits'] ), 2 ) : 0;

			return $kpi;
		}

		/**
		 * Get campaign statuses.
		 *
		 * @param string $status Campaign Status.
		 *
		 * @return array|string Return the status title if the status is provided otherwise return array of all statuses.
		 */
		public static function get_statuses( $status = '' ) {
			$statuses = array(
				'Active' => _x( 'Active', 'active campaign status', 'affiliate-for-woocommerce' ),
				'Draft'  => _x( 'Draft', 'draft campaign status', 'affiliate-for-woocommerce' ),
			);

			return empty( $status ) ? $statuses : ( ! empty( $statuses[ $status ] ) ? $statuses[ $status ] : '' );
		}

		/**
		 * Methods to arrange the rules for frontend by the rule details.
		 *
		 * @param array $rules_values The rule values.
		 *
		 * @return array Return the formatted rules for frontend select2.
		 */
		public function arrange_rule( $rules_values = array() ) {

			if ( empty( $rules_values ) ) {
				return array();
			}

			$data = array();

			// For affiliate group.
			if ( ! empty( $rules_values['affiliates'] ) ) {
				$data[] = array(
					'title'    => _x( 'Affiliates', 'The group name for affiliate list', 'affiliate-for-woocommerce' ),
					'group'    => 'affiliates',
					'children' => $rules_values['affiliates'],
				);
			}

			// For affiliate tags group.
			if ( ! empty( $rules_values['affiliate_tags'] ) ) {
				$data[] = array(
					'title'    => _x( 'Affiliate Tags', 'The group name affiliate tags list', 'affiliate-for-woocommerce' ),
					'group'    => 'affiliate_tags',
					'children' => $rules_values['affiliate_tags'],
				);
			}

			return $data;
		}

		/**
		 * Method to get the rule details by providing search term or rule data.
		 *
		 * @param string|array $term The value.
		 * @param array        $group The group name.
		 * @param bool         $for_search Whether the method will be used for searching or fetching the details by id.
		 *
		 * @return array.
		 */
		public function get_rule_details( $term = '', $group = array(), $for_search = false ) {

			if ( empty( $term ) ) {
				return array();
			}

			global $affiliate_for_woocommerce;

			$values = array();

			if ( ! is_array( $group ) ) {
				$group = (array) $group;
			}

			// Check the rule details for affiliate group.
			if ( true === in_array( 'affiliates', $group, true ) ) {
				if ( true === $for_search && is_scalar( $term ) ) {
					$affiliate_search = array(
						'search'         => '*' . $term . '*',
						'search_columns' => array( 'ID', 'user_nicename', 'user_login', 'user_email' ),
					);
				} elseif ( ! empty( $term['affiliates'] ) ) {
					$affiliate_search = array(
						'include' => ! is_array( $term['affiliates'] ) ? (array) $term['affiliates'] : $term['affiliates'],
					);
				}

				$values['affiliates'] = ! empty( $affiliate_search ) && is_callable( array( $affiliate_for_woocommerce, 'get_affiliates' ) ) ? $affiliate_for_woocommerce->get_affiliates( $affiliate_search ) : array();
			}

			// Check the rule details for affiliate tags.
			if ( true === in_array( 'affiliate_tags', $group, true ) ) {
				if ( true === $for_search && is_scalar( $term ) ) {
					$tag_search['search'] = $term;
				} elseif ( ! empty( $term['affiliate_tags'] ) ) {
					$tag_search['include'] = ! is_array( $term['affiliate_tags'] ) ? (array) $term['affiliate_tags'] : $term['affiliate_tags'];
				}

				if ( ! empty( $tag_search ) ) {
					$tag_search = $tag_search + array(
						'taxonomy'   => 'afwc_user_tags', // taxonomy name.
						'hide_empty' => false,
						'fields'     => 'id=>name',
					);

					$tags = get_terms( $tag_search );

					if ( ! empty( $tags ) ) {
						$values['affiliate_tags'] = $tags;
					}
				}
			}

			return $this->arrange_rule( $values );
		}
	}

}

return AFWC_Campaign_Dashboard::get_instance();
