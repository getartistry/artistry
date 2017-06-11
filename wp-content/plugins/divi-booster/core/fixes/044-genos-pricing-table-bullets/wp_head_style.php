<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
.et_pb_pricing .et_pb_pricing li:before,
.et_pb_pricing .et_pb_pricing li > span:before {
	background-attachment: scroll;
	background-clip: border-box;
	background-color: rgba(0, 0, 0, 0);
	background-image: url("<?php $url = plugin_dir_url(__FILE__); $url = preg_replace('#^http(s)?:#', '', $url); echo $url; ?>green-plus.png");
	background-origin: padding-box;
	background-position: 0 0;
	background-repeat: no-repeat;
	background-size: 50% 50% !important;
	border: medium none transparent !important;
	color: transparent;
	height: 16px;
	margin-left: -1px;
	margin-top: -5px;
	width: 16px;}

.et_pb_pricing li.et_pb_not_available:before,
.et_pb_pricing li.et_pb_not_available > span:before {
	background-attachment: scroll;
	background-clip: border-box;
	background-color: rgba(0, 0, 0, 0);
	background-image: url("<?php $url = plugin_dir_url(__FILE__); $url = preg_replace('#^http(s)?:#', '', $url); echo $url; ?>red-x.png");
	background-origin: padding-box;
	background-position: 0 0;
	background-repeat: no-repeat;
	background-size: 50% 50% !important;
	border: medium none transparent !important;
	color: transparent;
	height: 16px;
	margin-left: -1px;
	margin-top: -5px;
	width: 16px;}