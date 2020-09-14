<?php

namespace AutomateWoo\RuleQuickFilters\Queries;

use AutomateWoo\RuleQuickFilters\Clauses\ClauseInterface;
use UnexpectedValueException;
use WC_Order;

defined( 'ABSPATH' ) || exit;

/**
 * Class OrderQuery.
 *
 * @since   5.0.0
 * @package AutomateWoo\RuleQuickFilters\Queries
 */
class OrderQuery extends AbstractPostTypeQuery {

	/**
	 * Get data type for quick filtering.
	 *
	 * @return string
	 */
	public function get_data_type() {
		return 'order';
	}

	/**
	 * Get the WP post type for the data type.
	 *
	 * @return string
	 */
	protected function get_post_type() {
		return 'shop_order';
	}

	/**
	 * Get filter result object from ID.
	 *
	 * @param int $id
	 *
	 * @return WC_Order|false
	 */
	public function get_result_object( $id ) {
		return wc_get_order( $id );
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
			case 'billing_country':
			case 'billing_email':
			case 'billing_phone':
			case 'billing_postcode':
			case 'billing_state':
			case 'created_via':
			case 'payment_method':
			case 'shipping_country':
				$this->add_basic_post_meta_query_arg( $query_args, "_{$property}", $clause );
				break;
			case 'order_total':
			case 'customer_user':
				$this->add_numeric_post_meta_query_arg( $query_args, "_{$property}", $clause );
				break;
			case 'date_paid':
				$this->add_datetime_post_meta_query_arg( $query_args, "_{$property}", $clause, true );
				break;
			case 'date_created':
				$this->add_post_date_query_arg( $query_args, $clause );
				break;
			case 'status':
				$this->add_post_status_query_arg( $query_args, $clause, array_keys( wc_get_order_statuses() ) );
				break;
			case 'customer_note':
				$this->add_post_column_string_query_arg( 'post_excerpt', $clause );
				break;
			default:
				parent::map_clause_to_wp_query_arg( $clause, $query_args );
		}
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
		$args['post_status'] = array_keys( wc_get_order_statuses() );

		return $args;
	}

}
