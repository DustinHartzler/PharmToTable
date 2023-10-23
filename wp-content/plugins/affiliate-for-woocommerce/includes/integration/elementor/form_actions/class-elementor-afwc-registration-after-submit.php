<?php
/**
 * Affiliate Registration Form Action for Elementor
 *
 * @package     affiliate-for-woocommerce/includes/
 * @since       5.2.0
 * @version     1.0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Elementor_AFWC_Registration_After_Submit' ) ) {

	/**
	 * AFWC Registration action for Elementor form.
	 */
	class Elementor_AFWC_Registration_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {

		/**
		 * Get action name.
		 *
		 * @return string Return AFWC Registration action name
		 */
		public function get_name() {
			return 'afwc_registration';
		}

		/**
		 * Get action label.
		 *
		 * @return string
		 */
		public function get_label() {
			return esc_html_x( 'Affiliate Registration', 'Elementor form action name for Affiliate registration', 'affiliate-for-woocommerce' );
		}

		/**
		 * Register action controls.
		 *
		 * @param \Elementor\Widget_Base $widget The elementor widget.
		 */
		public function register_settings_section( $widget = null ) {}

		/**
		 * Run action.
		 *
		 * Runs the Affiliate Registration action after Elementor form submission.
		 *
		 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record The record.
		 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler The ajax handler.
		 *
		 * @return void.
		 */
		public function run( $record = null, $ajax_handler = null ) {

			// Get submitted form data.
			$raw_fields = is_callable( array( $record, 'get' ) ) ? $record->get( 'fields' ) : array();

			if ( empty( $raw_fields ) && is_callable( array( $ajax_handler, 'add_error_message' ) ) && is_callable( array( 'Ajax_Handler', 'get_default_message' ) ) ) {
				$ajax_handler->add_error_message( Ajax_Handler::get_default_message( Ajax_Handler::FIELD_REQUIRED ) );
				return;
			}

			if (
				isset( $raw_fields['afwc_confirm_password'] )
				&& ! empty( $raw_fields['afwc_password']['value'] )
				&& ( empty( $raw_fields['afwc_confirm_password']['value'] ) || ( $raw_fields['afwc_confirm_password']['value'] !== $raw_fields['afwc_password']['value'] ) )
				&& is_callable( array( $ajax_handler, 'add_error_message' ) )
			) {
				$ajax_handler->add_error_message( _x( 'Passwords do not match.', 'Password mismatch message', 'affiliate-for-woocommerce' ) );
				return;
			}

			// Normalize form data.
			$fields = array();

			foreach ( $raw_fields as $id => $field ) {
				$type = ! empty( $field['type'] ) ? $field['type'] : '';

				// Elementor ignored fields.
				if ( 'afwc_confirm_password' === $id ) {
					continue;
				}

				// Elementor ignored field types.
				if ( in_array( $type, array( 'acceptance', 'recaptcha', 'recaptcha_v3', 'honeypot' ), true ) ) {
					continue;
				}

				$fields[] = array(
					'key'   => $id,
					'value' => ! empty( $field['value'] ) ? $field['value'] : '',
					'type'  => ( 'upload' === $type ) ? 'file' : $type,
					'label' => ! empty( $field['title'] ) ? $field['title'] : '',
				);
			}

			$affiliate_registration = AFWC_Registration_Submissions::get_instance();
			$response               = is_callable( array( $affiliate_registration, 'register_user' ) ) ? $affiliate_registration->register_user( $fields ) : array();

			if ( ! empty( $response['status'] ) && $response['message'] ) {
				if ( 'success' === $response['status'] && is_callable( array( $ajax_handler, 'add_response_data' ) ) ) {
					$ajax_handler->add_response_data( 'afwc_registration', $response );
				} elseif ( is_callable( array( $ajax_handler, 'add_error_message' ) ) ) {
					$ajax_handler->add_error_message( $response['message'] );
				}
			}
		}

		/**
		 * On export.
		 *
		 * @param array $element The element.
		 */
		public function on_export( $element ) {
			return $element;
		}
	}

}
