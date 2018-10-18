<?php
/*
Plugin Name: ES Woocommerce ActiveCampaign
Plugin URI: https://www.equalserving.com/wordpress-plugins-equalserving/woocommerce-with-activecampaign/
Description: Integrates Woocommerce with ActiveCampaign by adding customers to ActiveCampaign at time of purchase.
Version: 1.9
Author: EqualServing.com
Author URI: http://www.equalserving.com/
*/

add_action( 'plugins_loaded', 'es_woocommerce_activecampaign_init', 0 );

function es_woocommerce_activecampaign_init() {

	if (!class_exists('WC_Integration')) {
		return;
	}

	include_once( 'class-es-wc-integration-activecampaign.php' );

	/**
 	* Add the Integration to WooCommerce
 	**/

	function add_es_activecampaign_integration($methods) {
    	$methods[] = 'ES_WC_Integration_ActiveCampaign';
		return $methods;
	}

	add_filter('woocommerce_integrations', 'add_es_activecampaign_integration' );

	/**
 	* Add the Setting link to the Plugins listing page
 	**/

	function es_activecampaign_action_links( $links ) {

		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=integration&section=activecampaign' ) . '">' . __( 'Settings', 'es_wc_activecampaign' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );

	}

	/**
	* Add the "Settings" links on the Plugins administration screen
 	**/

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'es_activecampaign_action_links' );

}