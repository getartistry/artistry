<?php
/**
 * Plugin Name:			Ocean Portfolio
 * Plugin URI:			https://oceanwp.org/extension/ocean-portfolio/
 * Description:			A complete extension to display your portfolio and work in a beautiful way.
 * Version:				1.0.8
 * Author:				OceanWP
 * Author URI:			https://oceanwp.org/
 * Requires at least:	4.5.0
 * Tested up to:		4.9.1
 *
 * Text Domain: ocean-portfolio
 * Domain Path: /languages/
 *
 * @package Ocean_Portfolio
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Portfolio to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Portfolio
 */
function Ocean_Portfolio() {
	return Ocean_Portfolio::instance();
} // End Ocean_Portfolio()

Ocean_Portfolio();

/**
 * Main Ocean_Portfolio Class
 *
 * @class Ocean_Portfolio
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Portfolio
 */
final class Ocean_Portfolio {
	/**
	 * Ocean_Portfolio The single instance of Ocean_Portfolio.
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
		$this->token 			= 'ocean-portfolio';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.8';
		$theme 					= wp_get_theme();

		define( 'OP_URL', $this->plugin_url );
		define( 'OP_PATH', $this->plugin_path );
		define( 'OP_VERSION', $this->version );

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'init', array( $this, 'updater' ), 1 );

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			require_once( OP_PATH .'/includes/page-template.php' );
			require_once( OP_PATH .'/includes/admin/class-register-cpt.php' );
			add_action( 'widgets_init', array( $this, 'register_sidebar' ), 11 );
			add_filter( 'ocean_get_sidebar', array( $this, 'display_sidebar' ) );
		}
	}

	/**
	 * Initialize License Updater.
	 * Load Updater initialize.
	 * @return void
	 */
	public function updater() {

		// Plugin Updater Code
		if( class_exists( 'OceanWP_Plugin_Updater' ) ) {
			$license	= new OceanWP_Plugin_Updater( __FILE__, 'Portfolio', $this->version, 'OceanWP' );
		}
	}

	/**
	 * Main Ocean_Portfolio Instance
	 *
	 * Ensures only one instance of Ocean_Portfolio is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Portfolio()
	 * @return Main Ocean_Portfolio instance
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
		load_plugin_textdomain( 'ocean-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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

			require_once( OP_PATH .'/includes/helpers.php' );
			require_once( OP_PATH .'/includes/customizer/class-customizer-settings.php' );
			require_once( OP_PATH .'/includes/admin/class-shortcode-generator.php' );
			require_once( OP_PATH .'/includes/class-portfolio-shortcode.php' );
			if ( current_user_can( $capabilities ) ) {
				add_action( 'butterbean_register', array( $this, 'new_field' ), 10, 2 );
			}
			add_filter( 'template_include', array( $this, 'portfolio_template' ), 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_fonts' ) );
			add_filter( 'ocean_post_layout_class', array( $this, 'layout' ) );
			add_filter( 'ocean_both_sidebars_style', array( $this, 'bs_class' ) );
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
			add_filter( 'ocean_page_header_style', array( $this, 'page_header_style' ) );
			add_filter( 'ocean_page_header_background_image', array( $this, 'page_header_image' ) );
			add_filter( 'ocean_post_title_bg_image_position', array( $this, 'page_header_image_position' ) );
			add_filter( 'ocean_post_title_bg_image_attachment', array( $this, 'page_header_image_attachment' ) );
			add_filter( 'ocean_post_title_bg_image_repeat', array( $this, 'page_header_image_repeat' ) );
			add_filter( 'ocean_post_title_bg_image_size', array( $this, 'page_header_image_size' ) );
			add_filter( 'ocean_post_title_height', array( $this, 'page_header_height' ) );
			add_filter( 'ocean_post_title_bg_overlay', array( $this, 'page_header_overlay' ) );
			add_filter( 'ocean_post_title_bg_overlay_color', array( $this, 'page_header_overlay_color' ) );
			add_filter( 'breadcrumb_trail_post_taxonomy', array( $this, 'breadcrumb_trail_post_taxonomy' ) );
			add_filter( 'post_type_archive_url', array( $this, 'breadcrumb_post_type_archive_url' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );
		}
	}

	/**
	 * Add new field in metabox.
	 *
	 * @since  1.0.8
	 */
	public function new_field( $butterbean, $post_type ) {

		// Return if it is not Portfolio post type
		if ( 'ocean_portfolio' != $post_type ) {
			return;
		}

		// Gets the manager object we want to add sections to.
		$manager = $butterbean->get_manager( 'oceanwp_mb_settings' );

	    $manager->register_control(
	        'op_external_url', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_main',
	            'type'    		=> 'text',
	            'label'   		=> esc_html__( 'External URL', 'ocean-portfolio' ),
	            'description'   => esc_html__( 'Add your external URL for this portfolio item.', 'ocean-portfolio' ),
	        )
	    );
		
		$manager->register_setting(
	        'op_external_url', // Same as control name.
	        array(
	            'sanitize_callback' => 'sanitize_text_field',
	        )
	    );

	    $manager->register_control(
	        'op_external_url_target', // Same as setting name.
	        array(
	            'section' 		=> 'oceanwp_mb_main',
	            'type'    		=> 'buttonset',
	            'label'   		=> esc_html__( 'External URL Target', 'ocean-portfolio' ),
	            'description'   => esc_html__( 'Choose your target for your external URL.', 'ocean-portfolio' ),
				'choices' 		=> array(
					'self' 		=> esc_html__( 'Self', 'ocean-portfolio' ),
					'blank' 	=> esc_html__( 'Blank', 'ocean-portfolio' ),
				),
	        )
	    );
		
		$manager->register_setting(
	        'op_external_url_target', // Same as control name.
	        array(
	            'sanitize_callback' => 'sanitize_text_field',
	            'default' 			=> 'self',
	        )
	    );

	}

	/**
	 * Loads Google fonts
	 *
	 * @since  1.0.0
	 */
	public static function load_fonts() {

		$settings = array(
			'op_portfolio_filter_typo_font_family',
			'op_portfolio_title_typo_font_family',
			'op_portfolio_category_typo_font_family',
		);

		$query = new WP_Query( array( 'post_type' => 'portfolio_shortcodes' ) );

		if ( $query->have_posts() ) :

			while ( $query->have_posts() ) : $query->the_post();

				foreach ( $settings as $setting ) {

			    	// Get fonts
					$fonts 	= array();
					$val 	= get_theme_mod( $setting );
					if ( $meta = get_post_meta( get_the_ID(), $setting, true ) ) {
						$val = $meta;
					}

					// If there is a value lets do something
					if ( ! empty( $val ) ) {

						// Sanitize
						$val = str_replace( '"', '', $val );

						$fonts[] = $val;

					}

					// Loop through and enqueue fonts
					if ( ! empty( $fonts ) && is_array( $fonts ) ) {
						foreach ( $fonts as $font ) {
							oceanwp_enqueue_google_font( $font );
						}
					}

				}

			endwhile;

			wp_reset_postdata();

		endif;

	}

	/**
	 * Add the portfolio template
	 *
	 * @since 1.0.0
	 */
	public static function portfolio_template( $template_path ) {

	    if ( 'ocean_portfolio' == get_post_type() ) {

			$theme_file = get_stylesheet_directory() . '/templates/portfolio-template.php';

			/**
			 * Checks if the file exists in the theme first
			 * Otherwise serve the file from the plugin
			 */
			if ( file_exists( $theme_file ) ) {
				$template_path = $theme_file;
			} else {
                $template_path = OP_PATH . '/portfolio-template.php';
            }

	    }

	    // Return
	    return $template_path;

	}

	/**
	 * Registers portfolio sidebar
	 *
	 * @since 1.0.0
	 */
	public static function register_sidebar() {

		register_sidebar( array(
			'name'			=> esc_html__( 'Portfolio Sidebar', 'ocean-portfolio' ),
			'id'			=> 'portfolio-sidebar',
			'description'	=> esc_html__( 'Widgets in this area are used in the portfolio.', 'ocean-portfolio' ),
			'before_widget'	=> '<div class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h4 class="widget-title">',
			'after_title'	=> '</h4>',
		) );

	}

	/**
	 * Display the portfolio sidebar
	 *
	 * @since 1.0.0
	 */
	public static function display_sidebar( $sidebar ) {

		if ( is_singular( 'ocean_portfolio' )
			|| op_portfolio_taxonomy() ) {
			$sidebar = 'portfolio-sidebar';
		}

		// Return
		return $sidebar;

	}

	/**
	 * Add the single portfolio item in full width
	 *
	 * @since 1.0.0
	 */
	public static function layout( $class ) {
		if ( is_singular( 'ocean_portfolio' ) ) {
			$class = get_theme_mod( 'op_portfolio_single_layout', 'full-width' );
		}
		return $class;
	}

	/**
	 * Set correct both sidebars layout style
	 *
	 * @since 1.0.5
	 */
	public static function bs_class( $class ) {
		if ( is_singular( 'ocean_portfolio' ) ) {
			$class = get_theme_mod( 'op_portfolio_single_both_sidebars_style', 'scs-style' );
		}
		return $class;
	}

	/**
	 * Posts per page for portfolio taxonomy
	 *
	 * @since 1.0.0
	 */
	public static function pre_get_posts( $query ) {

		// Main Checks
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		// Alter posts per page
		if ( op_portfolio_taxonomy() ) {
			$query->set( 'posts_per_page', get_theme_mod( 'op_portfolio_posts_per_page', '12' ) );
		}

	}

	/**
	 * Page header style
	 *
	 * @since 1.0.0
	 */
	public static function page_header_style( $style ) {

		// If featured image in page header
		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' )
			&& has_post_thumbnail() ) {
			$style = 'background-image';
		}

		// Return
		return $style;

	}

	/**
	 * Page header image
	 *
	 * @since 1.0.0
	 */
	public static function page_header_image( $bg_img ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' )
			&& has_post_thumbnail() ) {
			$bg_img = get_the_post_thumbnail_url();
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_bg_img = get_post_meta( get_the_ID(), 'ocean_post_title_background', true ) ) {
				$bg_img = $meta_bg_img;
			}
		}

		// Generate image URL if using ID
		if ( is_numeric( $bg_img ) ) {
			$bg_img = wp_get_attachment_image_src( $bg_img, 'full' );
			$bg_img = $bg_img[0];
		}
		
		$bg_img = $bg_img ? $bg_img : null;

		// Retrun
		return $bg_img;

	}

	/**
	 * Page header image position
	 *
	 * @since 1.0.0
	 */
	public static function page_header_image_position( $bg_img_position ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' ) ) {
			$bg_img_position = get_theme_mod( 'op_portfolio_single_title_bg_image_position', 'top center' );
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_bg_img_position = get_post_meta( get_the_ID(), 'ocean_post_title_bg_image_position', true ) ) {
				$bg_img_position = $meta_bg_img_position;
			}
		}

		// Retrun
		return $bg_img_position;

	}

	/**
	 * Page header image attachment
	 *
	 * @since 1.0.0
	 */
	public static function page_header_image_attachment( $bg_img_attachment ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' ) ) {
			$bg_img_attachment = get_theme_mod( 'op_portfolio_single_title_bg_image_attachment', 'initial' );
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_bg_img_attachment = get_post_meta( get_the_ID(), 'ocean_post_title_bg_image_attachment', true ) ) {
				$bg_img_attachment = $meta_bg_img_attachment;
			}
		}

		// Retrun
		return $bg_img_attachment;

	}

	/**
	 * Page header image repeat
	 *
	 * @since 1.0.0
	 */
	public static function page_header_image_repeat( $bg_img_repeat ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' ) ) {
			$bg_img_repeat = get_theme_mod( 'op_portfolio_single_title_bg_image_repeat', 'no-repeat' );
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_bg_img_repeat = get_post_meta( get_the_ID(), 'ocean_post_title_bg_image_repeat', true ) ) {
				$bg_img_repeat = $meta_bg_img_repeat;
			}
		}

		// Retrun
		return $bg_img_repeat;

	}

	/**
	 * Page header image size
	 *
	 * @since 1.0.0
	 */
	public static function page_header_image_size( $bg_img_size ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' ) ) {
			$bg_img_size = get_theme_mod( 'op_portfolio_single_title_bg_image_size', 'cover' );
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_bg_img_size = get_post_meta( get_the_ID(), 'ocean_post_title_bg_image_size', true ) ) {
				$bg_img_size = $meta_bg_img_size;
			}
		}

		// Retrun
		return $bg_img_size;

	}

	/**
	 * Page header height
	 *
	 * @since 1.0.0
	 */
	public static function page_header_height( $title_height ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' ) ) {
			$title_height = get_theme_mod( 'op_portfolio_single_title_bg_image_height', '400' );
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_title_height = get_post_meta( get_the_ID(), 'ocean_post_title_height', true ) ) {
				$title_height = $meta_title_height;
			}
		}

		// Retrun
		return $title_height;

	}

	/**
	 * Page header overlay
	 *
	 * @since 1.0.0
	 */
	public static function page_header_overlay( $overlay ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' ) ) {
			$overlay = get_theme_mod( 'op_portfolio_single_title_bg_image_overlay_opacity', '0.5' );
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_overlay = get_post_meta( get_the_ID(), 'ocean_post_title_bg_overlay', true ) ) {
				$overlay = $meta_overlay;
			}
		}

		// Retrun
		return $overlay;

	}

	/**
	 * Page header overlay color
	 *
	 * @since 1.0.0
	 */
	public static function page_header_overlay_color( $overlay_color ) {

		if ( true == get_theme_mod( 'op_portfolio_single_featured_image_title', false )
			&& is_singular( 'ocean_portfolio' ) ) {
			$overlay_color = get_theme_mod( 'op_portfolio_single_title_bg_image_overlay_color', '#000000' );
		}

		if ( 'background-image' == get_post_meta( get_the_ID(), 'ocean_post_title_style', true ) ) {
			if ( $meta_overlay_color = get_post_meta( get_the_ID(), 'ocean_post_title_bg_overlay_color', true ) ) {
				$overlay_color = $meta_overlay_color;
			}
		}

		// Retrun
		return $overlay_color;

	}

	/**
	 * Add the category term in the single items in the breadcrumb
	 *
	 * @since 1.0.0
	 */
	public static function breadcrumb_trail_post_taxonomy( $defaults ) {

		if ( is_singular( 'ocean_portfolio' ) && '%postname%' === trim( get_option( 'permalink_structure' ), '/' ) ) {
			$defaults['ocean_portfolio'] = 'ocean_portfolio_category';
		}

		return $defaults;

	}

	/**
	 * Add the portfolio page url in the taxonomy page in the breadcrumb
	 *
	 * @since 1.0.0
	 */
	public static function breadcrumb_post_type_archive_url( $url ) {

		if ( op_portfolio_taxonomy() && $page_id = get_theme_mod( 'op_portfolio_page' ) ) {
			$url = get_permalink( $page_id );
		}

		return $url;

	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 */
	public static function scripts() {

		// Load main stylesheet
		wp_enqueue_style( 'op-style', plugins_url( '/assets/css/style.min.css', __FILE__ ) );

		// Load main script
		wp_enqueue_script( 'op-script', plugins_url( '/assets/js/main.min.js', __FILE__ ), array( 'jquery', 'oceanwp-main' ), OP_VERSION, true );

	}

} // End Class