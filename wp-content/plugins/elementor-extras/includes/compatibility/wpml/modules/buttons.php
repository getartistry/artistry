<?php
namespace ElementorExtras\Compatibility\WPML;

use WPML_Elementor_Module_With_Items;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Buttons
 *
 * Registers translatable module with items
 *
 * @since 1.8.8
 */
class Buttons extends WPML_Elementor_Module_With_Items {

	/**
	 * @since 1.8.8
	 * @return string
	 */
	public function get_items_field() {
		return 'buttons';
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
			'text',
			'tooltip_content',
			'link' => array( 'url' ),
			'button_effect_text',
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
			return esc_html__( 'Buttons: Text', 'elementor-extras' );
		}

		if ( 'tooltip_content' === $field ) {
			return esc_html__( 'Buttons: Tooltip Content', 'elementor-extras' );
		}

		if ( 'url' === $field ) {
			return esc_html__( 'Buttons: Link', 'elementor-extras' );
		}

		if ( 'button_effect_text' === $field ) {
			return esc_html__( 'Buttons: Effect Text', 'elementor-extras' );
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
			case 'tooltip_content':
			case 'url':
			case 'button_effect_text':
				return 'LINE';
	 
			default:
				return '';
		 }
	}

}
