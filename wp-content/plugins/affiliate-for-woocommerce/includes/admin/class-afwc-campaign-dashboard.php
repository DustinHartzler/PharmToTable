<?php
/**
 * Main class for Campaigns Dashboard
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @version     1.1.3
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
		);

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
					$params[ $key ] = trim( wc_clean( wp_unslash( $value ) ) );
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

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
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
				$values['meta_data']         = ! empty( $campaign['metaData'] ) ? maybe_serialize( $campaign['metaData'] ) : '';

				if ( $campaign_id > 0 ) {
					$values['campaign_id'] = $campaign_id;
					$result                = $wpdb->query( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"UPDATE {$wpdb->prefix}afwc_campaigns SET title = %s, slug = %s, target_link = %s, short_description = %s, body = %s, status = %s, meta_data = %s WHERE id = %s",
														$values
													)
					);
				} else {
					$result       = $wpdb->query( // phpcs:ignore
										$wpdb->prepare( // phpcs:ignore
											"INSERT INTO {$wpdb->prefix}afwc_campaigns ( title, slug, target_link, short_description, body, status, meta_data ) VALUES ( %s, %s, %s, %s, %s, %s, %s )",
											$values
										)
					);
					$lastid = $wpdb->insert_id;
				}

				if ( false === $result ) {
					throw new RuntimeException( _x( 'Unable to save campaign. Database error.', 'campaign data save error message', 'affiliate-for-woocommerce' ) );
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

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
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
		public function fetch_dashboard_data( $params ) {

			if ( empty( $_POST['security'] ) ) { // phpcs:ignore
				return;
			}

			$security = wc_clean( wp_unslash( $_POST['security'] ) ); // phpcs:ignore 

			if ( ( wp_verify_nonce( $security, 'afwc-admin-campaign-dashboard-data' ) && current_user_can( 'manage_woocommerce' ) ) || wp_verify_nonce( $security, 'afwc-fetch-campaign' ) ) {

				$result['kpi']       = $this->fetch_kpi( $params );
				$result['campaigns'] = $this->fetch_camapigns( $params );
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

			wp_send_json(
				array(
					'ACK' => 'Failed',
					'msg' => _x( 'You do not have permission to fetch the campaigns', 'campaign fetching error message', 'affiliate-for-woocommerce' ),
				)
			);

		}

		/**
		 * Function to handle fetch campaigns
		 *
		 * @param array $params fetch campaign params.
		 * @return array $campaigns
		 */
		public static function fetch_camapigns( $params ) {
			global $wpdb;
			$campaigns = array();

			if ( ! empty( $params['campaign_status'] ) ) {
				$afwc_campaigns = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}afwc_campaigns WHERE status = %s", $params['campaign_status'] ),
					'ARRAY_A'
				);
			} else {
				$afwc_campaigns = $wpdb->get_results( // phpcs:ignore
					"SELECT * FROM {$wpdb->prefix}afwc_campaigns",
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
					$campaign['metaData']         = ! empty( $afwc_campaign['meta_data'] ) ? maybe_unserialize( $afwc_campaign['meta_data'] ) : '';
					$campaigns[]                  = $campaign;
				}
			}

			return $campaigns;
		}

		/**
		 * Function to get campaign KIPs
		 *
		 * @param array $params fetch params.
		 * @return array $kpi
		 */
		public function fetch_kpi( $params ) {
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

	}

}

return new AFWC_Campaign_Dashboard();
