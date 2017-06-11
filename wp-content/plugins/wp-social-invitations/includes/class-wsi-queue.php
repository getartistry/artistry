<?php

/**
 * Class that handle Queue of messages
 *
 * @since      2.5.0
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Wsi_Queue {
	/**
	 * The ID of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $wsi    The ID of this plugin.
	 */
	private $wsi;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	protected $hybridauth;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.5
	 * @var      string    $wsi       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wsi, $version ) {

		$this->wsi = $wsi;
		$this->version = $version;
	}


	/**
	 * Main function that run the queue and check every provider for messages
	 */
	public function run() {
		global $wpdb;
		//retrieve locks - We locks in case wp-cron is run multiple times
		$lock_fb 		= get_option('wsi-lock-fb'); //facebook
		$lock_tw 		= get_option('wsi-lock-tw'); //twitter
		$lock_lk 		= get_option('wsi-lock-lk'); //linkedin
		$lock_email 	= get_option('wsi-lock-emails'); //emails

		if ( !$lock_email )
		{
			$this->sendEmails();
		}//lock_email

		if ( !$lock_tw )
		{
			$this->sendTw();
		}//lock_twitter

		if ( !$lock_lk )
		{
			$this->sendLk();
		}//lock_twitter

		if ( !$lock_fb )
		{
			$this->sendFb();
		}//lock_fb
	}

	public function unlock_queue(){
		if( !isset( $_GET['wsi_queue_unlock']) || $_GET['wsi_queue_unlock'] != WSI_CRON_TOKEN )
			return;

		delete_option('wsi-lock-fb');
		Wsi_Logger::log( "Facebook queue unlocked");
		delete_option('wsi-lock-tw');
		Wsi_Logger::log( "Twitter queue unlocked");
		delete_option('wsi-lock-lk');
		Wsi_Logger::log( "Linkedin queue unlocked");
		delete_option('wsi-lock-emails');
		Wsi_Logger::log( "Mails queue unlocked");
	}
	/**
	 * Ajax callback from collector that check nonce and if correct call add data to queue
	 * @since 2.5.0
	 * @return void
	 */
	public function send_to_queue() {
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'wsi-ajax-nonce' ) )
			die ( 'Not good...not good');

		$this->add_to_queue($_POST);
		die();
	}

	/**
	 * Function that add data to queue database
	 * @param $input
	 * @since 2.5.0
	 * @return int record from db
	 */
	private function add_to_queue( $input ) {

		$friends = isset($_POST['friend']) ? $_POST['friend'] : '';
		$isFb = false;

		// check if provider is sent in input (fb only)
		if( !empty($input['provider']) ) {
			$provider = $input['provider'];
			$friends  = array('demo'.rand(1,99999999).'@demo.com'); // Add an empty user for facebook
			$isFb = true;
		} else {
			$provider = wsi_get_data('provider');
		}


		$wsi_ob_id = wsi_get_data('obj_id');
		// check if provider is sent in input (fb only)
		if( empty($wsi_ob_id) && !empty($input['wsi_ob_id']) ) {
			$wsi_ob_id = $input['wsi_ob_id'];
		}

		if( $provider == 'mail' )
		{
			//lets parse our textarea and add it to the friend post var
			$f_array	 		= explode(PHP_EOL, $friends);
			$friends 			= array_filter($f_array, 'trim');

		}

		// if it's a mailer provider we need to add non editable part with placeholders replaced
		if( wsi_is_mailer_provider($provider) ) {
			global $wsi_plugin;
			$opts = $wsi_plugin->get_opts();
			$input['message'] = $input['message'] .'
			 '.Wsi_Collector::replaceShortcodes( do_shortcode($opts['html_non_editable_message']) );
		}
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix.'wsi_queue',
			array(
				'provider'      => @$provider,
				'sdata'         => wsi_get_data('sdata'),
				'friends'       => @serialize($friends),
				'subject'       => @$input['subject'],
				'message'       => @$input['message'],
				'i_count'       => count($friends),
				'user_id'       => get_current_user_id(),
				'display_name'  => wsi_get_display_name(),
				'date_added'    => current_time('mysql', 1),
				'wsi_obj_id'    => $wsi_ob_id,
			),
			array('%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%d')

		);

		$this->add_to_invited($friends, $isFb);

		return $wpdb->insert_id;
	}

	/**
	 * Function that creates the accepts url in case of used
	 * @param $queue_id
	 * @param $content
	 *
	 * @return mixed
	 */
	public static function replaceShortcodes($queue_id, $content) {
		$que = array(
			'%%ACCEPTURL%%',
		);

		$accept_url 	= site_url('/wp-login.php?action=register&wsi_action=accept-invitation&wsi_invitation='.urlencode( base64_encode( esc_attr($queue_id) )) );

		$por = array(
			apply_filters('wsi/placeholders/accept_url'	, $accept_url),
		);

		return str_replace($que, $por, $content);
	}

	/**
	 * Save invites to db to filter later
	 * @param $friends
	 */
	private function add_to_invited( $friends, $isFb ) {
		$user_id = get_current_user_id();

		if( empty($user_id) || empty($friends) )
			return;

		global $wpdb;

		$provider = $isFb ? 'facebook' : wsi_get_data('provider');

		//grab previous invites for user and provider
		$already_invited = Wsi_Queue::getInvitedFriends($user_id, $provider);

		if( !empty( $already_invited ) ) {
			$wpdb->update(
				$wpdb->prefix.'wsi_invites',
				array(
					'friends'       => serialize(array_unique (array_merge ($friends,(array)$already_invited))),
				),
				array(
					'provider'      => $provider,
					'user_id'       => $user_id,
				),
				array('%s'),
				array('%s', '%d')
			);
		} else {
			//save a mix of both
			$wpdb->insert(
				$wpdb->prefix . 'wsi_invites',
				array(
					'provider'      => $provider,
					'friends'       => serialize( array_unique( array_merge( $friends ) ) ),
					'user_id'       => $user_id,
					'date_added'    => current_time('mysql', 1),
				),
				array( '%s', '%s', '%d', '%s' )
			);
		}
		//Update total invites
		$this->updateTotals($user_id);

	}

	/**
	 * Get already invited friends
	 *
	 * @param string $user_id
	 * @param string $provider
	 *
	 * @return mixed|void
	 */
	public static function getInvitedFriends($user_id = '', $provider = ''){
		global $wpdb;

		if( empty( $user_id ) )
			$user_id  = get_current_user_id();
		if( empty( $provider ) )
			$provider = wsi_get_data('provider');

		if( empty($user_id) || empty($provider) ) return;

		return unserialize( $wpdb->get_var($wpdb->prepare( "SELECT friends FROM {$wpdb->prefix}wsi_invites WHERE user_id = %d AND provider = %s", array( $user_id, $provider ) ) ) );
	}

	/**
	 * Create a goo.gl url from given one
	 * @param $url
	 *
	 * @return mixed
	 */
	public static function shortenUrl($url) {
		$api_key = apply_filters('wsi/google_shorten_api','');
		$googl 		= new Wsi_Googl($api_key);
		$shortened 	= $googl->shorten($url);
		unset($googl);
		return empty($shortened) ? $url : $shortened;
	}


	/**
	 * Insert a Facebook share to the queue and return id
	 * @since 2.5.0
	 * return string id
	 */
	function get_fb_queue_id(){

		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'wsi-ajax-nonce' ) )
			die ( 'Not good not good');

		$input['provider']      = 'facebook';
		$input['wsi_obj_id']    = isset($_POST['wsi_obj_id']) ? $_POST['wsi_obj_id'] : '';

		$queue_id = $this->add_to_queue($input);
		if( ! get_option('wsi-lock-fb') ) {
			$this->sendFb();
		}

		echo json_encode(array('queue_id' => urlencode( base64_encode( $queue_id ))));
		die();
	}

	/**
	 * Function that fires the sender email class
	 * @since 2.5.0
	 */
	private function sendEmails(){
		global $wpdb;
		//lock emails queue until we finish
		update_option('wsi-lock-emails','yes');
		Wsi_Logger::log( "Mails queue locked");
		try{
			//we get first row of emails
			$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE  ( send_at is NULL OR send_at <= NOW() ) AND (provider = 'google' OR provider = 'mail' OR provider = 'yahoo' OR provider = 'live' OR provider = 'foursquare') ORDER BY id ASC");
			//if we have something in queue
			if( isset($queue_data->id) )
			{
				$sender = new Wsi_Mailer($queue_data);
				$sender->send();

			}
			delete_option('wsi-lock-emails');
			Wsi_Logger::log( "Mails queue unlocked");
		}
		catch ( phpmailerException $e )
		{
			Wsi_Logger::log( "Wsi_Queue: Mail queue proccesing error - " . $e->errorMessage());
			delete_option('wsi-lock-emails');
			Wsi_Logger::log( "Mails queue unlocked");
		}
		catch( Exception $e ){
			Wsi_Logger::log( "Wsi_Queue: Mail queue proccesing error - " . $e->getMessage());
			delete_option('wsi-lock-emails');
			Wsi_Logger::log( "Mails queue unlocked");
		}
	}


	/**
	 * Function that fires Twitter sender class
	 * @since 2.5.0
	 */
	private function sendTw() {
		global $wpdb;
		//lock emails queue until we finish
		update_option('wsi-lock-tw','yes');
		Wsi_Logger::log( "Twitter queue locked");
		try{
			//we get first row of emails
			$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE  ( send_at is NULL OR send_at <= NOW() ) AND provider = 'twitter' ORDER BY id ASC");
			//if we have something in queue
			if( isset($queue_data->id) )
			{
				$sender = new Wsi_Twitter_Sender($queue_data);
				$sender->send();
			}
			delete_option('wsi-lock-tw');
			Wsi_Logger::log( "Twitter queue unlocked");
		}
		catch( Exception $e ){
			Wsi_Logger::log( "Wsi_Queue: Twitter queue processing error - " . $e->getMessage());
			delete_option('wsi-lock-tw');
			Wsi_Logger::log( "Twitter queue unlocked");
		}
	}

	/**
	 * Function that fires Likeding sender class
	 * @since 2.5.0
	 */
	private function sendLk() {
		global $wpdb;
		//lock emails queue until we finish
		update_option('wsi-lock-lk','yes');
		Wsi_Logger::log( "Linkedin queue locked");
		try{
			//we get first row of emails
			$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE  ( send_at is NULL OR send_at <= NOW() ) AND provider = 'linkedin' ORDER BY id ASC");
			//if we have something in queue
			if( isset($queue_data->id) )
			{
				$sender = new Wsi_Linkedin_Sender($queue_data);
				$sender->send();
			}
			delete_option('wsi-lock-lk');
			Wsi_Logger::log( "Linkedin queue unlocked");
		}
		catch( Exception $e ){
			Wsi_Logger::log( "Wsi_Queue: Linkedin queue processing error - " . $e->getMessage());
			delete_option('wsi-lock-lk');
			Wsi_Logger::log( "Linkedin queue unlocked");
		}
	}

	private function sendFb() {
		global $wpdb;
		update_option('wsi-lock-fb','yes');
		Wsi_Logger::log( "Facebook queue locked");
		try{
			//we get first row of emails
			$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE  provider = 'facebook' ORDER BY id ASC");
			//if we have something in queue
			if( isset($queue_data->id) )
			{
				$sender = new Wsi_Facebook_Sender($queue_data);
				$sender->send();
			}
			delete_option('wsi-lock-fb');
			Wsi_Logger::log( "Facebook queue unlocked");
		}
		catch( Exception $e ){
			Wsi_Logger::log( "Wsi_Queue: Facebook queue processing error - " . $e->getMessage());
			delete_option('wsi-lock-fb');
			Wsi_Logger::log( "Facebook queue unlocked");
		}
	}

	/**
	 * Updates the total invites the user send
	 * @param $user_id
	 */
	private function updateTotals( $user_id ) {
		global $wpdb;

		$counter = 0;
		$rows = $wpdb->get_results( $wpdb->prepare("SELECT friends FROM {$wpdb->prefix}wsi_invites WHERE user_id = %d", array( $user_id) ) );
		if( !empty($rows) ) {
			foreach ( $rows as $r ) {
				$counter += count( unserialize( $r->friends ) );
			}
		}
		update_user_meta( $user_id, 'wsi_total_invites', $counter);
	}

}