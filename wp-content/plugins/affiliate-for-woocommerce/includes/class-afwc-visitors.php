<?php
/**
 * Main class for Visitors.
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       6.31.0
 * @version     1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Visitors' ) ) {

	/**
	 * Main class for Visitor reports.
	 */
	class AFWC_Visitors {

		/**
		 * Variable to hold affiliate ids.
		 *
		 * @var array $affiliate_ids
		 */
		public $affiliate_ids = array();

		/**
		 * Variable to hold the from date.
		 *
		 * @var string $from
		 */
		public $from = '';

		/**
		 * Variable to hold the to date.
		 *
		 * @var string $to
		 */
		public $to = '';

		/**
		 * Variable to hold batch limit per request.
		 *
		 * @var int $batch_limit
		 */
		public $batch_limit = 0;

		/**
		 * Variable to hold batch start per request.
		 *
		 * @var int $start_limit
		 */
		public $start_limit = 0;

		/**
		 * Variable to hold the DB data.
		 *
		 * @var array $data
		 */
		private $data = array();

		/**
		 * Constructor
		 *
		 * @param array $affiliate_ids Affiliates ids.
		 * @param array $args          The arguments.
		 */
		public function __construct( $affiliate_ids = array(), $args = array() ) {
			$this->affiliate_ids = ( ! is_array( $affiliate_ids ) ) ? array( $affiliate_ids ) : $affiliate_ids;
			$this->from          = ( ! empty( $args['from'] ) ) ? gmdate( 'Y-m-d H:i:s', strtotime( $args['from'] ) ) : '';
			$this->to            = ( ! empty( $args['to'] ) ) ? gmdate( 'Y-m-d H:i:s', strtotime( $args['to'] ) ) : '';
			$this->batch_limit   = ( ! empty( $args['limit'] ) ) ? ( intval( $args['limit'] ) ) : 1;
			$this->start_limit   = ( ! empty( $args['start'] ) ) ? intval( $args['start'] ) : 0;
		}

		/**
		 * Method to get the visitor raw data.
		 *
		 * @return array The visitor data.
		 */
		public function get_data() {

			if ( empty( $this->data ) ) {
				// Set the data if not set.
				$this->set_data_from_db();
			}

			return ! empty( $this->data ) && is_array( $this->data ) ? $this->data : array();
		}

		/**
		 * Method to get the well formatted sanitized visitor reports.
		 *
		 * @return array The report data.
		 */
		public function get_reports() {
			// Get the raw data from the MySQL database.
			$raw_data = ! empty( $this->data ) ? $this->data : $this->get_data();

			if ( empty( $raw_data ) || ! is_array( $raw_data ) ) {
				return array();
			}

			$reports = array();

			// Process the raw data and return the report data.
			foreach ( $raw_data as $row ) {
				$hit_id = ! empty( $row['id'] ) ? intval( $row['id'] ) : 0;

				if ( empty( $hit_id ) ) {
					continue;
				}

				// Get the user agent information for the current looped visitor.
				$user_agents = $this->get_user_agent( $hit_id );

				$reports[] = array(
					'id'            => $hit_id,
					'datetime'      => $this->get_date_time( $hit_id ),
					'referring_url' => $this->get_referring_url( $hit_id ),
					'medium'        => $this->get_medium( $hit_id ),
					'ip'            => $this->get_ip( $hit_id ),
					'is_converted'  => $this->is_converted( $hit_id ),
					'user_agent'    => $this->get_user_agent( $hit_id ),
				);
			}

			return $reports;
		}

		/**
		 * Method to get the date and time for the given hit ID.
		 *
		 * @param int $hit_id The hit ID.
		 *
		 * @return string The date and time.
		 */
		public function get_date_time( $hit_id = 0 ) {
			if ( empty( $hit_id ) ) {
				return '';
			}
			$data = ! empty( $this->data ) ? $this->data : $this->get_data();
			return ! empty( $data ) && ! empty( $data[ $hit_id ] ) && ! empty( $data[ $hit_id ]['site_datetime'] ) ? $data[ $hit_id ]['site_datetime'] : '';
		}

		/**
		 * Method to get the referring URL which used to hit the page.
		 *
		 * @param int $hit_id The hit ID.
		 *
		 * @return string The referring URL.
		 */
		public function get_referring_url( $hit_id = 0 ) {
			if ( empty( $hit_id ) ) {
				return '';
			}
			$data = ! empty( $this->data ) ? $this->data : $this->get_data();
			return ! empty( $data ) && ! empty( $data[ $hit_id ] ) && ! empty( $data[ $hit_id ]['url'] ) ? esc_url_raw( $data[ $hit_id ]['url'] ) : '';
		}

		/**
		 * Method to gets the visitor medium.
		 *
		 * @param int $hit_id The hit ID.
		 *
		 * @return string The visitor medium.
		 */
		public function get_medium( $hit_id = 0 ) {
			if ( empty( $hit_id ) ) {
				return '';
			}
			$data = ! empty( $this->data ) ? $this->data : $this->get_data();

			$medium = ! empty( $data ) && ! empty( $data[ $hit_id ] ) && ! empty( $data[ $hit_id ]['type'] ) ? sanitize_key( $data[ $hit_id ]['type'] ) : '';
			if ( empty( $medium ) ) {
				return '';
			}

			$referral_mediums = array(
				'link'   => _x( 'Link', 'Referral medium title for link', 'affiliate-for-woocommerce' ),
				'coupon' => _x( 'Coupon', 'Referral medium title for coupon', 'affiliate-for-woocommerce' ),
			);

			return ! empty( $referral_mediums[ $medium ] ) ? esc_html( $referral_mediums[ $medium ] ) : $medium;
		}

		/**
		 * Method to gets the visitor IP Address.
		 *
		 * @param int $hit_id The hit ID.
		 *
		 * @return string The IP address.
		 */
		public function get_ip( $hit_id = 0 ) {
			if ( empty( $hit_id ) ) {
				return '';
			}
			$data = ! empty( $this->data ) ? $this->data : $this->get_data();
			// Decode IPv4.
			return ! empty( $data ) && ! empty( $data[ $hit_id ] ) && ! empty( $data[ $hit_id ]['ip'] ) ? long2ip( $data[ $hit_id ]['ip'] ) : '';
		}

		/**
		 * Method to gets the user agent.
		 *
		 * @param int $hit_id The hit ID.
		 *
		 * @return string The user agent string.
		 */
		public function get_user_agent( $hit_id = 0 ) {
			if ( empty( $hit_id ) ) {
				return '';
			}
			$data = ! empty( $this->data ) ? $this->data : $this->get_data();
			return ! empty( $data ) && ! empty( $data[ $hit_id ] ) && ! empty( $data[ $hit_id ]['user_agent'] ) ? $data[ $hit_id ]['user_agent'] : '';
		}

		/**
		 * Method to check whether the hit is converted to referral or not.
		 *
		 * @param int $hit_id The hit ID.
		 *
		 * @return bool The Return true if converted otherwise false.
		 */
		public function is_converted( $hit_id = 0 ) {
			if ( empty( $hit_id ) ) {
				return false;
			}
			$data = ! empty( $this->data ) ? $this->data : $this->get_data();
			return ! empty( $data ) && ! empty( $data[ $hit_id ] ) && ! empty( $data[ $hit_id ]['is_converted'] ) && 'yes' === $data[ $hit_id ]['is_converted'];
		}

		/**
		 * Method to set raw data from DB using provided filters.
		 */
		private function set_data_from_db() {

			global $wpdb;

			if ( ! empty( $this->affiliate_ids ) ) {
				if ( count( $this->affiliate_ids ) === 1 ) {
					$affiliate_id = current( $this->affiliate_ids );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
							$wpdb->prepare(
								"SELECT h.*,
                                DATE_FORMAT(CONVERT_TZ(h.datetime, '+00:00', %s), %s) AS site_datetime,
                                IF(r.hit_id IS NOT NULL, 'yes', 'no') AS is_converted
                            FROM {$wpdb->prefix}afwc_hits AS h
                            LEFT JOIN {$wpdb->prefix}afwc_referrals AS r ON (h.id = r.hit_id AND h.affiliate_id = r.affiliate_id AND h.type = r.type)
                            WHERE h.affiliate_id = %d
                            AND h.datetime BETWEEN %s AND %s
                            ORDER BY h.id DESC
                            LIMIT %d, %d",
								AFWC_TIMEZONE_STR,
								'%d-%b-%Y %H:%i:%s',
								$affiliate_id,
								$this->from,
								$this->to,
								intval( $this->start_limit ),
								intval( $this->batch_limit )
							),
							'ARRAY_A'
						);
					} else {
						$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
							$wpdb->prepare(
								"SELECT h.*,
                                DATE_FORMAT(CONVERT_TZ(h.datetime, '+00:00', %s), %s) AS site_datetime,
                                IF(r.hit_id IS NOT NULL, 'yes', 'no') AS is_converted
                            FROM {$wpdb->prefix}afwc_hits AS h
                            LEFT JOIN {$wpdb->prefix}afwc_referrals AS r ON (h.id = r.hit_id AND h.affiliate_id = r.affiliate_id AND h.type = r.type)
                            WHERE h.affiliate_id = %d
                            ORDER BY h.id DESC
                            LIMIT %d, %d",
								AFWC_TIMEZONE_STR,
								'%d-%b-%Y %H:%i:%s',
								$affiliate_id,
								intval( $this->start_limit ),
								intval( $this->batch_limit )
							),
							'ARRAY_A'
						);
					}
				} else {
					$option_nm = 'afwc_visitor_details_affiliate_ids_' . uniqid();
					update_option( $option_nm, implode( ',', $this->affiliate_ids ), 'no' );

					if ( ! empty( $this->from ) && ! empty( $this->to ) ) {
						$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
							$wpdb->prepare(
								"SELECT h.*,
                                DATE_FORMAT(CONVERT_TZ(h.datetime, '+00:00', %s), %s) AS site_datetime,
                                IF(r.hit_id IS NOT NULL, 'yes', 'no') AS is_converted
                            FROM {$wpdb->prefix}afwc_hits AS h
                            LEFT JOIN {$wpdb->prefix}afwc_referrals AS r ON (h.id = r.hit_id AND h.affiliate_id = r.affiliate_id AND h.type = r.type)
                            WHERE FIND_IN_SET ( h.affiliate_id, ( SELECT option_value
								FROM {$wpdb->prefix}options
								WHERE option_name = %s ) )
                            AND h.datetime BETWEEN %s AND %s
                            ORDER BY h.id DESC
                            LIMIT %d, %d",
								AFWC_TIMEZONE_STR,
								'%d-%b-%Y %H:%i:%s',
								$option_nm,
								$this->from,
								$this->to,
								intval( $this->start_limit ),
								intval( $this->batch_limit )
							),
							'ARRAY_A'
						);
					} else {
						$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
							$wpdb->prepare(
								"SELECT h.*,
                                DATE_FORMAT(CONVERT_TZ(h.datetime, '+00:00', %s), %s) AS site_datetime,
                                IF(r.hit_id IS NOT NULL, 'yes', 'no') AS is_converted
                            FROM {$wpdb->prefix}afwc_hits AS h
                            LEFT JOIN {$wpdb->prefix}afwc_referrals AS r ON (h.id = r.hit_id AND h.affiliate_id = r.affiliate_id AND h.type = r.type)
                            WHERE FIND_IN_SET ( h.affiliate_id, ( SELECT option_value
								FROM {$wpdb->prefix}options
								WHERE option_name = %s ) )
                            ORDER BY h.id DESC
                            LIMIT %d, %d",
								AFWC_TIMEZONE_STR,
								$option_nm,
								'%d-%b-%Y %H:%i:%s',
								intval( $this->start_limit ),
								intval( $this->batch_limit )
							),
							'ARRAY_A'
						);
					}
				}
			} elseif ( ! empty( $this->from ) && ! empty( $this->to ) ) {
				$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->prepare(
						"SELECT h.*,
                        DATE_FORMAT(CONVERT_TZ(h.datetime, '+00:00', %s), %s) AS site_datetime,
                        IF(r.hit_id IS NOT NULL, 'yes', 'no') AS is_converted
                    FROM {$wpdb->prefix}afwc_hits AS h
                    LEFT JOIN {$wpdb->prefix}afwc_referrals AS r ON (h.id = r.hit_id AND h.affiliate_id = r.affiliate_id AND h.type = r.type)
                    WHERE h.affiliate_id != %d
                    AND h.datetime BETWEEN %s AND %s
                    ORDER BY h.id DESC
                    LIMIT %d, %d",
						AFWC_TIMEZONE_STR,
						'%d-%b-%Y %H:%i:%s',
						0,
						$this->from,
						$this->to,
						intval( $this->start_limit ),
						intval( $this->batch_limit )
					),
					'ARRAY_A'
				);
			} else {
				$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->prepare(
						"SELECT h.*,
                        DATE_FORMAT(CONVERT_TZ(h.datetime, '+00:00', %s), %s) AS site_datetime,
                        IF(r.hit_id IS NOT NULL, 'yes', 'no') AS is_converted
                    FROM {$wpdb->prefix}afwc_hits AS h
                    LEFT JOIN {$wpdb->prefix}afwc_referrals AS r ON (h.id = r.hit_id AND h.affiliate_id = r.affiliate_id AND h.type = r.type)
                    WHERE h.affiliate_id != %d
                    ORDER BY h.id DESC
                    LIMIT %d, %d",
						AFWC_TIMEZONE_STR,
						'%d-%b-%Y %H:%i:%s',
						0,
						intval( $this->start_limit ),
						intval( $this->batch_limit )
					),
					'ARRAY_A'
				);
			}

			if ( empty( $results ) || ! is_array( $results ) ) {
				return;
			}

			foreach ( $results as $row ) {
				if ( empty( $row['id'] ) ) {
					continue;
				}
				$this->data[ $row['id'] ] = $row;
			}
		}
	}
}
