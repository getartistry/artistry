<?php
/* MyCurator Local Process
 * This file contains the code to read all sources and call cloud services to render and classify the article.  It will retrieve and post for all topics
 * It is multisite aware, but will only process for the site that it is called for using ?blogid = x
*/

//add action to set cache duration on simplepie
add_filter('wp_feed_cache_transient_lifetime', 'mct_ai_set_simplepie');

//this should be shorter than the interval in which we run cron, but longer than the longest running time of the process
define ('MCT_AI_PIE_CACHE',3600);  
    

function mct_ai_process_site($cron, $names=array()){
    //This function will process all topics for a site/blog
    //It is started from the Run AI Process button on the Topics page
    global $blog_id, $wpdb, $ai_topic_tbl, $ai_postsread_tbl, $ai_sl_pages_tbl, $ai_logs_tbl, $mct_ai_optarray, $proc_id;
    
    set_time_limit(300);  //Boost the time limit for execution
    $proc_id = time(); //Set Process ID
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Start Processing Site: '.$blog_id.'  ', '');
    //Clean postsread - also does logs and training targets
    mct_ai_clean_postsread(true);

    //Loop on all train and active topics in this site
    $sql = "SELECT *
            FROM $ai_topic_tbl
            WHERE topic_status != 'Inactive'";
    $topics = $wpdb->get_results($sql, ARRAY_A);
    if (empty($topics)){
        mct_ai_log("",MCT_AI_LOG_PROCESS, 'Stopping, No Topics Found ', ' ');
        $proc_id = 0;
        return;
    }
    //Use passed in topic list if present
    if (!empty($names)) {
        $newtopics = array();
        foreach ($topics as $topic) {
            if (in_array($topic['topic_name'],$names)) $newtopics[] = $topic;
        }
        if (empty($newtopics)) {
            mct_ai_log("",MCT_AI_LOG_PROCESS, 'Stopping, No Topics Found *', ' ');
            $proc_id = 0;
            return;
        }
        $topics = $newtopics;
    }
    foreach ($topics as $topic){
        if ($cron){
            if (!empty($topic['topic_last_run'])){
                $lastrun = strtotime($topic['topic_last_run']);
                $now = strtotime($wpdb->get_var('Select now()'));  //use mysql time
                if (($now - $lastrun) < 7200) {
                    mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Stopping, Topic recently updated ', ' ');
                    //delete_option('mct_ai_proc_queue');  //no more work
                    $proc_id = 0;
                    exit();
                }
            }
            $thistopic = $topic['topic_id'];
            $sql = "UPDATE $ai_topic_tbl SET topic_last_run = now() WHERE topic_id = '$thistopic'";
            $wpdb->query($sql);
        }
        unset($topic['topic_last_run']);  //Don't pass to cloud
        mct_ai_process_topic($topic);
    } //end for each topic
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'End Site Processing: '.$blog_id.'  ', '');
    $proc_id = 0;
}

function mct_ai_process_topic($topic){ 
    //Process all feeds and items within a topic
    //$topic is an array with each field from the topics file
    global $blog_id, $wpdb, $ai_topic_tbl, $ai_postsread_tbl, $ai_sl_pages_tbl, $ai_logs_tbl, $mct_ai_optarray;
    
    $titles = array();  //Store titles to search for dups
    $this_title = '';
    //Get previous post titles for this topic if title dup checking set
    if (!empty($mct_ai_optarray['ai_dup_title'])) {
        $terms = get_term_by( 'name', $topic['topic_name'], 'topic');
        $topic_slug = empty($terms->slug) ? "" : $terms->slug;
        if (!empty($topic_slug)) {
            $args = array('post_type' => 'target_ai', 
                'posts_per_page' => -1, 
                'post_status' => 'publish',
                'topic' => $topic_slug);
            $allposts = get_posts($args);
            if (!empty($allposts)){
                foreach ($allposts as $p) {
                    $titles[] = trim($p->post_title);
                }
                unset($allposts);
            }
        }
    }
    
    //Check for start/end date
    $topic_opt = mct_ai_get_topic_options($topic);
    if (!empty($topic_opt['opt_topic_start'])) {
        if (time() < strtotime($topic_opt['opt_topic_start'])) {
           mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Skipping Topic, Before Start Date ', $topic_opt['opt_topic_start']); 
           return;
        }
    }
    if (!empty($topic_opt['opt_topic_end'])) {
        if (time() > strtotime($topic_opt['opt_topic_end'])) {
           mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Skipping Topic, After End Date ', $topic_opt['opt_topic_end']); 
           return;
        }
    }
    //For this topic, get the sources
    if (empty($topic['topic_sources'])) {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Skipping Topic, No Sources ', '');
        return;
    }
    $sources = array_map('trim',explode(',',$topic['topic_sources']));
    //set any max source
    $maxsrc = 0;
    $src_cnt = 0;
    $plan = unserialize($mct_ai_optarray['ai_plan']);
    if (!empty($plan['maxsrc'])) $maxsrc = $plan['maxsrc'];
    //Check for topic taxonomy
    $terms = get_term_by( 'name', $topic['topic_name'], 'topic');
    if (empty($terms)) wp_insert_term($topic['topic_name'],'topic');
    //Get the restart transient which is set if last topic:source:feed didn't complete
    $restart = get_transient('mct_ai_last_feed');
    if ($restart) {
        $restart_arr = explode(':',$restart);
        if ($topic['topic_id'] != $restart_arr[0]) {
            //not this topic, so skip it
            return;
        } elseif (!in_array($restart_arr[1],$sources)) {
            //Source not in list, sources have changed for this topic, delete the transient & reset variables & go on
            $restart = false;
            $restart_arr = array();
            delete_transient('mct_ai_last_feed');
        }
    }
    
    $lookback = (empty($mct_ai_optarray['ai_lookback_days'])) ? 7 : $mct_ai_optarray['ai_lookback_days'];
    $farback = $lookback + 60;
    
    //Set up the topic info for the cloud service
    if (!mct_ai_cloudtopic($topic)) return; 
    //log that we are starting
    mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Start Processing Topic ', '');
    //read the sources
    foreach ($sources as $source){
        if ($restart && $restart_arr[1] != $source ) continue;  //Not the restart source, so try another
        
        //For this source, get each feed
        $args = array(
            'category' => $source,
            'orderby' => 'link_id',
            'hide_invisible' => false
        );
        $feeds = get_bookmarks($args);
        if (empty($feeds)){
            $srcterm = get_term($source, 'link_category');
            mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'No feeds for Source Category ', $srcterm->name);
            continue;
        }
        //Process the feeds
        foreach ($feeds as $feed){
            //Check for restart with this feed
            if ($restart) {
                if ($feed->link_id != $restart_arr[2]) continue;
                //Ok, this is the feed, so lets continue from here, 
                //reset the variables and log that we are restarting
                $restart = false;
                $restart_arr = array();
                mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Restarting with Feed ', $feed->link_name);
            }
            if (empty($feed->link_rss)){
                mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'No feed rss link for feed: '.$feed->link_name, $feed->link_rss, $feed->link_name);
                continue;  //no feed in link
            }
            //Set the restart transient for 36 hours as we process this feed
            $src_cnt += 1;
            if ($maxsrc > 0 && $src_cnt > $maxsrc) break;
            set_transient('mct_ai_last_feed',strval($topic['topic_id']).':'.strval($source).':'.strval($feed->link_id),60*60*36);
            //replace &amp; with & - from db sanitization
            $feed->link_rss = preg_replace('[&amp;]','&',$feed->link_rss);
            $feed->link_rss = preg_replace('[&#038;]','&',$feed->link_rss);
            //Process Feed
            $nowdate = new DateTime('now');
            $anynewposts = false;
            $onlyoldposts = true;
            //Build a list of items depending on feed type
            $items = array();
            if (stripos($feed->link_rss,'twitter.com') !== false) {
                $items = mct_ai_twapi($feed, $topic);
            } else {
                $items = mct_ai_rssapi($feed, $topic);
            }
            if (count($items) == 0) {
                mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'No Items to Process ', $feed->link_name);
                continue;
            }
            mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Processing Feed ', $feed->link_name);
            foreach ($items as $item){
                //See if old post
                $postdate = new DateTime($item->date);
                $postdate->modify('+'.$lookback.' day');  //add number of days we keep posts read around
                // $a = $nowdate->diff($postdate,true)->days; for version 5.3 or later only
                if ($postdate < $nowdate){
                    $postdate->modify('+'.$farback.' day');
                    if ($postdate > $nowdate){
                        $onlyoldposts = false;
                    }
                    continue;  //Post older than what we keep track of
                }
                $anynewposts = true; // Something fits our timeframe
                unset($postdate);
                $post_arr = array();  //will hold the post info as we build it
                $post_arr['source'] = $feed->link_name;  //save this source for log
                $page = mct_ai_get_page($item, $topic, $post_arr);  //try and get the page from the postsread file, 
                if (empty($page)){
                    continue;  //no more work, try the next item
                }
                $new_page = false;
                if ($page == "Not Here") $new_page = true;  //Flag to update posts read table, tell cloud to render the page from the link
                //Call cloud services to process
                $postit = mct_ai_cloudclassify($page, $topic, $post_arr);

                //Set up page in postsread if wasn't here - whether or not we are going to post 
                if ($new_page) {
                    $page = $post_arr['page'];
                    //update the style sheet with the local copy
                    $page = str_replace("mct_ai_local_style",plugins_url('MyCurator_page.css',__FILE__), $page);
                    mct_ai_setPRpage($page, $topic, $post_arr);  //Need to update posts read if this is a new page
                }
                //Post the new entry if good response
                if ($postit) {
                    //Check for dups if option set
                    if (!empty($mct_ai_optarray['ai_dup_title'])) {
                        $this_title = trim(mct_ai_get_title($page));
                        if (!empty($this_title) && in_array($this_title,$titles) ) {
                            mct_ai_log($topic['topic_name'],MCT_AI_LOG_ACTIVITY, 'Duplicate Title Found ', $this_title, $post_arr['source']);
                            continue;
                        }
                        if (!empty($this_title)) $titles[] = $this_title;
                    }
                    mct_ai_post_entry($topic, $post_arr, $page);  //post entries
                }
            } //end for each item
            unset($nowdate);
            //Log if only old posts
            if (!$anynewposts && $onlyoldposts) {
                mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Feed has stopped providing articles', ' ', $feed->link_name);
            }
            if ($maxsrc > 0 && $src_cnt > $maxsrc) break;
        }  //end for each feed
        //Delete the restart transient
        delete_transient('mct_ai_last_feed');
        if ($maxsrc > 0 && $src_cnt > $maxsrc) break;
    } //end for each source
    if ($maxsrc > 0 && $src_cnt > $maxsrc) mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Maximum of '.$maxsrc.' Sources Reached', $feed->link_rss, $feed->link_name);
}  
        
function mct_ai_process_request(){
    //Process any outstanding requests from the log
    global $blog_id, $wpdb, $ai_topic_tbl, $ai_postsread_tbl, $ai_sl_pages_tbl, $ai_logs_tbl, $mct_ai_optarray, $proc_id;
    
    $page = "Not Here";
    $tname = '';
    $proc_id = time(); //Get Process ID
    //Get Request entries
    $sql = "SELECT `logs_topic`, `logs_id`, `logs_url`, `logs_source`
            FROM $ai_logs_tbl WHERE `logs_type` = '".MCT_AI_LOG_REQUEST."' ORDER BY `logs_topic`";
    $rqsts = $wpdb->get_results($sql, ARRAY_A);
    if (empty($rqsts)) {
        mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Request Processing - No Requests: ', '');
        return;
    }
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Start Request Processing: ', count($rqsts));
    //Loop on Request entries
    foreach ($rqsts as $rqst){
        $post_arr = array(); 
        if($tname == '' || $tname != $rqst['logs_topic']) {
            $titles = array();  //Store titles to search for dups
            $this_title = '';
            
            //Get new Topic record
            $sql = "SELECT *
            FROM $ai_topic_tbl
            WHERE topic_name = '".$rqst['logs_topic']."'";
            $topic = $wpdb->get_row($sql, ARRAY_A);
            if (empty($topic)){
                //Topic gone, so get rid of this entry and continue
                mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Request Skipped - No Topic: ', $rqst['logs_topic']);
                $delrslt = $wpdb->delete($ai_logs_tbl,array('logs_id' => $rqst['logs_id']));
                if (!$delrslt) { 
                    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Request Error Deleting Log Entry - Quiting', $rqst['logs_url']);
                    $proc_id = 0;
                    exit();//Probably we are a duplicate process so get out
                }
                continue;
            }
            //Check for topic taxonomy
            $terms = get_term_by( 'name', $topic['topic_name'], 'topic');
            if (empty($terms)) wp_insert_term($topic['topic_name'],'topic');
            unset($topic['topic_last_run']);  //Don't pass to cloud 
            //Set up the topic info for the cloud service
            if (!mct_ai_cloudtopic($topic)) {
                //Topic not set, so get rid of this entry and continue
                mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Request Skipped - Failed Cloud Topic: ', $rqst['logs_topic']);
                $delrslt = $wpdb->delete($ai_logs_tbl,array('logs_id' => $rqst['logs_id']));
                if (!$delrslt) { 
                    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Request Error Deleting Log Entry - Quiting', $rqst['logs_url']);
                    $proc_id = 0;
                    exit();//Probably we are a duplicate process so get out
                }
                continue;
            }
            $tname = $topic['topic_name'];
            mct_ai_log($tname,MCT_AI_LOG_PROCESS, 'Request Processing Topic ', '');
            //Get previous post titles for this topic if title dup checking set
            if (!empty($mct_ai_optarray['ai_dup_title'])) {
                $terms = get_term_by( 'name', $topic['topic_name'], 'topic');
                $topic_slug = empty($terms->slug) ? "" : $terms->slug;
                if (!empty($topic_slug)) {
                    $args = array('post_type' => 'target_ai', 
                        'posts_per_page' => -1, 
                        'post_status' => 'publish',
                        'topic' => $topic_slug);
                    $allposts = get_posts($args);
                    if (!empty($allposts)){
                        foreach ($allposts as $p) {
                            $titles[] = trim($p->post_title);
                        }
                        unset($allposts);
                    }
                }
            }
        }
        //Check locally for page
        $item_obj = new stdClass;
        $item_obj->type = 'REQUEST';
        $item_obj->link = $rqst['logs_url'];
        $post_arr['source'] = $rqst['logs_source'];
        $page = mct_ai_get_page($item_obj, $topic, $post_arr);
        if (empty($page)) {
            $delrslt = $wpdb->delete($ai_logs_tbl,array('logs_id' => $rqst['logs_id']));
            if (!$delrslt) { 
                mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Request Error Deleting Log Entry - Quiting', $rqst['logs_url']);
                $proc_id = 0;
                exit();//Probably we are a duplicate process so get out
            }
            continue;  //we already have this
        }
        //Go get it
        $postit = mct_ai_cloudclassify($page, $topic, $post_arr);
        $page = $post_arr['page'];
        //update the style sheet with the local copy
        $page = str_replace("mct_ai_local_style",plugins_url('MyCurator_page.css',__FILE__), $page);
        mct_ai_setPRpage($page, $topic, $post_arr);  //Need to update posts read if this is a new page
        //Post the new entry if good response
        if ($postit) {
            //Check for dups if option set
            if (!empty($mct_ai_optarray['ai_dup_title'])) {
                $this_title = trim(mct_ai_get_title($page));
                if (!empty($this_title) && in_array($this_title,$titles) ) {
                    mct_ai_log($topic['topic_name'],MCT_AI_LOG_ACTIVITY, 'Duplicate Title Found ', $this_title, $post_arr['source']);
                    continue;
                }
                if (!empty($this_title)) $titles[] = $this_title;
            }
            mct_ai_post_entry($topic, $post_arr, $page);  //post entries
        } 
        //Get rid of log entry
        $delrslt = $wpdb->delete($ai_logs_tbl,array('logs_id' => $rqst['logs_id']));
        if (!$delrslt) { 
            mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Request Error Deleting Log Entry - Quiting', $rqst['logs_url']);
            $proc_id = 0;
            exit();//Probably we are a duplicate process so get out
        }
    }
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'End Request Processing: ', '');
    $proc_id = 0;
}

function mct_ai_rssapi($feed, $topic){
    //Process an RSS feed with Simple Pie and return an items object
    
    $items = array();
    $thefeed = fetch_feed($feed->link_rss);
    
    if (is_wp_error($thefeed)){
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, implode(',',$thefeed->get_error_codes()),$feed->link_rss, $feed->link_name);
        //$thefeed->__destruct(); 
        unset ($thefeed);
        return $items;  //feed error
    }
    foreach ($thefeed->get_items() as $item){
        $item_obj = new stdClass;
        $item_obj->type = 'RSS';
        $item_obj->date = $item->get_date();
        $item_obj->link = trim($item->get_permalink());
        $items[] = $item_obj;
    }
    $thefeed->__destruct(); 
    unset($thefeed);
    unset ($item_obj);
    return $items;
}

function mct_ai_twapi($feed, $topic) {
    //Process Twitter search/follow using 1.1 api
    
    global $mct_ai_optarray;
    $items = array();
    if (empty($mct_ai_optarray['ai_tw_conk']) || empty($mct_ai_optarray['ai_tw_cons'])){
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Twitter App Not Set in Options',$feed->link_rss, $feed->link_name);
        return $items;
    }
    //Set up twitter api class
    require_once(plugin_dir_path(__FILE__).'lib/class-mct-tw-api.php');
    
    $credentials = array(
      'consumer_key' => $mct_ai_optarray['ai_tw_conk'],
      'consumer_secret' => $mct_ai_optarray['ai_tw_cons']
    );
    add_filter( 'https_ssl_verify', '__return_false' );
    add_filter( 'https_local_ssl_verify', '__return_false' );
    $twitter_api = new mct_tw_Api( $credentials );
    if ($twitter_api->has_error) {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, $twitter_api->api_errmsg,$feed->link_rss, $feed->link_name);
        unset($twitter_api);
        return $items;  //empty array
    }
    //Check type of Twitter feed
    if (stripos($feed->link_rss,'search') !== false) {
        //Search
        $idx = stripos($feed->link_rss,'q=');
        $qterm = substr($feed->link_rss,$idx+2);
        $query = 'q='.$qterm.'&result_type=recent';
        $args = array('type' => 'search/tweets');
        $reply = $twitter_api->query( $query, $args );
        $reply = $reply->statuses;
    } else {
        //Timeline
        $idx = stripos($feed->link_rss,'screen_name=');
        $qterm = substr($feed->link_rss,$idx+12);
        $query = 'exclude_replies=true&include_rts=true&screen_name='.$qterm;
        $reply = $twitter_api->query( $query );
    }
    //Check errors
    if ($twitter_api->has_error) {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, $twitter_api->api_errmsg,$feed->link_rss, $feed->link_name);
        unset($twitter_api);
        return $items;  //empty array
    }
    //If we have some values, build the $items array
    if (!empty($reply)) {
        foreach ($reply as $item) {
            $item_obj = new stdClass;            
            $item_obj->type = 'TWITTER';
            $item_obj->date = $item->created_at;
            $item_obj->link = '';
            $item_obj->text = $item->text;
            $items[] = $item_obj;
        }
    }
    unset ($item_obj);
    unset ($twitter_api);
    remove_filter( 'https_ssl_verify', '__return_false' );
    remove_filter( 'https_local_ssl_verify', '__return_false' );
    return $items;
}

function mct_ai_get_page($item, $topic, &$post_arr){
    global $wpdb, $blog_id, $ai_postsread_tbl, $ai_sl_pages_tbl, $mct_ai_stored_page_id, $mct_ai_current_link;
    
    //item is a simplepie object
    //Check if we've read the page already and use it if so
    //If page is not here, return "Not Here" to tell cloud services to grab the page too
    //Return '' if nothing to process because of errors or we've processed it previously
    
    //clean up the link and check if it is from an excluded domain from this topic
    $ilink = trim($item->link);
    if (!empty($ilink)) {
        //replace &amp; with & - from simple pie sanitization
        $ilink = preg_replace('[&amp;]','&',$ilink);
        $ilink = preg_replace('[&#038;]','&',$ilink);
        //If google alert feed, get embedded link
        if (stripos($ilink,'www.google.com/url') !== false){
            $cnt = preg_match('{t&url=([^&]*)&ct}',$ilink,$matches);
            if ($cnt) {
                $ilink = trim(rawurldecode($matches[1]));
            }
        }
        //If bing news feed, get embedded link
        if (stripos($ilink,'www.bing.com/news') !== false){
            $cnt = preg_match('{&url=([^&]*)&c}',$ilink,$matches);
            if ($cnt) {
                $ilink = trim(rawurldecode($matches[1]));
            }
        }
        //If google news feed, get embedded link
        if (stripos($ilink,'news.google.com/news') !== false){
            $cnt = preg_match('{&url=(.*)$}',$ilink,$matches);
            if ($cnt) {
                $ilink = trim(rawurldecode($matches[1]));
            }
        }
    }
    //If twitter search feed, get embedded links or return '' if none
    if ($item->type == 'TWITTER'){
        $desc = $item->text;
        $cnt = preg_match('{https://t.co/([^"\s]*)["\s]?}',$desc,$matches);
        if ($cnt) {
            $elink = $ilink = trim('https://t.co/'.$matches[1]);  //get just first link
            $ilink = mct_ai_tw_expandurl($ilink);
            if ($ilink == '') {
                //mct_ai_log($topic['topic_name'],MCT_AI_LOG_ACTIVITY, "Could Not Resolve Twitter Link",$elink, $post_arr['source']);
                return '';  //Not an article link so skip it
            }
            //mct_ai_log($topic['topic_name'],MCT_AI_LOG_ACTIVITY, 'Twitter Article Found',$ilink);  //TESTING ONLY
        } else {
            return '';  //No link found
        }
    }
    //Check if excluded domain for this topic
    $dom_array = explode("\n",$topic['topic_skip_domains']);
    $linkhost = parse_url($ilink, PHP_URL_HOST);  //only check the host/domain as we could get problems with path/query
    foreach ($dom_array as $dom){
        $dom = rtrim($dom);
        if (stripos($linkhost,$dom) !== false) {
            return '';  //excluded domain so skip this item
        }
    }
    $this_topic = trim(strval($blog_id)).':'.trim($topic['topic_id']); // Set up topic key with site and topic
    //check postsread for the item
    $dbstr = (strlen($ilink) > 1000) ? substr($ilink,0,1000) : $ilink; //truncate for index lookup
    $sql_url = esc_sql($dbstr);
    $sql = "SELECT `pr_id`, `pr_page_content`, `pr_topics`
            FROM $ai_postsread_tbl
            WHERE pr_url = '$sql_url'";
    $pr_row = $wpdb->get_row($sql, ARRAY_A);
    if ($pr_row != NULL){
        //check to see if this topic is in list of topics read
        if (stripos($pr_row['pr_topics'],$this_topic) !== false){
            //We've processed this feed, update date and return blank to signal no more work
            $wpdb->update($ai_postsread_tbl,array('pr_date' => current_time('mysql')), array('pr_id' => $pr_row['pr_id']));
            return '';
        } else {
            // Not processed yet, so get the stored page and update with our topic
            $page_id = $pr_row['pr_id'];
            $pr_row['pr_topics'] .= ','.$this_topic;  
            $upd_array = array(
                'pr_topics' => $pr_row['pr_topics'],
                'pr_date' => current_time('mysql')
            );
            $wpdb->update($ai_postsread_tbl,$upd_array, array('pr_id' => $page_id));  //update postsread with topic/date
            //get stored page
            $page = $pr_row['pr_page_content'];
        }
    } else {
        //Not read yet, so signal we need it from cloud services
        $page = 'Not Here';
    }
    $post_arr['current_link'] = $ilink;
    
    return $page;
}

function mct_ai_cloudtopic($topic){
    //This function calls the cloud service and tells it the topic we will be processing, 
    //sending the relevant data to support processing
    
    //Have cloud store all search 2 words found by fooling it that we requested them
    if (!$topic['topic_tag_search2']) $topic['topic_tag_search2'] = 1;
    
    $response = mct_ai_callcloud('Topic', $topic, $topic);
    
    //response is json decoded
    if ($response == NULL) return false; //error already logged
    if ($response->LOG == 'OK') return true;
    $log = get_object_vars($response->LOG);
    //Log the error and return false
    mct_ai_log($log['logs_topic'], $log['logs_type'], $log['logs_msg'], $log['logs_url']);
    return false;
}

function mct_ai_cloudclassify($page,$topic,&$post_arr){
    //This function calls the cloud service and has it process a page for this topic
    //If page is "Not Here", it will return the new page
    global $mct_ai_optarray;
    
    $post_arr['page'] = $page;
    
    $response = mct_ai_callcloud('Classify', $topic, $post_arr);
    if ($response == NULL) return false; //error already logged
    if (!empty($response->postarr)) $post_arr = get_object_vars($response->postarr);  //may have page even if error
    if (!empty($response->LOG)) {
        $log = get_object_vars($response->LOG);
        //Log the error or request and return false
        mct_ai_log($log['logs_topic'], $log['logs_type'], $log['logs_msg'], $log['logs_url'], $post_arr['source']);
        if (stripos($log['logs_msg'],'Page Text') !== false) $post_arr['pg_err'] = true;
        return false;    
    }

    return true;
}

function mct_ai_callcloud($type,$topic,$postvals){
    //This function calls the cloud service with the token
    global $mct_ai_optarray;
    
    $ref = home_url();
    if (is_multisite()) $ref = network_home_url();
    
    $cloud_data = array(
        'args' => $postvals,
        'token' => $mct_ai_optarray['ai_cloud_token'],
        'type' => $type,
        'utf8' => $mct_ai_optarray['ai_utf8'], //which 'word' processing to use
        'ver' => MCT_AI_VERSION,
        'gzip' => 1, //enable compression
        'rqst' => $mct_ai_optarray['ai_page_rqst'], //Use request mode?
        'topic_id' => strval($topic['topic_id'])
        );
    if (isset($postvals['getit'])) $cloud_data['rqst'] = 0; //Don't use request mode on Get It
    $cloud_json = json_encode($cloud_data);
    //compression/header
    if (strlen($cloud_json) > 1000) {
        $cloud_json = gzcompress($cloud_json);
        $hdr = array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($cloud_json),
        'Content-Encoding: gzip');
    } else {
        $hdr = array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($cloud_json));
    }
    $useragent = $type;
    if (isset($postvals['getit'])) $useragent .= " GetIt";
    $ch = curl_init();
    // SET URL FOR THE POST FORM LOGIN
    curl_setopt($ch, CURLOPT_URL, 'http://tgtinfo.net'); //'http://tgtinfo.net' or http://localhost/cloudservice/
    // ENABLE HTTP POST
    curl_setopt ($ch, CURLOPT_POST, 1);
    // SET POST FIELD to the content
    curl_setopt($ch, CURLOPT_REFERER, $ref);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $cloud_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $hdr);                                                                       
    //Force curl to return results and not display
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    // EXECUTE REQUEST
    $cloud_response = curl_exec ($ch);
    if (curl_error($ch) != '') {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Curl Error: '.curl_error($ch),$type);
        return false;
    }
    if ($cloud_response === false) {
        //Curl exec call failed
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Curl Exec Failed',$type);
        return false;
    }
    $curlinfo = curl_getinfo($ch);
    curl_close($ch);
    if ($curlinfo == false) {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Call to Cloud Services Failed',$type);
        return false;
    }
    if ($curlinfo['http_code'] != 200) {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'HTTP error: '.strval($curlinfo['http_code']),$type);
        return false;
    } 
    //Cloud Response holds the returned Json data, decode and return
    if (stripos($curlinfo['content_type'],'gzip')!== false) {
        $cloud_response = gzuncompress($cloud_response);
        if ($cloud_response == false) {
            mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Uncompress Failed',$type);
            return false;
        }
    }
    $json_response = json_decode($cloud_response);
    
    if ($json_response == NULL) {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Invalid JSON Object Returned: '.$cloud_response,$type);
        return false;
    }
    return $json_response;
   
}

function mct_ai_setPRpage($page, $topic, $post_arr){
    //Set the new page into the postsread table
    global $wpdb, $blog_id, $ai_postsread_tbl;
    
    if (empty($page) && empty($post_arr['pg_err'])) return; //Only log page if an error
    
    $ilink = $post_arr['current_link'];
    $this_topic = trim(strval($blog_id)).':'.trim($topic['topic_id']); // Set up topic key with site and topic
    //insert into postsread
    $val_array = array(
        'pr_topics' => $this_topic,
        'pr_date' => current_time('mysql'),
        'pr_page_content' => $page,
        'pr_url' => trim($ilink)
    );
    $wpdb->insert($ai_postsread_tbl, $val_array);
    $page_id = $wpdb->insert_id;
    if (!$page_id){
        //log error and return
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Could not insert into Postsread DB - '.$wpdb->last_error,$ilink, $post_arr['source']);
        return '';
    }
}


function mct_ai_post_entry($topic, $post_arr, $page){
    global $wpdb, $ai_sl_pages_tbl, $blog_id, $user_ID, $mct_ai_optarray;
    //if filter type just post to blog (cat/tag) or Targets if training
    //else if relevance type and active: post good to blog(cat/tag), unknown to training
    //  else post training for all 
    //if relevance also log ai probs
    //return post id if successful, else false
    
    //Get Topic Options
    $topic_opt = mct_ai_get_topic_options($topic);
    // Get an image if we can - 1st match of appropriate size
    $image = '';
    $imgtitle = '';
    $thumb_id = 0;
    if (!empty($mct_ai_optarray['ai_save_thumb'])  || !empty($mct_ai_optarray['ai_post_img'])) {
        $regexp1 = '{<img [^>]*src\s*=\s*("|\')([^"\']*)("|\')[^>]*>}i'; 
        $pos = preg_match_all($regexp1,$page,$matchall, PREG_SET_ORDER);
        if ($pos) {
            foreach ($matchall as $matches) {
                $size = @getimagesize($matches[2]);
                if ($size && $size[0] >= 25 && $size[1] >= 25 ){  //excludes small pngs, icons, pixels
                    $image = $matches[2];
                    $pos = preg_match('{<img [^>]*alt\s*=\s*("|\')([^"\']*)("|\')[^>]*>}i',$matches[0],$match);
                    if ($pos) $imgtitle = $match[2];
                    break;
                }
            }
        }
    }
    if (!empty($topic_opt['opt_image_filter']) && empty($image) && empty($post_arr['getit'])) {
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ACTIVITY, 'No Image Found',$post_arr['current_link'], $post_arr['source']);
        return false;  //Skip if no image and image filter set in topic
    }
    //Good item, so save a copy of page (postsread version will go away eventually) and setredirect value
    $wpdb->insert($ai_sl_pages_tbl, array ('sl_page_content' => $page));
    $page_id = $wpdb->insert_id;
    if (!$page_id) {
        //log error - couldn't save the page
        mct_ai_log($topic['topic_name'],MCT_AI_LOG_ERROR, 'Could not save page in DB - '.$wpdb->last_error,$post_arr['current_link'], $post_arr['source']);
    } 
    //Set the redirect link
    if (is_multisite()){
        if ($blog_id == 1){
            $link_redir = network_site_url().'blog/'.MCT_AI_REDIR.'/'.trim(strval($page_id));
        } else {
            $link_redir = site_url().'/'.MCT_AI_REDIR.'/'.trim(strval($page_id));
        }
    } else {
        $link_redir = site_url().'/'.MCT_AI_REDIR.'/'.trim(strval($page_id));
    }
    //Set up the content
    mct_ai_getpostcontent($page, $post_arr,$topic['topic_type']);
    if ($topic['topic_type'] == "Video" && !empty($mct_ai_optarray['ai_video_nolink'])  && stripos($post_arr['article'], '<iframe title="Video Player"') !== false) {
        //video embedded and nolink set, so just include content, no link
        $post_content = $post_arr['article'];
    } else {
        if (!$mct_ai_optarray['ai_slpage_link']){
            $post_arr['orig_link'] = mct_ai_formatlink($post_arr);
            if (empty($mct_ai_optarray['ai_attr_top'])) {
                $post_content = $post_arr['article'].'<p id="mct-ai-attriblink">'.$post_arr['orig_link'].'</p>';
            } else {
                $post_content = '<p id="mct-ai-attriblink">'.$post_arr['orig_link'].'</p>'.$post_arr['article'];
            }
            if (empty($mct_ai_optarray['ai_orig_text'])) {
                $post_content = str_replace("Click here to view original web page at ",$mct_ai_optarray['ai_orig_text'],$post_content); //remove space too
            } else {
                $post_content = str_replace("Click here to view original web page at",$mct_ai_optarray['ai_orig_text'],$post_content);  // leave space
            }
        } else {
            $post_content = $post_arr['article'].'<p id="mct-ai-attriblink"><a href="'.$link_redir.'" >Click here to view full article</a></p>';
            $post_content = str_replace("Click here to view full article",$mct_ai_optarray['ai_save_text'],$post_content);
        }
    }
    $post_content = apply_filters('mct_ai_postcontent',$post_content, $post_arr);
    
    //Set up the values, not set are defaults
    //Get user for GetIt, if not we are in cron process so set  user for the site
    $pa = 0;
    if (!empty($post_arr['getit'])) {
        $cu = wp_get_current_user();
        $pa = $user_ID;
    } elseif (!empty($topic_opt['opt_post_user']) && $topic_opt['opt_post_user'] != "Not Set" ) {
        $useris = get_user_by('login',$topic_opt['opt_post_user']);
        if (!empty($useris) && !empty($useris->allcaps['publish_posts'])) {
            $pa = $useris->ID;
        } else {
            $pa = 0;
        }
    } elseif (!empty($mct_ai_optarray['ai_post_user'])) {
        $useris = get_user_by('login',$mct_ai_optarray['ai_post_user']);
        if (!empty($useris) && !empty($useris->allcaps['publish_posts'])) {
            $pa = $useris->ID;
        } else {
            $pa = 0;
        }
    } 
    if (!$pa) {     //didn't have a user or user doesn't have publish_posts cap, so use an admin   
        $useradms = get_users(array('role' => 'administrator'));
        if (empty($useradms)){
            $pa = 1;
        } else {
            $first = $useradms[0];
            $pa = $first->ID;
        }
    }
    wp_set_current_user($pa);
    $details = array(
      'post_content'  => $post_content,
      'post_author' => $pa,
      'post_title'  =>  $post_arr['title'],
      'post_name' => sanitize_title($post_arr['title']),
      'post_status' => 'publish'
    );
    //Save the excerpt field?
    //ai_nosave_excerpt
    if ($mct_ai_optarray['ai_nosave_excerpt']) {
        //don't save
    } else {
        $details['post_excerpt'] = $post_content;
    }
    //Use topic & aiclass in all cases
    $terms = get_term_by( 'name', $topic['topic_name'], 'topic');
    $topic_slug = empty($terms->slug) ? "" : $terms->slug;
    $class_slug = '';
    if ($topic['topic_type'] != 'Relevance'){
        $details['tax_input'] = array (  //add topic name 
        'topic' => $topic_slug
        );
    } else {
        $terms = get_term_by( 'name', $post_arr['classed'], 'ai_class');
        $details['tax_input'] = array (  //add topic name 
        'topic' => $topic_slug,
        'ai_class' => $terms->slug //add ai class
        );
        $class_slug = $terms->slug;
    }
    //check if active using relevance engine, but post is bad or unknown
    $rel_not_good = false;  
    if ($topic['topic_status'] == 'Active' && 
            $topic['topic_type'] == 'Relevance' && 
            $post_arr['classed'] != 'good'){
        $rel_not_good = true;
    }
    //Training or not relevant but active
    $post_msg = '';
    if ($topic['topic_status'] == 'Training'  || $rel_not_good){

        $details['post_type'] = 'target_ai'; //post as a target
        $post_id = wp_insert_post($details);
        if (!empty($post_arr['tags'])){
            update_post_meta($post_id,'mct_ai_tag_search2',$post_arr['tags']); //put keywords found in meta
        }
        //Sometimes Terms don't update, check if they did and force if not
        $terms = get_the_terms( $post_id, 'topic');
        if (empty($terms)) {
            wp_set_object_terms($post_id,$topic_slug,'topic',false);
        }
        $terms = get_the_terms( $post_id, 'ai_class');
        if (empty($terms) && !empty($class_slug)) {
             wp_set_object_terms($post_id,$class_slug,'ai_class',false);
        }
        //Message for Log
        $post_msg = (!empty($post_arr['classed'])) ? $post_arr['classed'] : '';  
    }
    //Active and relevant
    if ($topic['topic_status'] == 'Active' && !$rel_not_good){
        if (!empty($topic_opt['opt_post_ctype']) && $topic_opt['opt_post_ctype'] != 'not-selected') {
            $details['post_type'] = $topic_opt['opt_post_ctype'];
            if (!empty($topic_opt['opt_post_ctax']) && !empty($topic_opt['opt_post_ctaxval'])) {
                $details['tax_input'] = array_merge($details['tax_input'],array($topic_opt['opt_post_ctax'] => $topic_opt['opt_post_ctaxval']));
            }
        } else {
            $details['post_type'] = 'post';
            if (!$topic['topic_tag_search2']){
                $tagterm = get_term($topic['topic_tag'],'post_tag');
                if (!empty($tagterm) && !is_wp_error($tagterm)) $details['tags_input'] = array($tagterm->name);
            } else {
                if (!empty($post_arr['tags'])) $details['tags_input'] = $post_arr['tags'];
            }
            $details['post_category'] = array($topic['topic_cat']);
        }
        $details['comment_status'] = get_option('default_comment_status');
        $details['ping_status'] = get_option('default_ping_status');
        $details['post_status'] = 'draft';  //Set to draft then publish in case they have Publicize
        $post_id = wp_insert_post($details);
        wp_publish_post($post_id);
        $post_msg = "Live";
    }
    do_action('mct_ai_trainingpost',$post_id, $post_arr);
    //update post meta
    update_post_meta($post_id,'mct_sl_origurl',array($post_arr['current_link']));
    update_post_meta($post_id,'mct_sl_newurl',array($link_redir));
    //update relevance classification 
    if ($topic['topic_type'] == 'Relevance' && empty($post_arr['getit'])){
        update_post_meta($post_id, 'mct_ai_relevance',array(
            'classed' => $post_arr['classed'],
            'good' => sprintf('%.6f',$post_arr['good']),
            'bad' => sprintf('%.6f', $post_arr['bad']),
            'dbsize' => sprintf('%.0f',$post_arr['dbsize'])
        ));
    }
    //update the image if found as the featured image or inserted image for the post 
    if (!empty($mct_ai_optarray['ai_image_title'])) $imgtitle = $post_arr['title'];
    if ($topic['topic_type'] != 'Video' && !empty($image)){
        $thumb_id = mct_ai_postthumb($image,$post_id, $imgtitle);
    }
    if ($topic['topic_type'] == 'Video') {
        if (!empty($image) && stripos($post_content,'<iframe') === false) $thumb_id = mct_ai_postthumb($image,$post_id, $imgtitle); //only if no emgedded video
        if (empty($image) && !empty($mct_ai_optarray['ai_video_thumb']) && !empty($post_arr['yt_thumb'])) {
            $thumb_id = mct_ai_postthumb($post_arr['yt_thumb'],$post_id, $imgtitle);
            update_post_meta( $post_id, '_thumbnail_id', $thumb_id ); //Force as thumbnail 
            $thumb_id = 0; //Set to 0 so we don't post again below
        }
    }
    if ($thumb_id) {  
        if ($mct_ai_optarray['ai_save_thumb']) update_post_meta( $post_id, '_thumbnail_id', $thumb_id );
        //Add image to start of post if set
        if (isset($mct_ai_optarray['ai_post_img']) && $mct_ai_optarray['ai_post_img'] ){
            $details = array();
            if (!empty($mct_ai_optarray['ai_title_link'])) {
                $url = $post_arr['current_link'];
            } else {
                $url = get_permalink( $post_id );
            }
            $align = $mct_ai_optarray['ai_img_align'];
            $size = $mct_ai_optarray['ai_img_size'];
            $src = wp_get_attachment_image_src($thumb_id,$size);
            if (!$src) {
                $src = wp_get_attachment_image_src($thumb_id,'thumbnail');  //try thumbnail
                if (!$src) wp_get_attachment_image_src($thumb_id,'full');  //try full size
            }
            if ($src) {
                $imgstr = '<a href="'.$url.'"><img class="size-'.$size.' align'.$align.'" alt="'.$imgtitle.'" src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></a>';
                $details['post_content'] = (empty($mct_ai_optarray['ai_image_bottom'])) ? $imgstr.$post_content : $post_content.$imgstr;
                $details['ID'] = $post_id;
                wp_update_post($details);
            }
            
        }
    }
    mct_ai_log($topic['topic_name'],MCT_AI_LOG_ACTIVITY, 'New '.$post_msg.' post',$post_arr['current_link'], $post_arr['source']);
    //update the saved page with the post id or delete sl page depending on option setting
    if (!empty($mct_ai_optarray['ai_del_slpages']) && $post_msg == "Live") {
        $sql = "DELETE FROM $ai_sl_pages_tbl WHERE sl_page_id = $page_id";
        $del = $wpdb->query($sql);
    } else {
        $wpdb->update($ai_sl_pages_tbl, array('sl_post_id' => $post_id), array ('sl_page_id' => $page_id));
    }
    return $post_id;
}

function mct_ai_getpostcontent($page, &$post_arr, $type){
    //Grab the post content out of the rendered page
    global $mct_ai_optarray;
    $excerpt_length = $mct_ai_optarray['ai_excerpt'];
    $article = '';
    //$page has the content, with html, using the format of rendered page, separate sections
    
    $cnt = preg_match('{<span class="mct-ai-article-content">(.*)}si',$page,$matches);  //don't stop at end of line
    if ($cnt) $article = $matches[1];
    //Get title
    $post_arr['title'] = mct_ai_get_title($page);  //save title 
    // Get original URL
    $pos = preg_match('{<div id="source-url">([^>]*)>([^<]*)<}',$page,$matches);
    if (isset($mct_ai_optarray['ai_new_tab']) && $mct_ai_optarray['ai_new_tab'] ) {
        $post_arr['orig_link'] = $matches[1].' target="_blank">'.$matches[2].'</a>';
    } else {
        $post_arr['orig_link'] = $matches[1].'>'.$matches[2].'</a>';
    }
    //Now get article content
    if ($type == 'Video' && !empty($mct_ai_optarray['ai_embed_video'])) {
        //Embed the iframe into article
        $pos = preg_match('{<iframe title="Video Player"[^>]*>}',$page,$matches);
        if (!$pos) {
            //check for youtube source and build iframe in the page
            if (stripos($post_arr['current_link'],'www.youtube.com/watch')!== false){
                $vidid = getYouTubeVideoId($post_arr['current_link']);
                $vidlink = 'http://www.youtube.com/embed/'.$vidid;
                $vid = '<iframe title="Video Player" class="youtube-player" type="text/html" ';
                $vid .= 'width="250" height="250" src="'.$vidlink.'"';
                $vid .= 'frameborder="0" allowFullScreen></iframe>';
                $pos = stripos($page,'Click here to view original web page');
                $newpage = substr($page,0,$pos-1);
                $endpage = substr($page,$pos);
                $page = $newpage.$vid.$endpage;
                $pos = preg_match('{<iframe title="Video Player"[^>]*>}',$page,$matches);
            }
        } 
        if ($pos) {
            $post_arr['article'] = $matches[0]."</iframe><br />"; //embed the iframe
            //set height/width
            $post_arr['article'] = str_replace('width="250"', 'width="'.$mct_ai_optarray['ai_video_width'].'"',$post_arr['article']);
            $post_arr['article'] = str_replace('height="250"', 'height="'.$mct_ai_optarray['ai_video_height'].'"',$post_arr['article']);
            //set alignment
            if (!empty($mct_ai_optarray['ai_video_align']) && $mct_ai_optarray['ai_video_align'] != 'none') {
                $post_arr['article'] = str_replace('class="youtube-player"','class="youtube-player align'.$mct_ai_optarray['ai_video_align'].'"',$post_arr['article']);
            }
            $pos = preg_match('{src="([^"]*)"}',$post_arr['article'],$matches);
            if ($pos){
                //get rid of autoplay tags if there
                $pos = stripos($matches[1],'autoplay'); //match on lowercase
                if ($pos){
                    $qstr = substr($matches[1],$pos,8);//Not sure what is capitalized, so get original
                    $newstr = remove_query_arg($qstr,$matches[1]);
                    $post_arr['article'] = preg_replace('{(src=")([^"]*)(")}','$1'.$newstr.'$3',$post_arr['article']);
                }
                //add rel=0 tag if youtube video
                $pos = preg_match('{src="([^"]*)"}',$post_arr['article'],$matches);  //get source again
                if (stripos($matches[1],'youtube') !== false && stripos($matches[1],'rel=0') === false) {
                    $newstr = add_query_arg('rel','0',$matches[1]);
                    $post_arr['article'] = preg_replace('{(src=")([^"]*)(")}','$1'.$newstr.'$3',$post_arr['article']);
                }
            }
            //Try to get youtube description and thumbnail if options set
            if (!empty($mct_ai_optarray['ai_video_desc']) || !empty($mct_ai_optarray['ai_video_thumb'])) {
                if (preg_match('{youtube.com/(v|embed)/([^"|\?]*)("|\?)}i', $post_arr['article'], $match)) {
                    $video_id = $match[2];
                    if(!empty($mct_ai_optarray['ai_video_thumb'])) $post_arr['yt_thumb'] = "http://img.youtube.com/vi/$video_id/0.jpg";
                    if (!empty($mct_ai_optarray['ai_video_desc']) && $excerpt_length){
                        $res =  wp_remote_get("http://gdata.youtube.com/feeds/api/videos/".$video_id."?v=2");
                        if ( is_wp_error( $res ) ) return;
                        $pos = preg_match('{<media:description([^>]*)>([^<]*)</media:description>}',$res['body'], $match);
                        if (!$pos) return;
                        //shorten to excerpt length
                        $excerpt = $match[2];
                        $excerpt = preg_replace('/\s+/', ' ', $excerpt);  //get rid of extra spaces
                        //Get the word count specified
                        $words = explode(' ', $excerpt, $excerpt_length + 1);
                        if ( count($words) > $excerpt_length ) {
                                array_pop($words);
                                array_push($words, '[...]');
                                $excerpt = implode(' ', $words);
                        }
                        //try to activate links in the text
                        $desc = preg_replace('{https?://([^\s]*)}','<a href="$0" target="_blank">$0</a>',$excerpt);
                        $post_arr['article'] .= '<p class="mct_ai_ytdesc">'.$desc.'</p>';
                    }
                } 
            }
            return;
        } else {
            //update_option('video_page',$page);
            //update_option('video_link',$post_arr['current_link']);
        }
    }
    //Check for line breaks if option set
    if (!empty($mct_ai_optarray['ai_line_brk'])) {
        $article = preg_replace('{</p>\n?<p[^>]*>}','&&&&',$article);
        $article = preg_replace('{</li>}','&&&&',$article);
        $article = preg_replace('{<ol|ul>}','&&&&',$article);
        $article = preg_replace('{</h[1-9]>}','&&&&',$article);
        $article = preg_replace('{<br\s?/?>}','&&',$article);
    }
    $article = preg_replace('@<style[^>]*>[^<]*</style>@i','',$article);  //remove style tags
    $article = preg_replace('{<([^>]*)>}',' ',$article);  //remove tags but leave spaces
    //$article = preg_replace('{&[a-z]*;}',"'",$article);  //remove any encoding
    //Save article snippet
    $excerpt = preg_replace('/\s+/', ' ', $article);  //get rid of extra spaces
    //Get Excerpt words
    if (!$excerpt_length) {
        $post_arr['article'] = '';
        return;  //no excerpt if set to 0
    }
    
    //Get the word count specified
    $words = explode(' ', $excerpt, $excerpt_length + 1);
    if ( count($words) > $excerpt_length ) {
            array_pop($words);
            array_push($words, '[...]');
            $excerpt = implode(' ', $words);
    }
    //Replace any line break if set previously
    if (!empty($mct_ai_optarray['ai_line_brk'])) {
        $excerpt = preg_replace('{^\s*&&+}','',$excerpt); //no leading breaks from initial image as <p>
        $excerpt = preg_replace('{&&\s?&&\s?(&&\s?)+}','&&&&',$excerpt);  //Only one full break, not multiple
        $limit = 4; //limit line breaks based on excerpt length
        if ($excerpt_length > 80) $limit = 2*absint($excerpt_length/40);
        $excerpt = preg_replace('{&&}','<br>',$excerpt, $limit); 
        $excerpt = preg_replace('{&&}','',$excerpt); 
    }
    $post_arr['article'] = mct_ai_setexcerpt($excerpt);
}

function mct_ai_clean_postsread($pread){
    //Cleans up the log table, old target post types and the postsread table if posts are old (and $pread is true)
    global $ai_postsread_tbl, $mct_ai_optarray, $wpdb, $ai_logs_tbl;
    
    if ($pread){
        $feed_back = (empty($mct_ai_optarray['ai_lookback_days'])) ? 7 : $mct_ai_optarray['ai_lookback_days'];
        //If not updated since log_days, get rid of entries
        $sql = "DELETE FROM $ai_postsread_tbl
                WHERE pr_date < ADDDATE(NOW(),-".$feed_back.")";
        $pr_row = $wpdb->query($sql);
        if (!empty($pr_row)){
            mct_ai_log('Posts Read',MCT_AI_LOG_PROCESS, 'Deleted '.$pr_row, '');
        }
    }
    
    $back = $mct_ai_optarray['ai_log_days'];
    //clean ai_log of errors/activities
    $sql = "DELETE FROM $ai_logs_tbl
            WHERE logs_date < ADDDATE(NOW(),-".$back.")";
    $pr_row = $wpdb->query($sql);
    if (!empty($pr_row)){
        mct_ai_log('Log',MCT_AI_LOG_PROCESS, 'Deleted '.$pr_row, '');
    }
    
    $back = $mct_ai_optarray['ai_train_days'];
    //clean out old training targets, use wp_delete_post which will trigger our hook to delete the saved page
    $postfile = $wpdb->posts;
    $sql = "SELECT ID FROM $postfile WHERE post_type = 'target_ai' AND post_date < ADDDATE(NOW(),-".$back.")";
    $cols = $wpdb->get_col($sql);
    if (!empty($cols)){
        foreach ($cols as $postid){
            wp_delete_post($postid);
        }
        mct_ai_log('Targets',MCT_AI_LOG_PROCESS, 'Deleted '.count($cols), '');
    }
}


function mct_ai_set_simplepie($seconds){
    //Set the cache duration
    return MCT_AI_PIE_CACHE;
}

function getYouTubeVideoId($url)
{
    $video_id = false;
    $url = parse_url($url);
    if (strcasecmp($url['host'], 'youtu.be') === 0)
    {
        #### (dontcare)://youtu.be/<video id>
        $video_id = substr($url['path'], 1);
    }
    elseif (strcasecmp($url['host'], 'www.youtube.com') === 0)
    {
        if (isset($url['query']))
        {
            parse_str($url['query'], $url['query']);
            if (isset($url['query']['v']))
            {
                #### (dontcare)://www.youtube.com/(dontcare)?v=<video id>
                $video_id = $url['query']['v'];
            }
        }
        if ($video_id == false)
        {
            $url['path'] = explode('/', substr($url['path'], 1));
            if (in_array($url['path'][0], array('e', 'embed', 'v')))
            {
                #### (dontcare)://www.youtube.com/(whitelist)/<video id>
                $video_id = $url['path'][1];
            }
        }
    }
    return $video_id;
}
?>