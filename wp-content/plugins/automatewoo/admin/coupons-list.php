<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Admin_Coupons_List
 */
class AW_Admin_Coupons_List {


	function __construct() {
		add_action( 'pre_get_posts', [ $this, 'modify_results' ] );
		add_filter( 'views_edit-shop_coupon' , [ $this, 'filter_views' ] );
		add_filter( 'wp_count_posts' , [ $this, 'filter_counts' ], 10, 2 );
	}



	function filter_views( $views ) {

		$url = add_query_arg( [
			'post_type' => 'shop_coupon',
			'filter_automatewoo' => '1'
		], admin_url( 'edit.php' ) );

		$trash = aw_array_extract( $views, 'trash' );

		$count = number_format_i18n( $this->get_count() );
		$views['automatewoo'] = '<a href="' . $url . '"' . ( aw_request( 'filter_automatewoo' ) ? 'class="current"' : '' ) . '>' . __( 'AutomateWoo', 'automatewoo' ) . ' <span class="count">(' . $count . ')</span></a>';

		if ( $trash ) {
			$views['trash'] = $trash;
		}

		return $views;
	}


	/**
	 * @param $counts
	 * @param $type
	 * @return mixed
	 */
	function filter_counts( $counts, $type ) {

		if ( $type !== 'shop_coupon' ) {
			return $counts;
		}

		if ( ! isset( $counts->automatewoo ) ) {
			$count = $this->get_count();
			$counts->publish -= $count;
			$counts->automatewoo = $count;
		}

		return $counts;
	}


	/**
	 * @return int
	 */
	function get_count() {
		$coupons = get_posts([
			'post_type' => 'shop_coupon',
			'fields' => 'ids',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => '_is_aw_coupon',
					'value' => '1'
				]
			]
		]);
		return count( $coupons );
	}


	/**
	 * @param $query WP_Query
	 */
	function modify_results( $query ) {

		if ( ! $query->is_main_query() ) return;

		if ( ! isset( $query->query_vars['meta_query'] ) ) {
			$query->query_vars['meta_query'] = [];
		}

		if ( aw_request( 'filter_automatewoo' ) ) {
			$query->query_vars['meta_query'][] = [
				'key' => '_is_aw_coupon',
				'value' => '1'
			];
		}
		elseif ( aw_request( 'post_status' ) == 'publish' ) {
			$query->query_vars['meta_query'][] = [
				'key' => '_is_aw_coupon',
				'compare' => 'NOT EXISTS'
			];
		}
	}

}

new AW_Admin_Coupons_List();
