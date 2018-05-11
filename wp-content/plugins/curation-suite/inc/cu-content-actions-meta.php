<?php
//delete_option('curation_suite_listening_api_key');
$options = get_option('curation_suite_data');
$curated_platform_id = isset($_GET['platform_id']) ? ($_GET['platform_id']) : 0;
$content_item_id = isset($_GET['content_item_id']) ? ($_GET['content_item_id']) : 0;

$ybi_cs_hide_quick_add = 0;
$ybi_cs_remove_save_quick_add = 0;
$loadCSListening = 0;
$loadScrape = 0;
$curation_suite_direct_share_default = 1;
$curation_suite_headline_default = 0;
$curation_suite_blockquote_default = 0;
$curation_suite_link_attribution_location_default = '';
$cs_reddit_sort_default = 'new';
$cs_reddit_total_default = '10';
$cs_reddit_show_threads_default = 'threads';
$cs_reddit_time_frame_default = 'all';
$curation_suite_link_images_default = 0;
$image_credit_value_one_default = 'Image';
$image_credit_value_two_default = 'from';


if (isset($options) && is_array($options)) {
    if (array_key_exists('curation_suite_scraping_feature', $options))
        $loadScrape = get_option('curation_suite_scraping_feature');

    if (array_key_exists('curation_suite_listening_platform', $options))
        $loadCSListening = $options['curation_suite_listening_platform'];

    if (array_key_exists('curation_suite_direct_share_default', $options))
        $curation_suite_direct_share_default = $options['curation_suite_direct_share_default'];
    if (array_key_exists('curation_suite_headline_default', $options))
        $curation_suite_headline_default = intval($options['curation_suite_headline_default']);
    if (array_key_exists('curation_suite_blockquote_default', $options))
        $curation_suite_blockquote_default = intval($options['curation_suite_blockquote_default']);

    if (array_key_exists('curation_suite_link_attribution_location_default', $options))
        $curation_suite_link_attribution_location_default = $options['curation_suite_link_attribution_location_default'];

    if (array_key_exists('cs_reddit_sort_default', $options))
        $cs_reddit_sort_default = $options['cs_reddit_sort_default'];

    if (array_key_exists('cs_reddit_total_default', $options))
        $cs_reddit_total_default = $options['cs_reddit_total_default'];

    if (array_key_exists('cs_reddit_show_threads_default', $options))
        $cs_reddit_show_threads_default = $options['cs_reddit_show_threads_default'];

    if (array_key_exists('cs_reddit_time_frame_default', $options))
        $cs_reddit_time_frame_default = $options['cs_reddit_time_frame_default'];

    if (array_key_exists('curation_suite_link_images_default', $options))
        $curation_suite_link_images_default = $options['curation_suite_link_images_default'];

    if (array_key_exists('image_credit_value_one_default', $options))
        $image_credit_value_one_default = $options['image_credit_value_one_default'];

    if (array_key_exists('image_credit_value_two_default', $options))
        $image_credit_value_two_default = $options['image_credit_value_two_default'];
}


$ybi_cs_hide_quick_add = get_option('ybi_cs_hide_quick_add');
$ybi_cs_no_image_quick_add = get_option('ybi_cs_no_image_quick_add');
$cs_highlight_curated_content = get_option('cs_highlight_curated_content');
$ybi_cs_remove_save_quick_add = get_option('ybi_cs_remove_save_quick_add');
$ybi_hide = '';
if ($ybi_cs_hide_quick_add == 1)
    $ybi_hide = ' ybi_hide'; // this adds this class to the css below

// this will be replaced to a blank string if the Listening Engine is activated, this controls which tab is first and displayed as activated
// if the LE is off then this will drop the class "first" on the search tab.
$search_first_class = ' first';
?>
<a href="" name="cu_create_curation"></a><span class="notice_message"></span>
<input type="hidden" id="curation_suite_image_credit_wrap_element" name="curation_suite_image_credit_wrap_element" value="<?php echo $options['curation_suite_image_credit_wrap_element']; ?>"/>
<input type="hidden" id="curation_suite_image_credit_wrap_class" name="curation_suite_image_credit_wrap_class" value="<?php echo $options['curation_suite_image_credit_wrap_class']; ?>"/>
<input type="hidden" id="image_credit_value_one" name="image_credit_value_one" value="<?php echo $image_credit_value_one_default; ?>"/>
<input type="hidden" id="image_credit_value_two" name="curation_suite_image_credit_wrap_class" value="<?php echo $image_credit_value_two_default; ?>"/>
<div class="curation_suite_meta_section">
    <div class="curation_suite_link_row"><?php if ($loadCSListening): $search_first_class = ''; ?><a href="javascript:;" name="cu_listening_platform" class="cu_tab show_content_div first">Listening</a><?php endif; ?><a href="javascript:;" name="content_search_block" class="cu_tab show_content_div<?php echo $search_first_class; ?>">Search</a><a href="javascript:;" name="visual_editor" class="cu_tab show_content_div" id="cu_visual_editor_tab_control">Curate</a><a href="javascript:;" name="link_buckets_load_row" class="cu_tab show_content_div">Links</a><?php if ($loadScrape): ?><a href="javascript:;" name="content_scrape_block" class="cu_tab show_content_div">CS Sources</a><?php endif; ?><a href="javascript:;" name="social_media_actions" class="cu_tab last show_content_div">Sharing</a></div>
    <input type="hidden" id="delete_curated_link_on_add_id" name="delete_curated_link_on_add_id" value=""/>
    <div class="curation_suite_inner_meta_wrap">
        <?php
        // this is the custom sizing for the sidebar
        $curation_suite_default_sidebar_width = 50;
        $sidebar_size_css = '';
        if (is_array($options) && array_key_exists('curation_suite_default_sidebar_width', $options))
            $curation_suite_default_sidebar_width = $options['curation_suite_default_sidebar_width'];

        if (is_numeric($curation_suite_default_sidebar_width)) {
            if ($curation_suite_default_sidebar_width == 641)
                $sidebar_size_css = $curation_suite_default_sidebar_width . 'px';
            else
                $sidebar_size_css = $curation_suite_default_sidebar_width . '%';
        } else {
            $curation_suite_default_sidebar_width = '50%';
        }
        if ($curation_suite_default_sidebar_width == '')
            $curation_suite_default_sidebar_width = '50';
        ?>
        <style type="text/css">
            @media screen and (min-width: 768px) {
                #ybi_cu_content_actions_work_meta {
                    width: <?php echo $sidebar_size_css; ?> !important;
                }
            <?php
            $on_demand_left = '';
            $content_search_box_width = 45;

            switch ($sidebar_size_css) {
                case 641:
                    $on_demand_left = '';
                    $content_search_box_width = 41;
                    break;
                case 40:
                    $on_demand_left = 74;
                    $content_search_box_width = 41;
                    break;
                case 45:
                    $on_demand_left = 78;
                    break;
                    break;
                case 50:
                    $on_demand_left = 77;
                    break;
                case 55:
                    $on_demand_left = 81;
                    break;
                case 60:
                    $on_demand_left = 82;
                    break;
                case 65:
                    $on_demand_left = 83;
                    break;
                case 70:
                    $on_demand_left = 84;
                    break;
                default:
                    $on_demand_left = 50;
            }
            echo '#ybi_cu_content_actions_work_meta .cs_on_demand_left {width: '.$on_demand_left.'%;} #content_search_term {width: '.$content_search_box_width.'%;';

                ?>
            }
        </style>
        <?php
        if ($loadCSListening) {
            include_once(YBI_CURATION_SUITE_PATH . 'listening/listening-default-values.php');
            ?>
            <div id="cu_listening_platform" class="content_action_div">
                <input type="hidden" id="curated_content_item_id" name="curated_content_item_id"
                       value="<?php echo $content_item_id; ?>" after-curation-action=""/>
                <input type="hidden" id="curated_platform_id" name="curated_platform_id"
                       value="<?php echo $curated_platform_id; ?>"/>
                <input type="hidden" id="cu_current_display_page" name="cu_current_display_page" value="post-page"/>

                <div id="platform_search_wrap">
                    <div style="clear: both; overflow: auto; margin: 0 0 10px 0;">
                        <div style="clear: both; overflow: auto; margin: 0 auto;">
                            <a href="javascript:;" id="saved_content_shortcut"><i class="fa fa-bookmark-o"></i> Saved Content</a>
                            <!--<label><input type="checkbox" class="cs_save_setting" rel="ybi_cs_hide_quick_add"
                                          id="ybi_cs_hide_quick_add" name="ybi_cs_hide_quick_add" selected_view="hide" value="1" <?php /*checked($ybi_cs_hide_quick_add, 1); */?> /> Hide Quick Add</label>

                            <label class="ybi_cs_hide_quick_add_block ybi_remove_on_save<?php /*echo $ybi_hide; // this is in cu-content-actions-meta.php */?>">
                                <input type="checkbox" class="cs_save_setting" rel="ybi_cs_remove_save_quick_add" id="ybi_cs_remove_save_quick_add" name="ybi_cs_remove_save_quick_add" value="1" <?php /*checked($ybi_cs_remove_save_quick_add, 1); */?> /> Remove on Quick Add</label>-->
                        </div>
                        <?php include_once(YBI_CURATION_SUITE_PATH . '/listening/listening-meta.php'); ?>
                        <style type="text/css">
                            #link_buckets_load_row {
                                text-align: left;
                                display: none;
                            }

                            <?php if($topic_count <= 1): ?>
                            .new_platform_notice {
                                display: inline-block !important;
                            }

                            <?php endif; ?>
                        </style>
                    </div>
                    <!--blank- added with Jquery-->
                    <div id="le_quick_add_block"></div>
                </div>
                <div class="rcp-ajax waiting">
                    <iclass
                    ="fa fa-spinner fa-spin"></i></div>
                <div id="ybi_curation_suite_listening_links" class="cs_results"></div>
                <input type="hidden" name="cu_current_listening_page" id="cu_current_listening_page" value=""/>
            </div>
        <?php } else { ?>
            <style type="text/css">
                #link_buckets_load_row {
                    text-align: left;
                    display: none;
                }

                #content_search_block {
                    display: block;
                }
            </style>
        <?php } ?>
        <div id="link_buckets_load_row" class="content_action_div">
            <div id="bucket_links_block">
                <div id="bucket_links_search_wrapper">
                    <div class="input-group margin-bottom-sm">
                        <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                        <input type="text" name="bucket_search_term" id="bucket_search_term" class="regular-text ybi_curation_bucket_links_search form-control"/>
                    </div>
                </div>
                <div style="clear: both; overflow: auto; margin: 0 0 20px 0;">
                <span class="search_links_wrapper">
                <?php
                $args = array(
                    'show_option_all' => __('Show All Link Buckets'),
                    'show_option_none' => '',
                    'order' => 'ASC',
                    'show_count' => 1,
                    'hide_empty' => 0,
                    'name' => 'bucket_category_id',
                    'id' => 'bucket_category_id',
                    'taxonomy' => 'link_buckets',
                    'hide_if_empty' => false,
                );
                wp_dropdown_categories($args); ?>
                    <select name="bucket_link_sort_order" id="bucket_link_sort_order">
                <option value="DESC"><?php _e("Newest First"); ?></option>
                <option value="ASC"><?php _e("Oldest First"); ?></option>
                </select>
                    <?php wp_dropdown_users(array('name' => 'author', 'id' => 'bucket_link_author_id', 'name' => 'bucket_link_author_id', 'show_option_all' => __('Show All Users'))); ?>
                    <a href="javascript:;" class="show_bucket_links button action">Load Bucket Links</a>


                    <?php $count_posts = wp_count_posts('curation_suite_links')->publish; ?>
                    </span>
                    <span style="margin-top: 10px;"><span id="total_posts_display_wrapper"><strong id="total_posts_display"><?php echo $count_posts; ?></strong></span><?php _e(" Total Links"); ?></span>
                </div>

            </div><!--bucket_links_block-->
            <div class="rcp-ajax waiting"><i class="fa fa-spinner fa-spin"></i></div>
            <div id="ybi_curation_suite_bucket_links"></div>
            <input type="hidden" name="cu_current_link_page" id="cu_current_link_page" value=""/>
        </div><!--bucket_links_block-->
        <div id="content_search_block" class="content_action_div">
            <div id="cu_user_search_keywords">
                <div id="find_content_top">

                    <div id="direct_share_wrap">
                        <label class="load_direct_share_lbl"><input type="checkbox" name="load_direct_share" id="load_direct_share_demand_search" <?php checked(1, $curation_suite_direct_share_default, true); ?> /><i class="fa fa-share-alt"></i> Load Direct Share</label>
                    </div>
                    <div id="edit_remove_toggle_wrap">
                        <a href="javascript:;" name="turn_on_remove_keywords" rel="on" class="turn_on_remove_keywords"><i class="fa fa-minus-circle"></i> Edit/Remove Keywords</a></div>
                </div>
                <div id="user_keywords_wrap">
                    <span><strong>Keywords</strong>: </span>
                    <span class="user_keywords_list">
			<?php
            $existing_keywords = get_option('curation_suite_user_keywords');
            $keyword_html = ' <em>no keywords saved</em>';
            $pieces = explode("||", $existing_keywords);
            $i = 0;
            if ($existing_keywords):
                $keyword_html = '';
                foreach ($pieces as $val) {
                    if ($i > 1)
                        $keyword_html .= ' | ';
                    $keyword_html .= '<a href="javascript:;" name="social_media_actions" class="find_content_keyword">' . stripslashes(htmlentities($val, ENT_QUOTES)) . '</a>';
                    $i++;
                }
            endif;
            ?>
            <?php echo $keyword_html; ?>
	         </span>
                </div>
            </div>
            <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
            <div id="bucket_links_search_wrapper">

                <div class="input-group margin-bottom-sm">
                    <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                    <input type="text" name="content_search_term" id="content_search_term" class="regular-text ybi_curation_content_search_term form-control"/>
                    <select name="search_type" id="search_type">
                        <option value="google_news"><?php _e("Google News"); ?></option>
                        <?php if($loadCSListening) { ?><option value="cs_le"><?php _e("Listening Engine"); ?></option><?php } ?>
                        <option value="curation_bot"><?php _e("Curation Bot"); ?></option>
                        <!--<option value="google_blog"><?php /*_e("Google Blog"); */ ?></option>-->
                        <option value="pocket"><?php _e("Pocket"); ?></option>
                        <option value="twitter"><?php _e("Twitter"); ?></option>
                        <!--<option value="yahoo_news"><?php /*_e("Yahoo News"); */ ?></option>-->
                        <option value="bing_news"><?php _e("Bing News"); ?></option>
                        <option value="pinterest"><?php _e("Pinterest"); ?></option>
                        <option value="instagram"><?php _e("Instagram"); ?></option>
                        <option value="reddit"><?php _e("Reddit"); ?></option>
                        <!--<option value="google_explore"><?php _e("Google Explore"); ?></option>-->
                        <option value="imgur"><?php _e("ImgUr"); ?></option>
                        <option value="giphy"><?php _e("Giphy"); ?></option>
                        <option value="youtube"><?php _e("YouTube"); ?></option>
                        <option value="daily_motion"><?php _e("Daily Motion"); ?></option>
                        <option value="slideshare"><?php _e("Slideshare"); ?></option>
                    </select>
                    <a href="javascript:;" class="content_keyword_search button action">Search</a>
                    <span class="save_keyword_wrap">
                <i class="fa fa-plus-circle"></i> <a href="javascript:;" name="add_keyword" class="add_keyword">Save Keyword</a>
                </span>
                </div>
                <div id="additional_search_parameters">

                    <span class="search_options_text">Search Options:</span>
                    <select name="orderby" id="orderby">
                        <option value="relevance"><?php _e("Relevance"); ?></option>
                        <option value="published"><?php _e("Latest/Published Date"); ?></option>
                        <option value="viewCount"><?php _e("View Count"); ?></option>
                        <option value="rating"><?php _e("Rating"); ?></option>
                    </select>
                    <select name="cs_total_results" id="cs_total_results">
                        <?php
                        $values_arr = array(
                            '10' => '10',
                            '25' => '25',
                            '40' => '40',
                            '50' => '50',
                            '75' => '75',
                            '100' => '100',
                        );
                        foreach ($values_arr as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($cs_reddit_total_default, $key); ?>><?php _e($value); ?></option>
                        <?php } ?>
                    </select>
                    <select name="cs_search_ignore" id="cs_search_ignore">
                        <?php
                        $values_arr = array(
                            'threads' => 'Show Threads',
                            'ignore-threads' => 'Ignore Threads',
                        );

                        foreach ($values_arr as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($cs_reddit_show_threads_default, $key); ?>><?php _e($value); ?></option>
                        <?php } ?>
                    </select>
                    <select name="cs_search_time_frame" id="cs_search_time_frame">
                        <?php
                        $values_arr = array(
                            'all' => 'All',
                            'hour' => 'Hour',
                            'day' => 'Day',
                            'week' => 'Week',
                            'month' => 'Month',
                            'year' => 'Year',
                        );

                        foreach ($values_arr as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($cs_reddit_time_frame_default, $key); ?>><?php _e($value); ?></option>
                        <?php } ?>
                    </select>
                    <select name="google_news_blogs_language" id="google_news_blogs_language" class="language_option">
                        <?php
                        $ybi_cs_default_search_language = $options['cs_google_news_blog_search_language_default'];

                        $language_arr = array(
                            'es_ar' => 'Argentina (es_ar)',
                            'au' => 'Australia (au)',
                            'nl_be' => 'Belgie (nl_be)',
                            'fr_be' => 'Belgigue (fr_be)',
                            'en_bw' => 'Botswana (en_bw)',
                            'pt-BR_br' => 'Brasil (pt-BR_br)',
                            'ca' => 'Canada English (ca)',
                            'fr_ca' => 'Canada French (fr_ca)',
                            'cs_cz' => 'Česká republika (cs_cz)',
                            'es_cl' => 'Chile (es_cl)',
                            'es_co' => 'Colombia (es_co)',
                            'es_cu' => 'Cuba (es_cu)',
                            'de' => 'Deutschland (de)',
                            'es_us' => 'Estados Unidos (es_us)',
                            'en_et' => 'Ethopia (en_et)',
                            'fr' => 'France (fr)',
                            'en_gh' => 'Ghana (en_gh)',
                            'in' => 'India (in)',
                            'hi_in' => 'తెలుగు (India) (hi_in)',
                            'ta_in' => 'TA India (ta_in)',
                            'te_in' => 'TE India (ta_in)',
                            'id_id' => 'Indonesia (id_id)',
                            'en_ie' => 'Ireland (en_ie)',
                            'en_il' => 'Isreal English (en_il)',
                            'iw_il' => 'Isreal (iw_il)',
                            'it' => 'Italia (it)',
                            'en_ke' => 'Kenya (en_ke)',
                            'lv_lv' => 'Latvija (lv_lv)',
                            'lt_lt' => 'Lietuva (lt_lt)',
                            'hu_hu' => 'Magyarorszag (hu_hu)',
                            'en_my' => 'Malaysia (en_my)',
                            'fr_ma' => 'Maroc (fr_ma)',
                            'es_mx' => 'Mexico (es_mx)',
                            'en_na' => 'Namibia (es_na)',
                            'nl_nl' => 'Nederland (nl_nl)',
                            'nz' => 'New Zealand (nz)',
                            'en_nq' => 'Nigeria (en_nq)',
                            'no_no' => 'Norge (no_no)',
                            'de_at' => 'Osterreich (de_at)',
                            'en_pk' => 'Pakistan (en_pk)',
                            'es_pe' => 'Peru (es_pe)',
                            'en_ph' => 'Philippines (en_ph)',
                            'pl_pl' => 'Polska (pl_pl)',
                            'pt-PT_pt' => 'Portugal (pt-PT_pt)',
                            'ro_ro' => 'Romania (ro_ro)',
                            'de_ch' => 'Schweiz (de_ch)',
                            'fr_sn' => 'Sénégal (fr_sn)',
                            'en_sg' => 'Singapore English (en_sg)',
                            'sl_si' => 'Slovenia (sl_si)',
                            'sk_sk' => 'Slovensko (sk_sk)',
                            'en_za' => 'South Africa (en_za)',
                            'fr_ch' => 'Suisse (fr_ch)',
                            'sv_se' => 'Sverige (sv_se)',
                            'en_tz' => 'Tanzania (en_tz)',
                            'tr_tr' => 'Turkiye (tr_tr)',
                            'us' => 'United States (us)',
                            'uk' => 'U.K. (uk)',
                            'en_ug' => 'Uganda (en_ug)',
                            'es_ve' => 'Venezuela (es_ve)',
                            'vi_vn' => 'Việt Nam (Vietnam)‎ (vi_vn)',
                            'en_zw' => 'Zimbabwe (en_zw)',
                            'el_gr' => 'Ελλάδα (Greece) (el_gr)',
                            'ru_ru' => 'Россия (Russia) (ru_ru)',
                            'sr_rs' => 'Србија (Serbia) (sr_rs)',
                            'ru_ua' => 'Украина (Ukraine) (ru_ua)',
                            'uk_ua' => 'Україна (Ukraine) (uk_ua)',
                            'ar_at' => 'الإمارات (UAE) (ar_at)',
                            'ar_sa' => '- السعودية  (ar_sa) (KSA)',
                            'ar_me' => 'العالم العربي (Arabic world)(ar_me)',
                            'ar_lb' => ' لبنان (Lebanon)(ar_lb)',
                            'ar_eg' => ' مصر (Egypt) (ar_eg)',
                            'kr' => '한국 (Korea) (kr)',
                            'cn' => '中国版 (China) (cn)',
                            'tw' => '台灣版 (Taiwan) (tw)',
                            'jp' => '日本 (Japan) (jp)',
                            'hk' => '香港版 (Hong Kong) (hk)'
                        );

                        foreach ($language_arr as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($ybi_cs_default_search_language, $key); ?>><?php _e($value); ?></option>
                        <?php } ?>
                    </select>

                    <select name="iso_search_language" id="iso_search_language" class="language_option">
                        <?php
                        //$ybi_cs_default_search_language = $options['ybi_cs_default_search_language'];
                        // these are ISO 639-1 language codes, used by youtube, twitter
                        $language_arr = array(
                            "aa" => "Afar",
                            "ab" => "Abkhazian",
                            "ae" => "Avestan",
                            "af" => "Afrikaans",
                            "ak" => "Akan",
                            "am" => "Amharic",
                            "an" => "Aragonese",
                            "ar" => "Arabic",
                            "as" => "Assamese",
                            "av" => "Avaric",
                            "ay" => "Aymara",
                            "az" => "Azerbaijani",
                            "ba" => "Bashkir",
                            "be" => "Belarusian",
                            "bg" => "Bulgarian",
                            "bh" => "Bihari",
                            "bi" => "Bislama",
                            "bm" => "Bambara",
                            "bn" => "Bengali",
                            "bo" => "Tibetan",
                            "br" => "Breton",
                            "bs" => "Bosnian",
                            "ca" => "Catalan",
                            "ce" => "Chechen",
                            "ch" => "Chamorro",
                            "co" => "Corsican",
                            "cr" => "Cree",
                            "cs" => "Czech",
                            "cu" => "Church Slavic",
                            "cv" => "Chuvash",
                            "cy" => "Welsh",
                            "da" => "Danish",
                            "de" => "German",
                            "dv" => "Divehi",
                            "dz" => "Dzongkha",
                            "ee" => "Ewe",
                            "el" => "Greek",
                            "en" => "English",
                            "eo" => "Esperanto",
                            "es" => "Spanish",
                            "et" => "Estonian",
                            "eu" => "Basque",
                            "fa" => "Persian",
                            "ff" => "Fulah",
                            "fi" => "Finnish",
                            "fj" => "Fijian",
                            "fo" => "Faroese",
                            "fr" => "French",
                            "fy" => "Western Frisian",
                            "ga" => "Irish",
                            "gd" => "Scottish Gaelic",
                            "gl" => "Galician",
                            "gn" => "Guarani",
                            "gu" => "Gujarati",
                            "gv" => "Manx",
                            "ha" => "Hausa",
                            "he" => "Hebrew",
                            "hi" => "Hindi",
                            "ho" => "Hiri Motu",
                            "hr" => "Croatian",
                            "ht" => "Haitian",
                            "hu" => "Hungarian",
                            "hy" => "Armenian",
                            "hz" => "Herero",
                            "ia" => "Interlingua",
                            "id" => "Indonesian",
                            "ie" => "Interlingue",
                            "ig" => "Igbo",
                            "ii" => "Sichuan Yi",
                            "ik" => "Inupiaq",
                            "io" => "Ido",
                            "is" => "Icelandic",
                            "it" => "Italian",
                            "iu" => "Inuktitut",
                            "ja" => "Japanese",
                            "jv" => "Javanese",
                            "ka" => "Georgian",
                            "kg" => "Kongo",
                            "ki" => "Kikuyu",
                            "kj" => "Kwanyama",
                            "kk" => "Kazakh",
                            "kl" => "Kalaallisut",
                            "km" => "Khmer",
                            "kn" => "Kannada",
                            "ko" => "Korean",
                            "kr" => "Kanuri",
                            "ks" => "Kashmiri",
                            "ku" => "Kurdish",
                            "kv" => "Komi",
                            "kw" => "Cornish",
                            "ky" => "Kirghiz",
                            "la" => "Latin",
                            "lb" => "Luxembourgish",
                            "lg" => "Ganda",
                            "li" => "Limburgish",
                            "ln" => "Lingala",
                            "lo" => "Lao",
                            "lt" => "Lithuanian",
                            "lu" => "Luba-Katanga",
                            "lv" => "Latvian",
                            "mg" => "Malagasy",
                            "mh" => "Marshallese",
                            "mi" => "Maori",
                            "mk" => "Macedonian",
                            "ml" => "Malayalam",
                            "mn" => "Mongolian",
                            "mr" => "Marathi",
                            "ms" => "Malay",
                            "mt" => "Maltese",
                            "my" => "Burmese",
                            "na" => "Nauru",
                            "nb" => "Norwegian Bokmal",
                            "nd" => "North Ndebele",
                            "ne" => "Nepali",
                            "ng" => "Ndonga",
                            "nl" => "Dutch",
                            "nn" => "Norwegian Nynorsk",
                            "no" => "Norwegian",
                            "nr" => "South Ndebele",
                            "nv" => "Navajo",
                            "ny" => "Chichewa",
                            "oc" => "Occitan",
                            "oj" => "Ojibwa",
                            "om" => "Oromo",
                            "or" => "Oriya",
                            "os" => "Ossetian",
                            "pa" => "Panjabi",
                            "pi" => "Pali",
                            "pl" => "Polish",
                            "ps" => "Pashto",
                            "pt" => "Portuguese",
                            "qu" => "Quechua",
                            "rm" => "Raeto-Romance",
                            "rn" => "Kirundi",
                            "ro" => "Romanian",
                            "ru" => "Russian",
                            "rw" => "Kinyarwanda",
                            "sa" => "Sanskrit",
                            "sc" => "Sardinian",
                            "sd" => "Sindhi",
                            "se" => "Northern Sami",
                            "sg" => "Sango",
                            "si" => "Sinhala",
                            "sk" => "Slovak",
                            "sl" => "Slovenian",
                            "sm" => "Samoan",
                            "sn" => "Shona",
                            "so" => "Somali",
                            "sq" => "Albanian",
                            "sr" => "Serbian",
                            "ss" => "Swati",
                            "st" => "Southern Sotho",
                            "su" => "Sundanese",
                            "sv" => "Swedish",
                            "sw" => "Swahili",
                            "ta" => "Tamil",
                            "te" => "Telugu",
                            "tg" => "Tajik",
                            "th" => "Thai",
                            "ti" => "Tigrinya",
                            "tk" => "Turkmen",
                            "tl" => "Tagalog",
                            "tn" => "Tswana",
                            "to" => "Tonga",
                            "tr" => "Turkish",
                            "ts" => "Tsonga",
                            "tt" => "Tatar",
                            "tw" => "Twi",
                            "ty" => "Tahitian",
                            "ug" => "Uighur",
                            "uk" => "Ukrainian",
                            "ur" => "Urdu",
                            "uz" => "Uzbek",
                            "ve" => "Venda",
                            "vi" => "Vietnamese",
                            "vo" => "Volapuk",
                            "wa" => "Walloon",
                            "wo" => "Wolof",
                            "xh" => "Xhosa",
                            "yi" => "Yiddish",
                            "yo" => "Yoruba",
                            "za" => "Zhuang",
                            "zh" => "Chinese",
                            "zu" => "Zulu"
                        );
                        // for twitter and youtube
                        $ybi_cs_default_search_language = $options['cs_iso_search_language_default'];
                        foreach ($language_arr as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($ybi_cs_default_search_language, $key); ?>><?php _e($value); ?></option>
                        <?php } ?>
                    </select>

                    <select name="bing_languages" id="bing_languages" class="language_option">
                        <?php
                        $ybi_cs_default_search_language = $options['cs_bing_search_language_default'];
                        // language codes for Bing search API
                        $language_arr = array(
                            'ar-XA' => 'Arabic - Arabia',
                            'bg-BG' => 'Bulgarian - Bulgaria',
                            'cs-CZ' => 'Czech - Czech Republic',
                            'da-DK' => 'Danish - Denmark',
                            'de-AT' => 'German - Austria',
                            'de-CH' => 'German - Switzerland',
                            'de-DE' => 'German - Germany',
                            'el-GR' => 'Greek - Greece',
                            'en-AU' => 'English - Australia',
                            'en-CA' => 'English - Canada',
                            'en-GB' => 'English - United Kingdom',
                            'en-ID' => 'English - Indonesia',
                            'en-IE' => 'English - Ireland',
                            'en-IN' => 'English - India',
                            'en-MY' => 'English - Malaysia',
                            'en-NZ' => 'English - New Zealand',
                            'en-PH' => 'English - Philippines',
                            'en-SG' => 'English - Singapore',
                            'en-US' => 'English - United States',
                            'en-XA' => 'English - Arabia',
                            'en-ZA' => 'English - South Africa',
                            'es-AR' => 'Spanish - Argentina',
                            'es-CL' => 'Spanish - Chile',
                            'es-ES' => 'Spanish - Spain',
                            'es-MX' => 'Spanish - Mexico',
                            'es-US' => 'Spanish - United States',
                            'es-XL' => 'Spanish - Latin America',
                            'et-EE' => 'Estonian - Estonia',
                            'fi-FI' => 'Finnish - Finland',
                            'fr-BE' => 'French - Belgium',
                            'fr-CA' => 'French - Canada',
                            'fr-CH' => 'French - Switzerland',
                            'fr-FR' => 'French - France',
                            'he-IL' => 'Hebrew - Israel',
                            'hr-HR' => 'Croatian - Croatia',
                            'hu-HU' => 'Hungarian - Hungary',
                            'it-IT' => 'Italian - Italy',
                            'ja-JP' => 'Japanese - Japan',
                            'ko-KR' => 'Korean - Korea',
                            'lt-LT' => 'Lithuanian - Lithuania',
                            'lv-LV' => 'Latvian - Latvia',
                            'nb-NO' => 'Norwegian - Norway',
                            'nl-BE' => 'Dutch - Belgium',
                            'nl-NL' => 'Dutch - Netherlands',
                            'pl-PL' => 'Polish - Poland',
                            'pt-BR' => 'Portuguese - Brazil',
                            'pt-PT' => 'Portuguese - Portugal',
                            'ro-RO' => 'Romanian - Romania',
                            'ru-RU' => 'Russian - Russia',
                            'sk-SK' => 'Slovak - Slovak Republic',
                            'sl-SL' => 'Slovenian - Slovenia',
                            'sv-SE' => 'Swedish - Sweden',
                            'th-TH' => 'Thai - Thailand',
                            'tr-TR' => 'Turkish - Turkey',
                            'uk-UA' => 'Ukrainian - Ukraine',
                            'zh-CN' => 'Chinese - China',
                            'zh-HK' => 'Chinese - Hong Kong SAR',
                            'zh-TW' => 'Chinese - Taiwan'
                        );

                        foreach ($language_arr as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($ybi_cs_default_search_language, $key); ?>><?php _e($value); ?></option>
                        <?php } ?>
                    </select>

                    <label class="load_player"><input type="checkbox" name="show_player" id="show_player"/>Load Player</label>
                </div>
                <div class="search_notice_text"></div>
            </div>
            <div class="rcp-ajax waiting on_demand_loading"><i class="fa fa-spinner fa-spin"></i></div>
            <?php echo cs_html_get_tutorial_link('FCGzqLvQ6KM', 'tutorial', 'tut_on_demand_search'); ?>
            <div id="cod_quick_add_block"></div>

            <div id="ybi_curation_suite_content_searches" class="cs_results"></div>
        </div>
        <?php if ($loadScrape): ?>
            <div id="content_scrape_block" class="content_action_div">
                <div id="find_content_top">
                    <div id="direct_share_wrap">
                        <label class="load_direct_share_lbl"><input type="checkbox" name="load_direct_share" id="load_direct_share_scrape" <?php checked(1, $curation_suite_direct_share_default, true); ?> /><i class="fa fa-share-alt"></i> Load Direct Share</label>
                    </div>
                </div>
                <div id="scrape_load_wrapper">
                    <div class="input-group margin-bottom-sm">

                        <select name="content_scrape_id" id="content_scrape_id">
                            <?php
                            $all_sources = get_option('cs_scrape_sources');
                            foreach ($all_sources as $single_source) {
                                ?>
                                <option value="<?php echo $single_source['source'] . '|||' . $single_source['url']; ?>"><?php echo $single_source['title']; ?></option>
                            <?php } ?>
                        </select>
                        <a href="javascript:;" class="scrape_load button action">Load</a>
                    </div>
                    <div id="link_buckets_load_row"></div>
                    <div class="rcp-ajax waiting"><i class="fa fa-spinner fa-spin"></i></div>
                    <div id="ybi_curation_suite_scrape_content"></div>
                </div>
            </div>
        <?php endif; ?>

        <div id="visual_editor" class="content_action_div">
            <?php

            $loadLink = isset($_GET['u']) ? ($_GET['u']) : '';
            // due to security we mask the HTTP or HTTPS with xxxx or xxxxs.
            $loadLink = str_replace("xxxxs", "https://", $loadLink); // replace the mask to https
            $loadLink = esc_url($loadLink); // escape the URL to sanitize, this will add http is it doesn't exists, it wont add https as we do that above with the masking
            // include the main meta file
            include_once(YBI_CURATION_SUITE_PATH . 'inc/main-meta-worker.php');
            ?>
        </div>
        <div id="curation_suite_post_actions_wrapper">
            <input type="hidden" name="load_social_media_actions_nonce" id="load_social_media_actions_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>"/>
            <div class="loading_social waiting" style="display: none; text-align:center; font-size: 30px; clear:both; overflow: auto;"><i class="fa fa-spinner fa-spin"></i></div>
            <div id="social_media_actions" class="content_action_div">
                <div id="find_content_top">
                    <div id="co_schedule_load_wrap">
                        <label class="load_direct_share_lbl"><input type="checkbox" name="load_co_schedule" id="load_co_schedule"/><i class="fa fa-share-alt"></i> Load Co-Schedule Copy Text</label>
                    </div>
                </div>
                <div id="ignore_social_options">
                    <i>Ignore:</i>&nbsp;
                    <label><input type="checkbox" name="ignore_social_options" value="bold"/> Bold</label>
                    <label><input type="checkbox" name="ignore_social_options" value="italic"/> Italic</label>
                    <label><input type="checkbox" name="ignore_social_options" value="headlines"/> Headlines</label>
                    <label><input type="checkbox" name="ignore_social_options" value="links"/> Links</label>
                </div>
                <div id="social_action_load_wrapper">
                    <label>Add Text to All Updates:</label>
                    <input type="text" name="social_action_text" id="social_action_text" class="regular-text form-control" value=""/>
                    <select id="social_text_location">
                        <option value="before">before link</option>
                        <option value="after">after link</option>
                    </select>
                    <a href="javascript:;" class="load_social_media_actions button action">Load Social Actions</a>
                </div>
                <div id="ybi_cu_social_media_actions"></div>
            </div>

        </div>
        <div id="base_quick_add_block">
            <div id="quick_add_parms" class="ybi_cs_hide_quick_add_block<?php echo $ybi_hide; ?>">
                <hr/>
                <div id="blockquote_button_select_wrapper">
                    <div class="blockquote_button_headline"><i class="fa fa-quote-left"></i> Blockquote</div>
                    <div class="on_off_switch">
                        <input type="checkbox" name="on_off_switch" class="on_off_switch-checkbox" id="cu_quick_blockquote_switch" style="display:none;" <?php checked(1, $curation_suite_blockquote_default, true); ?>>
                        <label class="on_off_switch-label" for="cu_quick_blockquote_switch">
                            <div class="on_off_switch-switch"></div>
                            <div class="on_off_switch-inner"></div>
                        </label>
                    </div>
                </div>
                <div id="headline_button_select_wrapper">
                    <div class="headline_button_headline"><i class="fa fa-header"></i> Headline</div>
                    <div class="on_off_switch">
                        <input type="checkbox" name="on_off_switch" class="on_off_switch-checkbox" id="cu_quick_headline_switch" style="display:none;" <?php checked(1, $curation_suite_headline_default, true); ?>>
                        <label class="on_off_switch-label" for="cu_quick_headline_switch">
                            <div class="on_off_switch-switch"></div>
                            <div class="on_off_switch-inner"></div>
                        </label>
                    </div>
                </div>
                <div id="link_attribution_options_wrapper">
                    <div class="link_attribution_headline"><i class="fa fa-link"></i> Link Attribution</div>
                    <div class="link_attribution_switch">
                        <select name="quick_attribution_link_location" id="quick_attribution_link_location">
                            <option value="link_before" <?php selected($curation_suite_link_attribution_location_default, 'link_before'); ?>><?php _e("Link Before"); ?></option>
                            <option value="link_after" <?php selected($curation_suite_link_attribution_location_default, 'link_after'); ?>><?php _e("Link After"); ?></option>
                            <option value="link_headline" <?php selected($curation_suite_link_attribution_location_default, 'link_headline'); ?>><?php _e("Headline Link"); ?></option>
                            <option value="link_above" <?php selected($curation_suite_link_attribution_location_default, 'link_above'); ?>><?php _e("Link Above"); ?></option>
                        </select>

                    </div>
                </div>
                <div id="quick_add_link_text_option">
                    <div class="link_attribution_headline"><i class="fa fa-link"></i> Link Text
                        <a href="javascript:;" class="clear_element" name="cu_quick_link_text"><i class="fa fa-eraser"></i> clear</a></div>
                    <input type="text" id="cu_quick_link_text" value="" name="cu_quick_link_text" class=""/>
                </div>
                <div id="quick_add_options">
                    <label><input type="checkbox" class="cs_save_setting" id="ybi_cs_no_image_quick_add" name="ybi_cs_no_image_quick_add" value="1" rel="ybi_cs_no_image_quick_add" <?php checked($ybi_cs_no_image_quick_add, 1); ?> /> No thumbnail</label>
                    <label class="quick_add_image_link_lbl"><input type="checkbox" id="cs_link_image_quick_add" <?php checked(1, $curation_suite_link_images_default, true); ?> /> Link Image</label>
                    <label class="quick_add_image_link_lbl"><input type="checkbox" name="cs_highlight_curated_content" rel="cs_highlight_curated_content" class="cs_save_setting" id="cs_highlight_curated_content" <?php checked(1, $cs_highlight_curated_content, true); ?> /> Highlight Curated Content</label>
                    <br/>
                </div>

            </div>
            <hr/>
        </div>

        <div style="clear: both; margin: 0 auto; overflow:auto;"></div>
    </div>
</div>
<script>
    // here we are attaching the quick add options to whatever is showing first, this will either be the Listening Engine tab or the content on demand search tab
    jQuery(document).ready(function ($) {
        <?php if($loadCSListening): ?>
        $("#base_quick_add_block").detach().appendTo("#le_quick_add_block");
        quick_add_current_location = 'content_search_block';
        <?php else: ?>
        $("#base_quick_add_block").detach().appendTo("#cod_quick_add_block");
        quick_add_current_location = 'content_search_block';
        <?php endif; ?>
    }); // end of doc
</script>