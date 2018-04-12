<?php

namespace MyListing;

use \CASE27\Classes\Term;
use \CASE27\Integrations\ListingTypes\ListingType;

class Explore {
	public
		$active_tab = false,
		$active_category = false,
		$active_region = false,
		$active_tag = false,
		$active_listing_type = false,
		$data = [];

	public $store = [];

	public static function init() {
		add_action( 'init', [ '\MyListing\Explore', 'add_rewrite_rules' ] );
	}

	public function __construct( $data ) {
		$this->data = $data;
		$this->parse_active_tab();
		$this->parse_listing_types();
		$this->parse_categories();
	}

	public function get_data( $key = null ) {
		if ( $key && isset( $this->data[ $key ] ) ) {
			return $this->data[ $key ];
		}

		return $this->data;
	}

	public function get_active_tab() {
		return $this->active_tab;
	}

	public function get_active_listing_type() {
		return $this->active_listing_type;
	}

	public function get_active_category() {
		return $this->active_category;
	}

	public function parse_listing_types() {
		$this->store['listing_types'] = array_filter( array_map( function( $listing_type ) {
			if ( ! ( $listing_type_obj = ( get_page_by_path( $listing_type, OBJECT, 'case27_listing_type' ) ) ) ) {
				return false;
			}

			return new ListingType( $listing_type_obj );
		}, array_column( (array) $this->data['listing_types'], 'type') ) );

		$this->parse_active_listing_type();

		// If the active tab is 'listing-types', but there's only one listing type,
		// show the filters tab instead.
		if ( $this->active_tab == 'listing-types' && count( $this->store['listing_types'] ) === 1 ) {
			$this->active_tab = 'search-form';
		}
	}

	public function parse_active_listing_type() {
		if ( empty( $this->store[ 'listing_types' ] ) ) {
			$this->active_listing_type = false;
			return false;
		}

		$this->active_listing_type = $this->store['listing_types'][0];

		if ( isset( $_GET['type'] ) && ( $getType = sanitize_text_field( $_GET['type'] ) ) ) {
			foreach ($this->store['listing_types'] as $listing_type) {
				if ( $listing_type->get_slug() == $getType ) {
					$this->active_listing_type = $listing_type;
					break;
				}
			}
		}
	}

	public function parse_active_tab() {
		$possible_tabs = [ 'listing-types', 'search-form', 'categories', 'regions', 'tags' ];

		// First check if the tab is available as a query var. This has the highest priority.
		if ( in_array( get_query_var( 'explore_tab' ), $possible_tabs ) ) {
			$this->active_tab = get_query_var( 'explore_tab' );
		}
		// Then check if the tab is provided as a GET param.
		// This is needed to maintain backwards compatibility.
		elseif ( ! empty( $_GET['tab'] ) && in_array( $_GET['tab'], $possible_tabs ) ) {
			$this->active_tab = sanitize_text_field( $_GET['tab'] );
		}
		// See if the active tab is provided through Elementor widget settings.
		elseif ( in_array( $this->data['active_tab'], $possible_tabs ) ) {
			$this->active_tab = $this->data['active_tab'];
		}
		// Otherwise, default to the 'listing-types' tab.
		else {
			$this->active_tab = 'listing-types';
		}
	}

	public function parse_categories() {
		$this->store['category-items'] = [];
		foreach ($this->store['listing_types'] as $type) {
			$this->store['category-items'][ $type->get_slug() ] = [];

			$args = [
				'number'     => $this->data['categories']['count'],
				'order'      => $this->data['categories']['order'],
				'orderby'    => $this->data['categories']['order_by'],
				'taxonomy'   => 'job_listing_category',
				'hide_empty' => $this->data['categories']['hide_empty'],
				'pad_counts' => false,
				'meta_query' => [
					'relation' => 'OR',
					[
						'key' => 'listing_type',
						'value' => '"' . $type->get_id() . '"',
						'compare' => 'LIKE',
					],
					[
						'key' => 'listing_type',
						'value' => '',
					],
					[
						'key' => 'listing_type',
						'compare' => 'NOT EXISTS',
					]
				],
			];

            $cache_version = get_option( 'listings_tax_' . 'job_listing_category' . '_version', 100 );
            // dump($cache_version);
            $categories_hash = 'c27_cats_' . md5( json_encode( $args ) ) . '_v' . $cache_version;
            $terms = get_transient( $categories_hash );

            if ( empty( $terms ) ) {
                $terms = get_terms( $args );
                set_transient( $categories_hash, $terms, HOUR_IN_SECONDS * 6 );
                // dump( 'Loaded via db query' );
            } else {
                // dump( 'Loaded from cache' );
            }


			if ( is_wp_error( $terms ) ) {
				continue;
			}

			foreach ($terms as $key => $term) {
				if ( is_wp_error( $term ) ) {
					unset( $terms[ $key ] );
					continue;
				}

				$terms[ $key ]->listing_type = array_filter( array_map( function( $type_id ) {
					if ( is_numeric( $type_id ) && $slug = get_post_field( 'post_name', absint( $type_id ) ) ) {
						return $slug;
					}

					return false;
				}, (array) get_term_meta( $term->term_id, 'listing_type', true ) ) );
			}
			// endcache

			foreach ( $terms as $term ) {
				// $this->store['category-items'][ 'term_' . $term->term_id ] = new Term( $term );
				$this->store['category-items'][ $type->get_slug() ][ 'term_' . $term->term_id ] = new Term( $term );
			}
		}

		$this->parse_active_category();
	}

	public function parse_active_category() {
		// Determine active category.
		if ( get_query_var( 'explore_category' ) && ( $term = get_term_by( 'slug', get_query_var( 'explore_category' ), 'job_listing_category' ) ) ) {
			$this->active_category = new Term( $term );
			$this->active_category->active = true;
		} elseif ( ! empty( $_GET['cid'] ) && ( $term = get_term_by( 'id', absint( $_GET['cid'] ), 'job_listing_category' ) ) ) {
			$this->active_category = new Term( $term );
			$this->active_category->active = true;
		} elseif ( ! empty( $this->store['category-items'] ) && $this->active_listing_type && ! empty( $this->store['category-items'][ $this->active_listing_type->get_slug() ] ) ) {
			foreach ($this->store['category-items'][ $this->active_listing_type->get_slug() ] as $term) {
				$this->active_category = $term;
				break;
			}
		} else {
			$this->active_category = false;
		}

		// Insert the active category as the first item in the "Categories" tab.
		if ( $this->active_category && $this->active_category->is_active() ) {
			foreach ($this->store['category-items'] as $group_key => $term_group) {
				if ( isset( $term_group[ 'term_' . $this->active_category->get_id() ] ) ) {
					unset( $term_group[ 'term_' . $this->active_category->get_id() ] );
				}

				$this->store['category-items'][ $group_key ] = [
						'term_' . $this->active_category->get_id() => $this->active_category,
					] + $this->store['category-items'][ $group_key ];
			}
		}

		$this->parse_active_taxonomies();
	}

	public function parse_active_taxonomies() {
		if ( get_query_var( 'explore_region' ) && ( $term = get_term_by( 'slug', sanitize_title( get_query_var( 'explore_region' ) ), 'region' ) ) ) {
			$this->active_region = new Term( $term );
		}

		if ( get_query_var( 'explore_tag' ) && ( $term = get_term_by( 'slug', sanitize_title( get_query_var( 'explore_tag' ) ), 'case27_job_listing_tags' ) ) ) {
			$this->active_tag = new Term( $term );
		}
	}

	public function get_active_taxonomy() {
		if ( in_array( $this->active_tab, ['categories', 'regions', 'tags'] ) ) {
			return $this->active_tab;
		}

		return false;
	}

	/*
	 * Add rewrite rules for pretty url-s in Explore page.
	 * e.g. site/explore/category/category-name
	 * 		site/explore/regions/region-name
	 * 		site/explore/tags/tag-name
	 */
	public static function add_rewrite_rules() {
		// Stack overflow link: https://wordpress.stackexchange.com/questions/89164/passing-parameters-to-a-custom-page-template-using-clean-urls
		if ( ! ( $explore_page_id = c27()->get_setting( 'general_explore_listings_page', false ) ) ) {
			return false;
		}

		if ( ! ( $explore_page = get_post( url_to_postid( $explore_page_id ) ) ) ) {
			return false;
		}

		// Add query vars.
		global $wp;
	    $wp->add_query_var( 'explore_tab' );

	    // Add rewrite tags.
    	add_rewrite_tag( '%explore_category%', '([^/]+)' );
    	add_rewrite_tag( '%explore_region%', '([^/]+)' );
    	add_rewrite_tag( '%explore_tag%', '([^/]+)' );

    	// dd(get_option('job_category_base'));
    	$bases = [
    		'category' => apply_filters( 'case27\taxonomy\category\base', 'category' ),
    		'region' => apply_filters( 'case27\taxonomy\region\base', 'region' ),
    		'tag' => apply_filters( 'case27\taxonomy\tag\base', 'tag' ),
    	];

		// Add rewrite rules.
	    add_rewrite_rule(
	    	sprintf( '^%s/([^/]+)?', $bases['category'] ),
	    	sprintf( 'index.php?page_id=%d&explore_tab=categories&explore_category=$matches[1]', $explore_page->ID ),
	    	'top'
	    );

	    add_rewrite_rule(
	    	sprintf( '^%s/([^/]+)?', $bases['region'] ),
	    	sprintf( 'index.php?page_id=%d&explore_tab=regions&explore_region=$matches[1]', $explore_page->ID ),
	    	'top'
	    );

	   	add_rewrite_rule(
	    	sprintf( '^%s/([^/]+)?', $bases['tag'] ),
	    	sprintf( 'index.php?page_id=%d&explore_tab=tags&explore_tag=$matches[1]', $explore_page->ID ),
	    	'top'
	    );
	}
}

Explore::init();
