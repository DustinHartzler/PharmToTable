<?php
// phpcs:ignoreFile

namespace AutomateWoo;

/**
 * @class Database_Tables
 * @since 2.8.2
 */
class Database_Tables extends Registry {

	/** @var array */
	static $includes;

	/** @var Database_Table[] */
	static $loaded = [];


	/**
	 * Updates any tables as required
	 */
	static function install_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		foreach( self::get_all() as $table ) {
			$table->install();
		}
	}


	/**
	 * @return array
	 */
	static function load_includes() {
		$includes = [
			'carts'         => DatabaseTables\Carts::class,
			'customer-meta' => DatabaseTables\CustomerMeta::class,
			'customers'     => DatabaseTables\Customers::class,
			'events'        => DatabaseTables\Events::class,
			'guest-meta'    => DatabaseTables\GuestMeta::class,
			'guests'        => DatabaseTables\Guests::class,
			'log-meta'      => DatabaseTables\LogMeta::class,
			'logs'          => DatabaseTables\Logs::class,
			'queue'         => DatabaseTables\Queue::class,
			'queue-meta'    => DatabaseTables\QueueMeta::class,
			'unsubscribes'  => DatabaseTables\Unsubscribes::class,
		];

		return apply_filters( 'automatewoo/database_tables', $includes );
	}


	/**
	 * @return Database_Table[]
	 */
	static function get_all() {
		return parent::get_all();
	}

	/**
	 * Get a database table object.
	 *
	 * @param $table_id
	 *
	 * @return Database_Table
	 *
	 * @throws Exception When table failed to load.
	 */
	static function get( $table_id ) {
		$table = parent::get( $table_id );

		if ( $table instanceof Database_Table ) {
			return $table;
		}

		throw new Exception( sprintf( __( "Failed to load the '%s' database table.", 'automatewoo' ), $table_id ) );
	}


	/**
	 * @deprecated since 3.8
	 * @param $table_id
	 * @return Database_Table
	 */
	static function get_table( $table_id ) {
		wc_deprecated_function( __METHOD__, '3.8.0', 'get' );

		return self::get( $table_id );
	}

}
