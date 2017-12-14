<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); 

$url = empty($option['logourl'])?'':$option['logourl'];
if (!preg_match('#^(http:|https:|tel:|skype:|/)#', $url)) { $url = "http://$url"; }
?>

jQuery(function($){
	$('.logo_container a').attr('href','<?php esc_html_e(addslashes($url)); ?>'); // Divi
	$('.et_extra a.logo').attr('href','<?php esc_html_e(addslashes($url)); ?>'); // Extra
}); 