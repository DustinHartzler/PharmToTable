<?php
// phpcs:ignoreFile

namespace AutomateWoo;

use AutomateWoo\Data_Types\Subscription_Item;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Types
 * @since 2.4.6
 */
class Data_Types extends Registry {

	/** @var array */
	static $includes;

	/** @var array  */
	static $loaded = [];

	/**
	 * @return array
	 */
	static function load_includes() {
		return apply_filters( 'automatewoo/data_types/includes', [
			'card'              => Data_Type_Card::class,
			'cart'              => Data_Type_Cart::class,
			'category'          => Data_Type_Category::class,
			'comment'           => Data_Type_Comment::class,
			'customer'          => Data_Type_Customer::class,
			'guest'             => Data_Type_Guest::class,
			'membership'        => Data_Type_Membership::class,
			'order_item'        => Data_Type_Order_Item::class,
			'order_note'        => Data_Type_Order_Note::class,
			'order'             => Data_Type_Order::class,
			'post'              => Data_Type_Post::class,
			'product'           => Data_Type_Product::class,
			'review'            => Data_Type_Review::class,
			'subscription_item' => Subscription_Item::class,
			'subscription'      => Data_Type_Subscription::class,
			'tag'               => Data_Type_Tag::class,
			'user'              => Data_Type_User::class,
			'wishlist'          => Data_Type_Wishlist::class,
			'workflow'          => Data_Type_Workflow::class,
		] );
	}

	/**
	 * @param $data_type_id
	 * @return Data_Type|false
	 */
	static function get( $data_type_id ) {
		return parent::get( $data_type_id );
	}

	/**
	 * @param string $data_type_id
	 * @param Data_Type $data_type
	 */
	static function after_loaded( $data_type_id, $data_type ) {
		$data_type->set_id( $data_type_id );
	}

	/**
	 * @return array
	 */
	static function get_non_stored_data_types() {
		return [ 'shop' ];
	}

	/**
	 * Checks that data type object is valid.
	 *
	 * @param mixed $item
	 *
	 * @since 4.9.0
	 *
	 * @return bool
	 */
	public static function is_item_valid( $item ) {
		return $item instanceof Data_Type;
	}
}
