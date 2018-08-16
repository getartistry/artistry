<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Settings extends QuadMenu_Configuration {

    public $tabs;
    public $panels = array();

    public function __construct() {

        add_action('quadmenu_modal_panels', array($this, 'panels'), 10, 4);

        add_action('wp_ajax_quadmenu_add_nav_menu_item_panel', array($this, 'ajax_add_nav_menu_item_panel'));
    }

    public function delete_children_nav_menu_items($menu_item_id, $menu_id = 0) {

        $deleted = array();

        if ($menu_item_id > 0 && is_nav_menu_item($menu_item_id)) {

            $delete_menu_items_id = $this->get_children_nav_menu_items($menu_item_id, $menu_id);

            $delete_menu_items_id[] = array('id' => $menu_item_id);

            if (is_array($delete_menu_items_id) && count($delete_menu_items_id)) {
                foreach ($delete_menu_items_id as $item) {

                    $id = absint($item['id']);

                    do_action('quadmenu_delete_nav_menu_item', $id, $menu_id);

                    if (wp_delete_post($id, true)) {

                        $deleted[] = $id;
                    }
                }
            }
        }

        return $deleted;
    }

    public function get_children_nav_menu_items($parent_menu_item_id, $menu_id) {

        $childrens = array();

        // check we're using a valid menu ID
        if (!is_nav_menu($menu_id)) {
            return $childrens;
        }

        if (is_array($menu = $this->wp_get_nav_menu_items($menu_id)) && count($menu)) {

            foreach ($menu as $item) {

                // find the child menu items
                if ($parent_menu_item_id == $item->menu_item_parent) {

                    $childrens[$item->ID] = array(
                        'id' => $item->ID,
                        'title' => $item->title,
                    );
                }
            }
        }

        return $childrens;
    }

    function wp_get_nav_menu_items($menu_id) {

        if (!$data = wp_cache_get('quadmenu', 'wp_get_nav_menu_items')) {

            $data = wp_get_nav_menu_items($menu_id);

            wp_cache_add('quadmenu', $data, 'wp_get_nav_menu_items');
        }

        return $data;
    }

    public function nav_menu_item_settings($setting, $item) {

        $type = $setting['type'];

        //$desc = isset($setting['desc']) ? '<span class="quadmenu-menu-item-setting-description">' . $setting['desc'] . '</span>' : '';
        //$tip = isset($setting['tip']) ? '<div class="quadmenu-menu-item-setting-tip"><i class="tip-icon"></i> ' . $setting['tip'] . '</div>' : '';

        $value = $item->{$setting['db']};

        $id = 'menu-item-' . $setting['db'];

        $name = 'menu-item-' . $setting['id'];

        $class = 'menu-item-quadmenu-setting ' . $id;

        $ops = '';

        if (isset($setting['ops'])) {
            $ops = $setting['ops'];
            if (!is_array($ops) && function_exists($ops)) {
                if (isset($setting['ops_args'])) {
                    $ops = $ops($setting['ops_args']);
                } else
                    $ops = $ops();
            }
        }

        switch ($type) {

            case 'checkbox':
                ?>
                <label class="multicheck-label">
                    <input type="checkbox" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($ops ? $ops : 'on'); ?>" <?php echo checked($value, $ops ? $ops : 'on'); ?> />
                    <?php echo esc_html($setting['placeholder']); ?>
                </label>
                <?php
                break;

            case 'multicheck':
                ?>
                <span class="field-wrapper">
                    <?php
                    $value = is_array($value) ? $value : array();

                    foreach ($ops as $_val => $_name):
                        ?>
                        <label class="multicheck-label" title="<?php echo esc_html($_val); ?>" >
                            <input type="checkbox" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?> checkbox multicheckbox" name="<?php echo esc_attr($name . '[]'); ?>" value="<?php echo esc_html($_val); ?>" <?php echo checked(in_array($_val, $value)); ?>  />
                            <?php echo esc_html($_name); ?>
                        </label>
                    <?php endforeach; ?>
                </span>
                <?php
                break;

            case 'text':
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }
                ?>
                <input type="text" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>" placeholder="<?php echo esc_html($setting['placeholder']); ?>" value="<?php echo esc_html($value); ?>" />
                <?php
                break;

            case 'number':
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }
                ?>
                <input type="number" step="<?php echo esc_attr($ops['step']); ?>" min="<?php echo esc_attr($ops['min']); ?>" max="<?php echo esc_attr($ops['max']); ?>" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_html($value); ?>" />
                <?php
                break;

            case 'hidden':
                ?>
                <input type="hidden" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?> checkbox multicheckbox" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_html($value); ?>" />
                <?php
                break;

            case 'textarea':
                ?>
                <textarea id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>"><?php echo esc_html($value); ?></textarea>
                <?php
                break;

            case 'select':
                ?>
                <select id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>" >
                    <?php foreach ($ops as $_val => $_name): ?>
                        <option value="<?php echo esc_html($_val); ?>" <?php selected($value, $_val); ?> ><?php echo $_name; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php
                break;

            case 'radio':
                ?>
                <span class="field-wrapper">
                    <?php foreach ($ops as $_val => $_name): ?>
                        <label class="radio-label" title="<?php echo esc_html($_val); ?>" >
                            <input id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" type="radio" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_html($_val); ?>" <?php echo checked($value, $_val); ?>  />
                            <?php echo esc_html($_name); ?>
                        </label>
                    <?php endforeach; ?>
                </span>
                <?php
                break;

            case 'width':

                extract($ops);
                ?>
                <div class="row">
                    <div class="col-auto quadmenu-setting-width-icons">
                        <div>
                            <span class="title">#</span>
                        </div>
                        <?php
                        if (!empty($icons)):
                            foreach ($icons as $size) :
                                ?>
                                <div class="<?php echo esc_attr($size); ?>"><i></i></div>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                    <?php if (!empty($columns)): ?>
                        <div class="col-7 quadmenu-setting-width-columns">
                            <div class="col">
                                <span class="title"><?php esc_html_e('Width', 'quadmenu'); ?></span>
                            </div>
                            <?php
                            foreach ($columns as $size) :

                                if ($size != '') {
                                    $size = '-' . $size;
                                }
                                ?>
                                <div class="col<?php echo esc_attr($size) ?>">
                                    <select id="menu-item-width<?php echo esc_attr($size); ?>-columns" class="menu-item-quadmenu-setting menu-item-columns<?php echo esc_attr($size); ?>" name="menu-item-quadmenu-settings[columns][]">
                                        <?php //if ($size != ''):        ?>
                                        <option value="">
                                            <?php esc_html_e('Inherit from smaller', 'quadmenu'); ?>
                                        </option>
                                        <?php //endif;       ?>
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) :
                                            $current = 'col' . $size . '-' . $i;
                                            ?>
                                            <option value="<?php echo esc_attr($current); ?>" <?php selected(true, in_array(sanitize_key($current), str_replace('col-xs-', 'col-', $value))); ?>>
                                                <?php printf(esc_html('%1$s column', 'quadmenu'), $i); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($offset)): ?>
                        <div class="col-auto quadmenu-setting-width-offset">
                            <div>
                                <span class="title"><?php esc_html_e('Offset', 'quadmenu'); ?></span>
                            </div>
                            <?php foreach ($offset as $size) : ?>
                                <div class="<?php echo esc_attr($size) ?>">
                                    <select id="menu-item-width-<?php echo esc_attr($size); ?>-offset" class="menu-item-quadmenu-setting menu-item-offset-<?php echo esc_attr($size); ?>" name="menu-item-quadmenu-settings[columns][]">
                                        <option value="">
                                            <?php esc_html_e('Inherit from smaller', 'quadmenu'); ?>
                                        </option>
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) :
                                            $current = 'offset-' . $size . '-' . $i;
                                            ?>
                                            <option value="<?php echo esc_attr($current); ?>" <?php selected(true, in_array(sanitize_key($current), $value)); ?>>
                                                <?php printf(esc_html('%1$s column', 'quadmenu'), $i); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($hidden)): ?>
                        <div class="col-xs-3 quadmenu-setting-width-hidden">
                            <div>
                                <span class="title"><?php esc_html_e('Hidden', 'quadmenu'); ?></span>
                            </div>
                            <?php
                            foreach ($hidden as $size) :
                                $current = 'hidden-' . $size;
                                ?>
                                <div class="<?php echo esc_attr($size) ?>">
                                    <input type="checkbox" id="menu-item-width-<?php echo esc_attr($size); ?>-hidden" class="menu-item-quadmenu-setting menu-item-hidden-<?php echo esc_attr($size); ?> checkbox multicheckbox" name="menu-item-quadmenu-settings[columns][]" value="<?php echo esc_attr($current); ?>" <?php checked(true, in_array(sanitize_key($current), $value)); ?>>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                break;

            case 'icon':
                ?>
                <div class="quadmenu-setting">
                    <label>
                        <span class="title"><?php esc_html_e('Icon', 'quadmenu'); ?></span>
                        <input type="text" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_html($value); ?>"/>
                    </label>
                </div>
                <div class="quadmenu-setting">
                    <div class="quadmenu-icons-search">
                        <span class="button-icon button-secondary" class="button"><i class="<?php echo esc_html($value); ?>"></i><?php echo _QuadMenu()->selected_icons()->name; ?></span>
                        <input type="search" value="" placeholder="<?php echo esc_html($setting['placeholder']); ?>"/>                        
                    </div>
                    <div class="quadmenu-icons-scroll">
                        <?php foreach (explode(',', _QuadMenu()->selected_icons()->iconmap) as $icon) : ?>
                            <a style="display: block;" class="icon _<?php echo esc_attr(str_replace(' ', '_', trim($icon))); ?>"><i class="<?php echo esc_attr($icon); ?>"></i></a>
                        <?php endforeach; ?>
                        <div class="clearfix"></div>
                    </div> 
                </div>
                <?php
                break;

            case 'background':

                $defaults = array(
                    'thumbnail-id' => 0,
                    'size' => '',
                    'position' => '',
                    'repeat' => '',
                    'origin' => 'border-box',
                    'opacity' => 1,
                );

                $value = wp_parse_args($value, $defaults);

                $_src = !empty($value['thumbnail-id']) ? wp_get_attachment_image_src($value['thumbnail-id'], 'thumbnail') : false;
                $_url = !empty($value['thumbnail-id']) ? wp_get_attachment_image_src($value['thumbnail-id'], 'full') : false;

                $have_media = ( strlen($_src[0]) > 0 ? 'active' : '' );
                ?>
                <span class="field-background" 
                      style="
                      background-image: url(<?php echo esc_attr($_url[0]); ?>);
                      background-size: <?php echo esc_attr($value['size']); ?>;
                      background-repeat: <?php echo esc_attr($value['repeat']); ?>;
                      background-position: <?php echo esc_attr($value['position']); ?>;
                      background-origin: <?php echo esc_attr($value['origin']); ?>;
                      opacity: <?php echo esc_attr($value['opacity']); ?>;
                      "></span>
                <span class="field-wrapper <?php echo esc_attr($have_media); ?>">
                    <span class="edit-menu-item-media-thumbnail">
                        <?php if ($have_media): ?>
                            <img src="<?php echo esc_attr($_src[0]); ?>" width="50" height="50" id="mega-media-thumbnail-menu-item-<?php echo esc_attr($item->ID); ?>" title="menu-item-<?php echo esc_attr($item->ID); ?>-media" alt="menu-item-<?php echo esc_attr($item->ID); ?>-media">
                        <?php endif ?>
                    </span>
                    <input type="hidden" id="edit-menu-item-media-thumbnail-id-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-media-thumbnail-id" name="<?php echo esc_attr($name . '[thumbnail-id]'); ?>" value="<?php echo esc_attr($value['thumbnail-id']); ?>" />
                    <span class="edit-menu-item-media-text"><?php esc_html_e('Select media image', 'quadmenu'); ?></span>
                    <a id="<?php echo esc_attr($item->ID); ?>" class="menu-item-media-upload" href="javascript:void(0)"><?php esc_html_e('Edit', 'quadmenu'); ?></a>
                    <a id="<?php echo esc_attr($item->ID); ?>" class="menu-item-media-clear" href="javascript:void(0)" ><?php esc_html_e('Remove', 'quadmenu'); ?></a>
                    <div class="clearfix"></div>                 
                    <label class="setting">
                        <b><?php esc_html_e('Opacity', 'quadmenu'); ?></b>
                        <input type="number" step="1" min="1" max="100" id="edit-menu-item-background-opacity-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-background-opacity" name="<?php echo esc_attr($name . '[opacity]'); ?>" value="<?php echo esc_attr($value['opacity']); ?>"/>               
                    </label>
                    <label class="setting">
                        <b><?php esc_html_e('Position', 'quadmenu'); ?></b>
                        <select id="edit-menu-item-background-position-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-background-position" name="<?php echo esc_attr($name . '[position]'); ?>">
                            <option value="left top" <?php selected('left top', $value['position']); ?>>left top</option>
                            <option value="left center" <?php selected('left center', $value['position']); ?>>left center</option>
                            <option value="left bottom" <?php selected('left bottom', $value['position']); ?>>left bottom</option>
                            <option value="right top" <?php selected('right top', $value['position']); ?>>right top</option>
                            <option value="right center" <?php selected('right center', $value['position']); ?>>right center</option>
                            <option value="right bottom" <?php selected('right bottom', $value['position']); ?>>right bottom</option>
                            <option value="center top" <?php selected('center top', $value['position']); ?>>center top</option>
                            <option value="center center" <?php selected('center center', $value['position']); ?>>center center</option>
                            <option value="center bottom" <?php selected('center bottom', $value['position']); ?>>center bottom</option>
                        </select>
                    </label>
                    <label class="setting">
                        <b><?php esc_html_e('Repeat', 'quadmenu'); ?></b>
                        <select id="edit-menu-item-background-repeat-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-background-repeat" name="<?php echo esc_attr($name . '[repeat]'); ?>">
                            <option value="repeat" <?php selected('repeat', $value['repeat']); ?>>repeat</option>
                            <option value="no-repeat" <?php selected('no-repeat', $value['repeat']); ?>>no-repeat</option>
                        </select>
                    </label>
                    <label class="setting">
                        <b><?php esc_html_e('Size', 'quadmenu'); ?></b>
                        <select id="edit-menu-item-background-size-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-background-size" name="<?php echo esc_attr($name . '[size]'); ?>" value="<?php echo esc_attr($value['size']); ?>">               
                            <option value="auto" <?php selected('auto', $value['size']); ?>>auto</option>
                            <option value="cover" <?php selected('cover', $value['size']); ?>>cover</option>
                            <option value="contain" <?php selected('contain', $value['size']); ?>>contain</option>
                        </select>
                    </label>           
                    <label class="setting">
                        <b><?php esc_html_e('Origin', 'quadmenu'); ?></b>
                        <select id="edit-menu-item-background-origin-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-background-origin" name="<?php echo esc_attr($name . '[origin]'); ?>" value="<?php echo esc_attr($value['origin']); ?>">               
                            <option value="content-box" <?php selected('content-box', $value['origin']); ?>>Boxed</option>
                            <option value="border-box" <?php selected('border-box', $value['origin']); ?>>Stretch</option>
                        </select>
                    </label>
                    <div class="clearfix"></div>  
                </span>
                <?php
                break;

            case 'media':

                $_src = !empty($value['thumbnail-id']) ? wp_get_attachment_image_src($value['thumbnail-id'], 'thumbnail') : false;

                $have_media = ( strlen($_src[0]) > 0 ? 'active' : '' );
                ?>
                <span class="field-wrapper <?php echo esc_attr($have_media); ?>">
                    <span class="edit-menu-item-media-thumbnail">
                        <?php if ($have_media): ?>
                            <img src="<?php echo esc_attr($_src[0]); ?>" width="50" height="50" id="mega-media-thumbnail-menu-item-<?php echo esc_attr($item->ID); ?>" title="menu-item-<?php echo esc_attr($item->ID); ?>-media" alt="menu-item-<?php echo esc_attr($item->ID); ?>-media">
                        <?php endif ?>
                    </span>
                    <span class="edit-menu-item-media-text"><?php esc_html_e('Select media image', 'quadmenu'); ?></span>
                    <a id="<?php echo esc_attr($item->ID); ?>" class="menu-item-media-upload" href="javascript:void(0)"><?php esc_html_e('Edit', 'quadmenu'); ?></a>
                    <a id="<?php echo esc_attr($item->ID); ?>" class="menu-item-media-clear" href="javascript:void(0)" ><?php esc_html_e('Remove', 'quadmenu'); ?></a>
                    <span class="edit-menu-item-media-css"><?php
                        if ($have_media): echo $value['position'] . ',' . $value['repeat'] . ',' . $value['attachment'] . ',' . $value['size'];
                        endif
                        ?>
                    </span>                        
                    <input type="hidden" id="edit-menu-item-media-thumbnail-id-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-media-thumbnail-id" name="<?php echo esc_attr($name . '[thumbnail-id]'); ?>" value="<?php echo esc_attr($value['thumbnail-id']); ?>" />
                    <input type="hidden" id="edit-menu-item-media-position-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-media-position" name="<?php echo esc_attr($name . '[position]'); ?>" value="<?php echo esc_attr($value['position']); ?>" />
                    <input type="hidden" id="edit-menu-item-media-repeat-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-media-repeat" name="<?php echo esc_attr($name . '[repeat]'); ?>" value="<?php echo esc_attr($value['repeat']); ?>" />
                    <input type="hidden" id="edit-menu-item-media-attachment-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-media-attachment" name="<?php echo esc_attr($name . '[attachment]'); ?>" value="<?php echo esc_attr($value['attachment']); ?>" />
                    <input type="hidden" id="edit-menu-item-media-size-<?php echo esc_attr($item->ID); ?>" class="<?php echo esc_attr($class); ?> edit-menu-item-media-size" name="<?php echo esc_attr($name . '[size]'); ?>" value="<?php echo esc_attr($value['size']); ?>" />         </span>
                <?php
                break;

            default:
                printf(esc_html__('Unknown field %s.', 'quadmenu'), $type);
        }

        //echo $desc;
        //echo $tip;
    }

    public function form($menu_obj, $menu_item_depth = 0, $settings = array()) {

        $fields = $this->nav_menu_item_fields($menu_obj);
        ob_start();
        ?>
        <form id="form_<?php echo esc_attr($menu_obj->quadmenu); ?>_<?php echo esc_attr($menu_obj->ID); ?>" class="form-<?php echo esc_attr($menu_obj->quadmenu); ?>" data-menu_item_id="<?php echo esc_attr($menu_obj->ID); ?>" data-menu_item_depth="<?php echo esc_attr($menu_item_depth); ?>">
            <?php
            foreach ($settings as $key):

                $classes = array();

                $classes[] = 'quadmenu-setting';

                $classes[] = 'quadmenu-setting-' . $fields[$key]['type'];

                $classes[] = 'quadmenu-setting-' . $fields[$key]['db'];

                if (isset($fields[$key]['depth'])) {

                    if (is_array($fields[$key]['depth'])) {

                        foreach ($fields[$key]['depth'] as $depth) {

                            $classes[] = 'quadmenu-setting-depth-' . $depth;
                        }
                    } else {

                        $classes[] = 'quadmenu-setting-depth-' . $fields[$key]['depth'];
                    }
                }
                ?>
                <div class="<?php echo join(' ', array_map('sanitize_html_class', $classes)); ?>">
                    <label>
                        <?php if (!empty($fields[$key]['title'])) : ?>
                            <span class="title"><?php echo esc_html($fields[$key]['title']); ?></span>
                        <?php endif; ?>
                        <?php $this->nav_menu_item_settings($fields[$key], $menu_obj); ?>
                        <?php if (!empty($fields[$key]['desc'])) : ?>
                            <span class="description"><?php echo esc_html($fields[$key]['desc']); ?></span>
                        <?php endif; ?>
                    </label>
                </div>
            <?php endforeach;
            ?>
        </form>
        <?php
        return ob_get_clean();
    }

    public function ajax_add_nav_menu_item_panel() {

        if (!check_ajax_referer('quadmenu', 'nonce', false)) {
            QuadMenu::send_json_error(esc_html__('Please reload page.', 'quadmenu'));
        }

        $panel = sanitize_text_field($_GET['menu_item_panel']);

        $menu_item_id = absint($_GET['menu_item_id']);

        $menu_id = absint($_GET['menu_id']);

        if (ob_get_contents())
            ob_clean();

        $menu_obj = QuadMenu::wp_setup_nav_menu_item($menu_item_id);

        $items = QuadMenu_Configuration::custom_nav_menu_items();

        if (!$settings = $items->{$menu_obj->quadmenu}->panels->$panel->settings) {
            QuadMenu::send_json_error(json_encode($menu_obj));
        }

        if ($form = $this->form($menu_obj, 0, $settings)) {
            QuadMenu::send_json_success($form);
        } else {
            QuadMenu::send_json_error(json_encode($menu_obj));
        }

        wp_die();
    }

    public function panels($menu_item_depth, $menu_obj, $menu_id) {

        $items = QuadMenu_Configuration::custom_nav_menu_items();

        $this->panels = $items->{$menu_obj->quadmenu}->panels;
        ?>
        <ul role="tablist" id="settings_<?php echo esc_attr($menu_obj->ID); ?>" class="quadmenu-tabs vertical">
            <li class="active"><a href="#setting_default_<?php echo esc_attr($menu_obj->ID); ?>" data-quadmenu="tab"><i class="dashicons dashicons-menu"></i><span class="title"><?php echo esc_html('Default', 'quadmenu'); ?></span></a></li>

            <?php
            foreach ($this->panels as $key => $panel) :

                $classes = array();

                if (isset($panel->depth)) {
                    foreach ($panel->depth as $depth) {

                        $classes[] = 'quadmenu-panel-depth-' . $depth;
                    }
                }

                $classes[] = 'quadmenu-panel-' . $key;
                ?>
                <li class="<?php echo join(' ', array_map('sanitize_html_class', $classes)); ?>" data-menu_item_id="<?php echo esc_attr($menu_obj->ID); ?>" data-menu_item_panel="<?php echo esc_attr($key); ?>"><a class="<?php echo esc_attr($key); ?>" href="#setting_<?php echo esc_attr($key); ?>_<?php echo esc_attr($menu_obj->ID); ?>" data-quadmenu="tab"><i class="<?php echo esc_attr($panel->icon); ?>"></i><span class="title"><?php echo esc_html($panel->title); ?></span></a></li>
                <?php $this->panels->{$key} = $panel->settings; ?>
            <?php endforeach; ?>

            <?php do_action('quadmenu_modal_panels_tab', $menu_item_depth, $menu_obj, $menu_id); ?>
        </ul>
        <div class="quadmenu-tabs-content <?php echo join(' ', array_map('sanitize_html_class', $classes)); ?>">
            <div role="tabpanel" class="quadmenu-tab-pane quadmenu-tab-pane-default active" id="setting_default_<?php echo esc_attr($menu_obj->ID); ?>">
                <?php echo $this->form($menu_obj, 0, array('url', 'target', 'title', 'attr-title', 'classes', 'xfn', 'description')); ?>
            </div>
            <?php foreach ($this->panels as $key => $settings) : ?>
                <div role="tabpanel" class="quadmenu-tab-pane quadmenu-tab-pane-<?php echo esc_attr($key); ?> fade" id="setting_<?php echo esc_attr($key); ?>_<?php echo esc_attr($menu_obj->ID); ?>">
                    <span class="spinner"></span>
                </div>
            <?php endforeach; ?>
            <?php do_action('quadmenu_modal_panels_pane', $menu_item_depth, $menu_obj, $menu_id); ?>
        </div>
        <?php
    }

}

new QuadMenu_Settings();
