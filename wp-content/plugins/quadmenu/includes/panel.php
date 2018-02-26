<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Panel {

    function __construct() {

        add_filter('quadmenu_global_js_data', array($this, 'js_data'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue'), 5);

        add_filter('admin_body_class', array($this, 'body'));

        add_action('admin_menu', array($this, 'panel'));
    }

    function enqueue() {

        $screen = get_current_screen();

        if (strpos($screen->base, sanitize_title(QUADMENU_NAME)) === false && $screen->base != 'toplevel_page_quadmenu_welcome')
            return;

        wp_enqueue_style('quadmenu-admin');

        wp_enqueue_script('quadmenu-admin-init');

        wp_localize_script('quadmenu-admin-init', 'quadmenu', apply_filters('quadmenu_global_js_data', array()));
    }

    function js_data($data) {

        $data['nonce'] = wp_create_nonce('quadmenu');

        return $data;
    }

    function panel() {
        add_menu_page(QUADMENU_NAME, QUADMENU_NAME, 'edit_posts', 'quadmenu_welcome', array($this, 'welcome'), QUADMENU_URL_ASSETS . '/backend/images/icon.svg', 60);
    }

    static function body($classes) {

        $screen = get_current_screen();

        if (strpos($screen->base, sanitize_title(QUADMENU_NAME)) === false && $screen->base != 'toplevel_page_quadmenu_welcome')
            return $classes;

        $classes .= ' admin-color-quadmenu';

        return $classes;
    }

    function header() {
        require_once QUADMENU_PATH . 'includes/panel/header.php';
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
                            <?php esc_html_e('Thank you for using our plugin, we\'re very grateful your welcome. We have worked very much and very hard to release this great product and we will do our absolute best to support it and fix all the issues.', 'quadmenu'); ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="feature-section">
                        <h3><?php esc_html_e('Easy and fast start', 'quadmenu'); ?></h3>
                        <p>
                            <?php esc_html_e('The QuadMenu Plugin has a simple and intuitive interface, integrated in the WP dashboard, allowing you to create and customize an unlimited amount of mega menus, without any programming skills.', 'quadmenu'); ?>
                        </p>
                        <p>
                            <?php esc_html_e('The QuadMenu Plugin has a simple and intuitive interface, integrated in the WP dashboard, allowing you to create and customize an unlimited amount of mega menus, without any programming skills.', 'quadmenu'); ?>
                        </p>
                        <p>
                            <?php printf(__('Go to the <a href="%s">Options</a> panel and activate the plugin in your theme locations.'), QuadMenu::taburl(0)); ?>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <img src="<?php echo QUADMENU_URL_ASSETS; ?>backend/images/screenshot.png">
                </div>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three">
                <h3><?php esc_html_e('Support forum', 'quadmenu'); ?></h3>
                <p>
                    <?php esc_html_e('We offer outstanding support through our forum. To get our support first you need to create an account and open a thread in the section.', 'quadmenu'); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DOCUMENTATION ?>" target="_blank"><?php esc_html_e('Open forum', 'quadmenu'); ?></a>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three">
                <h3><?php esc_html_e('Documentation', 'quadmenu'); ?></h3>
                <p>
                    <?php esc_html_e('Our online documentation will give you important information about the plugin. This is a exceptional resource to start discovering the plugin’s true potential.', 'quadmenu'); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DOCUMENTATION ?>" target="_blank"><?php esc_html_e('Open documentation', 'quadmenu'); ?></a>
            </div>
            <div class="quadmenu-admin-box-text quadmenu-admin-box-three quadmenu-admin-box-last">
                <h3><?php esc_html_e('View demo', 'quadmenu'); ?></h3>
                <p>
                    <?php esc_html_e('Thank you for choosing our mega menu plugin! Here you can see our demo content and some layout examples.', 'quadmenu'); ?>
                </p>
                <a class="button button-primary" href="<?php echo QUADMENU_DEMO; ?>" target="_blank"><?php esc_html_e('View demo', 'quadmenu'); ?></a>
                <a class="button button-primary" href="<?php echo QUADMENU_THEMEFOREST; ?>" target="_blank"><?php esc_html_e('Our portfolio', 'quadmenu'); ?></a>
            </div>
        </div>

        <?php
    }

}

new QuadMenu_Panel();
