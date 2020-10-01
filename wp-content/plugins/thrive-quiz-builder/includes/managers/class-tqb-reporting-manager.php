<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/**
 * Class TQB_Reporting_Manager
 *
 * Handles Reporting operations
 */
class TQB_Reporting_Manager {

	/**
	 * @var TQB_Reporting_Manager $instance
	 */
	protected $quiz_id;

	protected $report_type;

	protected $tqbdb;

	protected $tgedb;

	/**
	 * TQB_Reporting_Manager constructor.
	 */
	public function __construct( $quiz_id = null, $report_type = null ) {
		$this->quiz_id     = $quiz_id;
		$this->report_type = $report_type;

		global $tqbdb;
		$this->tqbdb = $tqbdb;

		global $tgedb;
		$this->tgedb = $tgedb;
	}

	public function get_report( $filters = array() ) {
		$data = false;
		switch ( $this->report_type ) {
			case 'completions':
				$data = $this->get_completions_report( $filters );
				break;
			case 'flow':
				$data = $this->get_flow_report();
				break;
			case 'questions':
				$data = $this->get_questions_report();
				break;
			case 'users':
				$data = $this->get_users_report();
				break;
		}

		return $data;
	}

	public function get_completions_report( $filters = array() ) {

		if ( empty( $filters['interval'] ) ) {
			$filters['interval'] = 'day';
		}

		if ( empty( $filters['date'] ) ) {
			$filters['date'] = Thrive_Quiz_Builder::TQB_LAST_7_DAYS;
		}

		$data = $this->tqbdb->get_quiz_completion_report( $this->quiz_id, $filters );

		return array(
			'chart_data'   => $data['graph_quiz'],
			'chart_x_axis' => $data['intervals'],
			'chart_y_axis' => __( 'Completions', Thrive_Quiz_Builder::T ),
			'quiz_id'      => $this->quiz_id,
			'date'         => $filters['date'],
			'interval'     => $filters['interval'],
			'quiz_list'    => $data['table_quizzes'],
			'since'        => $this->get_report_collection_data(),
		);
	}

	public function get_flow_report() {
		$structure_manager = new TQB_Structure_Manager( $this->quiz_id );
		$structure         = $structure_manager->get_quiz_structure_meta();
		if ( empty( $structure['ID'] ) ) {
			return false;
		}

		$data['since'] = $this->get_flow_report_collection_data();

		$default_values = array(
			Thrive_Quiz_Builder::TQB_IMPRESSION => 0,
			Thrive_Quiz_Builder::TQB_CONVERSION => 0,
			Thrive_Quiz_Builder::TQB_SKIP_OPTIN => 0,
		);

		if ( is_numeric( $structure['splash'] ) ) {
			$data['splash'] = $this->get_flow_splash( $structure['splash'], $data['since']['date'] );
		} else {
			$data['splash'] = false;
		}

		$data['qna']   = $this->get_flow_qna( $data['since']['date'] );
		$data['users'] = isset( $data['splash'][ Thrive_Quiz_Builder::TQB_IMPRESSION ] ) ? $data['splash'][ Thrive_Quiz_Builder::TQB_IMPRESSION ] : null;
		$data['users'] = isset( $data['users'] ) ? $data['users'] : $data['qna'][ Thrive_Quiz_Builder::TQB_IMPRESSION ];

		if ( is_numeric( $structure['optin'] ) ) {
			$data['optin']             = $this->get_flow_optin( $structure['optin'], $data['since']['date'] );
			$data['optin_subscribers'] = $this->get_page_subscribers( $structure['optin'], $data['since']['date'] );
		} elseif ( $structure['optin'] ) {
			$data['optin']             = $default_values;
			$data['optin_subscribers'] = 0;
		} else {
			$data['optin']             = false;
			$data['optin_subscribers'] = 0;
		}

		if ( is_numeric( $structure['results'] ) ) {
			$data['results']               = $this->get_flow_results( $structure['results'], $data['since']['date'] );
			$data['results_subscribers']   = $this->get_page_subscribers( $structure['results'], $data['since']['date'] );
			$data['results_social_shares'] = $this->get_page_social_shares( $structure['results'], $data['since']['date'] );
		} else {
			$data['results']               = $default_values;
			$data['results_subscribers']   = 0;
			$data['results_social_shares'] = 0;
		}

		$results_page         = TQB_Structure_Manager::make_page( $structure['results'] );
		$data['results_page'] = $results_page->to_json();

		$data['completions'] = $this->tqbdb->get_completed_quiz_count( $this->quiz_id, $data['since']['date'] );

		$data['quiz_id'] = $this->quiz_id;

		return $data;
	}

	public function get_page_subscribers( $id, $last_modified ) {

		return $this->tqbdb->get_page_subscribers( $id, $last_modified );
	}

	public function get_page_social_shares( $id, $last_modified ) {

		return $this->tqbdb->get_page_social_shares( $id, $last_modified );
	}

	public function get_flow_splash( $id, $last_modified ) {

		return $this->tqbdb->get_flow_data( $id, $last_modified );
	}

	public function get_flow_qna( $last_modified ) {
		return $this->tqbdb->get_flow_data( $this->quiz_id, $last_modified );
	}

	public function get_flow_optin( $id, $last_modified ) {
		return $this->tqbdb->get_flow_data( $id, $last_modified );
	}

	public function get_flow_results( $id, $last_modified ) {
		return $this->tqbdb->get_flow_data( $id, $last_modified );
	}

	public function get_questions_report() {

		$data['questions'] = $this->tqbdb->get_questions_report_data( $this->quiz_id );
		$data['since']     = $this->get_report_collection_data();

		return $data;
	}

	/**
	 * Get all questions with formated answers for a quiz [csv download data based on params]
	 *
	 * @return array
	 */
	public function get_full_csv_questions_report() {

		/**
		 * key => val represents [col.field AS alias]
		 */
		$filters = array(
			'columns'  => array(
				'IFNULL(COUNT( user_answer.id ), 0)' => 'answer_count',
				'answer.question_id'                 => 'question_id',
				'answer.id'                          => 'answer_id',
				'answer.text'                        => 'answer_text',
				'answer.image'                       => 'answer_image',
				'question.id'                        => 'q_id',
				'question.text'                      => 'question_text',
				'question.views'                     => 'question_views',
				'question.q_type'                    => 'question_type',
				'user.id'                            => 'uid',
			),
			'group_by' => array(
				'answer.question_id',
				'answer.id',
				'user.id',
			),
		);

		return $this->tqbdb->get_full_questions_report_data( $this->quiz_id, $filters );
	}

	public function get_users_report( $params = array() ) {

		if ( empty( $params['per_page'] ) ) {
			$params['per_page'] = 10;
		}
		if ( empty( $params['offset'] ) ) {
			$params['offset'] = 0;
		}
		$result['since'] = $this->get_report_collection_data();

		$result['data'] = $this->tqbdb->get_quiz_users( $this->quiz_id, array(
			'per_page' => $params['per_page'],
			'offset'   => $params['offset'],
		) );

		if ( empty( $result['data'] ) ) {
			return $result;
		}

		$quiz_type = TQB_Post_meta::get_quiz_type_meta( $this->quiz_id );

		if ( empty( $quiz_type ) ) {
			return false;
		}
		$timezone_diff = current_time( 'timestamp' ) - time();
		foreach ( $result['data'] as $key => $item ) {
			$result['data'][ $key ]->date_started = date( 'Y-m-d H:i:s', strtotime( $result['data'][ $key ]->date_started ) + $timezone_diff );
			$result['data'][ $key ]->number       = $params['offset'] + $key + 1;

			$result['data'][ $key ]->points = TQB_Quiz_Manager::get_user_points( $item->random_identifier, $item->quiz_id );
			if ( $result['data'][ $key ]->points === '-' ) {
				$points                         = $this->tqbdb->calculate_user_points( $item->random_identifier, $item->quiz_id );
				$result_explicit                = $this->tqbdb->get_explicit_result( $points );
				$result['data'][ $key ]->points = $result_explicit;
				if ( empty( $result['data'][ $key ]->points ) ) {
					$result['data'][ $key ]->points = '-';
				}
			}
		}
		$result['total_items']  = $this->tqbdb->get_quiz_users_count( $this->quiz_id, false );
		$result['per_page']     = $params['per_page'];
		$result['offset']       = $params['offset'];
		$result['quiz_id']      = $this->quiz_id;
		$result['total_pages']  = ceil( $result['total_items'] / $result['per_page'] );
		$result['current_page'] = floor( $params['offset'] / $result['per_page'] ) + 1;

		return $result;
	}

	public function get_report_collection_data() {
		$structure_manager = new TQB_Structure_Manager( $this->quiz_id );
		$structure_meta    = $structure_manager->get_quiz_structure_meta();

		if ( empty( $structure_meta['last_reset'] ) ) {
			$quiz         = get_post( $this->quiz_id );
			$data['text'] = __( 'Data collected since: ', Thrive_Quiz_Builder::T );
			$data['date'] = $quiz->post_date;
		} else {
			$data['text'] = __( 'Data collected since latest reset: ', Thrive_Quiz_Builder::T );
			$data['date'] = date( 'Y-m-d H:i:s', $structure_meta['last_reset'] );
		}

		return $data;
	}

	public function get_flow_report_collection_data() {
		$structure_manager = new TQB_Structure_Manager( $this->quiz_id );
		$structure_meta    = $structure_manager->get_quiz_structure_meta();
		$data              = array();

		$structure_meta['last_reset']    = empty( $structure_meta['last_reset'] ) ? 0 : $structure_meta['last_reset'];
		$structure_meta['last_modified'] = empty( $structure_meta['last_modified'] ) ? 0 : $structure_meta['last_modified'];

		if ( empty( $structure_meta['last_reset'] ) && empty( $structure_meta['last_modified'] ) ) {
			$quiz = get_post( $this->quiz_id );

			$data['text'] = __( 'Data collected since latest saved quiz structure: ', Thrive_Quiz_Builder::T );
			$data['date'] = $quiz->post_date;

			return $data;
		}

		if ( $structure_meta['last_reset'] > $structure_meta['last_modified'] ) {
			$data['text'] = __( 'Data collected since latest reset: ', Thrive_Quiz_Builder::T );
			$data['date'] = date( 'Y-m-d H:i:s', $structure_meta['last_reset'] );
		} else {
			$data['text'] = __( 'Data collected since latest  saved quiz structure: ', Thrive_Quiz_Builder::T );
			$data['date'] = date( 'Y-m-d H:i:s', $structure_meta['last_modified'] );
		}

		return $data;
	}

	public function get_users_answers( $user_id ) {
		$questions    = $this->tgedb->get_quiz_questions( array( 'quiz_id' => $this->quiz_id ), false );
		$user_answers = $this->tqbdb->get_user_answers( array( 'quiz_id' => $this->quiz_id, 'user_id' => $user_id ) );

		foreach ( $questions as $qkey => $question ) {
			$questions[ $qkey ]['answers'] = $this->tgedb->get_answers( array( 'question_id' => $question['id'] ), false );
			foreach ( $questions[ $qkey ]['answers'] as $i => $answer ) {
				foreach ( $user_answers as $key => $user_answer ) {
					if ( $user_answer->answer_id == $answer['id'] ) {
						$questions[ $qkey ]['answers'][ $i ]['chosen']      = true;
						$questions[ $qkey ]['answers'][ $i ]['answer_text'] = $user_answer->answer_text;
					}
				}
			}
		}

		return $questions;
	}

	/**
	 * Read from DB all the tags for answers chosen by user
	 *
	 * @param int $user_id
	 *
	 * @return array
	 */
	public function get_users_chosen_tags( $user_id ) {
		$tags = array();

		$user_answers = $this->tqbdb->get_detailed_user_answers_( array( 'quiz_id' => $this->quiz_id, 'user_id' => $user_id ) );

		if ( ! empty( $user_answers ) && is_array( $user_answers ) ) {
			foreach ( $user_answers as $answer ) {
				$answer_tags = ! empty( $answer['tags'] ) ? explode( ',', $answer['tags'] ) : array();
				$answer_tags = array_map( 'trim', $answer_tags );
				$tags        = array_merge( $tags, $answer_tags );
			}
		}

		return $tags;
	}
}

