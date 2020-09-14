<?php
// phpcs:ignoreFile

namespace AutomateWoo;

use AutomateWoo\Admin\AssetData;
use AutomateWoo\Admin\WCAdminConnectPages;
use Automattic\WooCommerce\Blocks\Assets\AssetDataRegistry;
use Automattic\WooCommerce\Blocks\Package as BlocksPackage;

/**
 * @class Admin
 */
class Admin {

	static function init() {
		$self = __CLASS__; /** @var $self Admin (for IDE) */

		Admin_Ajax::init();
		Admin_Notices::init();
		( new WCAdminConnectPages )->init();

		add_action( 'current_screen', [ $self, 'includes' ] );
		add_action( 'admin_enqueue_scripts', [ $self, 'register_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $self, 'register_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $self, 'enqueue_scripts_and_styles' ], 20 );
		if ( defined( 'WC_ADMIN_PLUGIN_FILE' ) ) {
			add_action( 'admin_enqueue_scripts', [ $self, 'load_react_ui_scripts' ] );
		}
		add_action( 'admin_menu', [ $self, 'admin_menu' ] );
		add_action( 'admin_footer', [ $self, 'replace_top_level_menu' ] );
		add_action( 'admin_head', [ $self, 'menu_highlight' ] );

		if ( Licenses::is_legacy() ) {
			add_action( 'admin_notices', [ $self, 'license_notices' ] );
		}

		add_filter( 'woocommerce_screen_ids', [ $self, 'filter_woocommerce_screen_ids' ] );
		add_filter( 'woocommerce_display_admin_footer_text', [ $self, 'filter_woocommerce_display_footer_text' ] );
		add_action( 'current_screen', [ $self, 'remove_woocommerce_help_tab' ], 100 );

		add_filter( 'woocommerce_reports_screen_ids', [ $self, 'inject_woocommerce_reports_screen_ids' ] );
		add_filter( 'editor_stylesheets', [ $self, 'add_editor_styles' ] );

		add_action( 'current_screen', [ $self, 'screen_options' ] );
		add_filter( 'set-screen-option', [ $self, 'handle_save_screen_option' ], 10, 3 );

		if ( aw_request( 'action' ) === 'automatewoo-settings' ) {
			add_action( 'wp_loaded', [ $self, 'save_settings' ] );
		}

		if ( aw_request( 'page' ) === 'automatewoo-preview' && aw_request( 'action' ) === 'loading' ) {
			add_action( 'admin_init', [ $self, 'cache_preview_loader' ] );
		}

		if ( aw_request( 'page' ) === 'automatewoo-preview' ) {
			add_action( 'admin_notices', [ $self, 'remove_admin_notices' ], 0 );
		}

		$registry = BlocksPackage::container()->get( AssetDataRegistry::class );
		( new AssetData( $registry ) )->add_data();
	}

	static function includes() {

		switch ( self::get_screen_id() ) {
			case 'aw_workflow' :
				new Admin_Workflow_Edit();
				break;

			case 'edit-aw_workflow' :
				new Admin_Workflow_List();
				break;

			case 'edit-shop_coupon' :
				new \AW_Admin_Coupons_List();
				break;
		}
	}


	static function screen_options() {
		switch ( $id = self::get_screen_id() ) {
			case 'logs' :
			case 'carts' :
			case 'guests' :
			case 'queue' :
			case 'unsubscribes' :
				add_screen_option( 'per_page', [
				   'option' => "automatewoo_{$id}_per_page",
				   'default' => 20
				]);
				break;
		}
	}


	/**
	 * Handle saving screen option.
	 *
	 * @param bool   $keep
	 * @param string $option
	 * @param int    $value
	 *
	 * @return bool|int
	 */
	public static function handle_save_screen_option( $keep, $option, $value ) {
		$options = [
		   'automatewoo_logs_per_page',
		   'automatewoo_carts_per_page',
		   'automatewoo_queue_per_page',
		   'automatewoo_guests_per_page',
		   'automatewoo_unsubscribes_per_page'
		];

		if ( in_array( $option, $options, true ) ) {
		   return $value;
		}

		return $keep;
	}


	static function admin_menu() {

		$sub_menu = [];
		$position = '55.6324'; // fix for rare position clash bug

		add_menu_page( __( 'AutomateWoo', 'automatewoo' ), __( 'AutomateWoo', 'automatewoo' ), 'manage_woocommerce', 'automatewoo', [ 'AutomateWoo\Admin', 'load_controller' ], 'none', $position );

		$sub_menu['dashboard'] = [
			'title' => __( 'Dashboard', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['workflows'] = [
			'title' => __( 'Workflows', 'automatewoo' ),
			'slug' => 'edit.php?post_type=aw_workflow'
		];

		$sub_menu['logs'] = [
			'title' => __( 'Logs', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['queue'] = [
			'title' => __( 'Queue', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		if ( Options::abandoned_cart_enabled() ) {
			$sub_menu['carts'] = [
				'title' => __( 'Carts', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];

			$sub_menu['guests'] = [
				'title' => __( 'Guests', 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];
		}

		$sub_menu['opt-ins'] = [
			'title' => Options::optin_enabled() ? __( 'Opt-ins', 'automatewoo' ) : __( 'Opt-outs', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['reports'] = [
			'title' => __( 'Reports', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['tools'] = [
			'title' => __( 'Tools', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['settings'] = [
			'title' => __( 'Settings', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['events'] = [
		   'title' => __( 'Events', 'automatewoo' ),
		   'function' => [ __CLASS__, 'load_controller' ]
		];

		$sub_menu['preview'] = [
			'title' => __( 'Preview', 'automatewoo' ),
			'function' => [ __CLASS__, 'load_controller' ]
		];

		if ( Licenses::is_legacy() ) {
			$sub_menu['licenses'] = [
				'title' => _n( 'License', 'Licenses', Addons::has_addons() ? 2 : 1, 'automatewoo' ),
				'function' => [ __CLASS__, 'load_controller' ]
			];
		}

		$sub_menu['data-upgrade'] = [
			'title' => __( 'AutomateWoo Data Update', 'automatewoo' ),
			'function' => [ __CLASS__, 'page_data_upgrade' ]
		];

		foreach ( $sub_menu as $key => $item ) {

			if ( empty( $item['function'] ) ) $item['function'] = '';
			if ( empty( $item['capability'] ) ) $item['capability'] = 'manage_woocommerce';
			if ( empty( $item['slug'] ) ) $item['slug'] = 'automatewoo-'.$key;
			if ( empty( $item['page_title'] ) ) $item['page_title'] = $item['title'];

			add_submenu_page( 'automatewoo', $item['page_title'], $item['title'], $item['capability'], $item['slug'], $item['function'] );

			if ( $key === 'workflows' ) {
				do_action( 'automatewoo/admin/submenu_pages', 'automatewoo' );
			}
		}

		if ( defined( 'WC_ADMIN_PLUGIN_FILE' ) ) {
			wc_admin_register_page(
				[
					'id'     => 'automatewoo-manual-workflow-runner',
					'title'  => __( 'Manual Workflow Runner', 'automatewoo' ),
					'parent' => 'automatewoo',
					'path'   => '/automatewoo/manual-workflow-runner',
				]
			);
		}
	}


	/**
	 * Highlight the correct top level admin menu item
	 */
	static function menu_highlight() {
		global $parent_file, $post_type;

		switch ( $post_type ) {
			case 'aw_workflow' :
				$parent_file = 'automatewoo';
				break;
		}
	}


	static function register_scripts() {

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$url = AW()->admin_assets_url( '/js' );
			$suffix = '';
		} else {
			$url = AW()->admin_assets_url( '/js/min' );
			$suffix = '.min';
		}

		$vendor_url = AW()->admin_assets_url( '/js/vendor' );

		wp_register_script( 'automatewoo-clipboard', $vendor_url."/clipboard$suffix.js", [], AW()->version );
		wp_register_script( 'js-cookie', WC()->plugin_url()."/assets/js/js-cookie/js.cookie{$suffix}.js", [], '2.1.4', true );

		wp_register_script( 'automatewoo', $url."/automatewoo$suffix.js", [ 'jquery', 'jquery-ui-datepicker', 'jquery-tiptip', 'backbone', 'underscore' ], AW()->version );
		wp_register_script( 'automatewoo-validate', $url."/validate$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-workflows', $url."/workflows$suffix.js", [ 'automatewoo', 'automatewoo-validate', 'automatewoo-modal', 'wp-util' ], AW()->version );
		wp_register_script( 'automatewoo-variables', $url."/variables$suffix.js", [ 'automatewoo-modal', 'automatewoo-clipboard' ], AW()->version );
		wp_register_script( 'automatewoo-tools', $url."/tools$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-sms-test', $url."/sms-test$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-modal', $url."/modal$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-rules', $url."/rules$suffix.js", [ 'automatewoo', 'automatewoo-workflows' ], AW()->version );
		wp_register_script( 'automatewoo-dashboard', $url."/dashboard$suffix.js", [ 'automatewoo', 'automatewoo-modal', 'jquery-masonry', 'flot', 'flot-resize', 'flot-time', 'flot-pie', 'flot-stack' ], AW()->version );
		wp_register_script( 'automatewoo-preview', $url."/preview$suffix.js", [ 'automatewoo' ], AW()->version );
		wp_register_script( 'automatewoo-settings', $url."/settings$suffix.js", [ 'automatewoo' ], AW()->version );


		global $wp_locale;

		wp_localize_script( 'automatewoo-dashboard', 'automatewooDashboardLocalizeScript', [] );

		wp_localize_script( 'automatewoo-validate', 'automatewooValidateLocalizedErrorMessages', [
			'noVariablesSupport' => __( 'This field does not support variables.', 'automatewoo' ),
			'invalidDataType' => __( "Variable '%s' is not available with the selected trigger. Please only use variables listed in the the variables box.", 'automatewoo' ),
			'invalidVariable' => __( "Variable '%s' is not a valid. Please only use variables listed in the the variables box.", 'automatewoo' )
		] );

		$settings = [
			'url'           => [
				'admin' => admin_url(),
				'ajax'  => admin_url( 'admin-ajax.php' )
			],
			'locale'        => [
				'month_abbrev'      => array_values( $wp_locale->month_abbrev ),
				'currency_symbol'   => get_woocommerce_currency_symbol(),
				'currency_position' => get_option( 'woocommerce_currency_pos' )
			],
			'nonces'        => [
				'remove_notice' => wp_create_nonce( 'aw-remove-notice' ),
			],
		];

		wp_localize_script(
			'automatewoo',
			'automatewooLocalizeScript',
			apply_filters( 'automatewoo/admin/js_settings', $settings )
		);
	}


	static function register_styles() {
		wp_register_style( 
			'automatewoo-main',
			AW()->admin_assets_url( '/css/aw-main.css' ),
			defined( 'WC_ADMIN_PLUGIN_FILE' ) ? [ 'wc-admin-app' ] : [],
			AW()->version
		);
		wp_register_style( 'automatewoo-preview', AW()->admin_assets_url( '/css/preview.css' ), [], AW()->version );
	 }


	/**
	 * Enqueue scripts based on screen id
	 */
	static function enqueue_scripts_and_styles() {
		$screen_id = self::get_current_screen_id();

		wp_enqueue_script( 'automatewoo' );
		wp_enqueue_style( 'automatewoo-main' );

		if ( self::is_automatewoo_screen() ) {
			wp_enqueue_script( 'woocommerce_admin' );
			wp_enqueue_script( 'wc-enhanced-select' );
			wp_enqueue_script( 'jquery-tiptip' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'js-cookie' );

			wp_enqueue_style( 'woocommerce_admin_styles' );
			wp_enqueue_style( 'jquery-ui-style' );
		}

		if ( $screen_id === 'automatewoo_page_automatewoo-preview' ) {
			wp_enqueue_script( 'automatewoo-preview' );
			wp_enqueue_style( 'automatewoo-preview' );
		}

	}

	/**
	 * Load react powered admin JS.
	 *
	 * @since 5.0.0
	 */
	public static function load_react_ui_scripts() {
		$asset_file_path = AW()->admin_path() . "/assets/build/index.asset.php";

		if ( ! file_exists( $asset_file_path ) ) {
			return;
		}

		$asset_file   = (array) include $asset_file_path;
		$dependencies = isset( $asset_file['dependencies'] ) ? $asset_file['dependencies'] : [];
		// Depend on main admin script for plugin settings
		$dependencies[] = 'automatewoo';
		$dependencies[] = 'wc-settings';
		$version        = isset( $asset_file['version'] ) ? $asset_file['version'] : false;
		$handle         = 'automatewoo-react-ui';

		wp_enqueue_script(
			$handle,
			AW()->admin_assets_url() . "/build/index.js",
			$dependencies,
			$version,
			true
		);
		wp_set_script_translations( $handle, 'automatewoo', AW()->path( '/languages' ) );
	}

	static function screen_ids() {

		$ids = [];
		$prefix = 'automatewoo_page_automatewoo';

		$ids[] = "$prefix-dashboard";
		$ids[] = "$prefix-logs";
		$ids[] = "$prefix-reports";
		$ids[] = "$prefix-settings";
		$ids[] = "$prefix-tools";
		$ids[] = "$prefix-carts";
		$ids[] = "$prefix-queue";
		$ids[] = "$prefix-guests";
		$ids[] = "$prefix-opt-ins";
		$ids[] = "$prefix-licenses";
		$ids[] = "$prefix-events";
		$ids[] = "$prefix-preview";
		$ids[] = 'aw_workflow';
		$ids[] = 'edit-aw_workflow';

		return apply_filters( 'automatewoo/admin/screen_ids', $ids );
	}


	/**
	 * Add AW screens to the woocommerce screen IDs list.
	 * Important for admin script loading.
	 *
	 * @since 4.4.2
	 *
	 * @param array $screen_ids
	 *
	 * @return array
	 */
	static function filter_woocommerce_screen_ids( $screen_ids ) {
		$screen_ids = array_merge( $screen_ids, self::screen_ids() );
		return $screen_ids;
	}


	/**
	 * Hide the WC footer message on AW screens.
	 *
	 * @since 4.4.2
	 *
	 * @param bool $display
	 *
	 * @return bool
	 */
	static function filter_woocommerce_display_footer_text( $display ) {
		if ( self::is_automatewoo_screen() ) {
			$display = false;
		}

		return $display;
	}

	/**
	 * Remove the WC help tab on AW screens
	 *
	 * @since 4.4.2
	 */
	static function remove_woocommerce_help_tab() {
		if ( self::is_automatewoo_screen() ) {
			$screen = get_current_screen();
			$screen->remove_help_tabs();
		}
	}

	/**
	 * Dynamic replace top level menu
	 */
	static function replace_top_level_menu() {
		?>
	   <script type="text/javascript">
           jQuery('#adminmenu').find('a.toplevel_page_automatewoo').attr('href', '<?php echo esc_url( self::page_url( 'dashboard' ) ); ?>');
	   </script>
		<?php
	}

	/**
	 * @param string $page
	 * @param bool|int $id
	 * @return false|string
	 */
	static function page_url( $page, $id = false ) {

		switch ( $page ) {

			case 'dashboard':
				return admin_url( 'admin.php?page=automatewoo-dashboard' );

			case 'workflows':
				return admin_url( 'edit.php?post_type=aw_workflow' );

			case 'settings':
				return admin_url( 'admin.php?page=automatewoo-settings' );

			case 'settings-bitly':
				return admin_url( 'admin.php?page=automatewoo-settings&tab=bitly' );

			case 'licenses':
				return admin_url( 'admin.php?page=automatewoo-licenses' );

			case 'logs':
				return admin_url( 'admin.php?page=automatewoo-logs' );

			case 'queue':
				return admin_url( 'admin.php?page=automatewoo-queue' );

			case 'guests':
				return admin_url( 'admin.php?page=automatewoo-guests' );

			case 'guest':
				return admin_url( "admin.php?page=automatewoo-guests&action=view&guest_id=$id" );

			case 'email-tracking':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=email-tracking' );

			case 'carts':
				return admin_url( 'admin.php?page=automatewoo-carts' );

			case 'opt-ins':
				return admin_url( 'admin.php?page=automatewoo-opt-ins' );

			case 'conversions':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=conversions' );

			case 'conversions-list':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=conversions-list' );

			case 'workflows-report':
				return admin_url( 'admin.php?page=automatewoo-reports&tab=runs-by-date' );

			case 'tools':
				return admin_url( 'admin.php?page=automatewoo-tools' );

			case 'status':
				return admin_url( 'admin.php?page=automatewoo-settings&tab=status' );

			case 'manual-workflow-runner':
				$url = admin_url( 'admin.php?page=wc-admin&path=/automatewoo/manual-workflow-runner' );
				if ( $id ) {
					return add_query_arg( 'workflowId', $id, $url );
				}
				return $url;
		}

		return false;
	}

	static function page_data_upgrade() {
		self::get_view( 'page-data-upgrade' );
	}

	/**
	 * @param $view
	 * @param array $imported_variables
	 * @param mixed $path
	 */
	static function get_view( $view, $imported_variables = [], $path = false ) {

		if ( $imported_variables && is_array( $imported_variables ) )
			extract( $imported_variables );

		if ( ! $path )
			$path = AW()->admin_path( '/views/' );

		include $path.$view.'.php';
	}

	static function load_controller() {
		if ( ! $screen_id = self::get_screen_id() ) {
			return;
		}

		if ( $screen_id == 'toplevel_page_automatewoo' ) {
			$screen_id = 'dashboard';
		}

		if ( $controller = Admin\Controllers::get( $screen_id ) ) {
			$controller->handle();
		}
	}

	/**
	 * @return string|bool
	 */
	static function get_screen_id() {
		$screen_id = self::get_current_screen_id();

		if ( ! $screen_id ) {
			return false;
		}

		$base_screen = sanitize_title( __( 'AutomateWoo', 'automatewoo' ) ); // required if plugin name was translated

		return str_replace( $base_screen . '_page_automatewoo-', '', $screen_id );
	}

	/**
	 * Save settings on wp_loaded
	 */
	static function save_settings() {
		if ( $controller = Admin\Controllers::get( 'settings' ) ) {
			$controller->save();
		}
	}

	static function license_notices() {

		if ( ! current_user_can( 'manage_woocommerce' ) || self::is_page( 'licenses' ) ) {
			return;
		}

		if ( Licenses::has_expired_products() && ! get_transient( 'aw_dismiss_licence_expiry_notice' ) ) {

			$strong = __( 'Your AutomateWoo license has expired.', 'automatewoo' );
			$more = sprintf(
				__( '<a href="%s" target="_blank">Renew your license</a> to receive updates and support.', 'automatewoo' ),
				Licenses::get_renewal_url( 'expired-license-notice' ),
				self::page_url( 'licenses' )
			);

			self::notice( 'warning is-dismissible', $strong, $more, 'aw-notice-licence-renew' );
		}

		if ( Licenses::has_unactivated_products() && ! get_transient( 'automatewoo_dismiss_license_nag_notice' ) ) {

			if ( Addons::has_addons() ) {
				$strong = __( 'AutomateWoo - You have unactivated products.', 'automatewoo' );
			} else {
				$strong = __( 'AutomateWoo is not activated.', 'automatewoo' );
			}

			$more = sprintf(
				__( 'Please <a href="%s">enter your license key</a> to receive plugin updates.', 'automatewoo' ),
				self::page_url( 'licenses' )
			);

			self::notice( 'warning is-dismissible', $strong, $more, 'automatewoo-notice--license-nag' );
		}
	}

	/**
	 * @param $page
	 * @return bool
	 */
	static function is_page( $page ) {

		$current_page = Clean::string( aw_request( 'page' ) );
		$current_tab = Clean::string( aw_request( 'tab' ) );

		switch ( $page ) {
			case 'dashboard':
				return $current_page == 'automatewoo-dashboard';
				break;
			case 'settings':
				return $current_page == 'automatewoo-settings';
				break;
			case 'reports':
				return $current_page == 'automatewoo-reports';
				break;
			case 'licenses':
				return $current_page == 'automatewoo-licenses';
				break;
			case 'status':
				return $current_page == 'automatewoo-settings' && $current_tab == 'status';
				break;
		}

		return false;
	}

	/**
	 * Display an admin notice.
	 *
	 * @param string $type         (warning,error,success)
	 * @param string $strong       highlighted notice content (text or html)
	 * @param string $more         notice content (text or html)
	 * @param string $class        extra classes to add to the notice (eg., is-dismissible)
	 * @param string $button_text  text to display on the primary button (not displayed if empty)
	 * @param string $button_link  link for the button (not displayed if empty)
	 * @param string $button_class extra classes to add to the button
	 */
	public static function notice( $type, $strong, $more = '', $class = '', $button_text = '', $button_link = '', $button_class = '' ) {
		self::get_view(
			'simple-notice',
			[
				'type'         => $type,
				'class'        => $class,
				'strong'       => $strong,
				'message'      => $more,
				'button_text'  => $button_text,
				'button_link'  => $button_link,
				'button_class' => $button_class,
			]
		);
	}

	/**
	 * @param $ids
	 * @return array
	 */
	static function inject_woocommerce_reports_screen_ids( $ids ) {
		$ids[] = 'automatewoo_page_automatewoo-reports';
		return $ids;
	}

	/**
	 * @param $stylesheets
	 * @return array
	 */
	static function add_editor_styles( $stylesheets ) {
		$stylesheets[] = AW()->admin_assets_url( '/css/editor.css' );
		return $stylesheets;
	}

	/**
	 * @param $id
	 * @param $title
	 * @param callable $callback
	 * @param null $screen
	 * @param string $context
	 * @param string $priority
	 * @param null $callback_args
	 */
	static function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null ) {
		$id = 'aw_'.$id;

		add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );

		add_filter( "postbox_classes_{$screen}_{$id}", [ __CLASS__, 'inject_postbox_class' ] );
	}

	/**
	 * @param $classes
	 *
	 * @return array
	 */
	static function inject_postbox_class( $classes ) {
		$classes[] = 'automatewoo-metabox';
		$classes[] = 'no-drag';
		return $classes;
	}

	/**
	 * @param $vars array
	 */
	static function get_hidden_form_inputs_from_query( $vars ) {
		foreach ( $vars as $var ) {
			if ( empty( $_GET[$var] ) )
				continue;

			echo '<input type="hidden" name="'.esc_attr( $var ).'" value="'.esc_attr( $_GET[$var] ).'">';
		}
	}


	/**
	 * Display a help tip.
	 *
	 * @param string $tip        The tip to display. Not expected to be escaped.
	 * @param bool   $pull_right Whether the tip should include the automatewoo-help-tip--right class
	 * @param bool   $allow_html Deprecated parameter, no longer used.
	 */
	static function help_tip( $tip, $pull_right = true, $allow_html = false ) {
		$tip = wc_sanitize_tooltip( $tip );
		if ( empty( $tip ) ) {
			return;
		}

		$classes = array_filter(
			[
				'automatewoo-help-tip',
				'woocommerce-help-tip',
				$pull_right ? 'automatewoo-help-tip--right' : '',
			]
		);

		printf( '<span class="%1$s" data-tip="%2$s"></span>', join( ' ', $classes ), $tip );
	}


	/**
	 * @param string $type
	 * @param string $dashicon
	 * @param bool $tip
	 * @return string
	 */
	static function badge( $type, $dashicon, $tip = false ) {
		$html = '<span class="automatewoo-badge automatewoo-badge--' . $type . ' automatewoo-tiptip" data-tip="' . esc_attr( $tip ) . '">';
		$html .= '<span class="dashicons dashicons-' . $dashicon . '"></span>';
		$html .= '</span>';
		return $html;
	}


	/**
	 * @param $url
	 * @param bool $pull_right
	 * @return string
	 */
	static function help_link( $url, $pull_right = true ) {
		return '<a href="'.$url.'" class="automatewoo-help-link '.( $pull_right ? 'automatewoo-help-link--right' : '' ).'" target="_blank"></a>';
	}


	/**
	 * @param string $page
	 * @param string|bool $utm_source
	 * @param string|bool $utm_campaign
	 * @return string
	 */
	static function get_docs_link( $page = '', $utm_source = false, $utm_campaign = false ) {
		return self::get_website_link( "docs/$page", $utm_source, $utm_campaign );
	}

	/**
	 * @param string $page
	 * @param string|bool $utm_source
	 * @param string|bool $utm_campaign
	 * @return string
	 */
	static function get_website_link( $page = '', $utm_source = false, $utm_campaign = false ) {
		$url = 'https://automatewoo.com/'.( $page ? trailingslashit( $page ) : '' );

		if ( $utm_source ) {
			$url = add_query_arg( [
				'utm_source' => $utm_source,
				'utm_medium' => 'plugin',
				'utm_campaign' => $utm_campaign ? $utm_campaign : 'plugin-links'
			], $url );
		}

		return $url;
	}

	/**
	 * Get WooCommerce.com marketplace product link.
	 *
	 * @since 3.7.0
	 *
	 * @param string $product
	 *
	 * @return string
	 */
	public static function get_marketplace_product_link( $product = 'automatewoo' ) {
		return "https://woocommerce.com/products/$product/";
	}

	/**
	 * Output loader
	 */
	static function cache_preview_loader() {
		@header_remove( 'Cache-Control' );
		@header( "Expires: ".gmdate( "D, d M Y H:i:s", time() + DAY_IN_SECONDS )." GMT" );
	}


	/**
	 * @param string $slug
	 * @param string $page_title
	 * @param string $page_content
	 * @param string $option
	 * @return int|bool Page ID
	 */
	static function create_page( $slug, $page_title, $page_content, $option ) {

		if ( get_option( $option ) ) {
			return false; // page is already defined in settings
		}

		$page_data = [
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_author' => 1,
			'post_name' => $slug,
			'post_title' => $page_title,
			'post_content' => $page_content,
			'comment_status' => 'closed',
		];

		$page_id = wp_insert_post( $page_data );
		update_option( $option, $page_id, false );

		return $page_id;
	}

	/**
	 * Return the current WP screen ID.
	 *
	 * @since 4.4.2
	 *
	 * @return bool|string
	 */
	static function get_current_screen_id() {
		$screen = get_current_screen();
		return $screen ? $screen->id : false;
	}

	/**
	 * Is the current WP screen an AutomateWoo screen?
	 *
	 * @since 4.7.0
	 * @return bool
	 */
	static function is_automatewoo_screen() {
		return in_array( self::get_current_screen_id(), self::screen_ids(), true );
	}

	/**
	 * Unhook all admin notices.
	 *
	 * @since 4.4.0
	 */
	static function remove_admin_notices() {
		remove_all_actions( 'admin_notices' );
	}

	/**
	 * Get marketplace subscriptions tab URL.
	 *
	 * @since 4.7.0
	 *
	 * @return string
	 */
	public static function get_marketplace_subscriptions_tab_url() {
		return add_query_arg(
			[
				'page'    => 'wc-addons',
				'section' => 'helper',
			],
			admin_url( 'admin.php' )
		);
	}

}
