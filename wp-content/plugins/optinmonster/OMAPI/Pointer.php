<?php
/**
 * Admin Pointer class.
 *
 * @since 1.6.5
 *
 * @package OMAPI
 * @author  Erik Jonasson
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Pointer class.
 *
 * @since 1.6.5
 */
class OMAPI_Pointer {
	/**
	 * Holds the class object.
	 *
	 * @since 1.6.5
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Class Constructor
	 */
	public function __construct() {
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

		$this->set();

		add_action( 'admin_enqueue_scripts', array( $this, 'load_pointer' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.6.5
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
		$this->view     = isset( $_GET['optin_monster_api_view'] ) ? stripslashes( $_GET['optin_monster_api_view'] ) : $this->base->get_view();
	}

	/**
	 * Loads our Admin Pointer, if:
	 *  1. We're on a valid WP Version
	 *  2. We're on the Dashboard Page
	 *  3. We don't have an API Key
	 *  4. The Pointer hasn't already been dismissed
	 *
	 * @since 1.6.5
	 */
	public function load_pointer() {

		// Don't run on WP < 3.3.
		if ( get_bloginfo( 'version' ) < '3.3' ) {
			return;
		}

		$screen = get_current_screen();

		// If we're not on the dashboard, or we already have an API key, don't trigger the pointer.
		if ( 'dashboard' !== $screen->id || $this->base->get_api_credentials() ) {
			return;
		}

		// Make sure the pointer hasn't been dismissed.
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		if ( in_array( 'om-welcome-pointer', $dismissed, true ) ) {
			return;
		}

		$content  = '<h3>' . esc_html__( 'Get More Leads, Subscribers and Sales Today!', 'optin-monster-api' ) . '</h3>';
		$content .= '<div class="om-pointer-close-link"><a class="close" href="#"></a></div>';
		$content .= '<h4>' . esc_html__( 'Grow Your Business with OptinMonster', 'optin-monster-api' ) . '</h4>';
		$content .= '<p>' . esc_html__( 'Turn your website visitors into subscribers and customers with OptinMonster, the #1 conversion optimization toolkit in the world.', 'optin-monster-api' ) . '</p>';
		$content .= '<p>' . esc_html__( 'For a limited time, get 50% off any plan AND get instant access to OptinMonster University - our exclusive training portal with over $2,000 worth of courses, courses, content and videos', 'optin-monster-api' ) . '<strong> ' . esc_html__( '100% Free!', 'optin-monster-api' ) . '</strong></p>';
		$content .= '<p><a class="button button-primary" id="omPointerButton" href="' . $this->base->welcome->get_link() . '">' . esc_html__( 'Click Here to Learn More', 'optin-monster-api' ) . '</a>';

		$pointer = array(
			'id'      => 'om-welcome-pointer',
			'target'  => '#toplevel_page_' . $this->base->menu->parent_slug(),
			'options' => array(
				'content'  => $content,
				'position' => array(
					'edge'  => 'left',
					'align' => 'right',
				),
			),
		);

		// Add pointers script to queue. Add custom script.
		wp_enqueue_script(
			$this->base->plugin_slug . '-pointer',
			$this->base->url . 'assets/dist/js/pointer.min.js',
			array( 'wp-pointer' ),
			$this->base->asset_version(),
			true
		);

		wp_enqueue_style(
			$this->base->plugin_slug . '-pointer',
			$this->base->url . 'assets/css/pointer.css',
			array( 'wp-pointer' ),
			$this->base->asset_version()
		);

		// Add pointer options to script.
		wp_localize_script( $this->base->plugin_slug . '-pointer', 'omapiPointer', $pointer );
	}
}
