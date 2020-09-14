<?php

namespace AutomateWoo\Async_Events;

use AutomateWoo\Customer_Factory;
use AutomateWoo\Language;
use AutomateWoo\Events;

defined( 'ABSPATH' ) || exit;

/**
 * Class MC4WP_Form_Success
 *
 * @since 4.8.0
 * @package AutomateWoo
 */
class MC4WP_Form_Success extends Abstract_Async_Event {

	/**
	 * Init the event.
	 */
	public function init() {
		add_action( 'mc4wp_form_success', [ $this, 'schedule_event' ] );
	}

	/**
	 * Schedule event.
	 *
	 * @param \MC4WP_Form $form
	 */
	public function schedule_event( $form ) {
		$form_data = $form->get_data();

		if ( empty( $form_data['EMAIL'] ) ) {
			return;
		}

		$customer = Customer_Factory::get_by_email( $form_data['EMAIL'] );

		if ( ! $customer ) {
			return;
		}

		// ensure language is set
		if ( Language::is_multilingual() ) {
			$customer->update_language( Language::get_current() );
		}

		Events::schedule_async_event( 'automatewoo/mc4wp_form_success_async', [ $form->ID, $customer->get_id() ] );
	}

}
