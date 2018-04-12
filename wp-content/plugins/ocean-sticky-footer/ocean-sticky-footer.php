<?php
/**
 * Plugin Name:			Ocean Sticky Footer
 * Plugin URI:			https://oceanwp.org/extension/ocean-sticky-footer/
 * Description:			A simple extension to attach the footer at the bottom of your screen.
 * Version:				1.0.7
 * Author:				OceanWP
 * Author URI:			https://oceanwp.org/
 * Requires at least:	4.5.0
 * Tested up to:		4.9.1
 *
 * Text Domain: ocean-sticky-footer
 * Domain Path: /languages/
 *
 * @package Ocean_Sticky_Footer
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Sticky_Footer to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Sticky_Footer
 */
function Ocean_Sticky_Footer() {
	return Ocean_Sticky_Footer::instance();
} // End Ocean_Sticky_Footer()

Ocean_Sticky_Footer();

/**
 * Main Ocean_Sticky_Footer Class
 *
 * @class Ocean_Sticky_Footer
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Sticky_Footer
 */
final class Ocean_Sticky_Footer {
	/**
	 * Ocean_Sticky_Footer The single instance of Ocean_Sticky_Footer.
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

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'ocean-sticky-footer';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.7';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'osf_load_plugin_textdomain' ) );

		add_filter( 'ocean_register_tm_strings', array( $this, 'register_tm_strings' ) );

		add_action( 'init', array( $this, 'osf_setup' ) );
		add_action( 'init', array( $this, 'osf_updater' ), 1 );
		add_action( 'init', array( $this, 'osf_menu' ) );
	}

	/**
	 * Initialize License Updater.
	 * Load Updater initialize.
	 * @return void
	 */
	public function osf_updater() {

		// Plugin Updater Code
		if( class_exists( 'OceanWP_Plugin_Updater' ) ) {
			$license	= new OceanWP_Plugin_Updater( __FILE__, 'Sticky Footer', $this->version, 'OceanWP' );
		}
	}

	/**
	 * Main Ocean_Sticky_Footer Instance
	 *
	 * Ensures only one instance of Ocean_Sticky_Footer is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Sticky_Footer()
	 * @return Main Ocean_Sticky_Footer instance
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
	public function osf_load_plugin_textdomain() {
		load_plugin_textdomain( 'ocean-sticky-footer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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
	 * Register translation strings
	 */
	public static function register_tm_strings( $strings ) {

		$strings['osf_text'] = 'Lorem ipsum dolor sit amet.';

		return $strings;

	}

	/**
	 * Setup all the things.
	 * Only executes if OceanWP or a child theme using OceanWP as a parent is active and the extension specific filter returns true.
	 * @return void
	 */
	public function osf_setup() {
		$theme = wp_get_theme();

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			add_action( 'customize_preview_init', array( $this, 'osf_customize_preview_js' ) );
			add_action( 'customize_register', array( $this, 'osf_customize_register' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'osf_scripts' ), 999 );
			add_filter( 'body_class', array( $this, 'osf_body_classes' ) );
			add_action( 'ocean_before_footer_inner', array( $this, 'osf_sticky_footer_bar' ) );
			add_filter( 'ocean_head_css', array( $this, 'osf_head_css' ) );
		}
	}

	/**
	 * Register new menu
	 */
	public function osf_menu() {

		register_nav_menus(
			array(
				'sticky_footer_menu' => esc_html__( 'Sticky Footer', 'ocean-sticky-footer' ),
			)
		);

	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function osf_customize_preview_js() {
		wp_enqueue_script( 'osf-customizer', plugins_url( '/assets/js/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.0', true );
		wp_localize_script( 'osf-customizer', 'osf_sticky_footer', array(
			'googleFontsUrl' 	=> '//fonts.googleapis.com',
			'googleFontsWeight' => '100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i',
		) );
	}

	/**
	 * Customizer Controls and settings
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function osf_customize_register( $wp_customize ) {

		/**
	     * Add a new section
	     */
        $wp_customize->add_section( 'osf_section' , array(
		    'title'      	=> esc_html__( 'Sticky Footer', 'ocean-sticky-footer' ),
		    'priority'   	=> 210,
		) );

		/**
	     * Hide nav on mobile
	     */
        $wp_customize->add_setting( 'osf_hide_nav_on_mobile', array(
			'default'			=> false,
			'sanitize_callback'	=> 'oceanwp_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osf_hide_nav_on_mobile', array(
			'label'			=> esc_html__( 'Hide Navigation On Mobile', 'ocean-sticky-footer' ),
			'section'		=> 'osf_section',
			'settings'		=> 'osf_hide_nav_on_mobile',
			'type'			=> 'checkbox',
			'priority'		=> 10,
		) ) );

		/**
	     * Hide text on mobile
	     */
        $wp_customize->add_setting( 'osf_hide_text_on_mobile', array(
			'default'			=> true,
			'sanitize_callback'	=> 'oceanwp_sanitize_checkbox',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osf_hide_text_on_mobile', array(
			'label'			=> esc_html__( 'Hide Text On Mobile', 'ocean-sticky-footer' ),
			'section'		=> 'osf_section',
			'settings'		=> 'osf_hide_text_on_mobile',
			'type'			=> 'checkbox',
			'priority'		=> 10,
		) ) );

		/**
		 * Opening Icon
		 */
		$wp_customize->add_setting( 'osf_opening_icon', array(
			'transport'           	=> 'postMessage',
			'sanitize_callback'		=> 'wp_kses_post',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osf_opening_icon', array(
			'label'	   				=> esc_html__( 'Opening Icon', 'ocean-sticky-footer' ),
			'description'	   		=> esc_html__( 'Enter your full icon class', 'ocean-sticky-footer' ),
			'type' 					=> 'text',
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_opening_icon',
			'priority' 				=> 10,
		) ) );

		/**
	     * Sticky footer opacity
	     */
        $wp_customize->add_setting( 'osf_footer_opacity', array(
			'transport' 		=> 'postMessage',
			'default'			=> '0.9',
			'sanitize_callback' => 'oceanwp_sanitize_number',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Range_Control( $wp_customize, 'osf_footer_opacity', array(
			'label'			=> esc_html__( 'Footer Opacity', 'ocean-sticky-footer' ),
			'section'		=> 'osf_section',
			'settings'		=> 'osf_footer_opacity',
		    'input_attrs' 	=> array(
		        'min'   	=> 0.1,
		        'max'   	=> 1,
    			'step' 		=> 0.1,
		    ),
			'priority'		=> 10,
		) ) );

		/**
	     * Text
	     */
        $wp_customize->add_setting( 'osf_text', array(
			'default'			=> 'Lorem ipsum dolor sit amet.',
			'transport'			=> 'postMessage',
			'sanitize_callback' => 'wp_kses_post',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'osf_text', array(
			'label'			=> esc_html__( 'Text', 'ocean-sticky-footer' ),
			'description' 	=> esc_html__( 'Enter your custom text.', 'ocean-sticky-footer' ),
			'section'		=> 'osf_section',
			'settings'		=> 'osf_text',
			'type'			=> 'textarea',
			'priority'		=> 10,
		) ) );

		/**
		 * Heading Typography
		 */
		$wp_customize->add_setting( 'osf_footer_bar_typography_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osf_footer_bar_typography_heading', array(
			'label'    		=> esc_html__( 'Typography', 'ocean-sticky-footer' ),
			'section'  		=> 'osf_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Typography
		 */
		$wp_customize->add_setting( 'osf_footer_bar_typo_font_family', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
		$wp_customize->add_setting( 'osf_footer_bar_typo_font_size',   	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
		$wp_customize->add_setting( 'osf_footer_bar_typo_font_weight', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
		$wp_customize->add_setting( 'osf_footer_bar_typo_font_style',  	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
		$wp_customize->add_setting( 'osf_footer_bar_typo_transform', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
		$wp_customize->add_setting( 'osf_footer_bar_typo_line_height', 	array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );
		$wp_customize->add_setting( 'osf_footer_bar_typo_spacing', 		array( 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_text_field', ) );

		$wp_customize->add_control( new OceanWP_Customizer_Typo_Control( $wp_customize, 'osf_footer_bar_typo', array(
			'label'	   				=> esc_html__( 'Typography', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
            'settings'    			=> array(
				'family'      	=> 'osf_footer_bar_typo_font_family',
				'size'        	=> 'osf_footer_bar_typo_font_size',
				'weight'      	=> 'osf_footer_bar_typo_font_weight',
				'style'       	=> 'osf_footer_bar_typo_font_style',
				'transform' 	=> 'osf_footer_bar_typo_transform',
				'line_height' 	=> 'osf_footer_bar_typo_line_height',
				'spacing' 		=> 'osf_footer_bar_typo_spacing'
			),
			'priority' 				=> 10,
			'l10n'        			=> array(),
		) ) );

		/**
		 * Heading Styling
		 */
		$wp_customize->add_setting( 'osf_footer_bar_styling_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osf_footer_bar_styling_heading', array(
			'label'    		=> esc_html__( 'Styling', 'ocean-sticky-footer' ),
			'section'  		=> 'osf_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Footer Bar Background Color
		 */
		$wp_customize->add_setting( 'osf_footer_bar_background', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#131313',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_footer_bar_background', array(
			'label'	   				=> esc_html__( 'Background Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_footer_bar_background',
			'priority' 				=> 10,
		) ) );

		/**
		 * Heading Styling
		 */
		$wp_customize->add_setting( 'osf_opening_btn_styling_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osf_opening_btn_styling_heading', array(
			'label'    		=> esc_html__( 'Opening Button Styling', 'ocean-sticky-footer' ),
			'section'  		=> 'osf_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Opening Button Background Color
		 */
		$wp_customize->add_setting( 'osf_opening_btn_background', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_opening_btn_background', array(
			'label'	   				=> esc_html__( 'Background Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_opening_btn_background',
			'priority' 				=> 10,
		) ) );

		/**
		 * Opening Button Hover Background Color
		 */
		$wp_customize->add_setting( 'osf_opening_btn_hover_background', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#333333',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_opening_btn_hover_background', array(
			'label'	   				=> esc_html__( 'Hover Background Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_opening_btn_hover_background',
			'priority' 				=> 10,
		) ) );

		/**
		 * Opening Button Color
		 */
		$wp_customize->add_setting( 'osf_opening_btn_color', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_opening_btn_color', array(
			'label'	   				=> esc_html__( 'Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_opening_btn_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Opening Button Hover Color
		 */
		$wp_customize->add_setting( 'osf_opening_btn_hover_color', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_opening_btn_hover_color', array(
			'label'	   				=> esc_html__( 'Hover Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_opening_btn_hover_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Heading Styling
		 */
		$wp_customize->add_setting( 'osf_menu_items_styling_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osf_menu_items_styling_heading', array(
			'label'    		=> esc_html__( 'Menu Items Styling', 'ocean-sticky-footer' ),
			'section'  		=> 'osf_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Menu Items Background Color
		 */
		$wp_customize->add_setting( 'osf_menu_items_background', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_menu_items_background', array(
			'label'	   				=> esc_html__( 'Background Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_menu_items_background',
			'priority' 				=> 10,
		) ) );

		/**
		 * Menu Items Hover Background Color
		 */
		$wp_customize->add_setting( 'osf_menu_items_hover_background', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#333333',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_menu_items_hover_background', array(
			'label'	   				=> esc_html__( 'Hover Background Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_menu_items_hover_background',
			'priority' 				=> 10,
		) ) );

		/**
		 * Menu Items Color
		 */
		$wp_customize->add_setting( 'osf_menu_items_color', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#a9a9a9',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_menu_items_color', array(
			'label'	   				=> esc_html__( 'Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_menu_items_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Menu Items Hover Color
		 */
		$wp_customize->add_setting( 'osf_menu_items_hover_color', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_menu_items_hover_color', array(
			'label'	   				=> esc_html__( 'Hover Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_menu_items_hover_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Heading Styling
		 */
		$wp_customize->add_setting( 'osf_text_styling_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osf_text_styling_heading', array(
			'label'    		=> esc_html__( 'Text Styling', 'ocean-sticky-footer' ),
			'section'  		=> 'osf_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Text Color
		 */
		$wp_customize->add_setting( 'osf_text_color', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#a9a9a9',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_text_color', array(
			'label'	   				=> esc_html__( 'Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_text_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Heading Styling
		 */
		$wp_customize->add_setting( 'osf_scroll_top_styling_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'osf_scroll_top_styling_heading', array(
			'label'    		=> esc_html__( 'Scroll Top Styling', 'ocean-sticky-footer' ),
			'section'  		=> 'osf_section',
			'priority' 		=> 10,
		) ) );

		/**
		 * Scroll Top Background Color
		 */
		$wp_customize->add_setting( 'osf_scroll_top_background', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_scroll_top_background', array(
			'label'	   				=> esc_html__( 'Background Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_scroll_top_background',
			'priority' 				=> 10,
		) ) );

		/**
		 * Scroll Top Hover Background Color
		 */
		$wp_customize->add_setting( 'osf_scroll_top_hover_background', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#333333',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_scroll_top_hover_background', array(
			'label'	   				=> esc_html__( 'Hover Background Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_scroll_top_hover_background',
			'priority' 				=> 10,
		) ) );

		/**
		 * Scroll Top Color
		 */
		$wp_customize->add_setting( 'osf_scroll_top_color', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_scroll_top_color', array(
			'label'	   				=> esc_html__( 'Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_scroll_top_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Scroll Top Hover Color
		 */
		$wp_customize->add_setting( 'osf_scroll_top_hover_color', array(
			'transport' 			=> 'postMessage',
			'default'				=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'osf_scroll_top_hover_color', array(
			'label'	   				=> esc_html__( 'Hover Color', 'ocean-sticky-footer' ),
			'section'  				=> 'osf_section',
			'settings' 				=> 'osf_scroll_top_hover_color',
			'priority' 				=> 10,
		) ) );
	}

	/**
	 * Enqueue scripts.
	 */
	public function osf_scripts() {

		// Load custom js methods.
		wp_enqueue_script( 'nicescroll' );
		wp_enqueue_script( 'osf-main-js', plugins_url( '/assets/js/main.min.js', __FILE__ ), array( 'jquery', 'oceanwp-main' ), null, true );

		// Load main stylesheet
		wp_enqueue_style( 'osf-style', plugins_url( '/assets/css/style.min.css', __FILE__ ) );

		// If rtl
		if ( is_RTL() ) {
			wp_enqueue_style( 'osf-style-rtl', plugins_url( '/assets/css/rtl.css', __FILE__ ) );
		}

		// Get fonts
		$fonts 	= array();
		$val 	= get_theme_mod( 'osf_footer_bar_typo_font_family' );

		// If there is a value lets do something
		if ( ! empty( $val ) ) {

			// Sanitize
			$val = str_replace( '"', '', $val );

			oceanwp_enqueue_google_font( $val );

		}

	}

	/**
	 * Add classes to body
	 */
	public function osf_body_classes( $classes ) {

		$classes[] = 'osf-footer';

		// If has footer callout
		if ( class_exists( 'Ocean_Footer_Callout' ) ) {
			$classes[] = 'has-footer-callout';
		}


		// Return classes
		return $classes;

	}

	/**
	 * Gets the sticky footer bar template part.
	 */
	public function osf_sticky_footer_bar() {

		$file 		= $this->plugin_path . 'template/sticky-footer-bar.php';
		$theme_file = get_stylesheet_directory() . '/templates/extra/sticky-footer-bar.php';

		if ( file_exists( $theme_file ) ) {
			$file = $theme_file;
		}

		if ( file_exists( $file ) ) {
			include $file;
		}

	}

	/**
	 * Add css in head tag.
	 */
	public function osf_head_css( $output ) {

		// Global vars
		$footer_opacity					= get_theme_mod( 'osf_footer_opacity', '0.9' );
		$footer_bar_bg					= get_theme_mod( 'osf_footer_bar_background', '#131313' );
		$opening_btn_bg					= get_theme_mod( 'osf_opening_btn_background' );
		$opening_btn_hover_bg			= get_theme_mod( 'osf_opening_btn_hover_background', '#333333' );
		$opening_btn_color				= get_theme_mod( 'osf_opening_btn_color', '#ffffff' );
		$opening_btn_hover_color		= get_theme_mod( 'osf_opening_btn_hover_color', '#ffffff' );
		$menu_items_background			= get_theme_mod( 'osf_menu_items_background' );
		$menu_items_hover_background	= get_theme_mod( 'osf_menu_items_hover_background', '#333333' );
		$menu_items_color				= get_theme_mod( 'osf_menu_items_color', '#a9a9a9' );
		$menu_items_hover_color			= get_theme_mod( 'osf_menu_items_hover_color', '#ffffff' );
		$text_color						= get_theme_mod( 'osf_text_color', '#a9a9a9' );
		$scroll_top_background			= get_theme_mod( 'osf_scroll_top_background' );
		$scroll_top_hover_background	= get_theme_mod( 'osf_scroll_top_hover_background', '#333333' );
		$scroll_top_color				= get_theme_mod( 'osf_scroll_top_color', '#ffffff' );
		$scroll_top_hover_color			= get_theme_mod( 'osf_scroll_top_hover_color', '#ffffff' );

		// Typography
		$font_family 					= get_theme_mod( 'osf_footer_bar_typo_font_family' );
		$font_size 						= get_theme_mod( 'osf_footer_bar_typo_font_size' );
		$font_weight 					= get_theme_mod( 'osf_footer_bar_typo_font_weight' );
		$font_style 					= get_theme_mod( 'osf_footer_bar_typo_font_style' );
		$text_transform 				= get_theme_mod( 'osf_footer_bar_typo_transform' );
		$line_height 					= get_theme_mod( 'osf_footer_bar_typo_line_height' );
		$letter_spacing 				= get_theme_mod( 'osf_footer_bar_typo_spacing' );

		// Define css var
		$css = '';
		$typo_css = '';

		// CSS if boxed style
		$boxed_style 		= get_theme_mod( 'ocean_main_layout_style', 'wide' );
		$boxed_width 		= get_theme_mod( 'ocean_boxed_width', '1280' );
		$half_boxed_width 	= $boxed_width/2;
		if ( 'boxed' == $boxed_style ) {
			$css .= '.osf-footer .site-footer{width:'. $boxed_width .'px;left:50%;margin-left:-'. $half_boxed_width .'px}';
		}

		// Add footer opacity
		if ( ! empty( $footer_opacity ) && '0.9' != $footer_opacity ) {
			$css .= '.osf-footer .site-footer{opacity:'. $footer_opacity .';}';
		}

		// Add footer bar background
		if ( ! empty( $footer_bar_bg ) && '#131313' != $footer_bar_bg ) {
			$css .= '#footer-bar{background-color:'. $footer_bar_bg .';}';
		}

		// Add opening button background
		if ( ! empty( $opening_btn_bg ) ) {
			$css .= '#footer-bar .osf-left li.osf-btn a{background-color:'. $opening_btn_bg .';}';
		}

		// Add opening button hover background
		if ( ! empty( $opening_btn_hover_bg ) && '#333333' != $opening_btn_hover_bg ) {
			$css .= '#footer-bar .osf-left li.osf-btn a:hover{background-color:'. $opening_btn_hover_bg .';}';
		}

		// Add opening button color
		if ( ! empty( $opening_btn_color ) && '#ffffff' != $opening_btn_color ) {
			$css .= '#footer-bar .osf-left li.osf-btn a{color:'. $opening_btn_color .';}';
		}

		// Add opening button hover color
		if ( ! empty( $opening_btn_hover_color ) && '#ffffff' != $opening_btn_hover_color ) {
			$css .= '#footer-bar .osf-left li.osf-btn a:hover{color:'. $opening_btn_hover_color .';}';
		}

		// Add menu items background
		if ( ! empty( $menu_items_background ) ) {
			$css .= '#footer-bar .osf-left li.menu-item a{background-color:'. $menu_items_background .';}';
		}

		// Add menu items hover background
		if ( ! empty( $menu_items_hover_background ) && '#333333' != $menu_items_hover_background ) {
			$css .= '#footer-bar .osf-left li.menu-item a:hover{background-color:'. $menu_items_hover_background .';}';
		}

		// Add menu items color
		if ( ! empty( $menu_items_color ) && '#a9a9a9' != $menu_items_color ) {
			$css .= '#footer-bar .osf-left li.menu-item a{color:'. $menu_items_color .';}';
		}

		// Add menu items hover color
		if ( ! empty( $menu_items_hover_color ) && '#ffffff' != $menu_items_hover_color ) {
			$css .= '#footer-bar .osf-left li.menu-item a:hover{color:'. $menu_items_hover_color .';}';
		}

		// Add text color
		if ( ! empty( $text_color ) && '#a9a9a9' != $text_color ) {
			$css .= '#footer-bar .osf-text{color:'. $text_color .';}';
		}

		// Add scroll top background
		if ( ! empty( $scroll_top_background ) ) {
			$css .= '#footer-bar .osf-right li #scroll-top{background-color:'. $scroll_top_background .';}';
		}

		// Add scroll top hover background
		if ( ! empty( $scroll_top_hover_background ) && '#333333' != $scroll_top_hover_background ) {
			$css .= '#footer-bar .osf-right li #scroll-top:hover{background-color:'. $scroll_top_hover_background .';}';
		}

		// Add scroll top color
		if ( ! empty( $scroll_top_color ) && '#ffffff' != $scroll_top_color ) {
			$css .= '#footer-bar .osf-right li #scroll-top{color:'. $scroll_top_color .';}';
		}

		// Add scroll top hover color
		if ( ! empty( $scroll_top_hover_color ) && '#ffffff' != $scroll_top_hover_color ) {
			$css .= '#footer-bar .osf-right li #scroll-top:hover{color:'. $scroll_top_hover_color .';}';
		}

		// Add font family
		if ( ! empty( $font_family ) ) {
			$typo_css .= 'font-family:'. $font_family .';';
		}

		// Add font size
		if ( ! empty( $font_size ) ) {
			$typo_css .= 'font-size:'. $font_size .';';
		}

		// Add font weight
		if ( ! empty( $font_weight ) ) {
			$typo_css .= 'font-weight:'. $font_weight .';';
		}

		// Add font style
		if ( ! empty( $font_style ) ) {
			$typo_css .= 'font-style:'. $font_style .';';
		}

		// Add text transform
		if ( ! empty( $text_transform ) ) {
			$typo_css .= 'text-transform:'. $text_transform .';';
		}

		// Add line height
		if ( ! empty( $line_height ) ) {
			$typo_css .= 'line-height:'. $line_height .';';
		}

		// Add letter spacing
		if ( ! empty( $letter_spacing ) ) {
			$typo_css .= 'letter-spacing:'. $letter_spacing .';';
		}

		// Typography css
		if ( ! empty( $typo_css ) ) {
			$css .= '#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text{'. $typo_css .'}';
		}

		// Return CSS
		if ( ! empty( $css ) ) {
			$output .= '/* Sticky Footer CSS */'. $css;
		}

		// Return output css
		return $output;

	}

} // End Class