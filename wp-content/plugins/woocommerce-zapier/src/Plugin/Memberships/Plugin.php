<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Memberships;

use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Base;
use OM4\WooCommerceZapier\Plugin\Memberships\Plan\MembershipPlanResource;
use OM4\WooCommerceZapier\Plugin\Memberships\User\UserMembershipResource;
use WC_Memberships;
use WC_Memberships_Loader;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality that is enabled when the WooCommerce Memberships plugin is active.
 *
 * @since 2.10.0
 */
class Plugin extends Base {

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * ContainerService instance.
	 *
	 * @var ContainerService
	 */
	protected $container;

	/**
	 * Name of the third party plugin.
	 */
	const PLUGIN_NAME = 'WooCommerce Memberships';

	/**
	 * The minimum WooCommerce Memberships version that this plugin supports.
	 */
	const MINIMUM_SUPPORTED_VERSION = '1.25.0';

	/**
	 * Constructor.
	 *
	 * @param FeatureChecker   $checker FeatureChecker instance.
	 * @param Logger           $logger Logger instance.
	 * @param ContainerService $container ContainerService instance.
	 */
	public function __construct( FeatureChecker $checker, Logger $logger, ContainerService $container ) {
		$this->checker     = $checker;
		$this->logger      = $logger;
		$this->container   = $container;
		$this->resources[] = MembershipPlanResource::class;
		$this->resources[] = UserMembershipResource::class;
	}

	/**
	 * Get the WooCommerce Memberships version number.
	 *
	 * @return string
	 */
	public function get_plugin_version() {
		return WC_Memberships::VERSION;
	}

	/**
	 * Whether the user has the WooCommerce Memberships plugin active.
	 *
	 * Also ensures that Memberships' minimum supported PHP version is met.
	 *
	 * @see WC_Memberships_Loader::is_environment_compatible()
	 *
	 * @return bool
	 */
	protected function is_active() {
		return $this->checker->class_exists( WC_Memberships_Loader::class ) &&
			\is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) &&
				\version_compare( PHP_VERSION, WC_Memberships_Loader::MINIMUM_PHP_VERSION, '>=' );
	}
}
