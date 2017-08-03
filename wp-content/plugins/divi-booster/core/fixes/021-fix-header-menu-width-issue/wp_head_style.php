<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
/* Hide the menu until it is resized */
#et-top-navigation { display:none; }

/* Centered layout */
@media only screen and (min-width:1100px) { 
	.et_header_style_centered #top-menu { max-width:980px; }
}
@media only screen and (max-width:1099px) { 
	.et_header_style_centered #top-menu { max-width:860px; }
}
.et_header_style_centered #et-top-navigation{display:block !important}