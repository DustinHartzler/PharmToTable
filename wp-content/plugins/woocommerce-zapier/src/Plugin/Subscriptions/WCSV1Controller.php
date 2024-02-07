<?php

namespace OM4\WooCommerceZapier\Plugin\Subscriptions;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionsTaskCreator;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use WC_REST_Subscriptions_V1_Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Allows the Woo Subscriptions REST API v1 Subscription endpoint methods to
 * be use directly by our V1 Subscriptions Controller.
 *
 * @since 2.7.0
 *
 * @internal
 */
class WCSV1Controller extends WC_REST_Subscriptions_V1_Controller {

	use APIListenerTrait;

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'subscription';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * SubscriptionsTaskCreator instance.
	 *
	 * @var SubscriptionsTaskCreator
	 */
	protected $task_creator;

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * Constructor.
	 *
	 * Not calling parent constructor to avoid add_filter() calls in \WC_REST_Subscriptions_V1_Controller::__construct().
	 *
	 * @param  Logger                   $logger        Logger instance.
	 * @param  SubscriptionsTaskCreator $task_creator  SubscriptionsTaskCreator instance.
	 * @param  FeatureChecker           $checker       FeatureChecker instance.
	 */
	public function __construct( Logger $logger, SubscriptionsTaskCreator $task_creator, FeatureChecker $checker ) {
		$this->logger       = $logger;
		$this->task_creator = $task_creator;
		$this->checker      = $checker;
		$this->add_filter_to_check_for_request_validation_error();
		if ( $this->checker->is_hpos_enabled() && ! $this->checker->is_hpos_in_sync() ) {
			/**
			 * HPOS saves a placeholder post type.
			 * The WC_REST_Subscriptions_V1_Controller::update_order() method uses the post type to determine if the id is valid or not.
			 */
			$this->post_type = $this->checker->hpos_placeholder_order_post_type();
		}
	}
}
