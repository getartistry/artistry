<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class RML_Ajax {
	private static $me = null;
        
        private function __construct() {
                
        }
        
        public function wp_ajax_bulk_move() {
                $ids = isset($_POST["ids"]) ? $_POST["ids"] : null;
                $to = isset($_POST["to"]) ? $_POST["to"] : null;
                if (!is_array($ids) && count($ids) == 0 && $to !== null) {
                        wp_die();
                }
                
                $folder = RML_Structure::getInstance()->getFolderById($to);
                if ($folder !== null) {
                        $folder->moveItemsHere($ids);
                }else{
                        if (is_array($ids) && count($ids) > 0) {
                            foreach ($ids as $value) {
                                update_post_meta($value, "_rml_folder", -1);
                            }
                        }
                }
                
                wp_die();
        }
        
        public function wp_ajax_bulk_sort() {
                $ids = isset($_POST["ids"]) ? $_POST["ids"] : null;
                if (!is_array($ids) && count($ids) == 0 && $to !== null) {
                        wp_die();
                }
                
                // fid (folderid): pid: (parentid)
                $struct = RML_Structure::getInstance();
                $i = 0;
                foreach ($ids as $value) {
                        $fid = $value["fid"]; // Folder ID
                        $pid = $value["pid"]; // Parent ID
                        
                        // Check
                        if (!is_numeric($fid) || !is_numeric($pid)) {
                                continue;
                        }
                        
                        // Execute
                        $fid = $struct->getFolderById($fid);
                        if ($fid !== null) {
                                $fid->setParent($pid, $i);
                        }
                        
                        $i++;
                }
                
                wp_die();
        }
        
        public function wp_ajax_folder_create() {
                $name = isset($_POST["name"]) ? $_POST["name"] : "";
                $parent = isset($_POST["parent"]) ? $_POST["parent"] : -1;
                
                if (RML_Structure::getInstance()->createFolder($name, $parent)) {
                        wp_send_json_success();
                }else{
                        wp_send_json_error("Please use a valid folder name.");
                }
        }
        
        public function wp_ajax_folder_rename() {
                $name = isset($_POST["name"]) ? $_POST["name"] : "";
                $id = isset($_POST["id"]) ? $_POST["id"] : -1;
                
                if (RML_Structure::getInstance()->renameFolder($name, $id)) {
                        wp_send_json_success();
                }else{
                        wp_send_json_error("Please use a valid folder name.");
                }
        }
        
        public function wp_ajax_folder_delete() {
                $id = isset($_POST["id"]) ? $_POST["id"] : -1;
                
                if (RML_Structure::getInstance()->deleteFolder($id)) {
                        wp_send_json_success();
                }else{
                        wp_send_json_error();
                }
        }
        
        public static function getInstance() {
                if (self::$me == null) {
                        self::$me = new RML_Ajax();
                }
                return self::$me;
        }
}

?>