<?php
// we define this so wordpress knows it's  an iframe request
//namespace curationsuite;
define('IFRAME_REQUEST', true);

// for certain installs we can't put the parsing files in the wp-admin folder, in that case we have a setting that calls it in the plugin dir
// since this file isn't included it needs to know where the admin.php file is so we pass it the homepath as a url parameter
$homepath = (isset($_GET['homepath']) ? $_GET['homepath'] : '');

/** WordPress Administration Bootstrap */
//require_once( dirname( __FILE__ ) . '/admin.php' );
if ($homepath != '')
    require_once($homepath . '/wp-admin/admin.php');
else
    require_once('./admin.php'); // files are in wp-admin folder so go up one dir and require the file


// check if we've turned on error reporting
$isErrorOn = get_option('ybi_turn_on_error_reporting') == 'on';
if ($isErrorOn) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
}

/*if ( ! current_user_can('edit_posts') )
	wp_die( __( 'Cheatin&#8217; uh, can you edit posts?' ) );*/

if (!current_user_can('edit_posts') || !current_user_can(get_post_type_object('post')->cap->create_posts)) {
    wp_die(
        '<h1>' . __('Cheatin&#8217; uh?') . '</h1>' .
        '<p>' . __('You are not allowed to create posts as this user.') . '</p>',
        403
    );
}

if (!class_exists('ybi_product')) {
    wp_die('<strong>Curation Suite requires the latest version of the You Brand, Inc. Plugin</strong><br /><br />
		<a href="https://members.youbrandinc.com/" target="_blank">Click here to download</a> or 
		go back to the WordPress <a href="' . get_admin_url(null, 'plugins.php') . '">Plugins page</a>');
}

$CurationSuiteProduct = new ybi_product('Curation Suite');
if (do_validate_license($CurationSuiteProduct) !== true)
    wp_die(__('Please validate Curation Suite with the YBI Licensing Plugin'));

$showActions = 0;
$ItFound = '';
$IgnoreVideos3rdParty = false;
$IgnoreVideos = false;
$debugS = '';
require_once(YBI_CURATION_SUITE_PATH . "inc/parse-page-worker-functions.php");

// get the url and the type of protocol
$url = $_GET["url"];
$http_type = 'http';

// in the meta-worker.php we replace http and https with xxxx or xxxx because for some servers if you include those in an iframe SRC it won't load
// this is most likely due to some form of mod_sec or other security setting, especially a problem with hostgator.
if (strpos($url, 'xxxxs') !== false) {
    $url = str_ireplace("xxxxs", "https://", $url);
    $http_type = 'https';
} else {
    $url = 'http://' . $url;
}

// replace space with %20 not plus as urlencode would do

$url = returnSpecialDomainParsing($url);
$parsedData = file_get_contents('http://35.153.147.135/?url=' . urlencode($url));
if (!empty($parsedData)) {
    $parsedData = json_decode($parsedData, JSON_OBJECT_AS_ARRAY);
    if (!empty($parsedData['success']['scrapping']))
        $parsedData = $parsedData['success']['scrapping'];
    else
        $parsedData = null;
} else {
    $parsedData = null;
}

if (empty($parsedData))
    return;
else
    $url = $parsedData['orig_url'];

// get the options
$options = get_option('curation_suite_data');
//$debugS = '';

//	wp_enqueue_style( 'colors' );
wp_enqueue_script('post');
_wp_admin_html_begin();
do_action('admin_print_styles');
do_action('admin_print_scripts');
do_action('admin_head');

?>
<script type="text/javascript">
    // this is added here for redundancy because some setups it doesn't take the include of these values in the curation-suite.php
    var ajaxurl = '<?php echo esc_js(admin_url('admin-ajax.php', 'relative')); ?>',
        pagenow = 'press-this',
        typenow = 'post',
        adminpage = 'press-this-php',
        curation_suite_default_video_width = 640,
        curation_suite_default_video_height = 360,
        thousandsSeparator = '<?php echo addslashes($wp_locale->number_format['thousands_sep']); ?>',
        decimalPoint = '<?php echo addslashes($wp_locale->number_format['decimal_point']); ?>',
        isRtl = <?php echo (int)is_rtl(); ?>;
</script>

<link rel="stylesheet" type="text/css"
      href="<?php echo plugins_url('youbrandinc_products/font-awesome/css/font-awesome.min.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url('curation-suite/css/parse-page-worker.css') ?>"/>
<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
</head>
<body>
<?php
if ($isErrorOn)
    echo '<strong>Error reporting is turned ON</strong>';

// what follows is a mix of php and jQuery code.
$title = $parsedData['title'];
$totalSummary = $parsedData['summary'];
$sourceDomain = $parsedData['domain'];
$meta_desc = $parsedData['description'];
$meta_keywords = $parsedData['keywords'];
// get the meta description and echo it tot he javascript
//if($totalSummary == '')
//$totalSummary = $sentances_or_paragraphs[1];
?>
<input type="hidden" id="title" value="<?php echo stripslashes((clean_string_simple(($title)))); ?>"/>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        // what follows is a combination of php and jQuery. This all is printed to the page right away and used to set the elements in the meta-worker.php (below the post box).
        // these elmenets are here first so we can set them first, the page parsing is down below and consists mainly of PHP code
        // also below this block is the functions for using the buttons and various shorcuts found on the CurationSuite meta box in the post
        var summaryText = '';
        var ClippedSummary = '';
        var title = '';
        var source = '';

        <?php
        echo "\nsource = '" . clean_string_simple($sourceDomain) . "';\n";
        echo "\ntitle = $('#title').val();";
        echo "\nurl = '" . $url . "';\n";
        ?>

        $('#curated_headline', window.parent.document).val(title);
        $('#og_curated_headline', window.parent.document).val(title);
        $('#curated_link_text', window.parent.document).val(title);

        $('#source_domain', window.parent.document).val(source);
        $('#via_domain', window.parent.document).html(source);
        // these set the shortcut text for the links
        $('#via_domain_via', window.parent.document).html('Via ' + source);
        $('#via_domain_ht', window.parent.document).html('h/t ' + source);
        $('#read_more_via_domain', window.parent.document).html('read more at ' + source);

        <?php

        $user_shorcuts = $options['curation_suite_custom_link_text'];
        $pieces = explode("|", $user_shorcuts);
        $i = 1;
        $user_shortcuts_html = '';
        if ($user_shorcuts):
            foreach ($pieces as $val) {
                $view_text = '';
                $user_shortcuts_html .= ' | <a href="javascript:;" class="change_link_text">' . $val . '</a>';
                $user_shortcuts_html .= ' | <a href="javascript:;" class="change_link_text">' . $val . ' ' . $sourceDomain . '</a>';
                $i++;
            }
        endif;

        ?>
        $('#users_link_text_shortcuts', window.parent.document).html('<?php echo addslashes($user_shortcuts_html); ?>');

        $('.tool_visit_link', window.parent.document).attr('href', url);

        var moz_link = 'http://www.opensiteexplorer.org/links?site='; //$('.tool_moz_link',window.parent.document).attr('href');
        $('.tool_moz_link', window.parent.document).attr('href', moz_link + url);

        var tool_majestic_link = 'http://www.majesticseo.com/reports/site-explorer/summary/'; //$('.tool_majestic_link',window.parent.document).attr('href');
        $('.tool_majestic_link', window.parent.document).attr('href', tool_majestic_link + source + '?IndexDataSource=F'); //?IndexDataSource=F

        var tool_ahrefs_link = 'https://ahrefs.com/site-explorer/overview/subdomains/?target='; //$('.tool_ahrefs_link',window.parent.document).attr('href');
        $('.tool_ahrefs_link', window.parent.document).attr('href', tool_ahrefs_link + url);

    });
</script>
<?php
$total_ext_links = count($parsedData['links']['external']);
$total_int_links = count($parsedData['links']['internal']);
$internalLinksArr = $parsedData['links']['internal'];
$externalLinksArr = $parsedData['links']['external'];
$s_feedback = "";
// here we are loading the sentaces will add this from the CT Plugin
$sentances_or_paragraphs = $parsedData['paragraphs'];

?>
<div id="images" class="selector_div">

    <div id="quick_add_image_w">
        <a href="javascript:;" id="reload_thumbnail" rel="" style="float: left; font-size: 12px;"><i
                    class="fa fa-refresh" id="reload_thumbnail_indicator"></i> Reload Screenshot</a>
        <label id="quick_add_lbl">
            <?php
            $curation_suite_upload_images = 0;
            if (array_key_exists('curation_suite_upload_images', $options))
                $curation_suite_upload_images = $options['curation_suite_upload_images'];
            ?>
            <input type="checkbox"
                   id="upload_quick_add_images" <?php checked(1, $curation_suite_upload_images, true); ?> />Upload Quick
            Add Images</label></div>
    <?php $thumbnail_url = 'https://s.wordpress.com/mshots/v1/' . urlencode($url) . '?w=600&r=3' ?>
    <div class="found_image_w">
        <a href="javascript:;"><img src="<?php echo $thumbnail_url; ?>" class="select_image imagedropshadow"
                                    id="content_thumbnail" alt="<?php echo($thumbnail_url); ?>"
                                    title="<?php echo($thumbnail_url); ?>"/></a>
    </div>


    <?php

    $imageArr = $parsedData['images'];
    //$total_images = count($parsedData['images']);
    // this holds the classes of the images we display
    $displayed_img_class_arr = array();
    $image_count = 0;
    foreach ($imageArr as $theSRC) {
        if(strlen($theSRC) < 10) {
            continue;
        }

        if(checkForBadImage($theSRC)) {
            continue;
        }
        if($image_count==1)
            break;

        $displayed_img_class_arr[] = 'found_image_' . $total_images;
        echo '<div class="found_image_w found_image_' . $total_images . '_w"><a href="javascript:;"><img src="' . $theSRC . '" class="select_image imagedropshadow found_image_' . $total_images . '" alt="' . $theSRC . '" title="' . $theSRC . '" /><a href="javascript:;" class="add_image_to_post" data-id="' . $total_images . '"><i class="fa fa-plus"></i> Add to Post</a><a href="javascript:;" class="set_image_featured" data-id="' . $total_images . '"><i class="fa fa-star"></i> Set Featured</a>
		 </a></div>';
        $image_count++;
    }
    $total_images = $image_count;
    ?>
</div>
<input type="hidden" id="sourceDomain" value="<?php echo $sourceDomain; ?>"/>
<div class="inside live_curating selector_div" name="live_curating" id="all_paragraphs">
    <?php
    $i = 1;
    $allText = '';
    $totalParagraphs = 0;
    //$debugS .= '<br>sentances_or_paragraphs [arraywalk]: '. count($sentances_or_paragraphs);
    foreach ($sentances_or_paragraphs as $curParagraph):

        //$totalLen = strlen(trim($curParagraph));
        $totalLen = str_word_count($curParagraph, 0);

        $curParagraph = (stripslashes($curParagraph));

        // if it's not a repull we don't show sentances one word or less.
        if (!$isRepull) {
            if ($totalLen <= 1)
                continue;
        }
        $curParagraph = preg_replace("/\r|\n/", " ", $curParagraph);
        ?>
        <div class="visual_row">
            <div id="raw_paragraph_actions_<?php echo $totalParagraphs; ?>" class="raw_paragraph_actions">
                <a href="javascript:;" class="add_raw_paragraph" data-add-type="plain"
                   rel="<?php echo $totalParagraphs; ?>"><i class="fa fa-arrow-circle-left"></i></a><br>
                <a href="javascript:;" class="add_raw_paragraph" data-add-type="blockquote"
                   rel="<?php echo $totalParagraphs; ?>"><i class="fa fa-quote-left"></i></a>
            </div>
            <div class="raw_paragraph_content">
                <p draggable="true"><span
                            class="visual_paragraph visual_paragraph_add visual_paragraph_<?php echo $totalParagraphs; ?>"
                            name="<?php echo $totalParagraphs; ?>" rel="<?php echo $totalParagraphs; ?>">
          	<?php echo $curParagraph; ?></span>&nbsp;&nbsp;(<strong><?php echo $totalLen; ?></strong>)<a
                            href="javascript:;" class="p<?php echo $totalParagraphs; ?> add_to_visual add_to"
                            name="<?php echo $totalParagraphs; ?>"> <i class="fa fa-plus-circle"></i>add-to</a></p>
            </div>
        </div>

        <?php
        // if we got this far then this is a paragraph we are going to display
        $totalParagraphs++;
    endforeach; //while ($i <= count($sentances_or_paragraphs))
    ?>
</div>

<div id="summary_meta_text" class="selector_div">
    <?php if ($totalSummary):
        //htmlspecialchars_decode(stripslashes(removeUnwantedText($totalSummary)), ENT_QUOTES);
        //$totalSummary = (($totalSummary));
        $totalSummary = stripslashes($totalSummary);
        //$totalSummary = cleanString($totalSummary);
        $totalSummary = trim(stripslashes($totalSummary));
        //htmlspecialchars($totalSummary, ENT_COMPAT);
        ?>
        <h3>Summary Text:</h3>
        <div class="visual_row">
            <div id="raw_paragraph_actions_summary_text" class="raw_paragraph_actions">
                <a href="javascript:;" class="add_raw_paragraph" data-add-type="plain" rel="summary_text"><i
                            class="fa fa-arrow-circle-left"></i></a><br>
                <a href="javascript:;" class="add_raw_paragraph" data-add-type="blockquote" rel="summary_text"><i
                            class="fa fa-quote-left"></i></a>
            </div>
            <div class="raw_paragraph_content">
                <p><a href="javascript:;"
                      class="summary_text psummary_text visual_paragraph_summary_text visual_paragraph_add visual_paragraph"
                      name="summary_text" rel="summary_text">
                        <?php echo $totalSummary; ?></a>
                    <!-- Note the class psummary_text above this is so the jquery function above will do the add too for the link below -->
                    <a href="javascript:;" class="add_to_visual add_to" name="summary_text"><i
                                class="fa fa-plus-circle"></i>add-to</a></p>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($meta_desc): ?>
        <h3>Meta Text:</h3>
        <div class="visual_row">
            <div id="raw_paragraph_actions_meta_text" class="raw_paragraph_actions">
                <a href="javascript:;" class="add_raw_paragraph" data-add-type="plain" rel="meta_text"><i
                            class="fa fa-arrow-circle-left"></i></a><br>
                <a href="javascript:;" class="add_raw_paragraph" data-add-type="blockquote" rel="meta_text"><i
                            class="fa fa-quote-left"></i></a>
            </div>
            <div class="raw_paragraph_content">
                <p><a href="javascript:;"
                      class="meta_text pmeta_text visual_paragraph_meta_text visual_paragraph_add visual_paragraph"
                      name="meta_text" rel="meta_text">
                        <?php
                        //$meta_desc = stripslashes(cleanString($meta_desc));
                        $meta_desc = stripslashes($meta_desc);
                        echo $meta_desc; ?>
                    </a><a href="javascript:;" class="meta_text add_to_visual add_to" name="meta_text"><i
                                class="fa fa-plus-circle"></i>add</a></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($meta_keywords):
        ?>
        <h3>Tags:</h3>
        <div class="meta_tag_added"><i class="fa fa-plus"></i> Tag Added</div>
        <?php
        $pieces = $meta_keywords;
        sort($pieces); // apply default sorting (alpha)
        //var_dump($pieces);
        $i = 1;
        $tagsCombined = '';
        foreach ($pieces as $val) {

            if ($val != ''):
                if ($i > 1): ?>, <?php endif; ?><a href="javascript:;" class="meta_tags meta_tags_add visual_paragraph"
                                                   name="meta_tags"
                                                   rel="<?php echo cleanString($val); ?>"><?php echo trim(stripslashes(cleanString($val))); ?></a><?php

                if ($i > 1)
                    $tagsCombined .= ',';
                $tagsCombined .= $val;
                $i++;
            endif; //if($val != '')
        }
        ?>
        <?php if ($tagsCombined != ''): ?><a href="javascript:;"
                                             class="meta_tags meta_tags_add visual_paragraph meta_add_all"
                                             name="meta_tags" id="all_suggested_tags"
                                             rel="<?php echo cleanString($tagsCombined); ?>"><i
                    class="fa fa-plus-circle"></i> add all</a><?php endif; ?>
    <?php
    endif;
    ?>

</div>
<div id="list_content" class="selector_div">
    <?php
    $all_list_arr = (array)$parsedData['lists'];
    foreach ($all_list_arr as $l_key => $l_val) {
        $all_list_arr[$l_key] = implode('<br />', $l_val);
    }
    ?>
    <?php
    $totalLists = count($all_list_arr);
    $i = 1;
    foreach ($all_list_arr as $val) {
        $val = stripslashes($val);
        ?>
        <div class="list_item_block">
            <h4>List #<?php echo $i; ?></h4>
            <div class="visual_row">
                <div id="raw_paragraph_actions_list<?php echo $i ?>" class="raw_paragraph_actions">
                    <a href="javascript:;" class="add_raw_paragraph" data-add-type="plain" data-type-text="html"
                       rel="list<?php echo $i ?>"><i class="fa fa-arrow-circle-left"></i></a><br>
                    <a href="javascript:;" class="add_raw_paragraph" data-add-type="blockquote" data-type-text="html"
                       rel="list<?php echo $i ?>"><i class="fa fa-quote-left"></i></a>
                </div>
                <div class="raw_paragraph_content">
                    <a href="javascript:;" class="visual_paragraph visual_paragraph_add" name="list<?php echo $i ?>"
                       data-content-type="list" rel="list<?php echo $i ?>">
                        <?php echo stripslashes(($val)); ?>
                    </a>
                    <a href="javascript:;" class="list<?php echo $i ?> add_to_visual add_to"
                       name="list<?php echo $i ?>"><i class="fa fa-plus-circle"></i>add_to</a>
                    <a href="#" style="display: none;"
                       class="visual_paragraph_list<?php echo $i ?> plist<?php echo $i ?>">
                        <?php echo stripslashes(replaceBRwithLB($val)); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        $i++;
    }
    ?>

</div>
<div id="social" class="selector_div">
    <?php
    $total_social = 0;
    $showTwitter = true;
    $showFacebook = true;
    $showGooglePlus = true;
    $showInstagram = true;
    // we get an array of slideshare values (in an array with each element representing one slideshare)
    $allPotentialSlideShareArr = $parsedData['social']['slide_share'];

    $video_id = 0;
    $full_video_url = '';
    $total_videos = 0;
    $allPotentialVideosArr = $parsedData['videos'];
    $allInstagramSRCArr = $parsedData['social']['instagram'];
    $all_vine_arr = $parsedData['social']['vine'];
    $all_imgur_arr = $parsedData['social']['imgur'];
    $foundFacebookArr = $parsedData['social']['facebook'];

    foreach ($allPotentialSlideShareArr as $element) {
        $embedSRC = 'http://www.slideshare.net/slideshow/embed_code/' . $element['id'];
        if ($theSRC != '') {
            ?>
            <div class="slideshare_block">
                <div class="slideshare_left">
                    <iframe src="<?php echo $embedSRC; ?>" width="340" height="290" frameborder="0" marginwidth="0"
                            marginheight="0" scrolling="no"
                            style="border:1px solid #CCC; border-width:1px 1px 0; margin-bottom:5px; max-width: 100%;"
                            allowfullscreen></iframe>
                </div>
                <div class="slideshare_right">
                    <div class="slideshare_links">
                        <p><input type="text" value="<?php echo $embedSRC ?>" style="width: 88%; height: 22px;"></p>
                        <p><a href="javascript:;" rel="<?php echo $embedSRC; ?>" class="add_slideshare" name="add_link"><i
                                        class="fa fa-link"></i> Add as Link</a></p>
                        <p><a href="javascript:;" rel="<?php echo $embedSRC; ?>" class="add_slideshare"
                              name="add_iframe_slidshare"><i class="fa fa-video-camera"></i> Add as iFrame</a></p>
                        <p><a href="<?php echo $embedSRC; ?>" target="_blank"><i class="fa fa-caret-square-o-right"></i>
                                See on SlideShare</a></p>
                    </div>
                    <div class="video_image">
                        <img src="<?php echo $element['thumbnail']; ?>" class="select_image"
                             alt="<?php echo $element['thumbnail'];; ?>"/>
                    </div>

                </div>
            </div>
            <?php
            $total_social++;
        }
    }

    if ($showFacebook):
        $loadFacebookCode = false;

        /*foreach($internalLinksArr as $link) {
            if(strpos($link,'/posts/') !== false) {
                $foundFacebookArr[] = $link;
            }
        }
        var_dump($foundFacebookArr);*/


    if ($foundFacebookArr): ?>
        <h3 class="facebook"><i class="fa fa-facebook"></i> Facebook Embeddable Updates</h3>
        <?php
        foreach ($foundFacebookArr as $theSRC) : ?>
            <div class="embed_row">
                <div class="embed_side_wrapper">
                    <div class="embed_side_inside">
                        <div class="embed_side_content_action">
                            <a href="javascript:;" data-url="<?php echo $theSRC; ?>"
                               class="add_embed_content_to_post embed_action_link">
                                <i class="fa fa-caret-left"></i></a>
                        </div>
                    </div>
                    <div class="embed_content_wrapper">
                        <!--<div id="fb-root"></div>
                        <div class="fb-post" data-href="<?php /*echo $theSRC; */?>" data-width="466">
                            <div class="fb-xfbml-parse-ignore"></div>
                        </div>-->
                        <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fcurationsuite%2Fposts%2F2031774007087012&width=500" width="500" height="453" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>

                    </div>
                </div>


                <p><a href="javascript:;" class="add_plaintext_link" rel="<?php echo $theSRC; ?>"><i
                                class="fa fa-facebook facebook"></i> Add Post to Staging</a> |
                    <a href="<?php echo $theSRC; ?>" target="_blank" class="facebook"><i
                                class="fa fa-facebook facebook"></i> Visit Post</a></p>
                <input type="text" value="<?php echo urldecode($theSRC); ?>" style="width: 88%; height: 22px;"/><br/>
            </div>
            <?php
            $loadFacebookCode = false;
            $total_social++;
        endforeach;
    endif;
    ?>
        <?php if ($loadFacebookCode): ?>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
    <?php endif; //if($loadFacebookCode)
    endif; //$showFacebook;

    if ($showTwitter):
        $foundTwitterStatusesArr = $parsedData['social']['twitter'];
        if ($foundTwitterStatusesArr): ?>
            <?php foreach ($foundTwitterStatusesArr as $theSRC) {

                $total_social++;
                ?>
                <h3 class="twitter"><i class="fa fa-twitter"></i> Twitter Status</h3>
                <div class="embed_row">
                    <div class="embed_side_wrapper">
                        <div class="embed_side_inside">
                            <div class="embed_side_content_action">
                                <a href="javascript:;" data-url="<?php echo $theSRC; ?>"
                                   class="add_embed_content_to_post embed_action_link">
                                    <i class="fa fa-caret-left"></i></a>
                            </div>
                        </div>
                        <div class="embed_content_wrapper">
                            <?php echo wp_oembed_get($theSRC); ?>
                        </div>
                    </div>
                    <p><a href="javascript:;" class="add_plaintext_link" rel="<?php echo $theSRC; ?>"><i
                                    class="fa fa-twitter-square twitter"></i> Add Tweet to Staging</a> |
                        <a href="<?php echo $theSRC; ?>" target="_blank" class="twitter"><i
                                    class="fa fa-twitter twitter"></i> Visit Tweet</a></p>
                    <input type="text" value="<?php echo urldecode($theSRC); ?>"
                           style="width: 88%; height: 22px;"/><br/>
                </div>
            <?php } ?>
        <?php endif; //foundTwitterStatusesArr
    endif; //$showTwitter
    ?>

    <?php
    if ($showInstagram):
        if ($allInstagramSRCArr):
            foreach ($allInstagramSRCArr as $theSRC) {
                $starts_with = ybi_startsWith($theSRC, 'https');
                if (!$starts_with) {
                    $starts_with = ybi_startsWith($theSRC, 'http');
                    if (!$starts_with)
                        $theSRC = 'https:' . $theSRC;
                }

                // note we remove embed because this is the raw embed URL for oembed for WordPress, but we put it back in the iframe down below
                $theSRC = str_replace("embed/", "", $theSRC);
                $total_social++;
                ?>
                <h3 class="instagram_row"><i class="fa fa-instagram"></i> Instagram Post</h3>
                <div class="embed_row">
                    <div class="embed_side_wrapper">
                        <div class="embed_side_inside">
                            <div class="embed_side_content_action">
                                <a href="javascript:;" data-url="<?php echo $theSRC; ?>"
                                   class="add_embed_content_to_post embed_action_link">
                                    <i class="fa fa-caret-left"></i></a>
                            </div>
                        </div>
                        <div class="embed_content_wrapper">
                            <iframe src="<?php echo $theSRC; ?>embed/" width="400" height="484" frameborder="0"
                                    scrolling="no" allowtransparency="true"></iframe>
                        </div>
                    </div>
                    <div>
                        <p><a href="javascript:;" class="add_instagram_embed" rel="<?php echo $theSRC; ?>"><i
                                        class="fa fa-instagram"></i> Add Instagram Embed to Staging</a></p>
                        <input type="text" value="<?php echo urldecode($theSRC); ?>" style="width: 88%; height: 22px;"/><br/>
                    </div>


                </div>
            <?php }
        endif; //$allInstagramSRCArr
    endif; //showInstagram
    ?>

    <?php
    $showVine = true;
    if ($showVine):
        if ($all_vine_arr): ?>

            <?php
            foreach ($all_vine_arr as $theSRC) {
                $total_social++;
                ?>
                <h3 class="vine"><i class="fa fa-vine vine"></i> Vine Posts</h3>
                <div style="clear: both; overflow:auto; margin: 0 auto;">
                    <div class="embed_side_wrapper">
                        <div class="embed_side_inside">
                            <div class="embed_side_content_action">
                                <a href="javascript:;" data-url="<?php echo $theSRC; ?>"
                                   class="add_embed_content_to_post embed_action_link">
                                    <i class="fa fa-caret-left"></i></a>
                            </div>
                        </div>
                        <div class="embed_content_wrapper">
                            <iframe src="<?php echo $theSRC; ?>" width="400" height="464" frameborder="0" scrolling="no"
                                    allowtransparency="true"></iframe>
                        </div>
                    </div>
                    <div>
                        <p><a href="javascript:;" class="add_vine_embed vine" rel="<?php echo $theSRC; ?>"><i
                                        class="fa fa-vine"></i> Add Vine Embed</a></p>
                        <input type="text" value="<?php echo urldecode($theSRC); ?>" style="width: 88%; height: 22px;"/><br/>
                    </div>
                </div>
            <?php }
        endif; //$AllViune
    endif; //ShowAllVine
    ?>

    <?php
    $showImgur = true;
    if ($showImgur):
        if ($all_imgur_arr): ?>
            <?php
            foreach ($all_imgur_arr as $theSRC) {
                $total_social++;
                ?>
                <h3 class="imgur"><i class="fa fa-picture-o"></i> Imgur Content</h3>
                <div style="clear: both; overflow:auto; margin: 0 auto;">
                    <div class="embed_side_wrapper">
                        <div class="embed_side_inside">
                            <div class="embed_side_content_action">
                                <a href="javascript:;" data-url="<?php echo $theSRC; ?>"
                                   class="add_embed_content_to_post embed_action_link">
                                    <i class="fa fa-caret-left"></i></a>
                            </div>
                        </div>
                        <div class="embed_content_wrapper">
                            <blockquote class="imgur-embed-pub" lang="en" data-id="FCRn5AJ"><a
                                        href="<?php echo $theSRC; ?>/">View post on imgur.com</a></blockquote>
                            <script async src="//s.imgur.com/min/embed.js" charset="utf-8"></script>

                        </div>
                    </div>
                    <div>
                        <p><a href="javascript:;" class="add_vine_embed vine" rel="<?php echo $theSRC; ?>"><i
                                        class="fa fa-vine"></i> Add Vine Embed</a></p>
                        <input type="text" value="<?php echo urldecode($theSRC); ?>" style="width: 88%; height: 22px;"/><br/>
                    </div>
                </div>
            <?php }
        endif; //$AllViune
    endif; //ShowAllVine
    ?>


    <?php
    if ($showGooglePlus):
        $possibleGooglePlus = $parsedData['social']['google_plus'];
     if ($possibleGooglePlus): ?>
            <h3 class="google_plus_row"><i class="fa fa-google-plus"></i> Google+ Embeddable Posts</h3>

            <?php foreach ($possibleGooglePlus as $theSRC) {
                $total_social++;
                ?>
                <div style="clear: both; overflow:auto; margin: 0 auto;">
                    <div style="width: 520px; float: left; overflow:auto; margin: 0 auto;">
                        <div class="g-post" data-href="<?php echo $theSRC; ?>">Google+ Embeded Post</div>
                    </div>
                    <div style="overflow: hidden; margin: 30px 0 0 30px;">
                        <input type="text" value="<?php echo urldecode($theSRC); ?>" style="width: 88%; height: 22px;"/><br/>
                        <p><a href="javascript:;" class="add_google_plus_embed" rel="<?php echo $theSRC; ?>"><i
                                        class="fa fa-google-plus-square"></i> Add Embedded Post</a></p>
                        <p><a href="<?php echo $theSRC; ?>" target="_blank" class="google_plus_row"><i
                                        class="fa fa-google-plus"></i> <i class="fa fa-external-link"></i> Visit
                                Plus</a></p>
                    </div>
                </div>
            <?php } ?>
        <?php endif;
    endif; //$showGooglePlus
    ?>

</div>


<div id="videos" class="selector_div">
    <?php
    /* FINDVIDEOS */

    if (!$IgnoreVideos):
        foreach ($allPotentialVideosArr as $element) {
            $video_type = $element['type'];
            $theSRC = $element['src'];


            if ($theSRC != '') {
                $foundVideo = false;
                $video_source_name = '';
                $video_thumb_url = '';
                $icon = '';
                $full_video_url = '';
                if ($video_type == 'youtube') {
                    if (strpos($theSRC, "videoseries") !== false) {
                        //http://www.youtube.com/embed/videoseries?list=UU7vZJ3iwqo3a_FBfVbbUK_A&hl=en_US
                        $full_video_url = 'http://www.' . $theSRC;
                        $full_embed_link = '//www.' . $theSRC;
                        $foundVideo = true;
                    } else {
                        $video_id = ybi_cs_getYouTubeVideoID($theSRC);
                        if ($video_id <> '') {
                            $full_video_url = 'http://www.youtube.com/watch?v=' . $video_id;
                            $full_embed_link = '//www.youtube.com/embed/' . $video_id . '?html5=1';
                            $video_thumb_url = 'http://img.youtube.com/vi/' . $video_id . '/0.jpg';
                            $foundVideo = true;
                        }
                    }
                    $video_source_name = 'YouTube';
                    $icon = 'youtube';
                } //if($video_type == 'youtube')

                if ($video_type == 'vimeo') {
                    $video_source_name = 'Vimeo';
                    //$debugS .= '<br>'. $video_source_name . '1st replace: '.$theSRC;
                    //$theSRC = str_replace("//player.vimeo.com/video/", "", $theSRC);
                    //$theSRC = str_replace("http:", "", $theSRC);
                    //$theSRC = str_replace("https:", "", $theSRC);
                    //$debugS .= '<br>'. $video_source_name . '1st replace: '.$theSRC;

                    if (preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $theSRC, $output_array)) {
                        $video_id = $output_array[5];
                        //$video_id = str_replace("vimeo.com/video/", "", $theSRC);
                        //$debugS .= '<br>'. $video_source_name . 'video_id: '.$video_id;
                        $full_video_url = 'http://vimeo.com/video/' . $video_id;
                        ////player.vimeo.com/video/
                        $full_embed_link = '//player.vimeo.com/video/' . $video_id . '?html5=1';

                        $video_thumb_url = getVimeoThumb($video_id);
                    }
                    $icon = 'vimeo-square';
                    $foundVideo = true;
                    //<iframe src="//player.vimeo.com/video/88479074" width="500" height="281" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                } // if($video_type == 'vimeo')
                if ($video_type == 'other') {
                    //$debugS .= '<br>video_type other : '.$theSRC;
                    $full_embed_link = $theSRC;
                    $full_video_url = $theSRC;
                    $video_source_name = 'other';
                    $video_id = $theSRC;
                    $foundVideo = true;
                }
                if ($video_type == 'facebook') {
                    if (strpos($theSRC, "videos") === false) {
                        continue;
                    }
                    //$debugS .= '<br>video_type other : '.$theSRC;
                    $full_embed_link = 'https://www.facebook.com/plugins/video.php?href=' . $theSRC . '&show_text=0&width=450';
                    $full_video_url = $theSRC;
                    $video_source_name = 'facebook';
                    $video_id = $theSRC;
                    $foundVideo = true;
                }
                //
                if ($foundVideo) {
                    $total_videos++;
                    ?>
                    <div class="video_block">
                        <div class="embed_side_wrapper">
                            <div class="embed_side_inside">
                                <div class="embed_side_content_action">
                                    <a href="javascript:;" data-url="<?php echo $full_video_url; ?>"
                                       class="add_embed_content_to_post embed_action_link">
                                        <i class="fa fa-caret-left"></i></a>
                                </div>
                            </div>
                            <div class="embed_content_wrapper video_embed">
                                <iframe width="450" height="253" src="<?php echo $full_embed_link ?>" frameborder="0"
                                        allowfullscreen scrolling="no" noresize marginwidth="0"
                                        marginheight="0"></iframe>
                            </div>
                        </div>
                        <div class="video_links">
                            <?php if ($full_video_url && ($video_type != 'other')): ?><a href="javascript:;"
                                                                                         rel="<?php echo $full_video_url; ?>"
                                                                                         class="add_video"
                                                                                         name="add_link"><i
                                            class="fa fa-link"></i> Add as Link</a><?php endif; ?>
                            <a href="javascript:;" rel="<?php echo $full_embed_link; ?>" class="add_video"
                               name="add_iframe_<?php echo $video_source_name; ?>"><i class="fa fa-video-camera"></i>
                                Add as iFrame</a>
                            <?php if ($full_video_url && ($video_type != 'other')): ?><a
                                href="<?php echo $full_video_url; ?>" target="_blank"><i
                                        class="fa fa-<?php echo $icon ?>"></i> Watch
                                on <?php echo $video_source_name; ?></a><?php endif; ?>
                        </div>
                        <?php if ($video_thumb_url != ''): ?>
                            <div class="video_image">
                                <div class="found_image_w_200 found_image_v<?php echo $total_videos; ?>_w">
                                    <img src="<?php echo $video_thumb_url; ?>"
                                         class="select_image imagedropshadow found_image_v<?php echo $total_videos; ?>"
                                         alt="<?php echo $video_thumb_url; ?>" title="<?php echo $video_thumb_url; ?>"/>
                                    <a href="javascript:;" class="add_image_to_post video_image_add_to_post"
                                       data-id="v<?php echo $total_videos; ?>"><i class="fa fa-plus"></i> Add to
                                        Post</a>
                                    <a href="javascript:;" class="set_image_featured video_set_featured_image"
                                       data-id="v<?php echo $total_videos; ?>"><i class="fa fa-star"></i> Set
                                        Featured</a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($full_video_url): ?><p><input type="text" value="<?php echo $full_video_url ?>">
                            </p><?php endif; ?>

                    </div>
                    <?php
                    $video_thumb_url = '';

                } // if($foundVideo)
            } //if($theSRC != '')
        } //foreach($allPotentionVideos as $element)
    endif; // displayVideos
    ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("div#twitter_follow").removeClass("hidden");

        function setFoundNumbers(inElement, inNumber) {
            $('#' + inElement + ' .total', window.parent.document).html(" (" + inNumber + ")");
        }

        // this function sets all the numbers of the content found
        setFoundNumbers('paragraphs_link',<?php echo $totalParagraphs; ?>);
        setFoundNumbers('videos_link',<?php echo $total_videos; ?>);
        setFoundNumbers('links_link',<?php echo $total_int_links + $total_ext_links; ?>);

        setFoundNumbers('lists_link',<?php echo $totalLists; ?>);
        setFoundNumbers('social_links',<?php echo $total_social; ?>);
        var total_found_images = <?php echo $total_images; ?>+1; // plus one because we have the screenshot
        <?php
        if(isset($options['curation_suite_auto_summary'])):
        if($options['curation_suite_auto_summary']): ?>
        // add the data to the meta fields on the parent post page
        var summaryText = $('.summary_text').text();
        $('#summary_text_textarea', window.parent.document).val(cleanText(summaryText));
        <?php endif; endif; ?>
        $('#reload_thumbnail', window.parent.document).attr('rel', '<?php echo $thumbnail_url; ?>');
        $('#reload_thumbnail').attr('rel', '<?php echo $thumbnail_url; ?>');

        <?php
        foreach($displayed_img_class_arr as $image_class) { ?>

        $(".<?php echo $image_class; ?>").error(function () {
            total_found_images--;
            $(".<?php echo $image_class; ?>_w").addClass('broke');
            $(".<?php echo $image_class; ?>_w").css({"display": "none"});
            setFoundNumbers('images_link', total_found_images);
        });
        <?php
        }
        ?>

        setFoundNumbers('images_link', total_found_images);

    });
</script>
<script id="IntercomSettingsScriptTag">
    window.intercomSettings = {
        email: "<?php echo bloginfo('admin_email'); ?>",
        'site_url': "<?php echo bloginfo('url'); ?>",
        'cs_license': "<?php echo get_option('curation_suite_license_key'); ?>",
        // TODO: The current logged in user's sign-up date as a Unix timestamp.
        created_at: <?php echo time(); ?>,
        current_url: "<?php echo bloginfo('url'); ?>",
        cs_version: "<?php echo CURATION_SUITE_VERSION; ?>",
        app_id: "shlo4zrc"
    };
</script>
<script>(function () {
        var w = window;
        var ic = w.Intercom;
        if (typeof ic === "function") {
            ic('reattach_activator');
            ic('update', intercomSettings);
        } else {
            var d = document;
            var i = function () {
                i.c(arguments)
            };
            i.q = [];
            i.c = function (args) {
                i.q.push(args)
            };
            w.Intercom = i;

            function l() {
                var s = d.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://static.intercomcdn.com/intercom.v1.js';
                var x = d.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            }

            if (w.attachEvent) {
                w.attachEvent('onload', l);
            } else {
                w.addEventListener('load', l, false);
            }
        }
    })()</script>

<?php
do_action('admin_footer');
do_action('admin_print_footer_scripts');
?>
</body>
</html>