<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Search_Form_Element
 */
class TCB_Search_Form_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Search', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'search_elem';
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
	 * WordPress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-search-form';
	}

	/**
	 * @return string
	 */
	public function html() {
		return TCB_Search_Form::render();
	}

	/**
	 * @return bool
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * @return array
	 */
	public function own_components() {

		return array(
			'search_form' => array(
				'config' => array(
					'PostTypes'    => array(
						'config' => array(
							'sortable'  => false,
							'clickable' => false,
						),
					),
					'ButtonLayout' => array(
						'config'  => array(
							'buttons' => array(
								array(
									'text'  => __( 'Text Only', 'thrive-cb' ),
									'value' => 'text',
								),
								array(
									'text'  => __( 'Icon Only', 'thrive-cb' ),
									'value' => 'icon',
								),
								array(
									'text'    => __( 'Icon & Text', 'thrive-cb' ),
									'value'   => 'icon_text',
									'default' => true,
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'FormType'     => array(
						'config'  => array(
							'buttons' => array(
								array(
									'text'    => __( 'With Button', 'thrive-cb' ),
									'value'   => 'with',
									'default' => true,
								),
								array(
									'text'  => __( 'Without Button', 'thrive-cb' ),
									'value' => 'without',
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'ContentWidth' => array(
						'config'  => array(
							'default' => '1024',
							'min'     => '100',
							'label'   => __( 'Width', 'thrive-cb' ),
							'um'      => array( 'px', '%' ),
							'css'     => 'max-width',
						),
						'extends' => 'Slider',
					),
					'Size'         => array(
						'config'     => array(
							'default' => '20',
							'min'     => '10',
							'max'     => '150',
							'label'   => __( 'Size', 'thrive-cb' ),
							'um'      => array( 'px', 'em' ),
							'css'     => 'font-size',
						),
						'css_prefix' => tcb_selection_root() . ' ',
						'extends'    => 'Slider',
					),
				),
			),
			'shadow'      => array(
				'config' => array(
					'disabled_controls' => array( 'text' ),
				),
			),
			'typography'  => array(
				'hidden' => true,
			),
			'animation'   => array(
				'hidden' => true,
			),
		);
	}
}

