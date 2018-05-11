<?php
/**
 * WC_PB_Admin class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Bundles Admin Class.
 *
 * Loads admin scripts, includes admin classes and adds admin hooks.
 *
 * @class    WC_PB_Admin
 * @version  5.5.0
 */
class WC_PB_Admin {

	/**
	 * Setup Admin class.
	 */
	public static function init() {

		add_action( 'init', array( __CLASS__, 'admin_init' ) );

		// Enqueue scripts.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ), 11 );

		// Template override scan path.
		add_filter( 'woocommerce_template_overrides_scan_paths', array( __CLASS__, 'template_scan_path' ) );

		// Add "Insufficient Stock" report tab.
		add_filter( 'woocommerce_admin_reports', array( __CLASS__, 'add_insufficient_stock_report_tab' ) );
	}

	/**
	 * Admin init.
	 */
	public static function admin_init() {
		self::includes();
	}

	/**
	 * Inclusions.
	 */
	public static function includes() {

		// Product Import/Export.
		if ( WC_PB_Core_Compatibility::is_wc_version_gte( '3.1' ) ) {
			require_once( 'export/class-wc-pb-product-export.php' );
			require_once( 'import/class-wc-pb-product-import.php' );
		}

		// Product Metaboxes.
		require_once( 'meta-boxes/class-wc-pb-meta-box-product-data.php' );

		// Post type stuff.
		require_once( 'class-wc-pb-admin-post-types.php' );

		// Admin AJAX.
		require_once( 'class-wc-pb-admin-ajax.php' );
	}

	/**
	 * Admin writepanel scripts.
	 */
	public static function admin_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'wc-pb-admin-product-panel', WC_PB()->plugin_url() . '/assets/js/wc-pb-admin-write-panels' . $suffix . '.js', array( 'jquery', 'jquery-ui-datepicker', 'wc-admin-meta-boxes' ), WC_PB()->version );

		wp_register_style( 'wc-pb-admin-css', WC_PB()->plugin_url() . '/assets/css/wc-pb-admin.css', array(), WC_PB()->version );
		wp_style_add_data( 'wc-pb-admin-css', 'rtl', 'replace' );

		wp_register_style( 'wc-pb-admin-product-css', WC_PB()->plugin_url() . '/assets/css/wc-pb-admin-write-panels.css', array( 'woocommerce_admin_styles' ), WC_PB()->version );
		wp_style_add_data( 'wc-pb-admin-product-css', 'rtl', 'replace' );

		wp_register_style( 'wc-pb-admin-edit-order-css', WC_PB()->plugin_url() . '/assets/css/wc-pb-admin-edit-order.css', array( 'woocommerce_admin_styles' ), WC_PB()->version );
		wp_style_add_data( 'wc-pb-admin-edit-order-css', 'rtl', 'replace' );

		wp_enqueue_style( 'wc-pb-admin-css' );

		// Get admin screen id.
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		// WooCommerce admin pages.
		if ( in_array( $screen_id, array( 'product' ) ) ) {

			wp_enqueue_script( 'wc-pb-admin-product-panel' );

			// Find group modes with a parent item.
			$group_mode_options      = WC_Product_Bundle::get_group_mode_options();
			$group_modes_with_parent = array();

			foreach ( $group_mode_options as $group_mode_key => $group_mode_title ) {
				if ( WC_Product_Bundle::group_mode_has( $group_mode_key, 'parent_item' ) || WC_Product_Bundle::group_mode_has( $group_mode_key, 'faked_parent_item' ) ) {
					$group_modes_with_parent[] = $group_mode_key;
				}
			}

			$params = array(
				'add_bundled_product_nonce' => wp_create_nonce( 'wc_bundles_add_bundled_product' ),
				'group_modes_with_parent'   => $group_modes_with_parent,
				'is_wc_version_gte_3_2'     => WC_PB_Core_Compatibility::is_wc_version_gte( '3.2' ) ? 'yes' : 'no'
			);

			wp_localize_script( 'wc-pb-admin-product-panel', 'wc_bundles_admin_params', $params );
		}

		if ( in_array( $screen_id, array( 'edit-product', 'product' ) ) ) {
			wp_enqueue_style( 'wc-pb-admin-product-css' );
		}

		if ( $screen_id === 'edit-product' ) {
			wc_enqueue_js( "
				jQuery( function( $ ) {
					jQuery( '.show_insufficient_stock_items' ).on( 'click', function() {
						var anchor = jQuery( this ),
							panel  = jQuery( this ).parent().find( '.insufficient_stock_items' );

						if ( anchor.hasClass( 'closed' ) ) {
							anchor.removeClass( 'closed' );
							panel.show();
						} else {
							anchor.addClass( 'closed' );
							panel.hide();
						}
						return false;
					} );
				} );
			" );
		}

		if ( in_array( $screen_id, array( 'shop_order', 'edit-shop_order' ) ) ) {
			wp_enqueue_style( 'wc-pb-admin-edit-order-css' );
		}
	}

	/**
	 * Support scanning for template overrides in extension.
	 *
	 * @param  array  $paths
	 * @return array
	 */
	public static function template_scan_path( $paths ) {

		$paths[ 'WooCommerce Product Bundles' ] = WC_PB()->plugin_path() . '/templates/';

		return $paths;
	}

	/**
	 * Adds an "Insufficient stock" tab to the WC stock reports.
	 *
	 * @param  array  $reports
	 * @return array
	 */
	public static function add_insufficient_stock_report_tab( $reports ) {

		$reports[ 'stock' ][ 'reports' ][ 'insufficient_stock' ] = array(
			'title'       => __( 'Insufficient stock', 'woocommerce-product-bundles' ),
			'description' => '',
			'hide_title'  => true,
			'callback'    => array( __CLASS__, 'get_insufficient_stock_report_content' )
		);

		return $reports;
	}

	/**
	 * Renders the "Insufficient stock" report content.
	 *
	 * @param  string  $name
	 * @return void
	 */
	public static function get_insufficient_stock_report_content( $name ) {

		require_once( 'reports/class-wc-pb-report-insufficient-stock.php' );

		$report = new WC_PB_Report_Insufficient_Stock;
		$report->output_report();
	}
}

WC_PB_Admin::init();
