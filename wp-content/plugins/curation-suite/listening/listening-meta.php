<?php
// get the api key
//delete_option('curation_suite_listening_api_key');
$data = null;
if ($api_key):
    $action_parms = array('get-organization-platforms');
    $data = ybi_curation_suite_api_call('', array(), $action_parms);
    //echo $data['url'];
endif;
$cs_le_allow_user_search = false;
if ($data && array_key_exists('status', $data) && $data['status'] == 'success'):
    $platforms = $data['results'];
    $Organization = $data['organization'];
    if ($Organization) {
        $cs_le_allow_user_search = $Organization['allow_text_search'] == 1;
    }

    $platform_arr = array();
    $topic_arr = array('all' => 'All Topics'); // add all to the topic array

    $platform_count = 0;
    $default_timeframe_max_value = 7;

    foreach ($platforms as $platform) {
        $id = $platform['id'];

        //this is setting the default platform id
        if ($platform_count == 0 && $platform_id <= 0)  // also the platform_id might have been setting in the listening-default-values.php
            $platform_id = $id;

        $this_platform_id = $id;
        $platform_arr[$id] = $platform['platform_name'];
        $topics = $platform['topics'];
        $topic_name = '';
        $topic_count = 0;
        if (is_array($topics)) {
            foreach ($topics as $topic) {
                // hidden input box with class definition so we can load the topics for each specific platform
                $topic_name = $topic['name'];
                $platform_topic_name = $topic['platform_topic_name']; // this is the platform topics title or name which can be different
                if ($platform_topic_name != '')
                    $topic_name = $platform_topic_name;
                echo '<input type="hidden" cu_topic_key="' . $topic['id'] . '" value="' . $topic_name . '" class="platform_topics_' . $this_platform_id . '" />';
                $this_topic_id = $topic['id'];


                // we only fill the first platform topics so if this is still the first plaform add it to the array used below
                if ($this_platform_id == $platform_id) {
                    $topic_arr[$this_topic_id] = $topic_name;
                }
                $topic_count++;
            }
        }

        $temp_source_block_timeframe = $platform['PlatformSettings']['data']['temp_source_block_timeframe'];
        $platform_max_timeframe_value = $platform['max_timeframe_value'];
        if ($platform_max_timeframe_value < 0)
            $platform_max_timeframe_value = 7;

        if ($this_platform_id == $platform_id && $platform_max_timeframe_value > 0)
            $default_timeframe_max_value = $platform_max_timeframe_value;

        $system_message = '';
        if (array_key_exists('system_message', $data))
            $system_message = $data['system_message'];

        echo '<input type="hidden" value="' . $platform_max_timeframe_value . '" class="platform_' . $this_platform_id . '_max_timeframe_value" />';
        echo '<input type="hidden" value="' . $temp_source_block_timeframe . '" class="platform_' . $this_platform_id . '_temp_block_timeframe" />';
        $platform_count++;

    }

    ?>

    <br/>
    <?php if ($system_message != ''): ?>
    <div class="le_message le_notice"><?php echo $system_message; ?></div>
<?php endif; ?>

    <style type="text/css">
        <?php if($ybi_cs_hide_platform_dropdown==1): ?>
        #cu_listening_platform_id {
            display: none;
        }

        <?php endif; ?>
        <?php if($ybi_cs_hide_shortcut_sidebar==1): ?>
        #cs_le_shortcuts {
            display: none;
        }

        <?php endif; ?>
    </style>
    <div id="le_access_tabs">
        <ul>
            <li id="le_main_control_tab"><a href="#le_main_control"><i class="fa fa-th-list"></i> Platform</a></li>
            <li id="cs_le_user_saved_keyword_tab"><a href="#cs_le_user_saved_keyword"><i class="fa fa-text-width"></i> Keywords</a></li>
            <li id="cs_le_user_saved_domain_name_id_tab"><a href="#cs_le_user_saved_domain_name_id"><i class="fa fa-compass"></i> Websites</a></li>
            <li id="cs_le_topic_websites_tab"><a href="#cs_le_topic_websites"><i class="fa fa-compass"></i> Topic Sites</a></li>
            <?php if ($cs_le_allow_user_search): ?>
                <li id="cs_le_user_saved_user_search_term_wrapper_tab"><a href="#cs_le_user_saved_user_search_term_wrapper"><i class="fa fa-search"></i> Search</a></li><?php endif; ?>
        </ul>
        <div id="cs_le_topic_websites" class="le_access_option">
            <?php echo cs_html_get_tutorial_link('nPfCdpZl70g', 'Topic Sites Tutorial'); ?>
            <div id="cs_le_topic_websites_int">
                <div class="ui-select">
                    <select id="cu_le_website_topic_id" name="cu_listening_platform_id" class="">
                        <?php
                        foreach ($topic_arr as $key => $value) {
                            if ($key == 'all')
                                continue;
                            ?>
                            <option value="<?php echo $key; ?>" <?php selected($topic_id, $key, true); ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
            <span id="cu_le_discover_sort_w">
                <div class="ui-select">
                    <select id="cu_le_discover_sort" name="cu_le_discover_sort">
                        <?php
                        $form_options = array(
                            'topic_feeds' => 'Just Your Feeds',
                            'domain_name' => 'Alphabetical',
                            'moz_score' => 'MOZ Score',
                            'avg_share' => 'Average Total Shares',
                            'published_date' => 'Latest Content'
                        );
                        foreach ($form_options as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected('', $key, true); ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </span>
                <a href="javascript:;" class="cu_le_topic_sites_load button action">Load Sites</a>
            </div>
            <div id="cs_le_topic_sites_list"></div>
        </div>
        <div id="le_main_control" class="le_access_option">
            <div style="width: auto;text-align: left;">
                <div class="ui-select">
                    <select id="cu_listening_platform_id" name="cu_listening_platform_id">
                        <?php
                        foreach ($platform_arr as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($platform_id, intval($key), true); ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="ui-select">
                    <select id="cu_platform_sources" name="cu_listening_platform_id" style="font-family:'FontAwesome', Arial;">
                        <?php
                        /*'just_platform_sources' => 'Just Subscriptions',
                        'ignore_platform_sources' => 'Ignore Subscriptions',*/
                        $form_options = array(
                            'all' => '&#xf022;  All Sources',
                            'just_feeds' => '&#xf143;  Just Feeds',
                            'just_keywords' => '&#xf035;  Just Keywords',
                            'saved_content_items' => '&#xf097  Saved Content',
                            'platform_control' => '&#xf085;  Platform Control'
                        );
                        // if the user has chosen to display the platform display options we don't show it in the dropdown, this removes it
                        if ($ybi_cs_hide_platform_display_features)
                            $form_options['platform_display'] = '&#xf009 News Display';

                        foreach ($form_options as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($platform_sources, $key, true); ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
    <span id="sub_platform_control_parameters">
        <div class="ui-select">
            <select id="sub_platform_control_item" name="sub_platform_control_item" class="auto_search_on_change">
                <?php
                $form_options = array(
                    'platform_setup' => 'Platform Setup',
                    'blocked_sources' => 'Blocked Sources',
                    'ignored_content' => 'Ignored Content',
                    'curated_content' => 'Curated Content',
                    'shared_content' => 'Shared Content',
                );
                // if the user has chosen to display the platform display options we don't show it in the dropdown, this removes it
                foreach ($form_options as $key => $value) { ?>
                    <option value="<?php echo $key; ?>" <?php selected('', $key, true); ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </div>
    </span>
    <span id="date_sort_parameters">
        <div class="ui-select">
            <select id="cu_date_sort" name="cu_date_sort">
                <?php
                $form_options = array(
                    'DESC' => 'Recent First',
                    'ASC' => 'Oldest First'
                );
                foreach ($form_options as $key => $value) { ?>
                    <option value="<?php echo $key; ?>" <?php selected('', $key, true); ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </div>
    </span>
        <span class="sub_search_parameters">
        <div class="ui-select topic_dd_w">
        <select id="cu_listening_topic_id" name="cu_listening_platform_id" class="auto_search_on_change">
        <?php
        foreach ($topic_arr as $key => $value) { ?>
            <option value="<?php echo $key; ?>" <?php selected($topic_id, $key, true); ?>><?php echo $value; ?></option>
        <?php } ?>
        </select>
        </div>
        <div class="ui-select">
        <select id="cu_time_frame" name="cu_listening_platform_id" class="auto_search_on_change">
        <?php
        //'2-HOUR' => 'Last 2 Hours',
        $form_options_hours = array(
            '6-HOUR' => 'Last 6 Hours',
            '12-HOUR' => 'Last 12 Hours',
            '24-HOUR' => 'Last 24 Hours',
            '48-HOUR' => 'Last 48 Hours',
            '72-HOUR' => 'Last 3 Days',
        );
        foreach ($form_options_hours as $key => $value) { ?>
            <option value="<?php echo $key; ?>" <?php selected($time_frame, $key, true); ?>><?php echo $value; ?></option>
        <?php }
        $form_options_days = array(
            '5' => 'Last 5 Days',
            '7' => 'Last 7 Days',
            '14' => 'Last 14 Days',
            '21' => 'Last 21 Days',
            '30' => 'Last 30 Days',
            '45' => 'Last 45 Days',
            '60' => 'Last 60 Days',
            '90' => 'Last 90 Days',
            //$default_timeframe_max_value
        );
        foreach ($form_options_days as $key => $value) {
            if ($key <= $default_timeframe_max_value) {
                ?>
                <option value="<?php echo $key; ?>-DAY" <?php selected($time_frame, $key, true); ?>><?php echo $value; ?></option>
            <?php }
        } ?>
        </select>
        </div>

		</span>
        <span id="platform_diplay_parameters">
            <div class="ui-select">
        <select id="cu_platform_display_parameters" name="cu_platform_display_parameters">
        <?php
        $default_display = 'active';
        $form_options = array('active' => 'Active Display', 'commentary_not_active' => 'With Commentary Not Active', 'featured' => 'Featured');
        foreach ($form_options as $key => $value) { ?>
            <option value="<?php echo $key; ?>" <?php selected($default_display, $key, true); ?>><?php echo $value; ?></option>
        <?php } ?>
        </select>
                </div>
        </span>
                <div style="clear: both;overflow: hidden; margin: 0 0 5px 0;" class="sub_search_parameters">
                    <div id="article_options">
                        <?php
                        $ybi_cs_show_articles_checkbox = get_option('ybi_cs_show_article_checkbox');
                        ?>
                        <div class="ui-select">
                            <input type="checkbox" id="show_article_checkbox"
                                   class="cs_save_setting" rel="ybi_cs_show_article_checkbox"
                                   value="1" <?php checked($ybi_cs_show_articles_checkbox, 1); ?> /><label>Articles</label>
                        </div>
                        <div class="ui-select">
                            <select id="cu_social_sort" name="cu_listening_platform_id" class="auto_search_on_change cu_mid_opt selectpicker" data-style="btn-primary" style="font-family:'FontAwesome', Arial;">
                                <fieldset>
                                    <?php
                                    $form_options = cs_le_get_sort_values(true);
                                    foreach ($form_options as $key => $value) { ?>
                                        <option class="<?php echo $key; ?> fa" value="<?php echo $key; ?>" <?php selected($article_sort, $key, true); ?> style="font-family:FontAwesome, Arial;"><?php echo $value; ?></option>
                                    <?php } ?>
                                </fieldset>
                            </select>
                        </div>
                    </div>
                    <div id="video_options">
                        <?php
                        $ybi_cs_show_videos_checkbox = get_option('ybi_cs_show_videos_checkbox');
                        ?>
                        <div class="ui-select">
                            <input type="checkbox" id="show_video_checkbox"
                                   class="cs_save_setting" rel="ybi_cs_show_videos_checkbox"
                                   value="1" <?php checked($ybi_cs_show_videos_checkbox, 1); ?> /><label>Videos</label>
                        </div>
                        <div class="ui-select">
                            <select id="cu_video_sort" name="cu_video_sort" class="auto_search_on_change cu_mid_opt selectpicker" data-style="btn-primary" style="font-family:FontAwesome, Arial;">
                                <fieldset>
                                    <?php
                                    $form_options = cs_le_get_video_sort_values(true);
                                    foreach ($form_options as $key => $value) { ?>
                                        <option class="<?php echo $key; ?> fa" value="<?php echo $key; ?>" <?php selected($video_sort, $key, true); ?> style="font-family:FontAwesome, Arial;"><?php echo $value; ?></option>
                                    <?php } ?>
                                </fieldset>
                            </select>
                            <input type="checkbox" id="load_video_player" value=""/><label>Load Player</label>
                        </div>
                    </div>

                </div>
                <a href="javascript:;" class="cu_listening_load button action">Load</a> <input type="checkbox" id="le_strict_date_limit" value="" class="cs_listening_option" rel="le_strict_date_limit" <?php checked( $le_strict_date_limit, 1 ); ?> /><label>Limit by Timeframe</label>
                <div style="clear: both; overflow: auto; margin: 0 auto;"></div>
            </div>
        </div><!--le-main-control-->
        <div id="cs_le_user_saved_search_items">
            <?php
            //delete_option('cs_le_detail_search_saves_keyword_'.$platform_id);
            //delete_option('cs_le_detail_search_saves_domain_name_id_'.$platform_id);
            //delete_option('cs_le_detail_search_saves_user_search_term_'.$platform_id);
            ?>
            <div id="cs_le_user_saved_keyword" class="le_access_option">
                <span class="refresh_le_items"><a href="javascript:;" class="reload_detail_search_item" data-search-type="keyword"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Keywords</a></span>
                <div class="results"><?php echo get_cs_le_search_detail_saved_items_html('keyword', $platform_id); ?></div>
                <!--<span><a href="javascript:;" class="reload_detail_search_item" data-search-type="keyword">refresh keywords</a> </span>-->
            </div>
            <div id="cs_le_user_saved_domain_name_id" class="le_access_option">
                <span class="refresh_le_items"><a href="javascript:;" class="reload_detail_search_item" data-search-type="feed"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Feed Sites</a></span>
                <div class="results"><?php echo get_cs_le_search_detail_saved_items_html('domain_name_id', $platform_id); ?></div>
                <!--<div class="remove_saved_items_wrapper">
                    <a href="javascript:;" class="remove_detail_saved_items" data-type="">Remove All</a>
                </div>-->
            </div>
            <?php if ($cs_le_allow_user_search): ?>
                <div id="cs_le_user_saved_user_search_term_wrapper" class="le_access_option">

                    <?php echo cs_html_get_tutorial_link('lnDXpc3azV8', 'Free Search Tutorial'); ?>
                    <div id="cs_le_user_saved_user_search_term" class="le_access_option"><?php echo get_cs_le_search_detail_saved_items_html('user_search_term', $platform_id); ?></div>

                    <div id="bucket_links_search_wrapper">
                        <div class="input-group margin-bottom-sm">
                            <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                            <input type="text" name="le_search_term" id="le_content_search_term" class="regular-text cs_le_search_term form-control"/>
                            <select id="le_search_term_element" name="cu_listening_platform_id" class="cu_mid_opt">
                                <?php
                                $form_options = array('title' => 'Title', 'snippet' => 'Snippet/Body', 'all' => 'All');
                                $default_user_keyword_search_type = 'title';
                                foreach ($form_options as $key => $value) { ?>
                                    <option class="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php selected($default_user_keyword_search_type, $key, true); ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <select id="le_search_term_content_type" name="le_search_term_content_type" class="cu_mid_opt">
                                <?php
                                $form_options = array('all-content-type' => 'All', 'article' => 'Articles', 'video' => 'Videos');
                                $default_user_keyword_search_type = 'all-content-type';
                                foreach ($form_options as $key => $value) { ?>
                                    <option class="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php selected($default_user_keyword_search_type, $key, true); ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <a href="javascript:;" class="cs_le_content_keyword_search button action">Search</a>
                        </div>
                    </div>

                    <p style="margin: 0 20%;"><i>This searches our Listening Engine Cloud. There is no guarantee that the content you are searching for is in our system.
                        If you want to guarantee content is found for a search term or keyword make sure you setup keyword discovery in your Platform Setup</i></p>
                </div>
            <?php endif; ?>
        </div>
    </div><!--le_access_tabs-->
    <div id="dialog-confirm" title="Remove Item?"><p>Confirm deletion of <span id="deletion_detail_item_type"></span>: <span id="deletion_detail_item"></span></p></div>
    <div id="dialog-add-narrative" title="Add Narrative Block?">
        <p style="margin: 0px 0 17px 0;display: inline-block;float: right; ">Watch tutorial before use: <a href="javascript:;" id="KM5FRjcHs84" class="cs_tutorial_popup" title="" style="text-decoration: none;color:#0073aa;"><i class="fa fa-video-camera"></i> Full Tutorial</a></p>
        <h4 id="narrative_title_selection"></h4>
        <p id="narrative_snippet_selection"></p>
        <hr />
        <a href="javascript:;" id="reset_narrative_items" class="cs_bad" style="float: right;"><i class="fa fa-refresh" aria-hidden="true"></i> Start Over</a>
        <input type="text" id="narrative_keyword_1" class="narrative_item" value="" readonly="readonly" style="margin-left: 68px;" /><br />
        <select id="narrative_select_1" name="narrative_select_1" class="narrative_item">
            <option class="" value="AND">AND</option><option class="" value="OR">OR</option>
        </select>
        <input type="text" id="narrative_keyword_2" class="narrative_item" value="" readonly="readonly" /><br />
        <select id="narrative_select_2" name="narrative_select_2" class="narrative_item">
            <option class="" value="AND">AND</option><option class="" value="OR">OR</option>
        </select>
        <input type="text" id="narrative_keyword_3" class="narrative_item" value="" readonly="readonly" />

        <div id="narrative_result"></div>
        Weight:
        <select id="narrative_strength_select" name="narrative_strength_select" class="">
            <option class="" value="title">Weak</option><option class="" value="all">Strong</option>
        </select>
        Timeframe:
        <select id="narrative_time_frame" name="narrative_time_frame" class="">
            <option class="" value="24-HOUR">1 Day</option><option class="" value="48-HOUR">2 Days</option><option class="" value="72-HOUR">3 Days</option><option class="" value="7-DAY">1 Week</option>
            <option class="" value="FOREVER">Forever</option>
        </select>
    </div>
    <div id="dialog-le-quick-editor" title="Curate to Post Quick Editor">
        <p style="margin: 0;display: inline-block;float: right; "><a href="javascript:;" id="TAmNxZ6fTRQ" class="cs_tutorial_popup" title="" style="text-decoration: none;color:#0073aa;"><i class="fa fa-video-camera"></i> Quick Overview</a>&nbsp;&nbsp;
            <a href="javascript:;" id="pMecC117CBg" class="cs_tutorial_popup" title="" style="text-decoration: none;color:#0073aa;"><i class="fa fa-video-camera"></i> Full Tutorial</a></p>
        <input type="hidden" value="" id="cs_le_qe_source_domain" class="cs_le_qe_reset" />
        <p id="cs_le_qe_title_label" class="cs_le_qe_bullet">Title</p>
        <input id="cs_le_qe_title" class="regular-text cs_le_qe_reset" spellcheck="true" autocomplete="off" type="text" value="" />
        <div id="sub_headline_row">
        <p id="" class="cs_le_qe_bullet">Sub Title (not required):</p>
        <input id="cs_le_qe_sub_headline" class="regular-text cs_le_qe_reset" type="text" value="" />
        </div>
        <div id="commentary_row">
            <div class="content_commentary_l">
                <p id="" class="cs_le_qe_bullet">Category:</p>
                    <?php
                    $args = array(
                        'show_option_all'    => '',
                        'show_option_none'   => '',
                        'option_none_value'  => '-1',
                        'orderby'            => 'ID',
                        'order'              => 'ASC',
                        'show_count'         => 0,
                        'hide_empty'         => 1,
                        'child_of'           => 0,
                        'exclude'            => '',
                        'include'            => '',
                        'echo'               => 1,
                        'selected'           => 0,
                        'hierarchical'       => 0,
                        'name'               => 'cat',
                        'id'                 => 'cs_le_qe_category_id',
                        'class'              => 'cs_le_qe_reset',
                        'depth'              => 0,
                        'tab_index'          => 0,
                        'taxonomy'           => 'category',
                        'hide_if_empty'      => false,
                        'value_field'	     => 'term_id',
                    );
                    wp_dropdown_categories( $args );
                    ?>
                <p id="" class="cs_le_qe_bullet">Publish Type:</p>
                <select id="ybi_cs_quick_post_publish_type_qe" name="ybi_cs_quick_post_publish_type_qe">
                    <?php
                    $cu_draft_options_arr = array('draft' => 'Draft', 'publish' => 'Publish','pending' => 'Pending');
                    foreach ($cu_draft_options_arr as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php selected($ybi_cs_quick_post_publish_type, $key, true); ?>><?php echo $value; ?></option>
                    <?php } ?>
                </select>

            </div>
        <div class="content_commentary_r"><p><span id="cs_le_qe_image_option"></span></p></div>
        </div>
            <div id="cs_le_qe_tags_row">
                <p id="" class="cs_le_qe_bullet">Tags <span style="font-weight: normal">(seperate with comma)</span>:</p>
                <input id="cs_le_qe_tags" class="regular-text cs_le_qe_reset" type="text" value="" />
            </div>
            <textarea id="cs_le_qe_curated_content_editor" class="mceEditor" style="width: 98%;"></textarea>
    </div>

<?php else: ?>
    <div id="cu_api_key_entry">
        <?php if ($data && array_key_exists('message', $data)): ?>
            <p id="api_error_message"><?php echo $data['message']; ?></p>
        <?php endif; ?>
        <div>
            <p class="api_message">Learn more about the <a href="http://curationsuite.com/listening-engine/" target="_blank">Curation Suite&trade; Listening Engine</a>.</p>
            <div class="le_enter_api_w" style="text-align: center;">

                <h3>Please enter your Listening Engine API key:</h3>

                <div id="api_enter_control">
                    <div id="api_enter_inner">
                        <input type="text" name="cu_api_key" id="cu_api_key" class="regular-text ybi_cu_api_key form-control" value="<?php echo $api_key; ?>"/>
                        <a href="javascript:;" class="cu_api_key_enter action button action">Enter API Key</a>
                    </div>
                </div>
            </div>
            <div id="api_explanation">
                <p>If you've purchased the Listening Engine please <a href="https://members.youbrandinc.com/dashboard/listening-engine/" target="_blank">visit this page to get your Listening Engine API Key</a>.</p>

                <div style="text-align: center; width: 100%;">
                    <h2>Getting Your API Key:</h2>
                    <p>
                        <iframe width="640" height="360" src="https://www.youtube.com/embed/87boRUO8tPk" frameborder="0" allowfullscreen></iframe>
                    </p>
                </div>

                <h2>Don't Have the Listening Engine Yet?</h2>
                <p class="api_message">Learn more about the <a href="http://curationsuite.com/listening-engine/" target="_blank">Curation Suite&trade; Listening Engine</a>.</p>
            </div>
        </div>
    </div>
    <style type="text/css">
        #cs_le_shortcuts {
            display: none !important;
        }
    </style>
<?php endif; //if($data && $data['status'] == 'success') ?>