<?php
/**
 * Active callback functions for the customizer
 */

function osh_cac_has_shrink_style() {
	if ( 'shrink' == get_theme_mod( 'osh_sticky_header_style', 'shrink' ) ) {
		return true;
	} else {
		return false;
	}
}