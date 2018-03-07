<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class User_Role
 */
class User_Role extends Select {

	protected $name = 'user_type'; // legacy name


	/**
	 * @param bool $allow_all
	 */
	function __construct( $allow_all = true ) {
		parent::__construct( true );

		$this->set_title( __( 'User role', 'automatewoo' ) );

		if ( $allow_all ) {
			$this->set_placeholder('[Any]');
		}

		global $wp_roles;

		foreach( $wp_roles->roles as $key => $role ) {
			$this->options[$key] = $role['name'];
		}
	}

}
