<?php
/**
 * Class to delete referrals data on order delete i.e. Housekeeping.
 *
 * @package   affiliate-for-woocommerce/includes/admin/
 * @since     6.14.0
 * @version   1.0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Admin_Housekeeping' ) ) {

	/**
	 * Main class for Affiliate's Housekeeping functionality.
	 */
	class AFWC_Admin_Housekeeping {

		/**
		 * Variable to hold instance of AFWC_Admin_Housekeeping
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		public function __construct() {

			// Update referral record when an order is trashed/untrashed/deleted.
			if ( is_callable( 'afwc_is_hpos_enabled' ) && afwc_is_hpos_enabled() ) {
				// These actions are available since a particular WooCommerce version.
				// @since WC 7.1.0.
				add_action( 'woocommerce_before_trash_order', array( $this, 'afwc_update_referral_before_trash' ), 9 );
				// @since WC 7.2.0.
				add_action( 'woocommerce_untrash_order', array( $this, 'afwc_update_referral_before_untrash' ), 9, 2 );
				// @since WC 7.1.0.
				add_action( 'woocommerce_before_delete_order', array( $this, 'afwc_update_referral_before_delete' ), 9, 2 );
			} else {
				add_action( 'trashed_post', array( $this, 'afwc_update_referral_on_trash_delete_untrash' ), 9, 1 );
				add_action( 'untrashed_post', array( $this, 'afwc_update_referral_on_trash_delete_untrash' ), 9, 1 );
				add_action( 'delete_post', array( $this, 'afwc_update_referral_on_trash_delete_untrash' ), 9, 1 );
			}
		}

		/**
		 * Get single instance of this class
		 *
		 * @return AFWC_Admin_Housekeeping Singleton object of this class
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Function to delete referral and order data before order is permanently deleted.
		 *
		 * @param int $order_id The Order ID to be deleted.
		 */
		public function afwc_update_referral_before_trash( $order_id = 0 ) {
			if ( empty( $order_id ) ) {
				return;
			}

			global $wpdb;

			$afwc_order_status = 'deleted';

			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET order_status = %s WHERE post_id = %d", $afwc_order_status, $order_id ) ); // phpcs:ignore
		}

		/**
		 * Function to delete referral and order data before order is untrashed.
		 *
		 * @param int    $order_id              The Order ID to be deleted.
		 * @param string $previous_order_status The Order status before order was trashed.
		 */
		public function afwc_update_referral_before_untrash( $order_id = 0, $previous_order_status = '' ) {
			if ( empty( $order_id ) ) {
				return;
			}

			$order = wc_get_order( $order_id );

			$afwc_order_status = ( ! empty( $previous_order_status ) ) ? $previous_order_status : ( is_callable( array( $order, 'get_status' ) ) ? $order->get_status() : '' );
			if ( empty( $afwc_order_status ) ) {
				return;
			}
			$afwc_order_status = afwc_prefix_wc_to_order_status( $afwc_order_status );

			global $wpdb;

			$affected_row = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET order_status = %s WHERE post_id = %d", $afwc_order_status, $order_id ) ); // phpcs:ignore

			// update order meta only if update was successful.
			if ( 1 === $affected_row ) {
				$order->update_meta_data( 'is_commission_recorded', 'yes' );
				$order->save();
			}
		}

		/**
		 * Function to delete referral and order data before order is permanently deleted.
		 *
		 * @param int    $order_id The Order ID to be deleted.
		 * @param object $order    The Order object.
		 */
		public function afwc_update_referral_before_delete( $order_id = 0, $order = null ) {
			if ( empty( $order_id ) ) {
				return;
			}

			global $wpdb;

			$affected_row = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}afwc_referrals WHERE post_id = %d", $order_id ) ); // phpcs:ignore

			// update affiliate's order meta only if update was successful.
			if ( 1 === $affected_row ) {
				if ( empty( $order ) || ! $order instanceof WC_Order ) {
					$order = wc_get_order( $order_id );
				}

				$order->delete_meta_data( 'is_commission_recorded' );
				$order->delete_meta_data( 'afwc_order_valid_plans' );
				$order->delete_meta_data( 'afwc_set_commission' );
				$order->delete_meta_data( 'afwc_parent_commissions' );
				$order->save();
			}
		}

		/**
		 * Function to update referral entry when order is trashed/untrashed/deleted.
		 *
		 * @param int $trashed_order_id The Order ID being trashed/untrashed/deleted.
		 */
		public function afwc_update_referral_on_trash_delete_untrash( $trashed_order_id = 0 ) {
			if ( empty( $trashed_order_id ) ) {
				return;
			}

			$current_action = current_action();

			global $wpdb;

			if ( 'trashed_post' === $current_action ) {
				$this->afwc_update_referral_before_trash( $trashed_order_id );
			}

			if ( 'untrashed_post' === $current_action ) {
				$order             = wc_get_order( $trashed_order_id );
				$afwc_order_status = ( $order instanceof WC_Order && is_callable( array( $order, 'get_status' ) ) ) ? $order->get_status() : '';
				if ( empty( $afwc_order_status ) ) {
					return;
				}
				$afwc_order_status = afwc_prefix_wc_to_order_status( $afwc_order_status );

				$affected_row = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}afwc_referrals SET order_status = %s WHERE post_id = %d", $afwc_order_status, $trashed_order_id ) ); // phpcs:ignore
				// update order meta only if update was successful.
				if ( 1 === $affected_row ) {
					update_post_meta( $trashed_order_id, 'is_commission_recorded', 'yes' );
				}
			}

			if ( 'delete_post' === $current_action ) {
				$affected_row = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}afwc_referrals WHERE post_id = %d", $trashed_order_id ) ); // phpcs:ignore
				// update affiliate's order meta only if update was successful.
				if ( 1 === $affected_row ) {
					delete_post_meta( $trashed_order_id, 'is_commission_recorded' );
					delete_post_meta( $trashed_order_id, 'afwc_order_valid_plans' );
					delete_post_meta( $trashed_order_id, 'afwc_set_commission' );
					delete_post_meta( $trashed_order_id, 'afwc_parent_commissions' );
				}
			}
		}
	}

}

return new AFWC_Admin_Housekeeping();
