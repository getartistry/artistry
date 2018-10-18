<?php

function sm_array_recursive_diff($array1, $array2) {
  $array_diff = array();
  foreach ($array1 as $key => $value) {
    if (array_key_exists($key, $array2)) {
      if (is_array($value)) {
        $recursive_diff = sm_array_recursive_diff($value, $array2[$key]);
        if (count($recursive_diff)) { $array_diff[$key] = $recursive_diff; }
      } else {
        if ($value != $array2[$key]) {
          $array_diff[$key] = $value;
        }
      }
    } else {
      $array_diff[$key] = $value;
    }
  }

  return $array_diff;
} 

function sm_multidimesional_array_search($id, $index, $array) {
   foreach ($array as $key => $val) {
   		if (empty($val[$index])) continue;

     	if ($val[$index] == $id) {
           return $key;
       	}
   }
   return null;
}

//Function to sort multidimesnional array based on any given key
function sm_multidimensional_array_sort($array, $on, $order=SORT_ASC){

    $sorted_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if ($key2 == $on) {
                        $sortable_array[$key] = $value2;
                    }
                }
            } else {
                $sortable_array[$key] = $value;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $key => $value) {
            $sorted_array[$key] = $array[$key];
        }
    }

    return $sorted_array;
}

//Function to compare column position
function sm_position_compare( $a, $b ){
    if ( $a['position'] == $b['position'] )
        return 0;
    if ( $a['position'] < $b['position'] ) {
        return -1;
    }
    return 1;
}

function sm_woo_get_price($regular_price, $sale_price, $sale_price_dates_from, $sale_price_dates_to) {
        // Get price if on sale
        if ($sale_price && $sale_price_dates_to == '' && $sale_price_dates_from == '') {
            $price = $sale_price;
        } else { 
            $price = $regular_price;
        }   

        if ($sale_price_dates_from && strtotime($sale_price_dates_from) < strtotime('NOW')) {
            $price = $sale_price;
        }
        
        if ($sale_price_dates_to && strtotime($sale_price_dates_to) < strtotime('NOW')) {
            $price = $regular_price;
        }
    
    return $price;
}




//function to fetch the variation current post title
function sm_get_current_variation_title( $pids = array() ) {

    $results = array();

    if( empty( $pids ) ) {
        return $results;
    }

    global $wpdb;

    $variable_taxonomy_ids = $wpdb->get_col( $wpdb->prepare( "SELECT taxonomy.term_taxonomy_id as term_taxonomy_id
                                                        FROM {$wpdb->prefix}terms as terms
                                                            JOIN {$wpdb->prefix}term_taxonomy as taxonomy 
                                                            ON (taxonomy.term_id = terms.term_id
                                                              AND taxonomy.taxonomy = %s)
                                                        WHERE terms.slug IN ('variable', 'variable-subscription')", 'product_type' ) );

    //query to get the parent ids old title
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT id, post_title 
                            FROM {$wpdb->posts} as p
                              JOIN {$wpdb->prefix}term_relationships as tp
                                ON(tp.object_id = p.id
                                  AND p.post_type = %s)
                              WHERE p.id IN (". implode(",",$pids) .")
                                AND tp.term_taxonomy_id IN (". implode(",",$variable_taxonomy_ids) .")", 'product' ), ARRAY_A );

    return $results;
}

//function to sync the variations title when the parent product title is updated
function sm_sync_variation_title( $new_title_update_case, $ids ) {

    if( !empty( $new_title_update_case ) && !empty( $ids ) ) {

        global $wpdb;

        $wpdb->query( $wpdb->prepare(
                          "UPDATE {$wpdb->posts}
                          SET post_title = (CASE ". implode(" ",$new_title_update_case) ." END)
                          WHERE post_type = %s
                          AND post_parent IN (". implode(",",$ids) .")",
                          'product_variation'
                      )
                  );
    }
}

function sm_update_price_meta( $ids ) {

   if( !empty($ids) ) {

      global $wpdb;

      $query = "SELECT post_id,
                  GROUP_CONCAT( meta_key ORDER BY meta_id SEPARATOR '##' ) AS meta_keys, 
                  GROUP_CONCAT( meta_value ORDER BY meta_id SEPARATOR '##' ) AS meta_values 
              FROM {$wpdb->prefix}postmeta 
              WHERE meta_Key IN ( '_regular_price', '_sale_price', '_sale_price_dates_from', '_sale_price_dates_to' ) 
                AND post_id IN (".implode(",", $ids).")
              GROUP BY post_id";
      $results = $wpdb->get_results ( $query, 'ARRAY_A' );
      
      $update_cases = array();
      $ids_to_be_updated = array();

      foreach ( $results as $result ) {
          $meta_keys = explode( '##', $result['meta_keys'] );
          $meta_values = explode( '##', $result['meta_values'] );

          if ( count( $meta_keys ) == count( $meta_values ) ) {
              $keys_values = array_combine( $meta_keys, $meta_values );

              $from_date = (isset($keys_values['_sale_price_dates_from'])) ? $keys_values['_sale_price_dates_from'] : '';
              $to_date = (isset($keys_values['_sale_price_dates_to'])) ? $keys_values['_sale_price_dates_to'] : '';

              $price = sm_woo_get_price( trim($keys_values['_regular_price']), trim($keys_values['_sale_price']), $from_date, $to_date);
              
              $price = trim($price); // For handling when both price and sales price are null

              $meta_value = (!empty($price)) ? $price : '';

              update_post_meta($result['post_id'], '_price', $meta_value);
            }
        }
   }
}
