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
 * Vippy Video SEO Details
 */
if ( ! class_exists( 'WPSEO_Video_Details_Vippy' ) ) {

	/**
	 * Class WPSEO_Video_Details_Vippy
	 *
	 * Requires the Vippy plugin https://wordpress.org/plugins/vippy/
	 *
	 * @todo - find out what the player_loc should be - this method is set by every other video class, so why not
	 * in this one ?
	 */
	class WPSEO_Video_Details_Vippy extends WPSEO_Video_Details {

		/**
		 * Instantiate the class
		 *
		 * @param array $vid     The video array with all the data.
		 * @param array $old_vid The video array with all the data of the previous "fetch", if available.
		 *
		 * @return \WPSEO_Video_Details_Vippy
		 */
		public function __construct( $vid, $old_vid = array() ) {
			if ( ! class_exists( 'Vippy' ) ) {
				// @todo [JRF -> Yoast] Why not use (merge with) oldvid data here if available ? Old data might still be better than none.
				$this->vid = $vid;
			}
			else {
				parent::__construct( $vid, $old_vid );
			}
		}

		/**
		 * Retrieve information on a video via a remote API call using the Vippy plugin for making the call
		 *
		 * @return void
		 */
		protected function get_remote_video_info() {
			$vippy    = new Vippy;
			$response = $vippy->get_video( array( 'videoId' => $this->vid['id'], 'statistics' => 1 ) );
			if ( ! isset( $response->error ) && ( isset( $response->vippy[0] ) && $response->vippy[0] ) ) {
				$this->remote_response = $response->vippy[0];
			}
		}

		/**
		 * Set the content location
		 */
		protected function set_content_loc() {
			if ( isset( $this->decoded_response->open_graph_url ) && ! empty( $this->decoded_response->highQuality ) ) {
				$this->vid['content_loc'] = $this->decoded_response->highQuality;
			}
		}

		/**
		 * Set the video duration
		 */
		protected function set_duration() {
			$this->set_duration_from_json_object();
		}

		/**
		 * Set the player location
		 */
		protected function set_player_loc() {
			/*
			 * @todo - find out what the player_loc should be - this method is set by (nearly)
			 * every other video class, so why not in this one?
			 */
		}

		/**
		 * Set the thumbnail location
		 */
		protected function set_thumbnail_loc() {
			if ( isset( $this->decoded_response->thumbnail ) && is_string( $this->decoded_response->thumbnail ) && $this->decoded_response->thumbnail !== '' ) {
				$image = $this->make_image_local( $this->decoded_response->thumbnail );
				if ( is_string( $image ) && $image !== '' ) {
					$this->vid['thumbnail_loc'] = $image;
				}
			}
		}

		/**
		 * Set the video view count
		 */
		protected function set_view_count() {
			if ( ! empty( $this->decoded_response->views ) ) {
				$this->vid['view_count'] = $this->decoded_response->views;
			}
		}
	} /* End of class */

} /* End of class-exists wrapper */
