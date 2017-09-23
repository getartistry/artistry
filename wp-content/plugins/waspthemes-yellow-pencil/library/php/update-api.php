<?php
/**
 * Auto Update API
 *
 * @author      WaspThemes
 * @category    Core
 * @version     1.2
*/

if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

// General. check timeout.
define("YP_TIMEOUT", 86400); // 1 DAY = 86400


// Formating version
function yp_version($v){
    $v = preg_replace('/[^0-9]/s', '', $v);
    if(strlen($v) == 2){
        return $v."0";
    }elseif(strlen($v) == 1){
        return $v."00";
    }else{
        return $v;
    }
}

// Defining plugin version
define("YP_FORMATTED_VERSION", yp_version(YP_VERSION));


// Getting purchase code
function yp_setting_purchase_code(){

    if(defined("YP_THEME_MODE")){
        define("YP_PURCHASE_CODE","YELLOW_PENCIL_THEME_LICENSE"); // Extended theme mode
    }else{
        define("YP_PURCHASE_CODE",get_option('yp_purchase_code')); // personal user information
    }

}
add_action("admin_init","yp_setting_purchase_code");


// Basic update
function yp_install_plugin($plugin){

    // Getting file system
    WP_Filesystem();

    // plugin array; name, download uri, install path
    $plugin = $plugin[0];

    // Plugins path
    $path = ABSPATH.'wp-content/plugins/';

    // Zip file path
    $zip = $path.$plugin['name'].'.zip';

    // The plugin folder
    $install = $plugin['install'];

    // trying to download zip file
    $response = wp_remote_get( 
        $plugin['uri'], 
        array( 
            'timeout'  => 300, 
            'stream'   => true, 
            'filename' => $zip 
        ) 
    );

    // Unzip zip file
    unzip_file($zip,$path);

    // delete zip file
    wp_delete_file($zip);

    // Force active the plugin
    yp_plugin_activate($install);

    return true;

}

// Response code
function yp_get_http_response_code($url){

    if( ini_get('allow_url_fopen') ) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }else{
        return null;
    }

}

// Getting version
function yp_getting_ver_from_changelog(){

    $version = 0;
    $pluginVersion = YP_FORMATTED_VERSION;

    // Changelog URL
    $url = "http://www.waspthemes.com/yellow-pencil/inc/changelog.txt";

    // If page found.
    if(yp_get_http_response_code($url) == 200){

        // Getting Changelog
        $changelog = wp_remote_get($url, array( 'sslverify' => false ));
        $changelog = wp_remote_retrieve_body( $changelog );

        // If have data.
        if(!empty($changelog)){

            // Get First line.
            $last_update = substr($changelog, 0, 32);

            // Part of first line.
            $array = explode('(',$last_update);

            // Only version.
            $version = yp_version($array['0']);

            if($version > $pluginVersion){
                            
                // Add to datebase, because have a new update.
                if(get_option('yp_update_status') !== false ){
                    update_option( 'yp_update_status', 'true');
                    update_option( 'yp_last_check_version', $pluginVersion);
                    update_option( 'yp_available_version', $version);
                }else{
                    add_option( 'yp_update_status', 'true');
                    add_option( 'yp_last_check_version', $pluginVersion);
                    add_option( 'yp_available_version', $version);
                }
                
                    return true;
                            
            }else{
                            
                // Update database, because not have a new update.
                if(get_option('yp_update_status') !== false ){
                    update_option( 'yp_update_status', 'false');
                }else{
                    add_option( 'yp_update_status', 'false');
                }
                
                return false;
                
            }
                
        } // If has data.
                
    } // IF URL working.

}

// Check everyday for update
function yp_update_checker(){

    // Update available just for pro users.
    if(defined('WTFV') == true){
    
        $timeStamp = current_time('timestamp', 1 );
        if(get_option('yp_checked_data') !== false ){

            if(($timeStamp-get_option('yp_checked_data')) > YP_TIMEOUT){ // 1 day. 86400

                yp_getting_ver_from_changelog();
                
                update_option( 'yp_checked_data', $timeStamp);
                
            }
        
        }else{
            
            // First Check
            yp_getting_ver_from_changelog();
            
            add_option( 'yp_checked_data', $timeStamp);
            
        }

    }
    
}

add_action('admin_init','yp_update_checker',9999);


// Getting plugin download uri from Envato
function yp_get_download_uri_by_purchase(){

    // Checks download uri
    $download_uri = 'http://waspthemes.com/yellow-pencil/auto-update/download.php?purchase_code='.YP_PURCHASE_CODE;

    // Getting plugin download url
    $data = wp_remote_get($download_uri, array( 'sslverify' => false ));
    $data = wp_remote_retrieve_body( $data );

    if($data == ''){
        die('Unknown error');
    }
    
    // Data is the download URL
    return $data;

}


// Active new version.
function yp_plugin_activate($installer){

    $current = get_option('active_plugins');
    $plugin = plugin_basename(trim($installer));

    if(!in_array($plugin, $current)){
        $current[] = $plugin;
        sort($current);
        do_action('activate_plugin', trim($plugin));
        update_option('active_plugins', $current);
        do_action('activate_'.trim($plugin));
        do_action('activated_plugin', trim($plugin));
        return true;
    }else{
        return false;
    }

}

// show update message.
function yp_update_message(){

    // Update available just for pro users.
    if(defined('WTFV') == true){

        // get screen
        $screen = get_current_screen();
        $base = $screen->base;

        $lastCheckVer = get_option('yp_last_check_version');
        $isUpdate = get_option('yp_update_status');
        $available = get_option('yp_available_version');

        if($isUpdate != 'true' && current_user_can("update_plugins") == true && YP_PURCHASE_CODE == '' && strstr($base,"yellow-pencil") == false){
            ?>
            <div class="update-nag yp-update-info-bar">
                <?php _e("Hola! Would you like to receive automatic updates? Please <a style='box-shadow:none !important;-webkit-box-shadow:none !important;-moz-box-shadow:none !important;' href='".admin_url('admin.php?page=yellow-pencil-license')."'>activate your copy</a> of Yellow Pencil.","yp"); ?>
            </div>
        <?php
        }
        
        if($isUpdate == 'true' && $lastCheckVer == YP_FORMATTED_VERSION && $available > YP_FORMATTED_VERSION && current_user_can("update_plugins") == true && YP_PURCHASE_CODE != ''){

            $versionDots = str_split($available);
            $versionView = join('.', $versionDots);
            
            ?>
            <div class="update-nag yp-update-info-bar">
                <a target="_blank" href="http://waspthemes.com/yellow-pencil/release-notes">Yellow Pencil <?php echo $versionView; ?></a> <?php _e("is available!","yp"); ?> <a style="box-shadow:none !important;-webkit-box-shadow:none !important;-moz-box-shadow:none !important;" href="#" class="yp_update_link"><?php _e("Please update now","yp"); ?>.</a>
            </div>
            <?php
                
        }elseif($isUpdate == 'true' && $lastCheckVer == YP_FORMATTED_VERSION && $available > YP_FORMATTED_VERSION && current_user_can("update_plugins") == true && strstr($base,"yellow-pencil") == false){

            ?>
            <div class="update-nag yp-update-info-bar">
                <?php _e("New updates are available for Yellow Pencil! Please activate your copy to receive automatic updates.","yp"); ?> <a style="box-shadow:none !important;-webkit-box-shadow:none !important;-moz-box-shadow:none !important;" href="<?php echo admin_url('admin.php?page=yellow-pencil-license'); ?>"><?php _e("Activate now!","yp"); ?></a>
            </div>
            <?php

        }

    }

}

// Begin to update for Pro version.
function yp_update_now(){

    $lastCheckVer = get_option('yp_last_check_version');
    $isUpdate = get_option('yp_update_status');
    $available = get_option('yp_available_version');
    
    if($isUpdate == 'true' && $lastCheckVer == YP_FORMATTED_VERSION && $available > YP_FORMATTED_VERSION && current_user_can("update_plugins") == true && YP_PURCHASE_CODE != ''){
        
        // Getting the download uri.
        $uri = yp_get_download_uri_by_purchase();

        // Update.
        $re = yp_install_plugin(array(
            array('name' => 'yellow_pencil_update_pack', 'uri' => $uri, 'install' => 'waspthemes-yellow-pencil/yellow-pencil.php'),
        ));

        if(!$re){
            wp_die('Server doesn\'t support automatic updates. Please update manually.');
        }
        
    }

    wp_die("The Plugin Updated.");

}


// Update javascript
function yp_update_javascript() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {

        jQuery(".yp_update_link").click(function(){

            // Only one click.
            if(!jQuery(this).hasClass("yp_update_link_disable")){

                // Updating.
                jQuery(this).text("Updating..").css("color","inherit").addClass("yp_update_link_disable");

                jQuery(this).append("<img src='<?php echo esc_url(plugins_url( '/images/wpspin_light.gif' , dirname(dirname(__FILE__)) )); ?>' style='position: relative;left: 7px;top: 2px;width: 12px;height: 12px;' />");

                var data = {
                    'action': 'yp_update_now'
                };

                jQuery.post(ajaxurl,data, function(response) {
                    jQuery(".yp-update-info-bar").html(response);
                });

            }

        });


        // Disable activation btn
        jQuery(".yp-product-activation").on("click",function(){
            jQuery(this).addClass("disabled");
        });


    });
    </script><?php
}

// Admin update script
add_action( 'admin_footer', 'yp_update_javascript' );

// Alert update
add_action( 'admin_notices', 'yp_update_message' );

// Ajax action.
add_action( 'wp_ajax_yp_update_now', 'yp_update_now' );