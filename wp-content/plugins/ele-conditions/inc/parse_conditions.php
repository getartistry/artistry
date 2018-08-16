<?php
function elecond_parse_condition($condition,$debug=false){
   
    $operators = [ '>' , '<' , '!=' , '!==' , '==' , '===' , '<=' , '>=' ];
    $clean = ['$','()','{','}'];
    $operator = '';
    foreach($operators as $op){
      if ( $operator ) break;
      list ($a,$b) = explode($op,$condition);
      $a = str_replace( $clean , '' , trim($a) );
      $b = str_replace( $clean , '' , trim($b) );
      if ( $a && $b ) {
        $operator=$op;
      }
    }
    
    $values = elecond_prepare_values([$a,$b]);

    $cmp1 = isset( $values[$a] ) ? ( $values[$a] ) : ( $a );

    $cmp2 = isset( $values[$b] ) ? ( $values[$b] ) : ( $b );
  
    $cmp1 = $cmp1=='true' ? true : $cmp1;
    $cmp2 = $cmp2=='true' ? true : $cmp2;
  
  //echo $cmp1.$operator.$cmp2;
    switch($operator){
        case '=':
            $result = ($cmp1 == $cmp2) ? true : false;
            break;
        case '==':
            $result = ($cmp1 == $cmp2) ? true : false;
            break;
        case '!=':
            $result = ($cmp1 != $cmp2) ? true : false;
            break;
        case '===':
            $result = ($cmp1 === $cmp2) ? true : false;
            break;
        case '!==':
            $result = ($cmp1 !== $cmp2) ? true : false;
            break;
         case '<=':
            $result = ($cmp1 <= $cmp2) ? true : false;
            break;
         case '>=':
            $result = ($cmp1 >= $cmp2) ? true : false;
            break;
         case '>':
            $result = ($cmp1 > $cmp2) ? true : false;
            break;
         case '<':
            $result = ($cmp1 < $cmp2) ? true : false;
            break;
      default:
            $result = true;
            break;
    }
    if ($debug && ( current_user_can('editor') || current_user_can('administrator') ))
      elecond_debug($condition, $a, $b, $cmp1,$cmp2,$operator,$result);
  return $result;
}


/*-----------------------------------------------------------------------------------*/
/* Value preps
/*-----------------------------------------------------------------------------------*/
function elecond_prepare_values($keys,$post=NULL){
  global $wp_query;
  if( !isset($wp_query)) return;
  if ($post!=NULL) global $post;
  if ($post->ID) $var=$post; 
    else $var=$wp_query->get_queried_object();
/**  Set custom vars **/
  $id=$var->ID;
  if($id) $permalink=get_permalink($id);
  $name=get_queried_object()->name;
  if ( isset($var->term_id) ) $description=do_shortcode(wpautop($var->description)); else {
    if ( isset($var->description) ) $var->description=NULL; /// to work only with terms descriptions
  } 

  if(!is_single() && !$content) $content=true; // if it is an elementor format it would not work... please research a little bit | nu merge sa se cheme elementor de id cand este in ea...
  $post_excerpt = $post_excerpt ? true : false;

  // add your own custom vars

  $custom_vars=apply_filters( 'eleconditions_vars', $custom_vars ); 
  
    $value==array();
  
  if ( isset($custom_vars) ) foreach($custom_vars as $ck=>$cv){
    $$ck = $cv;
  }

/** end seting custom vars **/
// adding the values in keys

  if ( isset($keys)) {
    foreach ($keys as $key) {
      $value[$key]=isset($$key) ? $$key : $var->$key; //echo "<br/> ".$key." "; print_r($var->$key);
      if ($value[$key]=="") { //echo "<br/> ".$key." "; print_r($custom_field);
        //Daca nu a gasit nici o proprietate a obeictului cauta custom field
        if ($post->ID) {
          $custom_field=get_post_meta( $post->ID, $key, true); //echo "<br/>..".$key." :"; print_r($custom_field);
        }
        $value[$key]=$custom_field ? $custom_field : "";//pune custom field sau sa stearga keya daca nu are valoare 
        if ($value[$key]=="" && function_exists("getProductAttributes") ) $value[$key] = getProductAttributes($post->ID,$key); // iau custom product attribute
        if ($value[$key]=="" && function_exists('get_field') && $var->term_id) $value[$key] = get_field($key, $var->taxonomy.'_'.$var->term_id);// iau custom field de la taxonomie
        if ($value[$key]=="") $value[$key]=$wp_query->query_vars[$key]; //get query_vars
      }
    }
  }
  //print_r($value);
  return $value;
}

function elecond_debug($condition,$var1,$var2,$val1,$val2,$operator,$result){
  $color1 = $var1 == $val1 ? "lightpink" : "lightgreen";
  $color2 = $var2 == $val2 ? "lightpink" : "lightgreen";
  $color3 = "lightblue";
  $color4 = "orange";
  $color5 = "black";
  $result = $result ? "true" : "false";
?>
<style>
  .ele_cond_debug span{
    padding:2px 5px;
  }
</style>
<div style="font-family:monospace; line-height: 2em;" class="ele_cond_debug">
 
  <div style="color:lightgray;">
   <span style="background:<?php echo $color5;?>;"><?php echo $condition; ?></span>   
  </div>
  <div>
    <span style="background:<?php echo $color1;?>;"><?php echo $val1;?></span> 
    <span style="background:<?php echo $color3;?>;"><?php echo $operator;?></span> 
    <span style="background:<?php echo $color2;?>;"><?php echo $val2;?></span> 
    <span style="background:<?php echo $color5;?>; color:white; font-weight:bold;">-&gt;</span>
    <span style="background:<?php echo $color4;?>;"><?php echo $result;?></span>
  </div>
<?php
}