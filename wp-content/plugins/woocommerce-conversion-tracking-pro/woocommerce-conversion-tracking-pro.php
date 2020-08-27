<?php
/*
Plugin Name: WooCommerce Conversion Tracking Pro
Plugin URI: https://wedevs.com/products/plugins/woocommerce-conversion-tracking/
Description: Premium version of WooCommerce Conversion Tracking Plugin
Version: 1.0.3
Author: weDevs
Author URI: https://wedevs.com/
Developer: weDevs
Developer URI: https://wedevs.com
Text Domain: woocommerce-conversion-tracking-pro
Domain Path: /languages
Woo: 4733156:0128ff6530d37b94f4561ae336312a83
Copyright: © 2009-2019 WooCommerce.
License: GPL2
WC requires at least: 3.0
WC tested up to: 3.6.2
*/

/**
 * Copyright (c) 2018 weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WeDevs_WC_Conversion_Tracking_Pro class
 */
class WeDevs_WC_Conversion_Tracking_Pro {

    /**
     * Plugin version
     *
     * @var string
     */
    private $version = '1.0.3';

    /**
     * Store the plan
     *
     * @var string
     */
    private $plan   = 'wcct-pro';

    /**
     * Constructor for Conversion tracking pro
     */
    function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );

        add_action( 'wcct_loaded', array( $this, 'includes' ) );
        add_action( 'wcct_loaded', array( $this, 'init_classes' ), 10 );
        add_action( 'admin_notices', array( $this, 'check_wcct' ) );

        add_filter( 'wcct_integrations', array( $this, 'add_integrations' ), 120, 1 );
    }

    /**
     * Initializes the WeDevs_WC_Conversion_Tracking() class
     *
     * Checks for an existing WeDevs_WC_Conversion_Tracking() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WeDevs_WC_Conversion_Tracking_Pro();
        }

        return $instance;
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WCCT_PRO_FILE', __FILE__ );
        define( 'WCCT_PRO_DIR', dirname( __FILE__ ) );
        define( 'WCCT_PRO_PATH', dirname( WCCT_PRO_FILE ) );
        define( 'WCCT_PRO_INCLUDES', WCCT_PRO_PATH . '/includes' );
        define( 'WCCT_PRO_URL', plugins_url( '', WCCT_PRO_FILE ) );
        define( 'WCCT_PRO_VERSION', $this->version );
    }

    /**
     * Plugin activation function
     */
    public function activate() {
        update_option( 'wcct_pro_version', WCCT_PRO_VERSION );
    }

    /**
     * Include all require files
     *
     * @return void
     */
    public function includes() {
        require_once WCCT_PRO_INCLUDES . '/class-admin.php';
        require_once WCCT_PRO_INCLUDES . '/class-ajax.php';
        require_once WCCT_PRO_INCLUDES . '/class-product-exporter.php';
        require_once WCCT_PRO_INCLUDES . '/functions.php';
    }

    /**
     * Initiate all required class
     *
     * @return void
     */
    public function init_classes() {
        new WCCT_Pro_Admin();
        new WCCT_Pro_Ajax();
        new WCCT_Product_Exporter();
    }

    /**
     * Add integration
     *
     * @param array $integrations
     */
    public function add_integrations( $integrations ) {
        $new_integrations = array(
            'perfect-audience'  => require_once WCCT_PRO_INCLUDES . '/integrations/class-integration-perfect-audience.php',
            'facebook'          => require_once WCCT_PRO_INCLUDES . '/integrations/class-integration-facebook-pro.php',
            'twitter'           => require_once WCCT_PRO_INCLUDES . '/integrations/class-integration-twitter-pro.php',
            'google'            => require_once WCCT_PRO_INCLUDES . '/integrations/class-integration-google-pro.php',
            'bing-ads'          => require_once WCCT_PRO_INCLUDES . '/integrations/class-integration-bing-adds.php'
        );

        return $this->array_replace( $integrations, $new_integrations );
    }

    /**
     * Array Replace
     *
     * @param  array $find_array
     * @param  array $replaced_arrray
     * @return array
     */
    private function array_replace( $find_array, $replaced_arrray ) {

        if ( empty( $replaced_arrray ) ) {
            return $find_array;
        }

        foreach ( $replaced_arrray as $key => $value ) {
            if ( array_key_exists( $key, $find_array ) ) {
                $find_array[ $key ] = $value;
            } else {
                $find_array[] = $value;
            }
        }

        return $find_array;
    }

    /**
     * Check Woocommerce Conversion Tracking exist
     *
     * @return void
     */
    public function check_wcct() {
        if ( ! class_exists( 'WeDevs_WC_Conversion_Tracking' ) ) {
            ?>
                <div class="error notice is-dismissible">
                    <p><?php esc_attr_e( '<b>Woocommerce Conversion Tracking Pro</b> requires <a href="https://wordpress.org/plugins/woocommerce-conversion-tracking/" target="_blank">Woocommerce Conversion Tracking</a>', 'woocommerce-conversion-tracking' );?></p>
                </div>
            <?php
        }
    }
}

// WeDevs_WC_Conversion_Tracking_Pro
$wc_tracking = WeDevs_WC_Conversion_Tracking_Pro::init();
