<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Panel {

    static $status = array();

    function __construct() {

        add_filter('quadmenu_global_js_data', array($this, 'js_data'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue'), 5);

        add_filter('admin_body_class', array($this, 'body'));

        add_action('admin_menu', array($this, 'panel'), 5);

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
        add_menu_page(QUADMENU_NAME, QUADMENU_NAME, 'edit_posts', 'quadmenu_welcome', array($this, 'welcome'), QUADMENU_URL_ASSETS . '/backend/images/icon.svg', 60);
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
                <a class="button button-primary" href="<?php echo QUADMENU_SUPPORT ?>" target="_blank"><?php esc_html_e('Open forum', 'quadmenu'); ?></a>
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
                <a class="button button-primary" href="<?php echo QUADMENU_PREMIUM; ?>" target="_blank"><?php esc_html_e('Our portfolio', 'quadmenu'); ?></a>
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
                            <td class="quadmenu-system-help"><!--<a href="#" class="help_tip">[?]</a>--></td>
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
