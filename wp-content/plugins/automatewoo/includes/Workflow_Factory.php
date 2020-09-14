<?php

namespace AutomateWoo;

/**
 * @since 3.9
 */
class Workflow_Factory {

	/**
	 * @param int $id
	 * @return Workflow|false
	 */
	public static function get( $id ) {
		$id = (int) $id;
		if ( $id <= 0 ) {
			return false;
		}

		$id = Clean::id( $id );
		if ( ! $id ) {
			return false;
		}

		$workflow = new Workflow( $id );
		if ( ! $workflow->exists ) {
			return false;
		}

		return $workflow;
	}
}
