<?php 

add_action( 'init', 'create_link_bucket_taxonomies', 0 );
function create_link_bucket_taxonomies() {
	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Buckets', 'taxonomy general name' ),
		'singular_name'              => _x( 'Bucket', 'taxonomy singular name' ),
		'search_items'               => __( 'Search Buckets' ),
		'popular_items'              => __( 'Popular Buckets' ),
		'all_items'                  => __( 'All Buckets' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Bucket' ),
		'update_item'                => __( 'Update Bucket' ),
		'add_new_item'               => __( 'Add New Bucket' ),
		'new_item_name'              => __( 'New Bucket Name' ),
		'separate_items_with_commas' => __( 'Separate buckets with commas' ),
		'add_or_remove_items'        => __( 'Add or remove buckets' ),
		'choose_from_most_used'      => __( 'Choose from the most used buckets' ),
		'not_found'                  => __( 'No buckets found.' ),
		'menu_name'                  => __( 'Link Buckets' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'link_buckets' ),
	);

	register_taxonomy( 'link_buckets', 'curation_suite_links', $args );
	wp_insert_term(
		'Quick Add Links',
		'link_buckets',
		array(
		  'description'	=> __('These are links for quick add.'),
		  'slug' 		=> 'quick-add-links'
		)
	);

}

if(!function_exists('curation_suite_custom_links_post_type'))
{

// Register Custom Post Type
function curation_suite_custom_links_post_type() {

	$labels = array(
		'name'                => __( 'Curation Links'),
		'singular_name'       => __( 'Curation Link'),
		'menu_name'           => __( 'Curation Links'),
		'parent_item_colon'   => __( 'Parent Link:'),
		'all_items'           => __( 'All Links'),
		'view_item'           => __( 'View Link'),
		'add_new_item'        => __( 'Add New Link'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Link'),
		'update_item'         => __( 'Update Link'),
		'search_items'        => __( 'Search Links'),
		'not_found'           => __( 'Not found'),
		'not_found_in_trash'  => __( 'Not found in Trash'),
	);
	$capabilities = array(
		'edit_post'           => 'edit_link',
		'read_post'           => 'view_link',
		'delete_post'         => 'delete_link',
		'edit_posts'          => 'edit_links',
		'edit_others_posts'   => 'edit_others_links',
		'publish_posts'       => 'publish_links',
		'read_private_posts'  => 'read_private_links',
	);
	$args = array(
		'label'               => 'curation_suite_links',
		'description'         => __('Curation Suite Links'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'ybi_cu_bucket_link' ),
		'taxonomies'          => array( 'link_buckets' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 75.32,
		'register_meta_box_cb' => 'add_bucket_link_metabox',
		'menu_icon'           => plugins_url('curation-suite/i/curation-suite-icon-15x19.png'), //'dashicons-admin-links',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'curation_suite_links', $args );

}


// Hook into the 'init' action
	add_action( 'init', 'curation_suite_custom_links_post_type', 0 );
}

add_filter('manage_posts_columns', 'cu_links_source_link');
function cu_links_source_link($defaults){
	global $current_screen;
	if( $current_screen->post_type != 'curation_suite_links' ) return $defaults;
	
    $defaults['cu_links_sources_link'] = __('Source');
    return $defaults;
}
add_filter('manage_posts_columns', 'cu_links_visit_link');
function cu_links_visit_link($defaults){
	global $current_screen;
	if( $current_screen->post_type != 'curation_suite_links' ) return $defaults;
    
	$defaults['cu_links_visit_link'] = __('Actions');
    return $defaults;
}


add_action('manage_posts_custom_column', 'cu_links_custom_column_source_link',5,2);
function cu_links_custom_column_source_link($column_name, $id){
    if($column_name === 'cu_links_sources_link'){
		$url = get_post_meta(get_the_ID(), '_bucket_url_domain', true);
		if($url == '')
			echo '<span class="cu_no_link">-</span>';
		else
			echo '<a href="http://'.$url .'" target="_blank">'.$url.'</a>';
    }
}

add_action('manage_posts_custom_column', 'cu_links_custom_column_visit_link',5,2);
function cu_links_custom_column_visit_link($column_name, $id){
    if($column_name === 'cu_links_visit_link'){
			$aPost = get_post_field('post_content',get_the_ID());
	$url = get_post_meta(get_the_ID(), '_bucket_url', true);
	$the_title = get_the_title();

			if($url == '')
				echo '<span class="cu_no_link">-</span>';
			else
			{

                $curate_url =str_replace("/","\/",$url);
		        echo '<a href="'.$url .'" target="_blank">Visit Link</a>'
				.' | <a href="'.CURATE_THIS_URL .'?u='.rawurlencode($curate_url).'&t='.$the_title.'">Curate</a>';
			}
    }
}
// this creates the search based on the drop down in the Link Buckets custom post type
function cu_restrict_links_by_link_bucket() {
		global $typenow;
		$post_type = 'curation_suite_links'; // change HERE
		$taxonomy = 'link_buckets'; // change HERE
		if ($typenow == $post_type) {
			$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
			$info_taxonomy = get_taxonomy($taxonomy);
			wp_dropdown_categories(array(
				'show_option_all' => __("Show All {$info_taxonomy->label}"),
				'taxonomy' => $taxonomy,
				'name' => $taxonomy,
				'orderby' => 'name',
				'selected' => $selected,
				'show_count' => true,
				'hide_empty' => true,
			));
		};
	}

	add_action('restrict_manage_posts', 'cu_restrict_links_by_link_bucket');

	function cu_convert_id_to_term_in_query($query) {
		global $pagenow;
		$post_type = 'curation_suite_links'; // change HERE
		$taxonomy = 'link_buckets'; // change HERE
		$q_vars = &$query->query_vars;
		if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
			$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
			$q_vars[$taxonomy] = $term->slug;
		}
	}

	add_filter('parse_query', 'cu_convert_id_to_term_in_query');



add_filter( 'post_row_actions', 'ybi_cu_remove_row_actions', 10, 2 );
function ybi_cu_remove_row_actions( $actions, $post )
{
  global $current_screen;
	if( $current_screen->post_type != 'curation_suite_links' ) return $actions;
	//unset( $actions['edit'] );
	unset( $actions['view'] );
	//unset( $actions['trash'] );
	unset( $actions['inline hide-if-no-js'] );
	//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
	return $actions;
}



add_filter('manage_curation_suite_links_posts_columns', 'ybi_cu_remove_custom_views');
function ybi_cu_remove_custom_views($columns){
  global $current_screen;
	if( $current_screen->post_type != 'curation_suite_links' ) return $columns;

	unset( $columns['post_views'] );
	unset( $columns['curation'] );
	unset( $columns['post_social_quotes'] );
	
	/*unset( $columns );
	$columns = array(
		'cb' => true,
		'title' => __('Link Title'),
		'author' => __('Added By'),
		'link_buckets' => __('Link Buckets'),
		'date ' => __('Add Date'),
		);*/
		
    return $columns;
}


function add_bucket_link_metabox() {
    add_meta_box('ybi_cu_bucket_link', 'Saved Link/URL', 'ybi_cu_meta_display', 'curation_suite_links', 'normal', 'high');

}

function ybi_cu_meta_display() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="bucket_link_meta_noncename" id="bucket_link_meta_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered
	$url = get_post_meta($post->ID, '_bucket_url', true);
	$_bucket_url_domain = get_post_meta($post->ID, '_bucket_url_domain', true);
	// Echo out the field
	echo '<label>Link:</label><input type="text" name="_bucket_url" value="' . $url  . '" class="widefat" />';
	echo '<label>Source Domain:</label><input type="text" name="_bucket_url_domain" value="' . $_bucket_url_domain . '" class="widefat" />';
}
// Save the Metabox Data
function ybi_cu_save_bucket_link_meta($post_id, $post) {
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	$bucket_link_meta_noncename = isset($_POST['bucket_link_meta_noncename']) ? $_POST['bucket_link_meta_noncename'] : '';
	if ( !wp_verify_nonce( $bucket_link_meta_noncename, plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$bucket_link_meta['_bucket_url'] = $_POST['_bucket_url'];
	$bucket_link_meta['_bucket_url_domain'] = ybi_cu_getDomainName($_POST['_bucket_url']);
	// Add values of $events_meta as custom fields
	foreach ($bucket_link_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'ybi_cu_save_bucket_link_meta', 1, 2); // save the custom fields