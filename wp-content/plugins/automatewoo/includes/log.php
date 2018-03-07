<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Log
 */
class Log extends Model {

	/** @var string */
	public $table_id = 'logs';

	/** @var string  */
	public $object_type = 'log';

	/** @var string  */
	public $meta_table_id = 'log-meta';

	/** @var Data_Layer */
	private $data_layer;


	/**
	 * @param bool|int $id
	 */
	function __construct( $id = false ) {
		if ( $id ) $this->get_by( 'id', $id );
	}


	/**
	 * @param int $workflow_id
	 */
	function set_workflow_id( $workflow_id ) {
		$this->set_prop( 'workflow_id', Clean::id( $workflow_id ) );
	}


	/**
	 * @return int
	 */
	function get_workflow_id() {
		return Clean::id( $this->get_prop( 'workflow_id' ) );
	}


	/**
	 * @param \DateTime $date
	 */
	function set_date( $date ) {
		$this->set_date_column( 'date', $date );
	}


	/**
	 * @return \DateTime|bool
	 */
	function get_date() {
		return $this->get_date_column( 'date' );
	}


	/**
	 * @param bool $has_errors
	 */
	function set_has_errors( $has_errors ) {
		$this->set_prop( 'has_errors', absint( (bool) $has_errors ) );
	}


	/**
	 * @return bool
	 */
	function has_errors() {
		return (bool) $this->get_prop( 'has_errors' );
	}


	/**
	 * @param bool $has_blocked_emails
	 */
	function set_has_blocked_emails( $has_blocked_emails ) {
		$this->set_prop( 'has_blocked_emails', absint( (bool) $has_blocked_emails ) );
	}


	/**
	 * @return bool
	 */
	function has_blocked_emails() {
		return (bool) $this->get_prop( 'has_blocked_emails' );
	}


	/**
	 * @param bool $enabled
	 */
	function set_tracking_enabled( $enabled ) {
		$this->set_prop( 'tracking_enabled', absint( (bool) $enabled ) );
	}


	/**
	 * @return bool
	 */
	function is_tracking_enabled() {
		return (bool) $this->get_prop( 'tracking_enabled' );
	}


	/**
	 * @param bool $enabled
	 */
	function set_conversion_tracking_enabled( $enabled ) {
		$this->set_prop( 'conversion_tracking_enabled', absint( (bool) $enabled ) );
	}


	/**
	 * @return bool
	 */
	function is_conversion_tracking_enabled() {
		return (bool) $this->get_prop( 'conversion_tracking_enabled' );
	}


	/**
	 * @param $url
	 */
	function record_click( $url ) {

		if ( ! $tracking = $this->get_meta('tracking_data') ) {
			$tracking = [];
		}

		$tracking[] = [
			'type' => 'click',
			'url' => $url,
			'date' => current_time( 'mysql', true )
		];

		// clicking requires an open so record one in case images were blocked
		if ( ! $this->has_open_recorded() ) {
			$tracking[] = [
				'type' => 'open',
				'date' => current_time( 'mysql', true )
			];
		}

		$this->update_meta( 'tracking_data', $tracking );
	}


	/**
	 * Only records an open once i.e. unique opens
	 */
	function record_open() {

		if ( $this->has_open_recorded() ) {
			return; // already opened
		}

		if ( ! $tracking = $this->get_meta('tracking_data') ) {
			$tracking = [];
		}

		$tracking[] = [
			'type' => 'open',
			'date' => current_time( 'mysql', true )
		];

		$this->update_meta( 'tracking_data', $tracking );
	}


	/**
	 * @return bool
	 */
	function has_open_recorded() {

		$tracking = $this->get_meta('tracking_data');

		if ( is_array( $tracking ) ) foreach( $tracking as $item ) {
			if ( $item['type'] == 'open') {
				return true;
			}
		}

		return false;
	}


	/**
	 * @return bool
	 */
	function has_click_recorded() {

		$tracking = $this->get_meta('tracking_data');

		if ( is_array( $tracking ) ) foreach( $tracking as $item ) {
			if ( $item['type'] == 'click')
				return true;
		}

		return false;
	}


	/**
	 * @return string|false
	 */
	function get_date_opened() {

		$tracking = $this->get_meta('tracking_data');

		if ( is_array( $tracking ) ) foreach( $tracking as $item ) {
			if ( $item['type'] == 'open')
				return $item['date'];
		}

		return false;
	}


	/**
	 * @return string|false
	 */
	function get_date_clicked() {

		$tracking = $this->get_meta('tracking_data');

		if ( is_array( $tracking ) ) foreach( $tracking as $item ) {
			if ( $item['type'] == 'click')
				return $item['date'];
		}

		return false;
	}



	/**
	 * @param $note string
	 */
	function add_note( $note ) {

		$notes = $this->get_meta( 'notes' );

		if ( ! is_array( $notes ) )
			$notes = [];

		$notes[] = $note;
		$this->update_meta( 'notes', $notes );
	}


	/**
	 * Returns the workflow without a data layer
	 * @return Workflow
	 */
	function get_workflow() {
		return AW()->get_workflow( $this->get_workflow_id() );
	}


	/**
	 * @param string $output - array|object this for backwards compatibility
	 * @return Data_Layer|array
	 */
	function get_data_layer( $output = 'array' ) {

		if ( ! isset( $this->data_layer ) ) {
			if ( $compressed = $this->get_compressed_data_layer() ) {
				$this->data_layer = $this->decompress_data_layer( $compressed );
			}
			else {
				$this->data_layer = new Data_Layer();
			}
		}

		if ( $output == 'array' ) {
			return $this->data_layer->get_raw_data();
		}

		return $this->data_layer;
	}


	/**
	 * Fetches the data layer from log meta, but does not decompress
	 * Uses the the supplied_data_items field on the workflows trigger
	 *
	 * @return array|false
	 */
	private function get_compressed_data_layer() {

		if ( ! $workflow = $this->get_workflow() )
			return false; // workflow must be set

		if ( ! $this->exists )
			return false; // log must be saved

		if ( ! $trigger = $workflow->get_trigger() )
			return false; // need a trigger

		$data_layer = [];

		foreach ( $trigger->get_supplied_data_items() as $data_type_id ) {

			$data_item_value = $this->get_compressed_data_item( $data_type_id, $trigger->get_supplied_data_items() );

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

		// user requires special logic when related to an order
		if ( $data_type_id === 'user' && in_array( 'order', $supplied_data_items ) ) {
			return 0; // get user data from the order when decompressing
		}

		$storage_key = $this->get_data_item_storage_key( $data_type_id );

		if ( ! $storage_key )
			return false;

		return Clean::string( $this->get_meta( $storage_key ) );
	}


	/**
	 * @param array $compressed_data_layer
	 * @return Data_Layer
	 */
	private function decompress_data_layer( $compressed_data_layer ) {

		$data = [];

		if ( is_array( $compressed_data_layer ) ) foreach ( $compressed_data_layer as $data_type_id => $compressed_item ) {
			if ( $data_type = Data_Types::get( $data_type_id ) ) {
				$data[$data_type_id] = $data_type->decompress( $compressed_item, $compressed_data_layer );
			}
		}

		return new Data_Layer( $data );
	}


	/**
	 * Stores a data layer in log meta
	 * @param Data_Layer $data_layer
	 */
	function store_data_layer( $data_layer ) {

		if ( ! $this->exists )
			return; // log must be saved before meta can be added

		foreach ( $data_layer->get_raw_data() as $data_type_id => $data_item ) {
			$this->store_data_item( $data_type_id, $data_item );
		}
	}


	/**
	 * @param $data_type_id
	 * @param $data_item
	 */
	private function store_data_item( $data_type_id, $data_item ) {

		$data_type = Data_Types::get( $data_type_id );

		if ( ! $data_type || ! $data_type->validate( $data_item ) )
			return;

		// special logic for users who are actually guests
		if ( $data_type_id === 'user' && $data_item->ID === 0 ) {
			$storage_key = 'guest_email';
			$storage_value = $data_item->user_email;
		}
		else {
			$storage_key = $this->get_data_item_storage_key( $data_type_id );
			$storage_value = $this->get_data_item_storage_value( $data_type_id, $data_item );
		}

		if ( $storage_key ) {
			$this->update_meta( $storage_key, $storage_value );
		}
	}



	/**
	 * @param $data_type_id string
	 * @return bool|string
	 */
	private function get_data_item_storage_key( $data_type_id ) {

		$storage_keys = apply_filters( 'automatewoo/log/data_layer_storage_keys', [
			'cart' => 'cart_id',
			'category' => 'category_id',
			'comment' => 'comment_id',
			'guest' => 'guest_email',
			'order' => 'order_id',
			'order_item' => 'order_item_id',
			'order_note' => 'order_note_id',
			'product' => 'product_id',
			'subscription' => 'subscription_id',
			'tag' => 'tag_id',
			'user' => 'user_id',
			'wishlist' => 'wishlist_id',
			'workflow' => 'workflow_id',
		]);

		if ( isset( $storage_keys[ $data_type_id ] ) ) {
			return $storage_keys[ $data_type_id ];
		}
		else {
			return '_data_layer_' . $data_type_id;
		}
	}


	/**
	 * @param $data_type_id
	 * @param $data_item : must be validated
	 * @return mixed
	 */
	private function get_data_item_storage_value( $data_type_id, $data_item ) {

		$value = false;

		if ( $data_type = Data_Types::get( $data_type_id ) ) {
			$value = $data_type->compress( $data_item );
		}

		return $value;
	}


	/**
	 * Delete the log and clear related conversion order meta
	 */
	function delete() {

		// delete conversion records for the log
		$query = new \WP_Query([
			'post_type' => 'shop_order',
			'post_status' => 'any',
			'posts_per_page' => -1,
			'no_found_rows' => true,
			'fields' => 'ids',
			'meta_query' => [
				[
					'key' => '_aw_conversion_log',
					'value' => $this->get_id()
				]
			]
		]);

		$converted_orders = $query->posts;

		if ( $converted_orders ) foreach ( $converted_orders as $order_id ) {
			if ( $order = wc_get_order( $order_id ) ) {
				Compat\Order::delete_meta( $order, '_aw_conversion' );
				Compat\Order::delete_meta( $order, '_aw_conversion_log' );
			}
		}

		$this->clear_cached_data();
		parent::delete();
	}


	/**
	 *
	 */
	function save() {
		$this->clear_cached_data();
		parent::save();
	}


	function clear_cached_data() {

		if ( ! $this->get_workflow_id() )
			return;

		Cache::delete_transient( 'times_run/workflow=' . $this->get_workflow_id() );
	}


	/**
	 * Reruns the workflow skipping validation
	 * @return Log|bool - the newly created log
	 */
	function rerun() {
		$workflow = $this->get_workflow();
		$workflow->maybe_run( $this->get_data_layer( 'object' ), true, true );

		$log = $workflow->get_current_log();

		if ( $log ) {
			$log->add_note( __( 'This log was created from manual workflow re-run.', 'automatewoo' ) );
			return $log;
		}

		return false;
	}

}

