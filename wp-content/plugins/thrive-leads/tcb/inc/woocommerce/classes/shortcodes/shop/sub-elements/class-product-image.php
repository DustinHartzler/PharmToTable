<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\Integrations\WooCommerce\Shortcodes\Shop;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Product_Image
 *
 * @package TCB\Integrations\WooCommerce\Shortcodes\Shop
 */
class Product_Image extends \TCB_Element_Abstract {

	/**
	 * Element name
	 *
	 * @return string|void
	 */
	public function name() {
		return __( 'Product Image', 'thrive-cb' );
	}

	/**
	 * WordPress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		$identifier = Main::get_sub_element_identifier( 'product-image' );

		return Main::get_shop_element_identifier( $identifier );
	}

	/**
	 * Element is not visible in the sidebar
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	public function own_components() {
		/* only the layout, borders and shadows are visible */
		$components = array(
			'typography'       => array( 'hidden' => true ),
			'background'       => array( 'hidden' => true ),
			'animation'        => array( 'hidden' => true ),
			'responsive'       => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),
			'shadow'           => array(
				'config' => array(
					'disabled_controls' => array( 'inner', 'text' ),
				),
			),
		);

		$components['layout']['disabled_controls'] = array( 'Display', 'Alignment', '.tve-advanced-controls' );

		$components['borders']['config']['Borders']['important'] = true;
		$components['borders']['config']['Corners']['important'] = true;

		return $components;
	}
}

return new Product_Image( 'product-image' );
