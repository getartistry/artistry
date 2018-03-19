<?php
// === Add update hook ===
function dlk_update_check() {
	
	// Get the old and new version
	$old = get_option(DLK_VERSION_OPTION);
	$new = DLK_VERSION;
	
	// Run update actions
    if ($old!=$new) { 
		do_action('divi_learndash_kit_update', $old, $new); 
		update_option(DLK_VERSION_OPTION, $new);
	} 
	
}
add_action('plugins_loaded', 'dlk_update_check');

// === Handle clearing of local storage ===
// Clear modified modules in local storage as necessary
add_action('divi_learndash_kit_update', 'dlk_clear_module_local_storage');

function dlk_clear_module_local_storage() { 
	add_action('admin_head', 'dlk_remove_from_local_storage');
}
function dlk_remove_from_local_storage() { 
	foreach(dlk_get_custom_modules() as $slug) {
		echo "<script>localStorage.removeItem('et_pb_templates_".esc_attr($slug)."');</script>"; 
	}
}