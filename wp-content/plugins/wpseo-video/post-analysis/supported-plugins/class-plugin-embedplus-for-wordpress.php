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
 * Add support for the Embedplus for WordPress plugin
 *
 * @see https://wordpress.org/plugins/embedplus-for-wordpress/
 *
 * {@internal Last update: July 2014 based upon v 5.1.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Embedplus_For_Wordpress' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Embedplus_For_Wordpress
	 */
	class WPSEO_Video_Plugin_Embedplus_For_Wordpress extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( class_exists( 'EmbedPlusOfficialPlugin' ) ) {
				$this->shortcodes[] = 'embedplusvideo';

				/*
				Does not seem active in current version of plugin
				$this->video_autoembeds = array(
					'youtube2embedplus' => 'youtube',
				);
				*/
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

			// @todo - should this be tested to see if this could really be a youtube url ?
			// if so, what to do with other types of video urls ?
			if ( isset( $atts['standard'] ) && is_string( $atts['standard'] ) && $atts['standard'] !== '' ) {
				$vid['type'] = 'youtube';
				$vid['url']  = $atts['standard'];
			}
			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
