<?php

/**
 * Customizer output: Header Social Icons section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_header_social_icons_output() {

	$dce_output .= '';
	
	if ( get_theme_mod( 'dce_header_social_icons_servicecolor', '0' ) ) {
			if ( ( 'on' === et_get_option( 'divi_show_facebook_icon' ) ) OR ( dce_active_social_icon( 'facebook' ) ) ) {	
				$dce_output .= ' #top-header #et-info .et-social-facebook a {color:#3b5998!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-facebook  a:hover {color:#2d4373!important;}' . "\n";
			}
			if ( ( 'on' === et_get_option( 'divi_show_twitter_icon' ) ) OR ( dce_active_social_icon( 'twitter' ) ) ) {	
				$dce_output .= ' #top-header #et-info .et-social-twitter a {color:#00aced!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-twitter a:hover {color:#0084b4!important;}' . "\n";
			}
			if ( ( 'on' === et_get_option( 'divi_show_google_icon' ) ) OR ( dce_active_social_icon( 'google-plus' ) ) ) {	
				$dce_output .= ' #top-header #et-info .et-social-google-plus a {color:#dd4b39!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-google-plus a:hover {color:#c23321!important;}' . "\n";
			}
			if ( ( 'on' === et_get_option( 'divi_show_rss_icon' ) ) OR ( dce_active_social_icon( 'rss' ) ) ) {		
				$dce_output .= ' #top-header #et-info .et-social-rss a {color:#ff6600!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-rss a:hover {color:#cc5200!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'pinterest' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-pinterest a {color:#cb2027!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-pinterest a:hover {color:#9f191f!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'linkedin' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-linkedin a {color:#007bb6!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-linkedin a:hover {color:#005983!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'instagram' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-instagram a {color:#517fa4!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-instagram a:hover {color:#406582!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'skype' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-skype a {color:#12a5f4!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-skype a:hover {color:#0986ca!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'flikr' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-flikr a {color:#ff0084!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-flikr a:hover {color:#cc006a!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'youtube' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-youtube a {color:#bb0000!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-youtube a:hover {color:#880000!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'vimeo' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-vimeo a {color:#1ab7ea!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-vimeo a:hover {color:#1295bf!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'dribbble' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-dribbble a {color:#ea4c89!important;}' . "\n";
				$dce_output .= ' #top-header #et-info .et-social-dribbble a:hover {color:#e51e6b!important;}' . "\n";
			}
		} elseif ( get_theme_mod( 'dce_header_social_icons_hoverservicecolor', '0' ) ) {
			if ( ( 'on' === et_get_option( 'divi_show_facebook_icon' ) ) OR ( dce_active_social_icon( 'facebook' ) ) ) {	
				$dce_output .= ' #top-header #et-info .et-social-facebook  a:hover {color:#3b5998!important;}' . "\n";
			}
			if ( ( 'on' === et_get_option( 'divi_show_twitter_icon' ) ) OR ( dce_active_social_icon( 'twitter' ) ) ) {	
				$dce_output .= ' #top-header #et-info .et-social-twitter a:hover {color:#00aced!important;}' . "\n";
			}
			if ( ( 'on' === et_get_option( 'divi_show_google_icon' ) ) OR ( dce_active_social_icon( 'google-plus' ) ) ) {	
				$dce_output .= ' #top-header #et-info .et-social-google-plus a:hover {color:#dd4b39!important;}' . "\n";
			}
			if ( ( 'on' === et_get_option( 'divi_show_rss_icon' ) ) OR ( dce_active_social_icon( 'rss' ) ) ) {		
				$dce_output .= ' #top-header #et-info .et-social-rss a:hover {color:#ff6600!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'pinterest' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-pinterest a:hover {color:#cb2027!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'linkedin' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-linkedin a:hover {color:#007bb6!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'instagram' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-instagram a:hover {color:#517fa4!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'skype' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-skype a:hover {color:#12a5f4!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'flikr' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-flikr a:hover {color:#ff0084!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'youtube' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-youtube a:hover {color:#bb0000!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'vimeo' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-vimeo a:hover {color:#1ab7ea!important;}' . "\n";
			}
			if ( dce_active_social_icon( 'dribbble' ) ) {
				$dce_output .= ' #top-header #et-info .et-social-dribbble a:hover {color:#ea4c89!important;}' . "\n";
			}
		} else {
			$default_icon_hover = et_get_option( 'accent_color', '#2ea3f2' );
			$icon_hover = get_theme_mod( 'dce_header_social_icons_hovercolor', $default_icon_hover );
			if ( $icon_hover != $default_icon_hover ) {
				$dce_output .= ' #top-header #et-info ul.et-social-icons li a:hover {color:' . $icon_hover . '!important;}' . "\n";
			}
	}

	$icon_margin = get_theme_mod( 'dce_header_social_icons_margin', 12 );
	if ( $icon_margin != 12 ) {
		$dce_output .= ' #top-header ul.et-social-icons li {margin-left:' . $icon_margin . 'px;}' . "\n";
	}

	return $dce_output;

}