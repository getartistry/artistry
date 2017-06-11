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
 * Add support for the YouTube White Label Shortcode plugin
 *
 * @see      https://wordpress.org/plugins/youtube-white-label-shortcode/
 *
 * {@internal Last update: July 2014 based upon v 0.3.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Youtube_White_Label_Shortcode' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Youtube_White_Label_Shortcode
	 */
	class WPSEO_Video_Plugin_Youtube_White_Label_Shortcode extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( class_exists( 'YouTube_White_Label_Shortcode' ) ) {
				$this->shortcodes[] = 'youtube-white-label';
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

			if ( isset( $atts['id'] ) && ( is_string( $atts['id'] ) && $atts['id'] !== '' ) ) {
				if ( $this->is_youtube_id( $atts['id'] ) ) {
					$vid['id'] = $atts['id'];
				}
				else {
					/*
					 * @todo - should this be tested to see if this could really be a youtube url?
					 * if so, what to do with other types of video urls?
					 */
					$vid['url'] = $atts['id'];
				}
			}

			if ( $vid !== array() ) {
				$vid['type'] = 'youtube';
				$vid         = $this->maybe_get_dimensions( $vid, $atts );
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
