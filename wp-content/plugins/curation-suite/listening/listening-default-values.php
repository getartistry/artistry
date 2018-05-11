<?php
$new_api_key = false;
// check to see if the api_key is in the url
$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';
if($api_key)
{
    update_option('curation_suite_listening_api_key',sanitize_text_field($api_key));
    $new_api_key = true; // this is set so we ignore any values defaulted
}
$api_key = get_option('curation_suite_listening_api_key');
//curation_suite_data
//delete_option('curation_suite_data');
$default_platform_id = 0;
$ybi_cs_hide_platform_dropdown = 0;
$ybi_cs_hide_platform_dropdown = get_option("ybi_cs_hide_platform_dropdown");
$ybi_cs_hide_platform_display_features = get_option("ybi_cs_hide_platform_display_features");
$ybi_cs_search_on_change = get_option("ybi_cs_search_on_change");
$le_strict_date_limit = get_option("le_strict_date_limit");
$ybi_cs_draft_image_feature = get_option("ybi_cs_draft_image_feature");
$ybi_cs_draft_blockquote_off_feature = get_option("ybi_cs_draft_blockquote_off_feature");
$ybi_cs_draft_link_text = '';
if(get_option("ybi_cs_draft_link_text")) {
    $ybi_cs_draft_link_text = get_option("ybi_cs_draft_link_text");
}
$ybi_cs_hide_shortcut_sidebar = get_option("ybi_cs_hide_shortcut_sidebar");
$ybi_cs_click_draft_action_type = get_option("ybi_cs_click_draft_action_type");
$ybi_cs_quick_post_publish_type = get_option("ybi_cs_quick_post_publish_type");
$ybi_cs_click_draft_video_actions = get_option("ybi_cs_click_draft_video_actions");
$ybi_cs_show_articles_checkbox = 1;
$ybi_cs_show_videos_checkbox = 1;

	// these are the core default values
	$platform_id = 0;
	$topic_id = 'all';
	$time_frame  = '72-HOUR';
	$article_sort  = 'total_shares';
	$video_sort  = 'view_count';
	$platform_sources  = 'all';

	// if we are setting the api key via the url then we want to ignore the previous saved values and reset them
    if(!$new_api_key)
    {
        // below is the logic for when a user assigns default values
        $saved_default_arr = '';
        $saved_default_arr = get_option('ybi_cs_platform_defaults');
        if($saved_default_arr != '')
        {
            $saved_default_arr = explode(':',$saved_default_arr);
            $controller_key = '';
            $default_values_arr = array();
            foreach($saved_default_arr as $value)
            {
                if($controller_key == '')
                {
                    $controller_key = $value;
                }
                else
                {
                    $default_values_arr[$controller_key] = $value;
                    $controller_key = '';
                }
            }
            //var_dump($default_values_arr);
            if(is_array($default_values_arr))
            {
                $platform_id = intval($default_values_arr['platform_id']);
                $topic_id = ($default_values_arr['topic_id']);
                $time_frame  = $default_values_arr['time_frame'];
                if(array_key_exists('article_sort',$default_values_arr))
                    $article_sort  = $default_values_arr['article_sort'];
                if(array_key_exists('video_sort',$default_values_arr))
                    $video_sort  = $default_values_arr['video_sort'];
                $platform_sources  = $default_values_arr['platform_sources'];
            }
        }
    }
    else
    {
        update_option('ybi_cs_platform_defaults','');
    }