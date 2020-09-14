<?php

namespace AutomateWoo\RuleQuickFilters\Queries;

use AutomateWoo\Exception;
use AutomateWoo\RuleQuickFilters\Clauses\ClauseInterface;
use UnexpectedValueException;
use WC_Subscription;

defined( 'ABSPATH' ) || exit;

/**
 * Class SubscriptionQuery.
 *
 * @since   5.0.0
 * @package AutomateWoo\RuleQuickFilters\Queries
 */
class SubscriptionQuery extends OrderQuery {

	/**
	 * Get data type for quick filtering.
	 *
	 * @return string
	 */
	public function get_data_type() {
		return 'subscription';
	}

	/**
	 * Get the WP post type for the data type.
	 *
	 * @return string
	 */
	protected function get_post_type() {
		return 'shop_subscription';
	}

	/**
	 * Get filter result object from ID.
	 *
	 * @param int $id
	 *
	 * @return WC_Subscription|false
	 */
	public function get_result_object( $id ) {
		return wcs_get_subscription( $id );
	}

	/**
	 * Get the default args to use with WP_Query.
	 *
	 * @param int $number
	 * @param int $offset
	 *
	 * @return array
	 */
	protected function get_default_wp_query_args( $number, $offset = 0 ) {
		$args                = parent::get_default_wp_query_args( $number, $offset );
		$args['post_status'] = array_keys( wcs_get_subscription_statuses() );

		return $args;
	}

	/**
	 * Map a quick filter clause to WP_Query arg.
	 *
	 * @param ClauseInterface $clause
	 * @param array           $query_args Array of WP_Query args.
	 *
	 * @throws UnexpectedValueException When there is an error mapping a query arg.
	 */
	protected function map_clause_to_wp_query_arg( $clause, &$query_args ) {
		$property = $clause->get_property();

		switch ( $property ) {
			case 'requires_manual_renewal':
				$this->add_basic_post_meta_query_arg( $query_args, '_' . $property, $clause );
				break;
			case 'status':
				$this->add_post_status_query_arg( $query_args, $clause, array_keys( wcs_get_subscription_statuses() ) );
				break;
			case 'end_date':
				$this->add_datetime_post_meta_query_arg( $query_args, '_schedule_end', $clause );
				break;
			case 'next_payment_date':
				$this->add_datetime_post_meta_query_arg( $query_args, '_schedule_next_payment', $clause );
				break;
			case 'trial_end_date':
				$this->add_datetime_post_meta_query_arg( $query_args, '_schedule_trial_end', $clause );
				break;
			default:
				parent::map_clause_to_wp_query_arg( $clause, $query_args );
		}
	}

}
