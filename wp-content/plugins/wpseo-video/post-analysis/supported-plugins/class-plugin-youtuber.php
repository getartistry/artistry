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
 * Add support for the YouTuber plugin
 *
 * @see      https://wordpress.org/plugins/youtuber/
 *
 * {@internal Last update: July 2014 based upon v 1.8.2.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Youtuber' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Youtuber
	 */
	class WPSEO_Video_Plugin_Youtuber extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( defined( 'YTBR_HEADER_V' ) ) {
				$this->shortcodes = array(
					'youtube',
					'vimeo',
					'googlevideo',
					'youtuber',
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

			if ( $sc === 'youtuber' ) {
				$vid = $this->get_info_from_shortcode_youtuber( $full_shortcode, $sc, $atts, $content );
			}
			elseif ( isset( $content ) && ( is_string( $content ) && $content !== '' ) ) {
				$vid['type'] = $sc;
				$vid         = $this->set_vid_from_value( $vid, $sc, $content );
			}

			return $vid;
		}


		/**
		 * Analyse the youtuber shortcode for usable video information
		 *
		 * @param  string $full_shortcode Full shortcode as found in the post content.
		 * @param  string $sc             Shortcode found.
		 * @param  array  $atts           Shortcode attributes - already decoded if needed.
		 * @param  string $content        The shortcode content, i.e. the bit between [sc]content[/sc].
		 *
		 * @return array   An array with the usable information found or else an empty array
		 */
		public function get_info_from_shortcode_youtuber( $full_shortcode, $sc, $atts = array(), $content = '' ) {
			$vid = array();

			if ( isset( $atts['youtube'] ) && ( is_string( $atts['youtube'] ) && $atts['youtube'] !== '' ) ) {
				$vid['type'] = 'youtube';
				$vid         = $this->set_vid_from_value( $vid, 'youtube', $atts['youtube'] );
			}
			elseif ( ! empty( $atts['vimeo'] ) ) {
				$vid['type'] = 'vimeo';
				$vid         = $this->set_vid_from_value( $vid, 'vimeo', $atts['vimeo'] );
			}
			elseif ( ! empty( $atts['googlevideo'] ) ) {
				$vid['type'] = 'googlevideo';
				$vid         = $this->set_vid_from_value( $vid, 'googlevideo', $atts['googlevideo'] );
			}

			return $vid;
		}


		/**
		 * Test a whether a received value could be one of the valid type of video id or set it as url
		 *
		 * @param  array  $vid     Video array so far.
		 * @param  string $service Video service to test the id against their pattern.
		 * @param  string $value   The value to test.
		 *
		 * @return array
		 */
		protected function set_vid_from_value( $vid, $service, $value ) {
			$method = 'is_' . $service . '_id';
			if ( method_exists( $this, $method ) && $this->$method( $value ) === true ) {
				$vid['id'] = $value;
			}
			else {
				$vid['url'] = $value;
			}

			return $vid;
		}
	}
}
