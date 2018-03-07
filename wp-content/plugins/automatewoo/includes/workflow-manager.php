<?php

namespace AutomateWoo;

/**
 * @class Workflow_Manager
 * @since 2.8.2
 */
class Workflow_Manager {


	/**
	 * Delete logs, unsubscribes, queue related to a workflow
	 *
	 * @param $workflow_id
	 */
	static function delete_related_data( $workflow_id ) {

		$logs_query = ( new Log_Query() )->where( 'workflow_id', $workflow_id );
		$queue_query = ( new Queue_Query() )->where( 'workflow_id', $workflow_id );

		$data = array_merge( $logs_query->get_results(), $queue_query->get_results() );

		foreach ( $data as $item ) {
			$item->delete();
		}
	}

}
