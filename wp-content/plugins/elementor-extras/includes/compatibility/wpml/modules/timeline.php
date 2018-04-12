<?php
namespace ElementorExtras\Compatibility\WPML;

use WPML_Elementor_Module_With_Items;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Timeline
 *
 * Registers translatable module with items
 *
 * @since 1.8.8
 */
class Timeline extends WPML_Elementor_Module_With_Items {

	/**
	 * @since 1.8.8
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
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
			'date', 					// Timeline item date
			'content', 					// Mobile cell header
			'link' => array( 'url' ), 	// Timeline item link
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
		if ( 'date' === $field ) {
			return esc_html__( 'Timeline: Date', 'elementor-extras' );
		}

		if ( 'content' === $field ) {
			return esc_html__( 'Timeline: Content', 'elementor-extras' );
		}

		if ( 'url' === $field ) {
			return esc_html__( 'Timeline: Link', 'elementor-extras' );
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
			case 'date':
			case 'url':
				return 'LINE';

			case 'content':
				return 'VISUAL';
	 
			default:
				return '';
		 }
	}

}
