<?php
/**
 * Welcome class.
 *
 * @since 1.1.4
 *
 * @package OMAPI
 * @author  Devin Vinson
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Welcome class.
 *
 * @since 1.1.4
 */
class OMAPI_Welcome {

	/**
	 * Holds the class object.
	 *
	 * @since 1.1.4.2
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.1.4.2
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.1.4.2
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Holds the welcome slug.
	 *
	 * @since 1.1.4.2
	 *
	 * @var string
	 */
	public $hook;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.1.4.2
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

		// Set our object.
		$this->set();

		// Load the Welcome screen
		add_action( 'admin_menu', array( $this, 'register_welcome_page' ) );

		// maybe redirect
		add_action( 'admin_init', array( $this, 'maybe_welcome_redirect' ) );

		// maybe add body classes
		add_action( 'current_screen', array( $this, 'welcome_screen_helpers' ) );

		// Maybe load a dashboard widget.
		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.1.4.2
	 */
	public function set() {

		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
		$this->view     = isset( $_GET['optin_monster_api_view'] ) ? stripslashes( $_GET['optin_monster_api_view'] ) : $this->base->get_view();

	}

	public function welcome_screen_helpers() {

		$screen = get_current_screen();
		if ( 'optinmonster_page_optin-monster-api-welcome' === $screen->id ) {
			add_filter( 'admin_body_class', array( $this, 'add_body_classes' ) );
		}

		// Make sure welcome page is always first page to view.
		if ( 'toplevel_page_' . $this->base->menu->parent_slug() === $screen->id ) {

			// If We don't have the OM API Key set, and the "Bypass welcome screen" query string isn't set
			if ( ! $this->base->get_api_credentials() && ! isset( $_GET['om-bypass-api-check'] ) ) {
				$this->redirect_to();
			}
		}
	}
	/**
	 * Add body classes
	 */
	public function add_body_classes( $classes ) {

		$classes .= ' omapi-welcome ';

		return $classes;
	}

	/**
	 * Maybe Redirect new users to the welcome page after install.
	 *
	 * @since 1.1.4.2
	 */
	public function maybe_welcome_redirect() {

		$options = $this->base->get_option();

		// Check for the new option
		if ( ! empty( $options['welcome']['status'] ) ) {

			// Check if they have been welcomed
			if ( 'none' === $options['welcome']['status'] ) {

				// Update the option.
				$options['welcome']['status'] = 'welcomed';
				update_option( 'optin_monster_api', $options );

				// If this was not a bulk activate send them to the page
				if ( ! isset( $_GET['activate-multi'] ) ) {
					// Only redirect if no trial is found.
					$trial = $this->base->menu->has_trial_link();
					if ( ! $trial ) {
						$this->redirect_to();
					}
				}
			}
		} else {
			// welcome option didn't exist so must be pre-existing user updating
			$options['welcome']['status'] = 'welcomed';
			update_option( 'optin_monster_api', $options );
		}

	}

	/**
	 * Loads the OptinMonster admin menu.
	 *
	 * @since 1.1.4.2
	 */
	public function register_welcome_page() {
		$slug       = 'optin-monster-api-welcome';
		$is_current = isset( $_GET['page'] ) && $slug === $_GET['page'];

		$this->hook = add_submenu_page(
			$is_current ? 'optin-monster-api-settings' : 'optin-monster-api-settings-no-menu', // parent slug
			esc_html__( 'Welcome to OptinMonster', 'optin-monster-api' ), // page title,
			esc_html__( 'Welcome', 'optin-monster-api' ),
			apply_filters( 'optin_monster_api_menu_cap', 'manage_options', $slug ), // cap
			$slug, // slug
			array( $this, 'callback_to_display_page' ) // callback
		);

		// Load settings page assets.
		if ( $this->hook ) {
			add_action( 'load-' . $this->hook, array( $this, 'maybe_redirect' ) );
			add_action( 'load-' . $this->hook, array( $this, 'assets' ) );
		}

	}

	/**
	 * If user already has credentials set, redirect them to the main page.
	 *
	 * @since  1.9.10
	 *
	 * @return void
	 */
	public function maybe_redirect() {
		if ( $this->base->get_api_credentials() ) {
			$this->base->menu->redirect_to_dashboard();
		}
	}

	/**
	 * Outputs the OptinMonster settings page.
	 *
	 * @since 1.1.4.2
	 */
	public function callback_to_display_page() {
		wp_enqueue_script(
			$this->base->plugin_slug . '-connect',
			$this->base->url . 'assets/dist/js/connect.min.js',
			array( 'jquery' ),
			$this->base->asset_version(),
			true
		);

		wp_localize_script(
			$this->base->plugin_slug . '-connect',
			'OMAPI',
			array(
				'app_url'  => trailingslashit( OPTINMONSTER_APP_URL ),
				'blogname' => esc_attr( get_option( 'blogname' ) ),
			)
		);

		$this->base->output_view(
			'welcome.php',
			array(
				'button_text' => $this->base->menu->has_trial_link() ? __( 'Get Started for Free', 'optin-monster-api' ) : __( 'Get OptinMonster Now', 'optin-monster-api' ),
				'button_link' => esc_url( $this->base->menu->get_action_link() ),
				'api_link'    => esc_url_raw( admin_url( 'admin.php?page=' . $this->base->menu->parent_slug() . '&om-bypass-api-check=true' ) ),
			)
		);
	}

	/**
	 * Loads a dashboard widget if the user has not entered and verified API credentials.
	 *
	 * @since 1.1.5.1
	 */
	public function dashboard_widget() {
		if ( $this->base->get_api_credentials() ) {
			return;
		}

		wp_add_dashboard_widget(
			'optin_monster_db_widget',
			esc_html__( 'Please Connect OptinMonster', 'optin-monster-api' ),
			array( $this, 'dashboard_widget_callback' )
		);

		global $wp_meta_boxes;
		$normal_dashboard      = $wp_meta_boxes['dashboard']['normal']['core'];
		$example_widget_backup = array( 'optin_monster_db_widget' => $normal_dashboard['optin_monster_db_widget'] );
		unset( $normal_dashboard['optin_monster_db_widget'] );
		$sorted_dashboard                             = array_merge( $example_widget_backup, $normal_dashboard );
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}

	/**
	 * Dashboard widget callback.
	 *
	 * @since 1.1.5.1
	 */
	public function dashboard_widget_callback() {
		?>
		<div class="optin-monster-db-widget" style="text-align:center;">
			<p><img src="<?php echo plugins_url( '/assets/css/images/dashboard-icon.png', OMAPI_FILE ); ?>" alt="<?php esc_attr_e( 'Archie', 'optin-monster-api' ); ?>" width="64px" height="64px"></p>
			<h3 style="font-weight:normal;font-size:1.3em;"><?php esc_html_e( 'Please Connect OptinMonster', 'optin-monster-api' ); ?></h3>
			<p><?php _e( 'OptinMonster helps you convert abandoning website visitors into subscribers and customers. <strong>Get more email subscribers now.</strong>', 'optin-monster-api' ); ?></p>
			<p><a href="<?php echo esc_url( $this->base->menu->get_dashboard_link() ); ?>" class="button button-primary" title="<?php esc_attr_e( 'Connect OptinMonster', 'optin-monster-api' ); ?>"><?php esc_html_e( 'Connect OptinMonster', 'optin-monster-api' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 1.1.4.2
	 */
	public function assets() {

		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'scripts' ) );
		add_filter( 'admin_footer_text', array( $this, 'footer' ) );
		add_action( 'in_admin_header', array( $this->base->menu, 'output_plugin_screen_banner' ) );

	}

	/**
	 * Register and enqueue settings page specific CSS.
	 *
	 * @since 1.1.4.2
	 */
	public function styles() {

		wp_register_style( $this->base->plugin_slug . '-settings', plugins_url( '/assets/dist/css/settings.min.css', OMAPI_FILE ), array(), $this->base->asset_version() );
		wp_enqueue_style( $this->base->plugin_slug . '-settings' );

	}

	public function scripts() {
		?>
		<script type="text/javascript">
			jQuery( '#js_omapi-welcome-video-link' )
				.on( 'click', function ( e ) {
					e.preventDefault();
					jQuery( this ).parents( '#js__omapi-video-well' ).addClass( 'active' );
					jQuery( '#js__omapi-welcome-video-frame' ).show().prop( 'src', jQuery( e.currentTarget ).attr( 'href' ) );
				})
		</script>
		<?php
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

		$new_text = sprintf(
			__( 'Thank you for using <a href="%1$s" target="_blank">OptinMonster</a>!', 'optin-monster-api' ),
			'https://optinmonster.com'
		);
		return str_replace( '</span>', '', $text ) . ' | ' . $new_text . '</span>';

	}

	/**
	 * Get the OM welcome url.
	 *
	 * @since  1.9.10
	 *
	 * @return string
	 */
	public function get_link() {
		return $this->base->menu->admin_page_url(
			array(
				'page' => 'optin-monster-api-welcome',
			)
		);
	}

	/**
	 * Redirect to the welcome page.
	 *
	 * @since  1.9.10
	 *
	 * @return void
	 */
	public function redirect_to() {
		$this->base->menu->redirect_to_dashboard(
			'',
			array(
				'page' => 'optin-monster-api-welcome',
			)
		);
	}

}
