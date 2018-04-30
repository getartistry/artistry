<?php
/**
 * Marketing Automation by ConvertFox
 * 
 * The official ConvertFox Wordpress plugin.
 * 
 * @package ConvertFox
 * @global object $WP_ConvertFox
 * @author Jitta Raghavender Rao <jitta@convertfox.com>
 * @version 1.4
 */
/*
Plugin Name: Marketing Automation by ConvertFox
Plugin URI: https://convertfox.com/?utm_source=wp-plugin&utm_medium=link&utm_campaign=plugins
Description: Instantly include the ConvertFox tracking script so you can use email and years of best practices to create a double-digit jump in your conversion rate.
Version: 1.4
Author: ConvertFox
Author URI: https://convertfox.com
License: GPLv2
*/

namespace convertfox;

class WP_CONVERTFOX {
	/**
	 * Instantiate a new instance
	 */
	 public function __construct() {
        if(is_admin()) {
	    	add_action('admin_menu', array($this, 'add_settings_page'));
	    	add_action('admin_init', array($this, 'convertfox_init'));
		} else {
			require_once dirname( __FILE__ ) . '/page.php';
		}
    }

	public function add_settings_page() {
        // This page will be under "Settings"
		add_options_page('ConvertFox', 'ConvertFox', 'manage_options', 'convertfox-admin', array($this, 'create_settings_page'));
    }

	public function create_settings_page() {
	?>
	<div class="wrap">
	    <?php screen_icon(); ?>
	    <h2>ConvertFox Settings</h2>
	    <?php 
		    $settings = (array) get_option( 'convertfox_settings' );
		    if ( !$settings['project_id'] ) {
	    ?>
		<p>Don't have a ConvertFox account? Go ahead, and <a href="https://convertfox.com/?utm_source=wp-plugin&utm_medium=link&utm_campaign=dashboard" class="cf-button" target="_blank">sign up for free</a>.</p>
		<?php } ?>
	    <form method="post" action="options.php">
	    <?php
            // This prints out all hidden setting fields
		    settings_fields('convertfox_settings_group');
		    do_settings_sections('convertfox_options');
		?>
	        <?php submit_button(); ?>
	    </form>
	</div>
	<?php
    }

	public function print_section_info() {
		print 'Enter your ConvertFox project ID below:';
    }

	function my_text_input( $args ) {
	    $name = esc_attr( $args['name'] );
	    $value = esc_attr( $args['value'] );
	    if(strlen($value) > 0) {
	    	$size = strlen($value) + 2;
	    } else {
	    	$size = 25;
	    }
	    echo "<input type='text' name='$name' size='$size' value='$value' />";
	}

	/** 
	 * Output the input for the enabled option
	 */
	public function admin_option_is_enabled() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( $settings['is_enabled'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[is_enabled]' value='1' " . 
			 $temp_checked . " /> " .
			"Enable tracking code on all pages</label>";
	}

	public function admin_option_disable_for_admin() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( $settings['disable_for_admin'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[disable_for_admin]' value='1' " . 
			 $temp_checked . " /> " .
			"Disable chat for WordPress admin users</label>";
	}

	public function admin_option_identify_users() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( $settings['identify_users'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[identify_users]' value='1' " . 
			 $temp_checked . " /> " .
			"Identify all logged-in WordPress users</label>";
	}

    public function convertfox_init() {
		register_setting('convertfox_settings_group', 'convertfox_settings', array($this, 'validate'));
      	$settings = (array) get_option( 'convertfox_settings' );

        add_settings_section(
		    'convertfox_settings_section',
		    'Tracking Code',
		    array($this, 'print_section_info'),
		    'convertfox_options'
		);

		add_settings_field(
		    'project_id',
		    'ConvertFox Project ID', // human readable part
		    array($this, 'my_text_input'),  // the function that renders the field
		    	'convertfox_options',
		    	'convertfox_settings_section', array(
			    	'name' => 'convertfox_settings[project_id]',
			    	'value' => $settings['project_id'],
				)
		);

		add_settings_field(
		    'convertfox_is_enabled',
		    'Enable Tracker', // human readable part
		    array($this, 'admin_option_is_enabled'),  // the function that renders the field
		    	'convertfox_options',
		    	'convertfox_settings_section', array(
			    	'name' => 'convertfox_settings[is_enabled]',
			    	'value' => $settings['is_enabled'],
				)
		);

		add_settings_field(
		    'convertfox_disable_for_admin',
		    'Disable chat for WordPress admin users', // human readable part
		    array($this, 'admin_option_disable_for_admin'),  // the function that renders the field
		    	'convertfox_options',
		    	'convertfox_settings_section', array(
			    	'name' => 'convertfox_settings[disable_for_admin]',
			    	'value' => $settings['disable_for_admin'],
				)
		);

		add_settings_field(
		    'convertfox_identify_users',
		    'Identify logged-in users', // human readable part
		    array($this, 'admin_option_identify_users'),  // the function that renders the field
		    	'convertfox_options',
		    	'convertfox_settings_section', array(
			    	'name' => 'convertfox_settings[identify_users]',
			    	'value' => $settings['identify_users'],
				)
		);
	}
	public function validate( $input ) {
		$output = get_option( 'convertfox_settings' );
	    if ( ctype_alnum( $input['project_id'] ) || $input['project_id'] == "" ) {
	        $output['project_id'] = $input['project_id'];
	    } else {
	    	echo "Adding Error \n"; #die;
	        add_settings_error( 'convertfox_options', 'project_id', 'The ConvertFox project ID you entered is invalid (should be alpha numeric)' );
	    }

	    if ( isset( $input['is_enabled'] ) ) {
	      $output['is_enabled'] = $input['is_enabled'] = true;
	    } else {
	      $output['is_enabled'] = false;
	    }

	    if ( isset( $input['disable_for_admin'] ) ) {
	      $output['disable_for_admin'] = $input['disable_for_admin'] = true;
	    } else {
	      $output['disable_for_admin'] = false;
	    }

	    if ( isset( $input['identify_users'] ) ) {
	      $output['identify_users'] = $input['identify_users'] = true;
	    } else {
	      $output['identify_users'] = false;
	    }

	    return $output;
	}
}
$ConvertFox = new \convertfox\WP_CONVERTFOX();
?>