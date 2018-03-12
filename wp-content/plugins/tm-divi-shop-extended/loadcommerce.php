<?php
/*
Plugin Name: Divi Shop Extended
Plugin URI: https://www.tantomarketing.com/
Description: Divi Shop Extended allows you to customise your E-Commerce using Divi Theme as well as Woocommerce. It includes a shop panel, a custom Shop Module and 15+ Custom Woocommerce modules for Divi Builder with which you can create any product layout.	
Author: TantoMarketing
Version: 1.0.9
Author URI: https://www.tantomarketing.com/
*/

/* Plugin license*/
	define('WOO_SLT_PATH',   plugin_dir_path(__FILE__));
    define('WOO_SLT_URL',    plugins_url('', __FILE__));
    define('WOO_SLT_APP_API_URL',      'http://www.tantomarketing.com');
    
    define('WOO_SLT_VERSION', '1.0.9');
    define('WOO_SLT_DB_VERSION', '1.2'); 
    
    define('WOO_SLT_PRODUCT_ID',           'DIVI_SHOP_PLU');
    define('WOO_SLT_INSTANCE',             str_replace(array ("https://" , "http://"), "", network_site_url()));
   
    include_once(WOO_SLT_PATH . '/inc/class.wooslt.php');
    include_once(WOO_SLT_PATH . '/inc/class.licence.php');
    include_once(WOO_SLT_PATH . '/inc/class.options.php');
    include_once(WOO_SLT_PATH . '/inc/class.updater.php');
    
    global $WOO_SLT;
    $WOO_SLT = new WOO_SLT();

/* Plugin Activation*/
	register_activation_hook(__FILE__,'tm_activation');
	function tm_activation(){
		global $wp_version;
		$divi_path = get_theme_root().'/Divi';
		
		$tm_wp_low_exit_msg = 'This plugin requires WordPress version 4.4 or higher.
		<a href="http://codex.wordpress.org/Upgrading_WordPress">Please update your WordPress version!</a>';
		$tm_wp_high_exit_msg = 'This plugin has not been tested with your version of WordPress, some functions maybe doesn\'t works';
		$tm_no_divi_msg = 'This plugin requires Divi theme to works, please install Divi theme before activate this plugin';
		$tm_no_woocommerce_msg = 'This plugin requires the Woocommerce plugin to works, please install Woocommerce plugin before activate this plugin';


		if(version_compare($wp_version, '4.8', '<')) wp_die($tm_wp_low_exit_msg);
		if(version_compare($wp_version, '5.2', '>')) wp_die($tm_wp_high_exit_msg);
		if ( !(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) ) wp_die($tm_no_woocommerce_msg);
		if(!file_exists($divi_path)) wp_die($tm_no_divi_msg);
	}


/* Plugin Deactivation */
	register_deactivation_hook(__FILE__,'tm_deactivation');
	function tm_deactivation(){
		
	}

/* Main Plugin Code */
	
	// Require functions
	define( 'TM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); // With trailing slash
	require_once(TM_PLUGIN_DIR.'/inc/functions.php');
	require_once(TM_PLUGIN_DIR.'/inc/utility.php');
	require_once(TM_PLUGIN_DIR.'/inc/image-flipper.php');
	require_once(TM_PLUGIN_DIR.'/inc/product-builder.php');

	// Constants definition
	define( 'TM_DIVI_PATH', get_theme_root().'/Divi/'); // With trailing slash
	define( 'TM_PLUGIN_URI', plugins_url('',__FILE__));
	define( 'TM_DIVI_VERSION', tm_get_theme_ver("Divi"));
	define( 'TM_SP_TEMPLATE_PATH', 'woocommerce/single-product.php');
	define( 'TM_SP_TEMPLATE_NAME', 'single-product.php');

	//Other options
	$et_shop_other = get_option('et_shop_other');

	function template_sp_activate() {

	   $plugin_dir = TM_PLUGIN_DIR . 'woocommerce/single-product.php';
	   $theme_dir = get_stylesheet_directory() . '/single-product.php';

	   if( !file_exists($theme_dir) ){
	   		copy($plugin_dir, $theme_dir);
	   	}
	}
	

	function template_sp_deactivate(){
		$plugin_dir = TM_PLUGIN_DIR . 'woocommerce/single-product.php';
		$theme_dir = get_stylesheet_directory() . '/single-product.php';

		if( file_exists($theme_dir) ){
			unlink($theme_dir);
		}
	
	}

	// Dinamic CSS
	add_action( 'wp_head', 'tm_custom_css' );
	
	// Admin scripts enqueue
	add_action( 'admin_enqueue_scripts', 'tm_admin_enqueue_scripts' );
	add_action('admin_enqueue_scripts', 'tm_admin_enqueue_styles');
	// User scripts enqueue
	add_action( 'wp_enqueue_scripts', 'tm_user_enqueue_scripts' );
	add_action('wp_enqueue_scripts', 'tm_user_enqueue_styles');
	
	// Modules Tmcommerce
	function tantomarketing() {
		wp_enqueue_style('Tmcommerce', plugin_dir_url( __FILE__ ) . 'css/style.css');
	}
	add_action('admin_enqueue_scripts', 'tantomarketing');

	function TM_Extended_Custom_Modules(){
		if(class_exists("ET_Builder_Module")){
			include(TM_PLUGIN_DIR."/modules/tmbutton_add_to_cart.php");
			include(TM_PLUGIN_DIR."/modules/tmprice.php");
			include(TM_PLUGIN_DIR."/modules/tmtitle.php");
			include(TM_PLUGIN_DIR."/modules/tmimage.php");
			include(TM_PLUGIN_DIR."/modules/tmmeta.php");
			include(TM_PLUGIN_DIR."/modules/tmreview.php");
			include(TM_PLUGIN_DIR."/modules/tmshortdescription.php");
			include(TM_PLUGIN_DIR."/modules/tmrelatedproduct.php");
			include(TM_PLUGIN_DIR."/modules/tmup-sell.php");
			include(TM_PLUGIN_DIR."/modules/tmbreadcrumb.php");
			include(TM_PLUGIN_DIR."/modules/tmrating.php");
			include(TM_PLUGIN_DIR."/modules/tmnotice.php");
			include(TM_PLUGIN_DIR."/modules/tmsumary.php");
			include(TM_PLUGIN_DIR."/modules/tminfo_adicional.php");
			include(TM_PLUGIN_DIR."/modules/tmgallery-products.php");
			include(TM_PLUGIN_DIR."/modules/tm-module-shop/tm-module-shop.php");
		}
	}
	
	function TM_Extended_Custom_Modules_Shop(){
		if(class_exists("ET_Builder_Module")){
			include(TM_PLUGIN_DIR."/modules/tm-module-shop/tm-module-shop.php");
		}
	}
	function Prep_TM_EXT_Custom_Modules($only_shop=false){
		global $pagenow;

		$is_admin = is_admin();
		$action_hook = $is_admin ? 'wp_loaded' : 'wp';
		$required_admin_pages = array( 'edit.php', 'post.php', 'post-new.php', 'admin.php', 'customize.php', 'edit-tags.php', 'admin-ajax.php', 'export.php' ); // list of admin pages where we need to load builder files
		$specific_filter_pages = array( 'edit.php', 'admin.php', 'edit-tags.php' );
		$is_edit_library_page = 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'et_pb_layout' === $_GET['post_type'];
		$is_role_editor_page = 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'et_divi_role_editor' === $_GET['page'];
		$is_import_page = 'admin.php' === $pagenow && isset( $_GET['import'] ) && 'wordpress' === $_GET['import']; 
		$is_edit_layout_category_page = 'edit-tags.php' === $pagenow && isset( $_GET['taxonomy'] ) && 'layout_category' === $_GET['taxonomy'];

		if ( ! $is_admin || ( $is_admin && in_array( $pagenow, $required_admin_pages ) && ( ! in_array( $pagenow, $specific_filter_pages ) || $is_edit_library_page || $is_role_editor_page || $is_edit_layout_category_page || $is_import_page ) ) ) {
			if(!$only_shop){
				add_action($action_hook, 'TM_Extended_Custom_Modules', 9789);
			}else{
				add_action($action_hook, 'TM_Extended_Custom_Modules_Shop', 9789);
			}
		}
	}

	// Delete woocommerce default zoom img efects 
	add_action( 'after_setup_theme', function() {
			remove_theme_support( 'wc-product-gallery-zoom' );
		}, 20 
	);
	// Load zoom libraries for simple product image
	add_action('wp_enqueue_scripts', 'tm_image_zoom');

	if( $et_shop_other['custom_woo_templates'] !== 'off' ){
		Prep_TM_EXT_Custom_Modules();
		template_sp_activate();
		
		// Woocommerce override template fix 
		require_once(TM_PLUGIN_DIR.'/inc/woocommerce-template-fix.php');

		// Enables zoom effect
		if($et_shop_other['product_img_hover'] ==='Zoom'){
			add_action('wp_enqueue_scripts', 'tm_single_product_img_zoom');
		}

		//Image flipper effect for products with 2 or more images
		if($et_shop_other['product_img_hover']==='Flip') tm_image_flipper();
		
	}else{
		Prep_TM_EXT_Custom_Modules(true);
		template_sp_deactivate();
		add_action('wp_enqueue_scripts', 'tm_single_product_img_zoom'); // Zoom on woocommerce default template
	}
	
	// Quick view main file include 
	$quick_view_path = dirname(__FILE__) . '/quick-view.php';
	include_once($quick_view_path);

	// Epanel view main file include
	$epanel_path = dirname(__FILE__) . '/epanel/epanel.php';
	include_once($epanel_path);

	/*// Mini cart main file include
	$mini_cart_path = dirname(__FILE__) . '/inc/mini-cart.php';
	include_once($mini_cart_path);

	// Nav-search main file include
	$nav_search_path = dirname(__FILE__) . '/inc/nav-search.php';
	include_once($nav_search_path);
	*/
	
	// Plugin Uninstall 
	register_uninstall_hook(__FILE__,'tm_uninstall');
	function tm_uninstall(){
		template_sp_deactivate();
	}
	
	
?>