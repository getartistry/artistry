<?php
/* MyCurator_fcns.php
 * Perform various support functions for MyCurator
 *
*/


function mct_ai_log($topic, $type, $msg, $url, $source = null){
    //This function creates a log entry from the passed in values
    global $wpdb, $ai_logs_tbl, $proc_id, $proc_cnt;
    
    $proc_cnt += 1; //Add a new count
    //Version 3 until cloud process is updated
    if($type == 'ACTIVITY') $type = MCT_AI_LOG_ACTIVITY;
    if (stripos($msg,'Page Text') !== false) $type = MCT_AI_LOG_ACTIVITY;
    //
    $ins_array = array(
                'logs_date' => current_time('mysql'),
                'logs_topic' => $topic,
                'logs_type' => $type,
                'logs_url' => $url,
                'logs_msg' => $msg,
                'logs_source' => $source,
                'logs_proc_id' => $proc_id,
                'logs_proc_cnt' => $proc_cnt 
            );
    $wpdb->insert($ai_logs_tbl, $ins_array);
    
}

function mct_ai_postlink($args, $update = false){
    //Post a link to db
    //Requires specific $arg fields which have been validated and cleansed already
    global $current_user;
    
    $link_category = array();
    if (!empty($args['link_category'])) $link_category = $args['link_category'];
    if (!empty($args['newlinkcat'])){
        $newlinkcat = $args['newlinkcat'];
        $theterm = get_term_by('name',$newlinkcat, 'link_category', ARRAY_A);  //already exists?
        if (!$theterm) $theterm = wp_insert_term($newlinkcat,'link_category');
        if (is_wp_error($theterm)){
            return $theterm->get_error_message();
        } else {
            $link_category[] = $theterm['term_id'];
        }
    }
    //$current_user = wp_get_current_user();  //Owner of this link
    //Create the new Link Record
    $linkdata = array(
 	"link_url"		=> $args['save-url'], // Domain of 
	"link_name"		=> $args['feed_name'], // varchar, the title of the link
	"link_visible"		=> 'Y', // varchar, Y means visible, anything else means not
	"link_owner"		=> $current_user->ID, // integer, a user ID
	"link_rss"		=> $args['rss-url'], // varchar, a URL of an associated RSS feed
	"link_category"		=> $link_category // array, the term ID of the link category. 
    );
    if (!$update) $linkval = wp_insert_link( $linkdata, true );
    else {
        $linkdata['link_id'] = $args['link-id'];
        $linkval = wp_update_link($linkdata);
    }
    if (is_wp_error($linkval)){
        return $linkval->get_error_message();
    }
    return '';
}

function mct_ai_get_title($page) {
    //Get the title from the diffbot formatted page
    $title = '';
    
    if (empty($page)) return;
    
    $cnt = preg_match('{<title>([^<]*)</title>}i',$page,$matches);
    if ($cnt) {
        $title = $matches[1];
        //Get rid of tags in title
        $title = preg_replace('{<([^>]*)>}',' ',$title);  //remove tags but leave spaces
    }
    return $title;
}

function checkmydate($mydate) { 
    
    if (strpos($mydate,'/') === false) return false;
    list($mm,$dd,$yy)=explode("/",$mydate); 
    if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) 
    { 
        return checkdate($mm,$dd,$yy); 
    } 
    return false;            
} 

function mct_ai_getexcerpt($content){
    //Retrieves the excerpt from the content, typically post content
    //uses strpos so that the excerpt can contain html
    $pos = stripos($content,'<blockquote id="mct_ai_excerpt">'); 
    if ($pos !== false) {
         $end = stripos($content,'</blockquote>',$pos+31);
         if ($end !== false) return substr($content,$pos+32,$end-($pos+32));
         return '';
    } 
    $pos = stripos($content,'<p id="mct_ai_excerpt">'); 
    if ($pos !== false) {
         $end = stripos($content,'</p>',$pos+23);
         if ($end !== false) return substr($content,$pos+23,$end-($pos+23));
         return '';
    } 
    return '';
}

function mct_ai_setexcerpt($excerpt) {
    //Set the excerpt in the right tags based on options
    global $mct_ai_optarray;
    if (empty($mct_ai_optarray['ai_no_quotes'])) {
        if (!empty($mct_ai_optarray['ai_line_brk'])) {
            return '<blockquote id="mct_ai_excerpt"><p>'.$excerpt.'</p></blockquote>';
        } else {
            return '<blockquote id="mct_ai_excerpt">'.$excerpt.'</blockquote>';
        }
    } else {
        return '<p id="mct_ai_excerpt">'.$excerpt.'</p>';
    }
}
function mct_ai_resetexcerpt($content,$newtext){
    //Reset  the excerpt from the newtext, typically post content
    //uses strpos so that the excerpt can contain html
    $pos = stripos($content,'<blockquote id="mct_ai_excerpt">'); 
    if ($pos !== false) {
         $end = stripos($content,'</blockquote>',$pos+31);
         if ($end !== false) {
             $p1 = substr($content,0,$pos);
             $p2 = substr($content,$end+13);
             return $p1.$newtext.$p2;
         }    
         return '';
    } 
    $pos = stripos($content,'<p id="mct_ai_excerpt">'); 
    if ($pos !== false) {
         $end = stripos($content,'</p>',$pos+23);
         if ($end !== false) {
            $p1 = substr($content,0,$pos);
            $p2 = substr($content,$end+4);
            return $p1.$newtext.$p2; 
         }
         return '';
    } 
    return '';
}

function mct_ai_get_trainpage(){
    //returns a post object of type page for the training page on the site
    $pages = get_pages(array('post_status' => 'publish,private'));
    foreach ($pages as $page) {
        if (stripos($page->post_content,"MyCurator_training_page") !== false) {
            return $page;
        }
    }
    return '';
}
function mct_ai_tw_expandurl($url){
    //Expand the passed in twitter url and return it - uses just the header, no body  
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_HEADER,true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_NOBODY,true);    
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
    $headers = curl_exec($ch);
    curl_close($ch);
    if (preg_match_all('/Location:\s(.+?\s)/i', $headers, $matches)) {
        $pos = count($matches[1]) - 1;
        $url = trim($matches[1][$pos]);
        //Strip off the query and fragments
        $parsed_url = parse_url($url);
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
        //mct_ai_testurl("$scheme$host$path");
        return "$scheme$host$path";
    } else {
        return "";
    }
    return $url;
}

function mct_ai_testurl($url){
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_HEADER,true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_NOBODY,true);    
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
    $headers = curl_exec($ch);
    $curlinfo = curl_getinfo($ch);
    curl_close($ch);
    if ($curlinfo['http_code'] != 200) {
        mct_ai_log("URL Test Error",MCT_AI_LOG_ACTIVITY, 'HTTP error: '.strval($curlinfo['http_code']),$url);
        return false;
    } 
}

function mct_ai_postthumb($imgurl, $post_id, $title) {
    // Load an image pointed to by a url into the post thumbnail featured image
    // Adapted from code by Aditya Mooley from auto post thumbnail plugin
    
    // Validate file and Get the file name
    $validate = @getimagesize($imgurl);
    if (!empty($validate['mime'])) { 
        $type = $validate['mime'];
    } else {
        mct_ai_log("Image Upload Error",MCT_AI_LOG_ERROR, 'Invalid File Type '.$imgurl,ini_get('allow_url_fopen'));//error_log('Invalid File Type '.$filename);
        return null;
    }
    $imgpath = parse_url($imgurl,PHP_URL_PATH); //ignore domain and query
    $filename = substr($imgpath, (strrpos($imgpath, '/'))+1);
    $filename = sanitize_file_name($filename);  //remove any url encoding for spaces etc.
    $filename = pathinfo($filename, PATHINFO_FILENAME); //Get just name to make sure we have no bad extensions
    if (!empty($type)) {
        $pos = strpos($type,'/');  //try to get extension from mime type
        if ($pos) $filename .= '.'.substr($type,$pos+1); //add extension
    }
    if (!(($uploads = wp_upload_dir(current_time('mysql')) ) && false === $uploads['error'])) {
        mct_ai_log("Image Upload Error",MCT_AI_LOG_ERROR, 'Failed to get upload dir '.$uploads['error'],$imgurl);//error_log('Failed to get upload dir '.$uploads['error']);
        return null;
    }

    // Generate unique file name
    $filename = wp_unique_filename( $uploads['path'], $filename );

    // Move the file to the uploads dir
    $new_file = $uploads['path'] . "/$filename";
    
    if (!ini_get('allow_url_fopen')) {
        $file_data = mct_ai_curl_get_file_contents($imgurl);
    } else {
        $file_data = @file_get_contents($imgurl);
    }
    
    if (!$file_data) {
        mct_ai_log("Image Upload Error",MCT_AI_LOG_ERROR, 'Failed to get file contents '.$imgurl,ini_get('allow_url_fopen'));//error_log('Failed to get file contents '.$imgurl);
        return null;
    }
    
    file_put_contents($new_file, $file_data);

    // Set correct file permissions
    $stat = stat( dirname( $new_file ));
    $perms = $stat['mode'] & 0000666;
    @ chmod( $new_file, $perms );
    // Compute the URL
    $url = $uploads['url'] . "/$filename";

    // Construct the attachment array
    $attachment = array(
        'post_mime_type' => $type,
        'guid' => $url,
        'post_parent' => null,
        'post_title' => $title,
        'post_content' => '',
    );

    $thumb_id = wp_insert_attachment($attachment, false, $post_id);
    if ( !is_wp_error($thumb_id) ) {
        require_once(ABSPATH . '/wp-admin/includes/image.php');
        
        wp_update_attachment_metadata( $thumb_id, wp_generate_attachment_metadata( $thumb_id, $new_file ) );
        update_post_meta($thumb_id,'_wp_attachment_image_alt',$title); //Add title as alt
        update_attached_file( $thumb_id, $new_file );
        return $thumb_id;
    }
    mct_ai_log("Image Upload Error",MCT_AI_LOG_ERROR, 'Failed to insert attachment '.$thumb_id->get_error_message(),$imgurl);//error_log('Failed to insert attachment '.$thumb_id->get_error_message());
    return null;
}

/**
 * Function to fetch the contents of URL using curl in absense of allow_url_fopen.
 * 
 * Copied from user comment on php.net (http://in.php.net/manual/en/function.file-get-contents.php#82255)
 */
function mct_ai_curl_get_file_contents($URL) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);

    if ($contents) {
        return $contents;
    }
    
    return FALSE;
}

function mct_ai_build_link($url, $title){
    //Build a well formatted link string for Getit, Notebooks
    //local_proc doesn't use this (maybe refactor in future)
    global $mct_ai_optarray;
    
    $hoststr = parse_url($url,PHP_URL_HOST);
    $intro = "Click here to view original web page at ".$hoststr;
    $post_arr['title'] = $title;
    if (!empty($mct_ai_optarray['ai_new_tab'])) {
        $post_arr['orig_link'] = '<a href="'.$url.'" target="_blank">'.$intro.'</a>';
    } else {
        $post_arr['orig_link'] = '<a href="'.$url.'" >'.$intro.'</a>';
    }
    $post_arr['orig_link'] = mct_ai_formatlink($post_arr);
    $content = '<p id="mct-ai-attriblink">'.$post_arr['orig_link'].'</p>';
    if (empty($mct_ai_optarray['ai_orig_text'])) {
        $content = str_replace("Click here to view original web page at ",$mct_ai_optarray['ai_orig_text'],$content); //remove space too
    } else {
        $content = str_replace("Click here to view original web page at",$mct_ai_optarray['ai_orig_text'],$content);  // leave space
    }
    return $content;
}

function mct_ai_formatlink($post_arr){
    //Format link based on options (used by build link and local_proc
    global $mct_ai_optarray;
    
    $link = $post_arr['orig_link'];
    $title = $post_arr['title'];
    $intro = "Click here to view original web page at ";
    $cnt = preg_match('{^(<a[^>]*>)([^<]*)(</a>)$}',$link,$matches);
    $anchor = str_replace($intro,"",$matches[2]);
    
    if (isset($mct_ai_optarray['ai_post_title']) && $mct_ai_optarray['ai_post_title'] ){
        $anchor = $title;
    }
    if (isset($mct_ai_optarray['ai_no_anchor']) && $mct_ai_optarray['ai_no_anchor'] ){
        return $intro.$matches[1].$anchor."</a>";
    } else {
        return $matches[1].$intro.$anchor."</a>";
    }
    return $link;
}


function mct_ai_getplan($force = false){
    //Get the plan from cloud service
    global $mct_ai_optarray, $proc_id, $proc_cnt;
    
    if (empty($mct_ai_optarray['ai_cloud_token'])) return false;
    if (!$force && get_transient('mct_ai_getplan') == 'checked') { //In case we are doing a lot of work
        if (empty($mct_ai_optarray['ai_plan'])) return false;
        $plan = unserialize($mct_ai_optarray['ai_plan']);
        if (isset($plan['max'])) {
            if ($plan['max']== -1) return false;
        }
        return true;  //In case we are doing a lot of work
    }
    //include_once ('MyCurator_local_proc.php');
    $topic = array('topic_id' => 0, 'topic_name' => 'CloudService'); //need a topic for cloud call
    $proc_id = time();
    $response = mct_ai_callcloud("GetPlan",$topic,"");
    if ($response == NULL || $response == false) {
        $mct_ai_optarray['ai_plan'] = '';
        update_option('mct_ai_options',$mct_ai_optarray);
        return false; //error already logged
    }
    if (!empty($response->LOG)) {
        $log = get_object_vars($response->LOG);
        //Log the error
        mct_ai_log($log['logs_topic'], $log['logs_type'], $log['logs_msg'], $log['logs_url']);
        //If Invalid Token, Version or Expired set plan as error, else return (and leave whatever plan we have)
        if (strpos($log['logs_msg'],"Token") !== false || strpos($log['logs_msg'],"Expired") !== false || strpos($log['logs_msg'],"Version") !== false) {
            $mct_ai_optarray['ai_plan'] = serialize(array('name'=> $log['logs_msg'], 'max' => -1  ));
            set_transient('mct_ai_getplan', 'checked',(60*5)); //Default is to wait for 5 minutes before checking
            update_option('mct_ai_options',$mct_ai_optarray);
            return false;
        } else {
            $mct_ai_optarray['ai_plan'] = '';
            update_option('mct_ai_options',$mct_ai_optarray);
            return false;    
        }
    } else { //No error, so set plan 
        $mct_ai_optarray['ai_plan'] = serialize(get_object_vars($response->planarr));
        set_transient('mct_ai_getplan', 'checked',(60*60*24)); // Got a plan so wait for a day before we check again
    }
    update_option('mct_ai_options',$mct_ai_optarray);
    return true;
}

function mct_ai_sourcemax() {
    //Check for number of sources greater than max
    //Returns false if no max, otherwise # of sources left, could be negative
    global $mct_ai_optarray, $ai_topic_tbl, $wpdb;
    
    $plan = unserialize($mct_ai_optarray['ai_plan']);
    if (!empty($plan['maxsrc'])){
        $src_count = 0;
        //Count sources in links file but skip 'blogroll' category
        $args = array(
            'hide_invisible' => false
        );
        $feeds = get_bookmarks($args);
        if (empty($feeds)){
            return $plan['maxsrc'];
        }
        $src_count = count($feeds);
        //now get those with blogroll
        $args = array(
            'category_name' => 'blogroll',
            'hide_invisible' => false
        );
        $feeds = get_bookmarks($args);
        if (!empty($feeds)){
            $src_count -= count($feeds);
        }
        return $plan['maxsrc'] - $src_count;
    }
    return false;
}

function mct_ai_showsrc() {
    //Show results of source check
    global $mct_ai_optarray;
    
    $token = $mct_ai_optarray['ai_cloud_token'];
    echo '<h3>The Maximum Sources for Your Plan has been reached - You Can Not Add Any More Sources</h3>';
    echo '<p>If you would like to set up more Sources than your current plan allows, <a href="http://www.target-info.com/myaccount/?token='.$token.'" >Upgrade to a Pro or Business Plan</a></p>';
}

function mct_ai_train_ajax() {
    //Handle ajax requests from training pages/posts
    global $mct_ai_optarray;
    
    $response = new WP_Ajax_Response;
    //User Cap ok?
    if (!current_user_can('edit_published_posts')){
        $response->add(array('data' => 'Error - Not Allowed'));
        $response->send();
        exit();
    }
    //Get args
    $args = wp_parse_args($_POST['qargs']);
    //Multi class set
    if (!empty($args['multi'])){ 
        $pid = intval($args['multi']);
        if (!check_ajax_referer('mct_ai_multi'.$pid,'nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        $terms = mct_ai_train_multi($pid);
        if ( is_wp_error($terms) ) { 
            $response->add(array('data' => 'Error - Update Term'));
        } else {
            $response->add(array(
                'data' => 'Ok',
                'supplemental' => array(
                    'action' => 'multi',
                    'remove' => 0
                ),
            ));
        }
        $response->send();
        exit();
    }
    //Delete post
    if (!empty($args['action']) && $args['action'] == 'trash'){ 
        $pid = intval($args['post']);
        if (!check_ajax_referer('trash-post_'.$pid,'nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        $terms = wp_trash_post($pid);
        if ( !$terms ) { 
            $response->add(array('data' => 'Error - Could Not Delete'));
        } else {
            $response->add(array(
                'data' => 'Ok',
                'supplemental' => array(
                    'action' => 'delete',
                    'remove' => $pid
                ),
            ));
        }
        $response->send();
        exit();
    }
    //Train Bad
    if (!empty($args['bad'])){ 
        $pid = intval($args['bad']);
        if (!check_ajax_referer('mct_ai_train_bad'.$pid,'nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        $tname = mct_ai_get_tname_ai($pid);
        if ($tname != ''){
            mct_ai_trainpost($pid, $tname, 'bad'); 
            $terms = wp_trash_post($pid);
            if ( !$terms ) { 
                $response->add(array('data' => 'Error - Could Not Delete Post'));
            } else {
                $response->add(array(
                    'data' => 'Ok',
                    'supplemental' => array(
                        'action' => 'bad',
                        'remove' => $pid
                    ),
                ));
            }
        } else {
            $response->add(array('data' => 'Error - Could not find Topic'));
        } 
        $response->send();
        exit();
    }
    //Train Good
    if (!empty($args['good'])){ 
        $pid = intval($args['good']);
        if (!check_ajax_referer('mct_ai_train_good'.$pid,'nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        $tname = mct_ai_get_tname_ai($pid);
        if ($tname != ''){
            mct_ai_trainpost($pid, $tname, 'good'); 
            if (!empty($mct_ai_optarray['ai_keep_good_here'])) {
                $remove = 0;
                $edit = 'no';
            } else {
                $remove = $pid;
                if (!empty($mct_ai_optarray['ai_edit_makelive'])) {
                    mct_ai_traintoblog($pid,'draft');
                    $edit = 'yes';
                } else {
                    mct_ai_traintoblog($pid,'publish');
                    $edit = 'no';
                }
            }
            
            $response->add(array(
                    'data' => 'Ok',
                    'supplemental' => array(
                        'action' => 'good',
                        'edit' => $edit,
                        'remove' => $remove
                    ),
                ));
        } else {
            $response->add(array('data' => 'Error - Could not find Topic'));
        } 
        $response->send();
        exit();
    }
    //Make Live
    if (!empty($args['move'])){ 
        $pid = intval($args['move']);
        if (!check_ajax_referer('mct_ai_move'.$pid,'nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        if (!empty($mct_ai_optarray['ai_edit_makelive'])) {
            mct_ai_traintoblog($pid,'draft');
            $edit = 'yes';
        } else {
            mct_ai_traintoblog($pid,'publish');
            $edit = 'no';
        }
        $response->add(array(
                'data' => 'Ok',
                'supplemental' => array(
                    'action' => 'move',
                    'edit' => $edit,
                    'remove' => $pid
                ),
        ));
        $response->send();
        exit();
    }
    //draft
    if (!empty($args['draft'])){ 
        $pid = intval($args['draft']);
        if (!check_ajax_referer('mct_ai_draft'.$pid,'nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        mct_ai_traintoblog($pid,'draft');
        $edit = 'no';
        $response->add(array(
                'data' => 'Ok',
                'supplemental' => array(
                    'action' => 'draft',
                    'edit' => 'no',
                    'remove' => $pid
                ),
        ));
        $response->send();
        exit();
    }
    //Quick Post
    if (!empty($args['quick'])){ 
        $pid = intval($args['quick']);
        if (!check_ajax_referer('bulk-posts','nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        //rebuild the post contents
        $thepost = get_post($pid, ARRAY_A);
        if (empty($thepost)) {
            $response->add(array('data' => 'Error - No Post - '.$pid));
            $response->send();
            exit();
        }
        $newpost = array();
        $newpost['ID'] = $pid;
        $newpost['post_title'] = trim(sanitize_text_field($_POST['title']));
        //$excerpt = trim(sanitize_text_field($_POST['excerpt']));
        $excerpt = str_replace(PHP_EOL,'<br>',$_POST['excerpt']);
        $excerpt = wp_kses_post($excerpt);
        $notes = str_replace(PHP_EOL,'<br>',$_POST['note']);
        $notes = wp_kses_post($notes);
        //$notes = trim(sanitize_text_field($_POST['note']));
        $type = trim(sanitize_text_field($_POST['type']));
        $content = $thepost['post_content'];
        //new text
        $newtext = '';
        if (!empty($notes)) $newtext = '<p>'.$notes.'</p>';
        if (!empty($excerpt)){
            $newtext = $newtext.mct_ai_setexcerpt($excerpt);
        }
        //place in content
        $newcontent = mct_ai_resetexcerpt($content,$newtext);  //- this will remove line breaks on any update
        if (!empty($newcontent)) {
            $content = $newcontent;
        } elseif (stripos($content, '<p id="mct-ai-attriblink">') !== false) {
            //place just in front of link
            $pos = stripos($content, '<p id="mct-ai-attriblink">');
            if ($pos == 0) {
                $content = $newtext.$content;
            } else {
                $content = substr($content,0,$pos).$newtext.substr($content,$pos);
            }
        } else {
            //just append to the end, probably a picture or a video
            $content = $content.$newtext;
        }
        $newpost['post_content'] = $content;
        wp_update_post($newpost);
        //Now move it out of training
        mct_ai_traintoblog($pid, $type);
        $response->add(array('data' => 'Ok'));
        $response->send();
        exit();
    }
    //Move to Notebook
    if (!empty($args['notebk'])){ 
        $pid = intval($args['notebk']);
        if (!check_ajax_referer('bulk-posts','nonce', false)) {
            $response->add(array('data' => 'Error - Bad Nonce'));
            $response->send();
            exit();
        }
        $note = str_replace(PHP_EOL,'<br>',$_POST['note']);
        $note = wp_kses_post($note);
        $notebk_id = (isset($_POST['nbook'])) ? intval($_POST['nbook']) : 0;
        if (!empty($_POST['newnb'])) {
            //Insert Notebook First
            $title = trim(sanitize_text_field($_POST['newnb']));
            $details = array(
              'post_content'  => '',
              'post_title'  =>  $title,
              'post_name' => sanitize_title($title),
              'post_type' =>  'mct_notebk',
              'post_status' => 'publish'
            );
            $notebk_id = wp_insert_post($details);
            if (empty($notebk_id)) {
                $response->add(array('data' => 'Error - Could Not Create Notebook'));
                $response->send();
                exit();
            }
        }
        if (empty($notebk_id)) {
                $response->add(array('data' => 'Error - No Notebook Selected'));
                $response->send();
                exit();
        }
        mct_nb_traintonotepg($pid, $notebk_id, $note);
        $response->add(array('data' => 'Ok'));
        $response->send();
        exit();
    }
    $response->add(array('data' => 'Error - No Trx Specified'));
    $response->send();
    exit();
}

function mct_ai_get_tname_ai($post_id){
    //Returns the topic name for a post if it is a Relevance type, else blank
    global $wpdb, $ai_topic_tbl;
    
    $terms = get_the_terms( $post_id, 'topic' );
    if (empty($terms)) return '';
    $tname = '';
    if (count($terms) == 1 ) { //should only be one
        //The array key is the id
        $tids = array_keys($terms);
        $term = $terms[$tids[0]];
        $tname = $term->name;
    } else {
        return '';
    }
    // Check whether Relevance type 
    $sql = "SELECT `topic_type`
            FROM $ai_topic_tbl
            WHERE topic_name = '$tname'";
     $edit_vals = $wpdb->get_row($sql, ARRAY_A);
     if ($edit_vals['topic_type'] != "Relevance") return '';
     
    return $tname;
}

function mct_ai_traintoblog($thepost, $status){
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray, $user_ID;
    
    //Move a post - change post type from target_ai to post
    //read topic table for this category/tag
    $topic = wp_get_object_terms($thepost,'topic',array('fields' => 'names'));
    if (!empty($topic)) {
        $tname = $topic[0];  //should always only be one
        $sql = "SELECT topic_cat, topic_tag, topic_tag_search2, topic_options FROM $ai_topic_tbl WHERE topic_name = '$tname'";
        $row = $wpdb->get_row($sql, ARRAY_A);
        $row = mct_ai_get_topic_options($row);
        if (!empty($row)){
            $details = array();
            $details['ID'] = $thepost;
            $details['post_status'] = 'draft';
            //update training post to draft for publicize
            wp_update_post($details);
            //
            if (!empty($row['opt_post_ctype']) && $row['opt_post_ctype'] != 'not-selected') {
                $details['post_type'] = $row['opt_post_ctype'];
                if (!empty($row['opt_post_ctax']) && !empty($row['opt_post_ctaxval'])) {
                    $details['tax_input'] = array($row['opt_post_ctax'] => $row['opt_post_ctaxval']);
                    $tax = wp_get_object_terms($thepost,'topic',array('fields' => 'slugs'));
                    if (!empty($tax)) $details['tax_input'] = array_merge($details['tax_input'],array('topic' => $tax[0]));
                    $tax = wp_get_object_terms($thepost,'ai_class',array('fields' => 'slugs'));
                    if (!empty($tax)) $details['tax_input'] = array_merge($details['tax_input'],array('ai_class' => $tax[0]));
                }
            } else {
                $details['post_type'] = 'post';
                $details['post_category'] = array($row['topic_cat']);
                if ($row['topic_tag_search2']){
                    $details['tags_input'] = get_post_meta($thepost,'mct_ai_tag_search2', true);
                } else {
                    $tagterm = get_term($row['topic_tag'],'post_tag');
                    if (!empty($tagterm) && !is_wp_error($tagterm)) $details['tags_input'] = array($tagterm->name);
                }
            }
            if ($status == 'draft') {
                $details['post_status'] = 'draft';
                if (!empty($mct_ai_optarray['ai_now_date'])) {
                    $details['edit_date'] = true;
                    $details['post_date'] = '';
                    $details['post_date_gmt'] = "0000-00-00 00:00:00";
                }
            } else {
                $details['post_status'] = 'publish';
                if (!empty($mct_ai_optarray['ai_now_date'])) {
                    $details['edit_date'] = true;
                    $details['post_date'] = '';
                    $details['post_date_gmt'] = '';
                }
            }
            
            if (!empty($mct_ai_optarray['ai_post_this_user'])){
                $cu = wp_get_current_user();
                $details['post_author'] = $user_ID;
            }
            
            //Clear the post meta that publicize uses to say its already been publicized
            delete_post_meta($thepost,'_wpas_done_all');  //publicize meta 
            
            $details['comment_status'] = get_option('default_comment_status');
            $details['ping_status'] = get_option('default_ping_status');
            $details = apply_filters('mct_traintoblog_details', $details);
            wp_update_post($details);
        }
    }
}

function mct_ai_train_multi($pid){
    //Set multi ai_class
   return wp_set_object_terms($pid,'multi','ai_class',false);
}

function mct_ai_showpg_ajax() {
    //Ajax functions to insert/set featured image from editor
    global $mct_ai_optarray;
    $response = new WP_Ajax_Response;
    
    //Get args
    
    $pid = intval($_POST['pid']);
    $src = $_POST['imgsrc'];
    $type = $_POST['type'];
    $title = $_POST['title'];
    //check nonce
    if (!check_ajax_referer('mct_ai_showpg','nonce',false)) {
        $response->add(array('data' => 'Error - Bad Nonce'));
        $response->send();
        exit();
    }
    $thumb_id = mct_ai_postthumb($src,$pid, $title);
    if (!$thumb_id) {
        $response->add(array('data' => 'Error - Could not save image'));
        $response->send();
        exit();
    }
    //handle insert
    if ($type == 'insert'){
        $details = array();
        $url = get_permalink( $pid );
        if (!empty($mct_ai_optarray['ai_title_link'])) {
            $url = get_post_meta($pid,'mct_sl_origurl',true);
            $url = $url[0];
        } else {
            $url = get_permalink( $pid );
        }
        $align = $_POST['align'];
        $size = $_POST['size'];
        $src = wp_get_attachment_image_src($thumb_id,$size);
        if (!$src) {
            $src = wp_get_attachment_image_src($thumb_id,'thumbnail');  //try thumbnail
            if (!$src) wp_get_attachment_image_src($thumb_id,'full');  //try full size
        }
        if ($src) {
            $imgstr = '<a href="'.$url.'"><img class="size-'.$size.' align'.$align.'" alt="'.$title.'" src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></a>';
            $response->add(array(
                'data' => 'Ok', 
                'supplemental' => array(
                    'imgstr' => $imgstr
                ),
           ));
        } else {
            $response->add(array('data' => 'Error - Could not get source'));
        }
    }
    //handle featured image
    if ($type == 'feature'){
        //Check for and delete current featured image
        $old_thumb = get_post_meta($pid, '_thumbnail_id',true);  //Post Thumbnail
        if ($old_thumb) wp_delete_attachment($old_thumb,true);
        //Now update
        $ret = update_post_meta( $pid, '_thumbnail_id', $thumb_id );
        $html = _wp_post_thumbnail_html( $thumb_id, $pid );
        $response->add(array(
                'data' => 'Ok', 
                'supplemental' => array(
                    'imgstr' => $html
                ),
           ));
    }
    $response->send();
    exit();
}

function mct_ai_schedcron(){
    //Schedule cron jobs
    global $mct_ai_optarray;
    
    if(empty($mct_ai_optarray['ai_on'])) return;
    
    if (!wp_next_scheduled('mct_ai_cron_process')){
        $cronperiod = 'mct6hour';  //default if not set
        if ($mct_ai_optarray['ai_cron_period'] == '3') $cronperiod = 'mct3hour';
        if ($mct_ai_optarray['ai_cron_period'] == '12') $cronperiod = 'twicedaily';
        if ($mct_ai_optarray['ai_cron_period'] == '24') $cronperiod = 'daily';
        $strt = time() +(60*5);
        $scheds = wp_get_schedules();
        if (!isset($scheds[$cronperiod])) $cronperiod = 'twicedaily'; //force to WP std value if another plugin has trashed our schedules
        wp_schedule_event($strt,$cronperiod,'mct_ai_cron_process');
    }
    if (!wp_next_scheduled('mct_ai_cron_rqstproc')){
        $strt = time()+(60*30);  //30 minutes from now
        if (!empty($mct_ai_optarray['ai_page_rqst'])) wp_schedule_event($strt,'hourly','mct_ai_cron_rqstproc');
    }
    
}

function mct_ai_timesince( $since ) {
        // array of time period chunks
        $chunks = array(
                /* translators: 1: The number of years in an interval of time. */
                array( 60 * 60 * 24 * 365, 'year'),
                /* translators: 1: The number of months in an interval of time. */
                array( 60 * 60 * 24 * 30, 'month'),
                /* translators: 1: The number of weeks in an interval of time. */
                array( 60 * 60 * 24 * 7, 'week' ),
                /* translators: 1: The number of days in an interval of time. */
                array( 60 * 60 * 24, 'day' ),
                /* translators: 1: The number of hours in an interval of time. */
                array( 60 * 60, 'hour' ),
                /* translators: 1: The number of minutes in an interval of time. */
                array( 60, 'minute'),
                /* translators: 1: The number of seconds in an interval of time. */
                array( 1, 'second' )
        );

        if ( $since <= 0 ) {
                return "Now";
        }

        // we only want to output two chunks of time here, eg:
        // x years, xx months
        // x days, xx hours
        // so there's only two bits of calculation below:

        // step one: the first chunk
        for ( $i = 0, $j = count( $chunks ); $i < $j; $i++ ) {
                $seconds = $chunks[ $i ][0];
                $name = $chunks[ $i ][1];

                // finding the biggest chunk (if the chunk fits, break)
                if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
                        break;
                }
        }

        // set output var
        $plural = ($count >1) ? $name."s": $name;
        $output = " ".strval($count). " ". $plural;

        // step two: the second chunk
        if ( $i + 1 < $j ) {
                $seconds2 = $chunks[ $i + 1 ][0];
                $name2 = $chunks[ $i + 1 ][1];

                if ( ( $count2 = floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) != 0 ) {
                        // add to output var
                        $plural = ($count2 >1) ? $name2."s": $name2;
                        $output .= ' ' . strval($count2). " ". $plural;
                }
        }

        return $output;
}

function mct_ai_helpvideo($name){
    
    $vstr = '';
    $docstr = '';
    $idstr = 'mct-ai-helpvideo';
    $fstr = '';
    switch ($name) {
        case 'quick': 
            $vstr = 'jSe4cFlSumU';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/getting-started/" target="_blank">Getting Started Documentation</a> for more details.</p>';
            break;
        case 'dash': 
            $vstr = 'iKg0ndygdTQ';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-dashboard/" target="_blank">Dashboard Documentation</a> for more details.</p>';
            break;
        case 'sources':
            $vstr = 'Nyn8EVA4Oc4';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-sources/" target="_blank">Sources Documentation</a> for more details.</p>';
            break;
        case 'news':
            $vstr = 'ryBQrV7mwXw';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-sources/" target="_blank">Sources Documentation</a> for more details.</p>';
            break;
        case 'addsource':
            $vstr = 'ToiBRPwB0aw';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-sources/" target="_blank">Sources Documentation</a> for more details.</p>';
            break;
        case 'curation':
            $vstr = 'bJo1Gc1xieM';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-manual-curation/" target="_blank">Curation</a> '
                    . '& <a href="http://www.target-info.com/documentation-2/documentation-training/" target="_blank">Training</a> documentation for more details.</p>';
            break;
        case 'topics':
            $vstr = 'F23bw7bgOtg';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-topics/" target="_blank">Topics Documentation</a> for more details.</p>';
            break;
        case 'topicpage':
            $vstr = 'R_1hCboTRH4';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-topics/" target="_blank">Topics Documentation</a> for more details.</p>';
            break;
        case 'getit':
            $vstr = 'hsGJIL4tQMQ';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-get-it/" target="_blank">Get It Documentation</a> for more details.</p>';
            break;
        case 'sourceit':
            $vstr = 'JO66meRuuKw';
            $idstr = 'mct-ai-helpvideo2';
            $fstr = '2';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-source-it/" target="_blank">Source It Documentation</a> for more details.</p>';
            break;
        case 'galerts':
            $vstr = 'G5S3lD1CQjA';
            $idstr = 'mct-ai-helpvideo2';
            $fstr = '2';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/google-alerts/" target="_blank">Google Alerts Documentation</a> for more details.</p>';
            break;
        case 'notebooks':
            $vstr = 'Kmq7SwTRkLw';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-notebooks/" target="_blank">Notebooks Documentation</a> for more details.</p>';
            break;
        case 'logs':
            $vstr = 'yhly-1l9iYQ';
            $docstr = '<p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-logs/" target="_blank">Logs Documentation</a> for more details.</p>';
            break;
    }
    $divstr = '
    <div id="'.$idstr.'" style="display: none" height="325">
        <iframe src="//www.youtube.com/embed/'.$vstr.'?autoplay=0&rel=0" width="600" height="300" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
    '.$docstr.'</div>';
    $script = '
        <script>
        function mctaishowvideo'.$fstr.'() {
            if (document.getElementById("'.$idstr.'").style.display == "none") {
                document.getElementById("'.$idstr.'").style.display = "block";
            } else {
                document.getElementById("'.$idstr.'").style.display = "none";
            }
            
        }
        </script>';
    return $divstr.$script;
} 


?>
