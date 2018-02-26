<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Nav_Menu_Mega extends QuadMenu_Nav_Menu_Column {

    public function __construct() {

        add_action('quadmenu_modal_panels_tab', array($this, 'modal_panels_tab'), 10, 4);

        add_action('quadmenu_modal_panels_pane', array($this, 'modal_panels_pane'), 10, 4);
    }

    function modal_panels_tab($menu_item_depth, $menu_obj, $menu_id) {

        if (!empty($menu_obj->quadmenu) && $menu_obj->quadmenu === 'mega') {
            ?>
            <li><a href="#setting_columns_<?php echo esc_attr($menu_obj->ID); ?>" data-quadmenu="tab" aria-expanded="true"><i class="dashicons dashicons-layout"></i><span class="title"><?php esc_html_e('Columns', 'quadmenu'); ?></span></a></li>
            <?php
        }
    }

    function modal_panels_pane($menu_item_depth, $menu_obj, $menu_id) {
        if (!empty($menu_obj->quadmenu) && $menu_obj->quadmenu === 'mega') {
            ?>
            <div role="tabpanel" class="quadmenu-tab-pane fade" id="setting_columns_<?php echo esc_attr($menu_obj->ID); ?>">
                <?php echo $this->columns($menu_obj, $menu_id); ?>                       
            </div>
            <?php
        }
    }

}

new QuadMenu_Nav_Menu_Mega();

