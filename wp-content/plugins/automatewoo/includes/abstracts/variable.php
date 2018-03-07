<?php

namespace AutomateWoo;

/**
 * @class Variable
 * @since 2.4
 */
abstract class Variable {

	/** @var string */
	protected $name;

	/** @var string */
	protected $description;

	/** @var array */
	protected $parameters = [];

	/** @var string */
	protected $data_type;

	/** @var string */
	protected $data_field;

	/** @var bool */
	public $use_fallback = true;

	/** @var bool */
	public $has_loaded_admin_details = false;


	/**
	 * Optional method
	 */
	function init() {}


	/**
	 * Method to set description and other admin props
	 */
	function load_admin_details() {}


	function maybe_load_admin_details() {
		if ( ! $this->has_loaded_admin_details ) {
			$this->load_admin_details();
			$this->has_loaded_admin_details = true;
		}
	}


	/**
	 * Constructor
	 */
	function __construct() {
		$this->init();
	}


	/**
	 * Sets the name, data_type and data_field props
	 * @param $name
	 */
	function setup( $name ) {
		$this->name = $name;
		list( $this->data_type, $this->data_field ) = explode( '.', $this->name );
	}


	/**
	 * @return string
	 */
	function get_description() {
		$this->maybe_load_admin_details();
		return $this->description;
	}


	/**
	 * @return array
	 */
	function get_parameters() {
		$this->maybe_load_admin_details();
		return $this->parameters;
	}


	/**
	 * @return bool
	 */
	function has_parameters() {
		$this->maybe_load_admin_details();
		return ! empty( $this->parameters );
	}


	/**
	 * @return string
	 */
	function get_name() {
		return $this->name;
	}


	/**
	 * @return string
	 */
	function get_data_type() {
		return $this->data_type;
	}


	/**
	 * @return string
	 */
	function get_data_field() {
		return $this->data_field;
	}


	/**
	 * @param $name
	 * @param $description
	 * @param bool $required
	 * @param string $placeholder
	 * @param array $extra
	 */
	protected function add_parameter_text_field( $name, $description, $required = false, $placeholder = '', $extra = [] ) {
		$this->parameters[$name] = array_merge([
			'type' => 'text',
			'description' => $description,
			'required' => $required,
			'placeholder' => $placeholder
		], $extra );
	}


	/**
	 * @param $name
	 * @param $description
	 * @param array $options
	 * @param bool $required
	 * @param array $extra
	 */
	protected function add_parameter_select_field( $name, $description, $options = [], $required = false, $extra = [] ) {
		$this->parameters[$name] = array_merge([
			'type' => 'select',
			'description' => $description,
			'options' => $options,
			'required' => $required
		], $extra );
	}

}
