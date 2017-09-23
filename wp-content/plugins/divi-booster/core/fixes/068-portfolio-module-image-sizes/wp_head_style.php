<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

/* Set the image widths */
.et_pb_portfolio_grid .et_pb_portfolio_item,
.et_pb_portfolio_grid et_portfolio_image,
.et_pb_portfolio_grid div.et_pb_portfolio_item {
    width: <?php echo htmlentities(@$option['imagewidth']); ?>px !important;
}

/* Set the image heights */
.et_pb_portfolio_grid .et_portfolio_image {
    height: <?php echo htmlentities(@$option['imageheight']); ?>px !important;
	overflow:hidden
}

/* Set the spacing between images */
.et_pb_portfolio_grid div.et_pb_portfolio_item { 
	<?php $margin = @floor((1080-$option['imagewidth']*$option['imagescount'])/$option['imagescount']); ?>
	margin-bottom:<?php echo intval($margin); ?>px !important; 
	margin-right: <?php echo round(intval($margin)/2); ?>px !important; 
	<?php if (!is_divi24()) { ?>
	margin-left: <?php echo round(intval($margin)/2); ?>px !important; 
	<?php } ?>
}

/* Change the position of the line breaks */
.et_pb_portfolio_grid .et_pb_portfolio_item { clear:none !important; }
.et_pb_portfolio_grid div.et_pb_portfolio_item:nth-child(<?php echo intval($option['imagescount']); ?>n+1) { clear:none !important; }

/* Ensure images float on mobile layouts */
.et_pb_portfolio_grid div.et_pb_portfolio_item { 
	float:left !important;
}

/* Fit the image to the box and center */
.et_pb_portfolio_grid .et_portfolio_image {
	-webkit-transform-style: preserve-3d;
	-moz-transform-style: preserve-3d;
	transform-style: preserve-3d;
}
.et_pb_portfolio_grid .et_portfolio_image > img {
	position: relative;
	top: 50%;
	transform: translateY(-50%);
}

/* Fill image area */
.et_pb_portfolio_grid .et_portfolio_image > img {
	
	/* Ensure image is contained within area */
	zoom: 10 !important;
    height: auto !important;
    max-height: 100% !important;
    width: auto !important;
    max-width: 100% !important;
	min-width: 0 !important;
	min-height: 0 !important;
	
	/* Center image in area */
	position: absolute;
	top: 50%; 
	left: 50%;
	margin-right:-50%;
	transform: translate(-50%, -50%) !important;
	
}

/* Fix translation 1/2 pixel issue */
.et_pb_portfolio_grid .et_portfolio_image {
	-webkit-transform-style: preserve-3d;
	-moz-transform-style: preserve-3d;
	transform-style: preserve-3d;
}

/* Disable zooming on IE as it overrides the max width / height */
body.ie div.et_pb_portfolio_grid span.et_portfolio_image > img {
    zoom: 1 !important; 
}
