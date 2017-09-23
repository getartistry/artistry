<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db015_user_css($plugin) {
	list($name, $option) = $plugin->get_setting_bases(__FILE__); ?>

	#page-container #top-header { 
		background-color: <?php echo htmlentities(@$option['bgcol']); ?> !important; 
	}
	#top-header #et-info-phone a:hover, 
	#top-header #et-info a:hover span#et-info-email, 
	#top-header .et-social-icon a:hover { 
		color: <?php echo htmlentities(@$option['hovercol']); ?> !important; 
	}
	#top-header #et-info-phone, 
	#top-header #et-info-phone a, 
	#top-header #et-info-email, 
	#top-header .et-social-icon a { 
		color: <?php echo htmlentities(@$option['col']); ?> !important; 
	}
	#top-header #et-info-phone, 
	#top-header #et-info-phone a, 
	#top-header #et-info-email, 
	#top-header .et-social-icon a { 
		font-size:<?php echo htmlentities(@$option['fontsize']); ?>% !important; 
	}
<?php 
}
add_action('wp_head.css', 'db015_user_css');


