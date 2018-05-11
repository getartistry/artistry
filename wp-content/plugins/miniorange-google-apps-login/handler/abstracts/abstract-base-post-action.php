<?php
/**
 * Created by PhpStorm.
 * User: Shailesh
 * Date: 15-03-2018
 * Time: 13:00
 *
 * This abstract class aims to handle post data.
 *
 */

abstract class BasePostAction {

	/**
	 * BasePostAction constructor. hooks to admin_init to get the post data
	 */
	function __construct(){
		add_action('admin_init',array($this,'handle_post_data'));
	}

	/**
	 * This function will get the post parameters for the first time. This function will be used for manipulation.
	 * @return mixed
	 */
	abstract function handle_post_data();

	/**
	 * This function is used to check if the post data has some validation errors.
	 * @return mixed
	 */
	abstract function validate_post_data($getData);

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	abstract function route_post_data($getData);


	public function check_if_post_empty($getData){
		foreach ($getData as $key=>$value){
			if(Mo_GSuite_Utility::isBlank($getData[$key])){
				do_action('mo_gsuite_registration_show_message',Mo_GSuite_Messages::showMessage('POST_ARRAY_EMPTY'),'ERROR');
				return true;
			}
		}
		return false;
	}
}