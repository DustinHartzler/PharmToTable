<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting;

use TVE\Reporting\Traits\Singleton;
use wpdb;

class Logs {

	use Singleton;

	const TABLE_NAME = 'thrive_reporting_logs';

	/** @var wpdb */
	protected $wpdb;

	/**
	 * @var string
	 */
	private $select;
	/**
	 * @var string
	 */
	private $where = '';
	/**
	 * @var string
	 */
	private $group_by = '';

	/**
	 * @var string
	 */
	private $order_by = '';

	/**
	 * @var string
	 */
	private $limit = '';
	/**
	 * @var string[]
	 */
	private $args = [];

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;

		$this->table = $wpdb->prefix . static::TABLE_NAME;
	}

	/**
	 * @param Event|mixed $event
	 *
	 * @return bool|int|\mysqli_result|resource|null
	 */
	public function insert( $event ) {
		$log_data = [
			'event_type' => $event::key(),
		];

		foreach ( array_keys( $event::get_registered_fields() ) as $field_key ) {
			$log_data[ $field_key ] = $event->get_field_value( $field_key );
		}

		$log_data['created'] = gmdate( 'Y-m-d H:i:s' );

		return $this->wpdb->insert( $this->table, $log_data );
	}

	/**
	 * @param $event_type
	 * @param $field
	 *
	 * @return array|object|\stdClass[]|null
	 */
	public function get_fields( $event_type, $field ) {
		$this->args = [];

		$this->where  = "event_type='%s'";
		$this->args[] = $event_type;

		//exp:   'SELECT DISTINCT item_id FROM wp_thrive_reporting_logs WHERE event_type = "tqb_quiz_completed"';
		$query = sprintf(
			"SELECT DISTINCT %s as 'value' FROM %s WHERE %s",
			$field,
			$this->table,
			$this->wpdb->prepare( $this->where, $this->args )
		);

		/* @codingStandardsIgnoreLine */
		return $this->wpdb->get_results( $query, ARRAY_A );
	}

	/**
	 * Remove logs
	 *
	 * @param $field_key
	 * @param $field_value
	 * @param $format
	 *
	 * @return bool|int|\mysqli_result|resource|null
	 */
	public function remove_by( $field_key, $field_value, $format = '%d' ) {
		return $this->wpdb->delete( $this->table, [ $field_key => $field_value ], [ $format ] );
	}

	public function set_query( array $args = [] ): Logs {
		if ( empty( $args['fields'] ) ) {
			$this->select = '*';
		} elseif ( is_string( $args['fields'] ) ) {
			$this->select = $args['fields'];
		} elseif ( is_array( $args['fields'] ) ) {
			$this->select = implode( ', ', $args['fields'] );
		}

		$this->args = [];

		if ( isset( $args['event_type'] ) ) {
			$this->where = "event_type='%s'";

			$this->args[] = $args['event_type'];
		} else {
			$this->where = '1';
		}

		if ( ! empty( $args['filters'] ) && is_array( $args['filters'] ) ) {
			foreach ( $args['filters'] as $key => $values ) {
				if ( empty( $values ) ) {
					continue;
				}

				$this->set_filter( $key, $values );
			}
		}

		if ( ! empty( $args['group_by'] ) ) {
			$group_by = is_string( $args['group_by'] ) ? $args['group_by'] : implode( ', ', $args['group_by'] );

			$this->group_by = " GROUP BY $group_by";

			$this->select .= ', COUNT(id) AS count';
		}

		if ( ! empty( $args['page'] ) && ! empty( $args['items_per_page'] ) ) {
			$this->limit = ' LIMIT %s, %s';

			$this->args[] = ( (int) $args['page'] - 1 ) * (int) $args['items_per_page'];
			$this->args[] = (int) $args['page'] * (int) $args['items_per_page'];
		}

		if ( ! empty( $args['order_by'] ) && $args['order_by'] === 'count' ) {
			$this->order_by = ' ORDER BY count DESC';

			if ( ! empty( $args['limit'] ) ) {
				$this->limit  = ' LIMIT %d';
				$this->args[] = (int) $args['limit'];
			}
		}

		return $this;
	}

	public function set_filter( $key, $values ) {
		switch ( $key ) {
			case 'created':
				if ( ! empty( $values['from'] ) ) {
					$this->where .= " AND created >= '%s'";

					$this->args[] = $values['from'];
				}

				if ( ! empty( $values['to'] ) ) {
					$this->where .= " AND created <= '%s'";

					$this->args[] = $values['to'];
				}
				break;
			case 'user_id':
			case 'post_id':
			case 'item_id':
			default:
				if ( is_array( $values ) ) {
					$this->where .= sprintf( ' AND %s IN ( %s )', $key, implode( ', ', $values ) );
				} else {
					$this->where .= " AND $key='%s'";

					$this->args[] = $values;
				}
				break;
		}
	}

	public function get() {
		/* @codingStandardsIgnoreLine */
		$query = "SELECT $this->select FROM $this->table " . $this->wpdb->prepare( "WHERE $this->where $this->group_by $this->order_by $this->limit", $this->args );

		/* @codingStandardsIgnoreLine */
		return $this->wpdb->get_results( $query, ARRAY_A );
	}

	public function num_rows(): int {
		return $this->wpdb->num_rows;
	}
}
