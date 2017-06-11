<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	var logowidth=($("#logo").length)?($("#logo").get(0).width):0;
	$("<style>@media only screen and (min-width:1100px) { #top-menu { max-width:"+(980-logowidth)+"px; } } @media only screen and (max-width:1099px) { #top-menu { max-width:"+(860-logowidth)+"px; } } #et-top-navigation { display:table-cell !important } </style>").appendTo("head");
});