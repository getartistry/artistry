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
 * Add support for the Viper's Video Quicktags plugin
 *
 * @see      https://wordpress.org/plugins/vipers-video-quicktags/
 *
 * {@internal Last update: July 2014 based upon v 6.5.2.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Vipers_Video_Quicktags' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Vipers_Video_Quicktags
	 */
	class WPSEO_Video_Plugin_Vipers_Video_Quicktags extends WPSEO_Video_Supported_Plugin {

		/**
		 * @var array $shortcodes_to_add Shortcodes added by this plugin
		 */
		private $shortcodes_to_add = array(
			'youtube',
			'googlevideo',
			'gvideo',
			'dailymotion',
			'vimeo',
			'veoh',
			'viddler',
			'metacafe',
			'blip.tv',
			'bliptv',
			'flickr video',
			'flickrvideo',
			'ifilm',
			'spike',
			'myspace',
			'flv',
			'quicktime',
			'flash',
			'videofile',
			// Legacy.
			'video',
			'avi',
			'mpeg',
			'wmv',
		);

		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( class_exists( 'VipersVideoQuicktags' ) ) {
				$this->shortcodes = $this->shortcodes_to_add;

				// Anarchy Media Plugin / Kimili Flash Embed support but only if those plugins aren't enabled.
				if ( ! class_exists( 'KimiliFlashEmbed' ) && ! function_exists( 'kml_flashembed' ) && ! isset( $shortcode_tags['kml_flashembed'] ) ) {
					$this->shortcodes[] = 'kml_flashembed';
				}

				// VideoPress support but only if the official plugin isn't installed.
				if ( ! function_exists( 'videopress_shortcode' ) && ! isset( $shortcode_tags['wpvideo'] ) ) {
					$this->shortcodes[] = 'wpvideo';
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
			$vid = array();

			switch ( $sc ) {
				case 'blip.tv':
				case 'bliptv':
					$vid = $this->what_the_blip( $vid, $content, $full_shortcode );
					break;

				case 'flv':
					if ( is_string( $content ) && $content !== '' ) {
						$vid['url']         = $content;
						$vid['content_loc'] = $content;
						$vid['player_loc']  = plugins_url( '/vipers-video-quicktags/resources/jw-flv-player/player.swf?file=' . urlencode( $content ) );
						$vid['id']          = md5( $content );
					}
					break;

				case 'viddler':
					// Deal with wp.com format: [viddler id=fad7437b&w=437&h=370][/viddler].
					if ( preg_match( '`id=["\']?([^&"\'\]\s]+)`i', $full_shortcode, $match ) ) {
						$vid['id'] = $match[1];
						if ( preg_match( '`(?:&|&#038;|&amp;)w=([0-9]+)`', $full_shortcode, $hmatch ) ) {
							$vid['width'] = $hmatch[1];
						}
						if ( preg_match( '`(?:&|&#038;|&amp;)h=([0-9]+)`', $full_shortcode, $wmatch ) ) {
							$vid['height'] = $wmatch[1];
						}
					}
					unset( $match, $hmatch, $wmatch );
					break;

				default:
					if ( is_string( $content ) && $content !== '' ) {
						// Is it a url or an id.
						if ( strpos( $content, 'http' ) === 0 || strpos( $content, '//' ) === 0 ) {
							$vid['url'] = $content;
						}
						else {
							$vid['id'] = $content;
						}
					}
					break;
			}

			if ( $vid !== array() ) {
				$vid['type'] = $this->determine_type_from_shortcode( $sc );

				if ( in_array( $vid['type'], array(
					'flv',
					'quicktime',
					'flash',
					'videofile',
					'video',
					'avi',
					'mpeg',
					'wmv',

				), true ) ) {
					$vid['maybe_local'] = true;
				}

				// Allow other plugins to modify the attributes (for example based on conditionals).
				$atts = apply_filters( 'vvq_shortcodeatts', $atts, $sc, $atts );

				// Width/height for video services without detail retrieval.
				$vid = $this->maybe_get_dimensions( $vid, $atts, true );

				// Quicktime thumbnail image.
				if ( $sc === 'quicktime' && ( isset( $atts['useplaceholder'] ) && $atts['useplaceholder'] == 1 ) ) {
					$vid['thumbnail_loc'] = str_replace( '.mov', '.jpg', $content );
				}
			}

			return $vid;
		}


		/**
		 * Determine the video type to set based on the Shortcode found.
		 *
		 * @todo: figure out what the service type should be for the below shortcodes
		 *  'myspace'
		 *  'quicktime' -> iframe ?
		 *  'flash'
		 *  'videofile' -> iframe ?
		 *  'video'
		 *  'avi'
		 *  'mpeg'
		 *  'wmv'
		 *  'kml_flashembed'
		 *
		 * @param  string $sc Shortcode.
		 *
		 * @return string      Video type
		 */
		protected function determine_type_from_shortcode( $sc ) {
			// Deal with non-standard service names.
			switch ( $sc ) {
				case 'gvideo':
					$type = 'googlevideo';
					break;

				case 'blip.tv':
				case 'bliptv':
					$type = 'blip';
					break;

				case 'flickr video':
				case 'flickrvideo':
					$type = 'flickr';
					break;

				case 'ifilm':
					$type = 'spike';
					break;

				case 'wpvideo':
					$type = 'videopress';
					break;

				default:
					$type = $sc;
					break;
			}

			return $type;
		}
	} /* End of class */

} /* End of class-exists wrapper */
