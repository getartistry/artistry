<?php

if ( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Smart_Manager_Controller' ) ) {
	class Smart_Manager_Controller {
		public $dashboard_key = '',
				$plugin_path = '';

		function __construct() {
			if (is_admin() ) {
				add_action ( 'wp_ajax_sm_beta_include_file', array(&$this,'request_handler') );
			}
			$this->plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) );

			add_action('admin_init',array(&$this,'call_custom_actions'),11);
			add_action('admin_footer',array(&$this,'sm_footer'));
			//Filter for setting the wp_editor default tab
			add_filter( 'wp_default_editor', array(&$this,'sm_wp_default_editor'),10, 1 );
		}

		public function sm_wp_default_editor( $tab ) { //TODO: change the name of the page befre release
			if ( !empty($_GET['page']) && $_GET['page'] == 'smart-manager' ) {
				$tab = "html";
			}
			return $tab;
		}

		public function sm_footer() {
			if(isset($_GET['sm_beta']) && $_GET['sm_beta'] == '1'){
				echo '<div id="sm_wp_editor" style="display:none;">';
				wp_editor( '', 'sm_inline_wp_editor', array('default_editor' => 'html') );
				echo '</div>';
			}
		}

		//Function to call custom actions on admin_init		
		public function call_custom_actions() {
			do_action('sm_admin_init');

			add_action('woocommerce_delete_product_transients',array(&$this,'delete_transients'));
			add_action('woocommerce_attribute_added',array(&$this,'delete_transients'));
			add_action('woocommerce_attribute_updated',array(&$this,'delete_transients'));
			add_action('woocommerce_attribute_deleted',array(&$this,'delete_transients'));
			add_action('added_woocommerce_term_meta',array(&$this,'delete_transients'));
			add_action('updated_woocommerce_term_meta',array(&$this,'delete_transients'));
			add_action('delete_woocommerce_term_meta',array(&$this,'delete_transients'));

			//for background updater
			if( defined('SMBETAPRO') && SMBETAPRO === true && file_exists(SM_BETA_PRO_URL . 'classes/class-smart-manager-pro-background-updater.php') ) {
				include_once SM_BETA_PRO_URL . 'classes/class-smart-manager-pro-background-updater.php';
				$sm_beta_pro_background_updater = Smart_Manager_Pro_Background_Updater::instance();
			}

		}

		public function delete_transients() {
			global $wpdb;

			$query_delete_transients = "DELETE 
							            FROM {$wpdb->options}
							            WHERE option_name LIKE '\_transient\_sm\_%'
							            OR option_name LIKE '\_transient\_timeout\_sm\_%'";
			$wpdb->query($query_delete_transients);
		}

		//Function to handle the wp-admin ajax request
		public function request_handler() {

			if (empty($_REQUEST) || empty($_REQUEST['active_module']) || empty($_REQUEST['cmd'])) return;

			check_ajax_referer('smart-manager-security','security');

			if ( !is_user_logged_in() || !is_admin() ) {
				return;
			}

			$pro_flag_class_path = $pro_flag_class_nm = $sm_pro_class_nm = '';

			if( defined('SMBETAPRO') && SMBETAPRO === true && ( !empty($_REQUEST['pro']) ) ) {
				$plugin_path = SM_BETA_PRO_URL .'classes';
				$pro_flag_class_path = 'pro-';
				$pro_flag_class_nm = 'Pro_';
			} else {
				$plugin_path = $this->plugin_path;
			}

			//Including the common utility functions class
			include_once $plugin_path . '/class-smart-manager-'.$pro_flag_class_path.'utils.php';
			include_once $this->plugin_path . '/class-smart-manager-base.php';
			
			if( defined('SMBETAPRO') && SMBETAPRO === true && ( !empty($_REQUEST['pro']) ) ) {
				$sm_pro_class_nm = 'class-smart-manager-'.$pro_flag_class_path.'base.php';
				include_once $plugin_path . '/'. $sm_pro_class_nm;
			}

			$func_nm = $_REQUEST['cmd'];
			$this->dashboard_key = $_REQUEST['active_module'];
			
			//Code for initializing the specific dashboard class

			$file_nm = str_replace('_', '-', $this->dashboard_key);

			$class_name = '';

			if (file_exists($plugin_path . '/class-smart-manager-'.$pro_flag_class_path.''.$file_nm.'.php')) {
				$class_name = 'Smart_Manager_'.$pro_flag_class_nm.''.ucfirst($this->dashboard_key);

				include_once $this->plugin_path . '/class-smart-manager-'.$file_nm.'.php';

				if( defined('SMBETAPRO') && SMBETAPRO === true && ( !empty($_REQUEST['pro']) ) ) {
					$sm_pro_class_nm = 'class-smart-manager-'.$pro_flag_class_path.''.$file_nm.'.php';
					include_once $plugin_path .'/'. $sm_pro_class_nm;
				}
			} else {
				$class_name = (!empty($pro_flag_class_nm)) ? 'Smart_Manager_'.$pro_flag_class_nm.'Base' : 'Smart_Manager_Base';
			}

			if( !empty($_REQUEST['pro']) ) { //additional params to handle sm beta pro requests
				$_REQUEST['class_nm'] = $class_name;
				$_REQUEST['class_path'] = $sm_pro_class_nm;
			}
			$handler_obj = new $class_name($this->dashboard_key);
			$handler_obj->$func_nm();
		}		

	}
}

?>