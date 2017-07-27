<?php

class WPURP_Giveaway {

    public function __construct()
    {
        $now = new DateTime();
		$giveaway_start = new DateTime( '2017-01-13 17:00:00', new DateTimeZone( 'Europe/Brussels' ) );
		$giveaway_end = new DateTime( '2017-01-27 23:00:00', new DateTimeZone( 'Europe/Brussels' ) );

		if ( $giveaway_start < $now && $now < $giveaway_end ) {
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 99 );
            add_action( 'admin_notices',    array( $this, 'giveaway_notice' ) );
		}
    }

    public function add_submenu_page()
    {
        add_submenu_page( 'edit.php?post_type=' . WPURP_POST_TYPE, 'Giveaway', '~ Plugin Giveaway! ~', 'manage_options', 'wpurp_giveaway', array( $this, 'page_template' ) );
	}

	public function page_template() {
		echo '<div class="wrap">';
		echo '<h1>Plugin Giveaway</h1>';
		echo '<a class="e-widget no-button" href="https://gleam.io/sTRSD/birthday-giveaway-2017" rel="nofollow">Birthday Giveaway 2017</a>';
		echo '<script type="text/javascript" src="https://js.gleam.io/e.js" async="true"></script>';
		echo '</div>';
	}

    public function giveaway_notice()
    {
        $screen = get_current_screen();

        if ( WPURP_POST_TYPE === $screen->post_type && 'recipe_page_wpurp_giveaway' !== $screen->id && !WPUltimateRecipe::is_premium_active() ) {
            echo '<div class="updated" style="padding: 10px;">';
			echo '<strong>Feeling lucky?</strong> Win plugins in our <a href="' . esc_url( admin_url( 'edit.php?post_type=' . WPURP_POST_TYPE . '&page=wpurp_giveaway' ) ) . '" target="_blank">Birthday Giveaway</a>!';
			echo '</div>';
        }
    }
}