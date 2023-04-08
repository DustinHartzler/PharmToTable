<?php

/*
	Plugin Name: aaa PTT Functions
	Description: Custom blocks for PharmToTable
	Version: 1.0
	Author: Dustin
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class PTTFunctions {
	function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'adminAssets' ) );
	}
	function adminAssets() {
		wp_enqueue_script( 'ournewblocktype', plugin_dir_url( __FILE__ ) . 'test.js', array( 'wp-blocks', 'wp-element' ) );
		wp_enqueue_script( 'silverscript', 'https://fonts.googleapis.com/css?family=Poppins:300,500,600,700,800', );
	}
}
$pttFunctions = new PTTFunctions();
