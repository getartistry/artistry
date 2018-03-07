<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Queued_Event
 *
 * @property array $data_items (legacy)
 */
class Queued_Event extends Model {

	/** @var string */
	public $table_id = 'queue';

	/** @var string  */
	public $object_type = 'queue';

	/** @var string  */
	public $meta_table_id = 'queue-meta';

	/** @var bool|array */
	private $uncompressed_data_layer;


	// error messages
	const F_WORKFLOW_INACTIVE = 100;
	const F_MISSING_DATA = 101;
	const F_FATAL_ERROR = 102;


	/**
	 * @param bool|int $id
	 */
	function __construct( $id = false ) {
		if ( $id ) $this->get_by( 'id', $id );
	}


	/**
	 * @param int $id
	 */
	function set_workflow_id( $id ) {
		$this->set_prop( 'workflow_id', Clean::id( $id ) );
	}


	/**
	 * @return int
	 */
	function get_workflow_id() {
		return Clean::id( $this->get_prop( 'workflow_id' ) );
	}


	/**
	 * @param bool $failed
	 */
	function set_failed( $failed = true ) {
		$this->set_prop( 'failed', (bool) $failed );
	}


	/**
	 * @return bool
	 */
	function is_failed() {
		return (bool) $this->get_prop( 'failed' );
	}


	/**
	 * @param int $failure_code
	 */
	function set_failure_code( $failure_code ) {
		$this->set_prop( 'failure_code', absint( $failure_code ) );
	}


	/**
	 * @return int
	 */
	function get_failure_code() {
		return absint( $this->get_prop( 'failure_code' ) );
	}


	/**
	 * @param \DateTime $date
	 */
	function set_date_created( $date ) {
		$this->set_date_column( 'created', $date );
	}


	/**
	 * @return bool|\DateTime
	 */
	function get_date_created() {
		return $this->get_date_column( 'created' );
	}


	/**
	 * @param \DateTime $date
	 */
	function set_date_due( $date ) {
		$this->set_date_column( 'date', $date );
	}


	/**
	 * @return bool|\DateTime
	 */
	function get_date_due() {
		return $this->get_date_column( 'date' );
	}


	/**
	 * @param Data_Layer $data_layer
	 */
	function store_data_layer( $data_layer ) {

		$this->uncompressed_data_layer = $data_layer->get_raw_data();

		foreach ( $this->uncompressed_data_layer as $data_type_id => $data_item ) {
			$this->store_data_item( $data_type_id, $data_item );
		}
	}


	/**
	 * @param $data_type_id
	 * @param $data_item
	 */
	private function store_data_item( $data_type_id, $data_item ) {

		$data_type = Data_Types::get( $data_type_id );

		if ( ! $data_type || ! $data_type->validate( $data_item ) ) {
			return;
		}

		$storage_key = $this->get_data_item_storage_key( $data_type_id );
		$storage_value = $data_type->compress( $data_item );

		if ( $storage_key ) {
			$this->update_meta( $storage_key, $storage_value );
		}
	}


	/**
	 * @param $data_type_id string
	 * @return bool|string
	 */
	function get_data_item_storage_key( $data_type_id ) {
		return 'data_item_' . $data_type_id;
	}


	/**
	 * @return Data_Layer
	 */
	function get_data_layer() {

		if ( ! isset( $this->uncompressed_data_layer ) ) {

			$uncompressed_data_layer = [];
			$compressed_data_layer = $this->get_compressed_data_layer();

			if ( $compressed_data_layer ) {
				foreach ( $compressed_data_layer as $data_type_id => $compressed_item ) {
					if ( $data_type = Data_Types::get( $data_type_id ) ) {
						$uncompressed_data_layer[$data_type_id] = $data_type->decompress( $compressed_item, $compressed_data_layer );
					}
				}
			}

			$this->uncompressed_data_layer = new Data_Layer( $uncompressed_data_layer );
		}

		return $this->uncompressed_data_layer;
	}


	/**
	 * Fetches the data layer from queue meta, but does not decompress
	 * Uses the the supplied_data_items field on the workflows trigger
	 *
	 * @return array|false
	 */
	function get_compressed_data_layer() {

		if ( ! $workflow = $this->get_workflow() )
			return false; // workflow must be set

		if ( ! $this->exists )
			return false; // queue must be saved

		if ( ! $trigger = $workflow->get_trigger() )
			return false; // need a trigger

		$data_layer = [];

		$supplied_items = $trigger->get_supplied_data_items();

		foreach ( $supplied_items as $data_type_id ) {

			$data_item_value = $this->get_compressed_data_item( $data_type_id, $supplied_items );

			if ( $data_item_value !== false ) {
				$data_layer[ $data_type_id ] = $data_item_value;
			}
		}

		return $data_layer;
	}


	/**
	 * @param $data_type_id
	 * @param array $supplied_data_items
	 * @return string|false
	 */
	private function get_compressed_data_item( $data_type_id, $supplied_data_items ) {

		if ( in_array( $data_type_id, Data_Types::get_non_stored_data_types() ) ) {
			return false; // storage not required
		}

		$storage_key = $this->get_data_item_storage_key( $data_type_id );

		if ( ! $storage_key ) {
			return false;
		}

		return Clean::string( $this->get_meta( $storage_key ) );
	}


	/**
	 * Returns the workflow without a data layer
	 * @return Workflow|false
	 */
	function get_workflow() {
		return AW()->get_workflow( $this->get_workflow_id() );
	}


	/**
	 * @return bool
	 */
	function check_data_layer() {
		foreach ( $this->get_data_layer()->get_raw_data() as $data_item ) {
			if ( ! $data_item )
				return false;
		}

		return true;
	}


	/**
	 * @return bool
	 */
	function run() {

		if ( ! $this->exists ) {
			return false;
		}

		// mark as failed and then delete if complete, so fatal error will not cause it to run repeatedly
		$this->mark_as_failed( self::F_FATAL_ERROR );
		$this->save();
		$success = false;

		$workflow = $this->get_workflow();
		$workflow->setup( $this->get_data_layer() );

		$failure = $this->do_failure_check( $workflow );

		if ( $failure ) {
			// queued event failed
			$this->mark_as_failed( $failure );
		}
		else {
			$success = true;

			// passed fail check so validate workflow and then delete
			if ( $this->validate_workflow( $workflow ) ) {
				$workflow->run();
			}

			$this->delete();
		}

		// important to always clean up
		$workflow->cleanup();
		return $success;
	}


	/**
	 * Returns false if no failure occurred
	 * @param Workflow $workflow
	 * @return bool|int
	 */
	function do_failure_check( $workflow ) {

		if ( ! $workflow || ! $workflow->is_active() ) {
			return self::F_WORKFLOW_INACTIVE;
		}

		if ( ! $this->check_data_layer() ) {
			return self::F_MISSING_DATA;
		}

		return false;
	}


	/**
	 * Validate the workflow before running it from the queue.
	 * This validation is different from the initial trigger validation.
	 *
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		if ( ! $trigger = $workflow->get_trigger() )
			return false;

		if ( ! $trigger->validate_before_queued_event( $workflow ) )
			return false;

		if ( ! $workflow->validate_rules() )
			return false;

		return true;
	}


	function clear_cached_data() {

		if ( ! $this->get_workflow_id() )
			return;

		Cache::delete_transient( 'current_queue_count/workflow=' . $this->get_workflow_id() );
	}


	function save() {

		if ( ! $this->exists ) {
			$this->set_date_created( new \DateTime() );
		}

		$this->clear_cached_data();

		parent::save();
	}


	function delete() {
		$this->clear_cached_data();
		parent::delete();
	}



	/**
	 * @param int $code
	 */
	function mark_as_failed( $code ) {
		$this->set_failed();
		$this->set_failure_code( $code );
		$this->save();
	}


	/**
	 * @return string
	 */
	function get_failure_message( ) {
		return Queue_Manager::get_failure_message( $this->get_failure_code() );
	}


	/**
	 * Just for unit tests
	 */
	function clear_in_memory_data_layer() {
		$this->uncompressed_data_layer = null;
	}



	/**
	 * @deprecated use set_date_due()
	 * @param $date \DateTime
	 */
	function set_date( $date ) {
		$this->set_date_due( $date );
	}

}
