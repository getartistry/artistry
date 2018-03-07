<?php

namespace AutomateWoo;


if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Tool_Reset_Workflow_Records
 * @since 2.4.5
 */
class Tool_Reset_Workflow_Records extends Tool {

	public $id = 'reset_workflow_records';

	/**
	 * Constructor
	 */
	function __construct() {
		$this->title = __( 'Reset Workflow Records', 'automatewoo' );
		$this->description = __( 'Delete all logs, queued events and unsubscribes for a workflow.', 'automatewoo' );
	}


	/**
	 *
	 */
	function get_form_fields() {

		$fields = [];

		$fields[] = ( new Fields\Workflow() )
			->set_name_base('args')
			->add_extra_attr( 'data-aw-tool', $this->id );

		return $fields;
	}


	/**
	 * @param $args
	 * @return bool|\WP_Error
	 */
	function validate_process( $args ) {

		$args = $this->sanitize_args( $args );

		if ( empty( $args['workflow'] ) ) {
			return new \WP_Error(1, __( 'Please select a workflow to reset.','automatewoo') );
		}

		if ( ! $workflow = AW()->get_workflow( $args['workflow'] ) )
			return false;

		return true;
	}



	/**
	 * Do validation in the validate_process() method not here
	 *
	 * @param $args
	 */
	function display_confirmation_screen( $args ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args['workflow'] );

		echo '<p>' . sprintf(__('Are you sure you want to reset all records for the workflow <strong>%s</strong>? This can not be undone.', 'automatewoo'), $workflow->title ) . '</p>';
	}



	/**
	 * @param $args
	 * @return bool|\WP_Error
	 */
	function process( $args ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args['workflow'] );

		$queries = [ 'AutomateWoo\Log_Query', 'AutomateWoo\Queue_Query' ];

		foreach ( $queries as $class ) {

			/** @var Query_Custom_Table $query */
			$query = new $class();
			$query->where( 'workflow_id', $workflow->get_id() );

			$results = $query->get_results();

			foreach( $results as $result ) {
				$result->delete();
			}
		}

		return true;
	}


	/**
	 * @param array $args
	 * @return array
	 */
	function sanitize_args( $args ) {

		if ( isset( $args['workflow'] ) ) {
			$args['workflow'] = absint( $args[ 'workflow' ] );
		}

		return $args;
	}


}

return new Tool_Reset_Workflow_Records();