<?php

/**
 * Customizer output: Post Layout section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_post_custom_styles_output() {

	$dce_output = '';

	if ( 'custom' == get_theme_mod( 'dce_post_layout', 'default' ) ) {

		$dce_output .= ' .dce_hero_image {padding: ' . get_theme_mod( 'dce_post_custom_hero_top_padding', 100 ) . 'px 0 ' . get_theme_mod( 'dce_post_custom_hero_bottom_padding', 100 ) . 'px 0;}' . "\n";
		if ( get_theme_mod( 'dce_post_custom_hero_add_overlay', 0 ) ) {
			$hero_overlay_color = get_theme_mod( 'dce_post_custom_hero_overlay_color', 'rgba(0,0,0,0.4)' );
			$dce_output .= ' .dce_hero_overlay {position: relative;}' . "\n";
			$dce_output .= ' .dce_hero_overlay:before {z-index: 0; content: ""; position: absolute; left: 0; right: 0; top: 0; bottom: 0; background-color: ' . $hero_overlay_color . ';}' . "\n";
		}

		$dce_output .= ' .dce_backimg {padding: ' . get_theme_mod( 'dce_post_custom_backimg_top_padding', 50 ) . 'px 0 ' . get_theme_mod( 'dce_post_custom_backimg_bottom_padding', 50 ) . 'px 0;}' . "\n";
		if ( get_theme_mod( 'dce_post_custom_backimg_add_overlay', 0 ) ) {
			$backimg_overlay_color = get_theme_mod( 'dce_post_custom_backimg_overlay_color', 'rgba(0,0,0,0.4)' );
			$dce_output .= ' .dce_backimg_overlay {position: relative;}' . "\n";
			$dce_output .= ' .dce_backimg_overlay:before {z-index: 0; content: ""; position: absolute; left: 0; right: 0; top: 0; bottom: 0; background-color: ' . $backimg_overlay_color . ';}' . "\n";
		}

		$title_color = 'color: ' . get_theme_mod( 'dce_post_custom_title_color', '#333' ) . ';';
		$title_top_margin = get_theme_mod( 'dce_post_custom_title_top_margin', 0 );
		$title_bottom_margin = get_theme_mod( 'dce_post_custom_title_bottom_margin', 0 );
		$title_hor_margin = get_theme_mod( 'dce_post_custom_title_horizontal_margin', 0 );
		$title_margin = ' margin-top: ' . $title_top_margin . 'px; margin-bottom: ' . $title_bottom_margin . 'px; margin-left: ' . $title_hor_margin . '%; margin-right: ' . $title_hor_margin . '%;';
		$title_align = ' text-align: ' . get_theme_mod( 'dce_post_custom_title_align', 'left' ) . ';';
		if ( get_theme_mod( 'dce_post_custom_title_add_background', '0' ) ) {
			$title_background = ' background-color: ' . get_theme_mod( 'dce_post_custom_title_background_color', 'rgba(0,0,0,0.5)' ) . ';';
			$title_ver_padding = get_theme_mod( 'dce_post_custom_title_vertical_padding', 10 );
			$title_hor_padding = get_theme_mod( 'dce_post_custom_title_horizontal_padding', 10 );
			$title_padding =  ' padding: ' . $title_ver_padding . 'px ' . $title_hor_padding . 'px;';
		}
		$dce_output .= ' h1.dce-post-title {' . $title_background . $title_color . $title_margin . $title_padding . $title_align . '}' . "\n";

		$meta_top_margin = get_theme_mod( 'dce_post_custom_meta_top_margin', 0 );
		$meta_bottom_margin = get_theme_mod( 'dce_post_custom_meta_bottom_margin', 0 );
		$meta_hor_margin = get_theme_mod( 'dce_post_custom_meta_horizontal_margin', 0 );
		$meta_margin = ' margin-top: ' . $meta_top_margin . 'px; margin-bottom: ' . $meta_bottom_margin . 'px; margin-left: ' . $meta_hor_margin . '%; margin-right: ' . $meta_hor_margin . '%;';
		$meta_align = ' text-align: ' . get_theme_mod( 'dce_post_custom_meta_align', 'left' ) . ';';
		if ( get_theme_mod( 'dce_post_custom_meta_add_background', '0' ) ) {
			$meta_background = ' background-color: ' . get_theme_mod( 'dce_post_custom_meta_background_color', 'rgba(0,0,0,0.5)' ) . ';';
			$meta_ver_padding = get_theme_mod( 'dce_post_custom_meta_vertical_padding', 10 );
			$meta_hor_padding = get_theme_mod( 'dce_post_custom_meta_horizontal_padding', 10 );
			$meta_padding =  ' padding: ' . $meta_ver_padding . 'px ' . $meta_hor_padding . 'px;';
		}
		$dce_output .= ' #dce-custom-post .post-meta {' . $meta_background . $meta_margin . $meta_padding . $meta_align . '}' . "\n";

		$content_text_size = get_theme_mod( 'dce_post_custom_content_text_size', dce_get_divi_option( 'body_font_size', 14 ) );
		$content_line_height = get_theme_mod( 'dce_post_custom_content_line_height', dce_get_divi_option( 'body_font_height', 1.7 ) );
		$dce_output .= ' #dce-custom-post {font-size: ' . $content_text_size . 'px; line-height: ' . $content_line_height . 'em;}' . "\n";

		$dce_output .= ' #dce-custom-post .dce_spacer {height: ' . get_theme_mod( 'dce_post_custom_spacer_height', 50 ) . 'px;}' . "\n";

	}

	return $dce_output;

}