<?php
/**
 * Menu class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Menu class.
 *
 * @since 1.0.0
 */
class OMAPI_Menu {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var OMAPI
	 */
	public $base;

	/**
	 * Holds the admin menu slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $hook;

	/**
	 * Holds a tabindex counter for easy navigation through form fields.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $tabindex = 429;

	/**
	 * The OMAPI_Pages object.
	 *
	 * @since 1.9.10
	 *
	 * @var OMAPI_Pages
	 */
	public $pages = null;

	/**
	 * Panel slugs/names.
	 *
	 * @since 1.9.0
	 *
	 * @var array
	 */
	public $panels = array();

	/**
	 * Registered page hooks.
	 *
	 * @since 1.9.10
	 *
	 * @var array
	 */
	public $hooks = array();

	/**
	 * The OM landing page url.
	 *
	 * @since 1.8.4
	 */
	const LANDING_URL = 'https://optinmonster.com/wp/?utm_source=orgplugin&utm_medium=link&utm_campaign=wpdashboard';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $isTesting
	 */
	public function __construct( $isTesting = false ) {

		if ( ! $isTesting ) {
			// Set our object.
			$this->set();

			// Load actions and filters.
			add_action( 'admin_menu', array( $this, 'menu' ) );
			add_action( 'admin_menu', array( $this, 'after_menu_registration' ), 999 );
			// Load helper body classes
			add_filter( 'admin_body_class', array( $this, 'admin_body_classes' ) );

			add_action( 'admin_notices', array( $this, 'maybe_output_notice' ), 2 );
			add_action( 'all_admin_notices', array( $this, 'maybe_output_notice' ), 2 );

			add_filter( 'plugin_action_links_' . plugin_basename( OMAPI_FILE ), array( $this, 'output_plugin_links' ) );
		}

		$this->panels = array(
			'optins'      => esc_html__( 'Campaigns', 'optin-monster-api' ),
			'api'         => esc_html__( 'Authorization', 'optin-monster-api' ),
			'woocommerce' => esc_html__( 'WooCommerce', 'optin-monster-api' ),
			'support'     => esc_html__( 'Support', 'optin-monster-api' ),
			'migrate'     => esc_html__( 'Migration', 'optin-monster-api' ),
		);
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {

		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
		$this->view     = isset( $_GET['optin_monster_api_view'] ) ? stripslashes( $_GET['optin_monster_api_view'] ) : $this->base->get_view();

	}

	/**
	 * Loads the OptinMonster admin menu.
	 *
	 * @since 1.0.0
	 */
	public function menu() {
		$this->pages = new OMAPI_Pages();

		$parent = $this->base->menu->parent_slug();

		// Filter to change the menu position if there is any conflict with another menu on the same position.
		$menu_position = apply_filters( 'optin_monster_api_menu_position', 26 );

		$this->hooks[] = $this->hook = add_menu_page(
			'OptinMonster',
			'OptinMonster<span class="om-pulse"></span>',
			apply_filters( 'optin_monster_api_menu_cap', 'manage_options', $parent ),
			$parent,
			array( $this, 'settings_page' ),
			'none',
			$menu_position
		);

		if ( $this->base->get_api_credentials() ) {

			$title = isset( $this->panels[ $this->view ] )
				? $this->panels[ $this->view ]
				: __( 'Campaigns', 'optin-monster-api' );

			// Just add a placeholder secondary page.
			$this->hooks[] = add_submenu_page(
				$parent, // parent slug
				$title, // page title,
				__( 'Campaigns', 'optin-monster-api' ),
				apply_filters( 'optin_monster_api_menu_cap', 'manage_options', $parent ), // cap
				$parent, // slug
				array( $this, 'settings_page' )
			);

			$this->hooks = array_merge( $this->hooks, $this->pages->register_submenu_pages( $parent ) );
		} else {
			$this->pages->register_submenu_redirects( $parent );
		}

		// Load global icon font styles.
		add_action( 'admin_head', array( $this, 'global_admin_styles' ) );
	}

	/**
	 * Handles enqueueing assets for registered pages, and ensuring about page is at bottom.
	 *
	 * @since  1.9.10
	 *
	 * @return void
	 */
	public function after_menu_registration() {
		global $submenu;
		$parent = $this->base->menu->parent_slug();

		// Make sure the about page is still the last page.
		if ( isset( $submenu[ $parent ] ) ) {
			$after  = array();
			$at_end = array( 'optin-monster-about' );
			foreach ( $submenu[ $parent ] as $key => $menu ) {
				if ( isset( $menu[2] ) && in_array( $menu[2], $at_end ) ) {
					$after[] = $menu;
					unset( $submenu[ $parent ][ $key ] );
				}
			}
			$submenu[ $parent ] = array_values( $submenu[ $parent ] );
			foreach ( $after as $menu ) {
				$submenu[ $parent ][] = $menu;
			}
		}

		// Load settings page assets.
		foreach ( $this->hooks as $hook ) {
			if ( ! empty( $hook ) ) {
				add_action( 'load-' . $hook, array( $this, 'assets' ) );
				add_action( 'load-' . $hook, array( $this->pages, 'load_general_styles' ) );
			}
		}
	}

	/**
	 * Loads the custom Archie icon.
	 *
	 * @since 1.0.0
	 */
	public function global_admin_styles() {
		$this->base->output_min_css( 'archie-css.php' );

		// Note: Completed 2019.
		if ( ! $this->is_om_page() && $this->should_show_notification_pulse() ) {
			$this->base->output_min_css( 'notification-pulse-css.php' );
		}
	}

	/**
	 * Should we show the OM menu item notification pulse.
	 *
	 * Disabled for now.
	 *
	 * @see https://github.com/awesomemotive/optin-monster-wp-api/pull/179
	 *
	 * @since  1.9.0
	 *
	 * @return bool
	 */
	public function should_show_notification_pulse() {
		return false;
	}

	/**
	 * Outputs a notice, if allowed.
	 *
	 * @see https://github.com/awesomemotive/optin-monster-wp-api/pull/179
	 *
	 * @since 1.9.0
	 */
	public function maybe_output_notice() {
		static $hooked = false;
		if (
			! $hooked
			&& $this->should_show_notification_pulse()
			&& $this->is_om_page()
		) {

			$url = '';
			$this->base->output_min_css( 'notification-css.php' );
			$this->base->output_view( 'notification.php', compact( 'url' ) );
			add_action( 'admin_footer', array( $this, 'handle_closing_notice' ) );
		}
		$hooked = true;
	}

	/**
	 * Handles the notice-closing and setting the cookie.
	 *
	 * @see https://github.com/awesomemotive/optin-monster-wp-api/pull/179
	 *
	 * @since 1.9.0
	 */
	public function handle_closing_notice() {
		$this->base->output_view( 'notification-close-js.php' );
	}

	/**
	 * Add pages to plugin action links in the Plugins table.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $links Default plugin action links.
	 *
	 * @return array $links Amended plugin action links.
	 */
	public function output_plugin_links( $links ) {

		$new_links = $this->base->get_api_credentials()
			? array(
				sprintf( '<a href="%s">%s</a>', $this->get_dashboard_link(), __( 'Campaigns', 'optin-monster-api' ) ),
				sprintf( '<a href="%s">%s</a>', $this->get_settings_link(), __( 'Settings', 'optin-monster-api' ) ),
			)
			: array(
				sprintf( '<a href="%s">%s</a>', $this->get_dashboard_link(), __( 'Connect', 'optin-monster-api' ) ),
			);

		$links = array_merge( $new_links, $links );

		return $links;
	}

	/**
	 * Adds om admin body classes
	 *
	 * @since  1.3.4
	 *
	 * @param  array $classes
	 *
	 * @return array
	 */
	public function admin_body_classes( $classes ) {

		$classes .= ' omapi-screen ';

		if ( $this->base->get_api_key_errors() ) {
			$classes .= ' omapi-has-api-errors ';
		}

		return $classes;

	}

	/**
	 * Check if we're on one of the OM menu/sub-menu pages.
	 *
	 * @since  1.9.0
	 *
	 * @return boolean
	 */
	public function is_om_page() {
		if ( ! is_admin() ) {
			return false;
		}

		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			$page   = $screen->id;
			if ( false !== strpos( $page, 'toplevel_page_optin-monster-' ) ) {
				return true;
			}

			if ( ! empty( $screen->parent_base ) && false !== strpos( $screen->parent_base, 'optin-monster-' ) ) {
				return true;
			}
		} else {
			$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		}

		return false !== strpos( $page, 'optin-monster' );
	}

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 1.0.0
	 */
	public function assets() {
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_filter( 'admin_footer_text', array( $this, 'footer' ) );
		add_action( 'in_admin_header', array( $this, 'output_plugin_screen_banner' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'fix_plugin_js_conflicts' ), 100 );

	}

	/**
	 * Register and enqueue settings page specific CSS.
	 *
	 * @since 1.0.0
	 */
	public function styles() {
		$version = $this->base->asset_version();

		wp_register_style( $this->base->plugin_slug . '-select2', $this->base->url . 'assets/css/select2.min.css', array(), $version );
		wp_enqueue_style( $this->base->plugin_slug . '-select2' );
		wp_register_style( $this->base->plugin_slug . '-settings', $this->base->url . 'assets/dist/css/settings.min.css', array(), $version );
		wp_enqueue_style( $this->base->plugin_slug . '-settings' );

		// Run a hook to load in custom styles.
		do_action( 'optin_monster_api_admin_styles', $this->view );

	}

	/**
	 * Register and enqueue settings page specific JS.
	 *
	 * @since 1.0.0
	 */
	public function scripts() {
		global $wpdb;

		$version = $this->base->asset_version();

		// Posts query.
		$postTypes = array_map( 'esc_sql', get_post_types( array( 'public' => true ) ) );
		$postTypes = implode( "','", $postTypes );

		$sql   = "
		SELECT ID AS `id`, post_title AS `text`
		FROM $wpdb->posts
		WHERE post_type IN ( '{$postTypes}' )
		AND post_status IN ('publish','future')
		ORDER BY post_title ASC
		";
		$posts = $wpdb->get_results( $sql, ARRAY_A );

		// Taxonomies query.
		$sql  = "
		SELECT terms.term_id AS 'id', terms.name AS 'text'
		FROM {$wpdb->term_taxonomy} tax
		LEFT JOIN {$wpdb->terms} terms ON terms.term_id = tax.term_id
		WHERE tax.taxonomy = 'post_tag'
		ORDER BY text ASC
		";
		$tags = $wpdb->get_results( $sql, ARRAY_A );

		wp_register_script( $this->base->plugin_slug . '-select2', $this->base->url . 'assets/js/select2.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( $this->base->plugin_slug . '-select2' );
		wp_register_script( $this->base->plugin_slug . '-connect', $this->base->url . 'assets/dist/js/connect.min.js', array( 'jquery' ), $version, true );
		wp_register_script(
			$this->base->plugin_slug . '-settings',
			$this->base->url . 'assets/dist/js/settings.min.js',
			array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-datepicker',
				'underscore',
				$this->base->plugin_slug . '-select2',
				$this->base->plugin_slug . '-connect',
			),
			$version,
			true
		);

		wp_localize_script(
			$this->base->plugin_slug . '-connect',
			'OMAPI',
			array(
				'posts'    => $posts,
				'tags'     => $tags,
				'app_url'  => trailingslashit( OPTINMONSTER_APP_URL ),
				'blogname' => esc_attr( get_option( 'blogname' ) ),
				'root'     => esc_url_raw( rest_url() ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'confirm'  => __( 'Are you sure you want to reset these settings?', 'optin-monster-api' ),
			)
		);
		wp_enqueue_script( $this->base->plugin_slug . '-settings' );
		wp_register_script( $this->base->plugin_slug . '-clipboard', $this->base->url . 'assets/js/clipboard.min.js', array( $this->base->plugin_slug . '-settings' ), $version, true );
		wp_enqueue_script( $this->base->plugin_slug . '-clipboard' );
		wp_register_script( $this->base->plugin_slug . '-tooltip', $this->base->url . 'assets/js/tooltip.min.js', array( $this->base->plugin_slug . '-settings' ), $version, true );
		wp_enqueue_script( $this->base->plugin_slug . '-tooltip' );
		wp_register_script( $this->base->plugin_slug . '-jspdf', $this->base->url . 'assets/js/jspdf.min.js', array( $this->base->plugin_slug . '-settings' ), $version, true );
		wp_enqueue_script( $this->base->plugin_slug . '-jspdf' );

		// Run a hook to load in custom styles.
		do_action( 'optin_monster_api_admin_scripts', $this->view );

	}

	/**
	 * Deque specific scripts that cause conflicts on settings page
	 *
	 * @since 1.1.5.9
	 */
	public function fix_plugin_js_conflicts() {
		if ( $this->is_om_page() ) {

			// Dequeue scripts that might cause our settings not to work properly.
			wp_dequeue_script( 'optimizely_config' );
		}
	}

	/**
	 * Customizes the footer text on the OptinMonster settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text  The default admin footer text.
	 * @return string $text Amended admin footer text.
	 */
	public function footer( $text ) {

		$url  = 'https://wordpress.org/support/plugin/optinmonster/reviews?filter=5#new-post';
		$text = sprintf( __( 'Please rate <strong>OptinMonster</strong> <a href="%1$s" target="_blank" rel="noopener">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%2$s" target="_blank" rel="noopener noreferrer">WordPress.org</a> to help us spread the word. Thank you from the OptinMonster team!', 'optin-monster-api' ), $url, $url );
		return $text;

	}

	/**
	 * Outputs the OptinMonster settings page.
	 *
	 * @since 1.0.0
	 */
	public function settings_page() {
		$first = true;

		?>
		<div class="wrap omapi-page">
			<h2></h2>
			<div class="omapi-ui">
				<div class="omapi-tabs">
					<ul class="omapi-panels">
						<?php foreach ( $this->get_panels() as $id => $panel ) :
							$first  = $first ? ' omapi-panel-first' : '';
							$active = $id == $this->view ? ' omapi-panel-active' : '';
						?>
							<li class="omapi-panel omapi-panel-<?php echo sanitize_html_class( $id ); ?><?php echo $first . $active; ?>"><a href="<?php echo esc_url_raw( $this->get_settings_link( $id ) ); ?>" class="omapi-panel-link" data-panel="<?php echo $id; ?>" data-panel-title="<?php echo $panel; ?>"><?php echo $panel; ?></a></li>
						<?php $first = false; endforeach; ?>
					</ul>
				</div>
				<div class="omapi-tabs-content">
					<?php
					foreach ( $this->get_panels() as $id => $panel ) :
						$active = $id == $this->view ? ' omapi-content-active' : '';
					?>
					<div class="omapi-content omapi-content-<?php echo sanitize_html_class( $id ); ?><?php echo $active; ?>">
						<?php
							do_action( 'optin_monster_api_content_before', $id, $panel, $this, $active );
							do_action( 'optin_monster_api_content_' . $id, $panel, $this, $active );
							do_action( 'optin_monster_api_content_after', $id, $panel, $this, $active );
						?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 * Retrieves the available tab panels.
	 *
	 * @since 1.0.0
	 *
	 * @return array $panels Array of tab panels.
	 */
	public function get_panels() {

		// Only load the API panel if no API credentials have been set.
		$panels           = array();
		$creds            = $this->base->get_api_credentials();
		$can_migrate      = $this->base->can_migrate();
		$is_legacy_active = $this->base->is_legacy_active();
		$is_minimum_woo   = OMAPI_WooCommerce::is_minimum_version();
		$can_manage_woo   = current_user_can( 'manage_woocommerce' );

		// Set panels requiring credentials.
		if ( $creds ) {
			$panels['optins'] = $this->panels['optins'];
		}

		// Set default panels.
		$panels['api'] = $this->panels['api'];

		// Set the WooCommerce panel.
		if ( $creds && ( $is_minimum_woo || OMAPI_WooCommerce::is_connected() ) && $can_manage_woo ) {
			$panels['woocommerce'] = $this->panels['woocommerce'];
		}

		// Set the Support panel
		$panels['support'] = $this->panels['support'];

		// Set the migration panel.
		if ( $creds && $can_migrate && $is_legacy_active ) {
			$panels['migrate'] = $this->panels['migrate'];
		}

		return apply_filters( 'optin_monster_api_panels', $panels );

	}

	/**
	 * Retrieves the setting UI for the setting specified.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id      The optin ID to target.
	 * @param string $setting The possible subkey setting for the option.
	 * @return string         HTML setting string.
	 */
	public function get_setting_ui( $id, $setting = '' ) {

		// Prepare variables.
		$ret      = '';
		$optin_id = isset( $_GET['optin_monster_api_id'] ) ? absint( $_GET['optin_monster_api_id'] ) : 0;
		$value    = 'optins' == $id ? get_post_meta( $optin_id, '_omapi_' . $setting, true ) : $this->base->get_option( $id, $setting );
		$optin    = get_post( $optin_id );

		// Load the type of setting UI based on the option.
		switch ( $id ) {
			case 'api':
				switch ( $setting ) {
					case 'user':
						$ret = $this->get_password_field( $setting, $value, $id, esc_html__( 'Legacy API Username', 'optin-monster-api' ), esc_html__( 'The Legacy API Username found in your OptinMonster Account API area.', 'optin-monster-api' ), esc_html__( 'Enter your Legacy API Username here...', 'optin-monster-api' ) );
						break 2;

					case 'key':
						$ret = $this->get_password_field( $setting, $value, $id, esc_html__( 'Legacy API Key', 'optin-monster-api' ), esc_html__( 'The Legacy API Key found in your OptinMonster Account API area.', 'optin-monster-api' ), esc_html__( 'Enter your Legacy API Key here...', 'optin-monster-api' ) );
						break 2;

					case 'apikey':
						$ret  = $this->get_api_field( $setting, $value, 'omapiAuthorizeButton', esc_html__( 'Authorize OptinMonster', 'optin-monster-api' ), sprintf( esc_html__( 'Click to connect your OptinMonster Account, or %1$s click here to enter an API Key Manually.%2$s', 'optin-monster-api' ), '<a href="#" id="omapiShowApiKey">', '</a>' ) );
						$ret .= $this->get_password_field( $setting, $value, $id, esc_html__( 'API Key', 'optin-monster-api' ), esc_html__( 'A single API Key found in your OptinMonster Account API area.', 'optin-monster-api' ), esc_html__( 'Enter your API Key here...', 'optin-monster-api' ), array(), true );
						add_filter( 'omapi_hide_submit_buttom', '__return_true' );
						break 2;

					case 'omwpdebug':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Debugging Rules', 'optin-monster-api' ), __( 'Allow logged-out/non-admin debugging of plugin rules with the <code>omwpdebug</code> query variable?', 'optin-monster-api' ) );
						break 2;
					default:
						break 2;
				}
				break;

			case 'settings':
				switch ( $setting ) {
					case 'cookies':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Clear local cookies on campaign update?', 'optin-monster-api' ), esc_html__( 'If checked, local cookies will be cleared for all campaigns after campaign settings are adjusted and saved.', 'optin-monster-api' ) );
						break 2;
					default:
						break 2;
				}
				break;

			case 'woocommerce':
				switch ( $setting ) {
					case 'settings':
						$ret = $this->get_woocommerce();
						break 2;
					default:
						break 2;
				}
				break;

			case 'support':
				switch ( $setting ) {
					case 'video':
						$ret = '<div class="omapi-half-column"><div class="omapi-video-container"><iframe width="640" height="360" src="https://www.youtube.com/embed/tUoJcp5Z9H0?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe></div></div>';
						break 2;

					case 'links':
						$ret = $this->get_support_links( $setting, esc_html__( 'Helpful Links', 'optin-monster-api' ) );
						break 2;

					case 'server-report';
						$ret = $this->get_plugin_report( $setting, esc_html__( 'Server / Plugin Report', 'optin-monster-api' ) );
						break 2;
					default:
						break 2;
				}
				break;

			case 'toggle':
				switch ( $setting ) {
					case 'advanced-start':
						$ret = $this->get_toggle_start( $setting, esc_html__( 'Advanced Settings', 'optin-monster-api' ), esc_html__( 'More specific settings available for campaign visibility.', 'optin-monster-api' ) );
						break 2;
					case 'advanced-end':
						$ret = $this->get_toggle_end();
						break 2;
					case 'woocommerce-start':
						$ret = $this->get_toggle_start( $setting, esc_html__( 'WooCommerce Settings', 'optin-monster-api' ), esc_html__( 'More specific settings available for WooCommerce integration.', 'optin-monster-api' ) );
						break 2;
					case 'woocommerce-end':
						$ret = $this->get_toggle_end();
						break 2;
					default:
						break 2;
				}
				break;

			case 'optins':
				switch ( $setting ) {
					case 'enabled':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Enable campaign on site?', 'optin-monster-api' ), esc_html__( 'The campaign will not be displayed on this site unless this setting is checked.', 'optin-monster-api' ) );
						break 2;

					case 'automatic':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Display the campaign automatically after blog posts', 'optin-monster-api' ), sprintf( __( 'If no advanced settings are selected below, the campaign will display after every post. You can turn this off and add it manually to your posts by <a href="%s" target="_blank" rel="noopener">clicking here and viewing the tutorial.</a>', 'optin-monster-api' ), 'https://optinmonster.com/docs/how-to-manually-add-an-after-post-or-inline-optin/' ), array( 'omapi-after-post-auto-select' ) );
						break 2;
					case 'automatic_shortcode':
						$full_shortcode = '[optin-monster slug="' . $optin->post_name . '"]';
						$ret            = $this->get_text_field(
							$setting,
							$full_shortcode,
							$id,
							esc_html__( 'Shortcode for this campaign', 'optin-monster-api' ),
							sprintf( __( 'Use the shortcode to manually add this campaign to inline to a post or page. <a href="%s" title="Click here to learn more about how this work" target="_blank" rel="noopener">Click here to learn more about how this works.</a>', 'optin-monster-api' ), 'https://optinmonster.com/docs/how-to-manually-add-an-after-post-or-inline-optin/' ),
							false,
							array(),
							true
						);
						break 2;

					case 'users':
						$ret = $this->get_dropdown_field( $setting, $value, $id, $this->get_user_output(), esc_html__( 'Who should see this campaign?', 'optin-monster-api' ), sprintf( __( 'Determines who should be able to view this campaign. Want to hide for newsletter subscribers? <a href="%s" target="_blank" rel="noopener">Click here to learn how.</a>', 'optin-monster-api' ), 'https://optinmonster.com/docs/how-to-hide-optinmonster-from-existing-newsletter-subscribers/' ) );
						break 2;

					case 'never':
						$val = is_array( $value ) ? implode( ',', $value ) : $value;
						$ret = $this->get_custom_field( $setting, '<input type="hidden" value="' . esc_attr( $val ) . '" id="omapi-field-' . $setting . '" class="omapi-select" name="omapi[' . $id . '][' . $setting . ']" data-placeholder="' . esc_attr__( 'Type to search and select post(s)...', 'optin-monster-api' ) . '">', esc_html__( 'Never load campaign on:', 'optin-monster-api' ), esc_html__( 'Never loads the campaign on the selected posts and/or pages. Does not disable automatic Global output.', 'optin-monster-api' ) );
						break 2;

					case 'only':
						$val = is_array( $value ) ? implode( ',', $value ) : $value;
						$ret = $this->get_custom_field( $setting, '<input type="hidden" value="' . esc_attr( $val ) . '" id="omapi-field-' . $setting . '" class="omapi-select" name="omapi[' . $id . '][' . $setting . ']" data-placeholder="' . esc_attr__( 'Type to search and select post(s)...', 'optin-monster-api' ) . '">', esc_html__( 'Load campaign specifically on:', 'optin-monster-api' ), esc_html__( 'Loads the campaign on the selected posts and/or pages.', 'optin-monster-api' ) );
						break 2;

					case 'categories':
						$categories = get_categories();
						if ( $categories ) {
							ob_start();
							wp_category_checklist( 0, 0, (array) $value, false, null, true );
							$cats = ob_get_clean();
							$ret  = $this->get_custom_field( 'categories', $cats, esc_html__( 'Load campaign on post categories:', 'optin-monster-api' ) );
						}
						break;

					case 'taxonomies':
						// Attempt to load post tags.
						$html = '';
						$tags = get_taxonomy( 'post_tag' );
						if ( $tags ) {
							$tag_terms = get_tags();
							if ( $tag_terms ) {
								$display = (array) $value;
								$display = isset( $display['post_tag'] ) ? implode( ',', $display['post_tag'] ) : '';
								$html    = $this->get_custom_field( $setting, '<input type="hidden" value="' . esc_attr( $display ) . '" id="omapi-field-' . $setting . '" class="omapi-select" name="tax_input[post_tag][]" data-placeholder="' . esc_attr__( 'Type to search and select post tag(s)...', 'optin-monster-api' ) . '">', esc_html__( 'Load campaign on post tags:', 'optin-monster-api' ), esc_html__( 'Loads the campaign on the selected post tags.', 'optin-monster-api' ) );
							}
						}

						// Possibly load taxonomies setting if they exist.
						$taxonomies                = get_taxonomies(
							array(
								'public'   => true,
								'_builtin' => false,
							)
						);
						$taxonomies['post_format'] = 'post_format';
						$data                      = array();

						if ( $this->base->is_woocommerce_active() ) {
							unset( $taxonomies['product_cat'] );
							unset( $taxonomies['product_tag'] );
						}

						// Allow returned taxonmies to be filtered before creating UI.
						$taxonomies = apply_filters( 'optin_monster_api_setting_ui_taxonomies', $taxonomies );

						if ( $taxonomies ) {
							foreach ( $taxonomies as $taxonomy ) {
								$terms = get_terms( $taxonomy );
								if ( $terms ) {
									ob_start();
									$display = (array) $value;
									$display = isset( $display[ $taxonomy ] ) ? $display[ $taxonomy ] : array();
									$tax     = get_taxonomy( $taxonomy );
									$args    = array(
										'descendants_and_self' => 0,
										'selected_cats' => (array) $display,
										'popular_cats'  => false,
										'walker'        => null,
										'taxonomy'      => $taxonomy,
										'checked_ontop' => true,
									);
									wp_terms_checklist( 0, $args );
									$output = ob_get_clean();
									if ( ! empty( $output ) ) {
										$data[ $taxonomy ] = $this->get_custom_field( 'taxonomies', $output, esc_html__( 'Load campaign on ', 'optin-monster-api' ) . strtolower( $tax->labels->name ) . ':' );
									}
								}
							}
						}

						// If we have taxonomies, add them to the taxonomies key.
						if ( ! empty( $data ) ) {
							foreach ( $data as $setting ) {
								$html .= $setting;
							}
						}

						// Return the data.
						$ret = $html;
						break;

					case 'show':
						$ret = $this->get_custom_field( 'show', $this->get_show_fields( $value ), esc_html__( 'Load campaign on post types and archives:', 'optin-monster-api' ) );
						break;

					case 'mailpoet':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Save lead to MailPoet?', 'optin-monster-api' ), esc_html__( 'If checked, successful campaign leads will be saved to MailPoet.', 'optin-monster-api' ) );
						break 2;

					case 'mailpoet_list':
						$ret = $this->get_dropdown_field( $setting, $value, $id, $this->base->mailpoet->get_lists(), esc_html__( 'Add lead to this MailPoet list:', 'optin-monster-api' ), esc_html__( 'All successful leads for the campaign will be added to this particular MailPoet list.', 'optin-monster-api' ) );
						break 2;

					case 'mailpoet_use_phone':
						$phone_field = get_post_meta( $optin_id, '_omapi_mailpoet_phone_field', true );

						$ret = $this->get_checkbox_field( $setting, ! empty( $phone_field ), $id, esc_html__( 'Save phone number to MailPoet?', 'optin-monster-api' ), esc_html__( 'If checked, Phone number will be saved in Mailpoet.', 'optin-monster-api' ) );
						break 2;

					case 'mailpoet_phone_field':
						$ret .= $this->get_dropdown_field( $setting, $value, $id, $this->base->mailpoet->get_custom_fields(), esc_html__( 'Select the custom field for phone:', 'optin-monster-api' ), esc_html__( 'If you have a custom field for phone numbers, select the field here.', 'optin-monster-api' ) );
						break 2;

					// Start WooCommerce settings.
					case 'show_on_woocommerce':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on all WooCommerce pages', 'optin-monster-api' ), esc_html__( 'The campaign will show on any page where WooCommerce templates are used.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_shop':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce shop', 'optin-monster-api' ), esc_html__( 'The campaign will show on the product archive page (shop).', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_product':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce products', 'optin-monster-api' ), esc_html__( 'The campaign will show on any single product.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_cart':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Cart', 'optin-monster-api' ), esc_html__( 'The campaign will show on the cart page.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_checkout':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Checkout', 'optin-monster-api' ), esc_html__( 'The campaign will show on the checkout page.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_account':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Customer Account', 'optin-monster-api' ), esc_html__( 'The campaign will show on the WooCommerce customer account pages.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on all WooCommerce Endpoints', 'optin-monster-api' ), esc_html__( 'The campaign will show when on any WooCommerce Endpoint.', 'optin-monster-api' ) );
						break 2;
					case 'is_wc_endpoint_order_pay':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Order Pay endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for order pay is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint_order_received':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Order Received endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for order received is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint_view_order':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce View Order endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for view order is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint_edit_account':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Edit Account endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for edit account is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint_edit_address':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Edit Address endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for edit address is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint_lost_password':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Lost Password endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for lost password is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint_customer_logout':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Customer Logout endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for customer logout is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_endpoint_add_payment_method':
						$ret = $this->get_checkbox_field( $setting, $value, $id, esc_html__( 'Show on WooCommerce Add Payment Method endpoint', 'optin-monster-api' ), esc_html__( 'The campaign will show when the endpoint page for add payment method is displayed.', 'optin-monster-api' ) );
						break 2;

					case 'is_wc_product_category':
						$taxonomy = 'product_cat';
						$terms    = get_terms( $taxonomy );
						if ( $terms ) {
							ob_start();
							$display = isset( $value ) ? (array) $value : array();
							$args    = array(
								'descendants_and_self' => 0,
								'selected_cats'        => $display,
								'popular_cats'         => false,
								'walker'               => null,
								'taxonomy'             => $taxonomy,
								'checked_ontop'        => true,
							);
							wp_terms_checklist( 0, $args );
							$output = ob_get_clean();
							if ( ! empty( $output ) ) {
								$ret = $this->get_custom_field( $setting, $output, esc_html__( 'Show on WooCommerce Product Categories:', 'optin-monster-api' ) );
							}
						}
						break 2;

					case 'is_wc_product_tag':
						$taxonomy = 'product_tag';
						$terms    = get_terms( $taxonomy );
						if ( $terms ) {
							ob_start();
							$display = isset( $value ) ? (array) $value : array();
							$args    = array(
								'descendants_and_self' => 0,
								'selected_cats'        => $display,
								'popular_cats'         => false,
								'walker'               => null,
								'taxonomy'             => $taxonomy,
								'checked_ontop'        => true,
							);
							wp_terms_checklist( 0, $args );
							$output = ob_get_clean();
							if ( ! empty( $output ) ) {
								$ret = $this->get_custom_field( $setting, $output, esc_html__( 'Show on WooCommerce Product Tags:', 'optin-monster-api' ) );
							}
						}
						break 2;

					default:
						break 2;
				}
				break;
			case 'note':
				switch ( $setting ) {
					case 'sidebar_widget_notice':
						$ret = $this->get_optin_type_note( $setting, esc_html__( 'Use Widgets to set Sidebar output', 'optin-monster-api' ), esc_html__( 'You can set this campaign to show in your sidebars using the OptinMonster widget within your sidebars.', 'optin-monster-api' ), 'widgets.php', esc_html__( 'Go to Widgets', 'optin-monster-api' ) );
						break 2;
					default:
						break 2;
				}
				break;
			default:
				break;
		}

		// Return the setting output.
		return apply_filters( 'optin_monster_api_setting_ui', $ret, $setting, $id );

	}

	/**
	 * Returns the user output settings available for an optin.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of user dropdown values.
	 */
	public function get_user_output() {

		return apply_filters(
			'optin_monster_api_user_output',
			array(
				array(
					'name'  => esc_html__( 'Show campaign to all visitors and users', 'optin-monster-api' ),
					'value' => 'all',
				),
				array(
					'name'  => esc_html__( 'Show campaign to only visitors (not logged-in)', 'optin-monster-api' ),
					'value' => 'out',
				),
				array(
					'name'  => esc_html__( 'Show campaign to only users (logged-in)', 'optin-monster-api' ),
					'value' => 'in',
				),
			)
		);

	}


	/**
	 * Retrieves the UI output for the single posts show setting.
	 *
	 * @since 1.9.10
	 *
	 * @param array $value  The meta index value for the show setting.
	 * @return string $html HTML representation of the data.
	 */
	public function get_show_fields( $value ) {

		// Increment the global tabindex counter.
		$this->tabindex++;

		$output     = '<label for="omapi-field-show-index" class="omapi-custom-label">';
		$output    .= '<input type="checkbox" id="omapi-field-show-index" name="omapi[optins][show][]" value="index"' . checked( in_array( 'index', (array) $value ), 1, false ) . ' /> ' . esc_html__( 'Front Page and Search Pages', 'optin-monster-api' ) . '</label><br />';
		$post_types = get_post_types( array( 'public' => true ) );
		foreach ( (array) $post_types as $show ) {
			$pt_object = get_post_type_object( $show );
			$label     = $pt_object->labels->name;
			$output   .= '<label for="omapi-field-show-' . esc_html( strtolower( $label ) ) . '" class="omapi-custom-label">';
			$output   .= '<input type="checkbox" id="omapi-field-show-' . esc_html( strtolower( $label ) ) . '" name="omapi[optins][show][]" tabindex="' . $this->tabindex . '" value="' . esc_attr( $show ) . '"' . checked( in_array( $show, (array) $value ), 1, false ) . ' /> ' . esc_html( $label ) . '</label><br />';

			// Increment the global tabindex counter and iterator.
			$this->tabindex++;
		}

		return $output;

	}

	/**
	 * Retrieves the UI output for a plain text input field setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $setting The name of the setting to be saved to the DB.
	 * @param mixed   $value    The value of the setting.
	 * @param string  $id      The setting ID to target for name field.
	 * @param string  $label   The label of the input field.
	 * @param string  $desc    The description for the input field.
	 * @param string  $place   Placeholder text for the field.
	 * @param array   $classes  Array of classes to add to the field.
	 * @param boolean $copy   Turn on clipboard copy button and make field readonly
	 * @return string $html   HTML representation of the data.
	 */
	public function get_text_field( $setting, $value, $id, $label, $desc = false, $place = false, $classes = array(), $copy = false ) {

		// Increment the global tabindex counter.
		$this->tabindex++;

		// Check for copy set
		$readonly_output = $copy ? 'readonly' : '';

		// Build the HTML.
		$field          = '<div class="omapi-field-box omapi-text-field omapi-field-box-' . $setting . ' omapi-clear">';
				$field .= '<p class="omapi-field-wrap"><label for="omapi-field-' . $setting . '">' . $label . '</label><br />';
				$field .= '<input type="text" id="omapi-field-' . $setting . '" class="' . implode( ' ', (array) $classes ) . '" name="omapi[' . $id . '][' . $setting . ']" tabindex="' . $this->tabindex . '" value="' . esc_attr( $value ) . '"' . ( $place ? ' placeholder="' . $place . '"' : '' ) . $readonly_output . ' />';
		if ( $copy ) {
			$field .= '<span class="omapi-copy-button button"  data-clipboard-target="#omapi-field-' . $setting . '">Copy to clipboard</span>';
		}
		if ( $desc ) {
			$field .= '<br /><label for="omapi-field-' . $setting . '"><span class="omapi-field-desc">' . $desc . '</span></label>';
		}
				$field .= '</p>';
		$field         .= '</div>';

		// Return the HTML.
		return apply_filters( 'optin_monster_api_text_field', $field, $setting, $value, $id, $label );

	}

	/**
	 * Retrieves the UI output for a password input field setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting The name of the setting to be saved to the DB.
	 * @param mixed  $value    The value of the setting.
	 * @param string $id      The setting ID to target for name field.
	 * @param string $label   The label of the input field.
	 * @param string $desc    The description for the input field.
	 * @param string $place   Placeholder text for the field.
	 * @param array  $classes  Array of classes to add to the field.
	 * @param bool   $hidden    If the field should be hidden by default.
	 * @return string $html   HTML representation of the data.
	 */
	public function get_password_field( $setting, $value, $id, $label, $desc = false, $place = false, $classes = array(), $hidden = false ) {

		// Increment the global tabindex counter.
		$this->tabindex++;

		// if the field should be hidden, add the omapi-hidden class
		$hidden_class = $hidden ? 'omapi-hidden' : '';

		// Build the HTML.
		$field          = '<div class="omapi-field-box omapi-password-field omapi-field-box-' . $setting . ' omapi-clear ' . $hidden_class . '">';
			$field     .= '<p class="omapi-field-wrap"><label for="omapi-field-' . $setting . '">' . $label . '</label><br />';
				$field .= '<input type="password" id="omapi-field-' . $setting . '" class="' . implode( ' ', (array) $classes ) . '" name="omapi[' . $id . '][' . $setting . ']" tabindex="' . $this->tabindex . '" value="' . esc_attr( $value ) . '"' . ( $place ? ' placeholder="' . $place . '"' : '' ) . ' />';
		if ( $desc ) {
			$field .= '<br /><label for="omapi-field-' . $setting . '"><span class="omapi-field-desc">' . $desc . '</span></label>';
		}
			$field .= '</p>';
		$field     .= '</div>';

		// Return the HTML.
		return apply_filters( 'optin_monster_api_password_field', $field, $setting, $value, $id, $label );

	}

	/**
	 * Retrieves the UI output for a password input field setting.
	 *
	 * @since 1.8.0
	 *
	 * @param string $setting The name of the setting to be saved to the DB.
	 * @param mixed  $value    The value of the setting.
	 * @param string $id      The setting ID to target for name field.
	 * @param string $label   The label of the input field.
	 * @param string $desc    The description for the input field.
	 * @return string $html   HTML representation of the data.
	 */
	public function get_api_field( $setting, $value, $id, $label, $desc = false ) {

		// Increment the global tabindex counter.
		$this->tabindex++;
		$value = trim( $value );

		// Build the HTML.
		$field = '<div class="omapi-field-box omapi-field-wrap omapi-api-field omapi-field-box-' . $setting . ' omapi-clear">';
		if ( empty( $value ) ) {
			$field .= "<p><input type='submit' id='{$id}' class='button button-primary button-large button-hero' value='{$label}'></p>";
			if ( $desc ) {
				$field .= '<p><label for="omapi-field-' . $setting . '"><span class="omapi-field-desc">' . $desc . '</span></label></p>';
			}
		} else {
			$field .= '<p>' . __( 'Your account is <strong>connected.</strong>', 'optin-monster-api' ) . '</p><p><button id="omapiDisconnectButton" class="button button-omapi-gray button-hero">' . esc_html__( 'Disconnect', 'optin-monster-api' ) . '</button></p>';
		}
		$field .= '</div>';

		// Return the HTML.
		return apply_filters( 'optin_monster_api_field', $field, $setting, $value, $id, $label );

	}

	/**
	 * Retrieves the UI output for a hidden input field setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting The name of the setting to be saved to the DB.
	 * @param mixed  $value    The value of the setting.
	 * @param string $id      The setting ID to target for name field.
	 * @param array  $classes  Array of classes to add to the field.
	 * @return string $html   HTML representation of the data.
	 */
	public function get_hidden_field( $setting, $value, $id, $classes = array() ) {

		// Increment the global tabindex counter.
		$this->tabindex++;

		// Build the HTML.
		$field  = '<div class="omapi-field-box omapi-hidden-field omapi-field-box-' . $setting . ' omapi-clear omapi-hidden">';
		$field .= '<input type="hidden" id="omapi-field-' . $setting . '" class="' . implode( ' ', (array) $classes ) . '" name="omapi[' . $id . '][' . $setting . ']" tabindex="' . $this->tabindex . '" value="' . esc_attr( $value ) . '" />';
		$field .= '</div>';

		// Return the HTML.
		return apply_filters( 'optin_monster_api_hidden_field', $field, $setting, $value, $id );

	}
	/**
	 * Retrieves the UI output for a plain textarea field setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting The name of the setting to be saved to the DB.
	 * @param mixed  $value    The value of the setting.
	 * @param string $id      The setting ID to target for name field.
	 * @param string $label   The label of the input field.
	 * @param string $desc    The description for the input field.
	 * @param string $place   Placeholder text for the field.
	 * @param array  $classes  Array of classes to add to the field.
	 * @return string $html   HTML representation of the data.
	 */
	public function get_textarea_field( $setting, $value, $id, $label, $desc = false, $place = false, $classes = array() ) {

		// Increment the global tabindex counter.
		$this->tabindex++;

		// Build the HTML.
		$field          = '<div class="omapi-field-box omapi-textarea-field omapi-field-box-' . $setting . ' omapi-clear">';
			$field     .= '<p class="omapi-field-wrap"><label for="omapi-field-' . $setting . '">' . $label . '</label><br />';
				$field .= '<textarea id="omapi-field-' . $setting . '" class="' . implode( ' ', (array) $classes ) . '" name="omapi[' . $id . '][' . $setting . ']" rows="5" tabindex="' . $this->tabindex . '"' . ( $place ? ' placeholder="' . $place . '"' : '' ) . '>' . $value . '</textarea>';
		if ( $desc ) {
			$field .= '<br /><label for="omapi-field-' . $setting . '"><span class="omapi-field-desc">' . $desc . '</span></label>';
		}
			$field .= '</p>';
		$field     .= '</div>';

		// Return the HTML.
		return apply_filters( 'optin_monster_api_textarea_field', $field, $setting, $value, $id, $label );

	}

	/**
	 * Retrieves the UI output for a checkbox setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting The name of the setting to be saved to the DB.
	 * @param mixed  $value    The value of the setting.
	 * @param string $id      The setting ID to target for name field.
	 * @param string $label   The label of the input field.
	 * @param string $desc    The description for the input field.
	 * @param array  $classes  Array of classes to add to the field.
	 * @return string $html   HTML representation of the data.
	 */
	public function get_checkbox_field( $setting, $value, $id, $label, $desc = false, $classes = array() ) {

		// Increment the global tabindex counter.
		$this->tabindex++;

		// Build the HTML.
		$field          = '<div class="omapi-field-box omapi-checkbox-field omapi-field-box-' . $setting . ' omapi-clear">';
			$field     .= '<p class="omapi-field-wrap"><label for="omapi-field-' . $setting . '">' . $label . '</label><br />';
				$field .= '<input type="checkbox" id="omapi-field-' . $setting . '" class="' . implode( ' ', (array) $classes ) . '" name="omapi[' . $id . '][' . $setting . ']" tabindex="' . $this->tabindex . '" value="' . esc_attr( $value ) . '"' . checked( $value, 1, false ) . ' /> ';
		if ( $desc ) {
			$field .= '<label for="omapi-field-' . $setting . '"><span class="omapi-field-desc">' . $desc . '</span></label>';
		}
			$field .= '</p>';
		$field     .= '</div>';

		// Return the HTML.
		return apply_filters( 'optin_monster_api_checkbox_field', $field, $setting, $value, $id, $label );

	}

	/**
	 * Retrieves the UI output for a dropdown field setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting The name of the setting to be saved to the DB.
	 * @param mixed  $value    The value of the setting.
	 * @param string $id      The setting ID to target for name field.
	 * @param array  $data     The data to be used for option fields.
	 * @param string $label   The label of the input field.
	 * @param string $desc    The description for the input field.
	 * @param array  $classes  Array of classes to add to the field.
	 * @return string $html   HTML representation of the data.
	 */
	public function get_dropdown_field( $setting, $value, $id, $data, $label, $desc = false, $classes = array() ) {

		// Increment the global tabindex counter.
		$this->tabindex++;

		// Build the HTML.
		$field          = '<div class="omapi-field-box omapi-dropdown-field omapi-field-box-' . $setting . ' omapi-clear">';
			$field     .= '<p class="omapi-field-wrap"><label for="omapi-field-' . $setting . '">' . $label . '</label><br />';
				$field .= '<select id="omapi-field-' . $setting . '" class="' . implode( ' ', (array) $classes ) . '" name="omapi[' . $id . '][' . $setting . ']" tabindex="' . $this->tabindex . '">';
		foreach ( $data as $i => $info ) {
			$field .= '<option value="' . esc_attr( $info['value'] ) . '"' . selected( $info['value'], $value, false ) . '>' . $info['name'] . '</option>';
		}
				$field .= '</select>';
		if ( $desc ) {
			$field .= '<br /><label for="omapi-field-' . $setting . '"><span class="omapi-field-desc">' . $desc . '</span></label>';
		}
			$field .= '</p>';
		$field     .= '</div>';

		// Return the HTML.
		return apply_filters( 'omapi_dropdown_field', $field, $setting, $value, $id, $label, $data );

	}

	/**
	 * Retrieves the UI output for a field with a custom output.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting The name of the setting to be saved to the DB.
	 * @param mixed  $value    The value of the setting.
	 * @param string $label   The label of the input field.
	 * @param string $desc    The description for the input field.
	 * @return string $html   HTML representation of the data.
	 */
	public function get_custom_field( $setting, $value, $label, $desc = false ) {

		// Build the HTML.
		$field      = '<div class="omapi-field-box omapi-custom-field omapi-field-box-' . $setting . ' omapi-clear">';
			$field .= '<p class="omapi-field-wrap"><label for="omapi-field-' . $setting . '">' . $label . '</label></p>';
			$field .= $value;
		if ( $desc ) {
			$field .= '<br /><label for="omapi-field-' . $setting . '"><span class="omapi-field-desc">' . $desc . '</span></label>';
		}
		$field .= '</div>';

		// Return the HTML.
		return apply_filters( 'optin_monster_api_custom_field', $field, $setting, $value, $label );

	}

	/**
	 * Starts the toggle wrapper for a toggle section.
	 *
	 * @since 1.1.5
	 *
	 * @param $label
	 * @param $desc
	 *
	 * @return mixed|void
	 */
	public function get_toggle_start( $setting, $label, $desc ) {
		$field      = '<div class="omapi-ui-toggle-controller">';
			$field .= '<p class="omapi-field-wrap"><label for="omapi-field-' . $setting . '">' . $label . '</label></p>';
		if ( $desc ) {
			$field .= '<span class="omapi-field-desc">' . $desc . '</span>';
		}
		$field .= '</div>';
		$field .= '<div class="omapi-ui-toggle-content">';

		return apply_filters( 'optin_monster_api_toggle_start_field', $field, $label, $desc );
	}

	/**
	 * Closes toggle wrapper.
	 *
	 * @since 1.1.5
	 * @return string HTML end for toggle start
	 */
	public function get_toggle_end() {

		$field = '</div>';

		return apply_filters( 'optin_monster_api_toggle_end_field', $field );
	}

	/**
	 *  Helper note output with title, text, and admin linked button.
	 *
	 * @since 1.1.5
	 *
	 * @param $setting
	 * @param $title
	 * @param $text
	 * @param $admin_page
	 * @param $button
	 *
	 * @return mixed|void
	 */
	public function get_optin_type_note( $setting, $title, $text, $admin_page, $button ) {

		$field = '<div class="omapi-field-box  omapi-inline-notice omapi-field-box-' . $setting . ' omapi-clear">';
		if ( $title ) {
			$field .= '<p class="omapi-notice-title">' . $title . '</p>';
		}
		if ( $text ) {
			$field .= '<p class="omapi-field-desc">' . $text . '</p>';
		}
		if ( $admin_page && $button ) {
			// Increment the global tabindex counter.
			$this->tabindex++;
			$field .= '<a href="' . esc_url_raw( admin_url( $admin_page ) ) . '" class="button button-small" title="' . $button . '" target="_blank">' . $button . '</a>';
		}
		$field .= '</div>';

		return apply_filters( 'optin_monster_api_inline_note_display', $field, $title, $text, $admin_page, $button );
	}

	/**
	 * Support Link output
	 *
	 * @param $setting
	 *
	 * @return mixed|void HTML of the list filtered as needed
	 */
	public function get_support_links( $setting, $title ) {

		$field = '';

		$field .= '<div class="omapi-support-links ' . $setting . '"><h3>' . $title . '</h3><ul>';
		$field .= '<li><a target="_blank" rel="noopener" href="' . esc_url( 'https://optinmonster.com/docs/' ) . '">' . esc_html__( 'Documentation', 'optin-monster-api' ) . '</a></li>';
		$field .= '<li><a target="_blank" rel="noopener noreferrer" href="' . esc_url( 'https://wordpress.org/plugins/optinmonster/changelog/' ) . '">' . esc_html__( 'Changelog', 'optin-monster-api' ) . '</a></li>';
		$field .= '<li><a target="_blank" rel="noopener" href="' . esc_url( OPTINMONSTER_APP_URL . '/account/support/' ) . '">' . esc_html__( 'Create a Support Ticket', 'optin-monster-api' ) . '</a></li>';
		$field .= '</ul></div>';

		return apply_filters( 'optin_monster_api_support_links', $field, $setting );
	}

	public function get_plugin_report( $setting, $title ) {

		$field = '';

		$field .= '<div class="omapi-support-data ' . $setting . '"><h3>' . $title . '</h3>';
		$link   = OPTINMONSTER_APP_URL . '/account/support/';
		$field .= '<p>' . sprintf( wp_kses( __( 'Download the report and attach to your <a href="%s">support ticket</a> to help speed up the process.', 'optin-monster-api' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $link ) ) . '</p>';
		$field .= '<a href="#" id="js--omapi-support-pdf" class="button button-primary button-large button-hero omapi-support-data-button" title="' . esc_attr__( 'Download a PDF Report for Support', 'optin-monster-api' ) . '" target="_blank">' . esc_html__( 'Download PDF Report', 'optin-monster-api' ) . '</a>';
		$field .= '</div>';

		return apply_filters( 'optin_monster_api_support_data', $field, $setting, $title );
	}

	/**
	 * Returns the WooCommerce tab output.
	 *
	 * @since 1.7.0
	 *
	 * @return string $output The WooCommerce panel output.
	 */
	public function get_woocommerce() {

		$keys_tab       = OMAPI_WooCommerce::version_compare( '3.4.0' ) ? 'advanced' : 'api';
		$keys_admin_url = admin_url( "admin.php?page=wc-settings&tab={$keys_tab}&section=keys" );
		$output         = '';

		if ( ! OMAPI_WooCommerce::is_minimum_version() && OMAPI_WooCommerce::is_connected() ) {

			$output .= '<p>' . esc_html( sprintf( __( 'OptinMonster requires WooCommerce %s or above.', 'optin-monster-api' ), OMAPI_WooCommerce::MINIMUM_VERSION ) ) . '</p>'
				. '<p>' . esc_html_x( 'This site is currently running: ', 'the current version of WooCommerce: "WooCommerce x.y.z"', 'optin-monster-api' )
				. '<code>WooCommerce ' . esc_html( OMAPI_WooCommerce::version() ) . '</code>.</p>'
				. '<p>' . esc_html__( 'Please upgrade to the latest version of WooCommerce to enjoy deeper integration with OptinMonster.', 'optin-monster-api' ) . '</p>';

		} elseif ( OMAPI_WooCommerce::is_connected() ) {

			// Set some default key details.
			$defaults = array(
				'key_id'        => '',
				'description'   => esc_html__( 'no description found', 'optin-monster-api' ),
				'truncated_key' => esc_html__( 'no truncated key found', 'optin-monster-api' ),
			);

			// Get the key details.
			$key_id        = $this->base->get_option( 'woocommerce', 'key_id' );
			$details       = OMAPI_WooCommerce::get_key_details_by_id( $key_id );
			$r             = wp_parse_args( array_filter( $details ), $defaults );
			$description   = esc_html( $r['description'] );
			$truncated_key = esc_html( $r['truncated_key'] );

			// Set up the key details for output.
			$key_string = "<code>{$description} (&hellip;{$truncated_key})</code>";
			$key_url    = esc_url( add_query_arg( 'edit-key', $r['key_id'], $keys_admin_url ) );

			$output .= '<p>' . esc_html__( 'WooCommerce is currently connected to OptinMonster with the following key:', 'optin-monster-api' ) . '</p>';
			$output .= '<p>' . $key_string . ' <a href="' . $key_url . '">View key</a></p>';
			$output .= '<p>' . esc_html__( 'You need to disconnect WooCommerce, below, to remove your keys from OptinMonster, or to change the consumer key/secret pair associated with OptinMonster.', 'optin-monster-api' ) . '</p>';
			$output .= $this->get_hidden_field( 'disconnect', '1', 'woocommerce' );

		} else {

			$output .= '<p>' . sprintf( __( 'In order to integrate WooCommerce with the Display Rules in the campaign builder, OptinMonster needs <a href="%s" target="_blank">WooCommerce REST API credentials</a>. OptinMonster only needs Read access permissions to work. Click below to have us auto-generate the consumer key/secret pair, you can manually enter your own.', 'optin-monster-api' ), esc_url( $keys_admin_url ) ) . '</p>';
			$output .= '<div class="omapi-hidden">';
			$output .= $this->get_text_field(
				'consumer_key',
				'',
				'woocommerce',
				esc_html__( 'Consumer key', 'optin-monster-api' ),
				'',
				'ck_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
			);
			$output .= $this->get_text_field(
				'consumer_secret',
				'',
				'woocommerce',
				esc_html__( 'Consumer secret', 'optin-monster-api' ),
				'',
				'cs_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
			);
			$output .= '<span class="omapi-hidden"><input class="button button-secondary" type="submit" name="omapi_woocommerce_connect" value="' . esc_attr__( 'Connect WooCommerce', 'optin-monster-api' ) . '" /></span>';
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Echo out plugin header banner
	 *
	 * @since 1.1.5.2
	 */
	public function output_plugin_screen_banner() {
		$this->base->output_view( 'plugin-banner.php' );
	}

	/**
	 * Called whenever a signup link is displayed, this function will
	 * check if there's an affiliate ID specified.
	 *
	 * There are three ways to specify an ID, ordered by highest to lowest priority
	 * - add_filter( 'optinmonster_sas_id', function() { return 1234; } );
	 * - define( 'OPTINMONSTER_SAS_ID', 1234 );
	 * - get_option( 'optinmonster_sas_id' ); (with the option being in the wp_options
	 * table) If an ID is present, returns the affiliate link with the affiliate ID. If no ID is
	 * present, just returns the OptinMonster WP landing page link instead.
	 */
	public function get_sas_link() {

		global $omSasId;
		$omSasId = '';

		// Check if sas ID is a constant
		if ( defined( 'OPTINMONSTER_SAS_ID' ) ) {
			$omSasId = OPTINMONSTER_SAS_ID;
		}

		// Now run any filters that may be on the sas ID
		$omSasId = apply_filters( 'optinmonster_sas_id', $omSasId );

		/**
		 * If we still don't have a sas ID by this point
		 * check the DB for an option
		 */
		if ( empty( $omSasId ) ) {
			$sasId = get_option( 'optinmonster_sas_id', $omSasId );
		}

		// Return the regular WP landing page by default
		$url = self::LANDING_URL;

		// Return the sas link if we have a sas ID
		if ( ! empty( $omSasId ) ) {
			$url = $this->get_affiliate_url( $omSasId );
		}

		return apply_filters(
			'optin_monster_action_link',
			$url,
			array(
				'type' => 'sas',
				'id'   => $omSasId,
			)
		);
	}

	/**
	 * Called whenever a signup link is displayed, this function will
	 * check if there's a trial ID specified.
	 *
	 * There are three ways to specify an ID, ordered by highest to lowest priority
	 * - add_filter( 'optinmonster_trial_id', function() { return 1234; } );
	 * - define( 'OPTINMONSTER_TRIAL_ID', 1234 );
	 * - get_option( 'optinmonster_trial_id' ); (with the option being in the wp_options
	 * table) If an ID is present, returns the trial link with the affiliate ID. If no ID is
	 * present, just returns the OptinMonster WP landing page URL.
	 */
	public function get_trial_link() {

		global $omTrialId;
		$omTrialId = '';

		// Check if trial ID is a constant
		if ( defined( 'OPTINMONSTER_TRIAL_ID' ) ) {
			$omTrialId = OPTINMONSTER_TRIAL_ID;
		}

		// Now run any filters that may be on the trial ID
		$omTrialId = apply_filters( 'optinmonster_trial_id', $omTrialId );

		/**
		 * If we still don't have a trial ID by this point
		 * check the DB for an option
		 */
		if ( empty( $omTrialId ) ) {
			$omTrialId = get_option( 'optinmonster_trial_id', $omTrialId );
		}

		// Return the regular WP landing page by default
		$url = self::LANDING_URL;

		// Return the trial link if we have a trial ID
		if ( ! empty( $omTrialId ) ) {
			$url = $this->get_affiliate_url( $omTrialId )
				. '%2Ffree-trial%2F%3Fid%3D' . urlencode( trim( $omTrialId ) );
		}

		return apply_filters(
			'optin_monster_action_link',
			$url,
			array(
				'type' => 'trial',
				'id'   => $omTrialId,
			)
		);
	}

	/**
	 * Get the affiliate url for given id.
	 *
	 * @since  1.8.4
	 *
	 * @param  mixed $reference_id The reference ID.
	 *
	 * @return string               The affilaite url.
	 */
	public function get_affiliate_url( $reference_id ) {
		return 'https://www.shareasale.com/r.cfm?u='
			. urlencode( trim( $reference_id ) )
			. '&b=601672&m=49337&afftrack=&urllink=optinmonster.com';
	}

	public function get_action_link() {
		global $omTrialId;

		if ( ! empty( $omTrialId ) ) {
			return $this->get_trial_link();
		}

		// Returns the sas or fallback url, which is self::LANDING_URL.
		return $this->get_sas_link();
	}

	public function has_trial_link() {
		$link = $this->get_trial_link();

		return strpos( $link, 'optinmonster.com/wp' ) === false;
	}

	/**
	 * Get the parent slug (contextual based on beta being enabled).
	 *
	 * @since  1.9.10
	 *
	 * @return string
	 */
	public function parent_slug() {
		return 'optin-monster-api-settings';
	}

	/**
	 * Get the OM settings url.
	 *
	 * @since  1.9.10
	 *
	 * @param  string $view The view query arg.
	 * @param  array  $args Array of query args.
	 *
	 * @return string
	 */
	public function get_settings_link( $view = '', $args = array() ) {
		return $this->get_dashboard_link( $view, $args );
	}

	/**
	 * Get the contextual OM dashboard url.
	 *
	 * @since  1.9.10
	 *
	 * @param  string $view The view query arg.
	 * @param  array  $args Array of query args.
	 *
	 * @return string
	 */
	public function get_dashboard_link( $view = '', $args = array() ) {
		$page = ! $this->base->get_api_credentials() && ! isset( $_GET['om-bypass-api-check'] )
			? 'optin-monster-api-welcome'
			: $this->parent_slug();

		$defaults = array( 'page' => $page );
		if ( $view && 'optin-monster-api-settings' === $page ) {
			$defaults['optin_monster_api_view'] = $view;
		}

		return $this->admin_page_url( wp_parse_args( $args, $defaults ) );
	}

	/**
	 * Get an admin page url.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public function admin_page_url( $args = array() ) {
		$url = add_query_arg( $args, admin_url( 'admin.php' ) );

		return esc_url_raw( $url );
	}

	/**
	 * Redirects to main OM page.
	 *
	 * @since  1.9.10
	 *
	 * @param  string $view The view query arg.
	 * @param  array  $args Array of query args.
	 *
	 * @return void
	 */
	public function redirect_to_dashboard( $view = '', $args = array() ) {
		$url = $this->get_dashboard_link( $view, $args );
		wp_safe_redirect( esc_url_raw( $url ) );
		exit;
	}


}
