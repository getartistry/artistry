<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Custom_Function
 */
class Action_Custom_Function extends Action {


	function load_admin_details() {
		$this->title = __( 'Custom Function', 'automatewoo' );
	}


	function load_fields() {
		$function_name = new Fields\Text();
		$function_name->set_title( __( 'Function name', 'automatewoo'  ) );
		$function_name->set_name('function_name');
		$function_name->set_description( __( 'More about custom functions here.', 'automatewoo'  ) );

		$this->add_field($function_name);
	}


	function run() {
		$function = Clean::string( $this->get_option('function_name') );
		if ( function_exists( $function ) ) {
			call_user_func( $function, $this->workflow );
		}
	}

}
