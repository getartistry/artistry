<?php 
	//$options = get_option('curation_suite_data');
    //$curation_suite_headline_default = 0;
    //$curation_suite_blockquote_default = 1;
    $curation_suite_default_image_size = 200;
    $curation_suite_upload_images = 0;

    if(is_array($options)) {
        if(array_key_exists('curation_suite_upload_images',$options))
            $curation_suite_upload_images = $options['curation_suite_upload_images'];
    }

    if($curation_suite_default_image_size == '' || $curation_suite_default_image_size < 20)
        $curation_suite_default_image_size = 200;

	 if (!class_exists('ybi_product')) {
		echo( __('<strong>Curation Suite requires the latest version of the You Brand, Inc. Plugin v1.5</strong><br /><br />
		<a href="https://members.youbrandinc.com/dashboard/curation-suite/" target="_blank">Click here to download</a> or 
		go back to the WordPress <a href="'.get_admin_url(null, 'plugins.php').'">Plugins page</a>'));
		return;
	 }

	$CurationSuiteProduct = new ybi_product('Curation Suite');
	if(do_validate_license($CurationSuiteProduct)!==true):
		echo( __( 'Please enter your license key for Curation Suite&trade; using the You Brand, Inc. Licensing Plugin - <a href=" ' . self_admin_url('admin.php?page=youbrandinc-license') . '"> Enter License Here' ) );
		return;
	endif;
	
	// check to see if we are using the plugin files
$curation_suite_parse_page_version_copied = get_option('curation_suite_parse_page_version_copied');
	if(get_option('ybi_cu_use_plugin_files') != 'yes')
	{
        if(get_option('curation_suite_parse_page_version_copied')) {
            if(CURATION_SUITE_VERSION != get_option('curation_suite_parse_page_version_copied')) {
                if (!copy_worker_files_wp_admin() || (CURATION_SUITE_VERSION != get_option('curation_suite_parse_page_version_copied')))
                    echo '<span class="errorj_text"><p>This plugin is missing a file it requires.</p>
				<p>To fix this <strong>de-activate</strong> and <strong>re-activate</strong> the plugin. If after re-activating the plugin you still get this error then a manual copy is required. 
				Click here to get step by step instructions on how to fix this (don\'t worry these steps are easy).</p></span>';
            }
        }


		if (!file_exists("parse-page-worker.php") || !file_exists("parse-page-worker.php") || (CURATION_SUITE_VERSION != get_option('curation_suite_parse_page_version_copied')))
		{
			if (!copy_worker_files_wp_admin() || (CURATION_SUITE_VERSION != get_option('curation_suite_parse_page_version_copied')))
				echo '<span class="errorj_text"><p>This plugin is missing a file it requires.</p>
				<p>To fix this <strong>de-activate</strong> and <strong>re-activate</strong> the plugin. If after re-activating the plugin you still get this error then a manual copy is required. 
				Click here to get step by step instructions on how to fix this (don\'t worry these steps are easy).</p></span>';
		}
	}

$curated_links_arr= get_post_meta( get_the_ID(), 'cu_curated_links', false );
// check if the custom field has a value
if( ! empty( $curated_links_arr ) ) {
    ?>
    <div id="curated_links_display">
    <h4>Curated Links</h4>
    <ul>
    <?php
    foreach($curated_links_arr as $link) {
        echo '<li><a href="' . $link . '" target="_blank">'.$link.'</a> | <a href="javascript:;" class="link_to_load" name="' . $link . '"><i class="fa fa-download"></i> Load Link</a></li>';
    }
    ?>
    </ul>
        <hr class="curated_links_hr" />
    </div>
        <?php
    }

?>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	<?php if($loadLink): ?>
		$("#contentiframe").css({"display":"block","visibility":"visible"});
		$(".link_shortcuts").css({"display":"block","visibility":"visible"});
		$("#content_block_links").css({"display":"block","visibility":"visible"});
        //$("#ybi_cu_content_actions_work_meta").css({"display":"block","visibility":"visible"});
        $(".content_action_div").css({"display":"none","visibility":"hidden"});
        $('#visual_editor').css({"display":"block","visibility":"visible"});
        //$('#main_toggle_arrow').html('<i class="fa fa-caret-right"></i>');
        $('.curation_suite_side_panel_control').toggle(true);
        $(".show_content_div").css({"background":"none repeat scroll 0 0 #333"});
        $('#cu_visual_editor_tab_control').css({"background":"#0074a2 no-repeat url(../wp-content/plugins/curation-suite/i/indicator.png) center bottom"});
        toggle_cs_sidebar('show');
	<?php endif; ?>
	$('#contentiframe').load(function() {
		$('.loading').hide();
	});
});
</script>
<div class="curation_suite_meta_section">
	<div class="content_add_error"></div>
	<div class="content_added"><i class="fa fa-plus"></i> Content Added to Post</div>
<div>
<div id="add_to_post_visual_top">
<div class="add_to_post_box_wrapper">
    <?php echo cs_get_thickbox_link_video('IlAMBtW15J4','span','tut_visual_editor'); ?>
	<div style="float:left;">
    <div class="add_to_post_box_row">
            <div class="add_to_post_box_headline"><strong>Add to Post</strong></div>
            <div class="btn-group">
              <a class="btn btn-default add_to_post_box" href="javascript:;" name="add_to_post" rel="alignleft"><i class="fa fa-align-left"></i></a>
              <a class="btn btn-default add_to_post_box" href="javascript:;" name="add_to_post" rel="aligncenter"><i class="fa fa-align-center"></i></a>
              <a class="btn btn-default add_to_post_box" href="javascript:;" name="add_to_post" rel="alignright"><i class="fa fa-align-right"></i></a>
            </div>
    </div>
    <div id="blockquote_button_select_wrapper">
        <div class="blockquote_button_headline"><i class="fa fa-quote-left"></i> Blockquote</div>
        <div class="on_off_switch" >
            <input type="checkbox" name="on_off_switch" class="on_off_switch-checkbox" id="myimage_cu_blockquote_switch" style="display:none;" <?php checked( 1, $curation_suite_blockquote_default, true ); ?>>
            <label class="on_off_switch-label" for="myimage_cu_blockquote_switch">
                <div class="on_off_switch-switch"></div>
                <div class="on_off_switch-inner"></div>
            </label>
        </div>
	</div>


    <div id="headline_button_select_wrapper">
        <div class="headline_button_headline"><i class="fa fa-header"></i> Headline</div>
        <div class="on_off_switch" >
            <input type="checkbox" name="on_off_switch" class="on_off_switch-checkbox" id="myimage_cu_headline_switch" style="display:none;" <?php checked( 1, $curation_suite_headline_default, true ); ?>>
            <label class="on_off_switch-label" for="myimage_cu_headline_switch">
                <div class="on_off_switch-switch"></div>
                <div class="on_off_switch-inner"></div>
            </label>
        </div>
	</div>
    <div id="link_attribution_options_wrapper">
        <div class="link_attribution_headline"><i class="fa fa-link"></i> Link Attribution</div>
        <div class="link_attribution_switch" >
        <select name="attribution_link_location" id="attribution_link_location">
	        <option value="link_before" <?php selected( $curation_suite_link_attribution_location_default, 'link_before' ); ?>><?php _e("Link Before"); ?></option>
    	    <option value="link_after" <?php selected( $curation_suite_link_attribution_location_default, 'link_after' ); ?>><?php _e("Link After"); ?></option>
        	<option value="link_headline" <?php selected( $curation_suite_link_attribution_location_default, 'link_headline' ); ?>><?php _e("Headline Link"); ?></option>
        	<option value="link_above" <?php selected( $curation_suite_link_attribution_location_default, 'link_above' ); ?>><?php _e("Link Above"); ?></option>
        </select>

        </div>
	</div>


    </div>
    <div class="add_to_post_box_clear">
        <label class="clear_when_add_label"><input type="checkbox" name="clear_when_add" id="clear_when_add" /> Clear when added</label>
        <a href="javascript:;" class="clear_element" name="CLEARALL"><i class="fa fa-eraser"></i> clear all</a>
	</div>
</div>
<div style="clear: both; margin: 0 auto; overflow: auto;"></div>
  <p>
  <a href="#load_link_box"></a>
    <input type="hidden" id="source_domain" name="source_domain" value="" />
    <input type="text" id="source_url" name="_ybi_curation_suite_meta[curated_link]" value="<?php echo $loadLink; ?>"/> <a href="javascript:;" id="load_content">Load Link</a>
    <a href="javascript:;" class="clear_element" name="source_url"><i class="fa fa-eraser"></i> clear</a>
    	<div id="link_domain_tools">Tools:
        <a href="" target="_blank" class="tool_visit_link" title="Visit Link"><i class="fa fa-external-link"></i> Visit Link</a>
        <a href="" target="_blank" class="tool_moz_link" title="MOZ Open Site Explorer">
        <img src="<?php echo plugins_url(); ?>/curation-suite/i/moz-icon.ico" />MOZ</a>
         <a href="" target="_blank" class="tool_majestic_link" title="MajesticSEO Site Explorer"><img src="<?php echo plugins_url(); ?>/curation-suite/i/majestic-icon.png" />MajesticSEO</a>
         <a href="" target="_blank" class="tool_ahrefs_link" title="aHREFS Site Explorer"><img src="<?php echo plugins_url(); ?>/curation-suite/i/ahrefs-icon.ico" />ahrefs</a>
        </div>
    <div class="loading"><i class="fa fa-spinner fa-spin"></i> Page is loading...</div>
  </p>
  <p>
	<h4>Headline Text <span class="headline_links"><a href="javascript:;" id="add_headline_to_title"><i class="fa fa-header"></i> Copy to Title</a> |
            <a href="javascript:;" id="cleanHeadline">Clean Headline</a> | <a href="javascript:;" id="originalHeadline">Original Headline</a> |
    </span>
    <a href="javascript:;" class="clear_element" name="curated_headline"><i class="fa fa-eraser"></i> clear</a></h4>
    <input type="text" id="curated_headline" name="_ybi_curation_suite_meta[curated_headline]" value="" />
    <input type="hidden" id="og_curated_headline" name="_ybi_curation_suite_meta[og_curated_headline]" value="" />
 </p>
  <p>
  <h4>Link Text <a href="javascript:;" class="clear_element" name="curated_link_text"><i class="fa fa-eraser"></i> clear</a></h4>
    <div class="link_shortcuts"><em>shortcuts:</em>&nbsp;&nbsp;
    <a href="javascript:;" id="copy_headline" class="change_link_text" name="copy_headline"><i class="fa fa-files-o"></i> copy headline</a> | 
    <a href="javascript:;" class="change_link_text">See more...</a> | 
    <a href="javascript:;" class="change_link_text">Read more...</a> | 
    <a href="javascript:;" id="via_domain" class="change_link_text"></a> | 
    <a href="javascript:;" id="via_domain_via" class="change_link_text"></a> | 
    <a href="javascript:;" id="via_domain_ht" class="change_link_text"></a> |
    <a href="javascript:;" id="read_more_via_domain" class="change_link_text"></a>
    <span id="users_link_text_shortcuts">
    <?php
	//curation_suite_custom_image_sizes
	$user_shorcuts = $options['curation_suite_custom_link_text'];	
	$pieces = explode("|", $user_shorcuts);
	$i = 1;
	if($user_shorcuts):
		foreach($pieces as $val) {
	   		if($i > 1): ?> | <?php endif; ?>
           <a href="javascript:;" class="change_link_text"><?php _e($val); ?></a>
	<?php
		$i++;
		} 
	endif;
	 ?>
    </span>
    </div>

    <div class="input-group">
        <span class="input-group-addon input-group-raw-link"><a href="javascript:;" class="add_raw_link_attribution" data-add-type="plain"><i class="fa fa-arrow-circle-left"></i></a></span>
    <input type="text" id="curated_link_text" name="_ybi_curation_suite_meta[curated_headline]" value="" />
    </div>
 </p>
   <div id="thumbnail_select_wrapper">
	<h4 id="thumbnail_headline">Thumbnail</h4>
    <div id="upload_button_select_wrapper">
        <div class="image_uploads_switch" >
            <input type="checkbox" name="image_uploads_switch" class="image_uploads_switch-checkbox" id="myimage_uploads_switch" style="display:none;" <?php checked( 1, $curation_suite_upload_images, true ); ?>>
            <label class="image_uploads_switch-label" for="myimage_uploads_switch">
                <div class="image_uploads_switch-switch"></div>
                <div class="image_uploads_switch-inner"></div>
            </label>
        </div>
	</div>
    <div id="thumbnail_sizing_wrapper">
    <a href="javascript:;" class="clear_thumbnail" name="clear_thumbnail"><i class="fa fa-eraser"></i> clear image</a>
    <span class="image_sizing_label">Size: <input type="text" id="image_sizing" name="image_sizing" value="<?php echo $curation_suite_default_image_size ?>" /></span>
        <label class="clear_when_add_label"><input type="checkbox" name="link_image" id="cs_link_image" <?php checked( 1, $curation_suite_link_images_default, true ); ?> /> Link Image</label>

	<span class="headline_links">
    <?php 
	// user shortcuts for sizing is comma seperated... should be anyway.
	$user_shorcuts = '';
    if (array_key_exists('$user_shorcuts', $options)){
        $user_shorcuts = $options['$user_shorcuts'];
    }
	if($user_shorcuts):
	$pieces = explode(",", $user_shorcuts);
	$i = 1;
	foreach($pieces as $val) {
		if($i > 1): ?> | <?php endif; ?>
            <a href="javascript:;" class="change_image_sizing"><?php echo trim($val); ?></a>
    <?php 
		$i++;
	} 
	endif; //	if($user_shorcuts):
	?> 
    </span>
    </div>
</div>
<div id="thumbnail_wrapper">
    <input type="hidden" id="curated_thumbnail" name="_ybi_curation_suite_meta[curated_thumbnail]" value="" />
    <img src="<?php echo plugins_url(); ?>/curation-suite/i/no-image-selected.png" id="chosenthumbnail" style="max-width: 200px;" />
</div>


  <h4>Curated Content <a href="javascript:;" class="clear_element" name="summary_text_textarea"><i class="fa fa-eraser"></i> clear</a>
              <div class="btn-group" style="margin-left: 20px;">
              <a class="btn btn-default add_to_post_box add_to_post_curated_only" href="javascript:;" name="add_to_post_curated_only" rel="alignleft">Add to Post</a>
              </div>

  </h4>
  <p>
    <textarea name="summary_text" id="summary_text_textarea"></textarea>
  </p>
<?php if(get_option('ybi_cu_use_plugin_files') == 'yes'): ?>
	<input type="hidden" value="<?php echo get_home_path(); ?>" id="homepath" />
<?php endif; ?>

  <?php
  if($loadLink != '')
  {
	  // we mask http and https because for some setups if there is a call to a iframe with a url as a parameter then the iframe won't load.
	  $loadLink = str_replace("http://", "", $loadLink);
	  $loadLink = str_replace("https://", "xxxxs", $loadLink);
	
	// if we use the plugin files the the load link is going to wp-content directory
	if(get_option('ybi_cu_use_plugin_files') == 'yes')
	{
		$loadLink = plugins_url() . '/curation-suite/admin-files/parse-page-worker.php?homepath='.get_home_path().'&curatethis=1&url=' . $loadLink;
	}
	else {
        $loadLink = 'parse-page-worker.php?curatethis=1&url=' . $loadLink; 	// the file should be loacated in the same directory (wp-admin) as the post.php, edit-post.php
    }
?>
      <script>
      		jQuery('html, body').animate({scrollTop: jQuery("#ybi_curation_suite_meta").offset().top}, 1000);
      </script>
  <?php
  }
  ?>
  <p>
</div>
<!--<div id="add_to_post_visual_bottom">-->
    <div id="content_block_links">
	<a href="javascript:;" class="show_div" name="images" id="images_link"><i class="fa fa-picture-o"></i> Images<span class="total"></span></a>
	<a href="javascript:;" class="show_div" name="all_paragraphs" id="paragraphs_link"><i class="fa fa-file-text-o"></i> Main Content<span class="total"></span></a>
	<a href="javascript:;" class="show_div" name="summary_meta_text"><i class="fa fa-file-text-o"></i> Summary/Meta<span class="total"></span></a>
	<a href="javascript:;" class="show_div" name="list_content" id="lists_link"><i class="fa fa-list-ul"></i> Lists<span class="total"></span></a>
	<!--<a href="javascript:;" class="show_div" name="links" id="links_link"><i class="fa fa-link"></i> Links<span class="total"></span></a>-->
	<a href="javascript:;" class="show_div" name="videos" id="videos_link"><i class="fa fa-video-camera"></i> Videos<span class="total"></span></a>
	<a href="javascript:;" class="show_div" name="social" id="social_links"><i class="fa fa-comments"></i> Social<span class="total"></span></a>
    </div>
  <div class="loading"><i class="fa fa-spinner fa-spin"></i> Page is loading...</div>
  <iframe id="contentiframe" src="<?php echo $loadLink; ?>" width="98%" height="400" scrolling="no"></iframe></p>
<!--</div>-->
</div>

</div>