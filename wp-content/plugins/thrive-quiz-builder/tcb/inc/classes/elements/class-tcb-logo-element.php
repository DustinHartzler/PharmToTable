<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Logo_Element
 */
class TCB_Logo_Element extends TCB_Image_Element {
	/**
	 * @return string
	 */
	public function name() {
		return __( 'Logo', 'thrive-cb' );
	}

	/**
	 * @return string
	 */
	public function icon() {
		return 'logo';
	}

	/**
	 * @return string
	 */
	public function identifier() {
		return '.' . TCB_Logo::IDENTIFIER;
	}

	/**
	 * @return string
	 */
	public function html() {
		/* by default, when added to the page, the logo has id = 0 ( which is the first default placeholder ) */
		return TCB_Logo::render_logo( array( 'data-id-d' => 0 ) );
	}

	/**
	 * Inherit all the controls from the Image Element, then remove what we don't need and add our own.
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::own_components();

		unset( $components['image'] );
		unset( $components['image-effects'] );

		/* remove hyperlink */
		$components['animation'] = array( 'hidden' => true );

		/* add the logo control */
		$components[ TCB_Logo::COMPONENT ] = array(
			'config'            => array(
				'ImageSize'             => array(
					'config'  => array(
						'default'   => '240',
						'min'       => '20',
						'max'       => '1024',
						'label'     => __( 'Size', 'thrive-cb' ),
						'um'        => array( 'px', '%' ),
						'css'       => 'width',
						'important' => true,
					),
					'extends' => 'ImageSize',
				),
				'MenuSplitLogoPosition' => array(
					'config'  => array(
						'full-width' => true,
						'name'       => __( 'Position when menu is Hamburger', 'thrive-cb' ),
						'buttons'    => array(
							array(
								'text'  => __( 'Left', 'thrive-cb' ),
								'value' => 'left',
							),
							array(
								'text'  => __( 'Right', 'thrive-cb' ),
								'value' => 'right',
							),
						),
					),
					'extends' => 'ButtonGroup',
				),
				'ImageAltText'          => array(
					'config'  => array(
						'label' => __( 'Alt Text', 'thrive-cb' ),
					),
					'extends' => 'LabelInput',
				),
			),
			'disabled_controls' => array(
				'Overflow',
			),
		);

		return $components;
	}
}
