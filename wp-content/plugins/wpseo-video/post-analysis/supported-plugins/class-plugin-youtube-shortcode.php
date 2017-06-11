<?php
/**
 * @package    Internals
 * @since      1.8.0
 * @version    1.8.0
 */

// Avoid direct calls to this file.
if ( ! class_exists( 'WPSEO_Video_Sitemap' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 *****************************************************************
 * Add support for the YouTube Shortcode plugin
 *
 * @see https://wordpress.org/plugins/youtube-shortcode/
 *
 * {@internal Last update: July 2014 based upon v 1.8.5.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Youtube_Shortcode' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Youtube_Shortcode
	 */
	class WPSEO_Video_Plugin_Youtube_Shortcode extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( class_exists( 'Youtube_shortcode' ) ) {
				$this->shortcodes[] = 'youtube_sc';
			}
		}


		/**
		 * Analyse a video shortcode from the plugin for usable video information
		 *
		 * @param  string $full_shortcode Full shortcode as found in the post content.
		 * @param  string $sc             Shortcode found.
		 * @param  array  $atts           Shortcode attributes - already decoded if needed.
		 * @param  string $content        The shortcode content, i.e. the bit between [sc]content[/sc].
		 *
		 * @return array   An array with the usable information found or else an empty array.
		 */
		public function get_info_from_shortcode( $full_shortcode, $sc, $atts = array(), $content = '' ) {
			$vid = array();

			/*
			 * @todo - should this be tested to see if this could really be a youtube url ?
			 * if so, what to do with other types of video urls ?
			 */
			if ( isset( $atts['url'] ) && ( is_string( $atts['url'] ) && $atts['url'] !== '' ) ) {
				$vid['url'] = $atts['url'];
			}
			elseif ( isset( $atts['v'] ) && ( is_string( $atts['v'] ) && $atts['v'] !== '' ) ) {
				$vid['url'] = $atts['v'];
			}
			elseif ( isset( $atts['video'] ) && ( is_string( $atts['video'] ) && $atts['video'] !== '' ) ) {
				$vid['url'] = $atts['video'];
			}

			if ( $vid !== array() ) {
				$vid['type'] = 'youtube';
				$vid         = $this->maybe_get_dimensions( $vid, $atts, true );
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
