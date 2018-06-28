<?php
/*
Plugin Name: Mautic for WooCommerce
Plugin URI: https://carlconrad.net/wordpress/plugins/
Description: This plug-in is designed to help you link your WooCommerce eshop web site to your Mautic based marketing automation platform for your ordering pages.
Version: 0.4.3.2
Author: Koffeeware
Author URI: http://www.koffeeware.com
Developer: Carl Conrad
Developer URI: https://www.carlconrad.net/
Text Domain: woo-mautic
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

// use Automattic\WooCommerce\Client;
// use Mautic\Auth\OAuthClient;
// use Mautic\Auth\ApiAuth;

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	if ( is_admin() ){
		if (! ( get_option( 'mautic_woocommerce_settings_server' ) ) ) {
			add_action( 'admin_notices', 'mautic_woocommerce_admin_notice_no_configuration' );
		}
		add_filter( 'woocommerce_settings_tabs_array', 'mautic_woocommerce_add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_mautic', 'mautic_woocommerce_settings_tab' );
		add_action( 'woocommerce_update_options_mautic', 'mautic_woocommerce_settings_tab_update' );

/*
		$woocommerce = mautic_woocommerce_authorize_woocommerce();
		$settings = $woocommerce->get('settings');
		$customers = $woocommerce->get('customers');
		$orders = $woocommerce->get('orders');
*/
	}
	else {
		add_filter('woocommerce_thankyou_order_received_text', 'mautic_woocommerce_send_pixel', 10, 2 );
/*
		if ( get_option( 'mautic_woocommerce_settings_woocommerce_consumer_key' )) {

			if ( get_option( 'woo_mautic_mautic_accessToken' )) {
				error_log("woo-mautic plug-in access to Mautic is enabled.");
				add_action('woocommerce_checkout_order_processed', 'mautic_woocommerce_send_pixel', 10, 3);
//				add_filter('woocommerce_thankyou_order_received_text', 'mautic_woocommerce_send_pixel', 10, 2 );
//				add_action('woocommerce_checkout_order_processed', 'mautic_woocommerce_update_via_api($order_id)', 10, 3);
			}
			else {
				error_log("woo-mautic plug-in access to Mautic is not enabled.");
			}
		}
		else {
			error_log("woo-mautic plug-in access to WooCommerce is not enabled.");
		}
*/
	}
}

/* 
Plug-in management
*/

function mautic_woocommerce_add_settings_tab($settings_tabs) {
//	authorize_mautic();
	$settings_tabs['mautic'] = __( 'Mautic', 'woo-mautic' );
	return $settings_tabs;
}

function mautic_woocommerce_settings_tab() {
    woocommerce_admin_fields( mautic_woocommerce_tab_settings() );
}

function mautic_woocommerce_settings_tab_update() {
    woocommerce_update_options( mautic_woocommerce_tab_settings() );
}

function mautic_woocommerce_tab_settings() {
	$settings = array(
/*
		'woocommerce_section_title' => array(
			'name' => __('Connection to your WooCommerce API', 'woo-mautic'),
			'type' => 'title',
			'desc' => __('The following options are used to connect to your WooCommerce API. Create your API keys using the API tab.', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_woocommerce_section_title'
		),
		'woocommerce_consumer_key' => array(
			'name' => __('WooCommerce API consumer key', 'woo-mautic'),
			'type' => 'text',
			'css' => 'min-width:200px;',
			'desc' => __('WooCommerce API consumer key', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_woocommerce_consumer_key'
		),
		'woocommerce_consumer_secret' => array(
			'name' => __('WooCommerce API consumer secret', 'woo-mautic'),
			'type' => 'text',
			'css' => 'min-width:200px;',
			'desc' => __('WooCommerce API consumer secret', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_woocommerce_consumer_secret'
		),
		'woocommerce_section_end' => array(
			'type' => 'sectionend',
			'id' => 'mautic_woocommerce_settings_woocommerce_section_end'
		),
*/
		'mautic_section_title' => array(
			'name' => __('Connection to your Mautic server', 'woo-mautic'),
			'type' => 'title',
			'desc' => __('The following options are used to connect to your Mautic server.', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_mautic_section_title'
		),
		'server' => array(
			'name' => __('Mautic server', 'woo-mautic'),
			'type' => 'text',
			'css' => 'min-width:200px;',
			'desc_tip' => __('Your Mautic server (including http/https, no trailing /)', 'woo-mautic'),
			'placeholder' =>  __('Your Mautic server (including http/https, no trailing /)', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_server'
		),
/*
		'mautic_public_key' => array(
			'name' => __('Mautic public key', 'woo-mautic'),
			'type' => 'text',
			'css' => 'min-width:200px;',
			'desc_tip' => __('Your Mautic OAuth2 public key', 'woo-mautic'),
			'placeholder' => __('Your Mautic OAuth2 public key', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_mautic_public_key'
		),
		'mautic_secret_key' => array(
			'name' => __('Mautic secret key', 'woo-mautic'),
			'type' => 'text',
			'css' => 'min-width:200px;',
			'desc_tip' => __('Your Mautic OAuth2 secret key', 'woo-mautic'),
			'placeholder' => __('Your Mautic OAuth2 secret key', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_mautic_secret_key'
		),
*/
		'mautic_section_end' => array(
			'type' => 'sectionend',
			'id' => 'mautic_woocommerce_settings_mautic_section_end'
		),
		'second_section_title' => array(
			'name' => __('Exported fields', 'woo-mautic'),
			'type' => 'title',
			'desc_tip' => __('If selected, the following fields will be exported to your Mautic marketing automation. Make sure these fields are marked as Publicly updatable in Mautic.', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_second_section_title'
		),
		'field_source' => array(
			'name' => __('Field source', 'woo-mautic'),
			'type' => 'select',
			'css' => 'min-width:200px;',
			'options' => array('_billing' => __('Billing', 'woo-mautic'),'_shipping' => __('Shipping', 'woo-mautic')),
			'id' => 'mautic_woocommerce_settings_field_source',
			'class' => 'wc-enhanced-select'
		),
		'fields' => array(
			'name' => __('Fields', 'woo-mautic'),
			'type' => 'select',
			'css' => 'min-width:200px;',
			'options' => array(__('First name, last name, email', 'woo-mautic'), __('First name, last name, email, phone', 'woo-mautic'), __('First name, last name, email, phone, address', 'woo-mautic')),
			'id' => 'mautic_woocommerce_settings_fields',
			'class' => 'wc-enhanced-select'
		),
/*
		'order_status' => array(
			'name' => __('Order status', 'woo-mautic'),
			'type' => 'checkbox',
			'desc_tip' => __('Order status. Values: pending, processing, on-hold, completed, cancelled, refunded and failed.', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_order_status'
		),
		'orders_history' => array(
			'name' => __('Orders history', 'woo-mautic'),
			'type' => 'checkbox',
			'desc_tip' => __('Orders history. Fields include: number of orders, mean order turnover, date of last order, mean number of products.', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_orders_history'
		),
*/
		'tag_ordered_products' => array(
			'name' => __('Tag ordered product(s)', 'woo-mautic'),
			'type' => 'checkbox',
			'desc_tip' => __('Ordered product(s) will appear as tags in the customer’s account.', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_tag_ordered_products'
		),
		'tag_ordered_products_prefix' => array(
			'name' => __('Ordered product(s) tag prefix', 'woo-mautic'),
			'type' => 'text',
			'desc_tip' => __('Prefix to the ordered product(s) tag as it will appear in Mautic.', 'woo-mautic'),
			'id' => 'mautic_woocommerce_settings_tag_ordered_products_prefix'
		),
		'second_section_end' => array(
			'type' => 'sectionend',
			'id' => 'mautic_woocommerce_settings_section_end'
		)
	);
	return apply_filters( 'mautic_woocommerce_settings', $settings );
}

function authorize_mautic() {
// https://www.mautic.org/blog/developer/how-to-use-the-mautic-rest-api/
	
	$accessTokenData = array(
		'accessToken' => get_option( 'mautic_woocommerce_mautic_accessToken' ),
		'accessTokenSecret' => get_option( 'mautic_woocommerce_mautic_accessTokenSecret' ),
		'accessTokenExpires' => get_option( 'mautic_woocommerce_mautic_accessTokenExpires' )
	);

	$settings = array(
		'baseUrl'           => get_option( 'mautic_woocommerce_settings_server' ),
		'clientKey'         => get_option( 'mautic_woocommerce_settings_public_key' ),
		'clientSecret'      => get_option( 'mautic_woocommerce_settings_secret_key' ),
		'callback'          => plugins_url( 'woo-mautic/oauth2callback' ),
		'version'           => 'OAuth2'
	);

	if (!empty($accessTokenData['accessToken']) && !empty($accessTokenData['accessTokenSecret'])) {
		$settings['accessToken']        = $accessTokenData['accessToken'] ;
		$settings['accessTokenSecret']  = $accessTokenData['accessTokenSecret'];
		$settings['accessTokenExpires'] = $accessTokenData['accessTokenExpires'];
	}

	$auth = \Mautic\Auth\ApiAuth::initiate($settings);

	if ($auth->validateAccessToken()) {
		if ($auth->accessTokenUpdated()) {
			$accessTokenData = $auth->getAccessTokenData();
				add_option('mautic_woocommerce_mautic_accessToken', $accessTokenData['accessToken']);
				add_option('mautic_woocommerce_mautic_accessTokenSecret', $accessTokenData['accessTokenSecret']);
				add_option('mautic_woocommerce_mautic_accessTokenExpires', $accessTokenData['accessTokenExpires']);
				error_log("Successful authorization.");
		} else {
			error_log("App is already authorized.");
		}
	} else {
		error_log("Mautic token is not valid.");
	}
}

/* 
Interaction with WooCommerce
*/


function mautic_woocommerce_authorize_woocommerce() {
	$site_url = home_url();
	$consumer_key = get_option( 'mautic_woocommerce_settings_woocommerce_consumer_key' );
	$consumer_secret = get_option( 'mautic_woocommerce_settings_woocommerce_consumer_secret' );
//	error_log("woo-mautic plug-in access to WooCommerce is enabled.( $site_url / $consumer_key / $consumer_secret)");
	$woocommerce = new Client(
		$site_url,
		$consumer_key, 
		$consumer_secret,
		[
			'wp_api' => true,
			'version' => 'wc/v2',
			'timeout' => 5,
			'query_string_auth' => true
		]
	);
	return $woocommerce;
}

function mautic_woocommerce_send_pixel($text, $order_id) {

	$order = new WC_Order( $order_id );
	$order_data = $order->get_data();

	$order_meta = get_post_meta($order_id);
	$fields_source = get_option( 'mautic_woocommerce_settings_field_source' );
	$settings_fields = get_option( 'mautic_woocommerce_settings_fields' );
	if ($fields_source == 0)
		switch ($settings_fields) {
			case '2':
				$data['address1'] = esc_attr($order->get_billing_address_1());
				$data['address2'] = esc_attr($order->get_billing_address_2());
				$data['zipcode'] = esc_attr($order->get_billing_postcode());
				$data['city'] = esc_attr($order->get_billing_city());
				$data['state'] = esc_attr($order->get_billing_state());
				$data['country'] = esc_attr($order->get_billing_country());
			case '1':
				$data['phone'] = esc_attr($order->get_billing_phone());
			case '0':
				$data['firstname'] = esc_attr($order->get_billing_first_name());
				$data['lastname'] = esc_attr($order->get_billing_last_name());
			break;
		}
	else
		switch ($settings_fields) {
			case '2':
				$data['address1'] = esc_attr($order->get_shipping_address_1());
				$data['address2'] = esc_attr($order->get_shipping_address_2());
				$data['zipcode'] = esc_attr($order->get_shipping_postcode());
				$data['city'] = esc_attr($order->get_shipping_city());
				$data['state'] = esc_attr($order->get_shipping_state());
				$data['country'] = esc_attr($order->get_shipping_country());
			case '1':
				$data['phone'] = esc_attr($order->get_shipping_phone());
			case '0':
				$data['firstname'] = esc_attr($order->get_shipping_first_name());
				$data['lastname'] = esc_attr($order->get_shipping_last_name());
			break;
		}
	$data['page_title'] = __('Order ID:', 'woo-mautic').' '.$order->get_order_number();
	$data['email'] = esc_attr($order->get_billing_email());
	if ( get_option( 'mautic_woocommerce_settings_tag_ordered_products' ) ) {
		$items = $order->get_items();
		$prefix = get_option( 'mautic_woocommerce_settings_tag_ordered_products_prefix' );
		foreach ($items as $item){
			$product_id = $item->get_product_id();
			$tags .= $prefix . $product_id . ',';
		}
		$tags = rtrim($tags, ',');
		$data['tags'] = esc_attr($tags);
	}
	$encoded_serialized_data = urlencode(base64_encode(serialize($data)));

	$server = get_option( 'mautic_woocommerce_settings_server' );
	$mautic_pixel = '<img src="' . $server . '/mtracking.gif?d=' . $encoded_serialized_data . '" alt="Mautic Tags" style="display:none;" />';
	return ($mautic_pixel);
}

function mautic_woocommerce_update_via_api($order_id) {
// https://www.mautic.org/community/index.php/3128-update-leads-tag-with-rest-api/0
	$mautic_woocommerce_mauticBaseUrl = get_option( 'mautic_woocommerce_settings_server' );
	$mautic_woocommerce_accessTokenData = array(
		'accessToken' => get_option( 'woo_mautic_mautic_accessToken' ),
		'accessTokenSecret' => get_option( 'woo_mautic_mautic_accessTokenSecret' ),
		'accessTokenExpires' => get_option( 'woo_mautic_mautic_accessTokenExpires' )
	);

	$order_meta = get_post_meta($order_id);
	$fields_source = get_option( 'mautic_woocommerce_settings_field_source' );
	$mautic_public_key = get_option( 'mautic_woocommerce_settings_public_key' );
	$mautic_secret_key = get_option( 'mautic_woocommerce_settings_secret_key' );
	if ($fields_source == 0)
		$fields_source = '_billing';
	else
		$fields_source = '_shipping';
	$fields = get_option( 'mautic_woocommerce_settings_fields' );


	$leadApi = \Mautic\MauticApi::getContext(
		"leads",
		$auth,
		$mautic_woocommerce_mauticBaseUrl . '/api/'
	);

	$lead = $leadApi->create(array(
		'ipAddress' => $_SERVER['REMOTE_ADDR'],
		'firstname' => $order_meta[$fields_source .'_first_name'][0],
		'lastname'  => $order_meta[$fields_source .'_last_name'][0],
		'email'     => $order_meta[$fields_source .'_email'][0],
	));
}

function mautic_woocommerce_admin_notice_no_configuration() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'The woo-mautic plug-in is missing its configuration and is therefore not actively collecting data.', 'woo-mautic' ); ?></p>
    </div>
    <?php
}

add_action('plugins_loaded', 'mautic_woocommerce_load_textdomain');
function mautic_woocommerce_load_textdomain() {
	load_plugin_textdomain( 'woo-mautic', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}