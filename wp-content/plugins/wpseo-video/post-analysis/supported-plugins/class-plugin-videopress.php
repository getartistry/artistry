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
 * Add support for the Videopress plugin by Automattic (replaced by/also included in JetPack)
 *
 * @see https://wordpress.org/plugins/video/
 *
 * {@internal Last update: July 2014 based upon v 1.5.6.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Videopress' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Videopress
	 */
	class WPSEO_Video_Plugin_Videopress extends WPSEO_Video_Supported_Plugin {

		/**
		 * @var string $videopress_class  Name of the Videopress class within this plugin
		 *                                Used as the JetPack class extends this class and the class names
		 *                                differ between the plugins, though the implementation doesn't.
		 */
		protected $videopress_class = 'Videopress';


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( class_exists( $this->videopress_class ) ) {
				$this->shortcodes[] = 'videopress';
				// Deprecated.
				$this->shortcodes[] = 'wpvideo';
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

			if ( ! empty( $content ) && call_user_func( array( $this->videopress_class, 'is_valid_guid' ), $content ) ) {
				$vid['id']   = $content;
				$vid['type'] = 'videopress';
				$vid         = $this->maybe_get_dimensions( $vid, $atts, true );
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
