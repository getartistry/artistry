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
 * Add support for the Premise plugin
 *
 * @see ....
 *
 * {@internal As no copy of the Premise plugin is available and in the original (old) code the
 * post meta values were handled in the same way as the VideoSEO custom fields post meta,
 * we're just extending the VideoSEO class to let that handle the post meta fields.
 * No other features identified which come specifically from Premise, so this should be fine.}}
 *
 * {@internal Last update: Never.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Premise' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Premise
	 */
	class WPSEO_Video_Plugin_Premise extends WPSEO_Video_Plugin_Yoast_Videoseo {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			// @todo - figure out what to test against to confirm that the plugin is loaded
			// if ( function_exists( 'WP_ayvpp_init' ) ) {
				$this->meta_keys[] = '_premise_settings';
			// }
		}
	} /* End of class */

} /* End of class-exists wrapper */
