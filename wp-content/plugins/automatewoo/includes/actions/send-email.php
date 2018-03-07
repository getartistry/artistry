<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Send_Email
 */
class Action_Send_Email extends Action {

	public $can_be_previewed = true;


	function load_admin_details() {
		$this->title = __( 'Send Email', 'automatewoo' );
		$this->group = __( 'Email', 'automatewoo' );
		$this->description = __( "This action allows you to send a HTML or plain text email. If you wish to send a HTML email choose the WooCommerce Default as your template. This means the email will match the style of your WooCommerce transactional emails.", 'automatewoo' );
	}


	function load_fields() {

		$to = ( new Fields\Text() )
			->set_name( 'to' )
			->set_title( __( 'To', 'automatewoo' ) )
			->set_description( __( 'Enter emails here or use variables such as {{ customer.email }}. Multiple emails can be separated by commas. Add <b>--notracking</b> after an email to disable open and click tracking for that recipient.', 'automatewoo' ) )
			->set_placeholder( __( 'E.g. {{ customer.email }}, admin@example.org --notracking', 'automatewoo' ) )
			->set_variable_validation()
			->set_required();

		$subject = ( new Fields\Text() )
			->set_name ('subject' )
			->set_title( __( 'Email subject', 'automatewoo' ) )
			->set_variable_validation()
			->set_required();

		$heading = ( new Fields\Text() )
			->set_name( 'email_heading' )
			->set_title( __('Email heading', 'automatewoo' ) )
			->set_variable_validation()
			->set_description( __( 'The appearance will depend on your email template. Not all templates support this field.', 'automatewoo' ) );

		$preheader = ( new Fields\Text() )
			->set_name( 'preheader' )
			->set_title( __('Email preheader', 'automatewoo' ) )
			->set_variable_validation()
			->set_description( __( 'A preheader is a short text summary that follows the subject line when an email is viewed in the inbox. If no preheader is set the first text found in the email is used.', 'automatewoo' ) );

		$template = ( new Fields\Select( false ) )
			->set_name('template')
			->set_title( __('Template', 'automatewoo' ) )
			->set_options( Emails::get_email_templates() );

		$email_content = ( new Fields\Email_Content() ); // no easy way to define data attributes

		$this->add_field( $to );
		$this->add_field( $subject );
		$this->add_field( $heading );
		$this->add_field( $preheader );
		$this->add_field( $template );
		$this->add_field( $email_content );
	}


	/**
	 * @param $content
	 * @return mixed
	 */
	private function sanitize_email_content( $content ) {

		add_filter( 'safe_style_css', [ $this, 'filter_safe_css' ] );

		$allowed_html = wp_kses_allowed_html('post');

		// allow inline styles
		$allowed_html['style'] = [
			'type' => true
		];

		$allowed_html['script'] = [];

		$content = wp_kses( $content, $allowed_html );

		remove_filter( 'safe_style_css', [ $this, 'filter_safe_css' ] );

		return $content;
	}


	/**
	 * @param array $css
	 * @return array
	 */
	function filter_safe_css( $css ) {
		$css[] = '-webkit-border-radius';
		$css[] = '-moz-border-radius';
		$css[] = 'border-radius';
		$css[] = 'display';
		$css[] = 'text-transform';
		return $css;
	}


	/**
	 * Generates the HTML content for the email
	 * @return string|\WP_Error
	 */
	function preview() {
		$current_user = get_user_by( 'id', get_current_user_id() );

		// no user should be logged in
		wp_set_current_user( 0 );

		$email = new Workflow_Email( $this->workflow );
		$email->set_recipient( $current_user->get('user_email') );
		$email->set_subject( Clean::string( $this->get_option( 'subject', true ) ) );
		$email->set_heading( Clean::string( $this->get_option('email_heading', true ) ) );
		$email->set_preheader( trim( Clean::string( $this->get_option( 'preheader', true ) ) ) );
		$email->set_template( Clean::string( $this->get_option( 'template' ) ) );
		$email->set_content( $this->sanitize_email_content( $this->get_option('email_content', true, true ) ) );

		return $email->get_html();
	}


	/**
	 * Generates the HTML content for the email
	 * @param array $send_to
	 * @return string|\WP_Error|true
	 */
	function send_test( $send_to = [] ) {

		$email_heading = Clean::string( $this->get_option('email_heading', true ) );
		$email_content = $this->sanitize_email_content( $this->get_option('email_content', true, true ) );
		$subject = Clean::string( $this->get_option( 'subject', true ) );
		$preheader = trim( Clean::string( $this->get_option( 'preheader', true ) ) );
		$template = Clean::string( $this->get_option( 'template' ) );

		wp_set_current_user( 0 ); // no user should be logged in

		foreach ( $send_to as $recipient ) {

			$email = new Workflow_Email( $this->workflow );
			$email->set_recipient( $recipient );
			$email->set_subject( $subject );
			$email->set_heading( $email_heading );
			$email->set_preheader( $preheader );
			$email->set_template( $template );
			$email->set_content( $email_content );

			$sent = $email->send();

			if ( is_wp_error( $sent ) ) {
				return $sent;
			}
		}

		return true;
	}


	function run() {

		$email_heading = Clean::string( $this->get_option('email_heading', true ) );
		$email_content = $this->sanitize_email_content( $this->get_option('email_content', true, true ) );
		$subject = Clean::string( $this->get_option('subject', true ) );
		$preheader = Clean::string( $this->get_option( 'preheader', true ) );
		$template = Clean::string( $this->get_option( 'template' ) );

		$recipients = Clean::string( $this->get_option( 'to', true ) );
		$recipients = Emails::parse_recipients_string( $recipients );

		foreach ( $recipients as $recipient_email => $recipient_args ) {

			$email = new Workflow_Email( $this->workflow );
			$email->set_recipient( $recipient_email );
			$email->set_subject( $subject );
			$email->set_heading( $email_heading );
			$email->set_preheader( $preheader );
			$email->set_template( $template );
			$email->set_content( $email_content );

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
