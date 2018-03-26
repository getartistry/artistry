<?php
/**
 * Importer function
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The importer class.
 */
class OPD_Importer {

	/**
	 * Class Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Return if not in admin
		if ( ! is_admin() || is_customize_preview() ) {
			return;
		}

		// Disable Woo Wizard
		add_filter( 'woocommerce_enable_setup_wizard', false );

		// Start things
		add_action( 'admin_init', array( $this, 'init' ) );

	}

	/**
	 * Register the AJAX method
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'wp_ajax_oceanwp_pro_demos_data', array( $this, 'importer' ) );		
	}

	/**
	 * Return data
	 *
	 * @since 1.0.0
	 */
	public static function get_data() {

		// Demos url
		$url = OPD_URL . '/demos/';

		$data = array(

			'construction' => array(
				'categories'        => array( 'Corporate' ),
				'xml_file'     		=> $url . 'construction/sample-data.xml',
				'theme_settings' 	=> $url . 'construction/oceanwp-export.json',
				'widgets_file'  	=> $url . 'construction/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '3',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'coffee' => array(
				'categories'        => array( 'Corporate' ),
				'xml_file'     		=> $url . 'coffee/sample-data.xml',
				'theme_settings' 	=> $url . 'coffee/oceanwp-export.json',
				'widgets_file'  	=> $url . 'coffee/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '3',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'hosting' => array(
				'categories'        => array( 'Corporate' ),
				'xml_file'     		=> $url . 'hosting/sample-data.xml',
				'theme_settings' 	=> $url . 'hosting/oceanwp-export.json',
				'widgets_file'  	=> $url . 'hosting/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '3',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'medical' => array(
				'categories'        => array( 'Corporate' ),
				'xml_file'     		=> $url . 'medical/sample-data.xml',
				'theme_settings' 	=> $url . 'medical/oceanwp-export.json',
				'widgets_file'  	=> $url . 'medical/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '3',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'photography' => array(
				'categories'        => array( 'Corporate' ),
				'xml_file'     		=> $url . 'photography/sample-data.xml',
				'theme_settings' 	=> $url . 'photography/oceanwp-export.json',
				'widgets_file'  	=> $url . 'photography/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '6',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'wedding' => array(
				'categories'        => array( 'Corporate' ),
				'xml_file'     		=> $url . 'wedding/sample-data.xml',
				'theme_settings' 	=> $url . 'wedding/oceanwp-export.json',
				'widgets_file'  	=> $url . 'wedding/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '5',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'spa' => array(
				'categories'        => array( 'One Page' ),
				'xml_file'     		=> $url . 'spa/sample-data.xml',
				'theme_settings' 	=> $url . 'spa/oceanwp-export.json',
				'widgets_file'  	=> $url . 'spa/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'restaurant' => array(
				'categories'        => array( 'One Page' ),
				'xml_file'     		=> $url . 'restaurant/sample-data.xml',
				'theme_settings' 	=> $url . 'restaurant/oceanwp-export.json',
				'widgets_file'  	=> $url . 'restaurant/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'chocolate' => array(
				'categories'        => array( 'One Page' ),
				'xml_file'     		=> $url . 'chocolate/sample-data.xml',
				'theme_settings' 	=> $url . 'chocolate/oceanwp-export.json',
				'widgets_file'  	=> $url . 'chocolate/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'hotel' => array(
				'categories'        => array( 'One Page' ),
				'xml_file'     		=> $url . 'hotel/sample-data.xml',
				'theme_settings' 	=> $url . 'hotel/oceanwp-export.json',
				'widgets_file'  	=> $url . 'hotel/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),
			
			'jewelry' => array(
				'categories'        => array( 'eCommerce' ),
				'xml_file'     		=> $url . 'jewelry/sample-data.xml',
				'theme_settings' 	=> $url . 'jewelry/oceanwp-export.json',
				'widgets_file'  	=> $url . 'jewelry/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '3',
				'elementor_width'  	=> '1260',
				'is_shop'  			=> true,
			),
			
			'shoes' => array(
				'categories'        => array( 'eCommerce' ),
				'xml_file'     		=> $url . 'shoes/sample-data.xml',
				'theme_settings' 	=> $url . 'shoes/oceanwp-export.json',
				'widgets_file'  	=> $url . 'shoes/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1320',
				'is_shop'  			=> true,
			),
			
			'flowers' => array(
				'categories'        => array( 'eCommerce' ),
				'xml_file'     		=> $url . 'flowers/sample-data.xml',
				'theme_settings' 	=> $url . 'flowers/oceanwp-export.json',
				'widgets_file'  	=> $url . 'flowers/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '3',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> true,
			),
			
			'garden' => array(
				'categories'        => array( 'eCommerce' ),
				'xml_file'     		=> $url . 'garden/sample-data.xml',
				'theme_settings' 	=> $url . 'garden/oceanwp-export.json',
				'widgets_file'  	=> $url . 'garden/widgets.wie',
				'home_title'  		=> 'Home',
				'blog_title'  		=> 'Blog',
				'posts_to_show'  	=> '3',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> true,
			),

			'electronic' => array(
				'categories'        => array( 'Coming Soon' ),
				'xml_file'     		=> $url . 'electronic/sample-data.xml',
				'theme_settings' 	=> $url . 'electronic/oceanwp-export.json',
				'widgets_file'  	=> $url . 'electronic/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'fashion' => array(
				'categories'        => array( 'Coming Soon' ),
				'xml_file'     		=> $url . 'fashion/sample-data.xml',
				'theme_settings' 	=> $url . 'fashion/oceanwp-export.json',
				'widgets_file'  	=> $url . 'fashion/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'food' => array(
				'categories'        => array( 'Coming Soon' ),
				'xml_file'     		=> $url . 'food/sample-data.xml',
				'theme_settings' 	=> $url . 'food/oceanwp-export.json',
				'widgets_file'  	=> $url . 'food/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'gaming' => array(
				'categories'        => array( 'Coming Soon' ),
				'xml_file'     		=> $url . 'gaming/sample-data.xml',
				'theme_settings' 	=> $url . 'gaming/oceanwp-export.json',
				'widgets_file'  	=> $url . 'gaming/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

			'pink' => array(
				'categories'        => array( 'Coming Soon' ),
				'xml_file'     		=> $url . 'pink/sample-data.xml',
				'theme_settings' 	=> $url . 'pink/oceanwp-export.json',
				'widgets_file'  	=> $url . 'pink/widgets.wie',
				'home_title'  		=> 'Home',
				'elementor_width'  	=> '1220',
				'is_shop'  			=> false,
			),

		);

		// Return
		return $data;

	}

	/**
	 * Get the category list of all categories used in the predefined demo imports array.
	 *
	 * @since 1.0.0
	 */
	public static function get_all_categories( $demo_imports ) {
		$categories = array();

		foreach ( $demo_imports as $item ) {
			if ( ! empty( $item['categories'] ) && is_array( $item['categories'] ) ) {
				foreach ( $item['categories'] as $category ) {
					$categories[ sanitize_key( $category ) ] = $category;
				}
			}
		}

		if ( empty( $categories ) ) {
			return false;
		}

		return $categories;
	}

	/**
	 * Return the concatenated string of demo import item categories.
	 * These should be separated by comma and sanitized properly.
	 *
	 * @since 1.0.0
	 */
	public static function get_item_categories( $item ) {
		$sanitized_categories = array();

		if ( isset( $item['categories'] ) ) {
			foreach ( $item['categories'] as $category ) {
				$sanitized_categories[] = sanitize_key( $category );
			}
		}

		if ( ! empty( $sanitized_categories ) ) {
			return implode( ',', $sanitized_categories );
		}

		return false;
	}

	/**
	 * Importer
	 *
	 * @since 1.0.0
	 */
	public function importer() {

		// Include settings importer
		include OPD_PATH . 'includes/class/class-settings-importer.php';

		// Include widgets importer
		include OPD_PATH . 'includes/class/class-widget-importer.php';

		if ( current_user_can( 'manage_options' ) ) {

			if ( ! isset( $_POST['demo_type'] ) || '' == trim( $_POST['demo_type'] ) ) {
				$demo_type = 'construction';
			} else {
				$demo_type = $_POST['demo_type'];
			}

			// Get demo data
			$demo = self::get_data()[ $demo_type ];

			// Content file
			$xml_file 			= isset( $demo['xml_file'] ) ? $demo['xml_file'] : '';

			// Settings file
			$theme_settings 	= isset( $demo['theme_settings'] ) ? $demo['theme_settings'] : '';

			// Widgets file
			$widgets_file 		= isset( $demo['widgets_file'] ) ? $demo['widgets_file'] : '';

			// Elementor width setting
			if ( $demo['elementor_width'] ) {
				$elementor_width = isset( $demo['elementor_width'] ) ? $demo['elementor_width'] : '';
			}

			// Reading settings
			$homepage_title 	= isset( $demo['home_title'] ) ? $demo['home_title'] : 'Home';
			$blog_title 		= isset( $demo['blog_title'] ) ? $demo['blog_title'] : '';

			// Posts to show on the blog page
			$posts_to_show 		= isset( $demo['posts_to_show'] ) ? $demo['posts_to_show'] : '';

			// If shop demo
			$shop_demo 			= isset( $demo['is_shop'] ) ? $demo['is_shop'] : false;

			// Import Posts, Pages, Images, Menus.
			$this->process_xml( $xml_file );

			// Assign WooCommerce pages if WooCommerce Exists
			if ( class_exists( 'WooCommerce' ) && $shop_demo ) {

				$woopages = array(
					'woocommerce_shop_page_id' 				=> 'Shop',
					'woocommerce_cart_page_id' 				=> 'Cart',
					'woocommerce_checkout_page_id' 			=> 'Checkout',
					'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
					'woocommerce_thanks_page_id' 			=> 'Order Received',
					'woocommerce_myaccount_page_id' 		=> 'My Account',
					'woocommerce_edit_address_page_id' 		=> 'Edit My Address',
					'woocommerce_view_order_page_id' 		=> 'View Order',
					'woocommerce_change_password_page_id' 	=> 'Change Password',
					'woocommerce_logout_page_id' 			=> 'Logout',
					'woocommerce_lost_password_page_id' 	=> 'Lost Password'
				);

				foreach ( $woopages as $woo_page_name => $woo_page_title ) {

					$woopage = get_page_by_title( $woo_page_title );
					if ( isset( $woopage ) && $woopage->ID ) {
						update_option( $woo_page_name, $woopage->ID );
					}

				}

				// We no longer need to install pages
				delete_option( '_wc_needs_pages' );
				delete_transient( '_wc_activation_redirect' );

			}

			// Import settings
			if ( $theme_settings ) {
				$settings_importer = new OPD_Settings_Importer();
				$settings_importer->process_import_file( $theme_settings );
			}

			// Import widgets
			if ( $widgets_file ) {
				$widgets_importer = new OPD_Widget_Importer();
				$widgets_importer->process_import_file( $widgets_file );
			}

			// Set imported menus to registered theme locations
			$locations 	= get_theme_mod( 'nav_menu_locations' );
			$menus 		= wp_get_nav_menus();

			if ( $menus ) {
				
				foreach ( $menus as $menu ) {

					if ( $menu->name == 'Main Menu' ) {
						$locations['main_menu'] = $menu->term_id;
					} else if ( $menu->name == 'Top Menu' ) {
						$locations['topbar_menu'] = $menu->term_id;
					} else if ( $menu->name == 'Footer Menu' ) {
						$locations['footer_menu'] = $menu->term_id;
					} else if ( $menu->name == 'Sticky Footer' ) {
						$locations['sticky_footer_menu'] = $menu->term_id;
					}

				}

			}

			// Set menus to locations
			set_theme_mod( 'nav_menu_locations', $locations );

			// Disable Elementor default settings
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			if ( $elementor_width ) {
				update_option( 'elementor_container_width', $elementor_width );
			}

			// Assign front page and posts page (blog page).
		    $home_page 	= get_page_by_title( $homepage_title );
		    if ( $blog_page ) {
		    	$blog_page 	= get_page_by_title( $blog_title );
		    }

		    update_option( 'show_on_front', 'page' );

		    if ( isset( $home_page ) && $home_page->ID ) {
				update_option( 'page_on_front', $home_page->ID );
			}

			if ( isset( $blog_page ) && $blog_page->ID ) {
				update_option( 'page_for_posts', $blog_page->ID );
			}

			// Posts to show on the blog page
			if ( $posts_to_show ) {
				update_option( 'posts_per_page', $posts_to_show );
			}

			echo 'imported';

			exit;

		}

	}

	/**
	 * Import XML data
	 *
	 * @since 1.0.0
	 */
	public function process_xml( $file ) {
		
		$response = OPD_Helpers::get_remote( $file );

		// No sample data found
		if ( $response === false ) {
			return new WP_Error( 'xml_import_error', __( 'Can not retrieve sample data xml file. Github may be down at the momment please try again later. If you still have issues contact the theme developer for assistance.', 'ocean-pro-demos' ) );
		}

		// Write sample data content to temp xml file
		$temp_xml = OPD_PATH .'includes/temp.xml';
		file_put_contents( $temp_xml, $response );

		// Set temp xml to attachment url for use
		$attachment_url = $temp_xml;

		// If file exists lets import it
		if ( file_exists( $attachment_url ) ) {
			$this->import_xml( $attachment_url );
		} else {
			// Import file can't be imported - we should die here since this is core for most people.
			return new WP_Error( 'xml_import_error', __( 'The xml import file could not be accessed. Please try again or contact the theme developer.', 'ocean-pro-demos' ) );
		}

	}
	
	/**
	 * Import XML file
	 *
	 * @since 1.0.0
	 */
	private function import_xml( $file ) {

		// Make sure importers constant is defined
		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		// Import file location
		$import_file = ABSPATH . 'wp-admin/includes/import.php';

		// Include import file
		if ( ! file_exists( $import_file ) ) {
			return;
		}

		// Include import file
		require_once( $import_file );

		// Define error var
		$importer_error = false;

		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

			if ( file_exists( $class_wp_importer ) ) {
				require_once $class_wp_importer;
			} else {
				$importer_error = __( 'Can not retrieve class-wp-importer.php', 'ocean-pro-demos' );
			}
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			$class_wp_import = OPD_PATH . 'includes/class/class-wordpress-importer.php';

			if ( file_exists( $class_wp_import ) ) {
				require_once $class_wp_import;
			} else {
				$importer_error = __( 'Can not retrieve wordpress-importer.php', 'ocean-pro-demos' );
			}
		}

		// Display error
		if ( $importer_error ) {
			return new WP_Error( 'xml_import_error', $importer_error );
		} else {

			// No error, lets import things...
			if ( ! is_file( $file ) ) {
				$importer_error = __( 'Sample data file appears corrupt or can not be accessed.', 'ocean-pro-demos' );
				return new WP_Error( 'xml_import_error', $importer_error );
			} else {
				$importer = new WP_Import();
				$importer->fetch_attachments = true;
				$importer->import( $file );

				// Clear sample data content from temp xml file
				$temp_xml = OPD_PATH .'includes/temp.xml';
				file_put_contents( $temp_xml, '' );
			}
		}
	}

}
new OPD_Importer();