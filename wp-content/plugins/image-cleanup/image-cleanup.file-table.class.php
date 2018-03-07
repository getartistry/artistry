<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Image_Cleanup_File_Table extends WP_List_Table 
{
	//var $logs = null;
	var $slug = null;
	var $pathinfo = null;

	function concat_overlap($basedir, $parsedir)
	{
		$parsedir = str_replace($_SERVER['DOCUMENT_ROOT']."/", "", $parsedir);
		$i = strlen($basedir);
	  	while($i > 0) {
	  		
	    	if( substr($basedir,-$i) == substr($parsedir,0,$i) )  {
	    		return $basedir.substr($parsedir,$i);
	    		break;
	    	}
	    	$i--;
	  	}
	  	
	  	return false;
	}

	function get_columns(){
		$columns = array();
		
		if ( $this->slug != "attachment-images" && $this->slug != "nonref-script-images" && $this->slug != "nonref-post-images")
		{ 
			$columns['cb'] = '<input type="checkbox" />';
		}

		// wach out with += arrays, keys are not unique
		$columns += array(
		'ID'	 		=> 'Index',
		'actions'		=> '&nbsp;',
		'fn'			=> 'File',
		'xist'			=> 'File Exist'
		);

		if ($this->slug == "nonref-post-images")
			$columns += array(
			'post_title' 	=> 'Post Title'
			);
		elseif ($this->slug != "nonref-script-images" && $this->slug != "nonref-unused-images")
			$columns += array(
			'dimensions' 	=> 'Dimensions',
			'att_id'		=> 'Meta ID'
			);

		if ($this->slug != 'nonref-script-images')
			$columns += array(
			'bd'			=> 'Path'
			);
		else
			$columns += array(
			'scriptfile'	=> 'Script',
			'scriptpath'	=> 'Script Path'
			);
	
		return $columns;
	}

	public function set_log_type($slug)
	{
		$this->slug = $slug;		
	}

	/*
	Used for bulk delete in Invalid_meta
	 */
	function get_bulk_actions() 
	{
		$actions = array();

		if($this->slug == "invalid-meta")
		{
			$actions = array(
				'bulkrepairmeta'    => 'Repair',
				'bulkdeletemeta'    => 'Delete, Except [FULL] if file exist'
			);
		}
		

		if ($this->slug == "backup-images" || $this->slug == "obsolete-ref-images")
		{
			$actions = array(
				'bulkdeletemeta'    => 'Delete'
			);
		}

		/**/
		//temporaly disable mass delete meta on valid attachments
		/*
		if ($this->slug == "attachment-images")
		{
			$actions = array(
				'bulkdeletemeta'    => 'Delete, Except [FULL] if file exist'
			);
		}
		*/	
		/**/

		if ($this->slug == "nonref-unused-images")
		{
			$actions = array(
				'bulkmovefile'    => 'Move, If not deleted/moved',
				'bulkrestorefile' => 'Restore moved files',
				'bulkdeletefile' =>  'Delete'
			);			
		}
		return $actions;		
	}

	function column_cb($item) 
	{
		if ( $this->slug != "nonref-script-images" && $this->slug != "attachment-images")
		{	
                    $disabled = '';
			//avoid delete full meta on other than invalid meta			
			//if ($item['ms']=='full')
			//	if ($this->slug != 'invalid-meta')
			//		$disabled = 'disabled="disabled"';
			//else 
			//	$disabled = "";

			// to disable deletion of full size images
			if (@$item['ms'] != 'full' || $this->slug != 'attachment-images')
			return sprintf(
        		'<input type="checkbox" '.$disabled.' name="att[]" value="%s" />', $item['ID']
        	);  
        }
    }

    /*
    End bulk
     */
	function prepare_items() {
		add_thickbox();

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->pathinfo = wp_upload_dir();            
        }

	function prepare_items_ex($file = null) {
                $this->prepare_items();

		$per_page = 100;
	  	$current_page = $this->get_pagenum();
	
		$count = 0;
		$fp = @fopen($file, 'r');
		if(!$fp) return false;
		while (($buffer = fgets($fp)) !== false) 
		{								
			if ($count >= (($current_page-1)*$per_page) && $count <= ($current_page*$per_page))
			{
				$value = json_decode($buffer, true);	
				$value['ID'] = $count;
				$this->items[] = $value;
			}
			$count++;
		}		
		$total_items = $count;
		fclose($fp);
		
		$this->set_pagination_args( 
			array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page                     //WE have to determine how many items to show on a page
			) 
		);  		
		
	}

	function no_items() {
	  _e( '<br><center><i>No files found in selected log view</i></center><br>' );
	}

	function column_default( $item, $column_name ) 
	{
	  switch( $column_name ) 
	  { 
	    case 'ID':
	    case 'xist':
	    case 'dimensions':
	    case 'scriptfile':	    
	    case 'scriptpath':	    
	    case 'bd':	    	    
	    case 'att_id':
	    	return $item[$column_name];  	
	    	break;
		case 'fn':
			if (@$item['file_moved']!=true && @$item['file_deleted']!=true && $item['xist']==true) 
				return "<a class='thickbox' title='' href='".$this->concat_overlap($this->pathinfo['baseurl'],$item['bd'].'/'.$item['fn'])."'>".$item['fn']."</a>";
			else
				return $item['fn'];
			break;
	    case 'post_title':
	    	/**
	    	 	TODO: edit post link
	    	 */    	
	    	return $item[$column_name].' ['.$item['post_status'].'] <small>('.$item['post_id'].')</small>';  	
	    	break;
	    default:
	     	return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	  }
	}

	function column_dimensions($item)
	{
		$dimensions = "";

		if ($item['w'] == "" && $item['mw'] == "")
			$dimensions = "<center><i>-</i></center>";
		elseif ($item['w'] == $item['mw'] && $item['h'] == $item['mh'])
			$dimensions = $item['w'].' x '.$item['h'];
		else
		{
			// show file not found or its dimensions
			if ($item['xist'] == false)
				$dimensions = '<small>file not found</small>';
			else
				if ($item['w'] != "")
					$dimensions .= $item['w'].' x '.$item['h'];
				else
					$dimensions = '<small>corrupt!</small>';	

			// show action instead of dimenion
			if (@$item['meta_deleted'] == true)
				$dimensions .= ' [<span style="color:red">Deleted</span>]';				
			elseif (@$item['meta_updated'] == true)
				$dimensions .= ' [<span style="color:green">Fixed</span>]';				
			// or show the dimension			
			else
			{
				$dimensions .= ' [<span style="color:red">';
				$dimensions .= $item['mw'].' x '.$item['mh'];
				$dimensions .= '</span>]';				
			}
		}
		
		return $dimensions;
	}

	function column_att_id($item)
	{
		if (@$item['meta_deleted'] != true)
		{			
			if (!isset($item['att_id']))
				return sprintf('<center><i>-</i></center>');
			else
				return sprintf($item['att_id'].'&nbsp;(<small>'.$item['ms'].'</small>)');
		}
		else
		{
			return '[<span style="color:red">Deleted</span>]';
		}
	}


	function column_xist($item)
	{
		$html = "<center>";

		if ($item['xist'])
			$html .= '&#10003;'; // &#10004 / &#10003
		else
			$html .= '<span style="color:red">No</span>';

		// file status (move / delete / restored)
		if (@$item['file_moved'] == true)
			$html = '<center>[<span style="color:green">Moved</span>]';

		if (@$item['file_restored'] == true)
			$html .= '&nbsp;[<span style="color:green">Restored</span>]';

		if (@$item['file_deleted'] == true)
			$html = '<center>[<span style="color:red">Deleted</span>]';

		$html .= "</center>";

		return $html;
	}

	function column_actions($item)
	{
		$actions = array();
                $view = sanitize_text_field($_GET['view']);
		if ( $view!="attachment-images" && $view != "nonref-script-images" && $view != "nonref-unused-images")
		
		{
			if (isset($item['att_id']))
			{
				if ( $view == "invalid-meta") 
					if (@$item['meta_updated'] == false)
						// can not repair if the file doest not exists
						if ($item['xist'] != false)
		  					$actions['repair'] .= sprintf('<a href="?page=ImageCleanup&view=%s&action=%s&logkey=%s">Repair Meta</a>',$view,'updatemeta',$item['ID']);
	  			
	  			if (@$item['meta_deleted'] == false)
	  			{
	  				// can not delete meta from full size except if the file does not exist
	  				if ($item['ms'] != 'full' || !$item['xist'] )
	  				{	
	  					$actions['delete'] = sprintf('<a href="?page=ImageCleanup&view=%s&action=%s&logkey=%s">Delete Meta</a>',$view,'deletemeta',$item['ID']);
	  				}
	  			}
	  		}

	  		/*
	  			its too dangerous to offer delete file option, tmp removed
	  			- file can be used by other meta
	  			- after delete meta you can delete file in next run anyway
	  			REQS:
	  			- add other file meta check
	  		*/
			//if ($item['xist'])
	  			//$actions['delete-file'] = sprintf('<a href="?page=ImageCleanup&view=%s&action=%s&backup=%s">Delete File</a>',$view,'deletefile',$item['ID']);
  		}
  		elseif ($view == "nonref-unused-images")
  		{
  			//reset fields
  			//$actions['reset'] .= sprintf('<a href="?page=ImageCleanup&view=%s&action=%s&logkey=%s">Reset</a>',$view,'reset',$item['ID']);

  			if (@$item['file_moved'] == false && @$item['file_deleted'] == false)
                            $actions['movefile'] = @$actions['movefile'] . sprintf('<a href="?page=ImageCleanup&view=%s&action=%s&logkey=%s">Move File</a>',$view,'movefile',$item['ID']);					
                        elseif (@$item['file_moved'] == true)
                            $actions['restorefile'] = @$actions['restorefile'] . sprintf('<a href="?page=ImageCleanup&view=%s&action=%s&logkey=%s">Restore</a>',$view,'restorefile',$item['ID']);
			
			if (@$item['file_deleted'] == false)
				$actions['delete'] = @$actions['delete'] . sprintf('<a href="?page=ImageCleanup&view=%s&action=%s&logkey=%s">Delete</a>',$view,'deletefile',$item['ID']);  				
  		}
	
		return $this->row_actions($actions);

	}
}

?>