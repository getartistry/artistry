<?php
/**
 * Change default footer text, asking to review our plugin
 **/
function my_footer_text($default) {
    return 'If you like our <strong>WooCommerce Product Feed PRO</strong> plugin please leave us a <a href="https://wordpress.org/support/plugin/woo-product-feed-pro/reviews?rate=5#new-post" target="_blank" class="woo-product-feed-pro-ratingRequest">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Thanks in advance!';
}
add_filter('admin_footer_text', 'my_footer_text');

/**
 * Create notification object
 */
$notifications_obj = new WooSEA_Get_Admin_Notifications;
$notifications_box = $notifications_obj->get_admin_notifications ( '1', 'false' );

/**
 * Update project configuration 
 */
if (array_key_exists('project_hash', $_GET)){
        $project = WooSEA_Update_Project::get_project_data(sanitize_text_field($_GET['project_hash']));
        $channel_data = WooSEA_Update_Project::get_channel_data(sanitize_text_field($_GET['channel_hash']));
        $manage_project = "yes";
} else {
        $project = WooSEA_Update_Project::update_project($_POST);
        $channel_data = WooSEA_Update_Project::get_channel_data(sanitize_text_field($_POST['channel_hash']));
}

function woosea_hierarchical_term_tree($category, $prev_mapped){
	$r = '';

    	$args = array(
        	'parent' 	=> $category,
		'hide_empty'    => false,
        	'no_found_rows' => true,
    	);

    	$next = get_terms('product_cat', $args);

    	if ($next) {
        	foreach ($next as $sub_category) {
			$x = $sub_category->term_id;
			$woo_category = $sub_category->name;
                     	$woo_category_id = $sub_category->term_id;
                     	$mapped_category = "";
                    	$mapped_active_class = "input-field-large";
                        $woo_category = preg_replace('/&amp;/','&',$woo_category);

                    	if (array_key_exists($woo_category, $prev_mapped)){
                        	$mapped_category = $prev_mapped[$woo_category];
                             	$mapped_active_class = "input-field-large-active";
                     	}

			if($sub_category->parent == 0){
				$r .= "<tr>";
            			$r .= "<td><input type=\"hidden\" name=\"mappings[$x][rowCount]\" value=\"$x\"><input type=\"hidden\" name=\"mappings[$x][categoryId]\" value=\"$woo_category_id\"><input type=\"hidden\" name=\"mappings[$x][criteria]\" class=\"input-field-large\" value=\"$woo_category\">$woo_category ($sub_category->count)</td>";
				$r .= "<td><input type=\"search\" name=\"mappings[$x][map_to_category]\" class=\"$mapped_active_class js-typeahead js-autosuggest autocomplete_$x\" value=\"$mapped_category\"></td>";
				$r .= "</tr>";
			} else {
				$r .= "<tr>";
            			$r .= "<td><input type=\"hidden\" name=\"mappings[$x][rowCount]\" value=\"$x\"><input type=\"hidden\" name=\"mappings[$x][categoryId]\" value=\"$woo_category_id\"><input type=\"hidden\" name=\"mappings[$x][criteria]\" class=\"input-field-large\" value=\"$woo_category\">-- $woo_category ($sub_category->count)</td>";
				$r .= "<td><input type=\"search\" name=\"mappings[$x][map_to_category]\" class=\"$mapped_active_class js-typeahead js-autosuggest autocomplete_$x\" value=\"$mapped_category\"></td>";
				$r .= "</tr>";
			}
			$r .= $sub_category->term_id !== 0 ? woosea_hierarchical_term_tree($sub_category->term_id, $prev_mapped) : null;
		}
    	}
    	return $r;
}
?>

<div class="wrap">
	<div class="woo-product-feed-pro-form-style-2">
		<div class="woo-product-feed-pro-form-style-2-heading">Category mapping</div>

                <div class="<?php _e($notifications_box['message_type']); ?>">
                       	<p><?php _e($notifications_box['message'], 'sample-text-domain' ); ?></p>
                </div>

		<form action="" method="post">

              	<div class="woo-product-feed-pro-table-wrapper">
            	<div class="woo-product-feed-pro-table-left">

		<table id="woosea-ajax-mapping-table" class="woo-product-feed-pro-table" border="1">	
			<thead>
            			<tr>
                			<th>Your category <i>(Number of products)</i></th>
					<th><?php print "$channel_data[name]";?> category</th>
            			</tr>
        		</thead>
       
 			<tbody class="woo-product-feed-pro-body"> 
			<?php 
			// Get already mapped categories
			$prev_mapped = array();
			if(isset($project['mappings'])){
				foreach ($project['mappings'] as $map_key => $map_value){
					if(strlen($map_value['map_to_category']) > 0){
						$map_value['criteria'] = str_replace("\\","",$map_value['criteria']);
						$prev_mapped[$map_value['criteria']] = $map_value['map_to_category'];
					}
				}
			}
			// Display mapping form
			echo woosea_hierarchical_term_tree(0,$prev_mapped);			
			?>
        		</tbody>
                                
			<tr>
				<td colspan="2">
                                <input type="hidden" id="channel_hash" name="channel_hash" value="<?php print "$project[channel_hash]";?>">
			  	<?php
                                	if(isset($manage_project)){
                                        ?>
                                             	<input type="hidden" name="project_update" id="project_update" value="yes" />
                                             	<input type="hidden" name="project_hash" value="<?php print "$project[project_hash]";?>">
                                             	<input type="hidden" name="step" value="100">
                               			<input type="submit" value="Save mappings" />
					<?php
                                      	} else {
                                       	?>
						<input type="hidden" name="project_hash" value="<?php print "$project[project_hash]";?>">
                		                <input type="hidden" name="step" value="4">
                               			<input type="submit" value="Save mappings" />
					<?php
					}
					?>
				</td>
			</tr>
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
                                                                <li><strong><a href="https://adtribes.io/support/" target="_blank">F.A.Q.</a></strong></li>
                                                                <li><strong><a href="https://www.youtube.com/channel/UCXp1NsK-G_w0XzkfHW-NZCw" target="_blank">YouTube tutorials</a></strong></li>
                                                        </ul>
                                                        Or just reach out to us at  <a href="mailto:support@adtribes.io">support@adtribes.io</a> and we'll make sure your product feeds will be up-and-running within no-time.
                                                </td>
                                        </tr>
                                </table><br/>

                                <table class="woo-product-feed-pro-table">
                                        <tr>
                                                <td><strong>Upgrade to Elite</strong></td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        Enjoy all priviliges of our Elite features and priority support and upgrade to the Elite version of our plugin now!
                                                        <ul>
                                                                <li><strong>1.</strong> WooCommerce Structured Data bug fix </li>
                                                                <li><strong>2.</strong> Add GTIN, brand and more fields to your store</li>
                                                                <li><strong>3.</strong> Exclude individual products from your product feeds</li>
                                                                <li><strong>4.</strong> Priority support</li>
                                                        </ul>
                                                        <a href="https://adtribes.io/pro-vs-elite/?utm_source=$domain&utm_medium=plugin&utm_campaign=upgrade-elite" target="_blank">Upgrade to Elite here!</a>
                                                </td>
                                        </tr>
                                </table><br/>



                        </div>
                        </div>
		</form>
	</div>
</div>
