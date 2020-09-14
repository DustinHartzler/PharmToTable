<?php
// phpcs:ignoreFile

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Integration_ActiveCampaign
 * @since 2.6.1
 */
class Integration_ActiveCampaign extends Integration {

	/** @var string */
	public $integration_id = 'activecampaign';

	/** @var string */
	private $api_key;

	/** @var string */
	private $api_url;

	/** @var \ActiveCampaign */
	private $sdk;

	/** @var int */
	public $request_count = 1;

	/**
	 * Enable API debugging.
	 *
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * Constructor.
	 *
	 * @param string $api_url
	 * @param string $api_key
	 * @param bool   $debug
	 */
	function __construct( $api_url, $api_key, $debug ) {
		$this->api_url = $api_url;
		$this->api_key = $api_key;
		$this->debug   = $debug;
	}


	/**
	 * @return array
	 */
	function get_lists() {

		if ( $cache = Cache::get_transient( 'ac_lists' ) ) {
			return $cache;
		}

		$lists = $this->request( 'list/list', [ 'ids' => 'all' ] );
		$clean_lists = [];

		foreach ( $lists as $list ) {
			if ( is_object($list) ) {
				$clean_lists[$list->id] = $list->name;
			}
		}

		Cache::set_transient( 'ac_lists', $clean_lists, 0.15 );

		return $clean_lists;
	}



	/**
	 * Check is the contact exists in ActiveCampaign.
	 *
	 * Result from API is cached for 5 minutes.
	 *
	 * @param string $email
	 *
	 * @return bool
	 */
	public function is_contact( $email ) {

		$cache_key = 'aw_ac_is_contact_' . md5( $email );

		if ( $cache = get_transient( $cache_key ) ) {
			return $cache === 'yes';
		}

		$contact = $this->request( 'contact/view?email=' . urlencode( $email ) );

		$is_contact = $contact->success;

		set_transient( $cache_key, $is_contact ? 'yes': 'no', MINUTE_IN_SECONDS * 5 );

		return $is_contact;
	}


	/**
	 * @param $email
	 */
	function clear_contact_transients( $email ) {
		delete_transient( 'aw_ac_is_contact_' . md5( $email ) );
	}


	/**
	 * @return array
	 */
	function get_contact_custom_fields() {

		if ( $cache = Cache::get_transient( 'ac_contact_fields' ) ) {
			return $cache;
		}

		$response = $this->request( 'list/field/view?ids=all' );
		$fields = [];

		foreach ( $response as $item ) {
			if ( is_object($item) ) {
				$fields[ $item->id ] = $item;
			}
		}

		Cache::set_transient( 'ac_contact_fields', $fields, 0.15 );

		return $fields;
	}


	/**
	 * @param $path
	 * @param $data
	 * @return \ActiveCampaign|false
	 *
	 * @throws \Exception
	 */
	function request( $path, $data = [] ) {
		$sdk = $this->get_sdk();

		if ( ! $sdk ) {
			return false;
		}

		$this->request_count++;

		// avoid overloading the api
		if ( $this->request_count % 4 == 0 ) {
			sleep(2);
		}

		if ( $this->debug ) {
			// Switch on debugging
			$sdk->debug = true;
			ob_start();
		}

		try {
			$response = $sdk->api( $path, $data );
		} catch ( \Exception $e ) {
			if ( $this->debug ) {
				// Unfortunately, the wrapper outputs the debugging info as HTML
				Logger::error( 'active-campaign', ob_get_clean() );
			}

			// Rethrow for workflow logs
			throw $e;
		} finally {
			$sdk->debug = false;
		}

		if ( $this->debug ) {
			ob_clean();
		}

		return $response;
	}


	/**
	 * @return \ActiveCampaign
	 */
	protected function get_sdk() {
		if ( ! isset( $this->sdk ) ) {

			if ( ! class_exists( '\ActiveCampaign' ) ) {
				require_once AW()->lib_path( '/activecampaign-api-php/includes/ActiveCampaign.class.php' );
			}

			if ( $this->api_url && $this->api_key ) {
				$this->sdk = new \ActiveCampaign( $this->api_url, $this->api_key );
			}
			else {
				$this->sdk = false;
			}
		}

		return $this->sdk;
	}


	function clear_cache_data() {
		Cache::delete_transient( 'ac_lists' );
		Cache::delete_transient( 'ac_contact_fields' );
	}

}
