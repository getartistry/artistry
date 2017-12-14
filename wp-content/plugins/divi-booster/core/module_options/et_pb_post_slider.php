<?php
// Add options to post slider - just adds standard et_pb_slider options
add_filter('dbmo_et_pb_post_slider_whitelisted_fields', 'dbmo_et_pb_slider_register_fields');
add_filter('dbmo_et_pb_post_slider_fields', 'dbmo_et_pb_slider_add_fields');
add_filter('db_pb_post_slider_content', 'db_pb_post_slider_filter_content', 10, 2);

function db_pb_post_slider_filter_content($content, $args) {
	return db_pb_slider_filter_content($content, $args, 'et_pb_post_slider');
}
