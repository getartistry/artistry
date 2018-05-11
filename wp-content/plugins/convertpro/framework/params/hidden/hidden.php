<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

$hidden_attributes = array(
	'id'      => 'cp_hidden_par',
	'type'    => 'hidden',
	'min'     => 1,
	'max'     => 100,
	'scripts' => 'hidden.js',
	'styles'  => '',
);

echo json_encode( $hidden_attributes );
