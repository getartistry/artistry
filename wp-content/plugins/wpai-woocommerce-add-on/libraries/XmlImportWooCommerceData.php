<?php

require_once dirname(__FILE__) . '/XmlImportWooCommerceProduct.php';
require_once dirname(__FILE__) . '/XmlImportWooCommerceShopOrder.php';

/**
 * Is used to parse XML using specified template and root node
 */
class XmlImportWooCommerceData {	

	public $importer = false;

	public function __construct( $request )
	{		
		switch ($request['import']->options['custom_type']) 
		{
			case 'product':			
				$this->importer = new XmlImportWooCommerceProduct( $request );
				break;			

			case 'shop_order':				
				$this->importer = new XmlImportWooCommerceShopOrder( $request );							
				break;

			default:
				# code...
				break;
		}
	}

	public function parse()
	{
		return ($this->importer) ? $this->importer->parse() : false;
	}

	public function import( $data )
	{
		return ($this->importer) ? $this->importer->import( $data ) : false;
	}

	public function after_save_post( $data )
	{
		return ($this->importer) ? $this->importer->after_save_post( $data ) : false;
	}
}