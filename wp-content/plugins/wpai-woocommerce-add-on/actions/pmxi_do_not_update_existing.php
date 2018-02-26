<?php
function pmwi_pmxi_do_not_update_existing($post_to_update_id, $import_id, $iteration, $xml, $i){

    $import = new PMXI_Import_Record();
    $import->getBy('id', $import_id);
    if ( ! $import->isEmpty() and in_array($import->options['custom_type'], array('product', 'product_variation'))) {

        $product_sku = get_post_meta($post_to_update_id, '_sku', true);

        $args = array(
            'post_type' => 'product_variation',
            'meta_query' => array(
                array(
                    'key' => '_sku',
                    'value' => $product_sku
                )
            )
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ){

            $duplicate_id = $query->post->ID;

            if ($duplicate_id) {
                $postRecord = new PMXI_Post_Record();
                $postRecord->clear();
                $postRecord->getBy(array(
                    'post_id'   => $duplicate_id,
                    'import_id' => $import_id
                ));
                if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $iteration))->update();
            }
        }

        if ("xml" == $import->options['matching_parent'] && "" != $import->options['variations_xpath'] && !empty($xml)){

            $cxpath = '/pmxi_records' . $import->xpath;

            $variation_xpath = $cxpath . '[' . ( $i + 1 ) . ']/'.  ltrim(trim(str_replace("[*]", "", $import->options['variations_xpath']),'{}'), '/');

            $records = array();

            $variations = XmlImportParser::factory($xml, $variation_xpath, '/', $file)->parse($records); $tmp_files[] = $file;

            $count_variations = count($variations);

            if ($count_variations){

                // Variation SKUs
                if ( $import->options['variable_sku'] != "" )
                {
                    $variation_sku = XmlImportParser::factory($xml, $variation_xpath, $import->options['variable_sku'], $file)->parse(); $tmp_files[] = $file;
                }
                else
                {
                    $count_variations and $variation_sku = array_fill(0, $count_variations, '');
                }

                foreach ($variations as $j => $void) {

                    $variation_sku_for_title = ("" == $variation_sku[$j]) ? $j : $variation_sku[$j];

                    if ($import->options['variable_sku_add_parent']){
                        $variation_sku[$j] = $product_sku . '-' . $variation_sku[$j];
                        $variation_sku_for_title = $product_sku . '-' . $variation_sku[$j];
                    }

                    $postRecord = new PMXI_Post_Record();
                    $postRecord->clear();
                    $postRecord->getBy(array(
                        'unique_key' => 'Variation ' . $variation_sku_for_title . ' of ' . $post_to_update_id,
                        'import_id' => $import->id
                    ));

                    if ( ! $postRecord->isEmpty() ){
                        $postRecord->set(array('iteration' => $iteration))->update();
                    }
                }

                foreach ($tmp_files as $file) { // remove all temporary files created
                    if (file_exists($file)) @unlink($file);
                }
            }
        }
    }
}