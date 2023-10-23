<?php
/**
 * Main class for Elementor Form Submission actions.
 *
 * @package     affiliate-for-woocommerce/includes/integration/elementor/
 * @since       5.2.0
 * @version     1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AFWC_Elementor_Form_Actions' ) ) {

	/**
	 * AFWC_Elementor_Form_Actions class for registering the Elementor form submission action.
	 */
	class AFWC_Elementor_Form_Actions {

		/**
		 * Variable to hold instance of AFWC_Elementor_Form_Actions.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		private function __construct() {
			// Register the form actions.
			add_action( 'elementor_pro/forms/actions/register', array( $this, 'register_form_actions' ) );
			add_action( 'elementor_pro/forms/new_record', array( $this, 'response_message' ), 99, 2 );
			add_filter( 'elementor_pro/forms/render/item', array( $this, 'render_fields' ), 99, 3 );
			add_action( 'elementor_pro/forms/validation', array( $this, 'validation' ), 99, 2 );
		}

		/**
		 * Get single instance of AFWC_Elementor_Form_Actions.
		 *
		 * @return AFWC_Elementor_Form_Actions Singleton object of AFWC_Elementor_Form_Actions
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register the actions.
		 *
		 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar The Form_Actions_Registrar handler.
		 *
		 * @return void.
		 */
		public function register_form_actions( $form_actions_registrar = null ) {

			if ( is_object( $form_actions_registrar ) && is_callable( array( $form_actions_registrar, 'register' ) ) ) {
				require_once __DIR__ . '/form_actions/class-elementor-afwc-registration-after-submit.php';

				if ( class_exists( 'Elementor_AFWC_Registration_After_Submit' ) ) {
					$form_actions_registrar->register( new Elementor_AFWC_Registration_After_Submit() );
				}
			}
		}

		/**
		 * Response message for successfully submission.
		 *
		 * Runs the Affiliate Registration action after Elementor form submission.
		 *
		 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record The record.
		 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler The ajax handler.
		 *
		 * @return void.
		 */
		public function response_message( $record = null, $ajax_handler = null ) {

			if ( ! $ajax_handler instanceof ElementorPro\Modules\Forms\Classes\Ajax_Handler
				|| empty( $ajax_handler->data )
				|| empty( $ajax_handler->data['afwc_registration'] )
				|| empty( $ajax_handler->data['afwc_registration']['status'] )
				|| 'success' !== $ajax_handler->data['afwc_registration']['status']
				|| empty( $ajax_handler->data['afwc_registration']['message'] )
			) {
				return;
			}

			wp_send_json_success(
				array(
					'message' => $ajax_handler->data['afwc_registration']['message'],
					'data'    => $ajax_handler->data,
				)
			);
		}

		/**
		 * Render the affiliate hide and readonly fields.
		 *
		 * @param array                                    $item The form item.
		 * @param int                                      $i The form field item index.
		 * @param \ElementorPro\Modules\Forms\Widgets\Form $widget The form widget instance.
		 *
		 * @return array.
		 */
		public function render_fields( $item = array(), $i = 0, $widget = null ) {

			if ( ( is_callable( array( ElementorPro\Plugin::elementor()->editor, 'is_edit_mode' ) ) && ElementorPro\Plugin::elementor()->editor->is_edit_mode() ) || empty( $item['custom_id'] ) || ! $widget instanceof \ElementorPro\Modules\Forms\Widgets\Form ) {
				return $item;
			}

			$user = wp_get_current_user();

			if ( $user instanceof WP_User && is_callable( array( $user, 'exists' ) ) && $user->exists() ) {
				$affiliate_registration = AFWC_Registration_Submissions::get_instance();

				// Hide the hidden fields.
				if ( ! empty( $affiliate_registration->hide_fields ) && is_array( $affiliate_registration->hide_fields ) && in_array( $item['custom_id'], $affiliate_registration->hide_fields, true ) ) {

					// Remove the attributes.
					if ( is_callable( array( $widget, 'remove_render_attribute' ) ) ) {
						$widget->remove_render_attribute( 'input' . $i, 'required' );
						$widget->remove_render_attribute( 'input' . $i, 'aria-required' );
						$widget->remove_render_attribute( 'input' . $i, 'aria-invalid' );
						$widget->remove_render_attribute( 'input' . $i, 'type' );

						if ( ! empty( $item['field_type'] ) ) {
							$widget->remove_render_attribute( 'field-group' . $i, 'class', 'elementor-field-type-' . $item['field_type'] );
						}

						$widget->remove_render_attribute( 'field-group' . $i, 'class', 'elementor-field-required elementor-mark-required' );
					}

					$item['required']   = null;
					$item['field_type'] = 'hidden';

					if ( is_callable( array( $widget, 'set_render_attribute' ) ) ) {
						$widget->set_render_attribute( 'input' . $i, 'style', 'display:none !important;' );
					}

					if ( is_callable( array( $widget, 'add_render_attribute' ) ) ) {
						$widget->add_render_attribute( 'field-group' . $i, 'class', 'elementor-field-type-' . $item['field_type'] );
						$widget->add_render_attribute( 'input' . $i, 'type', $item['field_type'] );
					}
				}

				// Set readonly to the readonly fields.
				if ( ! empty( $affiliate_registration->readonly_fields ) && is_array( $affiliate_registration->readonly_fields ) && in_array( $item['custom_id'], $affiliate_registration->readonly_fields, true ) ) {
					if ( is_callable( array( $widget, 'set_render_attribute' ) ) ) {
						$widget->set_render_attribute( 'input' . $i, 'readonly' );
						$widget->set_render_attribute( 'input' . $i, 'value', ! empty( $user->user_email ) ? $user->user_email : '' );
					}
				}
			}

			return $item;
		}

		/**
		 * Skip validation for hidden fields for logged in user.
		 *
		 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $form The form.
		 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler The ajax handler.
		 *
		 * @return void.
		 */
		public function validation( $form = null, $ajax_handler = null ) {

			if ( ! is_user_logged_in() ) {
				return;
			}

			$affiliate_registration = AFWC_Registration_Submissions::get_instance();
			if ( empty( $affiliate_registration->hide_fields ) ) {
				return;
			}

			foreach ( $affiliate_registration->hide_fields as $field ) {
				if ( isset( $ajax_handler->errors[ $field ] ) ) {
					unset( $ajax_handler->errors[ $field ] );
				}
			}
		}
	}

}
AFWC_Elementor_Form_Actions::get_instance();
