<?php
if (!function_exists('ybi_cu_content_actions_meta_init')) {
	add_action('admin_init','ybi_cu_content_actions_meta_init');
	function ybi_cu_content_actions_meta_init()
	{
		$post_type_arr = ybi_cu_get_post_type_feature();
		foreach ($post_type_arr as $type) 
		{
			add_meta_box('ybi_cu_content_actions_work_meta', '<i class="fa fa-tasks"></i> Curation Suite Content & Actions', 'ybi_cu_content_actions_meta_setup', $type, 'normal', 'high');
		}
	}

}

if (!function_exists('ybi_cu_content_actions_meta_setup')) {
	function ybi_cu_content_actions_meta_setup()
	{
		global $post;
		// using an underscore, prevents the meta variable from showing up in the custom fields section
		//$meta = get_post_meta($post->ID,'_ybi_cu_bucket_links_meta',TRUE);
		// instead of writing HTML here, lets do an include
		include(YBI_CURATION_SUITE_PATH . 'inc/cu-content-actions-meta.php');
		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="_ybi_cu_bucket_links_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}
}