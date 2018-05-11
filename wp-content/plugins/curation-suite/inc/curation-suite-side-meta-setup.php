<?php 
function ybi_cs_add_sidebar_meta_box() {
	$post_type_arr = ybi_cu_get_post_type_feature();
	foreach ($post_type_arr as $type) 
	{
		add_meta_box('ybi_cu_sidebar_meta', 'Saved Curated Tags', 'ybi_cu_sidebar_meta_display', $type, 'side', 'default');
	}
}
add_action('admin_init','ybi_cs_add_sidebar_meta_box');

function ybi_cu_sidebar_meta_display() {
	global $post;
	// Noncename needed to verify where the data originated
		// from showing up in the custom fields section
		$cu_ybi_saved_tags = get_post_meta($post->ID,'_ybi_cu_sidebar_saved_tags',TRUE);
		// instead of writing HTML here, lets do an include
		include(YBI_CURATION_SUITE_PATH . 'inc/curation-suite-side-meta.php');
		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="ybi_cu_sidebar_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';

}

function ybi_cu_sidebar_meta_save($post_id) {

// Check if our nonce is set.
	if ( ! isset( $_POST['ybi_cu_sidebar_meta_noncename'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	/*if ( ! wp_verify_nonce( $_POST['ybi_cu_sidebar_meta_noncename'], 'ybi_cu_sidebar_meta_noncename' ) ) {
		return;
	}*/
	// the tags we get are one long comma seperated string, we turn that into a unique array and apply rules, right now there's just a strlen rule but in the future we might add more.
	$my_data = ( $_POST['_ybi_cu_sidebar_saved_tags'] );
	$pieces = explode(",", $my_data);
	$tagArr = array_unique($pieces);
	$my_data_to_save = '';
	$i = 1;
	foreach ($tagArr as &$value) {
		if($value != '' && strlen($value) > 2):
			if($i > 1)
				$my_data_to_save .= ', ';

		   	$my_data_to_save .= $value;
			$i++;
		endif;
	}
	// Update the meta field in the database.
	update_post_meta( $post_id, '_ybi_cu_sidebar_saved_tags', $my_data_to_save );
	
}
add_action( 'save_post', 'ybi_cu_sidebar_meta_save' );
?>