<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) ) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

/**
 * @class Admin_Licenses_Table
 */
class Admin_Licenses_Table extends \WP_List_Table {

	/** @var int */
	public $per_page = 100;

	/** @var array */
	public $data = [];

	/** @var string  */
	public $nonce_action;


	function __construct() {
		parent::__construct([
			'singular' => 'license',
			'plural' => 'licenses',
			'ajax' => false
		]);

		$this->gather_data();
	}


	/**
	 *
	 */
	function gather_data() {

		$license_info = Licenses::get_primary_license();

		$this->data[] = [
			'product_id' => AW()->plugin_slug,
			'product_name' => __( 'AutomateWoo', 'automatewoo' ),
			'product_version' => AW()->version,
			'license_key' => $license_info ? $license_info['key'] : false,
			'license_expiry' => $license_info ? $license_info['expiry'] : false
		];

		foreach ( Addons::get_all() as $addon ) {
			$license_info = Licenses::get_license( $addon->id );

			$this->data[] = array(
				'product_id' => $addon->id,
				'product_name' => $addon->name,
				'product_version' => $addon->version,
				'license_key' => $license_info ? $license_info['key'] : false,
				'license_expiry' => $license_info ? $license_info['expiry'] : false
			);
		}
	}


	function get_columns() {
		$columns = [
			'product_id' => __( 'Product ID', 'automatewoo' ),
			'product_name' => __( 'Product', 'automatewoo' ),
			'product_version' => __( 'Version', 'automatewoo' ),
			'license_key' => __( 'License Key', 'automatewoo' ),
			'license_expiry' => __( 'License Expires', 'automatewoo' )
		];
		return $columns;
	}


	/**
	 * @param $item array
	 * @return string
	 */
	function column_product_name( $item ) {
		return wpautop( '<strong>' . $item['product_name'] . '</strong>' );
	}


	/**
	 * @param array $item
	 * @return string
	 */
	function column_product_version( $item ) {
		return wpautop( $item['product_version'] );
	}


	/**
	 * @param array $item
	 * @return string
	 */
	function column_license_key( $item ) {
		if ( $item['license_key'] ) {
			$url = wp_nonce_url( add_query_arg( [
				'action' => 'deactivate',
				'product' => $item['product_id']
			], Admin::page_url('licenses') ), $this->nonce_action );
			return "<a href='$url' class='button'>" . __( 'Deactivate', 'automatewoo' ) . "</button>";
		}
		else {
			return '<input name="license_keys[' . esc_attr( $item['product_id'] ) . ']" id="license_keys-' . esc_attr( $item['product_id'] ) . '" type="text" value="" size="40" placeholder="' . esc_attr( __( 'Place license key here', 'automatewoo' ) ) . '">';
		}
	}


	/**
	 * @param $item
	 * @return string
	 */
	function column_license_expiry( $item ) {

		if ( $item['license_key'] && $item['license_expiry'] ) {
			$renew_link = Licenses::get_renewal_url( 'expired-license-table' );
			$date = new \DateTime( $item['license_expiry'] );
			$date_string = $date->format( get_option( 'date_format' ) );

			if ( Licenses::is_expired( $item['product_id'] ) ) {
				$date_string = __( 'Expired', 'automatewoo' ) . ' ' . sprintf( '<a href="' . $renew_link . '">%s</a>', __( '(Renew)', 'automatewoo' ) );
			}

			return $date_string;
		}

		return '-';
	}


	/**
	 *
	 */
	function prepare_items() {

		$columns  = $this->get_columns();
		$hidden   = [ 'product_id' ];
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];

		$total_items = count( $this->data );

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page' => $total_items,
			'total_pages' => 1
		]);

		$this->items = $this->data;
	}

}
