<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

function db134_user_css($plugin) { ?>
<style>
.et-fb-page-settings-bar .et-fb-button--publish { 
    position: fixed; 
    left: 30px; 
    bottom: 65px; 
    border-radius: 3px 0 0 0 !important; 
}
.et-fb-page-settings-bar .et-fb-button--save-draft { 
    position: fixed; 
    left: 104px; 
    bottom: 65px; 
    border-radius: 0 3px 0 0 !important; 
} 
.et-fb-page-settings-bar > :first-child button { 
    border-top-left-radius: 0 !important; 
    border-top-right-radius: 0 !important; 
}
.et-fb-page-settings-bar > :first-child button { 
    width: 42px;
}
</style>
<?php 
}
add_action('wp_head', 'db134_user_css');