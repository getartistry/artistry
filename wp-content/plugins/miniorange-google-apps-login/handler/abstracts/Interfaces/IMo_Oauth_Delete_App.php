<?php
/**
 * Created by PhpStorm.
 * User: SushmaSingh
 * Date: 28-04-2018
 * Time: 16:55
 */

Interface IMo_Oauth_Delete_App {
	/**
	 * Delete the app from the configured list.
	 * @param $app_name
	 *
	 * @return mixed
	 */
	public function delete_app($app_name);
}