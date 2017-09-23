<?php

function woocommerce_catalog_restrictions_country_input( $value = '', $args = '' ) {
	global $woocommerce;

	$key = 'location';

	$args = wp_parse_args( $args, array('class' => array(), 'id' => 'location', 'label_class' => array(), 'label' => __( 'Select your location', 'wc_catalog_restrictions' )) );

	$field = '<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) . '">' . $args['label'] . '</label>
              <select name="' . $key . '" id="' . $key . '" class="country_to_state ' . implode( ' ', $args['class'] ) . '">
              <option value="">' . __( 'Select a country&hellip;', 'woocommerce' ) . '</option>';

	foreach ( $woocommerce->countries->get_allowed_countries() as $ckey => $cvalue ) {
		$selected = '';
		if ( is_array( $value ) ) {
			$selected = in_array( $ckey, $value ) ? 'selected="selected"' : '';
		} else {
			$selected = $ckey == $value ? 'selected="selected"' : '';
		}

		$field .= '<option value="' . $ckey . '" ' . $selected . '>' . __( $cvalue, 'woocommerce' ) . '</option>';
	}

	$field .= '</select>';

	echo $field;
}

function woocommerce_catalog_restrictions_country_multiselect_options( $selected_countries = '', $escape = false ) {
	$countries = WC()->countries->get_allowed_countries();
	foreach ( $countries as $key => $val ) {

		echo '<option ' . selected( in_array( $key, $selected_countries ), true, false ) . ' value="' . $key . '">' . ( $escape ? esc_js( $val ) : $val ) . '</option>';
	}
}