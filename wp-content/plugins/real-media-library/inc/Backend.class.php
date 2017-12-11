<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class RML_Backend {
	private static $me = null;
        
        private function __construct() {
                
        }
        
        public function shortcode_atts_gallery($out, $pairs, $atts) {
                $atts = shortcode_atts( array(
                        'fid' => -2
                 ), $atts );
                
                if ($atts["fid"] > -2) {
                        if ($atts["fid"] > -1) {
                                $folder = RML_Structure::getInstance()->getFolderByID($atts["fid"]);
                                if ($folder != null) {
                                        $out["include"] .= ',' . implode(',', $folder->fetchFileIds());
                                }
                        }else{
                                $out["include"] .= ',' . implode(',', RML_Folder::sFetchFileIds(-1));
                        }
                        $out["include"] = ltrim($out["include"], ',');
                        $out["include"] = rtrim($out["include"], ',');
                }
                
                return $out;
        }
        
        public function admin_enqueue_scripts($hook) {
        	// Scripts
        	wp_enqueue_script('jquery');
        	// Deprecated => Included in main.js
        	//wp_enqueue_script('rml-jquery-ui', 'https://code.jquery.com/ui/1.10.4/jquery-ui.min.js', array('jquery'));
        	//wp_enqueue_script('touch-punch', plugins_url('assets/js/jquery.ui.touchpunch.min.js', RML_FILE), array('rml-jquery-ui'));
        	//wp_enqueue_script('nestedSortable', plugins_url( 'assets/js/jquery.ui.nestedSortable.js', RML_FILE ), array('touch-punch'));
        	
		wp_enqueue_script('rml-main-script', plugins_url( 'assets/js/main.js', RML_FILE ), array('jquery'), RML_VERSION);
		
		// Styles
		wp_enqueue_style('font-awesome',  'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
		wp_enqueue_style('rml-main-style',  plugins_url( 'assets/css/main.css', RML_FILE ), RML_VERSION);
        }
        
        public function admin_footer() {
                $pathes = array(
                    "inc/admin_footer/sidebar.dummy.php",
                    );
                
                for ($i = 0; $i < count($pathes); $i++) {
                    require_once(RML_PATH . '/' . $pathes[$i]);
                }
                
                // Render thickbox create folder gallery
                RML_Filter::getInstance()->add_media_display();
        }
        
        /**
         * @deprecated
         */
        public function admin_body_class($classes) {
                $mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';	
        	$classes .= ' upload-php-mode-' . $mode . ' ';
                return $classes;
        }
        
        public static function getInstance() {
                if (self::$me == null) {
                        self::$me = new RML_Backend();
                }
                return self::$me;
        }
}

?>