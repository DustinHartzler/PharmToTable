<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 7/5/2018
 * Time: 4:44 PM
 */

require_once 'class-tcb-button-group-item-element.php';

/**
 * Class TCB_Button_Group_Item_Element
 */
class TCB_Button_Group_Item_Element extends TCB_Button_Element {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Button Group Item', 'thrive-cb' );
	}

	/**
	 * Hide Element From Sidebar Menu
	 *
	 * @return bool
	 */
	public function hide() {
		return true;
	}

	/**
	 * Allow this element to be also styled for active state
	 *
	 * The active state class is .tcb-active-state
	 *
	 * @return string
	 */
	public function active_state_config() {
		return '.tcb-active-state';
	}

	/**
	 * Button element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-button-group-item';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::own_components();

		$components['button']['disabled_controls']               = array(
			'.tve-control[data-key="style"]',
			'.tcb-button-link-container',
		);
		$components['animation']                                 = array(
			'hidden' => true,
		);
		$components['shared-styles']                             = array(
			'hidden' => true,
		);
		$components['layout']['disabled_controls']               = array(
			'Alignment',
			'.tve-advanced-controls',
			'hr',
		);
		$components['borders']['config']['Borders']['important'] = true;
		$components['borders']['config']['Corners']['important'] = true;

		return $components;
	}
}
