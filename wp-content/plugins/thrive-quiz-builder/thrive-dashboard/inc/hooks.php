<?php
/**
 * Holds ACTIONS/FILTERS implementations ONLY
 * User: Danut
 * Date: 12/8/2015
 * Time: 4:40 PM
 */

/**
 * Hook for "init" wp action
 */
function tve_dash_init_action() {
	if ( $GLOBALS['tve_dash_loaded_from'] === 'plugins' ) {
		defined( 'TVE_DASH_URL' ) || define( 'TVE_DASH_URL', untrailingslashit( plugins_url() ) . '/' . trim( $GLOBALS['tve_dash_included']['folder'], '/\\' ) . '/thrive-dashboard' );
	} else {
		defined( 'TVE_DASH_URL' ) || define( 'TVE_DASH_URL', untrailingslashit( get_template_directory_uri() ) . '/thrive-dashboard' );
	}

	defined( 'TVE_DASH_IMAGES_URL' ) || define( 'TVE_DASH_IMAGES_URL', TVE_DASH_URL . '/css/images' );

	require_once( TVE_DASH_PATH . '/inc/font-import-manager/classes/Tve_Dash_Font_Import_Manager.php' );
	require_once( TVE_DASH_PATH . '/inc/font-manager/font-manager.php' );

	/**
	 * Run any database migrations
	 */
	if ( defined( 'TVE_TESTS_RUNNING' ) || is_admin() ) {
		TD_DB_Manager::collect_migration_managers();
	}
}

/**
 * Add main Thrive Dashboard item to menu
 */
function tve_dash_admin_menu() {
	add_menu_page(
		"Thrive Dashboard",
		"Thrive Dashboard",
		TVE_DASH_CAPABILITY,
		"tve_dash_section",
		"tve_dash_section",
		TVE_DASH_IMAGES_URL . '/logo-icon.png'
	);

	if ( is_super_admin() ) {
		add_submenu_page(
			null,
			__( 'Access Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			__( 'Access Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'manage_options',
			'tve_dash_access_manager',
			'tve_dash_access_manager_main_page'
		);
	}

	add_submenu_page(
		null,
		__( 'System Info', TVE_DASH_TRANSLATE_DOMAIN ),
		__( 'System Info', TVE_DASH_TRANSLATE_DOMAIN ),
		'manage_options',
		'tve-debug',
		function () {
			tve_dash_enqueue();
			require_once( TVE_DASH_PATH . '/inc/plugin-updates/debug-screen.php' );
		}
	);

	/**
	 * @param tve_dash_section parent slug
	 */
	do_action( 'tve_dash_add_menu_item', 'tve_dash_section' );

	$menus = array(
		'license_manager'     => array(
			'parent_slug' => is_plugin_active( 'thrive-product-manager/thrive-product-manager.php' ) ? null : 'tve_dash_section',
			'page_title'  => __( 'Thrive License Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'License Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_license_manager_section',
			'function'    => 'tve_dash_license_manager_section',
		),
		'general_settings'    => array(
			'parent_slug' => 'tve_dash_section',
			'page_title'  => __( 'Thrive General Settings', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'General Settings', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => TVE_DASH_CAPABILITY,
			'menu_slug'   => 'tve_dash_general_settings_section',
			'function'    => 'tve_dash_general_settings_section',
		),
		'ui_toolkit'          => array(
			/**
			 * in order to not include the page in the menu -> use null as the first parameter
			 */
			'parent_slug' => tve_dash_is_debug_on() ? 'tve_dash_section' : null,
			'page_title'  => __( 'Thrive UI toolkit', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Thrive UI toolkit', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'tve_dash_ui_toolkit',
			'function'    => 'tve_dash_ui_toolkit',
		),
		/* Font Manager Page */
		'font_manager'        => array(
			'parent_slug' => null,
			'page_title'  => __( 'Thrive Font Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Thrive Font Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => TVE_DASH_CAPABILITY,
			'menu_slug'   => 'tve_dash_font_manager',
			'function'    => 'tve_dash_font_manager_main_page',
		),
		/* Font Import Manager Page */
		'font_import_manager' => array(
			'parent_slug' => null,
			'page_title'  => __( 'Thrive Font Import Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Thrive Font Import Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => TVE_DASH_CAPABILITY,
			'menu_slug'   => 'tve_dash_font_import_manager',
			'function'    => 'tve_dash_font_import_manager_main_page',
		),
		'icon_manager'        => array(
			'parent_slug' => null,
			'page_title'  => __( 'Icon Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'menu_title'  => __( 'Icon Manager', TVE_DASH_TRANSLATE_DOMAIN ),
			'capability'  => TVE_DASH_CAPABILITY,
			'menu_slug'   => 'tve_dash_icon_manager',
			'function'    => 'tve_dash_icon_manager_main_page',
		),
	);

	$thrive_products_order = tve_dash_get_menu_products_order();
	$menus                 = array_merge( $menus, apply_filters( 'tve_dash_admin_product_menu', array() ) );

	foreach ( $thrive_products_order as $order => $menu_short ) {
		if ( array_key_exists( $menu_short, $menus ) ) {
			add_submenu_page( $menus[ $menu_short ]['parent_slug'], $menus[ $menu_short ]['page_title'], $menus[ $menu_short ]['menu_title'], $menus[ $menu_short ]['capability'], $menus[ $menu_short ]['menu_slug'], $menus[ $menu_short ]['function'] );
		}
	}
}

function tve_dash_icon_manager_main_page() {
	$tve_icon_manager = Tve_Dash_Thrive_Icon_Manager::instance();
	$tve_icon_manager->mainPage();
}

function tve_dash_font_import_manager_main_page() {
	$font_import_manager = Tve_Dash_Font_Import_Manager::getInstance();
	$font_import_manager->mainPage();
}

/**
 * Checks if the current screen (current admin screen) needs to have the dashboard scripts and styles enqueued
 *
 * @param string $hook current admin page hook
 */
function tve_dash_needs_enqueue( $hook ) {
	$accepted_hooks = array(
		'toplevel_page_tve_dash_section',
		'thrive-dashboard_page_tve_dash_license_manager_section',
		'thrive-dashboard_page_tve_dash_general_settings_section',
		'thrive-dashboard_page_tve_dash_ui_toolkit',
		'admin_page_tve_dash_ui_toolkit',
		'admin_page_tve_dash_api_connect',
		'admin_page_tve_dash_api_error_log',
		'admin_page_tve_dash_api_connect',
		'thrive-dashboard_page_tve_dash_access_manager',
	);

	$accepted_hooks = apply_filters( 'tve_dash_include_ui', $accepted_hooks, $hook );

	return in_array( $hook, $accepted_hooks );
}

function tve_dash_admin_enqueue_scripts( $hook ) {
	if ( tve_dash_needs_enqueue( $hook ) ) {
		tve_dash_enqueue();
	}

	/**
	 * Enqueue roboto from gutenberg blocks
	 */
	if ( ! tve_dash_is_google_fonts_blocked() && ( 'post.php' == $hook || 'post-new.php' == $hook ) ) {
		tve_dash_enqueue_style( 'tve-block-font', '//fonts.googleapis.com/css?family=Roboto:400,500,700' );
	}
}

/**
 * Dequeue conflicting scripts
 *
 * @param string $hook
 */
function tve_dash_admin_dequeue_conflicting( $hook ) {
	if ( isset( $GLOBALS['tve_dash_resources_enqueued'] ) || tve_dash_needs_enqueue( $hook ) ) {
		// NewsPaper messing about and including css / scripts all over the admin panel
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );
		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
	}
}

/**
 * enqueue the dashboard CSS and javascript files
 */
function tve_dash_enqueue() {
	$js_suffix = tve_dash_is_debug_on() ? '.js' : '.min.js';

	tve_dash_enqueue_script( 'tve-dash-main-js', TVE_DASH_URL . '/js/dist/tve-dash' . $js_suffix, array(
		'jquery',
		'backbone',
	) );
	wp_enqueue_script( 'jquery-zclip', TVE_DASH_URL . '/js/util/jquery.zclip.1.1.1/jquery.zclip.min.js', array( 'jquery' ) );
	tve_dash_enqueue_style( 'tve-dash-styles-css', TVE_DASH_URL . '/css/styles.css' );
	wp_enqueue_script( 'tve-dash-api-wistia-popover', '//fast.wistia.com/assets/external/popover-v1.js', array(), '', true );

	$options = array(
		'nonce'              => wp_create_nonce( 'tve-dash' ),
		'dash_url'           => TVE_DASH_URL,
		'actions'            => array(
			'backend_ajax'        => 'tve_dash_backend_ajax',
			'ajax_delete_api_log' => 'tve_dash_api_delete_log',
			'ajax_retry_api_log'  => 'tve_dash_api_form_retry',
		),
		'routes'             => array(
			'settings'          => 'generalSettings',
			'license'           => 'license',
			'active_states'     => 'activeState',
			'error_log'         => 'getErrorLogs',
			'affiliate_links'   => 'affiliateLinks',
			'add_aff_id'        => 'saveAffiliateId',
			'get_aff_id'        => 'getAffiliateId',
			'token'             => 'token',
			'save_token'        => 'saveToken',
			'delete_token'      => 'deleteToken',
			'change_capability' => 'changeCapability',
		),
		'translations'       => array(
			'UnknownError'      => __( "Unknown error", TVE_DASH_TRANSLATE_DOMAIN ),
			'Deleting'          => __( 'Deleting...', TVE_DASH_TRANSLATE_DOMAIN ),
			'Testing'           => __( 'Testing...', TVE_DASH_TRANSLATE_DOMAIN ),
			'Loading'           => __( 'Loading...', TVE_DASH_TRANSLATE_DOMAIN ),
			'ConnectionWorks'   => __( 'Connection works!', TVE_DASH_TRANSLATE_DOMAIN ),
			'ConnectionFailed'  => __( 'Connection failed!', TVE_DASH_TRANSLATE_DOMAIN ),
			'Unlimited'         => __( 'Unlimited', TVE_DASH_TRANSLATE_DOMAIN ),
			'CapabilityError'   => __( 'You are not allowed to remove this capability!', TVE_DASH_TRANSLATE_DOMAIN ),
			'CapabilitySuccess' => __( 'Capability changed successfully', TVE_DASH_TRANSLATE_DOMAIN ),
			'RequestError'      => 'Request error, please contact Thrive developers !',
			'Copy'              => 'Copy',
		),
		'products'           => array(
			TVE_Dash_Product_LicenseManager::ALL_TAG => 'All products',
			TVE_Dash_Product_LicenseManager::TCB_TAG => 'Thrive Architect',
			TVE_Dash_Product_LicenseManager::TL_TAG  => 'Thrive Leads',
			TVE_Dash_Product_LicenseManager::TCW_TAG => 'Thrive Clever Widgets',
		),
		'license_types'      => array(
			'individual' => __( 'Individual product', TVE_DASH_TRANSLATE_DOMAIN ),
			'full'       => __( 'Full membership', TVE_DASH_TRANSLATE_DOMAIN ),
		),
		'is_polylang_active' => is_plugin_active( 'polylang/polylang.php' ),
	);


	/**
	 * Allow vendors to hook into this
	 * TVE_Dash is the output js object
	 */
	$options = apply_filters( 'tve_dash_localize', $options );

	wp_localize_script( 'tve-dash-main-js', 'TVE_Dash_Const', $options );

	/**
	 * Localize token data
	 */
	$token_options          = array();
	$token_options['model'] = get_option( 'thrive_token_support' );
	if ( ! empty( $token_options['model']['token'] ) && ! get_option( 'tve_dash_generated_token' ) ) {
		/* Backwards-compat: store this option separately in the database */
		update_option( 'tve_dash_generated_token', array(
			'token'   => $token_options['model']['token'],
			'referer' => $token_options['model']['referer'],
		) );
	}
	wp_localize_script( 'tve-dash-main-js', 'TVE_Token', $token_options );

	/**
	 * output the main tpls for backbone views used in dashboard
	 */

	add_action( 'admin_print_footer_scripts', 'tve_dash_backbone_templates' );
	/**
	 * set this flag here so we can later remove conflicting scripts / styles
	 */
	$GLOBALS['tve_dash_resources_enqueued'] = true;
}

/**
 * main entry point for the incoming ajax requests
 *
 * passes the request to the TVE_Dash_AjaxController for processing
 */
function tve_dash_backend_ajax() {
	check_ajax_referer( 'tve-dash' );

	if ( ! current_user_can( TVE_DASH_CAPABILITY ) ) {
		wp_die( '' );
	}
	$response = TVE_Dash_AjaxController::instance()->handle();

	wp_send_json( $response );
}


function tve_dash_reset_license() {
	$options = array(
		'tcb'    => 'tve_license_status|tve_license_email|tve_license_key',
		'tl'     => 'tve_leads_license_status|tve_leads_license_email|tve_leads_license_key',
		'tcw'    => 'tcw_license_status|tcw_license_email|tcw_license_key',
		'themes' => 'thrive_license_status|thrive_license_key|thrive_license_email',
		'dash'   => 'thrive_license',
	);

	if ( ! empty( $_POST['products'] ) ) {
		$filtered = array_intersect_key( $options, array_flip( $_POST['products'] ) );
		foreach ( explode( '|', implode( '|', $filtered ) ) as $option ) {
			delete_option( $option );
		}
		$message = 'Licenses reset for: ' . implode( ', ', array_keys( $filtered ) );

		$dash_license = get_option( 'thrive_license', array() );
		foreach ( $_POST['products'] as $prod ) {
			unset( $dash_license[ $prod ] );
		}
		update_option( 'thrive_license', $dash_license );

	}

	require dirname( dirname( ( __FILE__ ) ) ) . '/templates/settings/reset.phtml';
}

function tve_dash_load_text_domain() {
	$domain = TVE_DASH_TRANSLATE_DOMAIN;
	$locale = $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	$path = 'thrive-dashboard/languages/';
	//$path = apply_filters('tve_dash_filter_plugin_languages_path', $path);

	load_textdomain( $domain, WP_LANG_DIR . '/thrive/' . $domain . "-" . $locale . ".mo" );
	load_plugin_textdomain( $domain, false, $path );
}

/**
 *
 * fetches and outputs the backbone templates needed for thrive dashboard
 *
 * called on 'admin_print_footer_scripts'
 *
 */
function tve_dash_backbone_templates() {
	$templates = tve_dash_get_backbone_templates( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/backbone', 'backbone' );

	tve_dash_output_backbone_templates( $templates );
}

/**
 * Returns the disable state of the google fonts
 *
 * @return bool
 */
function tve_dash_is_google_fonts_blocked() {
	return (bool) get_option( 'tve_google_fonts_disable_api_call', '' );
}

/**
 * Returns the disable state of the google fonts
 *
 * @return bool
 */
function tve_dash_allow_video_src() {
	return (bool) get_option( 'tve_allow_video_src', '' );
}

/**
 * output script nodes for backbone templates
 *
 * @param array $templates
 */
function tve_dash_output_backbone_templates( $templates, $prefix = '', $suffix = '' ) {

	foreach ( $templates as $tpl_id => $path ) {
		$tpl_id = $prefix . $tpl_id . $suffix;
		echo '<script type="text/template" id="' . $tpl_id . '">';
		include $path;
		echo '</script>';
	}
}

/**
 * include the backbone templates in the page
 *
 * @param string $dir basedir for template search
 * @param string $root
 */
function tve_dash_get_backbone_templates( $dir = null, $root = 'backbone' ) {
	if ( null === $dir ) {
		$dir = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/backbone';
	}

	$folders   = scandir( $dir );
	$templates = array();

	foreach ( $folders as $item ) {
		if ( in_array( $item, array( ".", ".." ) ) ) {
			continue;
		}

		if ( is_dir( $dir . '/' . $item ) ) {
			$templates = array_merge( $templates, tve_dash_get_backbone_templates( $dir . '/' . $item, $root ) );
		}

		if ( is_file( $dir . '/' . $item ) ) {
			$_parts     = explode( $root, $dir );
			$_truncated = end( $_parts );
			$tpl_id     = ( ! empty( $_truncated ) ? trim( $_truncated, '/\\' ) . '/' : '' ) . str_replace( array(
					'.php',
					'.phtml',
				), '', $item );

			$tpl_id = str_replace( array( '/', '\\' ), '-', $tpl_id );

			$templates[ $tpl_id ] = $dir . '/' . $item;
		}
	}

	return $templates;
}

/**
 * enqueue the frontend.js script
 */
function tve_dash_frontend_enqueue() {

	/**
	 * action filter - can be used to skip inclusion of dashboard frontend script
	 *
	 * each product should hook and return true if it needs this script
	 *
	 * @param bool $include
	 */
	$include = apply_filters( 'tve_dash_enqueue_frontend', false );

	if ( ! $include ) {
		return false;
	}

	tve_dash_enqueue_script( 'tve-dash-frontend', TVE_DASH_URL . '/js/dist/frontend.min.js', array( 'jquery' ), false, true );

	$captcha_api    = Thrive_Dash_List_Manager::credentials( 'recaptcha' );
	$show_recaptcha = ! empty( $captcha_api ) && ! empty( $captcha_api['connection'] ) && $captcha_api['connection']['version'] === 'v3' && ! empty( $captcha_api['connection']['browsing_history'] );

	if ( apply_filters( 'thrive_dashboard_show_recaptcha', $show_recaptcha ) ) {
		tve_dash_enqueue_script( 'tve-dash-recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $captcha_api['site_key'] );
	}
	unset( $captcha_api['secret_key'] );
	/**
	 * When a caching plugin is active on the user's site, we need to always send the first ajax load request - we cannot know for sure if the page will be cached for a crawler or a regular visitor
	 */

	$force_ajax_send = tve_dash_detect_cache_plugin();
	$data            = array(
		'ajaxurl'         => admin_url( 'admin-ajax.php' ),
		/**
		 * 'force_send_ajax' => true if any caching plugin is active
		 */
		'force_ajax_send' => $force_ajax_send !== false,
		/**
		 * 'is_crawler' only matters in case there is cache plugin active
		 * IF we find an active caching plugin -> 'is_crawler' is irrelevant and the initial ajax request will be always sent
		 */
		'is_crawler'      => $force_ajax_send !== false ? false : (bool) tve_dash_is_crawler( true ),
		// Apply the filter to allow overwriting the bot detection. Can be used by 3rd party plugins to force the initial ajax request
		'recaptcha'       => $captcha_api,
	);
	wp_localize_script( 'tve-dash-frontend', 'tve_dash_front', $data );
}

/**
 * main AJAX request entry point
 * this is sent out by thrive dashboard on every request
 *
 * $_POST[data] has the following structure:
 * [tcb] => array(
 *  key1 => array(
 *      action => some_tcb_action
 *      other_data => ..
 *  ),
 *  key2 => array(
 *      action => another_tcb_action
 *  )
 * ),
 * [tl] => array(
 * ..
 * )
 */
function tve_dash_frontend_ajax_load() {
	$response = array();
	if ( empty( $_POST['tve_dash_data'] ) || ! is_array( $_POST['tve_dash_data'] ) ) {
		wp_send_json( $response );
	}

	foreach ( $_POST['tve_dash_data'] as $key => $data ) {
		/**
		 * this is a really ugly one, but is required, because code from various plugins relies on $_POST / $_REQUEST
		 */
		foreach ( $data as $k => $v ) {
			$_REQUEST[ $k ] = $v;
			$_POST[ $k ]    = $v;
			$_GET[ $k ]     = $v;
		}
		/**
		 * action filter - each product should have its own implementation of this
		 *
		 * @param array $data
		 */
		$response[ $key ] = apply_filters( 'tve_dash_main_ajax_' . $key, array(), $data );
	}

	if ( ! empty( $GLOBALS['tve_dash_resources'] ) ) {
		$response['__resources'] = $GLOBALS['tve_dash_resources'];
	}

	wp_send_json( $response );
}

/**
 * Compatibility with WP Deferred Javascripts
 */
add_filter( 'do_not_defer', 'exclude_canvas_script' );
function exclude_canvas_script( $do_not_defer ) {

	$defer_array = array(
		'tho-footer-js',
		'tve-main-frame',
		'tve_editor',
		get_site_url() . '/wp-includes/js/utils.min.js',
		'thrive-main-script',
		'thrive-main-script',
		'thrive-admin-postedit',
		'tve-leads-editor',
		'tve-dash-frontend',
		'tvo_slider',
		'tve_frontend',
		'tve_leads_frontend',
		'media-editor', // wp media
		'jquery-ui-sortable', // sortable
		'tge-editor', // Quiz Builder Graph Editor
		'jquery-ui-draggable', // Quiz Builder Image Editor
		'tge-jquery', // Quiz Builder Image Editor
		'spectrum-script', // Quiz Builder Image Editor
		'tie-editor-script', // Quiz Builder Image Editor
		'tie-html2canvas', // Quiz Builder Image Editor
		'tqb-frontend', // Quiz Builder Front-End
	);

	$do_not_defer = array_merge( $do_not_defer, $defer_array );

	return $do_not_defer;
}

/**
 * For $post_types we add meta data that block google crawl to index the page.
 */
function tve_dash_custom_post_no_index() {
	if ( ! tve_dash_should_index_page() ) {
		echo '<meta name="robots" content="noindex">';
	}
}

/**
 * Whether or not the current page should be indexed by crawlers
 *
 * @return bool
 */
function tve_dash_should_index_page() {
	/**
	 * Filter a list of post types that should not be indexed by crawlers.
	 *
	 * @param array $post_types
	 *
	 * @return array
	 */
	$post_types = apply_filters( 'tve_dash_exclude_post_types_from_index', array() );

	$should_index = empty( $post_types ) || ! is_singular( $post_types );

	/**
	 * Allows filtering whether or not the current page should be indexed.
	 *
	 * @param bool $should_index
	 *
	 * @return bool
	 */
	return apply_filters( 'tve_dash_should_index_page', $should_index );
}

function tve_dash_current_screen() {

	$screen = get_current_screen();

	if ( $screen->id === 'admin_page_tve_dash_license_manager_section' && is_plugin_active( 'thrive-product-manager/thrive-product-manager.php' ) ) {
		$url = thrive_product_manager()->get_admin_url();
		wp_redirect( $url );
		die;
	}
}

/**
 * Add thrive edit links in admin bar
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function tve_dash_admin_bar_menu( $wp_admin_bar ) {
	$thrive_parent_node = tve_dash_get_thrive_parent_node();

	/**
	 * Allow plugins to add their own node in the admin bar as a child to the parent thrive node
	 */
	$nodes = apply_filters( 'tve_dash_admin_bar_nodes', array() );

	if ( empty( $nodes ) ) {
		return;
	}

	$no_of_nodes = count( $nodes );
	/** If we have more than one node add the parent and sort items */
	if ( $no_of_nodes > 1 ) {
		$wp_admin_bar->add_node( $thrive_parent_node );

		/* Sort the nodes by order */
		usort( $nodes, function ( $a, $b ) {
			return $a['order'] - $b['order'];
		} );
	}

	/** Let wordpress know about the nodes */
	foreach ( $nodes as $node ) {
		$node['parent'] = ( $no_of_nodes > 1 ) ? $thrive_parent_node['id'] : '';
		$wp_admin_bar->add_node( $node );
	}
}

add_action( 'admin_bar_menu', 'tve_dash_admin_bar_menu', 999 );

/* Quick query to remove all of our transients */
add_action( 'wp_ajax_tve_debug_reset_transient', function () {

	check_ajax_referer( 'tve-dash' );

	if ( current_user_can( 'manage_options' ) ) {
		global $wpdb;

		tvd_reset_transient();

		if ( ! empty( $wpdb->last_error ) ) {
			wp_die( "Error: $wpdb->last_error" );
		}
	}

	wp_die( 'Transients removed successfully' );
} );


/**
 * WP-Rocket Compatibility - exclude files from caching
 */
add_filter( 'rocket_exclude_js', 'tvd_rocket_exclude_js' );
/**
 * Exclude the js dist folder from caching and minify-ing
 *
 * @param $excluded_js
 *
 * @return array
 */
function tvd_rocket_exclude_js( $excluded_js ) {

	$excluded_js[] = str_replace( home_url(), '', TVE_DASH_URL ) . 'js/dist/(.*).js';

	return $excluded_js;
}

add_action( 'admin_notices', 'tve_dash_incompatible_tar_version' );

/**
 * Unify all alerts that inform the users that certain products are not compatible with TAR version
 */
function tve_dash_incompatible_tar_version() {

	$installed_products             = tve_dash_get_products( false );
	$products_incompatible_with_tar = array();

	/**
	 * @var TVE_Dash_Product_Abstract $product
	 */
	foreach ( $installed_products as $product ) {
		if ( $product->needs_architect() && $product->get_incompatible_architect_version() ) {

			$parts = parse_url( $product->get_admin_url() );
			parse_str( $parts['query'], $query );

			$products_incompatible_with_tar[] = array(
				'title'  => $product->getTitle(),
				'screen' => ! empty( $query['page'] ) ? $query['page'] : '',
			);
		}
	}


	$products_counter = count( $products_incompatible_with_tar );

	if ( $products_counter > 0 ) {

		$titles  = array_column( $products_incompatible_with_tar, 'title' );
		$screens = array_column( $products_incompatible_with_tar, 'screen' );

		/**
		 * @var WP_Screen
		 */
		$screen = get_current_screen();
		if ( $screen && in_array( str_replace( 'thrive-dashboard_page_', '', $screen->base ), $screens, true ) ) {
			return;
		}

		$version      = 'version';
		$products_str = $titles[0];
		$is_not       = 'is not';
		if ( $products_counter > 1 ) {
			$version = 'versions';
			$is_not  = 'are not';

			if ( $products_counter === 2 ) {
				$products_str = implode( ' and ', $titles );
			} elseif ( $products_counter > 2 ) {
				$products_str = implode( ', ', $titles );

				$pos = strrpos( $products_str, ', ' );
				if ( $pos !== false ) {
					$products_str = substr_replace( $products_str, ' and ', $pos, strlen( ', ' ) );
				}
			}
		}

		$text = sprintf( 'Current %s of %s %s compatible with the current version of Thrive Architect. Please update all plugins to the latest versions.', $version, $products_str, $is_not );

		$text .= ' <a href="' . network_admin_url( 'plugins.php' ) . '">' . __( 'Manage plugins', TVE_DASH_TRANSLATE_DOMAIN ) . '</a>';

		echo sprintf( '<div class="error"><p>%s</p></div>', $text );
	}
}

/**
 * Called on wp_login hook
 *
 * Updates the last login for a specific user & fires the login hook
 *
 * @param string  $user_login
 * @param WP_User $user
 */
function tve_dash_on_user_login( $user_login, $user ) {
	update_user_meta( $user->ID, 'tve_last_login', current_time( 'timestamp' ) );

	$user_form_data = tvd_get_login_form_data( 'success' );

	tve_trigger_core_user_login_action( $user_login, $user_form_data, $user );
}

/**
 * Called on wp_login_failed hook
 *
 * @param string        $username
 * @param WP_Error|null $error
 */
function tve_dash_on_user_login_failed( $username, $error = null ) {
	$user_form_data = tvd_get_login_form_data( 'fail' );

	tve_trigger_core_user_login_action( $username, $user_form_data, null );
}

/**
 * A wrapper over the thrive_core_user_login action for the system to include only once the hook in the 3rd party developer documentation
 *
 * @param string       $user_login
 * @param array        $user_form_data
 * @param WP_User|null $user
 */
function tve_trigger_core_user_login_action( $user_login, $user_form_data, $user ) {
	/**
	 * This hook is fired when a user logs into the platform.The hook can be fired multiple times per user.
	 * </br>
	 * Example use case:- Show the users specific content depending on the login URL
	 *
	 * @param string Username
	 * @param array User Form Data [href = #formdata]
	 * @param WP_User|null WP_User [href = #user]
	 *
	 * @api
	 */
	do_action( 'thrive_core_user_login',
		$user_login,
		$user_form_data,
		$user
	);
}

/**
 * Yoast Compatibility
 *
 * Do not generate sitemap for some of our custom post types
 */
add_filter( 'wpseo_sitemap_exclude_post_type', static function ( $exclude, $post_type ) {

	if ( in_array( $post_type, apply_filters( 'tve_dash_yoast_sitemap_exclude_post_types', array() ), true ) ) {
		$exclude = true;
	}

	return $exclude;
}, 10, 2 );

/**
 * Yoast Compatibility
 *
 * Do not generate sitemap for some of our custom taxonomies
 */
add_filter( 'wpseo_sitemap_exclude_taxonomy', static function ( $exclude, $tax_name ) {

	if ( in_array( $tax_name, apply_filters( 'tve_dash_yoast_sitemap_exclude_taxonomies', array() ), true ) ) {
		$exclude = true;
	}

	return $exclude;
}, 10, 2 );
