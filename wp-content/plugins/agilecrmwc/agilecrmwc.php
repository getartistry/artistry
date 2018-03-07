<?php

/**
 * @package AgileCRM_WooCommerce
 * @version 1.0
 */
/*
 * Plugin Name: Agile CRM WooCommerce
 * Plugin URI: https://www.agilecrm.com/woocommerce-crm
 * Description: Agile CRM integration plugin for WooCommerce. Sync customers, orders, notes and run smart campaigns.
 * Author: Agile CRM Team
 * Author URI: https://www.agilecrm.com/
 * Version: 1.0
 * Requires at least: 4.0
 * Tested up to: 4.1
 */

function AgileWC_add_admin_menu()
{
    add_menu_page('Agile CRM WooCommerce Settings', 'Agile CRM WC', 'manage_options', 'agilewc', 'AgileWC_options_page', plugins_url('static/agile_menu.png', __FILE__), '100.143');
}

function AgileWC_options_page()
{
    include(sprintf("%s/templates/agile.php", dirname(__FILE__)));
}

function AgileWC_admin_init()
{
    register_setting('agile-settings-group', 'agile-domain-setting');
    register_setting('agile-settings-group', 'agile-key-setting');
    register_setting('agile-settings-group', 'agile-sync-settings');

    add_settings_section('agile-section-one', '', '', 'agile-plugin');
    add_settings_field('agile-domain-field', 'Enter Domain Name', 'AgileWC_domain_callback', 'agile-plugin', 'agile-section-one');
    add_settings_field('agile-key-field', 'Enter API Key', 'AgileWC_key_callback', 'agile-plugin', 'agile-section-one');
}

function AgileWC_domain_callback()
{
    $setting = esc_attr(get_option('agile-domain-setting'));
    echo "<div'><span style='padding:3px; margin:0px; border: 1px solid #dfdfdf; border-right: 0px; background-color:#eee;'>https://</span><input type='text' name='agile-domain-setting' style='width:100px; margin:0px; border-radius: 0px;' value='$setting' /><span style='margin:0px; padding: 3px; border: 1px solid #dfdfdf; background-color:#eee; border-left: 0px;'>.agilecrm.com</div></span><br><small>If you are using abc.agilecrm.com, enter abc</small>";
}

function AgileWC_key_callback()
{
    $setting = esc_attr(get_option('agile-key-setting'));
    echo "<input type='text' name='agile-key-setting' style='width:250px;' value='$setting' placeholder='Javascript API key '/><br><small>For instructions to find your API key, please click <a href='https://github.com/agilecrm/javascript-api#setting-api--analytics' target='_blank'>here</a></small>";
}

$AGILEWC_DOMAIN = get_option('agile-domain-setting');
$AGILEWC_KEY = get_option('agile-key-setting');
$AGILEWC_SYNC_OPTIONS = get_option('agile-sync-settings');

require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'Curl.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'agilecrm.class.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';

function AgileWC_registerSession()
{
    if (!session_id()) {
        session_start();
    }
}

add_action('init', 'AgileWC_registerSession',1);

if (is_admin()) {
    add_action('admin_menu', 'AgileWC_add_admin_menu');
    add_action('admin_init', 'AgileWC_admin_init');
}

if ($AGILEWC_DOMAIN && $AGILEWC_KEY) {
    if (is_array($AGILEWC_SYNC_OPTIONS)) {
        if (isset($AGILEWC_SYNC_OPTIONS['track_visitors']) || isset($AGILEWC_SYNC_OPTIONS['web_rules'])) {
            add_action('wp_footer', 'AgileWC_script');
        }

        if (isset($AGILEWC_SYNC_OPTIONS['sync_customers'])) {
            add_action('woocommerce_checkout_order_processed', 'AgileWC_created_customer');
        }

        if (isset($AGILEWC_SYNC_OPTIONS['sync_orders'])) {
            add_action('woocommerce_new_order', 'AgileWC_new_order');
            add_action('woocommerce_order_status_changed', 'AgileWC_order_status_changed');
            add_action('woocommerce_new_customer_note', 'AgileWC_new_customer_note');
        }
    }
}