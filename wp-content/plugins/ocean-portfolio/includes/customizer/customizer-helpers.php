<?php
/**
 * Active callback functions for the customizer
 */

function op_portfolio_cac_has_title() {
	if ( 'on' == get_theme_mod( 'op_portfolio_title', 'on' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_pagination() {
	if ( 'on' == get_theme_mod( 'op_portfolio_pagination', 'off' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_filter() {
	if ( 'on' == get_theme_mod( 'op_portfolio_filter', 'off' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_custom_responsive_filter_links() {
	if ( 'on' == get_theme_mod( 'op_portfolio_filter', 'off' )
		&& 'custom' == get_theme_mod( 'op_portfolio_responsive_filter_links', '480' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_image_custom_size() {
	if ( 'custom' == get_theme_mod( 'op_portfolio_size', 'medium' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_image_overlay_icons() {
	if ( 'on' == get_theme_mod( 'op_portfolio_img_overlay_icons', 'on' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_outside_title_cat_position() {
	if ( 'outside' == get_theme_mod( 'op_portfolio_title_cat_position', 'outside' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_category() {
	if ( 'on' == get_theme_mod( 'op_portfolio_category', 'on' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_single_bs_layout() {
	if ( 'both-sidebars' == get_theme_mod( 'op_portfolio_single_layout', 'full-width' ) ) {
		return true;
	} else {
		return false;
	}
}

function op_portfolio_cac_has_single_title_bg_image() {
	if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false ) ) {
		return true;
	} else {
		return false;
	}
}