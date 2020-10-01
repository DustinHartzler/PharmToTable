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
 * Class TVD_Smart_Shortcodes
 */
final class TVD_Smart_Shortcodes {

	/**
	 * Database instance for Smart Site
	 *
	 * @var TVD_Smart_DB
	 */
	private $db;

	public static $smart_shortcodes
		= array(
			TVD_Smart_Site::GLOBAL_FIELDS_SHORTCODE     => 'tvd_tss_smart_fields',
			TVD_Smart_Site::GLOBAL_FIELDS_SHORTCODE_URL => 'tvd_tss_smart_url',

		);

	/**
	 * TVD_Smart_Shortcodes constructor.
	 */
	public function __construct() {
		$this->db = new TVD_Smart_DB();

		foreach ( static::$smart_shortcodes as $shortcode => $func ) {
			$function = array( $this, $func );
			add_shortcode( $shortcode, static function ( $attr ) use ( $function ) {
				$output = call_user_func_array( $function, func_get_args() );

				return TVD_Global_Shortcodes::maybe_link_wrap( $output, $attr );
			} );
		}
	}

	/**
	 * Execute smart fields shortcode
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function tvd_tss_smart_fields( $args ) {
		$data = '';
		if ( $args['id'] ) {
			$field = $this->db->get_fields( array(), $args['id'] );

			if ( ! empty( $field ) ) {
				$groups = $this->db->get_groups( $field['group_id'], false );
				$group  = array_pop( $groups );

				$field['group_name'] = $group['name'];
				$field_data          = maybe_unserialize( $field['data'] );
				$data                = TVD_Smart_DB::format_field_data( $field_data, $field, $args );
			}

		}

		return $data;
	}

	/**
	 * Execute smart url shortcode
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function tvd_tss_smart_url( $args ) {
		$data = '';
		if ( ! empty( $args['id'] ) ) {
			$field      = $this->db->get_fields( array(), $args['id'] );
			$field_data = maybe_unserialize( $field['data'] );
			if ( ! empty( $field_data ) ) {
				$data = $field_data['url'];
			}
		}

		return $data;
	}

}
