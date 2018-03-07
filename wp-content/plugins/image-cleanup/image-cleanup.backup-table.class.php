<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Image_Cleanup_Backup_Table extends WP_List_Table 
{
	function get_columns(){
	  $columns = array(
	    'ID'	 		=> 'Index',
	    'actions'	 	=> '&nbsp;',
	    'state'			=> 'Last action',
	    'time'   		=> 'Date',
	    //'file_count'  	=> 'Removable Images',
	    //'meta_count'  	=> 'Meta to Delete',
	    'log'			=> 'Download Logs'
	  );
	  return $columns;
	}

	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
	}

	function prepare_items_ex($backup_array) {
            $this->prepare_items;
            $this->items = $backup_array;
        }
        
	function no_items() {
	  _e( 'No saved index found in database' );
	}

	function column_default( $item, $column_name ) 
	{
	  switch( $column_name ) 
	  { 
	    case 'time':
	    	return date('d-m-Y H:i:s', $item[$column_name]);
	    case 'state':	    	
	    case 'meta_count':
	    case 'log':
	    	return $item[$column_name];  	
	    case 'file_count':
	    	return '<center>'.$item[$column_name].'</center>';	    	
	    default:
	     	return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	  }
	}

	function column_actions($item) 
	{	
		$actions = array();

		//if ($item['file_count'] > 0)
		//{
			if ($item['state'] != "deleted") {
				// ($item['state'] != "moved")
		  			$actions['move'] = sprintf('<a href="?page=ImageCleanup&action=%s&backup=%s">Move Files</a>','move',$item['ID']);	  	
				// ($item['state'] != "restored")
					$actions['restore'] = sprintf('<a href="?page=ImageCleanup&action=%s&backup=%s">Restore Files</a>','restore',$item['ID']);
				$actions['delete'] = sprintf('<a href="?page=ImageCleanup&action=%s&backup=%s">Delete Files</a>','deletefiles',$item['ID']);
			}
		//}
		  	
	  	/*
	  	}
        else
       	*/
		//$actions['deleteindex'] = sprintf('<a href="?page=ImageCleanup&action=%s&backup=%s">Delete Index</a>','deleteindex',$item['ID']);

		return $this->row_actions($actions);
	}

	function column_ID($item) 
	{
		$actions = array();

		//FB::log($actions);
		return sprintf('%1$s %2$s', $item['ID'], $this->row_actions($actions) );
	}

}

?>