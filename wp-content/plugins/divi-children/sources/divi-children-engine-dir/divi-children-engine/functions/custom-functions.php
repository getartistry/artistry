<?php

/**
 * Divi Child Theme customizing functions
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



/**
 * Custom version of the et_get_footer_credits Divi pluggable function to allow Divi Children custom Footer Credits
 */

function et_get_footer_credits() {
	$original_footer_credits = et_get_original_footer_credits();
	$disable_custom_credits = et_get_option( 'disable_custom_footer_credits', false );
	if ( $disable_custom_credits ) {
		return '';
	}
	$credits_format = '<p id="footer-info">%1$s</p>';
	if ( get_theme_mod( 'dce_customize_footer_credits', 1 ) ) {
			$footer_credits = dce_get_footer_credits();
		} else {
			$footer_credits = et_get_option( 'custom_footer_credits', '' );
			if ( '' === trim( $footer_credits ) ) {
				return et_get_safe_localization( sprintf( $credits_format, $original_footer_credits ) );
			}			
	}
	return et_get_safe_localization( sprintf( $credits_format, $footer_credits ) );
}


/**
 * Custom Footer Credits
 */

function dce_get_footer_credits() {
	$firstyear = get_theme_mod( 'dce_footer_credits_firstyear', date( 'Y' ) );
	$current_year = date( 'Y' );
	if ( $firstyear AND ( $firstyear != $current_year ) AND ( $firstyear != 0 ) ) {
			$footer_credits_years = $firstyear . ' - ' . $current_year;
		} else {
			$footer_credits_years = $current_year;	
	}
	$separator = get_theme_mod( 'dce_footer_credits_separator', '&#124;' );
	$credits = '';
	$default_footer_credits = dce_get_footer_credits_defaults();
	$footer_credits = get_theme_mod( 'dce_footer_credits', $default_footer_credits );
	foreach ( $footer_credits as $key => $element ) {
		$before = $element['before'];
		$before = ( $before != '' ) ? $before . ' ' : '';
		$link = $element['link'];
		$url = $element['url'];
		$after = $element['after'];
		$after = ( $after != '' ) ? ' ' . $after : '';
		if ( $element['blank'] ) {
				$target = ' target="_blank" ';
			} else {
				$target = '';
		}
		$years = $element['years'];
		if ( 'none' !== $years ) {
			if ( 'before-before' === $years ) {
					$before = $footer_credits_years. ' ' . $before;
				} elseif ( 'after-before' === $years ) {
					$before = $before . $footer_credits_years. ' ';
				} elseif ( 'before-link' === $years ) {
					$link = $footer_credits_years. ' ' . $link;
				} elseif ( 'after-link' === $years ) {
					$link = $link . ' ' . $footer_credits_years;
				} elseif ( 'before-after' === $years ) {
					$after = ' ' . $footer_credits_years. $after;
				} elseif ( 'after-after' === $years ) {
					$after = $after . ' ' . $footer_credits_years;
			}
		}
		if ( 0 === $key ) {
				$credits_separator = '';
			} else {
				$credits_separator = '<span class="dce-credits-separator">' . $separator . '</span>';
		}
		if ( '' != $url ) {
				$credits .= $credits_separator . $before . '<a href="' . esc_url( $url ) . '"' . $target .'>' . $link . '</a>' . $after;
			} else {
				$credits .= $credits_separator . $before . $link . $after;
		}
	}
	return $credits;
}


/**
 * Footer Credits defaults
 */

function dce_get_footer_credits_defaults(){
	$defaults = array();
	$user_name = wp_get_current_user()->display_name;
	$site_name = get_bloginfo( 'name' );
	$site_url = home_url();
	$defaults[] = array(
		'before'	=> __( 'Copyright ©', 'divi-children-engine' ),
		'link'	=> $site_name,
		'url'	=> $site_url,
		'blank'	=> '1',
		'after'	=> '',
		'years'	=> 'after-before',
	);
	$defaults[] = array(
		'before'	=> __( 'Developed by', 'divi-children-engine' ),
		'link'	=> $user_name,
		'url'	=> $site_url,
		'blank'	=> '1',
		'after'	=> '',
		'years'	=> 'none',
	);
	$defaults[] = array(
		'before'	=> __( 'Proudly powered by', 'divi-children-engine' ),
		'link'	=> __( 'WordPress', 'divi-children-engine' ),
		'url'	=> 'http://www.wordpress.org/',
		'blank'	=> '1',
		'after'	=> '',
		'years'	=> 'none',
	);
	return $defaults;
}


/**
 * Modifies the Divi et_divi_post_meta() pluggable function. Replaces it with the custom child theme function dce_post_meta() when needed.
 */

function et_divi_post_meta() {
	if ( is_single() ) {
			dce_post_meta( 'single' );
		} else {
			$postinfo = is_single() ? et_get_option( 'divi_postinfo2' ) : et_get_option( 'divi_postinfo1' );
			if ( $postinfo ) :
				echo '<p class="post-meta">';
				echo et_pb_postinfo_meta( $postinfo, et_get_option( 'divi_date_format', 'M j, Y' ), esc_html__( '0 comments', 'Divi' ), esc_html__( '1 comment', 'Divi' ), '% ' . esc_html__( 'comments', 'Divi' ) );
				echo '</p>';
			endif;
	}
}


/**
 * Post meta function modified to add a prefix indicating where the function is used (blog module-grid, blog module-fullwidth, single post) - et_divi_post_meta modified
 */

function dce_post_meta( $module_prefix ) {
	if ( $module_prefix == 'single' ) {
		$module_prefix = 'dce_post_';
	}
	$postinfo = is_single() ? et_get_option( 'divi_postinfo2' ) : et_get_option( 'divi_postinfo1' );
	if ( $module_prefix == 'dce_blog_grid_' ) { // Allow comments for Grid Blog module
		$postinfo = et_get_option( 'divi_postinfo2' );	
	}
	if ( $postinfo ) :
		echo '<p class="post-meta">';
		dce_postinfo_meta( $module_prefix, $postinfo, et_get_option( 'divi_date_format', 'M j, Y' ), esc_html__( '0 comments', 'Divi' ), esc_html__( '1 comment', 'Divi' ), '% ' . esc_html__( 'comments', 'Divi' ) );
		echo '</p>';
	endif;
}


/**
 * Post meta with or without icons - et_pb_postinfo_meta modified
 */

function dce_postinfo_meta( $module_prefix, $postinfo, $date_format, $comment_zero, $comment_one, $comment_more ){
	$postinfo_meta = '';

	if ( get_theme_mod( 'dce_customize_postmeta', 1 ) ) {
			$postmeta_elements_defaults = array(
				array(
					'element' => 'author',
					'text' => __( 'By:', 'divi-children-engine' ),
				),
				array(
					'element' => 'date',
					'text' => __( 'Published on:', 'divi-children-engine' ),
				),
				array(
					'element' => 'categories',
					'text' => __( 'Categories:', 'divi-children-engine' ),
				),
				array(
					'element' => 'comments',
					'text' => '',
				),
			);
			$postmeta_elements = get_theme_mod( $module_prefix . 'postmeta_elements', $postmeta_elements_defaults );
		} else {
			$postmeta_elements = array(
				array(
					'element' => 'author',
					'text' => __( 'by', 'et_builder' ),
				),
				array(
					'element' => 'date',
					'text' => '',
				),
				array(
					'element' => 'categories',
					'text' => '',
				),
				array(
					'element' => 'comments',
					'text' => '',
				),
			);
	}

	if ( 'icons' == get_theme_mod( $module_prefix . 'postmeta_with_icons', 'default' ) ) {
			$icons = true;
		} else {
			$separator = get_theme_mod( $module_prefix . 'postmeta_separator', '&#124;' );
			$postmeta_separator = '<span class="dce-postmeta-separator" >' . $separator . '</span>';
			$icons = false;
	}
	$last_key = count( $postmeta_elements ) - 1;
	foreach ( $postmeta_elements as $key => $postmeta_element ) {
		$element = $postmeta_element['element'];
		$text_before = $postmeta_element['text'];
		$text_before = ( $text_before !== '' ) ? $text_before . ' ' : '';
		if ( ( 'author' === $element ) AND ( in_array( 'author', $postinfo ) ) ) {
			$before = ( $icons ) ? '<span class="dce_icon icon_profile" ></span>' : $text_before . ' ';
			$postinfo_meta .= $before . et_pb_get_the_author_posts_link();
		}
		if ( ( 'date' === $element ) AND ( in_array( 'date', $postinfo ) ) ) {
			$before = ( $icons ) ? '<span class="dce_icon icon_calendar" ></span>' : $text_before . ' ';
			$postinfo_meta .= $before . get_the_time( $date_format );
		}
		if ( ( 'mod_date' === $element ) AND ( in_array( 'date', $postinfo ) ) ) {
			$before = ( $icons ) ? '<span class="dce_icon icon_refresh" ></span>' : $text_before . ' ';
			$postinfo_meta .= $before . get_the_modified_time( $date_format );
		}
		if ( ( 'categories' === $element ) AND ( in_array( 'categories', $postinfo ) ) ) {
			$before = ( $icons ) ? '<span class="dce_icon icon_clipboard" ></span>' : $text_before . ' ';
			$postinfo_meta .= $before . get_the_category_list(', ' );
		}
		if ( ( 'comments' === $element ) AND ( in_array( 'comments', $postinfo ) ) ) {
			$before = ( $icons ) ? '<span class="dce_icon icon_chat" ></span>' : $text_before . ' ';
			$postinfo_meta .= $before . et_pb_get_comments_popup_link( $comment_zero, $comment_one, $comment_more );
		}
		if ( 'tags' === $element ) {
			$posttags = get_the_tags();
			if ( $posttags ) {
				$before = ( $icons ) ? '<span class="dce_icon icon_tags" ></span>' : $text_before . ' ';
				$postinfo_meta .= $before;
				$count = 0;
				foreach( $posttags as $tag ) {
					if ($count > 0) {
						$postinfo_meta .= ', ';
					}
					$postinfo_meta .= '<a href="' . get_home_url() . '/tag/' . $tag->slug . '">' . $tag->name . '</a>';
					$count++;
				}
			}
		}
		if ( !$icons AND ( $last_key != $key ) ) {
			$postinfo_meta .= $postmeta_separator;
		}				
	}
	echo $postinfo_meta;
}


/**
 * Post meta tags with or without icon at the end of the post
 */
 
function dce_tags_after_content( $content ) {
	if ( get_theme_mod( 'dce_post_tags_after_content', '' ) ) {
		if( is_single() ) {
			$posttags = get_the_tags();
			if ( $posttags ) {
				$content .= '<p class= "dce-post-tags">';
				if ( 'icons' == get_theme_mod( 'dce_post_postmeta_with_icons', 'default' ) ) {
						$content .= '<span class="icon_tags" ></span>';
					} else {
						$content .= '<em>' . get_theme_mod( 'dce_post_tags_after_content_text', 'Tagged:' ) . '</em> ';
				}
				$count = 0;
				foreach( $posttags as $tag ) {
					if ($count > 0) {
						$content .= ', ';
					}
					$content .= '<a href="' . get_home_url() . '/tag/' . $tag->slug . '">' . $tag->name . '</a>';
					$count++;
				}
				$content .= '</p>';
			}
		}
	}
	return $content;	
}
add_filter( 'the_content', 'dce_tags_after_content', 1 );


/**
 * DCE Social Icons function
 */

function dce_get_social_icons() {
	$default_social_icons = dce_get_social_icons_defaults();
	$social_icons = get_theme_mod( 'dce_social_icons', $default_social_icons );
	ob_start();
	?>
	<ul class="et-social-icons">
		<?php
		foreach ( $social_icons as $social_icon ) {
			$network = $social_icon['network'];
			if( 'google+' === $network ) {
					$class = 'et-social-icon et-social-google-plus';
				} else {
					$class = 'et-social-icon et-social-' . $network;
			}
			if ( $social_icon['blank'] ) {
					$target = ' target="_blank" ';
				} else {
					$target = '';
			}
			$link = $social_icon['url'];
			?>
			<li class="<?php echo $class; ?>">
				<a href="<?php echo esc_url( $link ); ?>"<?php echo $target; ?> class="icon">
					<span><?php echo $network; ?></span>
				</a>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
	$output = ob_get_clean();
	return $output;
}


/**
 * DCE Social Icons defaults
 */

function dce_get_social_icons_defaults() {
	$defaults = array();
	if ( 'on' === dce_get_divi_option( 'divi_show_facebook_icon', 'on' ) ) {
		$defaults[] = array(
			'network'	=> esc_attr__( 'facebook', 'divi-children-engine' ),
			'url'		=> dce_get_divi_option( 'divi_facebook_url', '#' ),
			'blank'		=> '1',
		);
	}
	if ( 'on' === dce_get_divi_option( 'divi_show_twitter_icon', 'on' ) ) {
		$defaults[] = array(
			'network'	=> esc_attr__( 'twitter', 'divi-children-engine' ),
			'url'		=> dce_get_divi_option( 'divi_twitter_url', '#' ),
			'blank'		=> '1',
		);
	}
	if ( 'on' === dce_get_divi_option( 'divi_show_google_icon', 'on' ) ) {
		$defaults[] = array(
			'network'	=> esc_attr__( 'google+', 'divi-children-engine' ),
			'url'		=> dce_get_divi_option( 'divi_google_url', '#' ),
			'blank'		=> '1',
		);
	}
	if ( 'on' === dce_get_divi_option( 'divi_show_rss_icon', 'on' ) ) {
		$defaults[] = array(
			'network'	=> esc_attr__( 'rss', 'divi-children-engine' ),
			'url'		=> dce_get_divi_option( 'divi_rss_url', '#' ),
			'blank'		=> '1',
		);
	}
	return $defaults;
}


/**
 * Find if a social icon is active
 */

function dce_active_social_icon( $social_network ) {
	foreach ( get_theme_mod( 'dce_social_icons', dce_get_social_icons_defaults() ) as $element ) {
		if ( ( $element == $social_network ) OR ( is_array( $element ) AND in_array( $social_network, $element ) ) ) {
			return true;
		}
	}
	return false;
}


/**
 * Get a Divi option, even from the Customizer to establish defaults
 */

function dce_get_divi_option( $option_name, $default_value = '' ) {
	global $et_theme_options;
	if ( ! isset( $et_theme_options ) || isset( $_POST['wp_customize'] ) ) {
		$et_theme_options = get_option( 'et_divi' );
	}
	$option_value = isset( $et_theme_options[$option_name] ) ? $et_theme_options[$option_name] : false;
	if ( ! isset( $et_theme_options[ $option_name ] ) && ( '' != $default_value ) ) {
		$option_value = $default_value;
	}
	return $option_value;
}

