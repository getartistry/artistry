<?php

class USIN_List_Assets{

	protected $options;
	protected $page_slug;
	protected $version;
	protected $base_file;
	protected $nonce;
	protected $page;

	public function __construct($options, $page){
		$this->base_file = USIN_PLUGIN_FILE;
		$this->options = $options;
		$this->page = $page;
		$this->page_slug = $page->slug;
		$this->version = USIN_VERSION;
	}

	public function init(){
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets') );
		add_action( 'admin_enqueue_scripts', array($this, 'dequeue_um_select_css'), 100 );
		add_action( 'admin_print_scripts', array($this, 'print_inline') );
	}

	protected function is_usin_page(){
		global $current_screen;

		return strpos( $current_screen->base, $this->page_slug ) !== false;
	}


	public function enqueue_assets(){
		if($this->is_usin_page()){
			$main_js_deps = array();

			//enqueue JavaScript files
			wp_enqueue_script('usin_angular', 
				plugins_url('js/lib/angular/angular.min.js', $this->base_file), 
				array(), 
				$this->version);

			wp_enqueue_script('usin_ng_route', 
				plugins_url('js/lib/angular-route/angular-route.min.js', $this->base_file), 
				array('usin_angular'), 
				$this->version);

			wp_enqueue_script('usin_ng_sanitize', 
				plugins_url('js/lib/angular-sanitize/angular-sanitize.min.js', $this->base_file), 
				array('usin_angular'), 
				$this->version);
				
			wp_enqueue_script('usin_drag_drop', 
				plugins_url('js/lib/angular-drag-and-drop-lists/angular-drag-and-drop-lists.min.js', $this->base_file), 
				array('usin_angular'), 
				$this->version);
			
			wp_enqueue_script('usin_angular_material', 
				plugins_url('js/lib/angular-material/angular-material.min.js', $this->base_file), 
				array('usin_angular'), 
				$this->version);
				
			wp_enqueue_script('usin_select', 
				plugins_url('js/lib/angular-ui-select/select.min.js', $this->base_file), 
				array('usin_angular'), 
				$this->version);

			if(usin_module_options()->is_module_active('geolocation')){
				
				wp_enqueue_script('usin_maps', 
					plugins_url('js/lib/leaflet/leaflet.js', $this->base_file),  
					array(), 
					$this->version);
				
				wp_enqueue_style( 'usin_leaflet_css', plugins_url('js/lib/leaflet/leaflet.css', $this->base_file ), array(), $this->version );

				wp_enqueue_script('usin_marker_clusterer', 
					plugins_url('js/lib/leaflet-marker-clusterer/leaflet.markercluster.js', $this->base_file), 
					array('usin_maps'), 
					$this->version);
					
				wp_enqueue_style('usin_marker_clusterer_css_default', 
					plugins_url('js/lib/leaflet-marker-clusterer/MarkerCluster.Default.css', $this->base_file), 
					array(), 
					$this->version);
				
				$main_js_deps[]= 'usin_maps';
			}

			wp_enqueue_script('usin_helpers', 
				plugins_url('js/helpers.js', $this->base_file), 
				array('usin_angular'), 
				$this->version);
				

			wp_enqueue_script('usin_user_list', 
				plugins_url('js/user-list.min.js', $this->base_file), 
				array_merge($main_js_deps, array('usin_angular', 'usin_ng_route', 'usin_ng_sanitize', 'usin_helpers', 'usin_drag_drop', 'usin_angular_material', 'usin_select')), 
				$this->version);
			
			wp_enqueue_script('usin_templates', 
				plugins_url('views/user-list/templates.js', $this->base_file), 
				array('usin_user_list'), 
				$this->version);
			

			//enqueue CSS files
			wp_enqueue_style('usin_angular_meterial_css', 
				plugins_url('js/lib/angular-material/angular-material.min.css', $this->base_file), 
				array(), 
				$this->version);
				
			wp_enqueue_style('usin_select_css', 
				plugins_url('js/lib/angular-ui-select/select.min.css', $this->base_file), 
				array(), 
				$this->version);
				
			wp_enqueue_style( 'usin_main_css', plugins_url('css/style.css', $this->base_file ), array('usin_angular_meterial_css', 'usin_select_css'), $this->version );
			
		}

	}
	
	/**
	 * Dequeue the Ultimate Member styles from the Users Insights page, as they
	 * overwrite the select styles
	 */
	public function dequeue_um_select_css(){
		if($this->is_usin_page()){
			wp_dequeue_style('um_admin_select2');
			wp_dequeue_style('um_minified');
			wp_dequeue_style('um_styles');
			wp_dequeue_style('um_default_css');
		}
	}

	public function print_inline(){
		if($this->is_usin_page()){
			$this->init_js();
		}
		$this->print_css();
	}

	protected function init_js(){

		$options = array(
			'viewsURL' => 'views/user-list',
			'imagesURL' => plugins_url('images', $this->base_file),
			'ajaxURL' => admin_url( 'admin-ajax.php' ),
			'usersPerPage' => intval($this->options->get('users_per_page', 50)),
			'orderBy' => $this->options->get('orderby', 'registered'),
			'order' => $this->options->get('order', 'DESC'),
			'fields' => $this->options->set_icons($this->options->get_ordered_fields()),
			'unorderedFields' => $this->options->get_fields(),
			'editableFields' => $this->options->get_editable_fields(),
			'nonce' => $this->page->ajax_nonce,
			'months' => USIN_Helper::get_months(),
			'filterOperators' => $this->options->get_filter_operators(),
			'optionFieldTypes' => $this->options->get_field_types_by_type('option'),
			'textFieldTypes' => $this->options->get_field_types_by_type('text'),
			'geolocationActive' => usin_module_options()->is_module_active('geolocation'),
			'groups' => USIN_GROUPS::get_all_groups(),
			'segments' => USIN_Segments::get(),
			'customTemplates' => array(),
			'canUpdateUsers' => current_user_can(USIN_Capabilities::UPDATE_USERS),
			'canExportUsers' => current_user_can(USIN_Capabilities::EXPORT_USERS),
			'canManageSegments' => current_user_can(USIN_Capabilities::MANAGE_SEGMENTS),
			'is_ssl' => is_ssl(),
			'pageOptions' => array(10, 20, 50)
		);

		$strings = array(
			'daysAgo' => __('days ago', 'usin'),
			'day' => __('day', 'usin'),
			'month' => __('month', 'usin'),
			'year' => __('year', 'usin'),
			'loadMore' => __('Load More', 'usin'),
			'error' => __('Error', 'usin'),
			'errorLoading' => __('Error loading data', 'usin'),
			'addFilter' => __('Add Filter', 'usin'),
			'noResults' => __('0 results found', 'usin'),
			'title' => $this->page->title,
			'activity' => __('Activity', 'usin'),
			'noActivity' => __('No activity found', 'usin'),
			'back' => __('Back to user list', 'usin'),
			'of' => __('of', 'usin'),
			'usersPerPage' => __('Users per page', 'usin'),
			'view' => __('View', 'usin'),
			'users' => __('users', 'usin'),
			'mapUsersDetected' => __('user locations detected', 'usin'),
			'online' => __('online', 'usin'),
			'export' => __('Export this list of %d users', 'usin'),
			'cancel' => __('Cancel', 'usin'),
			'exportAction' => __('Export', 'usin'),
			'confirmExport' => __('Are you sure that you want to export the current list of <span class="usin-dialog-highlight">%s</span> users?'),
			'exportError' => __('Error exporting data', 'usin'),
			'groups' => __('User Groups', 'usin'),
			'groupUpdateError' => __('Error updating user groups', 'usin'),
			'notes' => __('Notes', 'usin'),
			'addNote' => __('Add Note', 'usin'),
			'by' => __('by', 'usin'),
			'noteError' => __('Error updating notes list', 'usin'),
			'delete' => __('Delete', 'usin'),
			'areYouSure' => __('Are you sure?', 'usin'),
			'fieldUpdateError' => __( 'Error updating fields', 'usin' ),
			'toggleColumns' => __('Toggle Columns', 'usin'),
			'enterMapView' => __('Enter Map View', 'usin'),
			'exitMapView' => __('Exit Map View', 'usin'),
			'select' => __('Select', 'usin'),
			'usersSelected' => __('%d Users Selected', 'usin'),
			'userSelected' => __('1 User Selected', 'usin'),
			'bulkActions' => __('Bulk Actions', 'usin'),
			'segments' => __('Segments', 'usin'),
			'saveSegmentTooltip' => __('Save the current filters as a segment', 'usin'),
			'disabledSegmentTooltip' => __('Apply filters to create a segment', 'usin'),
			'newSegment' => __('Create new segment', 'usin'),
			'saveSegment' => __('Save segment', 'usin'),
			'deleteSegment' => __('Delete segment', 'usin'),
			'segmentName' => __('Segment name', 'usin'),
			'confirmDeleteSegment' => __('Are you sure that you want to delete the segment <span class="usin-dialog-highlight">%s</span>?'),
			'createSegmentError' => __( 'Error creating the segment', 'usin' ),
			'fieldNotExist' => __( 'This field does not exist anymore', 'usin' ),
			'addGroup' => __('Add to group', 'usin'),
			'addUserGroupInfo' => __('Add the selected user to the following group', 'usin'),
			'addUsersGroupInfo' => __('Add the selected %d users to the following group', 'usin'),
			'removeGroup' => __('Remove from group', 'usin'),
			'removeUserGroupInfo' => __('Remove the selected user from the following group', 'usin'),
			'removeUsersGroupInfo' => __('Remove the selected %d users from the following group', 'usin'),
			'selectAllUsers' => __('Select all users'),
			'clearSelection' => __('Clear Selection'),
			'cancel' => __('Cancel', 'usin'),
			'apply' => __('Apply', 'usin'),
			'selectGroup' => __('Select a group', 'usin'),
			'noGroups' => __('There are no user groups created. Go to Users Insights -> User Groups to create a new group.', 'usin')
		);

		$options['strings'] = $strings;
		
		$options = apply_filters('usin_user_list_options', $options);

		$output = '<script type="text/javascript">var USIN = '.json_encode($options).';</script>';

		echo $output;
	}

	protected function print_css(){
		$output = '<style>
		#toplevel_page_'.$this->page_slug.' .dashicons-before img {
		  max-width: 20px;
		  height: auto;
		  padding-top:7px;
		}
		</style>';

		echo $output;
	}
}