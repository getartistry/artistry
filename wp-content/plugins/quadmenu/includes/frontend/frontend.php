<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Frontend {

    public function __construct() {

        add_action('wp_enqueue_scripts', array($this, 'register'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));

        add_action('wp_head', array($this, 'meta'));

        add_action('wp_head', array($this, 'css'));
    }

    public function register() {

        wp_register_style('owlcarousel', QUADMENU_URL . 'assets/frontend/owlcarousel/owl.carousel.min.css', array(), QUADMENU_VERSION, 'all');
        wp_register_script('owlcarousel', QUADMENU_URL . 'assets/frontend/owlcarousel/owl.carousel.min.js', array('jquery'), QUADMENU_VERSION, true);

        wp_register_style('pscrollbar', QUADMENU_URL . 'assets/frontend/pscrollbar/perfect-scrollbar.min.css', array(), QUADMENU_VERSION, 'all');
        wp_register_script('pscrollbar', QUADMENU_URL . 'assets/frontend/pscrollbar/perfect-scrollbar.jquery.min.js', array('jquery'), QUADMENU_VERSION, true);

        wp_register_style('quadmenu-normalize', QUADMENU_URL . 'assets/frontend/css/quadmenu-normalize' . QuadMenu::isMin() . '.css', array(), QUADMENU_VERSION, 'all');

        wp_register_script('quadmenu', QUADMENU_URL . 'assets/frontend/js/quadmenu' . QuadMenu::isMin() . '.js', array('hoverIntent'), QUADMENU_VERSION, true);

        wp_register_style('quadmenu', QUADMENU_URL . 'assets/frontend/css/quadmenu' . QuadMenu::isMin() . '.css', array(), QUADMENU_VERSION, 'all');

        if (is_file(QUADMENU_PATH_CSS . 'quadmenu-locations.css')) {
            wp_register_style('quadmenu-locations', QUADMENU_URL_CSS . 'quadmenu-locations.css', array(), filemtime(QUADMENU_PATH_CSS . 'quadmenu-locations.css'), 'all');
        } else {
            wp_register_style('quadmenu-locations', QUADMENU_URL . 'assets/frontend/css/quadmenu-locations.css', array(), QUADMENU_VERSION, 'all');
        }

        if (is_file(QUADMENU_PATH_CSS . 'quadmenu-widgets.css')) {
            wp_register_style('quadmenu-widgets', QUADMENU_URL_CSS . 'quadmenu-widgets.css', array(), filemtime(QUADMENU_PATH_CSS . 'quadmenu-widgets.css'), 'all');
        } else {
            wp_register_style('quadmenu-widgets', QUADMENU_URL . 'assets/frontend/css/quadmenu-widgets.css', array(), QUADMENU_VERSION, 'all');
        }
    }

    public function enqueue() {

        global $quadmenu;

        if (empty($quadmenu['styles']))
            return;

        if ($quadmenu['styles_pscrollbar']) {
            wp_enqueue_script('pscrollbar');
            wp_enqueue_style('pscrollbar');
        }

        if ($quadmenu['styles_owlcarousel']) {
            wp_enqueue_script('owlcarousel');
            wp_enqueue_style('owlcarousel');
        }

        if (!empty($quadmenu['styles_normalize'])) {
            wp_enqueue_style('quadmenu-normalize');
        }

        if (!empty($quadmenu['styles_widgets'])) {
            wp_enqueue_style('quadmenu-widgets');
        }

        wp_enqueue_style('quadmenu');

        wp_enqueue_style('quadmenu-locations');

        wp_enqueue_style(_QuadMenu()->selected_icons()->ID);

        wp_enqueue_script('quadmenu');

        wp_localize_script('quadmenu', 'quadmenu', apply_filters('quadmenu_global_js_data', array(
            'nonce' => wp_create_nonce('quadmenu'),
            'gutter' => $quadmenu['gutter'],
        )));

        wp_localize_script('quadmenu', 'ajaxurl', admin_url('admin-ajax.php'));
    }

    public function meta() {
        global $quadmenu;

        if (empty($quadmenu['viewport']))
            return;
        ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php
    }

    public function css() {

        global $quadmenu;

        if (empty($quadmenu['css']))
            return;
        ?>
        <style>
        <?php echo $quadmenu['css']; ?>   
        </style>
        <?php
    }

}

new QuadMenu_Frontend();
