<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

@media only screen and ( min-width: 1100px ) {

    .et_right_sidebar #sidebar .et_pb_widget { 
        margin-right:30px !important;
    }
	.et_left_sidebar #sidebar .et_pb_widget { 
        margin-left:30px !important;
    }
	
	.et_right_sidebar #left-area, 
	.et_left_sidebar #left-area { 
		width:<?php echo htmlentities(1080-@$option['sidebarwidth']-60); ?>px !important;
	}
    .et_right_sidebar #main-content div.container:before { 
        right:<?php echo htmlentities(@$option['sidebarwidth']+30); ?>px !important; 
    }
    .et_left_sidebar #main-content div.container:before { 
        left:<?php echo htmlentities(@$option['sidebarwidth']); ?>px !important; 
    }
    .et_right_sidebar #sidebar,
	.et_left_sidebar #sidebar { 
        width:<?php echo htmlentities(@$option['sidebarwidth']); ?>px !important; 
    }
}
