<?php

namespace AutomateWoo;

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

		$workflow = AW()->get_workflow( $post_id );

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
				echo '<button type="button" class="aw-switch js-toggle-workflow-status" '
					. 'data-workflow-id="'. $workflow->get_id() .'" '
					. 'data-aw-switch="'. ( $workflow->is_active() ? 'on' : 'off' ) . '">'
					. __( 'Toggle Status', 'automatewoo' ) . '</button>';
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
			if ( ! $trigger->allow_queueing ) {
				return _x( 'Custom', 'timing option', 'automatewoo' );
			}
		}


		switch( $workflow->get_timing_type() ) {

			case 'immediately':
				return _x( 'Immediate', 'timing option', 'automatewoo' );
				break;
			case 'delayed':
				return sprintf( __( 'Wait for <b>%s</b>', 'automatewoo' ), $this->get_delay_unit_text( $workflow ) );
				break;
			case 'scheduled':

				$time = $workflow->get_scheduled_time();
				$days = $workflow->get_scheduled_days();

				$text = sprintf( __( 'Schedule for <b>%s</b>', 'automatewoo' ), Format::time_of_day( $time ) );

				if ( $days ) {
					$text .= sprintf( __( ' on  <b>%s</b>', 'automatewoo' ), $this->get_weekday_text( $days ) );
				}

				if ( $workflow->get_timing_delay() ) {
					$text .= sprintf( __( ' after waiting <b>%s</b>', 'automatewoo' ), $this->get_delay_unit_text( $workflow ) );
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
			default:
				return '';
		}
		return sprintf( $unit_text, $number );
	}

}

new Admin_Workflow_List();
