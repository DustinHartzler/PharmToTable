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
 * Class TCB_Symbol_Template
 */
class TCB_Symbol_Template {

	/**
	 * Stores symbol types that contain states
	 *
	 * @var string[]
	 */
	public static $symbol_with_states = array( 'header' );

	/**
	 * Render the symbol content
	 *
	 * @param array $config
	 * @param bool  $do_shortcodes
	 *
	 * @return mixed|string
	 */
	public static function render_content( $config = array(), $do_shortcodes = false ) {
		static::enter_symbol_render();

		$symbol_id = ( ! empty( $config ) && isset( $config['id'] ) ) ? $config['id'] : get_the_ID();
		$content   = self::content( $symbol_id );

		/* prepare Events configuration */
		tve_parse_events( $content );

		/**
		 * Filter that allows skipping `do_shortcode` in various cases. Example: when exporting a symbol, do_shortcode should NOT be called,
		 * even though `wp_doing_ajax() === true`
		 *
		 * @param bool  $do_shortcodes initial value
		 * @param int   $symbol_id     current symbol ID
		 * @param array $config        configuration object passed to the method
		 *
		 * @return bool whether or not it should execute the shortcode functions
		 */
		$do_shortcodes = apply_filters( 'tcb_symbol_do_shortcodes', wp_doing_ajax() || $do_shortcodes, $symbol_id, $config );

		if ( $do_shortcodes ) {
			$content = shortcode_unautop( $content );

			$GLOBALS['symbol_id'] = $symbol_id;
			$content              = do_shortcode( $content );
			unset( $GLOBALS['symbol_id'] );

			//apply thrive shortcodes
			$keep_config = isset( $config['tve_shortcode_config'] ) ? $config['tve_shortcode_config'] : true;
			$content     = tve_thrive_shortcodes( $content, $keep_config );

			/* render the content added through WP Editor (element: "WordPress Content") */
			$content = tve_do_wp_shortcodes( $content, is_editor_page() );
		}
		/**
		 * This only needs to be executed on frontend. Do not execute it in the editor page or when ajax-loading the symbols in the editor
		 */
		if ( ! is_editor_page() && ! wp_doing_ajax() ) {
			$content = tve_restore_script_tags( $content );

			$content = tve_get_shared_styles( $content ) . $content;

			/**
			 * IF yoast is active reset printed styles because they mess up the post queries
			 */
			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
				$GLOBALS['tve_parsed_shared_styles'] = array();
			}
			//if it has custom icons make sure that font family is loaded
			if ( tve_get_post_meta( $symbol_id, 'thrive_icon_pack' ) ) {
				TCB_Icon_Manager::enqueue_icon_pack();
			}
		}

		$content = apply_filters( 'tcb_symbol_template', $content );

		$content = preg_replace( '!\s+!', ' ', $content );

		static::exit_symbol_render();

		return $content;
	}

	/**
	 * Include the start of the html content
	 */
	public static function body_open() {
		include TVE_TCB_ROOT_PATH . 'inc/views/symbols/symbol-body-open.php';
	}

	/**
	 * Include the end of the html content
	 */
	public static function body_close() {
		include TVE_TCB_ROOT_PATH . 'inc/views/symbols/symbol-body-close.php';
	}

	/**
	 * Get the content from the symbol
	 *
	 * @param int $symbol_id
	 *
	 * @return mixed|string
	 */
	public static function content( $symbol_id ) {
		$content = get_post_meta( (int) $symbol_id, 'tve_updated_post', true );

		return apply_filters( 'tcb_symbol_content', $content );
	}

	/**
	 * Get css for symbol
	 *
	 * @param $config
	 *
	 * @return string
	 */
	public static function tcb_symbol_get_css( $config ) {
		$symbol_id = ( ! empty( $config ) && isset( $config['id'] ) ) ? $config['id'] : 0;

		$css = "<style class='tve-symbol-custom-style'>" . static::css( $symbol_id ) . '</style>';

		$lightspeed = \TCB\Lightspeed\Css::get_instance( $symbol_id );

		if ( $lightspeed->should_load_optimized_styles() ) {
			$css = $lightspeed->get_optimized_styles() . $css;
		} else {
			\TCB\Lightspeed\Css::enqueue_flat();
		}

		return $css;
	}

	/**
	 * Get the css for a symbol
	 *
	 * @param int $id
	 *
	 * @return mixed|string
	 */
	public static function css( $id ) {
		$css = trim( get_post_meta( (int) $id, 'tve_custom_css', true ) );

		$css = TCB\Lightspeed\Fonts::parse_google_fonts( $css );

		/* If we want to change the symbol css just before is being inserted in the page */
		$css = apply_filters( 'tcb_symbol_css_before', $css, $id );

		return tve_prepare_global_variables_for_front( $css );
	}

	/**
	 * @param $symbol_type
	 *
	 * @return string
	 */
	public static function symbol_state_class( $symbol_type ) {
		$cls = '';

		if ( in_array( $symbol_type, self::$symbol_with_states, true ) ) {
			$cls = 'tve-default-state';
		}

		return $cls;
	}

	/**
	 * Render symbol shortcode content
	 *
	 * @param array   $config
	 * @param boolean $wrap
	 *
	 * @return string
	 */
	public static function symbol_render_shortcode( $config, $wrap = false ) {
		$content = '';

		if ( ! empty( $config['id'] ) ) {
			$symbol_id = $config['id'];

			$post = get_post( $symbol_id );

			if ( $post instanceof WP_Post && $post->post_status === 'publish' ) {
				$content    = self::render_content( $config, $wrap );
				$css        = self::tcb_symbol_get_css( $config );
				$type       = substr( TCB_Symbols_Taxonomy::get_symbol_type( $symbol_id ), 0, - 1 );
				$js_modules = '';

				if ( TCB_Utils::is_rest() || wp_doing_ajax() ) {
					/* we should return this inline when we retrieve the symbol with ajax */
					$js_modules = \TCB\Lightspeed\JS::get_instance( $symbol_id )->load_modules( true );
				} else {
					\TCB\Lightspeed\JS::get_instance( $symbol_id )->enqueue_scripts();
				}

				/**
				 * forcing this type allows knowing better whether is a gutenberg block
				 */
				if ( strpos( $type, 'gutenberg' ) !== false ) {
					$type = 'gutenberg_block';
				}

				$shortcode_class = in_array( $type, self::$symbol_with_states, true ) ? 'tve-default-state' : '';
				$name            = is_editor_page_raw() ? ' data-name="' . esc_attr( $post->post_title ) . '"' : '';

				$content = '<div class="thrive-shortcode-html thrive-symbol-shortcode ' . $shortcode_class . '"' . $name . self::data_attr( $symbol_id ) . '>' . $css . $js_modules . $content . '</div>';

				if ( $wrap ) {

					$content = TCB_Utils::wrap_content( $content, 'div', "thrive-$type",
						array( 'thrv_wrapper', 'thrv_symbol', 'thrive-shortcode', "thrv_$type", 'tve_no_drag', "thrv_symbol_$symbol_id", self::symbol_state_class( $type ) ),
						array(
							'data-id'            => $symbol_id,
							'data-selector'      => ".thrv_symbol_$symbol_id",
							'data-shortcode'     => "thrive_$type",
							'data-tcb-elem-type' => $type,
							'data-element-name'  => ucfirst( $type ),
						) );
				}
			}
		}

		return $content;
	}

	/**
	 * Check if a symbol is rendering right now
	 *
	 * @return bool
	 */
	public static function is_outside_symbol_render() {
		return empty( $GLOBALS[ TCB_RENDERING_SYMBOL ] );
	}

	/**
	 * Mark that we started rendering a symbol
	 */
	public static function enter_symbol_render() {
		$GLOBALS[ TCB_RENDERING_SYMBOL ] = true;
	}

	/**
	 * Mark that we finished rendering a symbol
	 */
	public static function exit_symbol_render() {
		$GLOBALS[ TCB_RENDERING_SYMBOL ] = false;
	}

	/**
	 * Return class for symbol element on it's page
	 *
	 * @return array
	 */
	public static function get_edit_symbol_vars() {

		$type = TCB_Symbols_Taxonomy::get_symbol_type( get_the_ID() );

		$is_hf = $type === 'headers' || $type === 'footers';

		if ( $is_hf ) {
			$type = substr( $type, 0, - 1 );
		}

		return array(
			'css_class' => $is_hf ? 'thrv_' . $type : '',
			'type'      => $type,
		);
	}

	public static function data_attr( $symbol_id ) {
		$globals = get_post_meta( $symbol_id, 'tve_globals', true );
		if ( empty( $globals ) ) {
			$globals = array();
		}
		/**
		 * backwards compat stuff
		 */
		if ( ! isset( $globals['data-tve-scroll'] ) ) {
			$scroll_behaviour = get_post_meta( $symbol_id, 'tcb_scroll_behaviour', true );
			if ( $scroll_behaviour ) {
				if ( $scroll_behaviour !== 'static' ) {
					$globals['data-tve-scroll'] = json_encode( array(
						'disabled' => array(),
						'mode'     => $scroll_behaviour === 'scroll_up' ? 'appear' : 'sticky',
					) );
					update_post_meta( $symbol_id, 'tve_globals', $globals );
				}
				delete_post_meta( $symbol_id, 'tcb_scroll_behaviour' );
			}
		}

		$attr = ' data-symbol-id="' . (int) $symbol_id . '"';

		foreach ( $globals as $k => $value ) {
			if ( strpos( $k, 'data-' ) === 0 ) {
				$attr .= ' ' . $k . '="' . esc_attr( $value ) . '"';
			}
		}

		return $attr;
	}
}
