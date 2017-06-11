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
 * Add support for the Vzaar Media Management plugin
 *
 * @see https://wordpress.org/plugins/vzaar-media-management/
 *
 * {@internal Last update: July 2014 based upon v 1.2.}}
 */
if ( ! class_exists( 'WPSEO_Video_Plugin_Vzaar_Media_Management' ) ) {

	/**
	 * Class WPSEO_Video_Plugin_Vzaar_Media_Management
	 *
	 * Take Note: this class extends the Vzaar Official class!
	 */
	class WPSEO_Video_Plugin_Vzaar_Media_Management extends WPSEO_Video_Plugin_Vzaar_Official {


		/**
		 * Conditionally add plugin features to analyse for video content
		 */
		public function __construct() {
			if ( class_exists( 'esa_vzaarLoader' ) ) {
				$this->shortcodes[] = 'vzaarmedia';
			}
		}
	} /* End of class */

} /* End of class-exists wrapper */
