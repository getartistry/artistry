<?php

/**
 * @class AutomateWoo_Legacy
 */
abstract class AutomateWoo_Legacy {

	/** @var AW_Cache_Helper */
	private $cache;

	/** @var AutomateWoo\Factory */
	private $factory;

	/** @var AutomateWoo\Addons */
	private $addons;

	/** @var AutomateWoo\Integrations */
	private $integrations;

	/** @var AutomateWoo\Emails - @deprecated */
	public $email;


	/**
	 * @deprecated
	 * @return AutomateWoo\Emails
	 */
	function email() {
		if ( ! isset( $this->email ) ) {
			$this->email = new AutomateWoo\Emails();
		}
		return $this->email;
	}


	/**
	 * @return AW_Cache_Helper
	 * @deprecated
	 */
	function cache() {
		if ( ! isset( $this->cache ) ) {
			$this->cache = new AW_Cache_Helper();
		}
		return $this->cache;
	}


	/**
	 * @return AutomateWoo\Addons
	 * @deprecated - maintained for addons
	 */
	function addons() {
		if ( ! isset( $this->addons ) ) {
			$this->addons = new AutomateWoo\Addons();
		}
		return $this->addons;
	}


	/**
	 * @deprecated
	 * @return AutomateWoo\Integrations
	 */
	function integrations() {
		if ( ! isset( $this->integrations ) ) {
			$this->integrations = new AutomateWoo\Integrations();
		}
		return $this->integrations;
	}


	/**
	 * @return AutomateWoo\Factory_Legacy
	 * @deprecated
	 */
	function factory() {
		if ( ! isset( $this->factory ) ) {
			$this->factory = new AutomateWoo\Factory_Legacy();
		}
		return $this->factory;
	}


	/**
	 * @deprecated
	 *
	 * @param $id
	 * @return AutomateWoo\Unsubscribe|bool
	 */
	function get_unsubscribe( $id ) {
		return AutomateWoo\Unsubscribe_Factory::get( $id );
	}



}
