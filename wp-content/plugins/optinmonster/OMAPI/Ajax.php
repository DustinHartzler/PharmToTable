<?php
/**
 * Ajax class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax class.
 *
 * @since 1.0.0
 */
class OMAPI_Ajax {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Load non-WordPress style ajax requests.
		if ( isset( $_REQUEST['optin-monster-ajax-route'] ) && $_REQUEST['optin-monster-ajax-route'] ) {
			if ( isset( $_REQUEST['action'] ) ) {
				add_action( 'init', array( $this, 'ajax' ), 999 );
			}
		}
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {

		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
		$this->view     = 'ajax';
	}

	/**
	 * Callback to process external ajax requests.
	 *
	 * @since 1.0.0
	 */
	public function ajax() {

		switch ( $_REQUEST['action'] ) {
			case 'mailpoet':
				$this->base->mailpoet->handle_ajax_call();
				break;
			case 'om_plugin_install':
				add_action( 'wp_ajax_om_plugin_install', array( $this, 'install_or_activate' ) );
				add_action( 'wp_ajax_nopriv_om_plugin_install', array( $this, 'install_or_activate' ) );
				break;
			default:
				break;
		}
	}

	/**
	 * Installs and activates a plugin for a given url
	 *
	 * @since 1.9.10
	 *
	 * @param string $plugin_url The Plugin URL
	 * @return void
	 */
	public function install_plugin( $plugin_url ) {
		// Todo: Add Nonce verification
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( esc_html__( 'Sorry, not allowed!', 'optin-monster-api' ) );
		}

		$creds = request_filesystem_credentials( admin_url( 'admin.php' ), '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( esc_html__( 'Sorry, not allowed!', 'optin-monster-api' ) );
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( new OMAPI_InstallSkin() );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) ) {
			wp_send_json_error();
		}

		$installer->install( esc_url_raw( $plugin_url ) ); // phpcs:ignore

		if ( ! $installer->plugin_info() ) {
			wp_send_json_error();
		}

		$plugin_basename = $installer->plugin_info();

		// Activate the plugin silently.
		$activated = activate_plugin( $plugin_basename );

		if ( ! is_wp_error( $activated ) ) {
			wp_send_json_success(
				array(
					'msg'          => esc_html__( 'Plugin installed & activated.', 'optin-monster-api' ),
					'is_activated' => true,
					'basename'     => $plugin_basename,
				)
			);
		}

		wp_send_json_success(
			array(
				'msg'          => esc_html__( 'Plugin installed.', 'optin-monster-api' ),
				'is_activated' => false,
				'basename'     => $plugin_basename,
			)
		);
	}

	/**
	 * Installs or Activates a plugin with a given plugin name
	 *
	 * @param string $plugin_name
	 * @return void
	 */
	public function install_or_activate() {
		$plugin = $_POST['plugin'];
		$action = $_POST['installAction'];
		$url    = $_POST['url'];

		if ( 'install' === $action ) {
			$this->install_plugin( $url );
		} else {
			$this->activate_plugin( $plugin );
		}
	}

	/**
	 * Activates a plugin with a given plugin name
	 *
	 * @param string $plugin_name
	 * @return void
	 */
	public function activate_plugin( $plugin_name ) {

		// Check for permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( esc_html__( 'Sorry, not allowed!', 'optin-monster-api' ) );
		}

		$activate = activate_plugins( sanitize_text_field( $plugin_name ) );

		if ( ! is_wp_error( $activate ) ) {
			wp_send_json_success( esc_html__( 'Plugin activated.', 'optin-monster-api' ) );
		}
	}
}
