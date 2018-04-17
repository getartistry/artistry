<?php

add_action( 'case27_widgets_init', function() {
	// Latest Posts Widget
	require_once CASE27_PLUGIN_DIR . '/widgets/latest-posts.php';

	// Contact Form Widget
	require_once CASE27_PLUGIN_DIR . '/widgets/contact-form.php';
});