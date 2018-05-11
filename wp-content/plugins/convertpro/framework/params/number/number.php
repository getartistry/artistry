<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

$number_attributes = array(
	'id'      => 'cp_number_par',
	'type'    => 'number',
	'min'     => 1,
	'max'     => 100,
	'step'    => 1,
	'scripts' => 'number.js',
	'styles'  => '',
);

echo json_encode( $number_attributes );
