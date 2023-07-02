<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use WC_REST_Product_Variations_Controller;

/**
 * Allows the WooCommerce's REST API v3 Product Variations endpoint methods to
 * be use directly by our Products Controller.
 *
 * @see \OM4\WooCommerceZapier\WooCommerceResource\Product\Controller
 * @internal
 */
class WCVariationController extends WC_REST_Product_Variations_Controller {

	use APIListenerTrait;

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'product';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $data_store;

	/**
	 * Constructor.
	 *
	 * Not calling parent constructor to avoid add_filter() call in \WC_REST_Products_V2_Controller::__construct().
	 *
	 * @param  Logger        $logger  Logger instance.
	 * @param  TaskDataStore $data_store  TaskDataStore instance.
	 */
	public function __construct( Logger $logger, TaskDataStore $data_store ) {
		$this->logger     = $logger;
		$this->data_store = $data_store;
	}
}
