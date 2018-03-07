<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @deprecated
 *
 * @class Unsubscribe
 *
 * @property $workflow_id int
 * @property $user_id int
 * @property $email string
 * @property $date string
 */
class Unsubscribe extends Model {

	/** @var string */
	public $table_id = 'unsubscribes';

	/** @var string  */
	public $object_type = 'unsubscribe';


	/**
	 * @param bool|int $id
	 */
	function __construct( $id = false ) {
		if ( $id ) $this->get_by( 'id', $id );
	}


	/**
	 * @return int
	 */
	function get_workflow_id() {
		return (int) $this->get_prop( 'workflow_id' );
	}


	/**
	 * @param $id
	 */
	function set_workflow_id( $id ) {
		$this->set_prop( 'workflow_id', Clean::id( $id ) );
	}


	/**
	 * @return int
	 */
	function get_customer_id() {
		return (int) $this->get_prop( 'customer_id' );
	}


	/**
	 * @param $id
	 */
	function set_customer_id( $id ) {
		$this->set_prop( 'customer_id', Clean::id( $id ) );
	}


	/**
	 * @param $date
	 */
	function set_date( $date ) {
		$this->date = $date;
	}


	/**
	 * @return string
	 */
	function get_date() {
		return $this->date;
	}


	/**
	 * @return Customer|bool
	 */
	function get_customer() {
		return Customer_Factory::get( $this->get_customer_id() );
	}

}

