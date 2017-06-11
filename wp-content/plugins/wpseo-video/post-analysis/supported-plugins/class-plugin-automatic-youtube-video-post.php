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
 * Add support for the Automatic YouTube Video Post plugin
 *
 * @see https://wordpress.org/plugins/automatic-youtube-video-posts/
 *
 * {@internal Last update: August 2014 based upon v 3.2.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Automatic_Youtube_Video_Post' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Automatic_Youtube_Video_Post
	 */
	class WPSEO_Video_Plugin_Automatic_Youtube_Video_Post extends WPSEO_Video_Supported_Plugin {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( function_exists( 'WP_ayvpp_init' ) ) {
				$this->meta_keys[] = '_tern_wp_youtube_video'; // Pre 5.0.
				$this->meta_keys[] = '_ayvpp_video'; // 5.0+.
			}
		}


		/**
		 * Analyse a specific post meta field for usable video information
		 *
		 * @param  string $meta_value  The value to analyse.
		 * @param  string $meta_key    The associated meta key.
		 * @param  int    $post_id     The id of the post this meta value applies to.
		 *
		 * @return array   An array with the usable information found or else an empty array
		 */
		public function get_info_from_post_meta( $meta_value, $meta_key, $post_id ) {
			$vid = array();

			if ( $this->is_youtube_id( $meta_value ) ) {
				$vid['type'] = 'youtube';
				$vid['id']   = $meta_value;

				// AYVP 5.0+.
				if ( $meta_key === '_ayvpp_video' ) {

					// Yes, they are doing it wrong, saving a url instead of a thumbnail id,
					// but that's just how it is...
					$thumb = get_post_meta( $post_id, '_thumbnail_id', true );
					if ( ! empty( $thumb ) ) {
						$vid['thumbnail_loc'] = $thumb;
					}

					$vid = $this->get_video_dimensions_from_option( $vid, 'ayvpp_settings', 'video_dims', 'ayvpp_options' );
				}
				// Old logic for pre-5.0.
				elseif ( $meta_key === '_tern_wp_youtube_video' ) {

					// From automatic-youtube-video-posts/core/video.php.
					$vid['thumbnail_loc'] = 'http://img.youtube.com/vi/' . $meta_value . '/0.jpg';

					$vid = $this->get_video_dimensions_from_option( $vid, 'tern_wp_youtube', 'dims', 'tern_wp_youtube_options' );
				}
			}

			return $vid;
		}


		/**
		 * Get the video dimensions from the AYVP option.
		 *
		 * Fall-back default from automatic-youtube-video-posts/conf.php.
		 *
		 * @since 3.8.0
		 *
		 * @param array  $vid           Current video information.
		 * @param string $option_name   The name of AYVP option.
		 * @param string $array_key     The key in the options array holding the video dimensions.
		 * @param string $global_option The name of the option in $GLOBALS.
		 *
		 * @return array Adjusted $vid array
		 */
		private function get_video_dimensions_from_option( $vid, $option_name, $array_key, $global_option ) {
			$options = get_option( $option_name );
			if ( $options !== false && ! empty( $options[ $array_key ][0] ) ) {
				$vid['width'] = $options[ $array_key ][0];
			}
			elseif ( ! empty( $GLOBALS[ $global_option ][ $array_key ][0] ) ) {
				$vid['width'] = $GLOBALS[ $global_option ][ $array_key ][0];
			}
			else {
				$vid['width'] = 506;
			}

			if ( $options !== false && ! empty( $options[ $array_key ][1] ) ) {
				$vid['height'] = $options[ $array_key ][1];
			}
			elseif ( ! empty( $GLOBALS[ $global_option ][ $array_key ][1] ) ) {
				$vid['height'] = $GLOBALS[ $global_option ][ $array_key ][1];
			}
			else {
				$vid['height'] = 304;
			}

			return $vid;
		}
	} /* End of class */

} /* End of class-exists wrapper */
