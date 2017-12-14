<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

/* Set the hover cover */
#top-header #et-info-phone a:hover, 
#top-header #et-info a:hover span#et-info-phone, 
#top-header #et-info a:hover span#et-info-email, 
#top-header #et-info #db-info-text a:hover, 
#top-header .et-social-icon a:hover,
#top-header #et-secondary-nav a:hover { 
	color: <?php echo htmlentities(@$option['hovercol']); ?> !important;
	opacity: 1 !important;
}

/* Add a transition effect for elements that don't already have it */
#top-header #et-info #db-info-text a,
#top-header #et-info #db-info-text a:hover,
#top-header #et-info a span#et-info-phone,
#top-header #et-info a:hover span#et-info-phone
 {
	-webkit-transition: color .5s;
	-moz-transition: color .5s;
	transition: color .5s;
}