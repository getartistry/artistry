<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Send_Email_Raw
 * @since 3.6.0
 */
class Action_Send_Email_Raw extends Action_Send_Email {

	public $can_be_previewed = true;


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Send Email - Raw HTML [BETA]', 'automatewoo' );
		$this->description = __( "This action sends emails with only the HTML/CSS entered in the action's HTML field and is designed for advanced use only. This is different from the standard Send Email action, which inserts the email content into a template. Some variables may display unexpectedly due to the different CSS. Please note that you should include an unsubscribe link by using the variable {{ unsubscribe_url }}.", 'automatewoo' );
	}


	function load_fields() {
		parent::load_fields();
		$this->remove_field('email_heading' );
		$this->remove_field('preheader' );
		$this->remove_field('template' );
		$this->remove_field('email_content' );

		$include_aw_css = new Fields\Checkbox();
		$include_aw_css->set_name( 'include_aw_css' );
		$include_aw_css->set_title( __( 'Include AutomateWoo CSS', 'automatewoo' ) );
		$include_aw_css->set_default_to_checked( true );
		$include_aw_css->set_description( __( 'Checking this box adds the basic AutomateWoo CSS that is used to style variables to your custom HTML.', 'automatewoo' ) );

		$html = new Fields\Text_Area();
		$html->set_name( 'email_html' );
		$html->set_title( __( 'Email HTML', 'automatewoo' ) );
		$html->set_description( __( 'Any CSS included in the HTML will be automatically inlined.', 'automatewoo' ) );
		$html->set_rows(14);
		$html->set_variable_validation();
		$html->add_classes( 'automatewoo-field--monospace' );
		$html->set_required();

		$this->add_field( $include_aw_css );
		$this->add_field( $html );
	}



	/**
	 * Generates the HTML content for the email
	 * @return string|\WP_Error
	 */
	function preview() {
		$html = $this->get_option('email_html', true, true );
		$subject = Clean::string( $this->get_option( 'subject', true ) );
		$include_aw_css = (bool) $this->get_option('include_aw_css' );

		$current_user = get_user_by( 'id', get_current_user_id() );

		wp_set_current_user( 0 ); // no user should be logged in

		$email = new Workflow_Email( $this->workflow );
		$email->set_recipient( $current_user->get('user_email') );
		$email->set_subject( $subject );
		$email->set_raw_html( $html );
		$email->set_include_automatewoo_styles( $include_aw_css );

		return $email->get_html();
	}


	/**
	 * @param array $send_to
	 * @return \WP_Error|true
	 */
	function send_test( $send_to = [] ) {
		$html = $this->get_option('email_html', true, true );
		$subject = Clean::string( $this->get_option( 'subject', true ) );
		$include_aw_css = (bool) $this->get_option('include_aw_css' );

		wp_set_current_user( 0 ); // no user should be logged in

		foreach ( $send_to as $recipient ) {

			$email = new Workflow_Email( $this->workflow );
			$email->set_recipient( $recipient );
			$email->set_subject( $subject );
			$email->set_raw_html( $html );
			$email->set_include_automatewoo_styles( $include_aw_css );

			$sent = $email->send();

			if ( is_wp_error( $sent ) ) {
				return $sent;
			}
		}

		return true;
	}


	function run() {

		$html = $this->get_option('email_html', true, true );
		$subject = Clean::string( $this->get_option('subject', true ) );
		$include_aw_css = (bool) $this->get_option('include_aw_css' );

		$recipients = Clean::string( $this->get_option( 'to', true ) );
		$recipients = Emails::parse_recipients_string( $recipients );

		foreach ( $recipients as $recipient_email => $recipient_args ) {

			$email = new Workflow_Email( $this->workflow );
			$email->set_recipient( $recipient_email );
			$email->set_subject( $subject );
			$email->set_raw_html( $html );
			$email->set_include_automatewoo_styles( $include_aw_css );

			if ( $recipient_args['notracking'] ) {
				$email->set_tracking_enabled( false );
			}

			$sent = $email->send();

			if ( is_wp_error( $sent ) ) {
				$this->workflow->log_action_email_error( $sent, $this );
			}
			else {
				$this->workflow->log_action_note( $this, sprintf( __( 'Successfully sent to %s', 'automatewoo'), $recipient_email ) );
			}
		}
	}

}
