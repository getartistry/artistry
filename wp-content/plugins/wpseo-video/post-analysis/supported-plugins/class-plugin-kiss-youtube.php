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
 * Add support for the KISS YouTube plugin
 *
 * @see https://wordpress.org/plugins/kiss-youtube/
 *
 * {@internal Last update: July 2014 based upon v 1.0.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Kiss_Youtube' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Kiss_Youtube
	 */
	class WPSEO_Video_Plugin_Kiss_Youtube extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( function_exists( 'kisstube' ) ) {
				$this->shortcodes = array(
					'youtube',
					'youtubewd',
				);
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

			if ( $this->is_youtube_id( $content ) ) {
				$vid['type'] = 'youtube';
				$vid['id']   = $content;
			}
			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
