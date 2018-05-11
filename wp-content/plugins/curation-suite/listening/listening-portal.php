<?php
add_shortcode('curation-suite-listening-portal', 'ybi_cu_listening_portal_content');
function ybi_cu_listening_portal_content( $atts, $content = null ) {
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'jquery-masonry' );
   //wp_enqueue_script( 'custom-ajax-request', '/path/to/settings.js', array( 'jquery' ) );

    wp_enqueue_script('cu_le_portal_script',plugins_url('js/post-admin-scripts.js',dirname(__FILE__) ),array('jquery'),'1.0');
  //  wp_enqueue_script('ybi_cu_post_scripts',plugins_url('js/post-admin-scripts.js',__FILE__ ),array('jquery-masonry'),'2.1.1');

    $ajax_url = admin_url('admin-ajax.php');
    $data = array(	'plugins_url' => plugins_url(),
              'ajax_url' => $ajax_url,

    );

    wp_localize_script('cu_le_portal_script', 'yb_cu_post_vars', $data);

    $read_more = '';
    $show_date = 'yes';
    $show_source = 'yes';
    $cu_date_sort = 'share_gravity';
    $view = 0;
    $brick_width = '450';
    $topic_width = 900;
    $sort_width = 900;
    extract(shortcode_atts(array(
        "show_date" => 'yes',
        "show_source" => 'yes',
		"brick_width" => '450',
        "view" => 1,
        'read_more' => 'Read more...'
    ), $atts));

    $display_date = ($show_date == 'yes');
    $display_source = ($show_source == 'yes');
    // view is the platform_id
    //$view = 4;
    $api_url_arr = array($view,'all',25);
    $passed_data = array('sort'=>$cu_date_sort);
    $data = ybi_curation_suite_api_call('display/content/portal',$passed_data, $api_url_arr);
    //echo $data['url'];

    //http://localhost/listening-platform/api/display/content/portal/Cur-Suite-030f38634a24/1/1/25
    //http://localhost/listening-platform/api/display/content/portal/Cur-Suite-030f38634a24/1/1/20
    //[curation-suite-display-platform view="1" brick_width="270" show_date="yes" show_source="yes"][/curation-suite-display-platform]
    $status= $data['status'];


    function cs_le_ip_visitor_country()
    {
        $return_arr = array();
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        $country  = "Unknown";
        $country_code = '';

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }
        $ch = curl_init();
        //http://www.geoplugin.net/json.gp?ip=98.240.201.232
        curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $ip_data_in = curl_exec($ch); // string
        curl_close($ch);

        $ip_data = json_decode($ip_data_in,true);
        $ip_data = str_replace('&quot;', '"', $ip_data); // for PHP 5.2 see stackoverflow.com/questions/3110487/

        if($ip_data && $ip_data['geoplugin_countryName'] != null) {
            $country = $ip_data['geoplugin_countryName'];
        }
        if($ip_data && $ip_data['geoplugin_countryCode'] != null) {
            $country_code = $ip_data['geoplugin_countryCode'];
        }
        $return_arr['ip'] = $ip;
        $return_arr['country_name'] = $country;
        $return_arr['country_code'] = $country_code;
        return $return_arr;
        //return 'IP: '.$ip.' # Country: '.$country;
    }



/*    $args = array( 'numberposts' => '2' );
    $recent_posts = wp_get_recent_posts( $args );

    foreach( $recent_posts as $recent ){

    }*/
    $portal_topic_title = '';

    if($status == 'success') {
        $email_address = isset($_GET['email_address']) ? $_GET['email_address'] : '';

        $default_topic_name ='All Topics';
        $default_sort_name = 'Trending';
        $current_visitor_arr =  cs_le_ip_visitor_country();
        $visitor_ip = '';
        $visitor_country_code = '';
        $visitor_country_name = '';
        if(is_array($current_visitor_arr)) {
            $visitor_ip = $current_visitor_arr['ip'];
            $visitor_country_code = $current_visitor_arr['country_code'];
            $visitor_country_name = $current_visitor_arr['country_name'];
        }

//<h2>Discover Top Trending Content</h2>
        $topic_html = '
<div class="subscribe_cta"><a href="javascript:;" class="button subscribe_reveal_button">Subscribe for Daily Trending Content <span class="cta_action_icon"><i class="fa fa-chevron-down"></i></span></a> </div>
<div id="subscribe_modal_wrapper">
<div id="subscribe_message"></div>
<div id="subscribe_modal">
<p>Enter your email below to recieve an exclusive daily email with trending content right in your inbox:</p>
<div id="leadpages-container-email" class="control-group">
<div class="controls">
<input id="email" type="email" required="required" data-role="email" data-source="leadpages-email" placeholder="Email Address *" value="'.$email_address.'" name="email">
<input type="hidden" id="visitor_ip" value="'.$visitor_ip.'" />
<input type="hidden" id="visitor_country_code" value="'.$visitor_country_code.'" />
<input type="hidden" id="visitor_country_name" value="'.$visitor_country_name.'" />
<input type="hidden" id="platform_id" value="'.$view.'" />
</div>
<span id="leadpages-error-email" class="error-container"></span>
</div>

<div class="control-group">
<div class="controls">

<button id="leadpages-submit-button" style="font-family: \'Open Sans\', Helvetica, Arial, sans-serif; font-size: 14pt; letter-spacing: 0em; background-color: rgb(18, 133, 221); border-style: solid; border-width: 0pt; border-top-width: 0pt; border-right-width: 0pt; border-bottom-width: 0pt; border-left-width: 0pt; border-color: rgb(221, 221, 221); text-shadow: rgb(18, 133, 221) 2px 2px 0px" type="submit">
<div style="text-align: center">
Subscribe Now
<span class="raquo">»</span>
</div>
</button>
</div>
</div>
<div style="clear: both; overflow: auto; margin 0 auto;"></div>
</div>
</div>';
        $topic_html .= '<div id="topic_list"><ul>';
        $topics = $data['platform']['topics'];
        $topic_html .= '<li><a href="javascript:;" class="topic reload_content selected" data-topic-id="all" data-platform-id="'.$data['platform']['id'].'"
        data-order-by="share_gravity">All Topics</a></li>';
        foreach($topics as $topic) {
            $topic_html .= '<li><a href="javascript:;" class="topic reload_content"
data-topic-id="'.$topic['id'].'"
data-platform-id="'.$data['platform']['id'].'"
data-order-by="share_gravity"
data-reload-type="topic">'. $topic['name'].'</a></li>';
        }
        $topic_html .= '</ul></div>';


        $sort_options_html = '<div id="sort_list"><ul>';
        foreach(cs_le_get_sort_values(false) as $key => $name) {
            $selected_class = '';
            if($key == 'share_gravity')
                $selected_class = ' selected';

            $sort_options_html .= '<li class="'.$key.'"><a href="javascript:;" class="sort_option reload_content'.$selected_class.'"
            data-reload-type="sort"
            data-sort="'.$key.'"
            data-platform-id="'.$data['platform']['id'].'"
            data-order-by="'.$key.'"
            data-topic-id="all"
            >'.$name.'</a></li>';
        }
        $sort_options_html .= '</ul></div>';



        $portal_topic_title = '<div style="text-align: center;" id="search_results_title">You\'re viewing top content in <strong>'.$default_topic_name. '</strong> by <strong>' .$default_sort_name . '</strong>...</div>';
        $results = $data['results'];
        $post_list = '';
        $post_list .= get_portal_content_simple_html($results, $display_date, $display_source, $read_more );

        ?>
        <script>
            jQuery(document).ready(function ($) {
                var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
                //	var ajax_url = yb_cu_post_vars.ajax_url;
                $('#ybi_curation_suite_listening_links').masonry({columnWidth: <?php echo $brick_width+50; ?>});
                var $container = $('#ybi_curation_suite_listening_links');
                $container.imagesLoaded(function () {
                    $container.masonry({
                        itemsSelector: '.item_thumb',
                        isFitWidth: true
                    }).resize();
                });

                $(".reload_content").click(function() {
                    var elem = $(this);
                    var reload_type = elem.attr('data-reload-type');
                    var topic_name = '';
                    var sort_text = '';

                    if(reload_type=='sort') {
                        $('.sort_option').removeClass('selected');
                        sort_text = elem.text();
                        topic_name = $('#topic_list .selected').text();

                    } else {
                        $('.topic').removeClass('selected');
                        topic_name = elem.text();
                        sort_text = $('#sort_list .selected').text();
                    }
                    elem.addClass('selected');

                    //var search_type = 'user_search_term';
                    //var platform_id = $('#cu_listening_platform_id').val();
                    var topic_id = elem.attr('data-topic-id');
                    var platform_id = elem.attr('data-platform-id');
                    var order_by = elem.attr('data-order-by');
                    // when you click a sort
                    $('.topic').attr('data-order-by',order_by);
                    $('.sort_option').attr('data-topic-id',topic_id);
                    var le_search_term_element = $('#le_search_term_element').val();
                    $('#portal_content_title').html('<div style="text-align: center;"><i class="fa fa-spinner fa-spin"></i> Analyzing Top Content in <strong>' + topic_name + '</strong> by <strong>' + sort_text +'</strong>...</div>');
                    $('#ybi_curation_suite_listening_links').html('');
                    data = {
                        action: 'cs_le_get_portal_content',
                        platform_id: platform_id,
                        topic_id: topic_id,
                        order_by: order_by,
                        topic_name: topic_name,
                        sort_text: sort_text
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function(platform_response)
                        {
                            if(platform_response.status == 'success')
                            {

                                $("#ybi_curation_suite_listening_links").html(platform_response.results);
                                $('#portal_content_title').html('<div style="text-align: center;">You\'re viewing top content in <strong>' + topic_name + '</strong> by <strong>' + sort_text +'</strong>...</div>');
                                refresh_display();
                                //cs_le_loading_show('hide',loading_arr);
                            }
                        }
                    });

                    clicky.log('#'+topic_name + sort_text,'Portal Sort');

                    ga('send', {
                        hitType: 'event',
                        eventCategory: 'Sort',
                        eventAction: 'click',
                        eventLabel: topic_name + '-' + sort_text
                    });

                });

                $("#ybi_curation_suite_listening_links").delegate(".outbound_link", "click", function(){
                    var link = $(this).attr('href');
                    ga('send', {
                        hitType: 'event',
                        eventCategory: 'Links',
                        eventAction: 'click',
                        eventLabel: link
                    });
                });
                $("#ybi_curation_suite_listening_links").delegate(".cu_platform_action", "click", function(){

                    var elem = $(this);
                    var cur_action = elem.attr('cur_action');
                    var time_frame = elem.attr('data-time-frame');
                    var type = elem.attr('type');
                    var parameter_id = elem.attr('parameter_id');
                    var platform_id = $('#cu_listening_platform_id').val();
                    var cu_current_display_page = $('#cu_current_display_page').val();

                    $('.cu_cid_row_' + parameter_id).hide(500);
                    $('.cu_cid_row_' + parameter_id).remove();
                    if(type == 'ignore-source' && cur_action == 'add')
                        $('.source_ignore_'+parameter_id).html('<i class="fa fa-spinner fa-spin"></i>');
                    else {
                        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                        if(cu_current_display_page == 'listening-page')
                        {
                            //if(platform_response.type == 'ignore-content-item' || platform_response.type == 'ignore-source' || (platform_response.type == 'save-content-item' && platform_response.cur_action != 'add') )
                            if(type == 'ignore-content-item' || type == 'ignore-source' || (type == 'save-content-item') )
                            {
                                $('#ybi_curation_suite_listening_links').masonry('reloadItems');
                                $('#ybi_curation_suite_listening_links').masonry('reload');
                            }
                        }
                        if(type=='ignore-content-item') {
                            $('.content_item_'+ parameter_id).hide(500);
                            $('.content_item_'+ parameter_id).remove();
                        }


                    }
                    data = {
                        action: 'cs_le_get_portal_content_platform_action',
                        platform_id: platform_id,
                        cur_action: cur_action,
                        type: type,
                        parameter_id: parameter_id,
                        time_frame: time_frame,
                        cu_current_display_page: cu_current_display_page
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function(platform_response)
                        {
                            if(platform_response.status == 'success')
                            {
                                if(platform_response.type == 'ignore-content-item')
                                {
                                    $('.content_item_'+ parameter_id).hide(500);
                                    $('.content_item_'+ parameter_id).remove();
                                    var cur_total = $('#ybi_lp_total').html();
                                    if(cur_total != '' || cur_total > 0)
                                        $('#ybi_lp_total').html(cur_total-1);
                                    refresh_display();
                                }
                                if(platform_response.type == 'ignore-source')
                                {
                                    $('.' + platform_response.hide_element).hide(500);
                                    $('.' + platform_response.hide_element).remove();
                                }
                                if(platform_response.type == 'ignore-keyword')
                                    $('.' + platform_response.hide_element).hide(600);
                                if(platform_response.type == 'save-content-item')
                                {
                                    if(platform_response.cur_action == 'add')
                                    {
                                        showNoticeMessage('Content Saved');
                                        $('.cu_cid_row_'+platform_response.passed_parameter_id).hide(500);
                                        $('.cu_cid_row_'+platform_response.passed_parameter_id).remove();
                                        //alert(platform_response.cu_current_display_page);
                                        //$('.' + platform_response.hide_element).html('<i class="fa fa-bookmark"></i>'); // change to closed bookmark
                                        //$('.' + platform_response.hide_element).css({'color':'blue'}); // update color
                                        //alert('add action'+platform_response.passed_parameter_id);

                                    }
                                    else
                                    {
                                        //alert('else action'+platform_response.passed_parameter_id);
                                        $('.' + platform_response.hide_element).hide(500);
                                        $('.' + platform_response.hide_element).remove();
                                        $('.cu_cid_row_'+platform_response.passed_parameter_id).addClass('available'); // it's no longer available for bulk actoins
                                    }
                                }

                                if(platform_response.cu_current_display_page == 'listening-page')
                                {
                                    //if(platform_response.type == 'ignore-content-item' || platform_response.type == 'ignore-source' || (platform_response.type == 'save-content-item' && platform_response.cur_action != 'add') )
                                    if(platform_response.type == 'ignore-content-item' || platform_response.type == 'ignore-source' || (platform_response.type == 'save-content-item') )
                                    {
                                        $('#ybi_curation_suite_listening_links').masonry('reloadItems');
                                        $('#ybi_curation_suite_listening_links').masonry('reload');
                                    }
                                }
                            }
                        }
                    });


                });




                function refresh_display()
                {
                    $( '#ybi_curation_suite_listening_links' ).masonry( { columnWidth: <?php echo $brick_width+50; ?> } );
                    var $container = $('#ybi_curation_suite_listening_links');
                    $container.imagesLoaded(function(){
                        $container.masonry({
                            itemsSelector: '.item_thumb',
                            isFitWidth: true
                        }).resize();
                    });
                    $('#ybi_curation_suite_listening_links').masonry('reloadItems');
                    $('#ybi_curation_suite_listening_links').masonry('reload');

                    $('.item_thumb').each(function(index, obj){
                        //you can use this to access the current item
                        obj.error(function() {
                            obj.addClass('broke');
                            //$(".item_thumb").css({"display":"none"});
                        });
                    });


                }

                $("#leadpages-submit-button").click(function() {
                    var platform_id = $('#cu_listening_platform_id').val();
                    var email_address = $('#email').val();
                    var visitor_ip = $('#visitor_ip').val();
                    var visitor_country_code = $('#visitor_country_code').val();
                    var visitor_country_name = $('#visitor_country_name').val();
                    $('#subscribe_message').html('<i class="fa fa-spinner fa-pulse"></i>');

                    data = {
                        action: 'cs_le_portal_subscribe_email',
                        platform_id: platform_id,
                        email_address: email_address,
                        visitor_ip: visitor_ip,
                        visitor_country_code: visitor_country_code,
                        visitor_country_name: visitor_country_name
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: ajax_url,
                        success: function(platform_response)
                        {
                            if(platform_response.status == 'success')
                            {

                                $("#subscribe_modal").css({"display":"none","visibility":"hidden"});
                                $('.subscribe_cta').hide(800);
                                $('.cta_action_icon').html('<i class="fa fa-chevron-down"></i>');
                                $('#subscribe_message').html(platform_response.html);
                            }
                        }
                    });


                    ga('send', {
                        hitType: 'event',
                        eventCategory: 'Email',
                        eventAction: 'subscribe',
                        eventLabel: email_address
                    });

                });
                $(".subscribe_reveal_button").click(function() {
                    if ( $("#subscribe_modal").css("visibility") == "hidden"){
                        // element is hidden
                        $("#subscribe_modal").css({"display":"block","visibility":"visible"});
                        $('.cta_action_icon').html('<i class="fa fa-chevron-up"></i>');


                    } else {
                        $("#subscribe_modal").css({"display":"none","visibility":"hidden"});
                        $('.cta_action_icon').html('<i class="fa fa-chevron-down"></i>');

                    }


                });


            }); // end of doc
        </script>
        <style type="text/css">
            * {
                box-sizing: border-box;
            }

            #ybi_curation_suite_listening_links {
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
                margin: 0 0 7px 0 !important
            }

            .brick .thumb {
                text-align: center;
                width: 100%;
            }

            .source_snippet {
                clear: both;
                margin: 0 auto;
                overflow: auto;
                height: auto;
                padding: 0 10px;
            }

            .commentary {
                padding: 10px 10px 10px;
            }

            blockquote {
                padding: 0 10px;
                margin-left: 10px;
            }
            #topic_list {text-align: center; }
            #topic_list ul {display: table; text-align: center; width: 100%;}
            #topic_list ul li {display: inline-block; list-style: none; margin: 10px; padding: 10px;  /*display: table-cell;*/ width: auto;white-space: nowrap; font-size: 16px;}
            .topic {padding: 10px;}
            #sort_list {text-align: center;}
            #sort_list ul li {display: inline; list-style: none; margin: 5px; padding: 5px; font-size: 15px;}
            .sort_option {padding:10px;}
            .source {margin: 0 0 10px 10px !important; font-size: 12px;}
            .facebook, .facebook_total a {color: #3B5998;}
            .twitter, .twitter_shares a {color: #4099FF;}
            .linkedin, .linkedin_shares a {color: #4875B4;}
            .googleplus, .googleplus_shares a {color: #C63D2D;}
            .tumblr {color: #32506d;}
            .reddit { color: #ff4500;}
            .total_share, .total_shares a {color: #8aba56;}
            .share_gravity a {color: #832051;}
            .stumble_upon a {color: #eb4924;}
            .pinterest, .pinterest_shares a { color: #cc2127;}
            .most_recent a {color: #0073AA;}
            #search_results_title {width: 100%; clear: both; overflow: auto; margin: 0 auto;}
            .selected {border: 1px solid red; border-radius: 7px;}
            .content_options {text-align: right; color: red !important; margin: 0 10px 0 0;}
            .content_options a {color: red !important; }
            .subscribe_cta {text-align: center;margin-top: 20px;}

            .button {
                display: inline-block;
                text-align: center;
                vertical-align: middle;
                padding: 12px 24px;
                border: 1px solid #a12727;
                border-radius: 8px;
                background: #ff4a4a;
                background: -webkit-gradient(linear, left top, left bottom, from(#ff4a4a), to(#992727));
                background: -moz-linear-gradient(top, #ff4a4a, #992727);
                background: linear-gradient(to bottom, #ff4a4a, #992727);
                text-shadow: #591717 1px 1px 1px;
                font: normal normal bold 20px arial;
                color: #ffffff;
                text-decoration: none;
                margin: 0;
            }
            .button:hover,
            .button:focus {
                background: #ff5959;
                background: -webkit-gradient(linear, left top, left bottom, from(#ff5959), to(#b62f2f));
                background: -moz-linear-gradient(top, #ff5959, #b62f2f);
                background: linear-gradient(to bottom, #ff5959, #b62f2f);
                color: #ffffff;
                text-decoration: none;
            }
            .button:active {
                background: #982727;
                background: -webkit-gradient(linear, left top, left bottom, from(#982727), to(#982727));
                background: -moz-linear-gradient(top, #982727, #982727);
                background: linear-gradient(to bottom, #982727, #982727);
            }


            #form-preview button[type="submit"], #leadpages-submit-button {
                border-radius: 3px;
                color: white;
                font-weight: bold;
                line-height: 100%;
                margin: 0.2em auto 0;
                max-width: none;
                padding: 0.9em;
                text-align: left;
                text-shadow: 2px 2px 0 #1285dd;
                text-transform: uppercase;
            }
            #form-preview button[type="submit"] span.raquo, #leadpages-submit-button span.raquo {
                font-size: 1.4em;
                font-style: normal;
                padding-left: 0.2em;
            }

            select, textarea, input[type="text"], input[type="date"], input[type="number"], input[type="email"], input[type="url"], input[type="tel"], input[type="password"] {
                background: #e1e1e1 none repeat scroll 0 0;
                border: 1pt solid transparent;
                box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1) inset;
                box-sizing: border-box;
                color: #5a5a5a;
                display: block;
                font-size: 1.2em;
                line-height: 1.2em;
                margin: 0;
                padding: 0.6em 12pt;
                transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s;
                width: 100%;
                max-width: 100%;
                height: 48px;
            }

            #subscribe_modal_wrapper { text-align: center; overflow: auto; clear: both; margin: 0 auto;}
            #subscribe_modal {
                width: 600px;
                display: none;
                visibility: hidden;
                overflow: auto;
                margin: 0 auto;
                /*padding: 25px;*/
            }
            #subscribe_message {  color: green; font-weight: bold;margin: 20px;}

        </style>
        <script data-leadbox="1474979f3f72a2:150d83b19b46dc" data-url="https://youbrand.leadpages.co/leadbox/1474979f3f72a2%3A150d83b19b46dc/5724160613416960/" data-config="%7B%7D" type="text/javascript" src="https://youbrand.leadpages.co/leadbox-893.js"></script>
        <?php

        echo '<input type="hidden" id="cu_listening_platform_id" value="'.$view.'" />';
        echo $topic_html;
        echo '<hr />';
        echo $sort_options_html;
        echo '<hr />';
        echo '<div id="portal_content_title">'.$portal_topic_title.'</div>';
        echo '<div id="ybi_curation_suite_listening_links">' . $post_list . '</div>';

    }
    else
        echo $data['message'];

} // end of function
function get_brick_html($i, $current_url, $title, $publish_date, $display_date, $fav_icon_url, $source, $display_source, $img_src,$commentary, $snippet, $read_more,$is_user=false)
{
    $html = '';

    if (0 != $i % 2): $alternate = ' alternate'; endif;

    $date = new DateTime($publish_date);
    $pubDate = $date->format('m/d/Y');

    $background_img = '';
    if ($fav_icon_url != '')
        $background_img = ' style="background: no-repeat url(' . $fav_icon_url . ') left top; background-position: left; background-size: 16px 16px; padding-left: 21px;"';

    $thumbnail_html = '';
    if($is_user) {
        $thumbnail_html = $img_src;
    } else {
        if ($img_src)
            $thumbnail_html = '<img src="' . $img_src . '" class="item_thumb" />';
    }


    if ($commentary != '')
        $commentary = stripslashes($commentary);

    if (strlen($snippet) > 200) {
        // truncate string
        $stringCut = substr($snippet, 0, 200);

        // make sure it ends in a word so assassinate doesn't become ass...
        $snippet = substr($stringCut, 0, strrpos($stringCut, ' ')); //.'...';
    }

    $snippet .= '. <a href="' . $current_url . '" target="_blank" onclick="clicky.log(\'.'.$current_url.'\',\''.$title.'\',\'outbound\'); return false;">'.$read_more.'</a>';

    $html .= '<div class="brick">';
    if ($display_date)
        $html .= '<p class="muted">' . $pubDate . '</p>';

    $html .= '<div class="thumb"><a href="' . $current_url . '" target="_blank">' . $thumbnail_html . '</a></div>';
    $html .= '<h4><a href="' . $current_url . '" target="_blank">' . $title . '</a></h4>';

    if ($display_source)
        $html .= '<p' . $background_img . ' class="source">' . $source . '</p>';

    if ($commentary != '')
        $html .= '<div class="commentary" >' . $commentary . '</div>';

    $html .= '<blockquote class="snippet">' . $snippet . '</blockquote>';
    $html .= '</div>';
    return $html;
}

function get_portal_content_simple_html($results, $display_date, $display_source, $read_more )
{
    $html = '';
    $i=0;
    foreach ($results as $ContentItem) {
        $current_url = $ContentItem['url'];
        if($current_url == '')
            continue;

        if($i == 3) {
            $ad_html = '<a href="https://youbrand.leadpages.co/leadbox/1474979f3f72a2%3A150d83b19b46dc/5724160613416960/" target="_blank">
<img src="http://du1j7fb61oxk0.cloudfront.net/curation_suite/i/mechanics-of-curation-cover-275.jpg" />
</a>';
            $UserContentItem = array(
                'url' => 'https://youbrand.leadpages.co/leadbox/1474979f3f72a2%3A150d83b19b46dc/5724160613416960/',
                'title' => '23 Peices of Curation You Can Publish Now',
                'image_src'=> $ad_html,
                'source_name'=> 'CurationSuite.com',
                'snippet'=> 'Learn how you can easily and quickly publish content to curate...',
                'published_date'=> '',
            );
            $html .= get_portal_brick_html_by_content_item($i,$UserContentItem,$display_date,$display_source,$read_more, true);
           //$html .= get_brick_html($i,'https://youbrand.leadpages.co/leadbox/1474979f3f72a2%3A150d83b19b46dc/5724160613416960/','23 Peices of Curation You Can Publish Now',false,'','','CurationSuite.com',true,$ad_html,'','Learn how you can easily and quickly publish content to curate...','',true);
        }

        if ($i == 5) {
            $args = array('numberposts' => '1',
                'post_status' => 'publish');
            $recent_posts = wp_get_recent_posts($args);

            foreach ($recent_posts as $recent) {
                $image_src = cs_le_get_post_thumbnail($recent['ID']);
                $UserContentItem = array(
                    'url' => get_permalink($recent["ID"]),
                    'title' => $recent["post_title"],
                    'image_src' => $image_src,
                    'source_name' => 'CurationSuite.com',
                    'snippet' => 'Learn how you can easily and quickly publish content to curate...',
                    'published_date' => '',
                );
                $html .= get_portal_brick_html_by_content_item($i, $UserContentItem, $display_date, $display_source, $read_more, false, true);
            }
        }


        $date = new DateTime($ContentItem['published_date']);
        $pubDate = $date->format('m/d/Y');

        $fav_icon = $ContentItem['DomainData']['data']['fav_icon_url'];
        //$html .= get_brick_html($i,$current_url,$ContentItem['title'],$ContentItem['published_date'],$display_date,$fav_icon,$ContentItem['source_name'],$display_source,$ContentItem['image_src'],$ContentItem['PlatformDisplay']['commentary'],$ContentItem['snippet'],$read_more);
        $html .= get_portal_brick_html_by_content_item($i,$ContentItem,$display_date,$display_source,$read_more);
        $i++;
    }
    return $html;
}


function get_portal_brick_html_by_content_item($i, $ContentItem, $display_date, $display_source, $read_more, $is_ad = false, $is_user_content=false )
{
    $post_list = ''; // . $url;
    $current_url = $ContentItem['url'];
    if($current_url == '')
        return '';

        $fav_icon = '';
        if (0 != $i % 2): $alternate = ' alternate'; endif;

        $date = new DateTime($ContentItem['published_date']);
        $pubDate = $date->format('m/d/Y');

        if(array_key_exists('DomainData',$ContentItem)) {
            if($ContentItem['DomainData']['data'] || array_key_exists('fav_icon_url',$ContentItem['DomainData']['data']))
                $fav_icon = $ContentItem['DomainData']['data']['fav_icon_url'];
        }


        $background_img = '';

        if ($fav_icon != '')
            $background_img = ' style="background: no-repeat url(' . $fav_icon . ') left top; background-position: left; background-size: 16px 16px; padding-left: 21px;"';

        $thumbnail_html = '';

        if($is_ad) {
            if ($ContentItem['image_src'])
                $thumbnail_html = $ContentItem['image_src'];

        } else {
            if ($ContentItem['image_src'])
                $thumbnail_html = '<img src="' . $ContentItem['image_src'] . '" class="item_thumb" />';
        }

        $commentary = '';

        if(array_key_exists('PlatformDisplay',$ContentItem)) {
            if ($ContentItem['PlatformDisplay']['commentary'] != '')
                $commentary = stripslashes($ContentItem['PlatformDisplay']['commentary']);
        }
        $snippet = $ContentItem['snippet'];
        if (strlen($snippet) > 200) {

            // truncate string
            $stringCut = substr($snippet, 0, 200);

            // make sure it ends in a word so assassinate doesn't become ass...
            $snippet = substr($stringCut, 0, strrpos($stringCut, ' ')); //.'...';
        }

        //$snippet .= '. <a href="' . $current_url . '" target="_blank" onclick="clicky.log(this.href,\''.urldecode($ContentItem['title']).'\',\'outbound\'); return true;">'.$read_more.'</a>';
        $snippet .= '. <a href="' . $current_url . '" target="_blank" class="outbound_link">'.$read_more.'</a>';
        $content_class = ' content_item_'.$ContentItem['id'];

        $post_list .= '<div class="brick'.$content_class.'">';
        $post_list .= '<div class="content_options">';
        if(!$is_ad && !$is_user_content) {
            global $current_user; // Use global
            if(user_can( $current_user, "publish_posts" ))
                $post_list .= '<a href="javascript:;" cur_action="add" type="ignore-content-item" parameter_id="'.$ContentItem['id'] .'" class="cu_platform_action close"><i class="fa fa-minus-circle"></i>';
        }
        $post_list .= '</div>';

        if ($display_date)
            $post_list .= '<p class="muted">' . $pubDate . '</p>';

        $post_list .= '<div class="thumb"><a href="' . $current_url . '" target="_blank">' . $thumbnail_html . '</a></div>';
        $post_list .= '<h4><a href="' . $current_url . '" target="_blank">' . $ContentItem['title'] . '</a></h4>';

        if ($display_source) {
            $post_list .= '<p' . $background_img . ' class="source">' . $ContentItem['DomainData']['data']['domain_name'] . '</p>';
        }



        if ($commentary != '')
            $post_list .= '<div class="commentary" >' . $commentary . '</div>';

        $post_list .= '<blockquote class="snippet">' . $snippet . '</blockquote>';

        $post_list .= '</div>';
        $i++;

    return $post_list;
}

function get_portal_content_html($results, $display_date, $display_source, $read_more )
{
    $post_list = ''; // . $url;
    $i=0;
    foreach ($results as $ContentItem) {
        $current_url = $ContentItem['url'];
        if($current_url == '')
            continue;

        $fav_icon = '';
        if (0 != $i % 2): $alternate = ' alternate'; endif;

        $date = new DateTime($ContentItem['published_date']);
        $pubDate = $date->format('m/d/Y');

        $fav_icon = $ContentItem['DomainData']['data']['fav_icon_url'];
        $background_img = '';

        if ($fav_icon != '')
            $background_img = ' style="background: no-repeat url(' . $fav_icon . ') left top; background-position: left; background-size: 16px 16px; padding-left: 21px;"';

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
        $post_list .= '<h4><a href="' . $current_url . '" target="_blank">' . $ContentItem['title'] . '</a></h4>';

        if ($display_source) {
            $DomainData = $ContentItem['DomainData'];
            $post_list .= '<p' . $background_img . ' class="source">' . $DomainData['source_domain'] . '</p>';
        }



        if ($commentary != '')
            $post_list .= '<div class="commentary" >' . $commentary . '</div>';

        $post_list .= '<blockquote class="snippet">' . $snippet . '</blockquote>';

        $post_list .= '</div>';
        $i++;
    }
    return $post_list;
}

/**
 * This gets the listening content for the custom listening page.
 *
 * @return JSON HTML
 */
function cs_le_get_portal_content()
{
    $platform_id = trim($_POST['platform_id']);

    $topic_id = trim($_POST['topic_id']);
    $order_by = trim($_POST['order_by']);
    $topic_name = trim($_POST['topic_name']);
    $sort_text = trim($_POST['sort_text']);
    $search_term_element = trim($_POST['le_search_term_element']);
    $send_data = array('sort' => $order_by);
    $search_url_arr = array($platform_id,$topic_id, 25);
    $data = ybi_curation_suite_api_call('display/content/portal',$send_data, $search_url_arr);
    $total = $data['total'];
    $results = $data['results'];
    $url = $data['url'];
    //$html = '<div>'.$url .'</div>';
   // $html = '<div style="text-align: center;" id="search_results_title">You\'re viewing top content in <strong>'.$topic_name. '</strong> by <strong>' .$sort_text . '</strong>...</div>';

    $html = get_portal_content_simple_html($results, false, true, 'read more...');
    echo json_encode(array('status'=> 'success', 'results' => $html) );
    die();
}

    add_action('wp_ajax_cs_le_get_portal_content', 'cs_le_get_portal_content');
    add_action('wp_ajax_nopriv_cs_le_get_portal_content', 'cs_le_get_portal_content');
/**
 * This function handles the majority of the platform actions for content, keywords, and sources
 * it takes the relevant data and combines it to the API URL based on the action
 *
 * @return JSON array
 */
function cs_le_get_portal_content_platform_action()
{
    //global $post;
    $platform_id = trim($_POST['platform_id']);
    $cur_action = trim($_POST['cur_action']);  // get the current action (add, delete)
    $time_frame = trim($_POST['time_frame']);  // get the current action (add, delete)
    $type  = trim($_POST['type']); // get the type of action it is
    $parameter_id  = trim($_POST['parameter_id']); // get the parameter id, usually the id (content_item_id) from the listening platform
    $curated_url  = trim($_POST['curated_url']); // get the parameter id, usually the id (content_item_id) from the listening platform

    //$id = $post->ID;
    $after_curation_action  = trim($_POST['after_curation_action']);

    if($after_curation_action != '') // the after curate action controls if the curation is hidden from the user, if it exists
        $type = $type . '-' . $after_curation_action; // add this to the type that get's sent to the API, the API knows how to handle this

    $data = array('curated_url' => $curated_url, 'time_frame' => $time_frame);


    $cu_current_display_page  = trim($_POST['cu_current_display_page']);

    $parm_arr = array('platform-action',$platform_id,$cur_action,$type,$parameter_id);
    $data = ybi_curation_suite_api_call('',$data, $parm_arr);

    // all these below are essentiall class names that are unique that get hidden if the API call was a success
    if($type == 'ignore-content-item')
        $hide_element = 'cu_cid_row_'.$parameter_id;

    if($type == 'ignore-keyword')
        $hide_element = 'cu_keyword_'.$parameter_id;

    if($type == 'save-content-item')
    {
        if($cur_action == 'add')
            $hide_element = 'save_content_item_'.$parameter_id; // this is the tag element
        else
            $hide_element = 'cu_cid_row_'.$parameter_id;  // we hide the row if the user clicked unsave
    }
    if($type == 'curated-content-item-curate-remove')
    {
        $hide_element = 'cu_cid_row_'.$parameter_id;  // this will hide the row after the user clicks an add to post action button
    }
    if($type == 'ignore-source')
    {
        $source_row_name = str_replace(".", "_", $parameter_id);
        $hide_element = 'source_'.$source_row_name;  // this will hide the row after the user clicks an add to post action button
    }


    $status = $data['status'];
    $message = $data['message'];
    echo json_encode(array('status' => $status, 'message' => $message, 'cur_action' => $cur_action, 'type' => $type,
        'hide_element' => $hide_element, 'cu_current_display_page' => $cu_current_display_page, 'passed_parameter_id' => $parameter_id, 'url' => $data['url']
    ) );
    die();
}


/**
 * This gets the listening content for the custom listening page.
 *
 * @return JSON HTML
 */
function cs_le_portal_subscribe_email()
{
    $platform_id = trim($_POST['platform_id']);
    $email_address = trim($_POST['email_address']);
    $visitor_ip = trim($_POST['visitor_ip']);
    $visitor_country_code = trim($_POST['visitor_country_code']);
    $visitor_country_name = trim($_POST['visitor_country_name']);
    $send_data = array(
        'email_address' => $email_address,
        'visitor_ip' => $visitor_ip,
        'visitor_country_code' => $visitor_country_code,
        'visitor_country_name' => $visitor_country_name,

    );
    ///@api_key/@platform_id/@action
    $param_arr = array($platform_id, 'subscribe');
    $data = ybi_curation_suite_api_call('platform/email',$send_data, $param_arr);
    $html = $data['message'];

    echo json_encode(array('status'=> 'success', 'html' => $html) );
    die();
}

    add_action('wp_ajax_cs_le_get_portal_content_platform_action', 'cs_le_get_portal_content_platform_action');
    add_action('wp_ajax_nopriv_cs_le_portal_subscribe_email', 'cs_le_portal_subscribe_email');
    add_action('wp_ajax_nopriv_cs_le_get_portal_content_platform_action', 'cs_le_get_portal_content_platform_action');

function cs_le_get_post_thumbnail($pID,$thumb='full') {
    $imgsrc = FALSE;
    if (has_post_thumbnail($pID)) {
        $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($pID),$thumb);
        $imgsrc = $imgsrc[0];
    } elseif ($postimages = get_children("post_parent=$pID&post_type=attachment&post_mime_type=image&numberposts=0")) {
        foreach($postimages as $postimage) {
            $imgsrc = wp_get_attachment_image_src($postimage->ID, $thumb);
            $imgsrc = $imgsrc[0];
        }
    } elseif ($imgsrc == '') {
        $post = get_post($pID);
        apply_filters('the_content', $post->post_content);
        if(preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', $post->post_content, $match) != FALSE)
        $imgsrc = $match[1];
    }
    if($imgsrc) {
        //$imgsrc = '<a href="'. get_permalink().'"><img src="'.$imgsrc.'" alt="'.get_the_title().'" class="summary-image" /></a>';
        return $imgsrc;
    }
}