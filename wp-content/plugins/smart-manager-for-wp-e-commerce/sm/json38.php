<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


ob_start();

// Code for checking whether user is valid or not
$current_user_role = '';

if (!function_exists('wp_get_current_user')) {
    require_once (ABSPATH . 'wp-includes/pluggable.php'); // Sometimes conflict with SB-Welcome Email Editor
}
$current_user = wp_get_current_user(); 
if ( !($current_user instanceof WP_User) )
   exit;

if ( !isset( $current_user->roles[0] ) ) {
    $current_user_role = array_values( $current_user->roles );
} else {
    $current_user_role = $current_user->roles;
}

//Fix for the client
if ( !empty( $current_user->caps ) ) {
    $caps = array_keys($current_user->caps);
    $current_user_role[0] = (!empty($caps)) ? $caps[0] : '';
}


$sm_privilege_option = (!empty($current_user_role[0])) ? get_option('sm_'.$current_user_role[0].'_dashboard') : '';

if ( !is_user_logged_in() || !is_admin() || ( $current_user_role[0] != 'administrator' && empty($sm_privilege_option) ) ) {
    exit;
}

global $sm_text_domain;

// creating a domain name for mutilingual
$sm_text_domain = (defined('SM_TEXT_DOMAIN')) ? SM_TEXT_DOMAIN : 'smart-manager-for-wp-e-commerce';

include_once (ABSPATH . 'wp-includes/wp-db.php');
include_once (ABSPATH . 'wp-includes/functions.php');
include_once (ABSPATH . 'wp-admin/includes/screen.php'); // Fix to handle the WPeC 3.8.10 and Higher versions
require_once( WP_PLUGIN_DIR . '/wp-e-commerce/wpsc-admin/includes/product-functions.php' );     // Fix for undefined function 'wpsc_product_has_children'
include_once (WP_PLUGIN_DIR . '/wp-e-commerce/wpsc-core/wpsc-functions.php');
include_once (WP_PLUGIN_DIR . '/wp-e-commerce/wpsc-includes/purchaselogs.class.php');

global $wp_version;

if (version_compare ( $wp_version, '4.0', '>=' )) {
    global $locale;
    load_textdomain( $sm_text_domain, WP_PLUGIN_DIR . '/' . dirname( dirname(plugin_basename( __FILE__ ))) . '/languages/smart-manager-' . $locale . '.mo' );
} else {
    load_textdomain( $sm_text_domain, WP_PLUGIN_DIR . '/' . dirname(dirname(plugin_basename( __FILE__ ))) . '/languages/smart-manager-' . WPLANG . '.mo' );
}



//checking the memory limit allocated
$mem_limit = ini_get('memory_limit');
if(intval(substr($mem_limit,0,strlen($mem_limit)-1)) < 64 ){
	ini_set('memory_limit','128M'); 
}

$result = array ();
$encoded = array ();

$offset = (isset ( $_POST ['start'] )) ? $_POST ['start'] : 0;
$limit = (isset ( $_POST ['limit'] )) ? $_POST ['limit'] : 100;

// For pro version check if the required file exists
if (file_exists ( WP_PLUGIN_DIR . '/' . dirname( dirname(plugin_basename( __FILE__ ))) . '/pro/sm38.php' )) {
	if ( !defined( 'SMPRO' ) ) define ( 'SMPRO', true );
	include_once ( WP_PLUGIN_DIR . '/' . dirname( dirname(plugin_basename( __FILE__ ))) . '/pro/sm38.php' );
} else {
	if ( !defined( 'SMPRO' ) ) define ( 'SMPRO', false );
}

function get_regions_ids(){ //getting the list of regions
	global $wpdb;
	$query   	 = "SELECT id,name FROM " . WPSC_TABLE_REGION_TAX;
	$reg_results = $wpdb->get_results ( $query,'ARRAY_A');

	foreach($reg_results as $reg_result){
		$regions_ids[$reg_result['id']] = $reg_result['name'];
	}
	return $regions_ids;
}
		
// getting the active module

$active_module = (isset($_POST ['active_module']) ? $_POST ['active_module'] : 'Products');
//$active_module = $_POST ['active_module'];

// function to return term_taxonomy_ids of a term name
function get_term_taxonomy_ids( $term_name ) {
    global $wpdb;
    
    $query = "SELECT DISTINCT term_taxonomy.term_taxonomy_id AS term_taxonomy_id
                    FROM {$wpdb->prefix}term_taxonomy AS term_taxonomy
                    LEFT JOIN {$wpdb->prefix}terms AS terms ON ( term_taxonomy.term_id = terms.term_id )
                    WHERE term_taxonomy.taxonomy IN ( 'wpsc_product_category', 'wpsc-variation' )
                            AND terms.name IN ( $term_name )
                    ORDER BY term_taxonomy.term_taxonomy_id";
    $term_taxonomy_ids = $wpdb->get_col( $query );

    return $term_taxonomy_ids;
}

function get_log_ids( $result ) {
    return $result['last_order_id'];
}

function get_all_matched_purchase_log_ids( $search_on = '' ) {
    global $wpdb;

    $purchase_log_ids = array();
    
    $search_condn_checkout_form_query = "SELECT DISTINCT log_id
                                                FROM " . WPSC_TABLE_SUBMITED_FORM_DATA . "
                                                WHERE value LIKE '%$search_on%'
                                        ";
    
    $checkout_form_purchase_log_ids = $wpdb->get_col( $search_condn_checkout_form_query );
    
    if ( !empty( $checkout_form_purchase_log_ids ) ) {
        return " OR wtpl.id IN ( " . implode( ',', $checkout_form_purchase_log_ids ) . " )";
    }
    return '';
}


// Searching a product in the grid
function get_data_wpsc_38 ( $post, $offset, $limit, $is_export = false ) {
	global $wpdb,$post_status,$parent_sort_id,$order_by;
	$_POST = $post;     // Fix: PHP 5.4
        $regions_ids = get_regions_ids();		
	$country_results = $wpdb->get_results( "SELECT isocode, country FROM " . WPSC_TABLE_CURRENCY_LIST, 'ARRAY_A' );
        $country_data = array();
        foreach ( $country_results as $country_result ) {
            $country_data[$country_result['isocode']] = $country_result['country'];
        }

//Code to handle the show variations query
function variation_query_params(){
	global $wpdb,$post_status,$parent_sort_id,$order_by;
	$post_status    = "('publish', 'pending', 'draft', 'inherit') AND {$wpdb->prefix}posts.ID NOT IN 
							( SELECT product.ID FROM {$wpdb->prefix}posts AS product 
							LEFT JOIN {$wpdb->prefix}posts AS product_variation 
							ON product_variation.ID = product.post_parent 
							WHERE product_variation.post_status = 'trash' ) ";
	$parent_sort_id = " ,if({$wpdb->prefix}posts.post_parent = 0,{$wpdb->prefix}posts.id,{$wpdb->prefix}posts.post_parent - 1 + ({$wpdb->prefix}posts.id)/pow(10,char_length(cast({$wpdb->prefix}posts.id as char)))	) as parent_sort_id";
	$order_by       = " ORDER BY parent_sort_id desc";
}

        
	// getting the active module
	// $active_module = $_POST ['active_module'];
        $active_module = (isset($_POST ['active_module']) ? $_POST ['active_module'] : 'Products');

	 variation_query_params ();
	
	if ( $is_export === true ) {
		$limit_string = "";
		$image_size = "full";
	} else {
		$limit_string = "LIMIT $offset,$limit";
		$image_size = "thumbnail";
	}

    $wpdb->query ( "SET SESSION group_concat_max_len=999999" );// To increase the max length of the Group Concat Functionality

    $view_columns = (!empty($_POST ['viewCols'])) ? json_decode ( stripslashes ( $_POST ['viewCols'] ) ) : '';
    
	if ($active_module == 'Products') { // <-products

		$wpsc_default_image = WP_PLUGIN_URL . '/wp-e-commerce/wpsc-theme/wpsc-images/noimage.png';
		if (isset ( $_POST ['incVariation'] ) && $_POST ['incVariation'] == 'true') {
			$show_variation = true;
		} else { // query params for non-variation products
			$show_variation = false;
			$post_status = "('publish', 'pending', 'draft','private')";
			$parent_sort_id = '';
			$order_by = " ORDER BY {$wpdb->prefix}posts.id desc";
		}

                $query_ids = "SELECT `ID` FROM {$wpdb->prefix}posts 
                            WHERE `post_type` = 'wpsc-product' 
                                AND `post_status` = 'publish' 
                                AND `post_parent`=0 
                                AND `ID` NOT IN ( SELECT distinct `post_parent` 
                                                  FROM {$wpdb->prefix}posts WHERE `post_parent`>0)";
                
                $result_ids = $wpdb->get_col ( $query_ids );
                $num_ids = $wpdb->num_rows;

                if ($num_ids > 0) {
                    for ($i=0;$i<sizeof($result_ids);$i++) {
                        $simple_ids [$result_ids[$i]] = 0;
                    }
                }
                
		// if max-join-size issue occurs
		$query = "SET SQL_BIG_SELECTS=1;";
		$wpdb->query ( $query );


         $results_trash = array();
        
        //Code to get the ids of all the products whose post_status is thrash
        $query_trash = "SELECT ID FROM {$wpdb->prefix}posts 
                        WHERE post_status = 'trash'
                            AND post_type IN ('product')";
        $results_trash = $wpdb->get_col( $query_trash );
        $rows_trash = $wpdb->num_rows;
        
        $query_deleted = "SELECT distinct products.post_parent 
                            FROM {$wpdb->prefix}posts as products 
                            WHERE NOT EXISTS (SELECT * FROM {$wpdb->prefix}posts WHERE ID = products.post_parent) 
                              AND products.post_parent > 0 
                              AND products.post_type = 'product_variation'";
        $results_deleted = $wpdb->get_col( $query_deleted );
        $rows_deleted = $wpdb->num_rows;
        
        for ($i=sizeof($results_trash),$j=0;$j<sizeof($results_deleted);$i++,$j++ ) {
            $results_trash[$i] = $results_deleted[$j];
        }
        
        
        if ($rows_trash > 0 || $rows_deleted > 0) {
            $trash_id = " AND {$wpdb->prefix}posts.post_parent NOT IN (" .implode(",",$results_trash). ")";
        }
        else {
            $trash_id = "";
        }

        //Query to get the Category Ids

        $query_categories = "SELECT {$wpdb->prefix}posts.id as id,
                                GROUP_CONCAT(distinct {$wpdb->prefix}term_relationships.term_taxonomy_id order by {$wpdb->prefix}term_relationships.object_id SEPARATOR '###') AS term_taxonomy_id
                            FROM {$wpdb->prefix}posts
                                    JOIN {$wpdb->prefix}term_relationships ON ({$wpdb->prefix}posts.id = {$wpdb->prefix}term_relationships.object_id)
                            WHERE {$wpdb->prefix}posts.post_status IN $post_status
                                    AND {$wpdb->prefix}posts.post_type LIKE 'wpsc-product'
                                    $trash_id
                            GROUP BY id";
        $records_categories = $wpdb->get_results ( $query_categories, 'ARRAY_A' );

        $category_ids_all = array();

        foreach ($records_categories as $records_category) {
            $category_ids_all[$records_category['id']] = $records_category['term_taxonomy_id'];
        }

        //Query to get the term_taxonomy_id for all the product categories
        $query_terms = "SELECT terms.name, wt.term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy AS wt
                        JOIN {$wpdb->prefix}terms AS terms ON (wt.term_id = terms.term_id)
                        WHERE wt.taxonomy LIKE 'wpsc_product_category'";
        $results = $wpdb->get_results( $query_terms, 'ARRAY_A' );
        $rows_terms = $wpdb->num_rows;

        
        if ( $rows_terms > 0 ) {
            foreach ( $results as $result ) {
                $term_taxonomy[$result['term_taxonomy_id']] = $result['name']; 
            }
        }

		// CAST(GROUP_CONCAT(DISTINCT term_relationships.term_taxonomy_id order by term_relationships.term_taxonomy_id SEPARATOR ',') AS CHAR(1000)) AS term_taxonomy_id

		$post_meta_select = (!empty($_POST['func_nm']) && $_POST['func_nm'] == 'exportCsvWpsc') ? ", GROUP_CONCAT(prod_othermeta.meta_key order by prod_othermeta.meta_id SEPARATOR '###') AS prod_othermeta_key,
					GROUP_CONCAT(prod_othermeta.meta_value order by prod_othermeta.meta_id SEPARATOR '###') AS prod_othermeta_value" : "";
		
		$select = "SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}posts.id,
					{$wpdb->prefix}posts.post_title,
					{$wpdb->prefix}posts.post_title as post_title_search,
					{$wpdb->prefix}posts.post_content,
					{$wpdb->prefix}posts.post_excerpt,
					{$wpdb->prefix}posts.post_status,
					{$wpdb->prefix}posts.post_parent
					$post_meta_select
					$parent_sort_id";

        //Used as an alternative to the SQL_CALC_FOUND_ROWS function of MYSQL Database
        $select_count = "SELECT COUNT(*) as count"; // To get the count of the number of rows generated from the above select query

        $search = "";
        $search_condn = "";

        //Code to clear the advanced search temp table
        if (empty($_POST['search_query']) || empty($_POST['search_query'][0]) || !empty($_POST['searchText']) ) {
            $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp");
            delete_option('sm_advanced_search_query');
        }

        $sm_advanced_search_results_persistent = 0; //flag to handle persistent search results

        //Advanced Search Code       
        if ((!empty($_POST['search_query']) && !empty($_POST['search_query'][0])) || (!empty($_POST['searchText'])) ) {

            if (empty($_POST['searchText'])) {
                $search_query_diff = (get_option('sm_advanced_search_query') != '') ? array_diff($_POST['search_query'],get_option('sm_advanced_search_query')) : $_POST['search_query'];
            } else {
                $search_query_diff = '';
            }
            
            if (!empty($search_query_diff)) {

                $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp"); // query to reset advanced search temp table

                $advanced_search_query = array();
                $i = 0;

                update_option('sm_advanced_search_query',$_POST['search_query']);

                foreach ($_POST['search_query'] as $search_string_array) {

                    $search_string_array = json_decode(stripslashes($search_string_array),true);

                    if (is_array($search_string_array)) {

                        $advanced_search_query[$i] = array();
                        $advanced_search_query[$i]['cond_posts'] = '';
                        $advanced_search_query[$i]['cond_postmeta'] = '';
                        $advanced_search_query[$i]['cond_terms'] = '';

                        $advanced_search_query[$i]['cond_postmeta_col_name'] = '';
                        $advanced_search_query[$i]['cond_postmeta_col_value'] = '';
                        $advanced_search_query[$i]['cond_postmeta_operator'] = '';

                        $advanced_search_query[$i]['cond_terms_col_name'] = '';
                        $advanced_search_query[$i]['cond_terms_col_value'] = '';
                        $advanced_search_query[$i]['cond_terms_operator'] = '';

                        $search_value_is_array = 0; //flag for array of search_values

                        foreach ($search_string_array as $search_string) {

                            $search_col = (!empty($search_string['col_name'])) ? $search_string['col_name'] : '';
                            $search_operator = (!empty($search_string['operator'])) ? $search_string['operator'] : '';
                            $search_data_type = (!empty($search_string['type'])) ? $search_string['type'] : 'string';
                            $search_value = (!empty($search_string['value'])) ? $search_string['value'] : (($search_data_type == "number") ? '0' : '');

                            if (!empty($search_string['table_name']) && $search_string['table_name'] == $wpdb->prefix.'posts') {

                                if ($search_data_type == "number") {
                                    $advanced_search_query[$i]['cond_posts'] .= $search_string['table_name'].".".$search_col . " ". $search_operator ." " . $search_value;
                                } else {
                                    if ($search_operator == 'is') {
                                        $advanced_search_query[$i]['cond_posts'] .= $search_string['table_name'].".".$search_col . " LIKE '" . $search_value . "'";
                                    } else if ($search_operator == 'is not') {
                                        $advanced_search_query[$i]['cond_posts'] .= $search_string['table_name'].".".$search_col . " NOT LIKE '" . $search_value . "'";
                                    } else {
                                        $advanced_search_query[$i]['cond_posts'] .= $search_string['table_name'].".".$search_col . " ". $search_operator ."'%" . $search_value . "%'";
                                    }
                                    
                                }
                                
                                $advanced_search_query[$i]['cond_posts'] .= " AND ";

                            } else if (!empty($search_string['table_name']) && $search_string['table_name'] == $wpdb->prefix.'postmeta') {
                       
                                if ($search_value == 'inches') {
                                    $search_value = 'in';
                                } else if ($search_value == 'pounds') {
                                    $search_value = 'pound';
                                } else if ($search_value == 'ounces') {
                                    $search_value = 'ounce';
                                } else if ($search_value == 'grams') {
                                    $search_value = 'gram';
                                } else if ($search_value == 'kilograms') {
                                    $search_value = 'kilogram';
                                }

                                $advanced_search_query[$i]['cond_postmeta_col_name'] .= $search_col;
                                $advanced_search_query[$i]['cond_postmeta_col_value'] .= $search_value;

                                if ($search_col != '_wpsc_price' && $search_col != '_wpsc_special_price' && $search_col != '_wpsc_sku'
                                     && $search_col != '_wpsc_stock') {

                                    $search_col_temp = $search_col;
                                    $search_value_temp = $search_value;
                                    $search_col = '_wpsc_product_metadata';
                                    if ($search_operator == 'is' || $search_operator == 'is not') {
                                        $search_value = '%'.$search_col_temp.'";s:'. strlen($search_value_temp) .':%"'.$search_value_temp.'";%';
                                    } else {
                                        $search_value = '%'.$search_col_temp.'%'.$search_value_temp.'%';    
                                    }
                                    
                                }

                                if ($search_data_type == "number") {

                                    if ($search_col != '_wpsc_price' && $search_col != '_wpsc_special_price' && $search_col != '_wpsc_sku'
                                     && $search_col != '_wpsc_stock') {
                                        $advanced_search_query[$i]['cond_postmeta'] .= " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value ". $search_operator ." " . $search_value . " )";
                                    } else {
                                        $advanced_search_query[$i]['cond_postmeta'] .= " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value ". $search_operator ." " . $search_value . " )";
                                    }

                                    
                                    $advanced_search_query[$i]['cond_postmeta_operator'] .= $search_operator;
                                } else {
                                    if ($search_operator == 'is') {

                                        $advanced_search_query[$i]['cond_postmeta_operator'] .= 'LIKE';
                                        $advanced_search_query[$i]['cond_postmeta'] .= " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value LIKE '" . $search_value . "'" . " )";

                                        
                                    } else if ($search_operator == 'is not') {

                                        $advanced_search_query[$i]['cond_postmeta_operator'] .= 'NOT LIKE';                                    
                                        $advanced_search_query[$i]['cond_postmeta'] .= " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value NOT LIKE '" . $search_value . "'" . " )";
                                        
                                    } else {

                                        $advanced_search_query[$i]['cond_postmeta_operator'] .= $search_operator;
                                        if ($search_col != '_wpsc_price' && $search_col != '_wpsc_special_price' && $search_col != '_wpsc_sku'
                                            && $search_col != '_wpsc_stock') {

                                            $advanced_search_query[$i]['cond_postmeta'] .= " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value ". $search_operator ."'" . $search_value . "'" . " )";
                                        } else {
                                            $advanced_search_query[$i]['cond_postmeta'] .= " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value ". $search_operator ."'%" . $search_value . "%'" . " )";
                                        }
                                    }
                                    
                                }

                                $advanced_search_query[$i]['cond_postmeta'] .= " AND ";
                                $advanced_search_query[$i]['cond_postmeta_col_name'] .= " AND ";
                                $advanced_search_query[$i]['cond_postmeta_col_value'] .= " AND ";
                                $advanced_search_query[$i]['cond_postmeta_operator'] .= " AND ";


                            } else if (!empty($search_string['table_name']) && $search_string['table_name'] == $wpdb->prefix.'term_relationships') {


                                $advanced_search_query[$i]['cond_terms_col_name'] .= $search_col;
                                $advanced_search_query[$i]['cond_terms_col_value'] .= $search_value;

                                if ($search_operator == 'is') {
                                    $advanced_search_query[$i]['cond_terms'] .= " ( ". $wpdb->prefix ."term_taxonomy.taxonomy LIKE '". $search_col . "' AND ". $wpdb->prefix ."terms.slug LIKE '" . $search_value . "'" . " )";
                                    $advanced_search_query[$i]['cond_terms_operator'] .= 'LIKE';
                                } else if ($search_operator == 'is not') {
                                    $advanced_search_query[$i]['cond_terms'] .= " ( ". $wpdb->prefix ."term_taxonomy.taxonomy LIKE '". $search_col . "' AND ". $wpdb->prefix ."terms.slug NOT LIKE '" . $search_value . "'" . " )";
                                    $advanced_search_query[$i]['cond_terms_operator'] .= 'NOT LIKE';
                                } else {
                                    $advanced_search_query[$i]['cond_terms'] .= " ( ". $wpdb->prefix ."term_taxonomy.taxonomy LIKE '". $search_col . "' AND ". $wpdb->prefix ."terms.slug ". $search_operator ."'%" . $search_value . "%'" . " )";
                                    $advanced_search_query[$i]['cond_terms_operator'] .= $search_operator;
                                }

                                $advanced_search_query[$i]['cond_terms'] .= " AND ";
                                $advanced_search_query[$i]['cond_terms_col_name'] .= " AND ";
                                $advanced_search_query[$i]['cond_terms_col_value'] .= " AND ";
                                $advanced_search_query[$i]['cond_terms_operator'] .= " AND ";

                            }
                        }

                        $advanced_search_query[$i]['cond_posts'] = (!empty($advanced_search_query[$i]['cond_posts'])) ? substr( $advanced_search_query[$i]['cond_posts'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_postmeta'] = (!empty($advanced_search_query[$i]['cond_postmeta'])) ? substr( $advanced_search_query[$i]['cond_postmeta'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_terms'] = (!empty($advanced_search_query[$i]['cond_terms'])) ? substr( $advanced_search_query[$i]['cond_terms'], 0, -4 ) : '';

                        $advanced_search_query[$i]['cond_postmeta_col_name'] = (!empty($advanced_search_query[$i]['cond_postmeta_col_name'])) ? substr( $advanced_search_query[$i]['cond_postmeta_col_name'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_postmeta_col_value'] = (!empty($advanced_search_query[$i]['cond_postmeta_col_value'])) ? substr( $advanced_search_query[$i]['cond_postmeta_col_value'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_postmeta_operator'] = (!empty($advanced_search_query[$i]['cond_postmeta_operator'])) ? substr( $advanced_search_query[$i]['cond_postmeta_operator'], 0, -4 ) : '';

                        $advanced_search_query[$i]['cond_terms_col_name'] = (!empty($advanced_search_query[$i]['cond_terms_col_name'])) ? substr( $advanced_search_query[$i]['cond_terms_col_name'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_terms_col_value'] = (!empty($advanced_search_query[$i]['cond_terms_col_value'])) ? substr( $advanced_search_query[$i]['cond_terms_col_value'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_terms_operator'] = (!empty($advanced_search_query[$i]['cond_terms_operator'])) ? substr( $advanced_search_query[$i]['cond_terms_operator'], 0, -4 ) : '';
                    }
                    $i++;
                }
            } else {
                if (!empty($_POST['searchText'])) {
                    $advanced_search_query[0]['cond_posts'] = $wpdb->prefix.'posts'.".id LIKE '" . $_POST['searchText'] . "'";
                    $advanced_search_query[1]['cond_posts'] = $wpdb->prefix.'posts'.".post_title LIKE '%" . $_POST['searchText'] . "%'";
                    $advanced_search_query[2]['cond_posts'] = $wpdb->prefix.'posts'.".post_status LIKE '%" . $_POST['searchText'] . "%'";
                    $advanced_search_query[3]['cond_posts'] = $wpdb->prefix.'posts'.".post_content LIKE '%" . $_POST['searchText'] . "%'";
                    $advanced_search_query[4]['cond_posts'] = $wpdb->prefix.'posts'.".post_excerpt LIKE '%" . $_POST['searchText'] . "%'";

                    $advanced_search_query[5]['cond_postmeta'] = $wpdb->prefix.'postmeta'.".meta_value LIKE '%". $_POST['searchText'] . "%'";

                    $advanced_search_query[6]['cond_terms'] = $wpdb->prefix ."term_taxonomy.taxonomy LIKE '%". $_POST['searchText'] . "%'";
                    $advanced_search_query[7]['cond_terms'] = $wpdb->prefix ."terms.slug LIKE '%" . $_POST['searchText'] . "%'" ;
                    $advanced_search_query[8]['cond_terms'] = $wpdb->prefix ."terms.name LIKE '%" . $_POST['searchText'] . "%'" ;

                } else {
                    $sm_advanced_search_results_persistent = 1;
                }
            }
        }

    /*    if (isset ( $_POST ['searchText'] ) && $_POST ['searchText'] != '') {
			$search_on = trim ( $_POST ['searchText'] );

			$count_all_double_quote = substr_count( $search_on, '"' );
			if ( $count_all_double_quote > 0 ) {
				$search_ons = array_filter( array_map( 'trim', explode( $wpdb->_real_escape( '"' ), $search_on ) ) );
			} else {
				$search_on = $wpdb->_real_escape( $search_on );
                $search_ons = explode( ' ', $search_on );
			}

                        //Code for searching using modified post title
                        $query_title = "SELECT ID FROM {$wpdb->prefix}posts
                                        WHERE post_title LIKE '%$search_on%'
                                            AND post_type = 'wpsc-product'";
                        $records_title = $wpdb->get_col ( $query_title );
                        $rows_title = $wpdb->num_rows;

                        if ($rows_title > 0) {
                            $search_title = "OR products.post_parent IN (
                                                    SELECT ID FROM {$wpdb->prefix}posts
                                                    WHERE post_title LIKE '%$search_on%'
                                                        AND post_type = 'wpsc-product')";
                        }
                        else {
                            $search_title = " ";
                        }
                        
			if ( is_array( $search_ons ) && ! empty( $search_ons ) ) {
				$term_taxonomy_ids = get_term_taxonomy_ids( '"' . implode( '","', $search_ons ) . '"' );
                                $search_condn = " HAVING ";
				foreach ( $search_ons as $search_on ) {
					$search_condn .= " concat(' ',REPLACE(REPLACE(post_title_search,'(',''),')','')) LIKE '%$search_on%'
						               OR post_content LIKE '%$search_on%'
						               OR post_excerpt LIKE '%$search_on%'
						               OR if(post_status = 'publish','Published',post_status) LIKE '$search_on%'
									   OR prod_othermeta_value LIKE '%$search_on%'
                                                                            $search_title
									   OR";
                                        
				}
                                if ( is_array( $term_taxonomy_ids ) && !empty( $term_taxonomy_ids ) ) {
                                    foreach ( $term_taxonomy_ids as $term_taxonomy_id ) {
                                        $search_condn .= " term_taxonomy_id LIKE '%$term_taxonomy_id%' OR";
                                    }
                                }
                                $search_condn = substr( $search_condn, 0, -2 );
			} else {
				$term_taxonomy_ids = get_term_taxonomy_ids( '"' . $search_on . '"' );
                                $search_condn = " HAVING concat(' ',REPLACE(REPLACE(post_title_search,'(',''),')','')) LIKE '%$search_on%'
						               OR post_content LIKE '%$search_on%'
						               OR post_excerpt LIKE '%$search_on%'
						               OR if(post_status = 'publish','Published',post_status) LIKE '$search_on%'
									   OR prod_othermeta_value LIKE '%$search_on%'
                                                                               $search_title
									   ";
                                if ( is_array( $term_taxonomy_ids ) && !empty( $term_taxonomy_ids ) ) {
                                    foreach ( $term_taxonomy_ids as $term_taxonomy_id ) {
                                        $search_condn .= " OR term_taxonomy_id LIKE '%$term_taxonomy_id%'";
                                    }
                                }
                                
			}
		} else {
			$search_condn = '';
		}

		*/

		//code for the advanced Search condition handling

		//Code for term_relationships
        if (!empty($advanced_search_query)) {

            $advanced_search_post_ids = array();

            $index_search_string = 1;

            foreach ($advanced_search_query as &$advanced_search_query_string) {

                if (!empty($advanced_search_query_string['cond_terms'])) {

                    $cond_terms_array = explode(" AND  ",$advanced_search_query_string['cond_terms']);

                    $cond_terms_col_name = (!empty($advanced_search_query_string['cond_terms_col_name'])) ? explode(" AND ",$advanced_search_query_string['cond_terms_col_name']) : '';
                    $cond_terms_col_value = (!empty($advanced_search_query_string['cond_terms_col_value'])) ?  explode(" AND ",$advanced_search_query_string['cond_terms_col_value']) : '';
                    $cond_terms_operator = (!empty($advanced_search_query_string['cond_terms_operator'])) ?  explode(" AND ",$advanced_search_query_string['cond_terms_operator']) : '';
                    
                    $cond_terms_post_ids = '';
                    $cond_cat_post_ids = array(); // array for storing the cat post ids
                    $result_terms_search = '';

                    $index=0;

                    foreach ($cond_terms_array as $cond_terms) {

                        $query_advanced_search_taxonomy_id = "SELECT {$wpdb->prefix}term_taxonomy.term_taxonomy_id
                                                              FROM {$wpdb->prefix}term_taxonomy
                                                                JOIN {$wpdb->prefix}terms
                                                                    ON ( {$wpdb->prefix}terms.term_id = {$wpdb->prefix}term_taxonomy.term_id)
                                                              WHERE ".$cond_terms;
                        $result_advanced_search_taxonomy_id = $wpdb->get_col ( $query_advanced_search_taxonomy_id );

                        if (!empty($result_advanced_search_taxonomy_id)) {

                            $terms_search_result_flag = ( $index == (sizeof($cond_terms_array) - 1) ) ? ', '.$index_search_string : ', 0';

                            $terms_advanced_search_select = "SELECT DISTINCT {$wpdb->prefix}posts.id ". $terms_search_result_flag;

                            $terms_advanced_search_select .= " ,1";

                            $terms_advanced_search_from = "FROM {$wpdb->prefix}posts
                                                        JOIN {$wpdb->prefix}term_relationships
                                                            ON ({$wpdb->prefix}term_relationships.object_id = {$wpdb->prefix}posts.id)";

                            $terms_advanced_search_where = "WHERE {$wpdb->prefix}term_relationships.term_taxonomy_id IN (". implode(",",$result_advanced_search_taxonomy_id) .")";


                            //Query to find if there are any previous conditions
                            $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
                                                                        SET flag = 0
                                                                        WHERE flag = ". $index_search_string);

                            //Code to handle condition if the ids of previous cond are present in temp table
                            if (($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_terms_search))) {
                                $terms_advanced_search_from .= " JOIN {$wpdb->base_prefix}sm_advanced_search_temp
                                                                    ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}posts.id)";

                                $terms_advanced_search_where .= "AND {$wpdb->base_prefix}sm_advanced_search_temp.flag = 0";
                            }


                            $query_terms_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
                                                            (".$terms_advanced_search_select . " " .
                                                                $terms_advanced_search_from . " " .
                                                                $terms_advanced_search_where . " " .")";

                            $result_terms_search = $wpdb->query ( $query_terms_search );

                            //query when no attr cond has been applied
                            $query_terms_search_cat_child = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
                                                                ( SELECT {$wpdb->prefix}posts.id ". $terms_search_result_flag ." ,1
                                                                    FROM {$wpdb->prefix}posts 
                                                                    JOIN {$wpdb->base_prefix}sm_advanced_search_temp
                                                                        ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}posts.post_parent)
                                                                    WHERE {$wpdb->base_prefix}sm_advanced_search_temp.cat_flag = 1 )";

                            $result_terms_search_cat_child = $wpdb->query ( $query_terms_search_cat_child );

                        }

                        $index++;
                    }

                    //Query to reset the cat_flag
                    $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp SET cat_flag = 0");

                    //Code to delete the unwanted post_ids
                    $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");

                }

                //Condn for postmeta
                if (!empty($advanced_search_query_string['cond_postmeta'])) {

                    $cond_postmeta_array = explode(" AND  ",$advanced_search_query_string['cond_postmeta']);

                    $cond_postmeta_col_name = (!empty($advanced_search_query_string['cond_postmeta_col_name'])) ? explode(" AND ",$advanced_search_query_string['cond_postmeta_col_name']) : '';
                    $cond_postmeta_col_value = (!empty($advanced_search_query_string['cond_postmeta_col_value'])) ? explode(" AND ",$advanced_search_query_string['cond_postmeta_col_value']) : '';
                    $cond_postmeta_operator = (!empty($advanced_search_query_string['cond_postmeta_operator'])) ? explode(" AND ",$advanced_search_query_string['cond_postmeta_operator']) : '';

                    $index = 0;
                    $cond_postmeta_post_ids = '';
                    $result_postmeta_search = '';
                    $postmeta_advanced_search_from = '';
                    $postmeta_advanced_search_where = '';

                    foreach ($cond_postmeta_array as $cond_postmeta) {

                        $cond_postmeta_col_name1 = (!empty($cond_postmeta_col_name[$index])) ? trim($cond_postmeta_col_name[$index]) : '';
                        $cond_postmeta_col_value1 = (!empty($cond_postmeta_col_value[$index])) ? trim($cond_postmeta_col_value[$index]) : '';
                        $cond_postmeta_operator1 = (!empty($cond_postmeta_operator[$index])) ? trim($cond_postmeta_operator[$index]) : '';

                        $postmeta_search_result_flag = ( $index == (sizeof($cond_postmeta_array) - 1) ) ? ', '.$index_search_string : ', 0';

                        //Query to find if there are any previous conditions
                        $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
                                                                    SET flag = 0
                                                                    WHERE flag = ". $index_search_string);

                        //Code to handle condition if the ids of previous cond are present in temp table
                        if (($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_postmeta_search))) {
                            $postmeta_advanced_search_from = " JOIN {$wpdb->base_prefix}sm_advanced_search_temp
                                                                ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}postmeta.post_id)";

                            $postmeta_advanced_search_where = " AND {$wpdb->base_prefix}sm_advanced_search_temp.flag = 0";
                        }

                        if ($cond_postmeta_col_name1 == 'weight' || $cond_postmeta_col_name1 == 'height' || $cond_postmeta_col_name1 == 'width'
                             || $cond_postmeta_col_name1 == 'length' || $cond_postmeta_col_name1 == 'local' || $cond_postmeta_col_name1 == 'international') {

                            $postmeta_advanced_search_query = "SELECT DISTINCT temp.post_id ". $postmeta_search_result_flag ." ,0
                                                                        FROM (SELECT {$wpdb->prefix}postmeta.post_id, SUBSTRING_INDEX( SUBSTRING( {$wpdb->prefix}postmeta.meta_value, (
                                                                                        INSTR( {$wpdb->prefix}postmeta.meta_value,  '". $cond_postmeta_col_name1 ."' ) + LENGTH('".$cond_postmeta_col_name1."') ) +4 ) ,';', 1) 
                                                                                        AS ". $cond_postmeta_col_name1 ."
                                                                                    FROM  {$wpdb->prefix}postmeta ". $postmeta_advanced_search_from ."
                                                                                    WHERE meta_key LIKE '_wpsc_product_metadata'
                                                                                        $postmeta_advanced_search_where
                                                                                    GROUP BY post_id
                                                                                        HAVING ".$cond_postmeta_col_name1." ". $cond_postmeta_operator1 ." ". $cond_postmeta_col_value1 .") AS temp";
                        } else {

                            $postmeta_advanced_search_query = "SELECT DISTINCT {$wpdb->prefix}postmeta.post_id ". $postmeta_search_result_flag ." ,0
                                                                                FROM {$wpdb->prefix}postmeta ". $postmeta_advanced_search_from ."
                                                                                WHERE ".$cond_postmeta . " " .
                                                                                    $postmeta_advanced_search_where;
                        }

                        $query_postmeta_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
                                                        (". $postmeta_advanced_search_query .")";
                        $result_postmeta_search = $wpdb->query ( $query_postmeta_search );

                        $index++;
                    }

                    //Query to delete the unwanted post_ids
                    $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");
                    
                }

                //Cond for posts
                if (!empty($advanced_search_query_string['cond_posts'])) {

                    $cond_posts_array = explode(" AND ",$advanced_search_query_string['cond_posts']);

                    $index = 0;
                    $cond_posts_post_ids = '';
                    $result_posts_search = '';

                    foreach ( $cond_posts_array as $cond_posts ) {

                        $posts_advanced_search_from = '';
                        $posts_advanced_search_where = '';

                        $posts_search_result_flag = ( $index == (sizeof($cond_posts_array) - 1) ) ? ', '.$index_search_string : ', 0';

                        //Query to find if there are any previous conditions
                        $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
                                                                    SET flag = 0
                                                                    WHERE flag = ". $index_search_string);


                        //Code to handle condition if the ids of previous cond are present in temp table
                        if (($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_posts_search))) {
                            $posts_advanced_search_from = " JOIN {$wpdb->base_prefix}sm_advanced_search_temp
                                                                ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}posts.id)";

                            $posts_advanced_search_where = " AND {$wpdb->base_prefix}sm_advanced_search_temp.flag = 0";
                        }

                        $query_posts_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
                                                        (SELECT DISTINCT {$wpdb->prefix}posts.id ". $posts_search_result_flag ." ,0
                                                        FROM {$wpdb->prefix}posts ". $posts_advanced_search_from ."
                                                        WHERE ".$cond_posts . 
                                                            "AND {$wpdb->prefix}posts.post_type = 'wpsc-product'" .
                                                            $posts_advanced_search_where .")";
                        $result_posts_search = $wpdb->query ( $query_posts_search );

                        $index++;
                    }

                    //Query to delete the unwanted post_ids
                    $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");

                }
                $index_search_string++;
            }
        }

        //for combined
        $advanced_search_from = '';
        $advanced_search_where = '';
        if (!empty($advanced_search_query) || $sm_advanced_search_results_persistent == 1) {

            $advanced_search_from = " JOIN {$wpdb->base_prefix}sm_advanced_search_temp
                                        ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}posts.id)";
            $advanced_search_where = " AND {$wpdb->base_prefix}sm_advanced_search_temp.flag > 0";
        }

        $from = "FROM {$wpdb->prefix}posts";

		$from_export = "FROM {$wpdb->prefix}posts
        						LEFT JOIN {$wpdb->prefix}postmeta as prod_othermeta ON (prod_othermeta.post_id = {$wpdb->prefix}posts.id and
        						  prod_othermeta.meta_key IN ('_wpsc_price', '_wpsc_special_price', '_wpsc_sku', '_wpsc_stock', '_thumbnail_id','_wpsc_product_metadata') )
                                LEFT JOIN {$wpdb->prefix}term_relationships AS term_relationships ON ( {$wpdb->prefix}posts.id = term_relationships.object_id )";

        $where = "WHERE {$wpdb->prefix}posts.post_status IN  $post_status
					AND {$wpdb->prefix}posts.post_type = 'wpsc-product'
                    $trash_id";

		$group_by = " GROUP BY {$wpdb->prefix}posts.id ";
		
        
        $query  = (!empty($_POST['func_nm']) && $_POST['func_nm'] == 'exportCsvWpsc') ? "$select $from_export $advanced_search_from $where $advanced_search_where $group_by $search_condn $order_by $limit_string;" : "$select $from $advanced_search_from $where $advanced_search_where $group_by $search_condn $order_by $limit_string;";

		// $query = "$select $from_where $advanced_search_string $group_by $search_condn $order_by $limit_string;";
		$records = $wpdb->get_results ( $query );
        $num_rows = $wpdb->num_rows;

        //To get the total count
        $recordcount_query = $wpdb->get_results ( 'SELECT FOUND_ROWS() as count;','ARRAY_A');
        $num_records = $recordcount_query[0]['count'];

        if (!empty($advanced_search_query) && !empty($advanced_search_post_ids)) {
            $advanced_search_post_ids = array_unique($advanced_search_post_ids);
            $num_records = sizeof($advanced_search_post_ids);
        }

        if ($num_rows <= 0) {
			$encoded ['totalCount'] = '';
			$encoded ['items'] = '';
			$encoded ['msg'] = __( 'No Records Found', $sm_text_domain ); 
		} else {

            if (empty($_POST['func_nm'])) {

                $post_ids = array();
                foreach ($records as $record) {
                    $post_ids[] = $record->id;    
                }

                $query_postmeta = "SELECT prod_othermeta.post_id as post_id,
                                        GROUP_CONCAT(prod_othermeta.meta_key order by prod_othermeta.meta_id SEPARATOR '###') AS prod_othermeta_key,
                                        GROUP_CONCAT(prod_othermeta.meta_value order by prod_othermeta.meta_id SEPARATOR '###') AS prod_othermeta_value
                                    FROM {$wpdb->prefix}postmeta as prod_othermeta 
                                    WHERE post_id IN (". implode(",",$post_ids) .") AND
                                        prod_othermeta.meta_key IN ('_wpsc_price', '_wpsc_special_price', '_wpsc_sku', '_wpsc_stock', '_thumbnail_id','_wpsc_product_metadata')
                                    GROUP BY post_id";

                $records_postmeta = $wpdb->get_results ( $query_postmeta, 'ARRAY_A' );

                $products_meta_data = array();

                foreach ($records_postmeta as $record_postmeta) {
                
                    $products_meta_data[$record_postmeta['post_id']] = array();
                    $products_meta_data[$record_postmeta['post_id']]['prod_othermeta_key'] = $record_postmeta['prod_othermeta_key'];
                    $products_meta_data[$record_postmeta['post_id']]['prod_othermeta_value'] = $record_postmeta['prod_othermeta_value'];
                }

                foreach ($records as &$record) {
                    $record->prod_othermeta_key = (!empty($products_meta_data[$record->id]['prod_othermeta_key'])) ? $products_meta_data[$record->id]['prod_othermeta_key'] : '';
                    $record->prod_othermeta_value = (!empty($products_meta_data[$record->id]['prod_othermeta_value'])) ? $products_meta_data[$record->id]['prod_othermeta_value'] : '';
                }
            }


			foreach ( $records as &$record ) {

				$record->post_content = str_replace('"','\'',$record->post_content);
				$record->post_excerpt = str_replace('"','\'',$record->post_excerpt);

				// if ( intval($record->post_parent) == 0 ) {
    //                 $category_terms = wp_get_object_terms($record->id, 'wpsc_product_category', array( 'fields' => 'names', 'orderby' => 'name', 'order' => 'ASC' ));
    //                 $record->category = implode( ', ', $category_terms );			// To hide category name from Product's variations
    //             }
                    
                $product_type = wp_get_object_terms($record->id, 'product_type', array('fields' => 'slugs'));

                // Code to get the Category Name from the term_taxonomy_id
                
                if (isset($category_ids_all[$record->id])) {

                    $category_names = "";

                    $category_id = explode('###', $category_ids_all[$record->id]);

                      for ($j = 0; $j < sizeof($category_id); $j++) {
                            if (isset($term_taxonomy[$category_id[$j]])) {
                                $category_names .=$term_taxonomy[$category_id[$j]] . ', ';
                            }
                        }
                        if ($category_names != "") {
                            $category_names = substr($category_names, 0, -2);
                            $record->category = $category_names;
                        }

                } else {
                    $record->category = "";
                }

                $product_type = (!is_wp_error($product_type)) ? (!empty($product_type[0]) ? $product_type[0] : '') : ''; // Code for WP_Error and empty check
                $record->category = ( ( $record->post_parent > 0 && $product_type == 'simple' ) || ( $record->post_parent == 0 ) ) ? (!empty($record->category) ? $record->category : '') : '';   // To hide category name from Product's variations

                $prod_meta_values = explode ( '###', $record->prod_othermeta_value );
				$prod_meta_key    = explode ( '###', $record->prod_othermeta_key);
				if ( count( $prod_meta_key ) != count( $prod_meta_values ) ) continue;
				$prod_meta_key_values = array_combine ( $prod_meta_key, $prod_meta_values );
                
                if ( intval($record->post_parent) > 0 ) {
                    $title = get_post_field( 'post_title', $record->post_parent, 'raw' );
                    $variation_terms = wp_get_object_terms($record->id, 'wpsc-variation', array( 'fields' => 'names', 'orderby' => 'name', 'order' => 'ASC' ));
                    $record->post_title = $title . ' - (' . implode( ', ', $variation_terms ) . ')';
                }
		
//				$thumbnail = isset( $prod_meta_key_values['_thumbnail_id'] ) ? wp_get_attachment_image_src( $prod_meta_key_values['_thumbnail_id'], $image_size ) : '';
//				$record->thumbnail    = ( $thumbnail[0] != '' ) ? $thumbnail[0] : false;

                $thumbnail = wpsc_the_product_thumbnail( '', '', $record->id, '' );
                $record->thumbnail    = ( $thumbnail != '' ) ? $thumbnail : false;

				foreach ( $prod_meta_key_values as $key => $value ) {
					if (is_serialized ( $value )) {
						
						$unsez_data = unserialize ( $value );
						$unsez_data ['weight'] = wpsc_convert_weight ( $unsez_data ['weight'], "pound", $unsez_data ['weight_unit']); // get the weight by converting it to repsective unit
						
						foreach ( (array)$unsez_data as $meta_key => $meta_value ) {
							if (is_array ( $meta_value )) {
								foreach ( $meta_value as $sub_metakey => $sub_metavalue )
									(in_array ( $sub_metakey, $view_columns )) ? $record->$sub_metakey = $sub_metavalue : '';
							} else {
								(in_array ( $meta_key, $view_columns )) ? $record->$meta_key = $meta_value : '';
							}

                                                        if( $record->post_parent == 0 && wpsc_product_has_children( $record->id ) ) {
                                                            if ( $show_variation == true ) {
                                                                $record->_wpsc_price = $record->_wpsc_special_price = ' ';
                                                            } elseif ( $show_variation == false ) {
                                                                $parent_price = (version_compare ( WPSC_VERSION, '3.8.10', '>=' ) == 1) ? wpsc_product_variation_price_from( $record->id ) : wpsc_product_variation_price_available( $record->id );
                                                                $record->_wpsc_price = substr( $parent_price, 1, strlen( $parent_price ) );
                                                                $record->_wpsc_special_price = substr( $parent_price, 1, strlen( $parent_price ) );
                                                            }
                                                        }
						}

						unset($prod_meta_key_values[$value]);
					} else {
						(in_array ( $key, $view_columns )) ? $record->$key = $value : '';
					}
				}

				unset ( $record->prod_othermeta_value );
				unset ( $record->prod_meta );
				unset ( $record->prod_othermeta_key );
			}
       		}
}//products ->
elseif ($active_module == 'Orders') {

	if (SMPRO == true && function_exists ( 'get_packing_slip' ) && (!empty($_POST['label']) && $_POST['label'] == 'getPurchaseLogs')){
		$log_ids_arr = json_decode ( stripslashes ( $_POST['log_ids'] ) );
		if (is_array($log_ids_arr))
		$log_ids = implode(', ',$log_ids_arr);
		get_packing_slip( $log_ids, $log_ids_arr );
	}else{
	
		if (isset ( $_POST ['searchText'] ) && $_POST ['searchText'] != '') {
			$search_on = $wpdb->_real_escape ( trim ( $_POST ['searchText'] ) );
		}
		
		if (isset ( $_POST ['fromDate'] )) {
			$from_date = strtotime ( $_POST ['fromDate'] );
			$to_date = strtotime ( $_POST ['toDate'] );
			if ($to_date == 0) {
				$to_date = strtotime ( 'today' );
			}
			// move it forward till the end of day
			$to_date += 86399;
			
			// Swap the two dates if to_date is less than from_date
			if ($to_date < $from_date) {
				$temp = $to_date;
				$to_date = $from_date;
				$from_date = $temp;
			}
			$where = " WHERE wtpl.date BETWEEN '$from_date' AND '$to_date'";
		}
                

                //Code to get the variation names
                $term_taxonomy_id_query = "SELECT term_relationships.object_id AS object_id,
                                        GROUP_CONCAT( DISTINCT terms.name ORDER BY terms.term_id SEPARATOR ',' ) AS variations
                                        FROM {$wpdb->prefix}term_relationships AS term_relationships
                                            LEFT JOIN {$wpdb->prefix}term_taxonomy AS term_taxonomy ON ( term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id )
                                            LEFT JOIN {$wpdb->prefix}terms AS terms ON ( terms.term_id = term_taxonomy.term_id )
                                        WHERE term_taxonomy.taxonomy = 'wpsc-variation'
                                        GROUP BY object_id";

                $term_taxonomy_id_results = $wpdb->get_results( $term_taxonomy_id_query, 'ARRAY_A' );
                $term_taxonomy_id_rows = $wpdb->num_rows;

                $term_names = array();
                if ($term_taxonomy_id_rows > 0) {

                    foreach ($term_taxonomy_id_results as $term_taxonomy_id_result) {
                        $term_names[$term_taxonomy_id_result['object_id']] = $term_taxonomy_id_result['variations'];
                    }
                }



                                        // LEFT JOIN {$wpdb->prefix}term_relationships AS term_relationships ON ( term_relationships.object_id = wtcc.prodid )
                                        //         LEFT JOIN 
                                        //         (

                                        //         ) AS terms ON ( terms.object_id = wtcc.prodid )

                // CAST( CONCAT( if( products.post_parent > 0, SUBSTRING_INDEX( products.post_title, '(', 1 ), products.post_title ), if( products.post_parent > 0, CONCAT( if( terms.variations IS NULL, '', '(' ), 
                //                                 terms.variations, 
                //                                 if( terms.variations IS NULL, '', ')' ) ), '' ),
                //                                 if( postmeta.meta_value != '',' [', ' '),
                //                                 postmeta.meta_value,
                //                                 if( postmeta.meta_value != '',']', ' ' ) )
                //                              AS CHAR(1000000) ) AS product_details,

                $product_details = "SELECT wtcc.prodid AS product_id,
                                            postmeta.meta_value as sku,
                                            products.post_title as title,
                                            products.post_parent as post_parent,
                                            wtcc.name AS additional_product_name
                                            FROM " . WPSC_TABLE_CART_CONTENTS . " AS wtcc
                                                LEFT JOIN {$wpdb->prefix}posts AS products ON ( products.ID = wtcc.prodid )
                                                LEFT JOIN {$wpdb->prefix}postmeta AS postmeta ON ( postmeta.post_id = wtcc.prodid AND postmeta.meta_key = '_wpsc_sku' )
                                            GROUP BY product_id";
                $results = $wpdb->get_results( $product_details, 'ARRAY_A' );

                $product_details_results = array();
                foreach ( $results as $result ) {
                        
                    $product_details = '';

                    if ($result['post_parent'] > 0) {

                        $product_details = substr($result['title'], 0, strpos($result['title'], '('));
                        if (!empty($term_names[$result['product_id']])) {
                            $product_details .= '(' . $term_names[$result['product_id']] . ')';
                        }    
                    } else {
                        $product_details = $result['title'];
                    }

                    if (!empty($result['sku'])) {
                        $product_details .= ' [' . $result['sku'] .']';
                    }
                    
                    $product_details_results[$result['product_id']] = ( !empty( $result['product_details'] ) ) ? $result['product_details'] : $result['additional_product_name'];
                }
                
                if ( !empty( $search_on ) ) {
                    
                    //Query for searching for Shipping_Country
                    $search_condn_country_query = "SELECT DISTINCT wtcl.isocode
                                                                FROM " .  WPSC_TABLE_CURRENCY_LIST. " AS wtcl
                                                                WHERE wtcl.country LIKE '%$search_on%'
                                                                    OR wtcl.continent LIKE '%$search_on%'
                                                        ";
                    
                    $country_search_ons = $wpdb->get_col( $search_condn_country_query );
                    
                    //Query for searching for Shipping_Region
                    $search_condn_region_query = "SELECT DISTINCT wtrt.id
                                                                FROM " . WPSC_TABLE_REGION_TAX . " AS wtrt
                                                                WHERE wtrt.name LIKE '%$search_on%'
                                                        ";
                    
                    $region_search_ons = $wpdb->get_col( $search_condn_region_query );
                    
                    //Code for handling the search using user email id
                    $email_query = "SELECT ID FROM $wpdb->users 
                                        WHERE user_email LIKE '%$search_on%'";
                    $email_result = $wpdb->get_col($email_query);
                    $email_rows = $wpdb->num_rows;
                    
                    if ($email_rows > 0) {
                        $email_query1 = "SELECT ID FROM {$wpdb->prefix}wpsc_purchase_logs 
                                        WHERE user_ID IN (". implode(",",$email_result) .")";
                        $email_result1 = $wpdb->get_col($email_query1);
                        
                        $email_search = ( !empty( $email_result1 ) ) ? " OR wtsfd.log_id IN ( " . implode( ',', $email_result1 ) . " )" : '';
                    }
                    
                    //Code for handling search using shipping_county OR shipping_Region
                    if ( !(empty( $country_search_ons )) || !(empty( $region_search_ons ))) {
                        $search_on_region_country .= " (";
                        foreach ( $country_search_ons as $country_search_on ) {
                            $search_on_region_country .= "meta_values LIKE '%###$country_search_on###%' OR "; 
                        }
                    
                        for ($j=0;$j<sizeof($region_search_ons);$j++) {
                            $search_on_region_country .= "meta_values LIKE '%###$region_search_ons[$j]###%' OR "; 
                        }
                    
                        $search_on_region_country = trim( $search_on_region_country , ' OR ' );
                        $search_on_region_country .= " )";
		} else {			
                        $search_condn_checkout_form_details_query = " meta_values LIKE '%$search_on%' 
                                                                      $email_search";
                        $search_on_region_country = '';
                    }
                } else {
                    $search_on_region_country = '';
                    $search_condn_checkout_form_details_query = '';
                }
			
                $having = ( !empty( $search_condn_checkout_form_details_query ) || !empty( $search_on_region_country ) ) ? " HAVING " . $search_condn_checkout_form_details_query . $search_on_region_country : '';
                                
                $checkout_form_details_select_query = "SELECT wtsfd.log_id AS purchase_log_id,
                                                        GROUP_CONCAT( wtcf.unique_name ORDER BY wtcf.id SEPARATOR '###' ) AS meta_keys,
                                                        GROUP_CONCAT( wtsfd.value ORDER BY wtsfd.form_id SEPARATOR '###' ) AS meta_values";


                $checkout_form_details_from_query = " FROM " . WPSC_TABLE_SUBMITED_FORM_DATA . " AS wtsfd
                                                            LEFT JOIN " . WPSC_TABLE_CHECKOUT_FORMS . " as wtcf   
                                                            ON (wtsfd.form_id = wtcf.id)
                                                            WHERE wtcf.active = 1 
                                                                AND wtcf.unique_name IN ('billingfirstname', 'billinglastname', 'billingemail',
                                                                                         'shippingfirstname', 'shippinglastname', 'shippingaddress',
                                                                                         'shippingcity', 'shippingstate', 'shippingcountry', 'shippingpostcode','billingphone')
                                                ";
                
                
                $results = $wpdb->get_results( $checkout_form_details_select_query . $checkout_form_details_from_query . " GROUP BY purchase_log_id" . $having, 'ARRAY_A' );
                $result_shipping = $results;
               
                $matched_checkout_form_details = false;
                if ( empty( $results ) ) {
                    $results = $wpdb->get_results( $checkout_form_details_select_query . $checkout_form_details_from_query . " GROUP BY purchase_log_id", 'ARRAY_A' );
                } else {
                    $matched_checkout_form_details = true;
                                        }
                
                $checkout_form_details = array();
                foreach ( $results as $result ) {
                    $checkout_form_details[$result['purchase_log_id']] = array();
                    $checkout_form_details[$result['purchase_log_id']]['meta_keys'] = $result['meta_keys'];
                    $checkout_form_details[$result['purchase_log_id']]['meta_values'] = $result['meta_values'];
                                        }
                                        
                
                $purchase_logs_select_query = "SELECT wtpl.id, 
                                                wtpl.totalprice AS amount, 
                                                wtpl.processed AS order_status, 
                                                wtpl.user_ID AS customer_id, 
                                                wtpl.date AS unixdate,
                                                wtpl.notes,
                                                wtpl.track_id,
                                                GROUP_CONCAT( CAST(wtcc.prodid AS CHAR) ORDER BY wtcc.id SEPARATOR ',' ) AS product_ids,
                                                CONCAT( CAST(SUM(wtcc.quantity) AS CHAR(100)), ' items') AS details";
                                                
                 $purchase_logs_from_query = " FROM " . WPSC_TABLE_PURCHASE_LOGS . " AS wtpl
                                                    LEFT JOIN " . WPSC_TABLE_CART_CONTENTS . " AS wtcc ON ( wtcc.purchaseid = wtpl.id )
                                        ";
                
                if ( !empty( $search_on ) ) {
                
                    $search_condn_purchase_log_ids = get_all_matched_purchase_log_ids( $search_on, $checkout_form_details_from_query );
                    
                    $variation_search_query = "SELECT DISTINCT tr.object_id
                                                    FROM {$wpdb->prefix}term_relationships AS tr
                                                        LEFT JOIN {$wpdb->prefix}term_taxonomy AS tt ON ( tt.term_taxonomy_id = tr.term_taxonomy_id )
                                                        LEFT JOIN {$wpdb->prefix}terms AS t ON ( t.term_id = tt.term_id )
                                                    WHERE tt.taxonomy = 'wpsc-variation'
                                                        AND t.name LIKE '%$search_on%'

                                                ";
                    $object_ids = $wpdb->get_col( $variation_search_query );
                    
                    $variation_search_ids = ( !empty( $object_ids ) ) ? " OR wtcc.prodid IN ( " . implode( ',', $object_ids ) . " )" : '';
                    
                    
                    $email_query = "SELECT ID FROM $wpdb->users 
                                        WHERE user_email LIKE '%$search_on%'";
                    $email_result = $wpdb->get_col($email_query);
                    
                    $email_search = ( !empty( $email_result ) ) ? " OR wtpl.user_ID IN ( " . implode( ',', $email_result ) . " )" : '';
                    
                    $search_condn_purchase_logs = " AND ( wtpl.id LIKE '%$search_on%'
                                                          OR totalprice LIKE '%$search_on%'
                                                          OR notes LIKE '%$search_on%'
                                                          OR date LIKE '%$search_on%'
                                                          OR wtpl.track_id LIKE '%$search_on%'
                                                          OR CASE wtpl.processed
								  WHEN 1 THEN 'Incomplete Sale'
								  WHEN 2 THEN 'Order Received'
								  WHEN 3 THEN 'Accepted Payment'
								  WHEN 4 THEN 'Job Dispatched'
								  WHEN 5 THEN 'Closed Order'
								  ELSE 'Payment Declined'
							     END like '%$search_on%'
                                                          OR wtcc.name LIKE '%$search_on%'
                                                          $variation_search_ids
                                                          $email_search
                                                         )
                                                    $search_condn_purchase_log_ids
                                                    ";
                
                } else {
                    $search_condn_purchase_logs = '';
                                    }
                
                $query = $purchase_logs_select_query . $purchase_logs_from_query . $where . $search_condn_purchase_logs . " GROUP BY wtpl.id ORDER BY wtpl.id DESC $limit_string";
                $results = $wpdb->get_results( $query, 'ARRAY_A' );

                if ( empty( $results ) ) {
                    
                    $log_id = array();

                    for ($i=0;$i<sizeof($result_shipping);$i++) {
                        $log_id[$i] = $result_shipping[$i]['purchase_log_id'];
                    }
                    
                    if (!(empty($log_id))) {
                        $where .= "AND wtpl.id IN(" . implode(",",$log_id) .")";
                        $query = $purchase_logs_select_query . $purchase_logs_from_query . $where . " GROUP BY wtpl.id ORDER BY wtpl.id DESC $limit_string";
                        $results = $wpdb->get_results( $query, 'ARRAY_A' );
                    }
                    
                    
                }
                
                if ( !$is_export ) {
                    $orders_count_result = $wpdb->get_results ( substr( $query, 0, strpos( $query, 'LIMIT' ) ),'ARRAY_A');
                    $num_records = count( $orders_count_result ); 
                } else {
                    $num_records = count( $results ); 
                                }
                                
                    $query = "SELECT ID,user_email FROM $wpdb->users";
                    $reg_user = $wpdb->get_results ($query ,'ARRAY_A');
                    
                    for ($i=0;$i<sizeof($reg_user);$i++) {
                        $user_email[$reg_user[$i]['ID']] = $reg_user[$i]['user_email'];
                    }
                    
		//To get the total count
		if ($num_records == 0) {
			$encoded ['totalCount'] = '';
			$encoded ['items'] = '';
			$encoded ['msg'] = __( 'No Records Found', $sm_text_domain );
		} else {			
                         
                        foreach ( $results as $data ) {
                            if ( $matched_checkout_form_details && !isset( $checkout_form_details[$data['id']] ) ) continue;
				
                            $checkout_form_details_keys = explode( '###', $checkout_form_details[$data['id']]['meta_keys'] );
                            $checkout_form_details_values = explode( '###', $checkout_form_details[$data['id']]['meta_values'] );

                            if ( count( $checkout_form_details_keys ) == count( $checkout_form_details_values ) ) {
                                $checkout_form_data = array_combine( $checkout_form_details_keys, $checkout_form_details_values );
                                
                                $name_emailid [0] = "<font class=blue>". $checkout_form_data['billingfirstname']."</font>";
                                $name_emailid [1] = "<font class=blue>". $checkout_form_data['billinglastname']."</font>";
                                $name_emailid [2] = "(".$checkout_form_data['billingemail'].")"; //email comes at 7th position.
								$data['name'] 	  = implode ( ' ', $name_emailid ); //in front end,splitting is done with this space.

								$data['date'] = gmdate('Y-m-d H:i:s', $data['unixdate'] ); //Code to display the order date in GMT format

                                if ($data['customer_id'] > 0) {
                                    $data['reg_email'] = $user_email[$data['customer_id']];
                                }
                                else {
                                    $data['reg_email'] = "";
                                }
                                    
                                $prod_ids = explode( ',', $data['product_ids'] );
                            
                                $products_name = '';
                                foreach ( $prod_ids as $prod_id ) {
                                    $products_name .= $product_details_results[$prod_id] . ', ';
					}
                                $data['products_name'] = trim( $products_name, ', ' );
                                
                                if( !empty( $checkout_form_data['shippingstate'] ) ) {
                                    $ship_state = $checkout_form_data['shippingstate'];
                                    $checkout_form_data['shippingstate'] = ( $regions_ids[$ship_state] != '' ) ? $regions_ids[$ship_state] : $ship_state;
				}
                                
                                if( !empty( $checkout_form_data['shippingcountry'] ) ) {
                                    $ship_country = $checkout_form_data['shippingcountry'];
                                    $checkout_form_data['shippingcountry'] = ( $country_data[$ship_country] != '' ) ? $country_data[$ship_country] : $ship_country;
			}
                                
                                $records[] = ( !empty( $checkout_form_data ) ) ? array_merge ( $checkout_form_data, $data ) : $data;
                            
		}
                            
                            unset( $data );
                            unset( $checkout_form_details_keys );
                            unset( $checkout_form_details_values );
                            unset( $checkout_form_data );
                            
	}
	
                }
	}

    	} else {
    
		//BOF Customer's module
                if (isset ( $_POST ['searchText'] ) && $_POST ['searchText'] != '') {
                    $search_on = $wpdb->_real_escape ( trim( $_POST ['searchText'] ) );
		} else{
                    $search_on = "";
                }
                
                $email_form_id = $wpdb->get_var("SELECT id FROM " .WPSC_TABLE_CHECKOUT_FORMS . " WHERE unique_name = 'billingemail'");
                
                $query_max_users_ids = "SELECT GROUP_CONCAT(wtpl.id ORDER BY wtpl.date DESC SEPARATOR ',' ) AS last_order_id,
                                        GROUP_CONCAT(wtpl.totalprice ORDER BY wtpl.date DESC SEPARATOR ',' ) AS _order_total,
                                        DATE_FORMAT( MAX(FROM_UNIXTIME( wtpl.date )),'%b %e %Y' ) AS Last_Order,
                                        COUNT(wtpl.id) AS count_orders,
                                        SUM(wtpl.totalprice) AS total_orders
                    
                                        FROM " . WPSC_TABLE_PURCHASE_LOGS . " AS wtpl
                                                 LEFT JOIN " . WPSC_TABLE_SUBMITED_FORM_DATA . " AS customer_email ON ( customer_email.log_id = wtpl.id AND customer_email.form_id = $email_form_id )
                                        WHERE wtpl.user_ID > 0
                                        Group by wtpl.user_ID";
                $result_max_users_ids = $wpdb -> get_results($query_max_users_ids, 'ARRAY_A' );
                
                $query_max_guest_ids = "SELECT GROUP_CONCAT(wtpl.id ORDER BY wtpl.date DESC SEPARATOR ',' ) AS last_order_id,
                                        GROUP_CONCAT(wtpl.totalprice ORDER BY wtpl.date DESC SEPARATOR ',' ) AS _order_total,
                                        DATE_FORMAT( MAX(FROM_UNIXTIME( wtpl.date )),'%b %e %Y' ) AS Last_Order,
                                        COUNT(wtpl.id) AS count_orders,
                                        SUM(wtpl.totalprice) AS total_orders
                                        
                                        FROM " . WPSC_TABLE_PURCHASE_LOGS . " AS wtpl
                                                 LEFT JOIN " . WPSC_TABLE_SUBMITED_FORM_DATA . " AS customer_email ON ( customer_email.log_id = wtpl.id AND customer_email.form_id = $email_form_id )
                                        WHERE wtpl.user_ID = 0
                                        GROUP BY customer_email.value
                                        ORDER BY Last_Order DESC";
                $result_max_guest_ids = $wpdb -> get_results($query_max_guest_ids, 'ARRAY_A' );
                
                for ($i=0;$i<sizeof($result_max_guest_ids);$i++) {
                    $temp_id =  (!empty($result_max_guest_ids[$i]['last_order_id'])) ? explode(",",$result_max_guest_ids[$i]['last_order_id']) : '';
                    $max_id[$i] = (!empty($temp_id[0])) ? $temp_id[0] : 0;
                    
                    $count_orders[$max_id[$i]] = (!empty($result_max_guest_ids[$i]['count_orders'])) ? $result_max_guest_ids[$i]['count_orders'] : '';
                    $total_orders[$max_id[$i]] = (!empty($result_max_guest_ids[$i]['total_orders'])) ? $result_max_guest_ids[$i]['total_orders'] : '';
                    $order_date[$max_id[$i]] = (!empty($result_max_guest_ids[$i]['Last_Order'])) ? $result_max_guest_ids[$i]['Last_Order'] : '';
                    
                    $temp_tot =  (!empty($result_max_guest_ids[$i]['_order_total'])) ? explode(",",$result_max_guest_ids[$i]['_order_total']) : '';
                    $last_order_total[$max_id[$i]] = (!empty($temp_tot[0])) ? $temp_tot[0] : '';
                    
                }
                
                $j = (!empty($max_id)) ? sizeof($max_id) : 0;
                
                for ($i=0;$i<sizeof($result_max_users_ids);$i++,$j++) {
                    $temp_id =  (!empty($result_max_users_ids[$i]['last_order_id'])) ? explode(",",$result_max_users_ids[$i]['last_order_id']) : '';
                    $max_id[$j] = (!empty($temp_id)) ? $temp_id[0] : 0;
                    
                    $count_orders[$max_id[$j]] = (!empty($result_max_users_ids[$i]['count_orders'])) ? $result_max_users_ids[$i]['count_orders'] : '';
                    $total_orders[$max_id[$j]] = (!empty($result_max_users_ids[$i]['total_orders'])) ? $result_max_users_ids[$i]['total_orders'] : '';
                    $order_date[$max_id[$j]] = (!empty($result_max_users_ids[$i]['Last_Order'])) ? $result_max_users_ids[$i]['Last_Order'] : '';
                    
                    $temp_tot = (!empty($result_max_users_ids[$i]['_order_total'])) ?  explode(",",$result_max_users_ids[$i]['_order_total']) : '';
                    $last_order_total[$max_id[$j]] = (!empty($temp_id)) ? $temp_tot[0] : '';
                }
                
                
                $total_search = "";
                
                if ( !empty( $search_on ) ) {
                    $searched_region = $wpdb->get_col( "SELECT code FROM " . WPSC_TABLE_REGION_TAX . " WHERE name LIKE '%$search_on%'" );
                    $searched_country = $wpdb->get_col( "SELECT isocode FROM " . WPSC_TABLE_CURRENCY_LIST . " WHERE country LIKE '%$search_on%' OR continent LIKE '%$search_on%'" );
                    $found_country_region = array_merge( $searched_region, $searched_country );
                    $found_country_region_having = '';
                    foreach ( $found_country_region as $country_region ) {
                        $found_country_region_having .= " OR meta_values LIKE '%$country_region%'";
                    }
                    
                        $email_query = "SELECT ID FROM $wpdb->users 
                                        WHERE user_email LIKE '%$search_on%'";
                    $email_result = $wpdb->get_col($email_query);
                    $email_rows = $wpdb->num_rows;
                    
                        //Query to get the user ids of the rows whose content matches the search text
                        $user_detail_query = "SELECT DISTINCT user_id FROM $wpdb->usermeta 
                                            WHERE meta_key IN ('first_name','last_name','wpshpcrt_usr_profile') 
                                                AND meta_value LIKE '%$search_on%'";
                        $user_detail_result = $wpdb->get_col($user_detail_query);
                        $user_detail_rows = $wpdb->num_rows;

                        //Code to merge all the user ids into a single array
                        if ($user_detail_rows > 0) {
                            for ($i=0,$j=sizeof($email_result);$i<sizeof($user_detail_result);$i++,$j++) {
                                $email_result[$j] = $user_detail_result[$i];
                            }
                        }

                        if ($email_rows > 0 || $user_detail_rows > 0) {
                        $email_query1 = "SELECT ID FROM {$wpdb->prefix}wpsc_purchase_logs 
                                        WHERE user_ID IN (". implode(",",$email_result) .")";
                        $email_result1 = $wpdb->get_col($email_query1);
                        
                        $email_search = ( !empty( $email_result1 ) ) ? " OR wtsfd.log_id IN ( " . implode( ',', $email_result1 ) . " )" : '';
                }
                
                }
                
                $where_log_id = (!empty($max_id)) ? 'WHERE log_id IN ('. implode(",",$max_id).')' : '';

                $max_id_imploded = (!empty($max_id)) ? implode(",",$max_id) : '';
                $orderby_log_id = (!empty($max_id)) ? "ORDER BY FIND_IN_SET(log_id,'$max_id_imploded')" : 'ORDER BY log_id';

                $customer_details_query_select = "SELECT wtsfd.log_id AS log_id,
                                                            GROUP_CONCAT( wtcf.unique_name ORDER BY wtcf.id SEPARATOR '###' ) AS meta_keys,
                                                            GROUP_CONCAT( wtsfd.value ORDER BY wtsfd.form_id SEPARATOR '###' ) AS meta_values

                                                        FROM " . WPSC_TABLE_SUBMITED_FORM_DATA . " AS wtsfd
                                                                 JOIN " . WPSC_TABLE_CHECKOUT_FORMS . " AS wtcf ON ( wtcf.id = wtsfd.form_id AND wtcf.active = 1 AND wtcf.unique_name IN ('billingfirstname','billinglastname','billingaddress',
                                                                                                    'billingcity','billingstate','billingcountry','billingpostcode',
                                                                                                    'billingemail','billingphone') )
                                                            $where_log_id
                                                        GROUP BY log_id";
                
                if ( !empty( $search_on ) ) {
                    $customer_details_query_having = " HAVING meta_values LIKE '%$search_on%'
                                                              $found_country_region_having
                                                              $email_search
                                                                  $total_search    
                                                     ";
                } else {
                    $customer_details_query_having = '';
                }
                
                    $order_by = " $orderby_log_id $limit_string";
                    
                    $full_customer_details_query = $customer_details_query_select . $customer_details_query_having . $order_by;
                $customer_details_results = $wpdb->get_results( $full_customer_details_query, 'ARRAY_A' );
                                                 
                    if (is_null($customer_details_results)) {
                        $full_customer_details_query = $customer_details_query_select . $order_by;
                        $customer_details_results = $wpdb->get_results( $full_customer_details_query, 'ARRAY_A' );
                    }
                
                if ( !$is_export ) {
                    $customers_count_result = $wpdb->get_results ( substr( $full_customer_details_query, 0, strpos( $full_customer_details_query, 'LIMIT' ) ),'ARRAY_A');
                    $num_records = count( $customers_count_result ); 
                } else {
                    $num_records = count( $customer_details_results );
                }

                //Code to get all the users along with their id and email in an array
                $query = "SELECT users.ID,users.user_email, GROUP_CONCAT(usermeta.meta_value 
                                         ORDER BY usermeta.umeta_id SEPARATOR '###' ) AS name
                            FROM $wpdb->users AS users
                                JOIN $wpdb->usermeta  AS usermeta ON usermeta.user_id = users.id
                            WHERE usermeta.meta_key IN ('first_name','last_name','wpshpcrt_usr_profile')
                            GROUP BY users.id DESC";
                $reg_user = $wpdb->get_results ($query ,'ARRAY_A');
                       
                for ($i=0;$i<sizeof($reg_user);$i++) {
                    $user_email[$reg_user[$i]['ID']] = $reg_user[$i]['user_email'];
                    $name = explode ("###",$reg_user[$i]['name']);
                    $user_fname[$reg_user[$i]['ID']] = $name[0];
                    $user_lname[$reg_user[$i]['ID']] = $name[1];
                    
                    if (!empty($name[2])) {
                        $unserialized_detail = unserialize($name[2]); 
                        
                        $user_add[$reg_user[$i]['ID']]      = $unserialized_detail[4];
                        $user_city[$reg_user[$i]['ID']]     = $unserialized_detail[5];
                        $user_region[$reg_user[$i]['ID']]   = (!empty($unserialized_detail[6])) ? $unserialized_detail[6] : '';
                        $user_country[$reg_user[$i]['ID']]  = $unserialized_detail[7][0];
                        $user_pcode[$reg_user[$i]['ID']]    = $unserialized_detail[8];
                        $user_phone[$reg_user[$i]['ID']]    = $unserialized_detail[18];
                }
                    
                }
                
                $country_result = $wpdb->get_results( "SELECT isocode,country FROM " . WPSC_TABLE_CURRENCY_LIST ,'ARRAY_A');
                $country_rows = $wpdb->num_rows;
                
                if ($country_rows > 0) {
                    for ($i=0;$i<sizeof($country_result);$i++) {
                        $country[$country_result[$i]['isocode']] = $country_result[$i]['country'];
                    }
                }
                
                if ($num_records == 0) {
			$encoded ['totalCount'] = '';
			$encoded ['items'] = '';
			$encoded ['msg'] = __( 'No Records Found', $sm_text_domain );
		} else {
				
			foreach ( $customer_details_results as $result ) {

                                        $meta_keys = explode( '###', $result['meta_keys'] );
                                        $meta_values = explode( '###', $result['meta_values'] );
                                        if ( count( $meta_keys ) == count( $meta_values ) ) {
                                            $customer_detail_data[$result['log_id']] = array_combine( $meta_keys, $meta_values );
                                        }

                                        $result['last_order_id'] =  $result['log_id'];
                                        $result['Last_Order'] = $order_date[$result['log_id']];
                                        $result['_order_total'] = $last_order_total[$result['log_id']];
                                        $result['count_orders']= $count_orders[$result['log_id']];
                                        $result['total_orders']= $total_orders[$result['log_id']];
                            
					if ( empty( $customer_detail_data[$result['last_order_id']] ) ) {
                                            $num_records--;
                                            continue;
                                        }
                                        $billing_user_details = $customer_detail_data[$result['last_order_id']];
                                        $billing_user_details['billingstate'] = ( !empty( $regions_ids[$billing_user_details['billingstate']] ) ) ? $regions_ids[$billing_user_details['billingstate']] : $billing_user_details['billingstate'];
                                        $billing_user_details['billingcountry'] = ( !empty( $country_data[$billing_user_details['billingcountry']] ) ) ? $country_data[$billing_user_details['billingcountry']] : $billing_user_details['billingcountry'];
					
                                        if (SMPRO == false) {
                                            $result['Last_Order'] = 'Pro only';
                                            $result['_order_total'] = 'Pro only';
                                            $result['count_orders']= 'Pro only';
                                            $result['total_orders']= 'Pro only';
					}
                                        
                                        //Code to get the email for reg users from wp_users table
                                        if (!empty($result['id']) && $result['id'] > 0) {
                                            $result['email']  = $user_email[$result['id']];
                                            $billing_user_details ['billingemail']      = $user_email[$result['id']];
                                            
                                            if(!(empty($user_fname[$result['id']]))) {
                                            $billing_user_details ['billingfirstname']  = $user_fname[$result['id']];
                                            }
                                            if(!(empty($user_lname[$result['id']]))) {
                                            $billing_user_details ['billinglastname']   = $user_lname[$result['id']];
                                            }
                                            
                                            $billing_user_details ['billingaddress']    = $user_add[$result['id']];
                                            $billing_user_details ['billingcity']       = $user_city[$result['id']];
                                            $billing_user_details ['billingstate']      = ( !empty( $regions_ids[$user_region[$result['id']]] ) ) ? $regions_ids[$user_region[$result['id']]] : $user_region[$result['id']];
                                            $billing_user_details ['billingcountry']    = $country[$user_country[$result['id']]];
                                            $billing_user_details ['billingpostcode']   = $user_pcode[$result['id']];
                                            $billing_user_details ['billingphone']      = $user_phone[$result['id']];
                                        }
                                        
					//NOTE: storing old email id in an extra column in record so useful to indentify record with emailid during updates.
                                        $result ['Old_Email_Id'] = $billing_user_details ['billingemail'];
                                        $records[] = ( !empty( $billing_user_details ) ) ? array_merge ( $billing_user_details, $result ) : $result;

                           unset($result);
                           unset($meta_keys);
                           unset($meta_values);
                           unset($billing_user_details);
                        }
                }
	
        }
	
        if (!isset($_POST['label']) || ( (!empty($_POST['label'])) && $_POST['label'] != 'getPurchaseLogs' )){
			$encoded ['items'] = (!empty($records)) ? $records : '';
			$encoded ['totalCount'] = $num_records;
			unset($records);
	        return $encoded;
	}
}

// Searching a product in the grid
if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'getData') {

    check_ajax_referer('smart-manager-security','security');

	$encoded = get_data_wpsc_38 ( $_POST, $offset, $limit );
	// ob_clean();

	while(ob_get_contents()) {
        ob_clean();
    }

        echo json_encode ( $encoded );
	unset($encoded);

	exit;
}

if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'state') {

        check_ajax_referer('smart-manager-security','security');

        global $current_user , $wpdb;

        $state_nm = array("dashboardcombobox", "Products", "Customers", "Orders","incVariation","search_type");
        
        for ($i=0;$i<sizeof($state_nm);$i++) {
            $stateid = "_sm_wpsc_".$current_user->user_email."_".$state_nm[$i];
        
            $query_state  = "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name like '$stateid'";
            $result_state =  $wpdb->get_col ( $query_state );
            $rows_state   = $wpdb->num_rows;
            
            if ($rows_state > 0) {
            
                if ($_POST ['op'] == 'get' ) {
                    $state[$state_nm[$i]] = $result_state[0];
                }
                elseif ($_POST ['op'] == 'set') {
                    $state_apply = $_POST[$state_nm[$i]];
                    $query_state = "UPDATE {$wpdb->prefix}options SET option_value = '$state_apply' WHERE option_name = '$stateid'";
                    $result_state =  $wpdb->query ( $query_state );
//                    $state = $_POST['state'];
                }

            }
            else {
                
                $state_apply = $_POST[$state_nm[$i]];
                
                $query_state = "INSERT INTO {$wpdb->prefix}options (option_name,option_value) values ('$stateid','$state_apply')";
                $result_state =  $wpdb->query ( $query_state );
                
                $state[$state_nm[$i]] = $state_apply;
            }
        }
        if ($_POST ['op'] == 'get' ) {   
            echo json_encode ($state);
        }

        exit;
}


if (isset ( $_GET ['func_nm'] ) && $_GET ['func_nm'] == 'exportCsvWpsc') {

    ini_set('memory_limit','512M');
    set_time_limit(0);

    $columns_header = array();
	$active_module = $_GET ['active_module'];
        
	switch ( $active_module ) {
		
		case 'Products':
				$columns_header['id'] 						= __('Post ID', $sm_text_domain);
				$columns_header['thumbnail'] 				= __('Product Image', $sm_text_domain);
				$columns_header['post_title'] 				= __('Product Name', $sm_text_domain);
				$columns_header['_wpsc_price'] 				= __('Price', $sm_text_domain);
				$columns_header['_wpsc_special_price'] 		= __('Sale Price', $sm_text_domain);
				$columns_header['_wpsc_stock'] 				= __('Inventory / Stock', $sm_text_domain);
				$columns_header['_wpsc_sku'] 				= __('SKU', $sm_text_domain);
				$columns_header['category'] 				= __('Category / Group', $sm_text_domain);
				$columns_header['weight'] 					= __('Weight', $sm_text_domain);
				$columns_header['weight_unit'] 				= __('Weight Unit', $sm_text_domain);
				$columns_header['height'] 					= __('Height', $sm_text_domain);
				$columns_header['height_unit'] 				= __('Height Unit', $sm_text_domain);
				$columns_header['width'] 					= __('Width', $sm_text_domain);
				$columns_header['width_unit'] 				= __('Width Unit', $sm_text_domain);
				$columns_header['length'] 					= __('Length', $sm_text_domain);
				$columns_header['length_unit'] 				= __('Length Unit', $sm_text_domain);
				$columns_header['local'] 					= __('Local Shipping Fee', $sm_text_domain);
				$columns_header['international'] 			= __('International Shipping Fee', $sm_text_domain);
			break;
			
		case 'Customers':
				$columns_header['id'] 					= __('User ID', $sm_text_domain);
				$columns_header['billingfirstname'] 	= __('First Name', $sm_text_domain);
				$columns_header['billinglastname'] 		= __('Last Name', $sm_text_domain);
				$columns_header['billingemail'] 		= __('E-mail ID', $sm_text_domain);
				$columns_header['billingaddress'] 		= __('Address', $sm_text_domain);
				$columns_header['billingpostcode'] 		= __('Postcode', $sm_text_domain);
				$columns_header['billingcity'] 			= __('City', $sm_text_domain);
				$columns_header['billingstate'] 		= __('State / Region', $sm_text_domain);
				$columns_header['billingcountry'] 		= __('Country', $sm_text_domain);
				$columns_header['billingphone'] 		= __('Phone / Mobile', $sm_text_domain);
                $columns_header['_order_total'] 		= __('Last Order Total', $sm_text_domain);
				$columns_header['Last_Order'] 		= __('Last Order Date', $sm_text_domain);
                $columns_header['count_orders']          = __('Total Number Of Orders', $sm_text_domain);
                $columns_header['total_orders'] 		= __('Total Purchased Till Date (By Customer)', $sm_text_domain);
				
			break;
			
		case 'Orders':
				$columns_header['id'] 						= __('Order ID', $sm_text_domain);
				$columns_header['date'] 					= __('Order Date', $sm_text_domain);
				$columns_header['billingfirstname'] 		= __('Billing First Name', $sm_text_domain);
				$columns_header['billinglastname'] 			= __('Billing Last Name', $sm_text_domain);
				$columns_header['billingemail'] 			= __('Billing E-mail ID', $sm_text_domain);
                $columns_header['billingphone'] 			= __('Billing Phone Number', $sm_text_domain);
				$columns_header['amount'] 					= __('Order Total', $sm_text_domain);
				$columns_header['details'] 					= __('Total No. of Items', $sm_text_domain);
				$columns_header['products_name'] 			= __('Order Items (Product Name[SKU])', $sm_text_domain);
				$columns_header['order_status'] 			= __('Order Status', $sm_text_domain);
				$columns_header['track_id'] 				= __('Track ID', $sm_text_domain);
				$columns_header['notes'] 					= __('Order Notes', $sm_text_domain);
				$columns_header['shippingfirstname'] 		= __('Shipping First Name', $sm_text_domain);
				$columns_header['shippinglastname'] 		= __('Shipping Last Name', $sm_text_domain);
				$columns_header['shippingaddress'] 			= __('Shipping Address', $sm_text_domain);
				$columns_header['shippingpostcode'] 		= __('Shipping Postcode', $sm_text_domain);
				$columns_header['shippingcity'] 			= __('Shipping City', $sm_text_domain);
				$columns_header['shippingstate'] 			= __('Shipping State / Region', $sm_text_domain);
				$columns_header['shippingcountry'] 			= __('Shippping Country', $sm_text_domain);
			break;
	}
	if ( $active_module == 'Products' ) {
		$_GET['viewCols'] = json_encode( array_keys( $columns_header ) );
	}
    ini_set('max_execution_time', 300);   
	$encoded = get_data_wpsc_38 ( $_GET, $offset, $limit, true );
	$data = $encoded ['items'];
	unset($encoded);
	
	$file_data = export_csv_wpsc_38 ( $active_module, $columns_header, $data );

	header("Content-type: text/x-csv; charset=UTF-8"); 
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: attachment; filename=".$file_data['file_name']); 
	header("Pragma: no-cache");
	header("Expires: 0");
		
	// ob_clean();

	while(ob_get_contents()) {
        ob_clean();
    }

        echo $file_data['file_content'];
		
	exit;
}

if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'dupData') {

    check_ajax_referer('smart-manager-security','security');

    $sm_dup_limit = (!empty($_POST['dup_limit'])) ? $_POST['dup_limit'] : 20; 

    global $wpdb;
    require_once (WP_PLUGIN_DIR . '/wp-e-commerce/wpsc-admin/admin.php');

    $dupCnt = 0;
    $activeModule = substr( $_POST ['active_module'], 0, -1 );
    $data_temp = json_decode ( stripslashes ( $_POST ['data'] ) );

    // Function to Duplicate the Product
    function duplicate_product ($strtCnt, $dupCnt, $data, $msg, $count, $per, $perval) {
        $post_data = array();

        for ($i = $strtCnt; $i < $dupCnt; $i ++) {
            $post_id = $data [$i];
            $post = get_post ( $post_id );
            if ($post->post_parent == 0) {
                $post_data [] = wpsc_duplicate_product_process($post);
            }
            else{
                $post_data [] = $data [$i];
            }
        }
        $duplicate_count = count ( $post_data );

        if ($duplicate_count == $count) {
            $result = true;
        }
        else{
            $result = false;
        }
        
        if ($result == true) {
                $encoded ['msg'] = $msg;
                $encoded ['dupCnt'] = $dupCnt;
                $encoded ['nxtreq'] = $_POST ['part'];
                $encoded ['per'] = $per;
                $encoded ['val'] = $perval;
        }
        elseif ($result == false) {
                $encoded ['msg'] = $activeModule . __('s were not duplicated',$sm_text_domain);
        }
        echo json_encode ( $encoded );

        exit;
    }

    /*Code to handle the First AJAX request used to calculate the 
        number of ajax request that needs to be prepared based on the 
        number of selected products*/
    if (isset ( $_POST ['part'] ) && $_POST ['part'] == 'initial') {

        //Code for getting the number of parent products for the dulplication of entire store
        if ( $_POST ['menu'] == 'store') {
            $query="SELECT id from {$wpdb->prefix}posts WHERE post_type='wpsc-product' AND post_status IN ('publish', 'pending', 'draft','private')";
            $data_dup = $wpdb->get_col ( $query );
        }
        else{
            if ($_POST ['incvariation'] == true) {
                $query="SELECT id from {$wpdb->prefix}posts WHERE post_type='wpsc-product' AND post_status IN ('publish', 'pending', 'draft')";
                $parent_ids = $wpdb->get_col ( $query );

                for ($i=0;$i<sizeof($parent_ids);$i++) {
                    $id[$parent_ids[$i]] = 'simple';
                }

                for ($i=0,$j=0;$i<sizeof($data_temp);$i++) {
                    if (isset($id[$data_temp[$i]])) {
                       $data_dup[$j] = $data_temp[$i];
                       $j++;
                    }
                }
            }
            else{
                $data_dup = $data_temp;
            }
        }
        $dupCnt = count ( $data_dup );

        if ($dupCnt > $sm_dup_limit) {
            for ($i=0;$i<$dupCnt;) {
                $count_dup ++;
                $i = $i+$sm_dup_limit;
            }
        }
        else{
            $count_dup = 1;
        }

        $data_dup = json_encode ( $data_dup );
        $encoded['count'] = $count_dup;
        $encoded['dupCnt'] = $dupCnt;
        $encoded['data_dup'] = $data_dup;
        
        echo json_encode ( $encoded );

        exit;
    }

    /*Code for handling the remmaing ajax request which actully calls the 
     function for duplicating the products */
    else {

        $count = $_POST ['count'];
        $data = json_decode ( stripslashes ( $_POST ['dup_data'] ) );

        $data_count = $_POST ['fdupcnt'] - $_POST ['dupcnt'];

        for ($i=1;$i<=$count;$i++) {
            if (isset ( $_POST ['part'] ) && $_POST ['part'] == $i) {
                $per = intval(($_POST ['part']/$count)*100); // Calculating the percentage for the display purpose
                $perval = $per/100;

                if ($per == 100) {
                    $dupCnt = $_POST['total_records'];
                    if ($data_count == 1) {
                        $msg = $dupCnt . " " . $activeModule . __(' Duplicated Successfully',$sm_text_domain);
                    }
                    else if ($data_count == 0) {
                        $msg = "Sorry! Variations Cannot be Duplicated";
                    }
                    else if ($_POST ['menu'] == 'store') {
                        $msg = "Store Duplicated Successfully";
                    }
                    else{
                        $msg = $dupCnt . " " . $activeModule . __('s Duplicated Successfully',$sm_text_domain);
                    }
                }
                else{
                    $msg = $per . "% Duplication Completed";
                }
                duplicate_product ($_POST ['dupcnt'], $_POST ['fdupcnt'], $data, $msg, $data_count, $per,$perval);
                break;
            }
        }
    }
}

if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'delData') {

    check_ajax_referer('smart-manager-security','security');

	global $purchlogs;
	$purchlogs = new wpsc_purchaselogs ();
	$delCnt = 0;
	
	if ($active_module == 'Products') {
		$data = json_decode ( stripslashes ( $_POST ['data'] ) );
		$delCnt = count ( $data );
		
		for($i = 0; $i < $delCnt; $i ++) {
			$post_id = $data [$i];
			$post = get_post ( $post_id );
	
			if ( $post->post_status == 'inherit' ) {
				$post_data [] = wp_delete_post( $post_id );
			} else {
				$post_data [] = wp_trash_post ( $post_id );
			}
		}
		
		$deleted_count = count ( $post_data );
		if ($deleted_count == $delCnt)
			$result = true;
		else
			$result = false;
		
		if ($result == true) {
			if ($delCnt == 1) {
				$encoded ['msg'] = "<b>" . $delCnt . "</b> " . __( 'Product Deleted Successfully', $sm_text_domain ); 
				$encoded ['delCnt'] = $delCnt;
			} else {
				$encoded ['msg'] = "<b>" . $delCnt . "</b> " . __( 'Products Deleted Successfully', $sm_text_domain );
				$encoded ['delCnt'] = $delCnt;
			}
		} elseif ($result == false) {
			$encoded ['msg'] = __("Products were not deleted", $sm_text_domain );
		} else {
			$encoded ['msg'] = __( "Products removed from the grid", $sm_text_domain );
		}
	} else if ($active_module == 'Orders') {
		$data = json_decode ( stripslashes ( $_POST ['data'] ) );
		foreach ( $data as $key => $id ) {
			$output = $purchlogs->deletelog ( $id );
			$delCnt ++;
		}
		
		if ($output) {
			//			$encoded ['msg'] = strip_tags($output);
			if ($delCnt == 1) {
				$encoded ['msg'] = "<b>" . $delCnt . "</b> " . __( 'Purchase Log deleted Successfully', $sm_text_domain ) ;
				$encoded ['delCnt'] = $delCnt;
			} else {
				$encoded ['msg'] = "<b>" . $delCnt . "</b> " . __( 'Purchase Logs deleted Successfully', $sm_text_domain ) ;
				$encoded ['delCnt'] = $delCnt;
			}
		} else
			$encoded ['msg'] = __( "Purchase Logs removed from the grid", $sm_text_domain ); 
	}
	// ob_clean();

	while(ob_get_contents()) {
        ob_clean();
    }

        echo json_encode ( $encoded );

        exit;
}

//update products for lite version.
function update_products($post) {
	global $result, $wpdb;
        $_POST = $post;     // Fix: PHP 5.4
	$edited_object = json_decode ( ( $_POST ['edited'] ) );
	$updateCnt = 1;
	foreach ( $edited_object as $obj ) {
		
		$update_name = $wpdb->query ( "UPDATE $wpdb->posts SET `post_title`= '".$wpdb->_real_escape($obj->post_title)."' WHERE ID = " . $wpdb->_real_escape($obj->id) );
		$update_price = $wpdb->query ( "UPDATE $wpdb->postmeta SET `meta_value`= ".$wpdb->_real_escape($obj->_wpsc_price)." WHERE meta_key = '_wpsc_price' AND post_id = " . $wpdb->_real_escape($obj->id) );
		$result ['updateCnt'] = $updateCnt ++;
	}
	
	if (($update_name >= 1 || $update_price >= 1) && $result ['updateCnt'] >= 1) {
		$result ['result'] = true;
		$result ['updated'] = 1;
	}
	return $result;
}

// Update Order LITE version
function update_orders($post) {
    global $wpdb; // to use as global
    $_POST = $post;     // Fix: PHP 5.4
    $edited_object = json_decode ( stripslashes ( $_POST ['edited'] ) );

    $ordersCnt = 1;
    foreach ( $edited_object as $obj ) {
        $query = "UPDATE `". WPSC_TABLE_PURCHASE_LOGS . "`
						   SET 	processed ='".$wpdb->_real_escape($obj->order_status)."'
				   				 WHERE id ='".$wpdb->_real_escape($obj->id)."'";
        $update_result = $wpdb->query ( $query );
        $result ['updateCnt'] = $ordersCnt ++;
    }
    $result ['result'] = true;
    $result ['updated'] = 1;
    return $result;
}

// Data required for insert and update product detials.
function data_for_insert_update($post) {
	global $result, $wpdb, $user_ID;
	require_once (WP_PLUGIN_DIR . '/wp-e-commerce/wpsc-admin/includes/product-functions.php');
	$_POST = $post;     // Fix: PHP 5.4
	$selected_object = json_decode ( $_POST ['selected'] );
	$edited_object   = json_decode ( $_POST ['edited'] );
	$objectArray 	 = array ();
	
	if (is_array ( $edited_object )) {
		foreach ( $edited_object as $obj ) {
			array_push ( $objectArray, $obj );
		}
	}
	
	if (is_array ( $selected_object )) {
		foreach ( $selected_object as $obj ) {
			if (! in_array ( $obj, $objectArray ))
				array_push ( $objectArray, $obj );
		}
	}
	
	$insertCnt = 1;
	$updateCnt = 1;
	$result ['updated'] = 0;
	$result ['inserted'] = 0;
	$result ['productId'] = array ();	

	if (is_array ( $objectArray )) {
		foreach ( $objectArray as $obj ) {
			if ( isset ( $obj->id ) && $obj->id != '' ) {
				$post = get_post ( $obj->id );
			}
			// Default $post Array used only to INSERT new post & get postId which will be used as productId.
			// FOR wp_insert_post FUNCTION
			$post = array (
			'ID' 			 => $obj->id, 			  																		//Are you updating an existing post?
			'menu_order' 	 => ( isset( $post->menu_order ) ) ? $post->menu_order : 0, 									// If new post is a page, sets the order should it appear in the tabs.
			'comment_status' => ( isset( $post->comment_status ) ) ? $post->comment_status : 'closed', 				  		// 'closed' means no comments.
			'ping_status' 	 => ( isset( $post->ping_status ) ) ? $post->ping_status : 'closed', 							// 'closed' means pingbacks or trackbacks turned off
			'pinged' 		 => ( isset( $post->pinged ) ) ? $post->pinged : '', 											// ?
			'post_author' 	 => ( isset( $post->post_author ) ) ? $post->post_author : $user_ID, 							// The user ID number of the author.
			'post_category'  => '', 																						// Add some categories.
			'post_content'   => $obj->post_content, 																		// The full text of the post. short description 
			'post_date' 	 => isset($post->post_date) ? $post->post_date : '', 											// The time post was made.
			'post_date_gmt'  => isset($post->post_date_gmt) ? $post->post_date_gmt : '',   									// The time post was made, in GMT.
			'post_excerpt'   => $obj->post_excerpt, 																		// For all your post excerpt needs. 
			'post_name'      => ( isset( $post->post_name ) ) ? $post->post_name : '', 										// The name (slug) for your post
			'post_parent'    => $obj->post_parent, 																			// Sets the parent of the new post.
			'post_password'  => ( isset( $post->post_password ) ) ? $post->post_password : '', 								// password for post?
			'post_title'     => $obj->post_title, 																			// The title of your post. Product Name
			'post_status'    => $obj->post_status, 		  																	// Set the status of the new post.
			'post_type'      => 'wpsc-product', 																			// You may want to insert a regular post, page, link, a menu item or some custom post type
//		    'tags_input'     => [ '<tag>, <tag>, <...>' ]   																// For tags.
			'to_ping' 		 => ( isset( $post->to_ping ) ) ? $post->to_ping : '', 											//'guid'		
			'post_content_filtered' => ( isset( $post->post_content_filtered ) ) ? $post->post_content_filtered : '' 
			);
			
			//Default $data Array
			//FOR wpsc_pre_update FUNCTION & FOR wpsc_admin_submit_product FUNCTION
			$data = array (
			'post_author' 		=> $user_ID,
			'post_date' 		=> date ( 'Y-m-d H:i:s' ),
			'post_date_gmt' 	=> gmdate ( 'Y-m-d H:i:s' ),
			'post_content'  	=> $obj->post_content, 
			'post_title' 		=> $obj->post_title,
			'post_excerpt'  	=> $obj->post_excerpt, // @todo: $obj->additional_description or $obj->post_excerpt also check if it is used only for wpsc_admin_submit_product function because currently this value is filled in this function only.
			'post_status'   	=> $obj->post_status, 
			'post_type' 	 	=> 'wpsc-product', 
			'comment_status' 	=> 'closed', 
			'ping_status' 	 	=> 'closed', 
			'post_password'  	=> '', 
			'post_name' 	 	=> '', 				//strtolower($obj->post_title), @todo check tne function which gives post_name.
			'to_ping' 	     	=> '', 
			'pinged' 		 	=> '', 
			'post_modified'  	=> date ( 'Y-m-d H:i:s' ), 
			'post_modified_gmt' => gmdate ( 'Y-m-d H:i:s' ), 
			'post_parent' 	 	=> $obj->post_parent, 
			'menu_order' 	 	=> 0, 
			'guid' 			 	=> '',    // 'guid'					=> $guid
			'post_content_filtered' => ''
			);			
 

 
		// 'tax_input' 	   => Array (
		// 												'product_tag' 			=> 1, 						  // product_tag
		// 												'wpsc_product_category' => Array (0 => $obj->category ), // product category
		// 												'wpsc-variation' 		=> Array (0 => 0 ) 			  // product variation
		// 											),

						
			//FOR wpsc_pre_update FUNCTION & FOR wpsc_admin_submit_product FUNCTION
			//(not passed as an argument but used in the function)

            
            $wpsc_product_metadeta = Array(
                                                            'wpec_taxes_taxable_amount' => '', 
                                                            'external_link'             => '', 
                                                            'external_link_text'        => '', 
                                                            'external_link_target'      => '', 
                                                            'weight'                    => $obj->weight, 
                                                            'weight_unit'               => $obj->weight_unit,
                                                            'display_weight_as'         => $obj->weight_unit,
                                                            'dimensions' => Array (
                                                                                    'height'      => $obj->height, 
                                                                                    'height_unit' => $obj->height_unit, 
                                                                                    'width'       => $obj->width, 
                                                                                    'width_unit'  => $obj->width_unit, 
                                                                                    'length'      => $obj->length, 
                                                                                    'length_unit' => $obj->length_unit 
                                                                                  ), 
                                                            'shipping'                => Array ('local' => $obj->local, 'international' => $obj->international ), 
                                                            'no_shipping'             => $obj->no_shipping, 
                                                            'merchant_notes'          => '', 
                                                            'engraved'                => 0, 
                                                            'can_have_uploaded_image' => 0, 
                                                            'enable_comments'         => '' 
                                            );

            if (!empty($_POST['isWPSC3814']) && $_POST['isWPSC3814'] == '1') {
                $wpsc_product_metadeta ['dimensions'] = Array (
                                                    'height'      => $obj->height, 
                                                    'width'       => $obj->width, 
                                                    'length'      => $obj->length
                                                  );

                $wpsc_product_metadeta ['dimension_unit'] = $obj->dimension_unit; 
            } else {
                $wpsc_product_metadeta ['dimensions'] = Array (
                                                    'height'      => $obj->height, 
                                                    'height_unit' => $obj->height_unit, 
                                                    'width'       => $obj->width, 
                                                    'width_unit'  => $obj->width_unit, 
                                                    'length'      => $obj->length, 
                                                    'length_unit' => $obj->length_unit 
                                                  );
            }


			$_POST = array (
						'original_publish' => $obj->post_status, 
						'publish' 		   => $obj->post_status, 

						
						
			'meta' => Array (
							'_wpsc_price' 		  	 	=> $obj->_wpsc_price, 
							'_wpsc_special_price' 	 	=> $obj->_wpsc_special_price, 
							'_wpsc_sku' 		  	 	=> $obj->_wpsc_sku, 
							'_wpsc_stock' 		  	 	=> $obj->_wpsc_stock, 
							'_wpsc_product_metadata' 	=> $wpsc_product_metadeta
								),
			'table_rate_price' 		 => Array ('quantity' => Array (0 => '' ), 'table_price' => Array (0 => '' )),
			'ID' 			   		 => '', 
			'product_id' 	   		 => '', 
			'post_title' 	   		 => $obj->post_title, 
			'content' 		   		 => $obj->post_content, 
			'additional_description' => $obj->post_excerpt  // $obj->post_excerpt or $obj->additional_description
			); 


			if ($obj->id == '') {
				$result ['inserted'] = 1;
				$product_id 		 = wp_insert_post ( $post );

				$guid 				  = site_url () . '/?post_type=wpsc-product&p=' . $product_id;
				
				$data ['guid'] 		  = $guid;
				$_POST ['product_id'] = $product_id;
				$_POST ['ID'] 		  = $product_id;

				$data_value 		  = wpsc_pre_update ( $data, $_POST );
				$inserted_product_id  = wpsc_admin_submit_product ( $product_id, $data );
				
				$product_meta = $_POST ['meta'];
				if ($product_meta != null) {
					foreach ( ( array ) $product_meta as $key => $value ) {						
						$bool = update_post_meta ( $inserted_product_id, $key, $value, $prev_value = '' );
						if ($bool == true)
						$success = true;
					}
				}
				
				if ((isset ( $inserted_product_id ) && $inserted_product_id != 0) || $success == true){
					$result ['result'] = true;
				}
				array_push ( $result ['productId'], $inserted_product_id );
				
				if ($result ['result'])
					$result ['insertCnt'] = $insertCnt ++;
									
			} else {
				if (in_array ( $obj, $edited_object )) {
					$result ['updated'] = 1; // setting a flag to check on whether updated or inserted.

					//this will update the current product since we are already 
					//passing the id while forming the $post array
					$product_id 		  = wp_insert_post ( $post ); 
					$_POST ['product_id'] = $product_id;					
					$_POST ['ID'] 		  = $product_id;

					// insert the product weight in pound unit since wp-e-commerce does the same.
					$_POST['meta']['_wpsc_product_metadata']['weight'] = wpsc_convert_weight($_POST['meta']['_wpsc_product_metadata']['weight'], $_POST['meta']['_wpsc_product_metadata']['weight_unit'], "pound",true);
					
					// get the actual array of post meta from the database and overwrite it with the new values 
					// so that the keys of the array will be maintained and will get the proper serialized value.
					// helpful files: processing.functions.php, display-items.page.php, display-items-functions.php
					$product_meta_values = get_post_meta( $product_id, '_wpsc_product_metadata', true );					
					$_POST['meta']['_wpsc_product_metadata'] = array_merge((array)$product_meta_values,$_POST['meta']['_wpsc_product_metadata']);
					$product_meta = $_POST['meta'];

					if ($product_meta != null) {
						foreach ( ( array ) $product_meta as $key => $value ) {
							$bool = update_post_meta ( $product_id, $key, $value, $prev_value = '' );
							if ($bool == true)
								$success = true;
						}
					}
					
					if ($product_id == $obj->id || $success == true) {
						$result ['result'] 	  = 1;
						$result ['updateCnt'] = $updateCnt ++;
					}
				}
			}			
		}
	}
	return $result;
}
// Update order deatils including customer shipping details
function data_for_update_orders($post) {
	global $wpdb; // to use as global
	$_POST = $post;     // Fix: PHP 5.4
        $edited_object = json_decode ( $_POST ['edited'] );
	
	$query = "SELECT id,unique_name
  	 		  FROM " . WPSC_TABLE_CHECKOUT_FORMS . " 
			  WHERE unique_name IN ('shippingfirstname', 'shippinglastname', 'shippingaddress', 'shippingcity', 'shippingpostcode')
			  AND active = 1 
			  GROUP BY unique_name 
			  ORDER BY id";
	$result = $wpdb->get_results ( $query, 'ARRAY_A' );
	
	if (count ( $result ) >= 1) {
		foreach ( $result as $key => $arr_value )
			$id_uniquename [$arr_value ['unique_name']] = $arr_value ['id'];
	}
	
	$ordersCnt = 1;
	foreach ( $edited_object as $obj ) {
		$query = "UPDATE `". WPSC_TABLE_PURCHASE_LOGS . "`
						   SET 	processed ='".$wpdb->_real_escape($obj->order_status)."',
								    notes ='".$wpdb->_real_escape($obj->notes)."',
								 track_id ='".$wpdb->_real_escape($obj->track_id)."'
				   				 WHERE id ='".$wpdb->_real_escape($obj->id)."'";
		$update_result = $wpdb->query ( $query );

		foreach ( $id_uniquename as $uniquename => $form_id ) {
			$update_value = $wpdb->_real_escape($obj->$uniquename);

			//$key contains unique name

			$query = "UPDATE `" . WPSC_TABLE_SUBMITED_FORM_DATA . "`
				         SET value   = '" . $update_value . "'
				       WHERE form_id = $form_id
				         AND log_id  = '{$wpdb->_real_escape($obj->id)}'";
			$update_result = $wpdb->query ( $query );
		}
		$result ['updateCnt'] = $ordersCnt ++;
		unset ( $ship_country_info ); // unsetting the $ship_country_info
	}
	$result ['result'] = true;
	$result ['updated'] = 1;
	return $result;
}
// Update customers details
function update_customers($post) {
	global $wpdb;
	$_POST = $post;     // Fix: PHP 5.4
	$query = "SELECT isocode,country FROM `" . WPSC_TABLE_CURRENCY_LIST . "` ORDER BY `country` ASC";
	$results = $wpdb->get_results ( $query, 'ARRAY_A' );
	foreach($results as $result )
	$isocode_country [$result ['isocode']] = $result ['country'];

	$sql 	= "SELECT * FROM  `". WPSC_TABLE_REGION_TAX ."` ";
	$result_regions = $wpdb->get_results( $sql, 'ARRAY_A' );

	if (count($result_regions) >= 1) {
		foreach ($result_regions as $key => $arr_value)
		$regions [$arr_value ['id']] = $arr_value ['name'];
	}
	
	$query  = "SELECT id,unique_name 
			   FROM " . WPSC_TABLE_CHECKOUT_FORMS . " 
			   WHERE unique_name in ('billingfirstname', 'billinglastname', 'billingaddress', 'billingcity', 'billingpostcode', 'billingphone', 'billingemail') 
			   AND active = 1 
			   GROUP BY unique_name 
			   ORDER BY id";
	
	$result = $wpdb->get_results( $query, 'ARRAY_A' );
	if ( count($result) >= 1 ){
		foreach ($result as $key => $arr_value)
		$id_uniquename [$arr_value ['unique_name']] = $arr_value ['id'];
	}

	$affected_rows  = 0;	
	$edited_objects = json_decode ( $_POST ['edited'] );
		
	foreach ($edited_objects as $obj){
        
            //Code for handling the update for Registered Customers
            if($obj->id > 0) {
                
                $modified = $obj->modified;
                
                if (!(is_null($modified->billingfirstname))) {
                    $query_fname = "UPDATE $wpdb->usermeta SET meta_value ='" . $obj->billingfirstname
                    ."'WHERE meta_key = 'first_name' AND user_id = $obj->id ";
                    $result_fname = $wpdb->query($query_fname);
                }
                
                if (!(is_null($modified->billinglastname))) {
                    $query_lname = "UPDATE $wpdb->usermeta SET meta_value ='". $obj->billinglastname
                    ."'WHERE meta_key = 'last_name' AND user_id = $obj->id ";
                    $result_lname = $wpdb->query($query_lname);
                }
                
                if (!(is_null($modified->billingemail))) {
                    $query_email = "UPDATE $wpdb->users SET user_email ='". $obj->billingemail
                    ."'WHERE ID = $obj->id ";
                    $result_email = $wpdb->query($query_email);
                }
                
                $query = "SELECT meta_value FROM $wpdb->usermeta 
                    WHERE meta_key = 'wpshpcrt_usr_profile' AND user_id = $obj->id ";
                $result1 = $wpdb->get_results($query, 'ARRAY_A');
                $unserialized = unserialize($result1[0]['meta_value']);
                
                $unserialized[2] = $obj->billingfirstname;
                $unserialized[3] = $obj->billinglastname;
                $unserialized[4] = $obj->billingaddress;
                $unserialized[5] = $obj->billingcity;
                $unserialized[6] = $obj->billingstate;
                $unserialized[8] = $obj->billingpostcode;
                $unserialized[9] = $obj->billingemail;
                $unserialized[18] = $obj->billingphone;
            
                $serialized = serialize($unserialized);
                
                $query_serialised = "UPDATE $wpdb->usermeta SET meta_value ='". $serialized
                    ."'WHERE meta_key = 'wpshpcrt_usr_profile' AND user_id = $obj->id ";
                
                $result_serialised = $wpdb->query($query_serialised);
                
                $affected_rows ++; // update counter to get the total number of customers updated
                
            }
            else {    
		$old_email_id = $wpdb->_real_escape( $obj->Old_Email_Id );
		foreach ((array)$id_uniquename as $uniquename => $form_id ) {
			$update_value = $wpdb->_real_escape( $obj->$uniquename );
			
			//$key contains unique name
			$query = "UPDATE " . WPSC_TABLE_SUBMITED_FORM_DATA . " s1,
						     " . WPSC_TABLE_SUBMITED_FORM_DATA . " s2,
						     " . WPSC_TABLE_PURCHASE_LOGS . "

			          SET s1.value   = '$update_value'
				         		 
				      WHERE s1.form_id =  $form_id
		         	  AND ".WPSC_TABLE_PURCHASE_LOGS.".id = s1.log_id
		         	  AND s1.log_id  = '" . $wpdb->_real_escape( $obj->last_order_id ) . "'";
			$update_result = $wpdb->query($query);

			if ($update_result) {
			$affected_rows ++;
		}
	}
            }
	}
	
	$result = array ();
	if ($affected_rows >= 1) {
		$result ['result'] = true;
		$result ['updateCnt'] = count ( $edited_objects );
	} else {
		$result ['result'] = true;
		$result ['updateCnt'] = 0;
	}
	$result ['updated'] = 1;
	return $result;
}


// For updating product,orders and customers details.
if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'saveData') {
    
    check_ajax_referer('smart-manager-security','security');

        //For encoding the string in UTF-8 Format
//        $charset = "EUC-JP, ASCII, UTF-8, ISO-8859-1, JIS, SJIS";
        $charset = ( get_bloginfo('charset') === 'UTF-8' ) ? null : get_bloginfo('charset');
        
        if (!(is_null($charset))) {
            $_POST['edited'] = mb_convert_encoding(stripslashes($_POST['edited']),"UTF-8",$charset);
        }
        else {
            $_POST['edited'] = stripslashes($_POST['edited']);
        }
    
	if ($active_module == 'Products') {
			$result = data_for_insert_update ( $_POST );
	} elseif ($active_module == 'Orders') {
            $result = data_for_update_orders ( $_POST );
    } elseif ($active_module == 'Customers') {
        $result = update_customers ( $_POST );
    }

	if ($result ['result']) {
		if ($result ['updated'] && $result ['inserted']) {
			if ($result ['updateCnt'] == 1 && $result ['insertCnt'] == 1)
				$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Record Updated and', $sm_text_domain ) . "<br><b>" . $result ['insertCnt'] . "</b> " . __( 'New Record Inserted Successfully', $sm_text_domain );
			elseif ($result ['updateCnt'] == 1 && $result ['insertCnt'] != 1)
				$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Record Updated and', $sm_text_domain ) . "<br><b>" . $result ['insertCnt'] . "</b> " . __( 'New Records Inserted Successfully', $sm_text_domain );
			elseif ($result ['updateCnt'] != 1 && $result ['insertCnt'] == 1)
				$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Records Updated and', $sm_text_domain ) . "<br><b>" . $result ['insertCnt'] . "</b> " . __( 'New Record Inserted Successfully', $sm_text_domain );
			else
				$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Records Updated and', $sm_text_domain ) . "<br><b>" . $result ['insertCnt'] . "</b> " . __( 'New Records Inserted Successfully', $sm_text_domain ); 
		} else {
			
			if ($result ['updated'] == 1) {
				if ($result ['updateCnt'] == 1) {
					$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Record Updated Successfully', $sm_text_domain );
				} else {
					$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Records Updated Successfully', $sm_text_domain );
                                }
			}
			
			if ($result ['inserted'] == 1) {
				if ($result ['insertCnt'] == 1) {
					$encoded ['msg'] = "<b>" . $result ['insertCnt'] . "</b> " . __( 'New Record Inserted Successfully', $sm_text_domain ); 
                                } else {
					$encoded ['msg'] = "<b>" . $result ['insertCnt'] . "</b> " . __(' New Records Inserted Successfully', $sm_text_domain );
                                }
			}
			
		}
	}
	// ob_clean();

	while(ob_get_contents()) {
        ob_clean();
    }

        echo json_encode ( $encoded );

        exit;
}

if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'getRolesDashboard') {

    check_ajax_referer('smart-manager-security','security');

	global $wpdb, $current_user;

	if (!function_exists('wp_get_current_user')) {
		require_once (ABSPATH . 'wp-includes/pluggable.php'); // Sometimes conflict with SB-Welcome Email Editor
	}

	$current_user = wp_get_current_user();
        if ( !isset( $current_user->roles[0] ) ) {
            $roles = array_values( $current_user->roles );
        } else {
            $roles = $current_user->roles;
        }
	if ( SMPRO != true || $roles[0] == 'administrator') {
		$results = array( 'Products', 'Customers_Orders' );
	} else {
		$results = get_dashboard_combo_store();
	}
	// ob_clean();

	while(ob_get_contents()) {
        ob_clean();
    }

        echo json_encode ( $results );

        exit;
}

if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'editImage') {

    check_ajax_referer('smart-manager-security','security');
    
	$wpsc_default_image = WP_PLUGIN_URL . '/wp-e-commerce/wpsc-theme/wpsc-images/noimage.png';

    if (!empty($_POST['thumbnail_id'])) {
        update_post_meta($_POST ['id'], '_thumbnail_id' , $_POST['thumbnail_id']);
    }

//	$post_thumbnail_id = get_post_thumbnail_id( $_POST ['id'] );
//	$image = isset( $post_thumbnail_id ) ? wp_get_attachment_image_src( $post_thumbnail_id, 'admin-product-thumbnails' ) : '';
//	$thumbnail = ( $image[0] != '' ) ? $image[0] : '';
        $image = wpsc_the_product_thumbnail( '','', $_POST ['id'], '' );
        $thumbnail    = ( $image != '' ) ? $image : '';
	// ob_clean();

        while(ob_get_contents()) {
            ob_clean();
        }
        
        echo json_encode ( $thumbnail );

        exit;
}
//ob_end_flush();
?>