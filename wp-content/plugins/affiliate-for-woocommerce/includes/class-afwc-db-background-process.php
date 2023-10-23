<?php
/**
 * Class to handle db update background process
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       3.0.0
 * @version     1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AFWC_DB_Background_Process' ) ) {

	/**
	 * AFWC_DB_Background_Process Class.
	 */
	class AFWC_DB_Background_Process {

		/**
		 * Variable to hold instance of this class
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_DB_Background_Process Singleton object of this class
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
		private function __construct() {

			add_action( 'wp_ajax_afwc_run_migration', array( $this, 'run_migration' ) );

			add_filter( 'wp_ajax_afwc_check_migration_process', array( $this, 'check_migration_status' ), 10, 2 );

			add_action( 'afwc_run_migrate_order_status_action', array( $this, 'run_migrate_order_status' ) );
			add_action( 'afwc_schedule_action_for_date_migration', array( $this, 'afwc_schedule_action_for_date_migration' ) );
		}

		/**
		 * Send migration process response
		 */
		public function check_migration_status() {

			$response                        = array();
			$migration_of_order_status_done  = get_option( 'afwc_migration_for_order_status_done', false );
			$afwc_dates_migration_done       = get_option( 'afwc_dates_migration_done', 'no' );
			$hits_table_migrations_done      = get_option( 'afwc_hits_migration_done', 'no' );
			$referrals_table_migrations_done = get_option( 'afwc_referrals_migration_done', 'no' );
			$payouts_table_migrations_done   = get_option( 'afwc_payouts_migration_done', 'no' );
			$dates_migrations_done_now       = get_option( 'afwc_order_status_migration_done_now', 'no' );

			if ( '1' === $migration_of_order_status_done && 'yes' === $afwc_dates_migration_done ) {
				$response['afwc_order_migration_status'] = 'All';
				wp_send_json( $response );
			}

			if ( '1' === $migration_of_order_status_done && 'yes' !== $afwc_dates_migration_done ) {
				$response['afwc_order_migration_status'] = '1/4';
				update_option( 'afwc_order_status_migration_done_now', 'yes', 'no' );
			}

			$dates_migrations_done_now = get_option( 'afwc_order_status_migration_done_now', 'no' );

			if ( 'yes' === $hits_table_migrations_done ) {
				$response['afwc_order_migration_status'] = ( 'yes' === $dates_migrations_done_now ) ? '2/4' : '1/3';
			} elseif ( 'yes' === $referrals_table_migrations_done ) {
				$response['afwc_order_migration_status'] = ( 'yes' === $dates_migrations_done_now ) ? '3/4' : '2/3';
			} elseif ( 'yes' === $payouts_table_migrations_done ) {
				$response['afwc_order_migration_status'] = 'All';
			}
			delete_option( 'afwc_order_status_migration_done_now' );

			wp_send_json( $response );
		}

		/**
		 * Function to start migration
		 */
		public function run_migration() {
			$migration_of_order_status_done = get_option( 'afwc_migration_for_order_status_done', false );
			if ( false === $migration_of_order_status_done ) {
				$this->run_migrate_order_status();
			}
			$this->run_migrate_dates();
		}

		/**
		 * Function to migrate order status in batch
		 */
		public function run_migrate_order_status() {
			global $wpdb;
			$batch_size = 50;

			if ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
				$result = $wpdb->query( // phpcs:ignore
							$wpdb->prepare( // phpcs:ignore
								"UPDATE {$wpdb->prefix}afwc_referrals as ar 
									JOIN (
										SELECT afwcr.post_id AS post_id,
												wco.status AS status
									 		FROM {$wpdb->prefix}afwc_referrals AS afwcr
									 			LEFT JOIN {$wpdb->prefix}wc_orders AS wco
									 				ON ( afwcr.post_id = wco.id
									 					AND wco.type = 'shop_order' )
											WHERE afwcr.order_status IS NULL LIMIT %d 
									) t
									ON ar.post_id = t.post_id
									SET order_status = IFNULL( t.status, 'deleted' )",
								$batch_size
							)
				);
			} else {
				$result = $wpdb->query( // phpcs:ignore
					$wpdb->prepare( // phpcs:ignore
						"UPDATE {$wpdb->prefix}afwc_referrals as ar 
						JOIN (
						 SELECT {$wpdb->prefix}afwc_referrals.post_id, {$wpdb->prefix}posts.post_status FROM {$wpdb->prefix}afwc_referrals LEFT JOIN
							{$wpdb->prefix}posts ON {$wpdb->prefix}afwc_referrals.post_id = {$wpdb->prefix}posts.ID 
							WHERE order_status IS NULL LIMIT %d 
						) t
						 ON ar.post_id = t.post_id
						SET order_status = IFNULL( t.post_status, 'deleted' ) 
								",
						$batch_size
					)
				);
			}

			$total_order_count    = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_referrals WHERE order_status IS NULL" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$total_order_option   = get_option( 'afwc_total_order_to_migrate', false );
			$total_order_migrated = get_option( 'afwc_total_order_migrated', false );

			if ( ! $total_order_option ) {
				update_option( 'afwc_total_order_to_migrate', $total_order_count, 'no' );
			}
			if ( ! $total_order_migrated ) {
				$t = ( $total_order_count < $batch_size ) ? $total_order_count : $batch_size;
				update_option( 'afwc_total_order_migrated', $t, 'no' );
			} else {
				$total_order_migrated = $total_order_migrated + $batch_size;
				update_option( 'afwc_total_order_migrated', $total_order_migrated, 'no' );
			}

			if ( $total_order_count > 0 ) {
				if ( function_exists( 'as_schedule_single_action' ) ) {
					update_option( 'afwc_is_migration_process_running', true, 'no' );
					$int = as_schedule_single_action( time(), 'afwc_run_migrate_order_status_action' );
				}
			} elseif ( 0 === absint( $total_order_count ) ) {
				update_option( 'afwc_migration_for_order_status_done', true, 'no' );
				delete_option( 'afwc_is_migration_process_running' );
				delete_option( 'afwc_total_order_to_migrate' );
				delete_option( 'afwc_total_order_migrated' );
			}
		}

		/**
		 * Function to migrate dates to GMT in hits, referrals and payouts table
		 */
		public function run_migrate_dates() {

			global $wpdb;
			$afwc_dates_migration_done = get_option( 'afwc_dates_migration_done', 'no' );
			if ( 'no' === $afwc_dates_migration_done ) {
				update_option( 'afwc_dates_migration_done', 'no', 'no' );
			}
			// count all the table records save if option not exits.
			$total_hits_record      = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_hits" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$total_referrals_record = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_referrals" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$total_payouts_record   = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_payouts" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

			// check if there is any record to update, if all tables empty then return.
			if ( empty( $total_hits_record ) && empty( $total_referrals_record ) && empty( $total_payouts_record ) ) {
				update_option( 'afwc_dates_migration_done', 'yes', 'no' );
				return;
			}

			// check for the afwc_dates_migration_done flag and run if not true.
			if ( 'no' === $afwc_dates_migration_done ) {
				$this->afwc_schedule_action_for_date_migration();
			}
		}

		/**
		 * Function to schedule action
		 */
		public function afwc_schedule_action_for_date_migration() {

			global $wpdb;
			$batch_size = 250;

			$offset       = get_option( 'gmt_offset' );
			$timezone_str = sprintf( '%+02d:%02d', (int) $offset, ( $offset - floor( $offset ) ) * 60 );

			// set flag for each table migration.
			// afwc_hits_migration_done.
			$afwc_hits_migration_done      = get_option( 'afwc_hits_migration_done', 'no' );
			$afwc_referrals_migration_done = get_option( 'afwc_referrals_migration_done', 'no' );
			$afwc_payouts_migration_done   = get_option( 'afwc_payouts_migration_done', 'no' );

			if ( 'no' === $afwc_hits_migration_done ) {
				$result = $wpdb->query( // phpcs:ignore
							$wpdb->prepare(// phpcs:ignore
								"UPDATE {$wpdb->prefix}afwc_hits set datetime = CONVERT_TZ( datetime, %s , '+00:00' ), migrate_date = TRUE WHERE migrate_date IS NULL LIMIT %d",
								$timezone_str,
								$batch_size
							)
				);

				$total_hits_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_hits WHERE migrate_date IS NULL" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

				if ( 0 === absint( $total_hits_count ) ) {
					update_option( 'afwc_hits_migration_done', 'yes' );
					// drop column from hits table.
					$res = $wpdb->query( // phpcs:ignore
							"ALTER table {$wpdb->prefix}afwc_hits DROP COLUMN migrate_date" // phpcs:ignore
					);
				}
			} elseif ( 'no' === $afwc_referrals_migration_done ) {
				$result = $wpdb->query( // phpcs:ignore
					$wpdb->prepare(// phpcs:ignore
						"UPDATE {$wpdb->prefix}afwc_referrals set datetime = CONVERT_TZ( datetime, %s , '+00:00' ), migrate_date = TRUE WHERE migrate_date IS NULL LIMIT %d",
						$timezone_str,
						$batch_size
					)
				);

				$total_referrals_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_referrals WHERE migrate_date IS NULL" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

				if ( 0 === absint( $total_referrals_count ) ) {
					update_option( 'afwc_referrals_migration_done', 'yes' );
					$res = $wpdb->query( // phpcs:ignore
						"ALTER table {$wpdb->prefix}afwc_referrals DROP COLUMN migrate_date" // phpcs:ignore
					);
				}
			} elseif ( 'no' === $afwc_payouts_migration_done ) {
				$result = $wpdb->query( // phpcs:ignore
					$wpdb->prepare(// phpcs:ignore
						"UPDATE {$wpdb->prefix}afwc_payouts set datetime = CONVERT_TZ( datetime, %s , '+00:00' ), migrate_date = TRUE WHERE migrate_date IS NULL LIMIT %d",
						$timezone_str,
						$batch_size
					)
				);

				$total_payouts_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}afwc_payouts WHERE migrate_date IS NULL" );// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

				if ( 0 === absint( $total_payouts_count ) ) {
					update_option( 'afwc_payouts_migration_done', 'yes' );
					$res = $wpdb->query( // phpcs:ignore
						"ALTER table {$wpdb->prefix}afwc_payouts DROP COLUMN migrate_date" // phpcs:ignore
					);
				}
			}

			// if all flags are true then set afwc_dates_migration_done = true.
			// else schedule another action calling afwc_schedule_action_for_date_migration.
			$afwc_hits_migration_done      = get_option( 'afwc_hits_migration_done' );
			$afwc_referrals_migration_done = get_option( 'afwc_referrals_migration_done' );
			$afwc_payouts_migration_done   = get_option( 'afwc_payouts_migration_done' );
			$afwc_dates_migration_done     = get_option( 'afwc_dates_migration_done' );

			if ( ( 'yes' === $afwc_hits_migration_done ) &&
				( 'yes' === $afwc_referrals_migration_done ) &&
				( 'yes' === $afwc_payouts_migration_done )
			) {
				$afwc_dates_migration_done = 'yes';
				update_option( 'afwc_dates_migration_done', 'yes', 'no' );
				delete_option( 'afwc_hits_migration_done' );
				delete_option( 'afwc_referrals_migration_done' );
				delete_option( 'afwc_payouts_migration_done' );
				delete_option( 'afwc_is_migration_process_running' );
				return;
			}

			if ( 'no' === $afwc_dates_migration_done ) {
				if ( function_exists( 'as_schedule_single_action' ) ) {
					update_option( 'afwc_is_migration_process_running', true, 'no' );
					$int = as_schedule_single_action( time(), 'afwc_schedule_action_for_date_migration' );
				}
			}
		}
	}
}

AFWC_DB_Background_Process::get_instance();
