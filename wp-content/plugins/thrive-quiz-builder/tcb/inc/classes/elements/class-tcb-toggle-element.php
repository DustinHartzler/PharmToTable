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
 * Class TCB_Testimonial_Element
 */
class TCB_Toggle_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Toggle', 'thrive-cb' );
	}

	public function is_placeholder() {
		return false;
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'toggle';
	}

	/**
	 * Section element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_toggle';
	}

	/**
	 * HTML layout of the element for when it's dragged in the canvas
	 *
	 * @return string
	 */
	protected function html() {
		return tcb_template( 'elements/' . $this->tag() . '.php', $this, true );
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$toggle = array(
			'toggle'     => array(
				'config' => array(
					'TogglePalettes'       => array(
						'config'  => array(),
						'extends' => 'Palettes',
					),
					'ColumnNumber' => array(
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
					'ToggleWidth'  => array(
						'config'  => array(
							'default' => '1024',
							'min'     => '100',
							'label'   => __( 'Max width', 'thrive-cb' ),
							'um'      => array( 'px', '%' ),
							'css'     => 'max-width',

						),
						'extends' => 'Slider',
					),

					'VerticalSpace'     => array(
						'to'      => ' .thrv_toggle_item',
						'config'  => array(
							'default' => '0',
							'min'     => '0',
							'max'     => '300',
							'label'   => __( 'Vertical Space', 'thrive-cb' ),
							'um'      => array( 'px', '%' ),
							'css'     => 'margin-top',

						),
						'extends' => 'Slider',
					),
					'HorizontalSpace'   => array(
						'config'  => array(
							'default' => '16',
							'min'     => '0',
							'max'     => '300',
							'label'   => __( 'Horizontal Space', 'thrive-cb' ),
							'um'      => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'AutoCollapse'      => array(
						'config'  => array(
							'label' => __( 'Auto collapse toggles' ),
							'info'  => true,
						),
						'extends' => 'Switch',
					),
					'DropdownAnimation' => array(
						'config'  => array(
							'name'    => __( 'Dropdown Animation', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => '',
									'name'  => __( 'None', 'thrive-cb' ),
								),
								array(
									'value' => 'slide',
									'name'  => __( 'Slide', 'thrive-cb' ),
								),
								array(
									'value' => 'fade',
									'name'  => __( 'Fade', 'thrive-cb' ),
								),
								array(
									'value' => 'slide-fade',
									'name'  => __( 'Slide & Fade', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'Select',
					),
					'AnimationSpeed'    => array(
						'config'  => array(
							'name'    => __( 'Animation speed', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'slow',
									'name'  => __( 'Slow', 'thrive-cb' ),
								),
								array(
									'value' => 'medium',
									'name'  => __( 'Medium', 'thrive-cb' ),
								),
								array(
									'value' => 'fast',
									'name'  => __( 'Fast', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'Select',
					),
					'List'              => array(
						'config' => array(
							'sortable'      => true,
							'settings_icon' => 'pen-light',
						),
					),

				),
			),
			'shadow'     => array(
				'config' => array(
					'disabled_controls' => array( 'inner', 'text' ),
				),
			),
			'borders'    => array(
				'disabled_controls' => array( 'Corners', 'hr' ),
				'config'            => array(),
			),
			'typography' => array( 'hidden' => true ),
			'animation'  => array( 'hidden' => true ),
		);

		return array_merge( $toggle, $this->group_component() );

	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return $this->get_thrive_advanced_label();
	}

	/**
	 * Group Edit Properties
	 *
	 * @return array|bool
	 */
	public function has_group_editing() {
		return array(
			'select_values' => array(
				array(
					'value'     => 'toggle_items',
					'selector'  => '.thrv_toggle_title:not(.tve-state-expanded)',
					'name'      => __( 'Grouped Toggle Items', 'thrive-cb' ),
					'singular'  => __( '-- Toggle Item %s', 'thrive-cb' ),
					'no_unlock' => true,
				),
				array(
					'value'     => 'toggle_items_expanded',
					'selector'  => '.thrv_toggle_title.tve-state-expanded',
					'name'      => __( 'Grouped Expanded Toggle Items', 'thrive-cb' ),
					'singular'  => __( '-- Toggle Item %s', 'thrive-cb' ),
					'no_unlock' => true,
				),
				array(
					'value'    => 'all_toggle_contents',
					'selector' => '.thrv_toggle_content',
					'name'     => __( 'Grouped Toggle Content', 'thrive-cb' ),
					'singular' => __( '-- Toggle Content %s', 'thrive-cb' ),
				),
			),
		);
	}
}
