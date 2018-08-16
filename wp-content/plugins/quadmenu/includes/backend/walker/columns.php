<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Nav_Menu_Column extends QuadMenu_Settings {

    public function __construct() {
        add_filter('quadmenu_edit_nav_menu_walker', array($this, 'add_nav_menu_item_column'), 10, 3);
    }

    function add_nav_menu_item_column($walker_class_name, $menu_id = null, $menu_obj = null, $menu_items = null) {

        if (!empty($menu_obj->menu_item_parent) && !empty($menu_obj->quadmenu) && $menu_obj->quadmenu === 'column') {
            return __CLASS__;
        }

        return $walker_class_name;
    }

    public function walk($elements, $max_depth) {

        $output = '';

        foreach ($elements as $e) {

            $output .= $this->column($e);
        }

        return $output;

        wp_die();
    }

    public function column($column_obj, $menu_id = 0) {

        ob_start();
        ?>
        <div id="column_<?php echo esc_attr($column_obj->ID); ?>" class="quadmenu-column quadmenu-item-depth-2 <?php echo join(' ', array_map('sanitize_html_class', $column_obj->columns)); ?>" data-columns="<?php echo join(' ', array_map('sanitize_html_class', $column_obj->columns)); ?>" data-menu_item_id="<?php echo esc_attr($column_obj->ID); ?>">
            <div class="inner">
                <div class="action-top clearfix">
                    <div class="actions">
                        <!--<a class="option contract" title="<?php echo esc_attr(esc_html__('Contract', 'quadmenu')); ?>"></a>
                        <a class="option expand" title="<?php echo esc_attr(esc_html__('Expand', 'quadmenu')); ?>"></a>-->
                        <a class="option edit" title="<?php esc_html_e('Edit', 'quadmenu'); ?>"></a>
                        <a class="option remove" title="<?php esc_html_e('Remove', 'quadmenu'); ?>"></a>
                        <span class="spinner"></span>
                    </div>
                </div>
                <div class="settings">
                    <?php echo $this->form($column_obj, 1, array('columns')); ?>                       
                </div>
                <ul id="quadmenu-column-items-<?php echo esc_attr($column_obj->ID); ?>" class="items add-quadmenu-column-item sortable-area" data-sortable-items=".quadmenu-column-item" data-sortable-handle=".action-top" data-sortable-connect=".items" data-menu_item_parent_id="<?php echo esc_attr($column_obj->ID); ?>">     
                    <?php
                    $items = $this->get_children_nav_menu_items($column_obj->ID, $menu_id);

                    if (is_array($items) && count($items)) :
                        foreach ($items as $item):

                            $menu_obj = QuadMenu::wp_setup_nav_menu_item($item['id']);

                            $walker_class_name = apply_filters('quadmenu_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', null, $menu_obj, null);

                            require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

                            if (class_exists($walker_class_name)) {

                                $args = array(
                                    'after' => '',
                                    'before' => '',
                                    'link_after' => '',
                                    'link_before' => '',
                                    'walker' => new $walker_class_name,
                                );

                                echo walk_nav_menu_tree(array($menu_obj), 0, (object) $args);
                            }

                        endforeach;
                    endif;
                    ?>
                </ul>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function columns($menu_obj, $menu_id = 0) {

        $columns = $this->get_children_nav_menu_items($menu_obj->ID, $menu_id);

        ob_start();
        //require ET_BUILDER_DIR . 'class-et-builder-element.php';
        //require_once( ET_BUILDER_DIR . 'core.php' );
        //require_once ET_BUILDER_DIR . 'functions.php';
        //require_once ET_BUILDER_DIR . 'ab-testing.php';
        //require_once ET_BUILDER_DIR . 'class-et-builder-settings.php';
        //et_pb_before_main_editor($menu_obj);
        ?>
        <div id="columns_<?php echo esc_attr($menu_obj->ID); ?>" class="quadmenu-columns sortable-area row" data-drop-area="drop-column" data-sortable-items=".quadmenu-column" data-sortable-handle=".action-top" data-menu_item_parent_id="<?php echo esc_attr($menu_obj->ID); ?>">
            <input data-menu_item_type="custom" data-menu_item_quadmenu="column" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e('Column', 'quadmenu'); ?>" data-menu_item_parent_id="<?php echo esc_attr($menu_obj->ID); ?>" type="button" class="button button-primary submit-add-to-quadmenu-column" value="<?php esc_html_e('Add Column', 'quadmenu'); ?>" name="add_column"/>
            <span class="spinner"></span>
            <?php
            if (is_array($columns) && count($columns)) :
                foreach ($columns as $column):

                    $column_obj = get_post($column['id']);


                    $column_obj = QuadMenu::wp_setup_nav_menu_item($column['id']);


                    if (!isset($column_obj->quadmenu) || $column_obj->quadmenu != 'column') {
                        continue;
                    }

                    echo $this->column($column_obj, $menu_id);

                endforeach;
            endif;
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

}

new QuadMenu_Nav_Menu_Column();

