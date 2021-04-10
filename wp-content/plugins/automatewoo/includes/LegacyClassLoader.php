<?php

namespace AutomateWoo;

/**
 * LegacyClassLoader class.
 *
 * This serves to alias legacy classes as needed.
 *
 * @package AutomateWoo
 * @since   5.0.0
 */
class LegacyClassLoader {

	/**
	 * Array of legacy classes and their replacements.
	 *
	 * The key is the old class, and the value is the new class.
	 *
	 * @var array
	 */
	protected $legacy_classes = [
		\AW_Rule_Cart_Count::class                       => Rules\CartItemCount::class,
		\AW_Rule_Cart_Total::class                       => Rules\CartTotal::class,
		\AW_Rule_Guest_Email::class                      => Rules\GuestEmail::class,
		\AW_Rule_Guest_Order_Count::class                => Rules\GuestOrderCount::class,
		\AW_Rule_Guest_Run_Count::class                  => Rules\GuestRunCount::class,
		\AW_Rule_Order_Has_Cross_Sells::class            => Rules\OrderHasCrossSells::class,
		\AW_Rule_Order_Is_Customers_First::class         => Rules\OrderIsCustomersFirst::class,
		\AW_Rule_Order_Is_POS::class                     => Rules\OrderIsPos::class,
		\AW_Rule_Order_Run_Count::class                  => Rules\OrderRunCount::class,
		\AW_Rule_Order_Shipping_Country::class           => Rules\OrderShippingCountry::class,
		\AW_Rule_Order_Shipping_Method_String::class     => Rules\OrderShippingMethodString::class,
		\AW_Rule_Order_Total::class                      => Rules\OrderTotal::class,
		\AW_Rule_Subscription_Payment_Count::class       => Rules\SubscriptionPaymentCount::class,
		\AW_System_Check_Cron_Running::class             => SystemChecks\CronRunning::class,
		\AW_System_Check_Database_Tables_Exist::class    => SystemChecks\DatabaseTablesExist::class,
		\AW_Variable_Comment_Author_Name::class          => Variables\CommentAuthorName::class,
		\AutomateWoo\Base_System_Check::class            => SystemChecks\AbstractSystemCheck::class,
		\AutomateWoo\Database_Table_Carts::class         => DatabaseTables\Carts::class,
		\AutomateWoo\Database_Table_Customer_Meta::class => DatabaseTables\CustomerMeta::class,
		\AutomateWoo\Database_Table_Customers::class     => DatabaseTables\Customers::class,
		\AutomateWoo\Database_Table_Events::class        => DatabaseTables\Events::class,
		\AutomateWoo\Database_Table_Guest_Meta::class    => DatabaseTables\GuestMeta::class,
		\AutomateWoo\Database_Table_Guests::class        => DatabaseTables\Guests::class,
		\AutomateWoo\Database_Table_Log_Meta::class      => DatabaseTables\LogMeta::class,
		\AutomateWoo\Database_Table_Logs::class          => DatabaseTables\Logs::class,
		\AutomateWoo\Database_Table_Queue::class         => DatabaseTables\Queue::class,
		\AutomateWoo\Database_Table_Queue_Meta::class    => DatabaseTables\QueueMeta::class,
		\AutomateWoo\Database_Update::class              => DatabaseUpdates\AbstractDatabaseUpdate::class,
		\AutomateWoo\Admin_Notices::class                => AdminNotices::class,
		\AutomateWoo\Workflow_Factory::class             => Workflows\Factory::class,
		\AutomateWoo\Query_Custom_Table::class           => Query_Abstract::class,
		\AutomateWoo\Data_Types::class                   => DataTypes\DataTypes::class,
		\AutomateWoo\Data_Types\Shop::class              => DataTypes\Shop::class,
		\AutomateWoo\Data_Type::class                    => DataTypes\AbstractDataType::class,
		\AutomateWoo\Data_Type_Card::class               => DataTypes\Card::class,
		\AutomateWoo\Data_Type_Cart::class               => DataTypes\Cart::class,
		\AutomateWoo\Data_Type_Category::class           => DataTypes\ProductCategory::class,
		\AutomateWoo\Data_Type_Comment::class            => DataTypes\Comment::class,
		\AutomateWoo\Data_Type_Customer::class           => DataTypes\Customer::class,
		\AutomateWoo\Data_Type_Guest::class              => DataTypes\Guest::class,
		\AutomateWoo\Data_Type_Membership::class         => DataTypes\Membership::class,
		\AutomateWoo\Data_Type_Order::class              => DataTypes\Order::class,
		\AutomateWoo\Data_Type_Order_Item::class         => DataTypes\OrderItem::class,
		\AutomateWoo\Data_Type_Order_Note::class         => DataTypes\OrderNote::class,
		\AutomateWoo\Data_Type_Post::class               => DataTypes\Post::class,
		\AutomateWoo\Data_Type_Product::class            => DataTypes\Product::class,
		\AutomateWoo\Data_Type_Review::class             => DataTypes\Review::class,
		\AutomateWoo\Data_Type_Subscription::class       => DataTypes\Subscription::class,
		\AutomateWoo\Data_Types\Subscription_Item::class => DataTypes\SubscriptionItem::class,
		\AutomateWoo\Data_Type_Tag::class                => DataTypes\ProductTag::class,
		\AutomateWoo\Data_Type_User::class               => DataTypes\User::class,
		\AutomateWoo\Data_Type_Wishlist::class           => DataTypes\Wishlist::class,
		\AutomateWoo\Data_Type_Workflow::class           => DataTypes\Workflow::class,
		\AutomateWoo\Workflow_Variable_Parser::class     => Workflows\VariableParsing\VariableParser::class,

		// Renamed in 5.4.0
		\AutomateWoo\Action_Subscription_Edit_Item_Abstract::class => Actions\Subscriptions\AbstractEditItem::class,
		\AutomateWoo_Subscriptions\Abstract_Action_Subscription_Edit_Shipping::class => Actions\Subscriptions\AbstractEditShipping::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Add_Shipping::class => Actions\Subscriptions\AddShipping::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Recalculate_Taxes::class => Actions\Subscriptions\RecalculateTaxes::class,
		\AutomateWoo_Subscriptions\Action_Regenerate_Download_Permissions::class => Actions\Subscriptions\RegenerateDownloadPermissions::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Remove_Shipping::class => Actions\Subscriptions\RemoveShipping::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Update_Currency::class => Actions\Subscriptions\UpdateCurrency::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Update_Next_Payment_Date::class => Actions\Subscriptions\UpdateNextPaymentDate::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Update_Product::class => Actions\Subscriptions\UpdateProduct::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Update_Schedule::class => Actions\Subscriptions\UpdateSchedule::class,
		\AutomateWoo_Subscriptions\Action_Subscription_Update_Shipping::class => Actions\Subscriptions\UpdateShipping::class,
	];

	/**
	 * Destructor for the Autoloader class.
	 *
	 * The destructor automatically unregisters the autoload callback function
	 * with the SPL autoload system.
	 */
	public function __destruct() {
		$this->unregister();
	}

	/**
	 * Registers the autoload callback with the SPL autoload system.
	 */
	public function register() {
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/**
	 * Unregisters the autoload callback with the SPL autoload system.
	 */
	public function unregister() {
		spl_autoload_unregister( [ $this, 'autoload' ] );
	}

	/**
	 * Autoload legacy AutomateWoo classes.
	 *
	 * @param string $legacy_class The legacy class name.
	 */
	public function autoload( $legacy_class ) {
		if ( ! array_key_exists( $legacy_class, $this->legacy_classes ) ) {
			return;
		}

		$new_class = $this->legacy_classes[ $legacy_class ];
		if ( ! class_exists( $new_class ) ) {
			return;
		}

		$this->trigger_class_warning( $legacy_class, $new_class );

		class_alias( $new_class, $legacy_class );
	}

	/**
	 * Get the deprecation warning message when a deprecated class is loaded.
	 *
	 * Override this method in a child class to change the message.
	 *
	 * @param string $legacy_class The fully qualified name of the legacy class that was used.
	 * @param string $new_class    The fully qualified name of the replacement class.
	 *
	 * @return string
	 */
	protected function get_deprecation_message( $legacy_class, $new_class ) {
		return sprintf(
			/* translators: %1$s is the deprecated class, and %2$s is the new class to replace it */
			__(
				'The class %1$s has been deprecated and replaced with %2$s. It will be removed in a future version of AutomateWoo.',
				'automatewoo'
			),
			$legacy_class,
			$new_class
		);
	}

	/**
	 * Trigger a notice to the user when a legacy class is loaded.
	 *
	 * @param string $legacy_class The legacy class name.
	 * @param string $new_class    The replacement class.
	 */
	private function trigger_class_warning( $legacy_class, $new_class ) {
		if ( ! WP_DEBUG ) {
			return;
		}

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions,WordPress.Security.EscapeOutput
		trigger_error( $this->get_deprecation_message( $legacy_class, $new_class ), E_USER_DEPRECATED );
	}
}
