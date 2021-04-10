<?php

namespace AutomateWoo;

defined( 'ABSPATH' ) || exit;

/**
 * Class Event_Query.
 *
 * @deprecated in 5.2.0 use AW()->action_scheduler() instead.
 */
class Event_Query extends Query_Abstract {

	/**
	 * Set's the database table ID.
	 *
	 * @var string
	 */
	public $table_id = 'events';

	/**
	 * Set's the class to use for the return object.
	 *
	 * @var string
	 */
	public $model = 'AutomateWoo\Event';

	/**
	 * Query based on event hook prop.
	 *
	 * @since 4.8.0
	 *
	 * @param string|array $hook
	 * @param string       $compare Defaults to '=' or 'IN' if array
	 *
	 * @return $this
	 */
	public function where_hook( $hook, $compare = null ) {
		return $this->where( 'hook', $hook, $compare );
	}

	/**
	 * Query based on event args. Uses hash of args.
	 *
	 * Array comparison is not available because $args is always converted to a string (hashed).
	 *
	 * @since 4.8.0
	 *
	 * @param array  $args
	 * @param string $compare Defaults to '='.
	 *
	 * @return $this
	 */
	public function where_args( $args, $compare = null ) {
		return $this->where( 'args_hash', Events::get_event_args_hash( $args ), $compare );
	}

	/**
	 * Get results.
	 *
	 * @return Event[]
	 */
	public function get_results() {
		return parent::get_results();
	}

}
