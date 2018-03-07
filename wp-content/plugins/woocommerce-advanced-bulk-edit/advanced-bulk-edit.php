<?php   
/* 
Plugin Name: Woocommerce Advanced Bulk Edit
Plugin URI: https://wpmelon.com
Description: Edit your products both individually or in bulk
Author: George Iron
Version: 4.2.2
Author URI: https://wpmelon.com
Text Domain: woocommerce-advbulkedit
*/ 
 
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class W3ExAdvancedBulkEditMain {
	
	private static $ins = null;
	private static $idCounter = 0;
	public static $table_name = "";
	const PLUGIN_SLUG = 'advanced_bulk_edit';


    public static function init()
    {
//		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
		{//don't take resources if woocommerce is not running(not working on some installations, so beat it)
//			global $wpdb;
	        add_action('admin_menu', array(self::instance(), '_setup'));
			add_action('wp_ajax_wpmelon_adv_bulk_edit',  array(__CLASS__, 'ajax_request'));
 //add action to load my plugin files
 			add_action('plugins_loaded', array(self::instance(), '_load_translations'));
			
		}
		
    }
	public function _load_translations()
    {
		 load_plugin_textdomain('woocommerce-advbulkedit', false,  dirname(plugin_basename(__FILE__)) .'/languages');
	}
	
    public static function instance()
    {
        is_null(self::$ins) && self::$ins = new self;
        return self::$ins;
    }

    public function _setup()
    {
	   add_submenu_page(
			'edit.php?post_type=product', 
			'Advanced Bulk Edit', 
			'Advanced Bulk Edit', 
			'manage_woocommerce', 
			self::PLUGIN_SLUG, 
			array(self::instance(), 'showpage')
		);
	   add_action( 'admin_enqueue_scripts', array(self::instance(), 'admin_scripts') );
    }
	
	public static function ajax_request()
	{
		require_once(dirname(__FILE__).'/ajax_handler.php');
		// IMPORTANT: don't forget to "exit"
		die();
	}
	
	
    function admin_scripts($hook)
	{
		$ibegin = strpos($hook,'advanced_bulk_edit',0);
	 	if( $ibegin === FALSE)
			return;
		$purl = plugin_dir_url(__FILE__);
		
		$ver = '4.2.2';
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-datepicker');
		if(function_exists( 'wp_enqueue_media' )){
		    wp_enqueue_media();
		}else{
		    wp_enqueue_style('thickbox');
		    wp_enqueue_script('media-upload');
		    wp_enqueue_script('thickbox');
		}
		
		wp_enqueue_style('w3exabe-slicjgrid',$purl.'css/slick.grid.css',false, $ver, 'all' );
  		wp_enqueue_style('w3exabe-jqueryui',$purl.'css/smoothness/jquery-ui-1.8.16.custom.css',false, $ver, 'all' );
		wp_enqueue_style('w3exabe-main',$purl.'css/main.css',false, $ver, 'all' );
		wp_enqueue_style('w3exabe-chosencss',$purl.'chosen/chosen.min.css',false, $ver, 'all' );
  	    wp_enqueue_style('w3exabe-colpicker',$purl.'controls/slick.columnpicker.css',false, $ver, 'all' );
		
		
	    wp_enqueue_script('w3exabe-sjdrag',$purl.'lib/jquery.event.drag-2.2.js', array(), $ver, true );

		wp_enqueue_script('w3exabe-score',$purl.'js/slick.core.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-schecks',$purl.'plugins/slick.checkboxselectcolumn.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-sautot',$purl.'plugins/slick.autotooltips.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-scellrd',$purl.'plugins/slick.cellrangedecorator.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-sranges',$purl.'plugins/slick.cellrangeselector.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-scopym',$purl.'plugins/slick.cellcopymanager.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-scells',$purl.'plugins/slick.cellselectionmodel.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-srowsel',$purl.'plugins/slick.rowselectionmodel.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-scolpicker',$purl.'controls/slick.columnpicker.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-sfor',$purl.'js/slick.formatters.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-seditor',$purl.'js/slick.editors.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-slgrid',$purl.'js/slick.grid.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-chosen',$purl.'chosen/chosen.jquery.min.js', array(), $ver, true );
		wp_enqueue_script('w3exabe-adminjs',$purl.'js/admin.js', array(), $ver, true );
		wp_localize_script('w3exabe-adminjs', 'W3ExABE', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'w3ex-advbedit-nonce' ),
			)
		);
		
	}
	
	public function showpage()
    {
        require_once(dirname(__FILE__).'/bulkedit.php');
    }
}

W3ExAdvancedBulkEditMain::init();
