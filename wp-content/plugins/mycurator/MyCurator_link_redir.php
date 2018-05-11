<?php
//MyCurator_link_redir.php
//
// These functions handle the redirection to the saved pages as well
//  as deleting and displaying saved page links
//
//Metabox to show link data on post page
add_action('add_meta_boxes','mct_sl_linkmeta');
//Remove saved page from DB if deleting post
add_action('before_delete_post','mct_sl_deletefile');
//add rewrite rule on init
add_action('init','mct_sl_add_rule');
//add filter on template redirect for rewrite
add_action('template_redirect','mct_sl_temp_redir');
add_filter('query_vars','mct_sl_qvar');


function mct_sl_add_rule(){
    //add ailink rule for saved db pages
    add_rewrite_rule('^(.*)/?'.MCT_AI_REDIR.'/([^/]+)/?$','index.php?aipageid=$matches[2]','top');
}
function mct_sl_qvar($vars){
    //query vars for rewrite
    $vars[]= 'aipageid';  //save pages in db link
    return $vars;
}
function mct_sl_temp_redir(){
    //Set up redirection
    global $userdata, $ai_sl_pages_tbl, $currenturl;

    //Redirect based on save page redirection
    $page_id = intval(get_query_var('aipageid'));

    if ($page_id != ''){
        $vals = mct_sl_getsavedpage($page_id);
        $page = $vals['sl_page_content'];

        //If page didn't render, we will find a redirect comment, with a url to redirect too
        $pos = preg_match('@Redirect{([^}]*)}@',$page,$matches);
        if ($pos){
            $sendback = wp_get_referer();  //How to get back
            header('Content-Type: text/html');
            header('Referer: '.$sendback);
            wp_redirect($matches[1]);
            exit;
            
        } else {  //display the saved page
            header('Content-Type: text/html');
            echo($page);
            exit();
        }
    }
}

function mct_sl_getsavedpage($sl_id){
    //this function returns the page content and post id for a saved page given a page id
    global $ai_sl_pages_tbl, $wpdb;
    
    $sql = "SELECT `sl_page_content`, `sl_post_id`
            FROM $ai_sl_pages_tbl
            WHERE sl_page_id = '$sl_id'";
    $vals = $wpdb->get_row($sql, ARRAY_A);
    return $vals;
}

function mct_sl_deletefile($post_id){
    //Hook to remove saved pages from db when post is deleted
    //Also remove images linked to a post if from MyCurator
    //Also handle delete of Notebooks (notepages will have a saved page)
    global $wpdb, $ai_sl_pages_tbl, $mct_ai_optarray;
    // Get the links from the meta data, allow for more than one
    $newlinks = get_post_meta($post_id,'mct_sl_newurl',true);
    $ptype = get_post_type($post_id);
    if (!empty($newlinks)){
        $sql = "DELETE FROM $ai_sl_pages_tbl WHERE sl_post_id = $post_id";
        $del = $wpdb->query($sql);
        //Delete image if being saved
        mct_sl_deleteimage($post_id);
        //Delete topic/ai class taxonomies if not target_ai type
        if ($ptype != 'target_ai') wp_delete_object_term_relationships( $post_id, array('topic','ai_class') );
    }
    //Check for Notebook post type and delete all pages
    if ($ptype == 'mct_notebk'){
        $args = array(
        'numberposts'     => -1,
        'post_type'       => 'mct_notepg',
        'post_parent'     => $post_id); 
        $notepgs = get_posts($args);
        foreach ($notepgs as $notepg) {
            wp_delete_post($notepg->ID,true);
        }
    }
}

function mct_sl_deleteimage($post_id){
    //Delete image attachments and featured images from a post
    global $wpdb;
    
    $thumb_id = get_post_meta($post_id, '_thumbnail_id',true);  //Post Thumbnail
    if ($thumb_id) wp_delete_attachment($thumb_id,true);
    //Now try inserted image
    $ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_parent = $post_id AND post_type = 'attachment'");
    if (!empty($ids)) {
        foreach ( $ids as $id )
                wp_delete_attachment($id);
    }
}
function mct_sl_linkmeta(){
    // Set up meta box for link replacement data
    add_meta_box('mct_sl_metabox','Link Replacement for MyCurator','mct_sl_linkmetashow','post','normal','low');
}

function mct_sl_linkmetashow($post){
    //Show the original and new links for the post
    $origlinks = get_post_meta($post->ID,'mct_sl_origurl',true);
    $newlinks = get_post_meta($post->ID,'mct_sl_newurl',true);
    if ($origlinks == ''){
        return;
    }
    ?><table> <?php
    for ($i=0;$i<count($origlinks);$i++){
        ?>
    <tr>
        <td><em>Original Link: </em></td>
        <td><?php echo '<a href="'.$origlinks[$i].'" target="_blank">'.$origlinks[$i].'</a>'; ?></td>
    </tr>
    <tr>
        <td><em>New Link: </em></td>
        <td><?php echo '<a href="'.$newlinks[$i].'" target="_blank">'.$newlinks[$i].'</a>'; ?>
    </tr>
    <?php }  //end for loop ?>
  
   
    </table> <?php
}

function mct_ai_getslpage($post_id){
    //this function returns the page content given a post id
    global $ai_sl_pages_tbl, $wpdb;
    
    $sql = "SELECT `sl_page_content`
            FROM $ai_sl_pages_tbl
            WHERE sl_post_id = $post_id";
    $vals = $wpdb->get_col($sql);
    if (empty($vals)) return '';
    return $vals[0];
}

function mct_ai_getslarticle($page){
    //Gets the article contents out of the page
    
    if (empty($page)) return '';
    //$page has the content, with html, using the format of rendered page, separate sections
    $cnt = preg_match('{<span class="mct-ai-article-content">(.*)}si',$page,$matches);  //don't stop at end of line
    $article = $matches[1];
    $article = preg_replace('{</span></div></body></html>}','',$article);
    //Get Author, Date
    //$cnt = preg_match('{<div id="savelink-author">([^<]*)</div>}',$page,$matches);
    //if ($cnt) $article = '<p>'.$matches[1].'</p>'.$article;
    return $article;
}

function mct_ai_getlinkredir($post_id) {
   //this function returns the redirect link to the saved page given a post id
    global $ai_sl_pages_tbl, $wpdb;
    
    $sql = "SELECT `sl_page_id`
            FROM $ai_sl_pages_tbl
            WHERE sl_post_id = $post_id";
    $vals = $wpdb->get_col($sql);
    if (empty($vals)) return '';  
    $page_id = $vals[0];
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
    return $link_redir;
}

function mct_ai_notable_article($page){
    //If table at beginning, don't get article, add other checks here as we find them
    $article = mct_ai_getslarticle($page);
    if (empty($article)) return $article;
    $msg = "<p>Invalid Article Format - Cannot display text on this page</p>
        <p>The article text will still be available in the editor</p>
        <p>Use the link to the original article web page to review this article</p>";
    
    $pos = preg_match('{^[\s]*<t}i',$article,$matches);
    if ($pos) return $msg;
    $pos = preg_match('{^[\s]*</div>}i',$article,$matches);
    if ($pos) return $msg;
    $pos = preg_match('{^[\s]*<body>}i',$article,$matches);
    if ($pos) return $msg;
    
    return $article;
}

function mct_ai_clean_article($page){
    //Clean articles for training page
    $article = mct_ai_getslarticle($page);
    if (empty($article)) return $article;
    $msg = "<p>Invalid Article Format - Cannot display text on this page</p>
        <p>The article text will still be available in the editor</p>
        <p>Use the link to the original article web page to review this article</p>";
    
    $pos = preg_match('{^[\s]*<body>}i',$article,$matches);
    if ($pos) return $msg;
    $pos = preg_match('{^[\s]*</div>}i',$article,$matches);
    if ($pos) return $msg;
    
    return $article;
}

?>