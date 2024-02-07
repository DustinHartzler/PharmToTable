<?php

declare(strict_types=1);

namespace OM4\WooCommerceZapier\Plugin\Memberships\Plan;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use SkyVerge\WooCommerce\Memberships\API\v3\Membership_Plans;
use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

/**
 * Exposes WooCommerce Memberships' REST API v3 Membership Plan endpoint via the WooCommerce Zapier endpoint namespace.
 *
 * @since 2.10.0
 */
class Controller extends Membership_Plans {

	use APIListenerTrait;

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'membership_plan';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * MembershipsPlanTaskCreator instance.
	 *
	 * @var MembershipPlanTaskCreator
	 */
	protected $task_creator;

	/**
	 * Constructor.
	 *
	 * @param  Logger                    $logger       Logger instance.
	 * @param  MembershipPlanTaskCreator $task_creator TaskCreator instance.
	 */
	public function __construct( Logger $logger, MembershipPlanTaskCreator $task_creator ) {
		parent::__construct();
		$this->logger       = $logger;
		$this->task_creator = $task_creator;
		$this->namespace    = 'wc-zapier/v1/memberships';
		$this->add_filter_to_check_for_request_validation_error();
	}

	/**
	 * Get the query params for Membership Plan list/search.
	 *
	 * - Adds new `name` parameter.
	 * - Removes the `search` parameter.
	 * - Adds in missing `order`/`orderby` parameters to make it consistent with other resources.
	 * - Preserves all other existing parameters.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$default_params = parent::get_collection_params();

		$params['context'] = $default_params['context'];
		unset( $default_params['context'] );

		$params['name'] = array(
			'description'       => __( 'Limit results to those with the specified plan name.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'rest_validate_request_arg',
		);

		unset( $default_params['search'] );

		// WooCommerce Memberships supports the `order` and `orderby` parameters, but doesn't document them in the schema.
		$params['order']   = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array( 'asc', 'desc' ),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['orderby'] = array(
			'description'       => __( 'Sort collection by object attribute.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'default'           => 'date',
			'enum'              => array(
				'date',
				'id',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);

		return array_merge( $params, $default_params );
	}

	/**
	 * Implement the `name` query parameter in the list/search endpoint, allowing users to search by Plan Name.
	 *
	 * @param  array           $prepared_args  Prepared arguments.
	 * @param  WP_REST_Request $request  Request object.
	 *
	 * @return array          $query_args
	 */
	protected function prepare_items_query( $prepared_args = array(), $request = \null ) {
		$query_args = parent::prepare_items_query( $prepared_args, $request );
		if ( isset( $request['name'] ) && ! empty( $request['name'] ) ) {
			$query_args['title'] = $request['name'];
		}

		return $query_args;
	}
}
