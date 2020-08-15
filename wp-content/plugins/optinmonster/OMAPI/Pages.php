<?php
/**
 * Menu class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Erik Jonasson
 */
class OMAPI_Pages {

	/**
	 * Holds the class object.
	 *
	 * @since 1.9.10
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.9.10
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.9.10
	 *
	 * @var OMAPI
	 */
	public $base;

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.9.10
	 */
	public function __construct() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Loads Stylesheets that should only be loaded on a specific page
	 *
	 * @return void
	 */
	public function load_general_styles() {
		wp_enqueue_style( $this->base->plugin_slug . '-common', $this->base->url . 'assets/dist/css/common.min.css', false, $this->base->asset_version() );
	}

	/**
	 * Returns an array of our registered pages.
	 * If we need more pages, add them to this array
	 *
	 * @return void
	 */
	public function get_registered_pages() {
		$pages = array(
			array(
				'name' => __( 'TrustPulse', 'optin-monster-api' ),
				'slug' => 'optin-monster-trustpulse',
			),
			array(
				'name'     => __( 'About Us', 'optin-monster-api' ),
				'slug'     => 'optin-monster-about',
				'callback' => array( $this, 'render_about_us_page' ),
			),
		);

		return $pages;
	}

	/**
	 * Registers our submenu pages
	 *
	 * @param string $parent_page_name The Parent Page Name
	 * @return void
	 */
	public function register_submenu_pages( $parent_page_name ) {
		$pages = $this->get_registered_pages();
		$hooks = array();

		foreach ( $pages as $page ) {
			if ( ! empty( $page['callback'] ) ) {
				$hooks[] = add_submenu_page(
					$parent_page_name, // $parent_slug
					$page['name'], // $page_title
					! empty( $page['menu'] ) ? $page['menu'] : $page['name'], // $menu_title
					apply_filters( 'optin_monster_api_menu_cap', 'manage_options', $page['slug'] ),
					$page['slug'],
					$page['callback']
				);
			}
		}

		return $hooks;
	}

	/**
	 * Registers our submenu pages, but redirects to main page when navigating to them.
	 *
	 * @since  1.9.10
	 *
	 * @param string $parent_page_name The Parent Page Name
	 * @return void
	 */
	public function register_submenu_redirects( $parent_page_name ) {
		$hooks = $this->register_submenu_pages( $parent_page_name . '-hidden' );
		foreach ( $hooks as $hook ) {
			add_action( 'load-' . $hook, array( $this->base->menu, 'redirect_to_dashboard' ) );
		}
	}

	/**
	 * Outputs the OptinMonster about-us page.
	 *
	 * @since 1.9.10
	 */
	public function render_about_us_page() {
		$all_plugins = get_plugins();

		$data = array(
			'google-analytics-for-wordpress/googleanalytics.php' => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-mi.png',
				'class' => 'google-analytics-for-wordpressgoogleanalyticsphp',
				'name'  => 'MonsterInsights',
				'desc'  => sprintf( esc_html__( '%s makes it “effortless” to properly connect your WordPress site with Google Analytics, so you can start making data-driven decisions to grow your business.', 'optin-monster-api' ), 'MonsterInsights' ),
				'url'   => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.zip',
				'pro'   => array(
					'plugin' => 'google-analytics-premium/googleanalytics-premium.php',
					'name'   => 'MonsterInsights Pro',
					'url'    => 'https://www.monsterinsights.com/?utm_source=proplugin&utm_medium=pluginheader&utm_campaign=pluginurl&utm_content=7%2E0%2E0',
				),
			),
			'wpforms-lite/wpforms.php' => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-wp-forms.png',
				'class' => 'wpforms-litewpformsphp',
				'name'  => 'WPForms',
				'desc'  => sprintf( esc_html__( '%s allows you to create beautiful contact forms, feedback form, subscription forms, payment forms, and other types of forms for your site in minutes, not hours!', 'optin-monster-api' ), 'WPForms' ),
				'url'   => 'https://downloads.wordpress.org/plugin/wpforms-lite.zip',
				'pro'   => array(
					'plugin' => 'wpforms-premium/wpforms.php',
					'name'   => 'WPForms Pro',
					'url'    => 'https://www.wpforms.com/?utm_source=proplugin&utm_medium=pluginheader&utm_campaign=pluginurl&utm_content=7%2E0%2E0',
				),
			),
		);

		foreach ( $data as $plugin_id => $plugin ) {

			$installed = array_key_exists( $plugin_id, $all_plugins ) || array_key_exists( $plugin['pro']['plugin'], $all_plugins );
			$active    = is_plugin_active( $plugin_id ) || is_plugin_active( $plugin['pro']['plugin'] );

			$data[ $plugin_id ]['status'] = $installed ?
				$active ?
					__( 'Active', 'optin-monster-api' ) :
					__( 'Installed', 'optin-monster-api' )
				: __( 'Not Installed', 'optin-monster-api' );

			$data[ $plugin_id ]['installed'] = $installed;
			$data[ $plugin_id ]['active']    = $active;
		}

		$this->base->output_view(
			'about.php',
			array(
				'all_plugins' => $all_plugins,
				'plugins'     => $data,
			)
		);
	}
}
