<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 19-04-2018
 * Time: 01:45
 */

Interface IMo_Oauth_Request {

	/**
	 * @param $getData Value of the given app.
	 *
	 * @return String string of request URI
	 */
	function build_oauth_request($getData);

	/**
	 * This will hit the Oauth request URI
	 * @param $getData
	 *
	 * @return mixed
	 */
	function send_oauth_request($getData);

}