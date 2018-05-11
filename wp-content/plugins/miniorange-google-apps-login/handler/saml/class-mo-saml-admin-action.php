<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 16-02-2018
 * Time: 13:56
 */

class Mo_SAML_Admin_action {

	/**
	 *This function is called by control handler
	 * Updates the mo_saml_enable_cloud_broker option value.
	 */
	static function initialize_SAML(){
		update_mo_gsuite_option( 'mo_saml_free_version', 1 );
		update_mo_gsuite_option('mo_saml_enable_cloud_broker', 'false');
	}
}
