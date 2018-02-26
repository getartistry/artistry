<?php

if (!defined('ABSPATH')) {
    die('-1');
}
/*
class QuadMenu_Widget extends WP_Widget {

    function QuadMenu_Widget() {
        $widget_ops = array('classname' => 'widget_quadmenu_widget', 'description' => esc_html__('A widget that displays the menu in the sidebar.', 'quadmenu'));

        $control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'quadmenu_widget');

        parent::__construct('quadmenu_eidget', esc_html__('QuadMenu Widget', 'quadmenu'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;

        if (empty($instance['location']))
            echo esc_html__('Select a valid theme location', 'quadmenu');
        else
            quadmenu(array(
                'theme_location' => $instance['location'],
            ));

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['location'] = strip_tags($new_instance['location']);
        return $instance;
    }

    function select_themes($current = false) {

        global $quadmenu_themes;

        foreach ($quadmenu_themes as $key => $theme) {

            $selected = $current == $key ? 'selected="selected"' : '';

            $output .= '<option value="' . esc_attr($key) . '" ' . $selected . '>';
            $output .= $theme['name'];
            $output .= '</option>';
        }

        return $output;
    }

    function form($instance) {

        $defaults = array(
            'location' => '',
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        //1326
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('location'); ?>"><?php esc_html_e('Location', 'quadmenu'); ?>:</label>
            <select id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>" class="widefat">
                <?php echo $this->select_themes($instance['location']); ?>
            </select>
        </p>

        <?php
    }

}*/
