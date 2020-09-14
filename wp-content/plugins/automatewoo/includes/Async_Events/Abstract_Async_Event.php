<?php

namespace AutomateWoo\Async_Events;

/**
 * Class Abstract_Async_Event
 *
 * @since 4.8.0
 * @package AutomateWoo
 */
abstract class Abstract_Async_Event {

	/**
	 * The unique name/ID of the event.
	 *
	 * @var string
	 */
	protected $event_name;

	/**
	 * Set any events that this event is dependant on.
	 *
	 * @var array
	 */
	protected $event_dependencies;

	/**
	 * Init the event.
	 */
	abstract public function init();

	/**
	 * Get the event name.
	 *
	 * @return string
	 */
	public function get_event_name() {
		return $this->event_name;
	}

	/**
	 * Set the event name.
	 *
	 * @param string $event_name
	 */
	public function set_event_name( $event_name ) {
		$this->event_name = $event_name;
	}

	/**
	 * Get the events this event is dependant on.
	 *
	 * @return array
	 */
	public function get_event_dependencies() {
		return (array) $this->event_dependencies;
	}

	/**
	 * Set the events this event is dependant on.
	 *
	 * @param array|string $event_dependencies
	 */
	public function set_event_dependencies( $event_dependencies ) {
		$this->event_dependencies = $event_dependencies;
	}

}

