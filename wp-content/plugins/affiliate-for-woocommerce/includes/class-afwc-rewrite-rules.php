<?php
/**
 * Class for Affiliates Pretty Referral URL functionality
 *
 * @package  affiliate-for-woocommerce/includes/
 * @since    6.0.0
 * @version  1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Rewrite_Rules' ) ) {

	/**
	 * Main class for Affiliate Pretty Referral URL functionality
	 */
	class AFWC_Rewrite_Rules {

		/**
		 * Variable to hold instance of AFWC_Rewrite_Rules
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 *  Constructor
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'rewrite_rules' ), 999998 );
			add_action( 'admin_init', array( $this, 'maybe_flush_rewrite_rules' ), 999999 );
			add_filter( 'redirect_canonical', array( $this, 'prevent_homepage_redirects' ), 0, 2 );
		}

		/**
		 * Get single instance of AFWC_Rewrite_Rules
		 *
		 * @return AFWC_Rewrite_Rules Singleton object of AFWC_Rewrite_Rules
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Adds the affiliate tracking param name endpoint in the URL.
		 */
		public function rewrite_rules() {
			$pname = afwc_get_pname();

			// Get the taxonomies.
			$taxonomies = get_taxonomies(
				array(
					'public'   => true,
					'_builtin' => false,
				),
				'objects'
			);

			// Add rewrite rules for taxonomies.
			foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
				if ( is_array( $taxonomy->rewrite ) && ! empty( $taxonomy->rewrite['slug'] ) ) {
					add_rewrite_rule( $taxonomy->rewrite['slug'] . '\/(.+?)\/' . $pname . '(/(.*))?/?$', 'index.php?' . $taxonomy_slug . '=$matches[1]&' . $pname . '=$matches[3]', 'top' );
				}
			}

			// Rewrite the endpoint.
			add_rewrite_endpoint( $pname, EP_ROOT | EP_PERMALINK | EP_PAGES | EP_CATEGORIES | EP_TAGS | EP_SEARCH | EP_ALL_ARCHIVES, false );
		}

		/**
		 * Flush rewrite rules if needed
		 */
		public function maybe_flush_rewrite_rules() {
			if ( get_option( 'afwc_flushed_rules_pretty_referral_links' ) ) {
				// Flush rewrite rules.
				flush_rewrite_rules();
				delete_option( 'afwc_flushed_rules_pretty_referral_links' );
			}
		}

		/**
		 * Prevent homepage redirect when the requested url contains the pretty affiliate endpoint.
		 *
		 * @param string $redirect_url  The URL to redirect to.
		 * @param string $requested_url The current URL.
		 *
		 * @return string $redirect_url
		 */
		public function prevent_homepage_redirects( $redirect_url, $requested_url ) {
			// Check if we are on the homepage.
			if ( ! is_front_page() ) {
				return $redirect_url;
			}

			$pname = afwc_get_pname();

			// Check if the requested url contains the affiliate tracking param name.
			if ( strpos( $requested_url, $pname ) !== false ) {
				return $requested_url;
			}

			return $redirect_url;
		}

	}

}

return new AFWC_Rewrite_Rules();
