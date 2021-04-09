<?php
/**
 * Class to handle db update background process
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       3.0.0
 * @version     1.0.0
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

			add_action( 'afwc_run_migrate_order_status_action', array( $this, 'run_migrate_order_status' ) );
			add_action( 'wp_ajax_afwc_run_migration', array( $this, 'run_migrate_order_status' ) );
			add_filter( 'wp_ajax_afwc_check_migration_process', array( $this, 'check_order_migration_status' ), 10, 2 );

		}

		/**
		 * Push coupon background progress in response
		 */
		public function check_order_migration_status() {
			global $wpdb;

			$current_db_version             = get_option( 'afwc_current_db_version' );
			$migration_of_order_status_done = get_option( 'afwc_migration_for_order_status_done', false );
			$response                       = array();
			if ( '1.2.7' >= $current_db_version && ! $migration_of_order_status_done ) {
				$total_orders                            = get_option( 'afwc_total_order_to_migrate', false );
				$migrated_orders                         = get_option( 'afwc_total_order_migrated', false );
				$response['afwc_order_migration_status'] = ( ! empty( $migrated_orders ) ) ? round( ( $migrated_orders * 100 ) / $total_orders ) : 0;
			} else {
				$response['afwc_order_migration_status'] = 100;
			}

			wp_send_json( $response );
		}

		/**
		 * Function to migrate order status in batch
		 */
		public function run_migrate_order_status() {
			global $wpdb;
			$batch_size = 50;

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
	}
}

AFWC_DB_Background_Process::get_instance();
