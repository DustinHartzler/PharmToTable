<?php
/**
 * Rest API Class, where we register/execute any REST API Routes
 *
 * @since 1.8.0
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
class OMAPI_Sites {

	/**
	 * The Base OMAPI Object
	 *
	 *  @since 1.8.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	public function __construct() {
		$this->base = OMAPI::get_instance();
	}

	/**
	 * Refresh the site data.
	 *
	 * @since 1.8.0
	 *
	 * @param mixed $api_key If we want to use a custom API Key, pass it in
	 *
	 * @return array|null $sites An array of sites if the request is successful
	 */
	public function fetch( $api_key = null, $get_cached = false ) {
		$cache_key = 'om_sites' . md5( $api_key );

		if ( $get_cached ) {
			$results = get_transient( $cache_key );
			if ( ! empty( $results ) ) {
				return $results;
			}
		}

		// Delete any cached sites.
		delete_transient( $cache_key );

		$creds = ! empty( $api_key ) ? array( 'apikey' => $api_key ) : array();
		$body  = OMAPI_Api::build( 'v2', 'sites/origin', 'GET', $creds )->request();

		if ( is_wp_error( $body ) ) {
			return $this->handle_error( $body );
		}

		$results = array(
			'siteId'       => '',
			'siteIds'      => array(),
			'customApiUrl' => '',
		);

		$domain = $this->get_domain();
		$tld    = $this->get_tld( $domain );

		if ( ! empty( $body->data ) ) {
			$checkCnames = true;
			foreach ( $body->data as $site ) {
				if ( empty( $site->domain ) ) {
					continue;
				}

				$matches        = $domain === (string) $site->domain;
				$wildcardDomain = '*.' === substr( $site->domain, 0, 2 ) && $tld === $this->get_tld( $site->domain );

				// Doesn't match, and not a wildcard? Bail.
				if ( ! $matches && ! $wildcardDomain ) {
					continue;
				}

				$results['siteIds'][] = (string) $site->siteId;

				// If we don't have a siteId yet, set it to this one.
				// If we DO already have a siteId and this one is NOT a wildcard,
				// we want to overwrite with this one.
				if ( empty( $results['siteId'] ) || ! $wildcardDomain ) {
					$results['siteId'] = (string) $site->siteId;
				}

				// Do we have a custom cnamed api url to use?
				if ( $site->settings->enableCustomCnames && $checkCnames ) {

					$found = false;
					if ( $site->settings->cdnCname && $site->settings->cdnCnameVerified ) {

						// If we have a custom CNAME, let's enable it and add the data to the output array.
						$results['customApiUrl'] = 'https://' . $site->settings->cdnUrl . '/app/js/api.min.js';
						$found                   = true;

					} elseif ( $site->settings->apiCname && $site->settings->apiCnameVerified ) {
						// Not sure if this will wreak havoc during verification of the domains, so leaving it commented out for now.
						// $results['customApiUrl'] = 'https://' . $site->settings->apiUrl . '/a/app/js/api.min.js';
						// $found = true;
					}

					// If this isn't a wildcard domain, and we found a custom api url, we don't
					// need to continue checking cnames.
					if ( $found && ! $wildcardDomain ) {
						$checkCnames = false;
					}
				}
			}
		}

		if ( empty( $results['siteId'] ) ) {
			$site = $this->attempt_create_site( $creds );
			if ( is_wp_error( $site ) ) {
				return $this->handle_error( $site );
			}

			if ( ! empty( $site->siteId ) ) {
				$results['siteId'] = (string) $site->siteId;
			}
		}

		if ( ! is_wp_error( $results ) && ! empty( $results['siteIds'] ) ) {
			set_transient( $cache_key, $results, 5 * MINUTE_IN_SECONDS );
		}

		return $results;
	}

	/**
	 * Attempt to create the associated site in the app.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $creds Array of credentials for request.
	 *
	 * @return mixed         Site-created response or WP_Error.
	 */
	public function attempt_create_site( $creds ) {
		$site_args = array(
			'domain'   => esc_url_raw( site_url() ),
			'name'     => esc_attr( get_option( 'blogname' ) ),
			'settings' => array(
				'wordpress' => 1,
				'homeUrl'   => esc_url_raw( home_url() ),
				'restUrl'   => esc_url_raw( get_rest_url() ),
			),
		);

		// Create/update the site for this WordPress site.
		$result = OMAPI_Api::build( 'v2', 'sites', 'POST', $creds )
			->request( $site_args );

		return 201 === (int) OMAPI_Api::instance()->response_code
			? OMAPI_Api::instance()->response_body
			: $result;
	}

	/**
	 * Get the domain for this WP site.
	 * Borrowed heavily from AwesomeMotive\OptinMonsterApp\Utils\Url
	 *
	 * @since  1.9.10
	 *
	 * @return string
	 */
	public function get_domain() {
		$url      = site_url();
		$parsed   = parse_url( $url );
		$hostname = ! empty( $parsed['host'] ) ? $parsed['host'] : $url;
		$domain   = preg_replace( '/^www\./', '', $hostname );

		return $domain;
	}

	/**
	 * Get the top-level-domain for the given domain.
	 *
	 * @since  2.0.1
	 *
	 * @param  string  $domain Domain to get tld for.
	 *
	 * @return string          The tld.
	 */
	public function get_tld( $domain ) {
		$parts = explode( '.', $domain );
		$count = count( $parts );
		$tld   = array_slice( $parts, max( 0, $count - 2 ) );

		return implode( '.', $tld );
	}

	/**
	 * Updates the error text when we try to auto-create this WP site, but it fails.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_Error $error The error object.
	 *
	 * @return WP_Error
	 */
	public function handle_error( $error ) {
		$instance = OMAPI_Api::instance();
		if ( 402 === (int) $error->get_error_data() && ! empty( $instance->response_body->siteAmount ) ) {

			$message = sprintf(
				__( 'We tried to register your WordPress site with OptinMonster, but You have reached the maximum number of registered sites for your current OptinMonster plan.<br>Additional sites can be added to your account by <a href="%1$s" target="_blank" rel="noopener">upgrading</a> or <a href="%2$s" target="_blank" rel="noopener">purchasing additional site licenses</a>.', 'optin-monster-api' ),
				esc_url_raw( OPTINMONSTER_APP_URL . '/account/upgrade/?utm_source=app&utm_medium=upsell&utm_campaign=header&feature=sites/' ),
				esc_url_raw( OPTINMONSTER_APP_URL . '/account/billing/#additional-licenses' )
			);

			$error = new WP_Error( $error->get_error_code(), $message, 402 );
		}

		return $error;
	}

}
