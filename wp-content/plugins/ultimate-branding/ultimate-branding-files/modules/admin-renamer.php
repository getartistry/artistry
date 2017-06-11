<?php

/*
  Plugin Name: Zach Admin Renamer
  Plugin URI:
  Description: Quick rename for admin strings
  Author: Marko Miljus (Incsub)
  Version: 0.0.1 b
 */

add_filter( 'gettext', 'ub_rename_admin_terms' );
add_filter( 'ngettext', 'ub_rename_admin_terms' );

function ub_rename_admin_menu_items( $terms ) {

	$terms = str_ireplace( 'WordPress', 'APNetwork', $terms );

	return $terms;
}
