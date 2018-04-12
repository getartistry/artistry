<?php
/**
 * UAEL Config.
 *
 * @package UAEL
 */

namespace UltimateElementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use UltimateElementor\Classes\UAEL_Helper;

/**
 * Class UAEL_Config.
 */
class UAEL_Config {

	/**
	 * Widget List
	 *
	 * @var widget_list
	 */
	public static $widget_list = null;

	/**
	 * Get Widget List.
	 *
	 * @since 0.0.1
	 *
	 * @return array The Widget List.
	 */
	public static function get_widget_list() {

		if ( null === self::$widget_list ) {

			self::$widget_list = array(
				'Advanced_Heading' => array(
					'slug'      => 'uael-advanced-heading',
					'title'     => __( 'Advanced Heading', 'uael' ),
					'icon'      => 'uael-icon-adv-heading',
					'title_url' => '#',
					'default'   => true,
				),
				'BaSlider'         => array(
					'slug'      => 'uael-ba-slider',
					'title'     => __( 'Before After Slider', 'uael' ),
					'icon'      => 'uael-icon-before-after',
					'title_url' => '#',
					'default'   => true,
				),
				'Buttons'          => array(
					'slug'      => 'uael-buttons',
					'title'     => __( 'Multi Buttons', 'uael' ),
					'icon'      => 'uael-icon-button',
					'title_url' => '#',
					'default'   => true,
				),
				'ContentToggle'    => array(
					'slug'      => 'uael-content-toggle',
					'title'     => __( 'Content Toggle', 'uael' ),
					'icon'      => 'uael-icon-content-toggle',
					'title_url' => '#',
					'default'   => true,
				),
				'Dual_Heading'     => array(
					'slug'      => 'uael-dual-color-heading',
					'title'     => __( 'Dual Color Heading', 'uael' ),
					'icon'      => 'uael-icon-dual-col',
					'title_url' => '#',
					'default'   => true,
				),
				'Fancy_Heading'    => array(
					'slug'      => 'uael-fancy-heading',
					'title'     => __( 'Fancy Heading', 'uael' ),
					'icon'      => 'uael-icon-fancy-text',
					'title_url' => '#',
					'default'   => true,
				),
				'Infobox'          => array(
					'slug'      => 'uael-infobox',
					'title'     => __( 'Info Box', 'uael' ),
					'icon'      => 'uael-icon-info-box',
					'title_url' => '#',
					'default'   => true,
				),
				'Modal_Popup'      => array(
					'slug'      => 'uael-modal-popup',
					'title'     => __( 'Modal Popup', 'uael' ),
					'icon'      => 'uael-icon-popup',
					'title_url' => '#',
					'default'   => true,
				),
			);
		}

		return self::$widget_list;
	}

	/**
	 * Returns Script array.
	 *
	 * @return array()
	 * @since 0.0.1
	 */
	static public function get_widget_script() {

		$folder = UAEL_Helper::get_js_folder();
		$suffix = UAEL_Helper::get_js_suffix();

		$js_files = array(
			'uael-frontend-script'  => array(
				'path'      => 'assets/' . $folder . '/uael-frontend' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'uael-cookie-lib'       => array(
				'path'      => 'assets/' . $folder . '/js_cookie' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'uael-modal-popup'      => array(
				'path'      => 'assets/' . $folder . '/uael-modal-popup' . $suffix . '.js',
				'dep'       => [ 'jquery', 'uael-cookie-lib' ],
				'in_footer' => true,
			),
			'uael-twenty-twenty'    => array(
				'path'      => 'assets/' . $folder . '/jquery_twentytwenty' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'uael-move'             => array(
				'path'      => 'assets/' . $folder . '/jquery_event_move' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'uael-fancytext-typed'  => array(
				'path'      => 'assets/' . $folder . '/typed' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'uael-fancytext-slidev' => array(
				'path'      => 'assets/' . $folder . '/rvticker' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
		);

		return $js_files;
	}

	/**
	 * Returns Style array.
	 *
	 * @return array()
	 * @since 0.0.1
	 */
	static public function get_widget_style() {

		if ( UAEL_Helper::is_script_debug() ) {

			$css_files = array(
				'uael-info-box'       => array(
					'path' => 'assets/css/modules/info-box.css',
					'dep'  => [],
				),
				'uael-heading'        => array(
					'path' => 'assets/css/modules/heading.css',
					'dep'  => [],
				),
				'uael-ba-slider'      => array(
					'path' => 'assets/css/modules/ba-slider.css',
					'dep'  => [],
				),
				'uael-buttons'        => array(
					'path' => 'assets/css/modules/buttons.css',
					'dep'  => [],
				),
				'uael-modal-popup'    => array(
					'path' => 'assets/css/modules/modal-popup.css',
					'dep'  => [],
				),
				'uael-content-toggle' => array(
					'path' => 'assets/css/modules/content-toggle.css',
					'dep'  => [],
				),
			);
		} else {

			$css_files = array(
				'uael-frontend' => array(
					'path' => 'assets/min-css/uael-frontend.min.css',
					'dep'  => [],
				),
			);
		}

		if ( is_rtl() ) {
			$css_files = array(
				'uael-frontend' => array(
					// This is autogenerated rtl file.
					'path' => 'assets/min-css/uael-frontend-rtl.min.css',
					'dep'  => [],
				),
			);
		}

		return $css_files;
	}
}
