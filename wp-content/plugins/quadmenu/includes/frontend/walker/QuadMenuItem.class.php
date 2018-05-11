<?php
if (!defined('ABSPATH')) {
    die('-1');
}

abstract class QuadMenuItem {

    protected $type = 'unknown';
    protected $ID = 0;
    protected $source_id = 0;
    protected $output;
    protected $item;
    protected $depth;
    protected $args;
    protected $id;
    protected $walker;
    protected $dropdown_classes = array();
    protected $dropdown_ul_classes = array();
    protected $item_classes = array(0 => '');
    protected $item_atts = array();
    protected $dropdown_style = array();
    protected $has_children = false;
    protected $thumbnail = '';
    protected $description = '';
    private $prefix = '#quadmenu-';

    function __construct(&$output, &$item, $depth = 0, &$args = array(), $id = 0, &$walker, $has_children = false) {

        $this->output = &$output;
        $this->item = &$item;
        $this->depth = $depth;
        $this->args = &$args;
        $this->id = $id;
        $this->walker = &$walker;
        $this->source_id = $this->item->db_id;
        $this->has_children = $has_children;

        $this->item->columns = array_filter((array) $this->item->columns);

        // Arguments
        // ---------------------------------------------------------------------
        $this->args->has_title = (bool) $this->item->title;
        $this->args->has_icon = (bool) $this->item->icon;
        $this->args->has_link = (bool) $this->item->url;
        $this->args->has_caret = $this->args->has_dropdown = $this->has_children;
        $this->args->has_submenu = (0 < $this->depth && $this->args->has_dropdown);
        $this->args->has_subtitle = (bool) $this->item->subtitle;
        $this->args->has_badge = (bool) $this->item->badge;
        $this->args->has_background = (bool) $this->item->background;
        $this->args->has_description = false;
        $this->args->has_thumbnail = false;
        $this->args->has_columns = array_filter((array) $this->item->columns);

        // Depth
        // ---------------------------------------------------------------------

        if (0 < $this->depth) {
            $this->args->has_description = (bool) $this->item->description;
        }

        $this->init();

        $this->remove_item_url();

        $this->add_item_dropdown_classes();

        $this->add_item_dropdown_ul_classes();
    }

    function start_el() {
        $this->output.= apply_filters('quadmenu_nav_menu_start_el', $this->get_start_el(), $this->item, $this->depth, $this->args);
    }

    function end_el() {
        $this->output.= apply_filters('quadmenu_nav_menu_end_el', $this->get_end_el(), $this->item, $this->depth, $this->args);
    }

    function start_lvl() {
        $this->output.= $this->get_dropdown_wrap_start();
    }

    function end_lvl() {
        $this->output.= $this->get_dropdown_wrap_end();
    }

    abstract function get_start_el();

    function get_end_el() {
        $item_output = '</li>';
        return $item_output;
    }

    function init() {
        
    }

    function add_item_classes() {

        $this->item_classes[] = 'menu-item-' . $this->item->ID;

        if (is_array($this->item->classes)) {
            $this->item_classes = array_merge($this->item_classes, $this->item->classes);
        }
    }

    function add_item_classes_current() {

        if ($this->args->layout_current) {

            $parent = (bool) array_search('current-menu-parent', $this->item->classes);

            $current = (bool) array_search('current-menu-item', $this->item->classes);

            if ($parent || $current) {
                $this->item_classes[] = 'open';
            }
        }
    }

    function add_item_classes_prefix() {

        $this->item_classes = array_diff($this->item_classes, array('menu-item-type-custom'));

        foreach ($this->item_classes as $i => $class) {

            if (in_array(sanitize_key($class), array('open', 'active')))
                continue;

            if (substr($class, 0, 4) == 'menu') {
                $this->item_classes[$i] = str_replace('menu', 'quadmenu', $class);
            }
        }
    }

    function add_item_classes_quadmenu() {

        $this->item_classes[] = 'quadmenu-item-type-' . $this->type;

        $this->item_classes[] = 'quadmenu-item-level-' . $this->depth;

        $this->item_classes[] = $this->args->has_dropdown ? 'quadmenu-dropdown' : '';

        $this->item_classes[] = $this->args->has_submenu ? 'quadmenu-dropdown-submenu' : '';

        $this->item_classes[] = $this->args->has_subtitle ? 'quadmenu-has-subtitle' : '';

        $this->item_classes[] = $this->args->has_badge ? 'quadmenu-has-badge' : '';

        $this->item_classes[] = $this->args->has_caret ? 'quadmenu-has-caret' : '';

        $this->item_classes[] = $this->args->has_description ? 'quadmenu-has-description' : '';

        $this->item_classes[] = $this->args->has_thumbnail ? 'quadmenu-has-image-' . $this->item->thumb : '';

        $this->item_classes[] = $this->args->has_title ? 'quadmenu-has-title' : '';

        $this->item_classes[] = $this->args->has_icon ? 'quadmenu-has-icon' : '';

        $this->item_classes[] = $this->args->has_link || $this->args->has_dropdown ? 'quadmenu-has-link' : '';

        $this->item_classes[] = $this->args->has_background ? 'quadmenu-has-background' : '';

        $this->item_classes[] = !empty($this->item->dropdown) ? 'quadmenu-dropdown-' . $this->item->dropdown : '';

        $this->item_classes[] = !empty($this->item->float) ? 'quadmenu-float-' . $this->item->float : '';

        $this->item_classes[] = !empty($this->item->hidden) && is_array($this->item->hidden) ? join(' ', array_map('sanitize_html_class', $this->item->hidden)) : '';
    }

    function add_item_classes_maxheight() {

        if ($this->args->layout_dropdown_maxheight) {
            $this->item_classes[] = 'dropdown-maxheight';
        }
    }

    function get_item_id() {

        return ' id="menu-item-' . esc_attr($this->item->ID) . '"';
    }

    function get_item_classes() {
        $class_names = join(' ', apply_filters('quadmenu_nav_menu_css_class', array_filter($this->item_classes), $this->item, $this->args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        return $class_names;
    }

    function add_link_atts() {

        $this->item_atts['title'] = !empty($this->item->attr_title) ? $this->item->attr_title : '';

        $this->item_atts['target'] = !empty($this->item->target) ? '_blank' : '';

        $this->item_atts['rel'] = !empty($this->item->xfn) ? $this->item->xfn : '';

        $this->item_atts['href'] = !empty($this->item->url) ? $this->item->url : 'javascript:void(0)';
    }

    function add_link_atts_toggle() {

        if ($this->has_children) {
            $this->item_atts['class'] = 'quadmenu-dropdown-toggle ' . $this->args->layout_trigger;
        }
    }

    function add_dropdown_background() {

        if (!$this->args->has_background)
            return;

        $_src = wp_get_attachment_image_src($this->item->background['thumbnail-id'], 'full');

        if (empty($_src[0]))
            return;

        ob_start();
        ?>
        <div class="quadmenu-dropdown-background" style="
             background-image: url('<?php echo esc_url($_src[0]); ?>');
             background-position: <?php echo esc_attr($this->item->background['position']); ?>;
             background-repeat: <?php echo esc_attr($this->item->background['repeat']); ?>;
             background-size: <?php echo esc_attr($this->item->background['size']); ?>;
             background-origin: <?php echo esc_attr($this->item->background['origin']); ?>;
             opacity: <?php echo esc_attr($this->item->background['opacity'] / 100); ?>">
        </div>
        <?php
        return ob_get_clean();
    }

    function get_link_attr() {

        $atts = '';

        foreach ($this->item_atts as $attr => $value) {

            if (empty($value))
                continue;

            if ($attr == 'href') {
                $value = esc_url($value);
            } elseif ($attr == 'title') {
                $value = esc_html($value);
            } else {
                $value = esc_attr($value);
            }

            $atts .= ' ' . esc_attr($attr) . '="' . $value . '"';
        }

        return $atts;
    }

    function get_link() {

        ob_start();
        ?>
        <?php echo $this->args->before; ?>
        <a <?php echo $this->get_link_attr(); ?>>
            <span class="quadmenu-item-content">
                <?php echo $this->args->link_before; ?>
                <?php echo $this->get_thumbnail(); ?>
                <?php echo $this->get_caret(); ?>
                <?php echo $this->get_icon(); ?>
                <?php echo $this->get_title(); ?>
                <?php echo $this->get_badge(); ?>
                <?php echo $this->get_subtitle(); ?>
                <?php echo $this->get_description(); ?>
                <?php echo $this->args->link_after; ?>
            </span>
        </a>
        <?php echo $this->args->after; ?>
        <?php
        return ob_get_clean();
    }

    function get_caret() {
        if ($this->args->has_caret) {
            ob_start();
            ?>
            <span class="quadmenu-caret"></span>
            <?php
            return ob_get_clean();
        }
    }

    function get_icon() {

        if ($this->args->has_icon && $this->item->icon) {
            ob_start();
            ?>
            <span class="quadmenu-icon <?php echo esc_attr($this->item->icon); ?>"></span>
            <?php
            return ob_get_clean();
        }
    }

    function get_title() {
        if ($this->args->has_title) {
            ob_start();
            ?>
            <span class="quadmenu-text"><?php echo $this->item->title; ?></span>
            <?php
            return ob_get_clean();
        }
    }

    function get_subtitle() {

        if (!empty($this->args->has_subtitle)) {
            ob_start();
            ?>
            <span class="quadmenu-subtitle"><?php echo esc_attr($this->item->subtitle); ?></span>
            <?php
            return ob_get_clean();
        }
    }

    function get_badge() {

        if (!empty($this->args->has_badge)) {
            ob_start();
            ?>
            <span class="quadmenu-badge"><span class="quadmenu-badge-bubble"><?php echo esc_attr($this->item->badge); ?></span></span>
            <?php
            return ob_get_clean();
        }
    }

    function get_thumbnail() {
        if (!empty($this->args->has_thumbnail)) {
            return get_the_post_thumbnail($this->item->object_id, $this->item->thumb);
        }
    }

    function get_description() {
        if (!empty($this->args->has_description)) {
            ob_start();
            ?>
            <span class="quadmenu-description"><?php echo esc_html($this->item->description); ?></span>
            <?php
            return ob_get_clean();
        }
    }

    function add_item_dropdown_classes() {
        $this->dropdown_classes[] = 'quadmenu-dropdown-menu';
    }

    function add_item_dropdown_ul_classes() {
        
    }

    function get_dropdown_ul_style() {
        if (!empty($this->dropdown_style)) {
            return ' style="' . join(';', $this->dropdown_style) . '"';
        }
    }

    function get_dropdown_ul_classes() {

        if (!empty($this->dropdown_ul_classes)) {

            $class_names = join(' ', $this->dropdown_ul_classes);
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
            return $class_names;
        }
    }

    function get_dropdown_ul_data() {
        
    }

    function get_dropdown_wrap_start() {
        ob_start();
        ?>
        <div id="dropdown-<?php echo esc_attr($this->item->ID); ?>" class="<?php echo join(' ', array_map('sanitize_html_class', $this->dropdown_classes)); ?>">
            <?php echo $this->add_dropdown_background(); ?>
            <ul<?php echo $this->get_dropdown_ul_style(); ?><?php echo $this->get_dropdown_ul_classes(); ?><?php echo $this->get_dropdown_ul_data(); ?>>
                <?php
                return ob_get_clean();
            }

            function get_dropdown_wrap_end() {
                ob_start();
                ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }

    function remove_item_url() {

        if (empty($this->item->url))
            return;

        if (strpos($this->item->url, $this->prefix) !== false) {
            $this->item->url = '';
        }
    }

    function clean_item_content($content) {

        $content = preg_replace('/\[[\/]?[^\]]*\]/', '', $content);

        $content = html_entity_decode($content);

        $content = wp_strip_all_tags($content, true);

        return $content;
    }

}
