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
 * Add support for the Weaver theme (not plugin)
 *
 * @see https://wordpress.org/themes/weaver
 *
 * {@internal Last update: August 2014 based upon old code as plugin/theme not available.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Weaver' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Weaver
	 *
	 * @todo Add to index-content plugin list
	 * @todo Add to travis download list
	 */
	class WPSEO_Video_Plugin_Weaver extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			// @todo - figure out what to test against to confirm that the plugin/theme is loaded
			// if ( class_exists( ???? ) ) {
				$this->shortcodes[] = 'weaver_vimeo';
				$this->shortcodes[] = 'weaver_youtube';
			// }
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

			if ( isset( $content ) && ( is_string( $content ) && $content !== '' ) && ( strpos( $atts['video'], 'http' ) === 0 || strpos( $atts['video'], '//' ) === 0 ) ) {
				$vid['url'] = $content;
			}
			elseif ( ! empty( $atts['id'] ) ) {
				if ( $sc === 'weaver_vimeo' && $this->is_vimeo_id( $atts['id'] ) ) {
					$vid['id'] = $atts['id'];
				}
				elseif ( $sc === 'weaver_youtube' && $this->is_youtube_id( $atts['id'] ) ) {
					$vid['id'] = $atts['id'];

				}
			}

			if ( $vid !== array() ) {
				$vid['type'] = str_replace( 'weaver_', '', $sc );
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
