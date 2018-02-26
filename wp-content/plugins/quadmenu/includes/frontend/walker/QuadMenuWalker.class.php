<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuWalker extends Walker_Nav_Menu {

    var $item_stack = array();
    var $current_umitem;
    var $auto_child = '';

    function __construct() {
        
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {

        if (!$element)
            return;

        $id_field = $this->db_fields['id'];

        $has_children = !empty($children_elements[$element->{$id_field}]);

        if (isset($args[0]) && is_array($args[0])) {
            $args[0]['has_children'] = $has_children;
        }

        $cb_args = array_merge(array(&$output, $element, $depth), $args);

        $id = $element->{$id_field};  // Moved up

        $umitem_obect_class = apply_filters('quadmenu_item_object_class', 'QuadMenuItemDefault', $element, $id, $this->auto_child);

        $umitem = new $umitem_obect_class($output, $element, $depth, $cb_args[3], $id, $this, $has_children); //The $args that get passed to start_el are $cb[3] -- i.e. the 4the element in the array merged above

        $this->push_item($umitem);

        call_user_func_array(array($this, 'start_el'), $cb_args);

        // descend only when the depth is right and there are childrens for this element
        if (($max_depth == 0 || $max_depth > $depth + 1 ) && isset($children_elements[$id])) {

            foreach ($children_elements[$id] as $child) {

                if (!isset($newlevel)) {
                    $newlevel = true;
                    //start the child delimiter
                    $cb_args = array_merge(array(&$output, $depth), $args);
                    call_user_func_array(array($this, 'start_lvl'), $cb_args);
                }
                $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
            }

            //Unset this item's children elements
            unset($children_elements[$id]); //TODO!!!
        }

        if (isset($newlevel) && $newlevel) {
            //end the child delimiter
            $cb_args = array_merge(array(&$output, $depth), $args);
            call_user_func_array(array($this, 'end_lvl'), $cb_args);
        }


        $cb_args = array_merge(array(&$output, $element, $depth), $args);

        call_user_func_array(array($this, 'end_el'), $cb_args);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        //$indent = str_repeat("\t", $depth);
        $this->current_umitem->start_lvl();
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        //$indent = str_repeat("\t", $depth);
        $this->current_umitem->end_lvl();
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

        $this->current_umitem->start_el();
    }

    function end_el(&$output, $item, $depth = 0, $args = array()) {
        $this->current_umitem->end_el();
        $this->pop_item();
    }

    function push_item($umitem) {
        $this->item_stack[] = $umitem;
        $this->current_umitem = $umitem;
    }

    function pop_item() {
        $umitem = array_pop($this->item_stack);
        $this->current_umitem = $this->current_item();
    }

    function current_item() {
        return end($this->item_stack);
    }

    public static function fallback($args) {

        global $_wp_registered_nav_menus;

        if (!current_user_can('manage_options'))
            return;

        extract($args);

        ob_start();
        ?>
        <li class="quadmenu-item quadmenu-item-object-custom quadmenu-item-level-0 quadmenu-item-type-default quadmenu-has-title quadmenu-has-icon quadmenu-has-link">
            <a title="Demos" href="<?php echo admin_url('nav-menus.php'); ?>">
                <span class="quadmenu-item-content">
                    <span class="quadmenu-icon">&#8505;</span>
                    <span class="quadmenu-text"><?php printf(esc_html__('Add a menu to %1$s', 'quadmenu'), $_wp_registered_nav_menus[$theme_location]); ?></span>
                </span>
            </a>
        </li>
        <?php
        $menu = ob_get_clean();

        $nav_menu = sprintf($items_wrap, esc_attr($menu_id), esc_attr($menu_class), $menu);

        echo quadmenu_template($nav_menu, (object) $args);
    }

}
