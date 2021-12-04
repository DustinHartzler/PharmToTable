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
 * Class TCB_Image_Gallery_Element
 */
class TCB_Image_Gallery_Element extends TCB_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Image Gallery', 'thrive-cb' );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'media';
	}


	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'image-gallery';
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.tcb-image-gallery';
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
	 * HTML layout of the element for when it's dragged in the canvas
	 *
	 * @return string
	 */
	protected function html() {
		return $this->html_placeholder();
	}

	/**
	 * @param null $title
	 *
	 * @return bool|string|null
	 */
	public function html_placeholder( $title = null ) {
		return tcb_template( 'elements/image-gallery', null, true );
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$components = array(
			'image_gallery' => array(
				'config' => array(
					'GalleryType'            => array(
						'config'  => array(
							'name'          => __( 'Gallery type', 'thrive-cb' ),
							'large_buttons' => true,
							'buttons'       => array(
								array(
									'value'   => 'grid',
									'data'    => array(
										'tooltip'  => __( 'Grid', 'thrive-cb' ),
										'position' => 'top',
									),
									'icon'    => 'gallery-grid',
									'default' => true,
								),
								array(
									'value' => 'verticalMasonry',
									'data'  => array(
										'tooltip'  => __( 'Vertical Masonry', 'thrive-cb' ),
										'position' => 'top',
										'width'    => '100%',
									),
									'icon'  => 'gallery-vertical-masonry',
								),
								array(
									'value' => 'horizontalMasonry',
									'data'  => array(
										'tooltip'  => __( 'Horizontal Masonry', 'thrive-cb' ),
										'position' => 'top',
										'width'    => '100%',
									),
									'icon'  => 'gallery-horizontal-masonry',
								),
								array(
									'value' => 'carousel',
									'data'  => array(
										'tooltip'  => __( 'Carousel', 'thrive-cb' ),
										'position' => 'top',
										'width'    => '100%',
									),
									'icon'  => 'gallery-carousel',
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'ShowCaptions'           => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Show captions', 'thrive-cb' ),
							'default' => false,
						),
						'extends' => 'Switch',
					),
					'ShowCaptionsInLightbox' => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Show captions on lightbox', 'thrive-cb' ),
							'default' => false,
						),
						'extends' => 'Switch',
					),
					'Columns'                => array(
						'config'  => array(
							'default' => '3',
							'min'     => '1',
							'max'     => '10',
							'label'   => __( 'Images per row', 'thrive-cb' ),
							'um'      => array( '' ),
						),
						'extends' => 'Slider',
					),
					'VerticalSpace'          => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '240',
							'label' => __( 'Vertical space', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'HorizontalSpace'        => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '240',
							'label' => __( 'Horizontal space', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'ColumnHeight'           => array(
						'config'  => array(
							'min'   => '1',
							'max'   => '800',
							'label' => __( 'Column Height', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'Gutter'                 => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '240',
							'label' => __( 'Gutter', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'ClickBehavior'          => array(
						'config'  => array(
							'name'    => __( 'Click behavior', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'fullscreen',
									'name'  => 'Open fullscreen lightbox',
								),
								array(
									'value' => 'none',
									'name'  => 'None (links can be added in edit mode)',
								),
							),
						),
						'extends' => 'Select',
					),
					'ThumbnailSize'          => array(
						'config'  => array(
							'name'    => __( 'Thumbnail size', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'auto',
									'name'  => 'Automatic',
								),
								array(
									'value' => 'medium',
									'name'  => 'Medium',
								),
								array(
									'value' => 'large',
									'name'  => 'Large',
								),
							),
							'info'    => true,
						),
						'extends' => 'Select',
					),
					'CropImages'             => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Crop images to fit', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
					'GalleryImageHeight'     => array(
						'config'  => array(
							'min'   => '1',
							'max'   => '800',
							'label' => __( 'Image height', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'FullscreenSize'         => array(
						'config'  => array(
							'name'    => __( 'Full screen image size', 'thrive-cb' ),
							'options' => array(
								array(
									'value' => 'medium',
									'name'  => 'Medium',
								),
								array(
									'value' => 'large',
									'name'  => 'Large',
								),
								array(
									'value' => 'full',
									'name'  => 'Original',
								),
							),
						),
						'extends' => 'Select',
					),
					'Autoplay'               => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Autoplay', 'thrive-cb' ),
							'default' => false,
							'info'    => true,
						),
						'extends' => 'Switch',
					),
					'AutoplaySpeed'          => array(
						'config'  => array(
							'default' => '3000',
							'min'     => '1',
							'max'     => '10000',
							'label'   => __( 'Speed', 'thrive-cb' ),
							'um'      => array( 'ms' ),
						),
						'extends' => 'Input',
					),
					'PauseOn'                => array(
						'config'  => array(
							'name'     => __( 'Pause on', 'thrive-cb' ),
							'checkbox' => true,
							'buttons'  => array(
								array(
									'value'   => 'pauseOnFocus',
									'text'    => 'Focus',
									'default' => true,
								),
								array(
									'value'   => 'pauseOnHover',
									'text'    => 'Hover',
									'default' => true,
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'Arrows'                 => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Show arrows', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
					'Dots'                   => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Show dots', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
					'SlidesToShow'           => array(
						'config'  => array(
							'min'   => '1',
							'max'   => '10',
							'label' => __( 'Columns', 'thrive-cb' ),
							'um'    => array(),
						),
						'extends' => 'Slider',
					),
					'SlidesToScroll'         => array(
						'config'  => array(
							'min'   => '1',
							'max'   => '3',
							'label' => __( 'Slides to scroll', 'thrive-cb' ),
							'um'    => array(),
						),
						'extends' => 'Slider',
					),
					'CenterMode'             => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Overlap end images', 'thrive-cb' ),
							'default' => false,
						),
						'extends' => 'Switch',
					),
					'CenterPadding'          => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '160',
							'label' => __( 'Distance', 'thrive-cb' ),
							'um'    => array( 'px', '%' ),
						),
						'extends' => 'Slider',
					),
					'Draggable'              => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Allow dragging images', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
					'Fade'                   => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Single image fader', 'thrive-cb' ),
							'default' => false,
							'info'    => true,
						),
						'extends' => 'Switch',
					),
					'FadeImageWidth'         => array(
						'config'  => array(
							'default' => '0',
							'min'     => '10',
							'max'     => '1080',
							'um'      => array( '%', 'px' ),
							'label'   => __( 'Image width', 'thrive-cb' ),
							'css'     => 'max-width',
						),
						'extends' => 'Slider',
					),
					'Infinite'               => array(
						'config'  => array(
							'name'    => '',
							'label'   => __( 'Infinite sliding', 'thrive-cb' ),
							'default' => true,
						),
						'extends' => 'Switch',
					),
				),
			),
			'layout'        => array(
				'disabled_controls' => array( 'Display' ),
			),
		);

		$components = array_merge( $components, $this->group_component() );

		return array_merge( $components, parent::own_components() );
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
	 * Group Edit Properties
	 *
	 * @return array|bool
	 */
	public function has_group_editing() {
		return array(
			'select_values' => array(
				array(
					'value'    => 'arrows',
					'selector' => '.tcb-carousel-arrow',
					'name'     => __( 'Next/Previous buttons Icons', 'thrive-cb' ),
					'singular' => __( '-- Next/Previous buttons Icon', 'thrive-cb' ),
				),
			),
		);
	}
}
