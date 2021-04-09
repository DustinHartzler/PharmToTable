<?php
/**
 * Main class for Affiliate For WooCommerce Migration
 *
 * @since       1.0.0
 * @version     1.2.1
 *
 * @package     affiliate-for-woocommerce/includes/migration/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Migrate_Affiliates' ) ) {

	/**
	 * Affiliate For WooCommerce Migration
	 */
	class AFWC_Migrate_Affiliates {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( is_admin() ) {
				add_action( 'admin_init', array( $this, 'track_affiliates_migration' ) );
			}
		}

		/**
		 * Track affiliate migration
		 */
		public function track_affiliates_migration() {
			global $wpdb;

			$request_page         = ( isset( $_REQUEST['page'] ) ) ? wc_clean( wp_unslash( $_REQUEST['page'] ) ) : ''; // phpcs:ignore
			$request_migrate      = ( isset( $_REQUEST['migrate'] ) ) ? wc_clean( wp_unslash( $_REQUEST['migrate'] ) ) : ''; // phpcs:ignore
			$request_is_from_docs = ( isset( $_REQUEST['is_from_docs'] ) ) ? absint( $_REQUEST['is_from_docs'] ) : 0; // phpcs:ignore

			if ( 'affiliate-for-woocommerce-settings' === $request_page ) {

				if ( ! empty( $request_migrate ) ) {

					if ( 'affiliates' === $request_migrate ) {
						if ( ! function_exists( '_affiliates_get_tablename' ) ) {
							if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
								/* translators: Link to back */
								wp_die( sprintf( esc_html__( 'Could not locate Affiliates plugin. Make sure that it is installed & activated. %s', 'affiliate-for-woocommerce' ), '<a href="' . esc_url( wc_clean( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) ) . '">' . esc_html__( 'Back', 'affiliate-for-woocommerce' ) . '</a>' ) ); // phpcs:ignore
							}
						}

						$this->map_missing_affiliates();
						$this->migrate_hits();
						$this->migrate_referrals();
						$this->update_commission_status();
						$this->migrate_affiliates_users();

						// Get pname from Affiliates and set pnmae for afwc.
						$affiliates_pname = ( defined( 'AFFILIATES_PNAME' ) ) ? AFFILIATES_PNAME : 'affiliates';
						$pname            = get_option( 'aff_pname', $affiliates_pname );
						update_option( 'afwc_migrated_pname', $pname, 'no' );

						update_option( 'show_migrate_affiliates_notification', 'no' );

					}

					if ( 'ignore_affiliates' === $request_migrate ) {
						update_option( 'show_migrate_affiliates_notification', 'no' );
					}

					if ( 1 === $request_is_from_docs ) {
						$docs_page = add_query_arg( array( 'page' => 'affiliate-for-woocommerce-documentation' ), admin_url( 'admin.php' ) );
						wp_safe_redirect( $docs_page );
					}
				}

				if ( ( afwc_is_plugin_active( 'affiliates/affiliates.php' ) || afwc_is_plugin_active( 'affiliates-pro/affiliates-pro.php' ) ) && defined( 'AFFILIATES_TP' ) ) {
					$tables            = $wpdb->get_results( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . AFFILIATES_TP ) . '%' ), ARRAY_A ); // phpcs:ignore
					$show_notification = get_option( 'show_migrate_affiliates_notification', 'yes' );
					// Note: To test migration uncomment following code.
					if ( ! empty( $tables ) && 'no' !== $show_notification ) {
						?>
						<div class="description updated">
							<p>
						<?php echo esc_html__( 'We found data from the "Affiliates". Do you want to migrate it?', 'affiliate-for-woocommerce' ); ?>
								<span class="migrate_affiliates_actions">
									<a href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page'    => 'affiliate-for-woocommerce-settings',
												'migrate' => 'affiliates',
											),
											admin_url( 'admin.php' )
										)
									);
									?>
												" class="button-primary" id="migrate_yes" ><?php echo esc_html__( 'Migrate Now', 'affiliate-for-woocommerce' ); ?></a>
									<a href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page'    => 'affiliate-for-woocommerce-settings',
												'migrate' => 'ignore_affiliates',
											),
											admin_url( 'admin.php' )
										)
									);
									?>
												" class="button" id="migrate_no" ><?php echo esc_html__( 'Dismiss', 'affiliate-for-woocommerce' ); ?></a>
								</span>
							</p>
						</div>
						<?php
					}
				}
			}
		}

		/**
		 * Migrate Affiliates users
		 */
		public function migrate_affiliates_users() {
			global $wpdb;

			$afwc_affiliates_users = get_afwc_tablename( 'affiliates_users' );
			$affiliates_users      = _affiliates_get_tablename( 'affiliates_users' );
			$create_table          = $wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}afwc_affiliates_users LIKE {$wpdb->prefix}aff_affiliates_users" ); // phpcs:ignore
			$result                = $wpdb->query( "INSERT {$wpdb->prefix}afwc_affiliates_users SELECT * FROM {$wpdb->prefix}aff_affiliates_users" ); // phpcs:ignore
		}

		/**
		 * Map missing affiliates
		 *
		 * @return array $affiliate_ids_to_user_ids
		 */
		public function map_missing_affiliates() {
			global $wpdb;

			$affiliates_to_users_results = $wpdb->get_results(  // phpcs:ignore
				"SELECT affiliates.affiliate_id, affiliates.name, affiliates.email, affiliates_users.user_id FROM {$wpdb->prefix}aff_affiliates AS affiliates
				LEFT JOIN {$wpdb->prefix}aff_affiliates_users AS affiliates_users
				ON ( affiliates.affiliate_id = affiliates_users.affiliate_id )",
				ARRAY_A
			); // phpcs:ignore

			$affiliate_ids_to_user_ids = array();

			if ( ! empty( $affiliates_to_users_results ) ) {

				foreach ( $affiliates_to_users_results as $affiliate ) {

					if ( empty( $affiliate['user_id'] ) ) {

						$user_data = array(
							'user_login'   => sanitize_user( $affiliate['name'] ),
							'user_email'   => $affiliate['email'],
							'display_name' => $affiliate['name'],
							'user_pass'    => sanitize_user( $affiliate['name'] ),
						);

						$user_id = wp_insert_user( $user_data );

						if ( ! is_wp_error( $user_id ) ) {
							update_user_meta( $user_id, 'afwc_paypal_email', $affiliate['email'] );
							$affiliate_ids_to_user_ids[ $affiliate['affiliate_id'] ] = $user_id;
							$wpdb->insert( // phpcs:ignore
								_affiliates_get_tablename( 'affiliates_users' ),
								array(
									'user_id'      => $user_id,
									'affiliate_id' => $affiliate['affiliate_id'],
								)
							);
						}
					} else {
						update_user_meta( $affiliate['user_id'], 'afwc_paypal_email', $affiliate['email'] );
					}
				}
			}

			return $affiliate_ids_to_user_ids;
		}

		/**
		 * Migrate hits
		 */
		public function migrate_hits() {
			global $wpdb;

			if ( ! function_exists( '_affiliates_get_tablename' ) ) {
				if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
					/* translators: Link to back */
					wp_die( sprintf( esc_html__( 'Could not locate Affiliates plugin. Make sure that it is installed & activated. %s', 'affiliate-for-woocommerce' ), '<a href="' . esc_url( wc_clean( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) ) . '">' . esc_html__( 'Back', 'affiliate-for-woocommerce' ) . '</a>' ) ); // phpcs:ignore
				}
			}

			$wpdb->query(  // phpcs:ignore
				"INSERT INTO {$wpdb->prefix}afwc_hits ( affiliate_id, datetime, ip, user_id, count, type )
				SELECT affiliates_users.user_id, hits.datetime, hits.ip, hits.user_id, hits.count, hits.type FROM {$wpdb->prefix}aff_hits AS hits
				INNER JOIN {$wpdb->prefix}aff_affiliates_users AS affiliates_users ON ( hits.affiliate_id = affiliates_users.affiliate_id )
				"
			); // phpcs:ignore
		}

		/**
		 * Migrate referrals
		 */
		public function migrate_referrals() {
			global $wpdb;

			if ( ! function_exists( '_affiliates_get_tablename' ) ) {
				if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
					/* translators: Link to go back */
					wp_die( sprintf( esc_html__( 'Could not locate Affiliates plugin. Make sure that it is installed & activated. %s', 'affiliate-for-woocommerce' ), '<a href="' . esc_url( sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) ) . '">' . esc_html__( 'Back', 'affiliate-for-woocommerce' ) . '</a>' ) ); // phpcs:ignore
				}
			}

			$affiliates_accepted_status = ( defined( 'AFFILIATES_REFERRAL_STATUS_ACCEPTED' ) ) ? AFFILIATES_REFERRAL_STATUS_ACCEPTED : 'accepted';
			$afwc_unpaid_status         = defined( 'AFWC_REFERRAL_STATUS_UNPAID' ) ? AFWC_REFERRAL_STATUS_UNPAID : 'unpaid';
			$affiliates_closed_status   = defined( 'AFFILIATES_REFERRAL_STATUS_CLOSED' ) ? AFFILIATES_REFERRAL_STATUS_CLOSED : 'closed';
			$afwc_paid_status           = defined( 'AFWC_REFERRAL_STATUS_PAID' ) ? AFWC_REFERRAL_STATUS_PAID : 'paid';
			$affiliates_pending_status  = defined( 'AFFILIATES_REFERRAL_STATUS_PENDING' ) ? AFFILIATES_REFERRAL_STATUS_PENDING : 'pending';
			$affiliates_rejected_status = defined( 'AFFILIATES_REFERRAL_STATUS_REJECTED' ) ? AFFILIATES_REFERRAL_STATUS_REJECTED : 'rejected';
			$afwc_rejected_status       = defined( 'AFWC_REFERRAL_STATUS_REJECTED' ) ? AFWC_REFERRAL_STATUS_REJECTED : 'rejected';

			$wpdb->query(  // phpcs:ignore
				$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}afwc_referrals ( affiliate_id, post_id, datetime, description, ip, user_id, amount, currency_id, data, status, type, reference )
									SELECT affiliates_users.user_id, referrals.post_id, referrals.datetime, referrals.description, referrals.ip, referrals.user_id, referrals.amount, referrals.currency_id, referrals.data, 
										CASE referrals.status
											WHEN %s THEN %s
											WHEN %s THEN %s
											WHEN %s THEN %s
											WHEN %s THEN %s
										END,
										referrals.type, referrals.reference FROM {$wpdb->prefix}aff_referrals AS referrals
											LEFT JOIN {$wpdb->prefix}aff_affiliates_users AS affiliates_users ON ( affiliates_users.affiliate_id = referrals.affiliate_id )
											",
					$affiliates_accepted_status,
					$afwc_unpaid_status,
					$affiliates_closed_status,
					$afwc_paid_status,
					$affiliates_pending_status,
					$afwc_unpaid_status,
					$affiliates_rejected_status,
					$afwc_rejected_status
				)
			);
		}

		/**
		 * Update commission status
		 */
		public function update_commission_status() {
			global $wpdb;

			$referrals_table = get_afwc_tablename( 'referrals' );

			$order_ids = $wpdb->get_col( // phpcs:ignore
				$wpdb->prepare(
					'SELECT DISTINCT post_id FROM {$wpdb->prefix}afwc_referrals WHERE status = %s',
					AFWC_REFERRAL_STATUS_PENDING
				)
			); // phpcs:ignore

			if ( count( $order_ids ) > 0 ) {
				$args                 = array( 'fields' => 'all_with_object_id' );
				$order_status_details = wp_get_object_terms( $order_ids, 'shop_order_status', $args );
				if ( count( $order_status_details ) > 0 ) {
					$statuses = array();
					foreach ( $order_status_details as $detail ) {
						$statuses[ $detail->slug ][] = $detail->object_id;
					}

					foreach ( $statuses as $order_status => $order_ids ) {

						if ( SA_WC_Compatibility_3_9::is_wc_gte_25() && strpos( $order_status, 'wc-' ) === 0 ) {
							$order_status = substr( $order_status, 3 );
						}

						switch ( $order_status ) {
							case 'refunded':
							case 'cancelled':
							case 'failed':
								$commission_status = AFWC_REFERRAL_STATUS_REJECTED;
								break;

							case 'completed':
							case 'pending':
							case 'on-hold':
							case 'processing':
								$commission_status = AFWC_REFERRAL_STATUS_UNPAID;
								break;
						}

						$current_user_id = get_current_user_id();
						if ( 0 !== $current_user_id ) {
							$temp_db_key = 'afwc_commission_order_ids_' . $current_user_id;

							// Store order ids temporarily in table.
							update_option( $temp_db_key, implode( ',', $order_ids ), 'no' );

							$wpdb->query( // phpcs:ignore
								$wpdb->prepare(
									"UPDATE {$wpdb->prefix}afwc_referrals SET status = %s WHERE post_id IN ( SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s )",
									$commission_status,
									$temp_db_key
								)
							); // phpcs:ignore

							delete_option( $temp_db_key );
						}
					}
				}
			}
		}

	}

	return new AFWC_Migrate_Affiliates();
}
