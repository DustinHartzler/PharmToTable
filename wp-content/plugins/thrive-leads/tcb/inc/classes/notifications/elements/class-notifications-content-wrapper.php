<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\Notifications;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Content_Wrapper
 *
 * @package TCB\Notifications
 */
class Notifications_Content_Wrapper extends \TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Notification', 'thrive-cb' );
	}

	/**
	 * WordPress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.notifications-content-wrapper .notifications-content';
	}

	public function own_components() {
		$components                                = parent::own_components();
		$components['animation']                   = [ 'hidden' => true ];
		$components['typography']                  = [ 'hidden' => true ];
		$components['layout']['disabled_controls'] = [ 'Alignment', 'Display', '.tve-advanced-controls' ];
		$components['notification']                = [
			'config' => [
				'DisplayPosition'    => [
					'config'  => [
						'name'          => __( 'Display position', 'thrive-cb' ),
						'large_buttons' => true,
						'buttons'       => [
							[
								'text'  => '',
								'value' => 'top-left',
							],
							[
								'text'  => '',
								'value' => 'top-center',
							],
							[
								'text'  => '',
								'value' => 'top-right',
							],
							[
								'text'  => '',
								'value' => 'middle-left',
							],
							[
								'text'  => '',
								'value' => 'middle-center',
							],
							[
								'text'  => '',
								'value' => 'middle-right',
							],
							[
								'text'  => '',
								'value' => 'bottom-left',
							],
							[
								'text'  => '',
								'value' => 'bottom-center',
							],
							[
								'text'  => '',
								'value' => 'bottom-right',
							],

						],
					],
					'extends' => 'ButtonGroup',
				],
				'VerticalSpacing'    => [
					'config'  => [
						'name'    => __( 'Top spacing', 'thrive-cb' ),
						'min'     => '0',
						'default' => '50',
						'um'      => array( 'px' ),
					],
					'extends' => 'Input',
				],
				'HorizontalSpacing'  => [
					'config'  => [
						'name'    => __( 'Side spacing', 'thrive-cb' ),
						'min'     => '0',
						'default' => '50',
						'um'      => array( 'px' ),
					],
					'extends' => 'Input',
				],
				'AnimationDirection' => [
					'config'  => [
						'name'    => __( 'Animation direction', 'thrive-cb' ),
						'default' => 'down',
						'options' => [
							[
								'value' => 'none',
								'name'  => __( 'No animation', 'thrive-cb' ),
							],
							[
								'value' => 'down',
								'name'  => __( 'Down', 'thrive-cb' ),
							],
							[
								'value' => 'up',
								'name'  => __( 'Up', 'thrive-cb' ),
							],
							[
								'value' => 'right',
								'name'  => __( 'Right', 'thrive-cb' ),
							],
							[
								'value' => 'left',
								'name'  => __( 'Left', 'thrive-cb' ),
							],
						],
					],
					'extends' => 'Select',
				],
				'AnimationTime'      => [
					'config'  => [
						'min'     => '0',
						'max'     => '10000',
						'default' => '3000',
						'label'   => __( 'Show for (ms)', 'thrive-cb' ),
						'um'      => '',
					],
					'extends' => 'Slider',
				],
			],
		];

		return $components;
	}

	public function hide() {
		return true;
	}

	public function has_hover_state() {
		return true;
	}
}

return new Notifications_Content_Wrapper( 'notification' );
