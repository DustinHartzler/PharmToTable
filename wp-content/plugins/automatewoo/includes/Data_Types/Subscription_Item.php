<?php

namespace AutomateWoo\Data_Types;

use AutomateWoo\Data_Type_Order_Item;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Data_Type_Subscription_Item
 *
 * @since 4.8.0
 */
class Subscription_Item extends Data_Type_Order_Item {

	/**
	 * Subscription items are retrieved from the order object so we must ensure that an order is always
	 * present in the data layer.
	 *
	 * @param int   $order_item_id
	 * @param array $compressed_data_layer
	 *
	 * @return mixed
	 */
	public function decompress( $order_item_id, $compressed_data_layer ) {
		if ( ! $order_item_id || ! isset( $compressed_data_layer['subscription'] ) ) {
			return false;
		}

		$subscription = wcs_get_subscription( $compressed_data_layer['subscription'] );
		if ( ! $subscription ) {
			return false;
		}

		return $subscription->get_item( $order_item_id );
	}
}
