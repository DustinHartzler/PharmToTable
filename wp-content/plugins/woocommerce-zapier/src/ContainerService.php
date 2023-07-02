<?php

namespace OM4\WooCommerceZapier;

use OM4\WooCommerceZapier\AdminUI;
use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\API\Controller\PingController;
use OM4\WooCommerceZapier\API\Controller\WebhookController;
use OM4\WooCommerceZapier\API\Controller\WebhookTopicsController;
use OM4\WooCommerceZapier\Auth\AuthKeyRotator;
use OM4\WooCommerceZapier\Auth\KeyDataStore;
use OM4\WooCommerceZapier\Auth\SessionAuthenticate;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Helper\HTTPHeaders;
use OM4\WooCommerceZapier\Helper\WordPressDB;
use OM4\WooCommerceZapier\Installer;
use OM4\WooCommerceZapier\LegacyMigration\ExistingUserUpgrade;
use OM4\WooCommerceZapier\LegacyMigration\LegacyFeedsDeletedNotice;
use OM4\WooCommerceZapier\LegacyMigration\UpgradedToVersion2Notice;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\NewUser\NewUser;
use OM4\WooCommerceZapier\NewUser\NewUserWelcomeNotice;
use OM4\WooCommerceZapier\Plugin;
use OM4\WooCommerceZapier\Plugin\Bookings\BookingResource;
use OM4\WooCommerceZapier\Plugin\Bookings\Plugin as BookingsPlugin;
use OM4\WooCommerceZapier\Plugin\Bookings\V1Controller as BookingsV1Controller;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Controller as SubscriptionsController;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Plugin as SubscriptionsPlugin;
use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionResource;
use OM4\WooCommerceZapier\Plugin\Subscriptions\V1Controller as SubscriptionsV1Controller;
use OM4\WooCommerceZapier\Privacy;
use OM4\WooCommerceZapier\SystemStatus\UI as SystemStatusUI;
use OM4\WooCommerceZapier\TaskHistory\Installer as TaskHistoryInstaller;
use OM4\WooCommerceZapier\TaskHistory\Listener\TriggerListener;
use OM4\WooCommerceZapier\TaskHistory\ListTable;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use OM4\WooCommerceZapier\TaskHistory\UI as TaskHistoryUI;
use OM4\WooCommerceZapier\Vendor\League\Container\Container;
use OM4\WooCommerceZapier\Webhook\DataStore as WebhookDataStore;
use OM4\WooCommerceZapier\Webhook\DeliveryFilter as WebhookDeliveryFilter;
use OM4\WooCommerceZapier\Webhook\Installer as WebhookInstaller;
use OM4\WooCommerceZapier\Webhook\Resources;
use OM4\WooCommerceZapier\Webhook\TopicsRetriever as WebhookTopicsRetriever;
use OM4\WooCommerceZapier\WooCommerceResource\Coupon\Controller as CouponController;
use OM4\WooCommerceZapier\WooCommerceResource\Coupon\CouponResource;
use OM4\WooCommerceZapier\WooCommerceResource\Customer\Controller as CustomerController;
use OM4\WooCommerceZapier\WooCommerceResource\Customer\CustomerResource;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;
use OM4\WooCommerceZapier\WooCommerceResource\Order\Controller as OrderController;
use OM4\WooCommerceZapier\WooCommerceResource\Order\OrderResource;
use OM4\WooCommerceZapier\WooCommerceResource\Product\Controller as ProductController;
use OM4\WooCommerceZapier\WooCommerceResource\Product\Price\Controller as ProductPriceController;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductResource;
use OM4\WooCommerceZapier\WooCommerceResource\Product\Stock\Controller as ProductStockController;
use OM4\WooCommerceZapier\WooCommerceResource\Product\WCVariationController;
use WC_Webhook_Data_Store;

defined( 'ABSPATH' ) || exit;

/**
 * Dependency Injection Container Service for WooCommerce Zapier.
 *
 * @since 2.0.0
 */
class ContainerService {

	/**
	 * Dependency Injection Container instance.
	 *
	 * @see https://container.thephpleague.com/2.x/ for documentation.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor, initialises our container.
	 */
	public function __construct() {

		$this->container = new Container();

		$this->register();
	}

	/**
	 * Register classes into the container.
	 * For registering classes, we use callback functions here so that the
	 * classes are only instantiated when first requested from the Container.
	 * So we can register it alphabetically.
	 *
	 * @return void
	 */
	protected function register() {
		$this->container->add(
			AdminUI::class,
			function () {
				return new AdminUI(
					$this->get( TaskHistoryUI::class ),
					$this->get( Settings::class ),
					$this->get( SystemStatusUI::class )
				);
			}
		);

		$this->container->add(
			API::class,
			function() {
				return new API(
					$this->get( FeatureChecker::class ),
					$this->get( ResourceManager::class ),
					$this->get( HTTPHeaders::class ),
					$this
				);
			}
		);

		$this->container->add(
			FeatureChecker::class,
			function() {
				return new FeatureChecker();
			}
		);

		$this->container->add(
			HTTPHeaders::class,
			function() {
				return new HTTPHeaders();
			}
		);

		$this->container->add(
			Installer::class,
			function() {
				return new Installer(
					$this->get( Logger::class )
				);
			}
		);

		$this->container->add(
			ExistingUserUpgrade::class,
			function() {
				return new ExistingUserUpgrade(
					$this->get( Logger::class ),
					$this->get( Settings::class ),
					$this->get( WordPressDB::class ),
					$this->get( UpgradedToVersion2Notice::class ),
					$this->get( LegacyFeedsDeletedNotice::class )
				);
			}
		);

		$this->container->add(
			UpgradedToVersion2Notice::class,
			function () {
				return new UpgradedToVersion2Notice(
					$this->get( Logger::class ),
					$this->get( Settings::class ),
					$this->get( AdminUI::class )
				);
			}
		);

		$this->container->add(
			LegacyFeedsDeletedNotice::class,
			function () {
				return new LegacyFeedsDeletedNotice(
					$this->get( Logger::class ),
					$this->get( Settings::class ),
					$this->get( AdminUI::class )
				);
			}
		);

		$this->container->add(
			NewUser::class,
			function () {
				return new NewUser(
					$this->get( Settings::class ),
					$this->get( TaskDataStore::class ),
					$this->get( KeyDataStore::class ),
					$this->get( NewUserWelcomeNotice::class )
				);
			}
		);

		$this->container->add(
			NewUserWelcomeNotice::class,
			function () {
				return new NewUserWelcomeNotice(
					$this->get( Logger::class ),
					$this->get( Settings::class ),
					$this->get( AdminUI::class )
				);
			}
		);

		$this->container->addShared(
			ResourceManager::class,
			function () {
				return new ResourceManager(
					$this
				);
			}
		);

		// Resource: Customer.
		$this->container->add(
			CustomerResource::class,
			function() {
				return new CustomerResource();
			}
		);

		$this->container->add(
			CustomerController::class,
			function() {
				return new CustomerController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		// Resource: Coupon.
		$this->container->add(
			CouponResource::class,
			function() {
				return new CouponResource(
					$this->get( FeatureChecker::class )
				);
			}
		);

		$this->container->add(
			CouponController::class,
			function() {
				return new CouponController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		// Resource: Order.
		$this->container->add(
			OrderResource::class,
			function() {
				return new OrderResource();
			}
		);

		$this->container->add(
			OrderController::class,
			function() {
				return new OrderController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		// Resource: Product.
		$this->container->add(
			ProductResource::class,
			function() {
				return new ProductResource();
			}
		);

		$this->container->add(
			ProductController::class,
			function() {
				return new ProductController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class ),
					$this->get( WCVariationController::class )
				);
			}
		);

		$this->container->add(
			ProductPriceController::class,
			function() {
				return new ProductPriceController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		$this->container->add(
			ProductStockController::class,
			function() {
				return new ProductStockController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		$this->container->add(
			WCVariationController::class,
			function() {
				return new WCVariationController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		// Bookings.
		$this->container->add(
			BookingsPlugin::class,
			function() {
				return new BookingsPlugin(
					$this->get( FeatureChecker::class ),
					$this->get( Logger::class )
				);
			}
		);

		$this->container->add(
			BookingResource::class,
			function() {
				return new BookingResource(
					$this->get( BookingsV1Controller::class ),
					$this->get( FeatureChecker::class )
				);
			}
		);

		$this->container->add(
			BookingsV1Controller::class,
			function() {
				return new BookingsV1Controller(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		// Subscriptions.
		$this->container->add(
			SubscriptionsPlugin::class,
			function() {
				return new SubscriptionsPlugin(
					$this->get( FeatureChecker::class ),
					$this->get( Logger::class ),
					$this->get( SubscriptionResource::class )
				);
			}
		);

		$this->container->add(
			SubscriptionResource::class,
			function() {
				return new SubscriptionResource(
					$this->get( FeatureChecker::class )
				);
			}
		);

		$this->container->add(
			SubscriptionsController::class,
			function() {
				return new SubscriptionsController(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		$this->container->add(
			SubscriptionsV1Controller::class,
			function() {
				return new SubscriptionsV1Controller(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		$this->container->add(
			KeyDataStore::class,
			function () {
				return new KeyDataStore(
					$this->get( WordPressDB::class )
				);
			}
		);

		$this->container->add(
			AuthKeyRotator::class,
			function () {
				return new AuthKeyRotator(
					$this->get( KeyDataStore::class ),
					$this->get( Logger::class )
				);
			}
		);

		$this->container->add(
			ListTable::class,
			function () {
				return new ListTable(
					$this->get( TaskDataStore::class ),
					$this->get( Resources::class ),
					$this->get( FeatureChecker::class ),
					$this->get( ResourceManager::class )
				);
			}
		);

		$this->container->add(
			Logger::class,
			function () {
				return new Logger(
					$this->get( Settings::class )
				);
			}
		);

		$this->container->add(
			PingController::class,
			function() {
				return new PingController(
					$this->get( Logger::class )
				);
			}
		);

		$this->container->add(
			Plugin::class,
			function() {
				return new Plugin(
					$this
				);
			}
		);

		$this->container->add(
			Privacy::class,
			function() {
				return new Privacy();
			}
		);

		$this->container->add(
			SystemStatusUI::class,
			function() {
				return new SystemStatusUI(
					$this->get( Settings::class ),
					$this->get( TaskDataStore::class ),
					$this->get( KeyDataStore::class ),
					$this->get( WebhookDataStore::class ),
					$this->get( Installer::class )
				);
			}
		);

		$this->container->add(
			SessionAuthenticate::class,
			function() {
				return new SessionAuthenticate(
					$this->get( KeyDataStore::class ),
					$this->get( Logger::class )
				);
			}
		);

		$this->container->add(
			TaskDataStore::class,
			function () {
				return new TaskDataStore(
					$this->get( WordPressDB::class )
				);
			}
		);

		$this->container->add(
			TaskHistoryInstaller::class,
			function () {
				return new TaskHistoryInstaller(
					$this->get( Logger::class ),
					$this->get( WordPressDB::class ),
					$this->get( TaskDataStore::class )
				);
			}
		);

		$this->container->add(
			TaskHistoryUI::class,
			function() {
				return new TaskHistoryUI(
					$this,
					$this->get( ResourceManager::class )
				);
			}
		);

		$this->container->add(
			TriggerListener::class,
			function () {
				return new TriggerListener(
					$this->get( Logger::class ),
					$this->get( TaskDataStore::class ),
					$this->get( Resources::class )
				);
			}
		);

		$this->container->add(
			Settings::class,
			function() {
				return new Settings();
			}
		);

		$this->container->add(
			WebhookDataStore::class,
			function() {
				return new WebhookDataStore( new WC_Webhook_Data_Store() );
			}
		);

		$this->container->add(
			WebhookDeliveryFilter::class,
			function() {
				return new WebhookDeliveryFilter(
					$this->get( HTTPHeaders::class ),
					$this->get( Logger::class )
				);
			}
		);

		$this->container->add(
			WebhookInstaller::class,
			function () {
				return new WebhookInstaller(
					$this->get( Logger::class ),
					$this->get( WebhookDataStore::class )
				);
			}
		);

		$this->container->add(
			WebhookController::class,
			function() {
				return new WebhookController( $this->get( ResourceManager::class ) );
			}
		);

		$this->container->add(
			WebhookTopicsController::class,
			function() {
				return new WebhookTopicsController(
					$this->get( Resources::class ),
					$this->get( ResourceManager::class )
				);
			}
		);

		$this->container->add(
			Resources::class,
			function() {
				return new Resources(
					$this->get( WebhookTopicsRetriever::class ),
					$this->get( ResourceManager::class )
				);
			}
		);

		$this->container->add(
			WebhookTopicsRetriever::class,
			function() {
				return new WebhookTopicsRetriever();
			}
		);

		$this->container->add(
			WordPressDB::class,
			function() {
				return new WordPressDB();
			}
		);
	}

	/**
	 * Retrieve an item/service from the container.
	 *
	 * @template T of object
	 * @param class-string<T> $alias Class name/alias.
	 * @return T
	 */
	public function get( $alias ) {
		return $this->container->get( $alias );
	}

}
