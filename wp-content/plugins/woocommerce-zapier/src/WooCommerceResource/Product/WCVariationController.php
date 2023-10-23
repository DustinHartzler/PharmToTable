<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Product;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductTaskCreator;
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
	 * ProductTaskCreator instance.
	 *
	 * @var ProductTaskCreator
	 */
	protected $task_creator;

	/**
	 * Constructor.
	 *
	 * Not calling parent constructor to avoid add_filter() call in \WC_REST_Products_V2_Controller::__construct().
	 *
	 * @param  Logger             $logger        Logger instance.
	 * @param  ProductTaskCreator $task_creator  ProductTaskCreator instance.
	 */
	public function __construct( Logger $logger, ProductTaskCreator $task_creator ) {
		$this->logger       = $logger;
		$this->task_creator = $task_creator;
	}
}
