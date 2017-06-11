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
 * Add support for the YouTube Insert Me plugin
 *
 * @see https://wordpress.org/plugins/youtube-insert-me/
 *
 * {@internal Last update: July 2014 based upon v 1.0.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Youtube_Insert_Me' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Youtube_Insert_Me
	 */
	class WPSEO_Video_Plugin_Youtube_Insert_Me extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( function_exists( 'youtubeinsertme' ) ) {
				$this->shortcodes[] = 'youtube';
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
		 * @return array   An array with the usable information found or else an empty array
		 */
		public function get_info_from_shortcode( $full_shortcode, $sc, $atts = array(), $content = '' ) {
			$vid = array();

			if ( isset( $content ) && ( is_string( $content ) && $content !== '' ) ) {
				if ( $this->is_youtube_id( $content ) ) {
					$vid['type'] = 'youtube';
					$vid['id']   = $content;
				}
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
