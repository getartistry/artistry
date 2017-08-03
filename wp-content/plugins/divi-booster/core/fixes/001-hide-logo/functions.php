<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

function db001_user_css($plugin) { 
	?>#logo { display:none !important; }<?php 
}
add_action('wp_head.css', 'db001_user_css');