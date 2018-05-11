<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 12-02-2018
 * Time: 18:03
 */

$existing_applist = get_mo_gsuite_option( 'mo_oauth_apps_list_test' );
include MOV_GSUITE_DIR . "views/oauth/instructions/class-mo-oauth-instruction-html.php";
include MOV_GSUITE_DIR . "views/oauth/oauth-configured-applist.php";

function display_app_list( $existing_applist,$disabled ) {
	foreach ( $existing_applist as $key => $app ) {

		echo "
				<td>" . $key . "</td>
				<td>
				<a href='#' ".$disabled." onclick='ajax_action(\"" . $key . "/*\",\"edit\")'>Edit Application</a> |";
				
				/*| <a href='#' ".$disabled." onclick='ajax_action(\"" . $key . "\",\"attribute_mapping\")'>Attribute Mapping</a> |
				 
				 <a href='#' ".$disabled." onclick='ajax_action(\"" . $key . "\",\"role_mapping\")'>Role Mapping</a> |*/

		echo	"<a id= 'delete_anchor' ".$disabled." href='#' onclick='ajax_action(\""         . $key . "\",\"delete\")'> Delete </a> | 
				  
				  <a id='instruction_anchor' ".$disabled." onclick='app_instructions(\"" . $key . "\")' href='#'> How to Configure? </a>
				  
				  </td>
			  
		    ";
	}
}