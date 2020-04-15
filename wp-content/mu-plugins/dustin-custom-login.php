<?php
/*
Plugin Name: Dustin's Custom Login
Plugin URI: http://YourWebsiteEngineer.com
Description: Let's you display a custom login screen
Version: 1.0
Author: Dustin Hartzler
Author URI: http://YourWebsiteEngineer.com
*/

function custom_login_css(){
	echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'dustin-custom-login/style.css' , __FILE__ ) . '"/> ';
}
add_action ('login_head','custom_login_css');

function custom_login_header_url($url){
	return 'https://pharmtotable.life';
}
add_filter('login_headerurl', 'custom_login_header_url');

function login_error_override(){
	return 'Nope! Try again. Incorrect login details.';
}
add_filter('login_errors', 'login_error_override');

function login_checked_remember_me() {
	add_filter( 'login_footer', 'rememberme_checked' );
}
add_action( 'init', 'login_checked_remember_me' );

function rememberme_checked() {
	echo "<script>document.getElementById('rememberme').checked = true;</script>";
}