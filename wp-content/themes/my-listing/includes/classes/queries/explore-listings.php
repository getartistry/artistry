<?php

namespace MyListing\Queries;

class ExploreListingsQuery extends Query {

	public $action = 'get_listings';

	public function handle() {
		check_ajax_referer( 'c27_ajax_nonce', 'security' );

		global $wpdb;

		if ( empty( $_POST['form_data'] ) || ! is_array( $_POST['form_data'] ) || empty( $_POST['listing_type'] ) ) {
			return false;
		}

		if ( ! ( $listing_type_obj = ( get_page_by_path( $_POST['listing_type'], OBJECT, 'case27_listing_type' ) ) ) ) {
			return false;
		}

		$type = new \CASE27\Integrations\ListingTypes\ListingType( $listing_type_obj );
		$form_data = $_POST['form_data'];

		$page = absint( isset($form_data['page']) ? $form_data['page'] : 0 );
		$per_page = absint( isset($form_data['per_page']) ? $form_data['per_page'] : c27()->get_setting('general_explore_listings_per_page', 9));
		$orderby = sanitize_text_field( isset($form_data['orderby']) ? $form_data['orderby'] : 'date' );
		$promoted_args = false;
		$args = [
			'order' => sanitize_text_field( isset($form_data['order']) ? $form_data['order'] : 'DESC' ),
			'offset' => $page * $per_page,
			'orderby' => $orderby,
			'posts_per_page' => $per_page,
			'tax_query' => [],
			'meta_query' => [],
			'__ignore_cache' => false,
		];

		$this->get_ordering_clauses( $args, $type, $form_data );

		$tax_query_operator = 'all' === get_option( 'job_manager_category_filter_type', 'all' ) ? 'AND' : 'IN';

		// Make sure we're only querying listings of the requested listing type.
		if ( ! $type->is_global() ) {
			$args['meta_query'][] = [
				'key'     => '_case27_listing_type',
				'value'   =>  $type->get_slug(),
				'compare' => '='
			];
		}

		foreach ( (array) $type->get_search_filters() as $facet ) {
			// wp-search -> search_keywords
			// location -> search_location
			// text -> facet.show_field
			// proximity -> proximity
			// date -> show_field
			// range -> show_field
			// dropdown -> show_field
			// checkboxes -> show_field

			if ($facet['type'] == 'wp-search' && isset($form_data['search_keywords']) && $form_data['search_keywords']) {
				// dd($form_data['search_keywords']);
				$args['search_keywords'] = sanitize_text_field( stripslashes( $form_data['search_keywords'] ) );
			}

			if ($facet['type'] == 'location' && isset($form_data['search_location']) && $form_data['search_location']) {
				$args['search_location'] = sanitize_text_field( stripslashes( $form_data['search_location'] ) );
			}

			if ($facet['type'] == 'text' && isset($form_data[$facet['show_field']]) && $form_data[$facet['show_field']]) {
				$args['meta_query'][] = [
					'key'     => "_{$facet['show_field']}",
					'value'   => sanitize_text_field( stripslashes( $form_data[$facet['show_field']] ) ),
					'compare' => 'LIKE',
				];
			}

			if ($facet['type'] == 'proximity' && isset($form_data['proximity']) && isset($form_data['search_location_lat']) && isset($form_data['search_location_lng'])) {
				$proximity = absint( $form_data['proximity'] );
				$location = isset($form_data['search_location']) ? sanitize_text_field( stripslashes( $form_data['search_location'] ) ) : false;
				$lat = (float) $form_data['search_location_lat'];
				$lng = (float) $form_data['search_location_lng'];
				$units = isset($form_data['proximity_units']) && $form_data['proximity_units'] == 'mi' ? 'mi' : 'km';

				if ( $lat && $lng && $proximity && $location ) {
					// dump($lat, $lng, $proximity);

					$earth_radius = $units == 'mi' ? 3959 : 6371;

					$sql = $wpdb->prepare( $this->get_proximity_sql(), $earth_radius, $lat, $lng, $lat, $proximity );

					// dump($sql);

					$post_ids = (array) $wpdb->get_results( $sql, OBJECT_K );

					if (empty($post_ids)) $post_ids = ['none'];

					$args['post__in'] = array_keys( (array) $post_ids );

					// Remove search_location filter when using proximity filter.
					$args['search_location'] = '';
				}
			}

			if ($facet['type'] == 'date') {
				$date_type = 'exact';
				$format = 'ymd';

				foreach ($facet['options'] as $option) {
					if ($option['name'] == 'type') $date_type = $option['value'];
					if ($option['name'] == 'format') $format = $option['value'];
				}

				// Exact date search.
				if ($date_type == 'exact' && isset($form_data[$facet['show_field']]) && $form_data[$facet['show_field']]) {
					// Y-m-d format search.
					if ($format == 'ymd') {
						$date = date('Y-m-d', strtotime( $form_data[$facet['show_field']] ));
						$compare = '=';
					}

					// Year search. The year is converted to a date format, and the query instead runs a 'BETWEEN' comparison,
					// to include the requested year from January 01 to December 31.
					if ($format == 'year') {
						$date = [
							date('Y-01-01', strtotime($form_data[$facet['show_field']] . '-01-01' )),
							date('Y-12-31', strtotime($form_data[$facet['show_field']] . '-12-31')),
						];
						$compare = 'BETWEEN';
					}

					$args['meta_query'][] = [
						'key'     => "_{$facet['show_field']}",
						'value'   => $date,
						'compare' => $compare,
						'type' => 'DATE',
					];
				}

				// Range date search.
				if ($date_type == 'range') {
					$date_from = false;
					$date_to = false;
					$values = [];

					if (isset($form_data["{$facet['show_field']}_from"]) && $form_data["{$facet['show_field']}_from"]) {
						$date_from = $values['date_from'] = date(($format == 'ymd' ? 'Y-m-d' : 'Y'), strtotime( $form_data["{$facet['show_field']}_from"] ));

						if ($format == 'ymd') {
							$date_from = $values['date_from'] = date('Y-m-d', strtotime($form_data["{$facet['show_field']}_from"]));
						}

						if ($format == 'year') {
							$date_from = $values['date_from'] = date('Y-m-d', strtotime($form_data["{$facet['show_field']}_from"] . '-01-01'));
						}
					}

					if (isset($form_data["{$facet['show_field']}_to"]) && $form_data["{$facet['show_field']}_to"]) {
						if ($format == 'ymd') {
							$date_to = $values['date_to'] = date('Y-m-d', strtotime($form_data["{$facet['show_field']}_to"]));
						}

						if ($format == 'year') {
							$date_to = $values['date_to'] = date('Y-m-d', strtotime($form_data["{$facet['show_field']}_to"] . '-12-31'));
						}
					}

					if (empty($values)) continue;
					if (count($values) == 1) $values = array_pop($values);

					$args['meta_query'][] = [
						'key'     => "_{$facet['show_field']}",
						'value'   => $values,
						'compare' => is_array($values) ? 'BETWEEN' : ($date_from ? '>' : '<'),
						'type' => 'DATE',
					];
				}
			}

			if ($facet['type'] == 'range' && isset($form_data[$facet['show_field']]) && $form_data[$facet['show_field']] && isset($form_data["{$facet['show_field']}_default"])) {
				$range_type = 'range';
				$range = $form_data[$facet['show_field']];
				$default_range = $form_data["{$facet['show_field']}_default"];

				// In case the range values include the maximum and minimum possible field values,
				// then skip, since the meta query is unnecessary, and would only make the query slower.
				if ($default_range == $range) continue;

				foreach ($facet['options'] as $option) {
					if ($option['name'] == 'type') $range_type = $option['value'];
				}

				if ($range_type == 'range' && strpos($range, '::') !== false) {
					$args['meta_query'][] = [
						'key'     => "_{$facet['show_field']}",
						'value'   => array_map('intval', explode('::', $range)),
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC',
					];
				}

				if ($range_type == 'simple') {
					$args['meta_query'][] = [
						'key'     => "_{$facet['show_field']}",
						'value'   => intval( $range ),
						'compare' => '<=',
						'type'    => 'NUMERIC',
					];
				}
			}

			if (($facet['type'] == 'dropdown' || $facet['type'] == 'checkboxes') && isset($form_data[$facet['show_field']]) && $form_data[$facet['show_field']]) {
				$dropdown_values = array_filter( array_map('stripslashes', (array) $form_data[$facet['show_field']] ) );

				if (!$dropdown_values) continue;

				// Tax query.
				if (
					$type->get_field( $facet[ 'show_field' ] ) &&
					! empty( $type->get_field( $facet[ 'show_field' ] )['taxonomy'] ) &&
					taxonomy_exists( $type->get_field( $facet[ 'show_field' ] )['taxonomy'] )
				) {
					$args['tax_query'][] = [
						'taxonomy' => $type->get_field( $facet[ 'show_field' ] )['taxonomy'],
						'field' => 'slug',
						'terms' => $dropdown_values,
						'operator' => $tax_query_operator,
						'include_children' => $tax_query_operator !== 'AND',
					];

					continue;
				}

				// If the meta value is serialized.
				if ( $type->get_field( $facet[ 'show_field' ] ) && $type->get_field( $facet[ 'show_field' ] )['type'] == 'multiselect' ) {
					foreach ( $dropdown_values as $dropdown_value) {
						// dd(serialize( $dropdown_value ), serialize( [ 'opt1' => 'opt3', 'rtfg' => 4554563 ] ));
						$args['meta_query'][] = [
							'key'     => "_{$facet['show_field']}",
							'value'   => '"' . $dropdown_value . '"',
							'compare' => 'LIKE',
						];
					}

					continue;
				}

				$args['meta_query'][] = [
					'key'     => "_{$facet['show_field']}",
					'value'   => $dropdown_values,
					'compare' => 'IN',
				];
			}
		}

		if ( c27()->get_setting( 'promotions_enabled', false ) ) {
			$promoted_args = [
				'post_type' => 'job_listing',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'orderby' => 'rand',
				'meta_query' => [[
					'key'     => '_case27_listing_type',
					'value'   =>  $type->get_slug(),
					'compare' => '='
				]],
			];
			$promoted_args['meta_query'][] = $this->promoted_only_clause();

			// $args['meta_query']['c27_promoted_clause'] = $this->promoted_first_clause();

			// $args['orderby'] = 'c27_promoted_clause_end_date ' . $args['orderby'];
		}


		$results = [];

		ob_start();

		$promoted_listings = !empty( $promoted_args ) ? new \WP_Query( $promoted_args ) : false;
		$promoted_ids = [];
		// dump($promoted_args, $promoted_listings->request);

		$result['found_jobs'] = false;
		$result['data'] = [];
		$listing_wrap = isset($_POST['listing_wrap']) && $_POST['listing_wrap'] ? sanitize_text_field($_POST['listing_wrap']) : '';

		if ( c27()->get_setting( 'promotions_enabled', false ) && $promoted_listings && $promoted_listings->have_posts() ) :
			while ( $promoted_listings->have_posts() ) : $promoted_listings->the_post();
				// dump(get_the_ID());
				global $post; $post->c27_options__wrap_in = $listing_wrap;
				get_job_manager_template_part( 'content', 'job_listing' );
				$result['data'][] = $post->_c27_marker_data;
				$promoted_ids[] = absint( get_the_ID() );
			endwhile; wp_reset_postdata();
		endif;

		$result['promoted_html'] = ob_get_clean();

		ob_start();

		$listings = $this->query( $args );

		// $result['args'] = $args;
		// $result['sql'] = $listings->request;

		if ( $listings->have_posts() ) : $result['found_jobs'] = true;
			while ( $listings->have_posts() ) : $listings->the_post();
				if ( absint( $listings->post_count ) > 3 && in_array( absint( get_the_ID() ), $promoted_ids ) ) {
					continue;
				}

				global $post; $post->c27_options__wrap_in = $listing_wrap; $post->_c27_show_promoted_badge = false;
				get_job_manager_template_part( 'content', 'job_listing' );
				$result['data'][] = $post->_c27_marker_data;
			endwhile;

			$result['listings_html'] = ob_get_clean();

			if ( absint( $listings->post_count ) <= 3 ) {
				$result['html'] = $result['listings_html'];
			} else {
				$result['html'] = $result['promoted_html'] . $result['listings_html'];
			}

			wp_reset_postdata();
		else:
			get_job_manager_template_part( 'content', 'no-jobs-found' );
			$result['html'] = ob_get_clean();
		endif;

		// Generate pagination
		$result['pagination'] = get_job_listing_pagination( $listings->max_num_pages, ($page + 1) );

		$result['showing'] = sprintf( __( '%d results', 'my-listing' ), $listings->found_posts);

		if ($listings->found_posts == 1) {
			$result['showing'] = __( 'One result', 'my-listing');
		}

		if ($listings->found_posts < 1) {
			$result['showing'] = __( 'No results', 'my-listing' );
		}

		$result['max_num_pages'] = $listings->max_num_pages;

		wp_send_json( $result );
	}

	public function get_ordering_clauses( &$args, $type, $form_data ) {
		$options = (array) $type->get_ordering_options();
		$sortby  = ! empty( $form_data['sort'] ) ? sanitize_text_field( $form_data['sort'] ) : false;

		if ( ! $sortby || empty( $options ) ) {
			return false;
		}

		if ( ( $key = array_search( $sortby, array_column( $options, 'key' ) ) ) === false ) {
			return false;
		}

		$option  = $options[$key];
		$clauses = $option['clauses'];
		$orderby = [];


		foreach ( $clauses as $clause ) {
			if ( empty( $clause['context'] ) || empty( $clause['orderby'] ) || empty( $clause['order'] ) || empty( $clause['type'] ) ) {
				continue;
			}

			if ( $clause['context'] === 'option' ) {
				if ( $clause['orderby'] == 'rand' ) {
					// Randomize every 3 hours.
					$seed = apply_filters( 'mylisting/explore/rand/seed', floor( time() / 10800 ) );
					$orderby[ "RAND({$seed})" ] = $clause['order'];
				} else {
					$orderby[ $clause['orderby'] ] = $clause['order'];
				}
			}

			if ( $clause['context'] == 'meta_key' ) {
				$clause_id = sprintf( 'clause-%s-%s', $clause['orderby'], uniqid() );

				$args['meta_query'][ $clause_id ] = [
					'key' => '_' . $clause['orderby'],
					'compare' => 'EXISTS',
					'type' => $clause['type'],
				];

				$orderby[ $clause_id ] = $clause['order'];
			}

			if ( $clause['context'] == 'raw_meta_key' ) {
				$clause_id = sprintf( 'clause-%s-%s', $clause['orderby'], uniqid() );

				$args['meta_query'][ $clause_id ] = [
					'key' => $clause['orderby'],
					'compare' => 'EXISTS',
					'type' => $clause['type'],
				];

				$orderby[ $clause_id ] = $clause['order'];
			}
		}

		if ( ! empty( $orderby ) ) {
			$args['orderby'] = $orderby;

			if ( isset( $args['order'] ) ) {
				unset( $args['order'] );
			}
		}

		// dd($clauses, $option);
		// dd($args, $orderby);
	}

	public function get_proximity_sql() {
		global $wpdb;

		return "
			SELECT $wpdb->posts.ID,
				( %s * acos(
					cos( radians(%s) ) *
					cos( radians( latitude.meta_value ) ) *
					cos( radians( longitude.meta_value ) - radians(%s) ) +
					sin( radians(%s) ) *
					sin( radians( latitude.meta_value ) )
				) )
				AS distance, latitude.meta_value AS latitude, longitude.meta_value AS longitude
				FROM $wpdb->posts
				INNER JOIN $wpdb->postmeta
					AS latitude
					ON $wpdb->posts.ID = latitude.post_id
				INNER JOIN $wpdb->postmeta
					AS longitude
					ON $wpdb->posts.ID = longitude.post_id
				WHERE 1=1
					AND ($wpdb->posts.post_status = 'publish' )
					AND latitude.meta_key='geolocation_lat'
					AND longitude.meta_key='geolocation_long'
				HAVING distance < %s
				ORDER BY $wpdb->posts.menu_order ASC, distance ASC";
	}

	public function promoted_only_clause() {
		return [
			'relation' => 'AND',
			[
				'key' => '_case27_listing_promotion_start_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '<=',
				'type' => 'DATETIME',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '>=',
				'type' => 'DATETIME',
			],
		];
	}
}

new ExploreListingsQuery;
