<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 6/27/2018
 * Time: 1:57 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Pricing_Table_Element
 *
 * Element Class
 */
class TCB_Pricing_Table_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Pricing Table', 'thrive-cb' );
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
	 * Element Icon
	 *
	 * @return string
	 */
	public function icon() {
		return 'pricing_box';
	}

	/**
	 * When element is selected in editor this identifier
	 * establishes element _type
	 *
	 * @return string
	 * @see TVE.main.element_selected() TVE._type()
	 *
	 */
	public function identifier() {

		return '.thrv-pricing-table';
	}

	/**
	 * This element is not a placeholder
	 *
	 * @return bool|true
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * Return the element HTML
	 *
	 * @return null|string
	 */
	protected function html() {
		return tcb_template( 'elements/' . $this->tag() . '.php', $this, true );
	}

	/**
	 * Components that apply only to this
	 *
	 * @return array
	 */
	public function own_components() {
		$pricing_table = array(
			'pricing_table' => array(
				'config' => array(
					'PriceInstances' => array(
						'config' => array(
							'sortable' => true,
						),
					),
				),
			),
			'typography'    => array( 'hidden' => true ),
			'animation'     => array(
				'disabled_controls' => array(
					'.btn-inline:not(.anim-animation)',
				),
			),
			'shadow'        => array(
				'config' => array(
					'disabled_controls' => array( 'text' ),
				),
			),
			'layout'        => array(
				'disabled_controls' => array(
					'Overflow',
				),
			),
		);

		return $pricing_table;
	}
}
