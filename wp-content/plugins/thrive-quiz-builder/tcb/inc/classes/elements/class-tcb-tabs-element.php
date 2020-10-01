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
 * Class TCB_Tabs_Element
 */
class TCB_Tabs_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Tabs', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'tabs';
	}

	/**
	 * Tabs element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-tabbed-content.tve-tab-upgraded';
	}

	/**
	 * This element is not a placeholder
	 *
	 * @return bool|true
	 */
	public function is_placeholder() {
		return false;
	}

	public function hide() {
		return false;
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array_merge( array(
			'tabs'       => array(
				'config' => array(
					'TabPalettes'      => array(
						'config'  => array(),
						'extends' => 'Palettes',
					),
					'ContentAnimation' => array(
						'config'  => array(
							'name'    => __( 'Content Switch Animation', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'appear',
									'name'  => __( 'Appear', 'thrive-cb' ),
								),
								array(
									'value' => 'slide-right',
									'name'  => __( 'From Right', 'thrive-cb' ),
								),
								array(
									'value' => 'slide-left',
									'name'  => __( 'From Left', 'thrive-cb' ),
								),
								array(
									'value' => 'slide-up',
									'name'  => __( 'From Top', 'thrive-cb' ),
								),
								array(
									'value' => 'slide-down',
									'name'  => __( 'From Bottom', 'thrive-cb' ),
								),
								array(
									'value' => 'carousel',
									'name'  => __( 'Carousel', 'thrive-cb' ),
								),
								array(
									'value' => 'smooth-resize',
									'name'  => __( 'Smooth Resize', 'thrive-cb' ),
								),
								array(
									'value' => 'swing-up',
									'name'  => __( 'Swing Up', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'Select',
					),
					'HoverEffect'      => array(
						'config'  => array(
							'name'    => __( 'Hover Effect', 'thrive-cb' ),
							'options' => array(
								''            => 'None',
								'c-underline' => 'Underline',
								'c-double'    => 'Double line',
								'c-brackets'  => 'Brackets',
								'c-thick'     => 'Thick Underline',
							),
						),
						'extends' => 'Select',
					),
					'DefaultTab'       => array(
						'config'  => array(
							'name'    => __( 'Default Tab', 'thrive-cb' ),
							'options' => array(),
						),
						'extends' => 'Select',
					),
				),
			),
			'typography' => array( 'hidden' => true ),
			'animation'  => array( 'hidden' => true ),
			'scroll'     => array(
				'hidden' => false,
			),
		), $this->group_component() );
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
	 * Enable group editing on text elements from table cells
	 *
	 * @return array|bool
	 */
	public function has_group_editing() {
		return array(
			'select_values' => array(
				array(
					'value'    => 'all_items',
					'selector' => ' .tve_scT > ul > .tve_tab_title_item',
					'name'     => __( 'Grouped Tab Items', 'thrive-cb' ),
					'singular' => __( '-- Tab Item %s', 'thrive-cb' ),
				),
				array(
					'value'    => 'all_content',
					'selector' => ' .tve_scT > .tve_tab_content',
					'name'     => __( 'Grouped Tab Contents', 'thrive-cb' ),
					'singular' => __( '-- Tab Content %s', 'thrive-cb' ),
				),
			),
		);
	}
}
