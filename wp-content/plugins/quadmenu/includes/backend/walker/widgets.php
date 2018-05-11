<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Nav_Menu_Widgets extends QuadMenu_Settings {

    public function __construct() {

        add_meta_box('quadmenu_custom_nav_widgets', esc_html__('QuadMenu Widgets', 'quadmenu'), array($this, 'nav_widgets'), 'nav-menus', 'side', 'high');

        add_filter('quadmenu_edit_nav_menu_walker', array($this, 'add_nav_menu_item_widgets'), 10, 3);

        add_action('quadmenu_delete_nav_menu_item', array($this, 'delete_nav_menu_widget'), 10, 2);

        add_action('wp_ajax_quadmenu_form_widget', array($this, 'ajax_form_widget'));

        add_action('wp_ajax_quadmenu_save_widget', array($this, 'ajax_save_widget'));

        add_action('wp_update_nav_menu_item', array($this, 'update_nav_menu_item_widget'), 20, 3);

        add_action('admin_print_footer_scripts-nav-menus.php', array($this, 'admin_print_footer_scripts'));

        add_action('admin_print_scripts-nav-menus.php', array($this, 'admin_print_scripts'));

        add_action('admin_print_styles-nav-menus.php', array($this, 'admin_print_styles'));
    }

    public function nav_widgets() {

        global $_nav_menu_placeholder;

        $widgets = $this->get_available_widgets();
        ?>
        <div id="posttype-quadmenu-widget" class="posttypediv">
            <div id="tabs-panel-quadmenu-widget" class="tabs-panel tabs-panel-active">
                <ul id ="quadmenu-widget-checklist" class="categorychecklist form-no-clear">
                    <?php
                    if (count($widgets)) :

                        foreach ($widgets as $id => $widget):

                            $title = esc_html__('Widget', 'quadmenu');

                            if (isset($widget['label'])) {
                                $title = $widget['label'];
                            }

                            $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval($_nav_menu_placeholder) - 1 : -1;
                            ?>

                            <li class="quadmenu-type-depth-<?php echo esc_attr($widget['depth']); ?>">
                                <label class="menu-item-title">
                                    <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-label]" value="0"> 
                                    <?php echo esc_html($title); ?>
                                    <?php if (!empty($widget['desc'])): ?>
                                        <small><?php echo $widget['desc']; ?></small>
                                    <?php endif; ?>
                                </label>
                                <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-type]" value="custom">
                                <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-title]" value="<?php echo esc_attr($title); ?>">
                                <input type="hidden" class="menu-item-quadmenu" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-quadmenu]" value="widget">
                                <input type="hidden" class="menu-item-quadmenu-widget" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-quadmenu-widget]" value="<?php echo esc_attr($id); ?>">
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <p class="button-controls">
                <span class="add-to-menu">
                    <input type="submit" class="button-secondary submit-add-to-quadmenu right" value="<?php esc_html_e('Add to Menu'); ?>" name="submit-posttype-quadmenu-widget" id="submit-posttype-quadmenu-widget">
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
        <?php
    }

    public function get_available_widgets() {

        global $wp_widget_factory;

        $widgets = array();

        if (count($wp_widget_factory->widgets)) {

            foreach ($wp_widget_factory->widgets as $widget) {

                $disabled_widgets = apply_filters('quadmenu_incompatible_widgets', array());

                if (!in_array(sanitize_key($widget->id_base), $disabled_widgets)) {

                    $widgets[$widget->id_base] = array(
                        'depth' => 2,
                        'label' => $widget->name,
                        'url' => '#widget',
                    );

                    if (isset($widget->widget_options['description'])) {
                        $widgets[$widget->id_base]['desc'] = $widget->widget_options['description'];
                    }
                }
            }
        }

        return $widgets;
    }

    public function update_nav_menu_item_widget($menu_id, $menu_item_db_id, $args) {

        if (!empty($args['menu-item-quadmenu-widget'])) {

            require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

            $saved_settings = array_filter((array) get_post_meta($menu_item_db_id, QUADMENU_DB_KEY, true));

            $id_base = sanitize_text_field($args['menu-item-quadmenu-widget']);

            $next_id = next_widget_id_number($id_base);

            $this->add_widget_instance($id_base, $next_id, $menu_item_db_id);

            $widget_id = $this->add_widget_to_sidebar($id_base, $next_id);

            $saved_settings['widget'] = $id_base;

            $saved_settings['widget_number'] = $next_id;

            $saved_settings['widget_id'] = $widget_id;

            update_post_meta($menu_item_db_id, QUADMENU_DB_KEY, $saved_settings);
        }
    }

    function add_nav_menu_item_widgets($walker_class_name, $menu_id = null, $menu_obj = null, $menu_items = null) {

        if (!empty($menu_obj->menu_item_parent) && !empty($menu_obj->quadmenu) && $menu_obj->quadmenu === 'widget') {
            return __CLASS__;
        }

        return $walker_class_name;
    }

    public function walk($elements, $max_depth, $args) {

        $output = '';

        foreach ($elements as $e) {

            $output .= $this->widgets($e, (object) $args);
        }

        return $output;

        wp_die();
    }

    public function widgets($widget_obj, $args) {

        global $wp_registered_widgets;

        if (!isset($args->doing_ajax) && $widget_obj->_invalid) {
            ob_start();
            ?>
            <li id="item-<?php echo esc_attr($widget_obj->ID); ?>" class="quadmenu-column-item quadmenu-invalid sortable-item" title="<?php echo esc_attr($widget_obj->title); ?>" data-menu_item_type="<?php echo esc_attr($widget_obj->quadmenu); ?>" data-menu_item_id="<?php echo esc_attr($widget_obj->ID); ?>" data-menu_item_position="<?php echo esc_attr($widget_obj->menu_order); ?>">
                <div class="inner">
                    <div class="action-top clearfix">
                        <div class="actions">
                            <a class="option remove" title="<?php echo esc_html__('Remove', 'quadmenu'); ?>"></a>
                            <span class="spinner active"></span>
                        </div>                
                        <div class="item-title">
                            <h4 class="quadmenu-title"><?php echo esc_html($widget_obj->title); ?> <?php esc_html_e('(Invalid)', 'quadmenu'); ?></h4>
                        </div>
                    </div>
                    <div class="settings">                       
                        <form class="form-widget" method="post" data-menu_item_id="<?php echo esc_attr($widget_obj->ID); ?>">
                        </form>
                    </div>
                </div>
            </li>
            <?php
            return ob_get_clean();
        }

        ob_start();
        ?>                
        <li id="item-<?php echo esc_attr($widget_obj->ID); ?>" class="quadmenu-column-item quadmenu-widget sortable-item" title="<?php echo esc_html($widget_obj->title); ?>" data-menu_item_type="<?php echo esc_attr($widget_obj->quadmenu); ?>" data-menu_item_id="<?php echo esc_attr($widget_obj->ID); ?>" data-menu_item_position="<?php echo esc_attr($widget_obj->menu_order); ?>" data-widget="<?php echo esc_attr($widget_obj->widget); ?>" data-widget_id="<?php echo esc_attr($widget_obj->widget_id); ?>">
            <div class="inner">
                <div class="action-top clearfix">
                    <div class="actions">
                        <a class="option edit" title="<?php echo esc_html__('Edit', 'quadmenu'); ?>"></a>
                        <a class="option remove" title="<?php echo esc_html__('Remove', 'quadmenu'); ?>"></a>
                        <span class="spinner active"></span>
                    </div>                
                    <div class="item-title">
                        <h4 class="quadmenu-title"><?php echo esc_html($widget_obj->title); ?></h4>
                    </div>
                </div>
                <div class="settings">
                    <div class="widget" title="<?php echo esc_attr($widget_obj->title); ?>" id="<?php echo esc_attr($widget_obj->widget_id); ?>" data-type="widget" data-id="<?php echo esc_attr($widget_obj->widget_id); ?>">
                        <div class="widget-inner widget-inside">                           
                            <form method="post" data-menu_item_id="<?php echo esc_attr($widget_obj->ID); ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <?php
        return ob_get_clean();
    }

    public function ajax_form_widget() {

        if (!check_ajax_referer('quadmenu', 'nonce', false)) {
            QuadMenu::send_json_error(esc_html__('Please reload page.', 'quadmenu'));
        }

        $widget_id = sanitize_text_field($_POST['widget_id']);
        $menu_item_id = absint($_POST['menu_item_id']);

        if (ob_get_contents())
            ob_clean();

        if ($form = $this->form_widget($widget_id)) {
            QuadMenu::send_json_success($form);
        } else {
            QuadMenu::send_json_error(sprintf(esc_html__('Failed to load widget %s form in %d', 'quadmenu'), $widget_id, $menu_item_id));
        }

        wp_die();
    }

    public function form_widget($widget_id) {

        global $wp_registered_widget_controls;

        $control = $wp_registered_widget_controls[$widget_id];

        $id_base = $this->get_id_base_for_widget_id($widget_id);

        ob_start();
        ?>
        <input type="hidden" name="widget-id" class="widget-id" value="<?php echo esc_attr($widget_id); ?>" />
        <input type="hidden" name="id_base" class="id_base" value="<?php echo esc_attr($id_base); ?>" />
        <input type="hidden" name="widget_id" class="widget_id" value="<?php echo esc_attr($widget_id); ?>" />
        <div class="widget-content">
            <?php
            if (is_callable($control['callback'])) {
                call_user_func_array($control['callback'], $control['params']);
            }
            ?>
            <?php submit_button(esc_html__('Save'), 'button button-primary alignright', 'savewidget', false); ?>
        </div>
        <div class="clearfix"></div>
        <?php
        return ob_get_clean();
    }

    private function add_widget_instance($id_base, $next_id, $menu_item_id) {

        $current_widgets = get_option('widget_' . $id_base);

        $current_widgets[$next_id] = array(
            'quadmenu_parent_menu_id' => $menu_item_id
        );

        update_option('widget_' . $id_base, $current_widgets);
    }

    private function add_widget_to_sidebar($id_base, $next_id) {

        $widget_id = $id_base . '-' . $next_id;

        $sidebar_widgets = $this->get_sidebar_widgets();

        $sidebar_widgets[] = $widget_id;

        $this->set_sidebar_widgets($sidebar_widgets);

        return $widget_id;
    }

    public function get_sidebar_widgets() {

        global $wp_registered_sidebars;

        if (!isset($wp_registered_sidebars['quadmenu-widgets'])) {
            return false;
        }

        return $wp_registered_sidebars['quadmenu-widgets'];
    }

    private function set_sidebar_widgets($widgets) {

        global $wp_registered_sidebars;

        if (!isset($wp_registered_sidebars['quadmenu-widgets'])) {
            return false;
        }

        $wp_registered_sidebars['quadmenu-widgets'] = $widgets;

        wp_set_sidebars_widgets($wp_registered_sidebars);
    }

    public function ajax_save_widget() {

        if (!check_ajax_referer('quadmenu', 'nonce', false)) {
            QuadMenu::send_json_error(esc_html__('Please reload page.', 'quadmenu'));
        }

        $menu_item_id = absint($_POST['menu_item_id']);

        $id_base = sanitize_text_field($_POST['id_base']);

        $saved = $this->save_widget($id_base);

        if ($saved) {
            QuadMenu::send_json_success(sprintf(esc_html__('Saved settings of %s Widget', 'quadmenu'), $saved[0]->name));
        } else {
            QuadMenu::send_json_error(sprintf(esc_html__('Failed to add %s to %d', 'quadmenu'), $id_base, $menu_item_id));
        }

        wp_die();
    }

    public function save_widget($id_base) {

        global $wp_registered_widget_updates;

        $control = $wp_registered_widget_updates[$id_base];

        if (isset($control['callback']) && isset($control['params']) && is_callable($control['callback'])) {

            call_user_func_array($control['callback'], $control['params']);

            return $control['callback'];
        }

        return false;
    }

    public function delete_nav_menu_widget($ID, $menu_id) {

        $menu_obj = get_post($ID);

        if (empty($menu_obj->ID))
            return;

        $menu_obj = wp_setup_nav_menu_item($menu_obj);

        if (empty($menu_obj->widget_id))
            return;

        $this->delete_widget($menu_obj->widget_id);
    }

    function delete_widget($widget_id) {

        $this->remove_widget_from_sidebar($widget_id);
        $this->remove_widget_instance($widget_id);

        return true;
    }

    private function remove_widget_from_sidebar($widget_id) {

        $widgets = $this->get_sidebar_widgets();

        $new_quadmenu_widgets = array();

        if (count($widgets)) {
            foreach ($widgets as $widget) {

                if ($widget != $widget_id)
                    $new_quadmenu_widgets[] = $widget;
            }
        }
        $this->set_sidebar_widgets($new_quadmenu_widgets);

        return $widget_id;
    }

    public function get_id_base_for_widget_id($widget_id) {
        global $wp_registered_widget_controls;

        if (!isset($wp_registered_widget_controls[$widget_id])) {
            return false;
        }

        $control = $wp_registered_widget_controls[$widget_id];

        $id_base = isset($control['id_base']) ? $control['id_base'] : $control['id'];

        return $id_base;
    }

    private function remove_widget_instance($widget_id) {

        $id_base = $this->get_id_base_for_widget_id($widget_id);

        $widget_number = $this->get_widget_number_for_widget_id($widget_id);

        $current_widgets = get_option('widget_' . $id_base);

        if (isset($current_widgets[$widget_number])) {

            unset($current_widgets[$widget_number]);

            update_option('widget_' . $id_base, $current_widgets);

            return true;
        }

        return false;
    }

    public function get_widget_number_for_widget_id($widget_id) {

        $parts = explode("-", $widget_id);

        return absint(end($parts));
    }

    public function admin_print_footer_scripts($hook) {

        do_action('admin_footer-widgets.php');
    }

    public function admin_print_scripts($hook) {

        do_action('admin_print_scripts-widgets.php');
    }

    public function admin_print_styles($hook) {

        do_action('admin_print_styles-widgets.php');
    }

}

new QuadMenu_Nav_Menu_Widgets();
