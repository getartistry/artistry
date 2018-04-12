<?php
namespace ElementorExtras\Compatibility\WPML;

use WPML_Elementor_Module_With_Items;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Hotspots
 *
 * Registers translatable module with items
 *
 * @since 1.8.8
 */
class Hotspots extends WPML_Elementor_Module_With_Items {

	/**
	 * @since 1.8.8
	 * @return string
	 */
	public function get_items_field() {
		return 'hotspots';
	}

	/**
	 * Retrieve the fields inside the repeater
	 * 
	 * @since 1.8.8
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'text', 					// Hotspot label
			'content', 					// Tooltip content
			'link' => array( 'url' ), 	// Hotspot link
		);
	}

	/**
	 * Method for setting the title for each translatable field
	 *
	 * @since 1.8.8
	 *
	 * @param string    $field The name of the field
	 * @return string
	 */
	protected function get_title( $field ) {
		if ( 'text' === $field ) {
			return esc_html__( 'Hotspots: Hotspot Text', 'elementor-extras' );
		}

		if ( 'content' === $field ) {
			return esc_html__( 'Hotspots: Tooltip Content', 'elementor-extras' );
		}

		if ( 'url' === $field ) {
			return esc_html__( 'Hotspots: Link', 'elementor-extras' );
		}

		return '';
	}

	/**
	 * Method for determining the editor type for each field
	 * @since 1.8.8
	 *
	 * @param  string    $field Name of the field
	 * @return string
	 */
	protected function get_editor_type( $field ) {

		switch( $field ) {
			case 'text':
			case 'url':
				return 'LINE';
	 
			case 'content':
				return 'VISUAL';
	 
			default:
				return '';
		 }
	}
}