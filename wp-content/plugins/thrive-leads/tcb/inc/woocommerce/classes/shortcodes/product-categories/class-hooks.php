<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\Integrations\WooCommerce\Shortcodes\Product_Categories;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Hooks
 *
 * @package TCB\Integrations\WooCommerce\Shortcodes\Product_Categories
 */
class Hooks {
	public static function add() {
		add_filter( 'tcb_content_allowed_shortcodes', [ __CLASS__, 'content_allowed_shortcodes_filter' ] );

		add_filter( 'tcb_element_instances', [ __CLASS__, 'tcb_element_instances' ] );
	}

	/**
	 * Allow the shop shortcode to be rendered in the editor
	 *
	 * @param $shortcodes
	 *
	 * @return array
	 */
	public static function content_allowed_shortcodes_filter( $shortcodes ) {
		if ( is_editor_page() ) {
			$shortcodes[] = Main::SHORTCODE;
		}

		return $shortcodes;
	}

	/**
	 * @param $instances
	 *
	 * @return mixed
	 */
	public static function tcb_element_instances( $instances ) {
		$product_categories_element = require_once __DIR__ . '/class-element.php';

		$instances[ $product_categories_element->tag() ] = $product_categories_element;

		$files = array_diff( scandir( __DIR__ . '/sub-elements' ), [ '.', '..' ] );

		foreach ( $files as $file ) {
			$instance                      = require_once __DIR__ . '/sub-elements/' . $file;
			$instances[ $instance->tag() ] = $instance;
		}

		return $instances;
	}
}
