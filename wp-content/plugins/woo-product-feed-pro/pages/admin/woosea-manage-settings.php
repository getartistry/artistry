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

if($license_information['notice'] == "true"){
	$notifications_box['message_type'] = $license_information['message_type'];
	$notifications_box['message'] = $license_information['message'];
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
								<span>Increase the number of products that will be approved in Google's Merchant Center:<br/>
								This option will fix WooCommerce's (JSON-LD) structured data bug and add extra structured data elements to your pages (<a href="https://adtribes.io/woocommerce-structured-data-bug/" target="_blank">Read more about this)</a></span>
							</td>
							<td>
                                                		<label class="woo-product-feed-pro-switch">
								<?php
								$structured_data_fix = get_option ('structured_data_fix');
 	                                                       	if($structured_data_fix == "yes"){
									if($license_information['license_valid'] == "true"){
                                                                		print "<input type=\"checkbox\" id=\"fix_json_ld\" name=\"fix_json_ld\" class=\"checkbox-field\" checked $disabled>";
									} else {							
                                                                		print "<input type=\"checkbox\" id=\"fix_json_ld\" name=\"fix_json_ld\" class=\"checkbox-field\" disabled>";
                                                       			}
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
								<span>Add GTIN, MPN, UPC, EAN, Product condition, Optimised title, Installment, Unit measure and Brand attributes to your store: (<a href="https://adtribes.io/add-gtin-mpn-upc-ean-product-condition-optimised-title-and-brand-attributes/" target="_blank">Read more about this)</a></span>
							</td>
							<td>
                                                		<label class="woo-product-feed-pro-switch">
                                                        	<?php
								$add_unique_identifiers = get_option ('add_unique_identifiers');
                                                        	if($add_unique_identifiers == "yes"){
									if($license_information['license_valid'] == "true"){
                                                                		print "<input type=\"checkbox\" id=\"add_identifiers\" name=\"add_identifiers\" class=\"checkbox-field\" checked $disabled>";
                                                       			} else {
                                                                		print "<input type=\"checkbox\" id=\"add_identifiers\" name=\"add_identifiers\" class=\"checkbox-field\" disabled>";
									}
							 	} else {
                                                                	print "<input type=\"checkbox\" id=\"add_identifiers\" name=\"add_identifiers\" class=\"checkbox-field\" $disabled>";
                                                        	}
                                                        	?>
                                                        	<div class="woo-product-feed-pro-slider round"></div>
                                                		</label>
							</td>
						</tr>
						<tr>
							<td>
								<span>Enable WPML support: (<a href="https://adtribes.io/wpml-support/" target="_blank">Read more about this)</a></span>
							</td>
							<td>
                                                		<label class="woo-product-feed-pro-switch">
                                                        	<?php
								$add_wpml_support = get_option ('add_wpml_support');
                                                        	if($add_wpml_support == "yes"){
									if($license_information['license_valid'] == "true"){
                                                                		print "<input type=\"checkbox\" id=\"add_wpml_support\" name=\"add_wpml_support\" class=\"checkbox-field\" checked $disabled>";
                                                       			} else {
                                                                		print "<input type=\"checkbox\" id=\"add_wpml_support\" name=\"add_wpml_support\" class=\"checkbox-field\" disabled>";
									}
							 	} else {
                                                                	print "<input type=\"checkbox\" id=\"add_wpml_support\" name=\"add_wpml_support\" class=\"checkbox-field\" $disabled>";
                                                        	}
                                                        	?>
                                                        	<div class="woo-product-feed-pro-slider round"></div>
                                                		</label>
							</td>
						</tr>

						<tr id="remarketing">
							<td>
								<span>Enable Google Dynamic Remarketing:</span>
							</td>
							<td>
                                                		<label class="woo-product-feed-pro-switch">
                                                        	<?php
								$add_remarketing = get_option ('add_remarketing');
                                                        	if($add_remarketing == "yes"){
                                                                	print "<input type=\"checkbox\" id=\"add_remarketing\" name=\"add_remarketing\" class=\"checkbox-field\" checked>";
							 	} else {
                                                                	print "<input type=\"checkbox\" id=\"add_remarketing\" name=\"add_remarketing\" class=\"checkbox-field\">";
                                                        	}
                                                        	?>
                                                        	<div class="woo-product-feed-pro-slider round"></div>
                                                		</label>
							</td>
						</tr>
						<?php
                                                if($add_remarketing == "yes"){
							$adwords_conversion_id = get_option('woosea_adwords_conversion_id');

							print "<tr id=\"adwords_conversion_id\"><td colspan=\"2\"><span>Insert your Dynamic Remarketing Conversion tracking ID:</span>&nbsp;<input type=\"text\" class=\"input-field-medium\" id=\"adwords_conv_id\" name=\"adwords_conv_id\" value=\"$adwords_conversion_id\">&nbsp;<input type=\"submit\" id=\"save_conversion_id\" value=\"Save\"></td></tr>";	
						}
						?>
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
                                                                <li><strong>1.</strong> Priority support: get your feeds live faster</li>
                                                                <li><strong>2.</strong> More products approved by Google</li>
                                                                <li><strong>3.</strong> Add GTIN, brand and more fields to your store</li>
                                                                <li><strong>4.</strong> Exclude individual products from your feeds</li>
                                                                <li><strong>5.</strong> WPML support</li>
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
                                                                <li><strong>4. <a href="https://adtribes.io/wpml-support/" target="_blank">Enable WPML support</a></strong></li>
                                                        </ul>
                                                </td>
                                        </tr>
                                </table><br/>


				</div>
			</div>
		</tbody>
	</div>
</div>
