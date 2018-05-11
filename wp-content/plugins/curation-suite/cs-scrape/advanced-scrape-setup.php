<?php 	
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	//include_once( 'advanced-ybi-setup-functions.php' );
	$all_sources = get_option( 'cs_scrape_sources' );
	//var_dump( $all_sources ); /* outputs false */
?>
 <div style="margin: 20px;">
<h3>Scraping Feature:</h3>
        <div class="on_off_switch" >
            <input type="checkbox" name="on_off_switch" class="on_off_switch-checkbox" id="cu_scraping_feature_switch" style="display:none;" <?php checked( 1, get_option('curation_suite_scraping_feature'), true ); ?>>

            <label class="on_off_switch-label" for="cu_scraping_feature_switch">
                <div class="on_off_switch-switch"></div>
                <div class="on_off_switch-inner"></div>
            </label>
        </div>
            <hr /> 
			<input type="hidden" value="new" id="current_action" name="current_action" />
			<input type="hidden" value="" id="edit_key" name="edit_key" />
 	<div id="scraping_edit_form">
         <div class="scrap_row">
            <label>Source URL:</label>
            <input type="text" name="url" id="url" class="regular-text ybi_scrape_url form-control" /><a href="javascript:;" class="get_title button action">Get Title</a>
            <span class="loading_action"></span>
        </div>
        <div class="scrap_row">		
            <label>Name:</label>
            <input type="text" name="scape_name" id="scrape_name" class="regular-text ybi_scrape_name form-control" />
        </div>
        <div class="scrap_row">       
            <label>Source:</label>
            <select name="source_type" id="source_type" class="">
            <option value="scoop.it"><?php _e("Scoop.it"); ?></option>
            <option value="newswhip"><?php _e("NewsWhip"); ?></option>
            <option value="googlecommunity"><?php _e("Google+ Community Page"); ?></option>
            <option value="alltop"><?php _e("AllTop"); ?></option>
            </select>
        </div>
        <div class="scrap_row">
             <a href="javascript:;" class="source_action button action">Add Source</a>
        </div>
	</div>
 
 <div id="source_listing_wrap" style="width: 100%;">
 <table class="wp-list-table widefat fixed posts" cellspacing="0">
<thead>
 <tr>
 	<th class="name_col">Name/Title</th>
 	<th class="source_col">Source</th>
 	<th class="url_col">URL</th>
 	<th class="actions_col">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php 
 $i = 0;
 $alternate = '';
 foreach($all_sources as $single_source) {
		$key = $single_source['source'] . '--' . $single_source['url'];
		if(0 != $i % 2): $alternate = ' alternate'; endif;
  ?>
 <tr class="type-post status-publish format-standard hentry<?php echo $alternate; ?>">
	 <td><a href="<?php echo $single_source['url'] ?>" target="_blank"><?php echo $single_source['title'] ?></a></td>
	 <td><?php echo $single_source['source'] ?></td>
	 <td><?php echo $single_source['url'] ?></td>
	 <td><a href="javascript:;" class="edit" rel="<?php echo $key; ?>">edit</a> | <a href="javascript:;" class="delete" >delete</a></td>
 
 </tr>
<?php 
	 $alternate = '';
	$i++;
} ?> 
</tbody>
 </table>
 </div>
 
 
 </div>