<?php	


/*
Plugin Name: Image Cleanup
Plugin URI: http://none
Description: Checks all attachment images against the current image sizes (default and custom sizes) and removes files which no are no longer represented
Author: Robbert Langezaal, Simon Duduica
Author URI: http://none
Version: 1.9.2
Tags: image, clean, scan, index
License: GPL200
*/

/**
 * important:
 * 		when $content_width has been set in a theme wp_get_attachment_src might return wrong data
 * 		to avoid this we will _not_ be using its functionality.
 */

//		error_reporting(2047); 
//		ini_set("display_errors",1);

/**
 TODO: 	
 	- make the move/restore/delete bulk actions ajax based!

	- add tab with unused valid images (not used in posts)
	- if used in post add post ID and title to valid meta
	- add tab with missing meta data sizes (generate action?)
	- add edit post link to used invalid meta

 	- add options page (separate)
 	- why does json_encode convert image['fn'] to null values? need to fix! now skipping those files
 	- one ajax call per file content search option! Solvable through skip image path-- per file will really add a _lot_ of time to index
 	- move files to Unreferenced Unused Images data after deletion of metadata (if file exist)
	? why is javascript variable step_size not updated after safe.. because saving takes place after constructor..

 	- maybe: rewrite information gathering process
 		find all possible meta/files
 			- get attachments
 			- get backups
 			- get images
 			- get post/page/custom images
 		bunch of rules to place file/meta arrays in different categories 	
 */

include('image-cleanup.backup-table.class.php');
include('image-cleanup.file-table.class.php');

new ImageCleanup;
	
class ImageCleanup 
{
	public static $STEPSIZE = 100;
	public static $POSTSTEPSIZE = 100;
	public static $UPLOAD_DIR = '';
	public static $UPLOAD_URL = '';

		
	const MAJOR = "1";
	const MINOR = "9";

	const WPQUERY_SLUG 		= "wp-query-result";
	const ATT_SLUG		 	= "attachment-images";	
	const IMG_SLUG			= "image-files";
	const ALL_NOREF_IMG_SLUG= "all-nonref-images";
	const BACKUP_IMG_SLUG	= "backup-images";
	const POST_IMG_SLUG		= "nonref-post-images";
	const SCRIPT_IMG_SLUG	= "nonref-script-images";
	const NOREF_IMG_SLUG	= "nonref-unused-images";
	const IMG_REF_SLUG		= "obsolete-ref-images";
	const INVALID_META      = "invalid-meta";

	var $logs = array(
			array(
				'slug' => self::ATT_SLUG, 	
				'title' => 'Valid Attachment Meta',
				'short' => 'Valid Meta'),
			array(
				'slug' => self::BACKUP_IMG_SLUG, 		
				'title' => 'Backup Images',
				'short' => 'Backups'),
			array(
				'slug' => self::POST_IMG_SLUG, 	
				'title' => 'Unreferenced Used Images',
				'short' => 'Nonref used images'),
			array(
				'slug' => self::SCRIPT_IMG_SLUG, 	
				'title' => 'Script Images',
				'short' => 'Script images'),
			array(
				'slug' => self::NOREF_IMG_SLUG, 	
				'title' => 'Unreferenced Unused Images',
				'short' => 'Unused images'),
			array(
				'slug' => self::IMG_REF_SLUG, 	
				'title' => 'Obsolete Referenced Images',
				'short' => 'Obsolete Ref images'),
			array(
				'slug' => self::INVALID_META, 			
				'title' => 'Invalid Attachment Meta',
				'short' => 'Invalid Meta')
		);

	function __construct()
	{
		$step_size = get_option('image_cleanup_step_size', 100);
		$post_step_size = get_option('image_cleanup_post_step_size', 100);

		self::$STEPSIZE = $step_size;
		self::$POSTSTEPSIZE = $post_step_size;

		$uploads = wp_upload_dir();
		self::$UPLOAD_DIR = $uploads['basedir'];
		self::$UPLOAD_URL = $uploads['baseurl'];

/**
	TODO: register_uninstall_hook($file, $callback) 

	//if uninstall not called from WordPress exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	    exit();

	- Delete DB entries
	- Delete Folder
 */
		add_action( 'admin_notices', array( &$this, 'admin_notice') ); 				      
		add_action( 'admin_init', array( &$this, 'admin_init') );			
		add_action( 'admin_menu', array( &$this, 'admin_menu') );						
		add_action( 'admin_init', array( &$this, 'column_css') );
	}	

	function ImageCleanup()
	{
		$this->__construct();		
	} 

	function ifsetor(&$variable, $default = null) {
	    if (isset($variable)) {
	        $result = $variable;
	    } else {
	        $result = $default;
	    }
	    return $result;
	}

	function column_css() {
		if (get_admin_page_title() == "Image Cleanup") 
		{
		    echo '<style type="text/css">';

		   	echo 'li a.delete { color:AA0000 !important; }';
		   	echo 'li a.delete:hover { color:red !important; }';

		    // all columns
		    if ( $this->ifsetor($_GET['view'],null) != null)
		 		echo '.column-ID { min-width:30px !important;  }';
			else
				echo '.column-ID { min-width:30px !important;  }';

  			echo '.markred a { color:red !important; }';
  			echo '.markgrey a { color:grey !important; }';

		    // overview column
		    echo '.widefat { width:auto !important; }';		    
		    echo '.column-state { min-width:90px !important; overflow:hidden }';
		   	echo '.column-time { min-width:140px !important; overflow:hidden }';
		   	echo '.column-file_count { min-width:140px !important; overflow:hidden }';
		   	echo '.column-meta_count { min-width:110px !important; overflow:hidden }';
		   	echo '.column-log { width: 99%; min-width:200px !important; overflow:hidden }';
		   	// file columns
		 	echo '.column-fn { min-width:180px !important; overflow:hidden }';

		 	if ( $this->ifsetor($_GET['view'],null) != null)
		 		echo '.column-actions { min-width:100px !important; overflow:hidden }';
		 	else
		 		echo '.column-actions { min-width:240px !important; overflow:hidden }';

		   	echo '.column-xist { min-width:70px !important; overflow:hidden }';
		   	echo '.column-dimensions { min-width:140px !important; overflow:hidden }';
		   	echo '.column-att_id { min-width:100px !important; overflow:hidden }';
		   	echo '.column-post_title { min-width:350px !important; overflow:hidden }';
		   	echo '.column-bd { width: 99%; min-width:300px !important; overflow:hidden }';
		   	// checkbox column
		   	echo '.widefat tbody th.check-column { padding:	5px 0 2px !important; }';
		   	// other
		   	echo '.result-message { color: green; }';

		   	//thickbox
		   	echo '#TB_window { border-width : 0 !important; padding-bottom: 15px; }';
		   	echo '#TB_window img#TB_Image { border-width : 0 !important;}';
		   	echo '#TB_closeWindow { display: none; }';
		   	echo '#TB_caption { display: none; }';

		    echo '</style>';
		}
	}

	
	function admin_menu()
	{
		add_management_page( 'Image Cleanup', 'Image Cleanup', 'import', 'ImageCleanup', array( &$this, 'image_cleanup_tool_menu') );				
	}
	
	function admin_init()
	{	
		wp_enqueue_script('image-cleanup', plugins_url('image-cleanup.js',__FILE__), array('jquery'));

		wp_localize_script( 'image-cleanup', 'wp_object', 
			array( 
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'log_url' => self::$UPLOAD_URL."/imagecleanup/",					
					'step_size' => self::$STEPSIZE,
					'post_step_size' => self::$POSTSTEPSIZE
			) 
		); 

		add_action( 'wp_ajax_save_debug', 			array( &$this, 'ajax_save_debug_data') );
		add_action( 'wp_ajax_image_cleanup_step1', 	array( &$this, 'ajax_image_cleanup_step1') );
		add_action( 'wp_ajax_image_cleanup_step1a', array( &$this, 'ajax_image_cleanup_step1a') );
		add_action( 'wp_ajax_image_cleanup_step2', 	array( &$this, 'ajax_image_cleanup_step2') );
		add_action( 'wp_ajax_image_cleanup_step3', 	array( &$this, 'ajax_image_cleanup_step3') );
		add_action( 'wp_ajax_image_cleanup_step3a', array( &$this, 'ajax_image_cleanup_step3a') );
		add_action( 'wp_ajax_image_cleanup_step4', 	array( &$this, 'ajax_image_cleanup_step4') );
		add_action( 'wp_ajax_image_cleanup_step5', 	array( &$this, 'ajax_image_cleanup_step5') );
	}
	
	/**
	 * Get all attachment images from database
	 */

	function get_wp_attachment_images($position=null)
	{
		//dont use own query to keep compatible with wordpress
		//we could write our own query to try to get meta data together with attachment
		$query_images_args = array(
		    'post_type' => 'attachment',
		    'post_mime_type' =>'image',
		    'post_status' => 'inherit',		    
		    'fields' => 'ids',
		    'orderby' => 'ID'
		);

		if ($position!=null) 
		{
			$query_images_args += array(
				'posts_per_page' => self::$STEPSIZE,
            	'paged'=>($position / self::$STEPSIZE)+1 //Without the plus +1 the results would be _very_ odd
            );
        }
        else
        	$query_images_args += array(
        		'posts_per_page' => -1
        	);
		
		$query_images = new WP_Query( $query_images_args );

		if ( $query_images->have_posts() ) 
			return $query_images;		

		return false;
	}
	
	function get_attachment_image_array($wp_images, &$invalid_meta, $position=null)
	{
		//for debugging
		$count = 1;
		$pos = (int)$position;		
		$total = (int)$_POST['total'];
		$start = microtime(true);	
		$debuglog = self::$UPLOAD_DIR.'/imagecleanup/debug.json';

		//get wp_sizes
		$wp_sizes = get_intermediate_image_sizes();	// custom sizes are included
		$wp_sizes[] = "full";

		//get skip paths
		$skip_paths = explode("\r\n", get_option('image_cleanup_skip_paths', null));						

		//write sizes to debuglog
		if ($pos == 0)
			file_put_contents($debuglog, "[".number_format(microtime(true) - $start, 4)."] Registered sizes: ".json_encode($wp_sizes)."\r\n", FILE_APPEND);	

		//initialize vars
		$attachment_images = array();
		$image_data = array();

    	// loop all attachments
		foreach ($wp_images->posts as $image)
		{	
			// debug position to file
			if ($pos == (int)$position || $pos == $total)
			{
				file_put_contents($debuglog, "[".number_format(microtime(true) - $start, 4)."] Meta indexed so far: ".((int)$position+(int)$count)." / ".$total."\r\n", FILE_APPEND);	
			}
			$count++;
			//file_put_contents($debuglog, $image.' '.$count."\r\n", FILE_APPEND);
			$pos = (int)$position+(int)$count;			

			// get real wordpress attachment sizes
			$meta = wp_get_attachment_metadata($image, true);

			if ($pos == 2)
				file_put_contents($debuglog, "[".number_format(microtime(true) - $start, 4)."] Sample meta:\r\n".json_encode($meta)."\r\n", FILE_APPEND);	

			// .. and sizes
			foreach ($wp_sizes as $size)
			{			
				// if sizes meta data and key exists or full size	
				if (isset($meta['sizes']))
				{
					//file_put_contents($debuglog, $size."\r\n", FILE_APPEND);
					if ((is_array($meta['sizes']) && array_key_exists($size, $meta['sizes'])) || $size == 'full')
					{
						$image_data['att_id'] = $image;

						$image_data['bd'] = null;
						$image_data['fn'] = null;
						$image_data['mw'] = null; 
						$image_data['mh'] = null;

						// set file with path
						if ($size == "full")
						{
							//$path_parts = pathinfo(' '.$meta['file']);
							if (isset($meta['file']))
							{
								$image_data['bd'] = self::$UPLOAD_DIR.'/'.dirname($meta['file']); //basename is not UTF8 (dirname is?)
								$image_data['fn'] = preg_replace('/^.+[\\\\\\/]/', '', $meta['file']); //basename(' '.$meta['file']); //bugged
								$image_data['mw'] = $meta['width']; 
								$image_data['mh'] = $meta['height'];
							}
							else
							{								
								file_put_contents($debuglog, "[".number_format(microtime(true) - $start, 4)."] Incorrect meta ([full] key not found):\r\n".json_encode($meta)."\r\n", FILE_APPEND);
							}
						}
						//not required because we test this at the start already..
						//elseif (is_array($meta['sizes']) && array_key_exists($size, $meta['sizes']))
						else
						{
							//$path_parts = pathinfo(' '.$meta['sizes'][$size]['file']);
							if (isset($meta['file']) && isset($meta['sizes'][$size]['file']))
							{
								$image_data['bd'] = self::$UPLOAD_DIR.'/'.dirname($meta['file']); //basename is not UTF8 (dirname is?)							
								$image_data['fn'] = $meta['sizes'][$size]['file'];
								$image_data['mw'] = $meta['sizes'][$size]['width']; 
								$image_data['mh'] = $meta['sizes'][$size]['height']; 					
							}
							else
							{
								file_put_contents($debuglog, "[".number_format(microtime(true) - $start, 4)."] Incorrect meta ([".$size."] key not found):\r\n".json_encode($meta)."\r\n", FILE_APPEND);
							}							
						}

						if ($image_data['fn'] != null)
							$image_data['xist'] = file_exists($image_data['bd'].'/'.$image_data['fn']);
						else
							$image_data['xist'] = false;

						$image_size[0] = 0;
						$image_size[1] = 0;

						if ($image_data['xist'])
						{
							if ($image_size = @getimagesize($image_data['bd'].'/'.$image_data['fn'])) {
							 	// ok
							} else {
								// couldnt read image size
							    file_put_contents($debuglog,  "[".number_format(microtime(true) - $start, 4)."] Could not read image dimensions: ".$image_data['bd'].'/'.$image_data['fn']."\r\n", FILE_APPEND);								
							}
						}
						
						$image_data['w'] = $image_size[0];
						$image_data['h'] = $image_size[1];
						$image_data['ms'] = $size;

						$skip = false;						
						$file = $image_data['bd'].'/'.$image_data['fn'];		

						foreach ($skip_paths as $key)
						{
							if (stripos($file, $key) !== false)
							{
								$skip = true;
							}
						}
						if (!$skip)
						{														
							// check file dimensions against meta dimensions
							// TODO: add exception if != 0 
							if ( 
								$image_data['w'] == $image_data['mw']
								&&  
								$image_data['h'] == $image_data['mh']
								&& 
								$image_data['fn'] != null
							)						
							{
								// add image to valid meta array							
								$attachment_images[] = $image_data;									
							} else {
								// add image to invalid meta array
								$invalid_meta[] = $image_data;
							}
						} else	{
							//file in skip array
							//FB::log($file);
						}						
					} 
					/*else
					{
						//current correct size not found in meta data
						//option to create missing image size?
						FB::log($size);
						FB::log($meta);
						FB::log(is_array($meta['sizes']));
					}
					*/
				}
			}
		}	
		
		return $attachment_images;
	}
	 
	function get_image_file_array($folder)
	{
		$count = 0;

		$images = array();
		$image_data = array();		

		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));		
		$ext = array('.png','.jpeg','.jpg','.jpe','.gif','.png','.bmp','tif','tiff','.ico');
		
		$skip_paths = explode("\r\n", get_option('image_cleanup_skip_paths', null));
		$skip_paths[] = 'imagecleanup';

		$start = microtime(true);	
		$debuglog = self::$UPLOAD_DIR.'/imagecleanup/debug.json';

		while($it->valid()) 
		{		
		    if (!$it->isDot()) 
		    {  
		    	if ($it->isFile()) 
		    	{
					if ( $this->strposa($it->getFilename(),$ext) !== false)	
					{						
						//$image_data['att_id'] = 'uknown';
						$image_data['bd'] = dirname($it->key());
						$image_data['fn'] = $it->getFilename();					
						$image_data['xist'] = true; //stupid, yes-- but it saves a file_exist later on?

						$skip = false;
						$file = $image_data['bd'].'/'.$image_data['fn'];		

						// check if path in skip array
						foreach($skip_paths as $key)
						{							
							// check if str in complete path and filename
							if (stripos($file, $key) !== false)
							{
								$skip = true;
							}
						}				
						if (!$skip)
						{
							$images[] = $image_data;							
						}
						
						$count++;

					}
				}
		    }		
		    $it->next();
		}	

		return $images;		
	}
	
	function strposa($haystack, $needle, $offset=0) {
	    if(!is_array($needle)) $needle = array($needle);

	    foreach($needle as $query)
	        if(stripos($haystack, $query, $offset) !== false) return true; // stop on first true result
	    
	    return false;
	}	

	function get_script_file_array($folder)
	{
		$files = array();		
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));		
		
		//TODO: make this configurable / test with a lot of websites
		
		$it->setMaxDepth(2); // this will save a LOT of time

		//bad coding if image used in file other than css
		$ext = array('.css','.js','.html','.htm', '.php','.php4','.php5');

		while($it->valid()) 
		{		
		    if (!$it->isDot()) 
		    {		        		        
				if ( $this->strposa($it->key(),$ext) !== false)					
				{		
					//FB::log($it->key());
		        	//$filename = $it->getFilename();
		        	//array_push($files, $filename);
		        	array_push($files, $basedir = $it->key()); //str_replace('\\', '/', $it->key())); //$it->getSubPathName(), $it->getSubPath()
		        }
		    }		
		    $it->next();
		}	
		
		return $files;		
	}
	
	function get_post_used_invalid_images(&$invalid_images,$position)
	{
		global $wpdb;
			
		// set query get all content with images
		$query = "SELECT id, post_status, post_title, post_content FROM ".$wpdb->posts." ".
					"WHERE post_type <> 'revision' AND (post_content LIKE '%img%') ".
					"LIMIT ".$position.",".self::$POSTSTEPSIZE;

		$posts = $wpdb->get_results($query);	

		/**
		 *  check which images are used in posts
		 */

		$used_invalid_images = array();
		foreach ($posts as $post) 
		{		
			foreach ($invalid_images as $key => $image_data)
			{
				//if (strpos($post->post_content, $image_url) > 0)
				if (stripos($post->post_content, $image_data['fn']) !== false)
				{
					$image_data['post_id'] = $post->id;
					$image_data['post_title'] = $post->post_title;
					$image_data['post_status'] = $post->post_status;

					$used_invalid_images[] = $image_data;

					// avoid duplicate values and speed up search
					// also saves udiff function later
					unset ($invalid_images[$key]);
				} 
				
				/**
				  TODO possibility: check if post file exists, if not add to array
				  - images in posts which are not located on disk are not indexed atm
				  - would be nice to add..
				  */
				//elseif ()
			}
		}	
		return $used_invalid_images;			
	}
	
	function findstr_in_file($file, &$needles) //,$key)
	{	
		$handle = fopen($file, 'r');

		// while the buffer of 1000 is there to save memory it might cause unfound images
		// balance between memory usage and accuracy :( need to check this out further
		while (($buffer = fgets($handle,1000)) !== false) { 
			for ($i = 0; $i <= count($needles); $i++)
			{
				/**
				TODO: check and fix the holes in needles array by unsetting in parent function
				 */
				if (isset($needles[$i]))
				    if (stripos($buffer, $needles[$i]['fn']) !== false) {
				        fclose($handle);
				        return $i;
				    }      
			}
		}		
		fclose($handle);

		return false;
		
	}

	function get_attachment_backup_images($wp_attachment_images)
	{
		$images = array();
		$image_data = array();		

		foreach ($wp_attachment_images->posts as $image)
		{	
			$backup_sizes = get_post_meta( $image, '_wp_attachment_backup_sizes', true );		

			if ( $backup_sizes != null && array_key_exists('full-orig', $backup_sizes) && is_array($backup_sizes['full-orig']) ) 
			{
				$image_data['att_id'] = $image;
				$image_data['fn'] = $backup_sizes['full-orig']['file'];
				
				$image_data['bd'] = dirname(get_attached_file($image) ); 
				$image_data['xist'] = file_exists($image_data['bd'].'/'.$image_data['fn']);

				$image_size[0] = 0;
				$image_size[1] = 0;
				if ($image_data['xist'])
				{
					if ($image_size = @getimagesize($image_data['bd'].'/'.$image_data['fn'])) {
					 	// ok
					} else {
						// couldnt read image size
					    //file_put_contents($debuglog,  "[".number_format(microtime(true) - $start, 4)."] Could not read image dimensions: ".$image_data['bd'].'/'.$image_data['fn']."\r\n", FILE_APPEND);								
					}
				}

				$image_data['w'] = $image_size[0];
				$image_data['h'] = $image_size[1];

				$image_data['ms'] = 'full-orig';
				$image_data['mw'] = $backup_sizes['full-orig']['width']; //width
				$image_data['mh'] = $backup_sizes['full-orig']['height']; //height

				$images[] = $image_data;
			}
		}

		return $images;
	}

	function get_script_used_invalid_images(&$invalid_images)
	{
		$images = array();

		$invalid_images = array_values($invalid_images);
		
		if (is_dir(self::$UPLOAD_DIR))
		{
			$script_files = array_merge(	
				$this->get_script_file_array(self::$UPLOAD_DIR)//,				
		    	//$this->get_script_file_array(WP_CONTENT_DIR.'/themes'), //took too much time to read all these files
		    	//$this->get_script_file_array(WP_CONTENT_DIR.'/plugins') //took too much time to read all these files
		    );
	    			
	    	//$debug = WP_CONTENT_DIR.'/uploads/imagecleanup/';
	    	//file_put_contents($debug."debug.json", $this->prettyPrint(json_encode($script_files))."\r\n", FILE_APPEND);

		    foreach ($script_files as $file)
		    {	    
		    	//file_put_contents($debug."debug.json", $file."\r\n", FILE_APPEND);
		    	//
				$pos = stripos($file, "imagecleanup");
			    if ( $pos === false)
			    {
			    	$key = 0;

			    	while ($key !== false )
			    	{	    		
			    		$key = $this->findstr_in_file($file, $invalid_images); //, $key);

						if ( $key !== false )
			    		{	 	    
			    			$pathinfo = pathinfo($file);
			    			$invalid_images[$key]['scriptfile'] = $pathinfo['filename'].'.'.$pathinfo['extension'];
			    			$invalid_images[$key]['scriptpath'] = $pathinfo['dirname'];

			    			$images[] = $invalid_images[$key];

				    		//reset array keys
				    		unset($invalid_images[$key]);
				    		$invalid_images = array_values($invalid_images);
				    	} 
			    	}
			    }
			}
		}
	    
	    return $images;		
	}

	function get_attachment_id( $file, $dir ) 
	{
        // baseurl never has a trailing slash
        if ( false === stripos( $file, $dir . '/' ) ) {
            // URL points to a place outside of upload directory
            //FB::log($dir['basedir']);
            return false;
        }

        $path_parts = pathinfo($file);
        $filename = $path_parts['filename'].'.'.$path_parts['extension'];

        $query = array(
            'post_type'  => 'attachment',
            'fields'     => 'ids',
            'meta_query' => array(
                array(
                    'value'   => $filename,
                    'compare' => 'LIKE',
                ),
            )
        );

		/**
        //	TODO: check this part! 
        //	this could work but is it nessecary? the second part of the function will index files always
        //  full size images will not be found this way anyway, previous functions will find it
        */
        /*
        $query['meta_query'][0]['key'] = '_wp_attached_file';

        // query attachments
        $ids = get_posts( $query );

        if ( ! empty( $ids ) ) {

            foreach ( $ids as $id ) {
            	
                // first entry of returned array is the URL
                // this is not going to work, due to url(domain) != path
                // wrong because src is url and not path!
                $meta = wp_get_attachment_image_src( $id, 'full' );
                if ( stripos( $meta['url'], $file ) !== false )
                {
                    //return $id;

                }
            }
        }
		*/
        $query['meta_query'][0]['key'] = '_wp_attachment_metadata';

        // query attachments again if no full size has been found
        $ids = get_posts( $query );

        //FB::log($ids);
        if ( empty( $ids) )
        {
            return false;
        }

        foreach ( $ids as $id ) {

            $meta = wp_get_attachment_metadata( $id );
            
            if (isset($meta['sizes']))
            {            	
	            foreach ( $meta['sizes'] as $size => $value ) {
	            	/**
	            	// do we need strpos?!
	            	*/
	                if ( stripos($value['file'], $filename) !== false )
	                {
	                	//file exists so we do not need to set 'fn' 'bd' and 'xist'
						$image_data['att_id'] = $id;

						$image_size[0] = 0;
						$image_size[1] = 0;
						
						if (file_exists($file)) //do we need to check file.. better be carefull though TODO doublecheck
						{
							if ($image_size = @getimagesize($file)) {
								// ok
							} else {
								// couldnt read image size
							    //file_put_contents($debuglog,  "[".number_format(microtime(true) - $start, 4)."] Could not read image dimensions: ".$image_data['bd'].'/'.$image_data['fn']."\r\n", FILE_APPEND);								
							}
						}

						$image_data['w'] = $image_size[0];
						$image_data['h'] = $image_size[1];
						
						$image_data['ms'] = $size;
						$image_data['mw'] = $value['width'];
						$image_data['mh'] = $value['height'];
						
	                    return $image_data;
	                }
	            }
	        } /*else {
				no sizes found in metadata
	        }
	        */

        }

        return false;
    }
	
	function get_file_attachment(&$invalid_images)
	{
		//FB::log($invalid_images);
		$attachments = array();
		foreach($invalid_images as $key => $image)
		{
			$meta = $this->get_attachment_id($image['bd'].'/'.$image['fn'], self::$UPLOAD_DIR);

			if ($meta)
			{
				$image_data = array_merge($invalid_images[$key], $meta);
				unset ($invalid_images[$key]);
				$attachments[] = $image_data;
			}
		}
		return $attachments;
	}

	/**
	 * Process Actions from File and Overview Table
	 *
	 * 
	 */
	function delete_meta($logid, &$logmeta)
	{
		$meta = wp_get_attachment_metadata($logmeta[$logid]['att_id']);

		$file = $logmeta[$logid]['bd'].'/'.$logmeta[$logid]['fn'];
		// can not remove full size image except if file does not exist
		
		if ($logmeta[$logid]['ms'] != 'full' || !file_exists($file))
		{
			if ($logmeta[$logid]['ms'] != 'full' && $_GET['view'] != self::BACKUP_IMG_SLUG)
			{
				// remove image size from meta array			
				unset($meta['sizes'][$logmeta[$logid]['ms']]);
				$logmeta[$logid]['meta_deleted'] = true;									
				update_post_meta($logmeta[$logid]['att_id'], '_wp_attachment_metadata', $meta);	
			} 
			else
			{
				if ($_GET['view'] == self::BACKUP_IMG_SLUG)
				{
					delete_post_meta($logmeta[$logid]['att_id'], '_wp_attachment_backup_sizes');				
				}
				if ($logmeta[$logid]['ms'] == 'full' && !file_exists($file))
				{
					// this will completely remove an attachment from the database
					// .. dangerous for the valid attachment meta, if full selected all images will go..
					wp_delete_attachment($logmeta[$logid]['att_id'], true); //bypass trash
				}
				
				$logmeta[$logid]['meta_deleted'] = true;
			}
		}

		/**
		 * TODO: if deleted add file to unreferenced images log (would it be confusing??)
		 */

	}

	function update_meta($logid, &$logmeta)
	{
		$meta = wp_get_attachment_metadata($logmeta[$logid]['att_id']);	
		$file = $logmeta[$logid]['bd'].'/'.$logmeta[$logid]['fn'];

		// not much to repair if file not exists
		if (file_exists($file))
		{
			// if full size update main meta
			if ($logmeta[$logid]['ms'] == 'full')
			{
				$meta['width'] = $logmeta[$logid]['w'];
				$meta['height'] = $logmeta[$logid]['h'];
			}
			else
			{
			// else update one of the sizes
				$meta['sizes'][$logmeta[$logid]['ms']]['width'] = $logmeta[$logid]['w'];
				$meta['sizes'][$logmeta[$logid]['ms']]['height'] = $logmeta[$logid]['h'];
			}

			update_post_meta($logmeta[$logid]['att_id'], '_wp_attachment_metadata', $meta);						
			
			// update invalid meta array
			$logmeta[$logid]['meta_updated'] = true;				            			
		}
	}

	function move_file($logid, &$logmeta)
	{
		if ($logmeta[$logid]['file_moved'] != true && $logmeta[$logid]['file_deleted'] != true)
		{
			$file = $logmeta[$logid]['bd'].'/'.$logmeta[$logid]['fn'];

			$logmeta[$logid]['file_moved'] = true;
			$logmeta[$logid]['file_restored'] = false;
		
			$this->default_initialization($start, $backups, $id, $folder, $debug);	
			$filefolder = $debug.$id;

			rename($file, $filefolder.'/'.$logmeta[$logid]['fn']);
		}
	}

	function restore_file($logid, &$logmeta)
	{
		if (@$logmeta[$logid]['file_moved'] == true && @$logmeta[$logid]['file_deleted'] != true)
		{
			$file = $logmeta[$logid]['bd'].'/'.$logmeta[$logid]['fn'];

			$logmeta[$logid]['file_moved'] = false;
			$logmeta[$logid]['file_restored'] = true;
			
			$this->default_initialization($start, $backups, $id, $folder, $debug);	
			$filefolder = $debug.$id;

			rename($filefolder.'/'.$logmeta[$logid]['fn'], $file);	
		}	
	}

	function delete_file($logid, &$logmeta)
	{
		if ($logmeta[$logid]['file_deleted'] != true)
		{
			$file = $logmeta[$logid]['bd'].'/'.$logmeta[$logid]['fn'];

			$logmeta[$logid]['file_moved'] = false;
			$logmeta[$logid]['file_restored'] = false;
			$logmeta[$logid]['file_deleted'] = true;
			
			$this->default_initialization($start, $backups, $id, $folder, $debug);	
			$filefolder = $debug.$id;

			if (file_exists($file))
				unlink($file);
			elseif (file_exists($filefolder.'/'.$logmeta[$logid]['fn']))
				unlink($filefolder.'/'.$logmeta[$logid]['fn']);		
		}
	}

	function reset_file($logid, &$logmeta)
	{
		$logmeta[$logid]['file_moved'] = false;
		$logmeta[$logid]['file_restored'] = false;
		$logmeta[$logid]['file_deleted'] = false;
	}

	function load_json_array(&$data, $file)
	{
		$data = array();

		$fp = @fopen($file, 'r');
    	if(!$fp) return false;			
		while (($buffer = fgets($fp)) !== false) 
		{
			$data[] = json_decode($buffer, true);				
		}
		fclose($fp);
	}

	function save_json_array(&$data, $file, $append = false)
	{
		if ($append)
			$fp = @fopen($file, 'a'); //append a+
		else
			$fp = @fopen($file, 'w'); //truncate w+

	    if(!$fp) die("Unable to open file");
	    if (is_array($data))
	    {
		    foreach ($data as $key => $meta)
		    {    		
		    	fwrite($fp, json_encode($meta) . "\n");
		    }						
		}
		fclose($fp);		
	}

	function reset_image_cleanup()
	{
		delete_option('image_cleanup_backups');
		delete_option('image_cleanup_skip_paths');
		delete_option('image_cleanup_step_size');
		delete_option('image_cleanup_post_step_size');
		delete_option('image_cleanup_post_step_size');
		delete_option('image_cleanup_resultcount');
		delete_option('image_cleanup_version');

		$this->deleteDirectory(self::$UPLOAD_DIR.'/imagecleanup/', true);			
	}

	function process_actions()
	{
		// avoid re-posting!
		if( isset($_SESSION['postdata'])) {
			$_POST = $_SESSION['postdata'];
			unset($_SESSION['postdata']);
		}

		// save options
		// 
		if ( isset($_POST['action']) && $_POST['action'] == "saveoptions")
		{
                    update_option('image_cleanup_skip_paths', sanitize_textarea_field($_POST['paths']));
                    $stepSize = intval($_POST['steps']);
                    $stepSize = $stepSize ? $stepSize : 100;
                    update_option('image_cleanup_step_size', $stepSize);
                    $stepSizeP = intval($_POST['posts']);
                    $stepSizeP = $stepSizeP ? $stepSizeP : 100;
		    update_option('image_cleanup_post_step_size', $stepSizeP);
		}



		if ( isset($_POST['action']) && $_POST['action'] == "deleteindex")
		{
			//$this->default_initialization($start, $backups, $id, $folder, $debug);
			$this->reset_image_cleanup();
		}
		
		$log_meta = array();
		if ( isset($_GET['view']) && $_GET['view'] != "" )
		{
			$this->default_initialization($start, $backups, $id, $folder, $debug);
			
			// get meta based on current view
			$file = $folder . sanitize_file_name($_GET['view']) . '.json';
			$this->load_json_array($log_meta, $file);				
		}

		/**
		 * File Table BULK actions!
		 */			

		if ( isset($_POST['action']) )
		{
			// check if there are bulk entries
			if ( is_array(@$_POST['att']) ) 
			{
				// for each selected log item
				foreach ($_POST['att'] as $logkey)
				{	
					switch ($_POST['action'])
					{
						case 'bulkdeletemeta':
							self::delete_meta($logkey, $log_meta);								
							break;

						case 'bulkrepairmeta':
							self::update_meta($logkey, $log_meta);
							break;
						
						case 'bulkmovefile':
							self::move_file($logkey, $log_meta);
							break;

						case 'bulkrestorefile':
							self::restore_file($logkey, $log_meta);
							break;

						case 'bulkdeletefile':
							self::delete_file($logkey, $log_meta);						
							break;	
					}
				}

				$this->save_json_array($log_meta, $file);
			}			
		}

		/**
		 * File Table actions!
		 */			
			
		elseif ( isset($_GET['action']) )
		{				
			if ( isset($_GET['logkey']) )
			{
                            $logKey = intval($_GET['logkey']);
			    switch($_GET['action'])
				{
					case 'updatemeta':	
						self::update_meta($logKey, $log_meta);	
						break;

					case 'deletemeta':
						self::delete_meta($logKey, $log_meta);
						break;	

					case 'movefile':
						self::move_file($logKey, $log_meta);
						break;

					case 'restorefile':
						self::restore_file($logKey, $log_meta);
						break;

					case 'deletefile':
						self::delete_file($logKey, $log_meta);						
						break;	

					case 'reset':
						self::reset_file($logKey, $log_meta);						
						break;	


				}

				$this->save_json_array($log_meta, $file);			
			}


			/**
			 * Overview Table actions
			 */
			
			elseif (isset($_GET['backup']))
			{		
				$this->default_initialization($start, $backups, $id, $folder, $debug);	

				if ($backups != null)			
				{
					$file = $folder.self::NOREF_IMG_SLUG.'.json';					
					$move_images = array();
					$this->load_json_array($move_images, $file);				

					switch ($_GET['action'])
					{
						/*
						case 'deleteindex':
							delete_option('image_cleanup_backups');
							$this->deleteDirectory ($debug, true);
							break;
						*/
					
						case 'move':

							foreach ($move_images as $key => $image)
							{
								self::move_file($key, $move_images);
							}
							$backups[$id]['state'] = "moved";					
							break;

						case 'restore':
							foreach ($move_images as $key => $image)
							{
								self::restore_file($key, $move_images);

							}
							$backups[$id]['state'] = "restored";						
							break;

						case 'deletefiles':
							foreach ($move_images as $key => $image)
							{
								self::delete_file($key, $move_images);
							}
							$backups[$id]['state'] = "deleted";
							break;				
					}

					update_option('image_cleanup_backups', $backups);
					$this->save_json_array($move_images, $file);			
				}
			}
		}	
	}

	function create_subsubsub_li($link_text, $tag, $postlink_text, $post_text=null, $pre_text=null)
	{	
                $current = false;
		if ($_GET['view'] == $tag)
			$current = "current";

		$add_class = null;

		if (!$current)
		{
			// mark unused and unused referenced red
			if (($tag == self::IMG_REF_SLUG || $tag == self::NOREF_IMG_SLUG) && ($postlink_text >= 1))
			{
				$add_class = "delete ";
			}
		}

		return '<li class="'.$tag.'">'.$pre_text.'<a class="'.$add_class.$current.'" href="tools.php?page=ImageCleanup&view='.$tag.'">'.$link_text.' <span class="count">('.$postlink_text.')</span></a> '.$post_text.'</li> ';
	}

	function image_cleanup_tool_menu() 
	{		
		$this->default_initialization($start, $backups, $id, $folder, $debug);


		//delete backups if more then 1 (upgrade from 1.0+)
		if (count($backups) > 1)
		{
			delete_option('image_cleanup_backups');
			$this->deleteDirectory ($debug, true);
		}

		//delete folders if version < 1.6.2
		$version = get_option('image_cleanup_version', null);

		if ($version != self::MAJOR.'.'.self::MINOR)
		{
			$verarr = explode('.', $version);

			if ($verarr[0] == '1' && $verarr[1] < '9' || $version==null)
			{
				$this->reset_image_cleanup();
			}

			update_option('image_cleanup_version', self::MAJOR.'.'.self::MINOR);
		}



		if (!current_user_can('import'))  
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}			

		$this->process_actions();
		
		
		echo '<div class="wrap">';
		echo '<div class="icon32" id="icon-tools"><br></div>';

		$version = explode('.', PHP_VERSION);
		if ($version[0] > 5 || ($version[0] == 5 && $version[1] >= 1))
			echo '<h2>Image Cleanup <a href="#" id="imagecleanuprun" class="add-new-h2">Index Images</a></h2>';
		else
		{
			echo '<h2>Image Cleanup</h2>';
			echo '<p><b><font color=red>This plugin needs at least PHP 5.1! Unable to index images. Please upgrade your PHP version.</font></b></p>';
		}

		//echo '<canvas id="test" style="position: absolute; width: 300px; height: 120px; left: 150px; top: -13px;" width="300" height="150"></canvas>';
		
		echo '<p style="display:none;" class="indexmessage"><img overflow:hidden; position:relative; top:5px;" width=20px src="'.plugins_url("/image-cleanup/loading_transparent.gif").'"> <i>Indexing Images</i></p>';
		echo '<p class="result-message"></p>';
		echo '<p class="error-message"></p>';

		echo "<p>Use the above button to initiate indexing of images.<br>";
		echo "Files will <i>not</i> be deleted, moved or renamed from your content folder.<br>";
		
		/**
			TODO: check for diskspace and warn if not enough space			
		 */
		if (count($backups) > 0)
	 		$count = get_option( 'image_cleanup_resultcount');	

		echo '<ul class="subsubsub">';
		echo $this->create_subsubsub_li("General", "",	count($backups));

		if (count($backups) > 0)
		{			
			echo ":: ";
			foreach ($this->logs as $key => $log)
			{				
				if (is_int($count[$log['slug']]) && $count[$log['slug']] > 0)
				{
					if ($key > 0)
						echo " | ";
				
					echo $this->create_subsubsub_li($log['title'],	$log['slug'], 	$count[$log['slug']]);				
				}
			}
		} 
		else 
			echo '<i><span style="color:red">Please index your images to activate tabs</span></i>';
		
		echo '</ul></div><div style="clear:both;">'; 
		
		if ( !isset($_GET['view']) || $_GET['view']=="")
		{
			//delete index option hidden 
			if ( isset($_GET['dx']) && $_GET['dx']=="dx")	
				if (count($backups) > 0)
				{
					echo '<form id="remove-index-event-filter" method="post">';
					submit_button( "Delete Index", "small", "deleteindex", false );
					echo '<input type="hidden" name="action" value="deleteindex" />';
					echo '</form>';
				}

			// skip path option
			$skip_paths = get_option('image_cleanup_skip_paths', null);
			echo '<form id="image_cleanup_event_filter" method="post">';
			echo '<br>Skip image if part of path matches (separate by newline) ';
			echo '<br><textarea name="paths" rows="3" cols="60">'.$skip_paths.'</textarea>';			

			// step size option
			$step_size = get_option('image_cleanup_step_size', 100);
			echo '<br>How many images to process each step ';
			echo '<input name="steps" type="search" value="'.$step_size.'" size="5"><br>';			

			// skip path option
			$post_step_size = get_option('image_cleanup_post_step_size', 100);
			echo 'How many posts to process each step ';
			echo '<input name="posts" type="search" value="'.$post_step_size.'" size="5"><br>';			
			submit_button( "Save", "primary small", "poststepsize", false );

			echo '<input type="hidden" name="action" value="saveoptions" /><br>';
			echo '</form>';						

			
		}


		/*
		echo '<div class="wrap"><h2>The Result</h2>'; 
		echo "<p>The result is a list of UNUSED and UNREFERENCED images found in your media folder.<br>".
				"Do not confuse the remainder of these files with valid attachment (or valid attachment variant size) files.<br>".
				"References in your wordpress database are _NOT_ linked to the files in this result.</p>";
		echo "<p>Results from indexing are saved to the table below for further handling (if last saved result has the state [indexed] it will be overwritten).<br>".
				"After choosing to move the images to another location its wise to check the website for missing images [highly unlikely].<br>".
				"There is _ALWAYS_ a possibility to restore moved files.<br>".
				"After moving the files to a temporaly folder you are able to permanetly delete them.</p>";				
		*/


		if ( !isset($_GET['view']) || $_GET['view']=="")
			$this->display_backup_table();
		if ( isset($_GET['view']) && $_GET['view']!="")			
		{
			foreach ($this->logs as $key => $log)
			{
				if ($_GET['view'] == $log['slug'])
				{
					//echo $this->display_help($log['slug']);
					$file = $folder.$log['slug'].'.json';

					//if ($slug == self::ATT_SLUG || $slug == self::NOREF_IMG_SLUG)
						$this->display_file_table($log['slug'], $file);
					//else
						//$this->display_file_table($log['slug'],json_decode(file_get_contents($folder.$log['slug'].'.json'),true), $file);
				}
			}
		}
		
		echo '</div>';

		/*
		echo "<p>Image Cleanup will do the following:</p>";
		echo "<p>- Find all images in your upload folder<br>".
				"- Subtract valid wordpress attachment images (and their resized counterparts)<br>".
				"- Subtract backup images used for restore functionality<br>".
				"- Subtract images found in scripts<br>".
				"- Subtract images used in posts<br><p>";

		echo "<p>To ensure that your media library image files are all generated<br/>according to the current wordpress default and custom image<br/>size settings it is highly recommended to run one of the<br/>following plugins:<br/><br/>[AJAX thumbnail rebuild] or [ONet Regenerate Thumbnails]</p>";
		*/

	}
	
	function display_help($slug)
	{
		$help = "";

		if ($slug == self::ATT_SLUG)
		{
			$help .= "This overview will list all the indexed valid metadata.<br>";
			$help .= "Entries in this list have correct dimensions and valid sizes<br>";
			$help .= "also the file is present in the content folder.<br><br>";
			$help .= "Options: Delete, will remove the metadata from the<br>";
			$help .= "database. Not possible for full size images to avoid<br>";
			$help .= "breaking the site beyond restoration.<br>";
		}

		return $help;
	}


	function display_file_table($slug, /*&$file_array,*/ $file = null)
	{			
			$FileTable = new Image_Cleanup_File_Table();
			$FileTable->set_log_type($slug);

			echo '<form id="file-table-event-filter" method="post">';
			$page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
			$paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
			$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRIPPED );
			echo '<input type="hidden" name="page" value="'.$page.'" />';
			echo '<input type="hidden" name="paged" value="'.$paged.'" />';
			echo '<input type="hidden" name="view" value="'.$view.'" />';

			$FileTable->prepare_items_ex($file); 
			$FileTable->display(); 
			echo '</form>';

	}

	function create_link($text, $post_text, $url)
	{
		return "<a target='_blank' href='{$url}'>{$text}</a> ({$post_text})";
	}
	
	function display_backup_table()
	{		
		$this->default_initialization($start, $backups, $id, $folder, $debug);

		$table_data = array();
		$table_item = array();

		if ($backups != null)
		{
			foreach ($backups as $key => $backup)
			{
				$table_item['ID'] 			= $key;
				$table_item['state'] 		= $backup['state'];				
				$table_item['time'] 		= $backup['time'];
				$table_item['log_url']		= $backup['log_url'];			

				if (count($backups) > 0)
	 				$count = get_option( 'image_cleanup_resultcount');	

				$table_item['log'] = '';					

				foreach ($this->logs as $key => $log)
				{										
					if (is_int($count[$log['slug']]) && $count[$log['slug']] > 0)
					{
						if ($key > 0)
							$table_item['log'] .= ', ';
					
						$table_item['log'] .= $this->create_link($log['short'],	$count[$log['slug']], $table_item['log_url'].$log['slug'].'.json');
					}
				}

				$table_data[] = $table_item;
			}

		}
			
		$BackupTable = new Image_Cleanup_Backup_Table();
		$BackupTable->prepare_items_ex($table_data); 
		$BackupTable->display(); 
		
	}
	
	
	function admin_notice() 
	{
		global $image_cleanup_admin_message;
		
		if (count($image_cleanup_admin_message) > 0)
		{				
			$admin_message = '<div id="message" class="error fade">';					
			$admin_message .= '<strong>Image Cleanup:</strong><br/>';
			foreach ($image_cleanup_admin_message as $message)
				$admin_message .= $message.'<br>';
			$admin_message .= '</div>';
			
			echo $admin_message;
		}
	}
	
        /**
         * 
         * @param type $a - disc file name usually
         * @param type $b - meta file name usually
         * @param type $aShaddowSuffixes - suffixes that the disc image can have - for example retina corresponding images: @2x.jpg
         * @return true if match, false otherwise
         */
	function image_array_compare($a, $b )
	{
            $aShaddowSuffixes = array('@2x.' => '.'/*, '.webp' => '.jpg', '.webp' => '.png', '.webp' => '.jpeg'*/);
            
	    $ret = strcasecmp ($a['bd'].'/'.$a['fn'], $b['bd'].'/'.$b['fn']);
            
            //if not found, check with the shaddow prefixes
            if($ret) {
                foreach($aShaddowSuffixes as $ssKey => $ssVal) {
                    if (   strcasecmp ($a['bd'].'/'.str_replace($ssKey, $ssVal, $a['fn']), $b['bd'].'/'.$b['fn']) == 0
                        || strcasecmp ($a['bd'].'/'.$a['fn'], $b['bd'].'/'.str_replace($ssKey, $ssVal, $b['fn'])) == 0) {
                        return 0;
                    }
                }
            }
            return $ret;
	}

	function deleteDirectory($dir, $DeleteMe) {
	    if(!$dh = @opendir($dir)) return;
	    while (false !== ($obj = readdir($dh))) {
	        if($obj=='.' || $obj=='..') continue;
	        if (!@unlink($dir.'/'.$obj)) $this->deleteDirectory($dir.'/'.$obj, true);
	    }

	    closedir($dh);
	    if ($DeleteMe){
	        @rmdir($dir);
	    }
	}
	
	function default_initialization(&$start, &$backups, &$id, &$folder, &$debug)
	{
		$start = microtime(true);

		$backups = get_option('image_cleanup_backups', array());
		$id = 0;

		if (count($backups) != 0) {
			end($backups);
			$id = key($backups);

			//avoid new backups being created
			//if ($backups[$id]['state'] != 'indexed')
				//$id++;			
		}

		$folder = self::$UPLOAD_DIR.'/imagecleanup/'.$id.'/logs/';
		$debug = self::$UPLOAD_DIR.'/imagecleanup/';
		if (!file_exists($folder)) 
			mkdir($folder, 0777, true);
	}


	function ajax_image_cleanup_step1() 
	{	
		$this->default_initialization($start, $backups, $id, $folder, $debug);

		// get all wp attachment images
		$wp_images = $this->get_wp_attachment_images();

		if ($wp_images === false)
		{
			echo false;
			file_put_contents($debug."debug.json", "Fatal: WP_Query did not return any results");
			die();
		}
		else
		{			
			//log wp_query results
			$this->save_json_array($wp_images, $folder.self::WPQUERY_SLUG.'.json');
			file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: WP_Query results written to log\r\n");
		}
		
		header('Content-Type: application/json');
		echo json_encode(
			array(
				'count'=> count ($wp_images->posts)
			)
		);

		die();
	}	

	function ajax_image_cleanup_step1a() 
	{	
		error_reporting(2047); 
		ini_set("display_errors",1);

		$error = null;
		$halt = false;
		$this->default_initialization($start, $backups, $id, $folder, $debug);
                $pos = intval($_POST['position']);

		// get part of the qp query results
		$wp_images = $this->get_wp_attachment_images($pos);

		//because this function is called upon many times we need to reset if position = 0		
		if ($pos == 0)
			$invalid_meta = array();
		else
			$this->load_json_array($invalid_meta, $folder.self::INVALID_META.'.json');

		// get the attachment meta from file and database
		$attachment_images = $this->get_attachment_image_array($wp_images, $invalid_meta, $pos);
		$this->save_json_array($invalid_meta, $folder.self::INVALID_META.'.json');

    	/**/
		unset ($wp_images);
		unset ($invalid_meta);
		/**/

		
		if ($pos != 0)
			$this->save_json_array($attachment_images, $folder.self::ATT_SLUG.'.json', true); //append  	
		else
			$this->save_json_array($attachment_images, $folder.self::ATT_SLUG.'.json'); //truncate

		$continue = true;
		if ($pos+self::$STEPSIZE >= intval($_POST['total']))
		{
			file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Valid and Invalid meta results written to log\r\n", FILE_APPEND);
			$continue = false;
		}

		header('Content-Type: application/json');
		echo json_encode(
			array(
				'continue'=> $continue,
				'errormsg' => $error,
				'halt' => $halt
			)
		);
		die();
	}

	function convert_mem($size)
	{
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}

	function ajax_image_cleanup_step2() 
	{	
		error_reporting(2047); 
		ini_set("display_errors",1);


		$this->default_initialization($start, $backups, $id, $folder, $debug);

		$attachments = array();				
		$invalid_images = array();

		// get data from previous function
		$this->load_json_array($attachments, $folder.self::ATT_SLUG.'.json');
		$this->save_count(self::ATT_SLUG, count($attachments));		
		file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Loaded attachments to memory [".$this->convert_mem(memory_get_usage(true))."]\r\n", FILE_APPEND);		
		
		// get all images in uploads
	    $image_files = $this->get_image_file_array(self::$UPLOAD_DIR);
		file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: ".count($image_files)." Images found [".$this->convert_mem(memory_get_usage(true))."]\r\n", FILE_APPEND);

		/** FIRST COMPARISON **/
	    // subtract valid images with all found images   	    
		$invalid_images = array_udiff($image_files, $attachments, array( &$this, "image_array_compare") );	
    	file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: ".count($invalid_images)." Invalid images found [".$this->convert_mem(memory_get_usage(true))."]\r\n", FILE_APPEND);		

/** SAVE MEMORY HERE **/
		unset ($attachments);
		$this->save_json_array($image_files, $folder.self::IMG_SLUG.'.json');
    	unset ($image_files); 
/** SAVE MEMORY HERE **/

/// we could break the script here for another step ///
/// 
    	$this->load_json_array($invalid_meta, $folder.self::INVALID_META.'.json');    	
    	$this->save_count(self::INVALID_META, count($invalid_meta));		
		
		/** SECOND COMPARISON **/    	
		$invalid_images = array_udiff($invalid_images, $invalid_meta, array( &$this, "image_array_compare") );
		
/** SAVE MEMORY HERE **/
		unset ($invalid_meta); 
/** SAVE MEMORY HERE **/

		$wp_images = $this->get_wp_attachment_images();
		file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Retrieved attachment images from DB [".$this->convert_mem(memory_get_usage(true))."]\r\n", FILE_APPEND);	

		// find backup images used for restore functions and subtract from invalid image array
		$backup_images = $this->get_attachment_backup_images($wp_images);					

/** SAVE MEMORY HERE **/
		unset($wp_images);
/** SAVE MEMORY HERE **/

		$invalid_images = array_udiff($invalid_images, $backup_images, array( &$this, "image_array_compare") );
		$this->save_json_array($invalid_images, $folder.self::ALL_NOREF_IMG_SLUG.'.json');
		$this->save_json_array($backup_images, $folder.self::BACKUP_IMG_SLUG.'.json');
		$this->save_count(self::BACKUP_IMG_SLUG, count($backup_images));		

		file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Backup meta results written to log [".$this->convert_mem(memory_get_usage(true))."]\r\n", FILE_APPEND);

		//here filenames are still intact!
		/*
		foreach($invalid_images as $file)
		{
			file_put_contents($debug."debug.json", $file['fn']."\r\n", FILE_APPEND);
		}
		*/

		echo true;
		die();
	}	

	function ajax_image_cleanup_step3() 
	{	
		error_reporting(2047); 
		ini_set("display_errors",1);
		$this->default_initialization($start, $backups, $id, $folder, $debug);		

		// get data from previous function
		$this->load_json_array($invalid_images, $folder.self::ALL_NOREF_IMG_SLUG.'.json');

		//remove filenames which can not be displayed// fix this one time
		foreach($invalid_images as $key => $file)
		{
			//remove null or empty values
			if (empty($file['fn']))
			{
				unset($invalid_images[$key]);
				file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Removed file from index because of incorrect encoding\r\n", FILE_APPEND);
			}
		}

		//get all content with img html inside
		global $wpdb;
		$query = "SELECT id FROM ".$wpdb->posts." WHERE post_type <> 'revision' AND (post_content LIKE '%img%')";			
		$posts = $wpdb->get_results($query);	

		header('Content-Type: application/json');
		echo json_encode(
			array(
				'count'=> count ($posts)
			)
		);
		die();
	}

	function ajax_image_cleanup_step3a() 
	{	
		error_reporting(2047); 
		ini_set("display_errors",1);

		$error = null;
		$halt = false;
		$this->default_initialization($start, $backups, $id, $folder, $debug);		
                $pos = intval($_POST['position']);
                $total = intval($_POST['total']);

		// get data from previous function
		$this->load_json_array($invalid_images, $folder.self::ALL_NOREF_IMG_SLUG.'.json');
		//file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Read invalid image file data\r\n", FILE_APPEND);	
		file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Posts checked for used images ".($pos+1)."/". $total ."\r\n", FILE_APPEND);

		// find images used in posts and subtract from invalid image array		
		$used_post_invalid_images = $this->get_post_used_invalid_images($invalid_images,$pos);			
	
		// combine old and new post image array
		if ($pos != 0)
		{
			//add previous invalid images to $used_post_invalid_images
			$this->load_json_array($old_array, $folder.self::POST_IMG_SLUG.'.json');
			$used_post_invalid_images = array_merge($old_array,$used_post_invalid_images);
		}	
		
		$this->save_json_array($used_post_invalid_images, $folder.self::POST_IMG_SLUG.'.json');
	    $this->save_count(self::POST_IMG_SLUG, count($used_post_invalid_images));		
		$this->save_json_array($invalid_images, $folder.self::ALL_NOREF_IMG_SLUG.'.json');	

		$continue = true;
		if ($pos+self::$POSTSTEPSIZE >= $total)
		{
			file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Invalid post image meta results written to log\r\n", FILE_APPEND);
			$continue = false;
		}

		header('Content-Type: application/json');
		echo json_encode(
			array(
				'continue'=> $continue,
				'errormsg' => $error,
				'halt' => $halt
			)
		);
		die();
	}	

	function ajax_image_cleanup_step4() 
	{	
		error_reporting(2047); 
		ini_set("display_errors",1);

		$this->default_initialization($start, $backups, $id, $folder, $debug);		

		//invalid images
		$this->load_json_array($invalid_images, $folder.self::ALL_NOREF_IMG_SLUG.'.json');
		$count = count($invalid_images);

		// initial run sometimes brings up too many images to reference
	    if ($count < 200 && $count > 0 ) //&& count ($script_files) < 150)
		{
			// subtract used invalid script images from invalid file array
			$used_script_invalid_images = $this->get_script_used_invalid_images($invalid_images);

			$this->save_json_array($invalid_images, $folder.self::ALL_NOREF_IMG_SLUG.'.json');
			$this->save_count(self::ALL_NOREF_IMG_SLUG, count($invalid_images));		
			$this->save_json_array($used_script_invalid_images, $folder.self::SCRIPT_IMG_SLUG.'.json');
			$this->save_count(self::SCRIPT_IMG_SLUG, count($used_script_invalid_images));		

			file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Invalid script image meta results written to log\r\n", FILE_APPEND);
		}
		else
		{
			$this->save_count(self::ALL_NOREF_IMG_SLUG, $count);					
			$this->save_count(self::SCRIPT_IMG_SLUG, 0);
			file_put_contents($debug."debug.json", "Warning: skipping script index because of too many image files\r\n", FILE_APPEND);
		}

		echo true;
		die();
	}	

	function save_count($slug, $count)
	{		
	    $count_arr = get_option('image_cleanup_resultcount',null);
	    if ($count_arr == null)
	    	$count_arr = array();

	    //$count_arr = array();
	    $count_arr[$slug] = $count;
 		update_option('image_cleanup_resultcount', $count_arr);
	}

	function ajax_image_cleanup_step5() 
	{	
		error_reporting(2047); 
		ini_set("display_errors",1);

		$this->default_initialization($start, $backups, $id, $folder, $debug);

		$this->load_json_array($invalid_images, $folder.self::ALL_NOREF_IMG_SLUG.'.json');
		unlink($folder.self::ALL_NOREF_IMG_SLUG.'.json');

		$count = count($invalid_images);

		// initial run sometimes brings up too many images to reference
	    if ($count < 500 && $count > 0 )
	    {
			/**
			 * Find image file meta (as its possible they are still linked to metadata)
			 */
			$invalid_referenced_images = $this->get_file_attachment($invalid_images);		
			$this->save_json_array($invalid_referenced_images, $folder.self::IMG_REF_SLUG.'.json');
			$this->save_count(self::IMG_REF_SLUG, count($invalid_referenced_images));		

			//save memory!
			unset($invalid_referenced_images);

			file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Invalid referenced image meta results written to log\r\n", FILE_APPEND);			
		}
		else
		{
			$this->save_count(self::IMG_REF_SLUG, 0);		
			file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Warning: skipping attachment index because of too many image files\r\n", FILE_APPEND);
		}

		// add moved backup files back into unreferenced unused images 
		// with correct meta so when we re-index the moved files are shown
		$backupfolder = $debug.$id;	

		/*
		if (file_exists($folder.self::NOREF_IMG_SLUG.'.json')) {

			$temp_images = json_decode( file_get_contents( $folder.self::NOREF_IMG_SLUG.'.json'), true );		

			if (count($temp_images) > 0) {
				foreach ($temp_images as $key => $image)
				{
					if (isset($image['file_moved']))
					{
						if ($image['file_moved'] == true) {
							if ($image['fn'] != "" && file_exists($backupfolder.'/'.$image['fn'])) {
								$invalid_images[] = $image;
							}
						}
					}
				}
			}
		}
		*/
	
		// read line by line	
		$line = 0;
		$fp = @fopen($folder.self::NOREF_IMG_SLUG.'.json', 'r');
    	if($fp)
    	{
			while (($buffer = fgets($fp)) !== false) 
			{
				$image = json_decode($buffer, true);

				if (isset($image['file_moved']))
				{
					if ($image['file_moved'] == true) {
						if ($image['fn'] != "" && file_exists($backupfolder.'/'.$image['fn'])) {
							array_splice($invalid_images, $line, 0, array($image));
							//$invalid_images[] = $image;
						}
					}
				}
				$line++;
			}
			fclose($fp);
		}
		


		

		// final result of unreferenced unused images		
		// file_put_contents($folder.self::NOREF_IMG_SLUG.'.json', json_encode($invalid_images));		
		
		// write line by line
		/*
		$fp = fopen($folder.self::NOREF_IMG_SLUG.'.json', 'w+'); //truncate	    
	    if(!$fp) die("Unable to open file");
	    foreach ($invalid_images as $key => $meta)
	    {    		
	    	fwrite($fp, json_encode($meta) . "\n");
	    }*/
	    $this->save_json_array($invalid_images, $folder.self::NOREF_IMG_SLUG.'.json');
	    $this->save_count(self::NOREF_IMG_SLUG, count($invalid_images));

		file_put_contents($debug."debug.json", "[".number_format(microtime(true) - $start, 4)."] Info: Unreferenced image file results written to log\r\n", FILE_APPEND);

		/**
		  * Log all results
		  */

		$logs['state'] = 'indexed';
		$logs['time'] = time();
		$logs['log_url'] = self::$UPLOAD_URL."/imagecleanup/".$id."/logs/" ;

		/**
		  * Save logs 
		  */

		if (count($backups) == 0) // || $backups[$id]['state'] != 'indexed')
			// new index
			$backups[] = $logs;
		else
			//overwrite old index
			$backups[key($backups)] = $logs;
	
		update_option('image_cleanup_backups',$backups);

		echo true;		
		die();
	}	


	function ajax_save_debug_data() 
	{
		$this->default_initialization($start, $backups, $id, $folder, $debug);

		file_put_contents($debug.'debug.json', "[".number_format(microtime(true) - $start, 4)."] Uknown error: Please see returned value below\r\n", FILE_APPEND);
		file_put_contents($debug.'debug.json', $_POST['debugdata']."\r\n", FILE_APPEND);		

		echo true;		
		die();
	}	

    /**
     * a basename alternative that deals OK with multibyte charsets (e.g. Arabic)
     * @param string $Path
     * @return string
     */
    static public function MB_basename($Path, $suffix = false){
        $Separator = " qq ";
        $qqPath = preg_replace("/[^ ]/u", $Separator."\$0".$Separator, $Path);
        if(!$qqPath) { //this is not an UTF8 string!! Don't rely on basename either, since if filename starts with a non-ASCII character it strips it off
            $fileName = end(explode(DIRECTORY_SEPARATOR, $Path));
            $pos = strpos($fileName, $suffix);
            if($pos !== false) {
                return substr($fileName, 0, $pos);
            }
            return $fileName;
        }
        $suffix = preg_replace("/[^ ]/u", $Separator."\$0".$Separator, $suffix);
        $Base = basename($qqPath, $suffix);
        $Base = str_replace($Separator, "", $Base);
        return $Base;
    }
}
    	
?>