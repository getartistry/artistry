<?php

/**
 * Class for sending Facebook messages
 *
 * @property  total_sent
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/senders
 */

class Wsi_Facebook_Sender extends Wsi_Senders {

	protected $opts;

	protected $data;
	protected $limit;
	protected $every;
	protected $user_data;
	protected $total_sent;
	protected $hybridauth;
	protected $adapter;
	protected $sdata;

	/**
	 * Sender contructor
	 *
	 * @param $queue_data
	 */
	function __construct( $queue_data ) {
		global $wsi_plugin;


		$this->opts     = $wsi_plugin->get_opts();
		$this->setData($queue_data);
	}


	/**
	 * Not really sending, as we are using js sdk. Mainly used for stats and points
	 * @return mixed
	 */
	public function send() {
		global $wpdb;

		do_action('wsi/invitation_sent', $this->data->user_id, $this->data->wsi_obj_id);

		//we shared once let save to stats
		Wsi_Logger::log_stat('facebook', $this->data->user_id, 1, $this->data->id, $this->data->display_name, $this->data->wsi_obj_id );

		$wpdb->query("DELETE FROM {$wpdb->prefix}wsi_queue WHERE id ='".$this->data->id."'");

		$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE provider = 'facebook' AND id > '".$this->data->id."' ORDER BY id ASC LIMIT 1");

		//if we have more rows, proccess them
		if( isset($queue_data->id) )
		{
			$this->setData($queue_data, 0);
			$this->send();
		}

	}
}