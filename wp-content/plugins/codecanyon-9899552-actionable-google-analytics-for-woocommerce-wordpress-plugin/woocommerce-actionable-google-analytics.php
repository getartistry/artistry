<?php
/*
  Plugin Name: Actionable Google Analytics
  Plugin URI: http://www.tatvic.com/actionable-google-analytics-woocommerce/
  Description: Actionable Google Analytics is a Plugin for Woocommerce stores which allows you to use some of the most important features of Universal Analytics including Enhanced Ecommerce & User ID Tracking. Additionally, the plugin supports I.P Anonymization, Product Refund, Content Grouping, Form Field Tracking & 15+ Custom Dimensions & Metrics.
  Author: Tatvic
  Author URI: http://www.tatvic.com
  Version: CC-V3-2.1

  === Copyright 2017 Tatvic ===

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

// Add the integration to WooCommerce
function wc_actionable_google_analytics_add_integration($integrations) {
    global $woocommerce;

    if (is_object($woocommerce)) {
        include_once( 'includes/class-actionable-google-analytics-integration.php' );        
        $integrations[] = 'WC_Actionable_Google_Analytics';
      }
    return $integrations;
}   

//function to call controller
function send_email_to_tatvic($email, $status,$t_tkn) {
    $url = "http://dev.tatvic.com/leadgen/woocommerce-plugin/store_email/actionable_ga/";
    //set POST variables
    $fields = array(
        "email" => urlencode($email),
        "domain_name" => urlencode(get_site_url()),
        "status" => urlencode($status),
        "tvc_tkn" =>$t_tkn
    );
    wp_remote_post($url, array(
        "method" => "POST",
        "timeout" => 1,
        "httpversion" => "1.0",
        "blocking" => false,
        "headers" => array(),
        "body" => $fields
            )
    );
}

add_filter('woocommerce_integrations', 'wc_actionable_google_analytics_add_integration', 10);


//plugin action links on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'tvc_plugin_action_links');

function tvc_plugin_action_links($links) {
    global $woocommerce;
        if (version_compare($woocommerce->version, "2.1", ">=")) {
        $setting_url = 'admin.php?page=wc-settings&tab=integration';
        $doc_link=plugins_url() . '/actionable-google-analytics/documentation/index.html';
        } else {
        $setting_url = 'admin.php?page=woocommerce_settings&tab=integration';
        $doc_link=plugins_url() . '/actionable-google-analytics/documentation/index.html';
        }
    $links[] = '<a href="' . get_admin_url(null, $setting_url) . '">Settings</a>';
     $links[] = '<a href="http://plugins.tatvic.com/actionable-google-analytics-woo-faq/">FAQ</a>';
    $links[] = '<a href="' . $doc_link . '" target="_blank">Documentation</a>';
    return $links;
}

//function to catch Plugin activation
function ee_plugin_activate() {
    
    $tvc_free_ee = 'enhanced-e-commerce-for-woocommerce-store/woocommerce-enhanced-ecommerce-google-analytics-integration.php';
    
    $PID = "actionable_google_analytics";
    $chk_Settings = get_option('woocommerce_' . $PID . '_settings');

    if ($chk_Settings)
    {
         if( is_plugin_active($tvc_free_ee) ) {
              deactivate_plugins($tvc_free_ee);
         }
         if (array_key_exists("ga_email", $chk_Settings)) {
            send_email_to_tatvic($chk_Settings['ga_email'], 'active',$chk_Settings['ga_RTkn']);
         }
         
     }
}

//function to catch Plugin deactivation
function ee_plugin_dectivate() {
    if (!current_user_can('activate_plugins'))
        return;
    $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
    $chk_nonce = check_admin_referer("deactivate-plugin_{$plugin}");

    $PID = "actionable_google_analytics";
    $chk_Settings = get_option('woocommerce_' . $PID . '_settings');

    if ($chk_nonce && $chk_Settings) {
        if (array_key_exists("ga_email", $chk_Settings)) {
            send_email_to_tatvic($chk_Settings['ga_email'], 'inactive',$chk_Settings['ga_RTkn']);
        }
    }         
}

//function to catch Plugin deletion
function ee_plugin_delete() {

    if (!current_user_can('activate_plugins'))
        return;

    $chk_nonce = check_admin_referer('bulk-plugins');

    if ($_GET['action'] == 'delete-selected') {
        $PID = "actionable_google_analytics";
        $chk_Settings = get_option('woocommerce_' . $PID . '_settings');
        if ($chk_nonce && $chk_Settings) {
            if (array_key_exists("ga_email", $chk_Settings)) {
                send_email_to_tatvic($chk_Settings['ga_email'], 'delete');
            }
        }
    }
}

register_activation_hook(__FILE__, 'ee_plugin_activate');
register_deactivation_hook(__FILE__, 'ee_plugin_dectivate');
register_uninstall_hook(__FILE__, 'ee_plugin_delete');
?>
