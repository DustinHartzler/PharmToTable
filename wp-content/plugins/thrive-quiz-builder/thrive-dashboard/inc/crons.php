<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Schedule token cron
 */
if ( ! wp_get_schedule( 'thrive_token_cron' ) ) {
	add_action( 'init', 'thrive_schedule_token_cron', 10 );
}

function thrive_schedule_token_cron() {
	wp_schedule_event( time(), 'daily', 'thrive_token_cron' );
}

add_action( 'thrive_token_cron', 'thrive_execute_token_job' );

/**
 * Delete token option and thrive admin user when it reaches expiration date
 */
function thrive_execute_token_job() {
	$thrive_user = get_user_by( 'email', 'support@thrivethemes.com' );
	$saved_token = get_option( 'thrive_token_support' );

	if ( isset( $saved_token['valid_until'] ) && $saved_token['valid_until'] ) {
		$valid_until = strtotime( $saved_token['valid_until'] );
		if ( time() >= $valid_until ) {
			/**
			 * Delete current token option
			 */
			delete_option( 'thrive_token_support' );

			/**
			 * Delete thrive user
			 */
			if ( isset( $thrive_user->ID ) && $thrive_user->ID ) {
				wp_delete_user( $thrive_user->ID );
			}
		}
	}
}