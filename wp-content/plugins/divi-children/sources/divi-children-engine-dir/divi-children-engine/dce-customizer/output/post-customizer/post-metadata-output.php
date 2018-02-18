<?php

/**
 * Customizer output: Post Meta Styles section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_post_metadata_output() {

	$dce_output = '';

	$text_color = get_theme_mod( 'dce_post_postmeta_text_color', '#666' );
	if ( $text_color != '#666' ) {
		$dce_output .= ' .single .post-meta {color: ' . $text_color . ';}' . "\n";
	}

	$links_color = get_theme_mod( 'dce_post_postmeta_links_color', '#666' );
	if ( $links_color != '#666' ) {
		$dce_output .= ' .single .post-meta a, .single .dce-post-tags a {color: ' . $links_color . ' !important;}' . "\n";
	}
	
	$links_hover_color = get_theme_mod( 'dce_post_postmeta_links_color_hover', '#666' );
	if ( $links_hover_color != '#666' ) {
		$dce_output .= ' .single .post-meta a:hover, .single .dce-post-tags a:hover {color: ' . $links_hover_color . ' !important;}' . "\n";
	}

	if ( 'icons' == get_theme_mod( 'dce_post_postmeta_with_icons', 'default' ) ) {

			$icon_size = get_theme_mod( 'dce_post_postmeta_icon_size', 16 );
			$tags_after_content = get_theme_mod( 'dce_post_tags_after_content', '' );
			$dce_output .= ' .single .post-meta .dce_icon {font-size:' . $icon_size . 'px;}' . "\n";
			$dce_output .= ' .single .post-meta .dce_icon:before {padding: 0 5px 0 ' . get_theme_mod( 'dce_post_postmeta_icon_padding', 12 ) . 'px;}' . "\n";
			$dce_output .= ' #main-content .post-meta .dce_icon:first-of-type:before {padding-left: 0 !important;}' . "\n";
			
			if ( 'same' == get_theme_mod( 'dce_post_postmeta_same_icons_color', 'same' ) ) {
					$same_color = get_theme_mod( 'dce_post_postmeta_icon_color', et_get_option( 'accent_color', '#2ea3f2' ) );
					$postmeta_author_color = $same_color;
					$postmeta_date_color = $same_color;
					$postmeta_mod_date_color = $same_color;
					$postmeta_categories_color = $same_color;
					$postmeta_comments_color = $same_color;
					if ( $tags_after_content ) {
						$postmeta_tags_color = $same_color;
					}
				} else {
					$postmeta_author_color = get_theme_mod( 'dce_post_postmeta_author_color', et_get_option( 'accent_color', '#2ea3f2' ) );
					$postmeta_date_color = get_theme_mod( 'dce_post_postmeta_date_color', et_get_option( 'accent_color', '#2ea3f2' ) );
					$postmeta_mod_date_color = get_theme_mod( 'dce_post_postmeta_mod_date_color', et_get_option( 'accent_color', '#2ea3f2' ) );
					$postmeta_categories_color = get_theme_mod( 'dce_post_postmeta_categories_color', et_get_option( 'accent_color', '#2ea3f2' ) );
					$postmeta_comments_color = get_theme_mod( 'dce_post_postmeta_comments_color', et_get_option( 'accent_color', '#2ea3f2' ) );
					$postmeta_tags_color = get_theme_mod( 'dce_post_postmeta_tags_color', et_get_option( 'accent_color', '#2ea3f2' ) );
			}

			$dce_output .= ' .single .post-meta .icon_profile {color:' . $postmeta_author_color . ';}' . "\n";
			$dce_output .= ' .single .post-meta .icon_calendar {color:' . $postmeta_date_color . ';}' . "\n";
			$dce_output .= ' .single .post-meta .icon_refresh {color:' . $postmeta_mod_date_color . ';}' . "\n";
			$dce_output .= ' .single .post-meta .icon_clipboard {color:' . $postmeta_categories_color . ';}' . "\n";
			$dce_output .= ' .single .post-meta .icon_chat {color:' . $postmeta_comments_color . ';}' . "\n";
			$dce_output .= ' .single .icon_tags {color:' . $postmeta_tags_color . '; font-size:' . $icon_size . 'px;}' . "\n";
			$dce_output .= ' .single .dce-post-tags .icon_tags:before {padding-right: 5px;}' . "\n";			

		} else {

			$weight = get_theme_mod( 'dce_post_postmeta_separator_weight', '500' );
			$separator_weight = ( $weight != '500' ) ? ' font-weight:' . $weight . ';' : '';
			$dce_output .= ' .single .dce-postmeta-separator {padding: 0 ' . get_theme_mod( 'dce_post_postmeta_separator_padding', 3 ) . 'px; color: ' . get_theme_mod( 'dce_post_postmeta_separator_color', '#666' ). '; ' . $separator_weight . '}' . "\n";

	}

	return $dce_output;

}
