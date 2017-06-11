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
 * Add support for the Sublime Video Official plugin
 *
 * @see https://wordpress.org/plugins/sublimevideo-official/
 *
 * {@internal Last update: July 2014 based upon v 1.8.2.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Sublimevideo_Official' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Sublimevideo_Official
	 */
	class WPSEO_Video_Plugin_Sublimevideo_Official extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( class_exists( 'SublimeVideo' ) ) {
				$this->shortcodes = array(
					'sublimevideo-lightbox',
					'sublimevideo',
				);
			}
		}


		/**
		 * Analyse a video shortcode from the plugin for usable video information
		 *
		 * @param  string $full_shortcode  Full shortcode as found in the post content.
		 * @param  string $sc              Shortcode found.
		 * @param  array  $atts            Shortcode attributes - already decoded if needed.
		 * @param  string $content         The shortcode content, i.e. the bit between [sc]content[/sc].
		 *
		 * @return array   An array with the usable information found or else an empty array.
		 */
		public function get_info_from_shortcode( $full_shortcode, $sc, $atts = array(), $content = '' ) {
			$vid = array();
			$src = '';

			if ( isset( $atts['src1'] ) && is_string( $atts['src1'] ) && $atts['src1'] !== '' ) {
				$src = $atts['src1'];
			}
			elseif ( isset( $atts['src2'] ) && is_string( $atts['src2'] ) && $atts['src2'] !== '' ) {
				$src = $atts['src2'];
			}
			elseif ( isset( $atts['src3'] ) && is_string( $atts['src3'] ) && $atts['src3'] !== '' ) {
				$src = $atts['src3'];
			}
			elseif ( isset( $atts['src4'] ) && is_string( $atts['src4'] ) && $atts['src4'] !== '' ) {
				$src = $atts['src4'];
			}

			if ( $src !== '' ) {
				// If needed, remove HD indicator from start of string.
				if ( strpos( $src, '(hd)' ) === 0 ) {
					$src = str_replace( '(hd)', '', $src );
				}

				$vid['type']        = 'Sublime'; // @todo why does this one have a capital ? None of the other types do
				$vid['url']         = $src;
				$vid['content_loc'] = $src;
				$vid['maybe_local'] = true;

				if ( isset( $atts['poster'] ) && is_string( $atts['poster'] ) && $atts['poster'] !== '' ) {
					$vid['thumbnail_loc'] = $atts['poster'];
				}

				$vid = $this->maybe_get_dimensions( $vid, $atts );
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
