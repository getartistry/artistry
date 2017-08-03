<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db002_user_css($plugin) { ?>
#footer-info { width: 100%; margin:0 auto; text-align: center !important; }
@media only screen and (min-width: 980px) { 
	#footer-bottom .et-social-icons { margin-bottom:-28px; } 
}
<?php 
}
add_action('wp_head.css', 'db002_user_css');