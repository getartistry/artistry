<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>

/* Font */
.et-social-icon a.socicon:before { 
	font-family: "socicon" !important; 
}

/* Icon positioning */
.et-social-icon a.socicon { top: 1px; }
.et-social-icon a.socicon:not(.et-extra-icon) { margin-right: 4px; }

/* Fix hover cutoff issue */
#et-secondary-menu .et-social-icon a.socicon {
	width: 16px;
	margin-right: -2px;
}
#footer-bottom .et-social-icon a.socicon {
	width: 40px;
    margin-left: -6px;
    margin-right: -6px;
}

/* Extra */
a.et-extra-icon.socicon:before{
	font-family:"socicon" !important
}
#et-info .et-extra-social-icons .et-extra-icon:hover {
    background: rgba(255, 255, 255, 0.3) !important;
}

/* === Icon adjustments === */

#top-header .et-social-icon a.socicon-imdb {
    font-size: 31px;
    margin-top: -16px;
    top: 9px;
}
#top-header .et-social-icon a.socicon-imdb:before {
    font-size: 31px;
    text-shadow: none;
}
#footer-bottom .et-social-icon a.socicon-imdb {
	font-size: 40px;
    margin-top: -16px;
    top: 9px;
}