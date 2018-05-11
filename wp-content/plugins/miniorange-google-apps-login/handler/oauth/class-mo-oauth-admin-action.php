<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 09-02-2018
 * Time: 16:45
 */

//ToDo Move this to intializer
class Mo_Oauth_Admin_Action {
	
	public static function initialize_OAuth(){
		update_option( 'mo_gsuite_oauth_google_enable','1');
	}
}