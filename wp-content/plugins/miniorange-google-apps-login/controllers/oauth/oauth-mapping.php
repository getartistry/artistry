<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 08-02-2018
 * Time: 13:48
 */
//ToDo:- Complete all the intializations here.

$mo_oauth_client_am_username;
$oauth_client_am_email;
$oauth_client_am_last_name;
$oauth_client_am_group_name;
$oauth_is_free_plugin= TRUE;

$field_disabled=$oauth_is_free_plugin?"disabled":"";

include MOV_GSUITE_DIR . "views/oauth/oauth_attribute_role_mapping.php";