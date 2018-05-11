<?php
/**
 * Created by PhpStorm.
 * User: SushmaSingh
 * Date: 28-04-2018
 * Time: 16:56
 */

Interface IMo_Oauth_Update_App {

	/**
	 * Updates the value of the configured app.
	 * @param $app_name
	 *
	 * @return mixed
	 */
	public function update_app($app_name);
}