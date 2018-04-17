<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Admin {

    public function __construct() {

        add_action('admin_enqueue_scripts', array($this, 'register'), -1);

        add_action('admin_notices', array($this, 'notices'));

        add_action('admin_footer', array($this, 'icons'));

        add_filter('plugin_action_links_' . QUADMENU_BASENAME, array($this, 'add_action_links'));

        //add_filter('pre_delete_post', array($this, 'pre_delete_post'), 10, 3);
    }

    function add_action_links($links) {

        $links[] = '<a target="_blank" href="' . QUADMENU_CODECANYON . '">' . esc_html__('Premium', 'quadmenu') . '</a>';

        $links[] = '<a href="' . admin_url('admin.php?page=' . QUADMENU_PANEL) . '">' . esc_html__('Settings', 'quadmenu') . '</a>';

        return $links;
    }

    public function register() {

        wp_register_style('quadmenu-modal', QUADMENU_URL . 'assets/backend/css/modal' . QuadMenu::isMin() . '.css', array(), QUADMENU_VERSION, 'all');

        wp_register_style('quadmenu-admin', QUADMENU_URL . 'assets/backend/css/quadmenu-admin' . QuadMenu::isMin() . '.css', array('quadmenu-modal', _QuadMenu()->selected_icons()->ID), QUADMENU_VERSION, 'all');

        wp_register_script('quadmenu-admin', QUADMENU_URL . 'assets/backend/js/quadmenu-admin' . QuadMenu::isMin() . '.js', array('jquery', 'jquery-ui-sortable'), QUADMENU_VERSION, false);
    }

    public function icons() {

        global $pagenow, $quadmenu_locations;

        if ($pagenow != 'nav-menus.php')
            return;

        if (count($quadmenu_locations) < 1)
            return;
        ?>
        <script>
            jQuery(document).ready(function () {
                var $list = jQuery('.menu-theme-locations'),
                        locations = <?php echo json_encode(array_keys($quadmenu_locations)); ?>,
                        icon = '<img src="<?php echo esc_url(QUADMENU_URL_ASSETS . '/backend/images/q2.png'); ?>" style="width: 1em; height: auto; margin: 1px 0 -1px 0; "/>';

                jQuery.each(locations, function (index, item) {
                    $list.find('input#locations-' + item).after(icon);
                });

            });
        </script>
        <?php
    }

    public function notices() {

        if ($notices = get_option('quadmenu_admin_notices', false)) {
            foreach ($notices as $notice) {

                if (empty($notice['class']) || empty($notice['notice']))
                    continue;
                ?>
                <div class="<?php echo esc_attr($notice['class']); ?>">
                    <p><?php esc_html_e($notice['notice']); ?></p>
                </div>
                <?php
            }
            delete_option('quadmenu_admin_notices');
        }
    }

    static function add_notification($class = 'updated', $notice = false) {

        if (!$notice)
            return;

        $notices = get_option('quadmenu_admin_notices', array());

        $notices[] = array(
            'class' => $class,
            'notice' => $notice,
        );

        update_option('quadmenu_admin_notices', $notices);
    }

}

new QuadMenu_Admin();
