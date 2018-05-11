<?php
/**
 * WC_PB_Compatibility class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.6.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles compatibility with other WC extensions.
 *
 * @class    WC_PB_Compatibility
 * @version  5.7.6
 */
class WC_PB_Compatibility {

	/**
	 * Min required plugin versions to check.
	 * @var array
	 */
	private $required = array();

	/**
	 * Publicly accessible props for use by compat classes. Still not moved for back-compat.
	 * @var array
	 */
	public static $addons_prefix          = '';
	public static $nyp_prefix             = '';
	public static $bundle_prefix          = '';
	public static $compat_product         = '';
	public static $compat_bundled_product = '';
	public static $stock_data;

	/**
	 * The single instance of the class.
	 * @var WC_PB_Compatibility
	 *
	 * @since 5.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main WC_PB_Compatibility instance. Ensures only one instance of WC_PB_Compatibility is loaded or can be loaded.
	 *
	 * @static
	 * @return WC_PB_Compatibility
	 * @since  5.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '5.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 5.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-product-bundles' ), '5.0.0' );
	}

	/**
	 * Setup compatibility class.
	 */
	protected function __construct() {

		// Define dependencies.
		$this->required = array(
			'cp'     => '3.13.6',
			'addons' => '2.9.1',
			'minmax' => '1.3',
			'topatc' => '1.0',
			'bd'     => '1.0.4',
			'bs'     => '1.0.5'
		);

		// Initialize.
		$this->load_modules();
	}

	/**
	 * Initialize.
	 *
	 * @since  5.4.0
	 *
	 * @return void
	 */
	protected function load_modules() {

		if ( is_admin() ) {
			// Check plugin min versions.
			add_action( 'admin_init', array( $this, 'add_compatibility_notices' ) );
		}

		// Load modules.
		add_action( 'plugins_loaded', array( $this, 'module_includes' ), 100 );

		// Prevent initialization of deprecated mini-extensions.
		$this->unload_modules();
	}

	/**
	 * Core compatibility functions.
	 *
	 * @return void
	 */
	public static function core_includes() {
		require_once( 'core/class-wc-pb-core-compatibility.php' );
	}

	/**
	 * Prevent deprecated mini-extensions from initializing.
	 *
	 * @since  5.0.0
	 *
	 * @return void
	 */
	protected function unload_modules() {

		// Tabular Layout mini-extension was merged into Bundles.
		if ( class_exists( 'WC_PB_Tabular_Layout' ) ) {
			remove_action( 'plugins_loaded', array( 'WC_PB_Tabular_Layout', 'load_plugin' ), 10 );
		}
	}

	/**
	 * Load compatibility classes.
	 *
	 * @return void
	 */
	public function module_includes() {

		$module_paths = array();

		// Addons support.
		if ( class_exists( 'WC_Product_Addons' ) && defined( 'WC_PRODUCT_ADDONS_VERSION' ) && version_compare( WC_PRODUCT_ADDONS_VERSION, $this->required[ 'addons' ] ) >= 0 ) {
			$module_paths[ 'product_addons' ] = 'modules/class-wc-pb-addons-compatibility.php';
		}

		// NYP support.
		if ( function_exists( 'WC_Name_Your_Price' ) ) {
			$module_paths[ 'name_your_price' ] = 'modules/class-wc-pb-nyp-compatibility.php';
		}

		// Points and Rewards support.
		if ( class_exists( 'WC_Points_Rewards_Product' ) ) {
			$module_paths[ 'points_rewards_products' ] = 'modules/class-wc-pb-pnr-compatibility.php';
		}

		// Pre-orders support.
		if ( class_exists( 'WC_Pre_Orders' ) ) {
			$module_paths[ 'pre_orders' ] = 'modules/class-wc-pb-po-compatibility.php';
		}

		// Composite Products support.
		if ( class_exists( 'WC_Composite_Products' ) && function_exists( 'WC_CP' ) && version_compare( str_replace( '-dev', '', WC_CP()->version ), $this->required[ 'cp' ] ) >= 0 ) {
			$module_paths[ 'composite_products' ] = 'modules/class-wc-pb-cp-compatibility.php';
		}

		// One Page Checkout support.
		if ( function_exists( 'is_wcopc_checkout' ) ) {
			$module_paths[ 'one_page_checkout' ] = 'modules/class-wc-pb-opc-compatibility.php';
		}

		// Cost of Goods support.
		if ( class_exists( 'WC_COG' ) ) {
			$module_paths[ 'cost_of_goods' ] = 'modules/class-wc-pb-cog-compatibility.php';
		}

		// QuickView support.
		if ( class_exists( 'WC_Quick_View' ) ) {
			$module_paths[ 'quickview' ] = 'modules/class-wc-pb-qv-compatibility.php';
		}

		// PIP support.
		if ( class_exists( 'WC_PIP' ) ) {
			$module_paths[ 'pip' ] = 'modules/class-wc-pb-pip-compatibility.php';
		}

		// Subscriptions fixes.
		if ( class_exists( 'WC_Subscriptions' ) ) {
			$module_paths[ 'subscriptions' ] = 'modules/class-wc-pb-subscriptions-compatibility.php';
		}

		// Min Max Quantities integration.
		if ( class_exists( 'WC_Min_Max_Quantities' ) ) {
			$module_paths[ 'min_max_quantities' ] = 'modules/class-wc-pb-min-max-compatibility.php';
		}

		// WP Import/Export support.
		$module_paths[ 'wp_import_export' ] = 'modules/class-wc-pb-wp-ie-compatibility.php';

		// WooCommerce Give Products support.
		if ( class_exists( 'WC_Give_Products' ) ) {
			$module_paths[ 'give_products' ] = 'modules/class-wc-pb-give-products-compatibility.php';
		}

		// Shipwire integration.
		if ( class_exists( 'WC_Shipwire' ) ) {
			$module_paths[ 'shipwire' ] = 'modules/class-wc-pb-shipwire-compatibility.php';
		}

		// Shipstation integration.
		$module_paths[ 'shipstation' ] = 'modules/class-wc-pb-shipstation-compatibility.php';

		/**
		 * 'woocommerce_bundles_compatibility_modules' filter.
		 *
		 * Use this to filter the required compatibility modules.
		 *
		 * @since  5.7.6
		 * @param  array $module_paths
		 */
		$module_paths = apply_filters( 'woocommerce_bundles_compatibility_modules', $module_paths );

		foreach ( $module_paths as $name => $path ) {
			require_once( $path );
		}
	}

	/**
	 * Checks versions of compatible/integrated/deprecated extensions.
	 *
	 * @return void
	 */
	public function add_compatibility_notices() {

		global $woocommerce_composite_products;

		// CP version check.
		if ( ! empty( $woocommerce_composite_products ) ) {
			$required_version = $this->required[ 'cp' ];
			if ( version_compare( str_replace( '-dev', '', $woocommerce_composite_products->version ), $required_version ) < 0 ) {
				$extension = 'WooCommerce Composite Products';
				$notice    = sprintf( __( 'The installed version of <strong>%1$s</strong> is not supported by Product Bundles. Please update <strong>%1$s</strong> to version <strong>%2$s</strong> or higher.', 'woocommerce-product-bundles' ), $extension, $required_version );
				WC_PB_Admin_Notices::add_notice( $notice, 'warning' );
			}
		}

		// Addons version check.
		if ( class_exists( 'WC_Product_Addons' ) ) {
			$required_version = $this->required[ 'addons' ];
			if ( ! defined( 'WC_PRODUCT_ADDONS_VERSION' ) || version_compare( WC_PRODUCT_ADDONS_VERSION, $required_version ) < 0 ) {
				$extension = 'WooCommerce Product Add-ons';
				$notice    = sprintf( __( 'The installed version of <strong>%1$s</strong> is not supported by Product Bundles. Please update <strong>%1$s</strong> to version <strong>%2$s</strong> or higher.', 'woocommerce-product-bundles' ), $extension, $required_version );
				WC_PB_Admin_Notices::add_notice( $notice, 'warning' );
			}
		}

		// Tabular layout mini-extension check.
		if ( class_exists( 'WC_PB_Tabular_Layout' ) ) {
			$notice = sprintf( __( 'The <strong>WooCommerce Product Bundles - Tabular Layout</strong> mini-extension is now part of <strong>WooCommerce Product Bundles</strong>. Please deactivate and remove the <strong>WooCommerce Product Bundles - Tabular Layout</strong> plugin.', 'woocommerce-product-bundles' ) );
			WC_PB_Admin_Notices::add_notice( $notice, 'warning' );
		}

		// Min/Max Items mini-extension version check.
		if ( class_exists( 'WC_PB_Min_Max_Items' ) ) {
			$required_version = $this->required[ 'minmax' ];
			if ( version_compare( WC_PB_Min_Max_Items::$version, $required_version ) < 0 ) {
				$extension = 'WooCommerce Product Bundles - Min/Max Items';
				$notice    = sprintf( __( 'The installed version of <strong>%1$s</strong> is not supported by Product Bundles. Please update <strong>%1$s</strong> to version <strong>%2$s</strong> or higher.', 'woocommerce-product-bundles' ), $extension, $required_version );
				WC_PB_Admin_Notices::add_notice( $notice, 'warning' );
			}
		}

		// Top Add-to-Cart mini-extension version check.
		if ( class_exists( 'WC_PB_Top_Add_To_Cart' ) ) {
			$required_version = $this->required[ 'topatc' ];
			if ( version_compare( WC_PB_Top_Add_To_Cart::$version, $required_version ) < 0 ) {
				$extension = 'WooCommerce Product Bundles - Top Add to Cart Button';
				$notice    = sprintf( __( 'The installed version of <strong>%1$s</strong> is not supported by Product Bundles. Please update <strong>%1$s</strong> to version <strong>%2$s</strong> or higher.', 'woocommerce-product-bundles' ), $extension, $required_version );
				WC_PB_Admin_Notices::add_notice( $notice, 'warning' );
			}
		}

		// Bulk Discounts mini-extension version check.
		if ( class_exists( 'WC_PB_Bulk_Discounts' ) ) {
			$required_version = $this->required[ 'bd' ];
			if ( version_compare( WC_PB_Bulk_Discounts::$version, $required_version ) < 0 ) {
				$extension = 'WooCommerce Product Bundles - Bulk Discounts';
				$notice    = sprintf( __( 'The installed version of <strong>%1$s</strong> is not supported by Product Bundles. Please update <strong>%1$s</strong> to version <strong>%2$s</strong> or higher.', 'woocommerce-product-bundles' ), $extension, $required_version );
				WC_PB_Admin_Notices::add_notice( $notice, 'warning' );
			}
		}

		// Bundle-Sells mini-extension version check.
		if ( class_exists( 'WC_PB_Bundle_Sells' ) ) {
			$required_version = $this->required[ 'bs' ];
			if ( version_compare( WC_PB_Bundle_Sells::$version, $required_version ) < 0 ) {
				$extension = 'WooCommerce Product Bundles - Bundle-Sells';
				$notice    = sprintf( __( 'The installed version of <strong>%1$s</strong> is not supported by Product Bundles. Please update <strong>%1$s</strong> to version <strong>%2$s</strong> or higher.', 'woocommerce-product-bundles' ), $extension, $required_version );
				WC_PB_Admin_Notices::add_notice( $notice, 'warning' );
			}
		}
	}

	/**
	 * Rendering a PIP document?
	 *
	 * @since  5.5.0
	 *
	 * @param  string  $type
	 * @return boolean
	 */
	public function is_pip( $type = '' ) {
		return class_exists( 'WC_PB_PIP_Compatibility' ) && WC_PB_PIP_Compatibility::rendering_document( $type );
	}

	/**
	 * Tells if a product is a Name Your Price product, provided that the extension is installed.
	 *
	 * @param  mixed  $product_id
	 * @return boolean
	 */
	public function is_nyp( $product_id ) {

		if ( ! class_exists( 'WC_Name_Your_Price_Helpers' ) ) {
			return false;
		}

		if ( WC_Name_Your_Price_Helpers::is_nyp( $product_id ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Tells if a product is a subscription, provided that Subs is installed.
	 *
	 * @param  mixed  $product
	 * @return boolean
	 */
	public function is_subscription( $product ) {

		if ( ! class_exists( 'WC_Subscriptions' ) ) {
			return false;
		}

		return WC_Subscriptions_Product::is_subscription( $product );
	}

	/**
	 * Tells if an order item is a subscription, provided that Subs is installed.
	 *
	 * @param  mixed     $order
	 * @param  WC_Prder  $order
	 * @return boolean
	 */
	public function is_item_subscription( $order, $item ) {

		if ( ! class_exists( 'WC_Subscriptions_Order' ) ) {
			return false;
		}

		return WC_Subscriptions_Order::is_item_subscription( $order, $item );
	}

	/**
	 * Checks if a product has any required addons.
	 *
	 * @param  int  $product_id
	 * @return boolean
	 */
	public function has_required_addons( $product_id ) {

		if ( ! function_exists( 'get_product_addons' ) ) {
			return false;
		}

		$addons = get_product_addons( $product_id, false, false, true );

		if ( $addons && ! empty( $addons ) ) {
			foreach ( $addons as $addon ) {
				if ( '1' == $addon[ 'required' ] ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Alias to 'wc_cp_is_composited_cart_item'.
	 *
	 * @since  5.0.0
	 *
	 * @param  array  $item
	 * @return boolean
	 */
	public function is_composited_cart_item( $item ) {

		$is = false;

		if ( function_exists( 'wc_cp_is_composited_cart_item' ) ) {
			$is = wc_cp_is_composited_cart_item( $item );
		}

		return $is;
	}

	/**
	 * Alias to 'wc_cp_is_composited_order_item'.
	 *
	 * @since  5.0.0
	 *
	 * @param  array     $item
	 * @param  WC_Order  $order
	 * @return boolean
	 */
	public function is_composited_order_item( $item, $order ) {

		$is = false;

		if ( function_exists( 'wc_cp_is_composited_order_item' ) ) {
			$is = wc_cp_is_composited_order_item( $item, $order );
		}

		return $is;
	}
}

WC_PB_Compatibility::core_includes();
