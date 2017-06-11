<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db008_user_css($plugin) { ?>
@media only screen and ( min-width: 981px ) { 

	/* Vertically center the top navigation */
	#et-top-navigation { display:table-cell; vertical-align: middle; float:none !important;}
	.container { display:table; }

	/* Right align the contents of the top navigation area */
	#et-top-navigation { text-align:right; }
	#et-top-navigation > * { text-align:left; }
	#top-menu-nav, #et_top_search { float:none !important; display:inline-block !important}
	#et_top_search { vertical-align: top !important; margin-top:3px }

}
<?php
}
add_action('wp_head.css', 'db008_user_css');