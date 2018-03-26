<?php
/**
 * Customizer Partial.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Partials
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Astra_Ext_Sticky_Header_Customizer_Partials' ) ) {

	/**
	 * Customizer Partials initial setup
	 */
	class Astra_Ext_Sticky_Header_Customizer_Partials {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() { }

		/**
		 * Render Stickt Header Custom Logo
		 */
		function _render_sticky_header_logo() {
			$header_logo = astra_get_option( 'sticky-header-logo' );

			if ( '' !== $header_logo ) {
				$custom_logo_id = attachment_url_to_postid( $header_logo );
				$html           = sprintf(
					'<a href="%1$s" class="sticky-custom-logo-link" rel="home" itemprop="url">%2$s</a>',
					esc_url( home_url( '/' ) ),
					wp_get_attachment_image(
						$custom_logo_id, 'full', false, array(
							'class' => 'custom-logo',
						)
					)
				);
				return $html;
			}

		}

	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Sticky_Header_Customizer_Partials::get_instance();
