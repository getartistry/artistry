<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Panel {

    static $status = array();

    function __construct() {

        add_filter('quadmenu_global_js_data', array($this, 'js_data'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue'), 5);

        add_filter('admin_body_class', array($this, 'body'), 99);

        add_action('admin_menu', array($this, 'panel'), 5);

        add_action('admin_menu', array($this, 'pro'), 10);

        add_action('admin_menu', array($this, 'menus'), 10);
    }

    function enqueue() {

        $screen = get_current_screen();

        if (strpos($screen->base, sanitize_title(QUADMENU_NAME)) === false && $screen->base != 'toplevel_page_quadmenu_welcome')
            return;

        wp_enqueue_style('quadmenu-admin');

        wp_enqueue_script('quadmenu-admin');

        wp_localize_script('quadmenu-admin', 'quadmenu', apply_filters('quadmenu_global_js_data', array()));
    }

    function js_data($data) {

        $data['nonce'] = wp_create_nonce('quadmenu');

        return $data;
    }

    function panel() {
        add_menu_page(QUADMENU_NAME, QUADMENU_NAME, 'edit_posts', 'quadmenu_welcome', array($this, 'welcome'), QUADMENU_URL_ASSETS . 'backend/images/icon-18x18.png', 60);
    }

    function pro() {
        add_submenu_page('quadmenu_welcome', esc_html__('PRO', 'quadmenu'), esc_html__('PRO', 'quadmenu'), 'edit_posts', 'quadmenu_pro', array($this, 'purchase'));
    }

    function menus() {
        add_submenu_page('quadmenu_welcome', esc_html__('Menus', 'quadmenu'), esc_html__('Menus', 'quadmenu'), 'manage_options', 'nav-menus.php');
    }

    static function body($classes) {

        $screen = get_current_screen();

        //if (strpos($screen->base, sanitize_title(QUADMENU_NAME)) === false && $screen->base != 'toplevel_page_quadmenu_welcome')
        //return $classes;

        $classes .= ' admin-color-quadmenu';

        return $classes;
    }

    function welcome() {
        $this->header();
        ?>
        <div class="about-wrap quadmenu-admin-wrap">
            <div class="two-col">
                <div class="col">
                    <div class="quadmenu-welcome-header">
                        <h1><?php echo QUADMENU_NAME ?> <div class="quadmenu-welcome-version"> v<?php echo QUADMENU_VERSION ?></div></h1>
                        <div class="about-text">
                            <?php esc_html_e('Thank you for using our plugin, we\'re very grateful for your welcome. We have worked very much and very hard to release this great product and we will do our absolute best to support it and fix all the issues.', 'quadmenu'); ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="feature-section">
                        <h3><?php esc_html_e('Easy and fast start', 'quadmenu'); ?></h3>
                        <p>
                            <?php esc_html_e('The QuadMenu Plugin has a simple and intuitive interface, integrated into the WP dashboard, allowing you to create and customize an unlimited amount of mega menus, without any programming skills.', 'quadmenu'); ?>
                        </p>
                        <p>
                            <?php printf(__('Go to the <a href="%s">Options</a> panel and activate the plugin in your theme locations.', 'quadmenu'), QuadMenu::taburl(0)); ?>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <img src="<?php echo QUADMENU_URL_ASSETS; ?>backend/images/screenshot.png">
                </div>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three">
                <h3><?php esc_html_e('Support', 'quadmenu'); ?></h3>
                <p>
                    <?php printf(__('We offer personalized support to all <a href="%s" target="_blank">QuadMenu PRO</a>. To get our support first you need to create an account and open a thread in the section.', 'quadmenu'), QUADMENU_DEMO); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_SUPPORT ?>" target="_blank"><?php esc_html_e('Submit ticket', 'quadmenu'); ?></a>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three">
                <h3><?php esc_html_e('Documentation', 'quadmenu'); ?></h3>
                <p>
                    <?php esc_html_e('Our online documentation will give you important information about the plugin. This is an exceptional resource to start discovering the plugin’s true potential.', 'quadmenu'); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DOCUMENTATION ?>" target="_blank"><?php esc_html_e('Open documentation', 'quadmenu'); ?></a>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three quadmenu-admin-box-last">
                <h3><?php esc_html_e('Demo', 'quadmenu'); ?></h3>
                <p>
                    <?php esc_html_e('Thank you for choosing our mega menu plugin! Here you can see our demo content and some layout examples to explore the QuadMenu features.', 'quadmenu'); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DEMO; ?>" target="_blank"><?php esc_html_e('View demo', 'quadmenu'); ?></a>
            </div>
        </div>

        <?php
    }

    function purchase() {
        $this->header();
        ?>
        <div class="about-wrap quadmenu-admin-wrap">
            <div class="two-col">
                <div class="col">
                    <div class="quadmenu-welcome-header">
                        <h1>PRO</h1>
                        <div class="about-text">
                            <?php esc_html_e('Unlock the power of our premium mega menu plugin which offers you a variety of unique elements that allow you to highlight your website over the competitors and provide an standout navigability for your users.', 'quadmenu'); ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="feature-section">
                        <h3><?php esc_html_e('Customizer', 'quadmenu'); ?></h3>
                        <p>
                            <?php esc_html_e('With the premium version, you\'ll receive a full integration with the live theme customizer dashboard. ', 'quadmenu'); ?>
                        </p>
                        <p>
                            <?php esc_html_e('This is a very powerfull feature if you will include this plugin in your themes project, as this allows you and your end users to tweak all menu color scheme or layout settings and see the effect of that changes in real time.', 'quadmenu'); ?>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <img src="<?php echo QUADMENU_URL_ASSETS; ?>backend/images/screenshot.png">
                </div>
            </div>
            <div class="two-col">
                <div class="col">
                    <img style="box-shadow: 0px 0px 20px 2px rgb(0,0,0,0.05);" src="<?php echo QUADMENU_URL_ASSETS; ?>backend/images/tabs.jpg">
                </div>
                <div class="col">
                    <div class="feature-section">
                        <h3><?php esc_html_e('Tabs', 'quadmenu'); ?></h3>
                        <p>
                            <?php printf(__('With the tabs, you can <a href="%s">create a tab mega menu</a> where you can include widgets or any kind of content available in your menu dashboard.', 'quadmenu'), 'https://quadmenu.com/documentation/content/tabs/?utm_source=quadmenu_admin'); ?>
                        </p>
                        <p>
                            <?php esc_html_e('This element is element allows you to set a custom background for the whole dropdown and handle the title, icons, badges and subtitles on each tab.', 'quadmenu'); ?>
                        </p>
                    </div>
                </div>
                <hr/>
            </div>
            <div class="two-col">
                <div class="col">
                    <div class="feature-section">
                        <h3><?php esc_html_e('Carousel', 'quadmenu'); ?></h3>
                        <p>
                            <?php printf(__('Our carousel element allows you to create a <a href="%s">carousel mega menu</a> where you can include widgets or any kind of content available in your menu dashboard.', 'quadmenu'), 'https://quadmenu.com/documentation/content/carousel/?utm_source=quadmenu_admin'); ?>
                        </p>
                        <p>
                            <?php printf(__('This element is also available for <a href="%s">categories and archives items</a> and lets you create a carousel of the latest\'s post, products, pages and any post type available in your site.', 'quadmenu'), 'https://quadmenu.com/documentation/content/archives/?utm_source=quadmenu_admin'); ?>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <img style="box-shadow: 0px 0px 20px 2px rgb(0,0,0,0.05);" src="<?php echo QUADMENU_URL_ASSETS; ?>backend/images/carousel.jpg">
                </div>
            </div>
            <div class="two-col">
                <div class="col">
                    <img style="box-shadow: 0px 0px 20px 2px rgb(0,0,0,0.05);" src="<?php echo QUADMENU_URL_ASSETS; ?>backend/images/login.jpg">
                </div>
                <div class="col">
                    <div class="feature-section">
                        <h3><?php esc_html_e('Login', 'quadmenu'); ?></h3>
                        <p>
                            <?php printf(__('The user login element provides your menu with a <a href="%s">login and register form</a> that is displayed in a drop-down. Recently we\'ve improved this feature to include a drop-down menu for logged in users where you can display any link and a quick access to the user account.', 'quadmenu'), 'https://quadmenu.com/documentation/content/login/?utm_source=quadmenu_admin'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="two-col">
                <div class="col">
                    <div class="feature-section">
                        <h3><?php esc_html_e('Social', 'quadmenu'); ?></h3>
                        <p>
                            <?php esc_html_e('The social icons element allow you to include a list of social networks icons with links. There is a mode called toggle that shows an icon in the main menu that toggles the list when the user makes a click and other mode called embed that displays the entire icons list.', 'quadmenu'); ?>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <img style="box-shadow: 0px 0px 20px 2px rgb(0,0,0,0.05);" src="<?php echo QUADMENU_URL_ASSETS; ?>backend/images/social.jpg">
                </div>
            </div>
            <!--<div class="one-col">
                <div class="feature-section">
                    <h3><?php esc_html_e('Tabs', 'quadmenu'); ?></h3>
                    <p>
            <?php esc_html_e('The QuadMenu Plugin has a simple and intuitive interface, integrated into the WP dashboard, allowing you to create and customize an unlimited amount of mega menus, without any programming skills.', 'quadmenu'); ?>
                    </p>
                    <p>
            <?php printf(__('Go to the <a href="%s">Options</a> panel and activate the plugin in your theme locations.', 'quadmenu'), QuadMenu::taburl(0)); ?>
                    </p>
                </div>
                <img src="https://quadmenu.com/wp-content/uploads/2017/12/tabs-1.png">
            </div>-->
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three">
                <h3><?php esc_html_e('Personal', 'quadmenu'); ?></h3>
                <p>
                    <?php printf(__('The single or personal license allows you to install %s in one site, receive the premium support for six month and lifetime updates.', 'quadmenu'), QUADMENU_NAME); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DEMO ?>" target="_blank"><?php esc_html_e('Purchase Now', 'quadmenu'); ?></a>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three">
                <h3><?php esc_html_e('Agency', 'quadmenu'); ?></h3>
                <p>
                    <?php printf(__('The single or personal license allows you to install %s in one site, receive the premium support for six month and lifetime updates.', 'quadmenu'), QUADMENU_NAME); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DEMO ?>" target="_blank"><?php esc_html_e('Purchase Now', 'quadmenu'); ?></a>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three quadmenu-admin-box-last">
                <h3><?php esc_html_e('Developer', 'quadmenu'); ?></h3>
                <p>
                    <?php printf(__('The single or personal license allows you to install %s in one site, receive the premium support for six month and lifetime updates.', 'quadmenu'), QUADMENU_NAME); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DEMO ?>" target="_blank"><?php esc_html_e('Purchase Now', 'quadmenu'); ?></a>
            </div>
        </div>

        <?php
    }

    function header() {
        require_once QUADMENU_PATH . 'includes/panel/header.php';
    }

    function add($section, $status_array) {
        self::$status[$section][] = $status_array;
    }

    function tables() {
        foreach (self::$status as $section_name => $section_statuses) {
            ?>
            <table class="widefat quadmenu-system-table" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan="4"><?php echo esc_html($section_name); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($section_statuses as $status_params) {
                        ?>
                        <tr>
                            <td class="quadmenu-system-name"><?php echo esc_html($status_params['check_name']); ?></td>
                            <!--<td class="quadmenu-system-help"></td>-->
                            <td class="quadmenu-system-status">
                                <?php
                                switch ($status_params['status']) {
                                    case 'green':
                                        echo '<div class="quadmenu-system-led success" title="' . esc_html__('Green status: this check passed our system status test!', 'quadmenu') . '"></div>';
                                        break;
                                    case 'yellow':
                                        echo '<div class="quadmenu-system-led warning" title="' . esc_html__('Yellow status: this setting may affect the backend of the site. The frontend should still run as expected. We recommend that you fix this.', 'quadmenu') . '"></div>';
                                        break;
                                    case 'red' :
                                        echo '<div class="quadmenu-system-led critical" title="' . esc_html__('Red status: the site may not work as expected with this option.', 'quadmenu') . '"></div>';
                                        break;
                                    case 'info':
                                        echo '<div class="quadmenu-system-led info title="' . esc_html__('Info status: this is just for information purposes and easier debug if a problem appears', 'quadmenu') . '">i</div>';
                                        break;
                                }
                                ?>
                            </td>
                            <td class="quadmenu-system-value"><?php
                                echo wp_kses($status_params['value'], array(
                                    'a' => array(
                                        'href' => array(),
                                        'title' => array()
                                    ),
                                    'br' => array(),
                                    'em' => array(),
                                    'strong' => array(),
                                    'span' => array(),
                                ));
                                ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
    }

}

new QuadMenu_Panel();
