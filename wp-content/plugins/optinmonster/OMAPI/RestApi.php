<?php
/**
 * Rest API Class, where we register/execute any REST API Routes
 *
 * @since 1.8.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rest Api class.
 *
 * @since 1.8.0
 */
class OMAPI_RestApi {

	/**
	 * The Base OMAPI Object
	 *
	 *  @since 1.8.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	/**
	 * The REST API Namespace
	 *
	 *  @since 1.8.0
	 *
	 * @var string The namespace
	 */
	protected $namespace = 'omapp/v1';

	/**
	 * Whether Access-Control-Allow-Headers header was set/updated by us.
	 *
	 *  @since 1.9.12
	 *
	 * @var bool
	 */
	protected $allow_header_set = false;

	/**
	 * Build our object.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		$this->base = OMAPI::get_instance();
		$this->register_rest_routes();
	}

	/**
	 * Registers our Rest Routes for this App
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {

		// Filter only available in WP 5.5.
		add_filter( 'rest_allowed_cors_headers', array( $this, 'set_allow_headers' ), 999 );

		// Fall-through to check if we still need to set header (WP < 5.5)
		add_filter( 'rest_send_nocache_headers', array( $this, 'fallback_set_allow_headers' ), 999 );

		// Fetch some quick info about this WP installation.
		register_rest_route(
			$this->namespace,
			'info',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'output_info' ),
			)
		);

		// Fetch in-depth support info about this WP installation.
		register_rest_route(
			$this->namespace,
			'support',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'support_info' ),
			)
		);

		// Route for triggering refreshing/syncing of all campaigns.
		// TODO: Keeping for future settings revamp.
		register_rest_route(
			$this->namespace,
			'/campaigns/refresh',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'has_valid_api_key' ),
				'callback'            => array( $this, 'refresh_campaigns' ),
			)
		);

		// Route for fetching the campaign data for specific campaign.
		// TODO: Keeping for future settings revamp.
		register_rest_route(
			$this->namespace,
			'/campaigns/(?P<id>\w+)',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_campaign_data' ),
			)
		);

		// Route for updating the campaign data.
		// TODO: Keeping for future settings revamp.
		register_rest_route(
			$this->namespace,
			'/campaigns/(?P<id>\w+)',
			array(
				'methods'             => 'PUT',
				'permission_callback' => array( $this, 'has_valid_api_key' ),
				'callback'            => array( $this, 'update_campaign_data' ),
			)
		);

		// Route for triggering refreshing/syncing of a single campaign.
		// TODO: Keeping for future settings revamp.
		register_rest_route(
			$this->namespace,
			'/campaigns/(?P<id>[\w-]+)/sync',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'has_valid_api_key' ),
				'callback'            => array( $this, 'sync_campaign' ),
			)
		);

		// Route for fetching the campaign data.
		// TODO: Keeping for future settings revamp.
		register_rest_route(
			$this->namespace,
			'/campaign-dashboard',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_campaigns_data' ),
			)
		);
	}

	/**
	 * Filters the list of request headers that are allowed for CORS requests,
	 * and ensures our API key is allowed.
	 *
	 * @since 1.9.12
	 *
	 * @param string[] $allow_headers The list of headers to allow.
	 *
	 * @return string[]
	 */
	public function set_allow_headers( $allow_headers ) {
		$allow_headers[] = 'X-OptinMonster-ApiKey';
		$this->allow_header_set = true;

		// remove fall-through.
		remove_filter( 'rest_send_nocache_headers', array( $this, 'fallback_set_allow_headers' ), 999 );

		return $allow_headers;
	}

	/**
	 * Fallback to make sure we set the allow headers.
	 *
	 * @since  1.9.12
	 *
	 * @param bool $rest_send_nocache_headers Whether to send no-cache headers.
	 *                                        We ignore this, because we're simply using this
	 *                                        as an action hook.
	 *
	 * @return bool Unchanged result.
	 */
	function fallback_set_allow_headers( $rest_send_nocache_headers ) {
		if ( ! $this->allow_header_set && ! headers_sent() ) {
			foreach ( headers_list() as $header ) {
				if ( 0 === strpos( $header, 'Access-Control-Allow-Headers: ' ) ) {

					list( $key, $value ) = explode( 'Access-Control-Allow-Headers: ', $header );
					if ( false === strpos( $value, 'X-OptinMonster-ApiKey' ) ) {
						header( 'Access-Control-Allow-Headers: ' . $value . ', X-OptinMonster-ApiKey' );
					}

					$this->allow_header_set = true;
					break;
				}
			}
		}

		return $rest_send_nocache_headers;
	}

	/**
	 * Triggers refreshing our campaigns.
	 *
	 * @since 1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function refresh_campaigns( $request ) {
		$this->base->refresh->refresh();

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Fetch some quick info about this WP installation
	 * (WP version, plugin version, rest url, home url, WooCommerce version)
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response
	 */
	public function output_info( $request ) {
		return new WP_REST_Response( $this->base->refresh->get_info_args(), 200 );
	}

	/**
	 * Fetch in-depth support info about this WP installation.
	 * Used for the debug PDF, but can also be requested by support staff with the right api key.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response
	 */
	public function support_info( $request ) {
		$support = new OMAPI_Support();

		$format = $request->get_param( 'format' );
		if ( empty( $format ) ) {
			$format = 'raw';
		}

		return new WP_REST_Response( $support->get_support_data( $format ), 200 );
	}

	/**
	 * Triggering refreshing/syncing of a single campaign.
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function sync_campaign( $request ) {
		$campaign_id = $request->get_param( 'id' );

		if ( empty( $campaign_id ) ) {
			return new WP_REST_Response(
				array( 'message' => esc_html__( 'No campaign ID given.', 'optin-monster-api' ) ),
				400
			);
		}

		$this->base->refresh->sync( $campaign_id, $request->get_param( 'legacy' ) );

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Gets all the data needed for the campaign dashboard for a given campaign.
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function get_campaign_data( $request ) {
		$campaign_id = $request->get_param( 'id' );

		if ( empty( $campaign_id ) ) {
			return new WP_REST_Response(
				array( 'message' => esc_html__( 'No campaign ID given.', 'optin-monster-api' ) ),
				400
			);
		}

		$campaign = $this->base->get_optin_by_slug( $campaign_id );
		if ( empty( $campaign->ID ) ) {
			return new WP_REST_Response(
				array(
					/* translators: %s: the campaign post id. */
					'message' => sprintf( esc_html__( 'Could not find campaign by given ID: %s.', 'optin-monster-api' ), $campaign_id ),
				),
				404
			);
		}

		// Get Campaigns Data.
		$data = $this->base->collect_campaign_data( $campaign );
		$data = apply_filters( 'optin_monster_api_setting_ui_data_for_campaign', $data, $campaign );

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Updates data for given campaign.
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function update_campaign_data( $request ) {
		$campaign_id = $request->get_param( 'id' );

		// If no campaign_id, return error.

		$campaign = $this->base->get_optin_by_slug( $campaign_id );

		// If no campaign, return 404.

		// Get the Request Params.
		$fields = json_decode( $request->get_body(), true );

		if ( ! empty( $fields['taxonomies'] ) ) {

			if ( isset( $fields['taxonomies']['categories'] ) ) {
				$fields['categories'] = $fields['taxonomies']['categories'];
			}

			// Save the data from the regular taxonomies fields into the WC specific tax field.
			// For back-compatibility.
			$fields['is_wc_product_category'] = isset( $fields['taxonomies']['product_cat'] )
				? $fields['taxonomies']['product_cat']
				: array();
			$fields['is_wc_product_tag']      = isset( $fields['taxonomies']['product_tag'] )
				? $fields['taxonomies']['product_tag']
				: array();
		}

		// Escape Parameters as needed.
		// Update Post Meta.
		foreach ( $fields as $key => $value ) {
			$value = $this->sanitize( $value );

			switch ( $key ) {
				default:
					update_post_meta( $campaign->ID, '_omapi_' . $key, $value );
			}
		}

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Gets all the data needed for the campaigns.
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function get_campaigns_data( $request ) {
		global $wpdb;

		if ( $request->get_param( 'refresh' ) ) {
			$this->base->refresh->refresh();
		}

		// Get Campaigns Data.
		$campaigns     = $this->base->get_optins();
		$campaigns     = ! empty( $campaigns ) ? $campaigns : array();
		$campaign_data = array();

		foreach ( $campaigns as $campaign ) {
			$campaign_data[] = $this->base->collect_campaign_data( $campaign );
		}

		$woo = $this->base->is_woocommerce_active();
		$mp  = $this->base->is_mailpoet_active();

		// Get Taxonomies Data.
		$taxonomies                 = get_taxonomies( array( 'public' => true ), 'objects' );
		$taxonomies                 = apply_filters( 'optin_monster_api_setting_ui_taxonomies', $taxonomies );
		$taxonomy_map               = array();
		$cats                       = get_categories();
		$taxonomy_map['categories'] = array(
			'name'  => 'categories',
			'label' => 'Post categories',
			'terms' => is_array( $cats ) ? array_values( $cats ) : array(),
			'wc'    => false,
		);

		$ignore = array(
			'category' => 1,
		);
		foreach ( $taxonomies as $taxonomy ) {
			if ( isset( $ignore[ $taxonomy->name ] ) ) {
				continue;
			}
			$terms                           = get_terms(
				array(
					'taxonomy' => $taxonomy->name,
					'get'      => 'all',
				)
			);
			$taxonomy_map[ $taxonomy->name ] = array(
				'name'  => $taxonomy->name,
				'label' => ucwords( $taxonomy->label ),
				'terms' => is_array( $terms ) ? array_values( $terms ) : array(),
				'wc'    => $woo && 0 === strpos( $taxonomy->name, 'product_' ),
			);
		}

		// Get "Config" data.
		$config = array(
			'hasMailPoet'    => $mp,
			'hasWooCommerce' => $woo,
			'mailPoetLists'  => $mp ? $this->base->mailpoet->get_lists() : array(),
		);

		// Posts query.
		$post_types = sanitize_text_field( implode( '","', get_post_types( array( 'public' => true ) ) ) );
		$posts      = $wpdb->get_results( "SELECT ID AS `value`, post_title AS `name` FROM {$wpdb->prefix}posts WHERE post_type IN (\"{$post_types}\") AND post_status IN('publish', 'future') ORDER BY post_title ASC", ARRAY_A );

		$response_data = apply_filters(
			'optin_monster_api_setting_ui_data',
			array(
				'campaigns'  => $campaign_data,
				'taxonomies' => $taxonomy_map,
				'config'     => $config,
				'posts'      => $posts,
				'post_types' => array_values( get_post_types( array( 'public' => true ), 'object' ) ),
			)
		);

		return new WP_REST_Response( $response_data, 200 );
	}

	/**
	 * Sanitize value recursively.
	 *
	 * @since  1.9.10
	 *
	 * @param  mixed $value The value to sanitize.
	 *
	 * @return mixed The sanitized value.
	 */
	public function sanitize( $value ) {
		if ( empty( $value ) ) {
			return $value;
		}

		if ( is_scalar( $value ) ) {
			return sanitize_text_field( $value );
		}

		if ( is_array( $value ) ) {
			return array_map( array( $this, 'sanitize' ), $value );
		}
	}

	/**
	 * Determine if OM API key is provided and valid.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function has_valid_api_key( $request ) {
		$header = $request->get_header( 'X-OptinMonster-ApiKey' );

		// Use this API Key to validate.
		if ( ! $this->validate_api_key( $header ) ) {
			return new WP_Error(
				'omapp_rest_forbidden',
				esc_html__( 'Could not verify your API Key.', 'optin-monster-api' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Determine if logged in or OM API key is provided and valid.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function logged_in_or_has_api_key( $request ) {
		return is_user_logged_in() || true === $this->has_valid_api_key( $request );
	}

	/**
	 * Validate this API Key
	 * We validate an API Key by fetching the Sites this key can fetch
	 * And then confirming that this key has access to at least one of these sites
	 *
	 * @since 1.8.0
	 *
	 * @param string $api_key The OM api key.
	 *
	 * @return bool True if the Key can be validated
	 */
	public function validate_api_key( $api_key ) {
		if ( empty( $api_key ) ) {
			return false;
		}

		$site_ids = $this->base->get_site_ids();

		if ( empty( $site_ids ) ) {
			return false;
		}

		$api_key_sites = $this->base->sites->fetch( $api_key );

		if ( is_wp_error( $api_key_sites ) || empty( $api_key_sites['siteIds'] ) ) {
			return false;
		}

		foreach ( $site_ids as $site_id ) {
			if ( in_array( $site_id, $api_key_sites['siteIds'] ) ) {
				return true;
			}
		}

		return false;
	}
}
