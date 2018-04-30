<?php
/**
 * Plugin Name: WooCommerce Product Feed PRO 
 * Version:     2.6.7
 * Plugin URI:  https://www.adtribes.io/support/?utm_source=wpadmin&utm_medium=plugin&utm_campaign=woosea_product_feed_pro
 * Description: Configure and maintain your WooCommerce product feeds for Google Shopping, Facebook, Remarketing, Bing, Yandex, Comparison shopping websites and over a 100 channels more.
 * Author:      AdTribes.io
 * Author URI:  https://www.adtribes.io
 * Developer:   Joris Verwater, Eva van Gelooven
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wporg
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 3.3
 */

/** 
 * WooCommerce Product Feed PRO is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * WooCommerce Product Feed PRO is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WooCommerce Product Feed PRO. If not, see <http://www.gnu.org/licenses/>.
 */

/** 
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

if (!defined('ABSPATH')) {
   exit;
}

/**
 * Plugin versionnumber, please do not override
 */
define( 'WOOCOMMERCESEA_PLUGIN_VERSION', '2.6.7' );
define( 'WOOCOMMERCESEA_PLUGIN_NAME', 'woocommerce-product-feed-pro' );

if ( ! defined( 'WOOCOMMERCESEA_FILE' ) ) {
        define( 'WOOCOMMERCESEA_FILE', __FILE__ );
}

if ( ! defined( 'WOOCOMMERCESEA_PATH' ) ) {
        define( 'WOOCOMMERCESEA_PATH', plugin_dir_path( WOOCOMMERCESEA_FILE ) );
}

if ( ! defined( 'WOOCOMMERCESEA_BASENAME' ) ) {
        define( 'WOOCOMMERCESEA_BASENAME', plugin_basename( WOOCOMMERCESEA_FILE ) );
}

/**
 * Enqueue css assets
 */
function woosea_styles() {
        wp_register_style( 'woosea_admin-css', plugins_url( '/css/woosea_admin.css', __FILE__ ), '',WOOCOMMERCESEA_PLUGIN_VERSION );
        wp_enqueue_style( 'woosea_admin-css' );

        wp_register_style( 'woosea_jquery_ui-css', plugins_url( '/css/jquery-ui.css', __FILE__ ), '',WOOCOMMERCESEA_PLUGIN_VERSION );
        wp_enqueue_style( 'woosea_jquery_ui-css' );

        wp_register_style( 'woosea_jquery_typeahead-css', plugins_url( '/css/jquery.typeahead.css', __FILE__ ), '',WOOCOMMERCESEA_PLUGIN_VERSION );
        wp_enqueue_style( 'woosea_jquery_typeahead-css' );
}
add_action( 'admin_enqueue_scripts' , 'woosea_styles' );

/**
 * Enqueue js assets
 */
function woosea_scripts($hook) {

	// Enqueue Jquery
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-calender');

	// Bootstrap typeahead
	wp_register_script( 'typeahead-js', plugin_dir_url( __FILE__ ) . 'js/typeahead.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	wp_enqueue_script( 'typeahead-js' );

	// JS for adding input field validation
	wp_register_script( 'woosea_validation-js', plugin_dir_url( __FILE__ ) . 'js/woosea_validation.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	wp_enqueue_script( 'woosea_validation-js' );

	// JS for autocomplete
	wp_register_script( 'woosea_autocomplete-js', plugin_dir_url( __FILE__ ) . 'js/woosea_autocomplete.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	wp_enqueue_script( 'woosea_autocomplete-js' );

	// JS for adding table rows to the rules page
	wp_register_script( 'woosea_rules-js', plugin_dir_url( __FILE__ ) . 'js/woosea_rules.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	wp_enqueue_script( 'woosea_rules-js' );

	// JS for adding table rows to the field mappings page
	wp_register_script( 'woosea_field_mapping-js', plugin_dir_url( __FILE__ ) . 'js/woosea_field_mapping.js', '', WOOCOMMERCESEA_PLUGIN_VERSION, true );
	wp_enqueue_script( 'woosea_field_mapping-js' );

	// JS for getting channels
	wp_register_script( 'woosea_channel-js', plugin_dir_url( __FILE__ ) . 'js/woosea_channel.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	wp_enqueue_script( 'woosea_channel-js' );

	// JS for manage projects page
	wp_register_script( 'woosea_manage-js', plugin_dir_url( __FILE__ ) . 'js/woosea_manage.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	wp_enqueue_script( 'woosea_manage-js' );

	// JS for checking license key
	wp_register_script( 'woosea_license-js', plugin_dir_url( __FILE__ ) . 'js/woosea_license.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	wp_enqueue_script( 'woosea_license-js' );
}
add_action( 'admin_enqueue_scripts' , 'woosea_scripts' );

/**
 * Required classes
 */
require plugin_dir_path(__FILE__) . 'classes/class-admin-pages-template.php';
require plugin_dir_path(__FILE__) . 'classes/class-cron.php';
require plugin_dir_path(__FILE__) . 'classes/class-get-products.php';
require plugin_dir_path(__FILE__) . 'classes/class-admin-notifications.php';
require plugin_dir_path(__FILE__) . 'classes/class-update-channel.php';
require plugin_dir_path(__FILE__) . 'classes/class-attributes.php';

/**
 * Hook and function that will run during plugin deactivation.
 */
function deactivate_woosea_feed(){
	require plugin_dir_path(__FILE__) . 'classes/class-deactivate-cleanup.php';
    	WooSEA_Deactivate_Cleanup::deactivate_cleanup();
}
register_deactivation_hook(__FILE__, 'deactivate_woosea_feed');

/**
 * Hooks and functions that will run during plugin activation.
 */
function activate_woosea_feed(){
	require plugin_dir_path(__FILE__) . 'classes/class-activate.php';
    	WooSEA_Activation::activate_checks();
}
register_activation_hook(__FILE__, 'activate_woosea_feed');

/**
 * Request our plugin users to write a review
 **/
function woosea_request_review(){
	// Only request for a review when:
	// Plugin activation has been > 1 week
	// Active projects > 0	
	$cron_projects = get_option( 'cron_projects' );
	if(!empty( $cron_projects )){
		$nr_projects = count($cron_projects);
		$first_activation = get_option ( 'woosea_first_activation' );
 		$notification_interaction = get_option( 'woosea_review_interaction' );
		$current_time = time();
		$show_after = 604800; // Show only after one week
		$is_active = $current_time-$first_activation;

		if(($nr_projects > 0) AND ($is_active > $show_after) AND ($notification_interaction != "yes")){
		echo '<div class="notice notice-info review-notification is-dismissible"><font color="green" style="font-weight:bold";><p>Hey, I noticed you have been using my plugin, WooCommerce Product Feed PRO, for over a week now and have created product feed projects with it - that\'s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost my motivation.<br/>~ Joris Verwater<br><ul><li><span class="ui-icon ui-icon-caret-1-e" style="display: inline-block;"></span><a href="https://wordpress.org/support/plugin/woo-product-feed-pro/reviews?rate=5#new-post" target="_blank" class="dismiss-review-notification">Ok, you deserve it</a></li><li><span class="ui-icon ui-icon-caret-1-e" style="display: inline-block;"></span><a href="#" class="dismiss-review-notification">Nope, maybe later</a></li><li><span class="ui-icon ui-icon-caret-1-e" style="display: inline-block;"></span><a href="#" class="dismiss-review-notification">I already did</a></li></ul></p></font></div>';	
		}
	}
}
add_action('admin_notices', 'woosea_request_review');

/**
 * Request our plugin users to write a review
 **/
function woosea_license_notice(){
	$license_information = get_option( 'license_information' );

	if((isset($license_information['notice'])) and ($license_information['notice'] == "true")){
	?>
    		<div class="<?php print "$license_information[message_type]"; ?>">
        		<p><?php _e( $license_information['message'], 'sample-text-domain' ); ?></p>
    		</div>
    	<?php
	}
}
add_action('admin_notices', 'woosea_license_notice');


/**
 * Create a seperate MySql table for saving conversion information
 */
function woosea_create_db_table(){
	// Create MySql conversion table
	global $wpdb;
	$version = get_option( 'my_plugin_version', '1.5.2' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'adtribes_my_conversions';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		conversion_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		project_hash varchar(256) NOT NULL,
		utm_source varchar(256) NOT NULL,
		utm_campaign varchar(256) NOT NULL,
		utm_medium varchar(256) NOT NULL,
		utm_term varchar(256) NOT NULL,
		productId int(128) NOT NULL,
		orderId int(128) NOT NULL,
		UNIQUE KEY id (id),
		UNIQUE KEY orderId (orderId)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook(__FILE__, 'woosea_create_db_table');

/**
 * Add some JS and mark-up code on every front-end page in order to get the conversion tracking to work
 */
function woosea_hook_header() {
	$marker = sprintf('<!-- This website runs the WooCommerce Product Feed PRO AdTribes.io plugin -->');
	echo "\n${marker}\n";

	// Make ajaxurl available on all pages
	echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
	
        // JS for adding the tracking code into the template
        wp_register_script( 'woosea_tracking-js', plugin_dir_url( __FILE__ ) . 'js/woosea_tracking.js', WOOCOMMERCESEA_PLUGIN_VERSION, true  );
        wp_enqueue_script( 'woosea_tracking-js' );

}
add_action('wp_head','woosea_hook_header');

/**
 * We need to be able to make an AJAX call on the thank you page
 */
function woosea_inject_ajax( $order_id ){
	// Last order details
	$order = new WC_Order( $order_id );
	$order_id = $order->get_id();
	$customer_id = $order->get_user_id();
	$total = $order->get_total();

	update_option('last_order_id', $order_id);

	$jscode = sprintf('<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>');
	echo "\n${jscode}\n\n";
}
add_action( 'woocommerce_thankyou', 'woosea_inject_ajax' );

/**
 * Register own cron hook(s), it will execute the woosea_create_all_feeds that will generate all feeds on scheduled event
 */
add_action( 'woosea_cron_hook', 'woosea_create_all_feeds'); // create a cron hook


/**
 * Check if license for Elite version is valid
 */
add_action( 'woosea_check_license', 'woosea_license_valid'); // check if license is valid

/**
 * Add WooCommerce SEA plugin to Menu
 */
function woosea_menu_addition(){
            add_menu_page(__('WooCommerce Product Feed PRO', 'woosea-feed'), __('Product Feed Pro', 'woosea-feed'), 'manage_options', __FILE__, 'woosea_generate_pages', 'dashicons-chart-bar',99);
            add_submenu_page(__FILE__, __('Feed configuration', 'woosea-feed'), __('Create feed', 'woosea-feed'), 'manage_options', __FILE__, 'woosea_generate_pages');
            add_submenu_page(__FILE__, __('Manage feeds', 'woosea-feed'), __('Manage feeds', 'woosea-feed'), 'manage_options', 'woosea_manage_feed', 'woosea_manage_feed');
            add_submenu_page(__FILE__, __('Settings', 'woosea-feed'), __('Settings', 'woosea-feed'), 'manage_options', 'woosea_manage_settings', 'woosea_manage_settings');
            add_submenu_page(__FILE__, __('Upgrade to Elite', 'woosea-feed'), __('Upgrade to Elite', 'woosea-feed'), 'manage_options', 'woosea_upgrade_elite', 'woosea_upgrade_elite');
}

/**
 * Get the attributes for displaying the attributes dropdown on the rules page
 * Gets all attributes, product, image and attributes
 */
function woosea_ajax() {
	$rowCount = sanitize_text_field($_POST['rowCount']);

	$attributes_dropdown = get_option('attributes_dropdown');
	if (!is_array($attributes_dropdown)){
		$attributes_obj = new WooSEA_Attributes;
		$attributes_dropdown = $attributes_obj->get_product_attributes_dropdown();
        	update_option( 'attributes_dropdown', $attributes_dropdown, '', 'yes');
	}

	$data = array (
		'rowCount' => $rowCount,
		'dropdown' => $attributes_dropdown
	);

	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_ajax', 'woosea_ajax' );

/**
 * Function to register a succesfull license activation
 */
function woosea_register_license(){
	$license_valid = sanitize_text_field($_POST['license_valid']);
	$license_created = sanitize_text_field($_POST['license_created']);
	$message = sanitize_text_field($_POST['message']);
	$message_type = sanitize_text_field($_POST['message_type']);
	$license_email = sanitize_text_field($_POST['license_email']);
	$license_key = sanitize_text_field($_POST['license_key']);


	$license_information = array (
		'license_valid' 	=> $license_valid,
		'license_created' 	=> $license_created,
		'message' 		=> $message,
		'message_type'		=> $message_type,
		'license_email'		=> $license_email,
		'license_key'		=> $license_key
	);
	update_option("license_information", $license_information);

}
add_action( 'wp_ajax_woosea_register_license', 'woosea_register_license' );

/**
 * Deactivate Elite license
 */
function woosea_deactivate_license(){
	delete_option( 'license_information' );
}
add_action( 'wp_ajax_woosea_deactivate_license', 'woosea_deactivate_license' );

/**
 * Retrieve variation product id based on it attributes
 **/
function woosea_find_matching_product_variation( $product, $attributes ) {
 
    foreach( $attributes as $key => $value ) {
        if( strpos( $key, 'attribute_' ) === 0 ) {
            continue;
        }
        unset( $attributes[ $key ] );
        $attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
    }
 
    if( class_exists('WC_Data_Store') ) {
        $data_store = WC_Data_Store::load( 'product' );
        return $data_store->find_matching_product_variation( $product, $attributes );
    } else {
        return $product->get_matching_variation( $attributes );
    }
}


/**
 * Remove the price from the JSON-LD on variant product pages
 * As WooCommerce shows the wrong price and it causes items
 * to disapproved in Google's Merchant center because of it
 */
function woosea_product_delete_meta_price( $product = null ) {

	$markup_offer = array();
	$structured_data_fix = get_option ('structured_data_fix');

	if ( ! is_object( $product ) ) {
		global $product;
	}
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return;
	}
	$shop_name = get_bloginfo( 'name' );
	$shop_url  = home_url();
	$shop_currency = get_woocommerce_currency();

	if($structured_data_fix == "yes"){

		if ( '' !== $product->get_price() ) {
			$product_id = get_the_id();
			
			// Get product condition
			$condition = ucfirst( get_post_meta( $product_id, '_woosea_condition', true ) );
			if(!$condition){
				$json_condition = "NewCondition";
			} else {
				$json_condition = $condition."Condition";
			}                	
			
			if ( $product->is_type( 'variable' ) ) {
				// We should first check if there are any _GET parameters available
				// When there are not we are on a variable product page but not on a specific variable one
				// In that case we need to put in the AggregateOffer structured data
				$variation_id = woosea_find_matching_product_variation( $product, $_GET );
				$nr_get = count($_GET);
	
				if($nr_get > 0){
					$variable_product = wc_get_product($variation_id);
					
					if(is_object( $variable_product ) ) {

						$product_price = $variable_product->get_price();

						// Get product condition
						$condition = ucfirst( get_post_meta( $variation_id, '_woosea_condition', true ) );
						if(!$condition){
							$json_condition = "NewCondition";
						} else {
							$json_condition = $condition."Condition";
						}                	

						// Get stock status
						$stock_status = $variable_product->get_stock_status();
                       		 		if ($stock_status == "outofstock"){
                       		         		$availability = "OutOfStock";
                        			} else {
                                			$availability = "InStock";
                        			}

						$markup_offer = array(
							'@type'         => 'Offer',
							'price'		=> $product_price,
							'priceCurrency' => $shop_currency,
							'itemCondition' => 'http://schema.org/'.$json_condition.'',
							'availability'  => 'https://schema.org/'.$availability.'',
							'sku'           => $product->get_sku(),
							'image'         => wp_get_attachment_url( $product->get_image_id() ),
							'description'   => $product->get_description(),
							'seller'        => array(
								'@type' => 'Organization',
								'name'  => $shop_name,
								'url'   => $shop_url,
							),
						);
					} else {
						// AggregateOffer
       	        	                 	$prices  = $product->get_variation_prices();
       	             		            	$lowest  = reset( $prices['price'] );
                        	        	$highest = end( $prices['price'] );

                   	          	   	if ( $lowest === $highest ) {
                        	                	$markup_offer = array(
                              		                  	'@type' => 'Offer',
                                	               	 	'price' => wc_format_decimal( $lowest, wc_get_price_decimals() ),
                                        	       		'priceCurrency' => $shop_currency,
								'itemCondition' => 'http://schema.org/'.$json_condition.'',
                                        			'availability'  => 'https://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
                                   	     				'sku'           => $product->get_sku(),
                                        				'image'         => wp_get_attachment_url( $product->get_image_id() ),
                                        				'description'   => $product->get_description(),
                                        				'seller'        => array(
                                                				'@type' => 'Organization',
                                                				'name'  => $shop_name,
                                                				'url'   => $shop_url,
                                        				), 
			                		);
                                		} else {
                                        		$markup_offer = array(
                                      		          	'@type'     => 'AggregateOffer',
                                             		   	'lowPrice'  => wc_format_decimal( $lowest, wc_get_price_decimals() ),
                        	             		        'highPrice' => wc_format_decimal( $highest, wc_get_price_decimals() ),
                                		              	'priceCurrency' => $shop_currency,
								'itemCondition' => 'http://schema.org/'.$json_condition.'',
								'availability'  => 'https://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
        	                                		'sku'           => $product->get_sku(),
                	                        		'image'         => wp_get_attachment_url( $product->get_image_id() ),
                        	                		'description'   => $product->get_description(),
                                	        		'seller'        => array(
                                        	       	 		'@type' => 'Organization',
                                        	        		'name'  => $shop_name,
                                                			'url'   => $shop_url,
                                        			),
			        	        	);
						}
					}
				} else {
					// When there are no parameters in the URL (so for normal users, not coming via Google Shopping URL's) show the old WooCommwerce JSON
                                	$prices  = $product->get_variation_prices();
                                	$lowest  = reset( $prices['price'] );
                                	$highest = end( $prices['price'] );

                                	if ( $lowest === $highest ) {
                                        	$markup_offer = array(
                                                	'@type' => 'Offer',
                                                	'price' => wc_format_decimal( $lowest, wc_get_price_decimals() ),
                                        	);
                                	} else {
                                        	$markup_offer = array(
                                                	'@type'     => 'AggregateOffer',
                                                	'lowPrice'  => wc_format_decimal( $lowest, wc_get_price_decimals() ),
                                                	'highPrice' => wc_format_decimal( $highest, wc_get_price_decimals() ),
                                        	);
                                	}

                        		$markup_offer += array(
                                		'priceCurrency' => $shop_currency,
                                		'availability'  => 'https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
                                		'seller'        => array(
                                        		'@type' => 'Organization',
                                        		'name'  => $shop_name,
                                        		'url'   => $shop_url,
                                		),
                        		);

				}
	   		} else {
				$markup_offer = array(
 	           	            	'@type' => 'Offer',
                       			'price' => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
					'priceCurrency' => $shop_currency,
					'itemCondition' => 'http://schema.org/'.$json_condition.'',
					'availability'  => 'https://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
					'sku'           => $product->get_sku(),
					'image'         => wp_get_attachment_url( $product->get_image_id() ),
					'description'   => $product->get_description(),
					'seller'        => array(
						'@type' => 'Organization',
						'name'  => $shop_name,
						'url'   => $shop_url,
					),
                      		);
            		}
		}
	} else {
		// Just use the old WooCommerce buggy setting
                if ( '' !== $product->get_price() ) {
                        if ( $product->is_type( 'variable' ) ) {
                                $prices  = $product->get_variation_prices();
                                $lowest  = reset( $prices['price'] );
                                $highest = end( $prices['price'] );

                                if ( $lowest === $highest ) {
                                        $markup_offer = array(
                                                '@type' => 'Offer',
                                                'price' => wc_format_decimal( $lowest, wc_get_price_decimals() ),
                                        );
                                } else {
                                        $markup_offer = array(
                                                '@type'     => 'AggregateOffer',
                                                'lowPrice'  => wc_format_decimal( $lowest, wc_get_price_decimals() ),
                                                'highPrice' => wc_format_decimal( $highest, wc_get_price_decimals() ),
                                        );
                                }
                        } else {
                                $markup_offer = array(
                                        '@type' => 'Offer',
                                        'price' => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
                                );
                        }

                        $markup_offer = array(
                                'priceCurrency' => $shop_currency,
                                'availability'  => 'https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
                                'seller'        => array(
                                        '@type' => 'Organization',
                                        'name'  => $shop_name,
                                        'url'   => $shop_url,
                                ),
                        );
		}
	}
	return $markup_offer;
}
add_filter( 'woocommerce_structured_data_product_offer', 'woosea_product_delete_meta_price' );

/**
 * Get the shipping zone countries and ID's
 */
function woosea_shipping_zones(){
	$shipping_options = "";
	$shipping_zones = WC_Shipping_Zones::get_zones();

	$shipping_options = "<option value=\"all_zones\">All zones</option>";

	foreach ( $shipping_zones as $zone){
		$shipping_options .= "<option value=\"$zone[zone_id]\">$zone[zone_name]</option>";		
	}

	$data = array (
		'dropdown' => $shipping_options,
	);

	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_shipping_zones', 'woosea_shipping_zones' );

/**
 * Get the dynamic attributes
 */ 
function woosea_special_attributes(){
	$attributes_obj = new WooSEA_Attributes;
	$special_attributes = $attributes_obj->get_special_attributes_dropdown();
	$special_attributes_clean = $attributes_obj->get_special_attributes_clean();

	$data = array (
		'dropdown' => $special_attributes,
		'clean' => $special_attributes_clean,
	); 

	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_special_attributes', 'woosea_special_attributes' );

/**
 * Get the available channels for a specific country
 */
function woosea_channel() {
	$country = sanitize_text_field($_POST['country']);
	$channel_obj = new WooSEA_Attributes;
	$data = $channel_obj->get_channels($country);

	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_channel', 'woosea_channel' );

/**
 * Delete a project from cron
 */
function woosea_project_delete(){
	$project_hash = sanitize_text_field($_POST['project_hash']);
        $feed_config = get_option( 'cron_projects' );
	$found = false;

        foreach ( $feed_config as $key => $val ) {
                if ($val['project_hash'] == $project_hash){
			$found = true;
			$found_key = $key;

                	$upload_dir = wp_upload_dir();
                	$base = $upload_dir['basedir'];
                	$path = $base . "/woo-product-feed-pro/" . $val['fileformat'];
                	$file = $path . "/" . sanitize_file_name($val['filename']) . "." . $val['fileformat'];
		}
	}

	if ($found == "true"){
		# Remove project from project array		
		unset($feed_config[$found_key]);
		
		# Update cron
		update_option('cron_projects', $feed_config);

		# Remove project file
		@unlink($file);
	}

}
add_action( 'wp_ajax_woosea_project_delete', 'woosea_project_delete' );

/**
 * Stop processing of a project
 */
function woosea_project_cancel(){
	$project_hash = sanitize_text_field($_POST['project_hash']);
        $feed_config = get_option( 'cron_projects' );

        foreach ( $feed_config as $key => $val ) {
                if ($val['project_hash'] == $project_hash){

        		$batch_project = "batch_project_".$project_hash;
                    	delete_option( $batch_project );
			
			$feed_config[$key]['nr_products_processed'] = 0;

                     	// Set processing status on ready
                      	$feed_config[$key]['running'] = "stopped";
                      	$feed_config[$key]['last_updated'] = date("d M Y H:i");

                   	// In 1 minute from now check the amount of products in the feed and update the history count
                     	wp_schedule_single_event( time() + 60, 'woosea_update_project_stats', array($val['project_hash']) );
		}
	}		
	update_option( 'cron_projects', $feed_config);	
}
add_action( 'wp_ajax_woosea_project_cancel', 'woosea_project_cancel' );

/**
 * Refresh a project 
 */
function woosea_project_refresh(){
	$project_hash = sanitize_text_field($_POST['project_hash']);
        $feed_config = get_option( 'cron_projects' );

        foreach ( $feed_config as $key => $val ) {
                if ($val['project_hash'] == $project_hash){
        		$batch_project = "batch_project_".$project_hash;
			if (!get_option( $batch_project )){
        			update_option( $batch_project, $val);
        			$final_creation = woosea_continue_batch($project_hash);
			} else {
        			$final_creation = woosea_continue_batch($project_hash);
			}
		}
	}
}
add_action( 'wp_ajax_woosea_project_refresh', 'woosea_project_refresh' );

/**
 * Change status of a project from active to inactive or visa versa
 */
function woosea_project_status() {
	$project_hash = sanitize_text_field($_POST['project_hash']);
	$active = sanitize_text_field($_POST['active']);
	$feed_config = get_option( 'cron_projects' );

        foreach ( $feed_config as $key => $val ) {
                if ($val['project_hash'] == $project_hash){
                        $feed_config[$key]['active'] = $active;
                	$upload_dir = wp_upload_dir();
                	$base = $upload_dir['basedir'];
                	$path = $base . "/woo-product-feed-pro/" . $val['fileformat'];
                	$file = $path . "/" . sanitize_file_name($val['filename']) . "." . $val['fileformat'];
                }
        }

	// When project is put on inactive, delete the product feed
	if($active == "false"){
		@unlink($file);
	}

	// Regenerate product feed
	if($active == "true"){
		$update_project = woosea_project_refresh($project_hash);
	}

	// Update cron with new project status
        update_option( 'cron_projects', $feed_config);
}
add_action( 'wp_ajax_woosea_project_status', 'woosea_project_status' );

/**
 * Register interaction with the review request notification
 * We do not want to keep bothering our users with the notification
 */
function woosea_review_notification() {
	// Update review notification status
        update_option( 'woosea_review_interaction', 'yes');
}
add_action( 'wp_ajax_woosea_review_notification', 'woosea_review_notification' );


/**
 * This function enables the setting to fix the 
 * WooCommerce structured data bug
 */
function woosea_enable_structured_data (){
        $status = sanitize_text_field($_POST['status']);
	if ($status == "off"){
		update_option( 'structured_data_fix', 'no', 'yes');
	} else {
		update_option( 'structured_data_fix', 'yes', 'yes');
	}
}
add_action( 'wp_ajax_woosea_enable_structured_data', 'woosea_enable_structured_data' );


/**
 * This function enables the setting to add 
 * identifiers GTIN, MPN, EAN, UPC, Brand and Condition
 */
function woosea_add_identifiers (){
        $status = sanitize_text_field($_POST['status']);
	if ($status == "off"){
		update_option( 'add_unique_identifiers', 'no', 'yes');
	} else {
		update_option( 'add_unique_identifiers', 'yes', 'yes');
	}
}
add_action( 'wp_ajax_woosea_add_identifiers', 'woosea_add_identifiers' );

/**
 * This function add the actual fields to the edit product page for single products 
 * identifiers GTIN, MPN, EAN, UPC, Brand and Condition
 */
function woosea_custom_general_fields() {
        global $woocommerce, $post;

	// Check if the option is enabled or not in the pluggin settings 
	if( get_option('add_unique_identifiers') == "yes" ){

	        echo '<div id="woosea_attr" class="options_group">';

	        // Brand field
        	woocommerce_wp_text_input(
                	array(
                   		'id'          => '_woosea_brand',
             	           	'label'       => __( 'Brand', 'woocommerce' ),
               	        	'desc_tip'    => 'true',
                        	'value'           =>  get_post_meta( $post->ID, '_woosea_brand', true ),
                        	'description' => __( 'Enter the product Brand here.', 'woocommerce' )
                	)
        	);

        	echo '</div>';
        	echo '<div id="woosea_attr" class="options_group show_if_simple show_if_external">';

        	// Global Trade Item Number (GTIN) Field
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_gtin',
                        	'label'       => __( 'GTIN', 'woocommerce' ),
                        	'desc_tip'    => 'true',
                        	'description' => __( 'Enter the product Global Trade Item Number (GTIN) here.', 'woocommerce' ),
                	)
        	);

        	// MPN Field
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_mpn',
                        	'label'       => __( 'MPN', 'woocommerce' ),
                        	'desc_tip'    => 'true',
                        	'description' => __( 'Enter the manufacturer product number', 'woocommerce' ),
                	)
        	);

        	// Universal Product Code (UPC) Field
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_upc',
                        	'label'       => __( 'UPC', 'woocommerce' ),
                        	'desc_tip'    => 'true',
                        	'description' => __( 'Enter the Universal Product Code (UPC) here.', 'woocommerce' ),
                	)
        	);

        	// International Article Number (EAN) Field
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_ean',
                        	'label'       => __( 'EAN', 'woocommerce' ),
                        	'desc_tip'    => 'true',
                        	'description' => __( 'Enter the International Article Number (EAN) here.', 'woocommerce' ),
                	)
        	);

        	// Optimized product custom title Field
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_optimized_title',
                        	'label'       => __( 'Optimized title', 'woocommerce' ),
                       	 	'desc_tip'    => 'true',
                        	'description' => __( 'Enter a optimized product title.', 'woocommerce' ),
                	)
        	);

		// Add product condition drop-down
		woocommerce_wp_select(
			array(
				'id'		=> '_woosea_condition',
				'label'		=> __( 'Product condition', 'woocommerce' ),
				'desc_tip'	=> 'true',
				'description'	=> __( 'Select the product condition.', 'woocommerce' ),
				'options'	=> array (
					''		=> __( '', 'woocommerce' ),
					'new'		=> __( 'new', 'woocommerce' ),
					'refurbished'	=> __( 'refurbished', 'woocommerce' ),
					'used'		=> __( 'used', 'woocommerce' ),
					'damaged'	=> __( 'damaged', 'woocommerce' ),
				)
			)
		);

		// Exclude product from feed
		woocommerce_wp_checkbox(
			array(
				'id'		=> '_woosea_exclude_product',
				'label'		=> __( 'Exclude from feeds', 'woocommerce' ),
				'desc_tip'	=> 'true',
				'description'	=> __( 'Check this box if you want this product to be excluded from product feeds.', 'woocommerce' ),
			)
		);

        	echo '</div>';
	}
}
add_action( 'woocommerce_product_options_general_product_data', 'woosea_custom_general_fields' );

/**
 * This function saves the input from the extra fields on the single product edit page
 */
function woosea_save_custom_general_fields($post_id){

        $woocommerce_brand      	= $_POST['_woosea_brand'];
        $woocommerce_gtin       	= $_POST['_woosea_gtin'];
        $woocommerce_upc        	= $_POST['_woosea_upc'];
        $woocommerce_mpn        	= $_POST['_woosea_mpn'];
        $woocommerce_ean        	= $_POST['_woosea_ean'];
        $woocommerce_title      	= $_POST['_woosea_optimized_title'];
        $woocommerce_condition      	= $_POST['_woosea_condition'];
	$woocommerce_exclude_product 	= $_POST['_woosea_exclude_product'];

        if(isset($woocommerce_brand))
                update_post_meta( $post_id, '_woosea_brand', esc_attr($woocommerce_brand));

        if(isset($woocommerce_mpn))
                update_post_meta( $post_id, '_woosea_mpn', esc_attr($woocommerce_mpn));

        if(isset($woocommerce_upc))
                update_post_meta( $post_id, '_woosea_upc', esc_attr($woocommerce_upc));

        if(isset($woocommerce_ean))
                update_post_meta( $post_id, '_woosea_ean', esc_attr($woocommerce_ean));

        if(isset($woocommerce_gtin))
                update_post_meta( $post_id, '_woosea_gtin', esc_attr($woocommerce_gtin));

        if(isset($woocommerce_title))
                update_post_meta( $post_id, '_woosea_optimized_title', esc_attr($woocommerce_title));
     
	if(isset($woocommerce_condition))
                update_post_meta( $post_id, '_woosea_condition', esc_attr($woocommerce_condition));

	if(isset($woocommerce_exclude_product))
                update_post_meta( $post_id, '_woosea_exclude_product', esc_attr($woocommerce_exclude_product));
}
add_action( 'woocommerce_process_product_meta', 'woosea_save_custom_general_fields' );

/**
 * Create the unique identifier fields for variation products
 */
function woosea_custom_variable_fields( $loop, $variation_id, $variation ) {

        // Check if the option is enabled or not in the pluggin settings 
        if( get_option('add_unique_identifiers') == "yes" ){

                // Variation Brand field
                woocommerce_wp_text_input(
                        array(
                                'id'       => '_woosea_variable_brand['.$loop.']',
                                'label'       => __( '<br>Brand', 'woocommerce' ),
                                'placeholder' => 'Parent Brand',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the product Brand here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_brand', true),
                                'wrapper_class' => 'form-row-full',
                        )
                );

                // Variation GTIN field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_variable_gtin['.$loop.']',
                                'label'       => __( '<br>GTIN', 'woocommerce' ),
                                'placeholder' => 'GTIN',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the product GTIN here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_gtin', true),
                                'wrapper_class' => 'form-row-last',
                        )
                );

                // Variation MPN field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_variable_mpn['.$loop.']',
                                'label'       => __( '<br>MPN', 'woocommerce' ),
                                'placeholder' => 'Manufacturer Product Number',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the product UPC here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_mpn', true),
                                'wrapper_class' => 'form-row-first',
                        )
                );
                // Variation UPC field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_variable_upc['.$loop.']',
                                'label'       => __( '<br>UPC', 'woocommerce' ),
                                'placeholder' => 'UPC',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the product UPC here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_upc', true),
                                'wrapper_class' => 'form-row-last',
                        )
                );

                // Variation EAN field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_variable_ean['.$loop.']',
                                'label'       => __( '<br>EAN', 'woocommerce' ),
                                'placeholder' => 'EAN',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the product EAN here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_ean', true),
                                'wrapper_class' => 'form-row-first',
                        )
                );

                // Variation optimized title field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_optimized_title['.$loop.']',
                                'label'       => __( '<br>Optimized title', 'woocommerce' ),
                                'placeholder' => 'Optimized title',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter a optimized product title here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_optimized_title', true),
                                'wrapper_class' => 'form-row-last',
                        )
                );

		// Add product condition drop-down
		woocommerce_wp_select(
			array(
				'id'		=> '_woosea_condition['.$loop.']',
				'label'		=> __( 'Product condition', 'woocommerce' ),
				'placeholder'	=> 'Product condition',
				'desc_tip'	=> 'true',
				'description'	=> __( 'Select the product condition.', 'woocommerce' ),
                                'value'       	=> get_post_meta($variation->ID, '_woosea_condition', true),
                                'wrapper_class' => 'form-row form-row-full',
				'options'	=> array (
					''		=> __( '', 'woocommerce' ),
					'new'		=> __( 'new', 'woocommerce' ),
					'refurbished'	=> __( 'refurbished', 'woocommerce' ),
					'used'		=> __( 'used', 'woocommerce' ),
					'damaged'	=> __( 'damaged', 'woocommerce' ),
				)
			)
		);

		// Exclude product from feed
		woocommerce_wp_checkbox(
			array(
				'id'		=> '_woosea_exclude_product['.$loop.']',
				'label'		=> __( '&nbsp;Exclude from feeds', 'woocommerce' ),
				'placeholder'	=> 'Exclude from feeds',
				'desc_tip'	=> 'true',
				'description'	=> __( 'Check this box if you want this product to be excluded from product feeds.', 'woocommerce' ),
                                'value'       	=> get_post_meta($variation->ID, '_woosea_exclude_product', true),
			)
		);
	}
}
add_action( 'woocommerce_product_after_variable_attributes', 'woosea_custom_variable_fields', 10, 3 );

/**
 * Save the unique identifier fields for variation products
 */
function woosea_save_custom_variable_fields( $post_id ) {

        if (isset( $_POST['variable_sku'] ) ) {

                $variable_sku          = $_POST['variable_sku'];
                $variable_post_id      = $_POST['variable_post_id'];

                $max_loop = max( array_keys( $_POST['variable_post_id'] ) );

                for ( $i = 0; $i <= $max_loop; $i++ ) {

                if ( ! isset( $variable_post_id[ $i ] ) ) {
                  continue;
                }

                // Brand Field
                $_brand = $_POST['_woosea_variable_brand'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_brand[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_brand', stripslashes( $_brand[$i]));
                        }


                // MPN Field
                $_mpn = $_POST['_woosea_variable_mpn'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_mpn[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_mpn', stripslashes( $_mpn[$i]));
                        }

                // UPC Field
                $_upc = $_POST['_woosea_variable_upc'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_upc[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_upc', stripslashes( $_upc[$i]));
                        }

                // EAN Field
                $_ean = $_POST['_woosea_variable_ean'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_ean[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_ean', stripslashes( $_ean[$i]));
                        }


                // GTIN Field
                $_gtin = $_POST['_woosea_variable_gtin'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_gtin[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_gtin', stripslashes( $_gtin[$i]));
                        }

                // Optimized title Field
                $_opttitle = $_POST['_woosea_optimized_title'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_opttitle[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_optimized_title', stripslashes( $_opttitle[$i]));
                        }

                // Product condition Field
                $_condition = $_POST['_woosea_condition'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_condition[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_condition', stripslashes( $_condition[$i]));
                        }

                // Exclude product from feed
                if(isset($_POST['woosea_exclude_product'])){
			$_excludeproduct = $_POST['_woosea_exclude_product'];
        	                $variation_id = (int) $variable_post_id[$i];
                	        if ( isset( $_excludeproduct[$i] ) ) {
                        	        update_post_meta( $variation_id, '_woosea_exclude_product', stripslashes( $_excludeproduct[$i]));
                       		 }
        		}
		}	
	}
}
add_action( 'woocommerce_save_product_variation', 'woosea_save_custom_variable_fields', 10, 1 );

/**
 * Returns the user ID of the user which is used as the
 * login for AdTribes support. The user is created here if it doesn't
 * exist yet, with role Shop Manager.
 * 
 * @return int adtribes-support user ID
 */
function woosea_create_support_user() {
        $status = sanitize_text_field($_POST['status']);
	$user_id = get_current_user_id();
       	$user = get_user_by( 'login', 'adtribes-support' );

     	if ( $user instanceof WP_User ) {
		// user exists already
		if($status == "off"){
        		$user_id = $user->ID;
			$del_user = wp_delete_user($user_id);	
			update_option('woosea_support_user', 'no', 'yes');
		} else {
        		$user_id = $user->ID;
			update_option('woosea_support_user', 'yes', 'yes');
		}
       	} else {
        	$user_pass = wp_generate_password( 12 );
               	$maybe_user_id = wp_insert_user( array(
                	'user_login' => 'adtribes-support',
                    	'role'       => 'administrator',
                    	'user_pass'  => $user_pass,
			'user_email' => 'support@adtribes.io',
			'display_name' => 'AdTribes.io Support',
			'description' => 'AdTribes.io Support user created to help with configuration of product feeds',
            	) );
             	if ( !( $maybe_user_id instanceof WP_Error ) ) {
                	$user_id = $maybe_user_id;

			update_option('woosea_support_user', 'yes', 'yes');

                      	// notify admin
                      	$user = get_userdata( $user_id );
                     	$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

                  	$message  = sprintf( __( 'AdTribes.io support user created on %s:', WOOCOMMERCESEA_PLUGIN_NAME ), $blogname ) . "\r\n\r\n";
                    	$message .= sprintf( __( 'Username: %s', WOOCOMMERCESEA_PLUGIN_NAME ), $user->user_login ) . "\r\n\r\n";
                     	$message .= sprintf( __( 'Password: %s', WOOCOMMERCESEA_PLUGIN_NAME ), $user_pass ) . "\r\n\r\n";
                     	$message .= __( 'The user has the role of an Admin.', WOOCOMMERCESEA_PLUGIN_NAME ) . "\r\n";

                   	@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] AdTribes.io Support User', WOOCOMMERCESEA_PLUGIN_NAME ), $blogname ), $message);
        
			// notify AdTribes.io support
                     	$websitehome = wp_specialchars_decode( get_option( 'home' ), ENT_QUOTES );
			$admin_login = wp_login_url( get_permalink() );
			$websiteadmin = $admin_login;           
 
		      	$message  = sprintf( __( 'AdTribes.io support user created on %s:', WOOCOMMERCESEA_PLUGIN_NAME ), $blogname ) . "\r\n\r\n";
			$message .= sprintf( __( 'Website: %s', WOOCOMMERCESEA_PLUGIN_NAME ), $websitehome ) . "\r\n\r\n";
			$message .= sprintf( __( 'WP Admin: %s', WOOCOMMERCESEA_PLUGIN_NAME ), $websiteadmin ) . "\r\n\r\n";
			$message .= sprintf( __( 'Username: %s', WOOCOMMERCESEA_PLUGIN_NAME ), $user->user_login ) . "\r\n\r\n";
                     	$message .= sprintf( __( 'Password: %s', WOOCOMMERCESEA_PLUGIN_NAME ), $user_pass ) . "\r\n\r\n";
			
			$message .= __( 'The user has the role of a Admin.', WOOCOMMERCESEA_PLUGIN_NAME ) . "\r\n";

                   	@wp_mail( 'support@adtribes.io', sprintf( __( '[%s] AdTribes.io Support User', WOOCOMMERCESEA_PLUGIN_NAME ), $blogname ), $message);
	      	}
   	}
      	return $user_id;
}
add_action( 'wp_ajax_woosea_create_support_user', 'woosea_create_support_user' );

/**
 * Set project history: amount of products in the feed
 **/
function woosea_update_project_history($project_hash){
        $feed_config = get_option( 'cron_projects' );
  	
	foreach ( $feed_config as $key => $project ) {
	       if ($project['project_hash'] == $project_hash){
			$nr_products = 0;
			$upload_dir = wp_upload_dir();
     			$base = $upload_dir['basedir'];
     			$path = $base . "/woo-product-feed-pro/" . $project['fileformat'];
      			$file = $path . "/" . sanitize_file_name($project['filename']) . "." . $project['fileformat'];

     			if (file_exists($file)) {
        			if(($project['fileformat'] == "csv") || ($project['fileformat'] == "txt")){
               				$fp = file($file);
                      			$raw_nr_products = count($fp);
                      			$nr_products = $raw_nr_products-1; // header row of csv
	     			} else {
                     			$xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);

					if($project['name'] == "Yandex"){
                         			$nr_products = count($xml->offers->offer);
					} else {
                      				if ($project['taxonomy'] == "none"){
                         				$nr_products = count($xml->product);
                       				} else {
                          				$nr_products = count($xml->channel->item);
                      				}
					}
            			}
  			}
        		$count_timestamp = date("d M Y H:i");
       			$number_run = array(
        			$count_timestamp => $nr_products,
      			);

 	    		$feed_config = get_option( 'cron_projects' );
     			foreach ( $feed_config as $key => $val ) {
      				if (($val['project_hash'] == $project['project_hash']) AND ($val['running'] == "ready")){
      					//unset($feed_config[$key]['history_products']);
             				if (array_key_exists('history_products', $feed_config[$key])){
             					$feed_config[$key]['history_products'][$count_timestamp] = $nr_products;
              				} else {
                				$feed_config[$key]['history_products'] = $number_run;
            				}
      				}
      			}
       			update_option( 'cron_projects', $feed_config);
		}
	}
}
add_action( 'woosea_update_project_stats', 'woosea_update_project_history',1,1 );


/**
 * Get the attribute mapping helptexts
 */
function woosea_fieldmapping_dialog_helptext(){
	$field = sanitize_text_field($_POST['field']);

	switch ($field) {
		case "g:id";
			$helptext = "(Required field) The g:id field is used to uniquely identify each product. The g:id needs to be unique and remain the same forever. Google advises to map the g:id field to a SKU value, however since this field is not always present nor always filled we suggest you map the 'Product Id' field to g:id.";
			break;
		case "g:title";
			$helptext = "(Required field) The g:title field should clearly identify the product you are selling. We suggest you map this field to your 'Product name'.";
			break;
		case "g:description";
			$helptext = "(Required field) The g:description field should tell users about your product. We suggest you map this field to your 'Product description' or 'Product short description'";
			break;
		case "g:link";
			$helptext = "(Required field) The g:link field should be filled with the landing page on your website. We suggest you map this field to your 'Link' attribute.";
			break;
		case "g:image_link";
			$helptext = "(Required field) Include the URL for your main product image with the g:image_link attribute. We suggest you map this field to your 'Main image' attribute.";
			break;
		case "g:definition";
			$helptext = "(Required field) Use the g:availability attribute to tell users and Google whether you have a product in stock. We suggest you map this field to your 'Availability' attribute.";
			break;
		case "g:price";
			$helptext = "(Required field) Use the g:price attribute to tell users how much you are charging for your product. We suggest you map this field to your 'Price' attribute. When a product is on sale the plugin will automatically get the sale price instead of the normal base price. Also, make sure you use a currency pre- or suffix as this is required by Google when you have not configured a currency in your Google Merchant center. The plugin automatically determines your relevant currency and puts this in the price prefix field.";
			break;
		case "g:google_product_category";
			$helptext = "(Required for some product categories) Use the g:google_product_category attribute to indicate the category of your item based on the Google product taxonomy. Map this field to your 'Category' attribute. In the next configuration step you will be able to map your categories to Google's category taxonomy. Categorizing your product helps ensure that your ad is shown with the right search results.";
			break;
		case "g:brand";
			$helptext = "Use the g:brand attribute to indicate the product's brand name. The brand is used to help identify your product and will be shown to users who view your ad. g:brand is required for each product with a clearly associated brand or manufacturer. If the product doesn't have a clearly associated brand (e.g. movies, books, music) or is a custom-made product (e.g. art, custom t-shirts, novelty products and handmade products), the attribute is optional. As WooCommerce does not have a brand attribute out of the box you will probably have to map the g:brand field to a custom/dynamic field or product attribute.";
			break;
		case "g:gtin";
			$helptext = "(Required for all products with a GTIN assigned by the manufacturer). This specific number helps Google to make your ad richer and easier for users to find. Products submitted without any unique product identifiers are difficult to classify and may not be able to take advantage of all Google Shopping features. Several different types of ID numbers are considered a GTIN, for example: EAN, UPC, JAN, ISBN, IFT-14. Most likely you have configured custom/dynamic or product attribute that you need to map to the g:gtin field.";
			break;
		case "g:mpn";
			$helptext = "(Required for all products without a manufacturer-assigned GTIN.) USe the mpn attribute to submit your product's Manufacturer Part Number (MPN). MPNs are used to uniquely identify a specific product among all products from the same manufacturer. Users might search Google Shopping specifically for an MPN, so providing the MPN can help ensure that your product is shown in relevant situations. When a product doesn't have a clearly associated mpn or is a custom-made product (e.g. art, custom t-shirts, novelty products and handmade products), the attribute is optional.";
			break;
		case "g:identifier_exists";
			$helptext = "(Required only for new products that don’t have <b>gtin and brand</b> or <b>mpn and brand</b>.) Use the g:identifier_exists attribute to indicate that unique product identifiers aren’t available for your product. Unique product identifiers include gtin, mpn, and brand. The plugin automatically determines if the value for a product is 'no' or 'yes' when you set the g:identifier_exists to 'Plugin calculation'.";
			break;
		case "g:condition";
			$helptext = "(Required) Tell users about the condition of the product you are selling. Supported values are: 'new', 'refurbished' and 'used'. We suggest you map this field to the 'Condition' attribute.";
			break;
		case "g:item_group_id";
			$helptext = "(Required for the following countries: Brazil, France, Germany, Japan, United Kingdom and the United States). The g:item_group_id is used to group product variants in your product data. We suggest you map the g:item_group_id to the 'Item group ID' attribute. The plugin automatically ads the correct value to this field and makes sure the 'mother' products is not in your product feed (as required by Google).";
			break;
		case "g:shipping";
			$helptext = "(Required when you need to override the shipping settings that you set up in Merchant Center) Google recommends that you set up shipping costs through your Merchant center. However, when you need to override these settings you can map the g:shipping field to the 'Shipping price' attribute.";
			break;
		case "Grant access";
			$helptext = "Grant access to our support employees so we can help you out with creating your product feed.";
			break;
		case "Structured data fix";
			$helptext = "Because of a bug in WooCommerce variable products will get disapproved in Google's Merchant Center. WooCommerce adds the price of the cheapest variable product in the structured data for all variations of a product. Because of this there will be a mismatch between the product price you provide to Google in your Google Shopping product feed and the structured data price on the product landingpage. Google will therefor disapprove the product in its merchant center. You won't be able to advertise on that product in your Google Shopping campaign. Enable this option will fix the structured data on variable product pages by adding the correct variable product price in the JSON-LD structured data so Google will approve the variable products you submitted.";
			break;
		case "Unique identifiers";
			$helptext = "In order to optimise your product feed for Google Shopping and meet all Google's Merchant Center requirements you need to add extra fields / attributes to your products that are not part of WooCommerce by default. Enable this option to get Brand, GTIN, MPN, UPC, EAN, Product condition and optimised title fields";
			break;
		default:
			$helptext = "need information about this field? reach out to support@adtribes.io";
	}

	$data = array (
		'helptext' => $helptext,
	);

	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_fieldmapping_dialog_helptext', 'woosea_fieldmapping_dialog_helptext' );


/**
 * Get the dropdowns for the fieldmapping page
 */
function woosea_fieldmapping_dropdown(){
	$channel_hash = sanitize_text_field($_POST['channel_hash']);
	$rowCount = sanitize_text_field($_POST['rowCount']);
        $channel_data = WooSEA_Update_Project::get_channel_data($channel_hash);

        require plugin_dir_path(__FILE__) . '/classes/channels/class-'.$channel_data['fields'].'.php';
        $obj = "WooSEA_".$channel_data['fields'];
        $fields_obj = new $obj;
        $attributes = $fields_obj->get_channel_attributes();
	$field_options = "<option selected></option>";
 	
	foreach($attributes as $key => $value){
		$field_options .= "<option></option>";
		$field_options .= "<optgroup label='$key'><strong>$key</strong>";
		foreach($value as $k => $v){
               		$field_options .= "<option value='$v[feed_name]'>$k ($v[name])</option>";
		}
	}
 
        $attributes_obj = new WooSEA_Attributes;
        $attribute_dropdown = $attributes_obj->get_product_attributes();

	$attribute_options = "<option selected></option>";
   	foreach($attribute_dropdown as $drop_key => $drop_value){
        	$attribute_options .= "<option value='$drop_key'>$drop_value</option>";
	}

	$data = array (
		'field_options' => $field_options,
		'attribute_options' => $attribute_options,
	);

	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_fieldmapping_dropdown', 'woosea_fieldmapping_dropdown' );

/**
 * Get the attribute dropdowns for category mapping
 */
function woosea_autocomplete_dropdown() {
	$rowCount = sanitize_text_field($_POST['rowCount']);
	
	$mapping_obj = new WooSEA_Attributes;
	$mapping_dropdown = $mapping_obj->get_mapping_attributes_dropdown();

	$data = array (
		'rowCount' => $rowCount,
		'dropdown' => $mapping_dropdown
	);

	echo json_encode($data);
	wp_die();

}
add_action( 'wp_ajax_woosea_autocomplete_dropdown', 'woosea_autocomplete_dropdown' );

/**
 * Autosuggest categories or productnames for category mapping page
 */
function woosea_autocomplete_mapping() {
	$query = sanitize_text_field($_POST['query']);
	$searchin = sanitize_text_field($_POST['searchin']);
	$condition = sanitize_text_field($_POST['condition']);

	$data = array();	
	$data_raw = array();

	// search on exact productname
	if (($searchin == "title") AND ($condition == "=") OR ($condition == "contains")){
		$prods = new WP_Query(
                	array(
				's' => $query,
           			'posts_per_page' => -1,
                         	'post_type' => array('product'),
                              	'post_status' => 'publish',
                            	'fields' => 'ids',
                              	'no_found_rows' => true
                    	)
          	);

                while ($prods->have_posts()) : $prods->the_post();
               		global $product;
			$product_title = $product->get_title();

			if ($product->is_type( 'variable' )) {
				$attrv = $product->get_variation_attributes();
				foreach ($attrv as $ka => $va){
					foreach ($va as $k => $v){
						$data_raw[] = $product_title ." ". ucfirst($v);
					}
				}
			}
            	endwhile;
             	wp_reset_query();	
	// search on exact categoryname
	} elseif (($searchin == "categories") AND ($condition == "=")) {
		$hide_empty = false ;
		$cat_args = array(
			'search'	=> $query,
    			'hide_empty' 	=> $hide_empty,
		);
		$all_categories = get_terms( 'product_cat', $cat_args );
            	foreach($all_categories as $sub_category) {
                	$data_raw[] = $sub_category->name;
            	}   
	} else {
		$data_raw[] = "";
	}

	$data = array_unique($data_raw);
	$data = json_encode($data);
	echo $data;
	wp_die();
}
add_action( 'wp_ajax_woosea_autocomplete_mapping', 'woosea_autocomplete_mapping' );

/**
 * Function for serving different HTML templates while configuring the feed
 * Some cases are left blank for future steps and pages in the configurations process
 */
function woosea_generate_pages(){
	if (!$_POST){
		$generate_step = 0;
	} else {
		$from_post = $_POST;
		$channel_hash = sanitize_text_field($_POST['channel_hash']);
		$step = sanitize_text_field($_POST['step']);	
		$generate_step = $step;
	}

	if (array_key_exists('step', $_GET)){
		if (array_key_exists('step', $_POST)){
			$generate_step = $step;
		} else {
			$generate_step = sanitize_text_field($_GET["step"]);
		}
	}

	if (isset($_GET['channel_hash'])){
		$channel_hash = sanitize_text_field($_GET['channel_hash']);
	}

        /**
         * Get channel information 
         */
	if ($generate_step){
        	$channel_data = WooSEA_Update_Project::get_channel_data($channel_hash);
	}

	/**
	 * Determing if we need to do field mapping or attribute picking after step 0
	 */
	if ($generate_step == 99){
		$generate_step = 7;
	} elseif ($generate_step == 100){
	        /**
       	 	 * Update existing feed configuration with new values from previous step
        	 */
        	$project = WooSEA_Update_Project::reconfigure_project($from_post);
	} elseif ($generate_step == 101){
		/**
         	 * Update project configuration 
         	 */
        	$project_data = WooSEA_Update_Project::update_project($from_post);

        	/**
         	 * Set some last project configs
         	 */
        	$project_data['active'] = true;
        	$project_data['last_updated'] = date("d M Y H:i");
        	$project_data['running'] = "processing";

		$count_variation = wp_count_posts('product_variation');
		$count_single = wp_count_posts('product');
		$published_single = $count_single->publish;
		$published_variation = $count_variation->publish;
		$published_products = $published_single+$published_variation;

        	$project_data['nr_products'] = $published_products;
        	$project_data['nr_products_processed'] = 0;

        	$add_to_cron = WooSEA_Update_Project::add_project_cron($project_data, "donotdo");
        	$batch_project = "batch_project_".$project_data['project_hash'];
        	
		if (!get_option( $batch_project )) {
			// Batch project hook expects a multidimentional array
        		update_option( $batch_project, $project_data);
        		$final_creation = woosea_continue_batch($project_data['project_hash']);
		} else {
        		$final_creation = woosea_continue_batch($project_data['project_hash']);
		}
	}

	/**
	 * Switch to determing what template to use during feed configuration
	 */
	switch($generate_step){
		case 0:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-0.php' );
			break;
		case 1:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-1.php' );
			break;
		case 2:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-2.php' );
			break;
		case 3:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-3.php' );
			break;
		case 4:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-4.php' );
			break;
		case 5:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-5.php' );
			break;
		case 6:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-6.php' );
			break;
		case 7:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-generate-feed-step-7.php' );
			break;
		case 8:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-statistics-feed.php' );
			break;
		case 100:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-manage-feed.php' );
			break;
		case 101:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-manage-feed.php' );
			break;
		default:
			load_template( plugin_dir_path( __FILE__ ) . '/pages/admin/woosea-manage-feed.php' );
			break;
	}
}

/**
 * Check for active license, is this a paid version of the plugin?
 * This function is called by the cron after 30 seconds. The check only
 * needs to be done once and not continuesly so it gets removed.
 */
function woosea_de_register_license(){
	wp_dequeue_script( 'woosea_adtribes-js' );
  	wp_deregister_script( 'woosea_adtribes-js' );
}
add_action( 'woosea_deregister_hook', 'woosea_de_register_license', 99999); // deregister the paid version check after 60 seconds

/**
 * This function checks if the Elite license is valid. When the license
 * key is invalid or expired the advanced options of this plugin will be disabled
 */
function woosea_license_valid(){
	$domain = $_SERVER['HTTP_HOST'];
	$license_information = get_option('license_information');

        $curl = curl_init();
        $url = "https://www.adtribes.io/check/license.php?key=$license_information[license_key]&email=$license_information[license_email]&domain=$domain&version=2.6.7";

	curl_setopt_array($curl, array(
      		CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'AdTribes license cURL Request'
        ));
        $response = curl_exec( $curl );
        curl_close($curl);
        $json_return = json_decode($response, true);
        	
	$license_start_time = strtotime($json_return['created']);
        $license_end_time = strtotime('+1 years', $license_start_time);
        $current_time = time();
	$license_information['notice'] = $json_return['notice'];

	if($json_return['valid'] == "false"){
		$license_information['message'] = $json_return['message'];
        	$license_information['message_type'] = $json_return['message_type'];
		$license_information['license_valid'] = "false";

        	update_option ('license_information', $license_information);
        	delete_option ('structured_data_fix');
        	delete_option ('add_unique_identifiers');
	} else {
		$license_information['message'] = $json_return['message'];
        	$license_information['message_type'] = $json_return['message_type'];
		$license_information['license_valid'] = "true";

        	update_option ('license_information', $license_information);
	}
}


/**
 * Function used by event scheduling to create feeds 
 * Feed can automatically be generated every hour, twicedaiy or once a day
 */
function woosea_create_all_feeds(){
	$feed_config = get_option( 'cron_projects' );
	$nr_projects = count($feed_config);
	$cron_start_date = date("d M Y H:i");	
	$cron_start_time = time();
	$hour = date('H');

	// Update project configurations with the latest amount of live products
        $count_products = wp_count_posts('product', 'product_variation');
        $nr_products = $count_products->publish;
	
	if(!empty($feed_config)){	
		foreach ( $feed_config as $key => $val ) {

			// Force garbage collection dump
			gc_enable();
			gc_collect_cycles();

			// Only process projects that are active
			if(($val['active'] == "true") AND (!empty($val))){		
		
				if (($val['cron'] == "daily") AND ($hour == 07)){
					$batch_project = "batch_project_".$val['project_hash'];
                        		if (!get_option( $batch_project )){
                                		update_option( $batch_project, $val);
						$start_project = woosea_continue_batch($val['project_hash']);
					} else {
						$start_project = woosea_continue_batch($val['project_hash']);
					}
					unset($start_project);	
				} elseif (($val['cron'] == "twicedaily") AND ($hour == 19 || $hour == 07)){
					$batch_project = "batch_project_".$val['project_hash'];
                        		if (!get_option( $batch_project )){
                                		update_option( $batch_project, $val);
						$start_project = woosea_continue_batch($val['project_hash']);
					} else {
						$start_project = woosea_continue_batch($val['project_hash']);
					}
					unset($start_project);	
				} elseif (($val['cron'] == "twicedaily" || $val['cron'] == "daily") AND ($val['running'] == "processing")){
					// Re-start daily and twicedaily projects that are hanging
					$batch_project = "batch_project_".$val['project_hash'];
                        		if (!get_option( $batch_project )){
                                		update_option( $batch_project, $val);
						$start_project = woosea_continue_batch($val['project_hash']);
					} else {
						$start_project = woosea_continue_batch($val['project_hash']);
					}
					unset($start_project);
				} elseif (($val['cron'] == "no refresh") AND ($hour == 26)){
					// It is never hour 26, so this project will never refresh
				} elseif ($val['cron'] == "hourly") {
					$batch_project = "batch_project_".$val['project_hash'];
                        		if (!get_option( $batch_project )){
                                		update_option( $batch_project, $val);
						$start_project = woosea_continue_batch($val['project_hash']);
					} else {
						$start_project = woosea_continue_batch($val['project_hash']);
					}
					unset($start_project);	
				}
			}
		}
	}
}

/**
 * Update product amounts for project
 */
function woosea_nr_products($project_hash, $nr_products){
	$feed_config = get_option( 'cron_projects' );

	foreach ( $feed_config as $key => $val ) {
		if ($val['project_hash'] == $project_hash){
			$feed_config[$key]['nr_products'] = $nr_products;
		}
	}
	update_option( 'cron_projects', $feed_config);
}

/**
 * Update cron projects with last update timestamp
 */
function woosea_last_updated($project_hash){
	$feed_config = get_option( 'cron_projects' );

	$last_updated = date("d M Y H:i");

	foreach ( $feed_config as $key => $val ) {
		if ($val['project_hash'] == $project_hash){
        		$upload_dir = wp_upload_dir();
        		$base = $upload_dir['basedir'];
        		$path = $base . "/woo-product-feed-pro/" . $val['fileformat'];
        		$file = $path . "/" . sanitize_file_name($val['filename']) . "." . $val['fileformat'];

			$last_updated = date("d M Y H:i");

			if (file_exists($file)) {
				$last_updated = date("d M Y H:i", filemtime($file));
				$feed_config[$key]['last_updated'] = date("d M Y H:i", filemtime($file));
			} else {
				$feed_config[$key]['last_updated'] = date("d M Y H:i");
			}
		}
	}

	update_option( 'cron_projects', $feed_config);
	return $last_updated;
}

/**
 * Track user and channel conversions
 */
function woosea_track_conversion () {

	$save_conversion = "no";

	// First check if adTribesID cookie is active
	if(isset($_COOKIE['adTribesID'])) {
		$adTribesID = $_COOKIE['adTribesID'];
		$utm_source = ""; // we did not save the utm values in cookies
		$utm_campaign = "";
		$utm_medium = "";
		$utm_term = "";
		$save_conversion = "yes";
	// Or conversion is trackes in session
	} elseif(!empty($_POST['adTribesID'])) {
		$adTribesID = sanitize_text_field($_POST['adTribesID']);
		$utm_source = sanitize_text_field($_POST['utm_source']);
		$utm_campaign = sanitize_text_field($_POST['utm_campaign']);
		$utm_medium = sanitize_text_field($_POST['utm_medium']);
		$utm_term = sanitize_text_field($_POST['utm_term']);
		$save_conversion = "yes";
	}
	
	if($save_conversion == "yes"){
		list($project_hash, $plugin, $productId) = explode('|', $adTribesID);
		
		if((!empty($productId)) AND ($productId > 0)){
			$conversion_timestamp = date("j-n-Y G:i:s");

			// Insert the conversion data into the MySql conversion table
			global $wpdb;
       	 		$charset_collate = $wpdb->get_charset_collate();
        		$table_name = $wpdb->prefix . 'adtribes_my_conversions';

			// Get the last order ID
        		$orderId = get_option( 'last_order_id' );
			$inserted_id = 0;
			$success = "no";

			// Check if order was not inserted before inserting it.
			$orderId_check = $wpdb->get_results("SELECT * FROM $table_name WHERE orderId = '".$orderId."'");

			if($wpdb->num_rows == 0){
				$wpdb->insert($table_name, 
						array(
							'conversion_time' => current_time('mysql' , 1),
							'project_hash' => $project_hash,
							'utm_source' => $utm_source,
							'utm_campaign' => $utm_campaign,
							'utm_medium' => $utm_medium,
							'utm_term' => $utm_term,
							'productId' => $productId,
							'orderId' => $orderId		
						),
						array(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d'
						)
				);

				$inserted_id = $wpdb->insert_id;
			}
	
			if($inserted_id > 0){
				$success = "yes";
			}

			$data = array (
				"conversion_saved" => $success,
			);

		        $data = json_encode($data);
		        echo $data;
		        wp_die();
		}
	}
}
add_action( 'wp_ajax_woosea_track_conversion','woosea_track_conversion');
add_action( 'wp_ajax_nopriv_woosea_track_conversion','woosea_track_conversion');

/**
 * Set tracking cookies
 */
function woosea_set_cookie () {

	if(!empty($_POST['adTribesID'])) {
		$adTribesID = sanitize_text_field($_POST['adTribesID']);

		// Conversion cookie will expire in 30 days from now. Make this configurable later.
		$number_of_days = 30;
		$date_of_expiry = time() + 60 * 60 *24 * $number_of_days;
		setcookie('adTribesID', $adTribesID, $date_of_expiry);

		$success = "yes";
		$data = array (
			"cookie_set" => $success,
		);

	        $data = json_encode($data);
	        echo $data;
	        wp_die();
	}
}
add_action( 'wp_ajax_woosea_set_cookie','woosea_set_cookie');
add_action( 'wp_ajax_nopriv_woosea_set_cookie','woosea_set_cookie');

/**
 * Process next batch for product feed
 */
function woosea_continue_batch($project_hash){
	$batch_project = "batch_project_".$project_hash;
	$val = get_option( $batch_project );

	if (!empty($val)){
		$line = new WooSEA_Get_Products;
       		$final_creation = $line->woosea_get_products( $val );
        	$last_updated = woosea_last_updated( $val['project_hash'] );

		// Clean up the single event project configuration
		unset($line);
		unset($final_creation);
		unset($last_updated);
	}
}
add_action( 'woosea_create_batch_event','woosea_continue_batch', 1, 1);

/**
 * Function with initialisation of class for managing existing feeds
 */
function woosea_manage_feed(){
	$html = new Construct_Admin_Pages();
	$html->set_page("woosea-manage-feed");
	echo $html->get_page();
}

/**
 * Function with initialisation of class for managing plugin settings
 */
function woosea_manage_settings(){
	$html = new Construct_Admin_Pages();
	$html->set_page("woosea-manage-settings");
	echo $html->get_page();
}

/**
 * Function with initialisation of class for the upgrade to Elite page
 */
function woosea_upgrade_elite(){
	$html = new Construct_Admin_Pages();
	$html->set_page("woosea-upgrade-elite");
	echo $html->get_page();
}

/**
 * Function for emptying all projects in cron at once
 * Kill-switch for all configured projects, be carefull!
 */
function woosea_clear(){
	$html = new Construct_Admin_Pages();
	$html->set_page("woosea-clear");
	delete_option( 'cron_projects' );
	echo $html->get_page();
}

/**
 * Add plugin links to Wordpress menu
 */
add_action( 'admin_menu' , 'woosea_menu_addition' );
?>
