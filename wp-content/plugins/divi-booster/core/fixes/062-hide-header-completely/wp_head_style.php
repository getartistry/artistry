<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
/* Hide the header */
#main-header { display:none; }
#page-container { 
	padding-top:0px !important; 
	margin-top:-1px !important 
}

/* Adjust padding for transparent headers */
.et_transparent_nav #main-content .container {
    padding-top: 58px !important;
}