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
		 * @since 1.4.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_register', array( $this, 'customize_register_new' ), 3 );

		}

		/**
		 * Register custom section and panel.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register_new( $wp_customize ) {

			require ASTRA_EXT_DIR . 'classes/customizer/class-astra-customizer-notices-configs.php';
		}


		/**
		 * Register custom section and panel.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register( $wp_customize ) {

			// if Customizer Control base class exist.
			if ( class_exists( 'Astra_Customizer_Control_Base' ) ) {

				/**
				 * Add Controls
				 */

				Astra_Customizer_Control_Base::add_control(
					'ast-responsive-background',
					array(
						'callback'         => 'Astra_Control_Responsive_Background',
						'santize_callback' => 'sanitize_responsive_background',
					)
				);

				Astra_Customizer_Control_Base::add_control(
					'ast-responsive-color',
					array(
						'callback'         => 'Astra_Control_Responsive_Color',
						'santize_callback' => 'sanitize_responsive_color',
					)
				);

				Astra_Customizer_Control_Base::add_control(
					'ast-border',
					array(
						'callback'         => 'Astra_Control_Border',
						'santize_callback' => 'sanitize_border',
					)
				);
				Astra_Customizer_Control_Base::add_control(
					'ast-customizer-refresh',
					array(
						'callback'          => 'Astra_Control_Customizer_refresh',
						'sanitize_callback' => '',
					)
				);
			}

			// Helper files.
			require ASTRA_EXT_DIR . 'classes/customizer/controls/background/class-astra-control-background.php';
			require ASTRA_EXT_DIR . 'classes/customizer/controls/border/class-astra-control-border.php';
			require ASTRA_EXT_DIR . 'classes/customizer/controls/responsive-color/class-astra-control-responsive-color.php';
			require ASTRA_EXT_DIR . 'classes/customizer/controls/responsive-background/class-astra-control-responsive-background.php';
			require ASTRA_EXT_DIR . 'classes/customizer/controls/heading/class-astra-control-heading.php';
			require ASTRA_EXT_DIR . 'classes/customizer/controls/color/class-astra-control-color.php';
			require ASTRA_EXT_DIR . 'classes/customizer/controls/class-astra-control-customizer-refresh.php';
		}

		/**
		 * Sanitize Alpha color
		 *
		 * @param  string $color setting input.
		 * @return string        setting input value.
		 */
		static public function sanitize_alpha_color( $color ) {

			if ( '' === $color ) {
				return '';
			}

			if ( false === strpos( $color, 'rgba' ) ) {
				/* Hex sanitize */
				return Astra_Customizer_Sanitizes::sanitize_hex_color( $color );
			}

			/* rgba sanitize */
			$color = str_replace( ' ', '', $color );
			sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
			return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
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

		/**
		 * Sanitize Border
		 *
		 * @param  array $border Background object.
		 * @return array         Background object.
		 */
		static public function sanitize_border( $border ) {
			if ( is_callable( 'Astra_Customizer_Sanitizes::sanitize_border' ) ) {
				return Astra_Customizer_Sanitizes::sanitize_border( $border );
			}

			return $border;
		}

		/**
		 * Sanitize Responsive Background Image
		 *
		 * @param  array $bg_obj Background object.
		 * @return array         Background object.
		 */
		static public function sanitize_responsive_background( $bg_obj ) {
			if ( is_callable( 'Astra_Customizer_Sanitizes::sanitize_responsive_background' ) ) {
				return Astra_Customizer_Sanitizes::sanitize_responsive_background( $bg_obj );
			}

			// Default Responsive Background Image.
			$defaults = array(
				'desktop' => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
				'tablet'  => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
				'mobile'  => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
			);

			// Merge responsive background object and default object into $out_bg_obj array.
			$out_bg_obj = wp_parse_args( $bg_obj, $defaults );

			foreach ( $out_bg_obj as $device => $bg ) {
				foreach ( $bg as $key => $value ) {
					if ( 'background-image' === $key ) {
						$out_bg_obj[ $device ] [ $key ] = esc_url_raw( $value );
					} else {
						$out_bg_obj[ $device ] [ $key ] = esc_attr( $value );
					}
				}
			}
			return $out_bg_obj;
		}

		/**
		 * Sanitize Responsive Color
		 *
		 * @param  array $color_obj color object.
		 * @return array         color object.
		 */
		static public function sanitize_responsive_color( $color_obj ) {

			// Default Responsive Background Image.
			$defaults = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			// Merge responsive color object and default object into $out_color_obj array.
			$out_color_obj = wp_parse_args( $color_obj, $defaults );

			foreach ( $out_color_obj as $device => $color ) {
				$out_color_obj[ $device ] = Astra_Customizer_Sanitizes::sanitize_alpha_color( $color );
			}
			return $out_color_obj;
		}
	}

	/**
	 * Initialize class object with 'get_instance()' method
	 */
	Astra_Addon_Customizer::get_instance();

endif;
