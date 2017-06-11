<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>

/* Font */
.et-social-icon a.socicon:before { 
	font-family: "socicon" !important; 
}

/* Icon positioning */
.et-social-icon a.socicon { top: 1px; }

/* Footer */
#footer-bottom { 
	overflow:hidden;
}

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
    background: rgba(255, 255, 255, 0.3); !important;
}