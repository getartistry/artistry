<?php

if(!defined( 'ABSPATH' )){
	exit;
}

/**
 * Shows general system info to help troubelshooting problems.
 * Can be accessed at http://site.com/wp-admin/admin.php?page=usin_debug
 */
class USIN_Debug{
	
	protected $info = array();
	protected $page_slug = 'usin_debug';
	
	public function __construct(){
		add_action('admin_menu', array($this, 'add_page'), 93);
		add_action( 'admin_head', array($this, 'add_styles') );
	}
	
	public function add_page(){
		add_submenu_page ( 
			null, // we don't need this page to be added to the menu, so we set the parent to null
			__('Users Insights Troubleshoot', 'usin'),
			__('Users Insights Troubleshoot', 'usin'),
			'administrator',
			$this->page_slug,
			array($this, 'print_page_markup') );
	}
	
	public function print_page_markup(){
		$this->load_info();
		echo '<h2>Users Insights Debug Info</h2>';
		echo '<textarea class="usin-troubleshoot">';
		$this->print_info();
		echo '</textarea>';
	}
	
	protected function load_info(){
		$info = array();
		$info['WordPress Environment'] = $this->get_wp_info();
		$info['Server Environment'] = $this->get_server_info();
		$info['Active Plugins'] = $this->get_plugin_info();
		$info['Users Insights'] = $this->get_usin_info();
		
		$this->info = $info;
	}
	
	protected function get_wp_info(){
		$info = array();
		$info['WP Version'] = get_bloginfo('version');
		$info['WP URL'] = get_bloginfo('url');
		$info['Multisite'] = is_multisite() ? 'yes' : 'no';
		$info['Debug Mode'] =  defined( 'WP_DEBUG' ) && WP_DEBUG ? 'yes' : 'no';
		$all_users = count_users();
		$info['Number of users'] = $all_users['total_users'];
		
		return $info;
	}
	
	protected function get_server_info(){
		$info = array();
		$info['Server Info'] = $_SERVER['SERVER_SOFTWARE'];
		$info['PHP Version'] = phpversion();
		$info['PHP Max Post Size'] = ini_get('post_max_size');
		$info['PHP Time Limit'] = ini_get( 'max_execution_time' );
		$info['PHP Max Input Vars'] = ini_get( 'max_input_vars' );
		
		if ( function_exists( 'curl_version' ) ) {
			$curl_version = curl_version();
			$info['cURL version'] = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
		} else {
			$info['cURL version'] = 'N/A';
		}
		
		$info['SUHOSIN Installed'] = extension_loaded( 'suhosin' ) ? 'yes' : 'no';
		
		global $wpdb;
		if($wpdb->is_mysql){
			$info['MySQL version'] = $wpdb->db_version();
		}else{
			$info['MySQL version'] = 'N/A'; 
		}
		
		
		return $info;
	}
	
	
	protected function get_usin_info(){
		$info = array();
		$info['Version'] = USIN_VERSION;
		$info['Active Modules'] = $this->get_active_modules();

		$license = USIN_Module::get('globallicense')->license;
		$info['License status'] = sprintf('%s, expires: %s', $license->status, $license->expires);

		if(usin_module_options()->is_module_active('geolocation')){
			$info['Geolocation Status'] = USIN_Geolocation_Status::is_paused() ? 'paused' : 'active';
		}
		
		$fields = $this->get_fields();
		$info['Visible Fields'] = $fields['visible'];
		$info['Hidden Fields'] = $fields['hidden'];
		
		$query = $this->get_query();
		$info['DB Query'] = $query['query'];
		$info['DB Query Time'] = $query['time'].'s';
		$info['DB Query Result'] = $query['res'];
		$info['DB Query Errors'] = empty($query['error']) ? 'none' : $query['error'];
		
		return $info;
	}
	
	protected function get_plugin_info(){
		$info = array();
		
		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
			$active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
		}
		
		foreach ( $active_plugins as $plugin ) {

			$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			if ( ! empty( $plugin_data['Name'] ) ) {

				$plugin_name = esc_html( $plugin_data['Name'] );
				$info[$plugin_name.' '.$plugin_data['Version']] = $plugin_data['PluginURI'];
				
			}
		}
		
		return $info;
	}
	
	
	protected function print_info(){
		foreach ($this->info as $info_title => $info) {
			echo "\n----------------------------------------- \n";
			echo $info_title."\n";
			echo "----------------------------------------- \n";
			foreach ($info as $key => $value) {
				echo "$key: $value \n";
			}
			
		}
	}
	
	
	protected function get_active_modules(){
		$module_options = USIN_Module_Options::get_instance();
		$modules = $module_options->get_module_options();
		$active_modules = array();
		foreach ($modules as $module ) {
			if($module['active']){
				$active_modules[]= $module['name'];
			}
		}
		
		return implode(', ', $active_modules);
	}
	
	protected function get_fields(){
		$options = usin_options();
		$fields = $options->get_fields();
		$visible_fields = array();
		$hidden_fields = array();
		foreach ($fields as $field ) {
			if($options->is_field_visible($field['id'])){
				$visible_fields[]=$field;
			}else{
				$hidden_fields[]=$field;
			}
		}
		
		$output = array();
		$output['visible'] = json_encode($visible_fields);
		$output['hidden'] = json_encode($hidden_fields);
		return $output;
	}
	
	
	protected function get_query(){
		$options = usin_options();
		
		$args = array(
			'orderBy' => $options->get('orderby', 'registered'),
			'order' => $options->get('order', 'DESC'),
			'number' => intval($options->get('users_per_page', 50))
		);

		global $wpdb;
		ob_start();
		$start_time = time();
		
		$wpdb->show_errors();
		$user_query = new USIN_User_Query($args);
		$users = $user_query->get_users();
		$wpdb->hide_errors();
		$error = ob_get_clean();
		
		$user_len = !empty($users) && !empty($users['users']) ? sizeof($users['users']) : 0;
		
		$end_time = time();
		$res = array(
			'query' => $user_query->query,
			'res' => $user_len.' users',
			'error' => strip_tags($error),
			'time' => $end_time - $start_time
		);
		
		return $res;
	}
	
	public function add_styles(){
		global $current_screen;

		if(strpos( $current_screen->base, $this->page_slug ) !== false){
			?>
			<style>
				.usin-troubleshoot{
					width: 90%;
					max-width: 90%;
					height:500px;
				}
			</style>
			<?php
		}
	}
	
}

new USIN_Debug();