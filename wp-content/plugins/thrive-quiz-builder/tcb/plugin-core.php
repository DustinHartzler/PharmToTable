<?php
/* global constants */
defined( 'TVE_TCB_ROOT_PATH' ) || define( 'TVE_TCB_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'TVE_VERSION' ) || DEFINE( 'TVE_VERSION', include TVE_TCB_ROOT_PATH . 'version.php' );
defined( 'TVE_TCB_DB_VERSION' ) || define( 'TVE_TCB_DB_VERSION', '1.2' );
defined( 'TVE_LANDING_PAGE_TEMPLATE' ) || DEFINE( 'TVE_LANDING_PAGE_TEMPLATE', plugins_url() . '/thrive-visual-editor/landing-page/templates' );
defined( 'TVE_LANDING_PAGE_TEMPLATE_DOWNLOADED' ) || DEFINE( 'TVE_LANDING_PAGE_TEMPLATE_DOWNLOADED', plugins_url() . '/../uploads/tcb_lp_templates/templates' );
/* will we need another key for Thrive Leads ? */
defined( 'TVE_EDITOR_FLAG' ) || define( 'TVE_EDITOR_FLAG', 'tve' );
defined( 'TVE_FRAME_FLAG' ) || define( 'TVE_FRAME_FLAG', 'tcbf' );
defined( 'TVE_GLOBAL_CSS_PREFIX' ) || define( 'TVE_GLOBAL_CSS_PREFIX', ':not(#tve)' );
defined( 'TVE_TCB_CORE_INCLUDED' ) || define( 'TVE_TCB_CORE_INCLUDED', true );
defined( 'TCB_THUMBNAIL_META_KEY' ) || define( 'TCB_THUMBNAIL_META_KEY', '_tcb_template_thumb' );
define( 'TCB_CT_POST_TYPE', 'tcb_content_template' );
define( 'TVE_CLOUD_TEMPLATES_FOLDER', 'tcb_content_templates' );
define( 'TCB_MIN_WP_VERSION', '4.8' );
define( 'TVE_GLOBAL_COLOR_VAR_CSS_PREFIX', '--tcb-color-' );
define( 'TVE_LP_COLOR_VAR_CSS_PREFIX', '--tcb-tpl-color-' );
define( 'TVE_DYNAMIC_COLOR_VAR_CSS_PREFIX', '--tcb-dynamic-color-' );
define( 'TVE_LOCAL_COLOR_VAR_CSS_PREFIX', '--tcb-local-color-' );
define( 'TVE_GLOBAL_GRADIENT_VAR_CSS_PREFIX', '--tcb-gradient-' );
define( 'TVE_LP_GRADIENT_VAR_CSS_PREFIX', '--tcb-tpl-gradient-' );
define( 'TVE_LOCAL_GRADIENT_VAR_CSS_PREFIX', '--tcb-local-gradient-' );
define( 'TVE_MAIN_COLOR_H', '--tcb-main-master-h' ); //Main Color Hue
define( 'TVE_MAIN_COLOR_S', '--tcb-main-master-s' ); //Main Color Saturation
define( 'TVE_MAIN_COLOR_L', '--tcb-main-master-l' ); //Main Color Lightness
define( 'TVE_GLOBAL_STYLE_CLS_PREFIX', 'tcb-global-' );
define( 'TVE_GLOBAL_STYLE_BUTTON_CLS_PREFIX', TVE_GLOBAL_STYLE_CLS_PREFIX . 'button-' );
define( 'TVE_GLOBAL_STYLE_SECTION_CLS_PREFIX', TVE_GLOBAL_STYLE_CLS_PREFIX . 'section-' );
define( 'TVE_GLOBAL_STYLE_CONTENTBOX_CLS_PREFIX', TVE_GLOBAL_STYLE_CLS_PREFIX . 'contentbox-' );
define( 'TVE_GLOBAL_STYLE_LINK_CLS_PREFIX', TVE_GLOBAL_STYLE_CLS_PREFIX . 'link-' );
define( 'TVE_GLOBAL_STYLE_TEXT_CLS_PREFIX', TVE_GLOBAL_STYLE_CLS_PREFIX . 'text-' );
defined( 'TVE_ICON_API' ) || define( 'TVE_ICON_API', '//landingpages.thrivethemes.com/cloud-api/icons-api.php' );

// global options
// all style sheet families listed below will be added to the editor.
global $tve_style_family_classes;
global $tve_thrive_shortcodes;
// append version to dynamically changed stylesheets, because browsers will cache them
$_version = get_bloginfo( 'version' );

$tve_style_family_classes = array(
	'Flat'    => 'tve_flt',
	'Classy'  => 'tve_clsy',
	'Minimal' => 'tve_min',
);

/* theme shortcodes available in TCB */
// list of shortcode identifier => callback function
/*
 * the callback function will be called with an array of attributes and must return a html code to be inserted into the DOM
 */
$tve_thrive_shortcodes = array(
	'post_symbol'                         => 'tcb_symbol_shortcode',
	'optin'                               => 'tve_do_optin_shortcode',
	'posts_list'                          => 'tve_do_posts_list_shortcode',
	'custom_menu'                         => 'tve_do_custom_menu_shortcode',
	'custom_phone'                        => 'tve_do_custom_phone_shortcode',
	'post_grid'                           => 'tve_do_post_grid_shortcode',
	'widget_menu'                         => 'tve_render_widget_menu',
	'leads_shortcode'                     => 'tve_do_leads_shortcode',
	'tve_leads_additional_fields_filters' => 'tve_leads_additional_fields_filters',
	'social_default'                      => 'tve_social_render_default',
	'tvo_shortcode'                       => 'tvo_render_shortcode',
	'ultimatum_shortcode'                 => 'tve_ult_render_shortcode',
	'quiz_shortcode'                      => 'tqb_render_shortcode',
	'thrive_widget'                       => 'thrive_widget_render',
);

/**
 * If a file called .flag-staging-templates exists, turn off caching of cloud templates
 */
if ( file_exists( plugin_dir_path( __FILE__ ) . '.flag-staging-templates' ) && ! defined( 'TCB_CLOUD_API_LOCAL' ) ) {
	define( 'TVE_STAGING_TEMPLATES', true );
	define( 'TCB_CLOUD_API_LOCAL', 'https://staging.landingpages.thrivethemes.com/cloud-api/index-api.php' );
	defined( 'TCB_TEMPLATE_DEBUG' ) || define( 'TCB_TEMPLATE_DEBUG', true );
	defined( 'TCB_CLOUD_DEBUG' ) || define( 'TCB_CLOUD_DEBUG', true );
	defined( 'TL_CLOUD_DEBUG' ) || define( 'TL_CLOUD_DEBUG', true );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '.flag-nocache' ) ) {
	defined( 'TCB_TEMPLATE_DEBUG' ) || define( 'TCB_TEMPLATE_DEBUG', true );
	defined( 'TCB_CLOUD_DEBUG' ) || define( 'TCB_CLOUD_DEBUG', true );
	defined( 'TL_CLOUD_DEBUG' ) || define( 'TL_CLOUD_DEBUG', true );
}
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-custom-fields-shortcode.php';
require_once TVE_TCB_ROOT_PATH . 'inc/compat.php';
require_once TVE_TCB_ROOT_PATH . 'inc/helpers/social.php';
require_once TVE_TCB_ROOT_PATH . 'inc/helpers/cloud.php';
require_once TVE_TCB_ROOT_PATH . 'inc/functions.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-editor-ajax.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-editor.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-elements.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-font-manager.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-icon-manager.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-post.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-utils.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-search-form.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/class-tcb-post-list.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/class-tcb-post-list-content.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/class-tcb-post-list-author-image.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/class-tcb-post-list-featured-image.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/class-tcb-post-list-shortcodes.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/pagination/class-tcb-pagination.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/pagination/class-tcb-pagination-load-more.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/pagination/class-tcb-pagination-none.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/post-list/pagination/class-tcb-pagination-numeric.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/logo/class-tcb-logo.php';
require_once TVE_TCB_ROOT_PATH . 'inc/woocommerce/classes/class-main.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/symbols/class-tcb-symbols-post-type.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/symbols/class-tcb-symbol-template.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/symbols/class-tcb-symbols-dashboard.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/symbols/class-tcb-symbols-taxonomy.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-menu-walker.php';
require_once TVE_TCB_ROOT_PATH . 'landing-page/inc/class-tcb-landing-page.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-lightbox.php';
require_once TVE_TCB_ROOT_PATH . 'inc/classes/class-tcb-login-element-handler.php';
require_once TVE_TCB_ROOT_PATH . 'inc/helpers/file-upload.php';

/* init the Event Manager */
require_once TVE_TCB_ROOT_PATH . 'event-manager/init.php';

add_action( 'admin_init', 'tve_revert_page_to_theme' );

/* ajax calls through WP API */
add_action( 'wp_ajax_tve_ajax_load', 'tve_ajax_load' );
add_action( 'wp_ajax_tve_load_user_template', 'tve_load_user_template' );
add_action( 'wp_ajax_load_element_from_api', 'tve_load_element_from_api' );
add_action( 'wp_ajax_tve_landing_pages_load', 'tve_landing_pages_load' );
add_action( 'wp_ajax_tve_do_post_grid_shortcode', 'tve_do_post_grid_shortcode' );
add_action( 'wp_ajax_tve_ajax_update_option', 'tve_ajax_update_option' );
add_action( 'wp_ajax_tve_social_count', 'tve_social_ajax_count' );
add_action( 'wp_ajax_nopriv_tve_social_count', 'tve_social_ajax_count' );
add_action( 'wp_ajax_tve_cf_submit', 'tve_submit_contact_form' );
add_action( 'wp_ajax_nopriv_tve_cf_submit', 'tve_submit_contact_form' );

add_action( 'wp_ajax_tve_cloud_templates', 'tve_ajax_landing_page_cloud' );

add_action( 'wp_enqueue_scripts', 'tve_enqueue_editor_scripts' );

/**
 * always enqueue the dash frontend script
 */
add_filter( 'tve_dash_enqueue_frontend', '__return_true' );

/**
 * hook for social share counts via ajax
 */
add_filter( 'tve_dash_main_ajax_tcb_social', 'tve_social_dash_ajax_share_counts', 10, 2 );

/**
 * Autoresponder APIs AJAX calls
 */
if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || apply_filters( 'tve_leads_include_auto_responder', false ) ) {
	add_action( 'wp_ajax_tve_api_editor_actions', 'tve_api_editor_actions' );

	/**
	 * submit Lead Generation form element via AJAX
	 */
	add_action( 'wp_ajax_nopriv_tve_api_form_submit', 'tve_api_form_submit' );
	add_action( 'wp_ajax_tve_api_form_submit', 'tve_api_form_submit' );

	add_action( 'wp_ajax_nopriv_tve_custom_form_submit', 'tve_custom_form_submit' );
	add_action( 'wp_ajax_tve_custom_form_submit', 'tve_custom_form_submit' );
}

/** CONTENT REVISION HOOKS */
/**
 * Append fields to be tracked of changes
 * This filter is called in revisions view
 */
add_filter( '_wp_post_revision_fields', 'tve_post_revision_fields', 10, 1 );
/** Restore content to revision */
add_action( 'wp_restore_post_revision', 'tve_restore_post_to_revision', 11, 2 );
/** Decide if post has changed and save a revision for it */
add_filter( 'wp_save_post_revision_post_has_changed', 'tve_post_has_changed', 10, 3 );

add_action( 'wp_enqueue_scripts', 'tve_remove_conflicting_scripts', PHP_INT_MAX );

// add the same tve_editor_filter but on this case on Landing Page templates - only applies to TCB
add_filter( 'tve_landing_page_content', 'tve_editor_content' );

// add TCB buttons to admin post/page listing screen
add_filter( 'page_row_actions', 'thrive_page_row_buttons', 10, 2 );
add_filter( 'post_row_actions', 'thrive_page_row_buttons', 10, 2 );

/* we need to always load this into the head section, because some themes styles will overwrite the font settings */
add_action( 'wp_head', 'tve_load_font_css' );
add_action( 'wp_head', 'tve_load_global_variables' );

/* load meta tags so scrapers can find them */
add_action( 'wp_head', 'tve_load_meta_tags' );

// add thrive edit link to admin bar
add_filter( 'tve_dash_admin_bar_nodes', 'thrive_editor_admin_bar' );

// To fight against themes creating custom wpautop scripts and injecting rogue <br/> and <p> tags into content we have to apply shortcodes early, then add our content to the page
// at priority 101, hence the two separate "the_content" actions
if ( ! is_admin() ) {
	add_action( 'wp', 'tve_wp_action' );
	function tve_wp_action() {
		add_filter( 'the_content', 'tve_clean_wp_editor_content', - 100 );
		add_filter( 'the_content', 'tve_editor_content', is_editor_page() ? PHP_INT_MAX : 10 );
	}
} else {
	require_once( TVE_TCB_ROOT_PATH . 'admin/class-tcb-admin.php' );
}

// manipulate social sharing hooks so that they work with TCB
if ( has_filter( 'dd_hook_wp_content' ) ) {
	remove_filter( 'the_content', 'dd_hook_wp_content' );
	add_filter( 'the_content', 'dd_hook_wp_content', 103 );
}

// make sure WP editor page doesn't overwrite TCB content
add_filter( 'is_protected_meta', 'tve_hide_custom_fields', 10, 2 );

// use settings API to store non post-level settings
add_action( 'init', 'tve_global_options_init' );

/* hook to defined location of translations files */
add_action( 'init', 'tve_load_plugin_textdomain' );

add_action( 'thrive_dashboard_loaded', 'tcb_dashboard_loaded' );

/* hook for displaying the main editor page ( control panel + content frame ) - only if the tve param is present */
if ( ! empty( $_REQUEST[ TVE_EDITOR_FLAG ] ) ) {
	if ( is_admin() ) {
		add_action( 'init', array( tcb_editor(), 'on_admin_init' ), 20 );
		/* Disable cache on editor page*/
		add_action( 'init', array( tcb_editor(), 'disable_content_cache' ), 20 );
	}
	add_action( 'post_action_architect', array( tcb_editor(), 'post_action_architect' ), 0 );
}

//rest routes
add_action( 'rest_api_init', 'tcb_create_admin_rest_routes' );

add_action( 'rest_api_init', 'tcb_rest_api_init' );

function tcb_rest_api_init() {
	TCB_Post_List::rest_api_init();
	TCB_Logo::rest_api_init();
}

// hook for detecting if a post is setup as a Custom Editable piece of content
add_action( 'template_redirect', 'tcb_custom_editable_content', 9 );

/**
 * filter used to clean meta-data stuff from the content, when displaying it on frontend, e.g.: lead generation code being saved in the HTML causes SEO issues
 */
add_filter( 'tcb_clean_frontend_content', 'tcb_clean_frontend_content' );

/**
 * init the Pinterest SDK
 */
add_action( 'tve_socials_init_pinterest', 'tve_socials_init_pinterest' );

add_filter( 'tve_filter_custom_fonts_for_enqueue_in_editor', 'tve_filter_custom_fonts_for_enqueue_in_editor' );

/**
 * shows a message in the main media uploader window that states: "Only .xxx files are allowed"
 */
add_action( 'post-upload-ui', 'tve_media_restrict_filetypes' );

/* only TCB-specific classes should be loaded here */
add_action( 'init', 'tve_load_tcb_classes' );

add_action( 'wp_footer', array( tcb_editor(), 'inner_frame_menus' ), 100 );
add_action( 'wp', array( tcb_editor(), 'clean_inner_frame' ) );

/**
 * Actions used for handling the interim login ( login via popup in TCB editor page )
 */
add_filter( 'tvd_auth_check_data', 'tcb_auth_check_data' );

add_action( 'thrive_prepare_migrations', 'tcb_prepare_db_migrations' );

/**
 * Add Thrive Architect to Gutenberg dropdown
 */

add_action( 'admin_print_scripts-edit.php', 'gutenberg_tcb_menu' );

function gutenberg_tcb_menu() {
	if ( is_plugin_active( 'gutenberg/gutenberg.php' ) && is_plugin_active( 'thrive-visual-editor/thrive-visual-editor.php' ) && tve_tcb__license_activated() ) {
		tve_enqueue_script( 'thrive-gutenberg-switch', tve_editor_url() . '/editor/js/admin/gutenberg-menu.js', array( 'jquery' ) );
	}
}

/**
 * Enable Thrive Architect as editor instead of any other
 */

add_filter( 'replace_editor', 'architect_init', 9, 2 );
/**
 * @param $return
 * @param $post
 *
 * @return mixed
 */
function architect_init( $return, $post ) {
	global $post_type;
	if ( isset( $_REQUEST['architect'] ) ) {

		if ( true === $return && current_filter() === 'replace_editor' ) {
			return $return;
		}

		add_filter( 'screen_options_show_screen', '__return_false' );

		$post->post_title  = urldecode( $_REQUEST['title'] );
		$post->post_status = 'draft';
		wp_update_post( $post );

		$editor_link = tcb_get_editor_url( $post->ID );
		if ( wp_redirect( $editor_link ) ) {
			exit;
		}
	}
}

if ( ! function_exists( 'tve_editor_url' ) ) {
	/**
	 * @return string the absolute url to this plugin's folder
	 *
	 * @param string $file optional, a path inside the plugin folder
	 */
	function tve_editor_url( $file = null ) {
		return rtrim( TVE_EDITOR_URL . ( null !== $file ? ltrim( $file, '/\\' ) : '' ), '/' );
	}
}

/**
 * Enable unavailable shortcode tooltips inside the editor
 */
add_filter( 'td_smartsite_shortcode_tooltip', 'is_editor_page_raw' );

/**
 * Symbol css backwards compatible
 */
add_filter( 'tcb_symbol_css_before', 'symbols_css_backwards_compatible', 10, 2 );

/* Replace tve_editor from css with global css prefix ( :not(#tve) )*/
add_filter( 'tcb_custom_css', 'tcb_custom_css' );

/**
 *Replaces element type with post_list if the type is post_list_featured
 */
add_filter( 'tcb_cloud_templates_replace_featured_type', array( 'TCB_Post_List', 'featured_type_replace' ) );

/**
 *Replaces element tag with post_list_featured if the type is post_list_featured
 */
add_filter( 'tcb_cloud_templates_replace_featured_tag', array( 'TCB_Post_List', 'post_list_tag_replace' ), 10, 2 );

/**
 * Checks if the post type is not blacklisted
 */
add_filter( 'tve_allowed_post_type', 'tar_is_post_type_allowed', 10, 2 );

/**
 * Default styles - printed earlier than global style, only on non-editor pages
 */
add_action( 'wp_head', function () {
	if ( tcb_should_print_unified_styles() ) {
		tcb_print_frontend_styles();
	}
}, 90, 0 );

/**
 * Backwards compatibility
 * Replace #tve_editor with the new selector
 *
 * Also checks if the disable css option is checked from Thrive Dashboard.
 * If so, we strip the import statements from the css string
 *
 * Gets called from all products that have TAR as a dependency
 *
 * @param $css
 *
 * @return string
 */
function tcb_custom_css( $css ) {

	if ( tve_dash_is_google_fonts_blocked() ) {
		$css = preg_replace( '/@import url\(\"(http:|https:)?\/\/fonts\.(googleapis|gstatic)\.com([^)]*)\);/', '', $css );
	}

	return str_replace( '#tve_editor', tcb_selection_root(), $css );
}

/**
 * render all necessary things for page-level event manager
 *
 * @param array $events
 */
if ( ! function_exists( 'tve_page_events' ) ) {
	function tve_page_events( $events = array() ) {
		$triggers = tve_get_event_triggers( 'page' );
		$actions  = tve_get_event_actions( 'page' );

		/* hold all the javascript callbacks required for the identified actions */
		$javascript_callbacks = isset( $GLOBALS['tve_event_manager_callbacks'] ) ? $GLOBALS['tve_event_manager_callbacks'] : array();

		/* holds all the Global JS required by different actions and event triggers on page load */
		$registered_javascript_globals = isset( $GLOBALS['tve_event_manager_global_js'] ) ? $GLOBALS['tve_event_manager_global_js'] : array();

		/* hold all instances of the Action classes in order to output stuff in the footer, we need to get out of the_content filter */
		$registered_actions = isset( $GLOBALS['tve_event_manager_actions'] ) ? $GLOBALS['tve_event_manager_actions'] : array();

		/* each trigger instance might also need a bit of javascript to trigger it */
		$registered_triggers = isset( $GLOBALS['tve_event_manager_triggers'] ) ? $GLOBALS['tve_event_manager_triggers'] : array();

		/*
		 * all page level events
		 */
		foreach ( $events as $index => $event_config ) {
			if ( empty( $event_config['t'] ) || empty( $event_config['a'] ) || ! isset( $triggers[ $event_config['t'] ] ) || ! isset( $actions[ $event_config['a'] ] ) ) {
				continue;
			}
			/** @var TCB_Event_Action_Abstract $action */
			$action                = $actions[ $event_config['a'] ];
			$registered_actions [] = array(
				'class'        => $action,
				'event_config' => $event_config,
			);

			/** @var TCB_Event_Trigger_Abstract $trigger */
			$trigger                = $triggers[ $event_config['t'] ];
			$registered_triggers [] = array(
				'class'        => $trigger,
				'event_config' => $event_config,
			);

			if ( ! isset( $javascript_callbacks[ $event_config['a'] ] ) ) {
				$javascript_callbacks[ $event_config['a'] ] = $action->getJsActionCallback();
			}
			if ( ! isset( $registered_javascript_globals[ 'action_' . $event_config['a'] ] ) ) {
				$registered_javascript_globals[ 'action_' . $event_config['a'] ] = $action;
			}
			if ( ! isset( $registered_javascript_globals[ 'trigger_' . $event_config['t'] ] ) ) {
				$registered_javascript_globals[ 'trigger_' . $event_config['t'] ] = $trigger;
			}
		}

		if ( empty( $javascript_callbacks ) ) {
			return;
		}

		/* we need to add all the javascript callbacks into the page */
		/* this cannot be done using wp_localize_script WP function, as each if the callback will actually be JS code */
		///euuuughhh

		$GLOBALS['tve_event_manager_callbacks'] = $javascript_callbacks;
		$GLOBALS['tve_event_manager_global_js'] = $registered_javascript_globals;
		$GLOBALS['tve_event_manager_actions']   = $registered_actions;
		$GLOBALS['tve_event_manager_triggers']  = $registered_triggers;

		/* execute the mainPostCallback on all of the related actions, some of them might need to register stuff (e.g. lightboxes) */
		foreach ( $GLOBALS['tve_event_manager_actions'] as $key => $item ) {
			if ( empty( $item['main_post_callback_'] ) ) {
				$GLOBALS['tve_event_manager_actions'][ $key ]['main_post_callback_'] = true;
				$item['class']->mainPostCallback( $item['event_config'] );
			}
		}

		/* remove previously assigned callback, if any */
		remove_action( 'wp_print_footer_scripts', 'tve_print_footer_events', - 50 );
		add_action( 'wp_print_footer_scripts', 'tve_print_footer_events', - 50 );
	}
}


add_filter( 'tve_frontend_options_data', 'tve_frontend_data' );

/**
 * Smart complete related stuff added through a filter so other plugins can use smart complete when TAr doesnt laod the frontend files directly
 *
 * @param $frontend_options
 *
 * @return mixed
 */
function tve_frontend_data( $frontend_options ) {
	$is_editor                        = is_editor_page();
	$frontend_options['ip']           = tve_dash_get_ip();
	$frontend_options['current_user'] = tve_current_user_data();

	if ( isset( $frontend_options['is_single'] ) && $frontend_options['is_single'] === '1' ) {
		$post_id                        = get_the_ID();
		$frontend_options['post_id']    = $post_id;
		$frontend_options['post_title'] = get_the_title();
		$frontend_options['post_type']  = get_post_type();
		$frontend_options['post_url']   = get_permalink();
		if ( ! $is_editor ) {
			$tcb_post                  = tcb_post( $post_id );
			$frontend_options['is_lp'] = $tcb_post->is_landing_page();
		}
	}

	if ( ! $is_editor ) {
		$frontend_options['post_request_data'] = empty( $_POST ) ? array() : $_POST;
	}

	return $frontend_options;
}

add_action( 'after_switch_theme', 'tve_reset_cloud_templates' );

/**
 * On theme switch we delete the Cloud Template Cache from transients
 */
function tve_reset_cloud_templates() {
	delete_transient( tve_get_cloud_templates_transient_name() );
}
