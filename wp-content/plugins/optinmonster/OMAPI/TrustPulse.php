<?php
/**
 * TrustPulse class.
 *
 * @since 1.9.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TrustPulse class.
 *
 * @since 1.9.0
 */
class OMAPI_TrustPulse {

	/**
	 * Holds the class object.
	 *
	 * @since 1.9.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.9.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.9.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Holds the welcome slug.
	 *
	 * @since 1.9.0
	 *
	 * @var string
	 */
	public $hook;

	/**
	 * Whether the TrustPulse plugin is active.
	 *
	 * @since 1.9.0
	 *
	 * @var bool
	 */
	public $trustpulse_active;

	/**
	 * Whether the TrustPulse plugin has been setup.
	 *
	 * @since 1.9.0
	 *
	 * @var bool
	 */
	public $trustpulse_setup;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.9.0
	 */
	public function __construct() {
		if ( ! defined( 'TRUSTPULSE_APP_URL' ) ) {
			define( 'TRUSTPULSE_APP_URL', 'https://app.trustpulse.com/' );
		}

		if ( ! defined( 'TRUSTPULSE_URL' ) ) {
			define( 'TRUSTPULSE_URL', 'https://trustpulse.com/' );
		}

		// If we are not in admin or admin ajax, return.
		if ( ! is_admin() ) {
			return;
		}

		// If user is in admin ajax or doing cron, return.
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			return;
		}

		// If user is not logged in, return.
		if ( ! is_user_logged_in() ) {
			return;
		}

		// If user cannot manage_options, return.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Set our object.
		$this->set();

		// Register the menu item.
		add_action( 'admin_menu', array( $this, 'register_welcome_page' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.9.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();

		$this->trustpulse_active = function_exists( 'trustpulse_dir_uri' );

		$account_id             = get_option( 'trustpulse_script_id', null );
		$this->trustpulse_setup = ! empty( $account_id );
	}

	/**
	 * Loads the OptinMonster admin menu.
	 *
	 * @since 1.9.0
	 */
	public function register_welcome_page() {
		$this->hook = add_submenu_page(
			// If trustpulse is active/setup, don't show the TP sub-menu item under OM.
			$this->trustpulse_active && $this->trustpulse_setup
				? $this->base->menu->parent_slug() . '-no-menu'
				: $this->base->menu->parent_slug(), // Parent slug
			esc_html__( 'TrustPulse', 'optin-monster-api' ), // Page title
			esc_html__( 'Social Proof Widget', 'optin-monster-api' ),
			apply_filters( 'optin_monster_api_menu_cap', 'manage_options', 'optin-monster-trustpulse' ), // Cap
			'optin-monster-trustpulse', // Slug
			array( $this, 'display_page' ) // Callback
		);

		// If TrustPulse is active, we want to redirect to its own landing page.
		if ( $this->trustpulse_active ) {
			add_action( 'load-' . $this->hook, array( __CLASS__, 'redirect_trustpulse_plugin' ) );
		}

		// Load settings page assets.
		add_action( 'load-' . $this->hook, array( $this, 'assets' ) );
	}

	/**
	 * Redirects to the trustpulse admin page.
	 *
	 * @since  1.9.0
	 */
	public static function redirect_trustpulse_plugin() {
		$url = esc_url_raw( admin_url( 'admin.php?page=trustpulse' ) );
		wp_safe_redirect( $url );
		exit;
	}

	/**
	 * Outputs the OptinMonster settings page.
	 *
	 * @since 1.9.0
	 */
	public function display_page() {
		$all = get_plugins();
		$this->base->output_view(
			'trustpulse-settings-page.php',
			array(
				'has_plugin' => ! empty( $all['trustpulse-api/trustpulse.php'] ),
			)
		);
	}

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 1.9.0
	 */
	public function assets() {
		add_filter( 'admin_body_class', array( $this, 'add_body_classes' ) );
		$this->base->menu->styles();
		$this->base->menu->scripts();

		wp_enqueue_style( 'om-tp-admin-css', $this->base->url . 'assets/dist/css/trustpulse.min.css', false, $this->base->asset_version() );
		add_action( 'in_admin_header', array( $this, 'render_banner' ) );
	}

	/**
	 * Renders TP banner in the page header
	 *
	 * @return void
	 */
	public function render_banner() {
		$this->base->output_view( 'trustpulse-banner.php' );
	}

	/**
	 * Add body classes.
	 *
	 * @since 1.9.0
	 */
	public function add_body_classes( $classes ) {

		$classes .= ' omapi-trustpulse ';

		return $classes;
	}

}
