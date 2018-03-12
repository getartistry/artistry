<?php // Add the plugin settings page

class DLK_Settings_Page
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Divi LearnDash Kit Settings', 
            'Divi LearnDash Kit', 
            'manage_options', 
            'dlk-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('divi_learndash_kit_option');
        ?>
        <div class="wrap">
            <h1>Divi LearnDash Kit Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'divi_learndash_kit_option_group' );
                do_settings_sections( 'dlk-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'divi_learndash_kit_option_group', // Option group
            'divi_learndash_kit_option' // Option name
            
        );

        add_settings_section(
            'setting_section_id', // ID
            'General', // Title
            array( $this, 'print_section_info' ), // Callback
            'dlk-setting-admin' // Page
        );  

        add_settings_field(
            'use_main_library', // ID
            'Use Main Library', // Title 
            array( $this, 'use_main_library_callback' ), // Callback
            'dlk-setting-admin', // Page
            'setting_section_id' // Section           
        );           
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        // print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function use_main_library_callback()
    {
		$use_main_library = empty($this->options['use_main_library'])?0:1;
		
		$html = '<input type="checkbox" id="use_main_library" name="divi_learndash_kit_option[use_main_library]" value="1"'.checked(1, $use_main_library, false ).'/>';
		
		echo $html;
		
    }

}

if (is_admin()) { $dlk_settings_page = new DLK_Settings_Page(); }


/* === Option to use main library on all learndash post types === */
add_filter('et_pb_show_all_layouts_built_for_post_type', 'dlk_show_layouts_from_main_library', 100, 2);

function dlk_show_layouts_from_main_library($post_type, $predefined) {
	
	$option = get_option('divi_learndash_kit_option');
	$use_main_library = empty($option['use_main_library'])?false:true;
		
	if ($use_main_library) {
		
		$library_group = array_merge(array('page'), dlk_get_learndash_post_types());
		
		$post_types = is_array($post_type)?$post_type:array($post_type);
		$post_types_in_group = count(array_intersect($post_types, $library_group));
		
		// Return original post type(s) along with rest of group
		if ($post_types_in_group) {
			return array_unique(array_merge($post_types, $library_group));
		}
	}
	
	return $post_type;
}
