<?php
/* MyCurator_notebk.php - 
* functions for implementing Notebook features */

//Register post type and taxonomies on init
add_action('init','mct_nb_register');
//Set up metaboxes on notebooks and pages
add_action("add_meta_boxes", "mct_nb_metaboxes");
//Save notebook metadata for a post
add_action('save_post','mct_nb_savenb');
//Ajax handler
add_action('wp_ajax_mct_nb_notebk_ajax','mct_nb_notebk_ajax');
//Save new notebook for this page when changing notebooks

function mct_nb_register(){
    //Registers custom post type notebooks
    //Set up args array
    $nbook_args = array (
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'supports' => array( 
            'title', 'author', 'editor'
        ),
        'labels' => array(
            'name' => 'Notebooks',
            'singular_name' => 'Notebook',
            'add_new' => 'Add New Notebook',
            'add_new_item' => 'Add New Notebook',
            'edit_item' => 'Edit Notebook',
            'new_item' => 'New Notebook',
            'view_item' => 'View Notebook',
            'search_items' => 'Search Notebooks',
            'not_found' => 'No Notebooks Found',
            'not_found_in_trash' => 'No Notebooks Found In Trash'
        ),
    );
   
    register_post_type('mct_notebk',$nbook_args);
    
    //Registers custom post type notebook pages
    //Set up args array
    $nbook_args = array (
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'supports' => array( 
            'title', 'author', 'editor'
        ),
        'labels' => array(
            'name' => 'Note Pages',
            'singular_name' => 'Note Page',
            'add_new' => 'Add New Note Page',
            'add_new_item' => 'Add New Note Page',
            'edit_item' => 'Edit Note Page',
            'new_item' => 'New Note Page',
            'view_item' => 'View Note Page',
            'search_items' => 'Search Note Pages',
            'not_found' => 'No Note Pages Found',
            'not_found_in_trash' => 'No Note Pages Found In Trash'
        ),
    );
   
    register_post_type('mct_notepg',$nbook_args);
    
}

function mct_nb_queuejs(){
    //Load JS, CSS for Notebook pages
    //Hooked on load- for notebook menu item
    $jsdir = plugins_url('js/MyCurator_notebook.js',__FILE__);
    wp_enqueue_script('mct_nb_notebk',$jsdir,array('jquery','thickbox','jquery-ui-dialog'),'1.0.0');
    $includes_url = includes_url();
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $params = array(
        'tb_pathToImage' => "{$includes_url}js/thickbox/loadingAnimation.gif",
        'tb_closeImage'  => "{$includes_url}js/thickbox/tb-close.png",
        'ajaxurl' => admin_url('admin-ajax.php',$protocol)
    );
    wp_localize_script('mct_nb_notebk', 'mct_nb_notedat', $params);
    //Enque ui styles
    wp_enqueue_style('thickbox');
    wp_enqueue_style('wp-jquery-ui-dialog');
    
}

function mct_nb_notebk_page(){
    //Display the Notebooks menu item and note pages
    global $wpdb;
    
    if (isset($_REQUEST['notebk'])) {
        mct_nb_notepg_display(intval($_REQUEST['notebk']));
    } else {
        mct_nb_notebk_display();
    }
}

function mct_nb_notebk_display(){
    //Set up prefixes for links
    global $user_ID;
    $cu = wp_get_current_user();
    
    $notepgs = admin_url('admin.php?page=mycurator/MyCurator.php_notebook');
    
    //Get notebooks
    $args = array(
        'numberposts'     => -1,
        'orderby'         => 'post_title',
        'order'           => 'DESC',
        'post_type'       => 'mct_notebk',
        'post_status'     => 'publish'); 
    $notebks = get_posts($args);
    //Set up pagination
    $myCount = count($notebks);
    $maxrow = 25;
    $currentPage = 1;
    if (isset($_GET['paged'])){
        $currentPage = $_GET['paged'];
    }
    
    //render the page
    ?>
    <div class='wrap'>
    
    <?php $imgnb = plugins_url('notebook.png', __FILE__);?>
    <img src="<?php echo $imgnb; ?>" class="alignleft" >
    <h2>Notebooks</h2> 
    <p>Notebooks support you beyond simple curation of a single article into a single post. You can store articles throughout the week for a weekly roundup or maybe a point-counterpoint discussion of an issue. 
        You can also use notebooks to research a topic, finding the best articles and ideas and storing them for reference. 
        When you craft an original article you can easily use quotes and images from the Notebook articles as you write. 
        <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
    <?php echo mct_ai_helpvideo('notebooks'); ?>
    <h4>Click the Title to View the Note Pages for the Notebook.  The Actions column has Links for you to Edit or Delete a Notebook.</h4>
    <?php if (mct_ai_menudisp())echo '<p>See our Training Videos or Documentation for more Information on Notebooks.</p>';    ?>
    <?php mct_ai_getplan(); if (mct_nb_showlimits(false, false)) { ?>
    <p><a class="mct-nb-add-notebk" href="#AddNew" >Add New Notebook</a>
    <?php } ?>
    <img class="aligncenter" src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>" alt="" id="saving" style="display:none;margin-right:50px;" /></p>
    <input type="hidden" id="nb-page-display" value="notebook" >
    <?php
    //Display pagination
    print("<div class=\"tablenav\">"); 
   $qargs = array(
       'paged' => '%#%', 
       );
   $page_links = paginate_links( array(
            'base' => add_query_arg($qargs ) ,
            'format' => '',
            'total' => ceil($myCount/$maxrow),
            'current' => $currentPage
    ));
    //Pagination display
    if ( $page_links )
            echo "<div class='tablenav-pages'>$page_links</div>";
    ?>
    <style>
        th.mct-nb-name {width: 30%; }
        th.mct-nb-desc {width: 40%; }
        th.mct-nb-author {width: 20%; }
        th.mct-nb-action {width: 10%; }
    </style>
        <table class="widefat" >
            <thead>
                <tr>
                <th class="mct-nb-name">Notebook</th>
                <th class="mct-nb-desc">Description</th>
                <th class="mct-nb-author">Author</th>
                <th class="mct-nb-action">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $bottom = ($currentPage - 1) * $maxrow;
	    $top = $currentPage * $maxrow;
            if ($top > $myCount) $top = $myCount;
            for ($i=$bottom; $i < $top; $i++) {
                $row = $notebks[$i];
                if (! current_user_can('edit_others_posts') && $row->post_author != $user_ID) continue;
                $content = str_replace('<br>',PHP_EOL,$row->post_content);
                echo('<tr class="post-'.$row->ID.'">');
                echo('<td id="title-'.$row->ID.'"><strong><a href="'.$notepgs.'&notebk='.$row->ID.'" >'.$row->post_title.'</a></strong></td>');
                echo('<td >'.$row->post_content.'</td>');
                echo('<td id="desc-'.$row->ID.'" style="display:none;">'.$content.'</td>'); //Row with display content
                $user = get_userdata($row->post_author);
                echo('<td>'.$user->user_login.'</td>');
                echo('<td> <a class="mct-nb-edit-notebk" id="'.$row->ID.'" href="#edit" >[Edit]</a>&nbsp;&nbsp; <a class="mct-nb-del-notebk" id="'.$row->ID.'" href="#delete" >[Delete]</a></td>');
                echo('</tr>');
            } ?>
           </tbody>
        </table>
    <?php  
    wp_nonce_field('mct_nb_nonce','_wpnonce',false, true);?>
    <?php if (mct_ai_menudisp()) { mct_ai_getplan();  echo mct_nb_showlimits(); }?>
    <div id="nb-add-dialog" class="hide-if-no-js" title="Add/Edit Notebook" style="margin-left:5px">
        <form>
            <p>Title: <input name="notebk" type="text" id="mct-nb-title" value="" size="100"></p>
            <p>Description:</p>
            <textarea name="Desc" id="mct-nb-desc" rows="5" cols="100" ></textarea>
         </form>
    </div>
    <div id="nb-del-dialog" class="hide-if-no-js" title="Delete Notebook" style="margin-left:5px">
            <h3>If you Delete this Notebook, All of the Note Pages will Also be Deleted!</h3>
            <p>You can Move the Pages to a New Notebook by Clicking the title of this notebook.  
                In the Note Pages screen choose Note Pages to save by clicking the checkbox and choosing the Move Pages
                bulk edit option.  Press Cancel now to do this first.</p>
            <p>Press Delete to go ahead and Delete this Notebook and all of its Note Pages.</p>
    </div>
    </div>
<?php

}

function mct_nb_notepg_display($this_notebk) {
    //Display the note pages for a notebook
    global $mct_ai_optarray;
    
    //Check for bulk action submit
    if (isset($_POST['apply-1']) || isset($_POST['apply-2'])) {
        $action = (isset($_POST['apply-1'])) ? $_POST['action-1'] : $_POST['action-2']; //set action  based on apply button pressed
        $items = $_POST['bulk']; //get bulk items to work on
        if ($action != -1 && count($items)) {
            if ($action == 'delete') {
                foreach ($items as $item) {
                    wp_delete_post(intval($item),true);
                }
            } else {
                $newbook = intval($action);
                foreach ($items as $item) {
                    $details = array(
                       'post_parent'  =>  $newbook,
                       'post_type' =>  'mct_notepg',
                      'ID' => intval($item)
                    );
                    $ret = wp_update_post($details, true);
                    $a = $ret;
                }
            }
        }
    }

    //Get Notebook post
    $the_nb = get_post($this_notebk);
    
    //Get Note Pages
    $args = array(
        'numberposts'     => -1,
        'orderby'         => 'post_title',
        'order'           => 'DESC',
        'post_type'       => 'mct_notepg',
        'post_parent'     => $this_notebk,
        'post_status'     => 'publish'); 
    $notebks = get_posts($args);
    //Set up pagination
    $myCount = count($notebks);
    $maxrow = 25;
    $currentPage = 1;
    if (isset($_GET['paged'])){
        $currentPage = $_GET['paged'];
    }
    //Set up move to notebooks
    $args = array(
        'numberposts'     => -1,
        'orderby'         => 'post_title',
        'order'           => 'DESC',
        'post_type'       => 'mct_notebk',
        'post_status'     => 'publish'); 
    $books = get_posts($args);
    ?>
    <div class='wrap'>
    
    <?php $imgnb = plugins_url('notebook.png', __FILE__);?>
    <img src="<?php echo $imgnb; ?>" class="alignleft" >
    <h2>Note Pages: <?php echo $the_nb->post_title; ?></h2>  
    <h4>Click on the Title to view the Saved Article Page.  The Actions column has Links for you to Edit or Delete a Note Page. </h4>
    <p><a href="<?php echo (admin_url('admin.php?page=mycurator/MyCurator.php_notebook')); ?>" >Back to Notebooks</a></p>
    <p><a class="mct-nb-add-notebk" href="#AddNew" >Add New Note Page</a>
    <img class="aligncenter" src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>" alt="" id="saving" style="display:none;margin-right:50px;" /></p>
    <input type="hidden" id="nb-page-display" value="notepage" >
    <input type="hidden" id="nb-parent-id" value="<?php echo $this_notebk; ?>" >
    <form id="np-bulk-action" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
        <div class="alignleft actions">
        <select name="action-1">
            <option value="-1" selected="selected">Bulk Actions</option>
            <option value="delete">Delete</option>
            <?php foreach ($books as $book){
                if ($the_nb->ID == $book->ID) continue;
                echo '<option value="'.$book->ID.'">Move to -> '.$book->post_title.'</option>';
            } ?>
            
        </select>
        <input type="hidden" value="<?php echo $this_notebk; ?>" name="notebk" id="notebk-id">
        <input type="submit" class="button-secondary" id="apply-1" value="apply" name="apply-1">
        </div>
        <style>
        th.mct-np-title {width: 25%; }
        th.mct-np-note {width: 30%; }
        th.mct-np-author {width: 10%; }
        th.mct-np-url {width: 25%; }
        th.mct-nb-action {width: 10%; }
        </style>
        <?php
        //Display pagination
        print("<div class=\"tablenav\">"); 
       $qargs = array(
           'paged' => '%#%', 
           );
       $page_links = paginate_links( array(
                'base' => add_query_arg($qargs ) ,
                'format' => '',
                'total' => ceil($myCount/$maxrow),
                'current' => $currentPage
        ));
        //Pagination display
        if ( $page_links )
                echo "<div class='tablenav-pages'>$page_links</div>";
        ?>
        <table class="wp-list-table widefat" >
            <thead>
                <tr>
                    <th class="mct-np-cb"><input type="checkbox" name="bulk-all-1" id="bulk-all-1" value="1"></th>
                    <th class="mct-np-title">Title</th>
                    <th class="mct-np-note">Notes</th>
                    <th class="mct-np-author">Author</th>
                    <th class="mct-np-url">Web Page</th>
                    <th class="mct-np-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $bottom = ($currentPage - 1) * $maxrow;
                $top = $currentPage * $maxrow;
                if ($top > $myCount) $top = $myCount;
                for ($i=$bottom; $i < $top; $i++) {
                    $notebk = $notebks[$i];
                    $content = str_replace('<br>',PHP_EOL,$notebk->post_content);
                    $origlinks = get_post_meta($notebk->ID,'mct_sl_origurl',true);
                    if (!empty($origlinks)) $domain = parse_url($origlinks[0], PHP_URL_HOST);
                    $edit_url = get_edit_post_link( $notebk->ID, array('edit' => '&amp;'));
                    //Get Article
                    $page = mct_ai_getslpage($notebk->ID);
                    if (!empty($page)) {
                        $article = mct_ai_notable_article($page); 
                        // Get original URL
                        $pos = preg_match('{<div id="source-url">([^>]*)>([^<]*)<}',$page,$matches);
                        $linktxt = $matches[1].' target="_blank">'.$matches[2].'</a>';
                        $article = '<p>'.$linktxt.'</p>'.$article;
                    }
                    ?>  
                <tr class="post-<?php echo $notebk->ID; ?>" >
                    <td><input type="checkbox" name="bulk[]" id="bulk-<?php echo $notebk->ID; ?>" value="<?php echo $notebk->ID; ?>"></td>
                    <?php if (empty($mct_ai_optarray['ai_no_inline_pg']) && !empty($page)) { ?>
                        <td> <?php
                        echo '<strong><a class="thickbox" href="#TB_inline?&width=550&height=700&inlineId=ai-page-'.$notebk->ID.'" title="'.$notebk->post_title.'" >'.$notebk->post_title.'</a></strong>';
                        ?>
                        <div id="ai-page-<?php echo $notebk->ID; ?>" style="max-width: 540; display: none;">
                        <p><?php echo $article; ?></p>
                        </div>
                        </td> <?php
                    } else {
                         $link_redir = mct_ai_getlinkredir($notebk->ID);
                         echo '<td>';
                         if (!empty($link_redir)) {
                             echo '<strong><a href="'.$link_redir.'" target="_blank">'.$notebk->post_title.'</a><strong>'; 
                         } else {
                             echo  '<strong>'.$notebk->post_title.'</strong>'; 
                         }
                         echo '</td>';
                    } ?>
                    <td><?php echo $notebk->post_content; ?></td>
                    <?php $user = get_userdata($notebk->post_author);
                    echo('<td>'.$user->user_login.'</td>'); ?>
                    <?php echo('<td id="desc-'.$notebk->ID.'" style="display:none;">'.$content.'</td>'); //Row with display content for edit
                    echo '<td id="title-'.$notebk->ID.'" style="display:none;">'.$notebk->post_title.'</td>'; // Row with title for edit ?>
                    <td><?php if (!empty($origlinks)) echo ('<a href="'.$origlinks[0].'" target="_blank" >'.$domain.'</a>'); ?></td>
                    <?php echo('<td> <a class="mct-nb-edit-notebk" id="'.$notebk->ID.'" href="#edit" >[Edit]</a>&nbsp;&nbsp; <a class="mct-nb-del-notepg" id="'.$notebk->ID.'" href="#delete" >[Delete]</a></td>'); ?>
                </tr>
                <?php } //end for loop ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><input type="checkbox" name="bulk-all-2" id="bulk-all-2" value="1"></th>
                    <th>Title</th>
                    <th>Notes</th>
                    <th>Author</th>
                    <th>URL</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
        <div class="alignleft actions">
        <select name="action-2">
            <option value="-1" selected="selected">Bulk Actions</option>
            <option value="delete">Delete</option>
            <?php foreach ($books as $book){
                if ($the_nb->ID == $book->ID) continue;
                echo '<option value="'.$book->ID.'">Move to -> '.$book->post_title.'</option>';
            } ?>
        </select>
        <input type="submit" class="button-secondary" id="apply-2" value="apply" name="apply-2">
        </div>
    </form>
    <div id="nb-add-dialog" class="hide-if-no-js" title="Add/Edit Note Page" style="margin-left:5px">
        <form>
            <p>Title: <input name="notebk" type="text" id="mct-nb-title" value="" size="100"></p>
            <p>Description:</p>
            <textarea name="Desc" id="mct-nb-desc" rows="5" cols="100" ></textarea>
         </form>
    </div>
    <?php wp_nonce_field('mct_nb_npnonce','_wpnonce',false, true);
    echo '</div>'; 
}
function mct_nb_showlimits($display=true, $upgrade=true) {
    //Display the Notebook Page Limits
    global $wpdb, $ai_topic_tbl, $mct_ai_optarray;
    
    //return false on no token, since we won't have plan
    if (empty($mct_ai_optarray['ai_cloud_token'])) return ($display) ? '<p><strong>Error - You need an API Key before you can add Notebooks</strong></p>' : false;
    //Get the plan
    if (empty($mct_ai_optarray['ai_plan'])){
        return ($display) ? "<p><strong>Error - No Plan Data Available, could not connect with cloud services.  Try again after 5 minutes. 
            If still having problems contact MyCurator support at support@target-info.com.</strong></p>" : false;
    }
    $plan = unserialize($mct_ai_optarray['ai_plan']);
    if ($plan['max'] == -1) {
        //error, invalid token or expired
        return ($display) ? "<p><strong>Error - ".$plan['name']." Try to correct the error and then try again after 5 minutes. 
            If still having problems contact MyCurator support at support@target-info.com.</strong></p>" : false;
    }
    if ($plan['maxnb'] == 0){
        return ($display) ? '<p>No Limit on Notebooks</p>' : true;
    }
    //Get current Notebook counts
    $args = array(
        'numberposts'     => -1,
        'post_type'       => 'mct_notebk',
        'post_status'     => 'publish'); 
    $books = get_posts($args);
    $cur_cnt = count($books);
    if (!$display) {
        return ($cur_cnt >= $plan['maxnb']) ? false : true;
    }
    //Get Token
    $token = $mct_ai_optarray['ai_cloud_token'];
    //Set up the display
    ob_start();
    ?>
    <h4><?php echo $plan['name']; ?> with <?php echo $plan['maxnb']; ?> Notebooks maximum and <?php echo $cur_cnt; ?> currently used</h4>
    <?php 
    if ($upgrade && current_user_can('manage_options')) { 
        if (stripos($plan['name'],'ind') !== false) {
            echo '<p>If you would like to create more Notebooks than your current plan allows <a href="http://www.target-info.com/myaccount/?token='.$token.'" >Upgrade to a Pro or Business Plan</a></p>';
        }
    }
    return ob_get_clean();
    
}
function mct_nb_addnotepg($notebk_id, $notes, $post_arr, $page) {
    //Create a new note page for a notebook from Get It
    global $user_id, $wpdb, $ai_sl_pages_tbl;
    $cu = wp_get_current_user();
    //Get rid of tags in title
    if (!$notebk_id) return false;
    $title = '';
    $cnt = preg_match('{<title>([^<]*)</title>}i',$page,$matches);
    if ($cnt) $title = $matches[1];
    $title = preg_replace('{<([^>]*)>}',' ',$title);  //remove tags but leave spaces
    if ($title == '') $title = 'No Title';
    $post_arr['title'] = $title;  //save title 
    //Insert Post
    $details = array(
      'post_content'  => $notes,
      'post_parent' => $notebk_id,
      'post_author' => $user_id,
      'post_title'  =>  $post_arr['title'],
      'post_name' => sanitize_title($post_arr['title']),
      'post_type' =>  'mct_notepg',
      'post_status' => 'publish'
    );
    $post_id = wp_insert_post($details);
    //Post Meta - orig and saved page links
    update_post_meta($post_id,'mct_sl_origurl',array($post_arr['current_link']));
    $newurl = mct_ai_getlinkredir($post_id);
    update_post_meta($post_id,'mct_sl_newurl',array($newurl));
    //Saved Page
    $wpdb->insert($ai_sl_pages_tbl, array ('sl_page_content' => $page, 'sl_post_id' => $post_id));
    
    return $post_id;
}
 
 function mct_nb_traintonotepg($thepost, $notebk_id, $note){
    //Move a training post to a notebook
    //$thepost is the new notebook page
    //post id of notebook passed in
    global $user_id;
    $cu = wp_get_current_user();
    
    $details = array();
    $details['ID'] = $thepost;
    $details['post_parent'] = $notebk_id;
    $details['post_content'] = $note;
    $details['post_excerpt'] = '';
    $details['post_author'] = $user_id;  //change user to whoever is moving it to a notebook
    $details['post_type'] = 'mct_notepg';
    $details['post_status'] = 'publish';
    wp_update_post($details);
    //Delete any saved images
    mct_sl_deleteimage($thepost);
}

function mct_nb_traintodraft($thepost,$cat,$stat='draft'){
    //Move Get It post from training to Draft
    $details = array();
    $details['ID'] = $thepost;
    $details['post_type'] = 'post';
    $details['post_category'] = $cat;
    $details['post_status'] = $stat;
    $details['edit_date'] = true;
    $details['post_date'] = '';
    $details['post_date_gmt'] = "0000-00-00 00:00:00";
    $details['comment_status'] = get_option('default_comment_status');
    $details['ping_status'] = get_option('default_ping_status');
    wp_update_post($details);
}

function mct_nb_metaboxes(){
    //manage metaboxes for notebook and notepage edit
    //add metabox for showing pages in edit
    global $post_type, $hook_suffix, $mct_ai_optarray;
   
    //Notebooks metabox for posts
    $args = array(
        'numberposts'     => -1,
        'post_type'       => 'mct_notebk',
        'post_status'     => 'publish'); 
    $notebks = get_posts($args); 
    if (!empty($notebks)) add_meta_box('mct-nb-choosenb','Notebooks','mct_nb_choosenb','post','side','default');
    //Get training page link if MyCurator
    $trainpage = '';
    if (!empty($mct_ai_optarray['MyC_version'])){
        $pages = get_pages(array('post_status' => 'publish,private'));
        foreach ($pages as $page) {
            if (stripos($page->post_content,"MyCurator_training_page") !== false) {
                $trainpage = get_page_link($page->ID);
                break;
            }
        }
        $trainpage = '  >><a href="'.$trainpage.'" />Link to MyCurator Training Page on your site</a>';
    }
    //Add the showpage metabox and js/css
    add_meta_box('mct_ai_slpage','Saved Page','mct_ai_showpage','post','normal','high');
    //add the css and js only on the pages where we need it
    if($post_type == 'post'){
        add_action("admin_print_scripts-{$hook_suffix}", 'mct_ai_showpg_queue');

    }
    //Add saved page, notebooks for custom types/taxonomies
    if (!empty($mct_ai_optarray['ai_custom_types'])) {
        $custom_types = maybe_unserialize($mct_ai_optarray['ai_custom_types']);
        foreach ($custom_types as $key => $val) {
            if ($key == $post_type) {  //This post type is set in Options, so add metaboxes
                //Add the showpage metabox and js/css
                add_meta_box('mct_ai_slpage','Saved Page','mct_ai_showpage',$key,'normal','high');
                //add the css and js only on the pages where we need it
                add_action("admin_print_scripts-{$hook_suffix}", 'mct_ai_showpg_queue');
                //Add Notebooks metabox
                if (!empty($notebks)) add_meta_box('mct-nb-choosenb','Notebooks','mct_nb_choosenb',$key,'side','default');
            }
        }
    }
}

function mct_nb_choosenb($post){
    //Chooose a notebook for this post
    //If new, load into post meta
    //Get notebooks
    global $user_ID;
    $cu = wp_get_current_user();
    
    $args = array(
        'numberposts'     => -1,
        'orderby'         => 'post_title',
        'order'           => 'DESC',
        'post_type'       => 'mct_notebk',
        'post_status'     => 'publish'); 
    if (! current_user_can('edit_others_posts')) $args['author'] = $user_ID;
    $notebks = get_posts($args); 
    //Get current notebooks
    if (empty($notebks)) return;
    $cur_nb = get_post_meta($post->ID,'mct_nb_postnb',true);
    if (empty($cur_nb)) $cur_nb[0] = 0;
    ?>
    <select name="mct-notebk">
    <option value="0" <?php selected($cur_nb[0],0); ?> >None</option>
    <?php foreach ($notebks as $notebk) { 
    //if (! current_user_can('edit_others_posts') && $notebk->post_author != $user_ID) continue;
    ?>
    <option value="<?php echo $notebk->ID; ?>" <?php selected($cur_nb[0],$notebk->ID); ?> ><?php echo $notebk->post_title; ?></option>
    <?php  } ?>
    </select>
    <p>Save or Update the Post to Bring in Notebook Pages</p>

<?php
}

function mct_nb_savenb($post_id){
    //Save chosen notebook to meta data for a post
    if (isset($_POST['mct-notebk'])){
        if (intval($_POST['mct-notebk']) == 0) {
            delete_post_meta($post_id,'mct_nb_postnb');
            return;
        }
        update_post_meta($post_id,'mct_nb_postnb',array($_POST['mct-notebk']));
        //check for request deleted notebook items
        $nb_ids = array_keys($_POST);
        if (count($nb_ids)) {
            //look for delete checkboxes
            foreach ($nb_ids as $nb_id) {
                if (strpos($nb_id,'del-nb-') !== false && isset($_POST[$nb_id])) {
                    $del_id = substr($nb_id,7);
                    $delok = wp_delete_post($del_id);
                }
            } 
        }
        //End delete checkboxes
    }
}

function mct_ai_showpg_queue(){
    //Queue tab stuff
    wp_enqueue_script('jquery-ui-tabs');
    $style = plugins_url('css/MyCurator.css',__FILE__);
    wp_register_style('myctabs',$style,array(),'1.0.0');
    wp_enqueue_style('myctabs');
    //Dialog and ajax
    $jsdir = plugins_url('js/MyCurator_showpg.js',__FILE__);
    wp_enqueue_script('mct_ai_showpg',$jsdir,array('jquery','jquery-ui-dialog'),'1.0.2');
    $includes_url = includes_url();
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $params = array(
        'ajaxurl' => admin_url('admin-ajax.php',$protocol)
    );
    wp_localize_script('mct_ai_showpg', 'mct_ai_showpg', $params);
    wp_enqueue_style('jquery-ui-dialog');
    wp_enqueue_style('wp-jquery-ui-dialog');
}

function mct_ai_showpage($post){
    //Display the saved page in a  meta box for use in curating post
    global $wp_query, $post, $mct_ai_optarray;

    $max = 0;
    $indexpg = false;
    $idxbody = array();
    $posts = array();
    
    if (mct_ai_getslpage($post->ID)) {
        $posts[0] = $post->ID;
        $max++;
    }
    //Get any Multi Posts
    $multis = '';
    if (!empty($mct_ai_optarray['MyC_version'])){
        $topics = wp_get_object_terms($post->ID,'topic',array('fields' => 'slugs'));
        if (!empty($topics)) {
            $term = wp_get_object_terms($post->ID,'ai_class',array('fields' => 'names'));
            if (!empty($term) && $term[0] == 'multi') {
                $args = array(
                    'post_type'       => 'target_ai',
                    'numberposts' => -1,
                    'topic' => $topics[0],
                    'ai_class'        => 'multi'
                );
                $multis = get_posts($args);
                foreach ($multis as $multi){
                    $posts[] = $multi->ID;
                    $max++;
                }
            }
        }
    }
    //Get any Notebook Items
    $nb_idx = 0;
    $cur_nb = get_post_meta($post->ID,'mct_nb_postnb',true);
    if (!empty($cur_nb)) {
        $args = array(
            'post_type'       => 'mct_notepg',
            'numberposts' => -1,
            'post_parent' => $cur_nb[0]
        );
        $nbps = get_posts($args);
        foreach ($nbps as $nbp){
            if ($nb_idx == 0) $nb_idx = $max;
            $posts[] = $nbp->ID;
            $max++;
        }
    }
    if ($max == 0) return;  //no saved pages
    if ($max > 5) $indexpg = true;
    //Set up dialog box
    ?>
    <div id="ai-dialog" class="hide-if-no-js" title="Insert Image" style="margin-left:5px">
        <p>Align/Size for Insert Into Post Only
        <?php  echo '<img src="'.esc_url( admin_url( "images/wpspin_light.gif" ) ).'" alt="" id="ai-saving" style="display:none;" />'; ?></p>
        <form>
            <p><label for="ai_img_align"><strong>Alignment</strong></label></p>
            <input name="ai_img_align" type="radio" id="ai_img_align" value="left" checked /> Left&nbsp;&nbsp;
            <input name="ai_img_align" type="radio" id="ai_img_align" value="right"  /> Right&nbsp;&nbsp;
            <input name="ai_img_align" type="radio" id="ai_img_align" value="center"  /> Center&nbsp;&nbsp;
            <input name="ai_img_align" type="radio" id="ai_img_align" value="none"  /> None
            <p><label for="ai_img_size"><strong>Size</strong></label></p>
            <input name="ai_img_size" type="radio" id="ai_img_size" value="thumbnail" checked  /> Thumbnail&nbsp;&nbsp;
            <input name="ai_img_size" type="radio" id="ai_img_size" value="medium"  /> Medium&nbsp;&nbsp;
            <input name="ai_img_size" type="radio" id="ai_img_size" value="large"  /> Large&nbsp;&nbsp;
            <input name="ai_img_size" type="radio" id="ai_img_size" value="full"  /> Full Size
            <p><label for="ai_title_alt"><strong>Title/Alt Tags</strong></label></p>
            <input name="ai_title_alt" type="text" id="ai_title_alt" size ="50" value="<?php echo $post->post_title; ?>" >
            <input name="ai_post_id" type="hidden" id="ai_post_id" value="<?php echo $post->ID; ?>" >
            <?php wp_nonce_field("mct_ai_showpg",'showpg_nonce', false);  ?>
        </form>
    </div>
    <div class="mct-ai-tabs">
    <div id="tabs">
        <ul>
        <?php
        if ($indexpg) {
            echo '<li><a href="#tabs-0">Index</a></li>';
        }
        for ($i=1;$i<=$max;$i++){
            echo '<li><a href="#tabs-'.$i.'">Article '.$i.'</a></li>';
        } 
        ?>
        </ul>
        <?php
    //Loop on all Articles
    $i = 1;
    foreach ($posts as $postid){
        //Get saved page id
        $page = mct_ai_getslpage($postid);
        //Get notes if note page
        $notes = '';
        if (get_post_type($postid) == 'mct_notepg') {
            $nbpost = get_post($postid);
            $notes = $nbpost->post_content;
        }
        $title = get_the_title($postid);
        //Get the original link and format it 
        $origlinks = get_post_meta($postid,'mct_sl_origurl',true);
        if (!empty($origlinks)) {
            $url = $origlinks[0];
            $origlink = mct_ai_build_link($url, $title);
        } else {
            $origlink = '';
        }
        //Get Article
        if (empty($page)) {
            $article = "<p>No Saved Page</p>";
            $images = '';
        } else {
            //pull out the article text
            $article = mct_ai_getslarticle($page);
            //pull out any side images
            $images = '';
            $pos = stripos($page,'<div id="box_media">');
            if ($pos){
                $images = substr($page,$pos);
                $pos = stripos($images,'</div>');
                if ($pos > 20) {
                    $images = substr($images,0,$pos+6);
                } else {
                    $images = '';
                }
            }
        } //end no page
        //Write out tab div
        echo '<div id="tabs-'.$i.'">';
        if (!empty($multis)) {
            echo '<input name="mct_ai_ismulti" type="hidden" value="1" />';  //Set this so we know we are publishing a multi post
        }
        // click copy notice
        if ($i == 1) {
            ?>
            <div id="ai-showpg-msg" style="float:right; width: 220px; border:1px solid; padding:2px;">
                Click on Highlighted Text or Image to Insert into Post at Cursor - Turn off Click-Copy&nbsp;
                <input name="usecopy" id="no-element-copy" type="checkbox" >
            </div>
            <?php
        } //end click copy
        //Delete Notebook Notice
        if ($nb_idx && ($i-1) >= $nb_idx) {
            ?>
            <div id="ai-showpg-del-nb" style="float:right; width: 220px; border:1px solid; padding:2px;">
                Delete Notebook Item on Save?&nbsp;
                <input name="del-nb-<?php echo $postid; ?>" id="del-nb-<?php echo $postid; ?>" type="checkbox" >
            </div>
            <?php
        }
        // End Delete Notice
        echo '<p><strong>'.$title.'</strong></p>';
        if (!empty($notes)) echo '<p>'.$notes.'</p>';
        if (!empty($origlink)) echo '<p>'.$origlink.'</p>';
        echo $article;
        if ($images){
            //quick style
            echo "<style> #box_media {padding: 5px;} #box_media #side_image {padding: 5px;} </style>";
            echo "<h3>Images</h3>".$images;
        } 
        echo '</div>';
        
        if ($indexpg) {
            $idxbody[] = "<p id='idx-entry'><a id='idx-article' href='$i' >Article $i</a>) $title</p>";
        }
        $i++;
    }
    if ($indexpg) {
        echo '<div id="tabs-0">';
        foreach ($idxbody as $idx) {
            echo $idx;
        }
        echo '</div>';
    }
    ?>    
    </div>
    </div>
    <?php
}

function mct_nb_notebk_ajax(){
    //Ajax for notebook pages in admin
    $response = new WP_Ajax_Response;
    //User Cap ok?
    if (!current_user_can('edit_published_posts')){
        $response->add(array('data' => 'Error - Not Allowed'));
        $response->send();
        exit();
    }
    //check nonce
    $page = sanitize_text_field($_POST['page']);
    if ($page == 'notebook') {
        $nonce_str = 'mct_nb_nonce';
    } else {
        $nonce_str = 'mct_nb_npnonce';
    }
    if (!check_ajax_referer($nonce_str,'nonce',false)) {
        $response->add(array('data' => 'Error - Bad Nonce'));
        $response->send();
        exit();
    }
    $type = sanitize_text_field($_POST['type']);
    if ($type == 'delete') {
        $pid = intval($_POST['postid']);
        $terms = wp_delete_post($pid, true);
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
    if ($type == 'add-nb' || $type == 'edit-nb') {
        $desc = str_replace(PHP_EOL,'<br>',$_POST['desc']);
        $desc = str_replace(array("\r\n", "\n", "\r"),'<br>',$desc);//in case PHP_EOL doesn't work
        $desc = wp_kses_post($desc);
        $title = sanitize_text_field($_POST['title']);
        
        if ($page == 'notebook') {
            $post_type = 'mct_notebk';
            $post_desc = 'Notebook';
        } else {
            $post_type = 'mct_notepg';
            $post_desc = 'Note Page';
            $notebk_id = intval($_POST['notebk']);
        }
        if ($type == 'edit-nb') {
            //update post
            $details = array(
              'post_content'  => $desc,
              'post_title'  =>  $title,
               'post_type' =>  $post_type,
              'ID' => intval($_POST['postid'])
            );
            $post_id = wp_update_post($details);
        } else {
            //Insert Post
            $details = array(
              'post_content'  => $desc,
              'post_title'  =>  $title,
              'post_name' => sanitize_title($title),
              'post_type' =>  $post_type,
              'post_status' => 'publish'
            );
            if ($post_type == 'mct_notepg') $details['post_parent'] = $notebk_id;
            $post_id = wp_insert_post($details);
        }
        if ($post_id) {
            $response->add(array('data' => 'Ok'));
        } else {
            if ($type == 'edit-nb') {
                $response->add(array('data' => 'Error - Could Not Edit '.$post_desc));
            } else {
                $response->add(array('data' => 'Error - Could Not Add New'.$post_desc));
            }
        }
        $response->send();
        exit();
    }
    //send invalid request
    $response->add(array('data' => 'Error - Invalid Request'));
    $response->send();
    exit();
}
?>