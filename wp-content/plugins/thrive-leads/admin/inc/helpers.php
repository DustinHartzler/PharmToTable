<?php
/**
 * global array for holding all the wistia videos used across TL admin UI
 */
global $tve_leads_help_videos;
$tve_leads_help_videos = array(
	'Forms'                => 'WprGD8qwBSM',
	// displayed when creating a new form (in the lightbox)
	'LeadGroups'           => 'vWEfgUJQ0LU',
	// displayed when there are no Lead Groups and when adding a new Lead Group
	'LeadShortcodes'       => '9FRCaY26fPo',
	// displayed when there are no Lead Shortcodes and when adding a new Lead Shortcode
	'TwoStepLightbox'      => 'd9rgh2e12aM',
	// displayed when there are no Lead 2-step lightboxes and when adding a new one
	'AssetGroup'           => '6YbgAuOgbwc',
	// displayed when there are no asset groups and when adding a new one
	'TriggerSettings'      => '//fast.wistia.net/embed/iframe/cjdd4fw8pu?popover=true',
	// displayed on the Trigger Settings Lightbox
	'VariationTest'        => 'ZG7CEHgKnAI',
	// displayed when starting a Variation Test (between designs)
	'GroupTest'            => 'aKvdzw3j_MQ',
	// displayed when starting a Group-level test (between form-types)
	'TestChart'            => 'RT9D5LzCa9U',
	// displayed in the upper-part of the Chart on the Test View page
	'TestTableData'        => 'rK_5eKeigxk',
	// displayed in the table header (below the chart on test view page)
	'GroupDisplaySettings' => 'vCJFSfttdnM',
	// displayed in the Group Targeting options popup
	'SignupSegue'          => '5GdzdRKL7G4',
	// displayed when there are no Signup Segues and when adding a new one
);


/** global variable with the colors that will be used in the chart and tables */
global $tve_leads_chart_colors;
$tve_leads_chart_colors = array( '#4bb35e', '#1da5e5', '#fc0', '#ef3131', '#9c27b0', '#cddc39', '#36c4e2', '#525252', '#ff9800', '#e91e63' );

/**
 * Read all templates and output the content into the page
 * called on admin_print_footer_scripts
 *
 * @param string $dir folder to scan
 *
 * @return array with key representing the path and the content stored in value
 */
function tve_leads_backbone_templates() {
	$templates = tve_dash_get_backbone_templates( plugin_dir_path( dirname( __FILE__ ) ) . 'views/template', 'template' );

	tve_dash_output_backbone_templates( $templates );
}

/**
 * Generate an array of dates between $start_date and $end_date depending on the $interval
 *
 * @param        $start_date
 * @param        $end_date
 * @param string $interval - can be 'day', 'week', 'month'
 *
 * @return array $dates
 * @author: Andrei
 *
 */
function tve_leads_generate_dates_interval( $start_date, $end_date, $interval = 'day' ) {
	switch ( $interval ) {
		case 'day':
			$date_format = 'd M, Y';
			break;
		case 'week':
			$date_format = '\W\e\e\k W, o';
			//TODO: the labels should be translated
			break;
		case 'month':
			$date_format = 'F Y';
			break;
		default:
			return array();
	}

	$dates = array();
	for ( $i = 0; strtotime( $start_date . ' + ' . $i . 'day' ) <= strtotime( $end_date ); $i ++ ) {
		$timestamp = strtotime( $start_date . ' + ' . $i . 'day' );
		$date      = date( $date_format, $timestamp );

		//remove the 0 from the week number
		if ( $interval == 'week' ) {
			$date = str_replace( 'Week 0', 'Week ', $date );
		}
		if ( ! in_array( $date, $dates ) ) {
			$dates[] = $date;
		}
	}

	return $dates;
}

/**
 * filter out some post_types that are to be displayed in the Group settings popup
 *
 * @param array $blacklist
 *
 * @return array
 */
function tve_leads_settings_post_types_blacklist( $blacklist ) {
	if ( ! is_array( $blacklist ) ) {
		$blacklist = array();
	}

	$blacklist [] = 'tcb_lightbox';
	$blacklist [] = 'thrive_optin';
	$blacklist [] = 'focus_area';

	return $blacklist;
}

/**
 * filter the most recent items in ThriveBoxes Menu Trigger
 */
function tve_leads_filter_nav_menu_items_two_step_recent( $most_recent_items ) {
	return array_slice( $most_recent_items, 0, 3 );
}

/**
 * Overwrite the quick search ajax action when an user search for on menu item
 * Displays the response(HTML/JSON) if the action is quick-search-posttype-tve_lead_2s_lightbox
 *
 * @return void if the action is not quick-search-posttype-tve_lead_2s_lightbox
 */
function tve_leads_menu_quick_search() {
	if ( empty( $_REQUEST['type'] ) ) {
		return;
	}

	if ( $_REQUEST['type'] !== 'quick-search-posttype-' . TVE_LEADS_POST_TWO_STEP_LIGHTBOX ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

	$args            = array();
	$query           = isset( $request['q'] ) ? $request['q'] : '';
	$response_format = isset( $_REQUEST['response-format'] ) && in_array( $_REQUEST['response-format'], array(
		'json',
		'markup',
	) ) ? $_REQUEST['response-format'] : 'json';

	if ( 'markup' == $response_format ) {
		$args['walker'] = new Walker_Nav_Menu_Checklist;
	}

	query_posts( array(
		'posts_per_page' => 10,
		'post_type'      => TVE_LEADS_POST_TWO_STEP_LIGHTBOX,
		's'              => $query,
	) );

	if ( ! have_posts() ) {
		return;
	}

	while ( have_posts() ) {
		the_post();
		if ( 'markup' == $response_format ) {
			$var_by_ref = get_the_ID();
			echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', array( get_post( $var_by_ref ) ) ), 0, (object) $args );
		} elseif ( 'json' == $response_format ) {
			echo wp_json_encode(
				array(
					'ID'         => get_the_ID(),
					'post_title' => get_the_title(),
					'post_type'  => get_post_type(),
				)
			);
			echo "\n";
		}
	}

	wp_die();
}

/**
 * Does not display extended menu option for ThriveBox menu items
 * Returns false if the menu item type is TVE_LEADS_POST_TWO_STEP_LIGHTBOX
 *
 * @param $display_option
 * @param $menu_item
 *
 * @return bool
 */
function filter_thrive_display_extended_menu_option( $display_option, $menu_item ) {
	return $menu_item->object === TVE_LEADS_POST_TWO_STEP_LIGHTBOX ? false : $display_option;
}

/**
 * Checks if TL needs upgrading some of its data.
 *
 * @return bool
 */
function tve_leads_needs_data_updates() {
	return ! get_option( 'tve_leads_impressions_migrate' );
}

/**
 * DB Update page
 */
function tve_leads_data_updates() {
	if ( tve_leads_needs_data_updates() ) {
		tve_dash_updater_page( new Thrive_Leads_Data_Updater() );
	} else {
		include dirname( __DIR__ ) . '/views/no-updates.php';
	}
}

/**
 *
 * Output a message that TL's database tables need upgrading
 *
 * @return bool
 */
function tve_leads_check_data_updates() {
	if ( tve_leads_needs_data_updates() ) {
		include dirname( __DIR__ ) . '/views/database-update-message.php';
	}
}
