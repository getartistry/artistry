<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

add_filter('et_theme_image_sizes', 'wtfdivi096_remove_featured_post_cropping');

function wtfdivi096_remove_featured_post_cropping($sizes) {
	if (isset($sizes['1080x675'])) { 
		unset($sizes['1080x675']); 
		$sizes['1080x9998'] = 'et-pb-post-main-image-fullwidth';
	}
	return $sizes; 
}
?>