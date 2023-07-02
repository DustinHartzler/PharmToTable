<?php
/**
 * Main class for Commission Dashboard
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       2.5.0
 * @version     1.3.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Commission_Dashboard' ) ) {

	/**
	 * Main class for Commission Dashboard
	 */
	class AFWC_Commission_Dashboard {

		/**
		 * The Ajax events.
		 *
		 * @var array $ajax_events
		 */
		private $ajax_events = array(
			'save_commission',
			'delete_commission',
			'fetch_dashboard_data',
			'save_plan_order',
			'fetch_extra_data',
		);

		/**
		 * Variable to hold instance of AFWC_Commission_Dashboard
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Commission_Dashboard Singleton object of this class
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
			add_action( 'wp_ajax_afwc_commission_controller', array( $this, 'request_handler' ) );
			add_action( 'wp_ajax_afwc_json_search_rule_values', array( $this, 'afwc_json_search_rule_values' ), 1, 2 );
		}

		/**
		 * Function to handle all ajax request
		 */
		public function request_handler() {
			if ( ! current_user_can( 'manage_woocommerce' ) || empty( $_REQUEST ) || empty( wc_clean( wp_unslash( $_REQUEST['cmd'] ) ) ) ) { // phpcs:ignore
				return;
			}

			foreach ( $_REQUEST as $key => $value ) { // phpcs:ignore
				if ( 'commission' === $key ) {
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
		 * Function to handle save commission
		 *
		 * @throws RuntimeException Data Exception.
		 * @param array $params save commission params.
		 */
		public function save_commission( $params = array() ) {

			check_admin_referer( 'afwc-admin-save-commissions', 'security' );

			global $wpdb;

			$response                  = array( 'ACK' => 'Failed' );
			$afwc_storewide_commission = get_option( 'afwc_storewide_commission', true );
			if ( ! empty( $params['commission'] ) ) {
				$commission = json_decode( $params['commission'], true );
				$values     = array();

				$commission_id                  = ! empty( $commission['commissionId'] ) ? intval( $commission['commissionId'] ) : '';
				$values['name']                 = ! empty( $commission['name'] ) ? $commission['name'] : '';
				$values['rules']                = ! empty( $commission['rules'] ) ? wp_json_encode( $commission['rules'] ) : '';
				$values['amount']               = ! empty( $commission['amount'] ) ? $commission['amount'] : $afwc_storewide_commission;
				$values['type']                 = ! empty( $commission['type'] ) ? $commission['type'] : 'Percentage';
				$values['status']               = ! empty( $commission['status'] ) ? $commission['status'] : 'Active';
				$values['apply_to']             = ! empty( $commission['apply_to'] ) ? $commission['apply_to'] : 'all';
				$values['action_for_remaining'] = ! empty( $commission['action_for_remaining'] ) ? $commission['action_for_remaining'] : 'continue';
				$values['no_of_tiers']          = ! empty( $commission['no_of_tiers'] ) ? $commission['no_of_tiers'] : '1';
				$values['distribution']         = ! empty( $commission['distribution'] ) ? implode( '|', (array) $commission['distribution'] ) : '';

				if ( $commission_id > 0 ) {
					$values['commission_id'] = $commission_id;
					$result                = $wpdb->query( // phpcs:ignore
													$wpdb->prepare( // phpcs:ignore
														"UPDATE {$wpdb->prefix}afwc_commission_plans SET name = %s, rules = %s, amount = %s, type = %s, status = %s, apply_to = %s, action_for_remaining = %s, no_of_tiers = %s, distribution = %s WHERE id = %s",
														$values
													)
					);
				} else {
					$result       = $wpdb->query( // phpcs:ignore
										$wpdb->prepare( // phpcs:ignore 
											"INSERT INTO {$wpdb->prefix}afwc_commission_plans ( name, rules, amount, type, status, apply_to, action_for_remaining, no_of_tiers, distribution ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s )",
											$values
										)
					);
					$lastid = $wpdb->insert_id;

					$plan_order = $this->get_commission_plans_order();
					$len        = count( $plan_order );

					// add new plan id to the -2 position.

					array_splice( $plan_order, $len, 0, $lastid );
					update_option( 'afwc_plan_order', $plan_order, 'no' );
				}

				if ( false === $result ) {
					throw new RuntimeException( _x( 'Unable to save commission plan. Database error.', 'commission plan save error message', 'affiliate-for-woocommerce' ) );
				}

				$response                     = array( 'ACK' => 'Success' );
				$response['last_inserted_id'] = ! empty( $lastid ) ? $lastid : 0;
			}
			wp_send_json( $response );
		}

		/**
		 * Function to handle delete commission
		 *
		 * @param array $params delete commission params.
		 */
		public function delete_commission( $params = array() ) {

			check_admin_referer( 'afwc-admin-delete-commissions', 'security' );

			global $wpdb;

			$response = array( 'ACK' => 'Failed' );
			if ( ! empty( $params['commission_id'] ) ) {

				$default_plan = afwc_get_default_commission_plan_id();

				if ( intval( $params['commission_id'] ) === $default_plan ) {
					return wp_send_json(
						array(
							'ACK' => 'Error',
							'msg' => _x( 'Default plan can not be deleted', 'commission default plan delete error message', 'affiliate-for-woocommerce' ),
						)
					);
				}

				$result = $wpdb->query( // phpcs:ignore
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}afwc_commission_plans WHERE id = %d",
						$params['commission_id']
					)
				);
				if ( false === $result ) {
					wp_send_json(
						array(
							'ACK' => 'Error',
							'msg' => _x( 'Failed to delete commission plan', 'commission plan delete error message', 'affiliate-for-woocommerce' ),
						)
					);
				} else {
					// delete from plan order.
					$plan_order = $this->get_commission_plans_order();
					if ( ! empty( $plan_order ) ) {
						$c          = $params['commission_id'];
						$plan_order = array_filter(
							$plan_order,
							function( $e ) use ( $c ) {
								$e = absint( $e );
								$c = absint( $c );
								return ( $e !== $c );
							}
						);
						update_option( 'afwc_plan_order', $plan_order, 'no' );
					}
					wp_send_json(
						array(
							'ACK' => 'Success',
							'msg' => _x( 'Commission plan deleted successfully', 'commission plan delete success message', 'affiliate-for-woocommerce' ),
						)
					);
				}
			}
		}

		/**
		 * Function to handle fetch data
		 *
		 * @param array $params fetch commission dashboard data params.
		 */
		public function fetch_dashboard_data( $params = array() ) {

			check_admin_referer( 'afwc-admin-commissions-dashboard-data', 'security' );

			$commission_plans = self::fetch_commission_plans( $params );

			if ( ! empty( $commission_plans ) ) {
				wp_send_json(
					array(
						'ACK'    => 'Success',
						'result' => array(
							'commissions' => $commission_plans,
							'plan_order'  => $this->get_commission_plans_order(),
						),
					)
				);
			}

			wp_send_json(
				array(
					'ACK' => 'Failed',
					'msg' => _x( 'No commission plans found', 'commission plans not found message', 'affiliate-for-woocommerce' ),
				)
			);

		}

		/**
		 * Function to handle fetch commissions
		 *
		 * @param array $params fetch commission params.
		 */
		public static function fetch_commission_plans( $params = array() ) {
			global $wpdb;
			$commissions = array();

			if ( ! empty( $params['commission_status'] ) ) {
				$afwc_commissions = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}afwc_commission_plans WHERE status = %s", $params['commission_status'] ),
					'ARRAY_A'
				);
			} else {
				$afwc_commissions = $wpdb->get_results( // phpcs:ignore
					"SELECT * FROM {$wpdb->prefix}afwc_commission_plans",
					'ARRAY_A'
				);
			}
			$default_plan_details = afwc_get_default_plan_details();
			$default_no_of_tiers  = ! empty( $default_plan_details['no_of_tiers'] ) ? $default_plan_details['no_of_tiers'] : 0;
			$default_distribution = ! empty( $default_plan_details['distribution'] ) ? $default_plan_details['distribution'] : array();
			if ( ! empty( $afwc_commissions ) ) {
				foreach ( $afwc_commissions as $afwc_commission ) {
					$commission['commissionId']         = ! empty( $afwc_commission['id'] ) ? $afwc_commission['id'] : '';
					$commission['name']                 = ! empty( $afwc_commission['name'] ) ? $afwc_commission['name'] : '';
					$commission['rules']                = ! empty( $afwc_commission['rules'] ) ? json_decode( $afwc_commission['rules'] ) : '';
					$commission['amount']               = ! empty( $afwc_commission['amount'] ) ? $afwc_commission['amount'] : '';
					$commission['type']                 = ! empty( $afwc_commission['type'] ) ? $afwc_commission['type'] : '';
					$commission['status']               = ! empty( $afwc_commission['status'] ) ? $afwc_commission['status'] : '';
					$commission['apply_to']             = ! empty( $afwc_commission['apply_to'] ) ? $afwc_commission['apply_to'] : 'all';
					$commission['action_for_remaining'] = ! empty( $afwc_commission['action_for_remaining'] ) ? $afwc_commission['action_for_remaining'] : 'continue';
					$commission['no_of_tiers']          = ! empty( $afwc_commission['no_of_tiers'] ) ? $afwc_commission['no_of_tiers'] : $default_no_of_tiers;
					$commission['distribution']         = ! empty( $afwc_commission['distribution'] ) ? explode( '|', $afwc_commission['distribution'] ) : $default_distribution;
					$commissions[]                      = $commission;
				}
			}
			return $commissions;
		}

		/**
		 * Function to handle save plan order
		 *
		 * @param array $params save plan order params.
		 */
		public static function save_plan_order( $params = array() ) {

			check_admin_referer( 'afwc-admin-save-commission-order', 'security' );

			$default_plan_id = afwc_get_default_commission_plan_id();
			if ( ! empty( $params['plan_order'] ) ) {
				$plan_order = (array) json_decode( $params['plan_order'], true );

				if ( ! empty( $default_plan_id ) ) {
					$key = array_search( $default_plan_id, $plan_order, true );
					if ( false !== $key ) {
						unset( $plan_order[ $key ] );
					}
					$plan_order[] = $default_plan_id;
				}
				$plan_order = array_values( $plan_order );
				update_option( 'afwc_plan_order', $plan_order, 'no' );
				wp_send_json(
					array(
						'ACK'    => 'Success',
						'result' => $plan_order,
					)
				);
			} else {
				wp_send_json(
					array(
						'msg' => _x( 'No commission plan order to save', 'no commission plan order save message', 'affiliate-for-woocomerce' ),
					)
				);
			}
		}

		/**
		 * Search for attribute values and return json
		 *
		 * @param string $x string.
		 * @param string $attribute string.
		 * @return void
		 */
		public function afwc_json_search_rule_values( $x = '', $attribute = '' ) {

			check_admin_referer( 'afwc-admin-search-commission-plans', 'security' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die( esc_html_x( 'You are not allowed to use this action', 'authorization failure message', 'affiliate-for-woocommerce' ) );
			}

			$term = ( ! empty( $_GET['term'] ) ) ? (string) urldecode( wp_strip_all_tags( wp_unslash( $_GET ['term'] ) ) ) : '';
			$type = ( ! empty( $_GET['type'] ) ) ? (string) urldecode( wp_strip_all_tags( wp_unslash( $_GET ['type'] ) ) ) : 'affiliate';

			if ( empty( $term ) ) {
				wp_die();
			}

			$rule_values = array();

			if ( ! empty( $type ) ) {
				$function    = 'get_' . $type . '_id_name_map';
				$rule_values = is_callable( array( 'self', $function ) ) ? self::$function( $term ) : array();
			}

			echo wp_json_encode( $rule_values );
			wp_die();
		}

		/**
		 * Get user id name map
		 *
		 * @param string|array $term The searched term.
		 * @param bool         $for_ajax Check if call with ajax.
		 *
		 * @return $rule_values array
		 */
		public static function get_affiliate_id_name_map( $term = '', $for_ajax = true ) {

			$rule_values = array();

			if ( empty( $term ) ) {
				return $rule_values;
			}

			global $affiliate_for_woocommerce;

			if ( true === $for_ajax ) {
				$search = array(
					'search'         => '*' . $term . '*',
					'search_columns' => array( 'ID', 'user_nicename', 'user_login', 'user_email' ),
				);
			} else {
				// Fetch affiliates by user ids.
				if ( ! is_array( $term ) ) {
					$term = (array) $term;
				}
				$search = array(
					'include' => $term,
				);
			}

			$rule_values = is_callable( array( $affiliate_for_woocommerce, 'get_affiliates' ) ) ? $affiliate_for_woocommerce->get_affiliates( $search ) : $rule_values;

			return $rule_values;

		}

		/**
		 * Get product id name map
		 *
		 * @param string|array $term The searched term.
		 * @param bool         $for_ajax Check if call with ajax.
		 *
		 * @return $rule_values array
		 */
		public static function get_product_id_name_map( $term = '', $for_ajax = true ) {

			$rule_values = array();

			if ( empty( $term ) ) {
				return $rule_values;
			}

			$search_meta_key = ( false === $for_ajax && is_array( $term ) ) ? 'post__in' : 's';

			$products = get_posts(
				array(
					'post_type'      => array( 'product', 'product_variation' ),
					'numberposts'    => -1,
					'post_status'    => 'publish',
					'fields'         => 'ids',
					$search_meta_key => $term,
				)
			);

			if ( ! empty( $products ) ) {
				foreach ( $products as $id ) {
					$product = wc_get_product( $id );

					if ( $product instanceof WC_Product && is_callable( array( $product, 'get_formatted_name' ) ) ) {
						$rule_values[ $id ] = wp_strip_all_tags( $product->get_formatted_name() );
					}
				}
			}

			return $rule_values;

		}

		/**
		 * Get affiliate tag id name map
		 *
		 * @param string|array $term The searched term.
		 * @param bool         $for_ajax Check if call with ajax.
		 *
		 * @return $rule_values array
		 */
		public static function get_affiliate_tag_id_name_map( $term = '', $for_ajax = true ) {

			$rule_values = array();

			if ( empty( $term ) ) {
				return $rule_values;
			}

			if ( $for_ajax ) {
				$args = array(
					'taxonomy'   => 'afwc_user_tags', // taxonomy name.
					'hide_empty' => false,
					'name__like' => $term,
					'fields'     => 'ids',
				);

			} else {
				$args = array(
					'taxonomy'   => 'afwc_user_tags', // taxonomy name.
					'hide_empty' => false,
					'fields'     => 'ids',
					'include'    => $term,
				);
			}
			$raw_tags = get_terms( $args );
			if ( ! empty( $raw_tags ) ) {
				foreach ( $raw_tags as $id ) {
					$rule_values[ $id ] = get_term( $id )->name;
				}
			}

			return $rule_values;
		}

		/**
		 * Get product category id name map
		 *
		 * @param string|array $term The searched term.
		 * @param bool         $for_ajax Check if call with ajax.
		 *
		 * @return $rule_values array
		 */
		public static function get_product_category_id_name_map( $term = '', $for_ajax = true ) {

			$rule_values = array();

			if ( empty( $term ) ) {
				return $rule_values;
			}

			if ( $for_ajax ) {
				$args = array(
					'taxonomy'   => 'product_cat', // taxonomy name.
					'hide_empty' => false,
					'name__like' => $term,
					'fields'     => 'ids',
				);

			} else {
				$args = array(
					'taxonomy'   => 'product_cat', // taxonomy name.
					'hide_empty' => false,
					'fields'     => 'ids',
					'include'    => $term,
				);
			}
			$raw_prod_cat = get_terms( $args );
			if ( ! empty( $raw_prod_cat ) ) {
				foreach ( $raw_prod_cat as $id ) {
					$rule_values[ $id ] = get_term( $id )->name;
				}
			}

			return $rule_values;
		}

		/**
		 * Fetch data call
		 *
		 * @param string $params mixed.
		 */
		public function fetch_extra_data( $params ) {
			check_admin_referer( 'afwc-admin-extra-data', 'security' );
			$data = json_decode( $params['data'], true );
			foreach ( $data as $type => $ids ) {
				$function             = 'get_' . $type . '_id_name_map';
				$rule_values[ $type ] = is_callable( array( 'self', $function ) ) ? self::$function( $ids, false ) : array();
			}

			if ( ! empty( $rule_values ) ) {
				wp_send_json(
					array(
						'ACK'    => 'Success',
						'result' => $rule_values,
					)
				);
			} else {
				wp_send_json(
					array(
						'ACK' => 'Success',
						'msg' => _x( 'No commission plans found', 'commission plans not found message', 'affiliate-for-woocommerce' ),
					)
				);
			}
		}

		/**
		 * Get the commission plans order.
		 *
		 * @return array
		 */
		public function get_commission_plans_order() {

			$plan_order  = get_option( 'afwc_plan_order', array() );
			$_plan_order = $plan_order;

			// Set plan order if plan order is empty.
			if ( empty( $plan_order ) ) {
				$commission_plans = self::fetch_commission_plans();
				$plan_order       = ( is_array( $commission_plans ) && ! empty( $commission_plans ) ) ? array_filter(
					array_map(
						function( $x ) {
								return ! empty( $x['commissionId'] ) ? absint( $x['commissionId'] ) : 0;
						},
						$commission_plans
					)
				) : array();
			}

			$default_plan_id = afwc_get_default_commission_plan_id();
			if ( ! empty( $default_plan_id ) ) {
				$key = array_search( $default_plan_id, $plan_order, true );
				// Unset the default plan id if exists in $plan_order.
				if ( false !== $key ) {
					unset( $plan_order[ $key ] );
				}

				// Assign default plan at last position of the array.
				$plan_order[] = $default_plan_id;
			}

			$plan_order = array_values( $plan_order );

			if ( $_plan_order !== $plan_order ) {
				update_option( 'afwc_plan_order', $plan_order, 'no' );
			}

			/**
			 * Filter for commission plan order.
			 *
			 * @param array Ordered plan list.
			 * @param array
			 */
			return apply_filters( 'afwc_get_commission_plans_order', $plan_order, array( 'source' => $this ) );
		}

		/**
		 * Get commission plan statuses.
		 *
		 * @param string $status Plan Status.
		 *
		 * @return array|string Return the status title if the status is provided otherwise return array of all statuses.
		 */
		public static function get_statuses( $status = '' ) {
			$statuses = array(
				'Active' => _x( 'Active', 'active commission plan status', 'affiliate-for-woocommerce' ),
				'Draft'  => _x( 'Draft', 'draft commission plan status', 'affiliate-for-woocommerce' ),
			);

			return empty( $status ) ? $statuses : ( ! empty( $statuses[ $status ] ) ? $statuses[ $status ] : '' );
		}

	}

}

return AFWC_Commission_Dashboard::get_instance();
