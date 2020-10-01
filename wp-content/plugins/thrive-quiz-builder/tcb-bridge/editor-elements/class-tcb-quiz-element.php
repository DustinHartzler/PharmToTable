<?php
/**
 * Created by PhpStorm.
 * User: ovidiu
 * Date: 6/23/2017
 * Time: 10:53 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Quiz_Element
 *
 * Quiz Builder Product - Allows inserting quizzes into pages
 */
class TCB_Quiz_Element extends TCB_Element_Abstract {
	private $quizzes = null;

	/**
	 * TCB_Quiz_Element constructor.
	 *
	 * @param string $tag
	 */
	public function __construct( $tag = '' ) {
		$this->quizzes = TQB_Quiz_Manager::get_quizzes();

		parent::__construct( $tag );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'thrive';
	}

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Quiz', Thrive_Quiz_Builder::T );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'quiz';
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrive-quiz-builder-shortcode'; //For backwards compatibility
	}

	/**
	 * This is only a placeholder element
	 *
	 * @return bool
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * Element HTML
	 *
	 * @return string
	 */
	public function html() {
		$content = '';
		ob_start();
		include tqb()->plugin_path( 'tcb-bridge/editor-layouts/elements/quiz.php' );
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Prepares the quizzes for change quiz control
	 *
	 * @return array
	 */
	private function get_quizes_for_component() {
		$return = array();
		if ( $this->quizzes === null ) {
			return $return;
		}

		foreach ( $this->quizzes as $quiz ) {
			$return[] = array(
				'name'  => $quiz->post_title,
				'value' => $quiz->ID,
			);
		}

		return $return;
	}

	/**
	 * Get quizzes scroll options
	 *
	 * @return array
	 */
	private function _get_quizzes_scroll_options() {

		$result = array();

		if ( ! is_array( $this->quizzes ) ) {
			return $result;
		}

		foreach ( $this->quizzes as $quiz ) {
			$result[] = array(
				'id'     => $quiz->ID,
				'scroll' => TQB_Post_meta::get_quiz_scroll_settings_meta( $quiz->ID ),
			);
		}

		return $result;
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		return array(
			'quiz'             => array(
				'config' => array(
					'change_quiz' => array(
						'config'  => array(
							'name'        => __( 'Change quiz', Thrive_Quiz_Builder::T ),
							'label_col_x' => 4,
							'options'     => $this->get_quizes_for_component(),
						),
						'extends' => 'Select',
					),
					'quiz_scroll' => array(
						'config'     => array(
							'name'    => '',
							'label'   => __( 'Enable quiz scroll', Thrive_Quiz_Builder::T ),
							'default' => true,
							'options' => $this->_get_quizzes_scroll_options(),
						),
						'css_suffix' => '',
						'css_prefix' => '',
						'extends'    => 'Switch',
					),
				),
			),
			'typography'       => array( 'hidden' => true ),
			'layout'           => array( 'hidden' => true ),
			'borders'          => array( 'hidden' => true ),
			'animation'        => array( 'hidden' => true ),
			'background'       => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),
			'shadow'           => array( 'hidden' => true ),
		);
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return $this->get_thrive_integrations_label();
	}
}
