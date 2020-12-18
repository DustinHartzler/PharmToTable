<?php

namespace AutomateWoo;

use AutomateWoo\Data_Types\Shop;
use AutomateWoo\Data_Types\Subscription_Item;

defined( 'ABSPATH' ) || exit;

/**
 * Data_Types registry class.
 *
 * @since 2.4.6
 */
class Data_Types extends Registry {

	const CARD              = 'card';
	const CART              = 'cart';
	const CATEGORY          = 'category';
	const COMMENT           = 'comment';
	const CUSTOMER          = 'customer';
	const GUEST             = 'guest';
	const MEMBERSHIP        = 'membership';
	const ORDER             = 'order';
	const ORDER_ITEM        = 'order_item';
	const ORDER_NOTE        = 'order_note';
	const POST              = 'post';
	const PRODUCT           = 'product';
	const REVIEW            = 'review';
	const SHOP              = 'shop';
	const SUBSCRIPTION      = 'subscription';
	const SUBSCRIPTION_ITEM = 'subscription_item';
	const TAG               = 'tag';
	const USER              = 'user';
	const WISHLIST          = 'wishlist';
	const WORKFLOW          = 'workflow';

	/** @var array */
	protected static $includes;

	/** @var array */
	protected static $loaded = [];

	/**
	 * @return array
	 */
	public static function load_includes() {
		return apply_filters(
			'automatewoo/data_types/includes',
			[
				self::CARD              => Data_Type_Card::class,
				self::CART              => Data_Type_Cart::class,
				self::CATEGORY          => Data_Type_Category::class,
				self::COMMENT           => Data_Type_Comment::class,
				self::CUSTOMER          => Data_Type_Customer::class,
				self::GUEST             => Data_Type_Guest::class,
				self::MEMBERSHIP        => Data_Type_Membership::class,
				self::ORDER_ITEM        => Data_Type_Order_Item::class,
				self::ORDER_NOTE        => Data_Type_Order_Note::class,
				self::ORDER             => Data_Type_Order::class,
				self::POST              => Data_Type_Post::class,
				self::PRODUCT           => Data_Type_Product::class,
				self::REVIEW            => Data_Type_Review::class,
				self::SHOP              => Shop::class,
				self::SUBSCRIPTION_ITEM => Subscription_Item::class,
				self::SUBSCRIPTION      => Data_Type_Subscription::class,
				self::TAG               => Data_Type_Tag::class,
				self::USER              => Data_Type_User::class,
				self::WISHLIST          => Data_Type_Wishlist::class,
				self::WORKFLOW          => Data_Type_Workflow::class,
			]
		);
	}

	/**
	 * Get a data type object.
	 *
	 * @param string $data_type_id
	 *
	 * @return Data_Type|false
	 */
	public static function get( $data_type_id ) {
		return parent::get( $data_type_id );
	}

	/**
	 * Runs after a valid item is loaded.
	 *
	 * @param string    $data_type_id
	 * @param Data_Type $data_type
	 */
	public static function after_loaded( $data_type_id, $data_type ) {
		$data_type->set_id( $data_type_id );
	}

	/**
	 * Get data types that shouldn't be stored.
	 *
	 * @return array
	 */
	public static function get_non_stored_data_types() {
		return [ 'shop' ];
	}

	/**
	 * Check if a data type should be stored.
	 *
	 * @param string $data_type_id
	 *
	 * @return bool
	 * @since 5.1.0
	 */
	public static function is_non_stored_data_type( $data_type_id ) {
		return in_array( $data_type_id, self::get_non_stored_data_types(), true );
	}

	/**
	 * Checks that data type object is valid.
	 *
	 * @param mixed $item
	 *
	 * @return bool
	 * @since 4.9.0
	 */
	public static function is_item_valid( $item ) {
		return $item instanceof Data_Type;
	}
}
