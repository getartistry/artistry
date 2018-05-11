<?php

//This is the file called by cron to kick off the MyCurator process for a single site
//If this is  a first call, it sets up a process queue for each topic 
//Then, it chains calls to this same page to process topics one at a time
//This helps to ensure it does not run out of process time
//If there are a lot of sources with a lot of new articles in each, it may run out of processing time.
//  If so, the queue will still be in the options table and it will pick up where it left off the next time.  
//  eventually it should catch up and then process normally
	
include_once (dirname(dirname(dirname(dirname(__FILE__)))) .  DIRECTORY_SEPARATOR."wp-load.php");
include_once(plugin_dir_path(__FILE__).'MyCurator_local_proc.php');
include_once(plugin_dir_path(__FILE__).'MyCurator_fcns.php');

set_time_limit(300);  //bump up execution time
//if multisite, blog id should be on the call string, switch to the given blog
if (is_multisite()){
    $theblog = intval($_GET['blogid']);
    if (empty($theblog)) {
        mct_ai_log('Blog',MCT_AI_LOG_ERROR, 'No Blog ID for MU Site'.$proc_id.'  ', ' ');
        exit();
    }
    mct_ai_newblog($theblog);
}
//Assign a process id
global $proc_id;
$proc_id = time();
$proc_q = array();
//Backwards check for old process queue option
if (get_option('mct_ai_proc_queue') != false) {
    //Get rid of old option and transient
    delete_option('mct_ai_proc_queue'); 
    delete_transient('mct_ai_proc_queue'); 
}
//Get the proc queue
$proc_q = get_transient('mct_ai_proc_queue');
if (empty($proc_q)) {
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Start Process: '.$proc_id.'  ', ' ');
} else {
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Start Process: '.$proc_id.'  ', implode(',',$proc_q));
}

//Do we have a process q?
if (empty($proc_q)) {
    //clean logs
    mct_ai_clean_postsread(true);
     //Build values - Loop on all train and active topics in this site
    $sql = "SELECT `topic_id`
            FROM $ai_topic_tbl
            WHERE topic_status != 'Inactive'";
    $topics = $wpdb->get_results($sql, ARRAY_A);
    if (!$wpdb->num_rows){
        mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'No Topics, End Process: '.$proc_id.'  ', ' ');
        exit();  //Nothing to do
    }
    foreach ($topics as $topic){
        $proc_q[] = trim(strval($topic['topic_id']));
    }
    set_transient('mct_ai_proc_queue',$proc_q,60*60*36);  //Set the 'stale' timer
    //Start new process to do the work
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'New Queue, End Process: '.$proc_id.'  ', implode(',',$proc_q));
    mct_ai_new_proc();
    exit();
}
//pop q item and process it
$thistopic = array_pop($proc_q);
//Get the topic
$sql = "SELECT *
    FROM $ai_topic_tbl
    WHERE topic_id = '$thistopic'";
    $topic = $wpdb->get_row($sql, ARRAY_A);
if ($wpdb->num_rows){  //may have been deleted since we set up the queue
    //Check last run is more than 2 hours (7200 seconds) ago
    if (!empty($topic['topic_last_run'])){
        $lastrun = strtotime($topic['topic_last_run']);
        $now = strtotime($wpdb->get_var('Select now()'));  //use mysql time
        if (($now - $lastrun) < 7200) {
            mct_ai_log($topic['topic_name'],MCT_AI_LOG_PROCESS, 'Stopping, Topic recently updated '.$proc_id.'  ', ' ');
            //delete_option('mct_ai_proc_queue');  //no more work
            exit();
        }
    }
    $sql = "UPDATE $ai_topic_tbl SET topic_last_run = now() WHERE topic_id = '$thistopic'";
    $wpdb->query($sql);
    unset($topic['topic_last_run']);  //Don't pass to cloud
    mct_ai_process_topic($topic);
}
//All done, 
if (empty($proc_q)){
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'End Site Processing: '.$proc_id.'  ', ' ');
    delete_transient('mct_ai_proc_queue'); //no more work
} else {
    set_transient('mct_ai_proc_queue',$proc_q,60*60*36);//Set the 'stale' timer
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'End Process: '.$proc_id.'  ', implode(',',$proc_q));
    mct_ai_new_proc(); //Start the next process 
}
exit();


function mct_ai_new_proc(){
    //Starts another process by getting this page
    
    //use curl 
    if (is_multisite()){
        global $blog_id;
        $url = plugins_url('MyCurator_process_page.php',__FILE__).'/?blogid='.strval($blog_id);
    } else {
        $url = plugins_url('MyCurator_process_page.php',__FILE__);
    }

    $response = wp_remote_get($url);
    if( is_wp_error( $response ) && stripos($response->get_error_message(),'timed out') === false ) {//ignore timeout - we expect it
        mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Error '.$response->get_error_message()." starting MyCurator",$url);
    }
}

function mct_ai_newblog($bid){
    //switches blog and sets globals
   global $wpdb, $ai_topic_tbl, $ai_postsread_tbl, $ai_sl_pages_tbl, $ai_logs_tbl, $mct_ai_optarray;
   
   switch_to_blog($bid);
   //Set up tables, options for this blog
   $ai_topic_tbl = $wpdb->prefix.'topic';
   $ai_postsread_tbl = $wpdb->prefix.'postsread';  
   $ai_sl_pages_tbl = $wpdb->prefix.'sl_pages';
   $ai_logs_tbl = $wpdb->prefix.'ai_logs';
   $mct_ai_optarray = get_option('mct_ai_options');
   
}
?>