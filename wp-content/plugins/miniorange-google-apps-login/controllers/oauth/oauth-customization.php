<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 08-02-2018
 * Time: 10:31
 */

$icon_width  = get_mo_gsuite_option( 'mo_oauth_icon_width' );
$icon_height = get_mo_gsuite_option( 'mo_oauth_icon_height' );
$icon_margin = get_mo_gsuite_option( 'mo_oauth_icon_margin' );
$custom_CSS  = get_mo_gsuite_option( 'mo_oauth_icon_configure_css');

include MOV_GSUITE_DIR . "views/oauth/oauth_customization.php";