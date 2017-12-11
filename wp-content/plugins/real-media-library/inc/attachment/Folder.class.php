<?php
/**
 * This class creates a folder object.
 * 
 * @author MatthiasWeb
 * @package real-media-library\inc\attachment
 * @since 1.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class RML_Folder {
    
    public $id;
    public $parent;
    public $name;
    public $cnt;
    public $order;
    public $children;

    public function __construct($id, $parent, $name, $order = 999) {
        $this->id = $id;
        $this->parent = $parent;
        $this->name = $name;
        /*$this->cnt = $cnt;
        $this->cnt = 1;*/
        
        // fixed count bug when WPML in usage
        // @since 2.1.2
        $query = new RML_WP_Query_Count(array(
        	'post_status' => 'inherit',
        	'post_type' => 'attachment',
        	'meta_query' => array(array('key' => '_rml_folder', 'value' => $this->id, 'compare' => '='))
        ));
        if (isset($query->posts[0])) {
            $this->cnt = $query->posts[0];
        }else{
            $this->cnt = 0;
        }
        
        
        $this->order = $order;
        $this->children = array();
    }
    
    /**
     * Move several items to this folder.
     * 
     * @param $ids array of post ids
     * @author MatthiasWeb
     * @package real-media-library\inc\attachment
     * @since 1.0
     */
    public function moveItemsHere($ids) {
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $value) {
                update_post_meta($value, "_rml_folder", $this->id);
            }
        }
    }
    
    /**
     * Fetch all attachment ids currently in this folder.
     * 
     * @return array of post ids
     * @author MatthiasWeb
     * @package real-media-library\inc\attachment
     * @since 1.0
     */
    public function fetchFileIds() {
        return self::sFetchFileIds($this->id);
    }
    
    public static function sFetchFileIds($id) {
        $query = new WP_Query(array(
        	'post_status' => 'inherit',
        	'post_type' => 'attachment',
        	'posts_per_page' => -1,
        	/*'meta_query' => array(
        	    array(
        	        'key' => '_rml_folder',
        	        'value' => $id,
        	        'compare' => '='
	            )),*/
	        'rml_folder' => $id,
	        'fields' => 'ids'
        ));
        $posts = $query->get_posts();
        return $posts;
    }
    
    public function slug() {
        return sanitize_title($this->name, "", "folder");
    }
    
    public function slugParents() {
        $return = array($this->slug());
        $folder = $this;
        while (true) {
            $f = RML_Structure::getInstance()->getFolderByID($folder->parent);
            if ($f !== null) {
                $folder = $f;
                $return[] = $folder->slug();
            }else{
                break;
            }
        }
        return implode("/", array_reverse($return));
    }
    
    public function setParent($id, $ord = 99) {
        if (RML_Structure::getInstance()->isFolder($id)) {
            $this->parent = $id;
            
            global $wpdb;
            $table_name = RML_Core::getInstance()->getTableName();
            $wpdb->query($wpdb->prepare("UPDATE $table_name SET parent=%d, ord=%d WHERE id = %d", $id, $ord, $this->id));
        }
    }
}

?>