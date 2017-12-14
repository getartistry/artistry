<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db135_user_css($plugin) { 
	list($name, $option) = $plugin->get_setting_bases(__FILE__); ?>

@media only screen and (max-width: 980px) {
	#top-header > .container,
	#main-header > .container,
	#et_search_outer > .container,
	#main-content > .container,
	div.et_pb_row,
	div.et_pb_row.et_pb_row_fullwidth {
       width: <?php echo intval(@$option['mobilewidth']); ?>% !important; 
   }
}

<?php 
}
add_action('wp_head.css', 'db135_user_css');
