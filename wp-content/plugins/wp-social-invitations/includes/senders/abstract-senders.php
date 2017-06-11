<?php

/**
 * Class for Wsi providers senders
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/senders
 */

abstract class Wsi_Senders {
	protected $hybridauth;
	protected $adapter;
	protected $data;

	/**
	 * Connect to a provider with a given session data
	 */
	protected function connectSession() {
		$this->hybridauth   = new Wsi_Hybrid();
		$this->hybridauth->restoreSessionData($this->sdata);
		$this->adapter     = $this->hybridauth->getAdapter($this->data->provider);
	}

	/**
	 * Set the queue data
	 *
	 * @param mixed $data
	 * @param int $total_sent
	 */
	public function setData( $data, $total_sent = 0 ) {
		$this->data             = $data;
		$this->data->friends    = unserialize($this->data->friends);
		$this->data->message    = Wsi_Queue::replaceShortcodes($this->data->id, $this->data->message);
		$this->total_sent       = $total_sent;
		$this->sdata            = $this->data->sdata;
		Wsi_Logger::log( ucfirst($this->data->provider)." queue data set");
	}

	/**
	 * Main function that sends the provider
	 * messages to be overrided in child class
	 */
	public function send() {

	}
}