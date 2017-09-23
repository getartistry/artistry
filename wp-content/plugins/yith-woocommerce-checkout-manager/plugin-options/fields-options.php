<?php
/**
 * GENERAL ARRAY OPTIONS
 */
if ( ! defined( 'YWCCP' ) ) {
	exit;
} // Exit if accessed directly

$billing = array(

	'fields' => array(
		array(
			'type'   => 'custom_tab',
			'action' => 'ywccp_fields_general_section'
		),
	)
);

return apply_filters( 'ywccp_panel_billing_options', $billing );