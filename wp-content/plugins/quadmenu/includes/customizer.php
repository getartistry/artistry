<?php
if (!defined('ABSPATH')) {
    die('-1');
}

/*class QuadMenu_Customizer {

    public function __construct() {

        if (!class_exists('ReduxFramework')) {
            require_once QUADMENU_PATH . 'includes/lib/ReduxCore/framework.php';
        }

        add_action('wp_head', array($this, 'head'), 999);

        add_action('wp_enqueue_scripts', array($this, 'enqueue'), 9999);

        add_action('customize_controls_enqueue_scripts', array($this, 'controls_enqueue'));

        add_filter('redux/options/' . QUADMENU_REDUX . '/args', array($this, 'arguments'));

        add_action('redux/extensions/' . QUADMENU_REDUX . '/before', array($this, 'advanced'), 0);
    }

    public function enqueue() {

        global $quadmenu;

        wp_dequeue_style('quadmenu-locations');

        wp_enqueue_style('quadmenu-customizer', QUADMENU_URL . 'assets/frontend/less/quadmenu_customizer.less', array(), '', 'all');
        wp_enqueue_script('quadmenu-customizer', QUADMENU_URL . 'assets/backend/js/quadmenu-customizer-preview.min.js', array(), false, true);
        wp_enqueue_script('quadmenu-less', QUADMENU_URL . 'assets/backend/js/less.min.js', array('quadmenu-customizer'), false, true);

        //wp_enqueue_script('quadmenu-less', QUADMENU_URL . 'assets/backend/js/less.min.js', array(), false, true);

        wp_localize_script('quadmenu-less', 'quadmenu', array(
            'variables' => json_encode(array_merge(QuadMenu_Compiler::less_locations(), QuadMenu_Compiler::less_variables($quadmenu))),
                )
        );
    }

    function head() {

        global $quadmenu;
        ?>
        <link rel="stylesheet/less" type="text/css" href="<?php echo QUADMENU_URL . 'assets/frontend/less/quadmenu-locations.less'; ?>" />
        <script>
            less = {
                env: "development",
            };
        </script>
        <script src="<?php echo QUADMENU_URL . 'assets/backend/js/less.min.js'; ?>"></script>
        <script>
            less.modifyVars(<?php echo json_encode(array_merge(QuadMenu_Compiler::less_locations(), QuadMenu_Compiler::less_variables($quadmenu))); ?>);
        </script>
        <?php
    }

    function controls_enqueue() {
        //wp_enqueue_style('quadmenu-admin');
        //wp_enqueue_style('quadmenu-admin-icons');
    }

    function arguments($args) {

        if (!file_exists(ReduxFramework::$_dir . 'inc/extensions/customizer/inc/customizer_section.php')) {

            QuadMenu_Admin::add_notification('error', esc_html__('An outdated version of redux have been loaded from other theme or plugin. Please update to enable the customizer.', 'quadmenu'));

            return $args;
        }

        $args['customizer'] = true;

        return $args;
    }

    function advanced($ReduxFramework) {

        if (!class_exists('ReduxFramework_extension_advanced_customizer')) {

            require_once(QUADMENU_PATH . 'includes/lib/redux/advanced_customizer/extension_advanced_customizer.php');

            new ReduxFramework_extension_advanced_customizer($ReduxFramework);
        }
    }

}

new QuadMenu_Customizer();*/
