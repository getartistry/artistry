<?php

namespace MyListing\Queries;

class Query {

	public function __construct() {
		add_action( sprintf( 'wp_ajax_%s', $this->action ), [ $this, 'handle' ] );
		add_action( sprintf( 'wp_ajax_nopriv_%s', $this->action ), [ $this, 'handle' ] );
	}

	public function send( $args = [] ) {
		$this->output( $this->query( $args ), ! empty( $args['output'] ) ? $args['output'] : [] );
	}

	public function query( $args = [] ) {
		global $wpdb, $job_manager_keyword;

		$args = wp_parse_args( $args, array(
			'search_location'   => '',
			'search_keywords'   => '',
			'search_categories' => array(),
			'job_types'         => array(),
			'offset'            => 0,
			'posts_per_page'    => 20,
			'orderby'           => 'date',
			'order'             => 'DESC',
			'featured'          => null,
			'filled'            => null,
			'fields'            => 'all',
			'post__in'          => [],
			'post__not_in'      => [],
			'meta_query'        => [],
			'tax_query'         => [],
			'author'            => null,
			'ignore_sticky_posts' => true,
			) );

		// dd($args);

		do_action( 'get_job_listings_init', $args );

		$query_args = array(
			'post_type'              => 'job_listing',
			'post_status'            => 'publish',
			'ignore_sticky_posts'    => $args['ignore_sticky_posts'],
			'offset'                 => absint( $args['offset'] ),
			'posts_per_page'         => intval( $args['posts_per_page'] ),
			'orderby'                => $args['orderby'],
			'order'                  => $args['order'],
			'tax_query'              => $args['tax_query'],
			'meta_query'             => $args['meta_query'],
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'cache_results'          => false,
			'fields'                 => $args['fields'],
			'author'                 => $args['author'],
		);

		// WPML workaround
		if ( ( strstr( $_SERVER['REQUEST_URI'], '/jm-ajax/' ) || ! empty( $_GET['jm-ajax'] ) ) && isset( $_POST['lang'] ) ) {
			do_action( 'wpml_switch_language', sanitize_text_field( $_POST['lang'] ) );
		}

		if ( $args['posts_per_page'] < 0 ) {
			$query_args['no_found_rows'] = true;
		}

		if ( ! empty( $args['search_location'] ) ) {
			$location_meta_keys = ['geolocation_formatted_address', '_job_location', 'geolocation_state_long'];
			$location_search    = ['relation' => 'OR'];
			foreach ( $location_meta_keys as $meta_key ) {
				$location_search[] = [
					'key'     => $meta_key,
					'value'   => $args['search_location'],
					'compare' => 'like'
				];
			}
			$query_args['meta_query'][] = $location_search;
		}

		if ( ! is_null( $args['featured'] ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_featured',
				'value'   => '1',
				'compare' => $args['featured'] ? '=' : '!='
			);
		}

		if ( ! empty( $args['job_types'] ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'job_listing_type',
				'field'    => 'slug',
				'terms'    => $args['job_types']
			);
		}

		if (!empty($args['post__in'])) {
			$query_args['post__in'] = $args['post__in'];
		}

		if (!empty($args['post__not_in'])) {
			$query_args['post__not_in'] = $args['post__not_in'];
		}

		if ( 'featured' === $args['orderby'] ) {
			$query_args['orderby'] = array(
				'menu_order' => 'ASC',
				'date'       => 'DESC'
			);
		}

		$job_manager_keyword = sanitize_text_field( $args['search_keywords'] );

		if ( ! empty( $job_manager_keyword ) && strlen( $job_manager_keyword ) >= apply_filters( 'job_manager_get_listings_keyword_length_threshold', 2 ) ) {
			$query_args['s'] = $job_manager_keyword;
			add_filter( 'posts_search', 'get_job_listings_keyword_search' );
		}

		$query_args = apply_filters( 'job_manager_get_listings', $query_args, $args );

		if ( empty( $query_args['meta_query'] ) ) {
			unset( $query_args['meta_query'] );
		}

		if ( empty( $query_args['tax_query'] ) ) {
			unset( $query_args['tax_query'] );
		}

		if ( ! $query_args['author'] ) {
			unset( $query_args['author'] );
		}

		/** This filter is documented in wp-job-manager.php */
		$query_args['lang'] = apply_filters( 'wpjm_lang', null );

		// Filter args
		$query_args = apply_filters( 'get_job_listings_query_args', $query_args, $args );

		// Generate hash
		$to_hash         = json_encode( $query_args ) . apply_filters( 'wpml_current_language', '' );
		$query_args_hash = 'jm_' . md5( $to_hash ) . \WP_Job_Manager_Cache_Helper::get_transient_version( 'get_job_listings' );

		do_action( 'before_get_job_listings', $query_args, $args );

		// Cache results
		if ( apply_filters( 'get_job_listings_cache_results', true ) && (!isset($args['__ignore_cache']) || !$args['__ignore_cache']) ) {

			if ( false === ( $result = get_transient( $query_args_hash ) ) ) {
				$result = new \WP_Query( $query_args );
				set_transient( $query_args_hash, $result, DAY_IN_SECONDS * 30 );
			}

			// random order is cached so shuffle them
			if ( $query_args[ 'orderby' ] == 'rand' ) {
				shuffle( $result->posts );
			}

		}
		else {
			$result = new \WP_Query( $query_args );
		}

		do_action( 'after_get_job_listings', $query_args, $args );

		remove_filter( 'posts_search', 'get_job_listings_keyword_search' );

		return $result;
	}

	public function output( $query, $args = [] ) {
		ob_start();

		$result = [];
		$result['data'] = [];
		$result['found_jobs'] = false;
		// $result['args'] = $args;
		// $result['page'] = $page;
		// $result['sql'] = $query->request;

		if ( $query->have_posts() ) {
			$result['found_jobs'] = true;

			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;
				$post->c27_options__wrap_in = isset( $args['item-wrapper'] ) ? $args['item-wrapper'] : 'col-md-4 col-sm-6 col-xs-12 reveal';

				get_job_manager_template_part( 'content', 'job_listing' );
				$result['data'][] = $post->_c27_marker_data;
			}
		} else {
			get_job_manager_template_part( 'content', 'no-jobs-found' );
		}

		$result['html']          = ob_get_clean();
		$result['pagination']    = get_job_listing_pagination( $query->max_num_pages, ( absint( isset( $_POST['page'] ) ? $_POST['page'] : 0 ) + 1 ) );
		$result['max_num_pages'] = $query->max_num_pages;
		$result['found_posts']   = $query->found_posts;


		if ( $query->found_posts < 1 ) {
			$result['showing'] = __( 'No results', 'my-listing' );
		} elseif ( $query->found_posts == 1 ) {
			$result['showing'] = __( 'One result', 'my-listing' );
		} else {
			$result['showing'] = sprintf( __( '%d results', 'my-listing' ), $query->found_posts);
		}

		wp_send_json( $result );
	}
}