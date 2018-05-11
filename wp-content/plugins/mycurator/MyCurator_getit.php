<?php
/**
 * Press This Display and Handler.
 *
 * @package WordPress
 * @subpackage Press_This
 */
/*MOD - each mod begins with this in comments
 * Use get-it as admin referer
 * Change title to Get It
 * blockquote selection, use fixed title on url
 */
define('IFRAME_REQUEST' , true);

/** WordPress Administration Bootstrap */
require_once('../../../wp-load.php');

//Load files needed for admin scripts/styles
if (defined('ABSPATH')) {
    require_once(ABSPATH . 'wp-admin/includes/post.php');
    require_once(ABSPATH . 'wp-admin/includes/template.php');
    require_once(ABSPATH . 'wp-includes/functions.wp-scripts.php'); 
} else {
    require_once('../../../wp-admin/includes/post.php');
    require_once('../../../wp-admin/includes/template.php');
    require_once('../../../wp-includes/functions.wp-scripts.php');
}

//header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
global $current_user;
$cu = wp_get_current_user();
if ($current_user->ID == ''){
    wp_login_form();
    exit();
}
if ( ! current_user_can('publish_posts') )
	wp_die( __( 'User not authorized to publish posts' ) );
$cu = wp_get_current_user();
/**
 * Press It form handler.
 *
 * @package WordPress
 * @subpackage Press_This
 * @since 2.6.0
 *
 * @return int Post ID
 */
function press_it() {
    //Use the cloud process to save a training post 
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray, $user_id;
    include_once('MyCurator_local_proc.php');
    
    $url = $_POST['save-url'];
    if (!empty($_POST['training']) || !empty($_POST['draft']) || !empty($_POST['draftedit'])) {
        //Get the Topic
        $tname = sanitize_text_field($_POST['post_topic']);
        $sql = "Select * From $ai_topic_tbl Where topic_name = '$tname'";
        $topic = $wpdb->get_row($sql, ARRAY_A);
        unset($topic['topic_last_run']);  //Don't pass to cloud
    } else {
        //Set null topic for post/notebook
        $topic = mct_ai_nulltopic();
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
                        $nbid = mct_ai_newnb();
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
            $nbid = mct_ai_newnb();
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

// For submitted posts.
if ( isset($_REQUEST['action']) && 'post' == $_REQUEST['action'] ) {
	check_admin_referer('MyCurator_getit');//MOD - use correct admin referer
	$posted = $post_ID = press_it();
        if ($posted && (isset($_REQUEST['draftedit']) || isset($_REQUEST['draftedit-cat']))) {
            $edit_url = get_edit_post_link( $posted, array('edit' => '&amp;'));
            wp_redirect($edit_url);
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

//Queue styles/scripts
mct_ai_queueit(); //Tabs styles/script
wp_enqueue_style( 'colors' );
wp_enqueue_script( 'post' );
_wp_admin_html_begin();
?>
<title><?php _e('Get It') ?></title> <!-- MOD change title to get it -->
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {'url':'<?php echo SITECOOKIEPATH; ?>','uid':'<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>','time':'<?php echo time() ?>'};
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>', pagenow = 'press-this', isRtl = <?php echo (int) is_rtl(); ?>;
var photostorage = false;
//]]>
</script>

<?php
        //Set up global colors for admin styles
        register_admin_color_schemes();
        do_action('admin_print_styles');
	do_action('admin_print_scripts');
	//do_action('admin_head');
?>
	<style type="text/css">
	#message {
		margin: 10px 0;
	}
	#title,
	.press-this #wphead {
		margin-left: 0;
		margin-right: 0;
	}
	.rtl.press-this #header-logo,
	.rtl.press-this #wphead h1 {
		float: right;
	}
        .posting {
            margin-right: 50px;
        }
        body.press-this {
            min-width: 275px;
            min-height: 200px;
        }
        #titlediv {
            font-size: 1.3em;
        }
</style>
<?php $default = empty($mct_ai_optarray['ai_getit_tab']) ? 0 : 1; 
      $default = !empty($mct_ai_optarray['ai_getit_pub']) ? 2 : $default; ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var deftab = <?php echo $default; ?>;
    jQuery('#publish, #submit, #draft').click(function() { jQuery('#saving').css('display', 'inline'); });
    jQuery( ".mct-ai-tabs #tabs" ).tabs({ active: deftab });
});
</script>
</head>
<body class="press-this wp-admin<?php if ( is_rtl() ) echo ' rtl'; ?>">
<form action="MyCurator_getit.php?action=post" method="post">   <!-- MOD post to MyCurator_getit.php -->
<div id="poststuff" class="metabox-holder">
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
</div>
</form>
<?php
//Use Admin Footers from source
//do_action('admin_footer');
do_action('admin_print_footer_scripts');
?>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->

<div id="wpfooter" role="contentinfo"></div>
<div class="clear"></div></div><!-- wpwrap -->
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
</body>
</html>
<?php
function mct_ai_nulltopic(){
    //Set up a null topic
    $topic = array(
        'topic_id' => 99999,
        'topic_name' => 'mct-temp',
        'topic_slug' => 'mct-temp', 
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
function mct_ai_newnb(){
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
