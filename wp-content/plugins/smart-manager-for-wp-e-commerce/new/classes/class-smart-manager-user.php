<?php

if ( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Smart_Manager_User' ) ) {
	class Smart_Manager_User extends Smart_Manager_Base {

		public $usermeta_ignored_cols;

		function __construct($dashboard_key) {
			$this->dashboard_key = $dashboard_key;
			$this->post_type = $dashboard_key;
			$this->req_params  	= (!empty($_REQUEST)) ? $_REQUEST : array();

			$this->usermeta_ignored_cols = apply_filters('sm_usermeta_ignored_cols', array('session_tokens', 'wp_woocommerce_product_import_mapping', 'wp_product_import_error_log'));

			add_filter( 'sm_default_dashboard_model',array(&$this,'default_user_dashboard_model') );
			add_filter( 'sm_beta_load_default_data_model',array(&$this,'load_default_data_model') );
			add_filter( 'sm_beta_default_inline_update',array(&$this,'default_inline_update') );
			add_filter( 'sm_inline_update_post',array(&$this,'user_inline_update'), 10, 2 );
			add_filter( 'sm_data_model',array(&$this,'generate_data_model'), 10, 3 );
		}

		public function default_user_dashboard_model ($dashboard_model) {

			global $wpdb, $current_user, $_wp_admin_css_colors;

			$col_model = array();

			$default_hidden_cols = apply_filters( 'sm_users_default_hidden_cols', array( 'user_url', 'user_activation_key', 'user_status' ) );
			$default_non_editable_cols = apply_filters( 'sm_users_default_non_editable_cols', array( 'ID', 'user_login' ) );
			$default_ignored_cols = apply_filters( 'sm_users_default_ignored_cols', array( 'user_activation_key', 'user_status' ) );

			$query_users_col = "SHOW COLUMNS FROM {$wpdb->prefix}users";
			$results_users_col = $wpdb->get_results($query_users_col, 'ARRAY_A');
			$users_num_rows = $wpdb->num_rows;

			if ($users_num_rows > 0) {
				foreach ($results_users_col as $col) {
					
					$temp = array();
					$field_nm = (!empty($col['Field'])) ? $col['Field'] : '';

					if( in_array($field_nm, $default_ignored_cols) ) {
						continue;
					}

					$temp ['src'] = 'users/'.$field_nm;
					$temp ['index'] = sanitize_title(str_replace('/', '_', $temp ['src'])); // generate slug using the wordpress function if not given 
					$temp ['name'] = __(ucwords(str_replace('_', ' ', $field_nm)), Smart_Manager::$text_domain);

					$temp ['table_name'] = $wpdb->prefix.'users';
					$temp ['col_name'] = $field_nm;

					$temp ['key'] = $temp ['name']; //for advanced search

					$type = 'string';
					$temp ['width'] = 100;
					$temp ['align'] = 'left';

					if (!empty($col['Type'])) {
						$type_strpos = strrpos($col['Type'],'(');
						if ($type_strpos !== false) {
							$type = substr($col['Type'], 0, $type_strpos);
						} else {
							$type = $col['Type'];
						}

						if (substr($type,-3) == 'int') {
							$type = 'number';
							$temp ['min'] = 0;
							$temp ['width'] = 50;
							$temp ['align'] = 'right';
						} else if ($type == 'text') {
							$temp ['width'] = 130;
							$type = 'string';
						} else if (substr($type,-4) == 'char' || substr($type,-4) == 'text') {
							if ($type == 'longtext') {
								$type = 'longstring';
								$temp ['width'] = 150;
							} else {
								$type = 'string';
							}
						} else if (substr($type,-4) == 'blob') {
							$type = 'longstring';
						} else if ($type == 'datetime' || $type == 'timestamp') {
							$type = 'datetime';
							$temp ['width'] = 102;
						} else if ($type == 'date' || $type == 'year') {
							$type = 'date';
						} else if ($type == 'decimal' || $type == 'float' || $type == 'double' || $type == 'real') {
							$type = 'integer';
							$temp ['min'] = 0;
							$temp ['width'] = 50;
							$temp ['align'] = 'right';
						} else if ($type == 'boolean') {
							$type = 'toggle';
							$temp ['width'] = 30;
						}

					}

					$temp ['hidden']			= false;
					$temp ['editable']			= true;
					$temp ['batch_editable']	= true; // flag for enabling the batch edit for the column
					$temp ['sortable']			= true;
					$temp ['resizable']			= true;

					//For disabling frozen
					$temp ['frozen']			= false;

					$temp ['allow_showhide']	= true;
					$temp ['exportable']		= true; //default true. flag for enabling the column in export
					$temp ['searchable']		= true;

					$temp ['placeholder'] = ''; //for advanced search

					//Code for handling the positioning of the columns
					if ($field_nm == 'ID') {
						$temp ['position'] = 1;
						$temp ['align'] = 'left';
					} else if ($field_nm == 'user_login') {
						$temp ['position'] = 2;
					} else if ($field_nm == 'user_pass') {
						$temp ['position'] = 3;
						$temp ['searchable'] = false;
						$temp ['placeholder'] = 'Click to change';
						$type = 'text';
					}

					if( !empty( $default_non_editable_cols ) && in_array( $field_nm, $default_non_editable_cols ) ) {
						$temp ['editable'] = false;
						$temp ['batch_editable'] = false;
					}

					$temp ['type'] = $type;

					$temp ['values'] = array();
					$temp ['hidden'] = ( in_array($field_nm, $default_hidden_cols) ) ? true : false;
					$temp ['category'] = ''; //for advanced search
					

					$col_model [] = $temp;


				}
			}

			$default_um_visible_cols = apply_filters('sm_usermeta_visible_cols', array('first_name', 'last_name', 'description', 'rich_editing', 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_country', 'billing_email', 'billing_phone'));

			$default_um_disabled_cols = apply_filters('sm_usermeta_disabled_cols', array('billing_country', 'billing_state'));

			//code for getting the meta cols
			$results_usermeta_col = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT(meta_key) as meta_key,
																meta_value
															FROM {$wpdb->prefix}usermeta
															WHERE meta_key NOT IN ( '". implode("','", $this->usermeta_ignored_cols) ."' )
																AND 1=%d
															GROUP BY meta_key", 1), 'ARRAY_A');
			$um_num_rows = $wpdb->num_rows;


			if ($um_num_rows > 0) {

				$meta_keys = array();

				foreach ($results_usermeta_col as $key => $usermeta_col) {
					if (empty($usermeta_col['meta_value'])) {
						$meta_keys [] = $usermeta_col['meta_key']; //TODO: if possible store in db instead of using an array
					}

					unset($results_usermeta_col[$key]);
					$results_usermeta_col[$usermeta_col['meta_key']] = $usermeta_col;
				}

				if (!empty($meta_keys)) {
					$results_um_meta_value = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT(meta_key) as meta_key,
																				meta_value
																			FROM {$wpdb->prefix}usermeta
																			WHERE meta_key IN ( '". implode("','", $meta_keys) ."' )
																				AND meta_value != %s
																			GROUP BY meta_key", ''), 'ARRAY_A');
					$num_rows_meta_value = $wpdb->num_rows;

					if ($num_rows_meta_value > 0) {
						foreach ($results_um_meta_value as $result_meta_value) {
							if (isset($results_usermeta_col [$result_meta_value['meta_key']])) {
								$results_usermeta_col [$result_meta_value['meta_key']]['meta_value'] = $result_meta_value['meta_value'];
							}
						}
					}
				}

				$index = sizeof($col_model);

				$col_model [$index] = array();
				$col_model [$index]['src'] = 'usermeta/user_id';
				$col_model [$index]['index'] = sanitize_title(str_replace('/', '_', $col_model [$index]['src'])); // generate slug using the wordpress function if not given 
				$col_model [$index]['name'] = __(ucwords(str_replace('_', ' ', 'user_id')), Smart_Manager::$text_domain);
				$col_model [$index]['key'] = $col_model [$index]['name']; //for advanced search
				$col_model [$index]['type'] = 'number';
				$col_model [$index]['hidden']	= true;
				$col_model [$index]['allow_showhide'] = false;
				$col_model [$index]['editable']	= false;
				$col_model [$index]['batch_editable']	= false;
				$col_model [$index]['exportable']		= true; //default true. flag for enabling the column in export
				$col_model [$index]['searchable']		= true;

				$col_model [$index]['table_name'] = $wpdb->prefix.'usermeta';
				$col_model [$index]['col_name'] = 'user_id';

				$col_model [$index] ['category'] = ''; //for advanced search
				$col_model [$index] ['placeholder'] = ''; //for advanced search

				$index++;


				$col_model [$index] = array();
				$col_model [$index]['src'] = 'usermeta/role';
				$col_model [$index]['index'] = sanitize_title(str_replace('/', '_', $col_model [$index]['src'])); // generate slug using the wordpress function if not given 
				$col_model [$index]['name'] = __(ucwords(str_replace('_', ' ', 'role')), Smart_Manager::$text_domain);
				$col_model [$index]['key'] = $col_model [$index]['name']; //for advanced search
				$col_model [$index]['type'] = 'list';
				$col_model [$index]['hidden']	= false;
				$col_model [$index]['allow_showhide'] = true;
				$col_model [$index]['editable']	= true;
				$col_model [$index]['position'] = 2;
				$col_model [$index]['batch_editable']	= true; // flag for enabling the batch edit for the column
				$col_model [$index]['exportable']		= true; //default true. flag for enabling the column in export
				$col_model [$index]['searchable']		= true;

				$all_roles = array();
				$col_model [$index]['values'] = array();

				if( function_exists('get_editable_roles') ) {
					$all_roles = get_editable_roles();	
				}

				if( !empty( $all_roles ) ) {

					$col_model [$index]['search_values'] = array();

					foreach ( $all_roles as $role => $details) {
                		$name = translate_user_role( $details['name'] );
                		$col_model [$index]['values'][$role] = $name;
                		$col_model [$index]['search_values'][] = array('key' => $role, 'value' => $name);
                	}
				}

				$col_model [$index]['table_name'] = $wpdb->prefix.'usermeta';
				$col_model [$index]['col_name'] = 'wp_capabilities';

				$col_model [$index] ['category'] = ''; //for advanced search
				$col_model [$index] ['placeholder'] = ''; //for advanced search


				$index++;

				$custom_cols = array('last_order_date', 'last_order_total', 'orders_count', 'orders_total');

				foreach( $custom_cols as $col ) {
					$col_model [$index] = array();
					$col_model [$index]['src'] = 'custom/'.$col;
					$col_model [$index]['index'] = sanitize_title(str_replace('/', '_', $col_model [$index]['src'])); // generate slug using the wordpress function if not given 
					$col_model [$index]['name'] = __(ucwords(str_replace('_', ' ', $col)), Smart_Manager::$text_domain);
					$col_model [$index]['key'] = $col_model [$index]['name']; //for advanced search
					$col_model [$index]['type'] = 'string';
					$col_model [$index]['hidden']	= false;
					$col_model [$index]['allow_showhide'] = true;
					$col_model [$index]['editable']	= false;

					$col_model [$index]['table_name'] = $wpdb->prefix.'usermeta';
					$col_model [$index]['col_name'] = 'user_id';
					$col_model [$index]['exportable'] = true; //default true. flag for enabling the column in export
					$col_model [$index]['searchable'] = true;

					$col_model [$index] ['category'] = ''; //for advanced search
					$col_model [$index] ['placeholder'] = ''; //for advanced search

					$index++;
				}


				foreach ($results_usermeta_col as $usermeta_col) {

					$temp = array();
					$type = 'string';

					$meta_key = ( !empty( $usermeta_col['meta_key'] ) ) ? $usermeta_col['meta_key'] : '';
					$meta_value = ( !empty( $usermeta_col['meta_value'] ) || $usermeta_col['meta_value'] == 0 ) ? $usermeta_col['meta_value'] : '';

					$temp ['src'] = 'usermeta/meta_key='.$meta_key.'/meta_value='.$meta_key;
					$temp ['index'] = sanitize_title(str_replace(array('/','='), '_', $temp ['src'])); // generate slug using the wordpress function if not given 
					$temp ['name'] = __(ucwords(str_replace('_', ' ', $meta_key)), Smart_Manager::$text_domain);
					$temp ['key'] = $temp ['name']; //for advanced search

					$temp ['table_name'] = $wpdb->prefix.'usermeta';
					$temp ['col_name'] = $meta_key;

					$temp ['width'] = 100;
					$temp ['align'] = 'left';

					if ( $meta_value == 'yes' || $meta_value == 'no' || $meta_value == 'true' || $meta_value == 'false' || ( is_numeric($meta_value) && ( $meta_value == 0 || $meta_value == 1 ) ) ) {
						$type = 'toggle';
						$temp ['width'] = 30;
					} else if( is_numeric( $meta_value ) ) {
						$type = 'number';
						$temp ['min'] = 0;
						$temp ['width'] = 50;
						$temp ['align'] = 'right';
					} else if( is_serialized( $meta_value ) === true ) {
						$type = 'longstring';
						$temp ['width'] = 200;
					}

					$temp ['type'] = $type;
					$temp ['values'] = array();

					if( $meta_key == 'admin_color' ) {

						$temp ['search_values'] = array();
						
						$themes = array_keys($_wp_admin_css_colors);
						foreach( $themes as $theme ) {
							$name = ( !empty($_wp_admin_css_colors[$theme]) ) ? $_wp_admin_css_colors[$theme]->name : ucwords($theme);
							$temp ['values'][$theme] = $name;
                			$temp ['search_values'][] = array('key' => $theme, 'value' => $name);
						}
					}

					$temp ['hidden'] = ( !empty($default_um_visible_cols) && in_array($meta_key, $default_um_visible_cols) ) ? false : true;
					$hidden_col_array = array('_edit_lock','_edit_last');

					if (array_search($meta_key,$hidden_col_array) !== false ) {
						$temp ['hidden'] = true;	
					}

					
					$temp ['editable']			= ( !empty($default_um_disabled_cols) && in_array($meta_key, $default_um_disabled_cols) ) ? false : true;
					$temp ['batch_editable']	= true; // flag for enabling the batch edit for the column
					$temp ['sortable']			= true;
					$temp ['resizable']			= true;
					$temp ['frozen']			= false;
					$temp ['allow_showhide']	= true;
					$temp ['exportable']		= true; //default true. flag for enabling the column in export
					$temp ['searchable']		= true;

					$temp ['category'] = ''; //for advanced search
					$temp ['placeholder'] = ''; //for advanced search

					$col_model [] = $temp;
				}

			}

			$dashboard_model[$this->dashboard_key]['columns'] = $col_model;

			return $dashboard_model;			
		}

		//function to avoid generation of the default data model
		public function load_default_data_model ($flag) {
			return false;
		}


		public function process_user_search_cond($params = array()) {

			global $wpdb;


			if( empty($params) || empty($params['search_query']) ) {
				return;
			}


			$wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp"); // query to reset advanced search temp table

            $advanced_search_query = array();
            $i = 0;

            update_option('sm_advanced_search_query',$params['search_query']);

            foreach ($params['search_query'] as $search_string_array) {

                $search_string_array = json_decode(stripslashes($search_string_array),true);

                if (is_array($search_string_array)) {

                		// START FROM HERE

                        $advanced_search_query[$i] = array();
                        $advanced_search_query[$i]['cond_users'] = '';
                        $advanced_search_query[$i]['cond_usermeta'] = '';

                        $advanced_search_query[$i]['cond_usermeta_col_name'] = '';
                        $advanced_search_query[$i]['cond_usermeta_col_value'] = '';
                        $advanced_search_query[$i]['cond_usermeta_operator'] = '';

                        $search_value_is_array = 0; //flag for array of search_values

                        $search_string_array = apply_filters('sm_user_before_search_string_process', $search_string_array);

                        foreach ($search_string_array as $search_string) {

                            $search_key = (!empty($search_string['key'])) ? $search_string['key'] : '';
                            $search_col = (!empty($search_string['col_name'])) ? $search_string['col_name'] : '';
                            $search_operator = (!empty($search_string['operator'])) ? $search_string['operator'] : '';
                            $search_data_type = (!empty($search_string['type'])) ? $search_string['type'] : 'string';
                            $search_value = (!empty($search_string['value']) && $search_string['value'] != "''") ? $search_string['value'] : (($search_data_type == "number") ? '0' : '');

                            $search_params = array('search_string' => $search_string,
													'search_col' => $search_col,
													'search_operator' => $search_operator, 
													'search_data_type' => $search_data_type, 
													'search_value' => $search_value,
													'SM_IS_WOO30' => (!empty($params['SM_IS_WOO30'])) ? $params['SM_IS_WOO30'] : '');

                            if (!empty($search_string['table_name']) && $search_string['table_name'] == $wpdb->prefix.'users') {

                            	$search_col = apply_filters('sm_search_format_query_users_col_name', $search_col, $search_params);
                                $search_value = apply_filters('sm_search_format_query_users_col_value', $search_value, $search_params);

                                if ($search_data_type == "number") {
                                    $users_cond = $search_string['table_name'].".".$search_col . " ". $search_operator ." " . $search_value;
                                } else {
                                    if ($search_operator == 'is') {
                                        $users_cond = $search_string['table_name'].".".$search_col . " LIKE '" . $search_value . "'";
                                    } else if ($search_operator == 'is not') {
                                        $users_cond = $search_string['table_name'].".".$search_col . " NOT LIKE '" . $search_value . "'";
                                    } else {
                                        $users_cond = $search_string['table_name'].".".$search_col . " ". $search_operator ."'%" . $search_value . "%'";
                                    }
                                }

                                $users_cond = apply_filters('sm_search_users_cond', $users_cond, $search_params);

                                $advanced_search_query[$i]['cond_users'] .= $users_cond ." AND ";

                            } else if (!empty($search_string['table_name']) && $search_string['table_name'] == $wpdb->prefix.'usermeta') {

                                $advanced_search_query[$i]['cond_usermeta_col_name'] .= $search_col;
                                $advanced_search_query[$i]['cond_usermeta_col_value'] .= $search_value;

                                $search_col = apply_filters('sm_search_format_query_usermeta_col_name', $search_col, $search_params);
                                $search_value = apply_filters('sm_search_format_query_usermeta_col_value', $search_value, $search_params);

                                if ($search_data_type == "number") {
                                    $postmeta_cond = " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value ". $search_operator ." " . $search_value . " )";
                                    $advanced_search_query[$i]['cond_usermeta_operator'] .= $search_operator;
                                } else {
                                    if( $search_operator == 'is' ) {

                                    	if( $search_key == 'Role' ) {
                                    		$search_value = '%'. $search_value .'%';
                                    	}

                                        $advanced_search_query[$i]['cond_usermeta_operator'] .= 'LIKE';
                                        $postmeta_cond = " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value LIKE '" . $search_value . "'" . " )";

                                        
                                    } else if( $search_operator == 'is not' ) {

                                    	if( $search_key == 'Role' ) {
                                    		$search_value = '%'. $search_value .'%';
                                    	}

                                        $advanced_search_query[$i]['cond_usermeta_operator'] .= 'NOT LIKE';
                                        $postmeta_cond = " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value NOT LIKE '" . $search_value . "'" . " )";

                                    } else {

                                        $advanced_search_query[$i]['cond_usermeta_operator'] .= $search_operator;
                                        $postmeta_cond = " ( ". $search_string['table_name'].".meta_key LIKE '". $search_col . "' AND ". $search_string['table_name'] .".meta_value ". $search_operator ." '%" . $search_value . "%'" . " )";
                                    }
                                    
                                }

                                $postmeta_cond = apply_filters('sm_search_usermeta_cond', $postmeta_cond, $search_params);

                                $advanced_search_query[$i]['cond_usermeta'] .= $postmeta_cond ." AND ";
                                $advanced_search_query[$i]['cond_usermeta_col_name'] .= " AND ";
                                $advanced_search_query[$i]['cond_usermeta_col_value'] .= " AND ";
                                $advanced_search_query[$i]['cond_usermeta_operator'] .= " AND ";

                            }

                            $advanced_search_query[$i] = apply_filters('sm_user_search_query_formatted', $advanced_search_query[$i], $search_params);
                        }

                        $advanced_search_query[$i]['cond_users'] = (!empty($advanced_search_query[$i]['cond_users'])) ? substr( $advanced_search_query[$i]['cond_users'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_usermeta'] = (!empty($advanced_search_query[$i]['cond_usermeta'])) ? substr( $advanced_search_query[$i]['cond_usermeta'], 0, -4 ) : '';

                        $advanced_search_query[$i]['cond_usermeta_col_name'] = (!empty($advanced_search_query[$i]['cond_usermeta_col_name'])) ? substr( $advanced_search_query[$i]['cond_usermeta_col_name'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_usermeta_col_value'] = (!empty($advanced_search_query[$i]['cond_usermeta_col_value'])) ? substr( $advanced_search_query[$i]['cond_usermeta_col_value'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_usermeta_operator'] = (!empty($advanced_search_query[$i]['cond_usermeta_operator'])) ? substr( $advanced_search_query[$i]['cond_usermeta_operator'], 0, -4 ) : '';

                    }

                    $i++;
                }

                //Code for handling advanced search conditions
		        if (!empty($advanced_search_query)) {

		            $index_search_string = 1; // index to keep a track of flags in the advanced search temp 
		            $search_params = array();

		            foreach ($advanced_search_query as &$advanced_search_query_string) {

		                //Cond for usermeta
		                if (!empty($advanced_search_query_string['cond_usermeta'])) {

		                    $cond_usermeta_array = explode(" AND  ",$advanced_search_query_string['cond_usermeta']);

		                    $cond_usermeta_col_name = (!empty($advanced_search_query_string['cond_usermeta_col_name'])) ? explode(" AND ",$advanced_search_query_string['cond_usermeta_col_name']) : '';
		                    $cond_usermeta_col_value = (!empty($advanced_search_query_string['cond_usermeta_col_value'])) ? explode(" AND ",$advanced_search_query_string['cond_usermeta_col_value']) : '';
		                    $cond_usermeta_operator = (!empty($advanced_search_query_string['cond_usermeta_operator'])) ? explode(" AND ",$advanced_search_query_string['cond_usermeta_operator']) : '';

		                    $index = 0;
		                    $cond_usermeta_post_ids = '';
		                    $result_usermeta_search = '';

		                    foreach ($cond_usermeta_array as $cond_usermeta) {

		                        $usermeta_search_result_flag = ( $index == (sizeof($cond_usermeta_array) - 1) ) ? ', '.$index_search_string : ', 0';

		                        $cond_usermeta_col_name1 = (!empty($cond_usermeta_col_name[$index])) ? trim($cond_usermeta_col_name[$index]) : '';
		                        $cond_usermeta_col_value1 = (!empty($cond_usermeta_col_value[$index])) ? trim($cond_usermeta_col_value[$index]) : '';
		                        $cond_usermeta_operator1 = (!empty($cond_usermeta_operator[$index])) ? trim($cond_usermeta_operator[$index]) : '';

		                        $search_params = array('cond_usermeta_col_name' => $cond_usermeta_col_name1,
		                    							'cond_usermeta_col_value' => $cond_usermeta_col_value1,
		                    							'cond_usermeta_operator' => $cond_usermeta_operator1,
		                    							'SM_IS_WOO30' => (!empty($params['SM_IS_WOO30'])) ? $params['SM_IS_WOO30'] : '');

		                        $cond_usermeta = apply_filters('sm_search_usermeta_condition_start', $cond_usermeta, $search_params);

		                        $search_params['cond_usermeta'] = $cond_usermeta;

		                        $usermeta_advanced_search_select = 'SELECT DISTINCT '.$wpdb->prefix.'usermeta.user_id '. $usermeta_search_result_flag .' ,0 ';
		                        $usermeta_advanced_search_from = 'FROM '.$wpdb->prefix.'usermeta ';
		                        $usermeta_advanced_search_where = 'WHERE '.$cond_usermeta;

		                        $usermeta_advanced_search_select = apply_filters('sm_search_query_usermeta_select', $usermeta_advanced_search_select, $search_params);
								$usermeta_advanced_search_from	= apply_filters('sm_search_query_usermeta_from', $usermeta_advanced_search_from, $search_params);
								$usermeta_advanced_search_where	= apply_filters('sm_search_query_usermeta_where', $usermeta_advanced_search_where, $search_params);

		                        //Query to find if there are any previous conditions
		                        $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
		                                                                    SET flag = 0
		                                                                    WHERE flag = ". $index_search_string);

		                        //Code to handle condition if the ids of previous cond are present in temp table
		                        if (($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_usermeta_search))) {
		                            $usermeta_advanced_search_from .= " JOIN ".$wpdb->base_prefix."sm_advanced_search_temp
		                                                                ON (".$wpdb->base_prefix."sm_advanced_search_temp.product_id = {$wpdb->prefix}usermeta.user_id)";

		                            $usermeta_advanced_search_where .= " AND ".$wpdb->base_prefix."sm_advanced_search_temp.flag = 0";
		                        }

		                        $result_usermeta_search = array();

		                        if (!empty($usermeta_advanced_search_select ) && !empty($usermeta_advanced_search_from ) && !empty($usermeta_advanced_search_where )) {
			                        $query_usermeta_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
			                                                        (". $usermeta_advanced_search_select ."
			                                                        ". $usermeta_advanced_search_from ."
			                                                        ".$usermeta_advanced_search_where.")";
			                        $result_usermeta_search = $wpdb->query ( $query_usermeta_search );
			                    }

			                    do_action('sm_search_usermeta_condition_complete',$result_usermeta_search,$search_params);

		                        $index++;
		                    }

		                    do_action('sm_search_usermeta_conditions_array_complete',$search_params);

		                    //Query to delete the unwanted post_ids
		                    $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");
		                }

		                //Cond for users
		                if (!empty($advanced_search_query_string['cond_users'])) {

		                    $cond_users_array = explode(" AND ",$advanced_search_query_string['cond_users']);

		                    $index = 0;
		                    $cond_users_post_ids = '';
		                    $result_users_search = '';

		                    foreach ( $cond_users_array as $cond_users ) {

		                        $users_search_result_flag = ( $index == (sizeof($cond_users_array) - 1) ) ? ', '.$index_search_string : ', 0';

		                        $cond_users = apply_filters('sm_search_users_condition_start', $cond_users);

		                        $search_params = array('cond_users' => $cond_users,
		                    							'SM_IS_WOO30' => (!empty($params['SM_IS_WOO30'])) ? $params['SM_IS_WOO30'] : '');

		                        $users_advanced_search_select = "SELECT DISTINCT ".$wpdb->prefix."users.id ". $users_search_result_flag ." ,0 ";
		                        $users_advanced_search_from = " FROM ".$wpdb->prefix."users ";
		                        $users_advanced_search_where = " WHERE ". $cond_users ." ";

		                        $users_advanced_search_select = apply_filters('sm_search_query_users_select', $users_advanced_search_select, $search_params);
								$users_advanced_search_from	= apply_filters('sm_search_query_users_from', $users_advanced_search_from, $search_params);
								$users_advanced_search_where	= apply_filters('sm_search_query_users_where', $users_advanced_search_where, $search_params);

		                        //Query to find if there are any previous conditions
		                        $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
		                                                                    SET flag = 0
		                                                                    WHERE flag = ". $index_search_string);


		                        //Code to handle condition if the ids of previous cond are present in temp table
		                        if ( ($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_users_search)) ) {
		                            $users_advanced_search_from .= " JOIN ".$wpdb->base_prefix."sm_advanced_search_temp
		                                                                ON (".$wpdb->base_prefix."sm_advanced_search_temp.product_id = {$wpdb->prefix}users.id) ";

		                            $users_advanced_search_where .= " AND ".$wpdb->base_prefix."sm_advanced_search_temp.flag = 0 ";
		                        }

		                        $result_users_search = array();

		                        if (!empty($users_advanced_search_select ) && !empty($users_advanced_search_from ) && !empty($users_advanced_search_where )) {
			                        $query_users_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
			                                                        ( ". $users_advanced_search_select ."
			                                                        ". $users_advanced_search_from ."
			                                                        ". $users_advanced_search_where .")";
			                        $result_users_search = $wpdb->query ( $query_users_search );
			                    }
		                        
			                    do_action('sm_search_users_condition_complete',$result_users_search,$search_params);

		                        $index++;
		                    }

		                    do_action('sm_search_users_conditions_array_complete',$search_params);

		                    //Query to delete the unwanted post_ids
		                    $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");

		                }
		                $index_search_string++;
		            }
		        }
		}



		//function to generate data model
		public function generate_data_model ($data_model, $data_cols, $data_cols_serialized) {
			global $wpdb, $current_user;

			$items = array();
			$index = 0;

			$join = $where = '';

			$start = (!empty($this->req_params['start'])) ? $this->req_params['start'] : 0;
			$limit = (!empty($this->req_params['limit'])) ? $this->req_params['limit'] : 50;
			$current_page = (!empty($this->req_params['page'])) ? $this->req_params['page'] : '1';
			$start_offset = ($current_page > 1) ? (($current_page - 1) * $limit) : $start;


			//Code to handle advanced search

			//Code to clear the advanced search temp table
	        if (empty($this->req_params['search_query']) || empty($this->req_params['search_query'][0]) || $this->req_params['search_query'][0] == '[]' || !empty($this->req_params['searchText']) ) {
	            $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp");
	            delete_option('sm_advanced_search_query');
	        }        

	        $sm_advanced_search_results_persistent = 0; //flag to handle persistent search results

	        //Code fo handling advanced search functionality
	        if ((!empty($this->req_params['search_query']) && !empty($this->req_params['search_query'][0]) && $this->req_params['search_query'][0] != '[]') || (!empty($this->req_params['searchText'])) ) {

	            if (empty($this->req_params['searchText'])) {
	                $search_query_diff = (get_option('sm_advanced_search_query') != '') ? array_diff($this->req_params['search_query'],get_option('sm_advanced_search_query')) : $this->req_params['search_query'];
	            } else {
	                $search_query_diff = '';
	            }

	            if (!empty($search_query_diff)) {

					$this->process_user_search_cond(array( 'search_query' => (!empty($this->req_params['search_query'])) ? $this->req_params['search_query'] : '',
	            										'SM_IS_WOO30' => (!empty($this->req_params['SM_IS_WOO30'])) ? $this->req_params['SM_IS_WOO30'] : '' ));

	            }

	            $join = " JOIN {$wpdb->base_prefix}sm_advanced_search_temp
                            	ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}users.id)";

                $where = " AND {$wpdb->base_prefix}sm_advanced_search_temp.flag > 0";

	        }

			//code to fetch data from users table
			$users_total_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(DISTINCT {$wpdb->prefix}users.id) 
																	FROM {$wpdb->prefix}users
																	". $join ."
																	WHERE 1=%d 
																	".$where, 1));

			$users_results = $wpdb->get_results( $wpdb->prepare("SELECT {$wpdb->prefix}users.* 
																FROM {$wpdb->prefix}users
																". $join ." 
																WHERE 1=%d 
																". $where ." 
																LIMIT %d,%d", 1, $start_offset, $limit), ARRAY_A );

			$total_pages = 1;

        	if ($users_total_count > $limit) {
        		$total_pages = ceil($users_total_count/$limit);
        	}

			if( !empty( $users_results ) ) {

				foreach( $users_results as $user ) {
					if( !empty($user['ID']) ) {
						$user_ids[] = $user['ID'];
					}
				}

				//code to get the usermeta data
				$um_results = $wpdb->get_results( $wpdb->prepare("SELECT user_id,
																		meta_key,
																		meta_value 
																		FROM {$wpdb->prefix}usermeta 
																		WHERE 1=%d 
																			AND user_id IN (". implode(",", $user_ids) .") 
																			AND meta_key NOT IN ('". implode("','",$this->usermeta_ignored_cols) ."')
																		GROUP BY user_id, meta_key", 1), ARRAY_A );

				if( count($um_results) > 0 ) {

					$records_meta = array();

					foreach ($um_results as $meta_data) {
	                    $key = preg_replace('/[^A-Za-z0-9\-_]/', '', $meta_data['meta_key']); //for formatting meta keys of custom keys
	                    $records_meta[$meta_data['user_id']][$key] = $meta_data['meta_value'];
	                }
				}


				$customer_ids = array();

				foreach( $users_results as $user ) {


					$user_id = (!empty( $user['ID'] )) ? $user['ID'] : 0;

					foreach( $user as $key => $value ) {
	    				if (array_search($key, $data_cols) === false) continue; //cond for checking col in col model

	    				$key_mod = 'users_'.strtolower(str_replace(' ', '_', $key));
	    				$items [$index][$key_mod] = ( $key != 'user_pass' ) ? $value : '';
	    			}


	    			if( !empty( $records_meta[$user_id] ) ) {

	    				foreach( $records_meta[$user_id] as $key => $value ) {

	    					if (array_search($key, $data_cols) === false) continue; //cond for checking col in col model

	    					//Code for handling serialized data
        					if (array_search($key, $data_cols_serialized) !== false) {
								$value = maybe_unserialize($value);
								if ( !empty( $value ) ) {
									$value = json_encode($value);
								}
								
	        				}

	        				$key_mod = 'usermeta_meta_key_'.$key.'_meta_value_'.$key;
	        				$items [$index][$key_mod] = (!empty($value)) ? $value : '';
	    				}

	    				
	    				if( defined( 'SMBETAPRO' ) && SMBETAPRO === true ) {
	    					$items [$index]['custom_last_order_date'] = '-';
		    				$items [$index]['custom_last_order_total'] = '-';
		    				$items [$index]['custom_orders_count'] = '-';
		    				$items [$index]['custom_orders_total'] = '-';	
	    				} else {
	    					$items [$index]['custom_last_order_date'] = '<a href="https://www.storeapps.org/product/smart-manager" target = \'_blank\' style=\'color:#0073aa !important;\'> Pro only </a>';
		    				$items [$index]['custom_last_order_total'] = '<a href="https://www.storeapps.org/product/smart-manager" target = \'_blank\' style=\'color:#0073aa !important;\'> Pro only </a>';
		    				$items [$index]['custom_orders_count'] =  '<a href="https://www.storeapps.org/product/smart-manager" target = \'_blank\' style=\'color:#0073aa !important;\'> Pro only </a>';
		    				$items [$index]['custom_orders_total'] =  '<a href="https://www.storeapps.org/product/smart-manager" target = \'_blank\' style=\'color:#0073aa !important;\'> Pro only </a>';
	    				}
	    				

	    				if( !empty($records_meta[$user_id]['wp_capabilities']) ) {

			    			$caps = maybe_unserialize($records_meta[$user_id]['wp_capabilities']);
			    			$role = array_keys($caps);
			    			$items [$index]['usermeta_role'] = ( !empty($role[0]) ) ? $role[0] : '';

			    			if( !empty( $items [$index]['usermeta_role'] ) && $items [$index]['usermeta_role'] == 'customer' ) {
			    				$customer_ids[$user_id] = $index;
			    			}
			    		}

	    			}

	    			$index++;
	    		}

	    		if( !empty( $customer_ids ) && defined( 'SMBETAPRO' ) && SMBETAPRO === true ) {

	    			$cust_ids = array_keys( $customer_ids );

		    		$customer_totals = $wpdb->get_results( $wpdb->prepare( "SELECT postmeta1.meta_value as cust_id,
		    																		GROUP_CONCAT(distinct postmeta1.post_ID 
												                                    ORDER BY posts.post_date DESC SEPARATOR ',' ) AS all_id,
												                           GROUP_CONCAT(postmeta2.meta_value 
												                                         ORDER BY posts.post_date DESC SEPARATOR ',' ) AS order_total,     
												                                    date_format(max(posts.post_date), '%%Y-%%m-%%d, %%r') AS date,
												                           count(postmeta1.post_id) as count,
												                           sum(postmeta2.meta_value) as total
												                           
												                           FROM {$wpdb->prefix}postmeta AS postmeta1
												                                    JOIN {$wpdb->prefix}posts AS posts 
												                                    	ON (posts.ID = postmeta1.post_id
												                                    		AND posts.post_type = 'shop_order'
												                                    		AND posts.post_status IN ('wc-completed','wc-processing'))
												                               INNER JOIN {$wpdb->prefix}postmeta AS postmeta2
												                                   ON (postmeta2.post_ID = postmeta1.post_ID AND postmeta2.meta_key = %s)
												                                                        
												                           WHERE postmeta1.meta_key = %s
												                                     AND postmeta1.meta_value IN (" . implode(",",$cust_ids) . ")                           
												                           GROUP BY postmeta1.meta_value
												                           ORDER BY date", '_order_total', '_customer_user' ), 'ARRAY_A' );

		    		if( !empty( $customer_totals ) ) {
		    			foreach( $customer_totals as $cust ) {

			    				$index = $customer_ids[$cust['cust_id']];
			    				$last_order_total = ( !empty( $cust['order_total'] ) ) ? explode(",", $cust['order_total']) : array();

			    				$items [$index]['custom_last_order_date'] = ( !empty( $cust['date'] ) ) ? $cust['date'] : '-';
			    				$items [$index]['custom_last_order_total'] = $last_order_total[0];
			    				$items [$index]['custom_orders_count'] = ( !empty( $cust['count'] ) ) ? $cust['count'] : 0;
			    				$items [$index]['custom_orders_total'] = ( !empty( $cust['total'] ) ) ? $cust['total'] : 0;
			    		}
					}
		    		
	    		}

	            

	    		
			}

			$data_model ['items'] = (!empty($items)) ? $items : '';
        	$data_model ['start'] = $start+$limit;
        	$data_model ['page'] = $current_page;
        	$data_model ['total_pages'] = $total_pages;
        	$data_model ['users_total_count'] = $users_total_count;

			return $data_model;

		}

		//function to avoid default inline update
		public function default_inline_update ($flag) {
			return false;
		}

		//function for modifying edited data before updating
		public function user_inline_update($edited_data, $params) {
			if (empty($edited_data)) return $edited_data;

			global $wpdb;


			$default_user_keys = array( 'ID', 'user_pass', 'user_login', 'user_nicename', 'user_url', 'user_email', 'display_name', 'nickname', 'first_name', 
										'last_name', 'description', 'rich_editing', 'syntax_highlighting', 'comment_shortcuts', 'admin_color', 'use_ssl',
										'user_registered', 'show_admin_bar_front', 'role', 'locale' );

			foreach ($edited_data as $id => $edited_row) {

				if( empty( $id ) ) {
					continue;
				}

				$default_insert_users = array();
				$insert_usermeta = array();

				foreach( $edited_row as $key => $value ) {
					$edited_value_exploded = explode("/", $key);
					
					if( empty( $edited_value_exploded ) ) continue;

					$update_table = $edited_value_exploded[0];
					$update_column = $edited_value_exploded[1];

					if ( sizeof( $edited_value_exploded ) <= 2) {
						if( ( ($update_table == 'users') || ($update_table == 'usermeta' && $update_column == 'role') ) ) {

							if( $update_table == 'usermeta' && $update_column == 'role' && (!empty( $params['data_cols_list_val'][$update_column] )) ) {
								$value = array_search($value, $params['data_cols_list_val'][$update_column]);
							}

							$default_insert_users [$update_column] = $value;
						}
					} else if ( sizeof( $edited_value_exploded ) > 2) {
						$cond = explode("=",$edited_value_exploded[1]);

						$update_column_exploded = explode("=",$edited_value_exploded[2]);
						$update_column = $update_column_exploded[1];

						if( $update_table == 'usermeta' && in_array( $update_column, $default_user_keys ) ) {

							if( $update_column == 'use_ssl' ) {
								$value = ( $value == 'yes' ) ? 0 : 1;
							}

							$default_insert_users [$update_column] = $value;
						} else if( $update_table == 'usermeta' && in_array( $update_column, $default_user_keys ) === false ) {
							$insert_usermeta [$update_column] = $value;
						}
					}
				}

				if( !empty( $default_insert_users ) ) {
					$default_insert_users['ID'] = (int) $id;
					$id = wp_update_user( $default_insert_users );
				}

				if( !is_wp_error( $id ) ) {
					
					if( !empty( $insert_usermeta ) ) {

						foreach( $insert_usermeta as $key => $value ) {
							update_user_meta( $id, $key, $value );
						}
					}
				}

			}

		}

	}
}
