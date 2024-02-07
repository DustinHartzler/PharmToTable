<?php

namespace OM4\WooCommerceZapier\Plugin\Subscriptions\Note;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Helper\WordPressDB;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\TaskHistory\Task\Event;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\SubscriptionNote;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Note\SubscriptionNoteTaskCreator;
use WC_REST_Subscription_notes_Controller;
use WC_REST_Subscription_Notes_V1_Controller;
use WP_Comment;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * Exposes Woo Subscriptions' REST API v3 Subscription Notes endpoint via the WooCommerce Zapier endpoint namespace.
 *
 * @since 2.9.0
 */
class Controller extends WC_REST_Subscription_notes_Controller {

	use APIListenerTrait;

	/**
	 * Whether our hooks have been added.
	 * This is used to ensure that our hooks are only added once, even if this class is instantiated multiple times.
	 *
	 * @var bool
	 */
	protected static $hooks_added = false;

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = API::REST_NAMESPACE;

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'subscriptions/notes';

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'subscription_note';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * SubscriptionTaskCreator instance.
	 *
	 * @var SubscriptionNoteTaskCreator
	 */
	protected $task_creator;

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * WordPressDB instance.
	 *
	 * @var WordPressDB
	 */
	protected $wp_db;

	/**
	 * Constructor.
	 *
	 * @param  Logger                      $logger      Logger instance.
	 * @param SubscriptionNoteTaskCreator $task_creator SubscriptionTaskCreator instance.
	 * @param  FeatureChecker              $checker FeatureChecker instance.
	 * @param  WordPressDB                 $wp_db WordPressDB instance.
	 */
	public function __construct(
		Logger $logger,
		SubscriptionNoteTaskCreator $task_creator,
		FeatureChecker $checker,
		WordPressDB $wp_db
	) {
		$this->logger       = $logger;
		$this->task_creator = $task_creator;
		$this->checker      = $checker;
		$this->wp_db        = $wp_db;

		if ( ! self::$hooks_added ) {
			add_filter( 'rest_post_dispatch', array( $this, 'rest_post_dispatch' ), 10, 3 );
			$this->add_filter_to_check_for_request_validation_error();
			self::$hooks_added = true;
		}
	}

	/**
	 * Override collection args for GET (list/search).
	 * These are the input fields displayed in the Find Subscription Note action.
	 *
	 * Override the args for POST (create).
	 * These are the input fields displayed in the Create Subscription Note action.
	 *
	 * Executed during the `rest_post_dispatch` filter because if the metadata is added via
	 * get_endpoint_args_for_item_schema(), it is removed by WordPress.
	 *
	 * @param WP_REST_Response $result  Result to send to the client.
	 * @param WP_REST_Server   $server  Server instance.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 *
	 * @return WP_HTTP_Response
	 */
	public function rest_post_dispatch( $result, $server, $request ) {
		if ( 'OPTIONS' !== $request->get_method() || 0 !== \strpos( $result->get_matched_route(), '/' . $this->namespace . '/' . $this->rest_base ) ) {
			return $result;
		}

		/**
		 * Schema data.
		 *
		 * @var array $data
		 */
		$data = $result->get_data();

		// List/Search Input Fields.
		$data = $this->modify_schema_args( 'GET', $data );

		// Create Input Fields.
		$data = $this->modify_schema_args( 'POST', $data );

		$result->set_data( $data );
		return $result;
	}

	/**
	 * Modify the schema args for the Subscription Notes endpoint.
	 *
	 * @param string $method  HTTP method of the request.
	 * @param array  $data    Schema data.
	 *
	 * @return array
	 */
	protected function modify_schema_args( $method, $data ) {
		$key = \array_search( $method, $data['methods'], true );
		if ( false === $key ) {
			return $data;
		}

		$message       = 'GET' === $method ?
			__( 'Limit results to those matching this Subscription ID.', 'woocommerce-zapier' ) :
			__( 'The Subscription ID to add the note to.', 'woocommerce-zapier' );
		$required      = 'GET' === $method ? false : true;
		$existing_args = $data['endpoints'][ $key ]['args'];

		$data['endpoints'][ $key ]['args'] = array(
			'subscription_id' => array(
				'description' => $message,
				'type'        => 'integer',
				'required'    => $required,
			),
		);
		unset( $existing_args['order_id'] );
		$data['endpoints'][ $key ]['args'] += $existing_args;

		return $data;
	}

	/**
	 * Ensure `subscription_id` is required when creating a new subscription note.
	 *
	 * @see WC_REST_Subscription_Notes_V1_Controller::register_routes()
	 *
	 * @param string $method Optional. HTTP method of the request.
	 *
	 * @return array Endpoint arguments.
	 */
	public function get_endpoint_args_for_item_schema( $method = WP_REST_Server::CREATABLE ) {
		$args = parent::get_endpoint_args_for_item_schema( $method );
		if ( WP_REST_Server::CREATABLE === $method ) {
			$args['subscription_id'] = array(
				'type'        => 'integer',
				'description' => __( 'The Subscription ID to add the note to.', 'woocommerce-zapier' ),
				'required'    => true,
			);
			unset( $args['order_id'] );
		}
		return $args;
	}

	/**
	 * Get the query params for collections of subscription notes.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();
		/**
		 * Logic based on WC_REST_CRUD_Controller's standard collection params.
		 *
		 * @see WC_REST_CRUD_Controller::get_collection_params()
		 */
		$params['search']   = array(
			'description'       => __( 'Limit results to those matching a string.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['page']     = array(
			'description'       => __( 'Current page of the collection.', 'woocommerce-zapier' ),
			'type'              => 'integer',
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'minimum'           => 1,
		);
		$params['per_page'] = array(
			'description'       => __( 'Maximum number of items to be returned in result set.', 'woocommerce-zapier' ),
			'type'              => 'integer',
			'default'           => 10,
			'minimum'           => 1,
			'maximum'           => 100,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['order']    = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array( 'asc', 'desc' ),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['orderby']  = array(
			'description'       => __( 'Sort collection by object attribute.', 'woocommerce-zapier' ),
			'type'              => 'string',
			'default'           => 'date',
			'enum'              => array( 'date', 'id' ),
			'validate_callback' => 'rest_validate_request_arg',
		);
		return $params;
	}

	/**
	 * Add `subscription_id` property to the existing schema definition, because we add this data to all REST API responses.
	 *
	 * @see self::woocommerce_rest_prepare_subscription_note()
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema     = parent::get_item_schema();
		$properties = array(
			'id'              => $schema['properties']['id'],
			'subscription_id' => array(
				'description' => __( 'Subscription ID', 'woocommerce-zapier' ),
				'type'        => 'integer',
				'context'     => array( 'view', 'edit' ),
				'required'    => false,
			),
		);
		unset( $schema['properties']['id'] );
		unset( $schema['properties']['order_id'] );
		$properties          += $schema['properties'];
		$schema['properties'] = $properties;

		return $schema;
	}


	/**
	 * Prepare a single subscription note output for response.
	 *
	 * Adding an `subscription_id` property to the response.
	 *
	 * @param WP_Comment      $note    Subscription note object.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response( $note, $request ) {
		$response    = parent::prepare_item_for_response( $note, $request );
		$parent_data = $response->get_data();
		if ( ! is_array( $parent_data ) ) {
			return $response;
		}
		$data = array(
			'id'              => $parent_data['id'],
			'subscription_id' => intval( $note->comment_post_ID ),
		);
		unset( $parent_data['id'] );
		$data += $parent_data;
		$response->set_data( $data );
		return $response;
	}

	/**
	 * Get subscription notes (search/list).
	 *
	 * Needed because the Woo Subscriptions Notes Controller only supports getting notes for a specific subscription.
	 *
	 * @see WC_REST_Order_Notes_V2_Controller::get_items()
	 *
	 * @param WP_REST_Request $request Request data.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {

		// Pagination/sorting.
		$args = array(
			'number' => intval( $request['per_page'] ),
			'order'  => $request['order'],
			'paged'  => intval( $request['page'] ),
		);
		if ( 'id' === $request['orderby'] ) {
			$args['orderby'] = 'comment_ID';
		} else {
			$args['orderby'] = 'comment_date_gmt';
		}
		$args['search'] = $request['search'];

		// Ensure notes belong to a subscription (not other custom order types).
		if ( $this->checker->is_hpos_enabled() ) {
			// Orders are not a post type in HPOS, so we need to use a filter to ensure the notes belong to an order.
			add_filter( 'comments_clauses', array( $this, 'hpos_comment_clauses' ) );
		} else {
			$args['post_type'] = 'shop_subscription';
		}

		/**
		 * Remaining subscription note query logic is based on
		 *
		 * @see WC_REST_Order_Notes_V2_Controller::get_items(),
		 * but without the `subscription_id` arg for search/list.
		 */
		$args['approve'] = 'approve';
		$args['type']    = 'order_note';
		if ( $request['subscription_id'] ) {
			$args['post_id'] = $request['subscription_id'];
		}

		// Allow filter by subscription note type.
		if ( 'customer' === $request['type'] ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$args['meta_query'] = array(
				array(
					'key'     => 'is_customer_note',
					'value'   => 1,
					'compare' => '=',
				),
			);
		} elseif ( 'internal' === $request['type'] ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$args['meta_query'] = array(
				array(
					'key'     => 'is_customer_note',
					'compare' => 'NOT EXISTS',
				),
			);
		}

		remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

		/**
		 * Retrieved Subscription Notes.
		 *
		 * @var WP_Comment[] $notes
		 */
		$notes = get_comments( $args );

		add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

		$data = array();
		foreach ( $notes as $note ) {
			$subscription_note = $this->prepare_item_for_response( $note, $request );
			$subscription_note = $this->prepare_response_for_collection( $subscription_note );
			$data[]            = $subscription_note;
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Get a single subscription note.
	 *
	 * The parent controller needs an `subscription_id` specified, so we need to add that to the request.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$note = SubscriptionNote::find( \intval( $request['id'] ) );
		if ( ! \is_null( $note ) ) {
			$request->set_param( 'order_id', $note->subscription_id );
		}
		$response = parent::get_item( $request );
		if ( \is_wp_error( $response ) && 'woocommerce_rest_order_invalid_id' === $response->get_error_code() ) {
				// Improve the error message to be consistent with other resources.
				$response = new WP_Error(
					'woocommerce_rest_invalid_id',
					__( 'Invalid ID.', 'woocommerce-zapier' ),
					array( 'status' => 404 )
				);
		}
		return $response;
	}

	/**
	 * Create a subscription note.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response REST API Response.
	 */
	public function create_item( $request ) {
		$request->set_param( 'order_id', $request['subscription_id'] );
		$response = parent::create_item( $request );

		$subscription_id = 0;
		if ( \is_wp_error( $response ) ) {
			if ( 'woocommerce_rest_order_invalid_id' === $response->get_error_code() ) {
				// Improve the error message to be consistent with other resources.
				$response = new WP_Error(
					'woocommerce_rest_invalid_id',
					__( 'Invalid ID.', 'woocommerce-zapier' ),
					array( 'status' => 404 )
				);
			} else {
				$subscription_id = (int) $request['subscription_id'];
			}

			$this->log_error_response( $request, $response );
			$this->task_creator->record(
				Event::action_create( $this->resource_type, $response ),
				$subscription_id,
				0
			);
			return $response;
		}
		$event = Event::action_create( $this->resource_type );
		// @phpstan-ignore-next-line Structure comes from WooCommerce.
		$this->task_creator->record( $event, $response->data['subscription_id'], $response->data['id'] );
		return $response;
	}

	/**
	 * Delete a single subscription note.
	 *
	 * The parent controller needs an `subscription_id` specified, so we need to add that to the request.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function delete_item( $request ) {
		$note = SubscriptionNote::find( intval( $request['id'] ) );
		if ( ! \is_null( $note ) ) {
			$request->set_param( 'order_id', $note->subscription_id );
		}
		$response = parent::delete_item( $request );
		if ( \is_wp_error( $response ) ) {
			switch ( $response->get_error_code() ) {
				case 'woocommerce_rest_trash_not_supported':
					// Improve the error message to be consistent with other resources.
					$response = new WP_Error(
						'woocommerce_rest_trash_not_supported',
						__( 'Subscription notes do not support trashing. Please specify `force`=`true`', 'woocommerce-zapier' ),
						array( 'status' => 400 )
					);
					break;
				case 'woocommerce_rest_order_invalid_id':
					// Improve the error message to be consistent with other resources.
					$response = new WP_Error(
						'woocommerce_rest_invalid_id',
						__( 'Invalid ID.', 'woocommerce-zapier' ),
						array( 'status' => 404 )
					);
					break;
			}
			$this->log_error_response( $request, $response );
			return $response;
		}
		$this->logger->critical(
			'Unsupported REST API access on resource_id %d, resource_type %s, message: %s',
			array(
				$request['id'],
				$this->resource_type,
				__( 'Deleted via Zapier', 'woocommerce-zapier' ),
			)
		);
		return $response;
	}

	/**
	 * Ensure Notes belong to a Woo Subscriptions and not other Order types.
	 *
	 * @param  array $clauses A compacted array of comment query clauses.
	 * @return array
	 */
	public function hpos_comment_clauses( $clauses ) {
		$clauses['join']  .= " JOIN {$this->wp_db->prefix}wc_orders ON
			{$this->wp_db->prefix}wc_orders.id = {$this->wp_db->prefix}comments.comment_post_ID ";
		$clauses['where'] .= ( $clauses['where'] ? ' AND ' : '' ) .
			" {$this->wp_db->prefix}wc_orders.type = 'shop_subscription' ";
		return $clauses;
	}

	/**
	 * Modify the resource ID that is used when creating an unsuccessful Task History record.
	 *
	 * @param int              $resource_id  The resource ID.
	 * @param WP_REST_Request  $request   Request used to generate the response.
	 * @param WP_REST_Response $response  Result to send to the client.
	 *
	 * @return int
	 * @since 2.10.0
	 */
	protected function modify_resource_id( $resource_id, $request, $response ) {
		if (
			isset( $request['subscription_id'] )
			&& false !== wcs_get_subscription( (int) $request['subscription_id'] )
		) {
			return (int) $request['subscription_id'];
		}
		return $resource_id;
	}

	/**
	 * Modify the child ID that is used when creating an unsuccessful Task History record.
	 *
	 * Ensures all Subscription Note related errors have a child ID of 0, which also means a child type of `subscription_note`.
	 *
	 * @param ?int             $child_id  The resource ID.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 * @param WP_REST_Response $response  Result to send to the client.
	 *
	 * @return ?int
	 * @since 2.10.0
	 */
	protected function modify_child_id( $child_id, $request, $response ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return 0;
	}
}
