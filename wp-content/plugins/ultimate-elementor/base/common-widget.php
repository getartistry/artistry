<?php
/**
 * UAEL Common Widget.
 *
 * @package UAEL
 */

namespace UltimateElementor\Base;

use Elementor\Widget_Base;
use UltimateElementor\Classes\UAEL_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Widget
 *
 * @since 0.0.1
 */
abstract class Common_Widget extends Widget_Base {

	/**
	 * Get categories
	 *
	 * @since 0.0.1
	 */
	public function get_categories() {
		return [ 'ultimate-elements' ];
	}

	/**
	 * Get widget slug
	 *
	 * @param string $slug Module slug.
	 * @since 0.0.1
	 */
	public function get_widget_slug( $slug = '' ) {
		return UAEL_Helper::get_widget_slug( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module slug.
	 * @since 0.0.1
	 */
	public function get_widget_title( $slug = '' ) {
		return UAEL_Helper::get_widget_title( $slug );
	}

	/**
	 * Get widget icon
	 *
	 * @param string $slug Module slug.
	 * @since 0.0.1
	 */
	public function get_widget_icon( $slug = '' ) {
		return UAEL_Helper::get_widget_icon( $slug );
	}
}
