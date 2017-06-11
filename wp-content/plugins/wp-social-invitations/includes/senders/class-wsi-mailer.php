<?php

/**
 * Class for sending emails
 *
 * @property  total_sent
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/senders
 */

class Wsi_Mailer {

	protected $opts;

	protected $data;
	protected $limit;
	protected $every;
	protected $user_data;
	protected $total_sent;

	/**
	 * Sender contructor
	 */
	function __construct( $queue_data ) {
		global $wsi_plugin;

		$this->opts     = $wsi_plugin->get_opts();
		$this->limit	= $this->opts['emails_limit'];
		$this->every	= $this->opts['emails_limit_time'];

		if( $this->opts['send_with'] != 'own' ) {
			add_action( 'phpmailer_init', array($this, 'php_mailer_settings'),10 );
		}
		$this->setData($queue_data);
	}

	/**
	 * Set the queue data
	 *
	 * @param mixed $data
	 * @param int $total_sent
	 */
	public function setData( $data, $total_sent = 0 ) {
		if( !empty($data) ) {
			$this->data          = $data;
			$this->data->friends = unserialize( $this->data->friends );
			$this->data->message = $this->data->message;
			$this->total_sent    = $total_sent;
		}
		Wsi_Logger::log( "Mails data set");
	}

	public function send() {
		global $wpdb;

		$delete_row = true;

		$sent_on_batch = 0;
		Wsi_Logger::log( "Mails sending queue #".$this->data->id);
		if( empty($this->data->friends)){
			$delete_row = true;
			Wsi_Logger::log( "Empty friends list in Queue #".$this->data->id);
		} else {

			foreach ( $this->data->friends as $key => $f ) {
				$this->send_email( $f, $this->data->subject, $this->get_email_content() );

				$this->total_sent ++;

				$sent_on_batch ++;

				do_action( 'wsi/invitation_sent', $this->data->user_id, $this->data->wsi_obj_id );

				unset( $this->data->friends[ $key ] );

				//if we reach our limit
				if ( $this->total_sent == $this->limit ) {
					$send_at = time() + $this->every; //when to send next bacth

					//if we still have mails on this batch
					if ( $sent_on_batch < $this->data->i_count ) {
						//we update count and send date
						$mails_left = $this->data->i_count - $sent_on_batch;

						$friends_a = serialize( $this->data->friends );

						$wpdb->query( "UPDATE {$wpdb->prefix}wsi_queue SET i_count = '$mails_left', send_at = '$send_at', friends = '$friends_a'  WHERE id = '" . $this->data->id . "'" );

						$delete_row = false; // we can't delete this yet
					} else //we don't have more mails on this batch but we reached our $this->_limit  limit every $this->_every
					{
						//be sure to update the next record in db that send emails
						$next_id = $wpdb->get_var( "SELECT id FROM {$wpdb->prefix}wsi_queue WHERE id > '" . $this->data->id . "' AND (provider = 'google' OR provider = 'yahoo' OR provider = 'mail' OR provider = 'live' OR provider = 'foursquare') ORDER BY id ASC LIMIT 1" );

						$wpdb->query( "UPDATE {$wpdb->prefix}wsi_queue SET send_at = '$send_at' WHERE id = '$next_id' " );
					}

					//exit our sending routine
					break;
				}

			}//endforeach
		}
		//save stats
		Wsi_Logger::log_stat($this->data->provider, $this->data->user_id, $sent_on_batch, $this->data->id, $this->data->display_name, $this->data->wsi_obj_id);
		Wsi_Logger::log( "Mails finished sending queue #".$this->data->id);

		// we finish with this row, lets delete it
		if( $delete_row ) {
			$wpdb->query("DELETE FROM {$wpdb->prefix}wsi_queue WHERE id ='".$this->data->id."'");
			Wsi_Logger::log( "Mails deleting queue #".$this->data->id);
		}

		//IF we finish our batch and we haven't reach our limit we proccess next row in db
		if( $this->total_sent < $this->limit )
		{

			$queue_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wsi_queue WHERE provider = 'google' OR provider = 'mail' OR provider = 'yahoo' OR provider = 'live' OR provider = 'foursquare' ORDER BY id ASC LIMIT 1");

			//if we have more rows, proccess them
			if( isset($queue_data->id) )
			{
				Wsi_Logger::log( "Mails sending new batch");
				$this->setData($queue_data, $this->total_sent);

				$this->send();
			}

		}
		Wsi_Logger::log( "Mails sending routine finished");
		return $this->total_sent;
	}

	function php_mailer_settings($phpmailer) {
		$phpmailer->IsSMTP();
		$phpmailer->From 			= $this->get_from_email();
		$phpmailer->FromName 		= $this->get_from_name();

		if( $this->opts['send_with'] == 'gmail' ) {
			$phpmailer->SMTPAuth   = true;  // Authentication
			$phpmailer->Host       = 'smtp.gmail.com';
			$phpmailer->Username   = $this->opts['gmail_username'];
			$phpmailer->Password   = $this->opts['gmail_pass'];
			$phpmailer->SMTPSecure = 'ssl'; // Enable if required - 'tls' is another possible value
			$phpmailer->Port       = 465;    // SMTP Port
		} else {
			$phpmailer->SMTPAuth   = true;  // Authentication
			$phpmailer->Host       = $this->opts['smtp_server'];
			$phpmailer->Username   = $this->opts['smtp_username'];
			$phpmailer->Password   = $this->opts['smtp_pass'];
			$phpmailer->SMTPSecure = $this->opts['smtp_secure']; // Enable if required - 'tls' is another possible value
			$phpmailer->Port       = $this->opts['smtp_port'];    // SMTP Port
		}
		if ( $this->opts['enable_dev'] && ! DOING_AJAX )
		{
			$phpmailer->SMTPDebug = 1;
			return;
		}
	}

	/**
	 * Return the sender email either from a registered user or site email
	 * @return mixed
	 */
	private function get_from_email() {

		if( !empty( $this->data->user_id ) )
			$this->user_data = get_userdata( $this->data->user_id );

		if( !empty($this->_user_data->user_email) ) return apply_filters( 'wsi/emails/from_email',$this->_user_data->user_email);

		return apply_filters( 'wsi/emails/from_email', get_bloginfo('admin_email') );
	}

	/**
	 * Return the sender Name either from a registered user or site name
	 * @return mixed
	 */
	private function get_from_name() {

		if( !empty($this->data->display_name) ) return apply_filters( 'wsi/emails/from_name', $this->data->display_name );

		return apply_filters( 'wsi/emails/from_name', get_bloginfo('name') );

	}


	/**
	 * Function that sends the email
	 * @param $email_to
	 * @param $subject
	 * @param $content
	 * @param string $attachments
	 *
	 * @return string
	 */
	function send_email( $email_to, $subject, $content, $attachments = '' )
	{
		$headers = array(
			"Content-Type: text/html",
			"From: ". $this->get_from_name() ." <". $this->get_from_email() .">"
		);
		$headers = apply_filters('wsi/emails/headers', $headers);


		ob_start();
		@$result = wp_mail( $email_to, $subject, $content, $headers, $attachments );
		$errors  = ob_get_contents();
		@ob_end_clean();
		if( $result === false )
		{
			Wsi_Logger::log( "Wsi_Queue: Mail queue proccesing error - " . $errors);
		}

		return $errors;
	}

	function get_email_content($subject = null, $footer = null, $message = null)
	{
		$plain_message      = @Wsi_Queue::replaceShortcodes($this->data->id, $this->data->message);
		$subject_template   = empty( $subject ) ? stripslashes($this->data->subject) : $subject ;
		$footer_template    = empty( $footer ) ?  stripslashes($this->opts['footer']) : $footer ;
		$message_template   = empty( $message ) ? stripslashes($plain_message) : $message ;

		//if email template is disabled, return only text message
		if( defined('WSI_DISABLE_HTML') )
			return $plain_message;

		ob_start();

		wsi_get_template( 'email/email-body.php', array(
			'email_subject' => $subject_template,
			'email_footer'  => $footer_template,
			'emailContent' 	=> $message_template
		) );

		return ob_get_clean();
	}


}