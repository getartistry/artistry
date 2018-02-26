<?php

function pmwi_pmxi_custom_field($cf_value, $pid, $cf_key, $existing_meta_keys, $import_id){

	if ($cf_key == 'total_sales')
	{
		delete_post_meta($pid, $cf_key);
	}

	return $cf_value;
}