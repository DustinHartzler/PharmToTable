<?php

namespace AutomateWoo\Actions\Subscriptions;

defined( 'ABSPATH' ) || exit;

/**
 * Change a subscription's next payment date.
 *
 * @since 5.4.0
 */
class UpdateNextPaymentDate extends AbstractEditItem {

	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	public function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Update Next Payment Date', 'automatewoo' );
		$this->description = __( 'Change a subscription\'s next payment date.', 'automatewoo' );
	}

	/**
	 * Method to get the new next payment date for the subscription.
	 *
	 * @return string MySQL date/time string representation of the DateTime object in UTC timezone.
	 */
	protected function get_object_for_edit() {
		$new_next_payment_date = wcs_get_datetime_from(
			sprintf(
				'%1$s %2$s:00',
				$this->get_option( 'new_payment_date' ),
				implode( ':', $this->get_option( 'new_payment_time' ) )
			)
		);

		return wcs_get_datetime_utc_string( $new_next_payment_date );
	}

	/**
	 * Edit the item managed by this class on the subscription passed in the workflow's trigger
	 *
	 * @param string           $new_next_payment_date Next payment date string.
	 * @param \WC_Subscription $subscription          Instance of the subscription being edited by this action.
	 *
	 * @throws \Exception When there is an error.
	 *
	 * @return bool True if the subscription was edited, false if no change was made.
	 */
	public function edit_subscription( $new_next_payment_date, $subscription ) {
		$subscription->update_dates( array( 'next_payment' => $new_next_payment_date ) );

		return true;
	}

	/**
	 * Load the fields required for the action.
	 */
	public function load_fields() {
		$date_field = ( new \AutomateWoo\Fields\Date() )
			->set_required()
			->set_name( 'new_payment_date' )
			->set_title( __( 'New Payment Date', 'automatewoo' ) );

		$time_field = ( new \AutomateWoo\Fields\Time() )
			->set_required()
			->set_name( 'new_payment_time' )
			->set_title( __( 'New Payment Time', 'automatewoo' ) );

		$this->add_field( $date_field );
		$this->add_field( $time_field );
	}

	/**
	 * Get the note on the subscription to record the next payment date change.
	 *
	 * @param string $new_next_payment_date Next payment date. The return value of @see $this->get_object_for_edit().
	 */
	protected function get_note( $new_next_payment_date ) {
		return sprintf(
			/* translators: %1$s: workflow name, %2$s: new next payment date, %3$s: workflow ID */
			__( '%1$s workflow run: updated next payment date to %2$s.  (Workflow ID: %3$d)', 'automatewoo' ),
			$this->workflow->get_title(),
			$new_next_payment_date,
			$this->workflow->get_id()
		);
	}
}
