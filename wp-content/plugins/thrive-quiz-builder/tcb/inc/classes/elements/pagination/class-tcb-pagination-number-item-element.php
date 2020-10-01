<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}


class TCB_Pagination_Number_Item_Element extends TCB_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Page Numbers', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tcb-pagination-number';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::general_components();

		$prefix_config = tcb_selection_root();

		$components['typography']['config']['css_suffix'] = '';
		$components['typography']['disabled_controls']    = array( 'TextTransform', 'TextAlign', '.tve-advanced-controls', '[data-value="tcb-typography-line-height"]' );

		foreach ( $components['typography']['config'] as $control => $config ) {
			if ( is_array( $config ) ) {
				$components['typography']['config'][ $control ]['css_suffix'] = '';
			}
		}

		$components['typography']['config']['FontColor']['important'] = true;
		$components['typography']['config']['FontSize']['important']  = true;

		/* add a suffix and prefix for the shadow controls */
		$components['shadow']['config']['css_prefix'] = $prefix_config;

		$components['layout']['disabled_controls'] = array( 'Display', 'Alignment', '.tve-advanced-controls' );

		$components['animation']        = array( 'hidden' => true );
		$components['responsive']       = array( 'hidden' => true );
		$components['styles-templates'] = array( 'hidden' => true );

		return $components;
	}

	/**
	 * Hide this element in the sidebar.
	 *
	 * @return string
	 */
	public function hide() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function has_hover_state() {
		return true;
	}
}
