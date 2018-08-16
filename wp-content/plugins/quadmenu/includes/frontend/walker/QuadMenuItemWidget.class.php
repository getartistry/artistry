<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemWidget extends QuadMenuItem {

    protected $type = 'widget';

    function get_start_el() {

        $item_output = '';

        $this->add_item_classes();

        $this->add_item_classes_prefix();

        $this->add_item_classes_widget();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $item_output .= $this->widget($this->item->widget_id);

        return $item_output;
    }

    function add_item_classes_widget() {

        $this->item_classes = array_diff($this->item_classes, array('quadmenu-item-type-custom'));

        $this->item_classes[] = 'quadmenu-item-type-' . $this->item->quadmenu;
    }

    public function widget($id) {

        if ($this->depth > 0) {

            global $wp_registered_widgets;

            if (!isset($wp_registered_widgets[$id])) {
                ob_start();
                ?>
                <div class="quadmenu-alert">
                    <?php printf(esc_html__('Widget %s dosen\'t exists', 'quadmenu'), $id); ?>     
                </div>
                <?php
                return ob_get_clean();
            }

            $params = array_merge(
                    array(array_merge(array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']))), (array) $wp_registered_widgets[$id]['params']
            );

            $params[0]['before_title'] = apply_filters('quadmenu_before_widget_title', '<h4 class="quadmenu-title">', $wp_registered_widgets[$id]);
            $params[0]['after_title'] = apply_filters('quadmenu_after_widget_title', '</h4>', $wp_registered_widgets[$id]);
            $params[0]['before_widget'] = apply_filters('quadmenu_before_widget', '', $wp_registered_widgets[$id]);
            $params[0]['after_widget'] = apply_filters('quadmenu_after_widget', '', $wp_registered_widgets[$id]);

            $callback = $wp_registered_widgets[$id]['callback'];

            if (is_callable($callback)) {
                ob_start();
                ?>
                <div class="quadmenu-item-widget widget <?php echo esc_attr($wp_registered_widgets[$id]['classname']); ?>">
                    <?php call_user_func_array($callback, $params); ?>
                </div>
                <?php
                return ob_get_clean();
            }
        }
    }

}
