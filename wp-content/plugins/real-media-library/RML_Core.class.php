<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Singleton Core Class
 * 
 * Handles all actions and filter. It includes all available
 * Classes that handle the callbacks.
 */
class RML_Core {
    
    private static $me = null;

    private function __construct() {
        
    }
    
    /**
     * Include all necessery files and classes
     */
    public function include_all() {
        $pathes = array(
            "inc/attachment/Folder.class.php",
            "inc/attachment/Structure.class.php",
            "inc/attachment/CustomField.class.php",
            "inc/attachment/Filter.class.php",
            "inc/WP_Query_Count.class.php",
            "inc/Backend.class.php",
            "inc/Ajax.class.php",
            "inc/install.php"
            );
        
        for ($i = 0; $i < count($pathes); $i++) {
            require_once(RML_PATH . '/' . $pathes[$i]);
        }
    }
    
    /**
     * Starts filters and actions
     */
    public function paging() {
        // Register actions
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'update_db_check') );
        add_action('admin_enqueue_scripts', array(RML_Backend::getInstance(), 'admin_enqueue_scripts') );
        add_action('admin_footer', array(RML_Backend::getInstance(), 'admin_footer'));
        add_action('pre_get_posts', array(RML_Filter::getInstance(), 'pre_get_posts'), 10);
        add_action('admin_head', array(RML_Filter::getInstance(), 'admin_head'));
        add_action('wp_ajax_bulk_move', array(RML_Ajax::getInstance(), 'wp_ajax_bulk_move'));
        add_action('wp_ajax_bulk_sort', array(RML_Ajax::getInstance(), 'wp_ajax_bulk_sort'));
        add_action('wp_ajax_folder_create', array(RML_Ajax::getInstance(), 'wp_ajax_folder_create'));
        add_action('wp_ajax_folder_delete', array(RML_Ajax::getInstance(), 'wp_ajax_folder_delete'));
        add_action('wp_ajax_folder_rename', array(RML_Ajax::getInstance(), 'wp_ajax_folder_rename'));
        
        // Filters
        add_filter('attachment_fields_to_edit', array(RML_CustomField::getInstance(), 'attachment_fields_to_edit'), 10, 2);
        add_filter('attachment_fields_to_save', array(RML_CustomField::getInstance(), 'attachment_fields_to_save'), 10 , 2);
        //add_filter('admin_body_class', array(RML_Backend::getInstance(), 'admin_body_class')); // Added Javascript Solution
        add_filter('restrict_manage_posts', array(RML_Filter::getInstance(), 'restrict_manage_posts'));
        add_filter('media_buttons_context', array(RML_Filter::getInstance(), 'add_media_button') );
        add_filter('shortcode_atts_gallery', array(RML_Backend::getInstance(), 'shortcode_atts_gallery'), 10, 3 );
        
        // Others
        register_activation_hook( RML_FILE, 'rml_install' );
        register_uninstall_hook( RML_PATH . '/inc/uninstall.php', 'rml_uninstall' );
    }
    
    public function update_db_check() {
        $installed = get_site_option( 'rml_db_version' );
        if ($installed != RML_VERSION) {
            rml_install();
        }
    }
    
    public function init() {
        global $shortcode_tags;
        add_shortcode("folder-gallery", $shortcode_tags['gallery']);
        add_thickbox();
    }
    
    public function getTableName() {
        global $wpdb;
        return $wpdb->prefix . 'realmedialibrary';
    }
    
    /**
     * Starts the plugin settings
     */
    public static function start() {
        
        $instance = self::getInstance();
        $instance->include_all();
        $instance->paging();
        
    }
    
    public static function print_r($row) {
        echo '<pre>';
        print_r($row);
        echo '</pre>';
    }
    
    public static function getInstance() {
        if (self::$me == null) {
            self::$me = new RML_Core();
        }
        return self::$me;
    }
    
    public static function get_object_vars_from_public($obj) {
        return get_object_vars($obj);
    }
    
}