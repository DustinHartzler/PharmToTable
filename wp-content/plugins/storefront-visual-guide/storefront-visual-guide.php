<?php
/*
Plugin Name: Storefront Visual Hook Guide
Description: Find Storefront and Woocommerce action hooks quick and easily by seeing their actual locations inside your theme.
Version: 2.0.1
Author: Antonio Sanchez (ARCHTKT)
Author URI: https://archtkt.com
Text Domain: storefront-visual-guide
Domain Path:  /languages
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 2.0
WC tested up to: 4.0
*/
/*
Storefront Visual Hook Guide is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Storefront Visual Hook Guide is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Storefront Visual Hook Guide. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define plugin path: SVG_PLUGIN_PATH.
if ( ! defined( 'SVG_PLUGIN_PATH' ) ) {
	define( 'SVG_PLUGIN_PATH', __FILE__ );
}

// Define plugin version: SVG_PLUGIN_VERSION.
if ( ! defined( 'SVG_PLUGIN_VERSION' ) ) {
	define( 'SVG_PLUGIN_VERSION', '2.0.1' );
}

/**
 *  Include the Storefront_Visual_Guide_Init class before doing anything else.
 *
 *  @since   1.1.0
 */
if ( ! class_exists( 'Storefront_Visual_Guide_Init' ) ) {
	include_once dirname( SVG_PLUGIN_PATH ) . '/inc/storefront-visual-guide-init.php';
}

/**
 *  Include the Storefront_Visual_Guide class.
 *
 *  @since   1.1.0
 */
if ( ! class_exists( 'Storefront_Visual_Guide' ) ) {
	include_once dirname( SVG_PLUGIN_PATH ) . '/inc/class-storefront-visual-guide.php';
}

/**
 *  Include the WooCommerce_Visual_Guide class.
 *
 *  @since   1.1.0
 */
 if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
     include_once dirname( SVG_PLUGIN_PATH ) . '/inc/class-woocommerce-visual-guide.php';
 }
