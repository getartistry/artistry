<?php

/**
 * Class for sending twitter messages
 *
 * @property  total_sent
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/senders
 */

class Wsi_Twitter_Sender extends Wsi_Senders {

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
		$this->limit	= 15;
		$this->every	= 60 * 15; //15 min
		$this->setData($queue_data);
		try {
			$this->connectSession();
		} catch( Exception $e ) {
			Wsi_Logger::log( "Wsi_Twitter_Sender : connectSession -" . $e->getMessage());
		}

	}

	/**
	 * Send Twitter DM
	 * @return mixed
	 */
	public function send() {
		global $wpdb;

		$delete_row = true;

		$sent_on_batch = 0;


		try {
			$this->adapter->setUserStatus( $this->data->message );
			$this->total_sent ++;
			$sent_on_batch ++;
			do_action( 'wsi/invitation_sent', $this->data->user_id, $this->data->wsi_obj_id );
		} catch( Exception $e ) {
			Wsi_Logger::log( "Wsi_Twitter_Sender: cannot post status -" . $e->getMessage());
			//if we reach here if that we wasn't able to post DM or status so just delete the record from queue to avoid getting stuck
			$delete_row = true;
		}

		//save stats
		Wsi_Logger::log_stat('twitter',$this->data->user_id, $sent_on_batch, $this->data->id,$this->data->display_name, $this->data->wsi_obj_id);

		// we finish with this row, lets delete it
		if( $delete_row ) $wpdb->query("DELETE FROM {$wpdb->prefix}wsi_queue WHERE id =  '".$this->data->id."'");

		//Let's see if we have more in queue
		$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE provider = 'twitter' AND id >  '".$this->data->id."' AND display_name != '". $this->data->display_name ."' ORDER BY id ASC LIMIT 1");

		//if we have more rows, proccess them
		if( isset($queue_data->id) )
		{
			$this->setData($queue_data, 0);
			$this->send();
		}

		return $this->total_sent;
	}


}