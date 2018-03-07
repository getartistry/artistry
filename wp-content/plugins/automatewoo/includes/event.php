<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Event
 * @since 3.4.0
 */
class Event extends Model {

	/** @var string */
	public $table_id = 'events';

	/** @var string  */
	public $object_type = 'event';


	/**
	 * @param bool|int $id
	 */
	function __construct( $id = false ) {
		if ( $id ) $this->get_by( 'id', $id );
	}


	/**
	 * @param string $hook
	 */
	function set_hook( $hook ) {
		$this->set_prop( 'hook', Clean::string( $hook ) );
	}


	/**
	 * @return string
	 */
	function get_hook() {
		return Clean::string( $this->get_prop( 'hook' ) );
	}


	/**
	 * Possible statuses are 'pending', 'started'
	 *
	 * @param string $status
	 */
	function set_status( $status ) {
		$this->set_prop( 'status', Clean::string( $status ) );
	}


	/**
	 * @return string
	 */
	function get_status() {
		return Clean::string( $this->get_prop( 'status' ) );
	}


	/**
	 * @param $status
	 * @return bool
	 */
	function has_status( $status ) {
		return $this->get_status() == $status;
	}


	/**
	 * @param array $args
	 */
	function set_args( $args ) {
		$args = Clean::recursive( $args );
		$this->set_prop( 'args', $args );
		$this->set_prop( 'args_hash', md5(serialize($args)) );
	}


	/**
	 * @return array
	 */
	function get_args() {
		return (array) Clean::recursive( $this->get_prop( 'args' ) );
	}


	/**
	 * @param \DateTime $date
	 */
	function set_date_scheduled( $date ) {
		$this->set_date_column( 'date_scheduled', $date );
	}


	/**
	 * @return \DateTime|bool
	 */
	function get_date_scheduled() {
		return $this->get_date_column( 'date_scheduled' );
	}


	/**
	 * @param \DateTime $date
	 */
	function set_date_created( $date ) {
		$this->set_date_column( 'date_created', $date );
	}


	/**
	 * @return \DateTime|bool
	 */
	function get_date_created() {
		return $this->get_date_column( 'date_created' );
	}


	/**
	 * Runs the event
	 */
	function run() {
		$this->set_status( 'started' );
		$this->save();

		do_action_ref_array( $this->get_hook(), $this->get_args() );

		$this->delete();
	}


	/**
	 * Save
	 */
	function save() {
		if ( ! $this->exists && ! $this->has_prop( 'date_created' ) ) {
			$this->set_date_created( new \DateTime() );
		}
		parent::save();
	}

}
