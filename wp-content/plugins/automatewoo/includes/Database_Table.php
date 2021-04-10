<?php

namespace AutomateWoo;

/**
 * @class Database_Table
 * @since 2.8.2
 */
abstract class Database_Table {

	/** @var string */
	protected $name;

	/** @var string */
	public $primary_key;

	/** @var string (only for meta tables) */
	public $object_id_column;

	/** @var int */
	public $max_index_length = 191;

	/**
	 * Getter method for inaccessible class properties.
	 *
	 * @since 5.0.0
	 *
	 * @param string $key The name of the class property to retrieve.
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		switch ( $key ) {
			case 'name':
				if ( WP_DEBUG ) {
					// phpcs:disable WordPress.PHP.DevelopmentFunctions,WordPress.Security.EscapeOutput
					// This could still be used in add-ons, so make the message geared towards updating the add-on.
					trigger_error(
						sprintf(
							/* translators: %1$s is the final class name accessing an unavailable property */
							__( '%1$s::$name is no longer directly accessible. Ensure you have the latest version of AutomateWoo and all Add-ons.', 'automatewoo' ),
							static::class
						),
						E_USER_DEPRECATED
					);
					// phpcs:enable
				}
				return $this->get_name();

			default:
				return null;
		}
	}

	/**
	 * @return array
	 */
	abstract public function get_columns();

	/**
	 * Get SQL-escaped table name.
	 *
	 * @since 4.6.0
	 *
	 * @return string
	 */
	public function get_name() {
		return esc_sql( $this->name );
	}

	/**
	 * Get SQL-escaped object ID column.
	 *
	 * @since 4.6.0
	 *
	 * @return string
	 */
	public function get_object_id_column() {
		return esc_sql( $this->object_id_column );
	}

	/**
	 * @return string
	 * @since 2.9.9
	 */
	public function get_install_query() {
		return '';
	}


	/**
	 * Install the database table
	 */
	public function install() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $this->get_install_query() );
	}


	/**
	 * @return string
	 */
	public function get_collate() {
		global $wpdb;
		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		return $collate;
	}

}
