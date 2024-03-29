<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 28.01.2015
 * Time: 14:39
 */
class Thrive_Leads_State_Manager extends Thrive_Leads_Request_Handler {
	/**
	 * @var array
	 */
	protected $variation;

	/**
	 * @var Thrive_Leads_Template_Manager
	 */
	protected static $instance = null;

	/**
	 * Used in regenerating the CSS IDs for the variation
	 *
	 * @var array
	 */
	protected $css_id_map = array();

	/**
	 *
	 *  singleton implementation
	 *
	 * @param array $variation the variation being edited - this is always the main (default) state for a variation
	 *
	 * @return Thrive_Leads_State_Manager
	 */
	public static function instance( $variation ) {
		if ( ! empty( $variation ) && ! empty( $variation['parent_id'] ) ) {
			$variation = tve_leads_get_form_variation( null, $variation['parent_id'] );
		}

		return new self( $variation );
	}

	/**
	 * get all templates available for a form type
	 *
	 * @param mixed $form_type
	 */
	public static function for_form_type( $form_type ) {
		if ( $form_type instanceof WP_Post ) {
			$form_type = get_post_meta( $form_type->ID, 'tve_form_type', true );
		}

		return self::instance( array() )->get_all( $form_type );
	}

	/**
	 *
	 * @param array $variation - the variation for which we load the state
	 */
	private function __construct( $variation ) {
		$this->variation = $variation;
	}

	/**
	 * forward the call based on the $action parameter
	 * API entry-point for the template chooser lightbox (from the editor)
	 *
	 * @param string $action
	 */
	public function api( $action ) {
		$method = 'api_' . $action;

		return call_user_func( array( $this, $method ) );
	}

	/**
	 * get the current template type (ribbon, post_footer etc)
	 *
	 * @param string|array $template_key optional array with TVE_LEADS_FIELD_TEMPLATE or directly the string key
	 *
	 * @return string
	 */
	public function type( $template_key = null ) {
		if ( is_array( $template_key ) ) {
			$template_key = $template_key[ TVE_LEADS_FIELD_TEMPLATE ];
		}

		list( $type, $key ) = explode( '|', $template_key ? $template_key : $this->variation[ TVE_LEADS_FIELD_TEMPLATE ] );

		return $type;
	}

	/**
	 * get the current template key (one_set, two_set etc)
	 *
	 * @param string|array $template_key optional array with TVE_LEADS_FIELD_TEMPLATE or directly the string key
	 *
	 * @return string
	 */
	public function key( $template_key = null ) {
		if ( is_array( $template_key ) ) {
			$template_key = $template_key[ TVE_LEADS_FIELD_TEMPLATE ];
		}

		list( $type, $key ) = explode( '|', $template_key ? $template_key : $this->variation[ TVE_LEADS_FIELD_TEMPLATE ] );

		return $key;
	}

	/**
	 * compose all the data that's required on a page after the content has been changed (editor content / CSS links / fonts etc)
	 *
	 * @param array $current_variation
	 *
	 * @return array
	 */
	public function state_data( $current_variation ) {
		global $variation;
		ob_start();
		$do_not_wrap = true;
		$variation   = $this->variation;
		include dirname( dirname( dirname( __FILE__ ) ) ) . '/editor-layouts/_form_states.php';
		$state_bar = ob_get_contents();
		ob_end_clean();

		$variation = $current_variation;
		$form_type = $this->type( $current_variation );

		$type = $this->type( $current_variation );

		$config = tve_leads_get_editor_template_config( $current_variation[ TVE_LEADS_FIELD_TEMPLATE ] );

		/**
		 * $css is an array with 2 keys fonts and css which need to be included in the page, if they do not already exist
		 */
		$asset_links = array();
		$assets      = tve_leads_enqueue_variation_scripts( $current_variation );
		foreach ( $assets['fonts'] as $_id => $_font ) {
			$asset_links[ $_id ] = $_font;
		}
		foreach ( $assets['css'] as $_id => $_css ) {
			if ( $_id == 'tve_leads_forms' ) {
				continue;
			}
			$asset_links[ $_id ] = $_css;
		}

		if ( $type == 'lightbox' || $type == 'screen_filler' ) {
			$asset_links['tve_lightbox_post'] = tve_editor_css() . '/editor_lightbox.css';
		}

		/**
		 * if current variation has parent_id = 0 then we should localize multi_step templates
		 */
		$get_multi_step = ! (bool) $current_variation['parent_id'];

		/**
		 * javascript global page data (that will overwrite parts of the global tve_path_params variable)
		 */
		$templates       = Thrive_Leads_Template_Manager::get_templates( $form_type, $get_multi_step );
		$javascript_data = array(
			'tl_templates'       => $templates['templates'],
			'tl_templates_error' => $templates['error'],
			'custom_post_data'   => array(
				'_key' => $current_variation['key'],
			),
			'tve_globals'        => isset( $variation[ TVE_LEADS_FIELD_GLOBALS ] ) ? $variation[ TVE_LEADS_FIELD_GLOBALS ] : array( 'e' => 1 ),
		);

		/**
		 * javascript global page data for the TL - editor part
		 */
		$editor_js = array(
			'_key'             => $current_variation['key'],
			'current_css'      => empty( $config['css'] ) ? '' : ( 'tve-leads-' . str_replace( '.css', '', $config['css'] ) ),
			'states'           => tve_leads_get_form_related_states( $current_variation ),
			'is_default_state' => empty( $current_variation['parent_id'] ),
		);

		/**
		 * remember the latest variation edited for this form_type / shortcode etc so that the next time the user will open the parent variation we can show him directly this child
		 *
		 */
		update_post_meta( $current_variation['post_parent'], 'tve_last_edit_state_' . $this->variation['key'], $current_variation['key'] );

		ob_start();
		tve_leads_output_custom_css( $current_variation, false );
		$custom_css = ob_get_contents();
		ob_end_clean();

		/**
		 * set this to request to be able to get the preview link by calling tcb_get_preview_url() which
		 * applies a filter implemented by TL on @see tve_leads_append_preview_link_args()
		 */
		$_REQUEST['_key'] = $current_variation['key'];
		$preview_link     = function_exists( 'tcb_get_preview_url' ) ? tcb_get_preview_url( $current_variation['post_parent'] ) : '';
		$preview_link     = str_replace( '#038;', '', $preview_link );

		/* Event Manager Actions - need a global $current_variation variable */
		$GLOBALS['current_variation'] = $current_variation;

		return array(
			'current_variation'   => $current_variation['key'],
			'state_bar'           => $state_bar,
			'needs_tt_wrapper'    => in_array( $form_type, array(
				'lightbox',
				'in_content',
				'post_footer',
				'widget',
				'shortcode'
			) ),
			'main_page_content'   => trim( $this->render_ajax_content( $current_variation ) ),
			'custom_css'          => $custom_css,
			'global_css'          => tve_get_shared_styles( '', '', false ),
			'css'                 => $asset_links,
			'body_class'          => $type === 'lightbox' ? 'tve-l-open tve-o-hidden tve-lightbox-page' : '',
			'tve_path_params'     => $javascript_data,
			'tve_leads_page_data' => $editor_js,
			'preview_link'        => $preview_link,
			'animation_options'   => tcb_event_manager_config(),
		);
	}

	/**
	 * render the html contents for a new variation to replace the previously edited one
	 *
	 * @param array $current_variation
	 *
	 * @return string the html
	 */
	public function render_ajax_content( $current_variation ) {
		global $variation;
		$variation = $current_variation;

		ob_start();
		$is_ajax_render = true;
		include dirname( dirname( dirname( __FILE__ ) ) ) . '/editor-layouts/' . $this->type( $current_variation ) . '.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	/**
	 * API-calls after this point
	 * --------------------------------------------------------------------
	 */
	/**
	 * choose a new template
	 */
	public function api_add() {
		if ( ! ( $state = $this->param( 'state' ) ) ) {
			exit();
		}

		global $tvedb;

		$child = $this->variation;
		unset( $child['key'] );
		/**
		 *
		 * UPDATE 19.11.2015 the user custom CSS is only saved in the parent state
		 */
		$child[ TVE_LEADS_FIELD_USER_CSS ] = '';
		$child['form_state']               = $this->param( 'state' );
		$child['state_order']              = $tvedb->variation_get_max_state_order( $this->variation['key'] ) + 1;
		$child['parent_id']                = $this->variation['key'];

		if ( $state == 'lightbox' ) { // we need to change the template to a lightbox and the get the default content for the lightbox
			$current_template = $child[ TVE_LEADS_FIELD_TEMPLATE ];
			list( $type, $tpl ) = explode( '|', $current_template );
			$original_config = tve_leads_get_editor_template_config( $current_template );
			$config          = tve_leads_get_editor_template_type_config( 'lightbox' );

			/**
			 * tries to find a similar lightbox for lightbox state
			 * ribbon|one_set for lightbox|one_set
			 */
			$found = false;
			foreach ( $config as $key => $data ) {
				if ( ! empty( $original_config['css'] ) && $data['css'] == $original_config['css'] ) {
					$child[ TVE_LEADS_FIELD_TEMPLATE ] = 'lightbox|' . $key;
					$found                             = true;
					break;
				}
			}

			if ( ! $found ) {
				$child[ TVE_LEADS_FIELD_TEMPLATE ] = 'lightbox|tcb2_blank';
			}

			/**
			 * this will populate the content of the lightbox with the default content based on the used template
			 */
			$child[ TVE_LEADS_FIELD_SAVED_CONTENT ] = tve_leads_get_editor_template_content( $child );
		}

		$this->regenerate_css_ids( $child );
		$child = tve_leads_save_form_variation( $child );

		return $this->state_data( $child );
	}

	/**
	 * duplicate a state
	 */
	public function api_duplicate() {
		if ( ! ( $id = $this->param( 'id' ) ) ) {
			return $this->state_data( $this->variation );
		}

		global $tvedb;

		$variation = tve_leads_get_form_variation( null, $id );
		$child     = $variation;
		if ( empty( $child['parent_id'] ) ) {
			/** if the default one gets duplicated, this means adding the new variation as a child of the main one */
			$child['parent_id'] = $variation['key'];
		}
		$child['form_state'] = empty( $child['form_state'] ) ? 'default' : $child['form_state'];
		unset( $child['key'] );
		/**
		 * UPDATE 19.11.2015 the user custom CSS is only saved in the parent state
		 */
		$child[ TVE_LEADS_FIELD_USER_CSS ] = '';
		$child['state_order']              = (int) $child['state_order'] + 1;

		$tvedb->variation_increment_state_order( $child['parent_id'], $child['state_order'] );

		$this->regenerate_css_ids( $child );
		$child = tve_leads_save_form_variation( $child );

		return $this->state_data( $child );
	}

	/**
	 * delete a state
	 */
	public function api_delete() {
		if ( ! ( $id = $this->param( 'id' ) ) ) {
			return $this->state_data( $this->variation );
		}

		$active_state = $this->param( 'active_state' );

		global $tvedb;

		$all        = tve_leads_get_form_child_states( $this->variation['key'] );
		$to_display = $this->variation;
		$previous   = $this->variation;

		/**
		 * handle variations like this, because we'll display the previous one if the user deletes the currently active state
		 */
		foreach ( $all as $v ) {
			if ( $active_state == $v['key'] ) {
				$active_state = $v;
			}
			/**
			 * make sure we don't delete the parent / default state for a variation
			 */
			if ( $v['key'] == $id && ! empty( $v['parent_id'] ) ) {
				$tvedb->delete_form_variation( $id );
				$to_display = $previous;
			}
			$previous = $v;
		}

		if ( ! is_array( $active_state ) ) {
			/**
			 * this means the default state is currently displayed
			 */
			$to_display = $this->variation;

		} elseif ( $active_state['key'] != $id ) {

			/**
			 * if we just deleted the active state, we need to display the previous one
			 */
			$to_display = $active_state;
		}

		return $this->state_data( $to_display );

	}

	/**
	 * display a state
	 *
	 * @return array
	 */
	public function api_display() {
		if ( ! ( $id = $this->param( 'id' ) ) || ! ( $variation = tve_leads_get_form_variation( null, $id ) ) ) {
			return $this->state_data( $this->variation );
		}

		return $this->state_data( $variation );
	}

	public function api_visibility() {
		$id      = $this->param( '_key' );
		$visible = $this->param( 'visible' );
		$visible = empty( $visible ) ? 1 : 0;

		$variation                                     = tve_leads_get_form_variation( null, $id );
		$variation[ TVE_LEADS_FIELD_STATE_VISIBILITY ] = $visible;

		/* update form visibility */
		tve_leads_save_form_variation( $variation );
		$state_data            = $this->state_data( $variation );
		$state_data['message'] = $visible ? __( 'Form will be visible for already subscribed users!', 'thrive-leads' ) : __( 'Form will be hidden for already subscribed users!', 'thrive-leads' );

		return $state_data;
	}

	/**
	 * Replace all CSS selectors used across the content of the $variation
	 * Also replace the CSS field with the replacement map
	 * Used in Add child state, Duplicate state
	 *
	 * Directly modify the variation
	 *
	 * @param array $variation
	 */
	public function regenerate_css_ids( &$variation ) {

		if ( empty( $variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] ) || empty( $variation[ TVE_LEADS_FIELD_INLINE_CSS ] ) ) {
			return;
		}
		/** if there is a previous style set on the lightbox wrapper we need to change that so the next state won't overwrite it*/
		if ( ! empty( $variation['globals'] ) && ! empty( $variation['globals']['content_css'] ) ) {
			/** replace it in the TCB fields(which are saved in the db) and also in the variation fields(no idea where those are used) */
			$old_custom_css_id                       = $variation['globals']['content_css'];
			$new_custom_css_id                       = 'tve-u-' . $this->generateRandomString();
			$variation[ TVE_LEADS_FIELD_INLINE_CSS ] = str_replace( $old_custom_css_id, $new_custom_css_id, $variation[ TVE_LEADS_FIELD_INLINE_CSS ] );
			$variation['globals']['content_css']     = $new_custom_css_id;
		}

		$decoration_id_pattern = '#data-clip-id="(.+?)"#s';
		preg_match_all( $decoration_id_pattern, $variation[ TVE_LEADS_FIELD_SAVED_CONTENT ], $decoration );
		if ( ! empty( $decoration[1] ) ) {
			$id_pairs = array();
			foreach ( $decoration[1] as $key => $id ) {
				$id_pairs[ $id ] = $this->generateRandomString();
			}
			$variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] = str_replace( array_keys( $id_pairs ), array_values( $id_pairs ), $variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] );
			$variation[ TVE_LEADS_FIELD_INLINE_CSS ]    = str_replace( array_keys( $id_pairs ), array_values( $id_pairs ), $variation[ TVE_LEADS_FIELD_INLINE_CSS ] );
		}

		$css_selector_pattern = '#data-css="tve-u-(.+?)"#s';

		$this->css_id_map                           = array();
		$variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] = preg_replace_callback( $css_selector_pattern, array(
			$this,
			'css_id_callback',
		), $variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] );
		if ( empty( $this->css_id_map ) ) {
			return;
		}

		$variation[ TVE_LEADS_FIELD_INLINE_CSS ] = str_replace( array_keys( $this->css_id_map ), array_values( $this->css_id_map ), $variation[ TVE_LEADS_FIELD_INLINE_CSS ] );
	}

	/**
	 * @param array $matches
	 *
	 * @return string
	 */
	public function css_id_callback( $matches ) {
		$old_id = 'tve-u-' . $matches[1];
		if ( ! isset( $this->css_id_map[ $old_id ] ) ) {
			$increment                   = count( $this->css_id_map );
			$new_id                      = 'tve-u-' . uniqid( $increment );
			$this->css_id_map[ $old_id ] = $new_id;
		}

		return 'data-css="' . $this->css_id_map[ $old_id ] . '"';
	}

	/**
	 * @param int $length
	 *
	 * @return string
	 */
	function generateRandomString( $length = 13 ) {
		$characters        = '0123456789abcdefghijklmnopqrstuvwxyz';
		$characters_length = strlen( $characters );
		$random_string     = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$random_string .= $characters[ rand( 0, $characters_length - 1 ) ];
		}

		return $random_string;
	}
}
