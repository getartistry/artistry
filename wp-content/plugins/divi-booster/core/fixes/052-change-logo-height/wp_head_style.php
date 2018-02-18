<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

@media only screen and ( min-width:981px ) {
	#logo { 
		height: <?php echo htmlentities(@$option['normal']); ?>px; 
		max-height: <?php echo htmlentities(@$option['normal']); ?>px !important; 
		padding-bottom:18px; 
	}
	.et-fixed-header #logo { 
		max-height: <?php echo htmlentities(@$option['shrunk']); ?>px !important; 
		<?php if (!is_divi24()) { ?>padding-bottom:10px !important;<?php } ?> 
	}
	.et-fixed-header div#page-container { 
		padding-top: <?php echo htmlentities(@$option['normal'])+18+18; ?>px !important; 
	}
	<?php if (is_divi24()) { ?>
	.et_header_style_left .et_menu_container { 
		height:<?php echo htmlentities(@$option['normal']+36); ?>px!important; 
	}
	.et-fixed-header .et_menu_container { 
		height:<?php echo htmlentities(@$option['shrunk']+20); ?>px!important; 
	}
	#logo { 
		max-height:100% !important; 
		margin-top:18px; 
		margin-bottom:18px !important; 
		border-sizing:border-box !important; 
		padding-bottom:0px !important
	}
	.et-fixed-header #logo { 
		margin-top:0px !important; 
		margin-bottom:0px !important; 
		padding-bottom: 0px !important;
	}
	<?php } ?>
}