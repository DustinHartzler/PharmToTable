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
 * Class TCB_Audio_Element
 */
class TCB_Audio_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Audio', 'thrive-cb' );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'audio';
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'audio';
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv_audio,.tve_audio_container';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'audio'      => array(
				'config' => array(
					'ExternalFields' => array(
						'config'  => array(
							'key'               => 'audio',
							'shortcode_element' => 'audio.tcb-audio',
						),
						'extends' => 'CustomFields',
					),
				),
			),
			'typography' => array( 'hidden' => true ),
			'background' => array( 'hidden' => true ),
			'shadow'     => array(
				'config' => array(
					'disabled_controls' => array( 'inner', 'text' ),
				),
			),
			'animation'  => array( 'hidden' => true ),
			'layout'     => array(
				'config'            => array(
					'Width'  => array(
						'important' => true,
					),
					'Height' => array(
						'css_suffix' => array( ' iframe', ' > :first-child' ),
					),
				),
				'disabled_controls' => array( 'Overflow', 'ScrollStyle' ),
			),
		);
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return static::get_thrive_advanced_label();
	}

	/**
	 * Element info
	 *
	 * @return string|string[][]
	 */
	public function info() {
		return array(
			'instructions' => array(
				'type' => 'help',
				'url'  => 'audio_element',
				'link' => 'https://help.thrivethemes.com/en/articles/4425842-how-to-use-the-audio-element',
			),
		);
	}
}
