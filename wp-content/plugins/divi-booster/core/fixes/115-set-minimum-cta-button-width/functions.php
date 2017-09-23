<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db115_user_css($plugin) { 
	list($name, $option) = $plugin->get_setting_bases(__FILE__); ?>

.et_pb_button { min-width: <?php echo intval(@$option['ctawidth']); ?>px; text-align:center; } 

<?php 
}
add_action('wp_head.css', 'db115_user_css');