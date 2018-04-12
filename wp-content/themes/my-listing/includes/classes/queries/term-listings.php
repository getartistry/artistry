<?php

namespace MyListing\Queries;

class TermListingsQuery extends Query {

	public $action = 'get_listings_by_taxonomy';

	public function handle() {
		check_ajax_referer( 'c27_ajax_nonce', 'security' );

		if ( empty( $_REQUEST['form_data'] ) || ! is_array( $_REQUEST['form_data'] ) ) {
			return false;
		}

		if ( empty( $_REQUEST['term'] ) ) {
			return false;
		}

		$taxonomy = ! empty( $_REQUEST['taxonomy'] ) ? sanitize_text_field( $_REQUEST['taxonomy'] ) : 'job_listing_category';

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}

		$form_data = $_REQUEST['form_data'];
		$page = absint( isset($form_data['page']) ? $form_data['page'] : 0 );
		$per_page = absint( isset($form_data['per_page']) ? $form_data['per_page'] : c27()->get_setting('general_explore_listings_per_page', 9));
		$term = sanitize_text_field( $_REQUEST['term'] );
		$wrapper = ! empty( $_POST['listing_wrap'] ) ? sanitize_text_field($_POST['listing_wrap']) : '';

		return $this->send( [
			'order' => sanitize_text_field( isset($form_data['order']) ? $form_data['order'] : 'DESC' ),
			'offset' => $page * $per_page,
			'orderby' => sanitize_text_field( isset($form_data['orderby']) ? $form_data['orderby'] : 'date' ),
			'posts_per_page' => $per_page,
			'meta_query' => [],
			'tax_query' => [[
				'taxonomy' => $taxonomy,
				'field' => 'id',
				'terms' => $term,
			]],
			'output' => [
				'item-wrapper' => $wrapper,
			],
		] );
	}
}

new TermListingsQuery;
