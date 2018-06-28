<?php

$product_name = 'stripepaymentgateway'; // name should match with 'Software Title' configured in server, and it should not contains white space
$product_version = '3.0.6';
$product_slug = 'eh-stripe-payment-gateway/stripe-payment-gateway.php'; //product base_path/file_name
$serve_url = 'https://www.xadapter.com/';
$plugin_settings_url = admin_url('admin.php?page=wc-settings&tab=checkout&section=eh_stripe_pay');

//include api manager
include_once ( 'wf_api_manager.php' );
new WF_API_Manager($product_name, $product_version, $product_slug, $serve_url, $plugin_settings_url);
?>
