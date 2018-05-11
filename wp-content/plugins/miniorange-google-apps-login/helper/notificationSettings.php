<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MO_GSuite_Notificaion_Settings {

	public $sendSMS;
	public $sendEmail;
	public $phoneNumber;
	public $fromEmail;
	public $fromName;
	public $toEmail;
	public $toName;
	public $subject;
	public $bccEmail;
	public $message;

	public function __construct() {
		if ( func_num_args() < 4 ) {
			$this->createSMSMO_GSuite_Notificaion_Settings( func_get_arg( 0 ), func_get_arg( 1 ) );
		} else {
			$this->createEmailMO_GSuite_Notificaion_Settings( func_get_arg( 0 ), func_get_arg( 1 ), func_get_arg( 2 ), func_get_arg( 3 ), func_get_arg( 4 ) );
		}
	}

	public function createSMSMO_GSuite_Notificaion_Settings( $phoneNumber, $message ) {
		$this->sendSMS     = true;
		$this->phoneNumber = $phoneNumber;
		$this->message     = $message;
	}

	public function createEmailMO_GSuite_Notificaion_Settings( $fromEmail, $fromName, $toEmail, $subject, $message ) {
		$this->sendEmail = true;
		$this->fromEmail = $fromEmail;
		$this->fromName  = $fromName;
		$this->toEmail   = $toEmail;
		$this->toName    = $toEmail;
		$this->subject   = $subject;
		$this->bccEmail  = '';
		$this->message   = $message;
	}
}