<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemCart extends QuadMenuItem {

    protected $type = 'cart';

    function init() {

        $this->item->url = '';
        $this->args->has_dropdown = $this->has_children = true;
        $this->args->has_title = false;

        if (empty($this->item->title)) {
            $this->item->title = esc_html__('Your cart', 'quadmenu');
        }
    }

    function get_end_el() {
        if (!class_exists('WooCommerce'))
            return '';
    }

    function get_start_el() {

        if (!class_exists('WooCommerce'))
            return '';

        $item_output = '';

        $this->add_item_classes();

        $this->add_item_classes_prefix();

        $this->add_item_classes_quadmenu();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $this->add_link_atts();

        $this->add_link_atts_toggle();

        $this->add_link_atts_cart();

        $item_output .= $this->get_link();

        $item_output .= $this->get_dropdown_wrap_start();

        $item_output .= $this->widget();

        $item_output .= $this->get_dropdown_wrap_end();

        return $item_output;
    }

    function add_link_atts_cart() {

        if (function_exists('wc_get_cart_url')) {

            $this->item_atts['data-cart-url'] = wc_get_cart_url();
        }

        $this->item_atts['data-cart-qty'] = (int) WC()->cart->get_cart_contents_count();
    }

    function get_icon() {
        //if ($this->item->icon) {
            ob_start();
            ?>
            <span class="quadmenu-cart-magic">
                <span class="quadmenu-icon <?php echo esc_attr($this->item->icon); ?>"></span>
                <span class="quadmenu-cart-qty"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
            </span>
            <span class="quadmenu-cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
            <?php
            return ob_get_clean();
        //}
    }

    function widget() {
        ob_start();
        the_widget('WC_Widget_Cart', 'title=' . $this->item->title, 'before_title=<h4 class="quadmenu-title">&after_title=</h4>');
        return ob_get_clean();
    }

}
