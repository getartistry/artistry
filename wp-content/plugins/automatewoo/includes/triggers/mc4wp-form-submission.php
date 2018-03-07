<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_MC4WP_Form_Submission
 * @since 3.0.0
 */
class Trigger_MC4WP_Form_Submission extends Trigger {

	public $supplied_data_items = [ 'customer' ];


	function load_admin_details() {
		$this->title = __( 'MailChimp for WordPress - Form Submission', 'automatewoo' );
		$this->description = __( 'This trigger fires after a MailChimp for WordPress form is successfully submitted.', 'automatewoo' );
		$this->group = __( 'MailChimp for WordPress', 'automatewoo' );
	}
	

	function register_hooks() {
		add_action( 'mc4wp_form_success', [ $this, 'catch_hooks' ] );
	}
	
	
	function load_fields() {

		$forms = mc4wp_get_forms();
		$options = [];

		foreach( $forms as $form ) {
			$options[ $form->ID ] = $form->name;
		}
		
		$form = ( new Fields\Select() )
			->set_title( __( 'Form', 'automatewoo' ) )
			->set_name('form_id')
			->set_options( $options )
			->set_description( __( 'Choose which MailChimp for WordPress form this workflow should trigger for.', 'automatewoo' ) )
			->set_required();

		$this->add_field( $form );
	}


	/**
	 * @param \MC4WP_Form $form
	 */
	function catch_hooks( $form ) {

		if ( ! $this->has_workflows() ) {
			return;
		}

		$form_data = $form->get_data();
		
		if ( empty( $form_data[ 'EMAIL' ] ) ) {
			return;
		}
		
		if ( ! $customer = Customer_Factory::get_by_email( $form_data[ 'EMAIL' ] )) {
			return;
		}

		// ensure language is set
		if ( Language::is_multilingual() ) {
			$customer->update_language( Language::get_current() );
		}

		foreach ( $this->get_workflows() as $workflow ) {
			
			$form_id = Clean::id( $workflow->get_trigger_option( 'form_id' ) );

			if ( ! $form_id || $form_id != $form->ID ) {
				continue;
			}
			
			$workflow->maybe_run([
				'customer' => $customer,
			]);
			
		}
	}

}
