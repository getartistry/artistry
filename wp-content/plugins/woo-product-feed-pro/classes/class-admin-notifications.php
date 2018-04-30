<?php
/**
 * Class holding the notification messages and type of notices
 * Returns the message and type of message (info, error, success)
 */
class WooSEA_Get_Admin_Notifications {

	public function __construct() {
		$this->notification_details = array();
	}

	public function woosea_debug_informations ($versions, $product_numbers, $order_rows, $cron_objects) {
                $upload_dir = wp_upload_dir();
		$filename = "debug";

                $base = $upload_dir['basedir'];
                $path = $base . "/woo-product-feed-pro/logs";
                $file = $path . "/". $filename ."." ."log";

		// Remove the previous file, preventing the file from becoming to big
		unlink($file);

                // External location for downloading the file   
                $external_base = $upload_dir['baseurl'];
                $external_path = $external_base . "/woo-product-feed-pro/logs";
                $external_file = $external_path . "/" . $filename ."." ."log";

                // Check if directory in uploads exists, if not create one      
                if ( ! file_exists( $path ) ) {
                        wp_mkdir_p( $path );
                }

                // Log timestamp
                $today = "\n";
                $today .= date("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
                $today .= "\n";

                $fp = fopen($file, 'a+');
                fwrite($fp, $today);
                fwrite($fp, print_r($versions, TRUE));
                fwrite($fp, print_r($product_numbers, TRUE));
                fwrite($fp, print_r($cron_objects, TRUE));
                fwrite($fp, print_r($order_rows, TRUE));
                fclose($fp);

		return $this->notification_details = $external_file;
	}
	
	public function get_admin_notifications ( $step, $error ) {
	
		switch($step){
			case 0:
				$message = "Please select the country and channel for which you would like to create a new product feed. The channel drop-down will populate with relevant country channels once you selected a country. Filling in a project name is mandatory.";		
				$message_type = "notice notice-info is-dismissible";
				break;
			case 1:
				$message = "Map your products or categories to the categories of your selected channel. For some channels adding their categorisation in the product feed is mandatory. Even when category mappings are not mandatory it is likely your products will get better visibility and higher conversions when mappings have been added.";		
				$message_type = "notice notice-info is-dismissible";
				break;
			case 2:
				$message = "Please drag and drop the attributes you want to be in your product feed from left to right.";		
				$message_type = "notice notice-info is-dismissible";
				break;
			case 3:
				$message = "Mapping your product categories to the channel categories will increase changes of getting all your products listed correctly, thus increase your conversion rates.";		
				$message_type = "notice notice-info is-dismissible";
				break;
			case 4:
				$message = "Create filter and rules so exactly the right products end up in your product feed. These filters and rules are only eligable for the current product feed you are configuring and will not be used for other feeds.<br/><br/><strong>Filters:</strong> Exclude or include products that meet certain conditions. [<strong><i><a href=\"https://adtribes.io/how-to-create-filters-for-your-product-feed/\" target=\"_blank\">Detailed information and filter examples</a></i></strong>]<br/><strong>Rules:</strong> Change attribute values based on other attribute values or conditions.<br/><br/>Order of execution: the filters and rules will be executed in the order of creation.";		
				$message_type = "notice notice-info is-dismissible";
				break;
			case 5:
				$message = "<strong>Conversion tracking:</strong><br/>Enable conversion tracking if you want the plugin to keep track of sales and conversions coming from your product feed. When enabled an AdTribesID parameter will be added to your landingpage URL's.<br/><br/><strong>Google Analytics UTM codes:</strong><br/>Adding Google Analytics UTM codes is not mandatory, it will however enable you to get detailed insights into how your products are performing in Google Analytics reporting and allow you to tweak and tune your campaign making it more profitable. We strongly advise you to add the Google Analytics tracking. When enabled the plugin will append the Google Analytics UTM parameters to your landingpage URL's.";
				$message_type = "notice notice-info is-dismissible";
				break;
			case 6:
				$message = "Your product feed is now being created, please be patient. Your feed details will be displayed when generation of the product feed has been finished.";
				$message_type = "notice notice-info is-dismissible";
				break;
			case 7:
				$message = "For the selected channel the attributes shown below are mandatory, please map them to your product attributes. We've already pre-filled a lot of mappings so all you have to do is check those and map the ones that are left blank or add new ones by hitting the 'Add field mapping' button.";
				$message_type = "notice notice-info is-dismissible";
				break;
			case 8:
				$message = "Manage your projects, such as the mappings and filter rules, below. Hit the refresh icon for the project to run with its new settings or just to refresh the product feed. When a project is being processed it is not possible to make changes to its configuration.";
				$message_type = "notice notice-info is-dismissible";
				break;
			case 9:
				$message = "You cannot create product feeds yet, please install WooCommerce first.";
				$message_type = "notice notice-error";
				break;
			case 10:
				$message = "The graph shows the amount of products in this product feed, measured after every scheduled and/or manually triggered refresh.";
				$message_type = "notice notice-info is-dismissible";
				break;
			case 11:
				$message = "You are running an old PHP version. This plugin might not work or be really slow. Please upgrade to PHP version 7.0 or newer.";
				$message_type = "notice notice-error is-dismissible";
				break;
			case 12:
				$message = "We are sorry but it seems you have disabled your WP CRON. This plugin creates product feeds in batches and needs the WP CRON to be active for doing so. Please enable the WP CRON in your wp-config.php file and re-activate this plugin before creating a product feed.";
				$message_type = "notice notice-error is-dismissible";
				break;
			case 13:
				$message = "We are sorry but it seems you are running an old version of WooCommerce. This plugin requires WooCommerce version 3.0 at least. Please upgrade to the latest version of WooCommerce before creating a product feed.";
				$message_type = "notice notice-error is-dismissible";
				break;
			case 14:
				$message = "Grant access to our customer service to help you with creating product feed configurations. Enabling this option will create a new WordPress user. Credentials will automatically be send to AdTribes.io support team. By disabling this option the Wordpress user will be deleted.<br/><br/><strong>Please note:<br/></strong>The structured data and adding of unique identifier features will soon become part of the Elite version of this plugin. Please be aware that when we launch the paid version these features will be disabled unless you upgrade to the paid plan.";
				$message_type = "notice notice-info is-dismissible";
				break;
		}
		
		$this->notification_details['message'] = $message;
		$this->notification_details['message_type'] = $message_type;
		return $this->notification_details;
	}
}
