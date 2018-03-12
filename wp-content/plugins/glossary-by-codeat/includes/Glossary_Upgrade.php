<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */

/**
 * The Upgrade system
 */
class Glossary_Upgrade {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		register_activation_hook( GT_TEXTDOMAIN . '/' . GT_TEXTDOMAIN . '.php', array( __CLASS__, 'activate' ) );
		add_action( 'admin_init', array( $this, 'activate' ) );
	}

	/**
	 * On activation
	 *
	 * @return void
	 */
	public static function activate() {
		if ( is_admin() ) {
			$version = get_option( 'glossary-version' );
			if ( version_compare( GT_VERSION, $version, '>' ) ) {
				include_once 'Requirements/requirements.php';
				new Plugin_Requirements(
						GT_NAME, GT_TEXTDOMAIN, array(
					'WP'        => new WordPress_Requirement( '4.7.0' ),
					'Extension' => new PHP_Extension_Requirement( array( 'mbstring' ) ),
						)
				);
				Glossary_Upgrade::add_admin_cap();
				update_option( 'glossary-version', GT_VERSION );
				// Was wrong in previous release with a missing of an _
				delete_option( GT_SETTINGS . 'count_terms' );
				delete_option( GT_SETTINGS . 'count_related_terms' );
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
			}

			flush_rewrite_rules();
		}
	}

	/**
	 * Add admin capabilities
	 *
	 * @return void
	 */
	public static function add_admin_cap() {
		$caps  = array(
			'create_glossaries',
			'read_glossary',
			'read_private_glossaries',
			'edit_glossary',
			'edit_glossaries',
			'edit_private_glossaries',
			'edit_published_glossaries',
			'edit_others_glossaries',
			'publish_glossaries',
			'delete_glossary',
			'delete_glossaries',
			'delete_private_glossaries',
			'delete_published_glossaries',
			'delete_others_glossaries',
			'manage_glossaries',
		);
		$roles = array(
			get_role( 'administrator' ),
			get_role( 'editor' ),
			get_role( 'author' ),
			get_role( 'contributor' ),
			get_role( 'subscriber' ),
		);
		foreach ( $roles as $role ) {
			if ( !is_null( $role ) ) {
				foreach ( $caps as $cap ) {
					$role->add_cap( $cap );
				}
			}
		}

		$bad_caps = array(
			'create_glossaries',
			'read_private_glossaries',
			'edit_glossary',
			'edit_glossaries',
			'edit_private_glossaries',
			'edit_published_glossaries',
			'edit_others_glossaries',
			'publish_glossaries',
			'delete_glossary',
			'delete_glossaries',
			'delete_private_glossaries',
			'delete_published_glossaries',
			'delete_others_glossaries',
			'manage_glossaries',
		);
		$roles    = array(
			get_role( 'author' ),
			get_role( 'contributor' ),
			get_role( 'subscriber' ),
		);
		foreach ( $roles as $role ) {
			if ( !is_null( $role ) ) {
				foreach ( $bad_caps as $cap ) {
					$role->remove_cap( $cap );
				}
			}
		}
	}

}

new Glossary_Upgrade();

