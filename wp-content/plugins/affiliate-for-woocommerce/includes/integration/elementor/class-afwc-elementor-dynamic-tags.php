<?php
/**
 * Class for registering the dynamic tags.
 *
 * @package     affiliate-for-woocommerce/includes/integration/elementor/
 * @since       5.2.0
 * @version     1.0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Elementor_Dynamic_Tags' ) ) {

	/**
	 * Register the dynamic tags and groups.
	 */
	class AFWC_Elementor_Dynamic_Tags {

		/**
		 * Variable to hold instance of AFWC_Elementor_Dynamic_Tags.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor.
		 */
		private function __construct() {
			// Register the dynamic tag group for affiliate registration form.
			add_action( 'elementor/dynamic_tags/register', array( $this, 'register_afwc_dynamic_tag_group' ) );
			// Register the dynamic tags.
			add_action( 'elementor/dynamic_tags/register', array( $this, 'register_afwc_dynamic_tag' ) );
		}

		/**
		 * Get single instance of AFWC_Elementor_Dynamic_Tags.
		 *
		 * @return AFWC_Elementor_Dynamic_Tags Singleton object of AFWC_Elementor_Dynamic_Tags.
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register New Dynamic Tag Group.
		 *
		 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
		 * @return void
		 */
		public function register_afwc_dynamic_tag_group( $dynamic_tags_manager = null ) {

			if ( ! is_object( $dynamic_tags_manager ) || ! is_callable( array( $dynamic_tags_manager, 'register_group' ) ) ) {
				return;
			}

			$dynamic_tags_manager->register_group(
				'afwc_registration_form',
				array(
					'title' => esc_html_x( 'Affiliate Registration Form', 'Elementor dynamic group title for Affiliate registration form ids', 'affiliate-for-woocommerce' ),
				)
			);
		}

		/**
		 * Register Dynamic Tag.
		 *
		 * Include dynamic tag file and register tag class.
		 *
		 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
		 * @return void.
		 */
		public function register_afwc_dynamic_tag( $dynamic_tags_manager = null ) {

			if ( ! is_object( $dynamic_tags_manager ) || ! is_callable( array( $dynamic_tags_manager, 'register' ) ) ) {
				return;
			}

			$fields = is_callable( array( 'AFWC_Registration_Submissions', 'get_default_fields' ) ) ? AFWC_Registration_Submissions::get_default_fields() : array();

			if ( empty( $fields ) ) {
				return;
			}

			foreach ( $fields as $field_key => $field ) {
				$field_slug = str_replace( '_', '-', $field_key );

				$loader = AFWC_PLUGIN_DIRPATH . '/includes/integration/elementor/dynamic_tags/class-elementor-dynamic-tag-' . $field_slug . '-field-id.php';

				if ( file_exists( $loader ) ) {
					include_once $loader;

					$class_name = 'Elementor_Dynamic_Tag_AFWC_' . ucwords( str_replace( 'afwc_', '', $field_key ), '_' ) . '_Field_ID';

					if ( class_exists( $class_name ) && is_callable( array( $class_name, 'get_instance' ) ) ) {

						$obj = call_user_func( array( $class_name, 'get_instance' ) );

						if ( is_object( $obj ) ) {
							$dynamic_tags_manager->register( $obj );
						}
					}
				}
			}
		}
	}
}
AFWC_Elementor_Dynamic_Tags::get_instance();
