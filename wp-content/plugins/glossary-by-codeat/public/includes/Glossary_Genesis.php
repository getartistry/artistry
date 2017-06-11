<?php

/**
 * Glossary_Genesis
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2017 GPL
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */

/**
 * Support for the Genesis framework
 */
class Glossary_Genesis {

	/**
	 * Initialize the class with all the hooks
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'genesis_entry_content', array( $this, 'genesis_content' ), 9 );
	}

	/**
	 * Genesis hack to add the support for the archive content page
	 * Based on genesis_do_post_content
	 *
	 * @return void
	 */
	public function genesis_content() {
		$gt_search_engine = Glossary_Search_Engine::get_instance();
		$content = '';

		// Only display excerpt if not a teaser.
		if ( !in_array( 'teaser', get_post_class(), true ) ) {
			remove_filter( 'the_content', array( $gt_search_engine, 'check_auto_link' ) );
			remove_filter( 'the_excerpt', array( $gt_search_engine, 'check_auto_link' ) );
			if ( is_singular() ) {
				$content = get_the_content( get_the_ID() );
				if ( is_single() && 'open' === get_option( 'default_ping_status' ) && post_type_supports( get_post_type(), 'trackbacks' ) ) {
					echo '<!--';
					trackback_rdf();
					echo '-->' . "\n";
				}
				if ( is_page() && apply_filters( 'genesis_edit_post_link', true ) ) {
					edit_post_link( __( '(Edit)', 'genesis' ), '', '' );
				}
			} else if ( 'excerpts' === genesis_get_option( 'content_archive' ) ) {
				$content = get_the_excerpt( get_the_ID() );
			}
			if ( is_archive() ) {
				if ( genesis_get_option( 'content_archive_limit' ) ) {
					$content = get_the_content_limit( ( int ) genesis_get_option( 'content_archive_limit' ), genesis_a11y_more_link( __( '[Read more...]', 'genesis' ) ) );
				} else {
					$content .= genesis_a11y_more_link( __( '[Read more...]', 'genesis' ) );
				}
			}

			if ( is_search() ) {
				$content = get_the_excerpt( get_the_ID() );
			}
			if ( !has_shortcode( $content, 'glossary-list' ) ) {
				$content = wpautop( do_shortcode( $content ) );
			} else {
				$content = do_shortcode( $content );
			}
			
			echo $gt_search_engine->check_auto_link( $content );
			remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
		}
	}

}

new Glossary_Genesis();
