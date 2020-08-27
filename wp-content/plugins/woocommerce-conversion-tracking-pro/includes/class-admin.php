<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *  The admin page handler class
 */
class WCCT_Pro_Admin {

    /**
     * Constructor for WCCT_Pro_Admin Class
     */
    function __construct() {
        add_filter( 'wcct_nav_tab', array( $this, 'fb_product_catalog_tab' ) );

        add_filter( 'wcct_nav_tab', array( $this, 'settings_tab' ) );

        add_filter( 'wcct_menu_page', array( $this, 'wcct_menu' ) );

        add_filter( 'wcct_capability', array( $this, 'wcct_capability' ) );

        add_action( 'wcct_nav_content_settings', array( $this, 'settings_page' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue Script
     *
     * @return void
     */
    public function enqueue_scripts() {

        /**
         * All style goes here
         */
        wp_enqueue_style( 'style-pro', plugins_url( 'assets/css/style.css', WCCT_PRO_FILE ), false, filemtime( WCCT_PRO_PATH . '/assets/css/style.css' ) );

        /**
         * All script goes here
         */
        wp_enqueue_script( 'clipboard-js', plugins_url( 'assets/js/clipboard.min.js', WCCT_PRO_FILE ), array( 'jquery' ), WCCT_PRO_VERSION, true );
        wp_enqueue_script( 'wcct-pro-admin', plugins_url( 'assets/js/admin.js', WCCT_PRO_FILE ), array( 'jquery'), filemtime( WCCT_PRO_PATH . '/assets/js/admin.js' ), true );

        wp_localize_script(
            'wcct-pro-admin', 'wc_tracking_pro', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
    }

    /**
     * Add facebook product catalog tab
     *
     * @param  array $sections
     *
     * @return array
     */
    public function fb_product_catalog_tab( $sections ) {

        $sections[] = array(
            'id'    => 'product-catalog',
            'title' => __( 'Facebook Product Catalog', 'woocommerce-conversion-tracking-pro' ),
        );

        return $sections;
    }

    /**
     * Add settings tab
     *
     * @param  array $sections
     *
     * @return array
     */
    public function settings_tab( $sections ) {

        if ( ! current_user_can( 'manage_options' ) ) {
            return $sections;
        }

        $sections[] = array(
            'id'    => 'settings',
            'title' => __( 'Settings', 'woocommerce-conversion-tracking-pro' ),
        );

        return $sections;
    }

    /**
     * Settings page
     *
     * @return void
     */
    public function settings_page() {
        global $wp_roles;
        $roles              = $wp_roles->get_names();
        $get_permission     = get_option( 'wcct_permissions' );
        $user               = wp_get_current_user();
        unset( $roles['administrator'] );

        include WCCT_PRO_INCLUDES . '/views/admin/settings.php';
    }

    /**
     * Manage menu
     *
     * @param  string $menu
     *
     * @return string
     */
    public function wcct_menu( $menu ) {

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            $menu = 'tools.php';
        }

        return $menu;
    }

    /**
     * Manage user capability
     *
     * @param  string $caps
     *
     * @return string
     */
    public function wcct_capability( $caps ) {

        $get_permission = get_option( 'wcct_permissions' );

        if ( ! $get_permission ) {
            return $caps;
        }

        foreach ( $get_permission as $key => $value ) {
            if ( current_user_can( $key ) ) {
                $caps   = $key;
            }
        }

        return $caps;
    }
}
