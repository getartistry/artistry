<?php
/*
Plugin Name: WooThumbs - Awesome Product Imagery
Plugin URI: https://iconicwp.com/products/woothumbs/
Description: Display your WooCommerce product images in an awesome way. Zoom, Slide, Enlarge, and more!
Version: 4.6.3
Author: Iconic
Author URI: https://iconicwp.com
Text Domain: iconic-woothumbs
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Iconic_WooThumbs {

    /*
     * Version
     *
     * @var string
     */
    public $version = "4.6.3";

    /*
     * Full name
     *
     * @var string
     */
    public $name = 'WooThumbs - Awesome Product Imagery';

    /*
     * Short name
     *
     * @var string
     */
    public $shortname = 'WooThumbs';

    /*
     * Slug
     *
     * @var string
     */
    public $slug = 'iconic-woothumbs';

    /*
     * Slug alt (underscores)
     *
     * @var string
     */
    public $slug_alt;

    /*
     * Variable to hold settings framework instance
     *
     * @var obj
     */
    public $settings_framework = null;

    /*
     * Absolute path to this plugin folder, trailing slash
     *
     * @var string
     */
    public $plugin_path;

    /*
     * URL to this plugin folder, no trailing slash
     *
     * @var string
     */
    public $plugin_url;

    /*
     * Page slug for bulk edit
     *
     * @var string
     */
    public $bulk_edit_slug;

    /*
     * Nonce name for ajax requests
     *
     * @var string
     */
    public $ajax_nonce_string;

    /*
     * Active Plugins List
     *
     * @var arr
     */
    public $active_plugins;

    /**
     * Class prefix
     *
     * @since 4.5.0
     * @access protected
     * @var string $class_prefix
     */
    protected $class_prefix = "Iconic_WooThumbs_";

    /*
     * WP All Import helper
     *
     * @var Iconic_WooThumbs_WP_All_import
     */
    protected $wp_all_import;

    /*
     * Transition settings helper
     *
     * @var Iconic_WooThumbs_Transition_Settings
     */
    protected $transition_settings;

    /*
     * Construct
     */
    function __construct() {

        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->bulk_edit_slug = $this->slug.'-bulk-edit';
        $this->ajax_nonce_string = $this->slug.'_ajax';
        $this->active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        $this->slug_alt = str_replace('-', '_', $this->slug);

        $this->load_classes();

        // Hook up to the plugins_loaded action
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded_hook' ) );

    }

    /**
     * Load classes
     */
    private function load_classes() {

        spl_autoload_register( array( $this, 'autoload' ) );

        require_once( $this->plugin_path.'inc/admin/vendor/class-envato-market-github.php' );

        if( is_admin() ) {

            $this->wp_all_import = new Iconic_WooThumbs_WP_All_import();

        }

        $this->transition_settings = new Iconic_WooThumbs_Transition_Settings();
        Iconic_WooThumbs_Shortcodes::init_shortcodes();

    }

    /**
     * Autoloader
     *
     * Classes should reside within /inc and follow the format of
     * Iconic_The_Name ~ class-the-name.php or {{class-prefix}}The_Name ~ class-the-name.php
     */
    private function autoload( $class_name ) {

        /**
         * If the class being requested does not start with our prefix,
         * we know it's not one in our project
         */
        if ( 0 !== strpos( $class_name, 'Iconic_' ) && 0 !== strpos( $class_name, $this->class_prefix ) )
            return;

        $file_name = strtolower( str_replace(
            array( $this->class_prefix, 'Iconic_', '_' ),      // Prefix | Plugin Prefix | Underscores
            array( '', '', '-' ),                              // Remove | Remove | Replace with hyphens
            $class_name
        ) );

        // Compile our path from the current location
        $file = dirname( __FILE__ ) . '/inc/class-'. $file_name .'.php';

        // If a file is found
        if ( file_exists( $file ) ) {
            // Then load it up!
            require( $file );
        }

    }


    /*
     *
     * Runs on plugins_loaded
     *
     */
    function plugins_loaded_hook() {

        load_plugin_textdomain( 'iconic-woothumbs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        $this->load_settings_framework();

        if ( is_admin() ) {

            $this->delete_transients();

            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
            add_action( 'wp_ajax_admin_load_thumbnails', array( $this, 'admin_load_thumbnails' ) );
            add_action( 'woocommerce_save_product_variation', array( $this, 'save_product_variation' ), 10, 2 );
            add_action( 'admin_menu', array( $this, 'bulk_edit_page' ), 20 );
            add_action( 'admin_init', array( $this, 'media_columns' ) );

            add_action( 'wp_ajax_iconic-woothumbs_bulk_save', array( $this, 'bulk_save' ) );

            add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'product_tab' ) );
            add_action( 'woocommerce_product_data_panels', array( $this, 'product_tab_fields' ) );
            add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_fields' ) );

            add_action( 'wp_ajax_iconic_woothumbs_get_variation', array( $this, 'ajax_get_variation' ) );
            add_action( 'wp_ajax_nopriv_iconic_woothumbs_get_variation', array( $this, 'ajax_get_variation' ) );

            add_action( 'wpsf_after_settings_iconic_woothumbs', array( $this, 'clear_image_cache_button' ), 10 );

        } else {

            add_action( 'woocommerce_before_single_product', array( $this, 'remove_hooks' ) );
            add_action( 'woocommerce_before_single_product_summary', array( $this, 'show_product_images' ), 20);
            add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_and_styles' ) );

            add_filter( 'body_class', array( $this, 'add_theme_class' ) );

            add_action( 'iconic_woothumbs_after_images', array( $this, 'add_yith_wishlist_icon' ) );

            add_action( 'woocommerce_api_product_response', array( $this, 'woocommerce_api_product_response' ), 10, 4 );

            add_filter( 'oembed_result', array( $this, 'oembed_remove_related' ), 10, 3 );

        }

        add_filter( 'woocommerce_available_variation', array( $this, 'alter_variation_json' ), 10, 3 );

    }

    /**
     * Load settings framework
     */
    public function load_settings_framework() {

        require_once( $this->plugin_path .'inc/admin/vendor/wp-settings-framework/wp-settings-framework.php' );

        $this->option_group = $this->slug_alt;

        $this->settings_framework = new WordPressSettingsFramework( $this->plugin_path .'inc/admin/settings.php', $this->option_group );

        $this->settings = $this->settings_framework->get_settings();

        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_settings_page' ), 20 );

        // Validate Settings
        add_filter( $this->option_group .'_settings_validate', array( $this, 'validate_settings' ), 10, 1 );

    }

    /**
     * Admin: Add settings menu item
     */
    public function add_settings_page() {

        $this->settings_framework->add_settings_page( array(
            'parent_slug' => 'woocommerce',
            'page_title' => $this->name,
            'menu_title' => $this->shortname,
            'capability' => 'manage_woocommerce'
        ) );

    }

    /**
     * Admin: Validate Settings
     *
     * @param arr $settings Un-validated settings
     * @return arr $validated_settings
     */
    public function validate_settings( $settings ) {

        // add_settings_error( $setting, esc_attr( 'iconic-woothumbs-error' ), $message, 'error' );

        return $settings;

    }

    /*
     *
     * Frontend: Alter Variation JSON
     *
     * This hooks into the data attribute on the variations form for each variation
     * we can get the additional image data here!
     *
     * Run frontend for variations form, and backend for ajax request
     *
     * @param mixed $variation_data
     * @param mixed $wc_product_variable
     * @param mixed $variation_obj
     *
     * @return arr
     *
     */
    public function alter_variation_json( $variation_data, $wc_product_variable, $variation_obj ) {

        $images = $this->get_all_image_sizes( $variation_data['variation_id'] );

        $variation_data['jck_additional_images'] = $images;

        return $variation_data;

    }


    /*
     *
     * Frontend: Add Theme Class to Body
     *
     * @param arr $classes exisiting classes
     *
     * @return arr
     *
     */
    public function add_theme_class( $classes ) {

        $theme_name = sanitize_title_with_dashes( wp_get_theme() );

        $classes[] = $this->slug.'-'.$theme_name;
        return $classes;

    }


    /*
     *
     * Helper: Is Enabled
     *
     * Check whether WooThumbs is enabled for this product
     *
     * @return bool
     *
     */
    public function is_enabled() {

        global $post;

        $pid = $post->ID;

        if ( $pid ) {

            $disable_woothumbs = get_post_meta( $pid, 'disable_woothumbs', true );

            return ( $disable_woothumbs && $disable_woothumbs == "yes" ) ? false : true;

        }

        return false;

    }


    /*
     *
     * Helper: Get Product ID from Slug
     *
     * Gets the product id from the slug of the current product
     *
     * @return int
     *
     */
    public function get_post_id_from_slug() {

        global $wpdb;

        $slug = str_replace( array( "/product/", "/" ), "", $_SERVER['REQUEST_URI'] );

        $sql = "
            SELECT
                ID
            FROM
                $wpdb->posts
            WHERE
                post_type = \"product\"
            AND
                post_name = \"%s\"
        ";

        return $wpdb->get_var( $wpdb->prepare( $sql, $slug ) );

    }


    /*
     *
     * Admin: Add Bulk Edit Page
     *
     */
    public function bulk_edit_page() {

        add_submenu_page( 'woocommerce', __('Bulk Edit Variation Images', 'iconic-woothumbs'), __('&rdsh; Bulk Edit Images', 'iconic-woothumbs'), 'manage_woocommerce', $this->bulk_edit_slug, array( $this, 'bulk_edit_page_display' ) );

    }


    /*
     *
     * Admin: Display Bulk Edit Page
     *
     */
    public function bulk_edit_page_display() {

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'iconic-woothumbs') );
        }

        require_once 'inc/admin/bulk-edit.php';

    }

    /*
     *
     * Admin: Add tab to product edit page
     *
     */
    public function product_tab( $tabs ) {
        global $post;

        echo '<li class="'.$this->slug.'_options_tab"><a href="#'.$this->slug.'_options">'.__('WooThumbs', 'iconic-woothumbs').'</a></li>';

    }


    /*
     *
     * Admin: Add custom product fields
     *
     */
    public function product_tab_fields() {

        global $woocommerce, $post;

        $product_settings = get_post_meta($post->ID, '_iconic_woothumbs', true);

        echo '<div id="'.$this->slug.'_options" class="panel woocommerce_options_panel">';

            // Disable WooThumbs
            woocommerce_wp_checkbox(
                array(
                    'id'            => 'disable_woothumbs',
                    'label'         => __('Disable WooThumbs?', 'iconic-woothumbs')
                )
            );

            // Video URL
            woocommerce_wp_text_input(array(
                'id' => 'iconic-woothumbs-video-url',
                'name' => 'iconic_woothumbs[video_url]',
                'label' => __('Video URL', 'iconic-woothumbs'),
                'desc_tip' => true,
                'description' => __('Video URL - most video hosting services are supported.', 'iconic-woothumbs'),
                'value' => isset($product_settings['video_url']) ? $product_settings['video_url'] : ""
            ));

            // Maintain slide index
            woocommerce_wp_checkbox(array(
                'id' => 'iconic-woothumbs-maintain-slide-index',
                'name' => 'iconic_woothumbs[maintain_slide_index]',
                'label' => __('Maintain Slide Index?', 'iconic-woothumbs'),
                'desc_tip' => true,
                'description' => __('When checked, the slide index will be maintained upon changing images. For example, if you\'re viewing the second image in the slider, and change variation, the second image will be selected from the new images.', 'iconic-woothumbs'),
                'value' => isset($product_settings['maintain_slide_index']) ? $product_settings['maintain_slide_index'] : ""
            ));

        echo '</div>';

    }


    /*
     *
     * Admin: Save custom product fields
     *
     */
    public function save_product_fields( $post_id ) {

        $product_settings = array();

        // Disable WooThumbs
        $disable_woothumbs = isset( $_POST['disable_woothumbs'] ) ? 'yes' : 'no';
        update_post_meta( $post_id, 'disable_woothumbs', $disable_woothumbs );

        if( isset($_POST['iconic_woothumbs']) ) {

            if( isset($_POST['iconic_woothumbs']['video_url']) ) {

                $product_settings['video_url'] = $_POST['iconic_woothumbs']['video_url'];

            }

            if( isset($_POST['iconic_woothumbs']['maintain_slide_index']) ) {

                $product_settings['maintain_slide_index'] = $_POST['iconic_woothumbs']['maintain_slide_index'];

            }

            update_post_meta( $post_id, '_iconic_woothumbs', $product_settings );

        }

        $this->delete_transients( true, $post_id );

    }

    /**
     * Clear image cache button
     */
    public function clear_image_cache_button() {
        ?>
        <form method="post">
            <button name="iconic-woothumbs-delete-image-cache" class="button button-secondary"><?php _e('Clear Image Cache', 'iconic-woothumbs'); ?></button>
        </form>
        <?php
    }

    /**
     * Helper: Delete all woothumbs transient
     *
     * @param bool $force
     */
    public function delete_transients( $force = false, $product_id = false ) {

        if( isset( $_POST['iconic-woothumbs-delete-image-cache'] ) || $force === true ) {

            global $wpdb;

            $transients = $wpdb->get_results(
                $wpdb->prepare(
                    "
                    SELECT * FROM $wpdb->options
                    WHERE `option_name` LIKE '%s'
                    ",
                    '%transient_iconic-woothumbs_%'
                )
            );

            if( $transients ) {

                foreach( $transients as $transient ) {

                    $transient_name = str_replace('_transient_', '', $transient->option_name);
                    delete_transient( $transient_name );

                }

            }

            if( $product_id ) {

                $dvi_transient_name = $this->get_transient_name( $product_id, "dvi" );
                delete_transient( $dvi_transient_name );

                $all_images_transient_name = $this->get_transient_name( $product_id, "all-images" );
                delete_transient( $all_images_transient_name );

            }

        }

    }


    /*
     *
     * Admin: Save Bulk Edit Page
     *
     */
    function bulk_save() {

        check_ajax_referer($this->ajax_nonce_string, 'nonce');

        header('Content-Type: application/json');

        $return = array('result' => 'success', 'content' => '', 'message' => '');

        $images = trim($_POST['images']);

        // Validate input
        $re = '/^\d+(?:,\d+)*$/'; // numbers or commas

        // if input contains only numbers or commas OR nothing was entered
        if (preg_match($re, $images) || $images == "") {

            $prevImages = get_post_meta($_POST['varid'], 'variation_image_gallery', true);
            $updatedImages = update_post_meta($_POST['varid'], 'variation_image_gallery', $images, $prevImages);

            if ($prevImages == $images) {
                $return['result'] = 'no-change';
            }
            elseif ($updatedImages === false) {
                $return['result'] = 'failed';
            }

            // if any other character is found
        } else {

            $return['result'] = 'invalid-format';

        }

        switch ($return['result']) {
        case 'no-change':
            $return['message'] = __('There was no change.', 'iconic-woothumbs');
            break;
        case 'invalid-format':
            $return['message'] = __('Please use only numbers and commas.', 'iconic-woothumbs');
            break;
        case 'failed':
            $return['message'] = __('Sorry, an error occurred. Please try again.', 'iconic-woothumbs');
            break;
        case 'empty':
            $return['message'] = __('Nothing was entered.', 'iconic-woothumbs');
            break;
        }

        $return['postdata'] = $_POST;

        echo json_encode($return);

        $this->delete_transients( true );

        wp_die();

    }


    /*
     *
     * Admin: Setup new media column for image IDs
     *
     */
    function media_columns() {
        add_filter( 'manage_media_columns', array( $this, 'media_id_col' ) );
        add_action( 'manage_media_custom_column', array( $this, 'media_id_col_val' ), 10, 2 );
    }


    /*
     *
     * Admin: Media column name
     *
     */
    function media_id_col( $cols ) {
        $cols["mediaid"] = "Image ID";
        return $cols;
    }


    /*
     *
     * Admin: media column content
     *
     * @param str $column_name
     * @param int $id
     *
     * @return str
     *
     */
    function media_id_col_val( $column_name, $id ) {
        if ($column_name == 'mediaid') echo $id;
    }


    /*
     *
     * Admin: Scripts
     *
     */
    public function admin_scripts() {

        global $post, $pagenow;

        if (
            ($post && (get_post_type( $post->ID ) == "product" && ($pagenow == "post.php" || $pagenow == "post-new.php"))) ||
            ($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == $this->bulk_edit_slug)
        ) {

            wp_enqueue_script( $this->slug, plugins_url('assets/admin/js/admin-scripts.js', __FILE__), array('jquery'), '2.0.1', true );
            wp_enqueue_style( 'iconic_woothumbs_admin_css', plugins_url('assets/admin/css/admin-styles.css', __FILE__), false, '2.0.1' );

            $vars = array(
                'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
                'nonce' => wp_create_nonce( $this->ajax_nonce_string ),
                'slug' => $this->slug
            );

            wp_localize_script( $this->slug, 'iconic_woothumbs_vars', $vars );

        }

    }


    /*
     *
     * Admin: Save variation images
     *
     * @param int $variation_id
     * @param int $i
     *
     */
    function save_product_variation( $variation_id, $i ) {

        $this->delete_transients( true, $variation_id );

        if ( isset( $_POST['variation_image_gallery'][$variation_id] ) ) {

            update_post_meta($variation_id, 'variation_image_gallery', $_POST['variation_image_gallery'][$variation_id]);

        }

    }


    /*
     *
     * Ajax: Load thumbnails via ajax for variation tabs
     *
     * @jck: change to new method
     *
     */
    function admin_load_thumbnails() {

        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], $this->ajax_nonce_string ) ) { die ( 'Invalid Nonce' ); }

        $attachments = get_post_meta($_GET['varID'], 'variation_image_gallery', true);
        $attachmentsExp = array_filter(explode(',', $attachments));
        $image_ids = array(); ?>

			<ul class="wooThumbs">

				<?php if (!empty($attachmentsExp)) { ?>

					<?php foreach ($attachmentsExp as $id) { $image_ids[] = $id; ?>
						<li class="image" data-attachment_id="<?php echo $id; ?>">
							<a href="#" class="delete" title="Delete image"><?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?></a>
						</li>
					<?php } ?>

				<?php } ?>

			</ul>
			<input type="hidden" class="variation_image_gallery" name="variation_image_gallery[<?php echo $_GET['varID']; ?>]" value="<?php echo $attachments; ?>">

		<?php exit;
    }


    /*
     *
     * Frontend: Remove product images
     *
     */
    public function remove_hooks() {

        if ( apply_filters( 'woothumbs_enabled', $this->is_enabled() ) ) {

            remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 10 );
            remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

            // Mr. Tailor
            remove_action( 'woocommerce_before_single_product_summary_product_images', 'woocommerce_show_product_images', 20 );
            remove_action( 'woocommerce_product_summary_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

            // Remove images from Bazar theme
            if ( class_exists( 'YITH_WCMG' ) ) {
                $this->remove_filters_for_anonymous_class( 'woocommerce_before_single_product_summary', 'YITH_WCMG_Frontend', 'show_product_images', 20 );
                $this->remove_filters_for_anonymous_class( 'woocommerce_product_thumbnails', 'YITH_WCMG_Frontend', 'show_product_thumbnails', 20 );
            }

        }

    }


    /*
     *
     * Frontend: Add product images
     *
     * @param str $variable
     *
     * @return str
     *
     */
    public function show_product_images() {

        if ( apply_filters( 'woothumbs_enabled', $this->is_enabled() ) ) {

            require 'inc/images.php';

        }

    }


    /*
     *
     * Frontend: Register scripts and styles
     *
     */
    public function register_scripts_and_styles() {

        global $post, $jckqv;

        if( !$post )
            return;

        if ( $jckqv || ( ( function_exists('is_product') && is_product() ) || has_shortcode( $post->post_content, 'product_page' ) ) && apply_filters( 'woothumbs_enabled', $this->is_enabled() ) || is_woocommerce() || is_product_category() ) {

            // Plugins/Libs
            $this->enqueue_photoswipe();
            $this->enqueue_tooltipster();

            // Vars
            $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            // CSS
            $this->load_file( 'slick-carousel', '/assets/vendor/slick.css' );
            $this->load_file( $this->slug . '-css', '/assets/frontend/css/main'.$min.'.css' );

            // Scripts
            $this->load_file( 'slick-carousel', '/assets/vendor/slick'.$min.'.js', true );
            $this->load_file( 'hoverIntent', '/assets/vendor/jquery.hoverIntent.js', true );
            $this->load_file( $this->slug . '-script', '/assets/frontend/js/main'.$min.'.js', true, array('jquery', 'wp-util') );

            $vars = array(
                'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
                'nonce' => wp_create_nonce( $this->ajax_nonce_string ),
                'loading_icon' => plugins_url('assets/frontend/img/loading.gif', __FILE__),
                'slug' => $this->slug,
                'settings' => $this->settings,
                'text' => array(
                    'fullscreen' => __('Fullscreen', 'iconic-woothumbs'),
                    'video' => __('Play Video', 'iconic-woothumbs')
                ),
                'is_rtl' => is_rtl()
            );

            wp_localize_script( $this->slug . '-script', 'iconic_woothumbs_vars', $vars );

            add_action( 'wp_head', array( $this, 'dynamic_css' ) );

        }

    }



    /**
     *
     * Enqueue Photoswipe
     *
     */
    public function enqueue_photoswipe() {

        if( $this->maybe_enable_fullscreen() ) {

            $this->load_file( 'photoswipe', '/assets/frontend/js/lib/photoswipe/photoswipe.min.js', true, array('jquery', 'wp-util') );
            $this->load_file( 'photoswipe-ui', '/assets/frontend/js/lib/photoswipe/photoswipe-ui-default.min.js', true );

            add_action( 'wp_footer', array( $this, 'photoswipe_html' ), 1 );

        }

    }



    /**
     *
     * Enqueue Tooltipster
     *
     */
    public function enqueue_tooltipster() {

        if( $this->settings['display_general_icons_tooltips'] ) {

            $this->load_file( 'tooltipster', '/assets/frontend/js/lib/tooltipster/jquery.tooltipster.min.js', true );
            $this->load_file( 'tooltipster', '/assets/frontend/css/lib/tooltipster/tooltipster.css' );

        }

    }



    /**
     *
     * Output photoswipe HTML template
     *
     */
    public function photoswipe_html() {
        include_once( $this->plugin_path . '/inc/pswp.php' );
    }



    /**
     * Helper: Maybe enable fullscreen
     */
    public function maybe_enable_fullscreen() {

        global $post, $jckqv;

        $product_settings = (array) get_post_meta($post->ID, '_iconic_woothumbs', true);

        if(
            $this->settings['fullscreen_general_enable'] ||
            ( isset( $product_settings['video_url'] ) && $product_settings['video_url'] != "" ) ||
            $jckqv
        ) {

            return true;

        } else {

            return false;

        }

    }


    /*
     *
     * Frontend: Add dynamic CSS to wp_head
     *
     */
    public function dynamic_css() {

        include $this->plugin_path.'assets/frontend/css/dynamic-styles.css.php';

    }


    /*
     *
     * Helper: register and enqueue a file
     *
     * @param str $handle
     * @param str $file_path
     * @param str $is_script
     * @param array $deps
     *
     */
    private function load_file( $handle, $file_path, $is_script = false, $deps = array('jquery') ) {

        $url = plugins_url($file_path, __FILE__);
        $file = plugin_dir_path(__FILE__) . $file_path;

        if ( file_exists( $file ) ) {
            if ( $is_script ) {
                wp_register_script( $handle, $url, $deps, $this->version, true ); //depends on jquery
                wp_enqueue_script( $handle );
            } else {
                wp_register_style( $handle, $url, array(), $this->version );
                wp_enqueue_style( $handle );
            }
        }

    }


    /*
     *
     * Helper: Allow to remove method for a hook when it's a class method used
     *
     * @param str $hook_name Name of the wordpress hook
     * @param str $class_name Name of the class where the add_action resides
     * @param str $method_name Name of the method to unhook
     * @param str $priority The priority of which the above method has in the add_action
     *
     */
    public function remove_filters_for_anonymous_class( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {

        global $wp_filter;

        // Take only filters on right hook name and priority
        if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) )
            return false;

        // Loop on filters registered
        foreach ( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
            // Test if filter is an array ! (always for class/method)
            if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
                // Test if object is a class, class and method is equal to param !
                if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && get_class($filter_array['function'][0]) == $class_name && $filter_array['function'][1] == $method_name ) {
                    unset($wp_filter[$hook_name][$priority][$unique_id]);
                }
            }

        }

        return false;

    }


    /*
     *
     * Helper: Get default variaiton ID
     *
     * Grabs the default variation ID, depending on the
     * settings for that particular product
     *
     * @return int
     *
     */
    public function get_default_variation_id() {

        global $post, $woocommerce, $product;

        if( !$product )
            return false;

        $product_id = $product->get_id();
        $default_variation_id = $product_id;
        $transient_name = $this->get_transient_name( $product_id, "dvi" );

        if ( false === ( $default_variation_id = get_transient( $transient_name ) ) ) {

            if ($product->get_type() == 'variable') {

                $defaults = self::get_variation_default_attributes( $product );
                $variations = array_reverse($product->get_available_variations());

                if (!empty($defaults)) {

                    foreach ($variations as $variation) {

                        $varCount = count($variation["attributes"]);
                        $attMatch = 0;
                        $partMatch = 0;

                        foreach ($defaults as $dAttName => $dAttVal) {

                            if (isset($variation["attributes"]['attribute_'.$dAttName])) {

                                $theAtt = $variation["attributes"]['attribute_'.$dAttName];

                                if ($theAtt == $dAttVal) {
                                    $attMatch++;
                                    $partMatch++;
                                }

                                if ($theAtt == "") {
                                    $partMatch++;
                                }

                            }

                        }

                        if ($varCount == $partMatch) {
                            $default_variation_id = $variation['variation_id'];
                        }

                        if ($varCount == $attMatch) {
                            $default_variation_id = $variation['variation_id'];
                        }

                    }

                }

            }

            set_transient( $transient_name, $default_variation_id, 12 * HOUR_IN_SECONDS );

        }

        return $default_variation_id;

    }


    /**
     * Get variation default attributes
     *
     * @param WC_Product $product
     * @return bool|arr
     */
    public static function get_variation_default_attributes( $product ) {

        if( method_exists( $product, 'get_default_attributes' ) ) {

            return $product->get_default_attributes();

        } else {

            return $product->get_variation_default_attributes();

        }

    }


    /*
     * Helper: Get selected variation
     *
     * If the URL contains variation data, get the related variation ID,
     * if it exists, and overwrite the current selected ID
     *
     * @param int $current_id
     * @return int
     */
    public function get_selected_variation( $current_id ) {

        global $post, $woocommerce, $product;

        if ($product->get_type() == 'variable') {

            if( !empty( $_POST['variation_id'] ) )
                return $_POST['variation_id'];

            $default_atts = $this->get_default_atts( $current_id );
            $selected_atts = wp_parse_args( $this->get_atts_from_query_string(), $default_atts );

            $selected_atts_count = count( $selected_atts );
            $available_atts_count = count( $product->get_variation_attributes() );

            if( empty($selected_atts) || $selected_atts_count < $available_atts_count )
                return $current_id;

            $variation = Iconic_WooThumbs_Product::find_matching_product_variation( $product, $selected_atts );

            if( $variation )
                $current_id = $variation;

        }

        return $current_id;

    }



    /**
     * Helper: Get attributes from query string
     */
    public function get_atts_from_query_string() {

        $atts = array();

        if( isset( $_GET ) ) {

            foreach( $_GET as $key => $value ) {

                if( strpos($key, 'attribute_') !== false ) {

                    $atts[$key] = $value;

                }

            }

        }

        return $atts;

    }

    /**
     * Helper: Get default attributes for variable product
     *
     * @param int $product_id
     */
    public function get_default_atts( $product_id ) {

        $atts = array();

        $parent_product_id = wp_get_post_parent_id( $product_id );
        $parent_product_id = $parent_product_id === 0 ? $product_id : $parent_product_id;
        $variable_product = wc_get_product( $parent_product_id );

        if( $variable_product->get_type() != "variable" )
            return $atts;

        $default_atts = self::get_variation_default_attributes( $variable_product );

        if( !$default_atts )
            return $atts;

        foreach( $default_atts as $key => $value ) {

            $atts['attribute_'.$key] = $value;

        }

        return $atts;

    }


    /**
     * Helper: WPML - Get original variation ID
     *
     * If WPML is active and this is a translated variaition, get the original ID.
     *
     * @param int $id
     */
    public function wpml_get_original_variation_id( $id ) {

        $wpml_original_variation_id = get_post_meta( $id, '_wcml_duplicate_of_variation', true );

        if( $wpml_original_variation_id )
            $id = $wpml_original_variation_id;

        return $id;

    }

    /**
     * Helper: Get translated media ID
     *
     * @param int $media_file_id
     * @return bool|int
     */
    public function get_translated_media_id( $media_file_id ) {

        global $wpdb;

        if( !function_exists('icl_object_id') )
            return $media_file_id;

        $media_file_id = icl_object_id( $media_file_id, 'attachment', true );

        return $media_file_id;

    }

    /**
     * Helper: Get all images transient name for specific variation/product
     *
     * @param int $id
     * @param str $type
     * @return str
     */
    public function get_transient_name( $id, $type ) {

        if( $type === "all-images" ) {

            $id = $this->wpml_get_original_variation_id( $id );

            $transient_name = sprintf("%s_variation_image_ids_%d", $this->slug, $id);

        } elseif( $type === "dvi" ) {

            $transient_name = sprintf('%s_dvi_%d', $this->slug, $id);

        } elseif ( $type === "sizes" ) {

			$transient_name = sprintf("%s_variation_image_sizes_%d", $this->slug, $id);

        } elseif ( $type === "variation" ) {

			$transient_name = sprintf("%s_variation_%d", $this->slug, $id);

        } else {

            $transient_name = false;

        }

        return apply_filters( 'iconic_woothumbs_transient_name', $transient_name, $type, $id);

    }


    /*
     *
     * Helper: Get all image IDs for a specifc variation
     *
     * @param int $id
     *
     */
    public function get_all_image_ids( $id ) {

        $transient_name = $this->get_transient_name( $id, "all-images" );

        if ( false === ( $all_images = get_transient( $transient_name ) ) ) {

            $all_images = array();
            $show_gallery = false;
            $has_featured_image = has_post_thumbnail( $id );
            $product = Iconic_WooThumbs_Product::get_product( $id );
            $post_type = get_post_type( $id );
            $parent_id = Iconic_WooThumbs_Product::get_parent_id( $product );

            // Main Image
            if ( $has_featured_image ) {

                $all_images['featured'] = get_post_thumbnail_id( $id );

            } else {

                if( $parent_id > 0 ) {

                    if ( has_post_thumbnail( $parent_id ) ) {
                        $all_images['featured'] = get_post_thumbnail_id( $parent_id );
                    } else {
                        $all_images[] = 'placeholder';
                    }

                    $show_gallery = true;

                } else {

                    $all_images[] = 'placeholder';

                }

            }

            // WooThumb Attachments

            if ( $post_type == 'product_variation' ) {

                $wt_attachments = array_filter(explode(',', get_post_meta($id, 'variation_image_gallery', true)));

                if( !empty( $wt_attachments ) ) {

                    $all_images = array_merge($all_images, $wt_attachments);

                    // if there was no featured image, set the first
                    // woothumbs attachment as the featured image

                    if( !$has_featured_image ) {
                        $all_images['featured'] = $all_images[0];
                        unset( $all_images[0] );

                        $show_gallery = false;
                    }

                }

            }

            // Gallery Attachments

            if ( $post_type == 'product' || $show_gallery ) {

                $id = !empty( $parent_id ) ? $parent_id : $id;
                $gallery_product = wc_get_product( $id );

                $attach_ids = Iconic_WooThumbs_Product::get_gallery_image_ids( $gallery_product );

                if ( !empty( $attach_ids ) ) {
                    $all_images = array_merge($all_images, $attach_ids);
                }

            }

            $all_images = array_map( array($this, 'get_translated_media_id'), $all_images );

            $all_images = apply_filters( 'iconic_woothumbs_all_image_ids_before_transient', $all_images, $id );

            set_transient( $transient_name, $all_images, 12 * HOUR_IN_SECONDS );

        }

        return apply_filters( 'iconic_woothumbs_all_image_ids', $all_images, $id );

    }


    /*
     *
     * Helper: Get all image sizes
     *
     * @param int $variation_id
     *
     */
    public function get_all_image_sizes( $variation_id ) {

        $image_ids = $this->get_all_image_ids( absint($variation_id) );
        $images = array();

        if ( !empty($image_ids) ) {
            foreach ($image_ids as $image_id):

                $transient_name = $this->get_transient_name( $image_id, "sizes" );

                if ( false === ( $image_sizes = get_transient( $transient_name ) ) ) {

                    $image_sizes = false;

                    if ($image_id == 'placeholder') {
                        $image_sizes = array(
                            'large' => array( wc_placeholder_img_src( $this->settings['display_general_large_image_size'] ) ),
                            'single' => array( wc_placeholder_img_src('shop_single') ),
                            'thumb' => array( wc_placeholder_img_src('shop_thumbnail') ),
                            'alt' => '',
                            'title' => ''
                        );
                    } else {

                        if (!array_key_exists($image_id, $images)) {
                            $attachment = $this->wp_get_attachment($image_id);

                            if( !$attachment )
                                continue;

                            $large = wp_get_attachment_image_src( $image_id, $this->settings['display_general_large_image_size'] );
                            $single = wp_get_attachment_image_src( $image_id, 'shop_single' );
                            $thumb = wp_get_attachment_image_src( $image_id, 'shop_thumbnail' );

                            $srcset = wp_get_attachment_image_srcset( $image_id, 'shop_single' );
                            $sizes = wp_get_attachment_image_sizes( $image_id, 'shop_single' );
                            $thumb_srcset = wp_get_attachment_image_srcset( $image_id, 'shop_thumbnail' );
                            $thumb_sizes = wp_get_attachment_image_sizes( $image_id, 'shop_thumbnail' );

                            $image_sizes = array(
                                'large' => $large,
                                'single' => $single,
                                'thumb' => $thumb,
                                'alt' => $attachment['alt'],
                                'title' => $attachment['title'],
                                'caption' => $attachment['caption'] ? $attachment['caption'] : "&mdash;",
                                'srcset' => $srcset ? $srcset : "",
                                'sizes' => $sizes ? $sizes : "",
                                'thumb_srcset' => $thumb_srcset ? $thumb_srcset : "",
                                'thumb_sizes' => $thumb_sizes ? $thumb_sizes : "",
                            );

                        }

                    }

                    $image_sizes = apply_filters( 'iconic_woothumbs_all_image_sizes', $image_sizes );

                    set_transient( $transient_name, $image_sizes, 12 * HOUR_IN_SECONDS );

                }

                if( $image_sizes )
                    $images[] = $image_sizes;

            endforeach;
        }

        return apply_filters( 'iconic_woothumbs_all_image_sizes', $images );

    }

    /**
     * Helper: Get image data
     *
     * @param arr $image
     * @param int $index
     * @param arr
     */
    public static function get_image_loop_data( $image, $index ) {

        $aspect = $index == 0 ? false : ($image['single'][2]/$image['single'][1])*100;

        $image_data = array(
            'src' => $index == 0 ? $image['single'][0] : "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=",
            'data-iconic-woothumbs-src' => $index == 0 ? false : $image['single'][0],
            'class' => 'iconic-woothumbs-images__image',
            'srcset' => !empty( $image['srcset'] ) ? $image['srcset'] : false,
            'sizes' => !empty( $image['sizes'] ) ? $image['sizes'] : false,
            'data-large-image' => !empty( $image['large'][1] ) ? $image['large'][0] : false,
            'data-large-image-width' => !empty( $image['large'][1] ) ? $image['large'][1] : false,
            'data-large-image-height' => !empty( $image['large'][1] ) ? $image['large'][2] : false,
            'title' => $image['title'],
            'alt' => $image['alt'],
            'width' => !empty( $image['single'][1] ) ? $image['single'][1] : false,
            'height' => !empty( $image['single'][2] ) ? $image['single'][2] : false,
            'style' => $aspect ? sprintf( 'padding-top: %s%%; height: 0px;', $aspect ) : false,
            'caption' => !empty( $image['caption'] ) ? $image['caption'] : false
        );

        return $image_data;

    }

    /**
     * Helper: Array to HTML Attributes
     */
    public static function array_to_html_atts( $array ) {

        if( !is_array( $array ) || empty( $array ) )
            return false;

        $return = '';

        foreach( $array as $key => $value ) {

            if( empty( $value ) )
                continue;

            $return .= sprintf( '%s="%s" ', $key, esc_attr( $value ) );

        }

        return $return;

    }

    /*
     *
     * Helper: Get an attachment with additional data
     *
     * @param arr $attachment_id
     *
     */
    public function wp_get_attachment( $attachment_id ) {

        $attachment = get_post( $attachment_id );

        if( !$attachment )
            return false;

        return array(
            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink( $attachment->ID ),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        );

    }



    /**
     * Ajax: Get variation by ID
     */
    public function ajax_get_variation() {

        $response = array(
            'success' => false,
            'variation' => false
        );

        if( !empty( $_GET['variation_id'] ) && !empty( $_GET['product_id'] ) ) {

            $transient_name = $this->get_transient_name( $_GET['variation_id'], "variation" );

            if ( false === ( $variation = get_transient( $transient_name ) ) ) {

                $variable_product = new WC_Product_Variable( absint( $_GET['product_id'] ) );
                $variation = $variable_product->get_available_variation( absint( $_GET['variation_id'] ) );

                set_transient( $transient_name, $variation, 12 * HOUR_IN_SECONDS );

            }

            $response['success'] = true;
            $response['variation'] = $variation;

        }

        // generate the response
    	$response['get'] = $_GET;

    	// response output
    	header('Content-Type: text/javascript; charset=utf8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 3628800');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

    	echo htmlspecialchars($_GET['callback']) . '(' . json_encode( $response ) . ')';

        wp_die();

    }



    /**
     * Frontend: Add YITH Wishlist Icon
     */

    public function add_yith_wishlist_icon() {

        if ( in_array( 'yith-woocommerce-wishlist/init.php', $this->active_plugins ) || in_array( 'yith-woocommerce-wishlist-premium/init.php', $this->active_plugins ) ) {

            global $product;

            $default_wishlists = is_user_logged_in() ? YITH_WCWL()->get_wishlists( array( 'is_default' => true ) ) : false;

			if( ! empty( $default_wishlists ) ){
				$default_wishlist = $default_wishlists[0]['ID'];
			}
			else{
				$default_wishlist = false;
			}

            $added = YITH_WCWL()->is_product_in_wishlist( $product->get_id(), $default_wishlist );
			$wishlist_url = YITH_WCWL()->get_wishlist_url();

            ?>

            <div class="iconic-woothumbs-wishlist-buttons <?php if( $added ) echo "iconic-woothumbs-wishlist-buttons--added"; ?>">

                <a class="iconic-woothumbs-wishlist-buttons__browse" href="<?php echo $wishlist_url; ?>" data-iconic-woothumbs-tooltip="<?php _e('Browse Wishlist', 'iconic-woothumbs'); ?>"><i class="iconic-woothumbs-icon iconic-woothumbs-icon-heart"></i></a>

                <a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product->get_id() ) )?>" rel="nofollow" data-product-id="<?php echo $product->get_id(); ?>" data-product-type="<?php echo $product->get_type(); ?>" class="iconic-woothumbs-wishlist-buttons__add add_to_wishlist" data-iconic-woothumbs-tooltip="<?php _e('Add to Wishlist', 'iconic-woothumbs'); ?>"><i class="iconic-woothumbs-icon iconic-woothumbs-icon-heart"></i></a>

            </div>

            <?php
        }

    }

    /**
     * Add variation images to the API
     *
     * @param arr $product_data
     * @param obj $product
     * @param arr $fields
     * @param obj $server
     */
    public function woocommerce_api_product_response( $product_data, $product, $fields, $server ) {

        if( !empty( $product_data['variations'] ) ) {

            foreach( $product_data['variations'] as $i => $variation ) {

                $product_data['variations'][$i]['images'] = $this->get_all_image_sizes( $variation['id'] );

            }

        }

        return $product_data;

    }

    /**
     * Bulk: Get current bulk page params
     *
     * @param arr $ignore
     * @return arr
     */
    public function get_bulk_parameters( $ignore = array() ) {

        $get = $_GET;

        if( empty( $get ) )
            return $get;

        foreach( $get as $key => $value ) {
            if( empty( $value ) )
                unset( $get[$key] );
        }

        if( empty( $ignore ) )
            return $get;

        foreach( $ignore as $ignore_key ) {
            unset($get[$ignore_key]);
        }

        return $get;

    }

    /**
     * Bulk: Output bulk page params
     *
     * @param arr $ignore
     */
    public function output_bulk_parameters( $ignore = array() ) {

        $params = $this->get_bulk_parameters( $ignore );

        if( !empty( $params ) ) {
            foreach( $params as $key => $value ) {
                if( is_string( $value ) ) {
                    printf('<input type="hidden" name="%s" value="%s">', $key, $value);
                }
            }
        }

    }

    /**
     * Bulk: Get pagination link
     *
     * @param int $page_number
     */
    public function get_pagination_link( $page_number = false ) {

        $params = $this->get_bulk_parameters( array('paged') );

        if( $page_number )
            $params['paged'] = $page_number;

        return sprintf( '?%s', http_build_query( $params ) );

    }

    /**
     * Oembed remove related videos
     *
     * @param arr $data
     * @param str $url
     * @param arr $args
     * @return arr
     */
    public static function oembed_remove_related( $data, $url, $args = array() ) {

        if( strpos($url, "iconic-woothumbs=1") === false )
            return $data;

    	//Autoplay
    	$autoplay = strpos($url, "autoplay=1") !== false ? "&amp;autoplay=1" : "";

    	//Setup the string to inject into the url
    	$str_to_add = apply_filters("iconic_woothumbs_oembed_parameters", "wmode=transparent&amp;rel=0&amp;showinfo=0");

    	//Regular oembed
    	if (strpos($data, "feature=oembed") !== false) {
    		$data = str_replace('feature=oembed', $str_to_add . $autoplay . '&amp;feature=oembed', $data);

    	//Playlist
    	} elseif (strpos($data, "list=") !== false) {
    		$data = str_replace('list=', $str_to_add . $autoplay . '&amp;list=', $data);
    	}

    	//All Set
    	return $data;
    }

}

$iconic_woothumbs_class = new Iconic_WooThumbs();