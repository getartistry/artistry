<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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
load_textdomain( $sm_text_domain, WP_PLUGIN_DIR . '/' . dirname( dirname(plugin_basename( __FILE__ ))) . '/languages/smart-manager-' . WPLANG . '.mo' );

global $wpdb;
$limit = 10;
$del = 3;
$result = array ();
$encoded = array ();

if (isset ( $_POST ['start'] ))
	$offset = $wpdb->_real_escape($_POST ['start']);
else
	$offset = 0;

if (isset ( $_POST ['limit'] ))
	$limit = $wpdb->_real_escape($_POST ['limit']);
	
// For pro version check if the required file exists
if (file_exists ( WP_PLUGIN_DIR . '/' . dirname( dirname(plugin_basename( __FILE__ ))) . '/pro/sm37.php' )) {
	define ( 'SMPRO', true );
	include_once ( WP_PLUGIN_DIR . '/' . dirname( dirname(plugin_basename( __FILE__ ))) . '/pro/sm37.php' );
} else {
	define ( 'SMPRO', false );
}
	
// getting the active module in the grid
$active_module = $_POST ['active_module'];

// Searching a product in the grid
function get_data_wpsc_37 ( $post, $offset, $limit, $is_export = false ) {
	global $wpdb;
	$_POST = $post;     // Fix: PHP 5.4
	// getting the active module
	$active_module = $_POST ['active_module'];
	
	if ( $is_export === true ) {
		$limit_string = "";
	} else {
		$limit_string = "LIMIT $offset,$limit";
	}

	if ($active_module == 'Products') { // <-products
	   $select_query = "SELECT pl.id,
	   						   pl.name,
	                           pl.description,
	                           pl.additional_description,
	                           pl.price,
							   pl.no_shipping as no_shipping,
	                           pl.pnp,
	                           pl.international_pnp,
	                           pi.image as thumbnail,
	                        if(pl.quantity_limited = 1,pl.quantity,-1 ) as quantity,
	                           pl.weight as weight,
	                        if(pl.publish = 1,'publish','draft') as publish,
	                           pl.price - pl.special_price as sale_price,
	                           sku_dimension,
	      	      			   GROUP_CONCAT(pc.name separator ', ') as category,
	                           pl.weight_unit as weight_unit";
		
		$from = " FROM ".WPSC_TABLE_PRODUCT_LIST." AS pl
            		       LEFT OUTER JOIN (".WPSC_TABLE_ITEM_CATEGORY_ASSOC." AS ic  
                           LEFT OUTER JOIN  ".WPSC_TABLE_PRODUCT_CATEGORIES." AS pc
                     	   ON (ic.category_id = pc.id) 
                     	   AND pc.active = 1)	ON ( pl.id = ic.product_id )
                     	   LEFT OUTER JOIN ".WPSC_TABLE_PRODUCT_IMAGES." as pi ON (pi.product_id = pl.id
                     	   AND pi.id = (SELECT MAX(pi_1.id) FROM ".WPSC_TABLE_PRODUCT_IMAGES." as pi_1 WHERE pi.product_id = pi_1.product_id ORDER BY pi_1.id DESC))

                     	   LEFT OUTER JOIN 
                     	   (SELECT GROUP_CONCAT(meta_value ORDER BY id) sku_dimension,product_id
                     	    FROM  ".WPSC_TABLE_PRODUCTMETA." 
                     	    WHERE meta_key = 'sku' 
                     	    OR meta_key = 'dimensions'
                     	    GROUP BY product_id) pm
                     	    ON ( pl.id = pm.product_id)";
		
		$where = " WHERE pl.active = 1 ";
		$group_by = " GROUP BY pl.id ";
		$limit_query = " $limit_string ;";
		
		if (isset ( $_POST ['searchText'] ) && $_POST ['searchText'] != '') {
			$search_on = $wpdb->_real_escape ( trim ( $_POST ['searchText'] ) );
			$where .= " AND ( concat(' ',pl.name) LIKE '% $search_on%' OR
										price LIKE '%$search_on%'  OR
                           	       	 quantity LIKE '%$search_on%'  OR
                              		   weight LIKE '%$search_on%'  OR
                               pl.weight_unit LIKE '%$search_on%'
                               		OR if(pl.publish = 1,'publish','draft') LIKE '%$search_on%'
                               		OR if(pl.no_shipping = 1,'Yes','No') LIKE '%$search_on%'
                              	    OR pl.price - pl.special_price LIKE '%$search_on%'
                              	    OR concat(' ',pc.name) LIKE '% $search_on%'
                              	    OR pl.pnp LIKE '%$search_on%'
                              	    OR sku_dimension LIKE '%$search_on%'
                              	    OR pl.international_pnp LIKE '%$search_on%'
                              	    OR pl.description LIKE '%$search_on%'
                              	    OR if(pl.quantity_limited = 1,pl.quantity,-1 ) LIKE '%$search_on%'                              	    
                              	    OR pl.additional_description LIKE '%$search_on%') ";
		}
		$recordcount_query = "SELECT COUNT( DISTINCT pl.id ) as count" . $from . "" . $where;
		$query = $select_query . "" . $from . "" . $where . "" . $group_by . "" . $limit_query;
		$record = $wpdb->get_results ( $query );		
		$num_rows = count( $record );
		$recordcount_result = $wpdb->get_results ( $recordcount_query );
		$num_records = $recordcount_result[0]->count;
		if ($num_rows == 0) {
			$encoded ['totalCount'] = '';
			$encoded ['items'] = '';
			$encoded ['msg'] = __( 'No Records Found',$sm_text_domain ) ;
		} else {
			foreach ( (array)$record as $data )
				$records[] = (array)$data;
		}
		$i = 0;
		// compare $i against $num_rows and not against $num_records
		// since $num_records gives the overall total count of the records in the database
		// whereas $num_rows gives the total count of records from current query
		while ($i < $num_rows ){
			if (is_array($records[$i])){
				
				foreach ($records[$i] as $record_key => $record_value){
					if ($record_key == 'sku_dimension')
					$sku_dimension_arr = explode(',',$record_value);

					$dimension_arr 				= unserialize($sku_dimension_arr[1]);
					$records[$i]['sku']         = $sku_dimension_arr[0];
					$records[$i]['height']      = $dimension_arr['height'];
					$records[$i]['height_unit'] = $dimension_arr['height_unit'];
					$records[$i]['width']       = $dimension_arr['width'];
					$records[$i]['width_unit']  = $dimension_arr['width_unit'];
					$records[$i]['length']      = $dimension_arr['length'];
					$records[$i]['length_unit'] = $dimension_arr['length_unit'];
					unset($records[$i]['sku_dimension']);
					if ( $record_key == 'thumbnail' ) {
						if ( file_exists( "../../../" . substr ( strstr ( WPSC_THUMBNAIL_URL, 'uploads' ), 0 ) . $records[$i]['thumbnail'] ) ) {
							$records[$i]['thumbnail'] = WP_CONTENT_URL . '/' . substr ( strstr ( WPSC_THUMBNAIL_URL, 'uploads' ), 0 ) . $records[$i]['thumbnail'];
						} else {
							$records[$i]['thumbnail'] = substr ( UPLOADS, strlen ( 'wp-content/' ) ) . substr ( strstr ( WPSC_THUMBNAIL_URL, 'uploads/' ), strlen ( 'uploads/' ) ) . $records[$i]['thumbnail'];
							
						}
					}
				}
			}
			$i++;
		}
	} //products ->
elseif ($active_module == 'Orders') {
		$query = "SELECT id,country_id, name, code FROM ".WPSC_TABLE_REGION_TAX;
			$result = $wpdb->get_results ( $query );

			if (count($result) >= 1){
				foreach ( (array) $result as $obj ) {
					$data = (array) $obj;
					$regions[$data['id']] = $data['name'];
				}
			}
			
		$query = "SELECT isocode,country FROM `".WPSC_TABLE_CURRENCY_LIST."` ORDER BY `country` ASC";
		$result = $wpdb->get_results ( $query );

		if (count($result) >= 1){
			foreach ( (array) $result as $obj ) {
				$data = (array) $obj;	
				$countries[$data['isocode']] = $data['country'];
			}
		}
		
	$select_query = "SELECT id,date,order_details,shipping_ids,shipping_unique_names,amount,track_id,order_status,details,notes";
		
	// added one more condition of active = 1 to where clause and replaced OR with AND for getting the submitted form id and added billing first name & last name in IN clause also added a for loop to create an array of key value
		$from = " FROM (SELECT GROUP_CONCAT( " . WPSC_TABLE_SUBMITED_FORM_DATA . ".value 
							   ORDER BY " . WPSC_TABLE_SUBMITED_FORM_DATA . ".`form_id` 
							   SEPARATOR '#' ) AS order_details, 
							   GROUP_CONCAT( CAST(form_id AS CHAR)
						       ORDER BY form_id  
							   SEPARATOR '#' ) AS shipping_ids,
							   GROUP_CONCAT(" . WPSC_TABLE_CHECKOUT_FORMS . ".unique_name
						       ORDER BY " . WPSC_TABLE_CHECKOUT_FORMS . ".`id` 
							   SEPARATOR '#' ) AS shipping_unique_names,
						       " . WPSC_TABLE_PURCHASE_LOGS . ".id, 
					  	       date_format(FROM_UNIXTIME(" . WPSC_TABLE_PURCHASE_LOGS . ".date),'%b %e %Y, %r') date,
						  	                             " . WPSC_TABLE_PURCHASE_LOGS . ".date as unixdate,
						  	                             " . WPSC_TABLE_PURCHASE_LOGS . ".date order_time,
							   						     " . WPSC_TABLE_PURCHASE_LOGS . ".totalprice amount,
							   							 " . WPSC_TABLE_PURCHASE_LOGS . ".track_id, 			                 
														 " . WPSC_TABLE_PURCHASE_LOGS . ".processed order_status,
                            						sessionid,
                            " . WPSC_TABLE_PURCHASE_LOGS . ".notes
						    FROM " . WPSC_TABLE_SUBMITED_FORM_DATA . ", 
						    	 " . WPSC_TABLE_PURCHASE_LOGS . ",
						    	 " . WPSC_TABLE_CHECKOUT_FORMS . "
						    	 
							WHERE " . WPSC_TABLE_SUBMITED_FORM_DATA . ".log_id = " . WPSC_TABLE_PURCHASE_LOGS . ".id
							 AND form_id = " . WPSC_TABLE_CHECKOUT_FORMS . ".id
							 AND " . WPSC_TABLE_CHECKOUT_FORMS . ".active = 1 
							 AND  " . WPSC_TABLE_SUBMITED_FORM_DATA . ".form_id IN 
							 
							 (							 
							 SELECT distinct id from " . WPSC_TABLE_CHECKOUT_FORMS . " WHERE unique_name
									IN (
										'billingfirstname',
										'billinglastname',
										'shippingfirstname',
										'shippinglastname',										
										'shippingaddress',
										'shippingcity',
										'shippingstate',
										'shippingcountry',
										'shippingpostcode',
										'billingemail',										
										'shippingphone'
									  )
							 	)
							 
							GROUP BY " . WPSC_TABLE_SUBMITED_FORM_DATA . ".log_id
							ORDER BY form_id DESC) as purchlog_info 
							
							LEFT JOIN (SELECT CONCAT(CAST(sum(quantity) AS CHAR) , ' items') details,
							GROUP_CONCAT(name) product_name,purchaseid
							
							FROM " . WPSC_TABLE_CART_CONTENTS . "
							GROUP BY " . WPSC_TABLE_CART_CONTENTS . ".purchaseid) as quantity_details 
							ON (purchlog_info.id = quantity_details.purchaseid)
							LEFT JOIN
							(SELECT  log_id,form_id,country,".WPSC_TABLE_REGION_TAX.".name as region
											FROM
											(SELECT log_id,form_id,country,CAST(CAST(SUBSTRING_INDEX(value,'\"',-2) AS signed)AS char) AS region_id
											FROM ".WPSC_TABLE_SUBMITED_FORM_DATA.",".WPSC_TABLE_CURRENCY_LIST." wwcl WHERE form_id =15
											AND RIGHT(SUBSTRING_INDEX(value,'\"',2),2) = isocode
											) AS country_info
											LEFT OUTER JOIN ".WPSC_TABLE_REGION_TAX."  
											ON (country_info.region_id = ".WPSC_TABLE_REGION_TAX.".id)) as countries_regions 
						                    ON (purchlog_info.id = countries_regions.log_id) ";
		
		$limit_query = " $limit_string ;";
		$where = ' WHERE 1 ';
		
		if (isset ( $_POST ['searchText'] ) && $_POST ['searchText'] != '') {
			$search_on = $wpdb->_real_escape ( trim ( $_POST ['searchText'] ) );
			$where .= " AND (purchlog_info.id in ('$search_on')
						  OR purchlog_info.sessionid like '%$search_on%'
						  OR purchlog_info.date like '%$search_on%'
						  OR purchlog_info.order_details like '%$search_on%'
						  OR purchlog_info.amount like '$search_on%'
						  OR purchlog_info.track_id like '%$search_on%'
						  OR purchlog_info.order_status like '%$search_on%'
						  OR purchlog_info.notes like '%$search_on%'
						  OR quantity_details.details like '%$search_on%' 
						  OR quantity_details.product_name like '%$search_on%' 
						  OR countries_regions.region like '%$search_on%'
						  OR countries_regions.country like '%$search_on%')";
		}
		
		if (isset ( $_POST ['fromDate'] )) {
			$from_date = strtotime ( $wpdb->_real_escape($_POST ['fromDate']) );
			$to_date = strtotime ( $wpdb->_real_escape($_POST ['toDate']) );
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
			$where .= " AND (purchlog_info.unixdate between '$from_date' and '$to_date') ";
		}
		
		$query = $select_query . " " . $from . "" . $where . " " . $limit_query;
		$result = $wpdb->get_results ( $query );
		$num_rows = count ( $result );
		
		//To get the total count
		$orders_count_query = $select_query . " " . $from . " " . $where;
		$orders_count_result = $wpdb->get_results ( $orders_count_query );
		$num_records = count ( $orders_count_result );
		
		if ($num_rows == 0) {
			$encoded ['totalCount'] = '';
			$encoded ['items'] = '';
			$encoded ['msg'] = __( 'No Records Found', $sm_text_domain ) ;
		} else {
			$count = 0;
			foreach ( $result as $obj ) {
				$data = (array) $obj;
				foreach ( $data as $key => $value ) {
					if ($key == 'order_details' || $key == 'shipping_ids' || $key == 'shipping_unique_names') {
						$order_details = explode ( '#', $data ['order_details'] );
						$shipping_ids = explode ( '#', $data ['shipping_ids'] );
						$shipping_unique_names = explode ( '#', $data ['shipping_unique_names'] );
						
						for($i = 0; $i < count ( $order_details ); $i ++) {
							$records [$count] [$shipping_unique_names [$i]] = $order_details [$i];
						}
						
						$name_emailid [0] = "<font class=blue>". $records [$count]['billingfirstname']."</font>";
						$name_emailid [1] = "<font class=blue>". $records [$count]['billinglastname']."</font>";
						$name_emailid [2] = "(".$records [$count]['billingemail'].")"; 
						$records [$count] ['name'] = implode ( ' ', $name_emailid ); //in front end,splitting is done with this space.
						
						//@todo confirm do u req formid in dataindex
						for($i = 0; $i < count ( $order_details ); $i ++) {
							// creating key by concat(id,unique name)
							if($shipping_unique_names [$i] == 'shippingcountry') {
								$order_details [$i]         = unserialize($order_details [$i]);
								$records [$count] [$shipping_unique_names [$i]] = $countries[$order_details[$i][0]];
								$records [$count] ['shippingstate'] = $regions[$order_details[$i][1]];
							}else {	
							    $records [$count] [$shipping_unique_names [$i]] = $order_details[$i];
							}
						}
					} else
						$records [$count] [$key] = $value;
				}
				$count ++;
			}			
		}
	} else {
		//Customer's module start
		
		$query 	  = "SELECT " . WPSC_TABLE_SUBMITED_FORM_DATA . ".id from " . WPSC_TABLE_SUBMITED_FORM_DATA . "
				  	 where form_id in (SELECT id FROM ". WPSC_TABLE_CHECKOUT_FORMS ." 
				  	 where `unique_name`in ('billingcountry', 'billingstate'))
				  	 $limit_string ;";
		
		$result   = $wpdb->get_results ( $query );
		$num_rows = count ( $result );		
		
		if ($num_rows){
			$region_exists  = true;
			$country_region = ', country, region';
		} else {
			$region_exists = false;
			$country_region = ' ';
		}
		
		if (SMPRO == true) {
			$customers_query = customers_query ( $wpdb->_real_escape ( $_POST ['searchText'] ), $region_exists, $country_region);
		} else {
				$customers_query = "SELECT log_id AS id,user_details,unique_names $country_region
                            FROM   (SELECT ord_emailid.log_id,
                                   user_details,unique_names $country_region
                                   FROM    (SELECT log_id, value email
                                           FROM " . WPSC_TABLE_SUBMITED_FORM_DATA . " wwsfd1
                                           WHERE form_id =( SELECT id
															  FROM ". WPSC_TABLE_CHECKOUT_FORMS ."
											WHERE unique_name =  'billingemail')) AS ord_emailid
											
                                     LEFT JOIN
									( SELECT log_id, 
									GROUP_CONCAT( wwsfd2.value ORDER BY form_id SEPARATOR  '#' ) user_details, 
									GROUP_CONCAT( wwcf.unique_name ORDER BY wwcf.id SEPARATOR  '#' ) unique_names					
									FROM ". WPSC_TABLE_SUBMITED_FORM_DATA ." as wwsfd2 
					
									LEFT JOIN ". WPSC_TABLE_CHECKOUT_FORMS ." wwcf ON ( wwcf.id = wwsfd2.form_id) 					
									WHERE unique_name
									IN (
										'billingfirstname',  
										'billinglastname',  
										'billingaddress',  
										'billingcity',  
										'billingstate',
										'billingcountry',
										'billingpostcode',
										'billingemail',
										'billingphone'
									) 
									GROUP BY log_id
									) AS ord_all_user_details ON ( ord_emailid.log_id = ord_all_user_details.log_id )";                                           

			if ($region_exists == true){
		     $customers_query .=   "LEFT JOIN
		                            (SELECT  log_id,form_id,country," . WPSC_TABLE_REGION_TAX . ".name as region
		                            FROM
		                            (SELECT log_id,form_id,country,CAST(CAST(SUBSTRING_INDEX(value,'\"',-2) AS signed)AS char) AS region_id
		                            FROM " . WPSC_TABLE_SUBMITED_FORM_DATA . " LEFT JOIN " . WPSC_TABLE_CURRENCY_LIST . " wwcl 
		                            ON (RIGHT(SUBSTRING_INDEX(value,'\"',2),2) = isocode) WHERE form_id = (SELECT id from ". WPSC_TABLE_CHECKOUT_FORMS ." WHERE unique_name = 'billingcountry')
		                            ) AS country_info
		                            LEFT OUTER JOIN " . WPSC_TABLE_REGION_TAX . " ON (country_info.region_id = " . WPSC_TABLE_REGION_TAX . ".id)) AS user_country_regions
		                            ON ( ord_emailid.log_id = user_country_regions.log_id)";
			}

			$customers_query .=   "GROUP BY email ) AS customers_info \n";

			if (isset ( $_POST ['searchText'] ) && $_POST ['searchText'] != '') {
				$search_text = $wpdb->_real_escape ( $_POST ['searchText'] );
				$customers_query .= "WHERE user_details LIKE '%$search_text%'
	    					         OR country   LIKE '$search_text%'
	    					         OR region   LIKE '$search_text%'";
			}
		}	
		
		$limit_query = " $limit_string ;";
		$query 	     = $customers_query . "" . $limit_query;
		$result 	 = $wpdb->get_results ( $query );
		$num_rows 	 = count ( $result );
		
		//To get Total count
		$customers_count_query = $customers_query;
		$customers_count_result = $wpdb->get_results ( $customers_count_query );
		$num_records = count ( $customers_count_result );
		
		if ($num_rows == 0) {
			$encoded ['totalCount'] = '';
			$encoded ['items'] = '';
			$encoded ['msg'] = __( 'No Records Found' , $sm_text_domain);
		} else {
			foreach ( $result as $obj ) {
				$data = (array) $obj;
				$user_details = explode ( '#', $data ['user_details'] );
				$unique_names = explode ( '#', $data ['unique_names'] );
				
				//note: while merging the array, $data as to be the second arg
				if (count ( $unique_names ) == count ( $user_details ))
					$records [] = array_merge ( array_combine($unique_names, $user_details), $data );				
			}			
			
			//getting records
			foreach ( $records as &$record ) {
				$record ['Last_Order'] = $record ['Last_Order_Date'] . ', ' . $record ['Last_Order_Amt'];
				$record ['billingcountry'] = $record['country'];
				$record ['billingstate']   = $record['region'];
				
		//create an extra array for email and merge it with the actual array because if we allow user to edit email addresses
		//then we cannot fire a query using email in the where clause since in the backend we will get a modified email address.
				$record ['Old_Email_Id'] = $record ['billingemail'];
				
				//no need to send this to front end
				unset($record['unique_names']);
				unset($record['user_details']);
				unset($record ['country']);
				unset($record ['region']);				
				
				if (SMPRO == false) {
					$record ['Total_Purchased'] = 'Pro only';
					$record ['Last_Order'] 	 	= 'Pro only';
				}
			}
		}
	}
	$encoded ['items'] = $records;
	$encoded ['totalCount'] = $num_records;
	return $encoded;
}

// Searching a product in the grid
if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'getData') {

	check_ajax_referer('smart-manager-security','security');

	$encoded = get_data_wpsc_37 ( $_POST, $offset, $limit );
        // ob_clean();

	while(ob_get_contents()) {
		ob_clean();
	}

	echo json_encode ( $encoded );
	unset($encoded);
}


if (isset ( $_GET ['func_nm'] ) && $_GET ['func_nm'] == 'exportCsvWpsc') {

	$encoded = get_data_wpsc_37 ( $_GET, $offset, $limit, true );
	$data = $encoded ['items'];
	unset($encoded);
	$columns_header = array();
	$active_module = $_GET ['active_module'];
	switch ( $active_module ) {
		
		case 'Products':
				$columns_header['id'] 						= 'Post ID';
				$columns_header['thumbnail'] 				= 'Product Image';
				$columns_header['name'] 					= 'Product Name';
				$columns_header['price'] 					= 'Price';
				$columns_header['sale_price'] 				= 'Sale Price';
				$columns_header['quantity'] 				= 'Inventory / Stock';
				$columns_header['sku'] 						= 'SKU';
				$columns_header['category'] 				= 'Category / Group';
				$columns_header['description'] 				= 'Product Description';
				$columns_header['additional_description'] 	= 'Additional Description';
				$columns_header['weight'] 					= 'Weight';
				$columns_header['weight_unit'] 				= 'Weight Unit';
				$columns_header['height'] 					= 'Height';
				$columns_header['height_unit'] 				= 'Height Unit';
				$columns_header['width'] 					= 'Width';
				$columns_header['width_unit'] 				= 'Width Unit';
				$columns_header['length'] 					= 'Length';
				$columns_header['length_unit'] 				= 'Length Unit';
				$columns_header['pnp'] 						= 'Local Shipping Fee';
				$columns_header['international_pnp'] 		= 'International Shipping Fee';
			break;
			
		case 'Customers':
				$columns_header['id'] 					= 'User ID';
				$columns_header['billingfirstname'] 	= 'First Name';
				$columns_header['billinglastname'] 		= 'Last Name';
				$columns_header['billingemail'] 		= 'E-mail ID';
				$columns_header['billingaddress'] 		= 'Address';
				$columns_header['billingpostcode'] 		= 'Postcode';
				$columns_header['billingcity'] 			= 'City';
				$columns_header['billingstate'] 		= 'State / Region';
				$columns_header['billingcountry'] 		= 'Country';
				$columns_header['Last_Order_Amt'] 		= 'Last Order Total';
				$columns_header['Last_Order_Date'] 		= 'Last Order Date';
				$columns_header['Total_Purchased'] 		= 'Total Purchased Till Date (By Customer)';
				$columns_header['billingphone'] 		= 'Phone / Mobile';
			break;
			
		case 'Orders':
				$columns_header['id'] 						= 'Order ID';
				$columns_header['date'] 					= 'Order Date';
				$columns_header['billingfirstname'] 		= 'Billing First Name';
				$columns_header['billinglastname'] 			= 'Billing Last Name';
				$columns_header['billingemail'] 			= 'Billing E-mail ID';
				$columns_header['amount'] 					= 'Order Total';
				$columns_header['details'] 					= 'Total No. of Items';
				$columns_header['order_status'] 			= 'Order Status';
				$columns_header['track_id'] 				= 'Track ID';
				$columns_header['notes'] 					= 'Order Notes';
				$columns_header['shippingfirstname'] 		= 'Shipping First Name';
				$columns_header['shippinglastname'] 		= 'Shipping Last Name';
				$columns_header['shippingaddress'] 			= 'Shipping Address';
				$columns_header['shippingpostcode'] 		= 'Shipping Postcode';
				$columns_header['shippingcity'] 			= 'Shipping City';
				$columns_header['shippingstate'] 			= 'Shipping State / Region';
				$columns_header['shippingcountry'] 			= 'Shippping Country';
			break;
	}
	// ob_clean();

	while(ob_get_contents()) {
		ob_clean();
	}

	echo export_csv_wpsc_37 ( $active_module, $columns_header, $data );
	exit;
}

// Delete product.
if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'delData') {

	check_ajax_referer('smart-manager-security','security');
	
	$delCnt = 0;
	if ($active_module == 'Products') {
		$data = json_decode ( stripslashes ( $_POST ['data'] ) );
		$data = implode ( ',', $data );
		$query = "UPDATE " . WPSC_TABLE_PRODUCT_LIST . " SET active = 0 WHERE id in ($data)";
		$result = $wpdb->query ( $query );
		$delCnt = $wpdb->rows_affected;
		if ($result) {
			if ($delCnt == 1) {
				$encoded ['msg'] = $delCnt . " " . __( 'Product deleted Successfully' , $sm_text_domain);
				$encoded ['delCnt'] = $delCnt;
			} else {
				$encoded ['msg'] = $delCnt . " " . __( 'Products deleted Successfully' , $sm_text_domain); 
				$encoded ['delCnt'] = $delCnt;
			}
		} else
			$encoded ['msg'] = __( "Products removed from the grid" , $sm_text_domain); 
	} else if ($active_module == 'Orders') {
		global $purchlogs;
		$data = json_decode ( stripslashes ( $_POST ['data'] ) );
		foreach ( $data as $key => $id ) {
			$output = $purchlogs->deletelog ( $id );
			$delCnt ++;
		}
		if ($output) {
			//			$encoded ['msg'] = strip_tags($output);
			if ($delCnt == 1) {
				$encoded ['msg'] = $delCnt . " " . __( 'Purchase Log deleted Successfully' , $sm_text_domain);
				$encoded ['delCnt'] = $delCnt;
			} else {
				$encoded ['msg'] = $delCnt . " " . __( 'Purchase Logs deleted Successfully' , $sm_text_domain);
				$encoded ['delCnt'] = $delCnt;
			}
		} else
			$encoded ['msg'] = __( "Purchase Logs removed from the grid" . $sm_text_domain); 
	}
	// ob_clean();

	while(ob_get_contents()) {
		ob_clean();
	}

        echo json_encode ( $encoded );
}

function update_products($post) {
	global $table_prefix, $result, $wpdb;
	$_POST = $post;     // Fix: PHP 5.4
        $edited_object = json_decode ( stripslashes ( $_POST ['edited'] ) );
	$updateCnt = 1;
	foreach ( $edited_object as $obj ) {
		$query = "UPDATE " . WPSC_TABLE_PRODUCT_LIST . " SET name = '" . $wpdb->_real_escape($obj->name) . "',
                                         				    price = " . $wpdb->_real_escape($obj->price) . "
                                      	 				 WHERE id = " . $wpdb->_real_escape($obj->id);
		$update_productListTbl = $wpdb->query ( $query );
		$result ['updateCnt'] = $updateCnt ++;
	}
	if ($update_productListTbl && $result ['updateCnt'] >= 1) {
		$result ['result'] = true;
		$result ['updated'] = 1;
	}
	return $result;
}

// For updating product and orders details.
if (isset ( $_POST ['cmd'] ) && $_POST ['cmd'] == 'saveData') {

	check_ajax_referer('smart-manager-security','security');
	
	if ($active_module == 'Products') {
		if (SMPRO == true)
			$result = data_for_insert_update ( $_POST );
		else
			$result = update_products ( $_POST );
	} 

	elseif ($active_module == 'Orders')
		$result = data_for_update_orders ( $_POST );
	elseif ($_POST ['active_module'] == 'Customers')
		$result = update_customers ( $_POST );
	
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
				if ($result ['updateCnt'] == 1)
					$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Record Updated Successfully', $sm_text_domain );
				else
					$encoded ['msg'] = "<b>" . $result ['updateCnt'] . "</b> " . __( 'Records Updated Successfully', $sm_text_domain ); 
			}
			
			if ($result ['inserted'] == 1) {
				if ($result ['updateCnt'] == 1)
					$encoded ['msg'] = "<b>" . $result ['insertCnt'] . "</b> " . __( 'New Records Inserted Successfully', $sm_text_domain );
				else
					$encoded ['msg'] = "<b>" . $result ['insertCnt'] . "</b> " . __( 'New Records Inserted Successfully', $sm_text_domain ); 
			}
		}
	}
	// ob_clean();

	while(ob_get_contents()) {
		ob_clean();
	}

        echo json_encode ( $encoded );	
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
}

?>