<div class="wrap" style="min-height: 800px;">
<?php
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	include_once( YBI_CURATION_SUITE_PATH . 'listening/listening-default-values.php' );
    $topic_count = 0;// we will use this to track if platform has been setup, is set in the listening-meta.php down below
    // Used further down in both the listening-meta.php and at the bottom of this file for quick post feature
    $categories = get_categories(array('hide_empty'=>0));
    $category_count = count($categories);
?>
 <div style="margin: 5px 20px;" id="listening-platform-display-page">
 <div id="settings_wrapper">
     <span style="display: inline-block; margin-right: 10px;" id="cs_le_health_notice"></span>
     <span style="color: #FF0000; margin-right: 10px;" class="new_platform_notice">New Platform Start Here &raquo;</span><a href="javascript:;" id="platform_setup_quicklink"><i class="fa fa-cogs"></i> Platform Setup</a>
     <a href="javascript:;" id="show_content_shortcut"><i class="fa fa-file-text-o"></i> Show Content</a>
     <a href="javascript:;" id="saved_content_shortcut"><i class="fa fa-bookmark-o"></i> Saved Content</a>
     <a href="javascript:;" id="show_tutorials">Tutorials <i class="fa fa-angle-down"></i></a>
     <a href="javascript:;" id="show_settings">Settings <i class="fa fa-caret-square-o-down"></i></a>
     <div id="tutorials_internal">
         <div id="listening_tutorials">
             <ul>
             <li><a href="http://media.youbrandinc.com/curation_suite/Listening-Engine-User-Guide.pdf" title="" target="_blank"><i class="fa fa-link"></i> Download Manual</a> - PDF Listening Engine Manual</li>
             <li><a href="javascript:;" id="RQrsFcCckrM" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Setting Up the Listening Engine</a> - Overview on how to setup the Listening Engine</li>
             <li><a href="javascript:;" id="aJ5Tp20gGeU" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Using the Listening Engine</a> - Overview of using the Listening Engine</li>
             <li><a href="javascript:;" id="D2_CffwsYlU" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Video Overview</a> - Overview of Video Content</li>
             <li><a href="javascript:;" id="8-erByTQrhs" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Blocking Content & Sources</a> - Block stories, websites, etc.</li>
             <li><a href="javascript:;" id="yp8D2MQaM5U" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Publishing Workflows Listening Engine</a> - Publishing tips</li>
             <li><a href="javascript:;" id="oh_0A1uSmEk" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Curate to Post</a> - Using the Curate to Post Feature</li>
             <li><a href="javascript:;" id="TAmNxZ6fTRQ" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Quick Curation Editor</a> - Using the Quick Editor</li>
             <li><a href="javascript:;" id="nBsyFZd5zmA" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Social Sharing with Listening Engine</a> - Sharing tips and advice</li>
             <li><a href="javascript:;" id="D2_CffwsYlU" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Keywords Tab</a> - How to use the Keywords tab</li>
             <li><a href="javascript:;" id="fdhEDlKhsMc" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Video Keywords</a> - Setting Up Video Discovery</li>
             <li><a href="javascript:;" id="VoKCxdImdEk" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Websites Tab</a> - How to use the Websites tab</li>
             <li><a href="javascript:;" id="nPfCdpZl70g" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Topic Sites Tab</a> - How to use the Topic Sites tab</li>
             <li><a href="javascript:;" id="lnDXpc3azV8" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Search Tab</a> - How to use the free search tab</li>
             <li><a href="javascript:;" id="O_pt0aWK9rQ" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Curated News Page</a> - How to setup the News Page feature</li>
             <li><a href="javascript:;" id="85eclfwSlxc" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Custom Curated RSS Feed</a> - How to use the news custom curated RSS feed</li>
             <li><a href="javascript:;" id="rQ7HRluT48o" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Trending RSS Feeds</a> - How to use the the Trending/Search RSS feeds</li>
             </ul>
         </div>
     </div>
     <div id="settings_internal">
     <div id="listening_settings">
	 <div id="settings_change_message">Click below to change settings</div>


         <div style="float: right; margin: 0 25px 0 0;">
             <?php if ( current_user_can('manage_options')): ?>
                 <p><input type="text" name="cu_api_key" id="cu_api_key_settings" class="regular-text ybi_cu_api_key form-control" value="<?php echo $api_key; ?>" />
                     <a href="javascript:;" class="cu_api_key_enter action button action" name="cu_api_key_settings">Enter API Key</a></p>
             <?php endif; ?>
             <label><input type="checkbox" class="cs_listening_option" rel="ybi_cs_hide_platform_dropdown" id="cs_listening_platform_platform_dropdown" name="ybi_cs_hide_platform_dropdown" value="1" <?php checked( $ybi_cs_hide_platform_dropdown, 1 ); ?> /> Check to Hide Platform Dropdown</label>
             <label><input type="checkbox" class="cs_listening_option" rel="ybi_cs_hide_platform_display_features" id="ybi_cs_hide_platform_display_features" name="ybi_cs_hide_platform_display_features" value="1" <?php checked( $ybi_cs_hide_platform_display_features, 1 ); ?> /> Show News Page Options</label>
             <label><input type="checkbox" class="cs_listening_option" rel="ybi_cs_search_on_change" id="ybi_cs_search_on_change" name="ybi_cs_search_on_change" value="1" <?php checked( $ybi_cs_search_on_change, 1 ); ?> /> Load Content on Sort (drop down) Change</label>
             <label><input type="checkbox" class="cs_listening_option" rel="ybi_cs_hide_shortcut_sidebar" id="ybi_cs_hide_shortcut_sidebar" name="ybi_cs_hide_shortcut_sidebar" value="1" <?php checked( $ybi_cs_hide_shortcut_sidebar, 1 ); ?> /> Hide Shortcuts Sidebar</label>
             <p><a href="javascript:;" class="save_platform_defaults button action">Click to Save Current Drop Down Search Values as Default</a></p>
         </div>
         <div style="float: right;margin:0 40px 0 0;">
             <h4>Curate to Post/Quick Editor Settings</h4>
             <div>
                 <a href="javascript:;" id="oh_0A1uSmEk" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Curate to Post Tutorial</a>
                 <a href="javascript:;" id="oh_0A1uSmEk" class="cs_tutorial_popup qe_tutorial" title=""><i class="fa fa-video-camera"></i> Quick Editor Tutorial</a>
             </div>
             <label>Quick Post Publish Type
                 <select id="ybi_cs_quick_post_publish_type" name="ybi_cs_quick_post_publish_type" class="cs_listening_option" rel="ybi_cs_quick_post_publish_type">
                     <?php
                     $cu_draft_options_arr = array('draft' => 'Draft', 'publish' => 'Publish','pending' => 'Pending');
                     foreach ($cu_draft_options_arr as $key => $value) { ?>
                         <option value="<?php echo $key; ?>" <?php selected($ybi_cs_quick_post_publish_type, $key, true); ?>><?php echo $value; ?></option>
                     <?php } ?>
                 </select>
             </label>
             <label><input type="checkbox" class="cs_listening_option" rel="ybi_cs_draft_image_feature" id="ybi_cs_draft_image_feature" name="ybi_cs_draft_image_feature" value="1" <?php checked( $ybi_cs_draft_image_feature, 1 ); ?> /> Set Featured Image on Quick Post</label>
             <label><input type="checkbox" class="cs_listening_option" rel="ybi_cs_draft_blockquote_off_feature" id="ybi_cs_draft_blockquote_off_feature" name="ybi_cs_draft_blockquote_off_feature" value="1" <?php checked( $ybi_cs_draft_blockquote_off_feature, 1 ); ?> /> Turn Off Blockquote on Quick Post</label>
             <label>Quick Post Link Text: <input type="text" class="cs_listening_option" rel="ybi_cs_draft_link_text" id="ybi_cs_draft_link_text" name="ybi_cs_draft_link_text" value="<?php echo $ybi_cs_draft_link_text; ?>" /></label>
             <label>Quick Post Categories Display
                 <select id="ybi_cs_click_draft_action_type" name="ybi_cs_click_draft_action_type" class="cs_listening_option" rel="ybi_cs_click_draft_action_type">
                     <?php
                     $cu_draft_options_arr = array('mouseenter'=>'Mouse Over','click'=>'On Click');
                     foreach($cu_draft_options_arr as $key => $value) { ?>
                         <option value="<?php echo $key; ?>" <?php selected( $ybi_cs_click_draft_action_type, $key, true); ?>><?php echo $value; ?></option>
                     <?php 	} ?>
                 </select>
             </label>
             <label>Video Content:
                 <select id="ybi_cs_click_draft_video_actions" name="ybi_cs_click_draft_video_actions" class="cs_listening_option" rel="ybi_cs_click_draft_video_actions">
                     <?php
                     $cu_draft_options_arr = array('embed_video_with_thumbnail'=>'Embed Video with Thumbnail','embed_video'=>'Just Embed Video','embed_video_feature_thumbnail'=>'Embed Video Thumbnail Featured Image',
                         'link_to_video'=>'Link to Video');
                     foreach($cu_draft_options_arr as $key => $value) { ?>
                         <option value="<?php echo $key; ?>" <?php selected( $ybi_cs_click_draft_video_actions, $key, true); ?>><?php echo $value; ?></option>
                     <?php 	} ?>
                 </select>
             </label>
             <br />
         </div>
     </div>
 </div>
 </div>
     <div style="text-align:center;" id="listening_meta_control">
    <?php include_once( YBI_CURATION_SUITE_PATH . 'listening/listening-meta.php' ); ?> 
    <input type="hidden" id="cu_current_display_page" name="cu_current_display_page" value="listening-page" />
    <input type="hidden" id="curated_platform_id" name="curated_platform_id" value="<?php echo $default_platform_id; ?>" />
    <input type="hidden" id="cu_current_display_page" name="cu_current_display_page" value="post-page" />
    </div>
<?php 
/*	$platform_id = trim($_POST['platform_id']);
	$topic_id = trim($_POST['topic_id']);
	$time_frame  = trim($_POST['time_frame']);
	$social_sort  = trim($_POST['social_sort']);
	$platform_sources  = trim($_POST['platform_sources']);
	$load_direct_share  = trim($_POST['load_direct_share']) == 'true';
	$start = trim($_POST['start']);
	$current_page = 0;
	*/
	// this is set in the listening-meta.php
	$search_url_arr = array('search',$platform_id,$topic_id,urlencode($time_frame),$article_sort,$platform_sources,'0,20');
    $show_articles = $ybi_cs_show_articles_checkbox == 1 ? 1 : 0;
    $show_videos = $ybi_cs_show_videos_checkbox == 1 ? 1 : 0;
    $param_arr = array('show_articles' => $show_articles,'show_videos' => $show_videos, 'video_sort'=> $video_sort);
    $data = ybi_curation_suite_api_call('',$param_arr, $search_url_arr);
    //$data = json_decode($JSON, true);
	//var_dump($data);
    $total = 0;
    if(array_key_exists('total',$data))
        $total = $data['total'];

    $status = 'failure';
    $api_status = '';
    $api_message = '';
    if(array_key_exists('status',$data)) {
        $status = $data['status'];
        $api_status = $data['status'];
        $api_message = $data['message'];
    }

    if($status == 'success') {
        $results = $data['results'];
    } else {

    }
 ?>
   <div class="rcp-ajax waiting" style="margin-top: 8px; display: none; text-align:center; font-size: 30px; clear:both; overflow: visible;"><i class="fa fa-spinner fa-spin"></i></div>
   <div><h2 id="content_title"></h2></div>
<span id="cs_le_shortcuts">
    <div id="cs_le_shotcuts_header"><i class="fa fa-bars"></i> <i class="fa fa-arrows-v"></i></div>
<ul>
    <li><a href="javascript:;" class="go_top"><i class="fa fa-angle-double-up"></i> Go To Top</a></li>
     <li><a href="javascript:;" class="ignore_all_content remove_red"><i class="fa fa-minus-circle"></i> Ignore All</a></li>
</ul>
    <div id="cs_le_shortcuts_message"></div>
</span>
    <div id="ybi_curation_suite_listening_links">
        <?php
        echo add_api_message_controller('reading_page',$api_status,$api_message); ?>
        <?php if($status == 'success'): ?>
            <?php if($topic_count == 0):
                //echo '<p>topic_count: ' . $topic_count . '</p>';
                ?>
                <div style="text-align: center; width: 100%;">
                    <h2>Setting Up Your Platform - Quick Start:</h2>
                    <p><iframe width="640" height="360" src="https://www.youtube.com/embed/RQrsFcCckrM" frameborder="0" allowfullscreen></iframe></p></div>
            <?php endif; ?>

        <div id="curation_links_pages">Total: <span id="ybi_lp_total"><?php echo count($results); ?></span></div>
        <!-- start your query before the .brick element -->
        <?php
        	$post_list = '';
	        foreach($results as $ContentItem)
	        {
		        // this function is located in the admin ajax, so there is only one place we have to modify it.
		        $post_list .= ybi_listening_page_content($platform_id, $ContentItem);
	        }
        ?>
        <?php
                echo $post_list;
            endif;  // status=success
        ?>
    </div>
 </div>
    <span class="notice_message"></span>
    <?php

    $num_of_columns = 1;
    $popup_width = 140;

    if($category_count > 8) {
        $num_of_columns = 2;
        $popup_width = 350;
    }
    if($category_count > 15) {
        $num_of_columns = 3;
        $popup_width = 400;
    }
    if($category_count > 21) {
        $num_of_columns = 4;
        $popup_width = 500;
    }
    ?>
    <span class="po_cats" style="width: <?php echo $popup_width; ?>px;">
        <p class="cs_draft_cats_title">Choose Post Category:</p>

        <?php
        $html = '<table cellpadding="0" cellspacing="2" width="100%">';
        $i = 0;
        $columns = 1;
        $html .= '<tr><td colspan="'.$num_of_columns.'" class="cs_le_qe_row"><a href="javascript:;" class="le_show_quick_editor cs_le_blog_cat_list"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Quick Editor</a></td></tr>';
        //$html .= '<a href="javascript:;" class="le_show_quick_editor"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Quick Editor</a>';
        foreach ($categories as $category) {
            if($columns == 1)
                $html .= '<tr>';
            //if($i>0)
              //  $html .= ' <i class="fa fa-circle"></i>';
            $html .= '<td><a href="javascript:;" class="cu_create_draft cu_cat_draft_item cs_le_blog_cat_list" category_id="'.$category->term_id .'">'. $category->cat_name . '</a></td>';

            if($columns == $num_of_columns) {
                $html .= '</tr>';
                $columns = 0;
            }
            $i++;
            $columns++;
        }
        $html .= '</table>';
        echo $html;
        ?>

    </span>
</div>
<script>
    window.intercomSettings = {
        name: "<?php
			global $current_user;
            wp_get_current_user();
            echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?>",
        email: "<?php echo bloginfo('admin_email'); ?>",
        'site_url' : "<?php echo bloginfo('url'); ?>",
        'cs_license' : "<?php echo get_option('curation_suite_license_key'); ?>",
        'api_key' : "<?php echo $api_key ?>",
        'cs_version' : "<?php echo CURATION_SUITE_VERSION; ?>",
        // TODO: The current logged in user's sign-up date as a Unix timestamp.
        created_at: <?php echo time(); ?>,
        app_id: "jrf8edny"
    };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/jrf8edny';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>