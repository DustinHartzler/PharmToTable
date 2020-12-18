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
		\AW_Rule_Cart_Count::class                       => \AutomateWoo\Rules\CartItemCount::class,
		\AW_Rule_Cart_Total::class                       => \AutomateWoo\Rules\CartTotal::class,
		\AW_Rule_Guest_Email::class                      => \AutomateWoo\Rules\GuestEmail::class,
		\AW_Rule_Guest_Order_Count::class                => \AutomateWoo\Rules\GuestOrderCount::class,
		\AW_Rule_Guest_Run_Count::class                  => \AutomateWoo\Rules\GuestRunCount::class,
		\AW_Rule_Order_Has_Cross_Sells::class            => \AutomateWoo\Rules\OrderHasCrossSells::class,
		\AW_Rule_Order_Is_Customers_First::class         => \AutomateWoo\Rules\OrderIsCustomersFirst::class,
		\AW_Rule_Order_Is_POS::class                     => \AutomateWoo\Rules\OrderIsPos::class,
		\AW_Rule_Order_Run_Count::class                  => \AutomateWoo\Rules\OrderRunCount::class,
		\AW_Rule_Order_Shipping_Country::class           => \AutomateWoo\Rules\OrderShippingCountry::class,
		\AW_Rule_Order_Shipping_Method_String::class     => \AutomateWoo\Rules\OrderShippingMethodString::class,
		\AW_Rule_Order_Total::class                      => \AutomateWoo\Rules\OrderTotal::class,
		\AW_Rule_Subscription_Payment_Count::class       => \AutomateWoo\Rules\SubscriptionPaymentCount::class,
		\AW_System_Check_Cron_Running::class             => \AutomateWoo\SystemChecks\CronRunning::class,
		\AW_System_Check_Database_Tables_Exist::class    => \AutomateWoo\SystemChecks\DatabaseTablesExist::class,
		\AW_Variable_Comment_Author_Name::class          => \AutomateWoo\Variables\CommentAuthorName::class,
		\AutomateWoo\Base_System_Check::class            => \AutomateWoo\SystemChecks\AbstractSystemCheck::class,
		\AutomateWoo\Database_Table_Carts::class         => \AutomateWoo\DatabaseTables\Carts::class,
		\AutomateWoo\Database_Table_Customer_Meta::class => \AutomateWoo\DatabaseTables\CustomerMeta::class,
		\AutomateWoo\Database_Table_Customers::class     => \AutomateWoo\DatabaseTables\Customers::class,
		\AutomateWoo\Database_Table_Events::class        => \AutomateWoo\DatabaseTables\Events::class,
		\AutomateWoo\Database_Table_Guest_Meta::class    => \AutomateWoo\DatabaseTables\GuestMeta::class,
		\AutomateWoo\Database_Table_Guests::class        => \AutomateWoo\DatabaseTables\Guests::class,
		\AutomateWoo\Database_Table_Log_Meta::class      => \AutomateWoo\DatabaseTables\LogMeta::class,
		\AutomateWoo\Database_Table_Logs::class          => \AutomateWoo\DatabaseTables\Logs::class,
		\AutomateWoo\Database_Table_Queue::class         => \AutomateWoo\DatabaseTables\Queue::class,
		\AutomateWoo\Database_Table_Queue_Meta::class    => \AutomateWoo\DatabaseTables\QueueMeta::class,
		\AutomateWoo\Database_Update::class              => \AutomateWoo\DatabaseUpdates\AbstractDatabaseUpdate::class,
		\AutomateWoo\Admin_Notices::class                => \AutomateWoo\AdminNotices::class,
		\AutomateWoo\Workflow_Factory::class             => \AutomateWoo\Workflows\Factory::class,
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
		// phpcs:disable WordPress.PHP.DevelopmentFunctions,WordPress.Security.EscapeOutput
		trigger_error( $this->get_deprecation_message( $legacy_class, $new_class ), E_USER_DEPRECATED );
		// phpcs:enable
	}
}
