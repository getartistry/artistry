<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_MailChimp_Add_To_Group
 * @since 3.4.0
 */
class Action_MailChimp_Add_To_Group extends Action_MailChimp_Abstract {

	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Add Contact To Group', 'automatewoo' );
	}


	function load_fields() {

		$email = ( new Fields\Text() )
			->set_name( 'email' )
			->set_title( __( 'Contact email', 'automatewoo' ) )
			->set_description( __( 'You can use variables such as {{ customer.email }} here.', 'automatewoo' ) )
			->set_required()
			->set_variable_validation();

		$groups = ( new Fields\Select() )
			->set_name( 'groups' )
			->set_title( __( 'Groups', 'automatewoo' ) )
			->set_multiple()
			->set_required()
			->set_dynamic_options_reference( 'list' );

		$allow_add_to_list = ( new Fields\Checkbox() )
			->set_name( 'allow_add_to_list' )
			->set_title( __( "Add contact to list if missing?", 'automatewoo' ) )
			->set_default_to_checked();

		$this->add_list_field();
		$this->add_field( $email );
		$this->add_field( $groups );
		$this->add_field( $allow_add_to_list );

	}


	/**
	 * @param $field_name
	 * @param $reference_field_value
	 * @return array
	 */
	function get_dynamic_field_options( $field_name, $reference_field_value = false ) {

		$options = [];
		/** @var Fields\Select $field */
		$field = $this->get_field( $field_name );

		if ( $field && $field_name !== 'groups' ) {
			return [];
		}

		// if reference value is not set load the last saved value, used when initially loading an action page
		if ( ! $reference_field_value ) {
			$reference_field_value = $this->get_option( $field->dynamic_options_reference_field_name );
		}

		foreach ( Integrations::mailchimp()->get_list_interest_categories( $reference_field_value ) as $interest_category ) {
			foreach( $interest_category['interests'] as $interest_id => $interest_name ) {
				$options[ $interest_id ] = "{$interest_category['title']} - {$interest_name}" ;
			}
		}

		return $options;
	}


	function run() {

		$list_id = Clean::string( $this->get_option('list') );
		$email = Clean::email( $this->get_option( 'email', true ) );
		$interests = Clean::recursive( $this->get_option( 'groups' ) );
		$allow_add_to_list = (int) $this->get_option( 'allow_add_to_list' );

		if ( ! $list_id || ! $interests || ! $email ) {
			return;
		}

		if ( ! Integrations::mailchimp()->is_contact( $email, $list_id ) && ! $allow_add_to_list ) {
			return;
		}

		$group_updates = [];

		foreach( $interests as $interest_id ) {
			$group_updates[ $interest_id ] = true;
		}

		Integrations::mailchimp()->update_contact_interest_groups( $email, $list_id, $group_updates );
	}

}
