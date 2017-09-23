<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>
<?php
$rgb = array(0,0,0);
if (isset($option['bgcol'])) { $rgb = wtfdivi046_hex2rgb($option['bgcol']); }
?>

/* Set background */
.et_pb_slide_description,
.et_pb_slide_description:before,
.et_pb_slide_description:after,
#et_builder_outer_content .et_pb_slide_description,
#et_builder_outer_content .et_pb_slide_description:before,
#et_builder_outer_content .et_pb_slide_description:after {
	background-color: rgba(<?php echo intval($rgb[0]); ?>, <?php echo intval($rgb[1]); ?>, <?php echo intval($rgb[2]); ?>, <?php echo htmlentities(@$option['opacity']/100); ?>);	
}
.et_pb_slide_description,
#et_builder_outer_content .et_pb_slide_description { 
	background-clip: content-box; 
}
.et_pb_slide_description:before,
.et_pb_slide_description:after { 
	content: ''; 
	display: block; 
	width: 100%; 
	height: 15px; 
}
.et_pb_slide_description:before { 
	margin-top:-15px; 
}
.et_pb_slide_description:after {  
	margin-bottom: -15px;
}

/* Rounded borders */
.et_pb_slide_description:before { 
	border-top-left-radius: 15px; 
	border-top-right-radius: 15px; 
}
.et_pb_slide_description:after { 
	border-bottom-left-radius: 15px; 
	border-bottom-right-radius: 15px; 
}

/* Layout adjustments */
.et_pb_more_button,
#et_builder_outer_content .et_pb_more_button { 
	margin-left: 15px; 
	margin-right: 15px; 
}
.db_pb_button_2,
#et_builder_outer_content .db_pb_button_2 {
	margin-left:15px !important;
}
.et_pb_slide_description .et_pb_slide_title {
	padding: 30px 30px 0 30px;
}
.et_pb_slide_description .et_pb_slide_content {
	padding: 0 30px 30px;
}

<?php
function wtfdivi046_hex2rgb( $colour ) {
	if ( $colour[0] == '#' ) {
			$colour = substr( $colour, 1 );
	}
	if ( strlen( $colour ) == 6 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
	} elseif ( strlen( $colour ) == 3 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
	} else {
			return false;
	}
	return array(hexdec($r), hexdec($g), hexdec($b));
}
?>

