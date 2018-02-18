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
