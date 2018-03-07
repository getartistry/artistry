<?php

/**
 * Includes the user groups functionality - registering the group taxonomy,
 * and general groups related functions, such as loading groups or updating
 * groups for the user.
 */
class USIN_Groups{
	
	public static $slug = 'usin_group';
	public static $color_meta_key = 'usin_color';
	protected $parent_slug;
	protected $capability;
	protected static $all_groups;
	
	public function __construct($parent_slug){
		$this->parent_slug = $parent_slug;
		$this->capability = USIN_Capabilities::MANAGE_GROUPS;
	}
	
	/**
	 * Registers the required hooks to create the user group taxonomy
	 * and the color meta for the group.
	 */
	public function init(){
		add_action( 'init', array($this, 'register_taxonomy'));
		add_action( 'admin_menu', array($this, 'add_page_to_menu') );
		add_filter( 'parent_file', array($this, 'highlight_parent_menu'));
		
		if(function_exists('add_term_meta')){
			//add the hooks to add a color option for the group taxonomy
			add_action( self::$slug.'_add_form_fields', array($this, 'add_color_field'));
			add_action( self::$slug.'_edit_form_fields', array($this, 'add_edit_color_field'), 10, 2 );
			add_action( 'admin_enqueue_scripts', array($this, 'load_color_select_assets'));
			add_action( 'created_'.self::$slug, array($this, 'save_color_meta'), 10, 2);
			add_action( 'edited_'.self::$slug, array($this, 'update_color_meta'), 10, 2);
			add_filter( 'manage_edit-'.self::$slug.'_columns', array($this, 'add_group_color_column'));
			add_filter( 'manage_'.self::$slug.'_custom_column',  array($this, 'add_group_color_column_content'), 10, 3 );
		}
	}
	
	/**
	 * Loads all of the groups, including the ones that are empty.
	 * @return array array containing the group data
	 */
	public static function get_all_groups(){
		
		if(empty(self::$all_groups)){
			$terms = get_terms(self::$slug, array('hide_empty' => false));
			$groups = array();
			
			if(!empty($terms)){
				foreach ($terms as $term) {
					$color = get_term_meta( $term->term_id, self::$color_meta_key, true );
					$groups[]=array('key'=>$term->term_id, 'val'=>$term->name, 
						'color'=>$color);
				}
			}
			self::$all_groups = $groups;
		}
		
		return self::$all_groups;
	}
	
	/**
	 * Update the groups assigned to a user.
	 * @param  int $user_id the ID of the user
	 * @param  array $groups  array containg the IDs of the groups to assign
	 * @return boolean          sets whether the update was successful or not
	 */
	public static function update_user_groups($user_id, $groups){
		$res = wp_set_object_terms( $user_id, $groups, self::$slug, false);
		if(is_wp_error($res)){
			return false;
		}
		
		clean_object_term_cache( $user_id, self::$slug );
		return true;
	}
	
	/**
	 * Loads the groups that are assigned to a user
	 * @param  int $user_id the ID of the user
	 * @return array          array of the groups IDs that are assigned to the user
	 */
	public static function get_user_groups($user_id){
		return wp_get_object_terms( $user_id, self::$slug, array('fields'=>'ids') );
	}
	
	public static function update_user_groups_bulk($users, $group_id, $action){
		wp_defer_term_counting(true); //defer counting terms, do not update the term
		//count after updating the group for each user
		$failures = 0;
		
		foreach ($users as $user_id) {
			$res = null;
			
			if($action == 'add'){
				$res = wp_set_object_terms( $user_id, $group_id, self::$slug, true );
			}elseif($action == 'remove'){
				$res = wp_remove_object_terms( $user_id, $group_id, self::$slug );
			}
			
			if(is_wp_error($res)){
				$failures++;
			}
		}
		wp_defer_term_counting(false);
		wp_update_term_count($group_id, self::$slug); //update the term count now (only once)
		
		if($failures === 0){
			return true;
		}
		return new WP_Error('usin_group_edit_fail', sprintf(__('Failed to update group of %d users', 'usin'), $failures));
	}
	
	/**
	 * Adds the User Groups taxonomy page to the Users Insights menu.
	 */
	public function add_page_to_menu(){
		add_submenu_page( $this->parent_slug, __( 'User Groups' , 'usin'), __( 'User Groups' , 'usin'), 
			$this->capability, 'edit-tags.php?taxonomy=' . self::$slug );
	}
	
	/**
	 * Checks whether the current page is the user group taxonomy page.
	 * @return boolean true if it is the user group taxonomy page and false otherwise
	 */
	protected function is_group_taxonomy_page(){
		global $pagenow;
		return !empty($_GET['taxonomy']) && ($pagenow == 'edit-tags.php' || $pagenow =='term.php') && $_GET['taxonomy'] == self::$slug;
	}
	
	/**
	 * Fix a bug with highlighting the parent menu item. By default, when on the 
	 * edit taxonomy page for a user taxonomy, the Posts tab is highlighted
	 * This will correct that bug.
	 */
	public function highlight_parent_menu($parent = '') {
		if($this->is_group_taxonomy_page()) {
			$parent	= $this->parent_slug;
		}
		
		return $parent;
	}
	
	/**
	 * Loads the assets for the Color Select element.
	 */
	public function load_color_select_assets(){
		if($this->is_group_taxonomy_page()) {
			wp_enqueue_script('usin_color_select', 
				plugins_url('js/color-select.js', USIN_PLUGIN_FILE), 
				array('jquery'), 
				USIN_VERSION);
			
			wp_enqueue_style( 'usin_color_select_css', 
				plugins_url('css/color-select.css', USIN_PLUGIN_FILE ), array(), USIN_VERSION );
		}
	}
	
	/**
	 * Registers the User Group taxonomy.
	 */
	public function register_taxonomy(){
		register_taxonomy(
			self::$slug,
			'user',
			array(
				'public' => false,
				'labels' => array(
					'name' => __( 'User Groups' , 'usin'),
					'singular_name' => __( 'User Group' , 'usin'),
					'menu_name' => __( 'User Groups' , 'usin'),
					'search_items' => __( 'Search User Groups' , 'usin'),
					'popular_items' => null,
					'all_items' => __( 'All User Groups' , 'usin'),
					'edit_item' => __( 'Edit User Group' , 'usin'),
					'update_item' => __( 'Update User Group' , 'usin'),
					'add_new_item' => __( 'Add New User Group' , 'usin'),
					'new_item_name' => __( 'New User Group Name' , 'usin'),
					'separate_items_with_commas' => __( 'Separate User Groups with commas' , 'usin'),
					'add_or_remove_items' => __( 'Add or remove User Groups' , 'usin'),
					'choose_from_most_used' => __( 'Choose from the most popular User Groups' , 'usin'),
				),
				'show_ui' => true,
				'show_in_menu' => true,
				'capabilities' => array(
					'manage_terms' => $this->capability,
					'edit_terms'   => $this->capability,
					'delete_terms' => $this->capability,
					'assign_terms' => $this->capability,
				)
			)
		);
	}
	
	/**
	 * Adds a color select field to the Add User Group form.
	 * @param string $taxonomy the taxonomy
	 */
	public function add_color_field($taxonomy){
		?><div class="form-field term-group">
			<label for="colort-group"><?php _e('Group Color', 'usin'); ?></label>
			<input type="hidden" name="usin-group-color" class="usin-color-select" 
				data-colors="<?php echo esc_attr($this->get_color_options_string()); ?>"/>
		</div><?php
	}
	
	/**
	 * Adds a color select field to the Edit User Group form.
	 * @param object $term     the term object that is being edited
	 * @param string $taxonomy the taxonomy
	 */
	public function add_edit_color_field($term, $taxonomy){
		$saved_color = get_term_meta( $term->term_id, self::$color_meta_key, true );
		?><tr class="form-field term-group-wrap">
	        <th scope="row">
				<label for="colort-group"><?php _e('Group Color', 'usin'); ?></label>
			</th>
	        <td>
				<input type="hidden" name="usin-group-color" class="usin-color-select"
				 value="<?php echo $saved_color; ?>" data-colors="<?php echo esc_attr($this->get_color_options_string()); ?>" />
			</td>
		</tr><?php
	}
	
	/**
	 * Saves the color meta when a new user group is created.
	 * @param  int $term_id the ID of the term that is created
	 * @param  int $tt_id   the term_taxonomy ID
	 */
	public function save_color_meta($term_id, $tt_id){
		if(isset($_POST['usin-group-color']) && '' !== $_POST['usin-group-color'] ){
			add_term_meta( $term_id, self::$color_meta_key, $_POST['usin-group-color'], true );
		}
	}
	
	/**
	 * Updates the color meta when a user group is updated.
	 * @param  int $term_id the ID of the term that is created
	 * @param  int $tt_id   the term_taxonomy ID
	 */
	public function update_color_meta($term_id, $tt_id){
		if(isset($_POST['usin-group-color']) && '' !== $_POST['usin-group-color'] ){
			update_term_meta( $term_id, self::$color_meta_key, $_POST['usin-group-color'] );
		}
	}
	
	/**
	 * Adds a Group Color column to the User Group table.
	 * @param array $columns the existing table columns
	 */
	public function add_group_color_column( $columns ){
		$new_columns = array_slice($columns, 0, 2, true) +
		    array('usin_color' =>  __( 'Group Color', 'usin' )) +
		    array_slice($columns, 2, count($columns) - 1, true) ;
		
	    return $new_columns;
	}
	
	/**
	 * Adds the color box to the Group Color column that indicates the color of
	 * the group.
	 * @param string $content     the default column content
	 * @param string $column_name the ID of the column
	 * @param int $term_id     the ID of the term/group in the table
	 */
	public function add_group_color_column_content($content, $column_name, $term_id){
		if( $column_name !== 'usin_color' ){
	        return $content;
	    }
		
		$term_id = absint( $term_id );
	    $color = get_term_meta( $term_id, self::$color_meta_key, true );

	    if( !empty( $color ) ){
	        $content .= '<div class="usin-color-box" style="background-color:#'.esc_attr($color).';"></div>';
	    }

	    return $content;
	}
	
	protected function get_color_options_string(){
		$colors = array('d1efdc','eee1ff','fbdde9','ffe5da','dbf0fd',
			'e5f5d9','e2ebff','d4f1ec','fff4d3','ddf2f4');
		$colors = apply_filters('usin_group_colors', $colors);
		return implode(',', $colors);
	}
	
}