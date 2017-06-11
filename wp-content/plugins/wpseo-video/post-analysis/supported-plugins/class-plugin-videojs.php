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
 * Add support for the Video.js plugin
 *
 * @see https://wordpress.org/plugins/videojs-html5-video-player-for-wordpress/
 *
 * {@internal Last update: July 2014 based upon v 4.5.0.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Videojs' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Videojs
	 */
	class WPSEO_Video_Plugin_Videojs extends WPSEO_Video_Support_Core {

		/**
		 * @var string $att_regex  Regular expression to use to find the video file
		 *                         Set here as this class extends the WP Core class which
		 *                         uses a slightly different regex, though the rest of the shortcode
		 *                         implementation is the same.
		 */
		protected $att_regex = '`(?:mp4|ogg|webm|youtube)=([\'"])?([^\'"\s]+)[\1\s]?`';


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( function_exists( 'register_videojs' ) && function_exists( 'video_shortcode' ) ) {
				$this->shortcodes[] = 'videojs';

				// From plugin: Only use the [video] shortcode if the correct option is set.
				$options = get_option( 'videojs_options' );
				if ( ! is_array( $options ) || ! array_key_exists( 'videojs_video_shortcode', $options ) || $options['videojs_video_shortcode'] ) {
					$this->shortcodes[] = 'video';
				}
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
			return parent::get_info_from_shortcode_video( $full_shortcode, $sc, $atts, $content );
		}
	} /* End of class */

} /* End of class-exists wrapper */
