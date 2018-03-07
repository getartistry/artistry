<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Memberships_Abstract
 * @since 2.8
 */
abstract class Action_Memberships_Abstract extends Action {

	function load_admin_details() {
		$this->group = __( 'Memberships', 'automatewoo' );
	}

}
