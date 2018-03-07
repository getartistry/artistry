<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Shop_Products
 */
class Variable_Shop_Products extends Variable_Abstract_Product_Display {

	public $support_limit_field = true;


	function load_admin_details() {

		$this->add_parameter_select_field( 'type', __( "Determines which products will be displayed.", 'automatewoo'), [
			'featured' => __( 'Featured', 'automatewoo' ),
			'sale' => __( 'Sale', 'automatewoo' ),
			'recent' => __( 'Recent', 'automatewoo' ),
			'top_selling' => __( 'Top Selling', 'automatewoo' ),
			'category' => __( 'By Product Category', 'automatewoo' ),
			'tag' => __( 'By Product Tag', 'automatewoo' ),
			'ids' => __( 'By Product IDs', 'automatewoo' ),
			'custom' => __( 'By Custom Filter', 'automatewoo' )
		], true );

		$this->add_parameter_text_field( 'ids', __( "Display products by ID, use '+' as a delimiter. E.g. 34+12+5", 'automatewoo'), true, '', [
			'show' => 'type=ids'
		] );

		$this->add_parameter_text_field( 'category', __( "Display products by product category slug. E.g. clothing or clothing+shoes", 'automatewoo'), true, '', [
			'show' => 'type=category'
		] );

		$this->add_parameter_text_field( 'tag', __( "Display products by product tag slug. E.g. winter or winter+summer", 'automatewoo'), true, '', [
			'show' => 'type=tag'
		] );

		$this->add_parameter_text_field( 'filter', __( "Display products by using a WP filter.", 'automatewoo'), true, '', [
			'show' => 'type=custom'
		] );

		$this->add_parameter_select_field( 'sort', __( "Set the sorting of the products.", 'automatewoo'), [
			'' => __( 'Date added - Descending', 'automatewoo' ),
			'date-asc' => __( 'Date added - Ascending', 'automatewoo' ),
			'title-desc' => __( 'Title - Descending', 'automatewoo' ),
			'title-asc' => __( 'Title - Ascending', 'automatewoo' ),
			'popularity-desc' => __( 'Popularity - Descending', 'automatewoo' ),
			'popularity-asc' => __( 'Popularity - Ascending', 'automatewoo' )
		] );


		parent::load_admin_details();

		$this->description = __( "Display your shop's products by various criteria.", 'automatewoo');
	}


	/**
	 * @param $parameters
	 * @param $workflow Workflow
	 * @return string
	 */
	function get_value( $parameters, $workflow ) {

		$type = isset( $parameters['type'] ) ? $parameters['type'] : false;
		$sort = isset( $parameters['sort'] ) ? $parameters['sort'] : '';
		$template = isset( $parameters['template'] ) ? $parameters['template'] : false;
		$limit = isset( $parameters['limit'] ) ? absint( $parameters['limit'] ) : 8;

		if ( strstr( $sort, '-' ) ) {
			$sort = explode('-', $sort );
			$orderby = $sort[0];
			$order = strtoupper( $sort[1] );
		}
		else {
			$orderby = 'date';
			$order = 'DESC';
		}

		switch ( $type ) {

			case 'ids':

				if ( empty( $parameters['ids'] ) )
					return false;

				$ids = explode('+', $parameters['ids'] );
				$ids = array_map( 'absint', $ids );

				$products = $this->prepare_products( $ids, $orderby, $order, $limit );

				break;

			case 'category':

				$categories = $this->get_term_ids_from_slugs( $parameters['category'], 'product_cat' );

				if ( empty( $categories ) ) {
					return false;
				}

				$products = $this->prepare_products( get_objects_in_term( $categories, 'product_cat' ), $orderby, $order, $limit );

				break;

			case 'tag':

				$tags = $this->get_term_ids_from_slugs( $parameters['tag'], 'product_tag' );

				if ( empty( $tags ) ) {
					return false;
				}

				$products = $this->prepare_products( get_objects_in_term( $tags, 'product_tag' ), $orderby, $order, $limit );

				break;

			case 'featured':
				$products = $this->prepare_products( wc_get_featured_product_ids(), $orderby, $order, $limit );
				break;

			case 'sale':
				$products = $this->prepare_products( wc_get_product_ids_on_sale(), $orderby, $order, $limit );
				break;

			case 'recent':
				$products = $this->prepare_products( aw_get_recent_product_ids( $limit ), $orderby, $order, $limit );
				break;

			case 'top_selling':
				$products = $this->prepare_products( aw_get_top_selling_product_ids( $limit ), $orderby, $order, $limit );
				break;

			case 'custom':

				if ( empty( $parameters['filter'] ) )
					return false;

				$product_ids = apply_filters( $parameters['filter'], [], $workflow, $parameters );
				$products = $this->prepare_products( $product_ids, $limit );

				break;

			default:
				return false;
				break;
		}


		$args = array_merge( $this->get_default_product_template_args( $workflow, $parameters ), [
			'products' => $products
		]);

		return $this->get_product_display_html( $template, $args );
	}


	/**
	 * Slugs should be separated by '+'
	 *
	 * @param string $slugs
	 * @param string $taxonomy
	 * @return array
	 */
	private function get_term_ids_from_slugs( $slugs, $taxonomy ) {

		if ( empty( $slugs ) )
			return [];

		$ids = [];

		foreach ( explode( '+', $slugs ) as $slug ) {
			if ( $term = get_term_by( 'slug', trim( $slug ), $taxonomy ) ) {
				$ids[] = $term->term_id;
			}
		}

		return $ids;
	}

}

return new Variable_Shop_Products();
