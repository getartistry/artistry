<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

$cpfonts = new CP_V2_Fonts();
$fonts   = $cpfonts::cp_get_fonts();

$text_attributes = array(
	'id'      => 'cp_font_par',
	'type'    => 'font',
	'scripts' => '',
	'styles'  => '',
	'options' => $fonts,
);

echo json_encode( $text_attributes );
