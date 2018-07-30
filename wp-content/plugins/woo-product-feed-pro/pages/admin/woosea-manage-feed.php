<?php
$cron_projects = get_option( 'cron_projects' );
$license_information = get_option( 'license_information' );
$error = "false";
$count_variation = wp_count_posts('product_variation');
$count_single = wp_count_posts('product');
$published_single = $count_single->publish;
$published_variation = $count_variation->publish;
$published_products = $published_single+$published_variation;

$product_numbers = array (
	"Single products" => $published_single,
	"Variation products" => $published_variation,
	"Total products" => $published_products
);

$plugin_data = get_plugin_data( __FILE__ );

$versions = array (
	"PHP" => (float)phpversion(),
	"Wordpress" => get_bloginfo('version'),
	"WooCommerce" => WC()->version,
	"WooCommerce Product Feed PRO" => WOOCOMMERCESEA_PLUGIN_VERSION
);

// Get the sales from created product feeds
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$table_name = $wpdb->prefix . 'adtribes_my_conversions';
$order_rows = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

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
        $notifications_box = $notifications_obj->get_admin_notifications ( "9", "false" );
} else {
        $notifications_box = $notifications_obj->get_admin_notifications ( '8', 'false' );
}

if ($versions['PHP'] < 5.6){
        $notifications_box = $notifications_obj->get_admin_notifications ( '11', 'false' );
}

if ($versions['WooCommerce'] < 3){
        $notifications_box = $notifications_obj->get_admin_notifications ( '13', 'false' );
}

if($license_information['notice'] == "true"){
        $notifications_box['message_type'] = $license_information['message_type'];
        $notifications_box['message'] = $license_information['message'];
}

if (!wp_next_scheduled( 'woosea_cron_hook' ) ) {
	$notifications_box = $notifications_obj->get_admin_notifications ( '12', 'false' );
}
?>
<div class="wrap">
        <div class="woo-product-feed-pro-form-style-2">
                <tbody class="woo-product-feed-pro-body">
			<?php
			if (array_key_exists('debug', $_GET)){
				if(sanitize_text_field($_GET['debug']) == "true"){
					$external_debug_file = $notifications_obj->woosea_debug_informations ($versions, $product_numbers, $order_rows, $cron_projects);
				?>	
                        		<div class="woo-product-feed-pro-form-style-2-heading">Debugging mode</div>
					<div class="notice notice-error is-dismissible">
                				<p>
						Thank you for taking the time to help us find bugs in our plugin. It is greatly appreciated by us and your feedback will help all current and future users of this plugin. Could you please copy / paste the debug URL in the box below and send it to <a href="mailto:support@adtribes.io">support@adtribes.io</a> so we can analyse how your feed projects are configured and discover potential problems.<br/><br/>
							<?php
							print "<strong>Debug file:</strong><br/><a href=\"$external_debug_file\" target=\"_blank\">$external_debug_file</a>";
							?>
						</p>
					</div><br/>
				<?php
				}
			}
			?>

                        <div class="woo-product-feed-pro-form-style-2-heading">Manage feeds</div>
			<div class="woo-product-feed-pro-table-wrapper">
			<div class="woo-product-feed-pro-table-left">

		        <table class="woo-product-feed-pro-table">
			<tr>
				<td><strong>Active</strong></td>
				<td><strong>Project name and channel</strong></td>
				<td><strong>Format</strong></td>
				<td><strong>Refresh interval</strong></td>
				<td><strong>Status</strong></td>
				<td></td>
			</tr>
	
			<?php
			if($cron_projects){
				$toggle_count = 1;
				$class = "";

				foreach ($cron_projects as $key=>$val){

					if($val['active'] == "true"){
						$checked = "checked";
						$class = "";
					} else {
						$checked = "";
						$class = "woo-product-feed-pro-strikethrough";
					}

					if(isset($val['filename'])){
						$projectname = ucfirst($val['projectname']);
					?>
					<form action="" method="post">
					<tr class="<?php print "$class";?>">
						<td>
                                                <label class="woo-product-feed-pro-switch">
                                                        <input type="hidden" name="manage_record" value="<?php print "$val[project_hash]";?>"><input type="checkbox" id="project_active" name="project_active[]" class="checkbox-field" value="<?php print "$val[project_hash]";?>" <?php print "$checked";?>>
                                                        <div class="woo-product-feed-pro-slider round"></div>
                                                </label>
						</td>
						<td><span><?php print "$projectname<br/</span><span class=\"woo-product-feed-pro-channel\">Channel: $val[name]</span>";?></span></td>
						<td><span><?php print "$val[fileformat]";?></span></td>
						<td><span><?php print "$val[cron]";?></span></td>
						<?php
							if ($val['running'] == "processing"){
								$proc_perc = round(($val['nr_products_processed']/$val['nr_products'])*100);
								print "<td><span class=\"woo-product-feed-pro-blink_me\">$val[running] ($proc_perc%)</span></td>";
							} else {
								print "<td><span class=\"woo-product-feed-pro-blink_off_$val[project_hash]\">$val[running]</span></td>";
							}
						?>
						<td>
							<div class="actions">
								<span class="gear ui-icon ui-icon-gear" id="gear_<?php print "$val[project_hash]";?>" title="project settings" style="display: inline-block;"></span>
								<?php 
								if ($val['running'] != "processing"){
								?>
									<?php
									if ($val['active'] == "true"){
										print "<span class=\"ui-icon ui-icon-refresh\" id=\"refresh_$val[project_hash]\" title=\"manually refresh productfeed\" style=\"display: inline-block;\"></span>";
										print "<a href=\"$val[external_file]\" target=\"_blank\"><span class=\"ui-icon ui-icon-arrowthickstop-1-s\" id=\"download\" title=\"download productfeed\" style=\"display: inline-block\"></span></a>";
									}?>
									<span class="trash ui-icon ui-icon-trash" id="trash_<?php print "$val[project_hash]";?>" title="delete project and productfeed" style="display: inline-block;"></span>

								<?php
								} else {
									print "<span class=\"ui-icon ui-icon-cancel\" id=\"cancel_$val[project_hash]\" title=\"cancel processing productfeed\" style=\"display: inline-block;\"></span>";
								}
								?>	
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="8">
							<div>
								<table class="woo-product-feed-pro-inline_manage">

									<?php
									if (($val['running'] == "ready") OR ($val['running'] == "stopped")){
									?>
									<tr>
										<td>
											<strong>Change settings</strong><br/>
											<span class="ui-icon ui-icon-caret-1-e" style="display: inline-block;"></span> <a href="admin.php?page=woo-product-feed-pro%2Fwoocommerce-sea.php&action=edit_project&step=0&project_hash=<?php print "$val[project_hash]";?>&channel_hash=<?php print "$val[channel_hash]";?>">General settings</a><br/>
											<?php
											if ($val['fields'] == "standard"){
												print "<span class=\"u-icon ui-icon-caret-1-e\" style=\"display: inline-block;\"></span> <a href=\"admin.php?page=woo-product-feed-pro%2Fwoocommerce-sea.php&action=edit_project&step=2&project_hash=$val[project_hash]&channel_hash=$val[channel_hash]\">Attribute selection</a></br/>";
											} else {
												print "<span class=\"ui-icon ui-icon-caret-1-e\" style=\"display: inline-block;\"></span> <a href=\"admin.php?page=woo-product-feed-pro%2Fwoocommerce-sea.php&action=edit_project&step=7&project_hash=$val[project_hash]&channel_hash=$val[channel_hash]\">Attribute mapping</a><br/>";
											}
											
											if ($val['taxonomy'] != "none"){
												print "<span class=\"ui-icon ui-icon-caret-1-e\" style=\"display: inline-block;\"></span> <a href=\"admin.php?page=woo-product-feed-pro%2Fwoocommerce-sea.php&action=edit_project&step=1&project_hash=$val[project_hash]&channel_hash=$val[channel_hash]\">Category mapping</a><br/>";
											}
											?>
											<span class="ui-icon ui-icon-caret-1-e" style="display: inline-block;"></span> <a href="admin.php?page=woo-product-feed-pro%2Fwoocommerce-sea.php&action=edit_project&step=4&project_hash=<?php print "$val[project_hash]";?>&channel_hash=<?php print "$val[channel_hash]";?>">Feed filters and rules</a><br/>
											<span class="ui-icon ui-icon-caret-1-e" style="display: inline-block;"></span> <a href="admin.php?page=woo-product-feed-pro%2Fwoocommerce-sea.php&action=edit_project&step=5&project_hash=<?php print "$val[project_hash]";?>&channel_hash=<?php print "$val[channel_hash]";?>">Conversion tracking and Google Analytics settings</a><br/>
										</td>
									</tr>
									<?php
									}
									?>
									<tr>
										<td>
											<strong>Feed URL</strong><br/>
											<?php
											if ($val['active'] == "true"){
											 	print "<span class=\"ui-icon ui-icon-caret-1-e\" style=\"display: inline-block;\"></span> $val[external_file]";
											} else {
												print "<span class=\"ui-icon ui-icon-alert\"></span> Whoops, there is no active product feed for this project as the project has been disabled.";
											}
											?>
										</td>
									</tr>
									
								</table>
							</div>
						</td>
					</tr>	
					</form>
					<?php
					$toggle_count++;
					}	
				}
			} else {
				?>
				<tr>
					<td colspan="6"><br/><span class="ui-icon ui-icon-alert"></span> You didn't configured a product feed yet, <a href="admin.php?page=woo-product-feed-pro%2Fwoocommerce-sea.php">please create one first</a>.<br/><br/></td>
				</tr>
				<?php
			}
			?>
			</table>
			</div>
			<div class="woo-product-feed-pro-table-right">
				<!--
				<table class="woo-product-feed-pro-table">
        		                <tr>
						<td><strong>Tutorials</strong></td>
					</tr>
					<tr>
						<td><br/><br/><br/><br/></td>
					</tr>
				</table><br/>
				-->


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


				<table class="woo-product-feed-pro-table">
        		                <tr>
						<td><strong>WooCommerce product numbers</strong></td>
					</tr>
					<tr>
						<td>
							You currently have <strong><?php print "$published_products";?></strong> published products on your website out of which <strong><?php print "$published_single";?></strong> are single products and <strong><?php print "$published_variation";?></strong> are variable products.
						</td>
					</tr>
				</table>
			</div>
			</div>
		</tbody>
	</div>
</div>
