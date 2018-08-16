<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemCart extends QuadMenuItem {

    protected $type = 'cart';
    public $count = 'cart';

    function init() {

        $this->item->url = '';
        $this->args->has_dropdown = $this->has_children = $this->args->has_caret = true;
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

        $this->add_item_classes_cart();

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

    function add_item_classes_cart() {

        $this->count = WC()->cart->get_cart_contents_count();

        if (empty($this->count)) {
            $this->item_classes[] = 'quadmenu-cart-empty';
        } else {
            $this->item->url = wc_get_cart_url();
        }
    }

    function add_link_atts_cart() {

        $this->item_atts['data-cart-url'] = wc_get_cart_url();

        $this->item_atts['data-cart-price'] = wc_price(0);

        $this->item_atts['data-cart-qty'] = esc_attr($this->count);

        if (!empty($this->args->navbar_animation_cart)) {
            $this->item_atts['data-cart-animation'] = join(' ', array_map('sanitize_html_class', (array) $this->args->navbar_animation_cart));
        }
    }

    function get_icon() {
        ob_start();
        ?>
        <span class="quadmenu-cart-magic">
            <span class="quadmenu-icon <?php echo esc_attr($this->item->icon); ?> <?php echo esc_attr($this->item->animation->icon); ?>"></span>
            <span class="quadmenu-cart-qty"><?php echo esc_html($this->count); ?></span>
        </span>
        <span class="quadmenu-cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
        <?php
        return ob_get_clean();
    }

    function widget() {
        ob_start();
        ?>
        <?php $this->get_cart_icon(); ?>
        <?php
        //if (!is_cart()) {
        the_widget('WC_Widget_Cart', 'title=');
        //}
        ?>
        <?php $this->get_cart_text(); ?>
        <?php
        return ob_get_clean();
    }

    function get_cart_text() {
        if (!empty($this->item->cart_text)) {
            ?>
            <div class="quadmenu-cart-text"><?php echo $this->item->cart_text; ?></div>
            <?php
        }
    }

    function get_cart_icon() {
        ?>
        <span class="quadmenu-empty-icon quadmenu-icon <?php echo esc_attr($this->item->icon); ?>"></span>
        <?php
    }

}
