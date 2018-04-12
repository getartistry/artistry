<?php
namespace ElementorExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Settings
 *
 * @since 1.8.0
 */
class Settings extends Settings_Page {

	const PAGE_ID = 'elementor-extras';

	// Tabs
	const TAB_GENERAL 		= 'general';
	const TAB_WIDGETS 		= 'widgets';
	const TAB_EXTENSIONS 	= 'extensions';
	const TAB_LICENSE 		= 'license';

	private $_tabs;

	/**
	 * menu
	 *
	 * Adds the item to the menu
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/

	public function menu() {
		$slug = 'elementor-extras';
		$capability = 'manage_options';

		add_submenu_page(
			\Elementor\Settings::PAGE_ID,
			$this->get_page_title(),
			__( 'Extras', 'elementor-extras' ),
			$capability,
			$slug,
			[ $this, 'render_page' ]
		);
	}

	/**
	* enqueue_scripts
	*
	* Enqueue styles and scripts
	*
	* @since 1.8.0
	*
	* @access public
	*/
	
	public function enqueue_scripts() {}

	/**
	* Hooked into admin_init action
	*
	* @since 1.8.0
	*
	* @access public
	*/

	public function init() {

		parent::init();

	}

	/**
	* Creates the tabs object
	*
	* @since 1.8.0
	*
	* @access protected
	*/

	protected function create_page_tabs() {
		return $this->_tabs;
	}

	/**
	 * Gets the settings sections
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/

	public function get_settings_sections() {

		$sections = array(
			array(
				'id'    => $this->settings_prefix . 'widgets',
				'title' => __( 'Widgets', 'elementor-extras' ),
				'desc'	=> __( 'Disable widgets from Elementor Extras. If disabled, a widget will no longer be available in the Elementor editor panel.' ),
			),
			array(
				'id'    => $this->settings_prefix . 'extensions',
				'title' => __( 'Extensions', 'elementor-extras' ),
				'desc'	=> __( 'Elementor Extras extensions are features added to the default Elementor elements. They display additional controls that can be found usually under the Advanced tab of each element. Below you can disable any or all these extensions. If disabled, these additional controls will no longer be available in the Elementor editor panel.' ),
			),
			array(
				'id'    => $this->settings_prefix . 'advanced',
				'title' => __( 'Advanced', 'elementor-extras' ),
			),
			array(
				'id'    => $this->settings_prefix . 'license',
				'title' => __( 'License', 'elementor-extras' ),
				'link'	=> admin_url( 'admin.php?page=elementor_extras_license' ),
			),
		);

		return $sections;
	}

	/**
	 * Gets the settings fields
	 *
	 * @since 1.8.0
	 *
	 * @access public
	*/

	public function get_settings_fields() {
		$fields = [];

		$sections = $this->get_settings_sections();

		foreach( $sections as $section ) {
			if ( $this->settings_api->is_tab_linked( $section ) )
				continue;

			$fields[ $section['id'] ] = call_user_func( array( $this, 'get_' . str_replace( $this->settings_prefix, '', $section['id'] ) . '_fields' ) );
		}

		return $fields;
	}

	/**
	* Returns current page title
	*
	* @since 1.8.0
	*
	* @access protected
	*/
	protected function get_widgets_fields() {

		$fields = [];

		$modules = ElementorExtrasPlugin::$instance->modules_manager->get_modules();

		foreach( $modules as $module ) {

			$module_name = $module->get_name();

			$module_class_name = str_replace( '-', ' ', $module_name );
			$module_class_name = str_replace( ' ', '', ucwords( $module_class_name ) );

			$widgets = $module->get_widgets();

			foreach( $widgets as $_widget ) {

				$class_name = 'ElementorExtras\Modules\\' . $module_class_name . '\Widgets\\' . $_widget;

				$widget_title 	= str_replace( '_', ' ', ucwords( $_widget ) );
				$widget_slug 	= strtolower( $_widget );

				$field = [
					'name'		=> 'enable_' . $widget_slug,
					'label' 	=> $widget_title,
					'desc' 		=> __( 'Enable', 'elementor-extras' ),
					'type' 		=> 'checkbox',
					'default' 	=> 'on',
				];

				if ( $class_name::requires_elementor_pro() && ! is_elementor_pro_active() ) {
					$field['type'] = 'html';
					$field['note'] = __( 'You need Elementor Pro installed and activated for this widget to be available.', 'elementor-extras' );

					unset( $field['desc'] );
				}

				$fields[] = $field;

			}
		}

		return $fields;
	}

	/**
	* Returns current page title
	*
	* @since 1.8.0
	*
	* @access protected
	*/
	protected function get_extensions_fields() {

		$fields = [];

		$extensions = ElementorExtrasPlugin::$instance->extensions_manager->available_extensions;

		foreach( $extensions as $extension_id ) {

			$extension_name = str_replace( '-', '_', $extension_id );
			$class_name = 'ElementorExtras\Extensions\Extension_' . ucwords( $extension_name );

			$extension_title = str_replace( '-', ' ', $extension_id );
			$extension_title = ucwords( $extension_title );

			$description = $class_name::get_description();

			$fields[] = [
				'name'		=> 'enable_' . $extension_name,
				'label' 	=> $extension_title,
				'desc' 		=> __( 'Enable', 'elementor-extras' ),
				'type' 		=> 'checkbox',
				'default' 	=> 'on',
				'note'		=> $description,
			];
		}

		return $fields;
	}

	protected function get_advanced_fields() {

		$gsap_version = sprintf( __( '%1$sCurrent TweenMax version: %2$s', 'elementor-extras' ), '<br>', '<strong>' . ElementorExtrasPlugin::$instance->gsap_version . '</strong>' );
		$gsap_description = sprintf( __( 'By default, we load GSAP\'s TweenMax which we use for Parallax Elements and other extensions and widgets. If another plugin uses this as well you might end up with conflicts. Set this to "No" ONLY such cases, otherwise some Extras functionality will not work. %s', 'elementor-extras' ), $gsap_version );

		$fields = [
			[
				'name'		=> 'load_tweenmax',
				'label'		=> __( 'Load TweenMax', 'elementor-extras' ),
				'desc' 		=> $gsap_description,
				'type'		=> 'radio',
				'default'	=> 'yes',
				'options'	=> [
					'yes' 	=> __( 'Yes', 'elementor-extras' ),
					'no' 	=> __( 'No', 'elementor-extras' ),
				]
			]
		];

		return $fields;

	}

	/**
	* Returns current page title
	*
	* @since 1.8.0
	*
	* @access protected
	*/

	protected function get_page_title() {
		return __( 'Elementor Extras', 'elementor-extras' );
	}

}

// initialize
new Settings();

?>