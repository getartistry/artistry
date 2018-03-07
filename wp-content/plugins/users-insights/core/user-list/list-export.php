<?php

class USIN_List_Export{

	protected $user_capability;
	protected $nonce_key;
	protected $options;

	public function __construct($options, $nonce_key){
		$this->options = $options;
		$this->user_capability = USIN_Capabilities::EXPORT_USERS;
		$this->nonce_key = $nonce_key;

		add_action('admin_init', array($this, 'init'));
	}


	public function init(){

		if(isset($_GET['usin_export'])){

			$this->validate_request();

			$options = $this->get_export_options();
			$filters = isset($options['filters']) ? $options['filters'] : null;
			$field_options = isset($options['fields']) ? $options['fields'] : null;
			$fields = $this->get_required_fields($field_options);
			$ordered_fields = $this->get_required_fields($field_options, true);

			if(isset($options['orderby']) && isset($options['order'])){
				$args['orderby'] = $options['orderby'];
				$args['order'] = $options['order'];
			}

			$users_data = $this->get_users_data($args, $filters, $fields);
			$users = $users_data['users'];

			$this->print_csv_data($ordered_fields, $users);

		}
	}

	protected function print_csv_data($fields, $users){
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=users.csv');

		$output = fopen('php://output', 'w');

		fputcsv($output, $fields);

		foreach ($users as $user) {
			$user_arr = array();
			foreach ($fields as $key => $value) {
				$user_val = "";
				
				if(isset($user->$key) && $user->$key !== null){
					if(is_array($user->$key)){
						$user_val = implode(', ', $user->$key);
					}else{
						$user_val = $user->$key;
					}
				}
					
				$user_arr[]=$user_val;
			}

			fputcsv($output, $user_arr);
		}


		fclose($output);

		exit;
	}

	protected function validate_request(){
		if(!current_user_can($this->user_capability)){
			echo __('You don\'t have permission to access this page', 'usin');
			exit;
		}
		if(!wp_verify_nonce( $_GET['nonce'], $this->nonce_key )){
			echo __('Nonce did not verify', 'usin');
			exit;
		}
		return true;
	}

	protected function get_export_options(){
		$transient_key = $_GET['usin_export'];
		$options = get_transient($transient_key);

		if(empty($options)){
			$options = array();
		}
		return $options;
	}

	protected function get_required_fields($exp_fields = null, $preserve_order = false){
		$all_fields = $preserve_order ? $this->options->get_ordered_fields($exp_fields) :
			$this->options->get_fields();
		
		$fields = array();
		
		foreach ($all_fields as $field) {
			if(in_array($field['id'], $exp_fields)){
				$fields[$field['id']] = $field['name'];
			}
		}

		return $fields;
	}

	protected function get_users_data($args = array(), $filters = null, $fields){

		$args['export'] = array('export_fields'=>array_keys($fields));

		$user_query = new USIN_User_Query($args, $filters);
		
		$field_ids = array_keys($fields);

		return $user_query->get_users($field_ids);
	}
	
}