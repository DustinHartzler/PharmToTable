<?php

namespace AutomateWoo\Triggers\Utilities;

use AutomateWoo\Customer_Factory;
use AutomateWoo\Data_Layer;
use AutomateWoo\DataTypes\DataTypes;
use AutomateWoo\Exceptions\InvalidValue;
use WC_Booking;

/**
 * Trait BookingDataLayer
 *
 * @since 5.3.0
 */
trait BookingDataLayer {

	/**
	 * Get the supplied data items for a booking.
	 *
	 * @return string[]
	 */
	protected function get_supplied_data_items_for_booking(): array {
		return [ DataTypes::BOOKING, DataTypes::CUSTOMER, DataTypes::PRODUCT ];
	}

	/**
	 * Generate a booking data layer from a booking object.
	 *
	 * Includes booking, customer, booking product data types.
	 *
	 * @param WC_Booking $booking
	 *
	 * @return Data_Layer
	 *
	 * @throws InvalidValue If the booking's customer or booking is not found.
	 */
	protected function generate_booking_data_layer( WC_Booking $booking ): Data_Layer {
		// First try to retrieve customer from order.
		$order    = $booking->get_order();
		$customer = Customer_Factory::get_by_order( $order );
		if ( ! $customer ) {
			// If that fails, retrieve customer from booking.
			$customer = Customer_Factory::get_by_user_id( $booking->get_customer_id() );
		}

		if ( ! $customer ) {
			throw InvalidValue::item_not_found( DataTypes::CUSTOMER );
		}

		$product = $booking->get_product();
		if ( ! $product ) {
			throw InvalidValue::item_not_found( DataTypes::PRODUCT );
		}

		return new Data_Layer(
			[
				DataTypes::BOOKING  => $booking,
				DataTypes::CUSTOMER => $customer,
				DataTypes::PRODUCT  => $product,
			]
		);
	}

}
