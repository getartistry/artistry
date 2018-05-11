<?php
$domain = $_SERVER['HTTP_HOST'];
$plugin_settings = get_option( 'plugin_settings' );
$license_information = get_option( 'license_information' );
$error = "false";
$disabled = "";
$plugin_data = get_plugin_data( __FILE__ );

$versions = array (
	"PHP" => (float)phpversion(),
	"Wordpress" => get_bloginfo('version'),
	"WooCommerce" => WC()->version,
	"WooCommerce Product Feed PRO" => WOOCOMMERCESEA_PLUGIN_VERSION
);

/**
 * Change default footer text, asking to review our plugin
 **/
function my_footer_text($default) {
    return 'If you like our <strong>WooCommerce Product Feed PRO</strong> plugin please leave us a <a href="https://wordpress.org/support/plugin/woo-product-feed-pro/reviews?rate=5#new-post" target="_blank" class="woo-product-feed-pro-ratingRequest">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Thanks in advance!';
}
add_filter('admin_footer_text', 'my_footer_text');

/**
 * Create notification object and get message and message type as WooCommerce is inactive
 * also set variable allowed on 0 to disable submit button on step 1 of configuration
 */
$notifications_obj = new WooSEA_Get_Admin_Notifications;
if (!in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $notifications_box = $notifications_obj->get_admin_notifications ( '9', 'false' );
} else {
        $notifications_box = $notifications_obj->get_admin_notifications ( '14', 'false' );
}

if ($versions['PHP'] < 5.6){
        $notifications_box = $notifications_obj->get_admin_notifications ( '11', 'false' );
}

if ($versions['WooCommerce'] < 3){
        $notifications_box = $notifications_obj->get_admin_notifications ( '13', 'false' );
}

if (!wp_next_scheduled( 'woosea_cron_hook' ) ) {
	$notifications_box = $notifications_obj->get_admin_notifications ( '12', 'false' );
}
?>

<div id="dialog" title="Basic dialog">
	<p>
     		<div id="dialogText"></div>
      	</p>
</div>

<div class="wrap">
        <div class="woo-product-feed-pro-form-style-2">
                <tbody class="woo-product-feed-pro-body">
                        <div class="woo-product-feed-pro-form-style-2-heading">Plugin settings</div>
                        <div class="<?php _e($notifications_box['message_type']); ?>">
                                <p><?php _e($notifications_box['message'], 'sample-text-domain' ); ?></p>
                        </div>
	
			<div class="woo-product-feed-pro-table-wrapper">
				<div class="woo-product-feed-pro-table-left">
			       		<table class="woo-product-feed-pro-table">
						<tr>
						</tr>
	
						<form action="" method="post">
						<tr>
							<td>
								<span>Grant access to AdTribes.io support:</span>
							</td>
							<td>
                                                		<label class="woo-product-feed-pro-switch">
                                                        	<?php
								$woosea_support_user = get_option('woosea_support_user');
							 	if($woosea_support_user == "yes"){
                                                                	print "<input type=\"checkbox\" id=\"grant_access\" name=\"grant_access\" class=\"checkbox-field\" checked>";
                                                        	} else {
                                                                	print "<input type=\"checkbox\" id=\"grant_access\" name=\"grant_access\" class=\"checkbox-field\">";
                                                        	}
                                                        	?>
                                                        	<div class="woo-product-feed-pro-slider round"></div>
                                                		</label>
							</td>
						</tr>

						<tr>
							<td>
								<span>Increase the number of products that will be approved in Google's Merchant Center:<br/>
								This option will fix WooCommerce's (JSON-LD) structured data bug and add extra structured data elements to your pages (<a href="https://adtribes.io/woocommerce-structured-data-bug/" target="_blank">Read more about this)</a></span>
								<?php	
								if ($license_information['license_valid'] == "false"){
									print "<span><a href=\"https://adtribes.io/pro-vs-elite/?utm_source=$domain&utm_medium=plugin&utm_campaign=upgrade-elite\" target=\"_blank\">Upgrade to Elite</a> to activate this feature</span>";
									$disabled = "disabled";
								}	
								?>
							</td>
							<td>
                                                		<label class="woo-product-feed-pro-switch">
								<?php
								$structured_data_fix = get_option ('structured_data_fix');
                                                        	if($structured_data_fix == "yes"){
                                                                	print "<input type=\"checkbox\" id=\"fix_json_ld\" name=\"fix_json_ld\" class=\"checkbox-field\" checked $disabled>";
                                                        	} else {
                                                                	print "<input type=\"checkbox\" id=\"fix_json_ld\" name=\"fix_json_ld\" class=\"checkbox-field\" $disabled>";
                                                        	}
                                                        	?>
                                                        	<div class="woo-product-feed-pro-slider round"></div>
                                                		</label>
							</td>
						</tr>

						<tr>
							<td>
								<span>Add GTIN, MPN, UPC, EAN, Product condition, Optimised title and Brand attributes to your store: (<a href="https://adtribes.io/add-gtin-mpn-upc-ean-product-condition-optimised-title-and-brand-attributes/" target="_blank">Read more about this)</a></span>
								<?php	
								if ($license_information['license_valid'] == "false"){
									print "<span><a href=\"https://adtribes.io/pro-vs-elite/?utm_source=$domain&utm_medium=plugin&utm_campaign=upgrade-elite\" target=\"_blank\">Upgrade to Elite</a> to activate this feature</span>";
								}	
								?>
							</td>
							<td>
                                                		<label class="woo-product-feed-pro-switch">
                                                        	<?php
								$add_unique_identifiers = get_option ('add_unique_identifiers');
                                                        	if($add_unique_identifiers == "yes"){
                                                                	print "<input type=\"checkbox\" id=\"add_identifiers\" name=\"add_identifiers\" class=\"checkbox-field\" checked $disabled>";
                                                        	} else {
                                                                	print "<input type=\"checkbox\" id=\"add_identifiers\" name=\"add_identifiers\" class=\"checkbox-field\" $disabled>";
                                                        	}
                                                        	?>
                                                        	<div class="woo-product-feed-pro-slider round"></div>
                                                		</label>
							</td>
						</tr>
						</form>
					</table>
				</div>

				<div class="woo-product-feed-pro-table-right">
				

                                <table class="woo-product-feed-pro-table">
                                        <tr>
                                                <td><strong>We’ve got you covered!</strong></td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        Need assistance? Check out our:
                                                        <ul>
                                                                <li><strong><a href="https://adtribes.io/support/" target="_blank">Frequently Asked Questions</a></strong></li>
                                                                <li><strong><a href="https://www.youtube.com/channel/UCXp1NsK-G_w0XzkfHW-NZCw" target="_blank">YouTube tutorials</a></strong></li>
                                                                <li><strong><a href="https://adtribes.io/blog/" target="_blank">Blog</a></strong></li>
                                                                <li><strong><a href="https://adtribes.io/pro-vs-elite/?utm_source=$domain&utm_medium=plugin&utm_campaign=upgrade-elite" target="_blank">Upgrade to Elite</a></strong></li>
                                                        </ul>
                                                        Or just reach out to us at  <strong><a href="https://wordpress.org/support/plugin/woo-product-feed-pro/" target="_blank">the support forum</a></strong> and we'll make sure your product feeds will be up-and-running within no-time.
                                                </td>
                                        </tr>
                                </table><br/>

                                <table class="woo-product-feed-pro-table">
                                        <tr>
                                                <td><strong>Why upgrade to Elite?</strong></td>
                                        </tr>
                                        <tr>
                                                <td>
							Enjoy all priviliges of our Elite features and priority support and upgrade to the Elite version of our plugin now!
                                                        <ul>
                                                                <li><strong>1.</strong> More products approved by Google </li>
                                                                <li><strong>2.</strong> Add GTIN, brand and more fields to your store</li>
                                                                <li><strong>3.</strong> Exclude individual products from your feeds</li>
                                                                <li><strong>4.</strong> Priority support</li>
							</ul>
							<strong>
							<a href="https://adtribes.io/pro-vs-elite/?utm_source=$domain&utm_medium=plugin&utm_campaign=upgrade-elite" target="_blank">Upgrade to Elite here!</a>
                                                	</strong>
						</td>
                                        </tr>
                                </table><br/>


                                <table class="woo-product-feed-pro-table">
                                        <tr>
                                                <td><strong>Our latest blog articles</strong></td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <ul>
                                                                <li><strong>1. <a href="https://adtribes.io/add-gtin-mpn-upc-ean-product-condition-optimised-title-and-brand-attributes/" target="_blank">Adding GTIN, Brand, MPN and more</a></strong></li>
                                                                <li><strong>2. <a href="https://adtribes.io/woocommerce-structured-data-bug/" target="_blank">WooCommerce structured data markup bug</a></strong></li>
                                                                <li><strong>3. <a href="https://adtribes.io/how-to-create-filters-for-your-product-feed/" target="_blank">How to create filters for your product feed</a></strong></li>
                                                        </ul>
                                                </td>
                                        </tr>
                                </table><br/>


				</div>
			</div>
		</tbody>
	</div>
</div>
