<?php
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
$versions = array (
        "PHP" => (float)phpversion(),
        "Wordpress" => get_bloginfo('version'),
	"WooCommerce" => WC()->version,
        "WooCommerce Product Feed PRO" => WOOCOMMERCESEA_PLUGIN_VERSION
);

$license_information = get_option( 'license_information' );

$notifications_obj = new WooSEA_Get_Admin_Notifications;
if (!in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $notifications_box = $notifications_obj->get_admin_notifications ( "9", "false" );
	$locale = "NL";
} else {
	$notifications_box = $notifications_obj->get_admin_notifications ( '0', 'false' );
	$default = wc_get_base_location();
	$locale = apply_filters( 'woocommerce_countries_base_country', $default['country'] );
}

if($license_information['notice'] == "true"){
        $notifications_box['message_type'] = $license_information['message_type'];
        $notifications_box['message'] = $license_information['message'];
}

if ($versions['PHP'] < 5.6){
        $notifications_box = $notifications_obj->get_admin_notifications ( '11', 'false' );
}

if (!wp_next_scheduled( 'woosea_cron_hook' ) ) {
        $notifications_box = $notifications_obj->get_admin_notifications ( '12', 'false' );
}

if ($versions['WooCommerce'] < 3){
        $notifications_box = $notifications_obj->get_admin_notifications ( '13', 'false' );
}

/**
 * Get shipping zones
 */
$shipping_zones = WC_Shipping_Zones::get_zones();
$nr_shipping_zones = count($shipping_zones);

/**
 * Get channels
 */
$channel_configs = get_option ('channel_statics');

/**
 * Get countries and channels
 */
$channel_obj = new WooSEA_Attributes;
$countries = $channel_obj->get_channel_countries();
$channels = $channel_obj->get_channels($locale);

if (array_key_exists('project_hash', $_GET)){
        $project = WooSEA_Update_Project::get_project_data($_GET['project_hash']);
	$manage_project = "yes";
}
?>

	<div class="wrap">
		<div class="woo-product-feed-pro-form-style-2">

		<div class="woo-product-feed-pro-form-style-2-heading">File name, format and channel</div>
       
		<form action="" id="myForm" method="post" name="myForm">
		
              	<div class="woo-product-feed-pro-table-wrapper">
                <div class="woo-product-feed-pro-table-left">

		<table class="woo-product-feed-pro-table">
			<tbody class="woo-product-feed-pro-body">
				<div id="projecterror"></div>
				<tr>
					<td width="30%"><span>Project name: <span class="required">*</span></span></td>
					<td>
						<div style="display: block;">
							<?php
							if (isset($project)){
								print"<input type=\"text\" class=\"input-field\" id=\"projectname\" name=\"projectname\" value=\"$project[projectname]\"/> <div id=\"projecterror\"></div>";
							} else {
								print"<input type=\"text\" class=\"input-field\" id=\"projectname\" name=\"projectname\"/> <div id=\"projecterror\"></div>";
							}
							?>
						</div>
					</td>
				</tr>
				<?php

				if ( function_exists('icl_object_id') ) {

                                 	$add_wpml_support = get_option ('add_wpml_support');
                                     	if($add_wpml_support == "yes"){
						// Adding WPML support here
						$my_current_lang = apply_filters( 'wpml_current_language', NULL );

						global $sitepress;
       		 				$list_lang = $sitepress->get_active_languages();
						$nr_lang = count($list_lang);

						if($nr_lang > 0){
                                		     	if (isset($manage_project)){
								print "<tr>";
								print "<td><span>WPML Language:</span></td>";
								print "<td>";
								print "<select name=\"WPML\" disabled>";
								foreach ($list_lang as $key => $value){
									if($key == $project['WPML']){
										print "<option value=\"$key\" selected>$value[english_name]</option>";
									} else {
										print "<option value=\"$key\">$value[english_name]</option>";
									}
								}
								print "</select>";
								print "</td>";
								print "</tr>";
							} else {
								print "<tr>";
								print "<td><span>WPML Language:</span></td>";
								print "<td>";
								print "<select name=\"WPML\">";
								foreach ($list_lang as $key => $value){
									if($key == $my_current_lang){
										print "<option value=\"$key\" selected>$value[english_name]</option>";
									} else {
										print "<option value=\"$key\">$value[english_name]</option>";
									}
								}
								print "</select>";
								print "</td>";
								print "</tr>";
							}
						}
					}
				}
				?>
				<tr>
					<td><span>Country:</span></td>
					<td>
						<?php
						if (isset($manage_project)){
							print"<select name=\"countries\" id=\"countries\" class=\"select-field\" disabled>";
						} else {
							print"<select name=\"countries\" id=\"countries\" class=\"select-field\">";
						}
						?>
						<option>Select a country</option>
						<?php
							foreach ($countries as $value){
								if((isset($project)) AND ($value == $project['countries'])){
									print "<option value=\"$value\" selected>$value</option>";
								} else {
									print "<option value=\"$value\">$value</option>";
								}
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td><span>Channel:</span></td>
					<td>
						<?php
						if (isset($manage_project)){
							print "<select name=\"channel_hash\" id=\"channel_hash\" class=\"select-field\" disabled>";
							print "<option value=\"$project[channel_hash]\" selected>$project[name]</option>";
							print "</select>";
						} else {
							$customfeed = "";
							$advertising = "";
							$marketplace = "";
							$shopping = "";
							$optgroup_customfeed = 0;
							$optgroup_advertising = 0;
							$optgroup_marketplace = 0;
							$optgroup_shopping = 0;

							print "<select name=\"channel_hash\" id=\"channel_hash\" class=\"select-field\">";

							foreach ($channels as $key=>$val){
								if ($val['type'] == "Custom Feed"){
									if ($optgroup_customfeed == 1){
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$customfeed .= "<option value=\"$val[channel_hash]\" selected>$key</option>";
										} else {
											$customfeed .= "<option value=\"$val[channel_hash]\">$key</option>";
										}	
									} else {	
										$customfeed =  "<optgroup label=\"Custom Feed\">";
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$customfeed .= "<option value=\"$val[channel_hash]\" selected>$key</option>";	
										} else {
											$customfeed .= "<option value=\"$val[channel_hash]\">$key</option>";	
										}									
										$optgroup_customfeed = 1;
									}
								}

								if ($val['type'] == "Advertising"){
									if ($optgroup_advertising == 1){
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$advertising .= "<option value=\"$val[channel_hash]\" selected>$key</option>";	
										} else {
											$advertising .= "<option value=\"$val[channel_hash]\">$key</option>";	
										}
									} else {	
										$advertising = "<optgroup label=\"Advertising\">";
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$advertising .= "<option value=\"$val[channel_hash]\" selected>$key</option>";	
										} else {
											$advertising .= "<option value=\"$val[channel_hash]\">$key</option>";	
										}
										$optgroup_advertising = 1;
									}
								}
	
								if ($val['type'] == "Marketplace"){
									if ($optgroup_marketplace == 1){
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$marketplace .= "<option value=\"$val[channel_hash]\" selected>$key</option>";	
										} else {
											$marketplace .= "<option value=\"$val[channel_hash]\">$key</option>";	
										}
									} else {	
										$marketplace = "<optgroup label=\"Marketplace\">";
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$marketplace .= "<option value=\"$val[channel_hash]\" selected>$key</option>";	
										} else {
											$marketplace .= "<option value=\"$val[channel_hash]\">$key</option>";	
										}
										$optgroup_marketplace = 1;
									}
								}

								if ($val['type'] == "Comparison shopping engine"){
									if ($optgroup_shopping == 0){
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$shopping .= "<option value=\"$val[channel_hash]\" selected>$key</option>";	
										} else {
											$shopping .= "<option value=\"$val[channel_hash]\">$key</option>";	
										}
									} else {	
										$shopping = "<optgroup label=\"Comparison Shopping Engine\">";
										if((isset($project)) AND ($val['channel_hash'] == $project['channel_hash'])){
											$shopping .= "<option value=\"$val[channel_hash]\">$key</option>";	
										} else {
											$shopping .= "<option value=\"$val[channel_hash]\" selected>$key</option>";	
										}
										$optgroup_shopping = 1;
									}
								}
							}
							print "$customfeed";
							print "$advertising";
							print "$marketplace";
							print "$shopping";
							print "</select>";
						}
						?>
					</td>
				</tr>
				<tr id="product_variations">
					<td><span>Include product variations:</span></td>
					<td>
                                                <label class="woo-product-feed-pro-switch">
                                                        <?php
                                                        if(isset($project['product_variations'])){
                                                                print "<input type=\"checkbox\" id=\"variations\" name=\"product_variations\" class=\"checkbox-field\" checked>";
                                                        } else {
                                                                print "<input type=\"checkbox\" id=\"variations\" name=\"product_variations\" class=\"checkbox-field\">";
                                                        }
                                                        ?>
                                                        <div class="woo-product-feed-pro-slider round"></div>
                                                </label>
					</td>
				</tr>
				<tr id="file">
					<td><span>File format:</span></td>
					<td>
						<select name="fileformat" id="fileformat" class="select-field">
							<?php
							$format_arr = array("xml","csv","txt","tsv");
							foreach ($format_arr as $format){
								$format_upper = strtoupper($format);
								if ((isset($project)) AND ($format == $project['fileformat'])){
									print "<option value=\"$format\" selected>$format_upper</option>";
								} else {
									print "<option value=\"$format\">$format_upper</option>";
								}
							}	
							?>
						</select>
					</td>
				</tr>
				<tr id="delimiter">
					<td><span>Delimiter:</span></td>
					<td>
						<select name="delimiter" class="select-field">
							<?php
							$delimiter_arr = array(",","|",";","tab");
							foreach ($delimiter_arr as $delimiter){
								if((isset($project)) AND (array_key_exists('delimiter', $project)) AND ($delimiter == $project['delimiter'])){
									print "<option value=\"$delimiter\" selected>$delimiter</option>";
								} else {
									print "<option value=\"$delimiter\">$delimiter</option>";
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td><span>Refresh interval:</span></td>
					<td>
						<select name="cron" class="select-field">
							<?php
							$refresh_arr = array("daily","twicedaily","hourly","no refresh");
							foreach ($refresh_arr as $refresh){
								$refresh_upper = ucfirst($refresh);
								if ((isset($project)) AND ($refresh == $project['cron'])){
									print "<option value=\"$refresh\" selected>$refresh_upper</option>";
								} else {
									print "<option value=\"$refresh\">$refresh_upper</option>";
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
						if (isset($project)){
							print "<input type=\"hidden\" name=\"project_hash\" id=\"project_hash\" value=\"$project[project_hash]\" />";
							print "<input type=\"hidden\" name=\"channel_hash\" id=\"channel_hash\" value=\"$project[channel_hash]\" />";
							print "<input type=\"hidden\" name=\"project_update\" id=\"project_update\" value=\"yes\" />";
							print "<input type=\"hidden\" name=\"step\" id=\"step\" value=\"100\" />";
							print "<input type=\"submit\" id=\"goforit\" value=\"Save\" />";
					
						} else {
							print "<input type=\"hidden\" name=\"step\" id=\"step\" value=\"99\" />";
							print "<input type=\"submit\" id=\"goforit\" value=\"Save & continue\" />";
						}
						?>
					</td>
				</tr>
			</tbody>
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
                                                                <li><strong>1. <a href="https://adtribes.io/can-i-add-mother-products-to-my-feed-and-leave-out-the-variations/" target="_blank">Can I leave out mother products?</a></strong></li>
                                                                <li><strong>2. <a href="https://adtribes.io/add-gtin-mpn-upc-ean-product-condition-optimised-title-and-brand-attributes/" target="_blank">Adding GTIN, Brand, MPN and more</a></strong></li>
                                                                <li><strong>3. <a href="https://adtribes.io/woocommerce-structured-data-bug/" target="_blank">WooCommerce structured data markup bug</a></strong></li>
                                                                <li><strong>4. <a href="https://adtribes.io/how-to-create-filters-for-your-product-feed/" target="_blank">How to create filters for your product feed</a></strong></li>
                                                                <li><strong>5. <a href="https://adtribes.io/wpml-support/" target="_blank">Enable WPML support</a></strong></li>
                                                        </ul>
                                                </td>
                                        </tr>
                                </table><br/>

				<!--
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
				-->
                        </div>
                        </div>



		</form>
		</div>
	</div>
