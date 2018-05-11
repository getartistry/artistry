<?php
function cs_le_get_news_content()
{
    $platform_id = trim($_POST['platform_id']);
    $start_num = trim($_POST['start_num']);
    $cu_date_sort = '';
    $view=4;
    $api_url_arr = array('content','latest',$view,$start_num);
    $passed_data = array('sort'=>$cu_date_sort);
    $data = ybi_curation_suite_api_call('display',$passed_data, $api_url_arr);
    //[curation-suite-display-platform view="1" brick_width="270" show_date="yes" show_source="yes"][/curation-suite-display-platform]
    $status= $data['status'];

    if($status == 'success') {

        $results = $data['results'];
    }

    $html = get_news_page_html($results, 'Read More', false, true);
    echo json_encode(array('status'=> 'success', 'html' => $html) );
    die();
}

add_action('wp_ajax_cs_le_get_news_content', 'cs_le_get_news_content');
add_action('wp_ajax_nopriv_cs_le_get_news_content', 'cs_le_get_news_content');


function get_news_page_html($results, $read_more, $display_date, $display_source)
{

    wp_enqueue_script('cu_le_portal_script',plugins_url('js/imagesloaded.pkgd.min.js',dirname(__FILE__) ),array('jquery'),'4.1.2');
    $i=0;
    $post_list = '';
    foreach ($results as $ContentItem) {
        $current_url = $ContentItem['url'];
        $fav_icon = '';
        if (0 != $i % 2): $alternate = ' alternate'; endif;

        $date = new DateTime($ContentItem['published_date']);
        $pubDate = $date->format('m/d/Y');

        $fav_icon = $ContentItem['DomainData']['data']['fav_icon_url'];
        $background_img = '';

        if ($fav_icon != '')
            $background_img = ' style="background: no-repeat url(' . $fav_icon . ') left top; background-position: left; background-size: 16px 16px; padding-left: 21px; margin-right: 10px; border-bottom: 1px solid #f5f5f5;"';

        $thumbnail_html = '';

        if ($ContentItem['image_src'])
            $thumbnail_html = '<img src="' . $ContentItem['image_src'] . '" class="item_thumb" />';

        $commentary = '';
        if ($ContentItem['PlatformDisplay']['commentary'] != '')
            $commentary = stripslashes($ContentItem['PlatformDisplay']['commentary']);

        $snippet = $ContentItem['snippet'];
        if (strlen($snippet) > 200) {

            // truncate string
            $stringCut = substr($snippet, 0, 200);

            // make sure it ends in a word so assassinate doesn't become ass...
            $snippet = substr($stringCut, 0, strrpos($stringCut, ' ')); //.'...';
        }

        $snippet .= '. <a href="' . $current_url . '" target="_blank">'.$read_more.'</a>';

        $post_list .= '<div class="brick">';
        if ($display_date)
            $post_list .= '<p class="muted">' . $pubDate . '</p>';

        $post_list .= '<div class="thumb"><a href="' . $current_url . '" target="_blank">' . $thumbnail_html . '</a></div>';
        $post_list .= '<h4><a href="' . $current_url . '" target="_blank">' . html_entity_decode($ContentItem['title']) . '</a></h4>';

        if ($display_source)
            $post_list .= '<p' . $background_img . ' class="source">' . $ContentItem['source_domain'] . '</p>';


        if ($commentary != '')
            $post_list .= '<div class="commentary" >' . $commentary . '</div>';

        $post_list .= '<blockquote class="snippet">' . $snippet . '</blockquote>';

        $post_list .= '</div>';
        $i++;
    }
    return $post_list;
}

add_shortcode('curation-suite-display-platform', 'ybi_cu_get_display_platform_content');
function ybi_cu_get_display_platform_content( $atts, $content = null ) {
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'jquery-masonry' );
    $read_more = '';
    $show_date = 'yes';
    $show_source = 'yes';
    $cu_date_sort = '';
    $view = 0;
    $brick_width = '330';
    extract(shortcode_atts(array(
        "show_date" => 'yes',
        "show_source" => 'yes',
		"brick_width" => '330',
        "view" => 0,
        'read_more' => 'Read more...'
    ), $atts));

    $display_date = ($show_date == 'yes');
    $display_source = ($show_source == 'yes');
    // view is the platform_id
    $api_url_arr = array('content','latest',$view,0);
    $passed_data = array('sort'=>$cu_date_sort);
    $data = ybi_curation_suite_api_call('display',$passed_data, $api_url_arr);
    //[curation-suite-display-platform view="1" brick_width="270" show_date="yes" show_source="yes"][/curation-suite-display-platform]
    $status= $data['status'];

    if($status == 'success') {

        $results = $data['results'];
        $post_list = get_news_page_html($results, $read_more, $display_date, $display_source);

        ?>
        <style type="text/css">
            * {
                box-sizing: border-box;
            }

            #ybi_cu_display_platform_content {
                /*width: 1080px;*/
                width: 100%;
                margin: auto;
            }

            .brick {
                border-top: 3px solid #007ea6;
                width: <?php echo $brick_width; ?>px;
                padding: 0;
                margin: 15px 25px;
                background-color: white;
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);

            }

            .brick h4 {
                font-family: "Ubuntu", sans-serif;
                font-size: 20px;
                font-weight: 500;
                letter-spacing: 0;
                line-height: 26px;
                margin-bottom: 0;
                margin-top: 7px;
                padding: 0 10px 5px;
                text-rendering: optimizelegibility;
                text-align: center;
            }

            .brick img {
                border: 0 none;
                height: auto;
                max-width: 100%;
                vertical-align: middle;

            }

            .brick .thumb {
                text-align: center;
                width: 100%;
            }

            .snippet {
                clear: both;
                margin: 0 auto;
                overflow: auto;
                height: auto;
                padding: 0 10px;
            }

            .commentary {
                padding: 10px 10px 10px;
            }
            .source {margin-left: 10px !important; font-size: 13px !important;color: #9f9f9f;}
            blockquote {
                padding: 0 8px 15px 0 !important;
                margin: 6px 15px !important;
                border-left: none !important;
                font-size: 1em !important;
            }
#news_page_container { margin-bottom: 300px;}
#le_news_load_more { clear: both; overflow: auto;background-color:#eee; color:#999; font-weight:bold; text-align:center; padding:10px 0; cursor:pointer; width: 50%; }
#le_news_load_more:hover{ color:#666; }
        </style>
        <script>
            jQuery(document).ready(function ($) {

                var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
                $('#ybi_cu_display_platform_content').masonry({columnWidth: <?php echo $brick_width+50; ?>});
                var $container = $('#ybi_cu_display_platform_content');
                $container.imagesLoaded(function () {
                    $container.masonry({
                        itemsSelector: '.item_thumb',
                        isFitWidth: true
                    }).resize();
                });



                $("#le_news_load_more").click(function() {
                    var platform_id = $('#view_id').val();
                    var start_num = $('#current_total').val();
                    $('#le_news_load_more').html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>  Loading...');
                    var data = {
                        action: 'cs_le_get_news_content',
                        platform_id: platform_id,
                        start_num: start_num
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function (platform_response) {
                            if (platform_response.status == 'success') {
                                var el = jQuery(platform_response.html);
                                jQuery("#ybi_cu_display_platform_content").append(el).masonry('appended', el, true);
                                var update_num = parseInt(start_num) + 20;

                                $('#current_total').val(update_num);
                                var $container = $('#ybi_curation_suite_listening_links');
                                $container.imagesLoaded(function(){
                                    $container.masonry({
                                        itemsSelector: '.item_thumb',
                                        isFitWidth: true
                                    }).resize();

                                    $('#ybi_curation_suite_listening_links').imagesLoaded( function() {
                                        $('#ybi_cu_display_platform_content').masonry('reloadItems');
                                        $('#ybi_cu_display_platform_content').masonry('reload');
                                        $('#ybi_cu_display_platform_content').masonry('layout');
                                    });



                                    $('#le_news_load_more').html('<a href="javascript:;" id="">Load More</a>');
                                });

                                $('.item_thumb').each(function(index, obj){
                                    //you can use this to access the current item
                                    obj.error(function() {
                                        obj.addClass('broke');
                                        //$(".item_thumb").css({"display":"none"});
                                    });
                                });
                            }
                        }
                    });
                });
            }); // end of doc
        </script>
        <input type="hidden" id="view_id" value="<?php echo $view; ?>">
        <input type="hidden" id="read_more" value="<?php echo $read_more; ?>">
        <input type="hidden" id="display_source" value="<?php echo $display_source; ?>">
        <input type="hidden" id="display_date" value="<?php echo $display_date; ?>">
        <input type="hidden" id="current_total" value="11">
        <?php
        echo '<div id="news_page_container">
                <div id="ybi_cu_display_platform_content">' . $post_list . '</div>
                <div style="text-align: center; clear: both; overflow: auto; margin-top: 200px;">
                    <div id="le_news_load_more"><a href="javascript:;" id="">Load More</a></div>
                </div>
              </div>';
    }
    else
        echo $data['message'];

} // end of function

?>