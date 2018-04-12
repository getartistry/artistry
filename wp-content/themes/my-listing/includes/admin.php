<?php
/**
 * Adds custom functionality to the Admin panel.
 */

class CASE27_Admin {

    protected static $_instance = null;

    public static function instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


	public function __construct()
	{
        // Enqueue Admin Scripts and Styles.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_menu', [$this, 'reorder_admin_menu'], 999);
        add_action('admin_init', function() {
            add_editor_style('assets/dist/styles/editor.' . CASE27_THEME_VERSION . '.css');
        });
	}

    /*
     * Enqueue Scripts and Styles.
     */
    public function enqueue_scripts()
    {
        // Material Icons.
        wp_enqueue_script('c27-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . c27()->get_setting('general_google_maps_api_key') . '&libraries=places&v=3', [], null, true);
        wp_enqueue_style('c27-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
        CASE27_Assets::instance()->enqueue_icons();

        if (CASE27_ENV === 'dev') {
            wp_enqueue_style( 'theme-style-general', c27()->template_uri('assets/styles/admin/general.css'), [], CASE27_THEME_VERSION );
            wp_enqueue_style( 'theme-style-listing-type-builder', c27()->template_uri('assets/styles/admin/listing-type-builder.css'), [], CASE27_THEME_VERSION );
            wp_enqueue_style( 'theme-style-shortcode-generator', c27()->template_uri('assets/styles/admin/shortcode-generator.css'), [], CASE27_THEME_VERSION );
            wp_enqueue_style( 'c27-select2', c27()->template_uri('assets/styles/vendor/select2.css'), [], CASE27_THEME_VERSION );
            wp_enqueue_style( 'c27-daterangepicker', c27()->template_uri('assets/styles/vendor/daterangepicker.css' ), [], CASE27_THEME_VERSION );
            wp_enqueue_style( 'jsoneditor', c27()->template_uri('assets/styles/vendor/jsoneditor.min.css' ), [], CASE27_THEME_VERSION );

            wp_enqueue_script( 'caret-js', c27()->template_uri("assets/scripts/vendor/jquery.caret.min.js"), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'atwho-js', c27()->template_uri("assets/scripts/vendor/jquery.atwho.min.js"), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'lodash-debounce', c27()->template_uri("assets/scripts/vendor/lodash.debounce.js"), array(), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'jsoneditor', c27()->template_uri("assets/scripts/vendor/jsoneditor.js"), array(), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'c27-maps', c27()->template_uri("assets/scripts/maps/maps.js"), array('jquery'), CASE27_THEME_VERSION, true );

            wp_enqueue_script( 'theme-vue-js', c27()->template_uri('assets/scripts/vendor/vue.js'), array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'theme-sortable', c27()->template_uri('assets/scripts/vendor/sortable.js'), array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'theme-vue-draggable', c27()->template_uri('assets/scripts/vendor/vue-draggable.js'), array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'theme-vue-resource', c27()->template_uri('assets/scripts/vendor/vue-resource.js'), array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'theme-script-main', c27()->template_uri('assets/scripts/admin/main.js'), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'theme-script-listing-type-builder', c27()->template_uri('assets/scripts/admin/listing-type-builder.js'), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'theme-script-shortcode-generator', c27()->template_uri('assets/scripts/admin/shortcode-generator.js'), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'c27-select-custom', c27()->template_uri("assets/scripts/vendor/select-custom.js"), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'c27-moment', c27()->template_uri("assets/scripts/vendor/moment.js"), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'c27-daterangepicker', c27()->template_uri("assets/scripts/vendor/daterangepicker.js"), array('jquery'), CASE27_THEME_VERSION, true );
            wp_enqueue_script( 'c27-jquery.repeater', c27()->template_uri("assets/scripts/vendor/jquery.repeater.js"), array('jquery'), CASE27_THEME_VERSION, true );


        } else {
            wp_enqueue_style( 'theme-style-general', c27()->template_uri('assets/dist/styles/admin.' . CASE27_THEME_VERSION . '.css'), [], CASE27_THEME_VERSION );
            wp_enqueue_script( 'theme-script-main', c27()->template_uri('assets/dist/scripts/admin.' . CASE27_THEME_VERSION . '.js'), ['jquery'], CASE27_THEME_VERSION, true );
        }

        wp_localize_script( 'theme-script-main', 'CASE27', array(
            'template_uri' => c27()->template_uri(),
            'map_skins' => c27()->get_map_skins(),
            'icon_packs' => $this->get_icon_packs(),
            'autocomplete' => array(
                'types' => (array) c27()->get_setting( 'general_autocomplete_types', 'geocode' ),
                'locations' => array_filter( (array) c27()->get_setting( 'general_autocomplete_locations', [] ) ),
            ),
            'l10n' => [
                'datepicker' => mylisting()->strings()->get_datepicker_locale(),
            ],
        ));
    }

    public function get_icon_packs() {
        if ( ! is_user_logged_in() ) {
            return;
        }

        $font_awesome_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/font-awesome.php';
        $material_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/material-icons.php';
        $theme_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/theme-icons.php';

        return [
            'font-awesome' => array_map( function( $icon ) {
                return "fa {$icon}";
            }, array_values( $font_awesome_icons ) ),

            'material-icons' => array_map( function( $icon ) {
                return "mi {$icon}";
            }, array_values( $material_icons ) ),

            'theme-icons' => array_values( $theme_icons ),
        ];
    }


    public function admin_menu()
    {
        c27()->new_admin_page(
            'menu',
            [
                __( '<strong>27 &mdash; </strong> Tools', 'my-listing' ),
                __( '<strong>Theme Tools</strong>', 'my-listing' ),
                'manage_options',
                'case27/tools.php',
                '',
                c27()->image('27.jpg'),
                1,
            ]
        );

        c27()->new_admin_page(
            'menu',
            [
                __( 'Listing Tools', 'my-listing' ),
                __( 'Listing Tools', 'my-listing' ),
                'manage_options',
                'case27/listing-tools.php',
                '',
                'dashicons-menu',
                3,
            ]
        );

        c27()->new_admin_page(
            'submenu',
            [
                'case27/tools.php',
                __( 'Shortcodes', 'my-listing' ),
                __( 'Shortcodes', 'my-listing' ),
                'manage_options',
                'case27-tools-shortcodes',
                function() {
                    require_once CASE27_INTEGRATIONS_DIR . '/27collective/shortcode-generator/index.php';
                },
            ]
        );

        c27()->new_admin_page(
            'submenu',
            [
                'case27/tools.php',
                __( 'Docs', 'my-listing' ),
                __( 'Docs', 'my-listing' ),
                'manage_options',
                'case27-tools-docs',
                function() { ?>
                    <div id="case27-docs-wrapper">
                        <iframe src="http://27collective.net/files/mylisting/docs/index.html" frameborder="0">
                    </div>
                <?php },
            ]
        );
    }

    public function reorder_admin_menu()
    {
        global $menu, $submenu;

        foreach ($menu as $key => $menu_item) {
            if ( isset($submenu['case27/tools.php']) && in_array( $menu_item[2], ['theme-general-settings'] ) ) {
                $submenu['case27/tools.php'][] = [$menu_item[0], $menu_item[1], $menu_item[2], $menu_item[3]];
                unset($menu[$key]);
            }

            if ( isset($submenu['case27/listing-tools.php']) && in_array( $menu_item[2], ['theme-integration-settings'] ) ) {
                $submenu['case27/listing-tools.php'][] = [$menu_item[0], $menu_item[1], $menu_item[2], $menu_item[3]];
                unset($menu[$key]);
            }
        }

        // Reorder submenu items.
        $submenu['case27/tools.php'] = array_filter([
            isset($submenu['case27/tools.php'][3]) ? $submenu['case27/tools.php'][3] : null,
            isset($submenu['case27/tools.php'][0]) ? $submenu['case27/tools.php'][0] : null,
            isset($submenu['case27/tools.php'][1]) ? $submenu['case27/tools.php'][1] : null,
            isset($submenu['case27/tools.php'][2]) ? $submenu['case27/tools.php'][2] : null,
        ]);

        foreach ($submenu['case27/tools.php'] as $key => $tools_page) {
            if ( $tools_page[2] == 'case27/tools.php' ) {
                unset( $submenu['case27/tools.php'][$key] );
            }
        }

        // Reorder listing tools items.
        $submenu['case27/listing-tools.php'] = array_filter([
            isset($submenu['case27/listing-tools.php'][0]) ? $submenu['case27/listing-tools.php'][0] : null,
            isset($submenu['case27/listing-tools.php'][2]) ? $submenu['case27/listing-tools.php'][2] : null,
            isset($submenu['case27/listing-tools.php'][1]) ? $submenu['case27/listing-tools.php'][1] : null,
        ]);

        // dd( $submenu['case27/listing-tools.php'] );


        // dd($submenu['case27/tools.php']);
    }
}

new CASE27_Admin;