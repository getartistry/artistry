<?php
/**
 * Astra Addon Customizer
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Addon_Customizer' ) ) :

	/**
	 * Astra_Addon_Customizer
	 *
	 * @since 1.0.0
	 */
	class Astra_Addon_Customizer {

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ) );
		}

		/**
		 * Register custom section and panel.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register( $wp_customize ) {
			// Register controls.
			$wp_customize->register_control_type( 'Astra_Control_Background' );

			// Helper files.
			require ASTRA_EXT_DIR . 'classes/customizer/controls/background/class-astra-control-background.php';
		}

		/**
		 * Sanitize background obj
		 *
		 * @param  array $bg_obj Background object.
		 * @return array         Background object.
		 */
		static public function sanitize_background_obj( $bg_obj ) {
			if ( is_callable( 'Astra_Customizer_Sanitizes::sanitize_background_obj' ) ) {
				return Astra_Customizer_Sanitizes::sanitize_background_obj( $bg_obj );
			}

			return $bg_obj;
		}

	}

	/**
	 * Initialize class object with 'get_instance()' method
	 */
	Astra_Addon_Customizer::get_instance();

endif;
