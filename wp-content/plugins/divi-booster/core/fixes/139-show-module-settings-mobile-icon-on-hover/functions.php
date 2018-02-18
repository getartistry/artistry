<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

add_action('admin_head', 'wtfdivi139_admin_css');

function wtfdivi139_admin_css() { ?>
<style>
.et-pb-option:hover .et-pb-mobile-settings-toggle {
    padding: 0 8px !important;
    z-index: 1 !important;
    opacity: 1 !important;
}
.et-pb-option:hover .et-pb-mobile-settings-toggle:after {
    opacity: 0.9;
    -moz-animation: et_pb_slide_in_bottom .6s cubic-bezier(0.77,0,.175,1);
    -webkit-animation: et_pb_slide_in_bottom .6s cubic-bezier(0.77,0,.175,1);
    -o-animation: et_pb_slide_in_bottom .6s cubic-bezier(0.77,0,.175,1);
    animation: et_pb_slide_in_bottom .6s cubic-bezier(0.77,0,.175,1);
}
</style>
<?php 
}
?>