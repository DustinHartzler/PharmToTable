<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package TCB2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TCB_Lead_Generation_Radio_Element extends TCB_Element_Abstract {

	public function name() {
		return __( 'Lead Generation Radio', 'thrive-cb' );
	}

	public function identifier() {
		return '.tve_lg_radio';
	}

	public function hide() {
		return true;
	}

	public function own_components() {

		$components = array(
			'lead_generation_radio' => array(
				'config' => array(
					'ShowLabel'       => array(
						'config'  => array(
							'label' => __( 'Show Label', 'thrive-cb' ),
						),
						'extends' => 'Switch',
					),
					'Required'        => array(
						'config'  => array(
							'default' => false,
							'label'   => __( 'Required field' ),
						),
						'extends' => 'Switch',
					),
					'ColumnNumber'    => array(
						'to'      => '.tve-radio-grid',
						'config'  => array(
							'default' => '1',
							'min'     => '1',
							'max'     => '5',
							'limit'   => '5',
							'label'   => __( 'Columns', 'thrive-cb' ),
							'um'      => array(),
						),
						'extends' => 'Slider',
					),
					'VerticalSpace'   => array(
						'to'      => '.tve-radio-grid',
						'config'  => array(
							'default' => '0',
							'min'     => '0',
							'max'     => '300',
							'label'   => __( 'Vertical Space', 'thrive-cb' ),
							'um'      => array( 'px', '%' ),
							'css'     => '--v-gutter',
						),
						'extends' => 'Slider',
					),
					'HorizontalSpace' => array(
						'to'      => '.tve-radio-grid',
						'config'  => array(
							'default' => '20',
							'min'     => '0',
							'max'     => '100',
							'label'   => __( 'Horizontal Space', 'thrive-cb' ),
							'um'      => array( 'px', '%' ),
							'css'     => '--h-gutter',
						),
						'extends' => 'Slider',
					),
					'OptionsList'     => array(
						'config' => array(
							'sortable'      => true,
							'settings_icon' => 'pen-light',
							'marked'        => true,
							'marking_text'  => __( 'Set as default', 'thrive-cb' ),
							'marking_icon'  => 'check',
							'marked_field'  => 'default',
						),
					),
					'AnswerTag'        => array(
						'config'  => array(
							'default' => false,
							'label'   => __( 'Send answer as tag', 'thrive-cb' ),
							'info'    => true,
						),
						'extends' => 'Switch',
					),
				),
			),
			'typography'            => array(
				'hidden' => true,
			),
			'layout'                => array(
				'disabled_controls' => array(
					'Width',
					'Height',
					'Alignment',
					'.tve-advanced-controls',
					'hr',
				),
				'config'            => array(),
			),
			'borders'               => array(
				'config' => array(),
			),
			'animation'             => array(
				'hidden' => true,
			),
			'background'            => array(
				'config' => array(),
			),
			'shadow'                => array(
				'hidden' => true,
			),
			'styles-templates'      => array(
				'config' => array(),
			),
			'responsive'            => array(
				'hidden' => true,
			),
		);

		return array_merge( $components, $this->group_component() );
	}

}
