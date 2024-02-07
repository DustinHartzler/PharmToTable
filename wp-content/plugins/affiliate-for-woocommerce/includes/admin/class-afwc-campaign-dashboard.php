<?php
/**
 * Main class for Campaigns Dashboard
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @version     1.3.2
 */

// Exit if accessed directly.
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
			'regenerate_sample_campaigns',
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

			if ( ! afwc_current_user_can_manage_affiliate() ) {
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

				$response = array(
					'ACK'              => 'Success',
					'last_inserted_id' => ! empty( $lastid ) ? $lastid : 0,
					'data'             => $this->fetch_campaigns(),
				);
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

			if ( ! afwc_current_user_can_manage_affiliate() ) {
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
			if ( afwc_current_user_can_manage_affiliate() && wp_verify_nonce( $security, 'afwc-admin-campaign-dashboard-data' ) ) {
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
					"SELECT * FROM {$wpdb->prefix}afwc_campaigns ORDER BY
						CASE status WHEN 'Active' THEN 1
							WHEN 'Draft' THEN 2
							ELSE 3 END
						,id DESC",
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

		/**
		 * Function to regenerate sample campaigns.
		 */
		public function regenerate_sample_campaigns() {
			check_admin_referer( 'afwc-admin-create-sample-campaigns', 'security' );

			if ( ! afwc_current_user_can_manage_affiliate() ) {
				wp_die( esc_html_x( 'You are not allowed to re-generate sample campaigns.', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			$response = array( 'ACK' => 'Failed' );

			$sample_campaigns = $this->get_sample_campaigns();
			if ( empty( $sample_campaigns ) || ! is_array( $sample_campaigns ) ) {
				wp_send_json( $response );
			}

			$is_inserted = $this->add_sample_campaigns_to_db( $sample_campaigns );
			if ( ! $is_inserted ) {
				wp_send_json( $response );
			}

			$response = array(
				'ACK'  => 'Success',
				'data' => $this->fetch_campaigns(),
			);

			wp_send_json( $response );
		}

		/**
		 * Function to get sample campaign.
		 */
		public function get_sample_campaigns() {
			$sample_campaigns = array();

			$sample                             = array();
			$sample['title']                    = 'Start Here: Common Assets, Logo, Branding';
			$sample['slug']                     = 'init' === current_action() ? 'common' : Affiliate_For_WooCommerce::uniqid( 'common-' );
			$sample['target_link']              = '';
			$sample['short_description']        = 'We\'ve included the most important design assets for you here. Please follow style guide and respect the terms of the affiliate program.';
			$sample['body']                     = '<section style="margin-top: 2rem;">
				<h2 style="margin-top: 0.5rem; font-size: 1.5rem; line-height: 2rem; color: #111827;">Logo &amp; Style Guide</h2>
				<p style="margin-top: 0.5rem;">Our logo and logo variations are our own property and we retain all rights afforded by US and international Law.</p>
				<p style="margin-top: 0.5rem;">As an affiliate partner, you can use our logo on your site to promote our products. But please ensure you follow the color, sizing and other branding guidelines.</p>
				<div style="margin-top: 0.5rem; display: grid; gap: 2rem; grid-template-columns: repeat(4, minmax(0, 1fr));">
				<div style="display: flex; flex-direction: column; padding: 2rem; text-align: center; background-color: #ffffff; border:0.06rem solid #E5E7EB;"><img  alt="logo" height="40" src="https://www.storeapps.org/wp-content/uploads/2020/07/storeapps-logo.svg" /> <span style="margin-top: 0.25rem; font-size: 0.75rem; line-height: 1rem; color: #6B7280;">Dark on light background</span></div>
				<div style="display: flex ;flex-direction: column; padding: 2rem; text-align: center; background-color: #111827; border:0.06rem solid #E5E7EB;"><img  alt="logo" height="40" src="https://www.storeapps.org/wp-content/uploads/2020/07/storeapps-logo-for-dark-bg.svg" /> <span style="margin-top: 0.25rem; font-size: 0.75rem; line-height: 1rem; color: #9CA3AF;">Light on dark background</span></div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>
				</div>
				<p style="margin-top: 0.5rem;"><a href="#"><strong>Download logo pack</strong></a><br /><span style="font-size: 0.875rem; line-height: 1.25rem; color: #4B5563;">(contains .png and .svg versions, both on light and dark backgrounds)</span></p>
				<p style="margin-top: 0.5rem;">Before using, please <a class="underline" href="https://woo.com/trademark-guidelines/">read our detailed style guide</a>: what\'s allowed and what\'s not.</p>
				</section>
				<section style="margin-top:2rem;">
				<h2 style="margin-top: 0.5rem; font-size: 1.5rem; line-height: 2rem; color: #111827;">Color Palette</h2>
				<div style="margin-top: 0.5rem; display: grid; gap: 2rem; grid-template-columns: repeat(4, minmax(0, 1fr));">
				<div style="margin-top: 0.5rem; padding: 2rem; color: #ffffff;background-color: #4F46E5;">Primary color (Indigo): #5850ec</div>
				<div style="margin-top: 0.5rem; padding: 2rem; color: #ffffff;background-color: #111827;">Secondary color (Dark Gray): #1a202e</div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>
				</div>
				</section>
				<section style="margin-top:2rem;">
				<h2 style="margin-top: 0.5rem; font-size: 1.5rem; line-height: 2rem; color: #111827;">Typography</h2>
				<p style="margin-top: 0.5rem;">We use one primary typeface in all our marketing materials - Proxima Nova.</p>
				<p style="margin-top: 0.5rem;">Proxima Nova is available from Adobe Typekit. If you do not have access to it, you may use another Sans Serif font.</p>
				</section>
				<section style="margin-top: 2rem">
				<h2 style="margin-top: 0.5rem; font-size: 1.5rem; line-height: 2rem; color: #111827; ">Banner Ads / Creatives</h2>
				<p style="margin-top: 0.5rem;">Feel free to create your own banners and graphics to promote us. Something your audience will resonate with. We\'ve found that works best.</p>
				<p style="margin-top: 0.5rem;">Here are some banners that you can use as-is, or as an inspiration.</p>
				<div style="margin-top: 0.5rem; display: flex; flex-wrap: wrap; flex: 1 1 auto; font-size: 0.75rem; line-height: 1rem; color: #6B7280">
				<p style="margin-top: 2rem; margin-left: 2rem;">Google Small Square (200x200 px) <br /><img border="1" style="height: 200px; width: 200px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="200" height="200" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Vertical Rectangle (240&times;400 px) <br /><img border="1" style="height: 400px; width: 240px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="240" height="400" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;margin-top: 2rem;">Google Square (250&times;250 px) <br /><img border="1" style="height: 250px; width: 250px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="250" height="250" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Inline Rectangle (300&times;250 px)<br /><img border="1" style="height: 250px; width: 300px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="300" height="250" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Skyscraper (120&times;600 px) <br /><img border="1" style="height: 600px; width: 120px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="120" height="600" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Wide Skyscraper (160&times;600 px) <br /><img border="1" style="height: 600px; width: 160px; border-width: .06rem;border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="160" height="600" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google HalfPage Ad (300&times;600 px)<br /><img border="1" style="height: 600px; width: 300px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="300" height="600" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Banner (468&times;60 px) <br /><img border="1" style="height: 60px; width: 468px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="468" height="60" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Leaderboard (728&times;90 px) <br /><img border="1" style="height: 90px; width: 728px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="728" height="90" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Large Leaderboard (970&times;90 px)<br /><img border="1" style="height: 90px; width: 970px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="970" height="90" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Billboard (970&times;250 px) <br /><img border="1" style="height: 250px; width: 970px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="970" height="250" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Mobile Banner (320&times;50 px) <br /><img border="1" style="height: 50px; width: 320px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="320" height="50" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Google Large Mobile Banner (320&times;100 px) <br /><img border="1" style="height: 100px; width: 320px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="320" height="100" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Facebook Ad (1200&times;628 px) <br /><img border="1" style="height: 628px; width: 1200px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="1200" height="628" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Twitter Lead Generation Card (800&times;200 px) <br /><img border="1" style="height: 200px; width: 800px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="800" height="200" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Twitter Image App Card (800&times;320 px) <br /><img border="1" style="height: 320px; width: 800px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="800" height="320" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Youtube Display Ad (300&times;60 px) <br /><img border="1" style="height: 60px; width: 300px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="300" height="60" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Youtube Display Ad (300&times;250 px) <br /><img border="1" style="height: 250px; width: 300px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="300" height="250" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Youtube Overlay Ad (480&times;70 px) <br /><img border="1" style="height: 70px; width: 480px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="480" height="70" /></p>
				<p style="margin-left: 2rem; margin-top: 2rem;">Adroll Rectangle (180&times;150 px)<br /><img border="1" style="height: 150px; width: 180px; border-width: .06rem; border-color: #374151; background-color: #D1D5DB;" src="https://www.storeapps.org/wp-content/uploads/2013/01/spacer.gif" width="180" height="150" /></p>
				</div>
				<p style="margin-top: 0.5rem;"><a href="#"><strong>Download banner pack</strong></a><br /><span style="font-size: 0.875rem; line-height: 1.25rem; color: #4B5563;">(contains optimized image formats)</span></p>
				</section>';
							$sample['status']   = 'Draft';
							$sample_campaigns[] = $sample;

							$sample                      = array();
							$sample['title']             = 'Email Swipes';
							$sample['slug']              = 'init' === current_action() ? 'email' : Affiliate_For_WooCommerce::uniqid( 'email-' );
							$sample['target_link']       = '';
							$sample['short_description'] = 'Email marketing has very high conversion rates. Emailing your audience about our products can be one of the quickest ways to make money. Here are some ready emails you can use (and tweak as you like).';
							$sample['body']              = '<section style="margin-top: 2rem;">
				<h2 style="margin-top: 0.5rem;font-size: 1.5rem;line-height: 2rem;color: #111827;">New Product Launch</h2>
				<p style="margin-top: 0.5rem;"><strong>SUBJECT: Just Launched - Awesome Product Name</strong></p>
				<textarea class="form-textarea" style="margin-top: 0.5rem;" cols="80" rows="20">Hi,

				Want to {your product\'s main benefit}?

				I\'ve just discovered the right solution - {your product\'s name}.

				It works really well.

				{affiliate link here}

				Here\'s why I love this company and their products:

				* Benefit 1
				* Benefit 2
				* Unique Feature 1
				* Unique Feature 2
				* Their attention to detail
				+ They\'re just super nice to do business with

				If you\'re looking to {another benefit + scarcity}, this is it!

				Get it here:

				{affiliate link here}

				To your success,
				{affiliate name}
				</textarea></section>
				<section style="margin-top: 2rem;">
				<h2 style="margin-top: 0.5rem; font-size: 1.5rem; line-height: 2rem; color: #111827;">Solution to their problem</h2>
				<p style="margin-top: 0.5rem;"><strong>SUBJECT: Your Solution for {audience\'s big problem}</strong></p>
				<textarea class="form-textarea" style="margin-top: 0.5rem;" cols="80" rows="20">Hi,

				Hope you\'re doing well.

				When it comes to list building and email marketing, there are so many ways to approach it.

				Wouldn\'t it be great to just get the meat of it all so you can get started faster?

				Well, the good news is, today you can grab my latest course called the \'Email List Building Blueprint\'.

				Get one for you here:

				{affiliate link here}

				Here\'s why you should buy this:

				* Benefit 1
				* Benefit 2
				* Unique Feature 1
				* Unique Feature 2
				* Their attention to detail
				+ They\'re just super nice to do business with

				It\'s rather simple. You could either spend hours, even days researching how to {benefit} or get this {product}
				and speed up your success.

				Get instant access here:

				{affiliate link here}

				To your success,
				{affiliate name}
				</textarea></section>';
							$sample['status']            = 'Draft';
							$sample_campaigns[]          = $sample;

							$sample                      = array();
							$sample['title']             = 'Single Product Promotion';
							$sample['slug']              = 'init' === current_action() ? 'product' : Affiliate_For_WooCommerce::uniqid( 'product-' );
							$sample['target_link']       = '';
							$sample['short_description'] = 'Promoting a single product requires slightly different marketing material than a whole brand. As a matter of fact, individual products would be easier to promote because you can talk about specific benefits and how they relate to your target audience. Here are some resources to promote a single product.';
							$sample['body']              = '<section style="margin-top: 2rem;">
				<h2 style="margin-top: 0.5rem; font-size: 1.5rem; line-height: 2rem; color: #111827;">{Awesome Product} Promo</h2>
				<p style="margin-top: 0.5rem;">You may use any of these marketing assets:</p>
				<ul style="margin-top: 0.5rem;">
				<li>Product Name: {Awesome Product}</li>
				<li>Photos: {link to product\'s main image}, {link to product\'s other images}</li>
				<li>Video: {link to product\'s promo video}</li>
				<li>Description: {product description}</li>
				<li>Price: {product price}</li>
				<li>Offer Price: {product discounted price}</li>
				<li>Coupon: {coupon code}</li>
				</ul>
				</section>
				<section style="margin-top: 2rem;">
				<h2 style="margin-top: 0.5rem; font-size: 1.5rem; line-height: 2rem; color: #111827;">Want to promote another product?</h2>
				<ul style="margin-top: 0.5rem;">
				<li>You may use product name, photos, videos, description and other marketing resources from our sales pages in your own promotional material.</li>
				<li>To take people directly to a product\'s page, append your affiliate tracking code to the URL, test it works, and then share with your people.</li>
				<li>You can use any channel of your choice - email, social media, direct promotions...</li>
				<li>If you would like to provide a coupon code, please contact us and we can work something out.</li>
				</ul>
				</section>';
			$sample['status']                            = 'Draft';
			$sample_campaigns[]                          = $sample;

			return $sample_campaigns;
		}

		/**
		 * Method to update campaigns into database.
		 *
		 * @param array $sample_campaigns Default Campaigns.
		 *
		 * @return bool Return true or false.
		 */
		public function add_sample_campaigns_to_db( $sample_campaigns = array() ) {
			if ( empty( $sample_campaigns ) || ! is_array( $sample_campaigns ) ) {
				return false;
			}

			global $wpdb;

			foreach ( $sample_campaigns as $campaign ) {
				$wpdb->insert( // phpcs:ignore
					$wpdb->prefix . 'afwc_campaigns',
					$campaign,
					array( '%s', '%s', '%s', '%s', '%s', '%s' )
				);
			}

			return true;
		}
	}

}

return AFWC_Campaign_Dashboard::get_instance();
