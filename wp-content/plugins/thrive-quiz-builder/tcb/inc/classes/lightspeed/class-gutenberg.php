<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\Lightspeed;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

/**
 * Class Gutenberg
 *
 * @package TCB\Lightspeed
 */
class Gutenberg {
	const  DISABLE_GUTENBERG_LP = '_tve_disable_gutenberg_lp';

	const  DISABLE_GUTENBERG = '_tve_disable_gutenberg';

	const HAS_GUTENBERG = '_tve_js_modules_gutenberg';

	public static function get_gutenberg_assets( $module = '', $key = '' ) {
		$data = [
			'gutenberg' => [
				'identifier' => '[class^="wp-block"]',
			],

		];

		if ( ! empty( $key ) ) {
			$data = array_map( static function ( $item ) use ( $key ) {
				return empty( $item[ $key ] ) ? [] : $item[ $key ];
			}, $data );
		}

		return empty( $module ) ? $data : $data[ $module ];
	}

	/**
	 * Checks if gutenberg scripts are disabled on a certain page
	 *
	 * @param false $is_lp
	 *
	 * @return bool
	 */
	public static function is_gutenberg_disabled( $is_lp = false ) {
		return ! empty( get_option( $is_lp ? static::DISABLE_GUTENBERG_LP : static::DISABLE_GUTENBERG, 0 ) );
	}

	public static function needs_gutenberg_assets() {
		$id                 = get_the_ID();
		$is_lp              = tve_post_is_landing_page( $id );
		$gutenberg_disabled = static::is_gutenberg_disabled( $is_lp );
		$gutenberg_key      = $is_lp ? static::DISABLE_GUTENBERG_LP : static::DISABLE_GUTENBERG;
		$has_gutenberg      = get_post_meta( $id, $gutenberg_key, true );

		return
			( empty( get_post_meta( $id, 'tcb2_ready', true ) ) ||
			  ! isset( $has_gutenberg ) ||
			  ! empty( $has_gutenberg ) ||
			  ! $gutenberg_disabled ||
			  ! empty( $_GET['force-all-js'] ) ||
			  is_editor_page_raw() || /* never optimize editor JS */
			  ! empty( get_post_meta( $id, static::HAS_GUTENBERG, true ) ) ); /* make sure the meta is set */
	}

	/**
	 * f we have gutenberg added on this page we save the optimize modules
	 *
	 * @param $post_id
	 * @param $post_content
	 */
	public static function update_post( $post_id, $post ) {
		/* We need this only if we are editing with WP */
		if ( ! empty( $_POST['action'] ) && $_POST['action'] === 'tcb_editor_ajax' ) {
			return;
		}

		$post_content = $post->post_content;

		$data = $post_content && strpos( $post_content, 'wp-block' ) !== false ? [ 'gutenberg' ] : [];

		update_post_meta( $post_id, static::HAS_GUTENBERG, $data );
	}
}
