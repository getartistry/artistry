<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db009_user_css($plugin) { ?>
.et_pb_column_4_4 .et_pb_team_member_description, 
.et_pb_column_3_4 .et_pb_team_member_description, 
.et_pb_column_2_3 .et_pb_team_member_description {
	margin-left: 0px;
}
.et_pb_column_4_4 > .et_pb_team_member_description, 
.et_pb_column_3_4 > .et_pb_team_member_description, 
.et_pb_column_2_3 > .et_pb_team_member_description {
	margin-left: 350px;
}
<?php
}
add_action('wp_head.css', 'db009_user_css');