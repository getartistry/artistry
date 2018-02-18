<?php

/**
 * Use the default Divi single.php or the Divi Children Custom Post Template.
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


$dce_post_layout = get_theme_mod( 'dce_post_layout', 'default' );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

$post_format = get_post_format(); // if $post_format is not false, then the post format is not 'Standard' (would be 'Quote', 'Audio', 'Gallery', 'Link'...)


if ( ( 'default' == $dce_post_layout ) OR $is_page_builder_used OR $post_format ) {

		// Use default Divi single.php in the following cases:
		//	a) If post layout set to 'default'
		// 	b) If Page Builder is used
		// 	c) If the post format is not 'Standard'
		include( get_template_directory() . '/single.php');

	} elseif ( 'custom' == $dce_post_layout ) {

		include( DCE_PATH . '/child-includes/templates/post-template-custom.php' );

}

?>