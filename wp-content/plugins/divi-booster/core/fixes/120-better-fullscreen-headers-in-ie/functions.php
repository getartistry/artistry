<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db120_user_css($plugin) { ?>
<style>
body
  .et_pb_fullwidth_header.et_pb_fullscreen 
    .et_pb_fullwidth_header_container {
  height: 1px;
}
</style>
<!--[if lte IE 9]>
<style>
.et_pb_fullwidth_header.et_pb_fullscreen 
  .header-content-container.center { 
  position: relative;
  top: 50%;
  transform: translateY(-50%);
}
.et_pb_fullwidth_header.et_pb_fullscreen 
  .header-content-container.bottom { 
  position: relative;
  top: calc(100% - 80px);
  transform: translateY(-100%);
}
.et_pb_fullwidth_header.et_pb_fullscreen 
  .header-content { 
  float: none !important; 
  margin:0; 
  width: 100%; 
}
</style>
<![endif]-->
<?php 
}
add_action('wp_head', 'db120_user_css');