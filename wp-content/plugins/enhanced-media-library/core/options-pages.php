<?php

if ( ! defined( 'ABSPATH' ) )
    exit;



/**
 *  wpuxss_eml_register_setting
 *
 *  @since    1.0
 *  @created  03/08/13
 */

add_action( 'admin_init', 'wpuxss_eml_register_setting' );

if ( ! function_exists( 'wpuxss_eml_register_setting' ) ) {

    function wpuxss_eml_register_setting() {

        // plugin settings: media library
        register_setting(
            'media-library', //option_group
            'wpuxss_eml_lib_options', //option_name
            'wpuxss_eml_lib_options_validate' //sanitize_callback
        );

        // plugin settings: taxonomies
        register_setting(
            'media-taxonomies', //option_group
            'wpuxss_eml_taxonomies', //option_name
            'wpuxss_eml_taxonomies_validate' //sanitize_callback
        );

        // plugin settings: taxonomies options
        register_setting(
            'media-taxonomies', //option_group
            'wpuxss_eml_tax_options', //option_name
            'wpuxss_eml_tax_options_validate' //sanitize_callback
        );

        // plugin settings: mime types
        register_setting(
            'mime-types', //option_group
            'wpuxss_eml_mimes', //option_name
            'wpuxss_eml_mimes_validate' //sanitize_callback
        );

        // plugin settings: network settings
        // no validation callback here
        // called explicitly in wpuxss_eml_update_network_settings
        register_setting(
            'eml-network-settings', //option_group
            'wpuxss_eml_network_options' //option_name
        );

        // plugin settings: mime types backup
        register_setting(
            'wpuxss_eml_mimes_backup', //option_group
            'wpuxss_eml_mimes_backup' //option_name
        );

        // plugin settings: all settings backup before import
        register_setting(
            'wpuxss_eml_backup', //option_group
            'wpuxss_eml_backup' //option_name
        );
    }
}



/**
 *  wpuxss_eml_admin_media_menu
 *
 *  @since    2.6
 *  @created  28/04/18
 */

add_action( 'admin_menu', 'wpuxss_eml_admin_media_menu' );

if ( ! function_exists( 'wpuxss_eml_admin_media_menu' ) ) {

    function wpuxss_eml_admin_media_menu() {

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['media_settings'] )
                return;
        }


        $eml_media_options_page = add_submenu_page(
            null,
            __('Media Settings','enhanced-media-library'), //page_title
            '',                                //menu_title
            'manage_options',                  //capability
            'media',                           //menu_slug
            'wpuxss_eml_print_media_settings'  //callback
        );

        $eml_medialibrary_options_page = add_submenu_page(
            'options-general.php',
            __('Media Library','enhanced-media-library'),
            __('Media Library','enhanced-media-library'),
            'manage_options',
            'media-library',
            'wpuxss_eml_print_media_library_options'
        );

        $eml_taxonomies_options_page = add_submenu_page(
            'options-general.php',
            __('Taxonomies','enhanced-media-library'),
            __('Media Taxonomies','enhanced-media-library'),
            'manage_options',
            'media-taxonomies',
            'wpuxss_eml_print_taxonomies_options'
        );

        $eml_mimetype_options_page = add_submenu_page(
            'options-general.php',
            __('MIME Types','enhanced-media-library'),
            __('MIME Types','enhanced-media-library'),
            'manage_options',
            'mime-types',
            'wpuxss_eml_print_mimetypes_options'
        );


        add_filter( 'custom_menu_order', 'wpuxss_eml_submenu_order' );
        add_filter( 'admin_title', 'wpuxss_eml_admin_title_for_media_options_sub_pages', 10, 2 );

        add_action( 'load-' . $eml_media_options_page, 'wpuxss_eml_load_media_options_page' );
        add_action( $eml_media_options_page, 'wpuxss_eml_media_options_page' );

        add_action('admin_print_scripts-' . $eml_medialibrary_options_page, 'wpuxss_eml_medialibrary_options_page_scripts');
        add_action('admin_print_scripts-' . $eml_taxonomies_options_page, 'wpuxss_eml_taxonomies_options_page_scripts');
        add_action('admin_print_scripts-' . $eml_mimetype_options_page, 'wpuxss_eml_mimetype_options_page_scripts');
    }
}



/**
 *  wpuxss_eml_admin_utility_menu
 *
 *  @since    2.6
 *  @created  28/04/18
 */

add_action( 'admin_menu', 'wpuxss_eml_admin_utility_menu' );

if ( ! function_exists( 'wpuxss_eml_admin_utility_menu' ) ) {

    function wpuxss_eml_admin_utility_menu() {

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['utilities'] )
                return;
        }


        $eml_options_page = add_options_page(
           __('Enhanced Media Library','enhanced-media-library'),
           __('Enhanced Media Library','enhanced-media-library'),
           'manage_options',
           'eml-settings',
           'wpuxss_eml_print_settings'
        );

        add_action('admin_print_scripts-' . $eml_options_page, 'wpuxss_eml_options_page_scripts');
    }
}



/**
 *  wpuxss_eml_network_admin_menu
 *
 *  @since    2.6
 *  @created  22/04/18
 */

add_action( 'network_admin_menu', 'wpuxss_eml_network_admin_menu' );

if ( ! function_exists( 'wpuxss_eml_network_admin_menu' ) ) {

    function wpuxss_eml_network_admin_menu() {

        $eml_network_options_page = add_submenu_page(
            'settings.php',
            __('Enhanced Media Library','enhanced-media-library'),
            __('Enhanced Media Library','enhanced-media-library'),
            'manage_options',
            'eml-settings',
            'wpuxss_eml_print_network_settings'
        );

        add_action('admin_print_scripts-' . $eml_network_options_page, 'wpuxss_eml_options_page_scripts');
    }
}



/**
 *  wpuxss_eml_submenu_order
 *
 *  Custom admin media menu.
 *
 *  @since    2.6
 *  @created  04/03/18
 */

if ( ! function_exists( 'wpuxss_eml_submenu_order' ) ) {

    function wpuxss_eml_submenu_order( $menu_order ) {

        global $submenu;


        $media_key = 0;
        $page = isset( $_GET['page'] ) && in_array( $_GET['page'], array('media','media-library','media-taxonomies','mime-types') ) ? $_GET['page'] : '';


        if ( isset( $submenu['options-general.php'] ) ) {

            foreach( $submenu['options-general.php'] as $key => $item ) {

                if ( 'options-media.php' === $item[2] ) {

                    $media_key = $key;
                    $submenu['options-general.php'][$key][2] = 'options-general.php?page=media';
                    $submenu['options-general.php'][$key][4] = ( 'media' === $page ) ? 'eml-menu-media current' : 'eml-menu-media';
                }

                if ( in_array( $item[2], array('media-library','media-taxonomies','mime-types') ) ) {

                    $item[4] = ( $item[2] === $page ) ? 'eml-media-submenu current' : 'eml-media-submenu';
                    $submenu['options-general.php'][++$media_key] = $item;
                    unset( $submenu['options-general.php'][$key] );
                }
            }

            ksort( $submenu['options-general.php'] );
        }

        return $menu_order;
    }
}



/**
 *  wpuxss_eml_load_media_options_page
 *
 *  Ensure compatibility with default options-media.php for third-parties
 *
 *  @since    2.3
 *  @created  14/06/16
 */

if ( ! function_exists( 'wpuxss_eml_load_media_options_page' ) ) {

    function wpuxss_eml_load_media_options_page() {

        global $pagenow;

        $hook_suffix = $pagenow = 'options-media.php';

        do_action( "load-{$hook_suffix}" );
        do_action( 'admin_enqueue_scripts', $hook_suffix );
        do_action( "admin_print_styles-{$hook_suffix}" );
        do_action( "admin_print_scripts-{$hook_suffix}" );
        do_action( "admin_head-{$hook_suffix}" );

        add_filter( 'admin_body_class', 'wpuxss_eml_admin_body_class_for_media_options_page' );
        add_filter( 'admin_title', 'wpuxss_eml_admin_title_for_media_options_page', 10, 2 );
    }
}



/**
 *  wpuxss_eml_admin_body_class_for_media_options_page
 *
 *  Ensure compatibility with default options-media.php for third-parties
 *
 *  @since    2.3.6
 *  @created  16/12/16
 */

if ( ! function_exists( 'wpuxss_eml_admin_body_class_for_media_options_page' ) ) {

    function wpuxss_eml_admin_body_class_for_media_options_page( $admin_body_class ) {

        $hook_suffix = 'options-media.php';

        $admin_body_class .= preg_replace('/[^a-z0-9_\-]+/i', '-', $hook_suffix);

        return $admin_body_class;
    }
}



/**
 *  wpuxss_eml_admin_title_for_media_options_page
 *
 *  @since    2.3.6
 *  @created  16/12/16
 */

if ( ! function_exists( 'wpuxss_eml_admin_title_for_media_options_page' ) ) {

    function wpuxss_eml_admin_title_for_media_options_page( $admin_title, $title ) {

        $admin_title = __('Media Settings','enhanced-media-library') . $admin_title;

        return $admin_title;
    }
}



/**
 *  wpuxss_eml_admin_title_for_media_options_sub_pages
 *
 *  @since    2.6
 *  @created  10/02/18
 */

if ( ! function_exists( 'wpuxss_eml_admin_title_for_media_options_sub_pages' ) ) {

    function wpuxss_eml_admin_title_for_media_options_sub_pages( $admin_title, $title ) {

        global $pagenow;


        if ( 'options-general.php' !== $pagenow ) {
            return $admin_title;
        }


        $page = isset( $_GET['page'] ) && in_array( $_GET['page'], array('media','media-library','media-taxonomies','mime-types') ) ? $_GET['page'] : '';

        switch ( $page ) {

            case 'media-library' :
                $admin_title = __('Media Library Settings', 'enhanced-media-library') . $admin_title;
                break;

            case 'media-taxonomies' :
                $admin_title = __('Media Taxonomies Settings', 'enhanced-media-library') . $admin_title;
                break;

            case 'mime-types' :
                $admin_title = __('MIME Types Settings', 'enhanced-media-library') . $admin_title;
                break;
        }

        return $admin_title;
    }
}



/**
 *  wpuxss_eml_media_options_page
 *
 *  Ensure compatibility with default options-media.php for third-parties
 *
 *  @since    2.3.6
 *  @created  16/12/16
 */

if ( ! function_exists( 'wpuxss_eml_media_options_page' ) ) {

    function wpuxss_eml_media_options_page() {

        $hook_suffix = 'options-media.php';

        do_action( $hook_suffix );
    }
}



/**
 *  wpuxss_eml_print_media_settings_tabs
 *
 *  @since    2.2.1
 *  @created  11/04/16
 */

if ( ! function_exists( 'wpuxss_eml_print_media_settings_tabs' ) ) {

    function wpuxss_eml_print_media_settings_tabs( $active ) { ?>

        <h2 class="nav-tab-wrapper wp-clearfix" id="eml-options-media-tabs">
            <a href="<?php echo get_admin_url( null, 'options-general.php?page=media' ); ?>" class="nav-tab<?php echo ( 'media' == $active ) ? ' nav-tab-active' : ''; ?>"><?php _e( 'General', 'enhanced-media-library' ); ?></a>
            <a href="<?php echo get_admin_url( null, 'options-general.php?page=media-library' ); ?>" class="nav-tab<?php echo ( 'library' == $active ) ? ' nav-tab-active' : ''; ?>"><?php _e( 'Media Library', 'enhanced-media-library' ); ?></a>
            <a href="<?php echo get_admin_url( null, 'options-general.php?page=media-taxonomies' ); ?>" class="nav-tab<?php echo ( 'taxonomies' == $active ) ? ' nav-tab-active' : ''; ?>"><?php _e( 'Media Taxonomies', 'enhanced-media-library' ); ?></a>
            <a href="<?php echo get_admin_url( null, 'options-general.php?page=mime-types' ); ?>" class="nav-tab<?php echo ( 'mimetypes' == $active ) ? ' nav-tab-active' : ''; ?>"><?php _e( 'MIME Types', 'enhanced-media-library' ); ?></a>
        </h2>

    <?php
    }
}



/**
 *  wpuxss_eml_print_media_settings
 *
 *  Based on wp-admin/options-media.php
 *
 *  @since    2.2.1
 *  @created  11/04/16
 */

if ( ! function_exists( 'wpuxss_eml_print_media_settings' ) ) {

    function wpuxss_eml_print_media_settings() {

        if ( ! current_user_can( 'manage_options' ) )
            wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['media_settings'] )
                wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );
        }


        $title = __('Media Settings');
        ?>

        <div class="wrap">
        <h1><?php echo esc_html( $title ); ?></h1>

        <?php wpuxss_eml_print_media_settings_tabs( 'media' ); ?>

        <form action="options.php" method="post">
        <?php settings_fields('media'); ?>

        <h2 class="title"><?php _e('Image sizes') ?></h2>
        <p><?php _e( 'The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media Library.' ); ?></p>

        <table class="form-table">
        <tr>
        <th scope="row"><?php _e('Thumbnail size') ?></th>
        <td>
        <label for="thumbnail_size_w"><?php _e('Width'); ?></label>
        <input name="thumbnail_size_w" type="number" step="1" min="0" id="thumbnail_size_w" value="<?php form_option('thumbnail_size_w'); ?>" class="small-text" />
        <label for="thumbnail_size_h"><?php _e('Height'); ?></label>
        <input name="thumbnail_size_h" type="number" step="1" min="0" id="thumbnail_size_h" value="<?php form_option('thumbnail_size_h'); ?>" class="small-text" />
        <p><input name="thumbnail_crop" type="checkbox" id="thumbnail_crop" value="1" <?php checked('1', get_option('thumbnail_crop')); ?>/>
        <label for="thumbnail_crop"><?php _e('Crop thumbnail to exact dimensions (normally thumbnails are proportional)'); ?></label></p>
        </td>
        </tr>

        <tr>
        <th scope="row"><?php _e('Medium size') ?></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e('Medium size'); ?></span></legend>
        <label for="medium_size_w"><?php _e('Max Width'); ?></label>
        <input name="medium_size_w" type="number" step="1" min="0" id="medium_size_w" value="<?php form_option('medium_size_w'); ?>" class="small-text" />
        <br />
        <label for="medium_size_h"><?php _e('Max Height'); ?></label>
        <input name="medium_size_h" type="number" step="1" min="0" id="medium_size_h" value="<?php form_option('medium_size_h'); ?>" class="small-text" />
        </fieldset></td>
        </tr>

        <tr>
        <th scope="row"><?php _e('Large size') ?></th>
        <td><fieldset><legend class="screen-reader-text"><span><?php _e('Large size'); ?></span></legend>
        <label for="large_size_w"><?php _e('Max Width'); ?></label>
        <input name="large_size_w" type="number" step="1" min="0" id="large_size_w" value="<?php form_option('large_size_w'); ?>" class="small-text" />
        <br />
        <label for="large_size_h"><?php _e('Max Height'); ?></label>
        <input name="large_size_h" type="number" step="1" min="0" id="large_size_h" value="<?php form_option('large_size_h'); ?>" class="small-text" />
        </fieldset></td>
        </tr>

        <?php do_settings_fields('media', 'default'); ?>
        </table>

        <?php
        /**
         * @global array $wp_settings
         */
        if ( isset( $GLOBALS['wp_settings']['media']['embeds'] ) ) : ?>
        <h2 class="title"><?php _e('Embeds') ?></h2>
        <table class="form-table">
        <?php do_settings_fields( 'media', 'embeds' ); ?>
        </table>
        <?php endif; ?>

        <?php if ( !is_multisite() ) : ?>
        <h2 class="title"><?php _e('Uploading Files'); ?></h2>
        <table class="form-table">
        <?php
        // If upload_url_path is not the default (empty), and upload_path is not the default ('wp-content/uploads' or empty)
        if ( get_option('upload_url_path') || ( get_option('upload_path') != 'wp-content/uploads' && get_option('upload_path') ) ) :
        ?>
        <tr>
        <th scope="row"><label for="upload_path"><?php _e('Store uploads in this folder'); ?></label></th>
        <td><input name="upload_path" type="text" id="upload_path" value="<?php echo esc_attr(get_option('upload_path')); ?>" class="regular-text code" />
        <p class="description"><?php
        	/* translators: %s: wp-content/uploads */
        	printf( __( 'Default is %s' ), '<code>wp-content/uploads</code>' );
        ?></p>
        </td>
        </tr>

        <tr>
        <th scope="row"><label for="upload_url_path"><?php _e('Full URL path to files'); ?></label></th>
        <td><input name="upload_url_path" type="text" id="upload_url_path" value="<?php echo esc_attr( get_option('upload_url_path')); ?>" class="regular-text code" />
        <p class="description"><?php _e('Configuring this is optional. By default, it should be blank.'); ?></p>
        </td>
        </tr>
        <?php endif; ?>
        <tr>
        <th scope="row" colspan="2" class="th-full">
        <label for="uploads_use_yearmonth_folders">
        <input name="uploads_use_yearmonth_folders" type="checkbox" id="uploads_use_yearmonth_folders" value="1"<?php checked('1', get_option('uploads_use_yearmonth_folders')); ?> />
        <?php _e('Organize my uploads into month- and year-based folders'); ?>
        </label>
        </th>
        </tr>

        <?php do_settings_fields('media', 'uploads'); ?>
        </table>
        <?php endif; ?>

        <?php do_settings_sections('media'); ?>

        <?php submit_button(); ?>

        </form>

        </div>

        <?php
    }
}



/**
 *  wpuxss_eml_medialibrary_options_page_scripts
 *
 *  @since    2.2.1
 *  @created  11/04/16
 */

if ( ! function_exists( 'wpuxss_eml_medialibrary_options_page_scripts' ) ) {

    function wpuxss_eml_medialibrary_options_page_scripts() {

        global $wpuxss_eml_dir;

        wp_enqueue_script(
            'wpuxss-eml-medialibrary-options-script',
            $wpuxss_eml_dir . 'js/eml-medialibrary-options.js',
            array( 'jquery' ),
            EML_VERSION,
            true
        );
    }
}



/**
 *  wpuxss_eml_taxonomies_options_page_scripts
 *
 *  @since    2.2
 *  @created  08/03/16
 */

if ( ! function_exists( 'wpuxss_eml_taxonomies_options_page_scripts' ) ) {

    function wpuxss_eml_taxonomies_options_page_scripts() {

        global $wpuxss_eml_dir;

        wp_enqueue_script(
            'wpuxss-eml-taxonomies-options-script',
            $wpuxss_eml_dir . 'js/eml-taxonomies-options.js',
            array( 'jquery', 'underscore', 'wpuxss-eml-admin-script' ),
            EML_VERSION,
            true
        );

        $l10n_data = array(
            'edit' => __( 'Edit', 'enhanced-media-library' ),
            'close' => __( 'Close', 'enhanced-media-library' ),
            'view' => __( 'View', 'enhanced-media-library' ),
            'update' => __( 'Update', 'enhanced-media-library' ),
            'add_new' => __( 'Add New', 'enhanced-media-library' ),
            'new' => __( 'New', 'enhanced-media-library' ),
            'name' => __( 'Name', 'enhanced-media-library' ),
            'parent' => __( 'Parent', 'enhanced-media-library' ),
            'all' => __( 'All', 'enhanced-media-library' ),
            'search' => __( 'Search', 'enhanced-media-library' ),

            'tax_new' => __( 'New Taxonomy', 'enhanced-media-library' ),

            'tax_deletion_confirm_title' => __( 'Remove Taxonomy', 'enhanced-media-library' ),
            'tax_deletion_confirm_text_p1' => '<p>' . __( 'Taxonomy will be removed.', 'enhanced-media-library' ) . '</p>',
            'tax_deletion_confirm_text_p2' => '<p>' . __( 'Taxonomy terms (categories) will remain intact in the database. If you create a taxonomy with the same name in the future, its terms (categories) will be available again.', 'enhanced-media-library' ) . '</p>',
            'tax_deletion_confirm_text_p3' => '<p>' . __( 'Media items will remain intact.', 'enhanced-media-library' ) . '</p>',
            'tax_deletion_confirm_text_p4' => '<p>' . __( 'Are you still sure?', 'enhanced-media-library' ) . '</p>',
            'tax_deletion_yes' => __( 'Yes, remove taxonomy', 'enhanced-media-library' ),

            'tax_error_duplicate_title' => __( 'Duplicate', 'enhanced-media-library' ),
            'tax_error_duplicate_text' => __( 'Taxonomy with the same name already exists. Please chose other one.', 'enhanced-media-library' ),

            'tax_error_empty_fileds_title' => __( 'Empty Fields', 'enhanced-media-library' ),
            'tax_error_wrong_taxname_title' => __( 'Wrong Taxonomy Name', 'enhanced-media-library' ),
            'tax_error_wrong_slug_title' => __( 'Wrong Slug', 'enhanced-media-library' ),

            'tax_error_empty_both' => __( 'Please choose Singular and Plural names for all new taxomonies.', 'enhanced-media-library' ),
            'tax_error_empty_singular' => __( 'Please choose Singular name for all new taxomonies.', 'enhanced-media-library' ),
            'tax_error_empty_plural' => __( 'Please choose Plural name for all new taxomonies.', 'enhanced-media-library' ),

            'tax_error_empty_taxname' => __( 'Taxonomy Name cannot be empty. If it was not generated from the Singular name please enter it manually.', 'enhanced-media-library' ),
            'tax_error_wrong_taxname' => __( 'Taxonomy Name should only contain lowercase Latin letters, the underscore character ( _ ), and be 3-32 characters long.', 'enhanced-media-library' ),
            'tax_error_wrong_slug' => __( 'Slug should only contain lowercase Latin letters, numbers, underscore ( _ ) or hyphen ( - ) characters.', 'enhanced-media-library' ),

            'okay' => __( 'Ok', 'enhanced-media-library' ),
            'cancel' => __( 'Cancel', 'enhanced-media-library' ),

            'sync_warning_title' => __( 'Synchronize Now', 'enhanced-media-library' ),
            'sync_warning_text' => __( 'This operation cannot be canceled! Are you still sure?', 'enhanced-media-library' ),
            'sync_warning_yes' => __( 'Synchronize', 'enhanced-media-library' ),
            'sync_warning_no' => __( 'Cancel', 'enhanced-media-library' ),
            'in_progress_sync_text' => __( 'Synchronizing...', 'enhanced-media-library' ),

            'bulk_edit_nonce' => wp_create_nonce( 'eml-bulk-edit-nonce' )
        );

        wp_localize_script(
            'wpuxss-eml-taxonomies-options-script',
            'wpuxss_eml_taxonomies_options_l10n_data',
            $l10n_data
        );
    }
}



/**
 *  wpuxss_eml_mimetype_options_page_scripts
 *
 *  @since    2.2
 *  @created  08/03/16
 */

if ( ! function_exists( 'wpuxss_eml_mimetype_options_page_scripts' ) ) {

    function wpuxss_eml_mimetype_options_page_scripts() {

        global $wpuxss_eml_dir;

        wp_enqueue_script(
            'wpuxss-eml-mimetype-options-script',
            $wpuxss_eml_dir . 'js/eml-mimetype-options.js',
            array( 'jquery', 'underscore' ),
            EML_VERSION,
            true
        );

        $l10n_data = array(
            'mime_restoring_confirm_title' => __( 'Restore WordPress default MIME Types', 'enhanced-media-library' ),
            'mime_restoring_confirm_text' => __( 'Warning! All your custom MIME Types will be deleted by this operation.', 'enhanced-media-library' ),
            'mime_restoring_yes' => __( 'Restore Defaults', 'enhanced-media-library' ),
            'in_progress_restoring_text' => __( 'Restoring...', 'enhanced-media-library' ),

            'okay' => __( 'Ok', 'enhanced-media-library' ),
            'cancel' => __( 'Cancel', 'enhanced-media-library' ),

            'mime_error_cannot_save_title' => __( 'MIME Types cannot be saved', 'enhanced-media-library' ),
            'mime_error_empty_fields' => __( 'Please fill into all fields.', 'enhanced-media-library' ),
            'mime_error_duplicate' => __( 'Duplicate extensions or MIME types. Please choose other one.', 'enhanced-media-library' )
        );

        wp_localize_script(
            'wpuxss-eml-mimetype-options-script',
            'wpuxss_eml_mimetype_options_l10n_data',
            $l10n_data
        );
    }
}



/**
 *  wpuxss_eml_options_page_scripts
 *
 *  @since    2.2
 *  @created  08/03/16
 */

if ( ! function_exists( 'wpuxss_eml_options_page_scripts' ) ) {

    function wpuxss_eml_options_page_scripts() {

        global $wpuxss_eml_dir;


        wp_enqueue_script(
            'wpuxss-eml-options-script',
            $wpuxss_eml_dir . 'js/eml-options.js',
            array( 'jquery', 'underscore', 'wpuxss-eml-admin-script' ),
            EML_VERSION,
            true
        );

        $l10n_data = array(
            'cleanup_warning_title' => __( 'Complete Cleanup', 'enhanced-media-library' ),
            'cleanup_warning_text_p1' => '<p>' . __( 'You are about to <strong style="text-transform:uppercase">delete all plugin data</strong> from the database including backups.', 'enhanced-media-library' ) . '</p>',
            'cleanup_warning_text_p2' => '<p>' . __( 'This operation cannot be canceled! Are you still sure?', 'enhanced-media-library') . '</p>',
            'cleanup_warning_yes' => __( 'Yes, delete all data', 'enhanced-media-library' ),
            'in_progress_cleanup_text' => __( 'Cleaning...', 'enhanced-media-library' ),
            'cancel' => __( 'Cancel', 'enhanced-media-library' )
        );

        wp_localize_script(
            'wpuxss-eml-options-script',
            'wpuxss_eml_options_l10n_data',
            $l10n_data
        );
    }
}



/**
 *  wpuxss_eml_print_settings
 *
 *  @since    2.1
 *  @created  25/10/15
 */

if ( ! function_exists( 'wpuxss_eml_print_settings' ) ) {

    function wpuxss_eml_print_settings() {

        if ( ! current_user_can( 'manage_options' ) )
            wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );


        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['utilities'] )
                wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );
        } ?>


        <div id="wpuxss-eml-global-options-wrap" class="wrap eml-options">

            <h2><?php _e( 'Enhanced Media Library Utilities', 'enhanced-media-library' ); ?></h2>

            <div id="poststuff">

                <div id="post-body" class="metabox-holder columns-2">

                    <div id="postbox-container-2" class="postbox-container">

                        <div class="postbox">

                            <h3 class="hndle"><?php _e( 'Export', 'enhanced-media-library' ); ?></h3>

                            <div class="inside">

                                <ul>
                                    <li><strong><?php _e( 'Plugin settings to export:', 'enhanced-media-library' ); ?></strong></li>
                                    <li><?php _e( 'Settings > Media Library', 'enhanced-media-library' ); ?></li>
                                    <li><?php _e( 'Settings > Media Taxonomies', 'enhanced-media-library' ); ?></li>
                                    <li><?php _e( 'Settings > MIME Types', 'enhanced-media-library' ); ?></li>
                                </ul>


                                <p><?php _e( 'Use generated JSON file to import the configuration into another website.', 'enhanced-media-library' ); ?></p>

                                <form method="post">
                                    <input type='hidden' name='eml-settings-export' />
                                    <?php wp_nonce_field( 'eml_settings_export_nonce', 'eml-settings-export-nonce' ); ?>
                                    <?php submit_button( __( 'Export Plugin Settings', 'enhanced-media-library' ), 'primary', 'eml-submit-settings-export', true ); ?>
                                </form>

                            </div>

                        </div>


                        <div class="postbox">

                            <h3 class="hndle"><?php _e( 'Import', 'enhanced-media-library' ); ?></h3>

                            <div class="inside">

                                <ul>
                                    <li><strong><?php _e( 'Plugin settings to import:', 'enhanced-media-library' ); ?></strong></li>
                                    <li><?php _e( 'Settings > Media Library', 'enhanced-media-library' ); ?></li>
                                    <li><?php _e( 'Settings > Media Taxonomies', 'enhanced-media-library' ); ?></li>
                                    <li><?php _e( 'Settings > MIME Types', 'enhanced-media-library' ); ?></li>
                                </ul>

                                <p><?php _e( 'Plugin settings will be imported from a configuration JSON file which can be obtained by exporting the settings on another website using the export button above.', 'enhanced-media-library' ); ?></p>
                                <p><?php _e( 'All plugin settings will be overridden by the import. You will have a chance to restore current data from an automatic backup in case you are not satisfied with the result of the import.', 'enhanced-media-library' ); ?></p>

                                <form method="post" enctype="multipart/form-data">
                                    <p><input type="file" name="import_file"/></p>
                                    <input type='hidden' name='eml-settings-import' />
                                    <?php wp_nonce_field( 'eml_settings_import_nonce', 'eml-settings-import-nonce' ); ?>
                                    <?php submit_button(  __( 'Import Plugin Settings', 'enhanced-media-library' ), 'primary', 'eml-submit-settings-import' ); ?>
                                </form>

                            </div>

                        </div>


                        <?php $wpuxss_eml_backup = get_option( 'wpuxss_eml_backup' ); ?>

                        <div class="postbox">

                            <h3 class="hndle"><?php _e( 'Restore', 'enhanced-media-library' ); ?></h3>

                            <div class="inside">

                                <?php if ( empty( $wpuxss_eml_backup ) ) : ?>

                                    <p><?php _e( 'No backup available at the moment.', 'enhanced-media-library' ); ?></p>

                                    <p><?php _e( 'Backup will be created automatically before any import operation.', 'enhanced-media-library' ); ?></p>

                                <?php else : ?>

                                    <p><?php _e( 'The backup has been automatically created before the latest import operation.', 'enhanced-media-library' ); ?></p>

                                    <ul>
                                        <li><strong><?php _e( 'Plugin settings to restore:', 'enhanced-media-library' ); ?></strong></li>
                                        <li><?php _e( 'Settings > Media Library', 'enhanced-media-library' ); ?></li>
                                        <li><?php _e( 'Settings > Media Taxonomies', 'enhanced-media-library' ); ?></li>
                                        <li><?php _e( 'Settings > MIME Types', 'enhanced-media-library' ); ?></li>
                                    </ul>

                                    <form method="post">
                                        <input type='hidden' name='eml-settings-restore' />
                                        <?php wp_nonce_field( 'eml_settings_restore_nonce', 'eml-settings-restore-nonce' ); ?>
                                        <?php submit_button( __( 'Restore Settings from the Backup', 'enhanced-media-library' ), 'primary', 'eml-submit-settings-restore', true, array( 'id' => 'eml-submit-settings-restore' ) ); ?>
                                    </form>

                                <?php endif; ?>


                            </div>

                        </div>


                        <?php if ( ! is_multisite() || is_network_admin() ) : ?>


                            <div class="postbox">

                                <h3 class="hndle"><?php _e( 'Complete Cleanup', 'enhanced-media-library' ); ?></h3>

                                <div class="inside">

                                    <?php $wpuxss_eml_taxonomies = wpuxss_eml_get_eml_taxonomies(); ?>

                                    <ul>
                                        <li><strong><?php _e( 'What will be deleted:', 'enhanced-media-library' ); ?></strong></li>
                                        <?php foreach( (array) $wpuxss_eml_taxonomies as $taxonomy => $params ) : ?>
                                            <li><?php _e( 'All', 'enhanced-media-library' );
                                            echo ' ' . esc_html( $params['labels']['name'] ); ?></li>
                                        <?php endforeach; ?>
                                        <li><?php _e( 'All plugin options', 'enhanced-media-library' ); ?></li>
                                        <li><?php _e( 'All plugin backups stored in the database', 'enhanced-media-library' ); ?></li>
                                    </ul>

                                    <ul>
                                        <li><strong><?php _e( 'What will remain intact:', 'enhanced-media-library' ); ?></strong></li>
                                        <li><?php _e( 'All media items', 'enhanced-media-library' ); ?></li>
                                        <li><?php _e( 'All taxonomies not listed above', 'enhanced-media-library' ); ?></li>
                                    </ul>

                                    <p><?php _e( 'The plugin cannot delete itself for security reasons. Please delete it manually from the plugin list after the cleanup is complete.', 'enhanced-media-library' ); ?></p>

                                    <p><strong style="color:red;"><?php _e( 'If you are not sure about this operation it\'s HIGHLY RECOMMENDED to create a backup of your database prior to cleanup!', 'enhanced-media-library' ); ?></strong></p>

                                    <form id="eml-form-cleanup" method="post">
                                        <input type='hidden' name='eml-settings-cleanup' />
                                        <?php wp_nonce_field( 'eml_settings_cleanup_nonce', 'eml-settings-cleanup-nonce' ); ?>
                                        <?php submit_button( __( 'Delete All Data & Deactivate', 'enhanced-media-library' ), 'primary', 'eml-submit-settings-cleanup', true ); ?>
                                    </form>

                                </div>

                            </div>

                            <?php do_action( 'wpuxss_eml_extend_settings_page' ); ?>

                        <?php endif; ?>

                    </div>

                    <div id="postbox-container-1" class="postbox-container">

                        <?php wpuxss_eml_print_credits(); ?>

                    </div>

                </div>

            </div>

        </div>

        <?php
    }
}



/**
 *  wpuxss_eml_print_network_settings
 *
 *  @since    2.6
 *  @created  22/04/18
 */

if ( ! function_exists( 'wpuxss_eml_print_network_settings' ) ) {

    function wpuxss_eml_print_network_settings() {

        if ( ! current_user_can( 'manage_network_options' ) )
            wp_die( __('You do not have sufficient permissions to access this page.', 'enhanced-media-library') );


        settings_errors();

        $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() ); ?>


        <div id="wpuxss-eml-global-options-wrap" class="wrap eml-options">

            <h2><?php _e( 'Enhanced Media Library Utilities', 'enhanced-media-library' ); ?></h2>

            <div id="poststuff">

                <div id="post-body" class="metabox-holder columns-2">

                    <div id="postbox-container-2" class="postbox-container">

                        <div class="postbox">

                            <h3 class="hndle" id="eml-license-key-section"><?php _e('Network Settings','enhanced-media-library'); ?></h3>


                            <div class="inside">

                                <?php if ( ! is_plugin_active_for_network( wpuxss_get_eml_basename() ) ) : ?>

                                    <p class="description"><?php _e( 'No settings available. The plugin is not network activated.', 'enhanced-media-library' ); ?></p>

                                <?php else : ?>

                                    <form method="post">

                                        <?php settings_fields( 'eml-network-settings' ); ?>

                                        <table class="form-table">

                                            <tr>
                                                <th scope="row"><?php _e('Media Settings per site','enhanced-media-library'); ?></th>
                                                <td>
                                                    <fieldset>
                                                        <legend class="screen-reader-text"><span><?php _e('Enable Media Settings','enhanced-media-library'); ?></span></legend>
                                                        <label><input name="wpuxss_eml_network_options[media_settings]" type="hidden" value="0" /><input name="wpuxss_eml_network_options[media_settings]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_network_options['media_settings'], true ); ?> /> <?php _e('Allow an individual site admin to edit enhanced Media Settings','enhanced-media-library'); ?></label>
                                                        <p class="description"><?php _e( 'Otherwise, only a network (super) admin can see the menu and edit media settings.', 'enhanced-media-library' ); ?></p>
                                                    </fieldset>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Plugin Utilities per site','enhanced-media-library'); ?></th>
                                                <td>
                                                    <fieldset>
                                                        <legend class="screen-reader-text"><span><?php _e('Enable plugin Utilities','enhanced-media-library'); ?></span></legend>
                                                        <label><input name="wpuxss_eml_network_options[utilities]" type="hidden" value="0" /><input name="wpuxss_eml_network_options[utilities]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_network_options['utilities'], true ); ?> /> <?php _e('Allow an individual site admin to import / export / restore plugin settings and perform the complete cleanup for a specific site','enhanced-media-library'); ?></label>
                                                        <p class="description"><?php _e( 'Otherwise, only a network (super) admin can see the menu and perform those actions.', 'enhanced-media-library' ); ?></p>
                                                    </fieldset>
                                                </td>
                                            </tr>

                                        </table>

                                        <?php submit_button( __( 'Save Changes' ), 'primary', 'eml-submit-network-settings', true ); ?>

                                    </form>

                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="postbox">

                            <h3 class="hndle"><?php _e( 'Complete Cleanup', 'enhanced-media-library' ); ?></h3>

                            <div class="inside">

                                <?php
                                $wpuxss_eml_taxonomies = array();

                                foreach( get_sites( array( 'fields' => 'ids' ) ) as $site_id ) :

                                    switch_to_blog( $site_id );

                                    $wpuxss_eml_taxonomies = array_merge( $wpuxss_eml_taxonomies, wpuxss_eml_get_eml_taxonomies() );

                                    restore_current_blog();

                                endforeach; ?>


                                <ul>
                                    <li><strong><?php _e( 'What will be deleted:', 'enhanced-media-library' ); ?></strong></li>
                                    <?php foreach( (array) $wpuxss_eml_taxonomies as $taxonomy => $params ) : ?>
                                        <li><?php _e( 'All', 'enhanced-media-library' );
                                        echo ' ' . esc_html( $params['labels']['name'] ); ?></li>
                                    <?php endforeach; ?>
                                    <li><?php _e( 'All plugin options on every site', 'enhanced-media-library' ); ?></li>
                                    <li><?php _e( 'Network settings', 'enhanced-media-library' ); ?></li>
                                    <li><?php _e( 'All plugin backups stored in the database', 'enhanced-media-library' ); ?></li>
                                </ul>

                                <ul>
                                    <li><strong><?php _e( 'What will remain intact:', 'enhanced-media-library' ); ?></strong></li>
                                    <li><?php _e( 'All media items', 'enhanced-media-library' ); ?></li>
                                    <li><?php _e( 'All taxonomies not listed above', 'enhanced-media-library' ); ?></li>
                                </ul>

                                <p><?php _e( 'The plugin cannot delete itself for security reasons. Please delete it manually from the plugin list after the cleanup is complete.', 'enhanced-media-library' ); ?></p>

                                <p><strong style="color:red;"><?php _e( 'If you are not sure about this operation it\'s HIGHLY RECOMMENDED to create a backup of your database prior to cleanup!', 'enhanced-media-library' ); ?></strong></p>

                                <form id="eml-form-cleanup" method="post">
                                    <input type='hidden' name='eml-settings-cleanup' />
                                    <?php wp_nonce_field( 'eml_settings_cleanup_nonce', 'eml-settings-cleanup-nonce' ); ?>
                                    <?php submit_button( __( 'Delete All Data & Network Deactivate', 'enhanced-media-library' ), 'primary', 'eml-submit-settings-cleanup', true ); ?>
                                </form>

                            </div>

                        </div>

                        <?php do_action( 'wpuxss_eml_extend_settings_page' ); ?>

                    </div>

                    <div id="postbox-container-1" class="postbox-container">

                        <?php wpuxss_eml_print_credits(); ?>

                    </div>

                </div>

            </div>

        </div>

    <?php
    }
}



/**
 *  wpuxss_eml_update_network_settings
 *
 *  @since    2.6
 *  @created  28/04/18
 */

add_action( 'network_admin_menu', 'wpuxss_eml_update_network_settings' );

if ( ! function_exists( 'wpuxss_eml_update_network_settings' ) ) {

    function wpuxss_eml_update_network_settings() {

        if ( ! isset($_POST['eml-submit-network-settings']) )
            return;

        check_admin_referer( 'eml-network-settings-options' );

        if ( ! current_user_can( 'manage_network_options' ) )
            return;


        $wpuxss_eml_network_options = isset( $_POST['wpuxss_eml_network_options'] ) ? $_POST['wpuxss_eml_network_options'] : array();

        $wpuxss_eml_network_options = wpuxss_eml_tax_options_validate( $wpuxss_eml_network_options );

        update_site_option( 'wpuxss_eml_network_options', $wpuxss_eml_network_options );

        add_settings_error(
            'eml-network-settings',
            'eml_network_settings_saved',
            __('Network settings saved.', 'enhanced-media-library'),
            'updated'
        );
    }
}



/**
 *  wpuxss_eml_settings_export
 *
 *  @since    2.1
 *  @created  25/10/15
 */

add_action( 'admin_init', 'wpuxss_eml_settings_export' );

if ( ! function_exists( 'wpuxss_eml_settings_export' ) ) {

    function wpuxss_eml_settings_export() {

        if ( ! isset( $_POST['eml-settings-export'] ) )
            return;

        if ( ! wp_verify_nonce( $_POST['eml-settings-export-nonce'], 'eml_settings_export_nonce' ) )
            return;

        if ( ! current_user_can( 'manage_options' ) )
            return;

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['utilities'] )
                return;
        }


        $settings = wpuxss_eml_get_settings();

        ignore_user_abort( true );

        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=eml-settings-' . date('m-d-Y_hia') . '.json' );
        header( "Expires: 0" );

        echo json_encode( $settings );

        exit;
    }
}



/**
 *  wpuxss_eml_settings_import
 *
 *  @since    2.1
 *  @created  25/10/15
 */

add_action( 'admin_init', 'wpuxss_eml_settings_import' );

if ( ! function_exists( 'wpuxss_eml_settings_import' ) ) {

    function wpuxss_eml_settings_import() {

        if ( ! isset( $_POST['eml-settings-import'] ) )
            return;

        if ( ! wp_verify_nonce( $_POST['eml-settings-import-nonce'], 'eml_settings_import_nonce' ) )
            return;

        if ( ! current_user_can( 'manage_options' ) )
            return;

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['utilities'] )
                return;
        }


        $import_file = $_FILES['import_file'];

        if ( empty( $import_file['tmp_name'] ) ) {

            add_settings_error(
                'eml-settings',
                'eml_settings_file_absent',
                __('Settings cannot be imported. Please upload a file to import settings.', 'enhanced-media-library'),
                'error'
            );

            return;
        }


        // backup settings
        $settings = wpuxss_eml_get_settings();
        update_option( 'wpuxss_eml_backup', $settings );


        $json_data = file_get_contents( $import_file['tmp_name'] );
        $settings = json_decode( $json_data, true );

        if ( empty( $settings ) ) {

            add_settings_error(
                'eml-settings',
                'eml_settings_wrong_format',
                __('Settings cannot be imported. Please upload a correct JSON file to import settings.', 'enhanced-media-library'),
                'error'
            );

            return;
        }


        update_option( 'wpuxss_eml_taxonomies', $settings['taxonomies'] );
        update_option( 'wpuxss_eml_lib_options', $settings['lib_options'] );
        update_option( 'wpuxss_eml_tax_options', $settings['tax_options'] );
        update_option( 'wpuxss_eml_mimes', $settings['mimes'] );

        do_action( 'wpuxss_eml_pro_set_settings', $settings );

        add_settings_error(
            'eml-settings',
            'eml_settings_imported',
            __('Plugin settings imported.', 'enhanced-media-library'),
            'updated'
        );
    }
}



/**
 *  wpuxss_eml_settings_restoring
 *
 *  @since    2.1
 *  @created  25/10/15
 */

add_action( 'admin_init', 'wpuxss_eml_settings_restoring' );

if ( ! function_exists( 'wpuxss_eml_settings_restoring' ) ) {

    function wpuxss_eml_settings_restoring() {

        if ( ! isset( $_POST['eml-settings-restore'] ) )
            return;

        if ( ! wp_verify_nonce( $_POST['eml-settings-restore-nonce'], 'eml_settings_restore_nonce' ) )
            return;

        if ( ! current_user_can( 'manage_options' ) )
            return;

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['utilities'] )
                return;
        }


        $wpuxss_eml_backup = get_option( 'wpuxss_eml_backup' );

        update_option( 'wpuxss_eml_taxonomies', $wpuxss_eml_backup['taxonomies'] );
        update_option( 'wpuxss_eml_lib_options', $wpuxss_eml_backup['lib_options'] );
        update_option( 'wpuxss_eml_tax_options', $wpuxss_eml_backup['tax_options'] );
        update_option( 'wpuxss_eml_mimes', $wpuxss_eml_backup['mimes'] );

        do_action( 'wpuxss_eml_pro_set_settings', $wpuxss_eml_backup );

        update_option( 'wpuxss_eml_backup', '' );

        add_settings_error(
            'eml-settings',
            'eml_settings_restored',
            __('Plugin settings restored from the backup.', 'enhanced-media-library'),
            'updated'
        );
    }
}



/**
 *  wpuxss_eml_settings_cleanup
 *
 *  @since    2.2
 *  @created  23/02/16
 */

add_action( 'admin_init', 'wpuxss_eml_settings_cleanup' );

if ( ! function_exists( 'wpuxss_eml_settings_cleanup' ) ) {

    function wpuxss_eml_settings_cleanup() {

        if ( ! isset( $_POST['eml-settings-cleanup'] ) )
            return;

        if ( ! wp_verify_nonce( $_POST['eml-settings-cleanup-nonce'], 'eml_settings_cleanup_nonce' ) )
            return;

        if ( ! current_user_can( 'manage_options' ) )
            return;

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['utilities'] )
                return;
        }


        if ( is_multisite()  ) {

            foreach( get_sites( array( 'fields' => 'ids' ) ) as $site_id ) {

                switch_to_blog( $site_id );

                wpuxss_eml_term_relationship_cleanup();
                wpuxss_eml_options_cleanup();
                deactivate_plugins( wpuxss_get_eml_basename() );

                restore_current_blog();
            }
        }
        else {

            wpuxss_eml_term_relationship_cleanup();
            wpuxss_eml_options_cleanup();
        }

        wpuxss_eml_common_options_cleanup();
        wpuxss_eml_transients_cleanup();
        deactivate_plugins( wpuxss_get_eml_basename(), false, is_multisite() );


        wp_safe_redirect( self_admin_url( 'plugins.php' ) );
        exit;
    }
}



/**
 *  wpuxss_eml_term_relationship_cleanup
 *
 *  @since    2.6
 *  @created  28/04/18
 */

if ( ! function_exists( 'wpuxss_eml_term_relationship_cleanup' ) ) {

    function wpuxss_eml_term_relationship_cleanup() {

        global $wpdb;


        foreach ( get_option( 'wpuxss_eml_taxonomies', array() ) as $taxonomy => $params ) {

            $terms = get_terms( $taxonomy, array( 'fields' => 'all', 'get' => 'all' ) );
            $term_pairs = wpuxss_eml_get_media_term_pairs( $terms, 'id=>tt_id' );

            if ( (bool) $params['eml_media'] ) {

                foreach ( $term_pairs as $id => $tt_id ) {
                    wp_delete_term( $id, $taxonomy );
                }

                $wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );
                delete_option( $taxonomy . '_children' );
            }
            elseif ( ! empty( $term_pairs ) ) {

                $deleted_tt_ids = array();
                $rows2remove_format = join( ', ', array_fill( 0, count( $term_pairs ), '%d' ) );

                $results = $wpdb->get_results( $wpdb->prepare(
                	"
                        SELECT $wpdb->term_relationships.term_taxonomy_id, $wpdb->term_relationships.object_id
                        FROM $wpdb->term_relationships
                        INNER JOIN $wpdb->posts
                        ON $wpdb->term_relationships.object_id = $wpdb->posts.ID
                        WHERE $wpdb->posts.post_type = 'attachment'
                        AND $wpdb->term_relationships.term_taxonomy_id IN ($rows2remove_format)
                	",
                    $term_pairs
                ) );

                foreach ( $results as $result ) {
                    $deleted_tt_ids[$result->object_id][] = $result->term_taxonomy_id;
                }

                foreach( $deleted_tt_ids as $attachment_id => $tt_ids ) {
                    do_action( 'delete_term_relationships', $attachment_id, $tt_ids );
                }

                $removed = $wpdb->query( $wpdb->prepare(
                	"
                        DELETE $wpdb->term_relationships.* FROM $wpdb->term_relationships
                        INNER JOIN $wpdb->posts
                        ON $wpdb->term_relationships.object_id = $wpdb->posts.ID
                        WHERE $wpdb->posts.post_type = 'attachment'
                        AND $wpdb->term_relationships.term_taxonomy_id IN ($rows2remove_format)
                	",
                    $term_pairs
                ) );

                if ( false !== $removed ) {

                    foreach( $deleted_tt_ids as $attachment_id => $tt_ids ) {
                        do_action( 'deleted_term_relationships', $attachment_id, $tt_ids );
                    }
                }
            }
        }
    }
}



/**
 *  wpuxss_eml_options_cleanup
 *
 *  @since    2.6
 *  @created  28/04/18
 */

if ( ! function_exists( 'wpuxss_eml_options_cleanup' ) ) {

    function wpuxss_eml_options_cleanup() {

        $options = array(
            'wpuxss_eml_taxonomies',
            'wpuxss_eml_lib_options',
            'wpuxss_eml_tax_options',
            'wpuxss_eml_mimes_backup',
            'wpuxss_eml_mimes',
            'wpuxss_eml_backup',
            'wpuxss_eml_version'
        );

        $options = apply_filters( 'wpuxss_eml_pro_add_options', $options );

        foreach ( $options as $option ) {
            delete_option( $option );
        }
    }
}



/**
 *  wpuxss_eml_common_options_cleanup
 *
 *  @since    2.6
 *  @created  28/04/18
 */

if ( ! function_exists( 'wpuxss_eml_common_options_cleanup' ) ) {

    function wpuxss_eml_common_options_cleanup() {

        $options = array(
            'wpuxss_eml_version'
        );

        if ( is_multisite() ) {
            $options[] = 'wpuxss_eml_network_options';
        }

        $options = apply_filters( 'wpuxss_eml_pro_add_common_options', $options );

        foreach ( $options as $option ) {
            delete_site_option( $option );
        }
    }
}



/**
 *  wpuxss_eml_transients_cleanup
 *
 *  @since    2.6
 *  @created  28/04/18
 */

if ( ! function_exists( 'wpuxss_eml_transients_cleanup' ) ) {

    function wpuxss_eml_transients_cleanup() {

        $transients = array();

        $transients = apply_filters( 'wpuxss_eml_pro_add_transients', $transients );

        foreach ( $transients as $transient ) {
            delete_site_transient( $transient );
        }
    }
}



/**
 *  wpuxss_eml_get_settings
 *
 *  @since    2.1
 *  @created  25/10/15
 */

if ( ! function_exists( 'wpuxss_eml_get_settings' ) ) {

    function wpuxss_eml_get_settings() {

        $wpuxss_eml_taxonomies = get_option( 'wpuxss_eml_taxonomies' );
        $wpuxss_eml_lib_options = get_option( 'wpuxss_eml_lib_options' );
        $wpuxss_eml_tax_options = get_option( 'wpuxss_eml_tax_options' );
        $wpuxss_eml_mimes = get_option( 'wpuxss_eml_mimes' );

        $settings = array (
            'taxonomies' => $wpuxss_eml_taxonomies,
            'lib_options' => $wpuxss_eml_lib_options,
            'tax_options' => $wpuxss_eml_tax_options,
            'mimes' => $wpuxss_eml_mimes,
        );

        $settings = apply_filters( 'wpuxss_eml_pro_get_settings', $settings );

        return $settings;
    }
}



/**
 *  wpuxss_eml_print_media_library_options
 *
 *  @type     callback function
 *  @since    1.0
 *  @created  28/09/13
 */

if ( ! function_exists( 'wpuxss_eml_print_media_library_options' ) ) {

    function wpuxss_eml_print_media_library_options() {

        if ( ! current_user_can( 'manage_options' ) )
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'enhanced-media-library' ) );

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['media_settings'] )
                wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );
        }


        $wpuxss_eml_lib_options = get_option( 'wpuxss_eml_lib_options' );
        $title = __('Media Settings'); ?>


        <div id="wpuxss-eml-media-library-options-wrap" class="wrap eml-options">

            <h1><?php echo esc_html( $title ); ?></h1>

            <?php wpuxss_eml_print_media_settings_tabs( 'library' ); ?>

            <div id="poststuff">

                <div id="post-body" class="metabox-holder">

                    <div id="postbox-container-2" class="postbox-container">

                        <form id="wpuxss-eml-form-taxonomies" method="post" action="options.php">

                            <?php settings_fields( 'media-library' ); ?>


                            <h2><?php _e('Filters','enhanced-media-library'); ?></h2>

                            <div class="postbox">

                                <div class="inside">

                                    <table class="form-table">

                                        <tr>
                                            <th scope="row"><?php _e('Force filters','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Force filters','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_lib_options[force_filters]" type="hidden" value="0" /><input name="wpuxss_eml_lib_options[force_filters]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_lib_options['force_filters'], true ); ?> /> <?php _e('Show media filters for ANY Media Popup','enhanced-media-library'); ?></label>
                                                    <p class="description"><?php _e( 'Try this if filters are not shown for third-party plugins or themes.', 'enhanced-media-library' ); ?></p>
                                                </fieldset>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php _e('Filters to show', 'enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Filters to show', 'enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_lib_options[filters_to_show][]" type="hidden" value="none" /><input name="wpuxss_eml_lib_options[filters_to_show][]" type="checkbox" value="types" <?php echo in_array('types', $wpuxss_eml_lib_options['filters_to_show']) ? 'checked' : ''; ?> /> <?php _e('Types','enhanced-media-library'); ?>
                                                    <em>(<?php _e( 'Can be disabled for Grid Mode only', 'enhanced-media-library' ); ?>)</em></label><br />
                                                    <label><input name="wpuxss_eml_lib_options[filters_to_show][]" type="checkbox" value="dates" <?php echo in_array('dates', $wpuxss_eml_lib_options['filters_to_show']) ? 'checked' : ''; ?> /> <?php _e('Dates','enhanced-media-library'); ?></label><br />
                                                    <label><input name="wpuxss_eml_lib_options[filters_to_show][]" type="checkbox" value="authors" <?php echo in_array('authors', $wpuxss_eml_lib_options['filters_to_show']) ? 'checked' : ''; ?> /> <?php _e('Authors','enhanced-media-library'); ?></label><br />
                                                    <label><input name="wpuxss_eml_lib_options[filters_to_show][]" type="checkbox" value="taxonomies" <?php echo in_array('taxonomies', $wpuxss_eml_lib_options['filters_to_show']) ? 'checked' : ''; ?> /> <?php _e('Media Taxonomies','enhanced-media-library'); ?></label>
                                                </fieldset>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php _e('Show count','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Show count','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_lib_options[show_count]" type="hidden" value="0" /><input name="wpuxss_eml_lib_options[show_count]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_lib_options['show_count'], true ); ?> /> <?php _e('Show item count per category for media filters','enhanced-media-library'); ?></label>
                                                    <p class="description"><?php _e( 'Disable this if it slows down your site admin. The problem is resolved in the upcoming major update v3.0', 'enhanced-media-library' ); ?></p>
                                                </fieldset>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php _e('Include children','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Include children','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_lib_options[include_children]" type="hidden" value="0" /><input name="wpuxss_eml_lib_options[include_children]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_lib_options['include_children'], true ); ?> /> <?php _e('Show media items of child media categories as a result of filtering', 'enhanced-media-library'); ?></label>
                                                </fieldset>
                                            </td>
                                        </tr>

                                    </table>

                                    <?php submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-lib-settings-filters' ) ); ?>

                                </div>

                            </div>


                            <?php do_action( 'wpuxss_eml_extend_library_option_page' ); ?>


                            <h2><?php _e('Order','enhanced-media-library'); ?></h2>

                            <div class="postbox">

                                <div class="inside">

                                    <table class="form-table">

                                        <tr>
                                            <th scope="row"><label for="wpuxss_eml_lib_options[media_orderby]"><?php _e('Order media items by','enhanced-media-library'); ?></label></th>
                                            <td>
                                                <select name="wpuxss_eml_lib_options[media_orderby]" id="wpuxss_eml_lib_options_media_orderby">
                                                    <option value="date" <?php selected( $wpuxss_eml_lib_options['media_orderby'], 'date' ); ?>><?php _e('Date','enhanced-media-library'); ?></option>
                                                    <option value="title" <?php selected( $wpuxss_eml_lib_options['media_orderby'], 'title' ); ?>><?php _e('Title','enhanced-media-library'); ?></option>
                                                    <option value="menuOrder" <?php selected( $wpuxss_eml_lib_options['media_orderby'], 'menuOrder' ); ?>><?php _e('Custom Order','enhanced-media-library'); ?></option>
                                                </select>
                                                <?php _e('For media library and media popups','enhanced-media-library'); ?>
                                                <p class="description"><?php _e( 'Allows changing media items order by drag and drop with Custom Order value.', 'enhanced-media-library' ); ?></p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><label for="wpuxss_eml_lib_options[media_order]"><?php _e('Sort order','enhanced-media-library'); ?></label></th>
                                            <td>
                                                <select name="wpuxss_eml_lib_options[media_order]" id="wpuxss_eml_lib_options_media_order">
                                                    <option value="ASC" <?php selected( $wpuxss_eml_lib_options['media_order'], 'ASC' ); ?>><?php _e('Ascending','enhanced-media-library'); ?></option>
                                                    <option value="DESC" <?php selected( $wpuxss_eml_lib_options['media_order'], 'DESC' ); ?>><?php _e('Descending','enhanced-media-library'); ?></option>
                                                </select>
                                                <?php _e('For media library and media popups','enhanced-media-library'); ?>
                                            </td>
                                        </tr>

                                        <tr id="wpuxss_eml_lib_options_natural_sort">
                                            <th scope="row"><?php _e('Natural sort order','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Natural sort order','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_lib_options[natural_sort]" type="hidden" value="0" /><input name="wpuxss_eml_lib_options[natural_sort]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_lib_options['natural_sort'], true ); ?> /> <?php _e('Apply human-friendly sort order to Media Library and Galleries','enhanced-media-library'); ?></label>
                                                    <p class="description"><?php _e( 'Example: [1, 2, 3, 10, 18, 22, abc-2, abc-11] instead of [1, 10, 18, 2, 22, 3, abc-11, abc-2]', 'enhanced-media-library' );  ?></p>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-lib-settings-order' ) ); ?>

                                </div>

                            </div>


                            <h2><?php _e('Grid Mode','enhanced-media-library'); ?></h2>

                            <div class="postbox">

                                <div class="inside">

                                    <table class="form-table">

                                        <tr>
                                            <th scope="row"><?php _e('Show caption','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Show caption','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_lib_options[grid_show_caption]" type="hidden" value="0" /><input id="wpuxss_eml_lib_options_grid_show_caption" name="wpuxss_eml_lib_options[grid_show_caption]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_lib_options['grid_show_caption'], true ); ?> /> <?php _e('Add text caption for media item thumbnails', 'enhanced-media-library'); ?></label>
                                                </fieldset>
                                            </td>
                                        </tr>

                                        <tr id="wpuxss_eml_lib_options_grid_caption_type">
                                            <th scope="row"><label for="wpuxss_eml_lib_options[media_order]"><?php _e('Caption type','enhanced-media-library'); ?></label></th>
                                            <td>
                                                <select name="wpuxss_eml_lib_options[grid_caption_type]">
                                                    <option value="title" <?php selected( $wpuxss_eml_lib_options['grid_caption_type'], 'title' ); ?>><?php _e('Title','enhanced-media-library'); ?></option>
                                                    <option value="filename" <?php selected( $wpuxss_eml_lib_options['grid_caption_type'], 'filename' ); ?>><?php _e('Filename','enhanced-media-library'); ?></option>
                                                    <option value="caption" <?php selected( $wpuxss_eml_lib_options['grid_caption_type'], 'caption' ); ?>><?php _e('Caption','enhanced-media-library'); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-lib-settings-grid-mode' ) ); ?>

                                </div>

                            </div>


                            <h2><?php _e('Media Shortcodes','enhanced-media-library'); ?></h2>

                            <div class="postbox">

                                <div class="inside">

                                    <table class="form-table">

                                        <tr>
                                            <th scope="row"><?php _e('Enhanced media shortcodes','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Enhanced media shortcodes','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_lib_options[enhance_media_shortcodes]" type="hidden" value="0" /><input name="wpuxss_eml_lib_options[enhance_media_shortcodes]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_lib_options['enhance_media_shortcodes'], true ); ?> /> <?php _e('Enhance WordPress media shortcodes to make them understand media taxonomies, upload date, and media items number limit','enhanced-media-library'); ?></label>
                                                    <p class="description"><?php _e( 'Gallery example:', 'enhanced-media-library' );  ?> [gallery media_category="5" limit="10" monthnum="12" year="2015"]</p>
                                                    <p class="description"><?php _e( 'Audio playlist example:', 'enhanced-media-library' ); ?> [playlist media_category="5" limit="10" monthnum="12" year="2015"]</p>
                                                    <p class="description"><?php _e( 'Video playlist example:', 'enhanced-media-library' ); ?> [playlist type="video" media_category="5" limit="10" monthnum="12" year="2015"]</p>
                                                    <p class="description"><?php
                                                    printf(
                                                        '<strong style="color:red">%s:</strong> ',
                                                        __( 'Warning', 'enhanced-media-library' )
                                                    );
                                                    printf(
                                                        __( 'Incompatibility with other gallery plugins or themes possible! <a href="%s">Learn more</a>.', 'enhanced-media-library' ),
                                                        esc_url('https://wpuxsolutions.com/documents/enhanced-media-library/enhanced-gallery-possible-conflicts/')
                                                    );
                                                    echo ' ';
                                                    printf(
                                                        __( 'Please check out your gallery front-end and back-end functionality once this option activated. If you find an issue please inform plugin authors at %s or %s.', 'enhanced-media-library' ),
                                                        '<a href="https://wordpress.org/support/plugin/enhanced-media-library">wordpress.org</a>',
                                                        '<a href="https://wpuxsolutions.com/support/create-new-ticket/">wpuxsolutions.com</a>'
                                                    ); ?></p>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-lib-settings-media-shortcode' ) ); ?>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <?php
    }
}



/**
 *  wpuxss_eml_print_taxonomies_options
 *
 *  @type     callback function
 *  @since    1.0
 *  @created  28/09/13
 */

if ( ! function_exists( 'wpuxss_eml_print_taxonomies_options' ) ) {

    function wpuxss_eml_print_taxonomies_options() {

        if ( ! current_user_can( 'manage_options' ) )
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'enhanced-media-library' ) );

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['media_settings'] )
                wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );
        }


        $wpuxss_eml_taxonomies = get_option( 'wpuxss_eml_taxonomies' );
        $title = __('Media Settings'); ?>


        <div id="wpuxss-eml-global-options-wrap" class="wrap eml-options">

            <h1><?php echo esc_html( $title ); ?></h1>

            <?php wpuxss_eml_print_media_settings_tabs( 'taxonomies' ); ?>

            <div id="poststuff">

                <div id="post-body" class="metabox-holder">

                    <div id="postbox-container-2" class="postbox-container">

                        <form id="wpuxss-eml-form-taxonomies" method="post" action="options.php">

                            <?php settings_fields( 'media-taxonomies' ); ?>

                            <div class="postbox">

                                <h3 class="hndle"><?php _e('Media Taxonomies','enhanced-media-library'); ?></h3>

                                <div class="inside">

                                    <p><?php _e('Assign following taxonomies to Media Library:','enhanced-media-library'); ?></p>

                                    <?php $html = '';

                                    foreach ( get_taxonomies(array(),'object') as $taxonomy ) {

                                        if ( (in_array('attachment',$taxonomy->object_type) && count($taxonomy->object_type) == 1) || empty($taxonomy->object_type) ) {

                                            $assigned = (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['assigned'];
                                            $eml_media = (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['eml_media'];

                                            if ( $eml_media )
                                                $li_class = 'wpuxss-eml-taxonomy';
                                            else
                                                $li_class = 'wpuxss-non-eml-taxonomy';

                                            $html .= '<li class="' . $li_class . '" id="' . esc_attr($taxonomy->name) . '">';

                                            $html .= '<input name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][eml_media]" type="hidden" value="' . $eml_media . '" />';
                                            $html .= '<label><input class="wpuxss-eml-assigned" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][assigned]" type="checkbox" value="1" ' . checked( true, $assigned, false ) . ' title="' . __('Assign Taxonomy','enhanced-media-library') . '" />' . esc_html($taxonomy->label) . '</label>';
                                            $html .= '<a class="wpuxss-eml-button-edit" title="' . __('Edit Taxonomy','enhanced-media-library') . '" href="javascript:;">' . __('Edit','enhanced-media-library') . ' &darr;</a>';

                                            if ( $eml_media ) {

                                                $html .= '<a class="wpuxss-eml-button-remove" title="' . __('Delete Taxonomy','enhanced-media-library') . '" href="javascript:;">&ndash;</a>';

                                                $html .= '<div class="wpuxss-eml-taxonomy-edit" style="display:none;">';

                                                $html .= '<div class="wpuxss-eml-labels-edit">';
                                                $html .= '<h4>' . __('Labels','enhanced-media-library') . '</h4>';
                                                $html .= '<ul>';
                                                $html .= '<li><label>' . __('Singular','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-singular_name" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][singular_name]" value="' . esc_html($taxonomy->labels->singular_name) . '" /></li>';
                                                $html .= '<li><label>' . __('Plural','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-name" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][name]" value="' . esc_html($taxonomy->labels->name) . '" /></li>';
                                                $html .= '<li><label>' . __('Menu Name','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-menu_name" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][menu_name]" value="' . esc_html($taxonomy->labels->menu_name) . '" /></li>';
                                                $html .= '<li><label>' . __('All','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-all_items" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][all_items]" value="' . esc_html($taxonomy->labels->all_items) . '" /></li>';
                                                $html .= '<li><label>' . __('Edit','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-edit_item" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][edit_item]" value="' . esc_html($taxonomy->labels->edit_item) . '" /></li>';
                                                $html .= '<li><label>' . __('View','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-view_item" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][view_item]" value="' . esc_html($taxonomy->labels->view_item) . '" /></li>';
                                                $html .= '<li><label>' . __('Update','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-update_item" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][update_item]" value="' . esc_html($taxonomy->labels->update_item) . '" /></li>';
                                                $html .= '<li><label>' . __('Add New','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-add_new_item" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][add_new_item]" value="' . esc_html($taxonomy->labels->add_new_item) . '" /></li>';
                                                $html .= '<li><label>' . __('New','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-new_item_name" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][new_item_name]" value="' . esc_html($taxonomy->labels->new_item_name) . '" /></li>';
                                                $html .= '<li><label>' . __('Parent','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-parent_item" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][parent_item]" value="' . esc_html($taxonomy->labels->parent_item) . '" /></li>';
                                                $html .= '<li><label>' . __('Search','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-search_items" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][labels][search_items]" value="' . esc_html($taxonomy->labels->search_items) . '" /></li>';
                                                $html .= '</ul>';
                                                $html .= '</div>';

                                                $html .= '<div class="wpuxss-eml-settings-edit">';
                                                $html .= '<h4>' . __('Settings','enhanced-media-library') . '</h4>';
                                                $html .= '<ul>';
                                                $html .= '<li><label>' . __('Taxonomy Name','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-taxonomy-name" name="" value="' . esc_attr($taxonomy->name) . '" disabled="disabled" /></li>';
                                                $html .= '<li><label>' . __('Hierarchical','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-hierarchical" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][hierarchical]" value="1" ' . checked( true, (bool) $taxonomy->hierarchical, false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Column for List View','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-show_admin_column" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][show_admin_column]" value="1" ' . checked( true, (bool) $taxonomy->show_admin_column, false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Filter for List View','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-admin_filter" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][admin_filter]" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['admin_filter'], false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Filter for Grid View / Media Popup','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-media_uploader_filter" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][media_uploader_filter]" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['media_uploader_filter'], false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Edit in Media Popup','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-media_popup_taxonomy_edit" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][media_popup_taxonomy_edit]" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['media_popup_taxonomy_edit'], false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Show in Nav Menu','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-show_in_nav_menus" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][show_in_nav_menus]" value="1" ' . checked( true, (bool) $taxonomy->show_in_nav_menus, false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Remember Terms Order (sort)','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-sort" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][sort]" value="1" ' . checked( true, (bool) $taxonomy->sort, false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Show in REST','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-show_in_rest" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][show_in_rest]" value="1" ' . checked( true, (bool) $taxonomy->show_in_rest, false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Rewrite Slug','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-slug" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][rewrite][slug]" value="' . esc_attr($taxonomy->rewrite['slug']) . '" /></li>';
                                                $html .= '<li><label>' . __('Slug with Front','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-with_front" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][rewrite][with_front]" value="1" ' . checked( true, (bool) $taxonomy->rewrite['with_front'], false ) . ' /></li>';
                                                $html .= '</ul>';
                                                $html .= '</div>';

                                                $html .= '</div>';
                                            }
                                            else {

                                                $html .= '<div class="wpuxss-eml-taxonomy-edit" style="display:none;">';

                                                $html .= '<div class="wpuxss-eml-settings-edit">';
                                                $html .= '<h4>' . __('Settings','enhanced-media-library') . '</h4>';
                                                $html .= '<ul>';
                                                $html .= '<li><label>' . __('Filter for List View','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-admin_filter" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][admin_filter]" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['admin_filter'], false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Filter for Grid View / Media Popup','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-media_uploader_filter" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][media_uploader_filter]" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['media_uploader_filter'], false ) . ' /></li>';
                                                $html .= '<li><label>' . __('Edit in Media Popup','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-media_popup_taxonomy_edit" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][media_popup_taxonomy_edit]" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['media_popup_taxonomy_edit'], false ) . ' /></li>';
                                                $html .= '</ul>';
                                                $html .= '</div>';
                                                $html .= '</div>';
                                            }
                                            $html .= '</li>';
                                        }
                                    }

                                    $html .= '<li class="wpuxss-eml-clone" style="display:none">';
                                    $html .= '<input name="" type="hidden" class="wpuxss-eml-eml_media" value="1" />';
                                    $html .= '<input name="" type="hidden" class="wpuxss-eml-create_taxonomy" value="1" />';
                                    $html .= '<label class="wpuxss-eml-taxonomy-label"><input class="wpuxss-eml-assigned" name="" type="checkbox" class="wpuxss-eml-assigned" value="1" checked="checked" title="' . __('Assign Taxonomy','enhanced-media-library') . '" />' . '<span>' . __('New Taxonomy','enhanced-media-library') . '</span></label>';

                                    $html .= '<a class="wpuxss-eml-button-remove" title="' . __('Delete Taxonomy','enhanced-media-library') . '" href="javascript:;">&ndash;</a>';

                                    $html .= '<div class="wpuxss-eml-taxonomy-edit">';

                                    $html .= '<div class="wpuxss-eml-labels-edit">';
                                    $html .= '<h4>' . __('Labels','enhanced-media-library') . '</h4>';
                                    $html .= '<ul>';
                                    $html .= '<li><label>' . __('Singular','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-singular_name" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Plural','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-name" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Menu Name','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-menu_name" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('All','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-all_items" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Edit','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-edit_item" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('View','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-view_item" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Update','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-update_item" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Add New','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-add_new_item" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('New','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-new_item_name" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Parent','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-parent_item" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Search','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-search_items" name="" value="" /></li>';
                                    $html .= '</ul>';
                                    $html .= '</div>';

                                    $html .= '<div class="wpuxss-eml-settings-edit">';
                                    $html .= '<h4>' . __('Settings','enhanced-media-library') . '</h4>';
                                    $html .= '<ul>';
                                    $html .= '<li><label>' . __('Taxonomy Name','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-taxonomy-name" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Hierarchical','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-hierarchical" name="" value="1" checked="checked" /></li>';
                                    $html .= '<li><label>' . __('Column for List View','enhanced-media-library') . '</label><input class="wpuxss-eml-show_admin_column" type="checkbox" name="" value="1" /></li>';
                                    $html .= '<li><label>' . __('Filter for List View','enhanced-media-library') . '</label><input class="wpuxss-eml-admin_filter" type="checkbox"  name="" value="1" /></li>';
                                    $html .= '<li><label>' . __('Filter for Grid View / Media Popup','enhanced-media-library') . '</label><input class="wpuxss-eml-media_uploader_filter" type="checkbox" name="" value="1" /></li>';
                                    $html .= '<li><label>' . __('Edit in Media Popup','enhanced-media-library') . '</label><input class="wpuxss-eml-media_popup_taxonomy_edit" type="checkbox" name="" value="1" /></li>';
                                    $html .= '<li><label>' . __('Show in Nav Menu','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-show_in_nav_menus" name="" value="1" /></li>';
                                    $html .= '<li><label>' . __('Remember Terms Order (sort)','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-sort" name="" value="1" /></li>';
                                    $html .= '<li><label>' . __('Show in REST','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-show_in_rest" name="" value="1" /></li>';
                                    $html .= '<li><label>' . __('Rewrite Slug','enhanced-media-library') . '</label><input type="text" class="wpuxss-eml-slug" name="" value="" /></li>';
                                    $html .= '<li><label>' . __('Slug with Front','enhanced-media-library') . '</label><input type="checkbox" class="wpuxss-eml-with_front" name="" value="1" checked="checked" /></li>';
                                    $html .= '</ul>';
                                    $html .= '</div>';

                                    $html .= '</div>';
                                    $html .= '</li>'; ?>

                                    <?php if ( ! empty( $html ) ) : ?>

                                        <ul class="wpuxss-eml-settings-list wpuxss-eml-media-taxonomy-list">
                                            <?php echo $html; ?>
                                        </ul>
                                        <div class="wpuxss-eml-button-container-right"><a class="add-new-h2 wpuxss-eml-button-create-taxonomy" href="javascript:;">+ <?php _e( 'Add New Taxonomy', 'enhanced-media-library' ); ?></a></div>
                                    <?php endif; ?>

                                    <?php submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-tax-settings-media' ) ); ?>
                                </div>

                            </div>

                            <div class="postbox">

                                <h3 class="hndle"><?php _e('Non-Media Taxonomies','enhanced-media-library'); ?></h3>

                                <div class="inside">

                                    <p><?php _e('Assign following taxonomies to Media Library:','enhanced-media-library'); ?></p>

                                    <?php $unuse = array('revision','nav_menu_item','attachment');

                                    foreach ( get_post_types(array(),'object') as $post_type ) {

                                        if ( ! in_array( $post_type->name, $unuse ) ) {

                                            $taxonomies = get_object_taxonomies($post_type->name,'object');
                                            if ( ! empty( $taxonomies ) ) {

                                                $html = '';

                                                foreach ( $taxonomies as $taxonomy ) {

                                                    if ( $taxonomy->name == 'post_format' ) {
                                                        continue;
                                                    }


                                                    $html .= '<li class="wpuxss-non-eml-taxonomy" id="' . esc_attr($taxonomy->name) . '">';
                                                    $html .= '<input name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][eml_media]" type="hidden" value="' . esc_attr($wpuxss_eml_taxonomies[$taxonomy->name]['eml_media']) . '" />';
                                                    $html .= '<label><input class="wpuxss-eml-assigned" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][assigned]" type="checkbox" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['assigned'], false ) . ' title="' . __('Assign Taxonomy','enhanced-media-library') . '" />' . esc_html($taxonomy->label) . '</label>';
                                                    $html .= '<a class="wpuxss-eml-button-edit" title="' . __('Edit Taxonomy','enhanced-media-library') . '" href="javascript:;">' . __('Edit','enhanced-media-library') . ' &darr;</a>';
                                                    $html .= '<div class="wpuxss-eml-taxonomy-edit" style="display:none;">';

                                                    $html .= '<h4>' . __('Settings','enhanced-media-library') . '</h4>';
                                                    $html .= '<ul>';
                                                    $html .= '<li><input type="checkbox" class="wpuxss-eml-admin_filter" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][admin_filter]" id="wpuxss_eml_taxonomies-' . esc_attr($taxonomy->name) . '-admin_filter" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['admin_filter'], false ) . ' /><label for="wpuxss_eml_taxonomies-' . esc_attr($taxonomy->name) . '-admin_filter">' . __('Filter for List View','enhanced-media-library') . '</label></li>';
                                                    $html .= '<li><input type="checkbox" class="wpuxss-eml-media_uploader_filter" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][media_uploader_filter]" id="wpuxss_eml_taxonomies-' . esc_attr($taxonomy->name) . '-media_uploader_filter" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['media_uploader_filter'], false ) . ' /><label for="wpuxss_eml_taxonomies-' . esc_attr($taxonomy->name) . '-media_uploader_filter">' . __('Filter for Grid View / Media Popup','enhanced-media-library') . '</label></li>';
                                                    $html .= '<li><input type="checkbox" class="wpuxss-eml-media_popup_taxonomy_edit" name="wpuxss_eml_taxonomies[' . esc_attr($taxonomy->name) . '][media_popup_taxonomy_edit]" id="wpuxss_eml_taxonomies-' . esc_attr($taxonomy->name) . '-media_popup_taxonomy_edit" value="1" ' . checked( true, (bool) $wpuxss_eml_taxonomies[$taxonomy->name]['media_popup_taxonomy_edit'], false ) . ' /><label for="wpuxss_eml_taxonomies-' . esc_attr($taxonomy->name) . '-media_popup_taxonomy_edit">' . __('Edit in Media Popup','enhanced-media-library') . '</label></li>';

                                                    $options = '';
                                                    $html .= apply_filters( 'wpuxss_eml_extend_non_media_taxonomy_options', $options, $taxonomy, $post_type, $wpuxss_eml_taxonomies );

                                                    $html .= '</ul>';

                                                    $html .= '</div>';
                                                    $html .= '</li>';
                                                } ?>

                                                <?php if ( ! empty( $html ) ) : ?>

                                                    <h4><?php echo esc_html($post_type->label); ?></h4>
                                                    <ul class="wpuxss-eml-settings-list wpuxss-eml-non-media-taxonomy-list">
                                                        <?php echo $html; ?>
                                                    </ul>

                                                <?php endif;
                                            }
                                        }
                                    }

                                    submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-tax-settings-non-media' ) ); ?>

                                </div>

                            </div>

                            <h2><?php _e('Options','enhanced-media-library'); ?></h2>

                            <?php $wpuxss_eml_tax_options = get_option( 'wpuxss_eml_tax_options' ); ?>

                            <div class="postbox">

                                <div class="inside">

                                    <table class="form-table">
                                        <tr>
                                            <th scope="row"><?php _e('Taxonomy archive pages','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Taxonomy archive pages','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_tax_options[tax_archives]" type="hidden" value="0" /><input name="wpuxss_eml_tax_options[tax_archives]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_tax_options['tax_archives'], true ); ?> /> <?php _e('Turn on media taxonomy archive pages on the front-end','enhanced-media-library'); ?></label>
                                                    <p class="description"><?php _e( 'Re-save your permalink settings after this option change to make it work.', 'enhanced-media-library' ); ?></p>
                                                </fieldset>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php _e('Assign all like hierarchical','enhanced-media-library'); ?></th>
                                            <td>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span><?php _e('Assign all like hierarchical','enhanced-media-library'); ?></span></legend>
                                                    <label><input name="wpuxss_eml_tax_options[edit_all_as_hierarchical]" type="hidden" value="0" /><input name="wpuxss_eml_tax_options[edit_all_as_hierarchical]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_tax_options['edit_all_as_hierarchical'], true ); ?> /> <?php _e('Show non-hierarchical taxonomies like hierarchical in Grid View / Media Popup','enhanced-media-library'); ?></label>
                                                </fieldset>
                                            </td>
                                        </tr>

                                    </table>

                                    <?php submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-tax-settings' ) ); ?>

                                </div>

                            </div>

                            <?php do_action( 'wpuxss_eml_extend_taxonomies_option_page' ); ?>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <?php
    }
}



/**
 *  wpuxss_eml_print_mimetypes_options
 *
 *  @type     callback function
 *  @since    1.0
 *  @created  28/09/13
 */

if ( ! function_exists( 'wpuxss_eml_print_mimetypes_options' ) ) {

    function wpuxss_eml_print_mimetypes_options() {

        if ( ! current_user_can('manage_options' ) )
            wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );

        if ( is_multisite() ) {

            $wpuxss_eml_network_options = get_site_option( 'wpuxss_eml_network_options', array() );

            if ( ! current_user_can( 'manage_network_options' ) && ! (bool) $wpuxss_eml_network_options['media_settings'] )
                wp_die( __('You do not have sufficient permissions to access this page.','enhanced-media-library') );
        }


        $wpuxss_eml_mimes = get_option('wpuxss_eml_mimes');
        $wpuxss_eml_mimes_backup = get_option('wpuxss_eml_mimes_backup');
        $title = __('Media Settings'); ?>

        <div id="wpuxss-eml-global-options-wrap" class="wrap eml-options">

            <h1>
                <?php echo esc_html( $title ); ?>
                <a class="add-new-h2 wpuxss-eml-button-create-mime" href="javascript:;">+ <?php _e('Add New MIME Type','enhanced-media-library'); ?></a>
            </h1>

            <?php wpuxss_eml_print_media_settings_tabs( 'mimetypes' ); ?>

            <div id="poststuff">

                <div id="post-body" class="metabox-holder">

                    <div id="postbox-container-2" class="postbox-container">

                        <form method="post" action="options.php" id="wpuxss-eml-form-mimetypes">

                            <?php settings_fields( 'mime-types' ); ?>

                            <?php wpuxss_eml_print_mimetypes_buttons(); ?>

                            <table class="wpuxss-eml-mime-type-list wp-list-table widefat" cellspacing="0">
                                <thead>
                                <tr>
                                    <th scope="col" class="manage-column wpuxss-eml-column-extension"><?php _e('Extension','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-mime"><?php _e('MIME Type','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-singular"><?php _e('Singular Label','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-plural"><?php _e('Plural Label','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-filter"><?php _e('Add Filter','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-upload"><?php _e('Allow Upload','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-delete"></th>
                                </tr>
                                </thead>


                                <tbody>

                                <?php
                                $allowed_mimes = get_allowed_mime_types();
                                $all_mimes = wp_get_mime_types();
                                ksort( $all_mimes, SORT_STRING ); ?>

                                <?php foreach ( $all_mimes as $type => $mime ) :

                                    if ( isset( $wpuxss_eml_mimes[$type] ) ) :

                                        $label = '<code>'. str_replace( '|', '</code>, <code>', esc_html($type) ) .'</code>';

                                        $allowed = false;
                                        if ( array_key_exists( $type,$allowed_mimes ) )
                                            $allowed = true; ?>

                                        <tr>
                                        <td id="<?php echo esc_attr($type); ?>"><?php echo $label; ?></td>
                                        <td><code><?php echo esc_html($mime); ?></code><input type="hidden" class="wpuxss-eml-mime" name="wpuxss_eml_mimes[<?php echo esc_attr($type); ?>][mime]" value="<?php echo esc_html($wpuxss_eml_mimes[$type]['mime']); ?>" /></td>
                                        <td><input type="text" name="wpuxss_eml_mimes[<?php echo esc_attr($type); ?>][singular]" value="<?php echo esc_html($wpuxss_eml_mimes[$type]['singular']); ?>" /></td>
                                        <td><input type="text" name="wpuxss_eml_mimes[<?php echo esc_attr($type); ?>][plural]" value="<?php echo esc_html($wpuxss_eml_mimes[$type]['plural']); ?>" /></td>
                                        <td class="checkbox_td"><input type="checkbox" name="wpuxss_eml_mimes[<?php echo esc_attr($type); ?>][filter]" title="<?php _e('Add Filter','enhanced-media-library'); ?>" value="1" <?php checked(true, (bool) $wpuxss_eml_mimes[$type]['filter']); ?> /></td>
                                        <td class="checkbox_td"><input type="checkbox" name="wpuxss_eml_mimes[<?php echo esc_attr($type); ?>][upload]" title="<?php _e('Allow Upload','enhanced-media-library'); ?>" value="1" <?php checked(true, $allowed); ?> /></td>
                                        <td><a class="wpuxss-eml-button-remove" title="<?php _e('Delete MIME Type','enhanced-media-library'); ?>" href="javascript:;">&ndash;</a></td>
                                        </tr>

                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <tr class="wpuxss-eml-clone" style="display:none;">
                                    <td><input type="text" class="wpuxss-eml-type" placeholder="jpg|jpeg|jpe" /></td>
                                    <td><input type="text" class="wpuxss-eml-mime" placeholder="image/jpeg" /></td>
                                    <td><input type="text" class="wpuxss-eml-singular" placeholder="Image" /></td>
                                    <td><input type="text" class="wpuxss-eml-plural" placeholder="Images" /></td>
                                    <td class="checkbox_td"><input type="checkbox" class="wpuxss-eml-filter" title="<?php _e('Add Filter','enhanced-media-library'); ?>" value="1" /></td>
                                    <td class="checkbox_td"><input type="checkbox" class="wpuxss-eml-upload" title="<?php _e('Allow Upload','enhanced-media-library'); ?>" value="1" /></td>
                                    <td><a class="wpuxss-eml-button-remove" title="<?php _e('Delete MIME Type','enhanced-media-library'); ?>" href="javascript:;">&ndash;</a></td>
                                </tr>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th scope="col" class="manage-column wpuxss-eml-column-extension"><?php _e('Extension','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-mime"><?php _e('MIME Type','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-singular"><?php _e('Singular Label','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-plural"><?php _e('Plural Label','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-filter"><?php _e('Add Filter','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-upload"><?php _e('Allow Upload','enhanced-media-library'); ?></th>
                                    <th scope="col" class="manage-column wpuxss-eml-column-delete"></th>
                                </tr>
                                </tfoot>
                            </table>

                            <?php wpuxss_eml_print_mimetypes_buttons(); ?>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <?php
    }
}



/**
 *  wpuxss_eml_print_mimetypes_buttons
 *
 *  @since    2.3.1
 *  @created  01/08/16
 */

if ( ! function_exists( 'wpuxss_eml_print_mimetypes_buttons' ) ) {

    function wpuxss_eml_print_mimetypes_buttons() { ?>

        <p class="submit">
            <?php submit_button( __( 'Save Changes' ), 'primary', 'eml-save-mime-types-settings', false, array( 'id' => 'eml-submit-settings-save-mime-types' ) ); ?>

            <input type="button" name="eml-restore-mime-types-settings" id="eml-restore-mime-types-settings" class="button" value="<?php _e('Restore WordPress default MIME Types','enhanced-media-library'); ?>">
        </p>

        <?php
    }
}



/**
 *  wpuxss_eml_print_credits
 *
 *  @since    1.0
 *  @created  28/09/13
 */

if ( ! function_exists( 'wpuxss_eml_print_credits' ) ) {

    function wpuxss_eml_print_credits() {
        ?>

        <div class="postbox" id="wpuxss-credits">

            <h3 class="hndle">Enhanced Media Library <?php echo EML_VERSION; ?></h3>

            <div class="inside">

                <h4><?php _e( 'Changelog', 'enhanced-media-library' ); ?></h4>
                <p><?php _e( 'What\'s new in', 'enhanced-media-library' ); ?> <a href="https://wordpress.org/plugins/enhanced-media-library/changelog/"><?php _e( 'version', 'enhanced-media-library' ); echo ' ' . EML_VERSION; ?></a>.</p>

                <h4>Enhanced Media Library PRO</h4>
                <p><?php _e( 'More features under the hood', 'enhanced-media-library' ); ?> <a href="https://wpuxsolutions.com/plugins/enhanced-media-library/">wpuxsolutions.com</a>.</p>

                <h4><?php _e( 'Support', 'enhanced-media-library' ); ?></h4>
                <p><?php _e( 'Feel free to ask for help on', 'enhanced-media-library' ); ?> <a href="https://wpuxsolutions.com/support/">wpuxsolutions.com</a>. <?php _e( 'Support is free for both versions of the plugin.', 'enhanced-media-library' ); ?></p>

                <h4><?php _e( 'Plugin rating', 'enhanced-media-library' ); ?> <span class="dashicons dashicons-thumbs-up"></span></h4>
                <p><?php _e( 'Please', 'enhanced-media-library' ); ?> <a href="https://wordpress.org/support/view/plugin-reviews/enhanced-media-library"><?php _e( 'vote for the plugin', 'enhanced-media-library' ); ?></a>. <?php _e( 'Thanks!', 'enhanced-media-library' ); ?></p>

                <h4><?php _e( 'Other plugins you may find useful', 'enhanced-media-library' ); ?></h4>
                <ul>
                    <li><a href="https://wordpress.org/plugins/toolbar-publish-button/">Toolbar Publish Button</a></li>
                </ul>

                <div class="author">
                    <span><a href="https://wpuxsolutions.com/">wpUXsolutions</a> by <a class="logo-webbistro" href="https://twitter.com/wpUXsolutions"><span class="icon-webbistro">@</span>webbistro</a></span>
                </div>

            </div>

        </div>

        <?php
    }
}



/**
 *  wpuxss_eml_settings_link
 *
 *  Add settings link to the plugin action links
 *
 *  @since    2.1
 *  @created  27/10/15
 */

add_filter( 'plugin_action_links_' . wpuxss_get_eml_basename(), 'wpuxss_eml_settings_link' );
add_filter( 'network_admin_plugin_action_links_' . wpuxss_get_eml_basename(), 'wpuxss_eml_settings_link' );

if ( ! function_exists( 'wpuxss_eml_settings_link' ) ) {

    function wpuxss_eml_settings_link( $links ) {

        $settings_page = is_network_admin() ? 'settings.php' : 'options-general.php';

        $settings_link = is_network_admin() ? array() : array(
            'settings' => '<a href="' . self_admin_url($settings_page.'?page=media') . '">' . __( 'Media Settings', 'enhanced-media-library' ) . '</a>'
        );

        $utility_link = array(
            'utility' => '<a href="' . self_admin_url($settings_page.'?page=eml-settings') . '">' . __( 'Utility', 'enhanced-media-library' ) . '</a>'
        );

        return array_merge( $settings_link, $utility_link, $links );
    }
}


/**
 *  wpuxss_eml_plugin_row_meta
 *
 *  @since    2.2.1
 *  @created  11/04/15
 */

add_filter( 'plugin_row_meta', 'wpuxss_eml_plugin_row_meta', 10, 2 );

if ( ! function_exists( 'wpuxss_eml_plugin_row_meta' ) ) {

    function wpuxss_eml_plugin_row_meta( $links, $file ) {

    	if ( wpuxss_get_eml_basename() == $file ) {

    		$links[] = '<a href="https://wordpress.org/support/view/plugin-reviews/enhanced-media-library" target="_blank"><span class="dashicons dashicons-thumbs-up"></span> ' . __( 'Vote!', 'enhanced-media-library' ) . '</a>';
    	}

    	return $links;
    }
}

?>
