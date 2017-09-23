<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
/* Fill image area */
.et_pb_filterable_portfolio_grid .et_portfolio_image img {
	
	/* Ensure image fills area */
	zoom: 0.1 !important;
    height: auto !important;
    min-height: 100% !important;
    width: auto !important;
    min-width: 100% !important;
	max-width: none !important;
	max-height: none !important;
	
	/* Center image in area */
	position: absolute;
	top: 50%; 
	left: 50%;
	margin-right:-50%;
	transform: translate(-50%, -50%) !important;
	
}

/* Fix translation 1/2 pixel issue */
.et_filterable_pb_portfolio_grid .et_portfolio_image {
	-webkit-transform-style: preserve-3d;
	-moz-transform-style: preserve-3d;
	transform-style: preserve-3d;
}

