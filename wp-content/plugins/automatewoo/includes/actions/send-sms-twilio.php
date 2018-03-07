<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Send_SMS_Twilio
 */
class Action_Send_SMS_Twilio extends Action {


	function load_admin_details() {
		$this->title = __( 'Send SMS (Twilio)', 'automatewoo' );
		$this->group = __( 'SMS', 'automatewoo' );
	}


	function load_fields() {
		$sms_recipient = ( new Fields\Text() )
			->set_name( 'sms_recipient' )
			->set_title( __( 'SMS recipients', 'automatewoo'  ) )
			->set_description( __( 'Multiple recipient numbers must be separated by commas. When using the {{ customer.phone }} variable the country code will be added automatically, if not already entered by the customer, by referencing the billing country.', 'automatewoo' ) )
			->set_variable_validation()
			->set_required();

		$sms_body = ( new Fields\Text_Area() )
			->set_name( 'sms_body' )
			->set_title( __( 'SMS body', 'automatewoo'  ) )
			->set_rows(4)
			->set_variable_validation()
			->set_required();

		$this->add_field( $sms_recipient );
		$this->add_field( $sms_body );
	}


	function run() {

		$recipients = Phone_Numbers::parse_list( Clean::string( $this->get_option( 'sms_recipient', true ) ) );
		$sms_body = Clean::textarea( $this->get_option( 'sms_body', true ) );

		$customer = $this->workflow->data_layer()->get_customer();

		if ( empty( $recipients ) ) {
			$this->workflow->log_action_error( $this, __( 'No valid recipients', 'automatewoo') );
			return;
		}

		if ( empty( $sms_body ) ) {
			$this->workflow->log_action_error( $this, __( 'Empty message body', 'automatewoo') );
			return;
		}

		if ( $this->workflow->is_ga_tracking_enabled() ) {
			$replacer = new Replace_Helper( $sms_body, [ $this->workflow, 'append_ga_tracking_to_url' ], 'text_urls' );
			$sms_body = $replacer->process();
		}

		Integrations::load_twilio();

		$from = AW()->options()->twilio_from;
		$sid = AW()->options()->twilio_auth_id;
		$token = AW()->options()->twilio_auth_token;

		$api = new \Services_Twilio( $sid, $token );

		foreach ( $recipients as $recipient ) {

			// parse phone number
			if ( $customer && $recipient == $customer->get_billing_phone() ) {
				$recipient = Phone_Numbers::parse( $recipient, $customer->get_billing_country() );
			}
			else {
				$recipient = Phone_Numbers::parse( $recipient );
			}

			try {
				$message = $api->account->messages->sendMessage( $from, $recipient, $sms_body );
				$this->workflow->log_action_note( $this, sprintf( __( 'Successfully sent to %s', 'automatewoo' ), $recipient ) );
			}
			catch( \Exception $e ) {
				$this->workflow->log_action_error( $this, $e->getMessage() );
			}
		}
	}

}
