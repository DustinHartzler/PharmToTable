<?php
/**
 * Class for registering dynamic tag for affiliate registration first name field id.
 *
 * @package     affiliate-for-woocommerce/includes/integration/elementor/dynamic_tags/
 * @since       5.2.0
 * @version     1.0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Elementor_Dynamic_Tag_AFWC_First_Name_Field_ID' ) ) {

	/**
	 * Elementor Dynamic tag class for First name field id.
	 */
	class Elementor_Dynamic_Tag_AFWC_First_Name_Field_ID extends \Elementor\Core\DynamicTags\Tag {

		/**
		 * Variable to hold the field id.
		 *
		 * @var $field_id
		 */
		private $field_id = 'afwc_first_name';

		/**
		 * Variable to hold instance of Elementor_Dynamic_Tag_AFWC_First_Name_Field_ID.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of Elementor_Dynamic_Tag_AFWC_First_Name_Field_ID.
		 *
		 * @return Elementor_Dynamic_Tag_AFWC_First_Name_Field_ID Singleton object of Elementor_Dynamic_Tag_AFWC_First_Name_Field_ID.
		 */
		public static function get_instance() {
				// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get dynamic tag name.
		 *
		 * @return string Dynamic tag name.
		 */
		public function get_name() {
			return 'afwc-registration-first-name-field-id';
		}

		/**
		 * Get dynamic tag title.
		 *
		 * @return string Dynamic tag title.
		 */
		public function get_title() {
			$afwc_fields = is_callable( array( 'AFWC_Registration_Submissions', 'get_default_fields' ) ) ? AFWC_Registration_Submissions::get_default_fields() : array();
			return ( ! empty( $afwc_fields ) && ! empty( $this->field_id ) && ! empty( $afwc_fields[ $this->field_id ] ) ) ? esc_html( $afwc_fields[ $this->field_id ] ) : '';
		}

		/**
		 * Get dynamic tag groups
		 *
		 * @return array Dynamic tag groups.
		 */
		public function get_group() {
			return array( 'afwc_registration_form' );
		}

		/**
		 * Get dynamic tag categories.
		 *
		 * @return array Dynamic tag categories.
		 */
		public function get_categories() {
			return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
		}

		/**
		 * Render tag output on the frontend.
		 *
		 * @return void
		 */
		public function render() {
			echo ! empty( $this->field_id ) ? sanitize_key( $this->field_id ) : '';
		}
	}

}
