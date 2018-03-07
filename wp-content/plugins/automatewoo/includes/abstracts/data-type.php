<?php

namespace AutomateWoo;

/**
 * @class Data_Type
 * @since 2.4.6
 */
abstract class Data_Type {

	/** @var string */
	public $id;


	/**
	 * @return string
	 */
	function get_id() {
		return $this->id;
	}


	/**
	 * @param string $id
	 */
	function set_id( $id ) {
		$this->id = $id;
	}


	/**
	 * @param $item
	 * @return bool
	 */
	abstract function validate( $item );


	/**
	 * Only validated $items should be passed to this method
	 *
	 * @param $item
	 * @return mixed
	 */
	abstract function compress( $item );


	/**
	 * @param $compressed_item
	 * @param $compressed_data_layer
	 * @return mixed
	 */
	abstract function decompress( $compressed_item, $compressed_data_layer );

}
