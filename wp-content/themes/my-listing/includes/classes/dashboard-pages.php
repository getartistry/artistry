<?php

namespace CASE27\Classes;

class DashboardPages {
	use \CASE27\Traits\Instantiatable;

	public $pages = [];

	/**
	 * Plugin actions.
	 */
	public function __construct() {
		// $this->pages = apply_filters( 'case27_dashboard_pages', [] );

		// Actions used to insert a new endpoint in WordPress.
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_filter( 'woocommerce_get_query_vars', array( $this, 'add_query_vars' ), 0 );

		// Insering your new tab/page into the My Account page.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
	}

	public function add_page( $page ) {
		$this->pages[$page['endpoint']] = $page;

		// dd($page);
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 */
	public function add_endpoints() {
		foreach ($this->pages as $page) {
			add_rewrite_endpoint( $page['endpoint'], EP_ROOT | EP_PAGES );
			add_action("woocommerce_account_{$page['endpoint']}_endpoint", function() use ($page) {
				require_once $page['template'];
			});
		}
	}

	/**
	 * Add new query var.
	 */
	public function add_query_vars( $vars ) {
		return array_merge($vars, array_column($this->pages, 'endpoint', 'endpoint'));
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 */
	public function new_menu_items($items)
	{
		// Remove the logout menu item.
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		// Insert custom endpoints.
		$items += array_column(array_filter($this->pages, function($page) {
			return $page['show_in_menu'];
		}), 'title', 'endpoint');

		// Insert back the logout item.
		$items['customer-logout'] = $logout;

		// Sort items.
		foreach ($items as $item_key => $item) {
			if ( in_array( $item_key, array_keys( $this->pages ) ) ) {
				$items[$item_key] = $this->pages[$item_key];
			}

			if ( $item_key == 'dashboard' ) {
				$items['dashboard'] = [
					'title' => __( 'My Account', 'my-listing' ),
					'order' => 1,
				];
			}
		}

		// dump($items);
		// uasort( $items, function($a, $b) {
		// 	$_a = 25;
		// 	$_b = 25;
		// 	if ( is_array( $a ) && ! empty( $a['order'] ) ) $_a = $a['order'];
		// 	if ( is_array( $b ) && ! empty( $b['order'] ) ) $_b = $b['order'];

		// 	return $_a - $_b;
		// });

		$items = $this->sortByProp( $items, 'order' );


		foreach ($items as $item_key => $item) {
			if ( is_array( $item ) && ! empty( $item['title'] ) ) {
				$items[$item_key] = $item['title'];
			}
		}

		return $items;
	}

	public  function sortByProp($array, $propName, $reverse = false) {
		$sorted = [];
		foreach ($array as $itemKey => $item) {
			if ( ! is_array( $item ) ) {
				$item = [ 'title' => $item, 'order' => 25, 'endpoint' => $itemKey ];
			}

			if ( ! isset( $item[ $propName ] ) ) {
				$item[ $propName ] = 25;
			}

			if ( ! isset( $item[ 'endpoint' ] ) ) {
				$item[ 'endpoint' ] = $itemKey;
			}

			$sorted[ $item[ $propName ] ][] = $item;
		}

		$reverse ? krsort( $sorted ) : ksort( $sorted );

		$result = [];
		foreach ($sorted as $subArray) foreach ($subArray as $item) {
			$result[ $item['endpoint'] ] = $item;
		}

		return $result;
	}
}

DashboardPages::instance();
