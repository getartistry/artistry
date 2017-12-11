<?php
/**
 * This class handles all hooks and functions for the structur.
 * 
 * @author MatthiasWeb
 * @package real-media-library\inc\attachment
 * @since 1.0
 * @singleton
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class RML_Structure {
    
    private static $me = null;
    
    private $rows;
    private $parsed;
    private $tree;
    private $cntAttachments;

    /**
     * C'tor
     * When starting the structure by singleton getInstance()
     * then fetch all folders with their parents.
     * 
     * @author MatthiasWeb
     * @since 1.0
     */
    public function __construct() {
        $this->rows = array();
        $this->parsed = array();
        $this->tree = array();
        $this->cntAttachments = wp_count_posts('attachment')->inherit;
        
        $this->fetch();
    }
    
    /**
     * Fetching all available folders into an array.
     * 
     * @author MatthiasWeb
     * @since 1.0
     */
    private function fetch() {
        global $wpdb;
        
        $table_name = RML_Core::getInstance()->getTableName();
        $where = "";
        if (is_multisite()) {
            $blog_id = get_current_blog_id();
            $where = " WHERE bid=$blog_id ";
        }
        
        /**
         * fixed count bug when WPML in usage
         * 
         * @since 2.1.2
         * 
        $this->rows = $wpdb->get_results("
            SELECT tn.*, (
                 SELECT COUNT(*) FROM " . $wpdb->prefix . "postmeta AS pm
                 WHERE pm.meta_key = '_rml_folder'
                 AND pm.meta_value = tn.id
            ) AS cnt 
            FROM $table_name AS tn
            $where
            ORDER BY parent, ord
        ");
         */
        
        $this->rows = $wpdb->get_results("
            SELECT tn.*
            FROM $table_name AS tn
            $where
            ORDER BY parent, ord
        ");
        
        $this->parse();
    }
    
    /**
     * This functions parses the readed rows into folder objects.
     * 
     * @author MatthiasWeb
     * @since 1.0
     */
    private function parse() {
        if (!empty($this->rows)) {
            foreach ($this->rows as $key => $value) {
                $this->parsed[] = new RML_Folder($value->id, $value->parent, $value->name, $value->ord);
            }
        }
        
        // Create the tree
        $folder = null;
        foreach($this->parsed as $key => $category){
            $parent = $category->parent;
            if ($parent > -1) {
                $folder = $this->getFolderByID($parent);
                if ($folder !== null) {
                    $folder->children[] = $category;
                }
            }
        }
        
        $cats_tree = array();
        foreach ($this->parsed as $category) {
            if ($category->parent <= -1) {
                $cats_tree[] = $category;
            }
        }
        $this->tree = $cats_tree;
    }
    
    /**
     * Creates a folder.
     * 
     * @param name String Name of the folder
     * @param parent int ID of the parent (-1 for root)
     * @return boolean
     */
    public function createFolder($name, $parent) {
        global $wpdb;
	
	    if ($this->isFolder($parent) && strpbrk($name, "\\/?%*:|\"<>") === FALSE) {
        	$table_name = RML_Core::getInstance()->getTableName();
        	$wpdb->insert( 
        		$table_name,
        		array( 
        			'parent' => $parent, 
        			'name' => $name,
        			'bid' => get_current_blog_id()
        		) 
        	);
        	return true;
	    }else{
	        return false;
	    }
    }
    
    /**
     * Deletes a specific folder.
     * 
     * @param id int ID of the folder
     * @return boolean
     */
    public function deleteFolder($id) {
        if ($this->isFolder($id) && $id > 0) {
            // Delete files in this folder
            $query = new WP_Query(array(
            	'post_status' => 'inherit',
            	'post_type' => 'attachment',
            	'meta_query' => array(
            	    array(
            	        'key' => '_rml_folder',
            	        'value' => $id,
            	        'compare' => '='
        	        ))
            ));
            $posts = $query->get_posts();
            foreach ($posts as $post) {
                wp_delete_attachment($post->ID);
            }
            
            // Delete folder
            global $wpdb;
            $table_name = RML_Core::getInstance()->getTableName();
            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %d", $id));
            
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Renames a folder
     * 
     * @param name String New name of the folder
     * @param id int ID of the folder
     * @return boolean
     */
    public function renameFolder($name, $id) {
        if ($this->isFolder($id) && strpbrk($name, "\\/?%*:|\"<>") === FALSE && $id > 0) {
            global $wpdb;
            $table_name = RML_Core::getInstance()->getTableName();
            $wpdb->query($wpdb->prepare("UPDATE $table_name SET name=%s WHERE id = %d", $name, $id));
            
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Gets a HTML formatted string for <option>
     * 
     * @recursive
     */
    public function optionsHTML($selected = -1, $tree = null, $slashed = "", $spaces = "--", $useAll = true) {
        $return = '';
        if ($tree == null) {
            $tree = $this->tree;
            if ($useAll) {
                $return .= '<option value="" ' . $this->optionsSelected($selected, "") . '>' . __('All', RML_TD) . '</option>';
            }
            $return .= '<option value="-1" ' . $this->optionsSelected($selected, "-1") . '
                                data-slug="/"
                                data-id="-1">' . __('Root', RML_TD) . '</option>';
        }
        
        if(!is_null($tree) && count($tree) > 0) {
            foreach($tree as $parent) {
                $return .= '<option value="' . $parent->id . '" ' . $this->optionsSelected($selected, $parent->id) . '
                                    data-slug="/' . $parent->slugParents() . '"
                                    data-id="' . $parent->id . '">
                                    ' . $spaces . '&nbsp;' . $parent->name . '
                            </option>';#
                
                if (isset($parent->children) &&
                    is_array($parent->children) &&
                    count($parent->children) > 0
                    ) {
                    //$return .= $this->treeHTML($selected, $parent->children);
                    $return .= $this->optionsHTML($selected, $parent->children, $slashed, $spaces . "--");
                }
            }
        }
        
        return $return;
    }
    
    /**
     * Gets the html string for the left tree.
     * 
     * @recursive
     */
    public function treeHTML($selected = -1, $tree = null) {
        $return = '';
        
        // First item
        if ($tree == null) {
            $tree = $this->tree;
            $return .= '<a href="' . $this->treeHref(-1) . '"
                            ' . $this->treeActive($selected, -1) . '
                            data-slug="/"
                            data-id="-1">
                            
                                <i class="fa fa-dot-circle-o"></i>/
                                <span class="rml-cnt-' . $this->getCntRoot() . '">' . $this->getCntRoot() . '</span>
                        </a>';
        }
        
        // Create list
        $return .= '<ul>';
        if(!is_null($tree) && count($tree) > 0) {
            foreach($tree as $parent) {
                $return .= '
                <li id="list_' . $parent->id . '">
                    <a href="' . $this->treeHref($parent->id) . '"
                        ' . $this->treeActive($selected, $parent->id) . '
                        data-slug="/' . $parent->slugParents() . '"
                        data-id="' . $parent->id . '">
                        
                        <i class="fa fa-folder-open"></i><i class="fa fa-folder"></i> ' . $parent->name . '
                        <span class="rml-cnt-' .  $parent->cnt . '">' . $parent->cnt . '</span>
                    </a>
                ';
                
                if (isset($parent->children) &&
                    is_array($parent->children) &&
                    count($parent->children) > 0
                    ) {
                    $return .= $this->treeHTML($selected, $parent->children);
                }else{
                    $return .= '<ul></ul>';
                }
                
                $return .= '</li>';
            }
        }
        $return .= '</ul>';
        
        return $return;
    }
    
    /**
     * Get array for the javascript backbone view.
     */
    public function namesSlugArray($tree = null, $spaces = "--") {
        $return = array(
            "names" => array(),
            "slugs" => array()
        );
        
        if ($tree == null) {
            $tree = $this->tree;
            $return["names"][] = "Root";
            $return["slugs"][] = -1;
        }
        
        if(!is_null($tree) && count($tree) > 0) {
            foreach($tree as $parent) {
                $return["names"][] = $spaces . ' ' . $parent->name;
                $return["slugs"][] = $parent->id;
                
                if (isset($parent->children) &&
                    is_array($parent->children) &&
                    count($parent->children) > 0
                    ) {
                    $append = $this->namesSlugArray($parent->children, $spaces . "--");
                    $return["names"] = array_merge($return["names"], $append["names"]);
                    $return["slugs"] = array_merge($return["slugs"], $append["slugs"]);
                }
            }
        }
        
        return $return;
    }
    
    public function treeHref($id) {
        $query = $_GET;
        $query['rml_folder'] = $id;
        $query_result = http_build_query($query);
        
        return admin_url('upload.php?' . $query_result);
    }
    
    public function treeActive($selected, $value) {
        if ($selected == $value) {
            return 'class="active"';
        }else{
            return '';
        }
    }
    
    public function optionsSelected($selected, $value) {
        if ($selected == $value) {
            return 'selected="selected"';
        }else{
            return '';
        }
    }
    
    public function isFolder($id) {
        if ($id == -1) {
            return true; // is root directory
        }
        
        return $this->getFolderByID($id) != null;
    }
    
    public function getFolderByID($id) {
        foreach ($this->parsed as $folder) {
            if ($folder->id == $id) {
                return $folder;
            }
        }
        return null;
    }
    
    public function getBreadcrumbByID($id) {
        $folder = $this->getFolderByID($id);
        if ($folder === null) {
            return null;
        }
        
        $return = array($folder);
        
        while (true) {
            if ($folder->parent > 0) {
                $folder = $this->getFolderByID($folder->parent);
                if ($folder === null) {
                    return null;
                }else{
                    $return[] = $folder;
                }
            }else{
                break;
            }
        }
        
        return array_reverse($return);
    }
    
    public function getHTMLBreadcrumbByID($id) {
        $breadcrumb = $this->getBreadcrumbByID($id);

        $output = '<i class="fa fa-folder-open"></i>';
        
        if (count($breadcrumb) == 0) {
            return $output . ' ' . __('Root', RML_TD);
        }
        
        for ($i = 0; $i < count($breadcrumb); $i++) {
            $output .= '<span class="folder">' . $breadcrumb[$i]->name . '</span>';
            
            // When not last, insert seperator
            if ($i < count($breadcrumb) - 1) {
                $output .= '<i class="fa fa-chevron-right"></i>';
            }
        }
        
        return $output;
    }
    
    public function getRows() {
        return $this->rows;
    }
    
    public function getParsed() {
        return $this->parsed;
    }
    
    public function getTree() {
        return $this->tree;
    }
    
    public function getCntAttachments() {
        return $this->cntAttachments;
    }
    
    public function getCntRoot() {
        $cnt = 0;
        foreach ($this->parsed as $folder) {
            $cnt += $folder->cnt;
        }
        return $this->getCntAttachments() - $cnt;
    }
    
    public static function getInstance() {
        if (self::$me == null) {
            self::$me = new RML_Structure();
        }
        return self::$me;
    }
    
    /* @deprecated
    public function tree($selected = -1, $tree = null, $root = null, $slashed = "") {
        $return = '';
        
        if ($tree == null) {
            $tree = $this->parsed;
            $root = -1;
            $return .= '<a href="' . $this->treeHref($slashed, -1) . '"
                            ' . $this->treeActive($selected, -1) . '
                            data-slug="/"
                            data-id="-1">
                            
                                <i class="fa fa-dot-circle-o"></i>/
                                <span class="rml-cnt-' . $this->getCntRoot() . '">' . $this->getCntRoot() . '</span>
                        </a>';
        }
        
        $return .= '<ul>';
        if(!is_null($tree) && count($tree) > 0) {
            foreach($tree as $parent) {
                $child = $parent->id;

                if($parent->parent == $root) {                    
                    unset($tree[$child]);
                    $slashed .= $parent->slug() . '/';
                    $return .= '<li id="list_' . $parent->id . '">
                        <a href="' . $this->treeHref($slashed, $parent->id) . '"
                            ' . $this->treeActive($selected, $parent->id) . '
                            data-slug="/' . $parent->slugParents() . '"
                            data-id="' . $parent->id . '">
                            
                            <i class="fa fa-folder-open"></i><i class="fa fa-folder"></i> ' . $parent->name . '
                            <span class="rml-cnt-' .  $parent->cnt . '">' . $parent->cnt . '</span>
                        </a>
                        ';
                    $return .= $this->tree($selected, $tree, $child, $slashed);
                    $return .= '</li>';
                }
            }
        }
        $return .= '</ul>';
        
        return $return;
    }
    */
    
    /* @deprecated
    public function options($selected = -1, $tree = null, $root = null, $slashed = "", $spaces = "--", $useAll = true) {
        $return = '';
        if ($tree == null) {
            $tree = $this->parsed;
            $root = -1;
            if ($useAll) {
                $return .= '<option value="" ' . $this->optionsSelected($selected, "") . '>' . __('All', RML_TD) . '</option>';
            }
            $return .= '<option value="-1" ' . $this->optionsSelected($selected, "-1") . ' data-slug="/" data-id="-1">' . __('Root', RML_TD) . '</option>';
        }
        
        if(!is_null($tree) && count($tree) > 0) {
            foreach($tree as $parent) {
                $child = $parent->id;
                if($parent->parent == $root) {                    
                    unset($tree[$child]);
                    $slashed .= $parent->slug() . '/';
                    $return .= '<option value="' . $parent->id . '" ' . $this->optionsSelected($selected, $parent->id) . ' data-slug="' . $slashed . '" data-id="' . $parent->id . '">' . $spaces . '&nbsp;' . $parent->name . '</option>';
                    $return .= $this->options($selected, $tree, $child, $slashed, $spaces . "--");
                }
            }
        }
        
        return $return;
    }
    */
    
}

?>