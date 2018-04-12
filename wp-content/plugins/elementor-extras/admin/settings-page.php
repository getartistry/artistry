<?php
namespace ElementorExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Settings_Page
 *
 * @since 1.8.0
 */
abstract class Settings_Page {

	// Vars
	var $notices = array();

	private $page_tabs;
	protected $settings_prefix;
	protected $settings_api;

	const PAGE_ID = '';

	/**
	 * __construct
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */

	function __construct() {

		$this->settings_api 	= new Settings_API;
		$this->settings_prefix 	= 'elementor_extras_';
	
		// actions
		add_action( 'admin_menu', 				[ $this, 'menu' ], 200 );
		add_action( 'admin_init', 				[ $this, 'init' ] );
		add_action( 'admin_enqueue_scripts',	[ $this, 'enqueue_scripts' ], 0 );
		add_action( 'admin_notices', 			[ $this, 'render_notices' ] );
		
	}

	/**
	 * Adds the item to the menu
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/

	public function menu() {}

	/**
	* Enqueue styles and scripts
	*
	* @since 1.8.0
	*
	* @access public
	*/
	
	public function enqueue_scripts() {

	}

	/**
	* Hooked into admin_init action
	*
	* @since 1.8.0
	*
	* @access public
	*/

	public function init() {

		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );

		$this->settings_api->admin_init();

	}

	/**
	 * Gets the settings sections
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/

	public function get_settings_sections() {
		return [];
	}

	/**
	 * Gets the settings fields
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/

	public function get_settings_fields() {
		return [];
	}

	/**
	* Get tabs to be displayed on page
	*
	* @since 1.8.0
	*
	* @access public
	*/

	public final function get_page_tabs() {
		$this->set_page_tabs();

		return $this->page_tabs;
	}

	/**
	* Sets the tabs variable
	*
	* @since 1.8.0
	*
	* @access protected
	*/
	private function set_page_tabs() {
		if ( null === $this->page_tabs ) {
			$this->page_tabs = $this->create_page_tabs();
		}
	}

	/**
	* Creates the tabs object
	*
	* @since 1.8.0
	*
	* @access protected
	*/
	abstract protected function create_page_tabs();

	/**
	* Returns current page title
	*
	* @since 1.8.0
	*
	* @access protected
	*/
	abstract protected function get_page_title();


	/**
	 * Renders the page
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/
	public function render_page() {

		$tabs = $this->get_page_tabs();

		?><div class="wrap ee-wrap"><?php
			$this->render_page_title();
			$this->render_page_tabs();
			$this->render_forms();
		?></div><?php

	}

	/**
	 * Renders the form with settings
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/
	public function render_forms() {
		$this->settings_api->render_navigation();
		$this->settings_api->render_forms();
	}

	/**
	 * Renders the page title
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/
	public function render_page_title() {
		?><h1><?php echo $this->get_page_title(); ?></h1><?php
	}

	/**
	 * Renders the page tabs
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/
	public function render_page_tabs() {

		if ( empty( $this->page_tabs ) )
			return;

		?><div id="ee-admin-tabs" class="ee-admin__tabs nav-tab-wrapper"><?php

			$active = true;

			foreach ( $this->page_tabs as $tab_id => $tab ) {
				
				if ( empty( $tab['sections'] ) )
					continue;

				$this->render_page_tab( $tab_id, $tab, $active );

				$active = false;

			}

		?></div><?php
	}

	/**
	 * Renders a tab by id
	 *
	 * @since 1.8.0
	 *
	 * @param $tab array
	 * @param $tab_id string
	 * @param $active bool
	 *
	 * @access public
	*/
	public function render_page_tab( $tab_id, $tab, $active = false ) {

		$_class_active = $active ? ' nav-tab-active' : '';

		?><a id='ee-admin-tab-<?php echo $tab_id; ?>' class='ee-admin__tabs__tab nav-tab <?php echo $_class_active; ?>' href='#tab-<?php echo $tab_id; ?>'>
			<?php echo $tab['label']; ?>
		</a><?php
	}

	/**
	 * Adds a notice to the current page
	 *
	 * @since 1.8.0
	 *
	 * @param $content string The text content of the notice
	 * @param $class string The css class for the notice element
	 * @param $wrap string The wrapper html tag
	 *
	 * @access public
	*/
	function add_notice( $content = '', $class = '', $wrap = 'p' ) {
		
		// append
		$this->notices[] = array(
			'content'	=> $text,
			'class'		=> $class,
			'wrap'		=> $wrap
		);
		
	}

	/**
	 * Returns all notices for current page
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/
	function get_notices() {
		
		if( empty( $this->notices ) )
			return false;
		
		return $this->notices;
		
	}

	/**
	 * Renders all notices for current page
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/
	function render_notices() {
		
		$notices = $this->get_notices();
		
		if( ! $notices ) return;
		
		foreach( $notices as $notice ) {
			
			$open = '';
			$close = '';
				
			if( $notice['wrap'] ) {
				
				$open = "<{$notice['wrap']}>";
				$close = "</{$notice['wrap']}>";
				
			}
				
			?>
			<div class="notice is-dismissible <?php echo esc_attr( $notice['class'] ); ?> ee-notice"><?php echo $open . $notice['text'] . $close; ?></div>
			<?php
				
		}

	}

}

?>