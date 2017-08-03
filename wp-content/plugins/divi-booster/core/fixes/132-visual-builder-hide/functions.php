<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

function db132_admin_css($plugin) { ?>
<style>#et_pb_fb_cta { display: none !important; }</style>
<?php 
}
add_action('admin_head-post.php', 'db132_admin_css');
add_action('admin_head-post-new.php', 'db132_admin_css');
add_action('admin_head-edit.php', 'db132_admin_css');

function db132_user_css($plugin) { ?>
<style>#wp-admin-bar-et-use-visual-builder { display: none !important; }</style>
<?php 
}
add_action('wp_head', 'db132_user_css');