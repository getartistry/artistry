<?php
/* This is the server page that handles training requests from blog pages
 *
*/
/** WordPress  Bootstrap */
include_once (dirname(dirname(dirname(dirname(__FILE__)))) .  DIRECTORY_SEPARATOR."wp-load.php");

$sendback = wp_get_referer();  //How to get back
$sendback = remove_query_arg( array('good', 'bad', 'move', 'multi'), $sendback );
//Handle training a post
if (isset($_GET['bad']) || isset($_GET['good'])){
    //call train_post with post id, topic, category
    if (!current_user_can('edit_published_posts')){
        wp_die('Insufficient Priveleges to Train Post');
    }
    if (isset($_GET['bad'])){
        $cat = 'bad';
        $pid = intval($_GET['bad']);
    }
    if (isset($_GET['good'])){
        $cat = 'good';
        $pid = intval($_GET['good']);
    }
    check_admin_referer('mct_ai_train_'.$cat.$pid);
    // Get the topic name 
    $terms = get_the_terms( $pid, 'topic' );
    $tname = '';
    if (count($terms) == 1 ) { //should only be one
        //The array key is the id
        $tids = array_keys($terms);
        $term = $terms[$tids[0]];
        $tname = $term->name;
    }
    if ($tname != ''){
        mct_ai_trainpost($pid, $tname, $cat);
        if ($cat == 'bad'){
            wp_trash_post($pid);
            $sendback = add_query_arg('ids',$pid, $sendback);
            wp_redirect($sendback);
        }
        if (isset($_GET['move'])){
            mct_ai_movepost($pid);  //same post as we just trained
        }
    }
    
} else {
    
    if (isset($_GET['move'])){
        if (!current_user_can('edit_published_posts')){
            wp_die('Insufficient Priveleges to Move Post');
        }
        $pid = intval($_GET['move']);
        check_admin_referer('mct_ai_move'.$pid);
        mct_ai_movepost($pid);
    }
    if (isset($_GET['draft'])){
        if (!current_user_can('edit_published_posts')){
            wp_die('Insufficient Priveleges to Move Post');
        }
        $pid = intval($_GET['draft']);
        check_admin_referer('mct_ai_draft'.$pid);
        mct_ai_traintoblog($pid,'draft');
    }

    if (isset($_GET['multi'])){
        if (!current_user_can('edit_published_posts')){
            wp_die('Insufficient Priveleges to Move Post');
        }
        $pid = intval($_GET['multi']);
        check_admin_referer('mct_ai_multi'.$pid);
        mct_ai_train_multi($pid);
    }
}
$sendback = add_query_arg('ids',$pid, $sendback);
wp_redirect($sendback);

function mct_ai_movepost($thepost){
    global $mct_ai_optarray;
    if ($mct_ai_optarray['ai_edit_makelive']) {
        mct_ai_traintoblog($thepost,'draft');
        $edit_url = get_edit_post_link( $thepost, array('edit' => '&amp;'));
        wp_redirect($edit_url);
        exit();
    } else {
        mct_ai_traintoblog($thepost,'publish');
    }
}
?>
