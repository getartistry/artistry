<?php

/**
 * Customizer output: Heading Styles section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_heading_styles_output() {

	$dce_output = '';

	$h1_customize = get_theme_mod( 'dce_heading_h1_customize', 0 );
	$h2_customize = get_theme_mod( 'dce_heading_h2_customize', 0 );
	$h3_customize = get_theme_mod( 'dce_heading_h3_customize', 0 );
	$h4_customize = get_theme_mod( 'dce_heading_h4_customize', 0 );
	$h5_customize = get_theme_mod( 'dce_heading_h5_customize', 0 );
	$h6_customize = get_theme_mod( 'dce_heading_h6_customize', 0 );

	if ( $h1_customize OR $h2_customize OR $h3_customize OR $h4_customize OR $h5_customize OR $h6_customize ) {
		$default_h1_size = dce_get_divi_option( 'body_header_size', 30 );
		if ( 30 != $default_h1_size ) {
			$default_h2_size = round( $default_h1_size * 0.86 );
			$default_h3_size = round( $default_h1_size * 0.73 );
			$default_h4_size = round( $default_h1_size * 0.60 );
			$default_h5_size = round( $default_h1_size * 0.53 );
			$default_h6_size = round( $default_h1_size * 0.47 );
			} else {
				$default_h2_size = 26;
				$default_h3_size = 22;
				$default_h4_size = 18;
				$default_h5_size = 16;
				$default_h6_size = 14;	
		}
		$default_line_height = dce_get_divi_option( 'body_header_height', 1 );
	}

	if ( $h1_customize ) {
		$h1_disable = get_theme_mod( 'dce_heading_h1_disable', 'none' );
		if ( 'tablet' == $h1_disable ) {
				$h1_media_query_open = '@media all and (min-width: 981px) {';
				$h1_media_query_close = '}';
			} elseif ( 'phone' == $h1_disable  ) {
				$h1_media_query_open = '@media all and (min-width: 768px) {';
				$h1_media_query_close = '}';
			} else {
				$h1_media_query_open = false;
				$h1_media_query_close = false;
		}
		$h1_size = get_theme_mod( 'dce_heading_h1_font_size', $default_h1_size );
		$h1_height = get_theme_mod( 'dce_heading_h1_line_height', $default_line_height );
		$h1_line_height = ( 1 != $h1_height ) ? ' line-height: ' . $h1_height . 'em;' : '';
		$h1_bottom = get_theme_mod( 'dce_heading_h1_bottom_padding', 10 );
		$h1_padding = ( 10 != $h1_bottom ) ? ' padding-bottom: ' . $h1_bottom . 'px;' : '';
		if ( $h1_media_query_open ) {
			$dce_output .= $h1_media_query_open . "\n";
		}
		$dce_output .= ' h1 { font-size: ' . $h1_size . 'px;' . $h1_line_height . $h1_padding . '}' . "\n";
		if ( get_theme_mod( 'dce_heading_h1_slider_title', 1 ) ) {
			$slide_title_size = $h1_size * 1.53;
			$dce_output .= ' .et_pb_slide_description .et_pb_slide_title { font-size: ' . $slide_title_size . 'px;}' . "\n";
		}
		if ( $h1_media_query_close ) {
			$dce_output .= $h1_media_query_close . "\n";
		}
	}

	if ( $h2_customize ) {
		$h2_apply_default =  array();
		$h2_apply_array = get_theme_mod( 'dce_heading_h2_apply', $h2_apply_default );
		$h2_apply = ' h2';
		$h2_dont_apply = '';
		if ( in_array( 'related_product', $h2_apply_array ) ) {
				$h2_apply .= ', .product .related h2';
			} else {
				$comma = ( '' != $h2_dont_apply ) ? ', ' : '';
				$h2_dont_apply .= $comma . '.product .related h2';
		}
		if ( in_array( 'large_blockquote', $h2_apply_array ) ) {
				$h2_apply .= ', .et_pb_column_1_2 .et_quote_content blockquote p';
			} else {
				$comma = ( '' != $h2_dont_apply ) ? ', ' : '';
				$h2_dont_apply .= $comma . '.et_pb_column_1_2 .et_quote_content blockquote p';
		}
		$h2_disable = get_theme_mod( 'dce_heading_h2_disable', 'none' );
		if ( 'tablet' == $h2_disable ) {
				$h2_media_query_open = '@media all and (min-width: 981px) {';
				$h2_media_query_close = '}';
			} elseif ( 'phone' == $h2_disable  ) {
				$h2_media_query_open = '@media all and (min-width: 768px) {';
				$h2_media_query_close = '}';
			} else {
				$h2_media_query_open = false;
				$h2_media_query_close = false;
		}
		$h2_size = get_theme_mod( 'dce_heading_h2_font_size', $default_h2_size );
		$h2_height = get_theme_mod( 'dce_heading_h2_line_height', $default_line_height );
		$h2_line_height = ( 1 != $h2_height ) ? ' line-height: ' . $h2_height . 'em;' : '';
		$h2_bottom = get_theme_mod( 'dce_heading_h2_bottom_padding', 10 );
		$h2_padding = ( 10 != $h2_bottom ) ? ' padding-bottom: ' . $h2_bottom . 'px;' : '';
		if ( $h2_media_query_open ) {
			$dce_output .= $h2_media_query_open . "\n";
		}
		$dce_output .= $h2_apply . ' {font-size: ' . $h2_size . 'px;' . $h2_line_height . $h2_padding . '}' . "\n";
		$dce_output .= $h2_dont_apply . ' {padding-bottom: 10px;}' . "\n";
		if ( $h2_media_query_close ) {
			$dce_output .= $h2_media_query_close . "\n";
		}
	}

	if ( $h3_customize ) {
		$h3_disable = get_theme_mod( 'dce_heading_h3_disable', 'none' );
		if ( 'tablet' == $h3_disable ) {
				$h3_media_query_open = '@media all and (min-width: 981px) {';
				$h3_media_query_close = '}';
			} elseif ( 'phone' == $h3_disable  ) {
				$h3_media_query_open = '@media all and (min-width: 768px) {';
				$h3_media_query_close = '}';
			} else {
				$h3_media_query_open = false;
				$h3_media_query_close = false;
		}
		$h3_size = get_theme_mod( 'dce_heading_h3_font_size', $default_h3_size );
		$h3_height = get_theme_mod( 'dce_heading_h3_line_height', $default_line_height );
		$h3_line_height = ( 1 != $h3_height ) ? ' line-height: ' . $h3_height . 'em;' : '';
		$h3_bottom = get_theme_mod( 'dce_heading_h3_bottom_padding', 10 );
		$h3_padding = ( 10 != $h3_bottom ) ? ' padding-bottom: ' . $h3_bottom . 'px;' : '';
		if ( $h3_media_query_open ) {
			$dce_output .= $h3_media_query_open . "\n";
		}
		$dce_output .= ' h3 { font-size: ' . $h3_size . 'px;' . $h3_line_height . $h3_padding . '}' . "\n";
		if ( $h3_media_query_close ) {
			$dce_output .= $h3_media_query_close . "\n";
		}
	}

	if ( $h4_customize ) {
		$h4_apply_default =  array(
			'footer_widget',
			'blog_grid',
			// 'narrow_blockquote',
			// 'grid_blockquote',
			// 'narrow_link',
			// 'grid_link',
			// 'narrow_audio',
			// 'grid_audio',
			// 'audio_module',
			'portfolio_grid',
			'gallery_grid',
			// 'circle_counter',
			// 'number_counter',
		);
		$h4_apply_array = get_theme_mod( 'dce_heading_h4_apply', $h4_apply_default );
		if ( in_array( 'footer_widget', $h4_apply_array ) ) {
				$h4_apply = ' h4, #main-content h4';
				$h4_dont_apply = '';
			} else {
				$h4_apply = ' h4:not(#footer-widget h4), #main-content h4';
				$h4_dont_apply .= '.footer-widget h4';
		}
		if ( in_array( 'blog_grid', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_blog_grid h2, .et_pb_column_1_3 .et_pb_post h2, .et_pb_column_1_4 .et_pb_post h2';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_blog_grid h2, .et_pb_column_1_3 .et_pb_post h2, .et_pb_column_1_4 .et_pb_post h2';
		}
		if ( in_array( 'narrow_blockquote', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_column_1_3 .et_quote_content blockquote p, .et_pb_column_3_8 .et_quote_content blockquote p, .et_pb_column_1_4 .et_quote_content blockquote p';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_column_1_3 .et_quote_content blockquote p, .et_pb_column_3_8 .et_quote_content blockquote p, .et_pb_column_1_4 .et_quote_content blockquote p';
		}
		if ( in_array( 'grid_blockquote', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_blog_grid .et_quote_content blockquote p';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_blog_grid .et_quote_content blockquote p';
		}
		if ( in_array( 'narrow_link', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_column_1_3 .et_link_content h2, .et_pb_column_3_8 .et_link_content h2, .et_pb_column_1_4 .et_link_content h2';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_column_1_3 .et_link_content h2, .et_pb_column_3_8 .et_link_content h2, .et_pb_column_1_4 .et_link_content h2';
		}
		if ( in_array( 'grid_link', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_blog_grid .et_link_content h2';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_blog_grid .et_link_content h2';
		}
		if ( in_array( 'narrow_audio', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_column_1_3 .et_audio_content h2, .et_pb_column_3_8 .et_audio_content h2, .et_pb_column_1_4 .et_audio_content h2';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_column_1_3 .et_audio_content h2, .et_pb_column_3_8 .et_audio_content h2, .et_pb_column_1_4 .et_audio_content h2';
		}
		if ( in_array( 'grid_audio', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_blog_grid .et_audio_content h2';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_blog_grid .et_audio_content h2';
		}
		if ( in_array( 'audio_module', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_column_3_8 .et_pb_audio_module_content h2, .et_pb_column_1_3 .et_pb_audio_module_content h2';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_column_3_8 .et_pb_audio_module_content h2, .et_pb_column_1_3 .et_pb_audio_module_content h2';
		}
		if ( in_array( 'gallery_grid', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_gallery_grid .et_pb_gallery_item h3';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_gallery_grid .et_pb_gallery_item h3';
		}
		if ( in_array( 'portfolio_grid', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_portfolio_grid .et_pb_portfolio_item h2, .et_pb_filterable_portfolio_grid .et_pb_portfolio_item h2';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_portfolio_grid .et_pb_portfolio_item h2, .et_pb_filterable_portfolio_grid .et_pb_portfolio_item h2';
		}
		if ( in_array( 'circle_counter', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_circle_counter h3';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_circle_counter h3';
		}
		if ( in_array( 'number_counter', $h4_apply_array ) ) {
				$h4_apply .= ', .et_pb_number_counter h3';
			} else {
				$comma = ( '' != $h4_dont_apply ) ? ', ' : '';
				$h4_dont_apply .= $comma . '.et_pb_number_counter h3';
		}
		$h4_disable = get_theme_mod( 'dce_heading_h4_disable', 'none' );
		if ( 'tablet' == $h4_disable ) {
				$h4_media_query_open = '@media all and (min-width: 981px) {';
				$h4_media_query_close = '}';
			} elseif ( 'phone' == $h4_disable  ) {
				$h4_media_query_open = '@media all and (min-width: 768px) {';
				$h4_media_query_close = '}';
			} else {
				$h4_media_query_open = false;
				$h4_media_query_close = false;
		}
		$h4_size = get_theme_mod( 'dce_heading_h4_font_size', $default_h4_size );
		$h4_height = get_theme_mod( 'dce_heading_h4_line_height', $default_line_height );
		$h4_line_height = ( 1 != $h4_height ) ? ' line-height: ' . $h4_height . 'em;' : '';
		$h4_bottom = get_theme_mod( 'dce_heading_h4_bottom_padding', 10 );
		$h4_padding = ( 10 != $h4_bottom ) ? ' padding-bottom: ' . $h4_bottom . 'px;' : '';
		if ( $h4_media_query_open ) {
			$dce_output .= $h4_media_query_open . "\n";
		}
		$dce_output .= $h4_apply . ' {font-size: ' . $h4_size . 'px;' . $h4_line_height . $h4_padding . '}' . "\n";
		$dce_output .= $h4_dont_apply . ' {padding-bottom: 10px;}' . "\n";
		if ( $h4_media_query_close ) {
			$dce_output .= $h4_media_query_close . "\n";
		}
	}

	if ( $h5_customize ) {
		$h5_apply_default =  array();
		$h5_apply_array = get_theme_mod( 'dce_heading_h5_apply', $h5_apply_default );
		$h5_apply = ' h5';
		$h5_dont_apply = '';
		if ( in_array( 'woocommerce', $h5_apply_array ) ) {
				$h5_apply .= ', .woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3';
			} else {
				$comma = ( '' != $h5_dont_apply ) ? ', ' : '';
				$h5_dont_apply .= $comma . '.woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3';
		}
		if ( in_array( 'audio_module', $h5_apply_array ) ) {
				$h5_apply .= ', .et_pb_column_1_4 .et_pb_audio_module_content h2';
			} else {
				$comma = ( '' != $h5_dont_apply ) ? ', ' : '';
				$h5_dont_apply .= $comma . '.et_pb_column_1_4 .et_pb_audio_module_content h2';
		}
		if ( in_array( 'portfolio_grid', $h5_apply_array ) ) {
				$h5_apply .= ', .et_pb_portfolio_grid .et_pb_portfolio_item h2, .et_pb_filterable_portfolio_grid .et_pb_portfolio_item h2';
			} else {
				$comma = ( '' != $h5_dont_apply ) ? ', ' : '';
				$h5_dont_apply .= $comma . '.et_pb_portfolio_grid .et_pb_portfolio_item h2, .et_pb_filterable_portfolio_grid .et_pb_portfolio_item h2';
		}
		if ( in_array( 'gallery_grid', $h5_apply_array ) ) {
				$h5_apply .= ', .et_pb_gallery_grid .et_pb_gallery_item h3';
			} else {
				$comma = ( '' != $h5_dont_apply ) ? ', ' : '';
				$h5_dont_apply .= $comma . '.et_pb_column_1_4 .et_pb_gallery_grid .et_pb_gallery_item h3';
		}
		$h5_disable = get_theme_mod( 'dce_heading_h5_disable', 'none' );
		if ( 'tablet' == $h5_disable ) {
				$h5_media_query_open = '@media all and (min-width: 981px) {';
				$h5_media_query_close = '}';
			} elseif ( 'phone' == $h5_disable  ) {
				$h5_media_query_open = '@media all and (min-width: 768px) {';
				$h5_media_query_close = '}';
			} else {
				$h5_media_query_open = false;
				$h5_media_query_close = false;
		}
		$h5_size = get_theme_mod( 'dce_heading_h5_font_size', $default_h5_size );
		$h5_height = get_theme_mod( 'dce_heading_h5_line_height', $default_line_height );
		$h5_line_height = ( 1 != $h5_height ) ? ' line-height: ' . $h5_height . 'em;' : '';
		$h5_bottom = get_theme_mod( 'dce_heading_h5_bottom_padding', 10 );
		$h5_padding = ( 10 != $h5_bottom ) ? ' padding-bottom: ' . $h5_bottom . 'px;' : '';
		if ( $h5_media_query_open ) {
			$dce_output .= $h5_media_query_open . "\n";
		}
		$dce_output .= $h5_apply . ' {font-size: ' . $h5_size . 'px;' . $h5_line_height . $h5_padding . '}' . "\n";
		$dce_output .= $h5_dont_apply . ' {padding-bottom: 10px;}' . "\n";
		if ( $h5_media_query_close ) {
			$dce_output .= $h5_media_query_close . "\n";
		}
	}

	if ( $h6_customize ) {
		$h6_disable = get_theme_mod( 'dce_heading_h6_disable', 'none' );
		if ( 'tablet' == $h6_disable ) {
				$h6_media_query_open = '@media all and (min-width: 981px) {';
				$h6_media_query_close = '}';
			} elseif ( 'phone' == $h6_disable  ) {
				$h6_media_query_open = '@media all and (min-width: 768px) {';
				$h6_media_query_close = '}';
			} else {
				$h6_media_query_open = false;
				$h6_media_query_close = false;
		}
		$h6_size = get_theme_mod( 'dce_heading_h6_font_size', $default_h6_size );
		$h6_height = get_theme_mod( 'dce_heading_h6_line_height', $default_line_height );
		$h6_line_height = ( 1 != $h6_height ) ? ' line-height: ' . $h6_height . 'em;' : '';
		$h6_bottom = get_theme_mod( 'dce_heading_h6_bottom_padding', 10 );
		$h6_padding = ( 10 != $h6_bottom ) ? ' padding-bottom: ' . $h6_bottom . 'px;' : '';
		if ( $h6_media_query_open ) {
			$dce_output .= $h6_media_query_open . "\n";
		}
		$dce_output .= ' h6 { font-size: ' . $h6_size . 'px;' . $h6_line_height . $h6_padding . '}' . "\n";
		if ( $h6_media_query_close ) {
			$dce_output .= $h6_media_query_close . "\n";
		}
	}

	return $dce_output;

}
