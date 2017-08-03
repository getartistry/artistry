<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
@media only screen and (min-width: 981px) {
	#main-header { 
		-moz-transition: all 0s ease-in-out; 
		-webkit-transition: all 0s ease-in-out; 
		transition: all 0s ease-in-out; 
		display: none;
	}
	#page-container { 
		padding-top:0 !important; 
	}
	.et_secondary_nav_enabled #page-container { 
		padding-top:33px !important; 
	}
}
