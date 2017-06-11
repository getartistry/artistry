<?php

DUP_PRO_U::hasCapability('export');

global $wpdb;

//COMMON HEADER DISPLAY
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/views/inc.header.php');

$nonce = wp_create_nonce('duplicator_pro_download_package_file');
?>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        /*	METHOD: Triggers the download of an installer/package file
         *	@param name		Window name to open
         *	@param button	Button to change color */
        DupPro.Pack.DownloadFile = function(event, button) {
            if (event.data != undefined) {
                window.open(event.data.name, '_self');
            } else {
                $(button).addClass('dpro-btn-selected');
                window.open(event, '_self');
            }
            return false;
        }
        
        // which: 0=installer, 1=archive, 2=sql file, 3=log
        DupPro.Pack.DownloadPackageFile = function (which, packageID) {
    
            var actionLocation = ajaxurl + '?action=duplicator_pro_get_package_file&which=' + which + '&package_id=' + packageID + '&nonce=' + <?php echo $nonce; ?>;
    
            if(which == 3)
            {
                var win=window.open(actionLocation, '_blank');
                win.focus();    
            }
            else
            {
                location.href = actionLocation;            
            }        
        }
    });
</script>

<div class="wrap">
    <?php 
		duplicator_pro_header(DUP_PRO_U::__("Templates"));
		include('template.controller.php');
    ?>
</div>