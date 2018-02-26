<?php
/**
 * Plugin Name: QuadMenu - Divi Mega Menu
 * Plugin URI: http://themeforest.net/user/quadlayers/portfolio
 * Description: Integrates QuadMenu with the Divi theme.
 * Version: 1.0.1
 * Author: QuadLayers
 * Author URI: http://themeforest.net/user/quadlayers
 * License: codecanyon
 * Copyright: 2018 QuadMenu (https://www.quadmenu.com)
 */
if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('QuadMenu_Divi')) :

    final class QuadMenu_Divi {

        function __construct() {

            add_action('admin_notices', array($this, 'required'), 10);

            add_action('init', array($this, 'hooks'));

            add_filter('quadmenu_default_themes', array($this, 'themes'), 10);

            add_filter('quadmenu_developer_options', array($this, 'developer'), 10);

            add_filter('quadmenu_default_options_theme_divi', array($this, 'divi'), 10);

            add_filter('quadmenu_default_options_location_primary-menu', array($this, 'primary_menu'), 10);
        }

        function required() {

            $path = 'quadmenu/quadmenu.php';
            
            $pro = 'quadmenu-pro/quadmenu.php';

            if (is_plugin_active($path)) {
                return;
            }

            if (is_plugin_active($pro)) {
                return;
            }

            $all_plugins = get_plugins();

            if (isset($all_plugins[$path])) :

                $plugin = plugin_basename($path);

                $link = '<a href="' . wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1', 'activate-plugin_' . $plugin) . '" class="edit">' . __('activate', 'quadmenu-Divi') . '</a>';
                ?>

                <div class="updated">
                    <p>
                        <?php printf(__('QuadMenu Divi requires QuadMenu. Please %s the QuadMenu plugin.', 'quadmenu-Divi'), $link); ?>
                    </p>
                </div>

                <?php
            else:
                ?>
                <div class="updated">
                    <p>
                        <?php printf(__('QuadMenu Divi requires QuadMenu. Please install the QuadMenu plugin.', 'quadmenu-Divi'), $link); ?>
                    </p>
                    <p class="submit">
                        <a href="<?php echo admin_url('plugin-install.php?tab=search&type=term&s=quadmenu') ?>" class='button button-secondary'><?php _e('Install QuadMenu', 'quadmenu-Divi'); ?></a>
                    </p>
                </div>
            <?php
            endif;
        }

        function hooks() {

            if (!function_exists('is_quadmenu_location'))
                return;

            if (!is_quadmenu_location('primary-menu'))
                return;

            remove_action('et_header_top', 'et_add_mobile_navigation');
            add_action('et_header_top', array($this, 'et_add_mobile_navigation'));

            add_action('wp_head', array($this, 'header'));
        }

        function et_add_mobile_navigation() {

            if (is_customize_preview() || ( 'slide' !== et_get_option('header_style', 'left') && 'fullscreen' !== et_get_option('header_style', 'left') )) {
                ?>
                <div id="et_mobile_nav_menu">
                    <div class="mobile_nav closed">
                        <span class="select_page"><?php esc_html_e('Select Page', 'Divi'); ?></span>
                        <span class="mobile_menu_bar mobile_menu_bar_toggle"></span>
                        <div class="et_mobile_menu">
                            <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'layout' => 'inherit')); ?>
                        </div>
                    </div>
                </div>

                <?php
            }
        }

        function header() {
            ?>
            <style>

                @media (max-width: 980px) {
                    #top-menu-nav #quadmenu {
                        display: none;
                    }
                }

                #top-menu-nav #quadmenu {
                    margin-top: -33px;
                    line-height: 1.1;
                }

                #top-menu-nav #quadmenu,
                #top-menu-nav #quadmenu .quadmenu-container {        
                    position: initial;
                }

                .et-fixed-header #top-menu-nav #quadmenu {
                    margin-top: -20px;
                }

                .et-fixed-header #top-menu-nav #quadmenu.quadmenu-divi.quadmenu-is-horizontal.quadmenu-is-horizontal .quadmenu-navbar-nav > li {
                    height: 53px;
                }

                .mobile_nav .et_mobile_menu {
                }

                .mobile_nav.opened .et_mobile_menu {
                    transition: all 0.4s;
                    display: block;
                }

            </style>
            <?php
        }

        function themes($themes) {

            $themes['divi'] = 'Divi Theme';

            return $themes;
        }

        function developer($options) {

            $options['viewport'] = 0;

            $options['primary-menu_unwrap'] = 0;

            $options['divi_theme_title'] = 'Divi Theme';

            $options['divi_navbar_logo'] = array(
                'url' => null
            );

            $options['divi_layout_breakpoint'] = 980;

            $options['divi_layout_width'] = 0;

            $options['divi_layout_width_selector'] = '';

            $options['divi_layout_sticky'] = 0;

            $options['divi_layout_sticky_offset'] = 0;

            $options['divi_layout_hover_effect'] = null;

            $options['divi_navbar_background'] = 'color';

            $options['divi_navbar_background_color'] = array(
                'color' => '#ffffff',
                'alpha' => '1',
                'rgba' => 'rgba(255,255,255,0)',
            );
            $options['divi_navbar_background_to'] = array(
                'color' => '#a281f7',
                'alpha' => '1',
                'rgba' => 'rgba(162,129,247,0)',
            );


            $options['divi_navbar'] = '';
            $options['divi_navbar_height'] = '80';
            $options['divi_navbar_width'] = '260';


            return $options;
        }

        function divi($defaults) {

            $defaults['layout'] = 'collapse';
            $defaults['layout_offcanvas_float'] = 'right';
            $defaults['layout_align'] = 'right';
            $defaults['layout_breakpoint'] = '1326';
            $defaults['layout_width'] = '0';
            $defaults['layout_width_selector'] = '.et_pb_row';
            $defaults['layout_trigger'] = 'hoverintent';
            $defaults['layout_current'] = '';
            $defaults['layout_animation'] = 'quadmenu_btt';
            $defaults['layout_classes'] = '';
            $defaults['layout_sticky'] = '0';
            $defaults['layout_sticky_offset'] = '90';
            $defaults['layout_divider'] = 'hide';
            $defaults['layout_caret'] = 'show';
            $defaults['layout_hover_effect'] = '';
            $defaults['navbar_background'] = 'color';
            $defaults['navbar_background_color'] = array(
                'color' => '#ffffff',
                'alpha' => '1',
                'rgba' => 'rgba(255,255,255,1)',
            );
            $defaults['navbar_background_to'] = array(
                'color' => '#a281f7',
                'alpha' => '1',
                'rgba' => 'rgba(162,129,247,1)',
            );
            $defaults['navbar_background_deg'] = '17';
            $defaults['navbar_divider'] = array(
                'color' => '#ffffff',
                'alpha' => '0.5',
                'rgba' => 'rgba(255,255,255,0.5)',
            );
            $defaults['navbar_text'] = '#8585bd';
            $defaults['navbar_height'] = '90';
            $defaults['navbar_width'] = '260';
            $defaults['navbar_mobile_border'] = array(
                'color' => '#ffffff',
                'alpha' => '0.01',
                'rgba' => 'rgba(255,255,255,0.01)',
            );
            $defaults['navbar_toggle_open'] = '#2ea3f2';
            $defaults['navbar_toggle_close'] = '#2ea3f2';
            $defaults['navbar_logo'] = array(
                'url' => '',
                'id' => '',
                'height' => '',
                'width' => '',
                'thumbnail' => '',
                'title' => '',
                'caption' => '',
                'alt' => '',
                'description' => '',
            );
            $defaults['navbar_logo_height'] = '43';
            $defaults['navbar_logo_bg'] = array(
                'color' => '#ffffff',
                'alpha' => '0',
                'rgba' => 'rgba(255,255,255,0)',
            );
            $defaults['navbar_link_margin'] = array(
                'border-top' => '0px',
                'border-right' => '0px',
                'border-bottom' => '0px',
                'border-left' => '0px',
                'border-style' => '',
                'border-color' => '',
            );
            $defaults['navbar_link_radius'] = array(
                'border-top' => '0px',
                'border-right' => '0px',
                'border-bottom' => '0px',
                'border-left' => '0px',
                'border-style' => '',
                'border-color' => '',
            );
            $defaults['navbar_link_transform'] = 'none';
            $defaults['navbar_link'] = '#666666';
            $defaults['navbar_link_hover'] = '#949494';
            $defaults['navbar_link_bg'] = array(
                'color' => '#ffffff',
                'alpha' => '0',
                'rgba' => 'rgba(255,255,255,0)',
            );
            $defaults['navbar_link_bg_hover'] = array(
                'color' => '#111111',
                'alpha' => '0',
                'rgba' => 'rgba(17,17,17,0)',
            );
            $defaults['navbar_link_hover_effect'] = array(
                'color' => '#09e1c0',
                'alpha' => '1',
                'rgba' => 'rgba(9,225,192,1)',
            );
            $defaults['navbar_button'] = '#ffffff';
            $defaults['navbar_button_bg'] = '#09e1c0';
            $defaults['navbar_button_hover'] = '#ffffff';
            $defaults['navbar_button_bg_hover'] = '#7272ff';
            $defaults['navbar_link_icon'] = '#09e1c0';
            $defaults['navbar_link_icon_hover'] = '#7272ff';
            $defaults['navbar_link_subtitle'] = '#8585bd';
            $defaults['navbar_link_subtitle_hover'] = '#949494';
            $defaults['navbar_badge'] = '#09e1c0';
            $defaults['navbar_badge_color'] = '#ffffff';
            $defaults['sticky_background'] = array(
                'color' => '#ffffff',
                'alpha' => '0',
                'rgba' => 'rgba(255,255,255,0)',
            );
            $defaults['sticky_height'] = '60';
            $defaults['sticky_logo_height'] = '25';
            $defaults['navbar_scrollbar'] = '#09e1c0';
            $defaults['navbar_scrollbar_rail'] = '#ffffff';
            $defaults['dropdown_shadow'] = 'show';
            $defaults['dropdown_margin'] = '5';
            $defaults['dropdown_radius'] = '2';
            $defaults['dropdown_border'] = array(
                'border-top' => '0px',
                'border-right' => '',
                'border-bottom' => '',
                'border-left' => '',
                'border-style' => '',
                'border-color' => '#000000',
            );
            $defaults['dropdown_background'] = array(
                'color' => '#ffffff',
                'alpha' => '1',
                'rgba' => 'rgba(255,255,255,1)',
            );
            $defaults['dropdown_scrollbar'] = '#09e1c0';
            $defaults['dropdown_scrollbar_rail'] = '#ffffff';
            $defaults['dropdown_title'] = '#2e2545';
            $defaults['dropdown_title_border'] = array(
                'border-top' => '1px',
                'border-right' => '',
                'border-bottom' => '',
                'border-left' => '',
                'border-style' => 'solid',
                'border-color' => '#09e1c0',
            );
            $defaults['dropdown_link'] = '#666666';
            $defaults['dropdown_link_hover'] = '#949494';
            $defaults['dropdown_link_bg_hover'] = array(
                'color' => '#f4f4f4',
                'alpha' => '1',
                'rgba' => 'rgba(244,244,244,1)',
            );
            $defaults['dropdown_link_border'] = array(
                'border-top' => '0px',
                'border-right' => '0px',
                'border-bottom' => '0px',
                'border-left' => '0px',
                'border-style' => 'none',
                'border-color' => '#f4f4f4',
            );
            $defaults['dropdown_link_transform'] = 'none';
            $defaults['dropdown_button'] = '#ffffff';
            $defaults['dropdown_button_hover'] = '#ffffff';
            $defaults['dropdown_button_bg'] = '#09e1c0';
            $defaults['dropdown_button_bg_hover'] = '#7272ff';
            $defaults['dropdown_link_icon'] = '#09e1c0';
            $defaults['dropdown_link_icon_hover'] = '#7272ff';
            $defaults['dropdown_link_subtitle'] = '#8585bd';
            $defaults['dropdown_link_subtitle_hover'] = '#949494';
            $defaults['font'] = array(
                'font-family' => 'Open Sans',
                'font-options' => '',
                'google' => '1',
                'font-weight' => '400',
                'font-style' => '',
                'subsets' => '',
                'font-size' => '14px',
            );
            $defaults['navbar_font'] = array(
                'font-family' => 'Open Sans',
                'font-options' => '',
                'google' => '1',
                'font-weight' => '600',
                'font-style' => '',
                'subsets' => '',
                'font-size' => '14px',
            );
            $defaults['dropdown_font'] = array(
                'font-family' => 'Open Sans',
                'font-options' => '',
                'google' => '1',
                'font-weight' => '600',
                'font-style' => '',
                'subsets' => '',
                'font-size' => '14px',
            );

            return $defaults;
        }

        function primary_menu($defaults) {

            $defaults['integration'] = 1;
            $defaults['theme'] = 'divi';

            return $defaults;
        }

        static function activation() {

            update_option('_quadmenu_compiler', true);

            if (class_exists('QuadMenu')) {

                QuadMenu_Redux::add_notification('blue', esc_html__('Thanks for install QuadMenu Divi. We have to create the stylesheets. Please wait.', 'quadmenu-Divi'));

                QuadMenu_Activation::activation();
            }
        }

    }

    endif; // End if class_exists check

new QuadMenu_Divi();

register_activation_hook(__FILE__, array('QuadMenu_Divi', 'activation'));
