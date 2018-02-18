<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

function db140_user_js($plugin) { ?>
jQuery(function($) {
	$('.single-post .entry-content').find('a[href$=".gif"], a[href$=".jpg"], a[href$=".png"], a[href$=".bmp"]').magnificPopup({type:'image'});
});
<?php 
}
add_action('wp_footer.js', 'db140_user_js');
