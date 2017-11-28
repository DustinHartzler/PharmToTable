<?php
/**
 * Plugin Name: Custom Functions for Pharm To Table
 * Description: A custom admin color scheme generated with <a href="http://themergency.com/generators/admin-color-scheme-generator" target="_blank">Admin Color Scheme Generator</a>
 * Author: Dustin Hartzler
 * Version: 1.0
 * Text Domain: your-website-engineer-color-scheme
 * License: GPL2
 *
 * Copyright 2013 Dustin Hartzler
 */

function wpb_add_google_fonts() {

	wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Yellowtail', false );
}

add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );

function myprefix_mce_buttons_1( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

// Add Shortcode
function signature_shortcode( $atts , $content = null ) {
	// Attributes
	extract( shortcode_atts(
		array(
			'type' => 'HTML',
		), $atts )
	);

	// Code
	return $signature .'<div class="signature">Dr. Hartzler</div>';

}
add_shortcode( 'drh', 'signature_shortcode' );
