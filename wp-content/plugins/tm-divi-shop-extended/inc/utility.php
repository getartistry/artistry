<?php
    // Returns current theme version
    function tm_get_theme_ver($theme_name){
        $my_theme = wp_get_theme($theme_name);
        return $my_theme->get( 'Version' );
    }

    	// Patch file function
	// $path_dir and $dir assume trailing slash, $filename no slash
	function tm_patch_file($dir, $file_name, $patch_dir){
		$full_path = $dir.$file_name;
		$full_path_backup = $dir.$file_name.'.BACKUP';
		$full_path_patch = $patch_dir.$file_name;
		
		if(rename($full_path, $full_path_backup)){
			if(copy($full_path_patch,$full_path)){
				return 1;
			}else{
				rename($full_path_backup, $full_path); // Unrename
				return -1;
			}
		}else{
			return -2;
		}
	}
	// Unpatch file function
	function tm_unpatch_file($dir, $file_name){
		$full_path = $dir.$file_name;
		$full_path_backup = $dir.$file_name.'.BACKUP';
		if(file_exists($full_path) && file_exists($full_path_backup)){
			unlink($full_path);
			rename($full_path_backup,$full_path);
		}
	}
    
		// Patch file function
	// $path_dir and $dir assume trailing slash, $filename no slash
	function tm_patch_template_file($dir, $file_name, $patch_dir){
		$full_path = $dir.$file_name;
		$full_path_backup = $dir.$file_name.'.BACKUP';
		$full_path_patch = $patch_dir.$file_name;
		
		if(file_exists($full_path)){
			if(rename($full_path, $full_path_backup)){
				if(copy($full_path_patch,$full_path)){
					return 1;
				}else{
					rename($full_path_backup, $full_path); // Unrename
					return -1;
				}
			}else{
				return -2;
			}
		}else{
			if(copy($full_path_patch,$full_path)){
					return 1;
				}else{
					rename($full_path_backup, $full_path); // Unrename
					return -1;
			}
		}
	}


	// Debug function, returns arg value using js console
	function tm_debug_to_console( $data ) {
	    if($data) $output = $data; 
	    	else echo "<script>console.log( 'No Data' );</script>";
	    if ( is_array( $output ) ) 
	    	$output = implode( ',', $output);

	    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
	}

	function tm_version_compare($ver1, $ver2, $operator = null)
	{
		$p = '#(\.0+)+($|-)#';
		$ver1 = preg_replace($p, '', $ver1);
		$ver2 = preg_replace($p, '', $ver2);
		return isset($operator) ? 
			version_compare($ver1, $ver2, $operator) : 
			version_compare($ver1, $ver2);
	}

	function tm_skip_notices(){
		global $wp_filter;

		if(is_network_admin() and isset($wp_filter["network_admin_notices"]))
		{
			unset($wp_filter['network_admin_notices']); 
		}
		elseif(is_user_admin() and isset($wp_filter["user_admin_notices"]))
		{
			unset($wp_filter['user_admin_notices']); 
		}
		else
		{
			if(isset($wp_filter["admin_notices"]))
			{
				unset($wp_filter['admin_notices']); 
			}
		}
		
		if(isset($wp_filter["all_admin_notices"]))
		{
			unset($wp_filter['all_admin_notices']); 
		}
	}

?>