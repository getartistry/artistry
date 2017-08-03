<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

@media only screen and ( min-width: 1200px ) {

    /* Remove the current box shadow */
    #page-container, #main-header { 
        -moz-box-shadow: none !important; 
        -webkit-box-shadow: none !important; 
        box-shadow: none !important; 
    }
	
    /* Add box shadow to just the main area instead */
    #et-main-area { 
        position:relative; 
        -moz-box-shadow: 0 0 10px 0 rgba(0,0,0,0.2); 
        -webkit-box-shadow: 0 0 10px 0 rgba(0,0,0,0.2); 
        box-shadow: 0 0 10px 0 rgba(0,0,0,0.2); 
    }

    /* Push the header behind the main area */
    #main-header { 
        z-index:0; 
    }
	
    /* Set a height for the new header area */
    body {
        padding: 0px;
        height:<?php echo htmlentities(@$option['headerheight']); ?>px; 
    }

    /* Set the same background color on the new header area and original header */
    body, #main-header { background-color: <?php echo htmlentities(@$option['headercol']); ?> !important; }
	
    /* Set the background color for the rest of the page */
    html { background-color: <?php echo htmlentities(@$option['bgcol']); ?> !important; }	

}