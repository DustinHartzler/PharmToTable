<?php
// phpcs:ignoreFile

namespace AutomateWoo;

use AutomateWoo\Workflows\Factory;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Admin_Workflow_List
 * @since 2.6.1
 */
class Admin_Workflow_List {

	/**
	 * Constructor
	 */
	function __construct() {

		add_filter( 'manage_posts_columns' , [ $this, 'columns'] );
		add_filter( 'manage_posts_custom_column' , [ $this, 'column_data'], 10 , 2 );
		add_filter( 'bulk_actions-edit-aw_workflow' , [ $this, 'bulk_actions' ], 10 , 2 );
		add_filter( 'post_row_actions' , [ $this, 'row_actions' ], 10 , 2 );
		add_filter( 'request', [ $this, 'filter_request_query_vars' ] );
		add_filter( 'views_edit-aw_workflow', [ $this, 'filter_views' ] );

		$this->statuses();
	}


	/**
	 * @param $columns
	 * @return array
	 */
	function columns( $columns ) {

		unset( $columns['date'] );

		$columns['timing'] = __( 'Timing', 'automatewoo' );
		$columns['times_run'] = __( 'Run Count', 'automatewoo' );
		$columns['queued'] = __( 'Queue Count', 'automatewoo' );
		$columns['aw_status_toggle'] = '';

		return $columns;
	}


	/**
	 * @param $column
	 * @param $post_id
	 */
	function column_data( $column, $post_id ) {
		$workflow = Factory::get( $post_id );

		if ( ! $workflow )
			return;

		switch ( $column ) {

			case 'timing':
				echo $this->get_timing_text( $workflow );
				break;

			case 'times_run':
				if ( $count = $workflow->get_times_run() ) {
					echo '<a href="' . add_query_arg( '_workflow', $workflow->get_id(), Admin::page_url('logs') ) . '">' . $count . '</a>';
				}
				else {
					echo '-';
				}
				break;

			case 'queued':
				if ( $count = $workflow->get_current_queue_count() ) {
					echo '<a href="' . add_query_arg( '_workflow', $workflow->get_id(), Admin::page_url('queue') ) . '">' . $count . '</a>';
				}
				else {
					echo '-';
				}
				break;

			case 'aw_status_toggle':
				if ( 'manual' === $workflow->get_type() ) {
					$url = Admin::page_url( 'manual-workflow-runner', $workflow->get_id() );
					printf(
						'<a href="%s" class="button button-primary alignright">%s</a>',
						esc_url( $url ),
						esc_html__( 'Run', 'automatewoo' )
					);
				} else {
					printf(
						'<button type="button" class="%s" data-workflow-id="%s" data-aw-switch="%s">%s</button>',
						'aw-switch js-toggle-workflow-status',
						esc_attr( $workflow->get_id() ),
						esc_attr( $workflow->is_active() ? 'on' : 'off' ),
						esc_html__( 'Toggle Status', 'automatewoo' )
					);
				}
				break;

		}
	}
	

	/**
	 * Tweak workflow statuses
	 */
	function statuses() {

		global $wp_post_statuses;

		// rename published
		$wp_post_statuses['publish']->label_count = _n_noop( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>', 'automatewoo' );

		$trash = $wp_post_statuses['trash'];
		unset( $wp_post_statuses['trash'] );
		$wp_post_statuses['trash'] = $trash;
	}


	/**
	 * @param $actions
	 * @return mixed
	 */
	function bulk_actions( $actions ) {
		unset($actions['edit']);
		return $actions;
	}


	/**
	 * @param $actions
	 * @return mixed
	 */
	function row_actions( $actions ) {
		unset($actions['inline hide-if-no-js']);
		return $actions;
	}


	/**
	 * @param Workflow $workflow
	 * @return string
	 */
	function get_timing_text( $workflow ) {

		if ( $trigger = $workflow->get_trigger() ) {

			if ( $trigger::SUPPORTS_CUSTOM_TIME_OF_DAY ) {
				$time = $workflow->get_trigger_option('time');

				if ( $time ) {
					$time_string = Format::time_of_day( $workflow->get_trigger_option('time') );
				}
				else {
					$time_string = _x( 'midnight', 'timing option', 'automatewoo' );
				}

				return sprintf( _x( 'Daily at %s', 'timing option', 'automatewoo' ), '<b>' . $time_string . '</b>' );
			}

			if ( ! $trigger::SUPPORTS_QUEUING ) {
				return _x( 'Custom', 'timing option', 'automatewoo' );
			}

		}

		switch( $workflow->get_timing_type() ) {

			case 'immediately':
				return _x( 'Immediate', 'timing option', 'automatewoo' );
				break;
			case 'delayed':
				return sprintf( _x( 'Wait for <b>%s</b>', 'timing option', 'automatewoo' ), $this->get_delay_unit_text( $workflow ) );
				break;
			case 'scheduled':

				$time = $workflow->get_scheduled_time();
				$days = $workflow->get_scheduled_days();

				$text = sprintf( _x( 'Schedule for <b>%s</b>', 'timing option', 'automatewoo' ), Format::time_of_day( $time ) );

				if ( $days ) {
					$text .= sprintf( _x( ' on  <b>%s</b>', 'timing option', 'automatewoo' ), $this->get_weekday_text( $days ) );
				}

				if ( $workflow->get_timing_delay() ) {
					$text .= sprintf( _x( ' after waiting <b>%s</b>', 'timing option', 'automatewoo' ), $this->get_delay_unit_text( $workflow ) );
				}

				return $text;
				break;
			case 'fixed':
				$date = $workflow->get_fixed_time();
				if ( $date ) {
					return sprintf( _x( 'Fixed at %s', 'timing option', 'automatewoo'), Format::datetime( $workflow->get_fixed_time() ) );
				}
				break;
			case 'datetime':
				return __( 'Schedule with a variable', 'automatewoo' );
				break;

		}

		return '-';
	}


	/**
	 * @param $days
	 * @return string
	 */
	function get_weekday_text( $days ) {

		$string = '';

		if ( array_diff( $days, [ 1,2,3,4,5 ] ) === [] && count( $days ) === 5 ) {
			$string .= __( 'Weekdays', 'automatewoo' );
		}
		elseif ( array_diff( $days, [ 6,7 ] ) === [] && count( $days ) === 2 ) {
			$string .= __( 'Weekends', 'automatewoo' );
		}
		else {
			$names = array_map( ['AutomateWoo\Format', 'weekday' ], $days );

			if ( count( $names ) > 1 ) {
				$last = array_pop( $names );
				$string .= implode( ', ', $names );
				$string .= _x( ' or ', 'day', 'automatewoo' ) . $last;
			}
			else {
				$string .= current( $names );
			}

		}

		return $string;
	}


	/**
	 * @param Workflow $workflow
	 * @return string
	 */
	function get_delay_unit_text( $workflow  ) {

		$unit = $workflow->get_timing_delay_unit();
		$number = $workflow->get_timing_delay_number();

		switch( $unit ) {
			case 'h':
				$unit_text = _n( '%s hour', '%s hours', $number, 'automatewoo' );
				break;
			case 'm':
				$unit_text = _n( '%s minute', '%s minutes', $number, 'automatewoo' );
				break;
			case 'd':
				$unit_text = _n( '%s day', '%s days', $number, 'automatewoo' );
				break;
			case 'w':
				$unit_text = _n( '%s week', '%s weeks', $number, 'automatewoo' );
				break;
			case 'month':
				$unit_text = _n( '%s month', '%s months', $number, 'automatewoo' );
				break;
			default:
				return '';
		}
		return sprintf( $unit_text, $number );
	}

	/**
	 * Is manual view?
	 *
	 * @since 5.0.0
	 *
	 * @return bool
	 */
	public function is_manual_view() {
		return (bool) aw_get_url_var( 'filter_manual' );
	}

	/**
	 * Filter workflow list main request query vars.
	 *
	 * @param array $query_vars
	 *
	 * @return array
	 */
	public function filter_request_query_vars( $query_vars ) {
		$is_all_view = empty( $query_vars['post_status'] );

		// Include disabled workflows in all view
		if ( $is_all_view ) {
			$query_vars['post_status'] = [ 'publish', 'aw-disabled' ];
		}

		if ( $this->is_manual_view() ) {
			$query_vars['meta_query'] = [
				[
					'key'   => 'type',
					'value' => 'manual',
				],
			];
		}

		return $query_vars;
	}

	/**
	 * Filter views on the workflow list table.
	 *
	 * @since 5.0.0
	 *
	 * @param array $views
	 *
	 * @return array
	 */
	public function filter_views( $views ) {
		$url = remove_query_arg( 'post_status', add_query_arg( 'filter_manual', 1 ) );

		$views['manual'] = sprintf(
			'<a href="%s" class="%s">%s <span class="count">(%s)</a>',
			esc_url( $url ),
			$this->is_manual_view() ? esc_attr( 'current' ) : '',
			esc_html__( 'Manual', 'automatewoo' ),
			Workflows::get_manual_workflows_count()
		);

		$trash = aw_array_extract( $views, 'trash' );
		if ( $trash ) {
			$views['trash'] = $trash;
		}

		return $views;
	}

}
