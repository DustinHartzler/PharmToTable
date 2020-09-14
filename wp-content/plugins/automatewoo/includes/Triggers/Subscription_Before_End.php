<?php
// phpcs:ignoreFile

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Subscription_Before_End
 */
class Trigger_Subscription_Before_End extends Trigger_Subscription_Before_Renewal {


	function load_admin_details() {
		$this->title       = __( 'Subscription Before End', 'automatewoo' );
		$this->description  = __( "This trigger runs once per day for any subscriptions that are due to expire/end on the workflow's target date. For example, if set to run 7 days before end, it would look for subscriptions that are due to end on the date exactly 7 days from now.", 'automatewoo' );
		$this->description .= ' ' . $this->get_description_text_workflow_not_immediate();

		$this->group       = Subscription_Workflow_Helper::get_group_name();
	}


	function load_fields() {

		$days_before = ( new Fields\Positive_Number() )
			->set_name( 'days_before' )
			->set_title( __( 'Days before end', 'automatewoo' ) )
			->set_required();

		$this->add_field( $days_before );
		$this->add_field( $this->get_field_time_of_day() );
		$this->add_field( Subscription_Workflow_Helper::get_products_field() );
	}


	/**
	 * @param Workflow $workflow
	 * @param int      $limit
	 * @param int      $offset
	 *
	 * @return array
	 */
	function get_background_tasks( $workflow, $limit, $offset = 0 ) {
		$tasks = [];
		$days_before = absint( $workflow->get_trigger_option( 'days_before' ) );

		// days before field must be set
		if ( ! $days_before ) {
			return [];
		}

		$date = new DateTime();
		$date->convert_to_site_time();
		$date->modify( "+$days_before days" );

		foreach ( $this->get_subscriptions_by_end_day( $date, $limit, $offset ) as $subscription_id ) {
			$tasks[] = [
				'workflow_id' => $workflow->get_id(),
				'workflow_data' => [
					'subscription_id' => $subscription_id
				]
			];
		}

		return $tasks;
	}


	/**
	 * @param DateTime $date Must be in site time!
	 * @param int      $limit
	 * @param int      $offset
	 *
	 * @return array
	 */
	function get_subscriptions_by_end_day( $date, $limit, $offset ) {
		$day_start = clone $date;
		$day_end = clone $date;
		$day_start->set_time_to_day_start();
		$day_end->set_time_to_day_end();

		$day_start->convert_to_utc_time();
		$day_end->convert_to_utc_time();

		$query = new \WP_Query([
			'post_type' => 'shop_subscription',
			'post_status' => [ 'wc-active', 'wc-pending-cancel' ],
			'fields' => 'ids',
			'posts_per_page' => $limit,
			'offset' => $offset,
			'no_found_rows' => true,
			'meta_query' => [
				[
					'key' => '_schedule_end',
					'compare' => '>=',
					'value' => $day_start->to_mysql_string(),
				],
				[
					'key' => '_schedule_end',
					'compare' => '<=',
					'value' => $day_end->to_mysql_string(),
				]
			]
		]);

		return $query->posts;
	}

	/**
	 * Validate before a queued workflow event.
	 *
	 * Ensures that the subscription is either active or pending cancellation.
	 *
	 * @param Workflow $workflow
	 *
	 * @return bool
	 */
	public function validate_before_queued_event( $workflow ) {
		$subscription = $workflow->data_layer()->get_subscription();

		if ( ! $subscription || ! $subscription->has_status( [ 'active', 'pending-cancel' ] ) ) {
			return false;
		}

		return true;
	}

}
