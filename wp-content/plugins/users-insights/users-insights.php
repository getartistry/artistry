<?php
/**
 * Plugin Name: Users Insights
 * Plugin URI: https://usersinsights.com/
 * Description: Everything about your WordPress users in one place
 * Version: 3.3.1
 * Author: Pexeto
 * Text Domain: usin
 * Domain Path: /lang
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright: Pexeto 2015
 *
 * Users Insights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 */

if(!defined( 'ABSPATH' )){
	exit;
}

global $usin;
$usin = new StdClass();

if(! class_exists('USIN_Manager')){

	/**
	 * Includes all of the initalization functionality of the Users Insights plugin.
	 */
	class USIN_Manager{

		public $title;
		public $slug = 'users_insights';
		public $user_data_db_table = 'usin_user_data';

		protected static $instance;

		protected function __construct(){}

		/**
		 * Returns the instance of the class, it is a singleton class.
		 */
		public static function get_instance(){
			if(! self::$instance ){
				self::$instance = new USIN_Manager();
				self::$instance->init();
			}
			return self::$instance;
		}

		/**
		 * Initializes the main plugin functionality.
		 */
		public function init(){
			$this->config();
			$this->include_files();

			$this->module_options = USIN_Module_Options::get_instance();

			if(is_admin()){
				$this->options = new USIN_Options();

				$this->list_page = new USIN_List_Page($this->title, $this->slug, $this->options);
				$this->list_page->init();


				$this->module_page = new USIN_Module_Page($this->slug, $this->module_options);
				$this->module_page->init();
				
				$this->cf_page = new USIN_Custom_Fields_Page($this->slug);
				$this->cf_page->init();

				$filters = new USIN_Filters();
				$filters->init();
				$actions = new USIN_Actions();
				$actions->init();
				
				$groups = new USIN_Groups($this->slug);
				$groups->init();
				
				$notes = new USIN_Notes();
				$notes->init();
				
				//updater
				$updates_license = $this->module_options->get_license('globallicense');
				$updater = new USIN_Plugin_Updater('https://usersinsights.com', __FILE__, array(
					'version' => USIN_VERSION,
					'license' => $updates_license,
				));

			}

			$user_detect = new USIN_User_Detect();
			$user_detect->init();

			$schema = new USIN_Schema($this->user_data_db_table, USIN_PLUGIN_FILE, USIN_VERSION);
			$schema->init();
			
			do_action('usin_loaded');

			//load the text domain
			add_action( 'plugins_loaded', array($this, 'load_textdomain') );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_page_link') );
		}

		/**
		 * Sets the main configuration options.
		 */
		public function config(){

			$this->title = __('Users Insights', 'usin');

			//set constants
			if ( ! defined( 'USIN_VERSION' ) ) {
				define( 'USIN_VERSION', '3.3.1' );
			}

			if ( ! defined( 'USIN_PLUGIN_FILE' ) ) {
				define( 'USIN_PLUGIN_FILE', __FILE__);
			}

		}

		/**
		 * Load the text domain for translations.
		 */
		public function load_textdomain(){
			load_plugin_textdomain( 'usin', false, basename( dirname( __FILE__ ) ) . '/lang/' );
		}
		
		/**
		 * Adds a "Settings" link to the Module Options page in the plugin listing
		 */
		public function add_settings_page_link($links){
			$links[]= sprintf('<a href="%s">%s</a>',
				admin_url( 'admin.php?page='.$this->module_page->slug ), __('Settings', 'usin'));
			return $links;
		}

		/**
		 * Include the required core files.
		 */
		public function include_files(){

			include_once('includes.php');
			USIN_Includes::call();

		}
	}

}


$usin->manager = USIN_Manager::get_instance();

