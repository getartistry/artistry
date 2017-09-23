<?php // add page builder support to all post types which have the editor (pre-divi 2.4 only)

if (!defined('ABSPATH')) { exit(); } // No direct access

if (!is_divi24()) { 

	function wtfdivi013_et_pb_before_main_editor( $post ) {
		if (in_array( $post->post_type, array('page', 'project'))) return;
		if (!post_type_supports($post->post_type, 'editor')) { return; }

		$is_builder_used = 'on' === get_post_meta( $post->ID, '_et_pb_use_builder', true ) ? true : false;

		printf( '<a href="#" id="et_pb_toggle_builder" data-builder="%2$s" data-editor="%3$s" class="button button-primary button-large%5$s">%1$s</a><div id="et_pb_main_editor_wrap"%4$s>',
			( $is_builder_used ? __( 'Use Default Editor', 'Divi' ) : __( 'Use Page Builder', 'Divi' ) ),
			__( 'Use Page Builder', 'Divi' ),
			__( 'Use Default Editor', 'Divi' ),
			( $is_builder_used ? ' class="et_pb_hidden"' : '' ),
			( $is_builder_used ? ' et_pb_builder_is_used' : '' )
		);
	}
	add_action( 'edit_form_after_title', 'wtfdivi013_et_pb_before_main_editor' );

	function wtfdivi013_et_pb_after_main_editor( $post ) {
		if (in_array( $post->post_type, array('page', 'project'))) return;
		if (!post_type_supports($post->post_type, 'editor')) { return; }
		?>
			<p class="et_pb_page_settings" style="display: none;">
			<input type="hidden" id="et_pb_use_builder" name="et_pb_use_builder" value="<?php echo esc_attr( get_post_meta( $post->ID, '_et_pb_use_builder', true ) ); ?>" />
			<textarea id="et_pb_old_content" name="et_pb_old_content"><?php echo esc_attr( get_post_meta( $post->ID, '_et_pb_old_content', true ) ); ?></textarea>
			</p>
			</div>
		<?php
	}
	add_action( 'edit_form_after_editor', 'wtfdivi013_et_pb_after_main_editor' );
	
}

function wtfdivi013_et_pb_builder_post_types($post_types) {
	foreach(get_post_types() as $pt) {
		if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
			$post_types[] = $pt;
		}
	} 
	return $post_types;
}
add_filter('et_pb_builder_post_types', 'wtfdivi013_et_pb_builder_post_types');

// Fix Divi color picker dependency bug 
function wtfdivi013_enqueue_admin_post_settings() { 
	wp_enqueue_script('wtfdivi013_divi_admin_post_settings', get_template_directory_uri() . '/js/admin_post_settings.js', array('wp-color-picker')); 
}
add_action('admin_enqueue_scripts', 'wtfdivi013_enqueue_admin_post_settings');

// Override post truncation function used by excerpt. 
// Need to remove blog module to avoid infinite loops. But can't just filter the_content as that removes the blog module from top level posts too.
if ( ! function_exists( 'truncate_post' ) ){
	function truncate_post( $amount, $echo = true, $post = '' ) {
		global $shortname;

		if ( '' == $post ) global $post;

		$post_excerpt = '';
		$post_excerpt = apply_filters( 'the_excerpt', $post->post_excerpt );

		if ( 'on' == et_get_option( $shortname . '_use_excerpt' ) && '' != $post_excerpt ) {
			if ( $echo ) echo $post_excerpt;
			else return $post_excerpt;
		} else {
			// get the post content
			$truncate = $post->post_content;

			// remove caption shortcode from the post content
			$truncate = preg_replace('@\[caption[^\]]*?\].*?\[\/caption]@si', '', $truncate);
			
			// DM - remove non-exerptable modules from excerpts
			$truncate = preg_replace('@\[et_pb_blog[^\]]*?\]@si', '', $truncate); // blog module
			$truncate = preg_replace('@\[et_pb_signup[^\]]*?\]@si', '', $truncate); // subscribe module
			$truncate = preg_replace('@\[et_pb_sidebar[^\]]*?\]@si', '', $truncate); // sidebar module
			
			// remove post header and footer content from excerpts
			$truncate = preg_replace('@\[(db_post_meta|db_feature_image|db_post_title|db_post_ad|db_comments_form)\]@si', '', $truncate); // sidebar module

			// apply content filters
			$truncate = apply_filters( 'the_content', $truncate );

			// decide if we need to append dots at the end of the string
			if ( strlen( $truncate ) <= $amount ) {
				$echo_out = '';
			} else {
				$echo_out = '...';
				// $amount = $amount - 3;
			}

			// trim text to a certain number of characters, also remove spaces from the end of a string ( space counts as a character )
			if ( ! $echo ) {
				$truncate = rtrim( et_wp_trim_words( $truncate, $amount, '' ) );
			} else {
				$truncate = rtrim( wp_trim_words( $truncate, $amount, '' ) );
			}

			// remove the last word to make sure we display all words correctly
			if ( '' != $echo_out ) {
				$new_words_array = (array) explode( ' ', $truncate );
				array_pop( $new_words_array );

				$truncate = implode( ' ', $new_words_array );

				// append dots to the end of the string
				$truncate .= $echo_out;
			}

			if ( $echo ) echo $truncate;
			else return $truncate;
		};
	}
}



// Keep Page Layout setting available at all times
function wtfdivi013_make_page_layout_full_width_by_default() {
  
  // Set the default pagebuilder layout
  echo <<<END
<script>
jQuery(function($){ 

	$('.post-new-php.post-type-post #et_pb_toggle_builder').click(function(){ 

		var textarea_id = 'content';
	
		if ($(this).text() == 'Use Page Builder') { 
			
			old_content = wp_editor_get_content('content');
			
			layout = $('#et_pb_page_layout').val();
			
			var post_header = '[et_pb_text admin_label="Post Header" background_layout="light" text_orientation="left"]<h1>[db_post_title]</h1>[db_post_meta][db_feature_image][/et_pb_text]';
			var post_content = '[et_pb_text admin_label="Post Content" background_layout="light" text_orientation="left"]'+old_content+'[/et_pb_text]';
			var post_footer = '[et_pb_text admin_label="Post Footer" background_layout="light" text_orientation="left"][db_post_ad][db_comments_form][/et_pb_text]';
			
			if (layout == "et_left_sidebar") { wp_editor_set_content('content', '[et_pb_section fullwidth="off" specialty="on"][et_pb_column type="1_4"][et_pb_sidebar admin_label="Sidebar" orientation="left" area="sidebar-1" background_layout="light" module_id="sidebar"/][/et_pb_column][et_pb_column type="3_4" specialty_columns="3"][et_pb_row_inner][et_pb_column_inner type="4_4"]' + post_header + post_content + post_footer + '[/et_pb_column_inner][/et_pb_row_inner][/et_pb_column][/et_pb_section]'); }
			
			if (layout == "et_right_sidebar") { wp_editor_set_content('content', '[et_pb_section fullwidth="off" specialty="on"][et_pb_column type="3_4" specialty_columns="3"][et_pb_row_inner][et_pb_column_inner type="4_4"]' + post_header + post_content + post_footer + '[/et_pb_column_inner][/et_pb_row_inner][/et_pb_column][et_pb_column type="1_4"][et_pb_sidebar admin_label="Sidebar" orientation="right" area="sidebar-1" background_layout="light" module_id="sidebar" /][/et_pb_column][/et_pb_section]');  }
			
			if (layout == "et_full_width_page") { wp_editor_set_content('content', '[et_pb_section fullwidth="off" specialty="off"][et_pb_row][et_pb_column type="4_4"]' + post_header + post_content + post_footer + '[/et_pb_column][/et_pb_row][/et_pb_section]');  }
			
			$('#et_pb_page_layout').val('et_full_width_page');
			
			setTimeout("jQuery('#et_pb_old_content').val(old_content);", 1000);
			
		} 

	});
		
	function wp_editor_set_content( textarea_id, content ) {
		if ( typeof window.tinyMCE !== 'undefined' && window.tinyMCE.get( textarea_id ) && ! window.tinyMCE.get( textarea_id ).isHidden() )
			window.tinyMCE.get( textarea_id ).setContent( content, { format : 'html'  } );
		else
			$( '#' + textarea_id ).val( content );
	}
	
	function wp_editor_get_content( textarea_id ) {
		var content = typeof window.tinyMCE !== 'undefined' && window.tinyMCE.get( textarea_id ) && ! window.tinyMCE.get( textarea_id ).isHidden()
			? window.tinyMCE.get( textarea_id ).getContent()
			: $( '#' + textarea_id ).val();

		return content.trim();
	}
});
</script>
END;
}
add_action('admin_head', 'wtfdivi013_make_page_layout_full_width_by_default', 1);

/* Make comments available as a shortcode */

function wtfdivi013_comments_form() {
	ob_start();
	if ((comments_open()||get_comments_number()) && 'on' == et_get_option('divi_show_postcomments', 'on')) {
		comments_template('', true);
	}
	return ob_get_clean();
}
add_shortcode('db_comments_form', 'wtfdivi013_comments_form');

function wtfdivi013_post_title() {
	return get_the_title();
}
add_shortcode('db_post_title', 'wtfdivi013_post_title');

function wtfdivi013_post_meta() {
	ob_start();
	if (!post_password_required()) { 
		et_divi_post_meta(); 
	}
	return ob_get_clean();
}
add_shortcode('db_post_meta', 'wtfdivi013_post_meta');

function wtfdivi013_post_ad() {
	ob_start();
	if ( et_get_option('divi_468_enable') == 'on' ){
		echo '<div class="et-single-post-ad">';
		if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
		else { ?>
			<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
<?php 	}
		echo '</div> <!-- .et-single-post-ad -->';
	}
	return ob_get_clean();
}
add_shortcode('db_post_ad', 'wtfdivi013_post_ad');

function wtfdivi013_featured_image() {
	ob_start();
	if (!post_password_required()) {
		$thumb = '';
		$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

		$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
		$classtext = 'et_featured_image';
		$titletext = get_the_title();
		$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
		$thumb = $thumbnail["thumb"];

		$post_format = get_post_format();

		if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
			printf(
				'<div class="et_main_video_container">
					%1$s
				</div>',
				$first_video
			);
		} else if ( ! in_array( $post_format, array( 'gallery', 'link', 'quote' ) ) && 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb ) {
			print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
		} else if ( 'gallery' === $post_format ) {
			et_gallery_images();
		}
	}
	return ob_get_clean();
}
add_shortcode('db_feature_image', 'wtfdivi013_featured_image');

function wtfdivi013_pagebuilder_for_posts_class($classes) {
	if (is_single() && et_pb_is_pagebuilder_used(get_the_ID())) {
		$classes[] = 'db_pagebuilder_for_posts';
	}
	return $classes;
}
add_filter('body_class', 'wtfdivi013_pagebuilder_for_posts_class');


function db013_user_css($plugin) { ?>
	/* Remove padding between post title and page builder content */
	.single .et_pb_section:nth-of-type(1), 
	.single .et_pb_section:nth-of-type(1) .et_pb_row:nth-of-type(1), 
	.single .entry-content { padding-top:0; }

	/* Remove content area top margin which appears when filtering blog modules from excerpts */
	#content-area { margin-top:0 !important; }

	/* Fix the unclickable links issue on sidebar with old version of pagebuilder for posts */
	.db_pagebuilder_for_posts.et_right_sidebar #sidebar *, 
	.db_pagebuilder_for_posts.et_left_sidebar #sidebar * { 
		position: relative; 
	}

	/* Fix empty specialty section layout issue */
	.db_pagebuilder_for_posts.et_full_width_page .et_pb_column { min-height:1px; } 

	/* Hide regular post content */
	.db_pagebuilder_for_posts.et_full_width_page article > :not(.entry-content) { display: none; }
	.db_pagebuilder_for_posts.et_full_width_page article.comment-body > * { display: block !important; }

	/* Adjust the padding to match the standard blog post format */
	.db_pagebuilder_for_posts.et_full_width_page .entry-content { padding-top: 0px !important; }
	.db_pagebuilder_for_posts.et_full_width_page .et_pb_widget_area_right { margin-bottom: 30px !important; margin-left:29px !important; }
	.db_pagebuilder_for_posts.et_full_width_page .et_pb_widget_area_left .et_pb_widget { margin-bottom: 30px !important; margin-left: 0px !important; margin-right: 30px !important; }

	<?php 
	if (is_divi24()) { ?>
		.single .et_pb_row { width:90% !important; }
		@media only screen and (min-width: 981px) {
			.single #sidebar.et_pb_widget_area {
			  width: 100% !important;
			}
		}
	<?php 
	} 
}
add_action('wp_head.css', 'db013_user_css');
