<?php
/**
 * UAEL Helper.
 *
 * @package UAEL
 */

namespace UltimateElementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use UltimateElementor\Classes\UAEL_Config;

/**
 * Class UAEL_Helper.
 */
class UAEL_Helper {

	/**
	 * CSS files folder
	 *
	 * @var css_folder
	 */
	private static $script_debug = null;

	/**
	 * CSS files folder
	 *
	 * @var css_folder
	 */
	private static $css_folder = null;

	/**
	 * CSS Suffix
	 *
	 * @var css_suffix
	 */
	private static $css_suffix = null;

	/**
	 * RTL CSS Suffix
	 *
	 * @var rtl_css_suffix
	 */
	private static $rtl_css_suffix = null;

	/**
	 * JS files folder
	 *
	 * @var js_folder
	 */
	private static $js_folder = null;

	/**
	 * JS Suffix
	 *
	 * @var css_suffix
	 */
	private static $js_suffix = null;

	/**
	 * Widget Options
	 *
	 * @var css_suffix
	 */
	private static $widget_options = null;

	/**
	 * Widget List
	 *
	 * @var css_suffix
	 */
	private static $widget_list = null;

	/**
	 * Provide General settings array().
	 *
	 * @return array()
	 * @since 0.0.1
	 */
	static public function get_widget_list() {

		self::$widget_list = UAEL_Config::get_widget_list();

		return apply_filters( 'uael_widget_list', self::$widget_list );
	}

	/**
	 * Check is script debug enabled.
	 *
	 * @since 0.0.1
	 *
	 * @return string The CSS suffix.
	 */
	public static function is_script_debug() {

		if ( null === self::$script_debug ) {

			self::$script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		}

		return self::$script_debug;
	}

	/**
	 * Get CSS Folder.
	 *
	 * @since 0.0.1
	 *
	 * @return string The CSS folder.
	 */
	public static function get_css_folder() {

		if ( null === self::$css_folder ) {

			self::$css_folder = self::is_script_debug() ? 'css' : 'min-css';
		}

		return self::$css_folder;
	}

	/**
	 * Get CSS suffix.
	 *
	 * @since 0.0.1
	 *
	 * @return string The CSS suffix.
	 */
	public static function get_css_suffix() {

		if ( null === self::$css_suffix ) {

			self::$css_suffix = self::is_script_debug() ? '' : '.min';
		}

		return self::$css_suffix;
	}

	/**
	 * Get JS Folder.
	 *
	 * @since 0.0.1
	 *
	 * @return string The JS folder.
	 */
	public static function get_js_folder() {

		if ( null === self::$js_folder ) {

			self::$js_folder = self::is_script_debug() ? 'js' : 'min-js';
		}

		return self::$js_folder;
	}

	/**
	 * Get JS Suffix.
	 *
	 * @since 0.0.1
	 *
	 * @return string The JS suffix.
	 */
	public static function get_js_suffix() {

		if ( null === self::$js_suffix ) {

			self::$js_suffix = self::is_script_debug() ? '' : '.min';
		}

		return self::$js_suffix;
	}

	/**
	 *  Get link rel attribute
	 *
	 *  @param string $target Target attribute to the link.
	 *  @param int    $is_nofollow No follow yes/no.
	 *  @param int    $echo Return or echo the output.
	 *  @since 0.0.1
	 *  @return string
	 */
	public static function get_link_rel( $target, $is_nofollow = 0, $echo = 0 ) {

		$attr = '';
		if ( '_blank' == $target ) {
			$attr .= 'noopener';
		}

		if ( 1 == $is_nofollow ) {
			$attr .= ' nofollow';
		}

		if ( '' == $attr ) {
			return;
		}

		$attr = trim( $attr );
		if ( ! $echo ) {
			return 'rel="' . $attr . '"';
		}
		echo 'rel="' . $attr . '"';
	}

	/**
	 * Returns an option from the database for
	 * the admin settings page.
	 *
	 * @param  string  $key     The option key.
	 * @param  boolean $network_override Whether to allow the network admin setting to be overridden on subsites.
	 * @return string           Return the option value
	 */
	public static function get_admin_settings_option( $key, $network_override = true ) {

		// Get the site-wide option if we're in the network admin.
		if ( $network_override && is_multisite() ) {
			$value = get_site_option( $key );
		} else {
			$value = get_option( $key );
		}

		return $value;
	}

	/**
	 * Updates an option from the admin settings page.
	 *
	 * @param string $key       The option key.
	 * @param mixed  $value     The value to update.
	 * @param bool   $network   Whether to allow the network admin setting to be overridden on subsites.
	 * @return mixed
	 */
	static public function update_admin_settings_option( $key, $value, $network = false ) {

		// Update the site-wide option since we're in the network admin.
		if ( $network && is_multisite() ) {
			update_site_option( $key, $value );
		} else {
			update_option( $key, $value );
		}

	}

	/**
	 * Provide White Label array().
	 *
	 * @return array()
	 * @since 0.0.1
	 */
	static public function get_white_labels() {

		$branding_default = apply_filters(
			'uael_branding_options', array(
				'agency'               => array(
					'author'        => '',
					'author_url'    => '',
					'hide_branding' => false,
				),
				'plugin'               => array(
					'name'        => '',
					'short_name'  => '',
					'description' => '',
					'cat_name'    => '',
					'screenshot'  => '',
				),
				'replace_logo'         => 'disable',
				'enable_knowledgebase' => 'enable',
				'knowledgebase_url'    => '',
				'enable_support'       => 'enable',
				'support_url'          => '',
			)
		);

		$branding = self::get_admin_settings_option( '_uael_white_label', true );
		$branding = wp_parse_args( $branding, $branding_default );

		return $branding;
	}

	/**
	 * Is White Label.
	 *
	 * @return string
	 * @since 0.0.1
	 */
	static public function is_hide_branding() {

		$branding = self::get_white_labels();

		if ( isset( $branding['agency']['hide_branding'] ) && false == $branding['agency']['hide_branding'] ) {

			return false;
		}

		return true;
	}

	/**
	 * Is replace_logo.
	 *
	 * @return string
	 * @since 0.0.1
	 */
	static public function is_replace_logo() {

		$branding = self::get_white_labels();

		if ( isset( $branding['replace_logo'] ) && 'disable' === $branding['replace_logo'] ) {

			return false;
		}

		return true;
	}

	/**
	 * Is Knowledgebase.
	 *
	 * @return string
	 * @since 0.0.1
	 */
	static public function knowledgebase_data() {

		$branding = self::get_white_labels();

		$knowledgebase = array(
			'enable_knowledgebase' => true,
			'knowledgebase_url'    => 'https://uaelementor.com/docs/',
		);

		if ( isset( $branding['enable_knowledgebase'] ) && 'disable' === $branding['enable_knowledgebase'] ) {

			$knowledgebase['enable_knowledgebase'] = false;
		}

		if ( isset( $branding['knowledgebase_url'] ) && '' !== $branding['knowledgebase_url'] ) {
			$knowledgebase['knowledgebase_url'] = $branding['knowledgebase_url'];
		}

		return $knowledgebase;
	}

	/**
	 * Is Knowledgebase.
	 *
	 * @return string
	 * @since 0.0.1
	 */
	static public function support_data() {

		$branding = self::get_white_labels();

		$support = array(
			'enable_support' => true,
			'support_url'    => 'https://uaelementor.com/support/',
		);

		if ( isset( $branding['enable_support'] ) && 'disable' === $branding['enable_support'] ) {

			$support['enable_support'] = false;
		}

		if ( isset( $branding['support_url'] ) && '' !== $branding['support_url'] ) {
			$support['support_url'] = $branding['support_url'];
		}

		return $support;
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 0.0.1
	 */
	static public function get_widget_slug( $slug = '' ) {

		$widget_list = self::get_widget_list();

		$widget_slug = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_slug = $widget_list[ $slug ]['slug'];
		}

		return apply_filters( 'uael_widget_slug', $widget_slug );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 0.0.1
	 */
	static public function get_widget_title( $slug = '' ) {

		$widget_list = self::get_widget_list();

		$widget_name = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_name = $widget_list[ $slug ]['title'];
		}

		return apply_filters( 'uael_widget_name', $widget_name );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 0.0.1
	 */
	static public function get_widget_icon( $slug = '' ) {

		$widget_list = self::get_widget_list();

		$widget_icon = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_icon = $widget_list[ $slug ]['icon'];
		}

		return apply_filters( 'uael_widget_icon', $widget_icon );
	}

	/**
	 * Provide Widget settings.
	 *
	 * @return array()
	 * @since 0.0.1
	 */
	static public function get_widget_options() {

		if ( null === self::$widget_options ) {

			$widgets       = self::get_widget_list();
			$saved_widgets = self::get_admin_settings_option( '_uael_widgets' );

			if ( is_array( $widgets ) ) {

				foreach ( $widgets as $slug => $data ) {

					if ( isset( $saved_widgets[ $slug ] ) ) {

						if ( 'disabled' === $saved_widgets[ $slug ] ) {
							$widgets[ $slug ]['is_activate'] = false;
						} else {
							$widgets[ $slug ]['is_activate'] = true;
						}
					} else {
						$widgets[ $slug ]['is_activate'] = ( isset( $data['default'] ) ) ? $data['default'] : false;
					}
				}
			}

			if ( false === self::is_hide_branding() ) {
				$widgets['White_Label'] = array(
					'slug'        => 'uael-white-label',
					'title'       => __( 'White Label', 'uael' ),
					'icon'        => '',
					'title_url'   => '#',
					'is_activate' => true,
				);
			}

			self::$widget_options = $widgets;
		}

		return apply_filters( 'uael_enabled_widgets', self::$widget_options );
	}

	/**
	 * Widget Active.
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 0.0.1
	 */
	static public function is_widget_active( $slug = '' ) {

		$widgets     = self::get_widget_options();
		$is_activate = false;

		if ( isset( $widgets[ $slug ] ) ) {
			$is_activate = $widgets[ $slug ]['is_activate'];
		}

		return $is_activate;
	}

	/**
	 * Returns Script array.
	 *
	 * @return array()
	 * @since 0.0.1
	 */
	static public function get_widget_script() {

		return UAEL_Config::get_widget_script();
	}

	/**
	 * Returns Style array.
	 *
	 * @return array()
	 * @since 0.0.1
	 */
	static public function get_widget_style() {

		return UAEL_Config::get_widget_style();
	}

}
