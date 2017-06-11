<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db005_user_css($plugin) { 
	list($name, $option) = $plugin->get_setting_bases(__FILE__); ?>

@media only screen and ( min-width:981px ) {
	/* Set the slider height */
	.et_pb_slider, .et_pb_slider .et_pb_container { height: <?php echo intval(@$option['sliderheight']); ?>px !important; }
	.et_pb_slider, .et_pb_slider .et_pb_slide { max-height: <?php echo intval(@$option['sliderheight']); ?>px; }
	.et_pb_slider .et_pb_slide_description { 
		position: relative; 
		top:25%; 
		padding-top: 15px !important;
		padding-bottom: 15px !important;
		margin-top: -15px !important;
		height:auto !important; 
	}
}

<?php 
}
add_action('wp_head.css', 'db005_user_css');