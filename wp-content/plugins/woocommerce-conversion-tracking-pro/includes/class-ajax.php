<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Ajax handler class
 */
class WCCT_Pro_Ajax {

    /**
     * WC Conversion Tracking Ajax Class Constructor
     */
    public function __construct() {
        add_action( 'wp_ajax_wcct_save_permissions', array( $this, 'save_permission' ) );
        add_action( 'wp_ajax_wcct_save_authenticate', array( $this, 'save_authenticate' ) );
    }

    /**
     * Save Permission
     *
     * @return void
     */
    public function save_permission() {

        if ( ! current_user_can( wcct_manage_cap() ) ) {
            return;
        }


        $permissions = wp_unslash( $_POST );

        if ( ! wp_verify_nonce( $permissions['_wpnonce'], 'wcct-settings' ) ) {
            return;
        }

        unset( $permissions['action'] );
        unset( $permissions['_wpnonce'] );
        unset( $permissions['_wp_http_referer'] );
        update_option( 'wcct_permissions', $permissions );

        wp_send_json_success( array(
            'message' => __( 'Settings has been saved successfully!', 'woocommerce-conversion-tracking-pro' )
        ) );
    }

    /**
     * Save Authenticate
     *
     * @return void
     */
    public function save_authenticate() {

        if ( ! current_user_can( wcct_manage_cap() ) ) {
            return;
        }

        $data_feed = wp_unslash( $_POST );

        if ( ! wp_verify_nonce( $data_feed['_wpnonce'], 'wcct-authenticate' ) ) {
            return;
        }

        unset( $data_feed['action'] );
        unset( $data_feed['_wpnonce'] );
        unset( $data_feed['_wp_http_referer'] );
        update_option( 'wcct_data_feed', $data_feed );

        wp_send_json_success( array(
            'message' => __( 'Settings has been saved successfully!', 'woocommerce-conversion-tracking-pro' )
        ) );
    }
}
