<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	$('#et-info .et-social-icons').prependTo('#et-secondary-menu');
	$('#et-secondary-menu .et_duplicate_social_icons').remove();
});