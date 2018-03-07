<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Abstract_Product_Display
 */
abstract class Variable_Abstract_Product_Display extends Variable {

	public $support_limit_field = false;

	public $supports_order_table = false;

	public $supports_cart_table = false;

	protected $temp_template_args = [];


	function load_admin_details() {

		$templates = apply_filters( 'automatewoo/variables/product_templates', [
			'' => __( 'Product Grid - 2 Column', 'automatewoo' ),
			'product-grid-3-col' => __( 'Product Grid - 3 Column', 'automatewoo' ),
			'product-rows' => __( 'Product Rows', 'automatewoo' ),
			'cart-table' => __( 'Cart Table', 'automatewoo' ),
			'order-table' => __( 'Order Table', 'automatewoo' ),
			'list-comma-separated' => __( 'List - Comma Separated', 'automatewoo' )
		]);

		if ( ! $this->supports_cart_table ) {
			unset( $templates[ 'cart-table' ] );
		}

		if ( ! $this->supports_order_table ) {
			unset( $templates[ 'order-table' ] );
		}

		$this->add_parameter_select_field( 'template', __( "Select which template will be used to display the products. The default is 'Product Grid - 2 Column'. For information on creating custom templates please refer to the documentation.", 'automatewoo'), $templates );

		$this->add_parameter_text_field( 'url_append', __( "Add a string to the end of each product URL. For example, using '#tab-reviews' can link customers directly to the review tab on a product page.", 'automatewoo'), false );

		if ( $this->support_limit_field ) {
			$this->add_parameter_text_field( 'limit', __( 'Set the maximum number of products that will be displayed.', 'automatewoo'), false, 8 );
		}
	}


	/**
	 * @param $product_ids
	 * @param string $orderby
	 * @param string $order
	 * @param int $limit
	 * @return array
	 */
	function prepare_products( $product_ids, $orderby = 'date', $order = 'DESC', $limit = 8 ) {

		if ( empty( $product_ids ) )
			return [];

		$product_ids = array_filter( $product_ids );

		$args = [
			'post_type' => 'product',
			'ignore_sticky_posts' => 1,
			'no_found_rows' => 1,
			'posts_per_page' => $limit,
			'post__in' => $product_ids,
			'fields' => 'ids',
			'orderby' => $orderby,
			'order' => $order,
			'tax_query' => WC()->query->get_tax_query(),
			'meta_query' => WC()->query->get_meta_query()
		];

		if ( $orderby == 'popularity' ) {
			$args['meta_key'] = 'total_sales';
			$args['orderby'] = 'meta_value_num';
		}

		$query = new \WP_Query( $args );

		return array_map( 'wc_get_product', $query->posts );
	}


	/**
	 * @param $workflow Workflow
	 * @param array $parameters
	 * @return array
	 */
	function get_default_product_template_args( $workflow, $parameters = [] ) {
		return [
			'workflow' => $workflow,
			'variable_name' => $this->get_name(),
			'data_type' => $this->get_data_type(),
			'data_field' => $this->get_data_field(),
			'url_append' => isset( $parameters['url_append'] ) ? trim( Clean::string( $parameters['url_append'] ) ) : ''
		];
	}


	/**
	 * @param string $template
	 * @param array $args
	 *
	 * @return string
	 */
	function get_product_display_html( $template, $args = [] ) {

		ob_start();

		if ( $template ) {
			$template = sanitize_file_name( $template );

			if ( ! pathinfo( $template, PATHINFO_EXTENSION ) )
				$template .= '.php';
		}
		else {
			$template = 'product-grid-2-col.php';
		}

		$this->temp_template_args = $args;
		add_filter( 'post_type_link', [ $this, 'filter_product_links' ], 100, 2 );

		aw_get_template( 'email/' . $template, $args );

		remove_filter( 'post_type_link', [ $this, 'filter_product_links' ] );
		$this->temp_template_args = [];

		return ob_get_clean();
	}


	/**
	 * @param string $link
	 * @param \WP_Post $post
	 * @return string
	 */
	function filter_product_links( $link, $post ) {

		if ( $post->post_type !== 'product') {
			return $link;
		}

		if ( isset( $this->temp_template_args['url_append'] ) ) {
			$link .= $this->temp_template_args['url_append'];
		}
		return $link;
	}

}
