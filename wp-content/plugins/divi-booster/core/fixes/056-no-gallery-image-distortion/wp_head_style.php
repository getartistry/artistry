<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
.et_pb_gallery_image img { 
	min-width:0 !important; min-height:0 !important; 
	position: relative;
	left: 50%; top: 50%;
	-webkit-transform: translateY(-50%) translateX(-50%);
	-ms-transform: translateY(-50%) translateX(-50%);
	transform: translateY(-50%) translateX(-50%);
}