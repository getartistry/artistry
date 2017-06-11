<?php

DUP_PRO_U::hasCapability('manage_options');

global $wpdb;

//COMMON HEADER DISPLAY
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/views/inc.header.php');
$current_tab = isset($_REQUEST['tab']) ? esc_html($_REQUEST['tab']) : 'general';
?>

<style>
	.narrow-input { width: 80px; }
	.wide-input {width: 400px; } 
	table.form-table tr td { padding-top: 25px; }
	td.dpro-license-type div {padding:5px 0 0 30px}
	td.dpro-license-type i.fa-check-square-o {display: inline-block; padding-right: 5px}
	td.dpro-license-type i.fa-square-o {display: inline-block; padding-right: 7px}
	td.dpro-license-type i.fa-question-circle {font-size:12px}
	div.sub-opts {padding:15px 0px 5px 30px }
</style>

<div class="wrap">
    <?php duplicator_pro_header(DUP_PRO_U::__("Settings")) ?>

    <h2 class="nav-tab-wrapper">  
        <a href="?page=duplicator-pro-settings&tab=general" class="nav-tab <?php echo ($current_tab == 'general') ? 'nav-tab-active' : '' ?>"> <?php DUP_PRO_U::_e('General'); ?></a> 
		<a href="?page=duplicator-pro-settings&tab=package" class="nav-tab <?php echo ($current_tab == 'package') ? 'nav-tab-active' : '' ?>"> <?php DUP_PRO_U::_e('Packages'); ?></a> 		
		<a href="?page=duplicator-pro-settings&tab=schedule" class="nav-tab <?php echo ($current_tab == 'schedule') ? 'nav-tab-active' : '' ?>"> <?php DUP_PRO_U::_e('Schedules'); ?></a> 	
        <a href="?page=duplicator-pro-settings&tab=storage" class="nav-tab <?php echo ($current_tab == 'storage') ? 'nav-tab-active' : '' ?>"> <?php DUP_PRO_U::_e('Storage'); ?></a> 
        <a href="?page=duplicator-pro-settings&tab=licensing" class="nav-tab <?php echo ($current_tab == 'licensing') ? 'nav-tab-active' : '' ?>"> <?php DUP_PRO_U::_e('Licensing'); ?></a> 
    </h2> 	

    <?php
	switch ($current_tab) {
        case 'general': include(dirname(__FILE__) . '/general.php');            
            break;
		case 'package': include(dirname(__FILE__) . '/package.php');
            break; 
		case 'schedule': include(dirname(__FILE__) . '/schedule.php');
            break; 		
        case 'storage': include(dirname(__FILE__) . '/storage.php');
            break;              
        case 'licensing': include(dirname(__FILE__) . '/licensing.php');
            break;   
	}
    ?>
</div>
