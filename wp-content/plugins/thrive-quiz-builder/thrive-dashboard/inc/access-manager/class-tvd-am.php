<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

final class TVD_AM {
	/**
	 * The singleton instance of the class.
	 *
	 * @var TVD_AM singleton instance.
	 */
	protected static $_instance = null;

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->includes();
	}

	public static function instance() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function includes() {
		add_action( 'current_screen', array( $this, 'conditional_hooks' ) );
	}

	public function display_page() {
		$all_roles    = $this->get_roles_url();
		$all_products = $this->get_products_capabilities( $all_roles );
		include( 'includes/templates/access-manager.php' );
	}

	/**
	 * Inserting the roles' capability for the Dash
	 *
	 * @param $all_roles
	 *
	 * @return stdClass
	 */
	private function get_dashboard_capability( $all_roles ) {
		$dashboard       = new stdClass();
		$dashboard->name = 'Thrive Dashboard Settings';
		$dashboard->tag  = 'td';
		$dashboard->logo = TVE_DASH_IMAGES_URL . '/dash-logo-icon-small.png';
		foreach ( $all_roles as $role ) {
			$wp_role                      = get_role( $role['tag'] );
			$has_roles                    = array();
			$has_roles['can_use']         = $wp_role ? $wp_role->has_cap( TVE_DASH_CAPABILITY ) : false;
			$has_roles['prod_capability'] = TVE_DASH_CAPABILITY;
			$has_roles['role']            = $role['tag'];
			$dashboard->roles[]           = $has_roles;
		}

		return $dashboard;
	}

	/**
	 * Getting site's roles and the url to their users
	 *
	 * @return array
	 */
	private function get_roles_url() {
		global $wp_roles;
		$all_roles = array();

		foreach ( $wp_roles->roles as $role_tag => $role ) {
			if ( isset( $role['capabilities']['edit_posts'] ) ) {
				$role['url'] = add_query_arg( 'role', $role['name'], admin_url( 'users.php' ) );
				$role['tag'] = $role_tag;
				unset( $role['capabilities'] );
				$all_roles[] = $role;
			}
		}

		return $all_roles;
	}

	/**
	 * Getting plugin capabilities based on current roles
	 *
	 * @param $all_roles
	 *
	 * @return array
	 */
	private function get_products_capabilities( $all_roles ) {
		$all_products   = array();
		$all_products[] = $this->get_dashboard_capability( $all_roles );
		foreach ( tve_dash_get_products( false ) as $product ) {
			//Skipping the old themes from displaying in access manager
			if ( $product->getType() === 'theme' && $product->getTag() !== 'thrive-theme' ) {
				continue;
			}
			$processed_product       = new stdClass();
			$processed_product->name = $product->getTitle();
			$processed_product->tag  = $product->getTag();
			$processed_product->logo = $product->getLogo();
			foreach ( $all_roles as $role ) {
				$wp_role                      = get_role( $role['tag'] );
				$has_roles                    = array();
				$has_roles['can_use']         = $wp_role ? $wp_role->has_cap( $product->get_cap() ) : false;
				$has_roles['prod_capability'] = $product->get_cap();
				$has_roles['role']            = $role['tag'];
				$processed_product->roles[]   = $has_roles;
			}
			$all_products[] = $processed_product;
		}

		return $all_products;
	}

	/**
	 * Hook based on the current screen
	 */
	public function conditional_hooks() {
		if ( ! $screen = get_current_screen() ) {
			return;
		}

		if ( $screen->id === 'admin_page_tve_dash_access_manager' ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'tve_dash_access_manager_include_scripts' ) );
		}
	}

	public function tve_dash_access_manager_include_scripts() {
		tve_dash_enqueue();
		include TVE_DASH_PATH . '/inc/access-manager/includes/assets/css/am-icons.svg';
		tve_dash_enqueue_style( 'tve-dash-access-manager-css', TVE_DASH_URL . '/inc/access-manager/includes/assets/css/style.css' );
		tve_dash_enqueue_script( 'tve-dash-access-manager-js', TVE_DASH_URL . '/inc/access-manager/includes/assets/dist/admin.min.js', array(
			'tve-dash-main-js',
			'jquery',
			'backbone',
		), false, true );
	}
}

function TVD_AM() {
	TVD_AM::instance();
}

TVD_AM();
