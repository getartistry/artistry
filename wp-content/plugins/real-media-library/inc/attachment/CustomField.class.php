<?php
/**
 * This class handles all hooks for the custom field in a attachment.
 * 
 * @author MatthiasWeb
 * @package real-media-library\inc\attachment
 * @since 1.0
 * @singleton
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class RML_CustomField {
        private static $me = null;
        
        private function __construct() {
                
        }
        
        /**
         * When uploading a new item move it to the root.
         * 
         * @hooked add_attachment
         * @author MatthiasWeb
         * @since 1.0
         */
        public function add_attachment($attachment_ID) {
                update_post_meta($attachment_ID, "_rml_folder", -1);
        }
        
        /**
         * When editing a attachment show up a select
         * option to change the parent folder.
         * 
         * @hooked attachment_fields_to_edit
         * @author MatthiasWeb
         * @since 1.0
         */
        public function attachment_fields_to_edit($form_fields, $post) {
                $folderID = get_post_meta($post->ID, "_rml_folder", true);
                if ($folderID == "") {
                        $folderID = -1;
                }
                
                $form_fields['rml_dir'] = array(
                	'label' => __('Folder', RML_TD),
                	'input' => 'html',
                	'html'  => 
                	    '<div class="rml-folder-edit">' .
                	    RML_Structure::getInstance()->getHTMLBreadcrumbByID($folderID) . '
                	       <select name="attachments[' . $post->ID . '][rml_folder]">
        	                        ' . RML_Structure::getInstance()->optionsHTML($folderID) . '
                	       </select>
                	    </div>',
                );
                return $form_fields;
        }
        
        /**
         * When saving a attachment change the parent folder.
         * 
         * @hooked attachment_fields_to_save
         * @author MatthiasWeb
         * @since 1.0
         */
        public function attachment_fields_to_save($post, $attachment) {
                if( isset($attachment['rml_folder'])){
                        if (RML_Structure::getInstance()->getFolderByID($attachment['rml_folder']) === null) {
                                $attachment['rml_folder'] = -1;
                        }
                        update_post_meta($post['ID'], '_rml_folder', $attachment['rml_folder']);
                }
                
                return $post;
        }
        
        public static function getInstance() {
                if (self::$me == null) {
                        self::$me = new RML_CustomField();
                }
                return self::$me;
        }
}