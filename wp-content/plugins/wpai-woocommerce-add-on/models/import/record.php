<?php

class PMWI_Import_Record extends PMWI_Model_Record {		

	/**
	 * Associative array of data which will be automatically available as variables when template is rendered
	 * @var array
	 */
	public $data = array();

	public $importer = false;	
	
	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMXI_Plugin::getInstance()->getTablePrefix() . 'imports');
	}	
	
	/**
	 * Perform import operation
	 * @param string $xml XML string to import
	 * @param callback[optional] $logger Method where progress messages are submmitted
	 * @return PMWI_Import_Record
	 * @chainable
	 */
	public function parse($parsing_data = array()) {

		if ( ! in_array($parsing_data['import']->options['custom_type'], array('product', 'shop_order')) ) return;		

		add_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // do not perform special filtering for imported content
		
		$this->options = $parsing_data['import']->options;

		$this->importer = new XmlImportWooCommerceData( $parsing_data );

		$this->data = $this->importer->parse();										

		remove_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // return any filtering rules back if they has been disabled for import procedure				

		if ($this->options['put_variation_image_to_gallery'])
		{
			add_action('pmxi_gallery_image', array($this, 'wpai_gallery_image'), 10, 3);
		}

		if ( ! empty($this->options['import_additional_variation_images']) ) {

			add_action( 'pmxi_saved_post', array( $this, 'wpai_additional_variation_images' ), 10, 3 );

		}

		return $this->data;
	}				

	public function import( $importData = array() ){

		if ( ! in_array($importData['post_type'], array('product', 'product_variation', 'shop_order')) ) return;

		$this->importer->import( $importData );
					
	}

	public function saved_post( $importData )
	{

		if ( ! in_array($importData['import']->options['custom_type'], array('product', 'product_variation', 'shop_order'))) return;

		$this->importer->after_save_post( $importData );							
		
	}
	
	public function wpai_gallery_image($pid, $attid, $image_filepath){			

		$table = $this->wpdb->posts;

		$p = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $table WHERE ID = %d;", (int) $pid));		

		if ($p and $p->post_parent){
			$gallery = explode(",", get_post_meta($p->post_parent, '_product_image_gallery_tmp', true));
			if (is_array($gallery)){
				$gallery = array_filter($gallery);
				if ( ! in_array($attid, $gallery) ) $gallery[] = $attid;
			}
			else{
				$gallery = array($attid);
			}

			update_post_meta($p->post_parent, '_product_image_gallery_tmp', implode(',', $gallery));		
		}
	}
	
	public function wpai_additional_variation_images( $pid, $xml, $update ) {

        $product = wc_get_product( $pid );
        if ( $product->is_type( 'variation' ) ) {
            if ( $gallery = get_post_meta( $pid, '_product_image_gallery', true ) ) {
                update_post_meta( $pid, '_wc_additional_variation_images', $gallery );
            }
        }
    }


	public function _filter_has_cap_unfiltered_html($caps)
	{
		$caps['unfiltered_html'] = true;
		return $caps;
	}		
	
}
