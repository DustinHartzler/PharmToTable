<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}


class TCB_Pagination_Label_Element extends TCB_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Pagination Label', 'thrive-cb' );
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tcb-pagination-label';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::general_components();

		$prefix_config = tcb_selection_root();

		$components['pagination_label'] = array(
			'config' => array(
				'Format' => array(
					'config'  => array(
						'name'    => __( 'Format', 'thrive-cb' ),
						'options' => array(
							'pages' => 'Page 1 of 8',
							'posts' => 'Showing 1-15 of 365',
						),
					),
					'extends' => 'Select',
				),
			),
		);

		$components['typography']['config']['css_suffix'] = ' p';

		foreach ( $components['typography']['config'] as $control => $config ) {
			if ( is_array( $config ) && $control !== 'FontFace' ) {
				$components['typography']['config'][ $control ]['css_suffix'] = ' p';
				$components['typography']['config'][ $control ]['important']  = true;
			}
		}

		/* fontface uses 'to' instead of css_suffix so it reads the values properly */
		$components['typography']['config']['FontFace']['css_suffix'] = '';
		$components['typography']['config']['FontFace']['important']  = true;
		$components['typography']['config']['FontFace']['to']         = 'p';

		/* add a suffix and prefix for the shadow controls */
		$components['shadow']['config']['css_suffix'] = ' p';
		$components['shadow']['config']['css_prefix'] = $prefix_config;
		$components['shadow']['config']['important']  = true;

		$components['layout']['disabled_controls'] = array( 'Display', 'Alignment' );

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
}
