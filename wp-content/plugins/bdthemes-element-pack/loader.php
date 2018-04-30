<?php
namespace ElementPack;

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class for element pack
 */
class Element_Pack_Loader {

	/**
	 * @var Element_Pack_Loader
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	private $classes_aliases = [
		'ElementPack\Modules\PanelPostsControl\Module' => 'ElementPack\Modules\QueryControl\Module',
		'ElementPack\Modules\PanelPostsControl\Controls\Group_Control_Posts' => 'ElementPack\Modules\QueryControl\Controls\Group_Control_Posts',
		'ElementPack\Modules\PanelPostsControl\Controls\Query' => 'ElementPack\Modules\QueryControl\Controls\Query',
	];

	/**
	 * @deprecated
	 *
	 * @return string
	 */
	public function get_version() {
		return BDTEP_VER;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'bdthemes-element-pack' ), '1.6.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'bdthemes-element-pack' ), '1.6.0' );
	}

	/**
	 * @return \Elementor\Element_Pack_Loader
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Element_Pack_Loader
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * we loaded module manager + admin php from here
	 * @return [type] [description]
	 */
	private function _includes() {
		require BDTEP_PATH . 'includes/modules-manager.php';
		if ( is_admin() ) {
			require BDTEP_PATH . 'includes/admin.php';
			// Updater function for update plugin automatically
			require(BDTEP_PATH . 'includes/updater/loader.php');
			// Set up the Update integration
			new \ElementPack\V1\PluginUpdater( 'Element Pack', 'https://bdthemes.co/license/', BDTEP_PNAME, BDTEP__FILE__, BDTEP_VER );
		}

	}

	/**
	 * Autoloader function for all classes files
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = BDTEP_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	/**
	 * Register all script that need for any specific widget on call basis.
	 * @return [type] [description]
	 */
	public function register_site_scripts() {

		$suffix   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$settings = get_option( 'element_pack_api_settings' );

		wp_register_script( 'uikit-icons', BDTEP_URL . 'assets/js/uikit-icons' . $suffix . '.js', ['jquery', 'bdt-uikit'], '3.0.0.40', true );
		wp_register_script( 'goodshare', BDTEP_URL . 'assets/vendor/js/goodshare' . $suffix . '.js', ['jquery'], '4.1.2', true );
		wp_register_script( 'twentytwenty', BDTEP_URL . 'assets/vendor/js/jquery.twentytwenty' . $suffix . '.js', ['jquery'], '0.1.0', true );
		wp_register_script( 'eventmove', BDTEP_URL . 'assets/vendor/js/jquery.event.move' . $suffix . '.js', ['jquery'], '2.0.0', true );
		wp_register_script( 'aspieprogress', BDTEP_URL . 'assets/vendor/js/jquery-asPieProgress' . $suffix . '.js', ['jquery'], '0.4.7', true );
		wp_register_script( 'morphext', BDTEP_URL . 'assets/vendor/js/morphext' . $suffix . '.js', ['jquery'], '2.4.7', true );
		wp_register_script( 'qrcode', BDTEP_URL . 'assets/vendor/js/jquery-qrcode' . $suffix . '.js', ['jquery'], '', true );
		wp_register_script( 'youtube-api', '//www.youtube.com/iframe_api', ['jquery'], null, true );
		wp_register_script( 'isotope', BDTEP_URL . 'assets/vendor/js/isotope.pkgd' . $suffix . '.js', ['jquery'], '', true );
		
		if (!empty($settings['google_map_key'])) {
			wp_register_script( 'gmap-api', '//maps.googleapis.com/maps/api/js?key='.$settings['google_map_key'], ['jquery'], null, true );
		} else {
			wp_register_script( 'gmap-api', '//maps.google.com/maps/api/js?v=3&sensor=false', ['jquery'], null, true );
		}
		wp_register_script( 'gmap', BDTEP_URL . 'assets/vendor/js/gmap' . $suffix . '.js', ['jquery', 'gmap-api'], '', true );
		wp_register_script( 'typed', BDTEP_URL . 'assets/vendor/js/typed' . $suffix . '.js', ['jquery'], '', true );
		wp_register_script( 'tilt', BDTEP_URL . 'assets/vendor/js/tilt.jquery' . $suffix . '.js', ['jquery'], '', true );
		wp_register_script( 'parallax', BDTEP_URL . 'assets/vendor/js/parallax' . $suffix . '.js', ['jquery'], '', true );
		//wp_register_script( 'ep-search', BDTEP_URL . 'assets/vendor/js/search' . $suffix . '.js', ['jquery'], '', true );
		if (!empty($settings['disqus_user_name'])) {
			wp_register_script( 'disqus', '//'.$settings['disqus_user_name'].'.disqus.com/count.js', ['jquery'], null, true );
		}
	}

	public function register_site_styles() {
		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_register_style( 'bdt-social-share', BDTEP_URL . 'assets/css/social-share' . $direction_suffix . '.css', [], BDTEP_VER );
		wp_register_style( 'twentytwenty', BDTEP_URL . 'assets/css/twentytwenty' . $direction_suffix . '.css', [], BDTEP_VER );
		wp_register_style( 'bdt-advanced-button', BDTEP_URL . 'assets/css/advanced-button' . $direction_suffix . '.css', [], BDTEP_VER );
	}

	/**
	 * Loading site related style from here.
	 * @return [type] [description]
	 */
	public function enqueue_site_styles() {

		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_enqueue_style( 'element-pack-site', BDTEP_URL . 'assets/css/element-pack-site' . $direction_suffix . '.css', [], BDTEP_VER );

		
	}


	/**
	 * Loading site related script that needs all time such as uikit.
	 * @return [type] [description]
	 */
	public function enqueue_site_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'bdt-uikit', BDTEP_URL . 'assets/js/bdt-uikit' . $suffix . '.js', ['jquery'], BDTEP_VER );

		$locale_settings = [ 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'element-pack-site' ),
		];

		// localize for user login widget ajax login script
	    wp_localize_script( 'bdt-uikit', 'element_pack_ajax_login_config', array( 
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'loadingmessage' => esc_html__('Sending user info, please wait...', 'bdthemes-element-pack'),
	    ));

	    // TODO for editor script
		wp_localize_script( 'bdt-uikit', 'ElementPackSiteConfig',
			apply_filters( 'element_pack/frontend/localize_settings', $locale_settings )
		);
	}

	/**
	 * Load editor editor related style from here
	 * @return [type] [description]
	 */
	public function enqueue_preview_styles() {
		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_enqueue_style('element-pack-preview', BDTEP_URL . 'assets/css/element-pack-preview' . $direction_suffix . '.css', '', BDTEP_VER );
	}


	/**
	 * Add element_pack_ajax_login() function with wp_ajax_nopriv_ function 
	 * @return [type] [description]
	 */
	public function element_pack_ajax_login_init() {
	    // Enable the user with no privileges to run element_pack_ajax_login() in AJAX
	    add_action( 'wp_ajax_nopriv_element_pack_ajax_login', [ $this, "element_pack_ajax_login"] );
	}

	/**
	 * For ajax login
	 * @return [type] [description]
	 */
	public function element_pack_ajax_login(){
	    // First check the nonce, if it fails the function will break
	    check_ajax_referer( 'ajax-login-nonce', 'security' );

	    // Nonce is checked, get the POST data and sign user on
		$access_info                  = [];
		$access_info['user_login']    = !empty($_POST['username'])?$_POST['username']:"";
		$access_info['user_password'] = !empty($_POST['password'])?$_POST['password']:"";
		$access_info['remember']      = true;
		$user_signon                  = wp_signon( $access_info, false );

	    if ( is_wp_error($user_signon) ){
	        echo json_encode( ['loggedin'=>false, 'message'=> esc_html__('Ops! Wrong username or password!', 'bdthemes-element-pack')] );
	    } else {
	        echo json_encode( ['loggedin'=>true, 'message'=> esc_html__('Login successful, Redirecting...', 'bdthemes-element-pack')] );
	    }

	    die();
	}

	public function element_pack_ajax_search() {
	    global $wp_query;

	    $result = array('results' => array());
	    $query  = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';

	    if (strlen($query) >= 3) {

			$wp_query->query_vars['posts_per_page'] = 5;
			$wp_query->query_vars['post_status']    = 'publish';
			$wp_query->query_vars['s']              = $query;
			$wp_query->is_search                    = true;

	        foreach ($wp_query->get_posts() as $post) {

	            $content = !empty($post->post_excerpt) ? strip_tags(strip_shortcodes($post->post_excerpt)) : strip_tags(strip_shortcodes($post->post_content));

	            if (strlen($content) > 180) {
	                $content = substr($content, 0, 179).'...';
	            }

	            $result['results'][] = array(
	                'title' => $post->post_title,
	                'text'  => $content,
	                'url'   => get_permalink($post->ID)
	            );
	        }
	    }

	    die(json_encode($result));
	}




	/**
	 * initialize the category
	 * @return [type] [description]
	 */
	public function element_pack_init() {
		$this->_modules_manager = new Manager();

		$elementor = \Elementor\Plugin::$instance;

		// Add element category in panel
		$elementor->elements_manager->add_category('element-pack', 
			[
				'title' => esc_html__( 'Element Pack', 'bdthemes-element-pack' ),
				'icon' => 'font',
			],
			1
		);
		
		do_action( 'bdthemes_element_pack/init' );
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'element_pack_init' ] );
		add_action( 'elementor/frontend/before_register_styles', [ $this, 'register_site_styles' ] );
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_site_scripts' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_styles' ] );
		//add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] ); // TODO
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'enqueue_site_styles' ] );
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_site_scripts' ] );


		// add_action('wp_ajax_element_pack_search', [ $this, 'element_pack_ajax_search' ] );
		// add_action('wp_ajax_nopriv_element_pack_search', [ $this, 'element_pack_ajax_search' ] );
		
		// When user not login add this action
		if (!is_user_logged_in()) {
			add_action('elementor/init', [ $this, 'element_pack_ajax_login_init'] );
		}
	}

	/**
	 * Element_Pack_Loader constructor.
	 */
	private function __construct() {
		// Register class automatically
		spl_autoload_register( [ $this, 'autoload' ] );
		// Include some backend files
		$this->_includes();
		// Finally hooked up all things
		$this->setup_hooks();
		// Load admin class for admin related content process
		if ( is_admin() ) {
			new Admin();
		}
	}
}

if ( ! defined( 'BDTEP_TESTS' ) ) {
	// In tests we run the instance manually.
	Element_Pack_Loader::instance();
}