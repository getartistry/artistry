<?php
/**
 * Plugin Name:			Ocean Sticky Header
 * Plugin URI:			https://oceanwp.org/extension/ocean-sticky-header/
 * Description:			A simple extension to attach the header at the top of your screen with an animation.
 * Version:				1.1.8
 * Author:				OceanWP
 * Author URI:			https://oceanwp.org/
 * Requires at least:	4.5.0
 * Tested up to:		4.9.4
 *
 * Text Domain: ocean-sticky-header
 * Domain Path: /languages/
 *
 * @package Ocean_Sticky_Header
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Sticky_Header to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Sticky_Header
 */
function Ocean_Sticky_Header() {
	return Ocean_Sticky_Header::instance();
} // End Ocean_Sticky_Header()

Ocean_Sticky_Header();

/**
 * Main Ocean_Sticky_Header Class
 *
 * @class Ocean_Sticky_Header
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Sticky_Header
 */
final class Ocean_Sticky_Header {
	/**
	 * Ocean_Sticky_Header The single instance of Ocean_Sticky_Header.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	// Customizer preview
	private $enable_postMessage  = true;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'ocean-sticky-header';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.1.8';

		define( 'OSH_URL', $this->plugin_url );
		define( 'OSH_PATH', $this->plugin_path );

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'init', array( $this, 'updater' ), 1 );
	}

	/**
	 * Initialize License Updater.
	 * Load Updater initialize.
	 * @return void
	 */
	public function updater() {

		// Plugin Updater Code
		if( class_exists( 'OceanWP_Plugin_Updater' ) ) {
			$license	= new OceanWP_Plugin_Updater( __FILE__, 'Sticky Header', $this->version, 'OceanWP' );
		}
	}

	/**
	 * Main Ocean_Sticky_Header Instance
	 *
	 * Ensures only one instance of Ocean_Sticky_Header is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Sticky_Header()
	 * @return Main Ocean_Sticky_Header instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'ocean-sticky-header', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Setup all the things.
	 * Only executes if OceanWP or a child theme using OceanWP as a parent is active and the extension specific filter returns true.
	 * @return void
	 */
	public function setup() {
		$theme = wp_get_theme();

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			// Capabilities
			$capabilities = apply_filters( 'ocean_main_metaboxes_capabilities', 'manage_options' );
			
			require_once( OSH_PATH .'/includes/helpers.php' );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			if ( current_user_can( $capabilities ) ) {
				add_action( 'butterbean_register', array( $this, 'new_tab' ), 10, 2 );
			}
			add_filter( 'osh_sticky_logo', array( $this, 'sticky_logo' ) );
			add_filter( 'osh_retina_sticky_logo', array( $this, 'retina_sticky_logo' ) );
			add_filter( 'osh_shrink_header_logo_height', array( $this, 'sticky_logo_height' ) );
			add_filter( 'osh_background_color', array( $this, 'background_color' ) );
			add_filter( 'osh_links_color', array( $this, 'links_color' ) );
			add_filter( 'osh_links_hover_color', array( $this, 'links_hover_color' ) );
			add_filter( 'osh_links_active_color', array( $this, 'links_active_color' ) );
			add_filter( 'osh_links_bg_color', array( $this, 'links_bg_color' ) );
			add_filter( 'osh_links_hover_bg_color', array( $this, 'links_hover_bg_color' ) );
			add_filter( 'osh_links_active_bg_color', array( $this, 'links_active_bg_color' ) );
			add_filter( 'osh_menu_social_links_color', array( $this, 'menu_social_links_color' ) );
			add_filter( 'osh_menu_social_hover_links_color', array( $this, 'menu_social_hover_links_color' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );
			add_filter( 'ocean_localize_array', array( $this, 'localize_array' ) );
			add_action( 'wp', array( $this, 'topbar_sticky' ), 999 );
			add_filter( 'ocean_header_classes', array( $this, 'header_classes' ) );
			add_filter( 'ocean_header_logo_classes', array( $this, 'logo_classes' ) );
			add_filter( 'ocean_head_css', array( $this, 'head_css' ) );
		}
	}

	/**
	 * Loads js file for customizer preview
	 *
	 * @since  1.0.0
	 */
	public function customize_preview_init() {
		if ( $this->enable_postMessage ) {
			wp_enqueue_script( 'osh-customize-preview',
				plugins_url( '/includes/customizer.min.js', __FILE__ ),
				array( 'customize-preview' ),
				OCEANWP_THEME_VERSION,
				true
			);
		}
	}

	/**
	 * Customizer Controls and settings
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 *
	 * @since  1.0.0
	 */
	public function customize_register( $wp_customize ) {

		/**
		 * Custom control
		 */
		require_once( $this->plugin_path .'/includes/customizer-helpers.php' );

		/**
	     * Add a new section
	     */
        $wp_customize->add_section( 'osh_section' , array(
		    'title'      	=> esc_html__( 'Sticky Header', 'ocean-sticky-header' ),
		    'priority'   	=> 210,
		) );

		/**
		 * Sticky
		 */
		$wp_customize->add_setting( 'osh_sticky_choose', array(
			'default'           	=> 'auto',
			'sanitize_callback' 	=> 'oceanwp_sanitize_select',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'osh_sticky_choose', array(
			'label'	   				=> esc_html__( 'Sticky', 'ocean-sticky-header' ),
		    'description'   		=> sprintf( esc_html__( 'This option has been designed for the Custom Header style. %1$sLearn more%2$s.', 'ocean-sticky-header' ), '<a href="http://docs.oceanwp.org/article/460-sticky-header-for-the-custom-header-style" target="_blank">', '</a>' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_sticky_choose',
			'priority' 				=> 10,
			'choices' 				=> array(
				'auto' 		=> esc_html__( 'Auto', 'ocean-sticky-header' ),
				'manual' 	=> esc_html__( 'Manual', 'ocean-sticky-header' ),
			),
		) ) );

		/**
	     * Sticky top bar
	     */
        $wp_customize->add_setting( 'osh_has_sticky_topbar', array(
			'default'			=> false,
			'sanitize_callback'	=> 'oceanwp_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osh_has_sticky_topbar', array(
			'label'			=> esc_html__( 'Sticky Top Bar', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'settings'		=> 'osh_has_sticky_topbar',
			'type'			=> 'checkbox',
			'priority'		=> 10,
		) ) );

		/**
	     * Sticky mobile
	     */
        $wp_customize->add_setting( 'osh_has_sticky_mobile', array(
			'default'			=> false,
			'sanitize_callback'	=> 'oceanwp_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osh_has_sticky_mobile', array(
			'label'			=> esc_html__( 'Sticky Mobile', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'settings'		=> 'osh_has_sticky_mobile',
			'type'			=> 'checkbox',
			'priority'		=> 10,
		) ) );

		/**
	     * Full width header
	     */
        $wp_customize->add_setting( 'osh_has_full_width_header', array(
			'default'			=> false,
			'sanitize_callback'	=> 'oceanwp_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osh_has_full_width_header', array(
			'label'			=> esc_html__( 'Full Width Scrolling', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'settings'		=> 'osh_has_full_width_header',
			'type'			=> 'checkbox',
			'priority'		=> 10,
		) ) );

		/**
	     * No shadow
	     */
        $wp_customize->add_setting( 'osh_no_shadow', array(
			'transport' 		=> 'postMessage',
			'default'			=> false,
			'sanitize_callback'	=> 'oceanwp_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osh_no_shadow', array(
			'label'			=> esc_html__( 'No Shadow When Scrolling', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'settings'		=> 'osh_no_shadow',
			'type'			=> 'checkbox',
			'priority'		=> 10,
		) ) );

		/**
	     * Sticky header style
	     */
        $wp_customize->add_setting( 'osh_sticky_header_style', array(
			'default'			=> 'shrink',
			'sanitize_callback' => 'oceanwp_sanitize_select',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'osh_sticky_header_style', array(
			'label'			=> esc_html__( 'Sticky Style', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'settings'		=> 'osh_sticky_header_style',
			'choices'		=> array(
				'shrink'	=> 'Shrink',
				'fixed'		=> 'Fixed',
			),
			'priority'		=> 10,
		) ) );

		/**
		 * Sticky header effect
		 */
		$wp_customize->add_setting( 'osh_sticky_header_effect', array(
			'default'           	=> 'none',
			'sanitize_callback' 	=> 'oceanwp_sanitize_select',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osh_sticky_header_effect', array(
			'label'	   				=> esc_html__( 'Sticky Effect', 'ocean-sticky-header' ),
			'description'	   		=> esc_html__( 'Do not work with all header styles.', 'ocean-sticky-header' ),
			'type' 					=> 'select',
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_sticky_header_effect',
			'priority' 				=> 10,
			'choices' 				=> array(
				'none' 		=> esc_html__( 'No Effect', 'ocean-sticky-header' ),
				'slide' 	=> esc_html__( 'Slide', 'ocean-sticky-header' ),
				'up'		=> esc_html__( 'Show Up/Hide Down', 'ocean-sticky-header' ),
			),
		) ) );

		/**
	     * Shrink sticky header height
	     */
        $wp_customize->add_setting( 'osh_shrink_header_height', array(
			'transport' 		=> 'postMessage',
			'default'			=> 54,
			'sanitize_callback' => 'oceanwp_sanitize_number',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'osh_shrink_header_height', array(
			'label'			=> esc_html__( 'Sticky Height', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'settings'		=> 'osh_shrink_header_height',
		    'input_attrs' 	=> array(
		        'min'   => 30,
		        'max'   => 100,
			    'step'  => 1,
		    ),
			'priority'		=> 10,
			'active_callback' => 'osh_cac_has_shrink_style',
		) ) );

		/**
		 * Header Padding
		 */
		$wp_customize->add_setting( 'osh_header_top_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_right_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_bottom_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_left_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_setting( 'osh_header_tablet_top_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_tablet_right_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_tablet_bottom_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_tablet_left_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_setting( 'osh_header_mobile_top_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_mobile_right_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_mobile_bottom_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'osh_header_mobile_left_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Dimensions_Control( $wp_customize, 'osh_header_top_padding', array(
			'label'	   				=> esc_html__( 'Sticky Padding (px)', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' => array(
	            'desktop_top' 		=> 'osh_header_top_padding',
	            'desktop_right' 	=> 'osh_header_right_padding',
	            'desktop_bottom' 	=> 'osh_header_bottom_padding',
	            'desktop_left' 		=> 'osh_header_left_padding',
	            'tablet_top' 		=> 'osh_header_tablet_top_padding',
	            'tablet_right' 		=> 'osh_header_tablet_right_padding',
	            'tablet_bottom' 	=> 'osh_header_tablet_bottom_padding',
	            'tablet_left' 		=> 'osh_header_tablet_left_padding',
	            'mobile_top' 		=> 'osh_header_mobile_top_padding',
	            'mobile_right' 		=> 'osh_header_mobile_right_padding',
	            'mobile_bottom' 	=> 'osh_header_mobile_bottom_padding',
	            'mobile_left' 		=> 'osh_header_mobile_left_padding',
		    ),
			'priority' 				=> 10,
		    'input_attrs' 			=> array(
		        'min'   => 0,
		        'max'   => 100,
		        'step'  => 1,
		    ),
		) ) );

		/**
	     * Sticky header opacity
	     */
        $wp_customize->add_setting( 'osh_sticky_header_opacity', array(
			'transport' 	=> 'postMessage',
			'default'		=> '0.95',
			'sanitize_callback' => 'oceanwp_sanitize_number',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'osh_sticky_header_opacity', array(
			'label'			=> esc_html__( 'Sticky Opacity', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'settings'		=> 'osh_sticky_header_opacity',
		    'input_attrs' => array(
		        'min'   => 0.1,
		        'max'   => 1,
    			'step' 	=> 0.01,
		    ),
			'priority'		=> 10,
		) ) );

		/**
		 * Heading Styling
		 */
		$wp_customize->add_setting( 'osh_logo_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osh_logo_heading', array(
			'label'    		=> esc_html__( 'Logo', 'ocean-sticky-header' ),
			'section'  		=> 'osh_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Sticky Image Logo
		 */
		$wp_customize->add_setting( 'osh_logo', array(
			'default'           	=> '',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'osh_logo', array(
			'label'	   				=> esc_html__( 'Sticky Logo', 'ocean-sticky-header' ),
			'description'	   		=> esc_html__( 'If you want to display a different logo when scrolling (optional)', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_logo',
			'priority' 				=> 10,
		) ) );

		/**
		 * Sticky Retina Logo
		 */
		$wp_customize->add_setting( 'osh_logo_retina', array(
			'default'           	=> '',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'osh_logo_retina', array(
			'label'	   				=> esc_html__( 'Sticky Retina Logo', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_logo_retina',
			'priority' 				=> 10,
		) ) );

		/**
	     * Shrink sticky header logo height
	     */
		$wp_customize->add_setting( 'osh_shrink_header_logo_height', array(
			'transport' 		=> 'postMessage',
			'default'			=> '30',
			'sanitize_callback' => 'oceanwp_sanitize_number',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osh_shrink_header_logo_height', array(
			'label'			=> esc_html__( 'Logo Height On Sticky', 'ocean-sticky-header' ),
			'section'		=> 'osh_section',
			'type'			=> 'number',
			'active_callback' => 'osh_cac_has_shrink_style',
			'settings'		=> 'osh_shrink_header_logo_height',
		    'input_attrs' => array(
		        'min'   => 10,
		        'max'   => 100,
			    'step'  => 1,
		    ),
			'priority'		=> 10,
		) ) );

		/**
		 * Heading Styling
		 */
		$wp_customize->add_setting( 'osh_styling_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osh_styling_heading', array(
			'label'    		=> esc_html__( 'Styling', 'ocean-sticky-header' ),
			'section'  		=> 'osh_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Sticky Background
		 */
		$wp_customize->add_setting( 'osh_background_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_background_color', array(
			'label'	   				=> esc_html__( 'Background Color', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_background_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Links Color
		 */
		$wp_customize->add_setting( 'osh_links_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_links_color', array(
			'label'	   				=> esc_html__( 'Links Color', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_links_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Links Hover Color
		 */
		$wp_customize->add_setting( 'osh_links_hover_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_links_hover_color', array(
			'label'	   				=> esc_html__( 'Links Color: Hover', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_links_hover_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Links Active Color
		 */
		$wp_customize->add_setting( 'osh_links_active_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_links_active_color', array(
			'label'	   				=> esc_html__( 'Current Menu Item Color', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_links_active_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Links Background Color
		 */
		$wp_customize->add_setting( 'osh_links_bg_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_links_bg_color', array(
			'label'	   				=> esc_html__( 'Links Background Color', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_links_bg_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Links Hover Background Color
		 */
		$wp_customize->add_setting( 'osh_links_hover_bg_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_links_hover_bg_color', array(
			'label'	   				=> esc_html__( 'Links Background Color: Hover', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_links_hover_bg_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Links Active Background Color
		 */
		$wp_customize->add_setting( 'osh_links_active_bg_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_links_active_bg_color', array(
			'label'	   				=> esc_html__( 'Current Menu Item Background', 'ocean-sticky-header' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_links_active_bg_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Menu Social Link Color
		 */
		$wp_customize->add_setting( 'osh_menu_social_links_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_menu_social_links_color', array(
			'label'	   				=> esc_html__( 'Simple Social Links Color', 'oceanwp' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_menu_social_links_color',
			'priority' 				=> 10,
			'active_callback' 		=> 'oceanwp_cac_has_menu_social',
		) ) );

		/**
		 * Menu Social Link Hover Color
		 */
		$wp_customize->add_setting( 'osh_menu_social_hover_links_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osh_menu_social_hover_links_color', array(
			'label'	   				=> esc_html__( 'Simple Social Links Color: Hover', 'oceanwp' ),
			'section'  				=> 'osh_section',
			'settings' 				=> 'osh_menu_social_hover_links_color',
			'priority' 				=> 10,
			'active_callback' 		=> 'oceanwp_cac_has_menu_social',
		) ) );
	}

	/**
	 * Add new tab in metabox.
	 *
	 * @since  1.0.0
	 */
	public function new_tab( $butterbean, $post_type ) {

		// Gets the manager object we want to add sections to.
		$manager = $butterbean->get_manager( 'oceanwp_mb_settings' );
						
		$manager->register_section(
	        'oceanwp_mb_sticky',
	        array(
	            'label' => esc_html__( 'Sticky Header', 'ocean-sticky-header' ),
	            'icon'  => 'dashicons-schedule'
	        )
	    );

		$manager->register_control(
	        'osh_disable_topbar_sticky', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'buttonset',
	            'label'   		=> esc_html__( 'Sticky Top Bar', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Disable the sticky top bar on this page/post.', 'ocean-sticky-header' ),
				'choices' 		=> array(
					'default' 	=> esc_html__( 'Default', 'ocean-sticky-header' ),
					'off' 		=> esc_html__( 'Disable', 'ocean-sticky-header' ),
				),
	        )
	    );
		
		$manager->register_setting(
	        'osh_disable_topbar_sticky', // Same as control name.
	        array(
	            'sanitize_callback' => 'sanitize_key',
	            'default' 			=> 'default',
	        )
	    );
		
		$manager->register_control(
	        'osh_disable_header_sticky', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'buttonset',
	            'label'   		=> esc_html__( 'Sticky Header', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Disable the sticky header on this page/post.', 'ocean-sticky-header' ),
				'choices' 		=> array(
					'default' 	=> esc_html__( 'Default', 'ocean-sticky-header' ),
					'off' 		=> esc_html__( 'Disable', 'ocean-sticky-header' ),
				),
	        )
	    );
		
		$manager->register_setting(
	        'osh_disable_header_sticky', // Same as control name.
	        array(
	            'sanitize_callback' => 'sanitize_key',
	            'default' 			=> 'default',
	        )
	    );
			
		$manager->register_control(
	        'osh_custom_sticky_logo', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'image',
	            'label'   		=> esc_html__( 'Logo', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a custom sticky logo on this page/post.', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_custom_sticky_logo', // Same as control name.
	        array(
	        	'sanitize_callback' => 'sanitize_key',
	        )
	    );
		
		$manager->register_control(
	        'osh_custom_retina_sticky_logo', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'image',
	            'label'   		=> esc_html__( 'Retina Logo', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a custom retina sticky logo on this page/post.', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_custom_retina_sticky_logo', // Same as control name.
	        array(
	        	'sanitize_callback' => 'sanitize_key',
	        )
	    );

	    $manager->register_control(
	        'osh_custom_sticky_logo_height', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'number',
	            'label'   		=> esc_html__( 'Logo Height On Sticky (px)', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Enter the height of your logo when you scroll.', 'ocean-sticky-header' ),
	            'attr'    		=> array(
					'min' 	=> '10',
					'step' 	=> '1',
				),
	        )
	    );
		
		$manager->register_setting(
	        'osh_custom_sticky_logo_height', // Same as control name.
	        array(
	            'sanitize_callback' => array( $this, 'sanitize_absint' ),
	        )
	    );

		$manager->register_control(
	        'osh_background_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Background Color', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #555', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_background_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );

	    $manager->register_control(
	        'osh_links_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Links Color', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #fff', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_links_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );

		$manager->register_control(
	        'osh_links_hover_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Links Color: Hover', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #13aff0', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_links_hover_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );

		$manager->register_control(
	        'osh_links_active_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Current Menu Item Color', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #333', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_links_active_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );

		$manager->register_control(
	        'osh_links_bg_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Links Background Color', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #333', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_links_bg_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );
	    
		$manager->register_control(
	        'osh_links_hover_bg_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Links Background Color: Hover', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #fff', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_links_hover_bg_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );
	    
		$manager->register_control(
	        'osh_links_active_bg_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Current Menu Item Background', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #13aff0', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_links_active_bg_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );
	    
		$manager->register_control(
	        'osh_menu_social_links_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Simple Social Links Color', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #fff', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_menu_social_links_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );
	    
		$manager->register_control(
	        'osh_menu_social_hover_links_color', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_sticky',
	            'type'    		=> 'rgba-color',
	            'label'   		=> esc_html__( 'Simple Social Links Color: Hover', 'ocean-sticky-header' ),
	            'description'   => esc_html__( 'Select a color. Hex code, ex: #13aff0', 'ocean-sticky-header' ),
	        )
	    );
		
		$manager->register_setting(
	        'osh_menu_social_hover_links_color', // Same as control name.
	        array(
	            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
	        )
	    );

	}

	/**
	 * Sanitize function for integers
	 *
	 * @since  1.0.0
	 */
	public function sanitize_absint( $value ) {
		return $value && is_numeric( $value ) ? absint( $value ) : '';
	}

	/**
	 * Custom sticky logo
	 *
	 * @since  1.0.0
	 */
	public function sticky_logo( $logo_url ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_custom_sticky_logo', true ) ) {
			$logo_url = $meta;

			// Generate image URL if using ID
			if ( is_numeric( $logo_url ) ) {
				$logo_url = wp_get_attachment_image_src( $logo_url, 'full' );
				$logo_url = $logo_url[0];
			}
		}

		return $logo_url;

	}

	/**
	 * Custom retina sticky logo
	 *
	 * @since  1.0.0
	 */
	public function retina_sticky_logo( $logo_url ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_custom_retina_sticky_logo', true ) ) {
			$logo_url = $meta;

			// Generate image URL if using ID
			if ( is_numeric( $logo_url ) ) {
				$logo_url = wp_get_attachment_image_src( $logo_url, 'full' );
				$logo_url = $logo_url[0];
			}
		}

		return $logo_url;

	}

	/**
	 * Custom shrink logo height
	 *
	 * @since  1.0.0
	 */
	public function sticky_logo_height( $logo_height ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_custom_sticky_logo_height', true ) ) {
			$logo_height = $meta;
		}

		return $logo_height;

	}

	/**
	 * Sticky header background color
	 *
	 * @since  1.0.0
	 */
	public function background_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_background_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu links color
	 *
	 * @since  1.0.0
	 */
	public function links_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_links_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu links hover color
	 *
	 * @since  1.0.0
	 */
	public function links_hover_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_links_hover_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu links active color
	 *
	 * @since  1.0.0
	 */
	public function links_active_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_links_active_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu links background color
	 *
	 * @since  1.0.0
	 */
	public function links_bg_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_links_bg_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu links hover background color
	 *
	 * @since  1.0.0
	 */
	public function links_hover_bg_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_links_hover_bg_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu links active background color
	 *
	 * @since  1.0.0
	 */
	public function links_active_bg_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_links_active_bg_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu social links color
	 *
	 * @since  1.0.0
	 */
	public function menu_social_links_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_menu_social_links_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * Sticky header menu social hover links color
	 *
	 * @since  1.0.0
	 */
	public function menu_social_hover_links_color( $color ) {

		if ( $meta = get_post_meta( oceanwp_post_id(), 'osh_menu_social_hover_links_color', true ) ) {
			$color = $meta;
		}

		return $color;

	}

	/**
	 * If enabled.
	 *
	 * @since  1.1.7
	 */
	public function if_enabled() {

		// Return true by default
		$return = true;

		// Apply filters and return
		return apply_filters( 'osh_enable_sticky_header', $return );

	}

	/**
	 * Enqueue scripts.
	 * @since   1.0.0
	 * @return  void
	 */
	public function scripts() {

		// Return if disabled
		if ( ! $this->if_enabled() ) {
			return;
		}
		
		// Load main stylesheet
		wp_enqueue_style( 'osh-styles', plugins_url( '/assets/css/style.min.css', __FILE__ ) );
		
		// Load custom js methods.
		wp_enqueue_script( 'osh-js-scripts', plugins_url( '/assets/js/main.min.js', __FILE__ ), array( 'jquery' ), null, true );

	}

	/**
	 * Localize array
	 *
	 * @since  1.0.0
	 */
	public function localize_array( $array ) {

		if ( $this->if_enabled() ) {
			$array['stickyChoose'] 		= get_theme_mod( 'osh_sticky_choose', 'auto' );
			$array['stickyStyle'] 		= get_theme_mod( 'osh_sticky_header_style', 'shrink' );
			$array['shrinkLogoHeight'] 	= apply_filters( 'osh_shrink_header_logo_height', get_theme_mod( 'osh_shrink_header_logo_height', '30' ) );
			$array['stickyEffect'] 		= get_theme_mod( 'osh_sticky_header_effect', 'none' );
			$array['hasStickyTopBar'] 	= $this->if_topbar_sticky();
			$array['hasStickyMobile'] 	= get_theme_mod( 'osh_has_sticky_mobile', false );
		}

		return $array;

	}

	/**
	 * If top bar sticky
	 *
	 * @since  1.1.5
	 */
	public function if_topbar_sticky() {

		// Return the customizer option by default
		$return = get_theme_mod( 'osh_has_sticky_topbar', false );
		
		// Retunr meta if Disable if selected
		$meta = oceanwp_post_id() ? get_post_meta( oceanwp_post_id(), 'osh_disable_topbar_sticky', true ) : '';

		if ( 'off' == $meta ) {
			$return = false;
		}

		return $return;

	}

	/**
	 * Adds the filter to add class to the top bar wrap if sticky top bar is enabled.
	 *
	 * @since  1.0.0
	 */
	public function topbar_sticky() {
		if ( true == $this->if_topbar_sticky() ) {
			add_filter( 'ocean_topbar_classes', array( $this, 'topbar_classes' ) );
		}
	}

	/**
	 * OceanWP Sticky Top Bar Class
	 * Adds the fixed class to the top bar wrap.
	 *
	 * @since  1.0.0
	 */
	public function topbar_classes( $classes ) {
		$classes[] = 'top-bar-sticky';

		// Full width header
		$hasFullWidthHeader = get_theme_mod( 'osh_has_full_width_header', false );
		if ( true == $hasFullWidthHeader ) {
			$classes[] = 'has-full-width-top';
		}

		return $classes;
	}

	/**
	 * If header sticky
	 *
	 * @since  1.1.5
	 */
	public function if_header_sticky() {

		// Return true by default
		$return = true;
		
		// Retunr meta if Disable if selected
		$meta = oceanwp_post_id() ? get_post_meta( oceanwp_post_id(), 'osh_disable_header_sticky', true ) : '';

		if ( 'off' == $meta ) {
			$return = false;
		}

		return $return;

	}

	/**
	 * Sticky Header Class
	 * Adds the fixed class to the header wrap.
	 *
	 * @since  1.0.0
	 */
	public function header_classes( $classes ) {

		// Return if disabled
		if ( false == $this->if_header_sticky()
			|| ! $this->if_enabled() ) {
			return $classes;
		}

		// Get header style
		$headerStyle = oceanwp_header_style();

		if ( 'vertical' != $headerStyle ) {
			$classes[] = 'fixed-scroll';
		}

		// If navigation sticky (for the WooCommerce sticky payment)
		if ( 'medium' == $headerStyle
			&& true == get_theme_mod( 'ocean_medium_header_stick_menu', false ) ) {
			$classes[] = 'fixed-nav';
		}

		// Sticky style
		$stickyStyle = get_theme_mod( 'osh_sticky_header_style', 'shrink' );
		if ( 'manual' != get_theme_mod( 'osh_sticky_choose', 'auto' )
			|| 'top' != $headerStyle
			|| ( 'medium' == $headerStyle
				&& true == get_theme_mod( 'ocean_medium_header_stick_menu', false ) )
			|| 'vertical' != $headerStyle ) {
			if ( 'shrink' == $stickyStyle ) {
				$classes[] = 'shrink-header';
			} else if ( 'fixed' == $stickyStyle ) {
				$classes[] = 'fixed-header';
			}
		}

		// Sticky effect
		$stickyEffect = get_theme_mod( 'osh_sticky_header_effect', 'none' );
		if ( 'none' != $stickyEffect
			&& 'vertical' != $headerStyle ) {
			$classes[] = $stickyEffect .'-effect';
		}

		// Sticky mobile
		if ( true == get_theme_mod( 'osh_has_sticky_mobile', false ) ) {
			$classes[] = 'has-sticky-mobile';
		}

		// Full width header
		if ( true == get_theme_mod( 'osh_has_full_width_header', false ) ) {
			$classes[] = 'has-full-width-header';
		}

		// No shadow
		if ( true == get_theme_mod( 'osh_no_shadow', false ) ) {
			$classes[] = 'no-shadow';
		}

		return $classes;
	}

	/**
	 * Sticky Logo Class
	 *
	 * @since  1.0.0
	 */
	public function logo_classes( $classes ) {

		// If has sticky logo
		if ( '' != osh_sticky_logo_setting() ) {
			$classes[] = 'has-sticky-logo';
		}

		return $classes;
	}

	/**
	 * Add css in head tag.
	 *
	 * @since  1.0.0
	 */
	public function head_css( $output ) {

		// Get header style
		$headerStyle 				= oceanwp_header_style();
		
		// Global vars
		$shrink_height 				= get_theme_mod( 'osh_shrink_header_height', '54' );
		$top_padding 				= get_theme_mod( 'osh_header_top_padding' );
		$right_padding 				= get_theme_mod( 'osh_header_right_padding' );
		$bottom_padding 			= get_theme_mod( 'osh_header_bottom_padding' );
		$left_padding 				= get_theme_mod( 'osh_header_left_padding' );
		$tablet_top_padding 		= get_theme_mod( 'osh_header_tablet_top_padding' );
		$tablet_right_padding 		= get_theme_mod( 'osh_header_tablet_right_padding' );
		$tablet_bottom_padding 		= get_theme_mod( 'osh_header_tablet_bottom_padding' );
		$tablet_left_padding 		= get_theme_mod( 'osh_header_tablet_left_padding' );
		$mobile_top_padding 		= get_theme_mod( 'osh_header_mobile_top_padding' );
		$mobile_right_padding 		= get_theme_mod( 'osh_header_mobile_right_padding' );
		$mobile_bottom_padding 		= get_theme_mod( 'osh_header_mobile_bottom_padding' );
		$mobile_left_padding 		= get_theme_mod( 'osh_header_mobile_left_padding' );
		$opacity 					= get_theme_mod( 'osh_sticky_header_opacity', '0.95' );
		$background_color 			= get_theme_mod( 'osh_background_color' );
		$links_color 				= get_theme_mod( 'osh_links_color' );
		$links_hover_color 			= get_theme_mod( 'osh_links_hover_color' );
		$links_active_color 		= get_theme_mod( 'osh_links_active_color' );
		$links_bg_color 			= get_theme_mod( 'osh_links_bg_color' );
		$links_hover_bg_color 		= get_theme_mod( 'osh_links_hover_bg_color' );
		$links_active_bg_color 		= get_theme_mod( 'osh_links_active_bg_color' );
		$social_links_color 		= get_theme_mod( 'osh_menu_social_links_color' );
		$social_hover_links_color 	= get_theme_mod( 'osh_menu_social_hover_links_color' );

		// Filters to altering settings via the metabox
		$background_color 			= apply_filters( 'osh_background_color', $background_color );
		$links_color 				= apply_filters( 'osh_links_color', $links_color );
		$links_hover_color 			= apply_filters( 'osh_links_hover_color', $links_hover_color );
		$links_active_color 		= apply_filters( 'osh_links_active_color', $links_active_color );
		$links_bg_color 			= apply_filters( 'osh_links_bg_color', $links_bg_color );
		$links_hover_bg_color 		= apply_filters( 'osh_links_hover_bg_color', $links_hover_bg_color );
		$links_active_bg_color 		= apply_filters( 'osh_links_active_bg_color', $links_active_bg_color );
		$social_links_color 		= apply_filters( 'osh_menu_social_links_color', $social_links_color );
		$social_hover_links_color 	= apply_filters( 'osh_menu_social_hover_links_color', $social_hover_links_color );

		// Define css var
		$css = '';

		if ( 'top' != $headerStyle && 'fixed' != get_theme_mod( 'osh_sticky_header_style', 'shrink' ) ) {

			// Add height
			if ( ! empty( $shrink_height ) && '54' != $shrink_height ) {
				$css .= '.is-sticky #site-header.shrink-header #site-logo #site-logo-inner, .is-sticky #site-header.shrink-header .oceanwp-social-menu .social-menu-inner, .is-sticky #site-header.shrink-header.full_screen-header .menu-bar-inner,.after-header-content .after-header-content-inner{height:'. $shrink_height .'px;}';
				$css .= '.is-sticky #site-header.shrink-header #site-navigation-wrap .dropdown-menu > li > a, .is-sticky #site-header.shrink-header #oceanwp-mobile-menu-icon a,.after-header-content .after-header-content-inner > a,.after-header-content .after-header-content-inner > div > a{line-height:'. $shrink_height .'px;}';
			}

		}

		// Padding
		if ( isset( $top_padding ) && '8' != $top_padding && '' != $top_padding
			|| isset( $right_padding ) && '0' != $right_padding && '' != $right_padding
			|| isset( $bottom_padding ) && '8' != $bottom_padding && '' != $bottom_padding
			|| isset( $left_padding ) && '0' != $left_padding && '' != $left_padding ) {
			$css .= 'body .is-sticky #site-header.fixed-scroll #site-header-inner{padding:'. oceanwp_spacing_css( $top_padding, $right_padding, $bottom_padding, $left_padding ) .'}';
		}

		// Tablet padding
		if ( isset( $tablet_top_padding ) && '' != $tablet_top_padding
			|| isset( $tablet_right_padding ) && '' != $tablet_right_padding
			|| isset( $tablet_bottom_padding ) && '' != $tablet_bottom_padding
			|| isset( $tablet_left_padding ) && '' != $tablet_left_padding ) {
			$css .= '@media (max-width: 768px){body .is-sticky #site-header.fixed-scroll #site-header-inner{padding:'. oceanwp_spacing_css( $tablet_top_padding, $tablet_right_padding, $tablet_bottom_padding, $tablet_left_padding ) .'}}';
		}

		// Mobile padding
		if ( isset( $mobile_top_padding ) && '' != $mobile_top_padding
			|| isset( $mobile_right_padding ) && '' != $mobile_right_padding
			|| isset( $mobile_bottom_padding ) && '' != $mobile_bottom_padding
			|| isset( $mobile_left_padding ) && '' != $mobile_left_padding ) {
			$css .= '@media (max-width: 480px){body .is-sticky #site-header.fixed-scroll #site-header-inner{padding:'. oceanwp_spacing_css( $mobile_top_padding, $mobile_right_padding, $mobile_bottom_padding, $mobile_left_padding ) .'}}';
		}

		// Add opacity
		if ( ! empty( $opacity ) && '0.95' != $opacity ) {
			$css .= '.is-sticky #site-header,.ocean-sticky-top-bar-holder.is-sticky #top-bar-wrap,.is-sticky .header-top{opacity:'. $opacity .';}';
		}

		// Add background
		if ( ! empty( $background_color ) ) {
			$css .= '.is-sticky #site-header,.is-sticky #searchform-header-replace{background-color:'. $background_color .'!important;}';
		}

		// Add links color
		if ( ! empty( $links_color ) ) {
			$css .= '.is-sticky #site-navigation-wrap .dropdown-menu > li > a,.is-sticky #oceanwp-mobile-menu-icon a,.is-sticky #searchform-header-replace-close{color:'. $links_color .';}';
		}

		// Add links hover color
		if ( ! empty( $links_hover_color ) ) {
			$css .= '.is-sticky #site-navigation-wrap .dropdown-menu > li > a:hover,.is-sticky #oceanwp-mobile-menu-icon a:hover,.is-sticky #searchform-header-replace-close:hover{color:'. $links_hover_color .';}';
		}

		// Add links active color
		if ( ! empty( $links_active_color ) ) {
			$css .= '.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a:hover,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a:hover > span{color:'. $links_active_color .';}';
		}

		// Add links background color
		if ( ! empty( $links_bg_color ) ) {
			$css .= '.is-sticky #site-navigation-wrap .dropdown-menu > li > a{background-color:'. $links_bg_color .';}';
		}

		// Add links hover background color
		if ( ! empty( $links_hover_bg_color ) ) {
			$css .= '.is-sticky #site-navigation-wrap .dropdown-menu > li > a:hover,.is-sticky #site-navigation-wrap .dropdown-menu > li.sfHover > a{background-color:'. $links_hover_bg_color .';}';
		}

		// Add links active background color
		if ( ! empty( $links_active_bg_color ) ) {
			$css .= '.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a:hover,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a:hover > span{background-color:'. $links_active_bg_color .';}';
		}

		// Menu social links color
		if ( ! empty( $social_links_color ) ) {
			$css .= '.is-sticky .oceanwp-social-menu ul li a,.is-sticky #site-header.full_screen-header .oceanwp-social-menu.simple-social ul li a{color:'. $social_links_color .';}';
		}

		// Menu social links hover color
		if ( ! empty( $social_hover_links_color ) ) {
			$css .= '.is-sticky .oceanwp-social-menu ul li a:hover,.is-sticky #site-header.full_screen-header .oceanwp-social-menu.simple-social ul li a:hover{color:'. $social_hover_links_color .'!important;}';
		}
			
		// Return CSS
		if ( ! empty( $css ) ) {
			$output .= '/* Sticky Header CSS */'. $css;
		}

		// Return output css
		return $output;

	}

} // End Class