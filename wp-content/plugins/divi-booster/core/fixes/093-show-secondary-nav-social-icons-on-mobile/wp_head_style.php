<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
@media only screen and (max-width: 767px) {
    div#et-secondary-menu { 
        display: block !important; 
		margin-top: 0px !important;
		padding-top: .75em;
    } 
	#et-secondary-menu>div.et_duplicate_social_icons>ul>li a {
		display: block;
		padding-bottom: .75em;
	}
}

@media only screen and (min-width: 768px) and (max-width: 980px) {
	div#et-secondary-menu { 
        display: block !important; 
		padding-top: .75em; 
    } 
}
