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
 * Add support for the YouTube with Style plugin
 *
 * @see https://wordpress.org/plugins/youtube-with-style/
 *
 * {@internal Last update: July 2014 based upon v 10.0.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Youtube_With_Style' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Youtube_With_Style
	 */
	class WPSEO_Video_Plugin_Youtube_With_Style extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( function_exists( 'yt_search_video' ) && function_exists( 'youtube_shortcode' ) ) {
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
		 * @return array   An array with the usable information found or else an empty array.
		 */
		public function get_info_from_shortcode( $full_shortcode, $sc, $atts = array(), $content = '' ) {
			$vid = array();

			if ( isset( $content ) && ( is_string( $content ) && $content !== '' ) ) {
				// Get first of multi-id playlist if applicable.
				$content = explode( ',', $content );
				$content = $content[0];
				if ( $this->is_youtube_id( $content ) ) {
					$vid['type'] = 'youtube';
					$vid['id']   = $content;
					$vid         = $this->maybe_get_dimensions( $vid, $atts );
				}
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
