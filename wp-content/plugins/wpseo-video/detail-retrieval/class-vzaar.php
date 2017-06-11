<?php
/**
 * @package    Internals
 * @since      1.7.0
 * @version    1.7.0
 */

// Avoid direct calls to this file.
if ( ! class_exists( 'WPSEO_Video_Sitemap' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 *****************************************************************
 * Vzaar Video SEO Details
 *
 * @todo Maybe add view_count based upon play_count ?
 *
 * JSON response format [2014/7/22]:
 * {
 *    "type":"video",
 *    "provider_url":"http://vzaar.com",
 *    "author_account":34,
 *    "framegrab_width":1280,
 *    "duration":1668.88,
 *    "framegrab_height":720,
 *    "thumbnail_url":"https://view.vzaar.com/1042280/thumb",
 *    "version":"1.0",
 *    "height":720,
 *    "author_name":"vzaar_marketing",
 *    "title":"Not set,",
 *    "width":1280,
 *    "video_status_id":2,
 *    "author_url":"http://app.vzaar.com/users/vzaar_marketing",
 *    "framegrab_url":"https://view.vzaar.com/1042280/image",
 *    "play_count":238,
 *    "video_status_description":"Active",
 *    "video_url":"https://view.vzaar.com/1042280/video",
 *    "thumbnail_width":"120",
 *    "total_size":1231444667,
 *    "html":"\u003Ciframe allowFullScreen allowTransparency=\"true\" class=\"vzaar-video-player\" frameborder=\"0\" height=\"432\" id=\"vzvd-1042280\" mozallowfullscreen name=\"vzvd-1042280\" src=\"//view.vzaar.com/1042280/player\" title=\"vzaar video player\" type=\"text/html\" webkitAllowFullScreen width=\"768\"\u003E\u003C/iframe\u003E",
 *    "thumbnail_height":"90",
 *    "provider_name":"vzaar"
 * }
 */
if ( ! class_exists( 'WPSEO_Video_Details_Vzaar' ) ) {

	/**
	 * Class WPSEO_Video_Details_Vzaar
	 *
	 * {@internal We don't strictly need a response, funnily enough, though we lack the duration when we don't get it.}}
	 */
	class WPSEO_Video_Details_Vzaar extends WPSEO_Video_Details {

		/**
		 * @var	string	Regular expression to retrieve a video id from a known video url
		 */
		protected $id_regex = '`[/\.](?:vzaar\.com|vzaar\.tv#CUSTOM_URL#)/(?:videos/)?([0-9]+)(?:[/\.](?:player|flashplayer|video|download|mobile))?$`i';

		/**
		 * @var	array  Information on the remote url to use for retrieving the video details
		 */
		protected $remote_url = array(
			'pattern'       => 'http://vzaar.com/api/videos/%s.json',
			'replace_key'   => 'id',
			'response_type' => 'json',
		);

		/**
		 * @var string Alternative remote url pattern for use with custom url
		 */
		private $alternate_remote_pattern = '%s%s.json';

		/**
		 * @var string  Vzaar base url
		 */
		protected $base_url = 'http://vzaar.com/videos/';

		/**
		 * @var array  Acceptable status ids
		 *
		 * @see http://developer.vzaar.com/docs/version_1.0/public/video_details.html#notes
		 *
		 * @todo Should we only accept state 2 ?
		 *
		 * Available video Status ids:
		 * 1    Processing not complete
		 * 2    Available (processing complete, video ready) <====
		 * 3    Expired
		 * 4    On Hold (waiting for encoding to be available)
		 * 5    Ecoding Failed
		 * 6    Encoding Unavailable
		 * 7    n/a
		 * 8    Replaced
		 * 9    Deleted
		 * 10    n/a
		 * 11    Initializing
		 * 12    Finalizing
		 */
		protected $ok_states = array( '1', '2', '12' );


		/**
		 * Instantiate the class
		 *
		 * Overwrite the vzaar base domain if needed before passing off to the parent constructor
		 *
		 * @param array $vid     The video array with all the data.
		 * @param array $old_vid The video array with all the data of the previous "fetch", if available.
		 *
		 * @return \WPSEO_Video_Details_Vzaar
		 */
		public function __construct( $vid, $old_vid = array() ) {
			$options = get_option( 'wpseo_video' );
			if ( isset( $options['vzaar_domain'] ) && $options['vzaar_domain'] !== '' ) {
				$this->base_url = 'http://' . rawurlencode( $options['vzaar_domain'] ) . '/';
				$this->id_regex = str_replace( '#CUSTOM_URL#', '|' . preg_quote( $options['vzaar_domain'], '`' ), $this->id_regex );
			}
			else {
				$this->id_regex = str_replace( '#CUSTOM_URL#', '', $this->id_regex );
			}
			parent::__construct( $vid, $old_vid );
		}


		/**
		 * Retrieve information on a video via a remote API call
		 *
		 * @uses WPSEO_Video_Details_Vzaar::$remote_url
		 * @uses WPSEO_Video_Details_Vzaar::$base_url
		 * @uses WPSEO_Video_Details_Vzaar::$alternate_remote_pattern
		 *
		 * @return void
		 */
		protected function get_remote_video_info() {
			parent::get_remote_video_info();

			if ( ! isset( $this->remote_response ) && ! empty( $this->vid['id'] ) && ! empty( $this->base_url ) ) {
				$url = sprintf( $this->alternate_remote_pattern, $this->base_url, $this->vid['id'] );
				$url = $this->url_encode( $url );

				$response = $this->remote_get( $url, array( 'referer' => get_site_url() ) );
				if ( $response !== false ) {
					$this->remote_response = $response;
				}
			}
		}


		/**
		 * Check if the response is for a video
		 *
		 * @return bool
		 */
		protected function is_video_response() {
			return ( ! empty( $this->decoded_response ) && ( ( isset( $this->decoded_response->type ) && $this->decoded_response->type === 'video' ) && ( isset( $this->decoded_response->video_status_id ) && in_array( (string) $this->decoded_response->video_status_id, $this->ok_states, true ) ) ) );
		}


		/**
		 * Set the content location
		 */
		protected function set_content_loc() {
			if ( ! empty( $this->decoded_response->video_url ) ) {
				$this->vid['content_loc'] = $this->decoded_response->video_url;
			}
			elseif ( ! empty( $this->vid['id'] ) ) {
				$this->vid['content_loc'] = 'https://view.vzaar.com/' . rawurlencode( $this->vid['id'] ) . '/video';
			}
		}


		/**
		 * Set the video duration
		 */
		protected function set_duration() {
			$this->set_duration_from_json_object();
		}


		/**
		 * Set the video height
		 */
		protected function set_height() {
			$this->set_height_from_json_object();
		}


		/**
		 * Set the player location
		 */
		protected function set_player_loc() {
			if ( ! empty( $this->vid['id'] ) ) {
				$this->vid['player_loc'] = 'https://view.vzaar.com/' . rawurlencode( $this->vid['id'] ) . '/flashplayer';
			}
		}


		/**
		 * Set the thumbnail location
		 */
		protected function set_thumbnail_loc() {
			if ( ! empty( $this->vid['id'] ) ) {
				$url   = $this->base_url . rawurlencode( $this->vid['id'] ) . '/image';
				$image = $this->make_image_local( $url, 'jpg' );
				if ( is_string( $image ) && $image !== '' ) {
					$this->vid['thumbnail_loc'] = $image;
				}
				elseif ( isset( $this->decoded_response->framegrab_url ) && is_string( $this->decoded_response->framegrab_url ) && $this->decoded_response->framegrab_url !== '' ) {
					$image = $this->make_image_local( $this->decoded_response->framegrab_url );
					if ( is_string( $image ) && $image !== '' ) {
						$this->vid['thumbnail_loc'] = $image;
					}
					else {
						$this->set_thumbnail_loc_from_json_object();
					}
				}
			}
		}


		/**
		 * Set the video view count
		 */
		protected function set_view_count() {
			if ( ! empty( $this->decoded_response->play_count ) ) {
				$this->vid['view_count'] = $this->decoded_response->play_count;
			}
		}


		/**
		 * Set the video width
		 */
		protected function set_width() {
			$this->set_width_from_json_object();
		}
	} /* End of class */

} /* End of class-exists wrapper */
