<?php
namespace ElementPack\Modules\UserRegister;

use ElementPack\Base\Element_Pack_Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	public function get_name() {
		return 'user-register';
	}

	public function get_widgets() {

		$widgets = [
			'User_Register',
		];
		
		return $widgets;
	}


	public static function get_recaptcha() {
		$attr = array( 'data-theme' => 'dark', );
		do_action( 'recaptcha_print' , $attr );
	}
}
