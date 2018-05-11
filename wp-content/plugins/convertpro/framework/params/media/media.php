<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

$text_attributes = array(
	'id'      => 'cp_media_par',
	'type'    => 'media',
	'scripts' => 'media.js',
	'styles'  => '',
);

echo json_encode( $text_attributes );
