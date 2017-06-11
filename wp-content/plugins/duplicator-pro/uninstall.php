<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   DUP_PRO
 * @link      https://snapcreek.com
 * @Copyright 2016 Snapcreek.com
 */
// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN'))
{
    exit;
}
require_once 'define.php';
require_once 'classes/utilities/class.u.php';
require_once 'classes/utilities/class.u.low.php';
require_once 'classes/entities/class.global.entity.php';

global $wpdb;

/* @var $global DUP_PRO_Global_Entity */
$global = DUP_PRO_Global_Entity::get_instance();

delete_option('duplicator_pro_plugin_version');

//Remvoe entire wp-snapshots directory
//if (DUP_PRO_Settings::Get('uninstall_files')) {
if ($global->uninstall_files)
{	
	$table_name = $wpdb->prefix . "duplicator_pro_packages";
	$wpdb->query("DROP TABLE `{$table_name}`");

    $ssdir = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH);
    $ssdir_tmp = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP);

    //Sanity check for strange setup
    $check = glob("{$ssdir}/wp-config.php");
    if (count($check) == 0)
    {

        //PHP sanity check
        foreach (glob("{$ssdir}/*_database.sql") as $file)
        {
            if (strstr($file, '_database.sql'))
                @unlink("{$file}");
        }
        foreach (glob("{$ssdir}/*_{$global->installer_base_name}") as $file)
        {
            if (strstr($file, "_{$global->installer_base_name}"))
                @unlink("{$file}");
        }
        foreach (glob("{$ssdir}/*_archive.zip") as $file)
        {
            if (strstr($file, '_archive.zip'))
                @unlink("{$file}");
        }
        foreach (glob("{$ssdir}/*_scan.json") as $file)
        {
            if (strstr($file, '_scan.json'))
                @unlink("{$file}");
        }
        foreach (glob("{$ssdir}/*.log") as $file)
        {
            if (strstr($file, '.log'))
                @unlink("{$file}");
        }

        //Check for core files and only continue removing data if the snapshots directory
        //has not been edited by 3rd party sources, this helps to keep the system stable
        $files = glob("{$ssdir}/*");
        if (is_array($files) && count($files) < 6)
        {
            $defaults = array("{$ssdir}/index.php", "{$ssdir}/robots.txt", "{$ssdir}/dtoken.php");
            $compare = array_diff($defaults, $files);

            //There might be a .htaccess file or index.php/html etc.
            if (count($compare) < 3)
            {
                foreach ($defaults as $file)
                {
                    @unlink("{$file}");
                }
                @unlink("{$ssdir}/.htaccess");
                @rmdir($ssdir_tmp);
                @rmdir($ssdir);
            }
        }
    }
}
function DUP_PRO_deactivate_license()
{
    $license = get_option('duplicator_pro_license_key', '');

    if (empty($license) === false)
    {
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $license,
            'item_name' => urlencode('Duplicator Pro')
        );

        // Call the custom API.
        $response = wp_remote_get(add_query_arg($api_params, 'https://snapcreek.com'));

        $response_string = print_r($response, true);
            
        DUP_PRO_Low_U::errLog("deactivate license response $response_string");
            
        // make sure the response came back okay
        if (is_wp_error($response))
        { 
            //DUP_PRO_LOG::traceObject("Error deactivating $license", $response);
            DUP_PRO_Low_U::errLog("error deactivating license $license");
            //return;
        }
        else
        {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            $license_data_string = print_r($license_data, true);

            DUP_PRO_Low_U::errLog("After deactivating license key license_data=$license_data_string");
        }
                                           
        // No error handling / reporting in this version - want it as simple as possible
    }
    else
    {
        DUP_PRO_Low_U::errLog('license key is empty on uninstall!');
    }
}

//delete_transient('duplicator_pro_ls');
        
//Remove all Settings
//if (DUP_PRO_Settings::Get('uninstall_settings')) {
if ($global->uninstall_settings)
{
    DUP_PRO_deactivate_license();

    //DUP_PRO_Settings::Delete();
    $global->delete();
    delete_option('duplicator_pro_license_key');
    delete_option('duplicator_pro_ui_view_state');
    delete_option('duplicator_pro_package_active');
    delete_option('duplicator_pro_send_trace_to_error_log');

	$entity_table_name = $wpdb->prefix . DUP_PRO_JSON_Entity_Base::DEFAULT_TABLE_NAME;

    $wpdb->query("DROP TABLE `{$entity_table_name}`");	    
}
?>