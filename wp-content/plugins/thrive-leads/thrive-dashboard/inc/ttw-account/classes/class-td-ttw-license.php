<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 *
 * @property int     status
 * @property string  name
 * @property string  state
 * @property string  expiration
 * @property string  refund_date
 * @property string  tag
 * @property boolean can_update
 *
 * Representation of a single user license
 * Class TD_TTW_License
 */
class TD_TTW_License {

	use TD_Magic_Methods;

	const EXPIRED_STATUS = 2;

	const REFUNDED_STATUS = 3;

	const INVALID_STATUS = 0;

	const MEMBERSHIP_TAG = 'all';

	private $_expected_fields = [
		'status',
		'name',
		'state',
		'tag',
		'expiration',
		'refund_date',
		'can_update',
	];

	public function __construct( $data ) {

		foreach ( $this->_expected_fields as $field ) {
			if ( isset( $data[ $field ] ) ) {
				$this->_data[ $field ] = $data[ $field ];
			}
		}
	}

	/**
	 * Check if the license is active
	 *
	 * @return bool
	 */
	public function is_active() {

		return in_array(
			(int) $this->status,
			array(
				1, // active
				9, // pending cancellation
			)
		);
	}

	/**
	 * Check if the license is expired
	 *
	 * @return bool
	 */
	public function is_expired() {

		return (int) $this->status === self::EXPIRED_STATUS;
	}

	/**
	 * Check if the license is invalid
	 *
	 * @return bool
	 */
	public function is_invalid() {

		return (int) $this->status === self::INVALID_STATUS;
	}

	/**
	 * Check if the license is refunded
	 *
	 * @return bool
	 */
	public function is_refunded() {

		return (int) $this->status === self::REFUNDED_STATUS;
	}

	/**
	 * @return string
	 */
	public function get_name() {

		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_state() {

		return $this->state;
	}

	/**
	 * @return string
	 */
	public function get_expiration() {

		return $this->expiration;
	}

	/**
	 * @return string
	 */
	public function get_refunded_date() {

		return $this->refund_date;
	}

	/**
	 * Check if the user has access to updates on this license
	 *
	 * @return bool
	 */
	public function can_update() {

		return true === $this->can_update;
	}

	/**
	 * Check if the current license is a membership
	 *
	 * @return bool
	 */
	public function is_membership() {

		return $this->tag === self::MEMBERSHIP_TAG;
	}
}
