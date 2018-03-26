<?php
namespace ElementorPro\Modules\Woocommerce;

use ElementorPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function get_name() {
		return 'woocommerce';
	}

	public function get_widgets() {
		return [
			'Products',
			'Add_To_Cart',
			'Elements',
			'Categories',
		];
	}

	public function add_product_post_class( $classes ) {
		$classes[] = 'product';

		return $classes;
	}

	public function add_products_post_class_filter() {
		add_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function remove_products_post_class_filter() {
		remove_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	public function __construct() {
		parent::__construct();

		// On Editor - register Woocommerce frontend hooks - before the Editor init
		add_action( 'admin_action_elementor', [ $this, 'register_wc_hooks' ], 9 );
	}
}
