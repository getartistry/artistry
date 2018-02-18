<?php

/**
 * Customizer output: Post Layout section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_post_layout_output() {

	$dce_output = '';

	if ( 'custom' == get_theme_mod( 'dce_post_layout', 'default' ) ) {

		$dce_output .= ' .dce_row {padding: 0;}' . "\n";

		$dce_output .= ' .dce_post_title, .dce_post_meta, .dce_featured_image, .dce_post_content, .dce_post_shortcode, .dce_post_code, .dce_post_comments, .dce_spacer {padding: 0;}' . "\n";
	
		$dce_output .= ' .dce_hero_image .et_pb_parallax_css, .dce_background_image .et_pb_parallax_css {overflow: hidden; position: absolute; width: 100%; height: 100%; background-repeat: no-repeat; background-attachment: fixed; background-position: top center; background-size: cover;}' . "\n";

		$dce_output .= ' #dce-custom-post.dce-fullwidth {width: 100%; max-width: 100%;padding: 0;}' . "\n";

		$dce_output .= ' #dce-custom-post.dce-sidebar .dce_row, #dce-custom-post.dce-sidebar .et_pb_row {width: 100%;}' . "\n";

		$dce_output .= ' #dce-custom-post.dce-sidebar .dce_hero_image .dce_row, #dce-custom-post.dce-sidebar .dce_background_image .dce_row {width: 80%;}' . "\n";

	}

	return $dce_output;

}
