<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemSearch extends QuadMenuItem {

    protected $type = 'search';
    var $instance = 0;

    function init() {
        $this->item->url = '';
        $this->item->title = '';
        $this->has_children = false;
        $this->has_placeholder = (bool) $this->item->placeholder;
        
        $this->item->placeholder = $this->has_placeholder ? $this->item->placeholder : esc_html__('Search', 'quadmenu');
    }

    function get_start_el() {

        $item_output = '';

        $this->add_item_classes();

        $this->add_item_classes_prefix();

        $this->add_item_classes_quadmenu();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $this->add_link_atts();

        $this->add_link_atts_toggle();

        $item_output .= $this->form();

        return $item_output;
    }

    function get_search_toggle() {
        $item_output = $this->get_link();
        $item_output .= $this->get_dropdown_wrap_start();
        $item_output .= $this->get_search_embed();
        $item_output .= $this->get_dropdown_wrap_end();
        return $item_output;
    }

    function form() {

        $this->instance = rand();

        ob_start();
        ?>

        <form role="search" method="get" id="searchform_<?php echo esc_attr($this->instance); ?>" action="<?php echo esc_url(home_url('/')); ?>">
            <span class="quadmenu-item-content">
                <?php echo $this->get_icon(); ?>
                <input type="text" id="s_<?php echo esc_attr($this->instance); ?>" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_html($this->item->placeholder); ?>" />
                <?php foreach ((array) $this->item->search as $search) : ?>
                    <input type="hidden" name="post_type[]" value="<?php echo esc_attr($search); ?>">
                <?php endforeach; ?>
                </form>
                <?php
                return ob_get_clean();
            }

        }
        