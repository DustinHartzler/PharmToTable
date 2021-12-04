<?php
/**
 * Main class for Commission Dashboard
 *
 * @package     affiliate-for-woocommerce/includes/admin/
 * @since       2.5.0
 * @version     1.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AFWC_Commission_Dashboard' ) ) {

	/**
	 * Main class for Commission Dashboard
	 */
	class AFWC_Commission_Dashboard {

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

			if ( empty( $_REQUEST ) || empty( $_REQUEST['cmd'] ) ) {
				return;
			}

			check_ajax_referer( AFWC_AJAX_SECURITY, 'security' );
			foreach ( $_REQUEST as $key => $value ) {
				if ( 'commission' === $key ) {
					$params[ $key ] = wp_unslash( $value );
				} else {
					$params[ $key ] = trim( wc_clean( wp_unslash( $value ) ) );
				}
			}
			$func_nm = $params['cmd'];

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
		public function save_commission( $params ) {
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
					// add id in plan order.
					$plan_order = get_option( 'afwc_plan_order', array() );
					$len        = count( $plan_order );
					array_splice( $plan_order, $len, 0, $lastid );
					update_option( 'afwc_plan_order', $plan_order, 'no' );
				}

				if ( false === $result ) {
					throw new RuntimeException( __( 'Unable to save commission plan. Database error.', 'funnelwise' ) );
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
		public function delete_commission( $params ) {
			global $wpdb;

			$response = array( 'ACK' => 'Failed' );
			if ( ! empty( $params['commission_id'] ) ) {
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
							'msg' => __( 'Failed to delete commission plan', 'affiliate-for-woocommerce' ),
						)
					);
				} else {
					// delete from plan order.
					$plan_order = get_option( 'afwc_plan_order', array() );
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
							'msg' => __( 'Commission plan deleted successfully', 'affiliate-for-woocommerce' ),
						)
					);
				}
			}
		}

		/**
		 * Function to handle fetch data
		 *
		 *  @param array $params fetch commission dashboard data params.
		 */
		public function fetch_dashboard_data( $params ) {

			$result['commissions'] = $this->fetch_commission_plans( $params );
			$plan_order            = get_option( 'afwc_plan_order', array() );
			$default_plan_id       = (int) get_option( 'afwc_default_commission_plan_id', false );
			if ( empty( $plan_order ) ) {
				$plan_order = array_map(
					function( $x ) {
							return absint( $x['commissionId'] );
					},
					$result['commissions']
				);
				$key        = array_search( $default_plan_id, $plan_order, true );
				unset( $plan_order[ $key ] );
				$plan_order[] = $default_plan_id;
				update_option( 'afwc_plan_order', $plan_order, 'no' );
			}
			$result['plan_order'] = $plan_order;

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
						'msg' => __( 'No commission plans found', 'affiliate-for-woocommerce' ),
					)
				);
			}

		}

		/**
		 * Function to handle fetch commissions
		 *
		 *  @param array $params fetch commission params.
		 */
		public static function fetch_commission_plans( $params ) {
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
		public static function save_plan_order( $params ) {
			$default_plan_id = (int) get_option( 'afwc_default_commission_plan_id', false );
			if ( ! empty( $params['plan_order'] ) ) {
				$plan_order = (array) json_decode( $params['plan_order'], true );
				$key        = array_search( $default_plan_id, $plan_order, true );
				unset( $plan_order[ $key ] );
				$plan_order[] = $default_plan_id;
				update_option( 'afwc_plan_order', $plan_order, 'no' );
				wp_send_json(
					array(
						'ACK'    => 'Success',
						'result' => true,
					)
				);
			} else {
				wp_send_json(
					array(
						'msg' => __( 'No plan order to save', 'affiliate-for-woocomerce' ),
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

			check_ajax_referer( AFWC_AJAX_SECURITY, 'security' );
			$term = ( ! empty( $_GET['term'] ) ) ? (string) urldecode( stripslashes( wp_strip_all_tags( $_GET ['term'] ) ) ) : ''; // phpcs:ignore
			$type = ( ! empty( $_GET['type'] ) ) ? (string) urldecode( stripslashes( wp_strip_all_tags( $_GET ['type'] ) ) ) : ''; // phpcs:ignore
			$type = ! empty( $type ) ? $type : 'affiliate';
			if ( empty( $term ) ) {
				die();
			}

			$rule_values = array();

			if ( ! empty( $type ) ) {
				$function    = 'get_' . $type . '_id_name_map';
				$rule_values = self::$function( $term );
			}

			echo wp_json_encode( $rule_values );
			die();

		}

		/**
		 * Get user id name map
		 *
		 * @param string $term string.
		 * @param string $for_ajax string.
		 * @return $rule_values array
		 */
		public static function get_affiliate_id_name_map( $term, $for_ajax = true ) {

			global $wpdb;

			$rule_values = array();

			$afwc_is_affiliate = 'yes';

			if ( $for_ajax ) {
				if ( is_integer( $term ) ) {
					$res = $wpdb->get_results( // phpcs:ignore
						$wpdb->prepare( // phpcs:ignore
							"SELECT id, display_name FROM {$wpdb->prefix}users as u JOIN {$wpdb->usermeta} as um
																ON(um.user_id = u.ID 
																AND um.meta_key = 'afwc_is_affiliate' )
														WHERE um.meta_value = %s  AND id = %d",
							$afwc_is_affiliate,
							$term
						),
						'ARRAY_A'
					);
				} else {
					$res = $wpdb->get_results( // phpcs:ignore
						$wpdb->prepare( // phpcs:ignore
							"SELECT id, display_name FROM {$wpdb->prefix}users as u JOIN {$wpdb->usermeta} as um
																ON(um.user_id = u.ID 
																AND um.meta_key = 'afwc_is_affiliate' )
														WHERE um.meta_value = %s AND (user_email LIKE %s OR display_name LIKE %s)",
							$afwc_is_affiliate,
							'%' . $term . '%',
							'%' . $term . '%'
						),
						'ARRAY_A'
					);
				}
			} else {
				$res = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"SELECT id, display_name FROM {$wpdb->prefix}users u JOIN {$wpdb->usermeta} as um
																ON(um.user_id = u.ID 
																AND um.meta_key = 'afwc_is_affiliate' AND um.meta_value = 'yes' ) WHERE id IN (" . implode( ',', array_fill( 0, count( $term ), '%d' ) ) . ')',
						$term
					),
					'ARRAY_A'
				);

			}
			if ( ! empty( $res ) ) {
				foreach ( $res as $value ) {
					$rule_values[ $value['id'] ] = $value['display_name'];
				}
			}

			return $rule_values;

		}

		/**
		 * Get product id name map
		 *
		 * @param string $term string.
		 * @param string $for_ajax string.
		 * @return $rule_values array
		 */
		public static function get_product_id_name_map( $term, $for_ajax = true ) {

			global $wpdb;

			$rule_values = array();
			if ( $for_ajax ) {
				$args = array(
					'post_type'   => array( 'product', 'product_variation' ),
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'      => 'ids',
					's'           => $term,
				);
			} else {
				$args = array(
					'post_type'   => array( 'product', 'product_variation' ),
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'      => 'ids',
					'post__in'    => $term,
				);
			}

			$res = get_posts( $args );

			if ( ! empty( $res ) ) {
				foreach ( $res as $id ) {
					$rule_values[ $id ] = get_the_title( $id );
				}
			}

			return $rule_values;

		}

		/**
		 * Get affiliate tag id name map
		 *
		 * @param string $term string.
		 * @param string $for_ajax string.
		 * @return $rule_values array
		 */
		public static function get_affiliate_tag_id_name_map( $term, $for_ajax = true ) {
			global $wpdb;

			$rule_values = array();

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
		 * @param string $term string.
		 * @param string $for_ajax string.
		 * @return $rule_values array
		 */
		public static function get_product_category_id_name_map( $term, $for_ajax = true ) {
			global $wpdb;

			$rule_values = array();

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
			$data = json_decode( $params['data'], true );
			foreach ( $data as $type => $ids ) {
				$function             = 'get_' . $type . '_id_name_map';
				$rule_values[ $type ] = self::$function( $ids, false );
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
						'msg' => __( 'No commissions found', 'affiliate-for-woocommerce' ),
					)
				);
			}
		}

		/**
		 * Fetch data call
		 *
		 * @param string $params mixed.
		 */
		public function update_feedback( $params ) {
			$update_action = ! empty( $params['update_action'] ) ? $params['update_action'] : '';
			if ( ! empty( $update_action ) ) {
				update_option( 'afwc_feedback_option_' . $update_action, true, 'no' );
				wp_send_json(
					array(
						'ACK' => 'Success',
						'msg' => __( 'Feedback option updated', 'affiliate-for-woocommerce' ),
					)
				);
			}
		}

	}

}

return new AFWC_Commission_Dashboard();
