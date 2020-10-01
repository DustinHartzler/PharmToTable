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
 * Class TCB_Column_Element
 */
class TCB_Column_Element extends TCB_Element_Abstract {

	/**
	 * Not directly available from the menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Column', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'column';
	}

	/**
	 * Text element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return 'div.tcb-col';
	}

	/**
	 * @return string
	 */
	protected function html() {
		return '';
	}

	/**
	 * @return array
	 */
	public function own_components() {
		return array(
			'column'           => array(
				'config' => array(
					'VerticalPosition' => array(
						'config'  => array(
							'name'      => __( 'Vertical position', 'thrive-cb' ),
							'important' => true,
							'buttons'   => array(
								array(
									'icon'    => 'none',
									'default' => true,
									'value'   => '',
								),
								array(
									'icon'  => 'top',
									'value' => 'flex-start',
								),
								array(
									'icon'  => 'vertical',
									'value' => 'center',
								),
								array(
									'icon'  => 'bot',
									'value' => 'flex-end',
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'FixedWidth'       => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Enable fixed width', 'thrive-cb' ),
							'default' => true,
							'info'    => true,
						),
						'extends' => 'Switch',
					),
					'ColumnWidth'      => array(
						'config'  => array(
							'default'     => '100',
							'min'         => '50',
							'max'         => '1500',
							'label'       => __( 'Width', 'thrive-cb' ),
							'um'          => array( 'px', '%', ),
							'um_disabled' => true,
						),
						'extends' => 'Slider',
					),
				),
			),
			'responsive'       => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),
			'layout'           => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
					'Width',
					'Height',
					'Alignment',
					'Display',
				),
			),
			'borders'          => array(
				'config' => array(
					'Borders' => array(
						'important' => true,
					),
				),
			),
		);
	}

	/**
	 * @return bool
	 */
	public function has_hover_state() {
		return true;
	}
}
