<?php

namespace MyListing\Queries;

class RelatedListingsQuery extends Query {

	public $action = 'get_related_listings_by_id';

	public function handle() {
		check_ajax_referer( 'c27_ajax_nonce', 'security' );

		if ( empty( $_POST['listing_id'] ) ) {
			return false;
		}

		$page       = absint( isset($_POST['page']) ? $_POST['page'] : 0 );
		$per_page   = absint( isset($_POST['per_page']) ? $_POST['per_page'] : 9 );
		$meta_query = [];

		$meta_query[] = [
			'key' => '_related_listing',
			'value' => absint( $_POST['listing_id'] ),
		];

		if ( ! empty( $_POST['listing_type'] ) ) {
			$meta_query[] = [
				'key' => '_case27_listing_type',
				'value' => sanitize_text_field( $_POST['listing_type'] ),
			];
		}

		return $this->send( [
			'order' => sanitize_text_field( isset( $_POST['order'] ) ? $_POST['order'] : 'DESC' ),
			'offset' => $page * $per_page,
			'orderby' => sanitize_text_field( isset($_POST['orderby']) ? $_POST['orderby'] : 'date' ),
			'posts_per_page' => $per_page,
			'meta_query' => $meta_query,
		] );
	}
}

new RelatedListingsQuery;
