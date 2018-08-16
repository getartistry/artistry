<?php

/*
 * Plugin Name: MyCurator
 * Plugin URI: http://www.target-info.com
 * Description: Automatically curates articles from your feeds and alerts, using the Relevance engine to find only the articles you like
 * Version: 3.2
 * Author: Mark Tilly
 * Author URL: http://www.target-info.com
 * License: GPLv2 or later
 */
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
/*
 * Filters/Actions Available:
 * apply_filter('mct_ai_postcontent',$post_content, $post_arr);
 *   This filter lets you modify the excerpted content of the MyCurator post, normally a blockquoted excerpt and the article link
 *   you have access to a range of items in the $post_arr array
 * do_action('mct_ai_trainingpost',$post_id, $post_arr);
 *   This action lets you perform additional actions after the new article post is created
 *   $post_id is the newly created post
 *   you have access to a range of items in the $post_arr array
 * $details = apply_filters('mct_traintoblog_details', $details);
 *   This filter lets you alter the details of a post before it is made a draft or
 *   published from a training post.
 */

//Define some constants
define ('MCT_AI_REDIR','ailink');
define ('MCT_AI_LOG_ERROR','ERROR');
define ('MCT_AI_LOG_ACTIVITY','ARTICLE');
define ('MCT_AI_LOG_PROCESS','PROCESS');
define ('MCT_AI_LOG_REQUEST','REQUEST');
define ('MCT_AI_VERSION', '3.2');

//Globals for DB
global $wpdb, $ai_topic_tbl, $ai_postsread_tbl, $ai_sl_pages_tbl, $ai_logs_tbl, $proc_id, $proc_cnt;
$ai_topic_tbl = $wpdb->prefix.'topic';
$ai_postsread_tbl = $wpdb->prefix.'postsread';  
$ai_sl_pages_tbl = $wpdb->prefix.'sl_pages';
$ai_logs_tbl = $wpdb->prefix.'ai_logs';
$proc_id = 0;
$proc_cnt = 0;

//Activation hook
register_activation_hook(__FILE__, 'mct_ai_activehook');
//wpmu activate site hook
add_action( 'wpmu_new_blog', 'mct_ai_wpmunewblog' );
//Get options
global $mct_ai_optarray;
$mct_ai_optarray = get_option('mct_ai_options');
//Get support functions
include('MyCurator_posttypes.php');
include('MyCurator_local_classify.php');  
include('MyCurator_fcns.php');
include('MyCurator_notebk.php');
include_once('MyCurator_link_redir.php');
include_once ('MyCurator_local_proc.php');

//Set up menus
add_action('admin_menu', 'mct_ai_createmenu');
//Set up Cron
add_action ('mct_ai_cron_process', 'mct_ai_run_mycurator');
add_action ('mct_ai_cron_rqstproc', 'mct_ai_process_request');
add_filter ('cron_schedules', 'mct_ai_set_cron_sched');

//Link manager, add rss column, change link entry form if requested
if (!empty($mct_ai_optarray['ai_short_linkpg'])) {
    add_filter('manage_link-manager_columns','mct_ai_linkcol');
    add_action('manage_link_custom_column','mct_ai_linkcolout',10,2);
    add_action('add_meta_boxes_link','mct_ai_linkeditmeta');
    //For wp3.5 add filter to keep link manager enabled
    add_filter( 'pre_option_link_manager_enabled', '__return_true' );
}

//Check for plan if we don't have one
if (empty($mct_ai_optarray['ai_plan']) && !empty($mct_ai_optarray['ai_cloud_token'])){  
    mct_ai_getplan();
}

function mct_ai_activehook() {
    //Set up basics on activation
    //
    //Set up default options
    global $mct_ai_optarray;
    
    //Create the data tables
    mct_ai_createdb();
    //Create the Training Page if not there already
    $details = array(
      'post_content'  => '[MyCurator_training_page]',
      'post_title'  =>  'MyCurator Training Page',
      'post_name' => sanitize_title('MyCurator Training Page'),
      'post_type' => 'page',
      'post_status' => 'private'
    );
    $trainpage = false;
    $pages = get_pages(array('post_status' => 'publish,private'));
    foreach ($pages as $page) {
        if (stripos($page->post_content,"MyCurator_training_page") !== false) {
            $trainpage = true;
        }
    }
    if (!$trainpage) wp_insert_post($details);
    //Redirect rules
    mct_sl_add_rule();
    flush_rewrite_rules();
    
}

function mct_ai_wpmunewblog($blog_id){
    //New blog added, call activation
    global $wpdb, $ai_topic_tbl, $ai_postsread_tbl, $ai_sl_pages_tbl, $ai_logs_tbl, $mct_ai_optarray;
    
    if ( 1 !== did_action( 'wpmu_new_blog' ) )
        return;
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    if ( !is_plugin_active_for_network( plugin_basename(__FILE__) ) ) return;
    
    switch_to_blog( $blog_id );
    $ai_topic_tbl = $wpdb->prefix.'topic';
    $ai_postsread_tbl = $wpdb->prefix.'postsread';  
    $ai_sl_pages_tbl = $wpdb->prefix.'sl_pages';
    $ai_logs_tbl = $wpdb->prefix.'ai_logs';
    mct_ai_activehook();
    restore_current_blog();
    $ai_topic_tbl = $wpdb->prefix.'topic';
    $ai_postsread_tbl = $wpdb->prefix.'postsread';  
    $ai_sl_pages_tbl = $wpdb->prefix.'sl_pages';
    $ai_logs_tbl = $wpdb->prefix.'ai_logs';
    $mct_ai_optarray = get_option('mct_ai_options');
}

function mct_ai_linkcol($colarray) {
    //Show a different set of link columns, including rss
    $colarray['rss'] = __( 'Feed URL' );
    unset($colarray['rel']);
    unset($colarray['visible']);
    unset($colarray['rating']);
    return $colarray;
}
function mct_ai_linkcolout($colname, $linkid){
    //Does the display of the rss column with link
    global $wp_object_cache;
    
    if ($colname == 'rss'){
        $thismark = get_bookmark($linkid);
        $thislink = $thismark->link_rss;
        $short_url = url_shorten( $thislink );
        echo '<a href="'.$thislink.'">'.$short_url.'</a>';
    }
}

function mct_ai_linkeditmeta($linkpg){
    //This function removes unneeded meta boxes on the link edit page and adds in a new meta box for just the rss link
    //if requested in the options set up
    remove_meta_box('linktargetdiv', null, 'normal');
    remove_meta_box('linkxfndiv', null, 'normal');
    remove_meta_box('linkadvanceddiv', null, 'normal');
    add_meta_box('mctlinkrssfeed', __('Feed URL'), 'mct_ai_linkrssbox', null, 'normal', 'core');
}

function mct_ai_linkrssbox($link){
    //Adds the link rss meta box if we've shortened the links page
    ?>
    <td><input name="link_rss" class="code" type="text" id="rss_uri" value="<?php echo  ( isset( $link->link_rss ) ? esc_attr($link->link_rss) : ''); ?>" size="50" style="width: 95%" />
    <p><?php _e('Example: <code>http://feeds.feedburner.com/exampleblog</code> &#8212; don&#8217;t forget the <code>http://</code>'); ?></p>
<?php
}

function mct_ai_createmenu() {
    //Set up our Topics menu
    global $mct_ai_optarray;
    
    if (mct_ai_menudisp()){
        add_menu_page('MyCurator', 'MyCurator','publish_posts',__FILE__,'mct_ai_firstpage');
        add_submenu_page(__FILE__,'Dashboard', 'Dashboard','publish_posts',__FILE__,'mct_ai_firstpage');
        $plan = unserialize($mct_ai_optarray['ai_plan']);
        if (!empty($plan) && $plan['max'] != -1) {
            add_submenu_page(__FILE__,'Sources', 'Sources','manage_categories',__FILE__.'_sources','mct_ai_sources');
            add_submenu_page(__FILE__,'Topics', 'Topics','manage_categories',__FILE__.'_alltopics','mct_ai_topics');
            $getpage = add_submenu_page(__FILE__,'Get It & Source It', 'Get It & Source It','publish_posts',__FILE__.'_getit','mct_ai_getitpage');
            $notebk = add_submenu_page(__FILE__,'NoteBooks','NoteBooks','publish_posts',__FILE__.'_notebook','mct_nb_notebk_page');
            $optionspage = add_submenu_page(__FILE__,'Options', 'Options','manage_categories',__FILE__.'_options','mct_ai_optionpage');
            add_submenu_page(__FILE__,'Performance','Performance','manage_categories',__FILE__.'_Report','mct_ai_logreport');
            add_action('load-'.$getpage, 'mct_ai_queueit');
            add_action('load-'.$optionspage, 'mct_ai_queueit');
            add_action('load-'.$notebk,'mct_nb_queuejs');
        }
        add_submenu_page(__FILE__,'Logs','Logs','manage_categories',__FILE__.'_Logs','mct_ai_logspage');
    } else {
        $getpage = add_menu_page('Get It & Notebooks', 'Get It & Notebooks','publish_posts',__FILE__,'mct_ai_getitpage');
        $notebk = add_submenu_page(__FILE__,'NoteBooks','NoteBooks','publish_posts',__FILE__.'_notebook','mct_nb_notebk_page');
        add_action('load-'.$getpage, 'mct_ai_queueit');
        add_action('load-'.$notebk,'mct_nb_queuejs');
    }
    
}

function mct_ai_queueit(){
    //Queue needed scripts and styles
    wp_enqueue_script('jquery-ui-tabs');
    $style = plugins_url('css/MyCurator.css',__FILE__);
    wp_register_style('myctabs',$style,array(),'1.0.0');
    wp_enqueue_style('myctabs');
}

function mct_ai_topics() {
    //Dispatch to correct topic page based on request
    if (isset($_REQUEST['newtopic'])) {
        mct_ai_topicpage();
        return;
    }
    if (isset($_REQUEST['topicsource'])) {
        mct_ai_topicsource();
        return;
    }
    if (isset($_REQUEST['deltopic'])) {
        mct_ai_removepage();
        return;
    }
    mct_ai_mainpage();
    return;
}

function mct_ai_editsource() {
    ?>
    <script type='text/javascript'>
    window.location.assign('link-manager.php');
    </script>
    <?php
    exit();
}
function mct_ai_firstpage() {
    //General Info page
    //Set up training page Link
    //Display other important links
    global $user_id, $wpdb, $mct_ai_optarray, $ai_topic_tbl, $ai_logs_tbl;
    
    $testmode = false;
    
    $imggood = plugins_url('thumbs_up.png',__FILE__);
    $imgbad = plugins_url('thumbs_down.png',__FILE__);
    $token = $mct_ai_optarray['ai_cloud_token'];
    //Any Topics?
    $sql = "SELECT topic_name, topic_sources
                    FROM $ai_topic_tbl
                    WHERE topic_status != 'Inactive'";
    $topics = $wpdb->get_results($sql, ARRAY_A);
    //Get training page link
    $page =  mct_ai_get_trainpage();
    if (!empty($page)) $trainpage = get_page_link($page->ID);
    $ruri = $_SERVER['REQUEST_URI'];
    $tpage = $ruri.'_alltopics';
    $spage = $ruri.'_sources';
    $logspage = $ruri.'_Logs';
    ?>
    <div class='wrap' >
        <?php //screen_icon('plugins'); ?>
        <h2>MyCurator Dashboard</h2> 
        <?php if (empty($token) || empty($topics)){ // Select header info?>
        <p> Welcome to MyCurator - lets get started!  First you'll paste in your API Key and Validate it.  Then our Setup Wizard will 
            take you through creating your first Topic of curated articles  and after a few minutes, MyCurator will start to find articles. 
            At any time, here or on other pages, click the Need Help? button to access a video and documentation link for more information. 
            <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
            <?php echo mct_ai_helpvideo('quick'); ?>
        <?php } else { ?>
        <p>The Dashboard provides the status of MyCurator's background processing.  It also shows you if anything is missing from your 
            server or PHP setup. <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
            <?php echo mct_ai_helpvideo('dash'); ?>
        <?php } //end header select ?>
        
        <div class="postbox-container" style="width:70%;">
            <?php
            //
            // If user is not an admin then just show some links for more information and exit
            //
           if (!current_user_can('manage_options')) { ?>
            <h3>Important Links</h3>
            <ul>
                    <li>- <a href="http://www.target-info.com/training-videos/" >Link to MyCurator Training Videos</a></li>
                    <li>- MyCurator <a href="http://www.target-info.com/documentation/" >Documentation</a></li>
                    <?php if (!empty($trainpage)) { ?>
                    <li>- <a href="<?php echo $trainpage; ?>" />Link to MyCurator Training Page on your site</a></li> <?php } ?>
            </ul>
            <h3>Continue Learning about MyCurator</h3>
            <ol>
                <li>View our <a href="http://www.target-info.com/training-videos/#curation" >Curation</a> training video to get some ideas.</li>
                <li>Review our <a href="http://www.target-info.com/training-videos/#notebook" >Notebooks</a> video and learn how to use this powerful tool.</li>
                <li>View our <a href="http://www.target-info.com/training-videos/#training" >Training</a> video and learn how to optimize MyCurator's classification ability.</li>
                <li>Review our <a href="http://www.target-info.com/training-videos/#getit" >Get It</a> video and learn how to use this bookmarklet to gather content.</li>
                <li>Check out the <a href="http://www.target-info.com/category/how-to/">How To</a> section on our Blog for tips and tricks.</li>
            </ol> 
            
            <?php 
            exit(); } 
            //
            // End not an admin 
            //
            // Installation Section for Token entry, get plan and check site requirements
            //
            $plan_err = '';
            if (isset($_POST['Submit'])){
                check_admin_referer('mct_ai_firstpage','dashboard');
                if (empty($_POST['ai_cloud_token'])) {
                    $token = '';
                    $mct_ai_optarray['ai_plan'] = '';  
                } else {
                    $token = trim(sanitize_text_field($_POST['ai_cloud_token']));
                }
                $mct_ai_optarray['ai_cloud_token'] = $token;
                update_option('mct_ai_options',$mct_ai_optarray);  //need to set options as getplan won't on empty key.
                $goodplan = mct_ai_getplan(true);
                echo "<script type='text/javascript'>
        window.location.reload(true) //=document.location.href;
        </script>"; //Force page refresh so that menu items reflect errors or success
            }
            
            ?>
            <h3>Installation Status</h3>
            <p>You must enter a valid API Key for MyCurator to operate, we also check all needed components and versions in this section.  Resolve all errors before proceeding any further.</p>
            <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI'] ); ?>">
                Enter the API Key to Access Cloud Services: 
                  <?php wp_nonce_field('mct_ai_firstpage','dashboard'); ?>
                  <input name="ai_cloud_token" type="text" id="ai_cloud_token" size ="50" value="<?php echo $token; ?>"  />
                  <input name="Submit" type="submit" value="Validate & Get Plan" class="button-primary" />
             </form> 
             <p><?php
             if (empty($token)) {
                 echo "<p>Paste your cloud services API Key into the field above and click Validate & Get Plan."
                 . " To get an API Key, sign up for a free plan or a trial at the <a href='https://www.target-info.com/pricing/' target='_blank' >MyCurator Site</a>.  "
                         . " You will then recieve an email with your API Key.</p>";
                 mct_ai_vercheck(); 
                 echo "</p><hr>";
                 exit(); //No other info until we have a key
             }
             $goodplan = mct_ai_getplan(); 
             $plan_display = '';
             if (strlen($token) < 32) echo "<img src='$imgbad' ></img> - API Key is too short, should be 32 characters.  Check 1st and last 3 characters to see if any are missing.";
             else if (strlen($token) > 32) echo "<img src='$imgbad' ></img> -API Key is too long, should be 32 characters.  Check 1st and last 3 characters to see if there are extra ones added.";
             else echo $plan_display = mct_ai_showplan(true, false);
             if (stripos($plan_display,'trial')) {
                 ?><p>Make sure you <a href="https://www.target-info.com/myaccount/?token=<?php echo $token; ?>" >Purchase a Pro or Business Plan </a>
                 before your Trial Period Ends!</p><?php
             }
             mct_ai_vercheck(); 
             echo "</p><hr>";
             if (!$goodplan) exit();
             //
             // Endo of installation section
             //
             //
             // Start Setup Section
             //
             //Any topics, Sources, Topic/Source Connects?
            $args = array(
                    'hide_invisible' => false
                );
            $sources = get_bookmarks($args);
            $cnxions = 0;
            $dostatus = false;
            foreach ($topics as $topic) {
                if (!empty($topic['topic_sources'])) $cnxions += 1;
            }
             
             if (!empty($sources) && !empty($topics) && $cnxions && !$testmode) {
                 echo "<h3>Setup Checklist</h3>";
                 if (count($topics) == 1) $tstr = '1 Topic';
                 else $tstr = count($topics).' Topics';
                 $tstr = '<a href="'.$tpage.'" >'.$tstr.'</a>';
                 if (count($sources) == 1) $sstr = '1 Source';
                 else $sstr = count($sources).' Sources';
                 $sstr = '<a href="'.$spage.'" >'.$sstr.'</a>';
                 echo "<p><img src='$imggood' ></img> Your setup is ready to go.  You have ".$tstr.' and '.$sstr.'. '
                         . "Check the Processing Status section below to see "
                         . "when articles are posted.";
                 $dostatus = true;
             } else if(empty($topics) || $testmode) {
                //Set up Wizard to create first Topic, Sources and connect them
                $error_flag = false;
                $getituri = $_SERVER['REQUEST_URI'].'_getit';
                
                if (isset($_POST['Wizard']) ) {
                    if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
                    check_admin_referer('mct_ai_wizard','wizardpg'); 
                    $topic_name = trim(sanitize_text_field($_POST['topic_name']));
                    $edit_vals = array (
                        'topic_name' => $topic_name,
                        'topic_type' => 'Relevance',
                        'topic_status' => 'Training', 
                        'topic_slug' => sanitize_title($topic_name),
                        'topic_search_1' => trim(sanitize_text_field(stripslashes($_POST['topic_search_1']))),
                        'topic_search_2' => trim(sanitize_text_field(stripslashes($_POST['topic_search_2']))),
                        'topic_exclude' => '',
                        'topic_skip_domains' => '',
                        'topic_cat' => strval(absint($_POST['topic_cat'])),
                        'topic_tag' => '',
                        'topic_tag_search2' =>  0,
                        'opt_post_user' => 'Not Set',
                        'opt_image_filter' => 0,
                        'opt_post_ctype' =>  'not-selected' ,
                        'opt_post_ctax' => '',
                        'opt_post_ctaxval' => '',
                        'opt_topic_start'  => '',
                        'opt_topic_end'  => '',
                        'topic_min_length' => 20
                    );
                    //Validate fields
                    if ($topic_name == '') {
                        $msg = 'Must have a Topic Name';
                        $error_flag = true;
                    } else {
                        if (preg_match('{^[-\p{L}\p{N}\s]+$}u',$topic_name) != 1) {
                            $error_flag = true;
                            $msg = "Topic Name may not contain special characters, just letters, - and numbers. ";
                        }
                    }
                    $srch1 = $edit_vals['topic_search_1'];
                    if (empty($srch1)) {
                        $msg = 'Must have Topic Search 1 Keywords';
                        $error_flag = true;
                    }
                    if (!$error_flag) {
                        //Set up Source Group
                        $theterm = get_term_by('name','Sources', 'link_category', ARRAY_A);
                        if (!$theterm) $theterm = wp_insert_term('Sources','link_category');
                        $link_category[] = $theterm['term_id'];
                        //Set up Sources
                        $srch1 = str_replace(',',' ',$srch1);  //replace , with space
                        $pos = stripos($srch1,' '); //title string gets first word
                        if ($pos) $ttlstr = substr($srch1,0,$pos);
                        else $ttlstr = $srch1;
                        $srchterm = rawurlencode($srch1); 
                        $args = array (
                            'feed_name' => 'Google News - '.$ttlstr,
                            'link_category' => $link_category,
                            'newlinkcat' => ''
                        );
                        $args['rss-url'] = 'https://news.google.com/rss/search/section/q/'.$srchterm.'/'.$srchterm.'?hl=en&gl=US&ned=us'; //Google news feed
                        $args['save-url'] = 'https://news.google.com/';
                        mct_ai_postlink($args);
                        $args['feed_name'] = 'Bing Search - '.$ttlstr;
                        $args['rss-url'] = 'https://www.bing.com/news/search?q='.$srchterm.'&format=rss&qft=interval%3d%228%22+sortbydate%3d%221%22'; //Bing Search string
                        $args['save-url'] = 'https://www.bing.com/';
                        mct_ai_postlink($args);
                        //Create Topic
                        $edit_vals['topic_sources'] = strval($theterm['term_id']);
                        //Compress option fields
                        $edit_vals['topic_options'] = maybe_serialize(array(
                            'opt_post_user' => $edit_vals['opt_post_user'],
                            'opt_image_filter' => $edit_vals['opt_image_filter'],
                            'opt_post_ctype' => $edit_vals['opt_post_ctype'],
                            'opt_post_ctax' => $edit_vals['opt_post_ctax'],
                            'opt_post_ctaxval' => $edit_vals['opt_post_ctaxval'],
                            'opt_topic_start'  => $edit_vals['opt_topic_start'],
                            'opt_topic_end'  => $edit_vals['opt_topic_end']
                            ));
                        //unset opt fields not in db
                        unset($edit_vals['opt_post_user']);
                        unset($edit_vals['opt_image_filter']);            
                        unset($edit_vals['opt_post_ctype']);
                        unset($edit_vals['opt_post_ctax']);
                        unset($edit_vals['opt_post_ctaxval']);
                        unset($edit_vals['opt_topic_start']);
                        unset($edit_vals['opt_topic_end']);
                        //Do an insert
                        $wpdb->insert($ai_topic_tbl, $edit_vals);
                        //Add the new topic to the taxonomy database for the Target custom posts
                        wp_insert_term($topic_name,'topic');
                        //Schedule Cron 
                        mct_ai_schedcron();
                        echo "<script type='text/javascript'>
                        window.location.reload(true) //=document.location.href;
                        </script>"; //Force page refresh so that menu items reflect errors or success
                    } //End if !$error_flag
                }
                if (!isset($edit_vals)) {
                    $edit_vals = array (
                        'topic_name' => '',
                        'topic_search_1' => '',
                        'topic_search_2' => '',
                        'topic_cat' => ''
                    );
                }
                //Set up cat dropdown
                $cats = array (
                    'orderby' => 'name',
                    'hide_empty' => FALSE,
                    'hierarchical' => true,
                    'name' => 'topic_cat'
                ); 
                if (!empty($edit_vals['topic_cat'])) $cats['selected'] = $edit_vals['topic_cat'];
                echo "<div class='wrap'><h3>Setup Wizard</h3>";
                if ($error_flag) echo '<div id="message" class="error" ><p><strong>'.$msg.'</strong></p></div>';
                ?>
             <p>Create your first Topic by filling in the fields below telling MyCurator what types of articles you want to curate.
                MyCurator will also create 2 Sources, a Bing Search and a Google News search, that will deliver articles with the keywords 
                you have entered for the Topic. You can skip this Setup Wizard by creating a Topic using the Topics menu item and the Wizard will disappear. 
                </p>
             <p>After you have Created the Topic a Processing Status section will show up here telling you when
                MyCurator background processing is checking for articles.  You can find new articles in the Training Posts menu item (usually below Comments
             in your dashboard) when articles have been found.</p>
             <p>Use spaces between each keyword.  Enclose a phrase in quotes where each word must be present together in that order.
                (* required)</p>
                <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>"> 
                    <style>fieldset {-webkit-padding-after: 1em;}</style>
                    <fieldset>
                        <legend>*Topic Name - The type of articles are you looking for eg: Energy or Camping or Digital Marketing</legend>
                        <input name="topic_name" type="input" id="topic_name" size="100" maxlength="200" value="<?php echo $edit_vals['topic_name']; ?>"  />   
                    </fieldset>
                    <fieldset>
                        <legend>*Topic Search 1 Keywords - Each of these keywords <strong>Must</strong> be in each article eg: energy. Fewer of these keywords means more articles.</legend>
                        <input name="topic_search_1" type="input" id="topic_search_1" size="100" value="<?php echo esc_attr($edit_vals['topic_search_1']); ?>"  />
                    </fieldset>
                    <fieldset>
                        <legend>Topic Search 2 Keywords - Only <strong>One</strong> of these words needs to be in the article eg: alternative solar wind battery "fuel cell". More (or none) of these keywords means more articles.</legend>
                        <textarea name="topic_search_2" id="topic_search_2" rows="5" cols="100" ><?php echo esc_attr($edit_vals['topic_search_2']); ?></textarea>  
                    </fieldset>
                    <fieldset>
                        <legend>*Assign Curated Articles to Which WordPress Category?</legend>
                        <?php wp_dropdown_categories($cats); ?>    
                    </fieldset>
                
                <?php wp_nonce_field('mct_ai_wizard','wizardpg'); ?>
                <div class="submit">
                    <input name="Wizard" type="submit" value="Create Topic" class="button-primary" /></div>
                </form>
                </div>
                 <?php
                 echo "<p><img src='$imggood' ></img> You can add content now with our Get It tool that captures"
                         . " articles when you are viewing them through a browser on your computer or mobile device.  Just drag it from the ";
                 echo ("<a href='$getituri' >Get It and Source It</a> menu item ");
                 echo " to your browser's bookmarks bar.  See our <a href='https://www.target-info.com/documentation-2/documentation-get-it/' target='_blank' >Get It Documentation</a> "
                         . " or <a href='https://www.target-info.com/training-videos/#getit' target='_blank'>Videos</a></p>.";
                 exit();
             } else {
                 echo "<h3>Setup Checklist</h3>";
                 echo "<p>Get started with our <a href='https://www.target-info.com/training-videos/#quick' target='_blank' >Quick Start Video</a> "
                 . "to set up your Sources and Topics and link them together.  The checklist below will tell you when each step is complete.";
                 if (empty($sources)) {
                    echo "<p><img src='$imgbad' ></img> You need to enter some Sources (RSS feeds) which MyCurator can read to find articles.  "
                            . " Use our Source It tool to capture an RSS feed from a site whose articles you would like to curate."
                            . " You can also create News & Twitter feeds and Google Alerts with our Add News link in our Sources menu item."
                            . " Find out more about the Source It bookmarklet with our <a href='https://www.target-info.com/documentation-2/documentation-source-it/' target='_blank'>Source It Documentation</a> "
                            . " or our <a href='https://www.target-info.com/training-videos/#sourceit' target='_blank'>Source It Video</a>.</p>"
                            . "<p>Sometimes Source It can't find the feed, so you can manually add the specific feed URL that "
                            . " you find on a site.  For more information on this and Sources in general, see our  "
                            . " <a href='https://www.target-info.com/documentation-2/documentation-sources/' target='_blank'>Sources Documentation</a> "
                            . " or our <a href='https://www.target-info.com/training-videos/#sources' target='_blank'>Manually Add Sources Video</a>.";
                 } else {
                    echo "<p><img src='$imggood' ></img> Sources: ".count($sources)." entered.  Go to the Sources menu item to view/edit your feeds."; 
                 }
                 if (empty($topics)) {
                    echo "<p><img src='$imgbad' ></img> - Topics tell MyCurator what to look for when reading your Sources (RSS feeds) for articles."
                            . " Use the Topics menu item to create a Topic.  Help in creating Topics can be found in our <a href='https://www.target-info.com/documentation-2/documentation-topics/' target='_blank'>Topics Documentation</a> "
                            . " or our <a href='https://www.target-info.com/training-videos/#topics' target='_blank'>Topics Video</a>.</p>";
                 } else {
                    echo "<p><img src='$imggood' ></img> Topics: ".count($topics)." entered,"; 
                 }
                 if (!$cnxions) {
                    echo "<p><img src='$imgbad' ></img> - The final step is to link Your Sources to your Topics.  This allows you to customize which Sources will provide the best "
                            . " articles for your Topic.  In the Topic page where you entered the Topic information, you will see checkboxes for your Sources near the botton "
                            . " of the page. Just check those to use for the Topic and Save the Topic.  You can also use our Sources menu item to add or change Sources linked to Topics.</p>";
                 }
                 echo "<p><img src='$imggood' ></img> You can add content now even without a complete set up with our Get It tool.  Just drag it from the Get It and Source It menu item "
             . "to your browser's bookmarks bar.  See our <a href='https://www.target-info.com/documentation-2/documentation-get-it/' target='_blank' >Get It Documentation</a> "
                         . "or <a href='https://www.target-info.com/training-videos/#getit' target='_blank'>Get It Videos</a>.";
             }
             if ($dostatus) {
                 echo "<hr>";
                 //
                 // End Setup Section
                 //
                 //
                 // Start Process Status section
                 //
                 echo "<h3>Processing Status</h3>";
                 if (empty($mct_ai_optarray['ai_on'])) {
                     echo "<p><img src='$imgbad' ></img> - MyCurator background processing is not turned on.  No articles will be posted automatically, only the Get It tool"
                     . "will be able to post content.  To turn on MyCurator processing go to the Options menu and check Turn on MyCurator Background Process?</p>";
                     // No more display
                 } else {
                     $proc_time = wp_next_scheduled('mct_ai_cron_process');
                     $rqst_time = wp_next_scheduled('mct_ai_cron_rqstproc');
                     if ($proc_time === false) {
                         mct_ai_schedcron();
                         $proc_time = wp_next_scheduled('mct_ai_cron_process');
                         $rqst_time = wp_next_scheduled('mct_ai_cron_rqstproc');
                     }
                     //Any Requests?
                     $sql = "SELECT Count(logs_type)  
                            FROM $ai_logs_tbl WHERE logs_type = '".MCT_AI_LOG_REQUEST ."'";
                    $rqst_cnt = $wpdb->get_var($sql);
                    $since_proc = mct_ai_timesince($proc_time - time());
                    $since_rqst = mct_ai_timesince($rqst_time - time());
                    if ($proc_time == false) {
                        echo "<p><img src='$imgbad' ></img> - MyCurator can't schedule its WP CRON job for background processing - contact MyCurator support at support@target-info.com.  </p> ";
                    //If cron is past its schdule time + 5 minuts, something is wrong
                    } else if ($proc_time < time()- 300) {
                        echo "<p><img src='$imgbad' ></img> - MyCurator background process is past its scheduled time.  This usually means the WordPress CRON is not working. </p> ";
                        echo "<ol>";
                        if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON){
                            echo "<li>WordPress CRON is disabled.  This is OK if you are using a Server Cron."
                                    . " If not, you should comment out the line define('DISABLE_WP_CRON', 'true'); in your wp-config.php"
                                    . " to turn WordPress CRON back on.  Then refresh this Dashboard page periodically over a few minutes to see if the background "
                                    . " process is rescheduled to a future time (which means MyCurator is processing again).</li>";
                        }
                        echo "<li>If your site is new or has low traffic, CRON will not be triggered often (it is triggered by your visitors).  To see if this is the case"
                        . " try refreshing this page a couple of times over the course of a few minutes to see if the background process will start. "
                                . " You can set up a Server Cron to get around this - see this article for details: "
                                . " <a href='https://holisticwp.com/blog/replace-wordpress-cron-with-real-cron-job/74' >Getting Cron to run on time</a></li>";
                        echo "<li>Some Hosts will block the WordPress CRON.  You can ask your host if this is the case and if so, will they unblock it.  </li>";
                        echo "<li>If you or your tech support or a plugin have made changes to .htaccess, that can cause CRON problems.  Try to reverse them "
                        . " (or deactivate the plugin that made them) and see if CRON starts up (by refreshing this page periodically). </li>";
                        echo "</ol>";
                    } else {
                        echo "<table class='widefat' ><tbody>";
                        echo "<tr><td>MyCurator checks for new articles in: </td>"
                        . "<td class='mct-stat-col2'>$since_proc</td></tr>";
                        if ($rqst_cnt) {
                            echo "<tr><td>Retrieve ".strval($rqst_cnt). " Requested Articles in: </td>"
                        . "<td class='mct-stat-col2'>$since_rqst</td></tr>";
                        }
                        echo "</tbody></table>";
                        $scheds = wp_get_schedules();
                        if (!isset($scheds['mct3hour'])) {
                            //Our schedules are being wiped out, so notify user
                            echo "<p><img src='$imgbad' ></img> - MyCurator Cron Schedules are being removed, so MyCurator can only run every 12 hours.  "
                                    . " This is usually because another plugin is not updating Cron schedules correctly.  If you want MyCurator to run more often, try deactivating "
                                    . " plugins that use a schedule to run and see if this message goes away.  If so, notify that plugin author that Cron schedules are not being updated"
                                    . " correctly and see if they will fix it.";
                        }
                        $logsuri = $_SERVER['REQUEST_URI'].'_Logs';
                        //Get Logs data for last 24 hours
                        $back = time() - 60*60*24;
                        $sql = "SELECT logs_type, count(`logs_id`) as cnt
                        FROM $ai_logs_tbl WHERE logs_type != '".MCT_AI_LOG_PROCESS. "' AND logs_proc_id > '".$back."' 
                        GROUP BY logs_type ASC";
                        $logs = $wpdb->get_results($sql, ARRAY_A);
                        if ($logs) {
                            echo "<h3>Items Processed by MyCurator in the last 24 hours.</h3>";
                            echo "<table class='widefat' ><tbody>";
                            foreach ($logs as $log) {
                                echo "<tr><td><a href='".$logspage."&type=".$log['logs_type']."'>".$log['logs_type']."</a></td>";
                                echo "<td>".$log['cnt']."</td></tr>";
                            }
                            echo "</tbody></table>";
                            echo '<h4><a href="'.admin_url('edit.php?post_type=target_ai').'">View Articles Found in your Training Posts</a></h4>';
                            echo "<p><strong><a href='".admin_url('edit.php?post_type=target_ai')."'>Article</a></strong> - A count of Articles found by MyCurator.  Some may not have been posted to your Training Posts.  Go to the "
                            . "<a href='".$logsuri."' >Logs Menu</a> item to see messages about which articles haven't been posted and the reason.</p>";
                            echo "<p><strong><a href='".$logspage."&type=ERROR'>Error</a></strong> - The count of errors processing articles or sources.</p>";
                            echo "<p><strong><a href='".$logspage."&type=REQUEST'>Request</a></strong> - These articles have been requested from our Cloud Service.  The processing status above tells when the Requested Articles will be retrieved. </p>";
                            echo "<p>Go to the Performance menu item to see all MyCurtor Activity by Topic and Source for the last "
                            .$mct_ai_optarray['ai_log_days']." days.</p>";
                        }
                    } //End if past scheduled time
                 } //End if 'ai_on'
             }
            //
            // End Status
            //
            // Links box
             ?>
            
        </div>
        <div class="postbox-container" style="width:20%; margin-top: 35px; margin-left: 15px;">
                <div class="metabox-holder">	
                        <div class="meta-box-sortables">

                                <div id="breadcrumbslike" class="postbox">
                                        <div class="handlediv" title="Click to toggle"><br /></div>
                                        <h3 class="hndle"><span><?php echo "Important Links";?></span></h3>
                                        <div class="inside">
                                                <ul>
                                                        <li>- <a href="http://www.target-info.com/training-videos/" >Link to MyCurator Training Videos</a></li>
                                                        <li>- MyCurator <a href="http://www.target-info.com/documentation/" >Documentation</a></li>
                                                        <li>- MyCurator <a href="http://wordpress.org/support/plugin/mycurator" >support forum</a></li>
                                                        <li>- MyCurator <a href="mailto:support@target-info.com" >Support Email</a></li>
                                                        <?php if (empty($mct_ai_optarray['ai_cloud_token'])) { ?>
                                                        <li>- MyCurator API Key: <a href="http://www.target-info.com/pricing/" />Get API Key</a></li><?php } ?>
                                                        <li>- <a href="http://www.target-info.com/myaccount/?token=<?php echo $token; ?>" >My Account</a> at Target Info</li>
                                                        <?php if (!empty($trainpage)) { ?>
                                                        <li>- <a href="<?php echo $trainpage; ?>" />Link to MyCurator Training Page on your site</a></li> <?php } ?>
                                                </ul>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
    </div>
<?php
}

function mct_ai_checkpage($page){
    //Checks if page is up on target-info.com site
    $response = wp_remote_head("http://www.target-info.com/".$page, array('timeout' => 1));
    if (is_wp_error($response)) return false;
    if ($response['response']['code'] == 404) return false;
    return true;
}

function mct_ai_showfeed($url, $cnt){
    //echos the title for a feed at url, showing cnt entries
    $rss=fetch_feed($url);
    if (is_wp_error($rss)) return;  //nothing to show
    $rss_items = $rss->get_items(0,$cnt);
    echo "<ul>";
    foreach ($rss_items as $item){
        $title = $item->get_title();
        $link = $item->get_permalink();
        echo "<li><a href='$link' >$title</a></li>\n";
    }
    echo "</ul>";
}

function mct_ai_vercheck() {
    //Display version of install and whether requirements met
    
    //get image links
    $imggood = plugins_url('thumbs_up.png',__FILE__);
    $imgbad = plugins_url('thumbs_down.png',__FILE__);
    $allgood = true;
    echo "<ul>";
    if  (in_array  ('curl', get_loaded_extensions())){
        //echo "<li><img src='$imggood' ></img> - PHP Curl Installed";
    } else {
        echo "<li><img src='$imgbad' ></img> - PHP Curl NOT Installed - MyCurator will not work without it, contact your host provider to have Curl installed!";
        $allgood = false;
    }
    $version = floatval(phpversion());
    if ($version >= 5.2) {
       // echo "<li><img src='$imggood' ></img> - PHP Version ".strval($version)." is OK";
    } else {
        echo "<li><img src='$imgbad' ></img> - PHP Version ".strval($version)." NOT 5.2 or Greater - MyCurator not tested with this version";
        $allgood = false;
    }
    $version = floatval(get_bloginfo('version'));
    if ($version >= 3.2) {
       // echo "<li><img src='$imggood' ></img> - Wordpress Version ".strval($version)." is OK";
    } else {
        echo "<li><img src='$imgbad' ></img> - Wordpress Version ".strval($version)." NOT 3.2 or Greater - MyCurator not tested with this version";  
        $allgood = false;
    }
    $furl = ini_get('allow_url_fopen');
    if (strtolower($furl) != 'on' && $furl != '1' && strtolower($furl) != 'true') {
        echo "<li><img src='$imgbad' ></img> - PHP Needs allow_url_fopen turned On in PHP.ini  - MyCurator Images & Source It will not work without it, contact your host provider to have allow_url_fopen turned on in PHP.ini! Currently set to: ".$furl;
        $allgood = false;
    }
    if ($allgood) echo "<li><img src='$imggood' ></img> - WordPress and PHP versions and components are all good!";
    echo "</ul>";
}
function mct_ai_mainpage() {
    //Creates the All Topics list, with topic name and sources highlighted as links for editing
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray;
    
    // run ai process?
    if (isset($_POST['run_ai'])){
        if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
        check_admin_referer('mct_ai_runai','runaiclick');  
        //Get Values from Db
        $sql = "SELECT `topic_name`, `topic_id` FROM $ai_topic_tbl";
        $topics = $wpdb->get_results($sql, ARRAY_A);
        $names = array();
        if (!empty($topics)) {
            foreach ($topics as $topic) {
                if (!empty($_POST['runai-'.$topic['topic_id']])) $names[] = $topic['topic_name'];
            }
        }
        if (!empty($mct_ai_optarray['ai_page_rqst'])) mct_ai_process_request(); //Get Requests
        if (!empty($names)) mct_ai_process_site(false, $names);
        echo '<div class="wrap">';
        echo '<h2>MyCurator Process Completed</h2></div>';
        exit;
    }
    //Set up prefixes for links
    $ruri = $_SERVER['REQUEST_URI'];
    $editpage = $ruri."&newtopic&edit=";
    $newpage = $ruri."&newtopic";
    $sourcepage = $ruri."&topicsource&edit=";
    $delpage = $ruri."&deltopic";
    //Get Values from Db
    $sql = "SELECT `topic_name`, `topic_status`, `topic_type`, `topic_cat`, `topic_tag`, `topic_sources`, `topic_options`, `topic_id`
            FROM $ai_topic_tbl ORDER BY topic_name";
    $edit_vals = $wpdb->get_results($sql, ARRAY_A);
    mct_ai_getplan(); 
    //render the page
    ?>
    <div class='wrap'>
    <?php //screen_icon('plugins'); ?>
    <h2>MyCurator Topic List</h2> 
    <p>Each of the MyCurator topics are listed below.  Each Topic defines a filter of keywords that focus your search for articles and information on an area of interest you wish to follow. 
        Each Topic also has one or more Sources where it will find the news, articles and posts to search for your topic. 
        <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
    <?php echo mct_ai_helpvideo('topics'); ?>
        <p><strong>Click the Title</strong> to view or change any topic.  <strong>Click the Source Group</strong> field to assign or update Sources.
    Make sure you click on any <span style="background-color: #FFFF00">No Sources Assigned</span> links and assign Source Groups or MyCurator will not find articles for that Topic.</p>
    <form name="manual_ai" method="post" >
        <p><input name="run_ai" type="hidden" value="run_ai" /></p>
        <?php wp_nonce_field('mct_ai_runai','runaiclick'); ?>
        <input name="run_ai_button" value="Run AI Process" type="submit" class="button-secondary">
        &nbsp;&nbsp;Manually run the process to gather content.  You can clear the Process checkbox on topics that you do not wish to manually process. 
        All outstanding page requests will also be processed (if you are using Page Request Mode).
        <br /><p>
    <?php if (mct_ai_showplan(false)) echo('<a href="'.$newpage.'" >Click Here</a> to Add a <strong>New Topic</strong>&nbsp&nbsp&nbsp'); 
          echo ('<a href="'.$delpage.'" >Click Here</a> to <strong>Delete or Rename</strong> a Topic</p>');?>
        <table class="widefat" >
            <thead>
                <tr>
                <th>Topic</th>
                <th>Type</th>
                <th>Status</th>
                <th>Category or Post Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Source Groups</th>
                <th class="column-cb check-column">Process
                    <input id="cb-select-all" type="checkbox" checked="checked">
                </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($edit_vals as $row){
                $row = mct_ai_get_topic_options($row);
                $sources = array();
                if (!empty($row['topic_sources'])) $sources = array_map('trim',explode(',',$row['topic_sources']));
                //Custom Type?
                if (!empty($row['opt_post_ctype']) && $row['opt_post_ctype'] != 'not-selected' ) {
                    $ctype = get_post_type_object($row['opt_post_ctype']);
                    $catstr = $ctype->labels->singular_name;
                } else {
                    $catstr = get_cat_name($row['topic_cat']);
                }
                echo('<tr>');
                echo('<td><a href="'.$editpage.trim($row['topic_name']).'" >'.$row['topic_name'].'</a></td>');
                echo('<td>'.$row['topic_type'].'</td>');
                echo('<td>'.mct_ai_status_display($row['topic_status'],'display').'</td>');
                echo('<td>'.$catstr.'</td>');
                echo('<td>'.$row['opt_topic_start'].'</td>');
                echo('<td>'.$row['opt_topic_end'].'</td>');
                if (count($sources) < 1){
                    $source_fld = '<span style="background-color: #FFFF00">No Sources Assigned</span>';
                } else {
                    $source_fld = '';
                    foreach ($sources as $src){
                        $term = get_term($src,'link_category');
                        if (empty($source_fld)) {
                            $source_fld = $term->name;
                        } else {
                            $source_fld .= ', '.$term->name;
                        }
                    }
                    if (strlen($source_fld > 40)){
                        $source_fld = substr($source_fld,0,40).'...';
                    }
                }
                echo('<td><a href="'.$sourcepage.trim($row['topic_name']).'" >'.$source_fld.'</a></td>');
                //Run AI checkbox
                echo '<td class="check-column"><input name="runai-'.$row['topic_id'].'" type="checkbox" value="1" checked="checked"  /></td>';
                echo('</tr>');
            } ?>
           </tbody>
        </table>
     </form>
    <?php echo mct_ai_showplan(true,true); ?>
    </div>
<?php
}

function mct_ai_topicpage() {
    //This function creates the New/Edit topic page
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray;

    //Initialize some variables
    $pagetitle = 'New Topic';
    $update_type = 'false';  //set up for insert
    $msg = '';
    $topic_name = '';
    $createcat = '';
    $error_flag = false;
    $edit_vals = array();
    $do_report = false;
    $no_more = false;
    $custom_types = array();
    //Create go back link
    $ruri = $_SERVER['REQUEST_URI'];
    $pos = stripos($ruri,"_alltopics");
    $backpage = '<p><a href="'.substr($ruri,0,$pos) .'_alltopics" >Click Here to go Back to Topics List Page</a></p>';
    
    //Set up user login dropdown
    $authusers = get_users(array('role' => 'author'));
    $editusers = get_users(array('role' => 'editor'));
    $moreusers = get_users(array('role' => 'administrator'));
    $notset = new stdClass;
    $notset->user_login = "Not Set";
    $allusers = array_merge(array($notset),$authusers,$editusers,$moreusers);

    //Set up cat/tag dropdown
    $cats = array (
        'orderby' => 'name',
        'hide_empty' => FALSE,
        'hierarchical' => true,
        'name' => 'topic_cat'
    );
    $tags = array (
        'orderby' => 'name',
        'name' => 'topic_tag',
        'hide_empty' => FALSE,
        'show_option_none' => 'No Tags',
        'taxonomy' => 'post_tag'
    );
    //Get custom types/taxonomies
    if (!empty($mct_ai_optarray['ai_custom_types'])) {
        $custom_types = maybe_unserialize($mct_ai_optarray['ai_custom_types']);
        $custom_types = array_reverse($custom_types, true);
        $custom_types['not-selected'] = '';
        $custom_types = array_reverse($custom_types, true);
    }
    //Set up topic sources - get all link categories
    $taxname = 'link_category';
    $terms = get_terms(array('taxonomy' => 'link_category', 'hide_empty' => false));
    //Check if submit
    if (isset($_POST['Submit']) ) {
        if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
        check_admin_referer('mct_ai_topicpg','topicpg'); 
        // Get the post values and sanitize
 
        //Clean up domains
        $valid_str = '';
        $varray = $_POST['topic_skip_domains'];
        $varray = str_replace(" ","\n",$varray); //change spaces to line breaks
        $varray = explode("\n",$varray);
        foreach ($varray as $vstr){
            $vstr = trim(sanitize_text_field($vstr));
            if (strlen($vstr) != 0){
                $valid_str .= $vstr."\n";
            }
        }
        $valid_str = trim($valid_str);
        //Check for topic sources
        $tsource = '';
        if (!empty($_POST['sourceChk'])){
            $tsource = implode(',',$_POST['sourceChk']);
        }
        $edit_vals = array (
            'topic_type' => trim(sanitize_text_field($_POST['topic_type'])),
            'topic_status' => mct_ai_status_display(trim($_POST['topic_status']),'db'), 
            'topic_search_1' => trim(sanitize_text_field(stripslashes($_POST['topic_search_1']))),
            'topic_search_2' => trim(sanitize_text_field(stripslashes($_POST['topic_search_2']))),
            'topic_exclude' => trim(sanitize_text_field(stripslashes($_POST['topic_exclude']))),
            'topic_skip_domains' => $valid_str,
            'topic_cat' => strval(absint($_POST['topic_cat'])),
            'topic_tag' => strval(absint($_POST['topic_tag'])),
            'topic_tag_search2' => strval(absint((isset($_POST['topic_tag_search2']) ? $_POST['topic_tag_search2'] : 0) )),
            'topic_sources' => $tsource,
            'opt_post_user' => $_POST['opt_post_user'],
            'opt_image_filter' => strval(absint((isset($_POST['opt_image_filter']) ? $_POST['opt_image_filter'] : 0) )),
            'opt_post_ctype' => (!empty($_POST['opt_post_ctype']) ? $_POST['opt_post_ctype'] : 'not-selected') ,
            'opt_topic_start'  => trim(sanitize_text_field($_POST['opt_topic_start'])),
            'opt_topic_end'  => trim(sanitize_text_field($_POST['opt_topic_end'])),
            'topic_min_length' => strval(absint($_POST['topic_min_length']))
        );        
        //Set the taxonomy based on the custom type chosen
        if (array_key_exists($edit_vals['opt_post_ctype'],$custom_types)) {
            $edit_vals['opt_post_ctax'] = $custom_types[$edit_vals['opt_post_ctype']];
            if (!empty($edit_vals['opt_post_ctax']) && $_POST[$edit_vals['opt_post_ctax']] != '-1') {
                $theterm = get_term($_POST[$edit_vals['opt_post_ctax']], $edit_vals['opt_post_ctax']);
                $edit_vals['opt_post_ctaxval'] = $theterm->slug;
            } else { 
                $edit_vals['opt_post_ctaxval'] = '';
            }
        } else {
            $edit_vals['opt_post_ctax'] = '';
            $edit_vals['opt_post_ctaxval'] = '';
        }
        // Get category create name
        $createcat = trim(sanitize_text_field((isset($_POST['topic_createcat']) ? $_POST['topic_createcat'] : '')));
        //Get the topic name and validate
        $topic_name = $edit_vals['topic_name'] = trim(sanitize_text_field($_POST['topic_name']));
        if ($topic_name == '') {
            $msg = 'Must have a Topic Name';
            $error_flag = true;
        } else {
            if (isset($mct_ai_optarray['ai_utf8']) && $mct_ai_optarray['ai_utf8']) {
                if (preg_match('{^[-\p{L}\p{N}\s]+$}u',$topic_name) != 1) $error_flag = true;
            } else {
                if (preg_match('{^[-a-zA-Z0-9\s]+$}',$topic_name) != 1) $error_flag = true;
            }
            if ($error_flag) $msg = "Topic Name may not contain special characters, just letters, - and numbers. ";
        }
        //Validate topic start/end dates
        $strt_val = $edit_vals['opt_topic_start'];
        if (!empty($strt_val) && !checkmydate($strt_val)) {
            $error_flag = true;
            $msg .= " Invalid Topic Start Date: ".$edit_vals['opt_topic_start']." (Make sure in mm/dd/yy format)";
        } elseif (!empty($strt_val)) {
                $strt = strtotime($strt_val);
        }
        $end_val = $edit_vals['opt_topic_end'];
        if (!empty($end_val) && !checkmydate($end_val)) {
            $error_flag = true;
            $msg .= " Invalid Topic End Date: ".$edit_vals['opt_topic_end']." (Make sure in mm/dd/yy format)";
        } elseif (!empty($end_val) && !empty($strt)){
            if (strtotime($end_val) < $strt) {
                $error_flag = true;
                $msg .= " Topic End Date Before Start Date ";
            }
        }  
        
        if (!$error_flag) {
            //Create Slug if needed
            if (empty($_POST['topic_slug'])){
                $topicslug = sanitize_title($topic_name);
            } else {
                $topicslug = $_POST['topic_slug'];
            }
            $edit_vals['topic_slug'] = $topicslug;
            //Save options into db field
            $edit_vals['topic_options'] = maybe_serialize(array(
                'opt_post_user' => $edit_vals['opt_post_user'],
                'opt_image_filter' => $edit_vals['opt_image_filter'],
                'opt_post_ctype' => $edit_vals['opt_post_ctype'],
                'opt_post_ctax' => $edit_vals['opt_post_ctax'],
                'opt_post_ctaxval' => $edit_vals['opt_post_ctaxval'],
                'opt_topic_start'  => $edit_vals['opt_topic_start'],
                'opt_topic_end'  => $edit_vals['opt_topic_end']
                ));
            //unset opt fields not in db
            unset($edit_vals['opt_post_user']);
            unset($edit_vals['opt_image_filter']);            
            unset($edit_vals['opt_post_ctype']);
            unset($edit_vals['opt_post_ctax']);
            unset($edit_vals['opt_post_ctaxval']);
            unset($edit_vals['opt_topic_start']);
            unset($edit_vals['opt_topic_end']);
            if ($_GET['updated'] == 'true'){
                //Do an update
                $where = array('topic_name' => $topic_name);
                $wpdb->update($ai_topic_tbl, $edit_vals, $where);
                $msg = "Topic Updated";
            } else {
                //Insert, Create New Category if entered
                if (!empty($createcat)) {
                    $theterm = wp_insert_term($createcat,'category');
                    if (is_wp_error($theterm)){
                        $msg = $theterm->get_error_message();
                    } else {
                        $edit_vals['topic_cat'] = $theterm['term_id'];
                    }
                }
                //Do an insert
                $edit_vals['topic_name'] = $topic_name;
                $wpdb->insert($ai_topic_tbl, $edit_vals);
                if (empty($msg)) $msg = "Topic $topic_name Added";
                else $msg = "Topic $topic_name Added - ".$msg;
                //Add the new topic to the taxonomy database for the Target custom posts
                wp_insert_term($edit_vals['topic_name'],'topic');
                $edit_vals = '';
                $createcat = '';
                //Try to Schedule Cron as may be first Topoic
                mct_ai_schedcron();
            }
        }
    }
    if (isset($_GET['edit'])){
        //We came in on an edit call, so set up variables
        $tname = trim($_GET['edit']);
        $pagetitle = 'Edit Topic';
        $update_type = 'true'; //Means we have to do an update
        //Load values from db
        $sql = "SELECT `topic_name`, `topic_slug`, `topic_status`, `topic_type`, `topic_search_1`, `topic_search_2`, 
                `topic_exclude`, `topic_skip_domains`, `topic_min_length`, `topic_cat`, `topic_tag`, `topic_tag_search2`, `topic_sources`, `topic_options`
                FROM $ai_topic_tbl
                WHERE topic_name = '$tname'";
        $edit_vals = $wpdb->get_row($sql, ARRAY_A);
        //Set status dropdown
        $curstat = mct_ai_status_display($edit_vals['topic_status'],'display');
        $typ = $edit_vals['topic_type'];
        $status_vals = array (
            mct_ai_status_display('Inactive','display'),
            mct_ai_status_display('Training','display'),
            mct_ai_status_display('Active','display')
        );

        //Set up cat/tag dropdown
        $cats['selected'] = $edit_vals['topic_cat'];
        $tags['selected'] = $edit_vals['topic_tag'];
        //Set up Relevance report
        if ($typ == 'Relevance'  && $curstat != 'Inactive' && current_user_can('manage_options')){
            $rel = new Relevance();
            $rpt = $rel->report($tname);
            if (!empty($rpt)) $do_report = true;
            unset($rel);
        }
        //Set up sources checkboxes
        $sources = array_map('trim',explode(',',$edit_vals['topic_sources']));
        //Set up options into edit vals
        $edit_vals = mct_ai_get_topic_options($edit_vals);
    } else {
        //New topic, if error, don't reset values
        $curstat = mct_ai_status_display('Training','display');
        if (empty($edit_vals)){
            $edit_vals = array();
            $edit_vals['topic_type'] = 'Relevance';
            $edit_vals['topic_tag_search2'] = '1';  //Default to use as tags
            $edit_vals['topic_name'] = '';
            $edit_vals['topic_slug'] = '';
            $edit_vals['topic_search_1'] = '';
            $edit_vals['topic_search_2'] = '';
            $edit_vals['topic_exclude'] = '';
            $edit_vals['topic_skip_domains'] = '';
            $edit_vals['topic_cat'] = '';
            $edit_vals['topic_tag'] = '';
            $edit_vals['topic_sources'] = '';
            $edit_vals['topic_min_length'] = '';
            $edit_vals['topic_createcat'] = '';
            $edit_vals['opt_post_user'] = 'Not Set';
            $edit_vals['opt_post_ctype'] = 'not-selected';
            $edit_vals['opt_post_ctax'] = '';
            $edit_vals['opt_post_ctaxval'] = '';
            $edit_vals['opt_image_filter'] = '';
            $edit_vals['opt_topic_start'] = '';
            $edit_vals['opt_topic_end'] = '';
        } else {
            //error, so reset selected cat, tag, sources
            $cats['selected'] = $edit_vals['topic_cat'];
            $tags['selected'] = $edit_vals['topic_tag'];
            //Set up sources checkboxes
            $sources = array_map('trim',explode(',',$edit_vals['topic_sources']));            
        }
        $status_vals = array (
            mct_ai_status_display('Inactive','display'),
            mct_ai_status_display('Training','display')
        );
    }
    //Render page
    ?>
    <div class='wrap'>
    <?php //screen_icon('plugins'); ?>
    <h2>MyCurator <?php echo $pagetitle; ?></h2>  
    <?php 
    echo ($backpage);
    if (!empty($msg)){ 
        if ($error_flag) { ?>
           <div id="message" class="error" ><p><strong><?php echo "TOPIC NOT CREATED: ".$msg ; ?></strong></p></div>
        <?php } else { ?>
           <div id="message" class="updated" ><p><strong><?php echo $msg ; ?></strong></p></div>
           <?php
           //If over plan limits, put up message and exit
            if ($update_type == 'false' ) {
                mct_ai_getplan();
                if (!mct_ai_showplan(false)) $no_more = true;
            }
       } 
    } else {
        //If over plan limits, put up message and exit
        if ($update_type == 'false' ) {
            mct_ai_getplan();
            if (!mct_ai_showplan(false)) $no_more = true;
        }
    }
    if ($no_more) {
         echo "<h3>You have reached the maximum number of Topics allowed for your plan and cannot add new Topics</h3>";
         echo mct_ai_showplan();
         exit();
    }
?>
       <p>Use spaces or commas to separate keywords.  You can use phrases in Keywords by enclosing words in single or double quotes 
           (start and end quotes must be the same).  Use the root of a keyword and it will match all endings, for example manage 
           will match manages, manager and management. 
       <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
       <?php echo mct_ai_helpvideo('topicpage'); ?>
       <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI'] . '&updated='.$update_type); ?>"> 
        <table class="form-table" >
            <?php if($update_type == 'false') { ?>
            <tr>
                <th scope="row">Topic Name</th>
                <td><input name="topic_name" type="input" id="topic_name" size="100" maxlength="200" value="<?php echo $edit_vals['topic_name']; ?>"  /></td>    
            </tr>
            <?php } else { ?>
            <tr>
                <th scope="row">Topic Name</th>
                <td><?php echo $edit_vals['topic_name']; ?></td>  
                <input name="topic_name" type="hidden" id="topic_name" value="<?php echo $edit_vals['topic_name']; ?>"  />
            </tr>
            <?php } ?>            <tr>
                <th scope="row">Topic Search 1</th>
                <td><input name="topic_search_1" type="input" id="topic_search_1" size="100" value="<?php echo esc_attr($edit_vals['topic_search_1']); ?>"  />
                <span>&nbsp;<em>Each of these terms MUST be in the article</em></span></td>
            </tr>
            <tr>
                <th scope="row">Topic Search 2</th>
                <td><textarea name="topic_search_2" id="topic_search_2" rows="5" cols="100" ><?php echo esc_attr($edit_vals['topic_search_2']); ?></textarea>
                <span>&nbsp;<em>At Least 1 of these terms MUST be in the article</em></span></td>    
            </tr>
            <tr>
                <th scope="row">Topic Excluded</th>
                <td><textarea name="topic_exclude" id="topic_exclude" rows="3" cols="100" ><?php echo esc_attr($edit_vals['topic_exclude']); ?></textarea>
                <span>&nbsp;<em>NONE of these terms may be in the article</em></span></td>    
            </tr>
            <tr>
                <th scope="row">Minimum Article Length (in words)</th>
                <td><input name="topic_min_length" type="input" id="topic_min_length" size="5" maxlength="5" value="<?php echo $edit_vals['topic_min_length']; ?>"  /></td>    
            </tr>
            <tr>
                <th scope="row">Exclude if No Image</th>
                <td><input name="opt_image_filter" type="checkbox" id="opt_image_filter" value="1" <?php checked('1', $edit_vals['opt_image_filter']); ?> /></td>    
            </tr> 
            <tr>
                <th scope="row">Skip These Domains</th>
                <td><textarea id='topic_skip_domains' rows='5' cols='100' name='topic_skip_domains'><?php echo $edit_vals['topic_skip_domains'] ?></textarea>
                <span>&nbsp;<em>One Domain per Line</em></span></td>    
            </tr>
            <tr>
                <th scope="row">Choose Type</th>
                <td><select name="topic_type" >
                    <option value="Filter" <?php selected($edit_vals['topic_type'],"Filter"); ?>>Filter</option>
                    <option value="Video" <?php selected($edit_vals['topic_type'],"Video"); ?>>Video</option>
                    <option value="Relevance" <?php selected($edit_vals['topic_type'],"Relevance"); ?>>Relevance</option></select></td>    
            </tr>
            <tr>
                   <th scope="row">Topic Status</th>
                   <td><select name="topic_status" >
                <?php foreach ($status_vals as $stat) {
                        echo('<option value="'.$stat.'" '.selected($curstat,$stat).'>'.$stat.'</option>' );
                      }
                ?>
                </select></td>
            </tr>
            <tr>
                <th scope="row">User for MyCurator Posts</th>
                <td><select name="opt_post_user" >
                <?php foreach ($allusers as $users){ ?>
                    <option value="<?php echo $users->user_login; ?>" <?php selected($edit_vals['opt_post_user'],$users->user_login); ?> ><?php echo $users->user_login; ?></option>
                <?php } //end foreach ?>
                    </select><span> (If Not Set will use user in Admin tab of Options)</span></td>       
            </tr>     
            <tr>
                <th scope="row">Assign to Category</th>
                <td><?php wp_dropdown_categories($cats); ?><td>    
            </tr>
            <?php if ($update_type == 'false') { ?>
            <tr>
                <th scope="row">Or Create New Category</th>
                <td><input name="topic_createcat" type="input" id="topic_createcat" size="50" maxlength="200" value="<?php echo $createcat; ?>" /><span> (Will override any Category chosen above)</span></td>    
            </tr> 
            <?php } ?>
            <tr>
                <th scope="row">Use Search 2 Keywords as Tags</th>
                <td><input name="topic_tag_search2" type="checkbox" id="topic_tag_search2" value="1" <?php checked('1', $edit_vals['topic_tag_search2']); ?> /><span> (Will override Tag chosen below)</span></td>    
            </tr> 
            <tr>
                <th scope="row">OR Assign to a Single Tag</th>
                <td><?php wp_dropdown_categories($tags); ?><td>    
            </tr> 
            <tr>
                <th scope="row">Topic Start Date (mm/dd/yy)</th>
                <td><input name="opt_topic_start" type="input" id="opt_topic_start" size="12" maxlength="12" value="<?php echo $edit_vals['opt_topic_start']; ?>"  /></td>    
            </tr>
            <tr>
                <th scope="row">Topic End Date (mm/dd/yy)</th>
                <td><input name="opt_topic_end" type="input" id="opt_topic_end" size="12" maxlength="12" value="<?php echo $edit_vals['opt_topic_end']; ?>"  /></td>    
            </tr>
            <!-- Custom Post Type Selection -->
            <?php if (!empty($custom_types)) { ?>
                <tr><td><h3>Custom Post Type Option</h3></td>
                    <td>Category and Tags above will be ignored if one of these is chosen</td></tr>
                <?php 
                foreach ($custom_types as $ctype_key => $ctax_key) { 
                    if ($ctype_key != 'not-selected' ) {
                        $ctype = get_post_type_object($ctype_key); 
                    } else {
                        $ctype = new stdClass();  //build the not selected class
                        $ctype->name = 'not-selected';
                        $ctype->labels = new stdClass();
                        $ctype->labels->singular_name = 'Not Selected';
                    }
                    $ctax = array (
                        'orderby' => 'name',
                        'name' => $ctax_key,
                        'hide_empty' => FALSE,
                        'show_option_none' => 'No Value',
                        'taxonomy' => $ctax_key
                    );
                    if (!empty($edit_vals['opt_post_ctaxval'])) {
                        $cterm = get_term_by('slug', $edit_vals['opt_post_ctaxval'], $ctax_key);
                        if (!empty($cterm)) $ctax['selected'] = $cterm->term_id;
                    }
                    ?>
                    <tr>
                        <td><input name="opt_post_ctype" type="radio" value="<?php echo $ctype->name; ?>" <?php checked($edit_vals['opt_post_ctype'],$ctype->name); ?> />
                        <?php echo $ctype->labels->singular_name; ?></td>
                        <td><?php if (!empty($ctax_key)) wp_dropdown_categories($ctax); ?></td> 
                    </tr>
                 <?php } } //foreach and if custom types ?>
        </table>
        <!-- Sources Selection -->
        <h3>Select Source Groups for this Topic</h3>
        <table class="form-table" >
        <?php foreach ($terms as $term) {  ?>
           <tr>
               <th scope="row"><?php echo $term->name; ?></th>
               <td><input name="sourceChk[]" type="checkbox" value="<?php echo $term->term_id; ?> "
                 <?php if (!empty($sources) && in_array($term->term_id,$sources)) echo 'checked="checked"'; ?>/></td>
           </tr>
        <?php } ?>
        </table>
        <!-- Show ai stats if admin -->
        <?php
        if ($do_report){  ?>
        <h3>MyCurator Relevance Statistics</h3>
        <table class="form-table" >
            <tr>
                <th scope="row">Relevance Good Items</th>
                <td><?php echo $rpt['good']; ?><td>  
            </tr>    
            <tr>
                <th scope="row">Relevance Bad Items</th>
                <td><?php echo $rpt['bad']; ?><td>  
            </tr>    
            <tr>
                <th scope="row">Relevance # of Words</th>
                <td><?php echo strval($rpt['dict']); ?><td>  
            </tr>  
            <tr>
                <th scope="row">Relevance DB Adjustment</th>
                <td><?php echo strval($rpt['shrinkdb']); ?><td>  
            </tr>    
            <tr>
                <th scope="row">Relevance Coefficient</th>
                <td><?php printf('%.4f',$rpt['coef']); ?><td>  
            </tr>    
        </table>
        <?php } ?>
        <?php wp_nonce_field('mct_ai_topicpg','topicpg'); ?>
            <!-- Topic Slug Hidden Fields -->
            <input name="topic_slug" type="hidden" id="topic_slug" value="<?php echo $edit_vals['topic_slug']; ?>" />
            <?php if (current_user_can('manage_options')) { ?>
           <div class="submit">
          <input name="Submit" type="submit" value="Save Topic" class="button-primary" />
        </div>
            <?php } //end manage options check ?>
       </form> 
    </div>
<?php
}

function mct_ai_status_display($status,$ret){
    //Convert statuses into 'db' or 'display' values 
    if ($status == 'Inactive') return 'Inactive';  //Always the same
    if ($ret == 'db') {
        return ($status == 'Manual Curation - Training') ? 'Training' : 'Active';
    } else {
        return ($status == 'Training') ? 'Manual Curation - Training' : 'Auto Post Good - Active';
    }
}

function mct_ai_optionpage() {
    //Enter or edit MyCurator Options
    //Always check if db created here in case it didn't happen - especially multi-user
    //since they have to come here at least once to turn on the system
    global $mct_ai_optarray;
    
    $msg = '';
    $errmsg = '';
    //Set up user login dropdown
   $allusers = get_users(array('role' => 'editor'));
   $moreusers = get_users(array('role' => 'administrator'));
   $allusers = array_merge($moreusers,$allusers);
   //Get custom post types
   $args = array(
      'public'   => true,
      '_builtin' => false

    );
    $custom_types = get_post_types($args,'objects', 'and');
    if (isset($_POST['Submit']) ) {
        //create db just in case
         //mct_ai_createdb();
        //load options into array and update db
        if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
        check_admin_referer('mct_ai_optionspg','optionset');
        $opt_update = mct_ai_setoptions(false);
        //Validation
        if (empty($opt_update['ai_log_days'])) $opt_update['ai_log_days'] = 7;
        if ($opt_update['ai_log_days'] > 90) $opt_update['ai_log_days'] = 90;
        if (empty($opt_update['ai_train_days'])) $opt_update['ai_train_days'] = 7;
        if ($opt_update['ai_train_days'] > 90) $opt_update['ai_train_days'] = 90;
        if (empty($opt_update['ai_lookback_days'])) $opt_update['ai_lookback_days'] = 7;
        if ($opt_update['ai_lookback_days'] > 90) $opt_update['ai_lookback_days'] = 90;
        //Custom Post Types handling
        if (!empty($custom_types)) {
            $types_array = array();
            foreach ($custom_types as $ctype) {
                if (!empty($_POST[$ctype->name])) {
                    if (!empty($_POST['ctax-'.$ctype->name])) {
                        $types_array[$ctype->name] = $_POST['ctax-'.$ctype->name];
                    } else {
                        $types_array[$ctype->name] = '';
                    }
                }
            }
            if (!empty($types_array)) {
                $opt_update['ai_custom_types'] = maybe_serialize($types_array);
            }
        }
        update_option('mct_ai_options',$opt_update);
        $msg = 'Options have been updated';
        //Set up cron for auto processing
        if ($opt_update['ai_on']){
            mct_ai_schedcron();
        } else {
            if (wp_next_scheduled('mct_ai_cron_process')){
                wp_clear_scheduled_hook('mct_ai_cron_process');  //Clear out old entries
            }
            if (wp_next_scheduled('mct_ai_cron_rqstproc')){
                wp_clear_scheduled_hook('mct_ai_cron_rqstproc');  //Clear out old entries
            }
        }
        //New Cron Schedule?
        if ($mct_ai_optarray['ai_cron_period'] != $opt_update['ai_cron_period']){
            $mct_ai_optarray['ai_cron_period'] = $opt_update['ai_cron_period'];
            wp_clear_scheduled_hook('mct_ai_cron_process');  //Clear out old entries
            mct_ai_schedcron();
        }
        //Set up twitter
        if (!empty($opt_update['ai_tw_conk']) && !empty($opt_update['ai_tw_cons'])) {
            require_once(plugin_dir_path(__FILE__).'lib/class-mct-tw-api.php');
            $credentials = array(
              'consumer_key' => $opt_update['ai_tw_conk'],
              'consumer_secret' => $opt_update['ai_tw_cons']
            );
            //Get bearer token with this call if needed
            add_filter( 'https_ssl_verify', '__return_false' );
            add_filter( 'https_local_ssl_verify', '__return_false' );
            $twitter_api = new mct_tw_Api( $credentials );
            if ($twitter_api->has_error) {
                $errmsg = 'Could Not Set Up Twitter Account: '.$twitter_api->api_errmsg;
            }
            remove_filter( 'https_ssl_verify', '__return_false' );
            remove_filter( 'https_local_ssl_verify', '__return_false' );
            unset ($twitter_api);
        } else {
            require_once(plugin_dir_path(__FILE__).'lib/class-mct-tw-api.php');
            //Reset bearer token if any credentials are empty
            $credentials = array(
              'consumer_key' => $opt_update['ai_tw_conk'],
              'consumer_secret' => $opt_update['ai_tw_cons']
            );
            $twitter_api = new mct_tw_Api( $credentials );
            unset ($twitter_api);
        }
    }
    //Get Options
    $cur_options = get_option('mct_ai_options');
    //Explode Custom Types
    $cur_types = array();
    if (!empty($cur_options['ai_custom_types'])){
        $cur_types = maybe_unserialize($cur_options['ai_custom_types']);
    }
    ?>
    <script>
    //<![CDATA[
    jQuery(function() {
        jQuery( ".mct-ai-tabs #tabs" ).tabs();
    });
    //]]>
    </script>
    <div class='wrap'>
    <?php //screen_icon('plugins'); ?>
    <h2>MyCurator Options</h2> 
    <?php if (!empty($msg)){ ?>
       <div id="message" class="updated" ><p><strong><?php echo $msg ; ?></strong></p></div>
    <?php } ?>
       <?php if (!empty($errmsg)){ ?>
       <div id="message" class="error" ><p><strong><?php echo $errmsg ; ?></strong></p></div>
    <?php } ?>
       <p>Use this page to Turn On MyCurator and set MyCurator options to change various processing and display items.</p> 
        <p style="font-size:150%">See <a href="http://www.target-info.com/documentation-2/documentation-options/" target="_blank" >Options Documentation</a> for 
        a detailed description of each Option item.</p>
        <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI'] . '&updated=true'); ?>" >
        <div class="mct-ai-tabs" >
         <div id="tabs">
            <ul>
            <li><a href="#tabs-1">Basic</a></li>
            <li><a href="#tabs-2">Curation</a></li>
            <li><a href="#tabs-3">Format</a></li>
            <li><a href="#tabs-4">Twitter</a></li>
            <li><a href="#tabs-5">Admin</a></li>
            </ul>
            <div id="tabs-1">            
                <table class="form-table" >
                    <tr><th><strong>Basic Settings</strong></th>
                <td> </td></tr>
                <tr>
                    <th scope="row">Turn on MyCurator Background Process?</th>
                    <td><input name="ai_on" type="checkbox" id="ai_on" value="1" <?php checked('1', $cur_options['ai_on']); ?>  /></td>    
                </tr>
                <?php /*
                <tr>
                    <th scope="row">Enter the API Key to Access Cloud Services</th>
                    <td><input name="ai_cloud_token" type="text" id="ai_cloud_token" size ="50" value="<?php echo $cur_options['ai_cloud_token']; ?>"  />
                    <?php if (empty($cur_options['ai_cloud_token'])) { ?><span>&nbsp;MyCurator API Key: <a href="http://www.target-info.com/pricing/" />Get API Key</a></span></td> <?php } ?>   
                </tr> 
                 */ ?>           
                <tr>
                    <th scope="row">Save first article picture as featured post thumbnail?</th>
                    <td><input name="ai_save_thumb" type="checkbox" id="ai_save_thumb" value="1" <?php checked('1', $cur_options['ai_save_thumb']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Save First Image into Curated Post</th>
                    <td><input name="ai_post_img" type="checkbox" id="ai_post_img" value="1" <?php checked('1', $cur_options['ai_post_img']); ?>  />
                    <span>&nbsp;<em>If checked, you should turn off saving as Featured Image above or you may get duplicate images</em></span></td>    
                </tr>
                <tr>
                    <th scope="row">&raquo; Image Alignment</th>
                    <td><input name="ai_img_align" type="radio" value="left" <?php checked('left', $cur_options['ai_img_align']); ?>  /> Left
                        <input name="ai_img_align" type="radio" value="right" <?php checked('right', $cur_options['ai_img_align']); ?>  /> Right
                        <input name="ai_img_align" type="radio" value="center" <?php checked('center', $cur_options['ai_img_align']); ?>  /> Center
                        <input name="ai_img_align" type="radio" value="none" <?php checked('none', $cur_options['ai_img_align']); ?>  /> None
                    </td>    
                </tr> 
                <tr>
                    <th scope="row">&raquo; Image Size</th>
                    <td><input name="ai_img_size" type="radio" value="thumbnail" <?php checked('thumbnail', $cur_options['ai_img_size']); ?>  /> Thumbnail
                        <input name="ai_img_size" type="radio" value="medium" <?php checked('medium', $cur_options['ai_img_size']); ?>  /> Medium
                        <input name="ai_img_size" type="radio" value="large" <?php checked('large', $cur_options['ai_img_size']); ?>  /> Large
                        <input name="ai_img_size" type="radio" value="full" <?php checked('full', $cur_options['ai_img_size']); ?>  /> Full Size
                    </td>    
                </tr> 
                <tr>
                    <th scope="row">&raquo; Insert at Bottom of Post</th>
                    <td><input name="ai_image_bottom" type="checkbox" id="ai_image_bottom" value="1" <?php checked('1', $cur_options['ai_image_bottom']); ?>  /></td>    
                </tr> 
                <tr>
                    <th scope="row">Use Post Title for Image Title & Alt Tag</th>
                    <td><input name="ai_image_title" type="checkbox" id="ai_image_title" value="1" <?php checked('1', $cur_options['ai_image_title']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Run MyCurator Every </th>
                    <td><input name="ai_cron_period" type="radio" value="3" <?php checked('3', $cur_options['ai_cron_period']); ?>  /> 3
                        <input name="ai_cron_period" type="radio" value="6" <?php checked('6', $cur_options['ai_cron_period']); ?>  /> 6
                        <input name="ai_cron_period" type="radio" value="12" <?php checked('12', $cur_options['ai_cron_period']); ?>  /> 12
                        <input name="ai_cron_period" type="radio" value="24" <?php checked('24', $cur_options['ai_cron_period']); ?>  /> 24 Hours
                    </td>    
                </tr> 
                <tr>
                    <th scope="row">Enable Non-English Language Processing?</th>
                    <td><input name="ai_utf8" type="checkbox" id="ai_utf8" value="1" <?php checked('1', $cur_options['ai_utf8']); ?>  />
                    <span>&nbsp;<em>This must be checked if your blog is Not in English, see 
                            <a href="http://www.target-info.com/documentation-2/documentation-international/" >Documentation -  International</a></em></span></td> 
                </tr>
                <tr>
                    <th scope="row">Set Get It Default Tab to Notebooks </th>
                    <td>
                        <input name="ai_getit_tab" type="checkbox" id="ai_getit_tab" value="1" <?php checked('1', $cur_options['ai_getit_tab']); ?>  />
                    </td>    
                </tr> 
                </table>
            </div>
            <div id="tabs-2">
                <table class="form-table" >
                <tr><th><strong>Manual Curation Settings</strong></th>
                <td> </td></tr>
                <tr>
                    <th scope="row">Keep good trainees on Training Page?</th>
                    <td><input name="ai_keep_good_here" type="checkbox" id="ai_keep_good_here" value="1" <?php checked('1', $cur_options['ai_keep_good_here']); ?>  />
                    <span>&nbsp;<em>Use [Make Live] to Post on blog.</em></span></td>    
                </tr>
                
                <tr>
                    <th scope="row">Edit post when made live?</th>
                    <td><input name="ai_edit_makelive" type="checkbox" id="ai_edit_makelive" value="1" <?php checked('1', $cur_options['ai_edit_makelive']); ?>  />
                    <span>&nbsp;<em>Will create draft post and display in post editor on [Make Live] (except for Bulk Actions)</em></span></td>     
                </tr>  
                <tr>
                    <th scope="row">Do NOT show readable page in Training Popups </th>
                    <td><input name="ai_no_inline_pg" type="checkbox" id="ai_no_inline_pg" value="1" <?php checked('1', $cur_options['ai_no_inline_pg']); ?>  />
                    <span>&nbsp;<em>Check this if you have Formatting Problems on Training Page or Admin Training Posts</em></span></td>    
                </tr>
                <tr>
                    <th scope="row">Place attribution link above excerpt</th>
                    <td><input name="ai_attr_top" type="checkbox" id="ai_attr_top" value="1" <?php checked('1', $cur_options['ai_attr_top']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Make Post Date 'Immediately' when Made Live</th>
                    <td><input name="ai_now_date" type="checkbox" id="ai_now_date" value="1" <?php checked('1', $cur_options['ai_now_date']); ?>  /></td>    
                </tr><tr>
                    <th scope="row">Remove Duplicate Titles Within Same Topic</th>
                    <td><input name="ai_dup_title" type="checkbox" id="ai_dup_title" value="1" <?php checked('1', $cur_options['ai_dup_title']); ?>  /></td>    
                </tr>
                <tr><th><strong>Video -----------------</strong></th><td><strong>The following options Only apply to Topics with a type of Video.</strong></td></tr>
                <tr>
                    <th scope="row">Embed Video in Post for Video Topic?</th>
                    <td><input name="ai_embed_video" type="checkbox" id="ai_embed_video" value="1" <?php checked('1', $cur_options['ai_embed_video']); ?>  /></td>     
                </tr> 
                <tr>
                    <th scope="row">&raquo;Size of Embed Iframe</th>
                    <td>Width <input name="ai_video_width" type="text" id="ai_video_width" size ="5" value="<?php echo $cur_options['ai_video_width']; ?>"  />&nbsp;
                        Height <input name="ai_video_height" type="text" id="ai_video_height" size ="5" value="<?php echo $cur_options['ai_video_height']; ?>"  /></td>    
                </tr>
                <tr>
                    <th scope="row">&raquo; Video Alignment</th>
                    <td><input name="ai_video_align" type="radio" value="none" <?php checked('none', $cur_options['ai_video_align']); ?>  /> None
                        <input name="ai_video_align" type="radio" value="center" <?php checked('center', $cur_options['ai_video_align']); ?>  /> Center
                        <input name="ai_video_align" type="radio" value="left" <?php checked('left', $cur_options['ai_video_align']); ?>  /> Left
                        <input name="ai_video_align" type="radio" value="right" <?php checked('right', $cur_options['ai_video_align']); ?>  /> Right                        
                    </td>    
                </tr> 
                <tr>
                    <th scope="row">Insert Description into Post for YouTube Videos?</th>
                    <td><input name="ai_video_desc" type="checkbox" id="ai_video_desc" value="1" <?php checked('1', $cur_options['ai_video_desc']); ?>  /></td>     
                </tr> 
                <tr>
                    <th scope="row">Do Not add link to embedded video.</th>
                    <td><input name="ai_video_nolink" type="checkbox" id="ai_video_nolink" value="1" <?php checked('1', $cur_options['ai_video_nolink']); ?>  /></td>     
                </tr> 
                 <tr>
                    <th scope="row">Capture YouTube Thumbnail as Featured Image</th>
                    <td><input name="ai_video_thumb" type="checkbox" id="ai_video_thumb" value="1" <?php checked('1', $cur_options['ai_video_thumb']); ?>  />
                    <span>&nbsp;<em>For use when embedded video not shown on Home page</em></span></td>     
                </tr> 
                <?php 
                if (!empty($custom_types)) {
                ?>
                <tr><th><strong>Custom Post Types ----</strong></th><td><strong>Choose Allowed Custom Posts Types for Topics Below.</strong></td></tr>
                <?php foreach ($custom_types as $ctype) { ?>
                    <tr>
                        <th scope="row"><?php echo $ctype->labels->singular_name; ?></th>
                        <td><input name="<?php echo $ctype->name; ?>" type="checkbox" id="<?php echo $ctype->name; ?>" value="1" <?php checked('1', array_key_exists($ctype->name,$cur_types)); ?>  /></td>     
                    </tr> 
                    <?php if (!empty($ctype->taxonomies)) { ?>
                    <tr>
                        <th scope="row">>>Taxonomy for Posts</th>
                            <?php
                            echo '<td>';
                            $thistax = (!empty($cur_types[$ctype->name])) ? $cur_types[$ctype->name] : ''; 
                            //None option
                            ?>
                            <input name="ctax-<?php echo $ctype->name; ?>" type="radio" value="" <?php checked('', $thistax); ?>  /> None
                            <?php
                            foreach ($ctype->taxonomies as $ctax) { 
                               $taxobj = get_taxonomy($ctax);?>
                               <input name="ctax-<?php echo $ctype->name; ?>" type="radio" value="<?php echo $ctax; ?>" <?php checked($ctax, $thistax); ?>  /> <?php echo $taxobj->labels->singular_name; ?>
                        <?php } echo '</td>'; //Foreach taxonomies ?>
                    </tr> 
                   <?php } //end if taxonomies ?>
                <?php }  } //end foreach & custom types ?>
                </table>
            </div>
            <div id="tabs-3">
                <table class="form-table" >
                <tr><th><strong>Format Settings</strong></th>
                <td> </td></tr>
                <tr>
                    <th scope="row">Link to Original Page Text</th>
                    <td><input name="ai_orig_text" type="text" id="ai_orig_text" size ="50" value="<?php echo $cur_options['ai_orig_text']; ?>"  />
                        <span>&nbsp;<em>If using link to original web page, customize this text</em></span></td> 
                </tr>
                <tr>
                    <th scope="row">&raquo;&raquo; Do Not Use this text as part of Link Anchor</th>
                    <td><input name="ai_no_anchor" type="checkbox" id="ai_no_anchor" value="1" <?php checked('1', $cur_options['ai_no_anchor']); ?>  /></td>    
                </tr>
                
                <tr>
                    <th scope="row">Use Article Title Instead of Domain in Original Article Link</th>
                    <td><input name="ai_post_title" type="checkbox" id="ai_post_title" value="1" <?php checked('1', $cur_options['ai_post_title']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Clicking on Post Title or Image will go to Original Article</th>
                    <td><input name="ai_title_link" type="checkbox" id="ai_title_link" value="1" <?php checked('1', $cur_options['ai_title_link']); ?>  /></td>
                </tr>
                <tr>
                    <th scope="row">Open Original Article Link in New Tab</th>
                    <td><input name="ai_new_tab" type="checkbox" id="ai_new_tab" value="1" <?php checked('1', $cur_options['ai_new_tab']); ?>  />
                    <span>&nbsp;<em>This will also work on the previous option if checked</em></span></td>    
                </tr>
                <tr>
                    <th scope="row">Do Not Use Blockquotes on Excerpt</th>
                    <td><input name="ai_no_quotes" type="checkbox" id="ai_no_quotes" value="1" <?php checked('1', $cur_options['ai_no_quotes']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Do Not Show Training Tags on Live Site for Admins</th>
                    <td><input name="ai_no_train_live" type="checkbox" id="ai_no_train_live" value="1" <?php checked('1', $cur_options['ai_no_train_live']); ?>  /></td>    
                </tr>
                 <tr>
                    <th scope="row">Excerpt length in words:</th>
                    <td><input name="ai_excerpt" type="text" id="ai_excerpt" size ="5" value="<?php echo $cur_options['ai_excerpt']; ?>"  /></td>    
                </tr> 
                <tr>
                    <th scope="row">Save Line Breaks in Excerpt?</th>
                    <td><input name="ai_line_brk" type="checkbox" id="ai_line_brk" value="1" <?php checked('1', $cur_options['ai_line_brk']); ?>  />
                    <span>&nbsp;<em>Warning: Use Blockquoted excerpt with this option if you are displaying the full text readable article on the single post page - see <a href="http://www.target-info.com/documentation-2/documentation-options/">Options</a> documentation</em></span></td>    
                </tr>
                <tr>
                    <th scope="row"># of Articles shown on Training Page</th>
                    <td><input name="ai_num_posts" type="text" id="ai_num_posts" size ="5" value="<?php echo $cur_options['ai_num_posts']; ?>"  /></td>    
                </tr>  
                
                </table>
            </div>
            <div id="tabs-4">
                <h3>To Set up your Twitter Keys see below or our <a href=" http://www.target-info.com/documentation-2/documentation-twitter-api/" target="_blank">Documentation</a> page with screen shots</h3>
                <p>Go to the Developers website: <a href="https://dev.twitter.com/apps" target ="_blank">https://dev.twitter.com/apps</a>. Sign in with your Twitter Account.  
                    Click Create a new application button on the right.</p>
                <p>Fill in the Application Details page.  You should use your own application names and descriptions.  
                    Scroll to the bottom of the page, click the Yes I agree checkbox and enter the Captcha information.  
                    Click the Create your Twitter Application button at the bottom.</p>
                <p>Copy the Consumer Key and Consumer Secret from the details screen under the Oath Settings heading into 
                    the same fields below.  Click Save Options and you should be 
                    ready to process your Twitter searches and follows.</p>
                <h3>To Change your Twitter Keys</h3>
                <p>If you wish to switch to a new Twitter App, you need to first blank out one or both of the fields below and then Save Options.  
                    This will remove your old application from the database.  Come back to this Tab and then copy in your new Consumer Key and Secret then Save Options again.</p>
                <table class="form-table" >
                <tr><th><strong>Twitter App Settings</strong></th>
                <td> </td></tr>
                <tr>
                    <th scope="row">Twitter App Consumer Key</th>
                    <td><input name="ai_tw_conk" type="text" id="ai_tw_conk" size ="75" value="<?php echo $cur_options['ai_tw_conk']; ?>"  /></td> 
                </tr>
                <tr>
                    <th scope="row">Twitter App Consumer Secret</th>
                    <td><input name="ai_tw_cons" type="password" id="ai_tw_cons" size ="75" value="<?php echo $cur_options['ai_tw_cons']; ?>"  /></td> 
                </tr>
                </table>
            </div>    
            <div id="tabs-5" >
                <script>
                    function mct_ai_slpage_box() {
                        if (document.getElementById("ai_del_slpages").checked) {
                            document.getElementById("ai_show_full").checked = false;
                            document.getElementById("ai_slpage_link").checked = false;
                            document.getElementById("ai_show_full").disabled = true;
                            document.getElementById("ai_slpage_link").disabled = true;
                        } else {
                            document.getElementById("ai_show_full").disabled = false;
                            document.getElementById("ai_slpage_link").disabled = false;
                        }
                    }
                </script>
                <table class="form-table" >
               <tr><th><strong>Administrative Settings</strong></th>
                <td> </td></tr>
                <tr>
                    <th scope="row">Do Not Save to Excerpt Field in Post</th>
                    <td><input name="ai_nosave_excerpt" type="checkbox" id="ai_nosave_excerpt" value="1" <?php checked('1', $cur_options['ai_nosave_excerpt']); ?>  />
                    <span>&nbsp;<em>Use this if your theme uses the_excerpt and you add comments to the post</em></span></td>     
                </tr> 
                <tr>
                    <th scope="row">User for MyCurator Posts</th>
                    <td><select name="ai_post_user" >
                    <?php foreach ($allusers as $users){ ?>
                        <option value="<?php echo $users->user_login; ?>" <?php selected($cur_options['ai_post_user'],$users->user_login); ?> ><?php echo $users->user_login; ?></option>
                    <?php } //end foreach ?>
                        </select></td>       
                </tr> 
                <tr>
                    <th scope="row">Use Logged In User when Making Posts Live</th>
                    <td><input name="ai_post_this_user" type="checkbox" id="ai_post_this_user" value="1" <?php checked('1', $cur_options['ai_post_this_user']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Show Links Menu Item?</th>
                    <td><input name="ai_short_linkpg" type="checkbox" id="ai_short_linkpg" value="1" <?php checked('1', $cur_options['ai_short_linkpg']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Delete Readable Page when Curated Article Published?</th>
                    <td><input name="ai_del_slpages" type="checkbox" id="ai_del_slpages" value="1" <?php checked('1', $cur_options['ai_del_slpages']); ?> onclick="mct_ai_slpage_box()" /></td>    
                </tr>
                <tr><th><strong>--Readable Page Links --</strong></th><td>The following 2 options May only be used if the previous option is cleared.</td></tr>
                <tr><th><strong>Warning</strong></th><td><strong>Use of these Options are Not recommended, you may be open to copyright and other issues with original article owner - see <a href="http://www.target-info.com/documentation-2/documentation-options/">Options</a> documentation</strong></td>
                </tr>
                <tr>
                    <th scope="row">Show full Article Text in Single Post Page? </th>
                    <td><input name="ai_show_full" type="checkbox" id="ai_show_full" value="1" <?php checked('1', $cur_options['ai_show_full']); ?>  />
                    </td>    
                </tr>
                <tr>
                    <th scope="row">Insert Readable Page Link not Original Article Link?</th>
                    <td><input name="ai_slpage_link" type="checkbox" id="ai_slpage_link" value="1" <?php checked('1', $cur_options['ai_slpage_link']); ?>  /></td>    
                </tr>
                <tr><th><strong>--Readable Page Links --</strong></th></tr>
                <tr>
                    <th scope="row">&raquo;&raquo; Link to Saved Readable Page Text</th>
                    <td><input name="ai_save_text" type="text" id="ai_save_text" size ="50" value="<?php echo $cur_options['ai_save_text']; ?>"  />
                        <span>&nbsp;<em>If using link to saved readable page, customize this text</em></span></td> 
                </tr>
                <tr>
                    <th scope="row">Remove Formatting Help</th>
                    <td><input name="ai_no_fmthelp" type="checkbox" id="ai_no_fmthelp" value="1" <?php checked('1', $cur_options['ai_no_fmthelp']); ?>  /></td>    
                </tr>
                <tr>
                    <th scope="row">Keep Log for How Many Days?</th>
                    <td><input name="ai_log_days" type="text" id="ai_log_days" size ="5" value="<?php echo $cur_options['ai_log_days']; ?>"  />
                    <em>Between 1 and 90 days</em></td>    
                </tr>
                <tr>
                    <th scope="row">Keep Training Posts for How Many Days?</th>
                    <td><input name="ai_train_days" type="text" id="ai_train_days" size ="5" value="<?php echo $cur_options['ai_train_days']; ?>"  />
                    <em>Between 1 and 90 days</em></td>    
                </tr>     
                 <tr>
                    <th scope="row">Look back How Many Days for Articles?</th>
                    <td><input name="ai_lookback_days" type="text" id="ai_lookback_days" size ="5" value="<?php echo $cur_options['ai_lookback_days']; ?>"  />
                    <em>Between 1 and 90 days</em></td>    
                </tr> 
                <tr>
                    <th scope="row">Page Request Mode</th>
                    <td><input name="ai_page_rqst" type="checkbox" id="ai_page_rqst" value="1" <?php checked('1', $cur_options['ai_page_rqst']); ?>  /></td>    
                </tr>  
                <tr>
                    <th scope="row">Use Inline Site Processing</th>
                    <td><input name="ai_no_procpg" type="checkbox" id="ai_no_procpg" value="1" <?php checked('1', $cur_options['ai_no_procpg']); ?>  />
                    </td>    
                </tr>  
                <?php if (mct_ai_ispaid()) { ?>
                <tr>
                    <th scope="row">Hide MyCurator menu for non-Admins?</th>
                    <td><input name="ai_hide_menu" type="checkbox" id="ai_hide_menu" value="1" <?php checked('1', $cur_options['ai_hide_menu']); ?>  />
                    <em>Only for Paid Plans</em></td>    
                </tr>   
                <tr>
                    <th scope="row">Add Publish Tab to Get It</th>
                    <td><input name="ai_getit_pub" type="checkbox" id="ai_getit_pub" value="1" <?php checked('1', $cur_options['ai_getit_pub']); ?>  />
                    <em>Only for Paid Plans</em></td>    
                </tr> 
                <?php } ?>
                </table>
            </div>
         </div>
        </div>
            <?php wp_nonce_field('mct_ai_optionspg','optionset'); ?>
            <?php if (current_user_can('manage_options')) { ?>
        <div class="submit">
          <input name="Submit" type="submit" value="Save Options" class="button-primary" />
        </div>
        <?php } //end manage options check ?>
        <em>Saves Options for All Tabs at once</em>
        </form>
    </div>
    <script>
                    if (document.getElementById("ai_del_slpages").checked) {
                            document.getElementById("ai_show_full").checked = false;
                            document.getElementById("ai_slpage_link").checked = false;
                            document.getElementById("ai_show_full").disabled = true;
                            document.getElementById("ai_slpage_link").disabled = true;
                        }
    </script>
<?php
}

function mct_ai_setoptions($default) {
    //Set all option values
    //if $default, use the default value (on install)
    //if !$default use the $_POST variable
    global $mct_ai_optarray;
    
    $opt_update = array (
            'ai_log_days' => ($default) ? 7 : absint($_POST['ai_log_days']),
            'ai_on' => ($default) ? TRUE : ($_POST['ai_on'] == FALSE ? FALSE : TRUE),
            'ai_cloud_token' => ($default) ? '' : $mct_ai_optarray['ai_cloud_token'],
            'ai_train_days' => ($default) ? 7 : absint($_POST['ai_train_days']),
            'ai_lookback_days' => ($default) ? 30 : absint($_POST['ai_lookback_days']),
            'ai_short_linkpg' => ($default) ? 0 : absint((isset($_POST['ai_short_linkpg']) ? $_POST['ai_short_linkpg'] : 0)),
            'ai_save_thumb' => ($default) ? 1 : absint((isset($_POST['ai_save_thumb']) ? $_POST['ai_save_thumb'] : 0)),
            'ai_cron_period' => ($default) ? 6 : absint($_POST['ai_cron_period']),
            'ai_keep_good_here' => ($default) ? 1 : absint((isset($_POST['ai_keep_good_here']) ? $_POST['ai_keep_good_here'] : 0)),
            'ai_excerpt' => ($default) ? 50 : absint($_POST['ai_excerpt']),
            'ai_nosave_excerpt' => ($default) ? 1 : absint((isset($_POST['ai_nosave_excerpt']) ? $_POST['ai_nosave_excerpt'] : 1)),
            'ai_show_orig' => ($default) ? 1 : absint((isset($_POST['ai_show_orig']) ? $_POST['ai_show_orig'] : 0)),
            'ai_orig_text' => ($default) ? 'Click here to view original web page at' : trim(sanitize_text_field($_POST['ai_orig_text'])),
            'ai_save_text' => ($default) ? 'Click here to view full article' : trim(sanitize_text_field($_POST['ai_save_text'])),
            'ai_post_user' => ($default) ? '' : trim(sanitize_text_field($_POST['ai_post_user'])),
            'ai_utf8' => ($default) ? 0 : absint((isset($_POST['ai_utf8']) ? $_POST['ai_utf8'] : 0)),
            'ai_edit_makelive' => ($default) ? 1 : absint((isset($_POST['ai_edit_makelive']) ? $_POST['ai_edit_makelive'] : 0)),
            'ai_num_posts' => ($default) ? 10 : absint($_POST['ai_num_posts']),
            'ai_post_title' => ($default) ? 0 : absint((isset($_POST['ai_post_title']) ? $_POST['ai_post_title'] : 0)),
            'ai_new_tab' => ($default) ? 0 : absint((isset($_POST['ai_new_tab']) ? $_POST['ai_new_tab'] : 0)),
            'ai_no_quotes' => ($default) ? 0 : absint((isset($_POST['ai_no_quotes']) ? $_POST['ai_no_quotes'] : 0)),
            'ai_now_date' => ($default) ? 1 : absint((isset($_POST['ai_now_date']) ? $_POST['ai_now_date'] : 0)),
            'ai_post_img' => ($default) ? 0 : absint((isset($_POST['ai_post_img']) ? $_POST['ai_post_img'] : 0)),
            'ai_img_align' => ($default) ? 'left' : trim(sanitize_text_field($_POST['ai_img_align'])),
            'ai_img_size' => ($default) ? 'thumbnail' : trim(sanitize_text_field($_POST['ai_img_size'])),
            'ai_no_anchor' => ($default) ? 0 : absint((isset($_POST['ai_no_anchor']) ? $_POST['ai_no_anchor'] : 0)),
            'ai_no_inline_pg' => ($default) ? 0 : absint((isset($_POST['ai_no_inline_pg']) ? $_POST['ai_no_inline_pg'] : 0)),
            'ai_no_train_live' => ($default) ? 0 : absint((isset($_POST['ai_no_train_live']) ? $_POST['ai_no_train_live'] : 0)),
            'ai_no_fmthelp' => ($default) ? 0 : absint((isset($_POST['ai_no_fmthelp']) ? $_POST['ai_no_fmthelp'] : 0)),
            'ai_show_full'  => ($default) ? 0 : absint((isset($_POST['ai_show_full']) ? $_POST['ai_show_full'] : 0)),
            'ai_embed_video' => ($default) ? 1 : absint((isset($_POST['ai_embed_video']) ? $_POST['ai_embed_video'] : 0)),
            'ai_video_width' => ($default) ? 400 : absint($_POST['ai_video_width']),
            'ai_video_height' => ($default) ? 300 : absint($_POST['ai_video_height']),
            'ai_video_align' => ($default) ? 'none' : trim(sanitize_text_field($_POST['ai_video_align'])),
            'ai_video_desc' => ($default) ? 0 : absint((isset($_POST['ai_video_desc']) ? $_POST['ai_video_desc'] : 0)),
            'ai_video_nolink' => ($default) ? 0 : absint((isset($_POST['ai_video_nolink']) ? $_POST['ai_video_nolink'] : 0)),
            'ai_video_thumb' => ($default) ? 0 : absint((isset($_POST['ai_video_thumb']) ? $_POST['ai_video_thumb'] : 0)),
            'ai_line_brk' => ($default) ? 0 : absint((isset($_POST['ai_line_brk']) ? $_POST['ai_line_brk'] : 0)),
            'ai_hide_menu' => ($default) ? 0 : absint((isset($_POST['ai_hide_menu']) ? $_POST['ai_hide_menu'] : 0)),
            'ai_image_title' => ($default) ? 1 : absint((isset($_POST['ai_image_title']) ? $_POST['ai_image_title'] : 0)),
            'ai_getit_tab' => ($default) ? 0 : absint((isset($_POST['ai_getit_tab']) ? $_POST['ai_getit_tab'] : 0)),
            'ai_tw_conk' => ($default) ? '' : trim(sanitize_text_field($_POST['ai_tw_conk'])),
            'ai_tw_cons' => ($default) ? '' : trim(sanitize_text_field($_POST['ai_tw_cons'])),
            'ai_plan' => ($default) ? '' : $mct_ai_optarray['ai_plan'],
            'ai_no_procpg' => ($default) ? 1 : absint((isset($_POST['ai_no_procpg']) ? $_POST['ai_no_procpg'] : 0)),
            'ai_page_rqst' => ($default) ? 1 : absint((isset($_POST['ai_page_rqst']) ? $_POST['ai_page_rqst'] : 0)),
            'ai_image_bottom' => ($default) ? 0 : absint((isset($_POST['ai_image_bottom']) ? $_POST['ai_image_bottom'] : 0)),
            'ai_custom_types' => ($default) ? '' : '', //Not set by post, special processing
            'ai_attr_top' => ($default) ? 0 : absint((isset($_POST['ai_attr_top']) ? $_POST['ai_attr_top'] : 0)),
            'ai_getit_pub' => ($default) ? 0 : absint((isset($_POST['ai_getit_pub']) ? $_POST['ai_getit_pub'] : 0)),
            'ai_dup_title' => ($default) ? 1 : absint((isset($_POST['ai_dup_title']) ? $_POST['ai_dup_title'] : 0)),
            'ai_post_this_user' => ($default) ? 0 : absint((isset($_POST['ai_post_this_user']) ? $_POST['ai_post_this_user'] : 0)),
            'ai_del_slpages' => ($default) ? 1 : absint((isset($_POST['ai_del_slpages']) ? $_POST['ai_del_slpages'] : 0)),
            'ai_title_link' => ($default) ? 0 : absint((isset($_POST['ai_title_link']) ? $_POST['ai_title_link'] : 0)),
            'ai_slpage_link' => ($default) ? 0 : absint((isset($_POST['ai_slpage_link']) ? $_POST['ai_slpage_link'] : 0)),
            'MyC_version' => ($default) ? MCT_AI_VERSION : MCT_AI_VERSION
        );
        return $opt_update;
}
function mct_ai_topicsource() {
    //Edit the topic sources
    
    global $wpdb, $ai_topic_tbl;
    $tname = '';
    $msg = '';
    //Create go back link
    $ruri = $_SERVER['REQUEST_URI'];
    $pos = stripos($ruri,"_alltopics");
    $backpage = '<p><a href="'.substr($ruri,0,$pos) .'_alltopics" >Click Here to go Back to Topics List Page</a></p>';
    
    $sources = array ();
    if (isset($_GET['edit'])){
        //Came in as edit, from the All Topics page, so we know the data to show
        $tname = trim(sanitize_text_field($_GET['edit']));
        $sql = "SELECT `topic_name`, `topic_sources`
            FROM $ai_topic_tbl
            WHERE topic_name = '$tname'";
        $edit_vals = $wpdb->get_row($sql, ARRAY_A);
        if (!empty($edit_vals['topic_sources'])) $sources = array_map('trim',explode(',',$edit_vals['topic_sources']));
    }
    if (isset($_POST['Submit']) ) {
        if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
        check_admin_referer('mct_ai_setsource','sources');
        //load options into array and update db
        $tname = $_POST['topic_name'];
        if (isset($_POST['sourceChk'])) $val_array = array('topic_sources' => implode(',',$_POST['sourceChk']));
            else $val_array = array('topic_sources' => '');
        $where = array('topic_name' => $tname);
        $wpdb->update($ai_topic_tbl, $val_array, $where);
        $msg = "Sources have been updated";
    }
    if (isset($_POST['topic']) || isset($_POST['Submit'])){
        //Get Topic Values from Db
        if (isset($_POST['topic'])){
            $tname = trim(sanitize_text_field($_POST['topic_name']));
        }
        $sql = "SELECT `topic_name`, `topic_sources`
            FROM $ai_topic_tbl
            WHERE topic_name = '$tname'";
        $edit_vals = $wpdb->get_row($sql, ARRAY_A);
        if (!empty($edit_vals['topic_sources'])) $sources = array_map('trim',explode(',',$edit_vals['topic_sources']));
            else $sources = array();
    }
    //Get all link categories
    $taxname = 'link_category';
    $terms = get_terms(array('taxonomy' => 'link_category', 'hide_empty' => false));
    //Get all topics for dropdown
    $sql = "SELECT `topic_name`
            FROM $ai_topic_tbl ORDER BY topic_name";
    $topic_vals = $wpdb->get_results($sql, ARRAY_A);
    ?>
    <div class='wrap'>
    <?php //screen_icon('plugins'); ?>
    <h2>MyCurator Topic Sources</h2>
    <?php echo($backpage); ?>
    <p>MyCurator relates Sources (RSS feeds, Alerts, news) to the Topics you set up based on Source Groups. Click the checkbox for each Source Group that you wish to assign to this Topic.  
    See our <a href="http://www.target-info.com/training-videos/#sources" >Sources</a> video and <a href="http://www.target-info.com/documentation-2/documentation-sources/" >Sources Documentation</a> for more details</p>
    
    <?php if (!empty($msg)){ ?>
       <div id="message" class="updated" ><p><strong><?php echo $msg ; ?></strong></p></div>
    <?php } ?>
   <?php /*
   <form name="select-topic" method='post'> 
	<input type="hidden" name="topic" value="select" />
        <select name="topic_name" >
          <?php foreach ($topic_vals as $topic){
              $topicnam = $topic['topic_name'];
               echo '<option value="'.$topicnam.'" '.selected($tname,$topicnam,false).'">'.$topicnam.'</option>';
          } ?>        
        </select>
	<input name="Select" value="Select Topic" type="submit" class="button-secondary" />
        <em>Choose Topic and Click Select Topic to Edit Sources for that Topic</em>
   </form>
    * 
    */ ?>
       <h4>Choose Source Groups for Topic => <?php echo ($tname);?></h4>
<?php if (!empty($tname)){ 
          if (!empty($terms)) {?>
   <form name="sources" method='post'> 
       
       <table class="form-table" >
      <?php foreach ($terms as $term) {  ?>
           <tr>
                <td><input name="sourceChk[]" type="checkbox" value="<?php echo $term->term_id; ?> "
                 <?php if (!empty($sources) && in_array($term->term_id,$sources)) echo 'checked="checked"'; ?>/>
                 <?php echo '&nbsp;<strong>'.$term->name.'</strong>'; ?></td>
                <td><?php echo $term->description; ?></td>
           </tr>
      <?php } ?>
       </table>
       <p>
           <?php wp_nonce_field('mct_ai_setsource','sources'); ?>
       <input type="hidden" name="topic_name" value="<?php echo $tname; ?>" />
       <input name="Submit" value="Submit" type="submit" class="button-primary"></p>
   </form>
    
<?php } else {
          echo 'No Sources Found. See our <a href="http://www.target-info.com/training-videos/#sources" >Sources</a> video and <a href="http://www.target-info.com/documentation-2/documentation-sources/" >Sources Documentation</a>';
      }
} ?>    
    </div>
<?php
}

function mct_ai_removepage() {
    global $wpdb, $ai_topic_tbl, $ai_logs_tbl;
    
    $msgclass = 'updated';
    //Create go back link
    $ruri = $_SERVER['REQUEST_URI'];
    $pos = stripos($ruri,"_alltopics");
    $backpage = '<p><a href="'.substr($ruri,0,$pos) .'_alltopics" >Click Here to go Back to Topics List Page</a></p>';
    
    if (isset($_POST['Delete']) ) {
        if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
        check_admin_referer('mct_ai_deltop','removeit');
        $delname = $_POST['topic_name'];
        //Delete Topic
        $termobj = get_term_by('name',$delname, 'topic');
        $wpdb->query($wpdb->prepare ("DELETE FROM $ai_topic_tbl WHERE topic_name = %s", $delname ));
        //Remove Training Posts with this topic
        $args = array(
                'posts_per_page' => -1,
                'post_type' => 'target_ai',
                'post_status' => 'publish',
                'tax_query' => array(
		    array(
			'taxonomy' => 'topic',
			'field'    => 'slug',
			'terms'    => $termobj->slug,
                            ),
                    ),
            );
        $training = get_posts($args);
        foreach ($training as $train){
            wp_trash_post($train->ID);
        }
        //Remove from topic term
        wp_delete_term($termobj->term_id, 'topic');
        $msg = 'Topic'.$delname.' has been deleted';
    }
    if (isset($_POST['Rename']) ) {
        if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
        check_admin_referer('mct_ai_rentop','renameit');
        $newname = $_POST['ai_rename'];
        if (empty($newname)) {
            $msg = "ERROR: Must enter a new name to rename a Topic";
            $msgclass = 'error';
        } else {
            $delname = $_POST['topic_name'];
            //update Topic
            $termobj = get_term_by('name',$delname, 'topic');
            $wpdb->query($wpdb->prepare ("UPDATE $ai_topic_tbl SET topic_name = %s WHERE topic_name = %s", $newname, $delname ));
            // Update Topic name in Logs
            $wpdb->query($wpdb->prepare ("UPDATE $ai_logs_tbl SET logs_topic = %s WHERE logs_topic = %s", $newname, $delname ));
            //update topic term, since we are updatiing, Training posts will be updated automatically
            $topicslug = sanitize_title($newname);
            wp_update_term($termobj->term_id, 'topic', array('name' => $newname, 'slug' => $topicslug));
            $msg = 'Topic'.$delname.' has been renamed to '.$newname;
        }
    }
    //Get all topics for dropdown
    $sql = "SELECT `topic_name`
            FROM $ai_topic_tbl ORDER BY topic_name";
    $topic_vals = $wpdb->get_results($sql, ARRAY_A);

    ?>
    <div class='wrap'>
    <?php //screen_icon('plugins'); ?>
    <h2>MyCurator Remove and Rename Topics</h2>  
    <?php echo ($backpage); ?>
    <?php if (!empty($msg)) echo ('<div id="message" class="'.$msgclass.'" ><h4>'.$msg.'</h4></div>'); ?>
    <h4>Choose a Topic from the drop down below </h4>
    
    <form name="select-topic" method='post'> <p>
	<input type="hidden" name="topic" value="select" />
        <select name="topic_name" >
          <?php foreach ($topic_vals as $topic){
              $topicnam = $topic['topic_name'];
               echo '<option value="'.$topicnam.'" >'.$topicnam.'</option>';
          } ?>        
        </select></p>
        <h4>Then click Delete to remove it and any associated training posts.</h4>
        <p>
        <?php wp_nonce_field('mct_ai_deltop','removeit'); ?>
        <input name="Delete" value="Delete" type="submit" class="button-primary" onclick="return confirm('Are you sure you want to Delete this Topic?');"></p>
        <h4>OR Enter a new name and click Rename to rename the Topic.</h4>
        <p>New Name for Topic:&nbsp<input name="ai_rename" type="text" id="ai_rename" size ="50" />
        <?php wp_nonce_field('mct_ai_rentop','renameit'); ?>
        <input name="Rename" value="Rename" type="submit" class="button-primary" onclick="return confirm('Are you sure you want to Rename this Topic?');"></p>
   </form>
    </div>
<?php
}

function mct_ai_getitpage() {
    //Page to set up the get-it bookmarklet
    require_once('./admin.php');

    
    
    if (isset($_REQUEST['dogetit'])) {
        mct_ai_dogetit();
        exit();
    }
    if (isset($_REQUEST['dosourceit'])) {
        mct_ai_dosourceit();
        exit();
    }
    if (mct_ai_menudisp()) {
        $title = 'Get It & Source It Bookmarklets';
    } else { 
        $title = 'Get It Bookmarklet';
    }

    require_once('./admin-header.php');
    $source_it = get_js_code();
    $source_it = str_replace("&dogetit","&dosourceit",$source_it);
    
    ?>
    <script>
    //<![CDATA[
    jQuery(function() {
        jQuery( ".mct-ai-tabs #tabs" ).tabs();
    });
    //]]>
    </script>

    <div class="wrap">
    <?php //screen_icon('tools'); ?>
    <h2><?php echo esc_html( $title ); ?></h2>

    <?php if ( current_user_can('edit_posts') ) : ?>
    <div class="mct-ai-tabs" >
     <div id="tabs">
        <ul>
        <li><a href="#tabs-1">Get It</a></li>
        <?php if (current_user_can('edit_others_posts') && mct_ai_menudisp()) { ?>
        <li><a href="#tabs-2">Source It</a></li>
         <?php } //end current user can publish ?>
        </ul>
        <div id="tabs-1">
            <h2>Get It</h2>
                <p><?php _e('Get It is a bookmarklet: a little app that runs in your browser and lets you grab bits of the web. '
                        . 'Use Get It to save articles to your training posts or as draft posts as you are reading them in your browser, phone or tablet!
                    Now you can add all of the content you find while browsing the web, twitter and your social networks. ');
                       if (mct_ai_menudisp()) {
                           echo '<span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>';
                           echo mct_ai_helpvideo('getit'); 
                       } else {
                           echo '</p>';
                       }
                   ?>
                
                <h3>PC/Mac Instructions</h3>
                <p class="description"><?php _e('Drag-and-drop the following link to your bookmarks bar') ?></p>
                <p class="pressthis"><a onclick="return false;" href="<?php echo htmlspecialchars( get_js_code() ); ?>"><span><?php _e('Get It') ?></span></a></p>
               
                <p class="description"><?php _e('If your bookmarks toolbar is hidden or your browser does not allow you to drag and drop the link then:') ?></p>
                <p class="description"><?php _e('Highlight the Bookmark code in the box below then Ctrl-c/Command-c to copy the code. Open your Bookmarks/Favorites manager and create a new bookmark/favorite. Edit the name to Get It and save.  
                    Click Manage/Organize Bookmarks/Favorites and edit the Get It entry you just created.  Paste the code into the URL/Location/Address field using Ctrl-v/Command-v.
                    Save the entry') ?></p>
                <p><textarea rows="6" cols="120" ><?php echo htmlspecialchars( get_js_code() ); ?></textarea></p>
                <h3>iPhone or iPad Instructions</h3>
                <p class="description"><?php _e('Touch the code box above once (keyboard appears) then touch and hold until the magnifier 
                    appears and choose Select All then Copy.  
                    Add a Bookmark and set the title to Get It then save.  Now touch the bookmarks option again and choose Edit bookmarks from the top right.
                    and select the Get It bookmark you just created.
                    Touch the location box then the x and remove the old location.  Now Touch and Paste your previous copy into the bookmark.  
                    Press the Bookmarks button at the top to finish editing and then touch done in the upper right.') ?></p>
                <h3>Android Phone/Tablet Instructions</h3>
                <p class="description"><?php _e('Android phones work a little differntly in activating the bookmarklet.  
                    To create the bookmark in your Chrome browser touch the code box above until the Edit Text menu appears, 
                    choose Select All then Copy.  Touch the menu (three vertical dots) and then press the Star at the top menu bar.  
                    At the bottom of the screen touch the Edit link and then change the title to Get It. 
                    Then touch the URL box and clear out the URL and then paste in your code you copied. 
                    You can change your bookmarks folder.  When done just press the back arrow at the top') ?></p>
                <p class="description"><?php _e('To use the new bookmark, go to an article you want to capture in your browser.
                    Now touch the Address bar where you would see the address of the page.  Start typing in the name of your bookmark
                    (Get It or whatever you named it).  You will see options such as web pages show up in a list.  Look for the bookmark name
                    and below it will be text starting with "javascript ..." in blue.  Just choose that item and Get It will process your article.') ?></p>
            </div>
         <?php if (current_user_can('edit_others_posts') && mct_ai_menudisp()) { ?>
            <div id="tabs-2">
            <h2>Source It</h2>
                <p>Source It is a bookmarklet: a little app that runs in your browser and lets you grab feeds directly from a site!
                    Use Source It to grab a feed and load it into your Sources when you are visiting a site that you want MyCurator
                    to read each day.  
                    <span  class="button-primary" onclick="mctaishowvideo2()">Need Help?</span></p>
                <?php echo mct_ai_helpvideo('sourceit'); ?>
                
                <h3>PC/Mac Instructions</h3>
                <p class="description"><?php _e('Drag-and-drop the following link to your bookmarks bar') ?></p>
                <p class="pressthis"><a onclick="return false;" href="<?php echo htmlspecialchars( $source_it ); ?>"><span><?php _e('Source It') ?></span></a></p>
               
                <p class="description"><?php _e('If your bookmarks toolbar is hidden or your browser does not allow you to drag and drop the link then:') ?></p>
                <p class="description"><?php _e('Highlight the Bookmark code in the box below then Ctrl-c/Command-c to copy the code. Open your Bookmarks/Favorites manager and create a new bookmark/favorite. Edit the name to Source It and save.  
                    Click Manage/Organize Bookmarks/Favorites and edit the Source It entry you just created.  Paste the code into the URL/Location/Address field using Ctrl-v/Command-v.
                    Save the entry') ?></p>
                <p><textarea rows="6" cols="120" ><?php echo htmlspecialchars( $source_it ); ?></textarea></p>
                <h3>iPhone or iPad Instructions</h3>
                <p class="description"><?php _e('Touch the code box above once (keyboard appears) then touch and hold until the magnifier 
                    appears and choose Select All then Copy.  
                    Add a Bookmark and set the title to Source it It then save.  Now touch the bookmarks option again and choose Edit bookmarks from the top right.
                    and select the Source It bookmark you just created.
                    Touch the location box then the x and remove the old location.  Now Touch and Paste your previous copy into the bookmark.  
                    Press the Bookmarks button at the top to finish editing and then touch done in the upper right.') ?></p>
                <h3>Android Phone/Tablet Instructions</h3>
                <p class="description"><?php _e('Android phones work a little differntly in activating the bookmarklet.  
                    To create the bookmark in your Chrome browser touch the code box above until the Edit Text menu appears, 
                    choose Select All then Copy.  Touch the menu (three vertical dots) and then press the Star at the top menu bar.  
                    At the bottom of the screen touch the Edit link and then change the title to Get It. 
                    Then touch the URL box and clear out the URL and then paste in your code you copied. 
                    You can change your bookmarks folder.  When done just press the back arrow at the top') ?></p>
                <p class="description"><?php _e('To use the new bookmark, go to an article you want to capture in your browser.
                    Now touch the Address bar where you would see the address of the page.  Start typing in the name of your bookmark
                    (Get It or whatever you named it).  You will see options such as web pages show up in a list.  Look for the bookmark name
                    and below it will be text starting with "javascript ..." in blue.  Just choose that item and Get It will process your article.') ?></p>
            </div>
         <?php } //end current user can publish ?>
        </div>
    </div>
    </div>
    <?php
    endif;
}

function get_js_code(){
    $link = "javascript:
var d=document,
w=window,
e=w.getSelection,
k=d.getSelection,
x=d.selection,
s=(e?e():(k)?k():(x?x.createRange().text:0)),
f='" . admin_url('admin.php?page=mycurator/MyCurator.php_getit&dogetit') . "',
l=d.location,
e=encodeURIComponent,
u=f+'&u='+e(l.host+l.pathname+l.search)+'&t='+e(d.title)+'&s='+e(s)+'&v=4';
a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=520,height=600'))l.href=u;};
if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0); else a();
void(0)";

    $link = str_replace(array("\r", "\n", "\t"),  '', $link);

    return $link;    
}
function mct_ai_logspage() {
    //Display the logs page, with dropdowns for filtering
    
    global $wpdb, $ai_logs_tbl, $ai_topic_tbl, $blog_id;
    
    $maxrow = 25;
    $alter = true;
    //Set current page from get
    $currentPage = 1;
    $topic = '';
    $source = '';
    if (isset($_GET['paged'])){
        $currentPage = $_GET['paged'];
    }
    //Set up the filter variables
    if (isset($_REQUEST['topic'])){
        if ($_REQUEST['topic'] == 'ALL'){
            $topic = '';
        } else {
            $topic = urldecode($_REQUEST['topic']);
        }
    }
    if (isset($_REQUEST['source'])){
        if ($_REQUEST['source'] == 'ALL'){
            $source = '';
        } else {
            $source = urldecode($_REQUEST['source']);
        }
    }
    if (isset($_REQUEST['type'])){
        if ($_REQUEST['type'] == 'ALL'){
            $type = '%';
        } else {
            $type = $_REQUEST['type'];
        }
    } else {
        $type = MCT_AI_LOG_ACTIVITY;  //default type
    }
    if ($type == MCT_AI_LOG_PROCESS) $type = '%'; //Get all records
    
    if (isset($_REQUEST['Filter'])){
        $currentPage = 1;  //reset paging when a filter selected
    }
    if (isset($_REQUEST['Reset-Log'])){
        mct_ai_clearlogs();
    }
    //Get total rows available
    $sql = "SELECT COUNT(*) as myCount FROM " .$ai_logs_tbl;
    $sql .= " WHERE `logs_type` LIKE '$type'";
    if ($source != '') $sql.= " AND `logs_source` LIKE '$source'";
    if ($topic != '' ) $sql.= " AND `logs_topic` LIKE '$topic'";
    $counts = $wpdb->get_row($sql,ARRAY_A);
    $myCount = $counts['myCount'];
    
    //Get all topics for dropdown
    $sql = "SELECT `topic_name`
            FROM $ai_topic_tbl ORDER BY topic_name";
    $topic_vals = $wpdb->get_results($sql, ARRAY_A);
    if ($blog_id == 1) $topic_vals[] = array('topic_name' => 'Blog');
    //Get restart transient for display
    $restart = get_transient('mct_ai_last_feed');
    if ($restart && $type == MCT_AI_LOG_PROCESS) {
        $restart_arr = explode(':',$restart);
        $rtopic = $wpdb->get_var("select topic_name from $ai_topic_tbl where topic_id = '$restart_arr[0]'");
        $src_term = get_term($restart_arr[1],'link_category');
        $rsource = $src_term->name;
        $link_obj = get_bookmark($restart_arr[2]);
        $rfeed = $link_obj->link_name;
    }
    ?>
    <div class='wrap'>
    <?php //screen_icon('plugins'); ?>
    <h2>MyCurator Logs</h2>    
     <p>MyCurator keeps logs of what it does with each article found in your feed sources.  If an article is not posted to your Training Posts
         you can read the Message column to see why.  You can choose Error from the drop down named Article to see any feed or processing 
         errors.  You can choose Process from the Article drop down to see each article and error and how MyCurator is processing in the background. 
         <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
        <?php echo mct_ai_helpvideo('logs'); ?>
     <p>You can reset the logs and the filter that keeps articles from being re-read.  If you do this, you will most likely
         get duplicate articles on your training page as previous articles are reprocessed.  Use this if you have made changes 
         to your Topics or Formatting options and wish to reset MyCurator to process all articles again.
         <form id="Reset" method="post" >
         <input name="Reset-Log" value="Reset Logs" type="submit" class="button-secondary" onclick="return confirm('Are you sure you want to Reset MyCurator Logs?  You may end up with a lot of duplicate articles on your training page!');" >
         </form></p>
    <?php
       if ($restart && $type == '%') {
           echo "<p>Restart with $rtopic - $rsource - $rfeed </p>";
       }
       print("<div class=\"tablenav\">"); 
       $qargs = array(
           'paged' => '%#%', 
           'topic' => urlencode($topic),
           'type' => $type);
       $page_links = paginate_links( array(
		'base' => add_query_arg($qargs ) ,
		'format' => '',
		'total' => ceil($myCount/$maxrow),
		'current' => $currentPage
	));
	//Pagination display
	if ( $page_links )
		echo "<div class='tablenav-pages'>$page_links</div>";
        //Select Topic
        echo '<div class = "alignleft" >';
        echo '<form id="Select" method="post" >';
        echo '<select name="topic">';
        echo '<option ';
        if ($topic == '') echo 'SELECTED';
        echo ' value="ALL">View all Topics</option>';
        foreach ($topic_vals as $tops){
            $topicnam = $tops['topic_name'];
            echo '<option value="'.$topicnam.'" '.selected($topic,$topicnam,false).'>'.substr($topicnam,0,30).'</option>';
        }
        echo '</select>';
        //Select Sources
        $sql = "SELECT distinct logs_source FROM $ai_logs_tbl WHERE logs_source is not null ORDER BY logs_source";
        $src_vals = array();
        $src_vals = $wpdb->get_results($sql, ARRAY_A);
        echo '<select name="source">';
        echo '<option ';
        if ($source == '') echo 'SELECTED';
        echo ' value="ALL">View all Sources</option>';
        foreach ($src_vals as $src){
            $srcnam = $src['logs_source'];
            echo '<option value="'.$srcnam.'" '.selected($source,$srcnam,false).'>'.substr($srcnam,0,30).'</option>';
        }
        //Select log type
        echo '</select>';
        echo '<select name="type">';
        echo '<option value="'.MCT_AI_LOG_PROCESS.'" '.selected($type,MCT_AI_LOG_PROCESS,false).'>'.MCT_AI_LOG_PROCESS.'</option>';
        echo '<option value="'.MCT_AI_LOG_ERROR.'" '.selected($type,MCT_AI_LOG_ERROR,false).'>'.MCT_AI_LOG_ERROR.'</option>';
        echo '<option value="'.MCT_AI_LOG_ACTIVITY.'" '.selected($type,MCT_AI_LOG_ACTIVITY,false).'>'.MCT_AI_LOG_ACTIVITY.'</option>';
        echo '<option value="'.MCT_AI_LOG_REQUEST.'" '.selected($type,MCT_AI_LOG_REQUEST,false).'>'.MCT_AI_LOG_REQUEST.'</option>';
        echo '</select>';
        echo '<input name="Filter" value="Select Filter" type="submit" class="button-secondary">';
        echo '</form></div>';
        
        //Get Values from Db
        $bottom = ($currentPage - 1) * $maxrow;
	$top = $currentPage * $maxrow;
        $sql = "SELECT `logs_date`, `logs_topic`, `logs_type`, `logs_msg`, `logs_url`, `logs_source`, `logs_proc_id`, `logs_proc_cnt`
            FROM $ai_logs_tbl ";
        $sql .= " WHERE `logs_type` LIKE '$type'";
        if ($source != '') $sql.= " AND `logs_source` LIKE '$source'";
        if ($topic != '' ) $sql.= " AND `logs_topic` LIKE '$topic'";
        $sql .= " ORDER BY `logs_proc_id` DESC, `logs_proc_cnt` ASC LIMIT " . $bottom . "," . $maxrow;
        $edit_vals = array();
        $edit_vals = $wpdb->get_results($sql, ARRAY_A);
        ?>
        </div>
        <style>
            th.mct-log-date {width: 10%; }
            th.mct-log-topic {width: 15%; }
            th.mct-log-type {width: 10%; }
            th.mct-log-msg {width: 20%; }
            th.mct-log-src {width: 10%; }
            th.mct-log-url {width: 25%; }
            th.mct-log-id {width: 5%; }
            th.mct-log-cnt {width: 5%; }
        </style>
        <table class="widefat" >
            <thead>
                <tr>
                <th class="mct-log-date">Date</th>
                <th class="mct-log-topic">Topic</th>
                <th class="mct-log-type">Type</th>
                <th class="mct-log-msg">Message</th>
                <th class="mct-log-src">Source</th>
                <th class="mct-log-url">URL</th>
                <?php if ($type == '%') { ?>
                <th class="mct-log-id">Proc</th>
                <th class="mct-log-cnt">Line</th>
                <?php } ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($edit_vals as $row){
                echo('<tr');
                if ($alter) {
		 	$alter = false;
		 	print(" class='alternate' ");
		} else {
			$alter = true;
		}
                echo ('>');
                echo('<td>'.$row['logs_date'].'</td>');
                echo('<td>'.$row['logs_topic'].'</td>');
                echo('<td>'.$row['logs_type'].'</td>');
                echo('<td>'.$row['logs_msg'].'</td>');
                echo('<td>'.$row['logs_source'].'</td>');
                //if (!empty($row['logs_url'])){
                    echo('<td><a href="'.$row['logs_url'].'" >'.$row['logs_url'].'</a></td>');
                //}
                if ($type == '%') {
                    echo('<td>'.$row['logs_proc_id'].'</td>');
                    echo('<td>'.$row['logs_proc_cnt'].'</td>');
                }
                echo('</tr>');
            } ?>
           </tbody>
        </table>
       <?php
       //Pagination display
	if ( $page_links ) echo "<div class='tablenav'><div class='tablenav-pages alignright'>$page_links</div></div>";
}

function mct_ai_logreport(){
    //Print out pie chart based on activity log
    //Use google pie chart api as an image
    global $wpdb, $mct_ai_optarray, $ai_logs_tbl;
    $ruri = $_SERVER['REQUEST_URI'];
    $logspage = str_replace('_Report','_Logs',$ruri);
    //Heading
    echo "<div class='wrap'>";
    //screen_icon('plugins');
    echo "<h2>MyCurator Performance Report</h2>";
    echo "<p>Summary of items Found in the Logs for the last ".$mct_ai_optarray['ai_log_days']." days ordered by Topic and by Source.</p>";
    echo '<p><strong><a href="'.admin_url('edit.php?post_type=target_ai').'">Articles Posted</a></strong> - These articles will have been posted to your Training Posts page.</p>';
    echo '<p><strong><a href="'.$logspage.'" >Articles Not Posted</a></strong> - These articles did not meet your Topic Criteria.</p>';
    echo "<p><strong><a href='".$logspage."&type=ERROR'>Errors</a></strong> - The count of errors processing articles or sources.</p>";
    echo "<p><strong><a href='".$logspage."&type=REQUEST'>Articles Requested</a></strong> - These articles have been requested from our Cloud Service and will be processed within an hour.</p>";
    echo "<style>
            th.mct-log-topic {width: 40%; }
            th.mct-log-good {width: 15%; }
            th.mct-log-notsure {width: 15%; }
            th.mct-log-bad {width: 15%; }
            th.mct-log-nopost {width: 15%; }
        </style>";
    $alter = true;
    $cnts = array();
    $cnts['good'] = $cnts['nopost'] = $cnts['error'] = $cnts['request'] = 0;
    $tot = 0;
    //First Topic then Source using a 2 count While
    //
    $i = 0;
    while ($i <= 1) {
        //Get Data
        if ($i == 0) {
            $sql = "SELECT `logs_msg`, `logs_topic`, `logs_type` 
                    FROM $ai_logs_tbl WHERE logs_type != '".MCT_AI_LOG_PROCESS. "'  
                    ORDER BY logs_topic ASC";
            $logs = $wpdb->get_results($sql, ARRAY_A);
            $this_col = 'logs_topic';
            $this_hdr = "By Topic";
        } else {
            $sql = "SELECT `logs_msg`, `logs_source`, `logs_type` 
                    FROM $ai_logs_tbl WHERE logs_type != '".MCT_AI_LOG_PROCESS. "'  
                    ORDER BY logs_source ASC";
            $logs = $wpdb->get_results($sql, ARRAY_A);
            $this_col = 'logs_source';
            $this_hdr = "By Source";
        }
        $i++;
        //Summary Heading
         echo "   <table class='widefat' >
            <thead>
                <tr>
                <th class='mct-log-topic'>".$this_hdr."</th>
                <th class='mct-log-good'>Articles Posted</th>
                <th class='mct-log-notsure'>Articles Not Posted</th>
                <th class='mct-log-bad'>Errors</th>
                <th class='mct-log-nopost'>Articles Requested</th>
                </tr>
            </thead>
            <tbody>";
        $this_break = '';
        foreach ($logs as $log){
            if ($this_break == '') $this_break = $log[$this_col];
            if ($log[$this_col] != $this_break) {
                //New Topic Print out values and reset
                echo('<tr');
                if ($alter) {
                        $alter = false;
                        print(" class='alternate' ");
                } else {
                        $alter = true;
                }
                echo ">";
                echo('<td>'.$this_break.'</td>');
                echo('<td>'.strval($cnts['good']).'</td>');
                echo('<td>'.strval($cnts['nopost']).'</td>');
                echo('<td>'.strval($cnts['error']).'</td>');
                echo('<td>'.strval($cnts['request']).'</td>');
                echo "</tr>";
                $cnts['good'] = $cnts['nopost'] = $cnts['error'] = $cnts['request'] = 0;
                $this_break = $log[$this_col];
            }
            $tot += 1;
            if ($log['logs_type'] ==  MCT_AI_LOG_ACTIVITY) {
                if (stripos($log['logs_msg'],'Live') !== false) {
                    $cnts['good'] += 1;
                }
                elseif (stripos($log['logs_msg'],'good') !== false) {
                    $cnts['good'] += 1;
                }
                elseif (stripos($log['logs_msg'],'bad') !== false) {
                    $cnts['good'] += 1;
                }
                elseif (stripos($log['logs_msg'],'not sure') !== false) {
                    $cnts['good'] += 1;
                }
                elseif (stripos($log['logs_msg'],'New  post') !== false) {
                    $cnts['good'] += 1;
                }
                else {
                    $cnts['nopost'] += 1;
                }
            }
            if ($log['logs_type'] ==  MCT_AI_LOG_ERROR) $cnts['error'] += 1;
            if ($log['logs_type'] ==  MCT_AI_LOG_REQUEST) $cnts['request'] += 1;
        }
        //Print final line
        echo('<tr><td>'.$this_break.'</td>');
        echo('<td>'.strval($cnts['good']).'</td>');
        echo('<td>'.strval($cnts['nopost']).'</td>');
        echo('<td>'.strval($cnts['error']).'</td>');
        echo('<td>'.strval($cnts['request']).'</td>');
        echo "</tr>";
        echo "</br></br>";
        $cnts['good'] = $cnts['nopost'] = $cnts['error'] = $cnts['request'] = 0;
    } //end while
    echo '</tbody></table></div>';
    
}


function mct_ai_createdb(){
    //This function creates our tables, uses dbdelta for easier updating
    
    global $wpdb, $ai_topic_tbl, $ai_postsread_tbl, $ai_sl_pages_tbl, $ai_logs_tbl, $mct_ai_fdvals_tbl;
    
    //Use WordPress defaults (from schema.php)
    $charset_collate = '';

    if ( ! empty($wpdb->charset) )
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    if ( ! empty($wpdb->collate) )
            $charset_collate .= " COLLATE $wpdb->collate";
    
    //Topics table holds all of the topic data and the classifier db for this topic
    //NOTE: update MyCurator_getit.php default null topic with any changes here
    $sql = "CREATE TABLE $ai_topic_tbl (
            topic_id int(11) NOT NULL AUTO_INCREMENT,
            topic_name varchar(190) NOT NULL,
            topic_slug varchar(200) NOT NULL, 
            topic_status varchar(20) NOT NULL,
            topic_type varchar(20) NOT NULL,
            topic_search_1 text,
            topic_search_2 text,
            topic_exclude text,
            topic_sources longtext,
            topic_aidbfc longtext,
            topic_aidbcat longtext,
            topic_skip_domains longtext,
            topic_min_length int(11),
            topic_cat int(11),
            topic_tag int(11),
            topic_tag_search2 char(1),
            topic_options text,
            topic_last_run DATETIME,
            PRIMARY KEY  (topic_id),
            KEY topic_name (topic_name)
    ) $charset_collate;";
    
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    //Posts read table keeps all of the posts read, including the readable page, from the rss feeds, for re-use by other topics and over time
    $sql = "CREATE TABLE $ai_postsread_tbl (
            pr_id int(11) NOT NULL AUTO_INCREMENT,
            pr_url varchar(1000) NOT NULL,
            pr_date DATETIME NOT NULL,
            pr_topics varchar(500),
            pr_page_content longtext,
            PRIMARY KEY  (pr_id),
            KEY pr_url (pr_url(190))
    ) $charset_collate;";
    dbDelta($sql);
    //Logs table keeps all of the logs for MyCurator
    $sql = "CREATE TABLE $ai_logs_tbl (
            logs_id int(11) NOT NULL AUTO_INCREMENT,
            logs_date DATETIME NOT NULL,
            logs_type varchar(20) NOT NULL,
            logs_topic varchar(200) NOT NULL,
            logs_url varchar(1000),
            logs_msg varchar(200) NOT NULL,
            logs_aiclass varchar(20),
            logs_aigood FLOAT(5,4),
            logs_aibad FLOAT(5,4),
            logs_source varchar(255),
            logs_proc_id int(11),
            logs_proc_cnt int(11),
            PRIMARY KEY  (logs_id)
    ) $charset_collate;";
    dbDelta($sql);
    //pages table keeps the readable page for each post
    $sql = "CREATE TABLE $ai_sl_pages_tbl (
            sl_page_id int(11) NOT NULL AUTO_INCREMENT,
            sl_page_content longtext NOT NULL,
            sl_post_id int(11),
            PRIMARY KEY  (sl_page_id),
            KEY sl_post_id (sl_post_id)
    ) $charset_collate;";
    dbDelta($sql);
}

function mct_ai_run_mycurator(){
    //starts the mycurator processing when triggered by cron
    global $mct_ai_optarray;
    
    if (!empty($mct_ai_optarray['ai_no_procpg'])) {
        mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Cron Starting MyCurator', 'Full Site');
        mct_ai_process_site(true);
        exit();
    }
     //use curl so we don't have to worry about local php implementations
    if (is_multisite()){
        global $blog_id;
        $url = plugins_url('MyCurator_process_page.php',__FILE__).'/?blogid='.strval($blog_id);
        if ($blog_id != 1) { //need an absolute url for curl
            $siteurl = get_site_url(1)."/";
            $pattern = "{(".$siteurl.")([^/]*)/(.*)$}";
            $url = preg_replace($pattern,"\\1\\3",$url); //remove blog path
        }
    } else {
        $url = plugins_url('MyCurator_process_page.php',__FILE__);
    }
    mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Cron Starting MyCurator', $url);
    
    $response = wp_remote_get($url);
    if( is_wp_error( $response ) && stripos($response->get_error_message(),'timed out') === false ) {//ignore timeout - we expect it
        mct_ai_log('Blog',MCT_AI_LOG_PROCESS, 'Error '.$response->get_error_message()." starting MyCurator",$url);
    }
    
    exit();

}

function mct_ai_set_cron_sched($schedules){
    //Set up every 3 and 6 hour schedules for cron
    //return $schedules;
    $schedules['mct3hour'] = array(
        'interval' => 10800,
        'display' => 'Every 3 Hours'
    );
    $schedules['mct6hour'] = array(
        'interval' => 21600,
        'display' => 'Every 6 Hours'
    );   
    return $schedules;
}

function mct_ai_showplan($display=true, $upgrade=true){
    //Show plan limits and current counts, if display is false, return whether topic can be used
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray;
    $imgbad = plugins_url('thumbs_down.png',__FILE__);
    $imggood = plugins_url('thumbs_up.png',__FILE__);
    //return false on no token, since we won't have plan
    if (empty($mct_ai_optarray['ai_cloud_token'])) return ($display) ? '<p><strong>Error - You need an API Key before you can add Topics</strong></p>' : false;
    //Get the plan
    if (empty($mct_ai_optarray['ai_plan'])){
        return ($display) ? "<p><strong><img src='$imgbad' ></img> Error - A problem was encountered connecting with cloud services.  "
                . "Go to the MyCurator Logs menu item and choose Error from the drop down named Articles and click Select Filter. "
                . "<p>If the Latest Error is 'http error: 0' most likely your Host is blocking access to our cloud server.  Request that "
                . "they allow PHP cURL access to our host at http://tgtinfo.net.</p>  <p>For other errors, report the latest "
                . "error message, or a screen shot, to  MyCurator support at support@target-info.com.</strong></p>" : false;
    }
    $plan = unserialize($mct_ai_optarray['ai_plan']);
    if ($plan['max'] == -1) {
        //error, invalid token or expired
        return ($display) ? "<p><strong><img src='$imgbad' ></img> Error - ".$plan['name']." Try to correct the error and then try again.  "
                . "If you are still having problems, email error message to MyCurator support at support@target-info.com.</strong></p>" : false;
    }
    if ($plan['max'] == 0){
        return ($display) ? "<h4><img src='$imggood' ></img> Business Plan with Unlimited Topics and Sources</h4>" : true;
    }
    //Get current topic counts
    $sql = "Select count(*) From $ai_topic_tbl";
    $cur_cnt = $wpdb->get_var($sql);
    if (!$display) {
        return ($cur_cnt >= $plan['max']) ? false : true;
    }
    if (empty($plan['maxsrc'])) {
        $source = "Unlimited";
    } else {
        $source = $plan['maxsrc'];
    }
    //Get Token
    $token = $mct_ai_optarray['ai_cloud_token'];
    //Set up the display
    ob_start();
    ?>
    <?php echo "<h4><img src='$imggood' ></img> ".$plan['name']; ?> with <?php echo $plan['max']; ?> Topics maximum and <?php echo $cur_cnt; ?> currently used and maximum of <?php echo $source; ?> Sources</h4>
    <?php 
    if ($upgrade && current_user_can('manage_options')) { 
        if (stripos($plan['name'],'ind') !== false) {
            echo '<p>If you would like to set up more Topics or Sources than your current plan allows, or install MyCurator on more sites, <a href="http://www.target-info.com/myaccount/?token='.$token.'" >Upgrade to a Pro or Business Plan</a></p>';
        } else { //must be pro, business already returned
            echo '<p>If you would like to set up more Topics or Sources than your current plan allows, or install MyCurator on more sites, <a href="http://www.target-info.com/myaccount/?token='.$token.'" >Go to My Account</a> on our site</p>';
        }
    }
    return ob_get_clean();
}

function mct_ai_menudisp(){
    global $mct_ai_optarray;
    
    $name = mct_ai_showplan(false, false);
    if (stripos($name,'error') !== false) return true;
    if (stripos($name,'individual plan') !== false) return true;
    
    if (!empty($mct_ai_optarray['ai_hide_menu']) && !current_user_can('manage_options')) return false;
    return true;
}

function mct_ai_ispaid(){
    global $mct_ai_optarray;
    
    $name = mct_ai_showplan(true, false);
    if (stripos($name,'error') !== false) return false;
    if (stripos($name,'individual plan') !== false) return false;
    return true;
}

function mct_ai_clearlogs(){
    //Clears postsread and logs to reset mycurator
    global $ai_postsread_tbl, $mct_ai_optarray, $wpdb, $ai_logs_tbl;
    
    //clear out Postsread table
    $sql = "DELETE FROM $ai_postsread_tbl";
    $pr_row = $wpdb->query($sql);

    //clear out ai_log
    $sql = "DELETE FROM $ai_logs_tbl";
    $pr_row = $wpdb->query($sql);

}

function mct_ai_get_topic_options($edit_vals){
    //Break out options from a topic option field
    $allopts = maybe_unserialize($edit_vals['topic_options']);
    $edit_vals['opt_post_user'] = empty($allopts['opt_post_user']) ? "" : $allopts['opt_post_user'] ;
    $edit_vals['opt_image_filter'] = empty($allopts['opt_image_filter']) ? "" : $allopts['opt_image_filter'];
    $edit_vals['opt_post_ctype'] = (empty($allopts['opt_post_ctype'])) ? "not-selected" : $allopts['opt_post_ctype'];
    $edit_vals['opt_post_ctax'] = (empty($allopts['opt_post_ctax'])) ? "" : $allopts['opt_post_ctax'];
    $edit_vals['opt_post_ctaxval'] = (empty($allopts['opt_post_ctaxval'])) ? "" : $allopts['opt_post_ctaxval'];
    $edit_vals['opt_topic_start'] = empty($allopts['opt_topic_start']) ? "" : $allopts['opt_topic_start'];
    $edit_vals['opt_topic_end'] = empty($allopts['opt_topic_end']) ? "" : $allopts['opt_topic_end'];
    return $edit_vals;
}

function mct_ai_dogetit() {
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray, $user_id;
    
    if ( ! current_user_can('publish_posts') )
	wp_die( __( 'User not authorized to publish posts' ) );
    $cu = wp_get_current_user();
    
    // For submitted posts.
    if ( isset($_REQUEST['action']) && 'post' == $_REQUEST['action'] ) {
            check_admin_referer('MyCurator_getit');//MOD - use correct admin referer
            $posted = $post_ID = mct_ai_do_get_it();
            if ($posted && (isset($_REQUEST['draftedit']) || isset($_REQUEST['draftedit-cat']))) {
                $edit_url = get_edit_post_link( $posted, array('edit' => '&amp;'));
                ?>
                <script type='text/javascript'>
                var newurl = '<?php echo $edit_url; ?>';
                window.location.assign(newurl);
                </script>
                <?php
                exit();
            }
    } else {
            $post = get_default_post_to_edit('target_ai', true);  //Set to custom post type for MyCurator
            $post_ID = $post->ID;
    }

    // Set Variables
    $title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';

    $selection = '';
    if ( !empty($_GET['s']) ) {
            $selection = str_replace('&apos;', "'", stripslashes($_GET['s']));
            $selection = trim( htmlspecialchars( html_entity_decode($selection, ENT_QUOTES) ) );
    }

    if ( ! empty($selection) ) {
            $selection = preg_replace('/(\r?\n|\r)/', '</p><p>', $selection);
            $selection = '<p>' . str_replace('<p></p>', '', $selection) . '</p>';
    }

    $url = isset($_GET['u']) ? esc_url($_GET['u']) : '';
    $image = isset($_GET['i']) ? $_GET['i'] : '';

    $default = empty($mct_ai_optarray['ai_getit_tab']) ? 0 : 1; 
    $default = !empty($mct_ai_optarray['ai_getit_pub']) ? 2 : $default; 
    ?>
    <script type='text/javascript' src='<?php echo includes_url('js/jquery/ui/core.min.js'); ?>'></script>
    <script type='text/javascript' src='<?php echo includes_url('js/jquery/ui/widget.min.js'); ?>'></script>
    <script type='text/javascript' src='<?php echo includes_url('js/jquery/ui/tabs.min.js'); ?>'></script>
    <script type="text/javascript">
    jQuery(function() {
        var deftab = <?php echo $default; ?>;
        //jQuery('#publish, #submit, #draft').click(function() { jQuery('#saving').css('display', 'inline'); });
        jQuery( ".mct-ai-tabs #tabs" ).tabs({ active: deftab });
    });
    </script>
    <div id="poststuff" class="metabox-holder">
    <form action="admin.php?page=mycurator/MyCurator.php_getit&dogetit&action=post" method="post">   <!-- MOD post to MyCurator_getit.php -->
    
        <?php wp_nonce_field('MyCurator_getit') ?>  <!-- MOD nonce get-it -->
        <!-- Mod: Create hidden fields for selection and url so they can be added to content if page doesn't render -->
        <input type="hidden" id="selection" name="selection" value="<?php echo strip_tags($selection,'<p><a>'); ?>" />
        <input type="hidden" id="save-url" name="save-url" value="<?php echo  $url ; ?>" />
        <input type="hidden" id="title" name="title" value="<?php echo  $title ; ?>" />
        
	<div class="mct-posting">

		<div id="wphead">
			<!-- <img id="header-logo" src="<?php echo esc_url( includes_url( 'images/blank.gif' ) ); ?>" alt="" width="16" height="16" /> -->
			<h1 id="site-heading">
				<a href="<?php echo get_option('home'); ?>/" target="_blank">
					<span id="site-title"><?php bloginfo('name'); ?></span>
				</a>
			</h1>
		</div>

		<?php
		if ( isset($posted) && intval($posted) ) {
			$post_ID = intval($posted); ?>
			<div id="message" class="updated">
			<p><strong><?php _e('Your post has been saved.'); ?></strong>
			<a href="#" onclick="window.close();"><?php _e('Close Window'); ?></a></p>
                        
			</div>
		        <script type="text/javascript">setTimeout('self.close();',2000);</script>
                       
                  <?php exit(); } 
                  if (isset($posted)) { ?>
                      <div id="message" class="error">
			<p><strong><?php _e('Article was NOT saved.'); ?></strong>
			<a href="#" onclick="window.close();"><?php _e('Close Window'); ?></a></p>
                        
			</div>
                  <?php exit(); } ?>

		<div id="titlediv">
			<div class="titlewrap">
				<?php echo esc_attr($title);?>
			</div>
		</div>

        <br />               
        <?php 
        //Get Topics
        if (!empty($mct_ai_optarray['MyC_version'])){
            $sql = "SELECT `topic_name`, `topic_options` FROM $ai_topic_tbl WHERE topic_status != 'Inactive' ORDER BY topic_name";
            $topics = $wpdb->get_results($sql, ARRAY_A); 
            if (!empty($topics) && !current_user_can('edit_others_posts')) {
                //Reduce topics to what this author can see
                $goodtopics= array();
                foreach ($topics as $key => $topic) { 
                    $topic = mct_ai_get_topic_options($topic);
                    if ($topic['opt_post_user'] == $user_login) $goodtopics[] = $topic;
                }
                $topics = $goodtopics;
            }
        } else {
            $topics = '';
        }
        //Get Categories
        $cats = array (
            'orderby' => 'name',
            'hide_empty' => FALSE,
            'name' => 'post_category'
        );
        //Get notebooks
        $args = array(
            'numberposts'     => -1,
            'orderby'         => 'post_title',
            'order'           => 'DESC',
            'post_type'       => 'mct_notebk',
            'post_status'     => 'publish'); 
        //Check if author
        if (! current_user_can('edit_others_posts')) {
            $args['author'] = $user_ID;
        }
        $notebks = get_posts($args);
        $tchk = count($topics) > 5 ? false : true;
        $nchk = count($notebks) > 1 ? false : true;
        ?>
        <div class="mct-ai-tabs" >
         <div id="tabs">
            <ul>
            <?php if (!empty($topics)) echo '<li><a href="#tabs-1">Training</a></li>'; ?>
            <?php if (empty($topics)) echo '<li><a href="#tabs-2">Post</a></li>'; ?>
            <li><a href="#tabs-3">Notebooks</a></li>
            <?php if (!empty($mct_ai_optarray['ai_getit_pub'])) echo '<li><a href="#tabs-4">Publish</a></li>'; ?>
            </ul>
            <?php if (!empty($topics)) { ?>
            <div id="tabs-1">     
                <div id="categorydiv" class="postbox">
                    <h3 class="hndle"><?php _e('Topics') ?></h3>
                    <div class="inside">
                    <div id="taxonomy-category" class="categorydiv">
                        <?php 
                        if ($tchk) {
                            $check = "checked";
                        } else {
                            echo '<select name="post_topic">';
                        }
                        foreach ($topics as $topic) { 
                            if ($tchk) {
                                echo '<p><input name="post_topic" type="radio" value="'.$topic['topic_name'].'" '.$check.' /> '.$topic['topic_name'].'</p>';
                                $check = "";
                            } else {    
                                echo '<option value="'.$topic['topic_name'].'" >'.$topic['topic_name'].'</option>';
                            }
                        }
                        if (!$tchk) echo '</select>';
                        ?>
                    </div>
                    </div>
                 </div>
                <input name="training" id="training" value="Save to Training" type="submit" class="button-primary">
                <input name="draftedit" id="draftedit" value="Save Draft & Edit" type="submit" class="button-primary" style="float:right">
                <input name="draft" id="draft" value="Save as Draft" type="submit" class="button-primary" style="float:right">
                <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" id="saving" style="display:none;" />
            </div>
            <?php } //!empty topics ?>
            <?php if (empty($topics)) { ?>
            <div id="tabs-2">
                <div id="categorydiv" class="postbox">
                    <h3 class="hndle"><?php _e('Category') ?></h3>
                    <div class="inside">
                    <div id="taxonomy-category" class="categorydiv">
                        <?php wp_dropdown_categories($cats); ?>   
                    </div>
                    </div>
                </div>
                <input name="draft-cat" id="draft-cat" value="Save as Draft" type="submit" class="button-primary" >
                
                <input name="draftedit-cat" id="draftedit-cat" value="Save Draft & Edit" type="submit" class="button-primary" >
                 
                <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" id="saving" style="display:none;" />
             </div>
             <?php } //empty topics ?>
             <div id="tabs-3">
                <div id="categorydiv" class="postbox">
                    <h3 class="hndle"><?php _e('Notebooks') ?></h3>
                    <div class="inside">
                    <div id="taxonomy-category" class="categorydiv">
              <?php if (!empty($notebks)) { 
                        if ($nchk) {
                            $check = "checked";
                        } else {
                            echo '<select name="notebk">';
                        }
                        foreach ($notebks as $notebk) { 
                            if ($nchk) {
                                echo '<p><input name="notebk" type="radio" value="'.$notebk->ID.'" '.$check.' /> '.$notebk->post_title.'</p>';
                                $check = "";
                            } else {    
                                echo '<option value="'.$notebk->ID.'" >'.$notebk->post_title.'</option>';
                            }
                        } 
                        if (!$nchk) echo '</select>';
                        }
                         mct_ai_getplan(); 
                         if (mct_nb_showlimits(false, false)) {
                             if (!empty($notebks)) echo " OR ";
                             echo 'Add to New Notebook: <input name="newnb" type="text" id="mct-nb-newnb" value="" size="50">'; 
                         }
                        ?>
                        <br>Notes:<br>
                        <textarea name="notes" rows="3" cols="50" ></textarea>
                    </div>
                    </div>
                </div>
                <input name="notebook" id="notebook" value="Save to Notebook" type="submit" class="button-primary" >
                <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" id="saving" style="display:none;" />
            </div>
            <?php if (!empty($mct_ai_optarray['ai_getit_pub'])) { ?>
            <div id="tabs-4">
                <div id="categorydiv" class="postbox">
                    <h3 class="hndle"><?php _e('Category') ?></h3>
                    <div class="inside">
                    <div id="taxonomy-category" class="categorydiv">
                        <?php wp_category_checklist(); ?>   
                    </div>
                    </div>
                </div>
                <input name="publish" id="publish-cat" value="Publish Post" type="submit" class="button-primary" >
                <input name="publish-draft" id="publish-draft-cat" value="Draft Post" type="submit" class="button-primary" >
                <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" id="saving" style="display:none;" />
             </div>
             <?php } //empty Get It Publish ?>
         </div>
        </div>
    </div>
    </form>
    </div>
    <script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
    </body>
    </html>
    <?php
}

function mct_ai_do_get_it() {
    //Use the cloud process to save a training post 
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray, $user_id, $proc_id;
    include_once('MyCurator_local_proc.php');
    
    $proc_id = time();
    $url = $_POST['save-url'];
    if (!empty($_POST['training']) || !empty($_POST['draft']) || !empty($_POST['draftedit'])) {
        //Get the Topic
        $tname = sanitize_text_field($_POST['post_topic']);
        $sql = "Select * From $ai_topic_tbl Where topic_name = '$tname'";
        $topic = $wpdb->get_row($sql, ARRAY_A);
        unset($topic['topic_last_run']);  //Don't pass to cloud
    } else {
        //Set null topic for post/notebook
        $topic = mct_ai_gnulltopic();
        if ((!empty($_REQUEST['draft']) || !empty($_REQUEST['draftedit'])) ) {
            //set category into null topic
            $topic['topic_cat'] = strval(absint($_POST['post_category']));
        }
    }
    $postit = false;
    $newpost = false;
    if (!empty($topic)){
        //Post using cloud process
        if (mct_ai_cloudtopic($topic)) {
            $post_arr = array(); 
            $post_arr['current_link'] = $url;
            $post_arr['getit'] = '1';
            $post_arr['source'] = 'GetIt';
            if (!empty($_POST['notebook'])) $post_arr['notebook'] = '1';
            $page = 'Not Here';
            $postit = mct_ai_cloudclassify($page, $topic, $post_arr);
            if ($postit) {
                //update the style sheet with the local copy
                $page = $post_arr['page'];
                $page = str_replace("mct_ai_local_style",plugins_url('MyCurator_page.css',__FILE__), $page);
                $post_arr['classed'] = 'not sure';
                if (!empty($_POST['notebook'])) {
                    //validate the notes
                    $nbid = (isset($_POST['notebk'])) ? absint($_POST['notebk']) : 0;
                    $note = str_replace(PHP_EOL,'<br>',$_POST['notes']);
                    $note = wp_kses_post($note);
                    if (!empty($_POST['newnb'])){
                        $nbid = mct_ai_gnewnb();
                    }
                    $newpost = mct_nb_addnotepg($nbid, $note,$post_arr, $page);
                } else {
                    $newpost = mct_ai_post_entry($topic, $post_arr, $page);
                }
                if ((!empty($_REQUEST['draft']) || !empty($_REQUEST['draftedit'])) && $newpost) {
                    mct_ai_traintoblog($newpost,'draft');
                }
                if ((!empty($_REQUEST['draft-cat']) || !empty($_REQUEST['draftedit-cat'])) && $newpost) {
                    mct_nb_traintodraft($newpost,array(absint($_POST['post_category'])));
                }
                if (!empty($_REQUEST['publish']) || !empty($_REQUEST['publish-draft'])) {
                    $poststat = empty($_REQUEST['publish']) ? 'draft' : 'publish' ;
                    $postcat = empty($_POST['post_category']) ? array() : $_POST['post_category'];
                    mct_nb_traintodraft($newpost,$postcat,$poststat);
                }
                return $newpost;
            }
        }
    }
    
    //Didn't render page or post correctly
    
    $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : 'No Title';
    
    $content = '';
    
    //Set up content
    if (!empty($_POST['notebook'])) {
        $note = str_replace(PHP_EOL,'<br>',$_POST['notes']);
        $note = wp_kses_post($note);
        $content =  $note;
        $nbid = (isset($_POST['notebk'])) ? absint($_POST['notebk']) : 0;
        if (!empty($_POST['newnb'])){
            $nbid = mct_ai_gnewnb();
        }
        if (!$nbid) return false;
    } else {
        //Set up link String
        $content = mct_ai_build_link($url, $title);
    }
    $details = array(
      'post_content'  => $content,
      'post_author' => $user_id,
      'post_title'  =>  $title,
      'post_name' => sanitize_title($title)
    );
    //Add variable fields
    if (!empty($_POST['training']) || !empty($_POST['draft']) || !empty($_POST['draftedit'])){
        //Training insert
        $details['tax_input'] = array (  //add topic name 
            'topic' => $topic['topic_name'],
            'ai_class' => 'not sure' //add ai class
        );
        $details['post_type'] = 'target_ai'; //post as a target
        $details['post_status'] = 'publish';
    } elseif (!empty($_POST['notebook'])) { 
        //Note page insert
        $details['post_type'] = 'mct_notepg'; 
        $details['post_status'] = 'publish';
        $details['post_parent'] = $nbid;
    } else {
        //minimal training post - no topic or class
        $details['post_type'] = 'target_ai'; //post as a target
        $details['post_status'] = 'publish';
    }
    //and post it
    $newpost = wp_insert_post($details);
    //move to draft if required
    if (!empty($_REQUEST['draft']) || !empty($_REQUEST['draftedit'])) {
        mct_ai_traintoblog($newpost, 'draft');
    }
    if ((!empty($_REQUEST['draft-cat']) || !empty($_REQUEST['draftedit-cat'])) && $newpost) {
                mct_nb_traintodraft($newpost,array(absint($_POST['post_category'])));
    }
    if (!empty($_REQUEST['publish']) || !empty($_REQUEST['publish-draft'])) {
            $poststat = empty($_REQUEST['publish']) ? 'draft' : 'publish' ;
            $postcat = empty($_POST['post_category']) ? array() : $_POST['post_category'];
            mct_nb_traintodraft($newpost,$postcat,$poststat);
    }
    //Post Meta
    update_post_meta($newpost,'mct_sl_origurl',array($url));
    
    return $newpost;
}

function mct_ai_gnulltopic(){
    //Set up a null topic
    $topic = array(
        'topic_id' => 99999,
        'topic_name' => 'GetIt',
        'topic_slug' => 'getit', 
        'topic_status' => 'Training',
        'topic_type' => 'Filter',
        'topic_search_1' => '',
        'topic_search_2' => '',
        'topic_exclude' => '',
        'topic_sources' => '',
        'topic_aidbfc' => '',
        'topic_aidbcat' => '',
        'topic_skip_domains' => '',
        'topic_min_length' => '',
        'topic_cat' => '',
        'topic_tag' => '',
        'topic_tag_search2' => '',
        'topic_options' => '',  
    );
    return $topic;
}
function mct_ai_gnewnb(){
    //Insert Notebook First
    $title = trim(sanitize_text_field($_POST['newnb']));
    $details = array(
      'post_content'  => '',
      'post_title'  =>  $title,
      'post_name' => sanitize_title($title),
      'post_type' =>  'mct_notebk',
      'post_status' => 'publish'
    );
    $nbid = wp_insert_post($details);
    return $nbid;
}

function mct_ai_dosourceit() {
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray, $user_id, $current_user;
    $cu = wp_get_current_user();
    if ($current_user->ID == ''){
        wp_login_form();
        exit();
    }
    if ( ! current_user_can('edit_posts') )
            wp_die( __( 'User not authorized to publish posts' ) );
    $args = array();
    $msg = '';
    $posted = false;
    $url = '';
    $nofeed = false;
    // For submitted posts.
    if ( isset($_REQUEST['action']) && 'post' == $_REQUEST['action'] ) {
            check_admin_referer('MyCurator_sourceit');//MOD - use correct admin referer
            $posted = true;
            $args['link_category'] = array(); //Set up link category array
            $args['newlinkcat'] = trim(sanitize_text_field($_POST['newlinkcat']));
            if (empty($args['newlinkcat'])) $args['link_category'][0] = absint($_POST['link_category']);
            $args['feed_name'] = trim(sanitize_text_field($_POST['feed_name']));
            $args['rss-url'] =  esc_url($_POST['rss-url']);
            $args['save-url'] = esc_url($_POST['save-url']);
            //Validate args
            if (strlen($args['feed_name']) == 0) $msg .= 'Must have a Feed Name. ';
            //if ok, post it
            if ($msg == '') $msg = mct_ai_postlink($args);
    } else {
        // Set Variables
        $args['feed_name'] = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';
        $args['rss-url'] = isset($_GET['u']) ? esc_url($_GET['u']) : ''; //this is the site url at this point
        $args['newlinkcat'] = '';
        $url = $args['rss-url'];
        //Look for an RSS Url
        $url = mct_ai_sfindrss($url);
        if ($url == '') {
            $nofeed = true;
        } else if (parse_url($url, PHP_URL_HOST) == '') {
            //Relative url, so add domain
            $domain = parse_url($args['rss-url'],PHP_URL_SCHEME)."://".parse_url($args['rss-url'],PHP_URL_HOST);
            $url = $domain.$url;
        }
        $args['rss-url'] = $url;
        $args['save-url'] = parse_url($url, PHP_URL_HOST);
    }

    //Get Link Categories for dropdown
    $cats = array (
        'orderby' => 'name',
        'hide_empty' => FALSE,
        'name' => 'link_category',
        'taxonomy' => 'link_category'
    );
    if (isset($args['link_category'])) $cats['selected'] = $args['link_category'];
    //Check if sources over max
    mct_ai_getplan();
    $src = mct_ai_sourcemax();

    ?>
    
    <form action="admin.php?page=mycurator/MyCurator.php_getit&dosourceit&action=post" method="post">   
    <div id="poststuff" class="metabox-holder">
            <?php wp_nonce_field('MyCurator_sourceit') ?>  
            <!--  Create hidden fields for feed url and link url so they can be added to feed entry, these are set by jQuery if not a google alert page -->
            <input type="hidden" id="save-url" name="save-url" value="<?php echo  $args['save-url'] ; ?>" />
            <input type="hidden" id="rss-url" name="rss-url" value="<?php echo  $args['rss-url'] ; ?>" />

            <div class="mct-posting">

                    <div id="wphead">
                            <h1>
                                    <a href="<?php echo get_option('home'); ?>/" target="_blank"><?php bloginfo('name'); ?></a>
                            </h1>

                    </div>

                    <?php 
                    
                    if ( empty($msg) && $posted) {?>
                            <div id="message" class="updated">
                            <p><strong><?php _e('Your Feed URL has been saved.'); ?></strong>
                            <a href="#" onclick="window.close();"><?php _e('Close Window'); ?></a></p>
                            <script type="text/javascript">setTimeout('self.close();',2000);</script>
                            </div>
                    <?php exit(); } ?>
                    <?php if ($src !== false && $src <= 0) {
                        mct_ai_showsrc();
                        exit;
                    } ?>
                    <?php
                    if ($nofeed) {  // Show Error Div  ?>
                    <div id="jquery-message" color: red; font-size: 18px;">
                            <h2><span id="feed-error"><?php _e('No Feed Found on this Page - '); ?></span>
                            <a href="#" onclick="window.close();"><?php _e('Click to Close Window'); ?></a></h2>
                         <p>If the site does not use the standard method for embedding feeds, Source It can't find it.  If you 
                        can find the feed link for this site manually, click on it and you should see a page of computer code.
                        If the link has /feed/ or /rss/ in it then try the Source It bookmark again and it should capture the feed.
                        If not, then copy the feed url and use the Sources menu item in your MyCurator menu at your site to add it.</p>
                    </div>
                    <?php 
                    } else { ?> 
                    <div id="titlediv">
                            <div class="titlewrap">
                                    <p>Source Title: <span id="feed-title" style="font-size:12px;"><?php echo $args['feed_name']; ?></span></p>
                                    <p>Feed URL: <span id="feed-url" style="font-size:12px;"><?php echo $args['rss-url']; ?></span></p>
                            </div>
                    </div>

                <br /> 
                <?php if ($msg != '' && $posted) { ?>
                        <div id="message" class="error" ><p><strong><?php echo "SOURCE NOT CREATED: ".$msg ; ?></strong></p></div>
                <?php } ?>
                <div id="categorydiv" class="postbox">
                    <h3 class="hndle"><?php _e('Add Source Link') ?></h3>
                    <div class="inside">
                    <div id="taxonomy-category" class="categorydiv">
                        <p>Source Name: <input name="feed_name" id="feed-name" type="input" size="50" maxlength="200" value="<?php echo  $args['feed_name'] ; ?>" /> </p>
                        <p>Choose Source Group: <?php wp_dropdown_categories($cats); ?></p>
                        <p>or Add New Source Group: <input name="newlinkcat" type="input" size="50" maxlength="200" value="<?php echo  $args['newlinkcat'] ; ?>" /> </p>
                        <p><em>Create a new Source Group rather than use BlogRoll</em></p>
                    </div>
                    </div>
                 </div>
                <div id="submit-button">
                <input name="submit" id="submit" value="Save New Source" type="submit" class="button-primary">
                <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" id="saving" style="display:none;" />
                </div>
                <?php } //end $nofeed = false ?>
            </div>
    </div>
    </form>
    
<?php
}

function mct_ai_sfindrss($url) {

    if (empty($url)) return "";
    //Check if an alert feed
    if (stripos($url, "google.com/alerts") !== false) return $url;
    if (stripos($url, "talkwalker.com/alerts") !== false) return $url;
    //On a feed page?
    if (stripos($url, "feeds.feedburner") !== false) return $url;
    if (stripos($url, "/feed") !== false) return $url;
    if (stripos($url, "/rss") !== false) return $url;
    //Get page if using url
    try {
        $opts = array('http' => array('user_agent'=> $_SERVER['HTTP_USER_AGENT']));
        $context = stream_context_create($opts);
        $webpage = @file_get_contents($url, false, $context);
        if ($webpage === false || $webpage == "") {
            return "";
        }
    } catch (exception $e) {
        return '';
    }
    //Check for link tags type alternate for rss or atom
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($webpage);
    foreach ($dom->getElementsByTagName('link') as $linknode) {
        if($linknode->getAttribute('rel') == 'alternate') {
            $typeAttr = $linknode->getAttribute('type');
            if ($typeAttr != "" && (stripos($typeAttr,'rss+xml') !== false || stripos($typeAttr,'atom+xml' || stripos($typeAttr,'xml')) !== false)){
                return $linknode->getAttribute( 'href' );
            }
        }    
    }   
    //No link alternate found, check a tags
    foreach ($dom->getElementsByTagName('a') as $linknode) {
        $link = $linknode->getAttribute( 'href' );
        if (stripos($link, "feeds.feedburner") !== false) return $link;
        if (stripos($link, "/feed") !== false) return $link;
        if (stripos($link, "/rss") !== false) return $link;
        if (stripos($link, "/atom") !== false) return $link;
    }
    return "";  //nothing found
}

//These are the stopwords that will be ignored in classifying a document
$stopwords = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone',
 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount',  'an', 'and', 'another', 'any',
'anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as',  'at', 'back','be','became', 'because','become',
'becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 
'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe',
 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough'
, 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 
'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 
'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers',
 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 
'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 
'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 
 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 
 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own',
 'part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several',
 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 
 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 
 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this',
 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 
  'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well',
 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 
'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within',
 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');

//We ignore 3 letter words, but we can add specific ones back here
$threeletter = array('new');



?>