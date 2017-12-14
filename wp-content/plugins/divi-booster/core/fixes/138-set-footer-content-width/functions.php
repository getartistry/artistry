<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db138_user_css($plugin) { 
	list($name, $option) = $plugin->get_setting_bases(__FILE__); ?>

@media only screen and (max-width: 980px) {
	#main-footer > .container,
	#et-footer-nav > .container,
	#footer-bottom > .container {
       width: <?php echo intval(@$option['mobilewidth']); ?>% !important; 
   }
}

<?php 
}
add_action('wp_head.css', 'db138_user_css');
