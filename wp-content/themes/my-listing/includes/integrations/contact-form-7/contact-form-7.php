<?php

/*
 * Contact Form 7 Integration.
 */

class CASE27_Contact_Form_7_Integration {

	/*
	 * Constructor.
	 */
	public function __construct() {
		add_filter('wpcf7_form_hidden_fields', [$this, 'add_custom_hidden_fields']);
		add_filter('wpcf7_mail_components', [$this, 'add_mail_recipients'], 100, 3);
	}


	/*
     * Add custom hidden fields to the form html markup. This is needed for
     * listing contact forms, where we need the post id to make sure it's a 'job_listing',
     * and need to add a placeholder for the list of email recipients, which in each listing will
     * be replaced by unqiue email(s) for each different listing.
	 */
	public function add_custom_hidden_fields($fields)
	{
		$fields['_case27_recipients'] = '%case27_recipients%';
		$fields['_case27_post_id'] = get_the_ID();

		return $fields;
	}


	/*
     * For 'job_listing' contact forms, update the 'recipient' component with
     * the email(s) of the requested listing.
	 */
	public function add_mail_recipients( $components, $form, $obj )
	{
		if ( $obj->name() !== 'mail' ) {
			return $components;
		}

		if ( empty( $_POST['_case27_post_id'] ) || empty( $_POST['_case27_recipients'] ) ) {
			return $components;
		}

		$postid = $_POST['_case27_post_id'];
		$recipients = explode( '|', $_POST['_case27_recipients'] );

		if ( ! ( $listing = \CASE27\Classes\Listing::get( $postid ) ) || ! is_array( $recipients ) ) {
			return $components;
		}

		$emails = [];

		foreach ( $recipients as $field_key ) {
			if ( ! ( $email = $listing->get_field( $field_key ) ) ) {
				continue;
			}

			if ( ! is_email( $email ) ) {
				continue;
			}

			$emails[] = $email;
		}

		if ( count( $emails ) ) {
			if ( isset( $components['recipient'] ) && is_string( $components['recipient'] ) ) {
				$components['recipient'] .= ',' . join( ',', $emails );
			} else {
				$components['recipient'] = join( ',', $emails );
			}
		}

		return $components;
	}
}

new CASE27_Contact_Form_7_Integration;