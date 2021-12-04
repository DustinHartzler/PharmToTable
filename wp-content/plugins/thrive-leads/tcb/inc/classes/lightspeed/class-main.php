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
 * Class Main
 *
 * @package TCB\Lightspeed
 */
class Main {

	const LIGHTSPEED_VERSION = 1;

	const ENABLE_LIGHTSPEED_OPTION = '_tve_enable_lightspeed';

	const OPTIMIZATION_VERSION_META = '_tve_lightspeed_version';

	const ASSETS_TO_PRELOAD = '_tve_assets_to_preload';

	const OPTIMIZE_FLAG = 'tcb-lightspeed-optimize';

	public static function init() {
		static::includes();

		Hooks::add_actions();

		Compat::init();
	}

	public static function includes() {
		require_once __DIR__ . '/class-dashboard.php';
		require_once __DIR__ . '/class-rest-api.php';
		require_once __DIR__ . '/class-compat.php';
		require_once __DIR__ . '/class-hooks.php';
		require_once __DIR__ . '/class-fonts.php';
		require_once __DIR__ . '/class-css.php';
		require_once __DIR__ . '/class-js.php';
		require_once __DIR__ . '/class-js-module.php';
	}

	public static function is_enabled() {
		return isset( $_GET['force-lightspeed'] ) || ! empty( get_option( static::ENABLE_LIGHTSPEED_OPTION, 0 ) );
	}

	public static function is_optimizing() {
		return isset( $_GET[ static::OPTIMIZE_FLAG ] );
	}

	/**
	 * Check if we can and should optimize assets for the current post
	 *
	 * @param int  $post_id
	 * @param bool $editor_check
	 *
	 * @return bool
	 */
	public static function has_optimized_assets( $post_id, $editor_check = true, $check_if_enabled = true ) {

		$optimized_version = (int) get_post_meta( $post_id, static::OPTIMIZATION_VERSION_META, true );

		$is_optimized = ! empty( $optimized_version );

		if ( $check_if_enabled ) {
			$is_optimized = $is_optimized && static::is_enabled();
		}

		if ( $editor_check ) {
			/* we don't optimize inside the editor, because we load everything there */
			$is_optimized = $is_optimized && ! is_editor_page_raw( true );
		}

		/**
		 * Decide if we're going to optimize the css for the current post
		 *
		 * @param $is_optimized         bool boolean value on what should we do for the current post
		 * @param $post_id              int current post id
		 *
		 * @return bool
		 */
		return apply_filters( 'tcb_lightspeed_has_optimized_assets', $is_optimized, $post_id );
	}

	/**
	 * Check to see if the current post needs optimization or not
	 *
	 * @param $post_id
	 *
	 * @return mixed|void
	 */
	public static function requires_architect_assets( $post_id ) {


		$is_lp    = tve_post_is_landing_page( $post_id );
		$meta_key = $is_lp ? "tve_custom_css_$is_lp" : 'tve_custom_css';

		$has_architect_css = ! empty( get_post_meta( $post_id, $meta_key, true ) );

		$is_editor = is_editor_page_raw( true );

		/**
		 * Filter if the current post requires optimization or not. By default we check to see if the user has architect styles saved
		 *
		 * @param boolean $requires
		 * @param int     $post_id
		 *
		 * @return boolean
		 */
		return apply_filters( 'tcb_lightspeed_requires_architect_assets', $has_architect_css || $is_editor, $post_id );
	}

	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public static function has_architect_content( $post_id ) {
		return ! empty( tve_get_post_meta( $post_id, 'tve_updated_post' ) );
	}

	/**
	 * Return upload dir path/url depending on key
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public static function upload_dir( $key = 'url' ) {
		$upload_dir = wp_upload_dir();

		$styles_dir = $upload_dir[ $key ] . '/thrive/';

		if ( ! is_dir( $styles_dir ) ) {
			wp_mkdir_p( $styles_dir );
		}

		return preg_replace( '/https?:/', '', $styles_dir );
	}

	/**
	 * @param $post_id
	 * @param $assets_to_preload
	 */
	public static function save_assets_to_preload( $post_id, $assets_to_preload ) {
		update_post_meta( $post_id, static::ASSETS_TO_PRELOAD, $assets_to_preload );
	}

	/**
	 * @param $id
	 */
	public static function preload_assets( $id ) {
		if ( is_editor_page_raw( true ) ) {
			return;
		}

		$assets_to_preload = get_post_meta( $id, static::ASSETS_TO_PRELOAD, true );

		if ( ! empty( $assets_to_preload ) ) {
			foreach ( $assets_to_preload as $asset ) {
				switch ( $asset['type'] ) {
					case 'image':
						echo static::get_preload_link( $asset['url'], 'image' );
						break;
					case 'dynamic':
						if ( $asset['url'] === 'featured' && has_post_thumbnail() ) {
							echo static::get_preload_link( \TCB_Post_List_Shortcodes::shortcode_function_content( 'the_post_thumbnail_url' ), 'image' );
						}
						break;
					default:
						break;
				}
			}
		}

		do_action( 'tve_lightspeed_preload_assets', $id );
	}

	/**
	 * @param $url
	 * @param $type
	 *
	 * @return string
	 */
	public static function get_preload_link( $url, $type ) {
		return '<link rel="preload" as="' . $type . '" href="' . $url . '">';
	}

	/**
	 * Return all posts that have architect content saved
	 *
	 * @return array
	 */
	public static function get_architect_posts_for_optimization() {

		/**
		 * Filter posts we don't want to optimize
		 *
		 * @param array $post_types
		 *
		 * @return array
		 */
		$excluded_post_types = apply_filters( 'tcb_lightspeed_excluded_post_types', [
			\TVD\Login_Editor\Post_Type::NAME,
			'product_variation',
			//TODO: this is just temporary
			'tva_module',
			'tva_lesson',
			'tvo_capture',
			'tvo_display',
			'tcb_content_template',
		] );

		$all_post_types = array_filter( array_values( get_post_types() ), static function ( $post_type ) use ( $excluded_post_types ) {
			return ! in_array( $post_type, $excluded_post_types, true );
		} );

		$posts = get_posts( [
			'posts_per_page'         => - 1,
			'post_type'              => $all_post_types,
			'fields'                 => 'ids',
			/* exclude blog page */
			'post__not_in'           => [ get_option( 'page_for_posts' ) ],
			'update_post_meta_cache' => false,
			'meta_query'             => [
				'relation' => 'OR',
				[
					'key'     => 'tve_custom_css',
					'compare' => 'EXISTS',
				],
				[
					'key'     => 'tve_landing_page',
					'compare' => 'EXISTS',
				],
			],
		] );

		$groups = [];

		foreach ( $posts as $post_id ) {
			$post = get_post( $post_id );

			if ( empty( $groups[ $post->post_type ] ) ) {
				$post_type_object = get_post_type_object( $post->post_type );

				$groups[ $post->post_type ] = [
					'type'  => $post->post_type,
					'label' => $post_type_object === null ? $post->post_type : $post_type_object->label,
					'items' => [],
				];
			}

			$groups[ $post->post_type ]['items'][] = [
				'id'      => $post->ID,
				'name'    => $post->post_title,
				'version' => (int) $post->{Main::OPTIMIZATION_VERSION_META},
				'url'     => get_permalink( $post->ID ),
			];
		}

		return $groups;
	}
}

