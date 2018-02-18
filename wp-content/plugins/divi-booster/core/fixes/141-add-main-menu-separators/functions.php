<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

function db141_user_css($plugin) { ?>
#top-menu > .menu-item + .menu-item:before {
    content: '|';
    position: absolute;
    left: -16px; 
    font-size: smaller;
    top: -1px;
}
	<?php 
}
add_action('wp_head.css', 'db141_user_css');