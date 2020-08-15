<?php
/**
 * Storefront Visual Hook Guide engine room
 *
 * @package storefront-visual-guide
 * @since   1.1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }

/**
 * Main Storefront Visual Guide Class.
 *
 */
class WooCommerce_Visual_Guide {

  /**
   * Storefront_Visual_Guide Constructor.
   */
  public function __construct() {
    add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu'), 100 );
    add_action( 'get_header', array( $this, 'woocommerce_hooker') );
    add_action( 'svg_woo_hook_button', array( 'Storefront_Visual_Guide', 'hooked_functions_button' ) );
    add_action( 'svg_woo_hook_content', array( 'Storefront_Visual_Guide', 'action_hooks_content') );
    add_action( 'svg_woo_hook_toggle', array( 'Storefront_Visual_Guide', 'remove_hooked_functions_toggle' ) );
  }

  /**
   * Adds admin-bar menu option to the frontend.
   *
   * @since   1.1.0
   */
  public function admin_bar_menu() {
		global $wp_admin_bar;

		if ( is_admin() )
			return;

    if ( isset($_GET['woocommerce_hooks'] ) && $_GET['woocommerce_hooks'] == 'show' ) {
      $wp_admin_bar->add_menu(
        array(
          'id'	   => 'svg_clear_woocommerce_hooks',
          'parent'   => 'sf_visual_guide',
          'title'	   => __( 'Hide Woocommerce Hooks', 'storefront-visual-guide' ),
          'href'	   => esc_url( remove_query_arg( 'woocommerce_hooks' ) ),
          'position' => 40,
        )
      );
    } else {
      $wp_admin_bar->add_menu(
        array(
          'id'	   => 'svg_woocommerce_hooks',
  				'parent'   => 'sf_visual_guide',
  				'title'	   => __( 'Show Woocommerce Hooks', 'storefront-visual-guide' ),
  				'href'	   => esc_url( add_query_arg( 'woocommerce_hooks', 'show' ) ),
  				'position' => 40,
        )
      );
    }
  }

  /**
   * A place to hook the calls to display hooks and filters.
   *
   * @since   1.1.0
   */
  public function woocommerce_hooker() {
    if ( ! ('show' == isset( $_GET['woocommerce_hooks'] ) ) ) {
 			return;
 		}

 		add_action( 'all', array( $this, 'woocommerce_action_hooks'), 1 );
 	}

  /**
   * Display Woocommerce action hooks.
   *
   * @since   1.1.0
   */
  public function woocommerce_action_hooks() {
    global $wp_actions;

    if ( 'show' != isset( $_GET['woocommerce_hooks'] ) ) {
      return;
    }

    if ( ! is_user_logged_in() ) {
      return;  // Avoid non logged users using this plugins functionality
    }

    $current_action = current_filter();
    if ( 'woocommerce_' !== substr( $current_action, 0, 12 ) ) {
			return; // Not a Woocommerce hook or filter.
		}

    if ( $current_action == 'woocommerce_before_template_part' ||
    $current_action == 'woocommerce_after_template_part' ||
    $current_action == 'woocommerce_sidebar' ||
    $current_action == 'woocommerce_shop_loop' ) {
      return; // Woocommerce unused and repetitive template hook.
    }

    if ( ! isset( $wp_actions[ $current_action ] ) ) {
      return; // This action is not an action (it is probably a filter).
    }

    ?> <div id="visual-hook-box" class="woocommerce-visual-hook-box" data-svg-hook-box="<?php echo $current_action; ?>"><h3><?php echo $current_action; ?></h3><?php

    do_action( 'svg_woo_hook_button', $current_action );

    ?><pre class="svg-hooked-functions"><?php

    do_action( 'svg_woo_hook_content', $current_action );

    do_action( 'svg_woo_hook_toggle', $current_action );

    ?> </pre></div> <?php

  }

}

return new WooCommerce_Visual_Guide();
