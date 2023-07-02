<?php

namespace OM4\WooCommerceZapier\Plugin\Bookings;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Plugin\Bookings\Payload;
use OM4\WooCommerceZapier\Plugin\Bookings\V1Controller;
use OM4\WooCommerceZapier\Webhook\Trigger\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;
use WC_Booking;
use WC_Bookings_REST_Booking_Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Bookings resource type.
 *
 * This resource is only enabled if WooCommerce Bookings is available.
 *
 * WooCommerce Bookings does not have webhook payload, topic and delivery functionality built-in,
 * so this class implements those.
 *
 * @since 2.2.0
 */
class BookingResource extends CustomPostTypeResource {

	/**
	 * Controller instance.
	 *
	 * @var V1Controller
	 */
	protected $controller;

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * {@inheritDoc}
	 *
	 * @param V1Controller   $controller Controller instance.
	 * @param FeatureChecker $checker    FeatureChecker instance.
	 */
	public function __construct( V1Controller $controller, FeatureChecker $checker ) {
		$this->controller          = $controller;
		$this->checker             = $checker;
		$this->key                 = 'booking';
		$this->name                = __( 'Booking', 'woocommerce-zapier' );
		$this->metabox_screen_name = 'wc_booking';
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_enabled() {
		return $this->checker->class_exists( WC_Bookings_REST_Booking_Controller::class );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_controller_name() {
		return V1Controller::class;
	}

	/**
	 * Get the Bookings REST API controller's REST API version.
	 *
	 * Bookings uses a REST API v1 payload.
	 *
	 * This is because the Bookings endpoint is a REST API v1 controller, we need to always deliver a v1 payload.
	 *
	 * @inheritDoc
	 */
	public function get_controller_rest_api_version() {
		return 1;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array_merge(
			array(
				/**
				 * Trigger when a booking is created.
				 *
				 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-created
				 *
				 * @hook: woocommerce_new_booking
				 * @see \WC_Booking_Data_Store::create()
				 * @param integer $booking_id ID of the booking.
				 *
				 * @return void
				 */
				new Trigger(
					'booking.created',
					__( 'Booking created', 'woocommerce-zapier' ),
					array( 'woocommerce_new_booking' )
				),
				/**
				 * Trigger when a booking is ordered (changes status from `in-cart` to any status except `cancelled`).
				 *
				 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-ordered
				 *
				 * @hook: woocommerce_booking_status_changed
				 * @see \WC_Booking::status_transitioned_handler()
				 * @param integer $booking_id ID of the booking.
				 *
				 * @return void
				 */
				new Trigger(
					'booking.ordered',
					__( 'Booking ordered', 'woocommerce-zapier' ),
					array( 'wc_zapier_woocommerce_booking_ordered' )
				),
				/**
				 * Trigger when a booking is deleted (trashed).
				 *
				 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-deleted
				 *
				 * @hook: trashed_post
				 * @see wp_trash_post()
				 * @param integer $booking_id ID of the booking.
				 *
				 * @return void
				 */
				new Trigger(
					'booking.deleted',
					__( 'Booking deleted', 'woocommerce-zapier' ),
					array( 'wc_zapier_woocommerce_booking_deleted' )
				),
				/**
				 * Trigger when a booking is restored from the trash.
				 *
				 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-restored
				 *
				 * @hook: untrashed_post
				 * @see \wp_untrash_post()
				 * @param integer $booking_id ID of the booking.
				 *
				 * @return void
				 */
				new Trigger(
					'booking.restored',
					__( 'Booking restored', 'woocommerce-zapier' ),
					array( 'wc_zapier_woocommerce_booking_restored' )
				),
				/**
				 * Trigger when a booking changes status.
				 *
				 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-status-changed
				 *
				 * @hook: woocommerce_booking_status_changed
				 * @see \WC_Booking::status_transitioned_handler()
				 * @param int $booking_id Booking ID.
				 *
				 * @return void
				 */
				new Trigger(
					'booking.status_changed',
					__( 'Booking status changed (any status)', 'woocommerce-zapier' ),
					array( 'wc_zapier_woocommerce_booking_status_changed' )
				),
				/**
				 * Trigger when a booking is cancelled.
				 *
				 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-cancelled
				 *
				 * @hook: woocommerce_booking_{new_status}
				 * @see \WC_Booking::status_transitioned_handler()
				 * @param integer $booking_id ID of the booking.
				 *
				 * @return void
				 */
				new Trigger(
					'booking.cancelled',
					__( 'Booking cancelled', 'woocommerce-zapier' ),
					array( 'woocommerce_booking_cancelled' )
				),
				/**
				 * Trigger when a booking is updated (including when it is first created).
				 *
				 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-updated
				 *
				 * @hook: save_post_wc_booking
				 * @see wp_insert_post()
				 * @param integer $post_ID ID of the booking.
				 * @param WP_Post $post    Booking object.
				 * @param bool    $update  Whether this is an existing post being updated.
				 *
				 * @return void
				 */
				new Trigger(
					'booking.updated',
					__( 'Booking updated', 'woocommerce-zapier' ),
					array( 'wc_zapier_woocommerce_booking_updated', 'woocommerce_new_booking' )
				),
			),
			$this->get_status_changed_dynamic_triggers()
		);
	}

	/**
	 * Dynamically create a "Booking Status Changed to ..." Trigger Rule,
	 * one for each registered WooCommerce booking status.
	 *
	 * @return Trigger[]
	 */
	protected function get_status_changed_dynamic_triggers() {
		$triggers = array();
		foreach ( $this->get_statuses() as $status => $status_label ) {
			$status_key = str_replace( '-', '_', sanitize_title_with_dashes( $status ) );
			$triggers[] = new Trigger(
				"booking.status_changed_to_{$status_key}",
				// Translators: Booking Status Name/Label.
				sprintf( __( 'Booking status changed to %s', 'woocommerce-zapier' ), $status_label ),
				array( "woocommerce_booking_{$status}" )
			);
		}
		return $triggers;
	}

	/**
	 * Get a list of all registered WooCommerce Booking statuses, excluding the default status (unpaid).
	 *
	 * @return array<string, string> Status key excludes the 'wc-' prefix.
	 */
	protected function get_statuses() {
		$statuses       = array();
		$default_status = ( new WC_Booking() )->get_status();

		/**
		 * Get a full list of possible Booking statuses.
		 *
		 * @see \WC_Booking::status_transition()
		 */
		$registered_statuses = array_unique(
			array_merge(
				\get_wc_booking_statuses( '', true ),
				\get_wc_booking_statuses( 'user', true ),
				\get_wc_booking_statuses( 'cancel', true )
			)
		);
		foreach ( $registered_statuses as $status => $status_label ) {
			// Use the status without wc- internal prefix.
			$status = 'wc-' === substr( $status, 0, 3 ) ? substr( $status, 3 ) : $status;
			if ( $default_status === $status ) {
				continue;
			}
			$statuses[ $status ] = $status_label;
		}
		return $statuses;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_payload() {
		return new Payload( $this->key, $this->controller );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id  Resource ID.
	 * @param int $variation_id Variation ID.
	 */
	public function get_description( $resource_id, $variation_id = 0 ) {
		$object = \get_wc_booking( $resource_id );
		if ( false !== $object && is_a( $object, 'WC_Booking' ) && 'trash' !== $object->get_status() ) {
			// Use the corresponding order's billing name.
			$order = \wc_get_order( $object->get_order_id() );
			if ( is_callable( array( $order, 'get_formatted_billing_full_name' ) ) ) {
				return \sprintf(
					/* translators: 1: Booking ID, 2: Order Formatted Full Billing Name */
					__( 'Booking #%1$d (%2$s)', 'woocommerce-zapier' ),
					$object->get_id(),
					\trim( $order->get_formatted_billing_full_name() )
				);
			} else {
				// Booking does not have an order or order no longer exists.
				/* translators: 1: Booking ID */
				return \sprintf( __( 'Booking #%d', 'woocommerce-zapier' ), $object->get_id() );
			}
		}
		return null;
	}
}
