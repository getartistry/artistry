<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
/* === Distraction-free EDD checkout === */

/* Hide Divi sidebar on checkout */
.edd-checkout #sidebar,
.edd-checkout #main-content .container:before {
	display: none;
}

/* Hide the secondary header */
.edd-checkout #top-header { display: none;  }

/* Hide footer widget areas and menu */
.edd-checkout #main-footer > .container,
.edd-checkout #et-footer-nav {
    display: none;
}

/* Hide main header links */
.edd-checkout #et-top-navigation {
    visibility: hidden !important;
}

/* Hide the social icons in the footer */
.edd-checkout #footer-bottom .et-social-icons {
    display: none;
}