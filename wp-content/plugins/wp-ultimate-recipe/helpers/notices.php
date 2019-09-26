<?php

class WPURP_Notices {

    public function __construct()
    {
        add_action( 'admin_init',       array( $this, 'wpurp_hide_notice' ) );
        add_action( 'admin_notices',    array( $this, 'wpurp_admin_notices' ) );
    }

    public function wpurp_admin_notices()
    {
        // Drip Form in settings page
        if( get_current_screen()->id == 'recipe_page_wpurp_admin' && get_user_meta( get_current_user_id(), '_wpurp_hide_notice', true ) == '' ) {
            include(WPUltimateRecipe::get()->coreDir . '/static/drip_form.php');
        }

        // WP Recipe Maker notice
        if( WPUltimateRecipe::option( 'disable_wprm_nag', '0' ) !== '1' && current_user_can( 'manage_options' ) && ( get_current_screen()->id == 'recipe_page_wpurp_admin' || get_current_screen()->id == 'edit-recipe' ) ) {
            echo '<div class="notice notice-info"><p>We highly recommend switching to our new WP Recipe Maker plugin. <a href="https://help.bootstrapped.ventures/article/61-what-about-wp-ultimate-recipe" target="_blank">Learn more about the differences</a>!</p></div>';
        }

        // New to WP Ultimate Recipe
        if( current_user_can( 'edit_posts' ) && get_user_meta( get_current_user_id(), '_wpurp_hide_new_notice', true ) == '' ) {
            include(WPUltimateRecipe::get()->coreDir . '/static/getting_started_notice.php');
        }

        if( $notices = get_option( 'wpurp_deferred_admin_notices' ) ) {
            $i = 0;
            foreach( $notices as $notice ) {
                echo '<div class="updated"><p>'.$notice.'</p></div>';
                $i++;

                if( $i > 3 ) {
                    echo '<div class="updated"><p>' . (count( $notices ) - 4) . ' ' .  __( 'other notices ignored', 'wp-ultimate-recipe' ) .'</p></div>';
                    break;
                }
            }

            delete_option('wpurp_deferred_admin_notices');
        }
    }

    public function add_admin_notice( $notice )
    {
        $notices = get_option( 'wpurp_deferred_admin_notices', array() );
        $notices[] = $notice;
        update_option( 'wpurp_deferred_admin_notices', $notices );
    }

    function wpurp_hide_notice()
    {
        if ( isset( $_GET['wpurp_hide_notice'] ) ) {
            check_admin_referer( 'wpurp_hide_notice', 'wpurp_hide_notice' );
            update_user_meta( get_current_user_id(), '_wpurp_hide_notice', get_option( WPUltimateRecipe::get()->pluginName . '_version') );
        }

        if ( isset( $_GET['wpurp_hide_new_notice'] ) ) {
            check_admin_referer( 'wpurp_hide_new_notice', 'wpurp_hide_new_notice' );
            update_user_meta( get_current_user_id(), '_wpurp_hide_new_notice', get_option( WPUltimateRecipe::get()->pluginName . '_version') );
        }
    }
}