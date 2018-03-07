<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_MailChimp_Abstract
 */
abstract class Action_MailChimp_Abstract extends Action {

	function load_admin_details() {
		$this->group = __( 'MailChimp', 'automatewoo' );
	}


	/**
	 * @return Fields\Select
	 */
	function add_list_field() {

		$list_select = ( new Fields\Select() )
			->set_title( __( 'List', 'automatewoo' ) )
			->set_name( 'list' )
			->set_options( Integrations::mailchimp()->get_lists() )
			->set_required();

		$this->add_field( $list_select );
		return $list_select;
	}

}
