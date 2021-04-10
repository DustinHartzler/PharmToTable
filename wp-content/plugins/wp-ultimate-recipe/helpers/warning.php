<?php

class WPURP_Warning {

    public function __construct()
    {
        // Actions
        add_action( 'admin_menu', array( $this, 'warning_menu' ), 999 );
    }

    public function warning_menu() {
        add_submenu_page( 'edit.php?post_type=recipe', 'WP Ultimate Recipe ' . __( 'Warning', 'wp-ultimate-recipe' ), '<span style="color:red;">⚠ ' . __( 'Warning', 'wp-ultimate-recipe' ) . '</span>', 'manage_options', 'wpurp_warning', array( $this, 'warning_page' ) );
    }

    public function warning_page() {
        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        include( WPUltimateRecipe::get()->coreDir . '/static/warning.php' );
    }
}