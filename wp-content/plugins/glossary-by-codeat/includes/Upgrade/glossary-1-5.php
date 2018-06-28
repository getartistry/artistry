<?php

// Typo on ids of settings
$settings = gl_get_settings();

if ( isset( $settings[ 'first_occurence' ] ) ) {
	$settings[ 'first_occurrence' ] = $settings[ 'first_occurence' ];
	unset( $settings[ 'first_occurence' ] );
}

if ( isset( $settings[ 'first_all_occurence' ] ) ) {
	$settings[ 'first_all_occurrence' ] = $settings[ 'first_all_occurence' ];
	unset( $settings[ 'first_all_occurence' ] );
}

update_option( GT_SETTINGS . '-settings', $settings );
$settings = get_option( GT_SETTINGS . '-customizer' );
// Upgrade ids to new consistency definition
if ( isset( $settings[ 'lemma_color' ] ) ) {
	$settings[ 'keyterm_color' ] = $settings[ 'lemma_color' ];
	unset( $settings[ 'lemma_color' ] );
}

if ( isset( $settings[ 'lemma_background' ] ) ) {
	$settings[ 'keyterm_background' ] = $settings[ 'lemma_background' ];
	unset( $settings[ 'lemma_background' ] );
}

if ( isset( $settings[ 'lemma_size' ] ) ) {
	$settings[ 'keyterm_size' ] = $settings[ 'lemma_size' ];
	unset( $settings[ 'lemma_size' ] );
}

if ( isset( $settings[ 'link_lemma_color' ] ) ) {
	$settings[ 'link_keyterm_color' ] = $settings[ 'link_lemma_color' ];
	unset( $settings[ 'link_lemma_color' ] );
}

update_option( GT_SETTINGS . '-customizer', $settings );

$widget = get_option( 'widget_alphabet-taxonomies-for-glossary-terms' );
if ( !empty( $widget ) ) {
	update_option( 'widget_glossary-alphabetical-index', $widget );
	delete_option( 'widget_alphabet-taxonomies-for-glossary-terms' );
}

$widget = get_option( 'widget_latest-glossary-terms' );
if ( !empty( $widget ) ) {
	update_option( 'widget_glossary-latest-terms', $widget );
	delete_option( 'widget_latest-glossary-terms' );
}

$widget = get_option( 'widget_search-glossary-terms' );
if ( !empty( $widget ) ) {
	update_option( 'widget_glossary-search-terms', $widget );
	delete_option( 'widget_search-glossary-terms' );
}

$sidebars = get_option( 'sidebars_widgets' );
foreach ( $sidebars as $slug => $sidebar ) {
	if ( is_array( $sidebar ) ) {
		foreach ( $sidebar as $key => $widget ) {
			switch ( $widget ) {
				case 'widget_alphabet-taxonomies-for-glossary-terms':
					$sidebars[ $slug ][ $key ] = 'widget_glossary-alphabetical-index';
					break;
				case 'widget_latest-glossary-terms':
					$sidebars[ $slug ][ $key ] = 'widget_glossary-latest-terms';
					break;
				case 'widget_search-glossary-terms':
					$sidebars[ $slug ][ $key ] = 'widget_glossary-search-terms';
					break;
			}
		}
	}
}

update_option( 'sidebars_widgets', $sidebars );
