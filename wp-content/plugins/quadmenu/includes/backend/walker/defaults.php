<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Nav_Menu_Defaults extends QuadMenu_Settings {

    public function __construct() {

        add_filter('quadmenu_edit_nav_menu_walker', array($this, 'add_nav_menu_item_defaults'), -10, 3);
    }

    function add_nav_menu_item_defaults($walker_class_name, $menu_id = null, $menu_obj = null, $menu_items = null) {

        if (!empty($menu_obj->menu_item_parent) && !empty($menu_obj->type) && in_array(sanitize_key($menu_obj->type), array('custom', 'taxonomy', 'post_type_archive', 'post_type', 'icon', 'search'))) {
            return __CLASS__;
        }

        return $walker_class_name;
    }

    public function walk($elements, $max_depth) {

        $output = '';

        foreach ($elements as $e) {

            $output .= $this->defaults($e);
        }

        return $output;

        wp_die();
    }

    public function defaults($menu_obj, $menu_id = 0) {

        if (!isset($args->doing_ajax) && $menu_obj->_invalid) {
            ob_start();
            ?>
            <li id="item-<?php echo esc_attr($menu_obj->ID); ?>" class="quadmenu-column-item quadmenu-invalid sortable-item" title="<?php echo esc_attr($menu_obj->title); ?>" data-menu_item_type="<?php echo esc_attr($menu_obj->quadmenu); ?>" data-menu_item_id="<?php echo esc_attr($menu_obj->ID); ?>" data-menu_item_position="<?php echo esc_attr($menu_obj->menu_order); ?>">
                <div class="inner">
                    <div class="action-top clearfix">
                        <div class="actions">
                            <a class="option remove" title="<?php echo esc_html__('Remove', 'quadmenu'); ?>"></a>
                            <span class="spinner active"></span>
                        </div>                
                        <div class="item-title">
                            <h4 class="quadmenu-title"><?php echo esc_html($menu_obj->title); ?> <?php esc_html_e('(Invalid)', 'quadmenu'); ?></h4>
                        </div>
                    </div>
                    <div class="settings">
                        <form method="post" data-menu_item_id="<?php echo esc_attr($menu_obj->ID); ?>">
                        </form>
                    </div>
                </div>
            </li>
            <?php
            return ob_get_clean();
        }

        ob_start();
        ?>                
        <li id="item-<?php echo esc_attr($menu_obj->ID); ?>" class="quadmenu-column-item sortable-item" title="<?php echo esc_attr($menu_obj->title); ?>" data-menu_item_type="<?php echo esc_attr($menu_obj->quadmenu); ?>" data-menu_item_id="<?php echo esc_attr($menu_obj->ID); ?>" data-menu_item_position="<?php echo esc_attr($menu_obj->menu_order); ?>">
            <div class="inner">
                <div class="action-top clearfix">
                    <div class="actions">
                        <a class="option edit" title="<?php esc_html_e('Edit', 'quadmenu'); ?>"></a>
                        <a class="option remove" title="<?php esc_html_e('Remove', 'quadmenu'); ?>"></a>
                        <span class="spinner active"></span>
                    </div>                
                    <div class="item-title">
                        <h4 id="quadmenu-title-<?php echo esc_attr($menu_obj->ID); ?>" class="quadmenu-title"><i class="<?php echo esc_attr($menu_obj->icon); ?>"></i><span><?php echo esc_html($menu_obj->title); ?></span></h4>
                    </div>
                </div>
                <div class="settings">
                    <?php do_action('quadmenu_modal_panels', $menu_obj->depth, $menu_obj, $menu_id); ?>       
                </div>
            </div>
        </li>
        <?php
        return ob_get_clean();
    }

}

new QuadMenu_Nav_Menu_Defaults();
