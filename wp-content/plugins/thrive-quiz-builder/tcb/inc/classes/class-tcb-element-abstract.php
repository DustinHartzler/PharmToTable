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
 * Class TCB_Element_Abstract
 */
abstract class TCB_Element_Abstract {

	/**
	 * Element alternate.
	 *
	 * @var string
	 */
	protected $_alternate = '';

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return $this->_alternate;
	}


	/**
	 * Element tag.
	 *
	 * @var string
	 */
	protected $_tag = '';

	/**
	 * TCB_Element_Abstract constructor.
	 *
	 * @param string $tag element tag.
	 */
	public function __construct( $tag = '' ) {
		if ( empty( $this->_tag ) ) {
			$this->_tag = $tag;
		}
	}

	/**
	 * Get element tag
	 *
	 * @return string
	 */
	public function tag() {
		return $this->_tag;
	}

	/**
	 * Element identifier that will help us understand on what we click and open the right menu
	 *
	 * @return string
	 */
	public function identifier() {
		return '';
	}

	/**
	 * Configuration of the element with components and elements
	 *
	 * @return array
	 */
	public function components() {
		$own_components = $this->own_components();
		$components     = tve_array_replace_recursive( $this->general_components(), $own_components );
		foreach ( $own_components as $key => $component ) {

			if ( isset( $component['disabled_controls'] ) ) {
				$components[ $key ]['disabled_controls'] = $component['disabled_controls'];
			}
		}

		$components = $this->normalize_components( $components );

		return $components;
	}

	/**
	 * Components that apply only to the element
	 *
	 * @return array
	 */
	public function own_components() {
		return array();
	}

	/**
	 * General components that apply to all elements
	 *
	 * @return array
	 */
	protected function general_components() {
		$prefix_config = tcb_selection_root();

		/**
		 * Avoid creating extra javascript configuration data
		 */
		if ( $this->inherit_components_from() || $this->is_placeholder() ) {
			return array();
		}
		$texts = array(
			' p',
			' li',
			' blockquote',
			' address',
			' .tcb-plain-text',
			' label',
		);

		$headings = array(
			' h1',
			' h2',
			' h3',
			' h4',
			' h5',
			' h6',
		);

		$h1_spacing = $h2_spacing = $h3_spacing = $p_spacing = array(
			'css_suffix' => ' p',
			'important'  => true,
			'config'     => array(
				'default' => '',
				'min'     => '0',
				'max'     => '100',
				'label'   => __( 'Paragraph Spacing', 'thrive-cb' ),
				'um'      => array( 'px', 'em' ),
				'css'     => 'fontSize',
			),
			'extends'    => 'Slider',
		);

		$h1_spacing['css_suffix']      = ' h1';
		$h1_spacing['config']['label'] = __( 'H1 Spacing', 'thrive-cb' );
		$h2_spacing['css_suffix']      = ' h2';
		$h2_spacing['config']['label'] = __( 'H2 Spacing', 'thrive-cb' );
		$h3_spacing['css_suffix']      = ' h3';
		$h3_spacing['config']['label'] = __( 'H3 Spacing', 'thrive-cb' );

		return array(
			'typography' => array(
				'disabled_controls' => array(
					'.tve-advanced-controls',
					'p_spacing',
					'h1_spacing',
					'h2_spacing',
					'h3_spacing',
				),
				'order'             => 90,
				'config'            => array(
					'ParagraphStyle'       => array(
						'hidden'  => true,
						'config'  => array(
							'label' => __( 'Paragraph Style', 'thrive-cb' ),
						),
						'extends' => 'StyleChange',
					),
					'ParagraphStylePicker' => array(
						'config' => array(
							'label'         => __( 'Choose Default Paragraph Style', 'thrive-cb' ),
							'default'       => '',
							'default_label' => __( tcb_post()->is_landing_page() ? 'Landing Page Default' : 'Default', 'thrive-cb' ),
						),
					),
					'ToggleControls'       => array(
						'config'  => array(
							'buttons' => array(
								array(
									'value'   => 'tcb-typography-font-size',
									'text'    => __( 'Font Size', 'thrive-cb' ),
									'default' => true,
								),
								array(
									'value' => 'tcb-typography-line-height',
									'text'  => __( 'Line Height', 'thrive-cb' ),
								),
								array(
									'value' => 'tcb-typography-letter-spacing',
									'text'  => __( 'Letter Spacing', 'thrive-cb' ),
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'FontSize'             => array(
						'css_suffix' => $texts,
						'css_prefix' => $prefix_config . ' ',
						'config'     => array(
							'default' => '16',
							'min'     => '1',
							'max'     => '100',
							'label'   => '',
							'um'      => array( 'px', 'em' ),
							'css'     => 'fontSize',
						),
						'extends'    => 'FontSize',
					),
					'LetterSpacing'        => array(
						'css_suffix' => $texts,
						'config'     => array(
							'default' => 'auto',
							'min'     => '0',
							'max'     => '100',
							'label'   => '',
							'um'      => array( 'px' ),
							'css'     => 'letterSpacing',
						),
						'extends'    => 'Slider',
					),
					'FontColor'            => array(
						'css_suffix' => array_merge( $texts, $headings ),
						'css_prefix' => $prefix_config . ' ',
						'config'     => array(
							'default' => '000',
							'label'   => 'Color',
							'options' => array(
								'output' => 'object',
							),
						),
						'extends'    => 'ColorPicker',
					),
					'TextAlign'            => array(
						'config'  => array(
							'name'    => __( 'Alignment', 'thrive-cb' ),
							'buttons' => array(
								array(
									'icon'    => 'format-align-left',
									'text'    => '',
									'value'   => 'left',
									'default' => true,
								),
								array(
									'icon'  => 'format-align-center',
									'text'  => '',
									'value' => 'center',
								),
								array(
									'icon'  => 'format-align-right',
									'text'  => '',
									'value' => 'right',
								),
								array(
									'icon'  => 'format-align-justify',
									'text'  => '',
									'value' => 'justify',
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'TextStyle'            => array(
						'css_suffix' => $texts,
						'css_prefix' => $prefix_config . ' ',
					),
					'TextTransform'        => array(
						'css_suffix' => array_merge( $texts, $headings ),
						'css_prefix' => $prefix_config . ' ',
						'config'     => array(
							'name'    => __( 'Transform', 'thrive-cb' ),
							'buttons' => array(
								array(
									'icon'    => 'none',
									'text'    => '',
									'value'   => 'none',
									'default' => true,
								),
								array(
									'icon'  => 'format-all-caps',
									'text'  => '',
									'value' => 'uppercase',
								),
								array(
									'icon'  => 'format-capital',
									'text'  => '',
									'value' => 'capitalize',
								),
								array(
									'icon'  => 'format-lowercase',
									'text'  => '',
									'value' => 'lowercase',
								),
							),
						),
						'extends'    => 'ButtonGroup',
					),
					'FontFace'             => array(
						'css_suffix' => $texts,
						'css_prefix' => $prefix_config . ' ',
						'config'     => array(
							'template' => 'controls/font-manager',
							'inline'   => false,
						),
					),
					'LineHeight'           => array(
						'css_suffix' => $texts,
						'css_prefix' => $prefix_config . ' ',
						'config'     => array(
							'default' => '16',
							'min'     => '1',
							'max'     => '100',
							'label'   => '',
							'um'      => array( 'px', 'em' ),
							'css'     => 'lineHeight',
						),
						'extends'    => 'LineHeight',
					),
					'p_spacing'            => $p_spacing,
					'h1_spacing'           => $h1_spacing,
					'h2_spacing'           => $h2_spacing,
					'h3_spacing'           => $h3_spacing,
				),
			),
			'layout'     => array(
				'order' => 100,
			),
			'background' => array(
				'order'             => 110,
				'config'            => array(
					'ColorPicker'       => array(
						'config' => array(
							'icon'      => true,
							'important' => true,
						),
					),
					'PreviewFilterList' => array(
						'config' => array(
							'sortable'    => false,
							'extra_class' => 'tcb-preview-list-white',
						),
					),
					'PreviewList'       => array(
						'config' => array(
							'sortable' => true,
						),
					),
				),
				'disabled_controls' => array(
					'video',
				),
			),
			'borders'    => array(
				'order'  => 120,
				'config' => array(
					'Corners' => array(
						'overflow' => true,
					),
				),
			),
			'animation'  => array(
				'order' => 130,
			),
			/**
			 * Reorder the sidebar, now the Shadow is before Responsive
			 */

			'shadow'           => array(
				'order'          => 140,
				'inline_text_to' => '',
			),
			'responsive'       => array(
				'order' => 150,
			),
			'styles-templates' => array(
				'order' => 160,
			),
			'scroll'           => array(
				'hidden' => true,
				'order'  => 125,
			),

		);
	}

	/**
	 * Return element config containing: components, identifier, name, icon, hide
	 *
	 * @return array|mixed
	 * @throws Exception
	 */
	public function config() {

		$config = array(
			'components'                  => $this->components(),
			'identifier'                  => $this->identifier(),
			'name'                        => $this->name(),
			'icon'                        => $this->icon(),
			'hide'                        => $this->hide(),
			'tag'                         => $this->tag(),
			'is_placeholder'              => $this->is_placeholder(),
			'hover'                       => $this->has_hover_state(),
			'active'                      => $this->active_state_config(),
			'expanded'                    => $this->expanded_state_config(),
			'expanded_state_label'        => $this->expanded_state_label(),
			'expanded_state_apply_inline' => $this->expanded_state_apply_inline(),
			'has_group'                   => $this->has_group_editing(),
			'category'                    => $this->category(),
		);
		if ( ( $inherit_from = $this->inherit_components_from() ) ) {
			$config['inherit_from'] = $inherit_from;
		}

		$config = apply_filters( 'tcb_element_' . $this->tag() . '_config', $config );

		return $config;
	}

	/**
	 * Normalize components by making sure all the variables are present
	 *
	 * @param array $components element config.
	 *
	 * @return array
	 */
	private function normalize_components( $components = array() ) {
		$i = 1;
		foreach ( $components as $key => $c ) {
			/**
			 * If component is marked as hidden, completely remove it from the localization object
			 */
			if ( ! empty( $c['hidden'] ) ) {
				unset( $components[ $key ] );
				continue;
			}

			/* update the order of the component in case it's not set */
			if ( ! isset( $c['order'] ) ) {
				$components[ $key ]['order'] = $i ++;
			}

			/* set the 'to' as empty if nothing is added => in this case the component will apply to the wrapper */
			if ( ! isset( $c['to'] ) ) {
				$components[ $key ]['to'] = '';
			}

			/* by default, if nothing is set, the nothing is hidden in the component */
			if ( ! isset( $c['hide'] ) ) {
				$components[ $key ]['hide'] = array();
			}

			/* if nothing is added, by default the config is empty */
			if ( ! isset( $c['config'] ) ) {
				$components[ $key ]['config'] = array();
			}
		}

		return $components;
	}

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return '';
	}

	/**
	 * The toString override
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->name();
	}

	/**
	 * The icon of the element
	 *
	 * @return string
	 */
	public function icon() {
		return '';
	}

	/**
	 * Either to display or not the element in the sidebar menu
	 *
	 * @return bool
	 */
	public function hide() {
		return false;
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
	 * Return the element html layout after applying the filter
	 *
	 * @param string $html Element layout.
	 *
	 * @return mixed
	 */
	public function layout( $html = '' ) {

		if ( empty( $html ) ) {
			$html = $this->html();
		}

		$html = apply_filters( 'tcb_' . $this->name() . '_element_layout', $html );

		return $html;
	}

	/**
	 * Get all custom sidebar states
	 * This can be overridden if an element needs multiple sidebar states
	 *
	 * @return array
	 */
	public function get_custom_sidebars() {
		$extra_state = $this->get_sidebar_extra_state();

		if ( empty( $extra_state ) ) {
			$sidebars = array();
		} else {
			$sidebars = array(
				$this->_tag => array(
					'template' => $extra_state,
					'title'    => $this->name(),
				),
			);
		}

		return $sidebars;
	}

	/**
	 * Get an extra state for the sidebar. Can be used to switch the sidebar to this state
	 *
	 * @return string|null
	 */
	public function get_sidebar_extra_state() {
		return '';
	}

	/**
	 * Whether or not this element is only a placeholder ( it has no menu, it's not selectable etc )
	 * e.g. Content Templates
	 *
	 * @return bool
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * Whether or not this element is available and should be included in the current page
	 *
	 * @return bool
	 */
	public function is_available() {
		return true;
	}

	/**
	 * Whether or not the this element can be edited while under :hover state
	 *
	 * @return bool
	 */
	public function has_hover_state() {
		return false;
	}

	/**
	 * Whether or not the this element can be styles for active state
	 *
	 * Can return the active class .tcb-active-state or the pseudo class :active
	 *
	 * @return bool | string
	 */
	public function active_state_config() {
		return false;
	}

	/**
	 * If the element has active state it offers possibility of changing default label
	 *
	 * @return bool | string
	 */
	public function expanded_state_label() {
		return __( 'Expanded', 'thrive-cb' );
	}

	/**
	 * Whether or not we can apply inline css too on element
	 * e.g for tabs/toggle we cant because elements can be in two states at the time
	 *
	 * @return bool | string
	 */
	public function expanded_state_apply_inline() {
		return false;
	}

	/**
	 * Whether or not this element can be styled for expanded state
	 *
	 * @return bool | string
	 */
	public function expanded_state_config() {
		return false;
	}

	/**
	 * Whether or not this element has cloud templates
	 *
	 * @return bool
	 */
	public function has_cloud_templates() {
		return false;
	}

	/**
	 * Allows different element names to use the same exact components as a base building block
	 * Example: a Testimonial element uses the same exact components as the Columns element ( because it is a column container element ) and
	 * has extra testimonial options
	 *
	 * @return null|string
	 */
	public function inherit_components_from() {
		return null;
	}

	/**
	 * Unified place for the "Thrive Integrations" category. Implemented here so that we can have a single translation for this
	 *
	 * @return string
	 */
	public static function get_thrive_integrations_label() {
		return __( 'Thrive Integrations', 'thrive-cb' );
	}

	/**
	 * Unified place for the "Thrive Foundation (Basic)" category. Implemented here so that we can have a single translation for this
	 *
	 * @return string
	 */
	public static function get_thrive_basic_label() {
		return __( 'Foundation', 'thrive-cb' );
	}

	/**
	 * Unified place for the "Thrive Building Blocks (Advanced)" category. Implemented here so that we can have a single translation for this
	 *
	 * @return string
	 */
	public static function get_thrive_advanced_label() {
		return __( 'Building Blocks', 'thrive-cb' );
	}

	/**
	 * Widgets section label
	 *
	 * @return string
	 */
	public static function get_widgets_label() {
		return __( 'Widgets', 'thrive-cb' );
	}

	/**
	 * Returns the HTML placeholder for an element (contains a wrapper, and a button with icon + element name)
	 *
	 * @param string $title Optional. Defaults to the name of the current element
	 *
	 * @return string
	 */
	public function html_placeholder( $title = null ) {
		if ( empty( $title ) ) {
			$title = $this->name();
		}

		return tcb_template( 'elements/element-placeholder', array(
			'icon'  => $this->icon(),
			'class' => str_replace( array( ',.', ',', '.' ), array( ' ', '', '' ), $this->identifier() ),
			'title' => $title,
		), true );
	}

	/**
	 * Element category that will be displayed in the sidebar.
	 * If the element is hidden it's ok not to have a category defined.
	 *
	 * @throws Exception
	 */
	public function category() {
		if ( ! $this->hide() ) {
			throw new Exception( 'Please define category for element:' . $this->name() );
		}

		return '';
	}

	/**
	 * Determines if an element has group editing or not
	 *
	 * @return bool
	 */
	public function has_group_editing() {
		return false;
	}

	/**
	 * Shows if the element is promoted.
	 *
	 * If promoted it will have a special place inside the element sidebar
	 *
	 * @return bool
	 */
	public function promoted() {
		return false;
	}

	/**
	 * Outputs Group Component Settings
	 *
	 * @return array
	 */
	public function group_component() {
		return array(
			'group' => array(
				'config' => array(
					'ButtonToggle' => array(
						'config' => array(
							'label'         => '',
							'class'         => 'tcb-group-toggle-btn',
							'icon_active'   => 'unlock-alt-regular',
							'icon_inactive' => 'lock-alt-regular',
							'tooltip'       => array(
								'active'   => __( 'Group styling disabled. The styling will be applied only for the selected element.', 'thrive-cb' ),
								'inactive' => __( 'Group styling active. The same styling will be applied to similar elements.', 'thrive-cb' ),
							),
						),
					),
					'preview'      => array(
						'config'  => array(
							'name'       => '',
							'full-width' => true,
							'options'    => array(),
						),
						'extends' => 'Select',
					),

				),
			),
		);
	}

	/**
	 * Outputs Shared Styles Component Settings
	 *
	 * @return array
	 */
	public function shared_styles_component() {
		return array(
			'shared-styles' => array(
				'order'  => 1,
				'config' => array(
					'global_style' => array(
						'config' => array(),
					),
					'preview'      => array(
						'config' => array(
							'label' => __( 'Style', 'thrive-cb' ),
							'items' => array(),
						),
					),
				),
			),
		);
	}
}
