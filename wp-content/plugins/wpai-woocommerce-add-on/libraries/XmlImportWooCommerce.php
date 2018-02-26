<?php

abstract class XmlImportWooCommerce {
	
	public $import;
	public $xml;
	public $logger;
	public $count;
	public $chunk;
	public $xpath;
	public $wpdb;

	public $data;

	public $articleData = false;

	function pushmeta( $pid, $meta_key, $meta_value )
	{
		if (empty($meta_key)) return;		
		
		if ( empty($this->articleData['ID']) or $this->is_update_cf($meta_key))
		{
			update_post_meta($pid, $meta_key, $meta_value);			
		}
	}

	function is_update_cf( $meta_key )
	{
		if ( $this->import->options['update_all_data'] == 'yes') return true;

		if ( ! $this->import->options['is_update_custom_fields'] ) return false;			

		if ( $this->import->options['update_custom_fields_logic'] == "full_update" ) return true;
		if ( $this->import->options['update_custom_fields_logic'] == "only" 
			and ! empty($this->import->options['custom_fields_list']) 
				and is_array($this->import->options['custom_fields_list']) 
					and in_array($meta_key, $this->import->options['custom_fields_list']) ) return true;
		if ( $this->import->options['update_custom_fields_logic'] == "all_except" 
			and ( empty($this->import->options['custom_fields_list']) or ! in_array($meta_key, $this->import->options['custom_fields_list']) )) return true;
		
		return false;
	}

	function filtering($var)
	{
		return ("" == $var) ? false : true;
	}
}