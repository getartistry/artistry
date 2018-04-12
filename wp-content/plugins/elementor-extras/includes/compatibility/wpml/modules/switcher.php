<?php
namespace ElementorExtras\Compatibility\WPML;

use WPML_Elementor_Module_With_Items;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Switcher
 *
 * Registers translatable module with items
 *
 * @since 1.9.0
 */
class Switcher extends WPML_Elementor_Module_With_Items {

	/**
	 * @since 1.9.0
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array(
			'title',
			'label',
		);
	}

	/**
	 * @param string $field
	 * @since 1.9.0
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		if ( 'title' === $field ) {
			return esc_html__( 'Switcher: Title', 'elementor-extras' );
		}

		if ( 'label' === $field ) {
			return esc_html__( 'Switcher: Navigation Label', 'elementor-extras' );
		}

		return '';
	}

	/**
	 * @param string $field
	 * @since 1.9.0
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {

		switch( $field ) {
			case 'title':
			case 'label':
				return 'LINE';

			default:
				return '';
		 }
	}

}
