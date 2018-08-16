<?php
/**
 * Plugin Name: WooCommerce Product Feed PRO 
 * Version:     3.4.8
 * Plugin URI:  https://www.adtribes.io/support/?utm_source=wpadmin&utm_medium=plugin&utm_campaign=woosea_product_feed_pro
 * Description: Configure and maintain your WooCommerce product feeds for Google Shopping, Facebook, Remarketing, Bing, Yandex, Comparison shopping websites and over a 100 channels more.
 * Author:      AdTribes.io
 * Plugin URI:  https://wwww.adtribes.io/pro-vs-elite/
 * Author URI:  https://www.adtribes.io
 * Developer:   Joris Verwater, Eva van Gelooven
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wporg
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 3.4
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
define( 'WOOCOMMERCESEA_PLUGIN_VERSION', '3.4.8' );
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

	// JS for managing keys
	// wp_register_script( 'woosea_key-js', plugin_dir_url( __FILE__ ) . 'js/woosea_key.js', '',WOOCOMMERCESEA_PLUGIN_VERSION, true  );
	// wp_enqueue_script( 'woosea_key-js' );
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
require plugin_dir_path(__FILE__) . 'classes/class-google-remarketing.php';

/**
 * Add links to the plugin page
 */
function woosea_plugin_action_links($links, $file) {
	static $this_plugin;
 
    	if (!$this_plugin) {
        	$this_plugin = plugin_basename(__FILE__);
    	}
 
    	// check to make sure we are on the correct plugin
    	if ($file == $this_plugin) {
 
		// link to what ever you want
        	$plugin_links[] = '<a href="https://adtribes.io/support/" target="_blank">Support</a>';
        	$plugin_links[] = '<a href="https://adtribes.io/blog/" target="_blank">Blog</a>';
        	//$plugin_links[] = '<a href="https://adtribes.io/pro-vs-elite/?utm_source=adminpage&utm_medium=pluginpage&utm_campaign=upgrade-elite" target="_blank">Upgrade to Elite</a>';
 
        	// add the links to the list of links already there
		foreach($plugin_links as $link) {
			array_unshift($links, $link);
		}
    	}
    	return $links;
}
add_filter('plugin_action_links', 'woosea_plugin_action_links', 10, 2);

/**
 * Add Google Adwords Remarketing code to footer
 */
function woosea_add_remarketing_tags( $product = null ){
        if ( ! is_object( $product ) ) {
                global $product;
        }
	$ecomm_pagetype = WooSEA_Google_Remarketing::woosea_google_remarketing_pagetype();
   	$add_remarketing = get_option ('add_remarketing');
      
	if($add_remarketing == "yes"){	
        	$adwords_conversion_id = get_option("woosea_adwords_conversion_id");

		if($adwords_conversion_id > 0){
			if ($ecomm_pagetype == "product"){
                		if ( '' !== $product->get_price()) {
                 		$ecomm_prodid = get_the_id();

				if(!empty($ecomm_prodid)){
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
							} else {
								// AggregateOffer
       	       					 	   	$prices  = $product->get_variation_prices();
       	       		 			     		$lowest  = reset( $prices['price'] );
               	        				 	$highest = end( $prices['price'] );

                 			  		     	if ( $lowest === $highest ) {
                        			     	  		$ecomm_price = wc_format_decimal( $lowest, wc_get_price_decimals() );
                               				 	} else {
                        			       	 		$ecomm_lowprice  = wc_format_decimal( $lowest, wc_get_price_decimals() );
                        				        	$ecomm_highprice = wc_format_decimal( $highest, wc_get_price_decimals() );
								}
							}
						} else {
							// When there are no parameters in the URL (so for normal users, not coming via Google Shopping URL's) show the old WooCommwerce JSON
                       		 			$prices  = $product->get_variation_prices();
                      	 				$lowest  = reset( $prices['price'] );
                      					$highest = end( $prices['price'] );

         	            	 		 	if ( $lowest === $highest ) {
                	       		 			$ecomm_price = wc_format_decimal( $lowest, wc_get_price_decimals());
                     		 			} else {
                       		 				$ecomm_lowprice = wc_format_decimal( $lowest, wc_get_price_decimals() );
                        			       	 	$ecomm_highprice = wc_format_decimal( $highest, wc_get_price_decimals() );
							}
						}
					} else {
        					$ecomm_price = wc_format_decimal( $product->get_price(), wc_get_price_decimals() );
      					}
				}
		
				?>
				<script type="text/javascript">
				var google_tag_params = {
				ecomm_prodid: '<?php print "$ecomm_prodid";?>',
				ecomm_pagetype: '<?php print "$ecomm_pagetype";?>',
				ecomm_totalvalue: <?php print "$ecomm_price";?>,
				};
				</script>
		
			<?php
			}
		} elseif ($ecomm_pagetype == "cart"){
				$ecomm_prodid = get_the_id();

				?>
				<script type="text/javascript">
				var google_tag_params = {
				ecomm_prodid: '<?php print "$ecomm_prodid";?>',
				ecomm_pagetype: '<?php print "$ecomm_pagetype";?>',
				};
				</script>
				<?php
		} else {
			// This is another page than a product page
			?>
               		<script type="text/javascript">
     	          	var google_tag_params = {
     	          	ecomm_pagetype: '<?php print "$ecomm_pagetype";?>',
    	           	};
        	       	</script>
			<?php
		}
		?>
			<!-- Google-code – remarketing tag added by AdTribes.io -->
			<!--------------------------------------------------
			You need to make sure that the ecomm_prodid parameter, which we fill with your
			WooCommerce product Id matches the g:id field for your Google Merchant Center feed. 
			--------------------------------------------------->
			<script type="text/javascript">
			/* <![CDATA[ */
			var google_conversion_id = <?php print "$adwords_conversion_id";?>;
			var google_custom_params = window.google_tag_params;
			var google_remarketing_only = true;
			/* ]]> */
			</script>
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
			</script>
			<noscript>
			<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/<?php print "$adwords_conversion_id";?>/?guid=ON&amp;script=0"/>
			</div>
			</noscript>
			<?php
		}
	}
}
add_action('wp_footer', 'woosea_add_remarketing_tags');

/**
 * Hook and function that will run during plugin uninstall.
 */
function uninstall_woosea_feed(){
	require plugin_dir_path(__FILE__) . 'classes/class-uninstall-cleanup.php';
    	WooSEA_Uninstall_Cleanup::uninstall_cleanup();
}
register_uninstall_hook(__FILE__, 'uninstall_woosea_feed');

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
function woosea_license_notice(){
        $license_information = get_option( 'license_information' );
        $license_notification = get_option( 'woosea_license_notification_closed' );
        $screen = get_current_screen();

        if($screen->id <> 'product-feed-elite_page_woosea_upgrade_elite'){
                if((isset($license_information['notice'])) and ($license_information['notice'] == "true") and ($license_notification <> 'yes')){
                ?>
                        <div class="<?php print "$license_information[message_type]"; ?> license_notification">
                                <p><?php _e( $license_information['message'], 'sample-text-domain' ); ?></p>
                        </div>
                <?php
                }
        }
}
add_action('admin_notices', 'woosea_license_notice');


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
//            add_submenu_page(__FILE__, __('Upgrade to Elite', 'woosea-elite-feed'), __('Upgrade to Elite', 'woosea-elite-feed'), 'manage_options', 'woosea_key', 'woosea_upgrade_elite');
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
 * Get a list of categories for the drop-down 
 */
function woosea_categories_dropdown() {
	$rowCount = sanitize_text_field($_POST['rowCount']);

	// Check if WPML is active, switch language?
//        if(isset($project['WPML'])){
//                if ( function_exists('icl_object_id') ) {
//                        // Get WPML language
//                        global $sitepress;
//                        $lang = $project['WPML'];
//                        $sitepress->switch_lang($lang);
//                }
//        }

	$orderby = 'name';
	$order = 'asc';
	$hide_empty = false ;
	$cat_args = array(
    		'orderby'    => $orderby,
    		'order'      => $order,
    		'hide_empty' => $hide_empty,
	);

	$categories_dropdown = "<select name=\"rules[$rowCount][criteria]\">";
	$product_categories = get_terms( 'product_cat', $cat_args );
	foreach ($product_categories as $key => $category) {
		$categories_dropdown .= "<option value=\"$category->name\">$category->name</option>";	

	}
	$categories_dropdown .= "</select>";

	$data = array (
		'rowCount' => $rowCount,
		'dropdown' => $categories_dropdown
	);
	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_categories_dropdown', 'woosea_categories_dropdown' );

/**
 * Save Google Dynamic Remarketing Conversion Tracking ID
 */
function woosea_save_adwords_conversion_id() {
	$adwords_conversion_id = sanitize_text_field($_POST['adwords_conversion_id']);
	update_option("woosea_adwords_conversion_id", $adwords_conversion_id);
}
add_action( 'wp_ajax_woosea_save_adwords_conversion_id', 'woosea_save_adwords_conversion_id' );


/**
 * Map categories to the correct Google Shopping category taxonomy
 */
function woosea_add_cat_mapping() {
	$rowCount = sanitize_text_field($_POST['rowCount']);
	$className = sanitize_text_field($_POST['className']);
	$map_to_category = sanitize_text_field($_POST['map_to_category']);
	$project_hash = sanitize_text_field($_POST['project_hash']);
	//$criteria = sanitize_text_field($_POST['criteria']);

	$criteria = $_POST['criteria'];
	$status_mapping = "false";
	$project = WooSEA_Update_Project::get_project_data(sanitize_text_field($project_hash));	

	// This is during the configuration of a new feed
	if(empty($project)){
		$project_temp = get_option( 'channel_project' );
		$project['mappings'][$rowCount]['rowCount'] = $rowCount;
		$project['mappings'][$rowCount]['categoryId'] = $rowCount;
		$project['mappings'][$rowCount]['criteria'] = $criteria;
		$project['mappings'][$rowCount]['map_to_category'] = $map_to_category;
                $project_fill = array_merge($project_temp, $project);
                update_option( 'channel_project',$project_fill,'','yes');
		$status_mapping = "true";
		// This is updating an existing product feed
	} else {
		$project['mappings'][$rowCount]['rowCount'] = $rowCount;
		$project['mappings'][$rowCount]['categoryId'] = $rowCount;
		$project['mappings'][$rowCount]['criteria'] = $criteria;
		$project['mappings'][$rowCount]['map_to_category'] = $map_to_category;

		$project_updated = WooSEA_Update_Project::update_project_data($project);	
		$status_mapping = "true";
	}

	$data = array (
		'rowCount' 		=> $rowCount,
		'className'		=> $className,
		'map_to_category' 	=> $map_to_category,
		'status_mapping' 	=> $status_mapping,
	);

	echo json_encode($data);
	wp_die();
}
add_action( 'wp_ajax_woosea_add_cat_mapping', 'woosea_add_cat_mapping' );

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
        $notice = sanitize_text_field($_POST['notice']);

        $license_information = array (
                'license_valid'         => $license_valid,
                'license_created'       => $license_created,
                'message'               => $message,
                'message_type'          => $message_type,
                'license_email'         => $license_email,
                'license_key'           => $license_key,
                'notice'                => $notice,
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
					        'priceCurrency' => $shop_currency,
                                        );


                                } else {
		                        $markup_offer = array(
                                                 '@type'     => 'AggregateOffer',
                                                'lowPrice'  => wc_format_decimal( $lowest, wc_get_price_decimals() ),
                                                'highPrice' => wc_format_decimal( $highest, wc_get_price_decimals() ),
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
                                	'availability'  => 'https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
                               		 'seller'        => array(
                                        	'@type' => 'Organization',
                                        	'name'  => $shop_name,
                                        	'url'   => $shop_url,
                                	),
				);
                        }
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

	$number_feeds = count($feed_config);

	if ($number_feeds > 0){

	        foreach ( $feed_config as $key => $val ) {
        	        if ($val['project_hash'] == $project_hash){
                	        $feed_config[$key]['active'] = $active;
               	 		$upload_dir = wp_upload_dir();
                		$base = $upload_dir['basedir'];
                		$path = $base . "/woo-product-feed-pro/" . $val['fileformat'];
                		$file = $path . "/" . sanitize_file_name($val['filename']) . "." . $val['fileformat'];
                	}
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
 * WPML support 
 */
function woosea_add_wpml (){
        $status = sanitize_text_field($_POST['status']);

	if ($status == "off"){
		update_option( 'add_wpml_support', 'no', 'yes');
	} else {
		update_option( 'add_wpml_support', 'yes', 'yes');
	}
}
add_action( 'wp_ajax_woosea_add_wpml', 'woosea_add_wpml' );

/**
 * This function enables the setting to add 
 * Google's Dynamic Remarketing 
 */
function woosea_add_remarketing (){
        $status = sanitize_text_field($_POST['status']);

	if ($status == "off"){
		update_option( 'add_remarketing', 'no', 'yes');
	} else {
		update_option( 'add_remarketing', 'yes', 'yes');
	}
}
add_action( 'wp_ajax_woosea_add_remarketing', 'woosea_add_remarketing' );

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

        	// Unit pricing measure Field
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_unit_pricing_measure',
                        	'label'       => __( 'Unit pricing measure', 'woocommerce' ),
                       	 	'desc_tip'    => 'true',
                        	'description' => __( 'Enter an unit pricing measure.', 'woocommerce' ),
                	)
        	);

        	// Unit pricing base measure Field
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_unit_pricing_base_measure',
                        	'label'       => __( 'Unit pricing base measure', 'woocommerce' ),
                       	 	'desc_tip'    => 'true',
                        	'description' => __( 'Enter an unit pricing base measure.', 'woocommerce' ),
                	)
        	);

        	// Installment months
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_installment_months',
                        	'label'       => __( 'Installment months', 'woocommerce' ),
                       	 	'desc_tip'    => 'true',
                        	'description' => __( 'Enter the number of monthly installments the buyer has to pay.', 'woocommerce' ),
                	)
        	);

        	// Installment amount
        	woocommerce_wp_text_input(
                	array(
                        	'id'          => '_woosea_installment_amount',
                        	'label'       => __( 'Installment amount', 'woocommerce' ),
                       	 	'desc_tip'    => 'true',
                        	'description' => __( 'Enter the amount the nuyer has to pay per month.', 'woocommerce' ),
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

        $woocommerce_brand      		= sanitize_text_field($_POST['_woosea_brand']);
        $woocommerce_gtin       		= sanitize_text_field($_POST['_woosea_gtin']);
        $woocommerce_upc        		= sanitize_text_field($_POST['_woosea_upc']);
        $woocommerce_mpn        		= sanitize_text_field($_POST['_woosea_mpn']);
        $woocommerce_ean        		= sanitize_text_field($_POST['_woosea_ean']);
        $woocommerce_title      		= sanitize_text_field($_POST['_woosea_optimized_title']);
        $woocommerce_unit_pricing_measure 	= sanitize_text_field($_POST['_woosea_unit_pricing_measure']);
        $woocommerce_unit_pricing_base_measure 	= sanitize_text_field($_POST['_woosea_unit_pricing_base_measure']);
        $woocommerce_installment_months      	= sanitize_text_field($_POST['_woosea_installment_months']);
        $woocommerce_installment_amount      	= sanitize_text_field($_POST['_woosea_installment_amount']);
        $woocommerce_condition      		= sanitize_text_field($_POST['_woosea_condition']);
	if(!empty($_POST['_woosea_exclude_product'])){
		$woocommerce_exclude_product 		= sanitize_text_field($_POST['_woosea_exclude_product']);
	} else {
		$woocommerce_exclude_product 		= "no";;
	}

        if(isset($woocommerce_brand))
                update_post_meta( $post_id, '_woosea_brand', $woocommerce_brand);

        if(isset($woocommerce_mpn))
                update_post_meta( $post_id, '_woosea_mpn', esc_attr($woocommerce_mpn));

        if(isset($woocommerce_upc))
                update_post_meta( $post_id, '_woosea_upc', esc_attr($woocommerce_upc));

        if(isset($woocommerce_ean))
                update_post_meta( $post_id, '_woosea_ean', esc_attr($woocommerce_ean));

        if(isset($woocommerce_gtin))
                update_post_meta( $post_id, '_woosea_gtin', esc_attr($woocommerce_gtin));

        if(isset($woocommerce_title))
                update_post_meta( $post_id, '_woosea_optimized_title', $woocommerce_title);

        if(isset($woocommerce_unit_pricing_measure))
                update_post_meta( $post_id, '_woosea_unit_pricing_measure', $woocommerce_unit_pricing_measure);
 
        if(isset($woocommerce_unit_pricing_base_measure))
                update_post_meta( $post_id, '_woosea_unit_pricing_base_measure', $woocommerce_unit_pricing_base_measure);
 
	if(isset($woocommerce_condition))
                update_post_meta( $post_id, '_woosea_condition', $woocommerce_condition);

	if(isset($woocommerce_installment_months))
                update_post_meta( $post_id, '_woosea_installment_months', esc_attr($woocommerce_installment_months));

	if(isset($woocommerce_installment_amount))
                update_post_meta( $post_id, '_woosea_installment_amount', esc_attr($woocommerce_installment_amount));

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

                // Variation Unit pricing measure field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_variable_unit_pricing_measure['.$loop.']',
                                'label'       => __( '<br>Unit pricing measure', 'woocommerce' ),
                                'placeholder' => 'Unit pricing measure',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the product Unit pricing measure here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_unit_pricing_measure', true),
                                'wrapper_class' => 'form-row-first',
                        )
                );

                // Variation Unit pricing base measure field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_variable_unit_pricing_base_measure['.$loop.']',
                                'label'       => __( '<br>Unit pricing base measure', 'woocommerce' ),
                                'placeholder' => 'Unit pricing base measure',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the product Unit pricing base measure here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_unit_pricing_base_measure', true),
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

                // Installment month field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_installment_months['.$loop.']',
                                'label'       => __( '<br>Installment months', 'woocommerce' ),
                                'placeholder' => 'Installment months',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the number of montly installments for the buyer here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_installment_months', true),
                                'wrapper_class' => 'form-row-last',
                        )
                );

                // Installment amount field
                woocommerce_wp_text_input(
                        array(
                                'id'          => '_woosea_installment_amount['.$loop.']',
                                'label'       => __( '<br>Installment amount', 'woocommerce' ),
                                'placeholder' => 'Installment amount',
                                'desc_tip'    => 'true',
                                'description' => __( 'Enter the installment amount here.', 'woocommerce' ),
                                'value'       => get_post_meta($variation->ID, '_woosea_installment_amount', true),
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
                                update_post_meta( $variation_id, '_woosea_brand', stripslashes( sanitize_text_field( $_brand[$i] )));
                        }


                // MPN Field
                $_mpn = $_POST['_woosea_variable_mpn'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_mpn[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_mpn', stripslashes( sanitize_text_field( $_mpn[$i] )));
                        }

                // UPC Field
                $_upc = $_POST['_woosea_variable_upc'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_upc[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_upc', stripslashes( sanitize_text_field( $_upc[$i] )));
                        }

                // EAN Field
                $_ean = $_POST['_woosea_variable_ean'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_ean[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_ean', stripslashes( sanitize_text_field( $_ean[$i] )));
                        }

                // GTIN Field
                $_gtin = $_POST['_woosea_variable_gtin'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_gtin[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_gtin', stripslashes( sanitize_text_field( $_gtin[$i] )));
                        }

                // Unit pricing measure Field
                $_pricing_measure = $_POST['_woosea_variable_unit_pricing_measure'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_pricing_measure[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_unit_pricing_measure', stripslashes( sanitize_text_field( $_pricing_measure[$i] )));
                        }

                // Unit pricing base measure Field
                $_pricing_base = $_POST['_woosea_variable_unit_pricing_base_measure'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_pricing_base[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_unit_pricing_base_measure', stripslashes( sanitize_text_field( $_pricing_base[$i] )));
                        }

		// Optimized title Field
                $_opttitle = $_POST['_woosea_optimized_title'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_opttitle[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_optimized_title', stripslashes( sanitize_text_field( $_opttitle[$i] )));
                        }

		// Installment months Field
                $_installment_months = $_POST['_woosea_installment_months'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_installment_months[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_installment_months', stripslashes( sanitize_text_field( $_installment_months[$i] )));
                        }

		// Installment amount Field
                $_installment_amount = $_POST['_woosea_installment_amount'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_installment_amount[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_installment_amount', stripslashes( sanitize_text_field( $_installment_amount[$i] )));
                        }

                // Product condition Field
                $_condition = $_POST['_woosea_condition'];
                        $variation_id = (int) $variable_post_id[$i];
                        if ( isset( $_condition[$i] ) ) {
                                update_post_meta( $variation_id, '_woosea_condition', stripslashes( sanitize_text_field( $_condition[$i] )));
                        }

                // Exclude product from feed
		if(empty($_POST['_woosea_exclude_product'])){
			$_excludeproduct[$i] = "no";
		} else {
			$_excludeproduct = $_POST['_woosea_exclude_product'];
        	} 
		   	$variation_id = (int) $variable_post_id[$i];
                	if ( isset( $_excludeproduct[$i] ) ) {
                     		update_post_meta( $variation_id, '_woosea_exclude_product', stripslashes( $_excludeproduct[$i]));
        		}
		}	
	}
}
add_action( 'woocommerce_save_product_variation', 'woosea_save_custom_variable_fields', 10, 1 );

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
        $url = "https://www.adtribes.io/check/license.php?key=$license_information[license_key]&email=$license_information[license_email]&domain=$domain&version=1.0.0";

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
                $license_information['license_key'] = $json_return['license_key'];
                $license_information['license_email'] = $json_return['license_email'];
                $license_information['notice'] = $json_return['notice'];

                update_option ('license_information', $license_information);
//                delete_option ('structured_data_fix');
//                delete_option ('add_unique_identifiers');
//		delete_option ('add_wpml_support');
        } else {
                $license_information['message'] = $json_return['message'];
                $license_information['message_type'] = $json_return['message_type'];
                $license_information['license_valid'] = "true";
                $license_information['notice'] = $json_return['notice'];

                update_option ('license_information', $license_information);
        }
}

/**
 * Function used by event scheduling to create feeds 
 * Feed can automatically be generated every hour, twicedaiy or once a day
 */
function woosea_create_all_feeds(){
	$feed_config = array();
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
	$html->set_page("woosea-key");
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

/**
 * Register all dashboard metaboxes
*/

function woosea_blog_widgets() {
	global $wp_meta_boxes;
	
	add_meta_box('woosea_rss_dashboard_widget', __('Latest Product Feed Pro blog posts', 'rc_mdm'), 'woosea_my_rss_box','dashboard','side','high');
	//wp_add_dashboard_widget('woosea_rss_dashboard_widget', __('Latest Product Feed Pro blog posts', 'rc_mdm'), 'woosea_my_rss_box');
}
add_action('wp_dashboard_setup', 'woosea_blog_widgets');

/**
 * Creates the RSS metabox
 */
function woosea_my_rss_box() {
	
	// Get RSS Feed(s)
	include_once(ABSPATH . WPINC . '/feed.php');
        $domain = $_SERVER['HTTP_HOST'];
	
	// My feeds list (add your own RSS feeds urls)
	$my_feeds = array( 
		'https://www.adtribes.io/feed/' 
	);
	
	// Loop through Feeds
	foreach ( $my_feeds as $feed) :
	
		// Get a SimplePie feed object from the specified feed source.
		$rss = fetch_feed( $feed );
		if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
		    // Figure out how many total items there are, and choose a limit 
		    $maxitems = $rss->get_item_quantity( 5 ); 
		
		    // Build an array of all the items, starting with element 0 (first element).
		    $rss_items = $rss->get_items( 0, $maxitems ); 
	
		    // Get RSS title
		    $rss_title = '<a href="'.$rss->get_permalink().'" target="_blank">'.strtoupper( $rss->get_title() ).'</a>'; 
		endif;
	
		// Display the container
		echo '<div class="rss-widget">';
		echo '<strong>'.$rss_title.'</strong>';
		echo '<hr style="border: 0; background-color: #DFDFDF; height: 1px;">';
		
		// Starts items listing within <ul> tag
		echo '<ul>';
		
		// Check items
		if ( $maxitems == 0 ) {
			echo '<li>'.__( 'No item', 'rc_mdm').'.</li>';
		} else {
			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $rss_items as $item ) :
				// Uncomment line below to display non human date
				//$item_date = $item->get_date( get_option('date_format').' @ '.get_option('time_format') );
				
				// Get human date (comment if you want to use non human date)
				$item_date = human_time_diff( $item->get_date('U'), current_time('timestamp')).' '.__( 'ago', 'rc_mdm' );
				
				// Start displaying item content within a <li> tag
				echo '<li>';
				// create item link
				echo '<a href="'.esc_url( $item->get_permalink() ).'?utm_source='.$domain.'&utm_medium=plugin&utm_campaign=dashboard-rss" title="'.$item_date.'" target="_blank">';
				// Get item title
				echo esc_html( $item->get_title() );
				echo '</a>';
				// Display date
				echo ' <span class="rss-date">'.$item_date.'</span><br />';
				// Get item content
				$content = $item->get_content();
				// Shorten content
				$content = wp_html_excerpt($content, 120) . ' [...]';
				// Display content
				echo $content;
				// End <li> tag
				echo '</li>';
			endforeach;
		}
		// End <ul> tag
		echo '</ul>';
		echo '<hr style="border: 0; background-color: #DFDFDF; height: 1px;">';
		echo '<a href="https://adtribes.io/blog/?utm_source='.$domain.'&utm_medium=plugin&utm_campaign=dashboard-rss" target="_blank">Read more like this on our blog</a>';

		echo '</div>';

	endforeach; // End foreach feed
}


?>
