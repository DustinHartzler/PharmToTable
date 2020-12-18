<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-quiz-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TQB_Shortcodes {

	protected static $quizzes = array();

	public static function init() {
		add_shortcode( 'tqb_quiz', array( 'TQB_Shortcodes', 'render_quiz_shortcode' ) );
		add_shortcode( 'tqb_quiz_options', array( 'TQB_Shortcodes', 'tqb_quiz_options' ) );
		add_shortcode( 'tqb_quiz_result', array( 'TQB_Shortcodes', 'render_quiz_result' ) );
	}

	/**
	 * Render quiz result content
	 *
	 * @param array $attributes
	 *
	 * @return int|string
	 */
	public static function render_quiz_result( $attributes ) {

		if ( ! isset( $attributes['result_type'] ) || ! isset( $attributes['data'] ) ) {
			return ! empty( $_REQUEST[ Thrive_Quiz_Builder::VARIATION_QUERY_KEY_NAME ] ) ? '' : 0;
		}

		global $tqbdb;

		$result = '';
		$data   = json_decode( $attributes['data'], true );

		if ( ! empty( $data['points']['explicit'] ) ) {
			$category = str_replace( Thrive_Quiz_Builder::COMMA_PLACEHOLDER, "'", $data['points']['explicit'] );

			$data['points']['explicit'] = addslashes( $category );
		}

		$score = $tqbdb->get_explicit_result( $data['points'] );

		if ( 'personality' === $data['quiz_type'] || 'right_wrong' === $data['quiz_type'] ) {
			return $score;
		}

		$score = str_replace( '%', '', $score );

		switch ( $attributes['result_type'] ) {

			case 'one_decimal':
				$result = round( $score, 1 );

				break;

			case 'two_decimal':
				$result = round( $score, 2 );

				break;

			case 'whole_number':
			case 'default':
				$result = round( $score );

				break;
		}

		if ( 'percentage' === $data['quiz_type'] ) {
			$result = $result . '%';
		}

		return $result;
	}

	public static function render_quiz_shortcode( $attributes ) {

		/**
		 * Make sure we enqueue only once the frontend scripts
		 * - in this way we dont overwrite the TQB_Front localization
		 */
		if ( ! defined( 'TQB_IN_SHORTCODE' ) ) {
			Thrive_Quiz_Builder::enqueue_frontend_scripts( $attributes['id'] );
		}

		defined( 'TQB_IN_SHORTCODE' ) || define( 'TQB_IN_SHORTCODE', true );

		$quiz_id   = $attributes['id'];
		$unique_id = 'tqb-' . uniqid();

		$style = TQB_Post_meta::get_quiz_style_meta( $quiz_id );
		$html  = '<div class="tve_flt" id="tve_editor">
			<div class="tqb-shortcode-wrapper" id="tqb-shortcode-wrapper-' . $quiz_id . '-' . $unique_id . '" data-quiz-id="' . $quiz_id . '" data-unique="' . $unique_id . '" >
				<div class="tqb-loading-overlay tqb-template-overlay-style-' . $style . '">
					<div class="tqb-loading-bullets"></div>
				</div>
				<div class="tqb-frontend-error-message"></div>
				<div class="tqb-shortcode-old-content"></div>
				<div class="tqb-shortcode-new-content tqb-template-style-' . $style . '"></div>
			</div></div>';

		TQB_Quiz_Manager::run_shortcodes_on_quiz_content( $quiz_id );

		if ( is_editor_page() || ( defined( 'DOING_AJAX' ) && DOING_AJAX && ! empty( $_REQUEST['tqb_in_tcb_editor'] ) ) ) {
			$html = str_replace( array( 'id="tve_editor"' ), '', $html );
			$html = '<div class="thrive-shortcode-html"><div>' . $html . '</div><style>.tqb-shortcode-wrapper{pointer-events: none;}</style></div>';
		}

		return $html;
	}

	public static function tqb_quiz_options( $args ) {
		return '#';
	}

	/**
	 * Render backbone templates
	 */
	public static function render_backbone_templates() {
		$templates = tve_dash_get_backbone_templates( tqb()->plugin_path( 'includes/frontend/views/templates' ), 'templates' );

		$templates = apply_filters( 'tqb_backbone_frontend_templates', $templates );

		tve_dash_output_backbone_templates( $templates );
	}
}

TQB_Shortcodes::init();

