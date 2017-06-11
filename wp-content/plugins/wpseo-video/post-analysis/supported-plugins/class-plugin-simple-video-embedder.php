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
 * Add support for the Simple Video Embedder plugin
 *
 * @see      https://wordpress.org/plugins/simple-video-embedder/
 *
 * {@internal Last update: August 2014 based upon v 2.2.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Simple_Video_Embedder' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Simple_Video_Embedder
	 */
	class WPSEO_Video_Plugin_Simple_Video_Embedder extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( function_exists( 'p75GetVideo' ) ) {
				$this->shortcodes[] = 'simple_video';

				// Legacy.
				$this->meta_keys[] = 'videoembed';
				// Legacy.
				$this->meta_keys[] = '_videoembed_manual';
				$this->meta_keys[] = '_videoembed';

				// Handler name => VideoSEO service name.
				$this->video_autoembeds = array(
					'p75_jw_player' => 'jwplayer',
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
			$vid     = array();
			$post_id = 0;
			if ( ! empty( $GLOBALS['post']->ID ) ) {
				$post_id = $GLOBALS['post']->ID;
			}

			if ( isset( $atts['url'] ) && ( is_string( $atts['url'] ) && $atts['url'] !== '' ) ) {
				$vid['url'] = $atts['url'];
			}
			elseif ( ! empty( $atts['id'] ) ) {
				// This is a post id to get the video from another post.
				$post_id = $atts['id'];
				$url     = get_post_meta( $post_id, '_videoembed', true );
				if ( is_string( $url ) && $url !== '' ) {
					$vid['url'] = $url;
				}
				else {
					$url = get_post_meta( $post_id, 'videoembed', true );
					if ( is_string( $url ) && $url !== '' ) {
						$vid['url'] = $url;
					}
					else {
						// Not sure we should even support this in any way, but if we do, maybe analyse it for the right details ?
						$embed = get_post_meta( $post_id, '_videoembed_manual', true );
						if ( $embed !== '' ) {
							$vid['__add_to_content'] = $embed;
						}
					}
				}
			}

			if ( $vid !== array() && isset( $vid['url'] ) ) {
				$vid['maybe_local'] = true;
				$vid                = $this->maybe_jwplayer( $vid );

				$vid = $this->maybe_get_dimensions( $vid, $atts );
				if ( ! isset( $vid['width'], $vid['height'] ) ) {
					$vid = $this->get_dimensions( $vid, $post_id );
				}
			}

			return $vid;
		}


		/**
		 * Analyse a specific post meta field for usable video information
		 *
		 * @param  string $meta_value The value to analyse.
		 * @param  string $meta_key   The associated meta key.
		 * @param  int    $post_id    The id of the post this meta value applies to.
		 *
		 * @return array   An array with the usable information found or else an empty array
		 */
		public function get_info_from_post_meta( $meta_value, $meta_key, $post_id ) {
			$vid = array();

			switch ( $meta_key ) {
				case 'videoembed':
				case '_videoembed':
					$vid['url'] = $meta_value;
					break;

				case '_videoembed_manual':
					$vid['__add_to_content'] = $meta_value;
					break;
			}

			if ( $vid !== array() && isset( $vid['url'] ) ) {
				$vid['maybe_local'] = true;
				$vid                = $this->maybe_jwplayer( $vid );
				if ( ! isset( $vid['type'] ) ) {
					$vid['type'] = 'custom_field';
				}
				$vid = $this->get_dimensions( $vid, $post_id );
			}

			return $vid;
		}


		/**
		 * Check an option of the plugin to see whether jwplayer will be used
		 *
		 * @param  array $vid Video array so far.
		 *
		 * @return array
		 */
		protected function maybe_jwplayer( $vid ) {
			if ( ! empty( $vid['url'] ) ) {
				$jwplayer = get_option( 'p75_jw_files' );
				if ( is_string( $jwplayer ) && $jwplayer !== '' ) {
					$vid['type'] = 'jwplayer';
					if ( preg_match( '`^(?:http[s]?:)?//.*\.(?:flv|mp4)$`i', $vid['url'] ) ) {
						$vid['content_loc'] = $vid['url'];
					}
				}
			}

			return $vid;
		}


		/**
		 * Set video dimensions (width/height) based on post meta or option settings if available.
		 *
		 * @param  array $vid     Video array so far.
		 * @param  int   $post_id The id of the post being analysed.
		 *
		 * @return array
		 */
		protected function get_dimensions( $vid, $post_id = 0 ) {
			if ( empty( $vid['width'] ) ) {
				if ( $post_id > 0 ) {
					$width = get_post_meta( $post_id, '_videowidth', true );
					if ( $width !== '' && $width > 0 ) {
						$vid['width'] = $width;
					}
				}

				if ( empty( $vid['width'] ) ) {
					$width = get_option( 'p75_default_player_width' );
					if ( $width !== false && $width > 0 ) {
						$vid['width'] = $width;
					}
				}

				if ( empty( $vid['width'] ) ) {
					// Their default.
					$vid['width'] = 400;
				}
			}


			if ( empty( $vid['height'] ) ) {
				if ( $post_id > 0 ) {
					$height = get_post_meta( $post_id, '_videoheight', true );
					if ( $height !== '' && $height > 0 ) {
						$vid['height'] = $height;
					}
				}

				if ( empty( $vid['height'] ) ) {
					$height = get_option( 'p75_default_player_height' );
					if ( $height !== false && $height > 0 ) {
						$vid['height'] = $height;
					}
				}

				if ( empty( $vid['height'] ) ) {
					// Their default.
					$vid['height'] = 300;
				}
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
