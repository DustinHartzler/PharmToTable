<?php
/**
 * Storefront Visual Hook Guide init room
 *
 * @package storefront-visual-guide
 * @since   1.1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }

/**
 * Main Storefront Visual Guide Init Class.
 */
class Storefront_Visual_Guide_Init {

  /**
   * Storefront_Visual_Guide_Init Constructor.
   *
   * @since   1.1.0
   */
  public function __construct() {
  	register_activation_hook( SVG_PLUGIN_PATH, array( $this, 'activation_check' ) ); //Activation checks for Storefront Theme.
    add_action( 'admin_notices', array( $this, 'theme_change_check' ) ); //Theme change checks for Storefront Theme.
  }

  /**
   * Activation checks for Storefront Theme.
   *
   * @since   1.1.0
   */
  public function activation_check() {
		$theme_info = wp_get_theme();
		$storefront_theme = array(
			'storefront',
			'Storefront',
		);

		if ( ! in_array( $theme_info->Template, $storefront_theme ) ) {
			$store_link = esc_url('https://wordpress.org/themes/storefront/');
			$install_theme = esc_url('/wp-admin/theme-install.php?search=storefront');
			$plugins_page = esc_url('/wp-admin/plugins.php');

			deactivate_plugins( plugin_basename( SVG_PLUGIN_PATH ) ); // Deactivate the plugin.
			wp_die( sprintf( __('Sorry, you can\'t activate this plugin unless you have installed and active the free <a href="%s" target="_blank" rel="noopener noreferrer">Storefront theme</a> or a Storefront child theme.<br><br>You can browse and <a href="%s">install the Storefront theme</a> right now.<br><br><br>Or you can just return to <a href="%s">the plugins page</a>.', 'storefront-visual-guide'),
			$store_link, $install_theme, $plugins_page ), 'WordPress > Error: Storefront needed' );
		}

	}

  /**
   * Theme change checks for Storefront Theme and prints admin notice when plugin is disabled due to theme change.
   *
   * @since   1.1.0
   */
  public function theme_change_check() {
    $theme_info = wp_get_theme();
    $storefront_theme = array(
      'storefront',
    );

    if ( ! in_array( $theme_info->Template, $storefront_theme ) ) {
      if ( is_plugin_active( plugin_basename( SVG_PLUGIN_PATH ) ) ) {
        deactivate_plugins( plugin_basename( SVG_PLUGIN_PATH ) );
        $plugins_page = esc_url('/wp-admin/plugins.php');
        ?>
          <div class="notice notice-error is-dismissible" >
              <p><?php printf( __( '<a href="%s">Storefront Visual Hook Guide</a> plugin has been disabled. You need to activate the Storefront theme or a Storefront child theme for this plugin to work.', 'storefront-visual-guide' ), $plugins_page ); ?></p>
          </div>
        <?php
      }
    }
  }
}

return new Storefront_Visual_Guide_Init();
