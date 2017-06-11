<?php

/**
 * Class for sending Linkedin messages
 *
 * @property  total_sent
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/senders
 */

class Wsi_Linkedin_Sender extends Wsi_Senders{

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

		add_filter('wsi/placeholders/accept_url', array( 'Wsi_Queue', 'shortenUrl'));
		$this->opts     = $wsi_plugin->get_opts();
		// Sends a message to up to 10 connections
		// Application: 5k
		// Per User: 10
		$this->limit	= 5000;
		$this->every	= strtotime('tomorrow');
		$this->setData($queue_data);
		try {
			$this->connectSession();
		} catch( Exception $e ) {
			Wsi_Logger::log( "Wsi_Linkedin_Sender : connectSession -" . $e->getMessage());
		}

	}


	/**
	 * Send the privates messages
	 * @return mixed
	 */
	public function send() {
		global $wpdb;

		$delete_row = true;

		$sent_on_batch = 0;

		$sent_messages = 0;

		do_action('wsi/invitation_sent', $this->data->user_id, $this->data->wsi_obj_id );

		try {
			$this->adapter->setUserStatus( $this->data->message );
		} catch( Exception $e ) {
			Wsi_Logger::log( "Wsi_Linkedin_Sender : setUserStatus - " . $e->getMessage());
		}


		Wsi_Logger::log_stat('linkedin',$this->data->user_id, $sent_on_batch, $this->data->id, $this->data->display_name, $this->data->wsi_obj_id);

		// we finish with this row, lets delete it
		if( $delete_row ) $wpdb->query("DELETE FROM {$wpdb->prefix}wsi_queue WHERE id ='".$this->data->id."'");


		//IF we finish our batch and we haven't reach our limit we proccess next row in db
		if( $this->total_sent < $this->limit )
		{
			//Let's see if we have more in queue
			$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE provider = 'linkedin' AND id > '".$this->data->id."' AND display_name != '".$this->data->display_name."' ORDER BY id ASC LIMIT 1");

			//if we have more rows, proccess them
			if( isset($queue_data->id) )
			{
				$this->setData($queue_data, $this->total_sent);
				$this->send();
			}
		}

		return $this->total_sent;
	}

}