<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

function db119_user_js($plugin) { 
	list($name, $option) = $plugin->get_setting_bases(__FILE__);
	?>
	jQuery(function($){
		$('#et-info-phone').wrap(function(){
			var num = <?php echo (empty($option['phonenum']))?'$(this).text()':"'".htmlentities(addslashes($option['phonenum']))."'"?>;
			num = num.replace(/[^0-9+]+/g, '-'); // sanitize
			num = num.replace(/^[-]|[-]$/g, ''); // trim
			return '<a href="tel:'+num+'"></a>';
		});
	});
<?php 
}
add_action('wp_footer.js', 'db119_user_js');

function db119_user_css($plugin) { ?>
#et-info-phone:hover { 
	opacity: 0.7; 
	-moz-transition: all 0.4s ease-in-out; 
	-webkit-transition: all 0.4s ease-in-out; 
	transition: all 0.4s ease-in-out; 
}
	
<?php 
}
add_action('wp_head.css', 'db119_user_css'); 