<?php
/**
 * Convert Pro Addon Advanced Scripts loader file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CPRO_Scripts_Loader' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 1.0.0
	 */
	final class CPRO_Scripts_Loader {

		/**
		 * Class Instance.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var array $instance
		 */
		private static $instance;

		/**
		 * All script code.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var array $all_script_code
		 */
		public static $all_script_code = null;

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {

			add_filter( 'cp_after_design_fields', array( $this, 'design_field_options' ), 10, 2 );
			add_action( 'after_shortcode_execute', array( $this, 'after_shortcode_execute' ), 10, 1 );
			add_action( 'wp_footer', array( $this, 'load_script_globally' ), 999 );
		}

		/**
		 * Design field options.
		 *
		 * @since 1.0.0
		 * @param array $options Option array.
		 * @param array $slug Slug.
		 * @return array $options
		 */
		public function design_field_options( $options, $slug ) {

			if (
				'before_after' != $slug
				&& 'inline' != $slug
				&& 'widget' != $slug
			) {

				$options[] = array(
					'type'         => 'textarea',
					'class'        => '',
					'name'         => 'popup_open_script',
					'opts'         => array(
						'title'       => __( 'After Call-to-action Open', 'convertpro-addon' ),
						'value'       => '',
						'description' => '',
						'tags'        => 'js,css,script,style',
					),
					'panel'        => 'Panel',
					'section'      => 'Design',
					'section_icon' => 'cp-icon-panel',
					'category'     => 'Additional JavaScript',
				);
				$options[] = array(
					'type'         => 'textarea',
					'class'        => '',
					'name'         => 'popup_close_script',
					'opts'         => array(
						'title'       => __( 'After Call-to-action Close', 'convertpro-addon' ),
						'value'       => '',
						'description' => '',
						'tags'        => 'js,css,script,style',
					),
					'panel'        => 'Panel',
					'section'      => 'Design',
					'section_icon' => 'cp-icon-panel',
					'category'     => 'Additional JavaScript',
				);
			}
			$options[] = array(
				'type'         => 'textarea',
				'class'        => '',
				'name'         => 'popup_submit_script',
				'opts'         => array(
					'title'       => __( 'After Submission', 'convertpro-addon' ),
					'value'       => '',
					'description' => '',
					'tags'        => 'js,css,script,style',
				),
				'panel'        => 'Panel',
				'section'      => 'Design',
				'section_icon' => 'cp-icon-panel',
				'category'     => 'Additional JavaScript',
			);
			return $options;
		}

		/**
		 * Shortcode.
		 *
		 * @since 1.0.0
		 * @param array $style_id Design ID.
		 * @return void
		 */
		public function after_shortcode_execute( $style_id ) {

			$popup_open   = cpro_get_style_settings( $style_id, 'design', 'popup_open_script' );
			$popup_close  = cpro_get_style_settings( $style_id, 'design', 'popup_close_script' );
			$popup_submit = cpro_get_style_settings( $style_id, 'design', 'popup_submit_script' );

			if ( '' != $popup_open || '' != $popup_close || '' != $popup_submit ) {

				/* After Popup Open Event */
				self::$all_script_code .= '<script>';

				if ( '' != $popup_open ) {
					self::$all_script_code .= 'jQuery( window ).on( "cp_after_popup_open", function( event, selector, module_type, style_id ) { if( style_id == "' . $style_id . '" ) {';
					self::$all_script_code .= $popup_open;
					self::$all_script_code .= '}});';
				}

				if ( '' != $popup_close ) {

					self::$all_script_code .= 'jQuery( document ).on( "closePopup", function( event, selector, style_id) { if( style_id == "' . $style_id . '" ) {';
					self::$all_script_code .= $popup_close;
					self::$all_script_code .= '}});';
				}

				if ( '' != $popup_submit ) {
					self::$all_script_code .= 'jQuery( document ).on( "cp_after_submit_action", function( event, modal, style_id, data ) { if( style_id == "' . $style_id . '" ) {';
					self::$all_script_code .= $popup_submit;
					self::$all_script_code .= '}});';
				}

				self::$all_script_code .= '</script>';
			}
		}

		/**
		 * Load script globally.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function load_script_globally() {

			if ( null != self::$all_script_code ) {
				echo self::$all_script_code;
			}
		}
	}

	$script_loader = CPRO_Scripts_Loader::get_instance();
}
