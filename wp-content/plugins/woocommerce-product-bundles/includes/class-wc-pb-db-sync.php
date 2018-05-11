<?php
/**
 * WC_PB_DB_Sync class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    5.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks for DB lifecycle management of products, bundles, bundled items and their meta.
 *
 * @class    WC_PB_DB_Sync
 * @version  5.7.1
 */
class WC_PB_DB_Sync {

	/**
	 * Task runner.
	 * @var WC_PB_DB_Sync_Task_Runner
	 */
	private static $sync_task_runner;

	/**
	 * Scan for bundles that need syncing on shutdown?
	 * @var boolean
	 */
	private static $sync_needed = false;

	/**
	 * Setup Admin class.
	 */
	public static function init() {

		// Duplicate bundled items when duplicating a bundle.
		add_action( 'woocommerce_product_duplicate_before_save', array( __CLASS__, 'duplicate_product_before_save' ), 10, 2 );

		// Delete bundled item DB entries when: i) the container bundle is deleted, or ii) the associated product is deleted.
		add_action( 'delete_post', array( __CLASS__, 'delete_post' ), 11 );
		add_action( 'woocommerce_delete_product', array( __CLASS__, 'delete_product' ), 11 );

		// When deleting a bundled item from the DB, clear the transients of the container bundle.
		add_action( 'woocommerce_delete_bundled_item', array( __CLASS__, 'delete_bundled_item' ) );

		// Delete meta reserved to the bundle type.
		add_action( 'woocommerce_before_product_object_save', array( __CLASS__, 'delete_reserved_price_meta' ) );

		if ( ! defined( 'WC_PB_DEBUG_STOCK_SYNC' ) ) {

			// Delete bundled item stock meta when stock changes.
			add_action( 'woocommerce_product_set_stock', array( __CLASS__, 'product_stock_changed' ), 100 );
			add_action( 'woocommerce_variation_set_stock', array( __CLASS__, 'product_stock_changed' ), 100 );

			// Delete bundled item stock meta when stock status changes.
			add_action( 'woocommerce_product_set_stock_status', array( __CLASS__, 'product_stock_status_changed' ), 100, 3 );
			add_action( 'woocommerce_variation_set_stock_status', array( __CLASS__, 'product_stock_status_changed' ), 100, 3 );

			if ( ! defined( 'WC_PB_DEBUG_STOCK_PARENT_SYNC' ) ) {

				include_once( 'class-wc-pb-db-sync-task-runner.php' );

				// Spawn task runner.
				add_action( 'init', array( __CLASS__, 'initialize_sync_task_runner' ), 5 );

				// Sync parent stock status and visibility with children on shutdown (not critical + async anyway).
				add_action( 'shutdown', array( __CLASS__, 'sync' ), 100 );
			}
		}
	}

	/**
	 * Duplicates bundled items when duplicating a bundle.
	 *
	 * @param  WC_Product  $duplicated_product
	 * @param  WC_Product  $product
	 */
	public static function duplicate_product_before_save( $duplicated_product, $product ) {

		if ( $product->is_type( 'bundle' ) ) {

			$bundled_items      = $product->get_bundled_data_items( 'edit' );
			$bundled_items_data = array();

			if ( ! empty( $bundled_items ) ) {
				foreach ( $bundled_items as $bundled_item ) {

					$bundled_item_data = $bundled_item->get_data();

					$bundled_item_data[ 'bundled_item_id' ] = 0;

					$bundled_items_data[] = $bundled_item_data;
				}

				$duplicated_product->set_bundled_data_items( $bundled_items_data );
			}
		}
	}

	/**
	 * Deletes bundled item DB entries when: i) their container product bundle is deleted, or ii) the associated bundled product is deleted.
	 *
	 * @param  mixed  $id  ID of post being deleted.
	 */
	public static function delete_post( $id ) {

		if ( ! current_user_can( 'delete_posts' ) ) {
			return;
		}

		if ( $id > 0 ) {

			$post_type = get_post_type( $id );

			if ( 'product' === $post_type ) {
				self::delete_product( $id );
			}
		}
	}

	/**
	 * Deletes bundled item DB entries when: i) their container product bundle is deleted, or ii) the associated bundled product is deleted.
	 *
	 * @param  mixed  $id  ID of product being deleted.
	 */
	public static function delete_product( $id ) {

		// Delete bundled item DB entries and meta when deleting a bundle.
		$bundled_items = WC_PB_DB::query_bundled_items( array(
			'bundle_id' => $id,
			'return'    => 'objects'
		) );

		if ( ! empty( $bundled_items ) ) {
			foreach ( $bundled_items as $bundled_item ) {
				$bundled_item->delete();
			}
		}

		// Delete bundled item DB entries and meta when deleting an associated product.
		$bundled_item_ids = array_keys( wc_pb_get_bundled_product_map( $id, false ) );

		if ( ! empty( $bundled_item_ids ) ) {
			foreach ( $bundled_item_ids as $bundled_item_id ) {
				WC_PB_DB::delete_bundled_item( $bundled_item_id );
			}
		}
	}

	/**
	 * When deleting a bundled item from the DB, clear the transients of the container bundle.
	 *
	 * @param  WC_Bundled_Item_Data  $item  The bundled item DB object being deleted.
	 */
	public static function delete_bundled_item( $item ) {
		$bundle_id = $item->get_bundle_id();
		wc_delete_product_transients( $bundle_id );
	}

	/**
	 * Delete price meta reserved to bundles/composites.
	 *
	 * @param  WC_Product  $product
	 * @return void
	 */
	public static function delete_reserved_price_meta( $product ) {

		$product->delete_meta_data( '_wc_pb_bundled_value' );
		$product->delete_meta_data( '_wc_pb_bundled_weight' );

		if ( false === in_array( $product->get_type(), array( 'bundle', 'composite' ) ) ) {
			$product->delete_meta_data( '_wc_sw_max_price' );
			$product->delete_meta_data( '_wc_sw_max_regular_price' );
		}
	}

	/**
	 * Delete bundled item stock meta cache when an associated product stock changes.
	 *
	 * @param  mixed   $product_id
	 * @param  string  $stock_status
	 * @param  mixed   $product
	 * @return void
	 */
	public static function product_stock_status_changed( $product_id, $stock_status, $product = null ) {

		if ( is_null( $product ) ) {
			$product = wc_get_product( $product_id );
		}

		$bundled_product_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();

		self::reset_bundled_items_stock_status( $bundled_product_id );
	}

	/**
	 * Delete bundled item stock meta cache when an associated product stock changes.
	 *
	 * @param  WC_Product  $product
	 * @return void
	 */
	public static function product_stock_changed( $product ) {

		$bundled_product_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();

		self::reset_bundled_items_stock_status( $bundled_product_id );
	}

	/**
	 * Delete bundled items stock meta when product stock (status) changes.
	 *
	 * @param  int  $product_id
	 * @return void
	 */
	public static function reset_bundled_items_stock_status( $product_id ) {
		global $wpdb;

		$map = wc_pb_get_bundled_product_map( $product_id, false );

		if ( ! empty( $map ) ) {
			self::schedule_sync();
			WC_PB_DB::delete_bundled_items_stock_meta( $map );
		}
	}

	/**
	 * Spawn task runner.
	 */
	public static function initialize_sync_task_runner() {
		self::$sync_task_runner = new WC_PB_DB_Sync_Task_Runner();
	}

	/**
	 * Sync:
	 *
	 * - bundled items stock status;
	 * - bundle stock status; and
	 * - bundle visibility.
	 *
	 * @see  'WC_PB_DB_Sync_Task_Runner::task'
	 *
	 * @return void
	 */
	public static function sync() {

		if ( ! is_object( self::$sync_task_runner ) ) {
			self::initialize_sync_task_runner();
		}

		// Need to queue extra items?
		if ( self::$sync_needed ) {

			WC_PB_Core_Compatibility::log( 'Scheduling sync...', 'info', 'wc_pb_db_sync_tasks' );

			$data_store = WC_Data_Store::load( 'product-bundle' );
			$ids        = $data_store->get_bundled_items_stock_status_ids( 'unsynced' );

			if ( ! empty( $ids ) ) {

				self::$sync_task_runner->push_to_queue( array(
					'sync_ids'   => $ids,
					'delete_ids' => array()
				) );

				self::$sync_task_runner->save();

				WC_PB_Core_Compatibility::log( sprintf( 'Queued %s IDs.', sizeof( $ids ) ), 'info', 'wc_pb_db_sync_tasks' );

				if ( ! self::$sync_task_runner->is_running() ) {

					// Give background processing a chance to work - 2 second grace period.
					if ( false === get_site_transient( 'wc_pb_db_sync_task_runner_manual_lock' ) ) {
						set_site_transient( 'wc_pb_db_sync_task_runner_manual_lock', microtime(), 2 );
					}

					// Remote post to self.
					self::$sync_task_runner->dispatch();
				}

			} else {

				WC_PB_Core_Compatibility::log( 'No IDs found.', 'info', 'wc_pb_db_sync_tasks' );
			}

		// Give background processing a chance to work before considering a manual run...
		} elseif ( false === get_site_transient( 'wc_pb_db_sync_task_runner_manual_lock' ) ) {

			if ( self::$sync_task_runner->is_queued() && ! self::$sync_task_runner->is_running() ) {

				WC_PB_Core_Compatibility::log( 'Task runner idling. Attempting to run queued tasks manually...', 'info', 'wc_pb_db_sync_tasks' );
				do_action( self::$sync_task_runner->get_cron_hook_identifier() );
			}
		}
	}

	/**
	 * Schedules a sync check.
	 */
	public static function schedule_sync() {
		self::$sync_needed = true;
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated methods.
	|--------------------------------------------------------------------------
	*/

	public static function delete_reserved_price_post_meta( $post_id ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0' );

		$product_type = WC_Product_Factory::get_product_type( $post_id );

		if ( false === in_array( $product_type, array( 'bundle', 'composite' ) ) ) {
			delete_post_meta( $post_id, '_wc_sw_max_price' );
			delete_post_meta( $post_id, '_wc_sw_max_regular_price' );
		}
	}
	public static function delete_bundled_items_stock_cache( $product_id ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0', __CLASS__ . '::reset_bundled_items_stock_status()' );
		return self::reset_bundled_items_stock_status( $product_id );
	}
	public static function delete_bundle_transients( $post_id ) {
		_deprecated_function( __METHOD__ . '()', '5.5.0' );
		if ( $post_id > 0 ) {
			/*
			 * Delete associated bundled items stock cache when clearing product transients.
			 * Workaround for https://github.com/somewherewarm/woocommerce-product-bundles/issues/22 .
			 */
			self::reset_bundled_items_stock_status( $post_id );
		}
	}
}

WC_PB_DB_Sync::init();
