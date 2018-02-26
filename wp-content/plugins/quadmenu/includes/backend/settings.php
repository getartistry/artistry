<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Settings extends QuadMenu_Admin {

    public $tabs;
    public $panels = array();

    public function __construct() {

        add_filter('quadmenu_setup_nav_menu_item', array($this, 'default_values_nav_menu_items'));

        add_filter('quadmenu_custom_nav_menu_items', array($this, 'default_nav_menu_items'));

        add_action('quadmenu_modal_panels', array($this, 'panels'), 10, 4);

        add_action('wp_ajax_quadmenu_add_nav_menu_item_panel', array($this, 'ajax_add_nav_menu_item_panel'));
    }

    static function custom_nav_menu_items() {

        $items = array(
            'mega' => array(
                'label' => esc_html__('QuadMenu Mega', 'quadmenu'),
                'title' => esc_html__('Mega', 'quadmenu'),
                'panels' => array(
                    'general' => array(
                        'title' => esc_html__('General', 'quadmenu'),
                        'icon' => 'dashicons dashicons-admin-settings',
                        'settings' => array('subtitle', 'badge', 'float', 'dropdown', 'hidden'),
                    ),
                    'icon' => array(
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'icon' => 'dashicons dashicons-art',
                        'settings' => array('icon'),
                    ),
                    'width' => array(
                        'title' => esc_html__('Width', 'quadmenu'),
                        'icon' => 'dashicons dashicons-align-left',
                        'settings' => array('width'),
                    ),
                    'background' => array(
                        'title' => esc_html__('Background', 'quadmenu'),
                        'icon' => 'dashicons dashicons-format-image',
                        'settings' => array('background'),
                    ),
                ),
                'desc' => esc_html__('A menu which can wrap any type of widget.', 'quadmenu'),
                'parent' => 'main',
                'depth' => 0,
            ),
            'icon' => array(
                'label' => esc_html__('QuadMenu Icon', 'quadmenu'),
                'title' => esc_html__('Icon', 'quadmenu'),
                'panels' => array(
                    'icon' => array(
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'icon' => 'dashicons dashicons-art',
                        'settings' => array('icon'),
                    ),
                    'general' => array(
                        'title' => esc_html__('General', 'quadmenu'),
                        'icon' => 'dashicons dashicons-admin-settings',
                        'settings' => array('float', 'dropdown', 'hidden'),
                    ),
                ),
                'desc' => esc_html__('Just an icon, no title.', 'quadmenu'),
                'depth' => 0,
            ),
            'cart' => array(
                'label' => esc_html__('QuadMenu Cart', 'quadmenu'),
                'title' => esc_html__('Cart', 'quadmenu'),
                'panels' => array(
                    'general' => array(
                        'title' => esc_html__('General', 'quadmenu'),
                        'icon' => 'dashicons dashicons-admin-settings',
                        'settings' => array('float', 'dropdown', 'hidden', 'cart'),
                    ),
                    'icon' => array(
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'icon' => 'dashicons dashicons-art',
                        'settings' => array('icon'),
                    ),
                ),
                'desc' => esc_html__('A cart widget for Woocommerce.', 'quadmenu'),
                'parent' => 'main',
                'depth' => 0,
            ),
            'search' => array(
                'label' => esc_html__('QuadMenu Search', 'quadmenu'),
                'title' => esc_html__('Search', 'quadmenu'),
                'panels' => array(
                    'general' => array(
                        'title' => esc_html__('General', 'quadmenu'),
                        'icon' => 'dashicons dashicons-admin-settings',
                        'settings' => array('float', 'hidden'),
                    ),
                    'icon' => array(
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'icon' => 'dashicons dashicons-art',
                        'settings' => array('icon'),
                    ),
                ),
                'desc' => esc_html__('A search form for the site.', 'quadmenu'),
                'depth' => 0,
            ),
            'column' => array(
                'label' => esc_html__('Column', 'quadmenu'),
                'title' => esc_html__('Column', 'quadmenu'),
                'settings' => array('columns'),
                'desc' => esc_html__('Column to organize the content.', 'quadmenu'),
                'depth' => 1,
                'parent' => array('panel', 'tab', 'mega'),
            ),
            'widget' => array(
                'label' => esc_html__('QuadMenu Widget', 'quadmenu'),
                'title' => esc_html__('Widget', 'quadmenu'),
                'panels' => array(
                    'general' => array(
                        'title' => esc_html__('General', 'quadmenu'),
                        'icon' => 'dashicons dashicons-align-left',
                        'settings' => array('hidden'),
                    ),
                ),
                'desc' => esc_html__('Include a widget inside column.', 'quadmenu'),
                'parent' => 'column',
            ),
        );

        return apply_filters('quadmenu_custom_nav_menu_items', $items);
    }

    static function default_nav_menu_items($items) {

        $items['custom'] = array(
            'panels' => array(
                /* 'default' => array(
                  'title' => esc_html__('Default', 'quadmenu'),
                  'icon' => 'dashicons dashicons-menu',
                  'settings' => array('url', 'title', 'attr-title', 'classes', 'xfn', 'description'),
                  ), */
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'dropdown', 'hidden'),
                ),
                'icon' => array(
                    'title' => esc_html__('Icon', 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
        );
        $items['taxonomy'] = array(
            'panels' => array(
                /* 'default' => array(
                  'title' => esc_html__('Default', 'quadmenu'),
                  'icon' => 'dashicons dashicons-menu',
                  'settings' => array('url', 'title', 'attr-title', 'classes', 'xfn', 'description'),
                  ), */
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'dropdown', 'hidden'),
                ),
                'icon' => array(
                    'title' => esc_html__('Icon', 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
        );
        $items['post_type'] = array(
            'panels' => array(
                /* 'default' => array(
                  'title' => esc_html__('Default', 'quadmenu'),
                  'icon' => 'dashicons dashicons-menu',
                  'settings' => array('url', 'title', 'attr-title', 'classes', 'xfn', 'description'),
                  ), */
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'dropdown', 'thumb', 'hidden'),
                ),
                'icon' => array(
                    'title' => esc_html__('Icon', 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
        );

        $items['post_type_archive'] = array(
            'panels' => array(
                /* 'default' => array(
                  'title' => esc_html__('Default', 'quadmenu'),
                  'icon' => 'dashicons dashicons-menu',
                  'settings' => array('url', 'title', 'attr-title', 'classes', 'xfn', 'description'),
                  ), */
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'dropdown', 'hidden'),
                ),
                'icon' => array(
                    'title' => esc_html__(esc_html__('Icon', 'quadmenu'), 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
        );

        return $items;
    }

    function default_values_nav_menu_items($item) {

        $defaults = $this->nav_menu_item_fields_defaults();

        foreach ($defaults as $key => $value) {

            if (empty($item->{$key}) && !empty($item->quadmenu)) {

                $item->{$key} = apply_filters('quadmenu_nav_menu_item_field_default', $value, $key, $item);
            }
        }

        return $item;
    }

    function nav_menu_item_fields_defaults() {

        $defaults = array();

        $fields = $this->nav_menu_item_fields();

        foreach ($fields as $id => $field) {

            //if (isset($field['db'])) {
            $defaults[$id] = isset($field['default']) ? $field['default'] : esc_html__('Undefined default', 'quadmenu');
            //}
        }

        $defaults = apply_filters('quadmenu_nav_menu_item_fields_defaults', $defaults);

        return $defaults;
    }

    public function nav_menu_item_fields() {

        $settings = array();

        $settings['id'] = array(
            'id' => 'id',
            'db' => 'id',
            'type' => 'id',
        );

        $settings['url'] = array(
            'id' => 'url',
            'db' => 'url',
            'title' => esc_html__('URL'),
            'placeholder' => esc_html__('URL'),
            'type' => 'text',
            'default' => '',
        );

        $settings['title'] = array(
            'id' => 'title',
            'db' => 'title',
            'title' => esc_html__('Navigation Label'),
            'placeholder' => esc_html__('Navigation Label'),
            'type' => 'text',
            'default' => '',
        );

        $settings['attr-title'] = array(
            'id' => 'attr-title',
            'db' => 'post_excerpt',
            'title' => esc_html__('Title Attribute'),
            'placeholder' => esc_html__('Title Attribute'),
            'type' => 'text',
            'default' => '',
        );

        $settings['classes'] = array(
            'id' => 'classes',
            'db' => 'classes',
            'title' => esc_html__('CSS Classes (optional)'),
            'placeholder' => esc_html__('CSS Classes (optional)'),
            'type' => 'text',
            'default' => '',
        );

        $settings['target'] = array(
            'id' => 'target',
            'db' => 'target',
            'target' => 'target',
            'title' => esc_html__('Open link in a new tab'),
            'type' => 'checkbox',
            'default' => '',
        );

        $settings['xfn'] = array(
            'id' => 'xfn',
            'db' => 'xfn',
            'title' => esc_html__('Link Relationship (XFN)'),
            'placeholder' => esc_html__('Link Relationship (XFN)'),
            'type' => 'text',
            'default' => '',
        );

        $settings['description'] = array(
            'id' => 'description',
            'db' => 'description',
            'desc' => esc_html__('The description will be displayed in the menu if the current theme supports it.'),
            'type' => 'textarea',
            'default' => '',
        );

        $settings['icon'] = array(
            'id' => 'quadmenu-settings[icon]',
            'db' => 'icon',
            'type' => 'icon',
            'placeholder' => esc_html__('Search', 'quadmenu'),
            'default' => '',
        );

        $settings['subtitle'] = array(
            'id' => 'quadmenu-settings[subtitle]',
            'db' => 'subtitle',
            'title' => esc_html__('Subtitle', 'quadmenu'),
            'placeholder' => esc_html__('Enter item subtitle', 'quadmenu'),
            'type' => 'text',
            'default' => '',
        );

        $settings['badge'] = array(
            'id' => 'quadmenu-settings[badge]',
            'db' => 'badge',
            'title' => esc_html__('Badge', 'quadmenu'),
            'placeholder' => esc_html__('Item badge title', 'quadmenu'),
            'type' => 'text',
            'default' => '',
        );

        $settings['float'] = array(
            'id' => 'quadmenu-settings[float]',
            'db' => 'float',
            'title' => esc_html__('Float', 'quadmenu'),
            'placeholder' => esc_html__('Float item to left or right', 'quadmenu'),
            'type' => 'select',
            'default' => '',
            'ops' => array(
                '' => esc_html__('Default item position', 'quadmenu'),
                'opposite' => esc_html__('Float item opposite to default', 'quadmenu')
            )
        );

        $settings['dropdown'] = array(
            'id' => 'quadmenu-settings[dropdown]',
            'db' => 'dropdown',
            'title' => esc_html__('Dropdown Float', 'quadmenu'),
            'placeholder' => esc_html__('Float dropdown to left o right', 'quadmenu'),
            'type' => 'select',
            'default' => 'dropdown-left',
            'ops' => array(
                'right' => esc_html__('Float dropdown right', 'quadmenu'),
                'left' => esc_html__('Float dropdown left', 'quadmenu')
            )
        );

        $settings['hidden'] = array(
            'id' => 'quadmenu-settings[hidden]',
            'db' => 'hidden',
            'title' => esc_html__('Hide on screen sizes', 'quadmenu'),
            'type' => 'multicheck',
            'default' => '',
            'ops' => array(
                'hidden-xs' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'XS'),
                'hidden-sm' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'SM'),
                'hidden-md' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'MD'),
                'hidden-lg' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'LG'),
            )
        );

        $settings['thumb'] = array(
            'id' => 'quadmenu-settings[thumb]',
            'db' => 'thumb',
            'title' => esc_html__('Show featured image', 'quadmenu'),
            'type' => 'select',
            'default' => '',
            'ops' => array(
                '' => esc_html__('Hide featured image', 'quadmenu'),
                'thumbnail' => esc_html__('Show featured image in thumbnail size', 'quadmenu'),
                'large' => esc_html__('Show featured image in wide size', 'quadmenu'),
            ),
        );

        $settings['background'] = array(
            'id' => 'quadmenu-settings[background]',
            'db' => 'background',
            'type' => 'background',
            'default' => '',
        );

        $settings['width'] = array(
            'id' => 'quadmenu-settings[columns]',
            'db' => 'columns',
            'type' => 'width',
            'default' => array(),
            'ops' => array(
                'columns' => true,
            ),
        );

        $settings['columns'] = array(
            'id' => 'quadmenu-settings[columns]',
            'db' => 'columns',
            'type' => 'width',
            'default' => array(),
            'ops' => array(
                'columns' => true,
                'hidden' => true,
            ),
        );

        $settings['cart'] = array(
            'id' => 'quadmenu-settings[cart]',
            'db' => 'cart',
            'title' => esc_html__('Cart', 'quadmenu'),
            'type' => 'select',
            'default' => 'woo',
            'ops' => array(
                'woo' => esc_html__('Woocommerce Cart', 'quadmenu'),
                'edd' => esc_html__('Easy Digital Downloads Cart', 'quadmenu'),
            )
        );

        $settings['social'] = array(
            'id' => 'quadmenu-settings[social]',
            'db' => 'social',
            'title' => esc_html__('Social', 'quadmenu'),
            'type' => 'select',
            'default' => 'toggle',
            'ops' => array(
                'embed' => esc_html__('Embeded', 'quadmenu'),
                'toggle' => esc_html__('Toggle', 'quadmenu'),
            )
        );

        return apply_filters('quadmenu_nav_menu_item_fields', $settings);
    }

    public function nav_menu_item_settings($setting, $item) {

        $type = $setting['type'];

        //$desc = isset($setting['desc']) ? '<span class="quadmenu-menu-item-setting-description">' . $setting['desc'] . '</span>' : '';
        //$tip = isset($setting['tip']) ? '<div class="quadmenu-menu-item-setting-tip"><i class="tip-icon"></i> ' . $setting['tip'] . '</div>' : '';

        $value = $item->{$setting['db']};

        $id = 'menu-item-' . $setting['db'];

        $name = 'menu-item-' . $setting['id'];

        $class = 'menu-item-quadmenu-setting ' . $id;

        $ops;

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
                <label class="multicheck-label" title="<?php echo esc_html($_val); ?>" >
                    <input type="checkbox" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>" <?php echo checked($value, 'on'); ?> />
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
                <input type="number" step="<?php echo esc_attr($ops['step']); ?>" min="<?php echo esc_attr($ops['min']); ?>" max="<?php echo esc_attr($ops['max']); ?>" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" name="<?php echo esc_attr($name); ?>" placeholder="<?php echo esc_html($setting['placeholder']); ?>" value="<?php echo esc_html($value); ?>" />
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

                $sizes = array(
                    '',
                    'sm',
                    'md',
                    'lg');
                ?>
                <div class="row">
                    <div class="col-xs-3 quadmenu-setting-width-icons">
                        <div>
                            <span class="title">#</span>
                        </div>
                        <div class="xs"><i></i></div>
                        <div class="sm"><i></i></div>
                        <div class="md"><i></i></div>
                        <div class="lg"><i></i></div>
                    </div>
                    <?php if (!empty($ops['columns'])): ?>
                        <div class="col-xs-3 quadmenu-setting-width-columns">
                            <div>
                                <span class="title"><?php esc_html_e('Width', 'quadmenu'); ?></span>
                            </div>
                            <?php
                            foreach ($sizes as $size) :

                                if ($size != '') {
                                    $size = '-' . $size;
                                }
                                ?>
                                <div class="col<?php echo esc_attr($size) ?>">
                                    <select id="menu-item-width<?php echo esc_attr($size); ?>-columns" class="menu-item-quadmenu-setting menu-item-columns<?php echo esc_attr($size); ?>" name="menu-item-quadmenu-settings[columns][]">
                                        <?php //if ($size != ''): ?>
                                            <option value="">
                                                <?php esc_html_e('Inherit from smaller', 'quadmenu'); ?>
                                            </option>
                                        <?php //endif; ?>
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
                    <?php if (!empty($ops['offset'])): ?>
                        <div class="col-xs-3 quadmenu-setting-width-offset">
                            <div>
                                <span class="title"><?php esc_html_e('Offset', 'quadmenu'); ?></span>
                            </div>
                            <?php foreach ($sizes as $size) : ?>
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
                    <?php if (!empty($ops['hidden'])): ?>
                        <div class="col-xs-3 quadmenu-setting-width-hidden">
                            <div>
                                <span class="title"><?php esc_html_e('Hidden', 'quadmenu'); ?></span>
                            </div>
                            <?php
                            foreach ($sizes as $size) :
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

        $fields = $this->nav_menu_item_fields();
        ob_start();
        ?>
        <form id="form_<?php echo esc_attr($menu_obj->quadmenu); ?>_<?php echo esc_attr($menu_obj->ID); ?>" class="form-<?php echo esc_attr($menu_obj->quadmenu); ?>" data-menu_item_id="<?php echo esc_attr($menu_obj->ID); ?>" data-menu_item_depth="<?php echo esc_attr($menu_item_depth); ?>">
            <?php
            foreach ($settings as $key):

                $classes = array();

                $classes[] = 'quadmenu-setting';

                $classes[] = 'quadmenu-setting-' . $fields[$key]['type'];

                if (isset($fields[$key]['depth'])) {
                    $classes[] = 'quadmenu-setting-depth-' . $fields[$key]['depth'];
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

        check_ajax_referer('quadmenu', 'nonce');

        $panel = sanitize_text_field($_POST['menu_item_panel']);

        $menu_item_id = absint($_POST['menu_item_id']);

        $menu_id = absint($_POST['menu_id']);

        if (ob_get_contents())
            ob_clean();

        $menu_obj = get_post($menu_item_id);

        if (!empty($menu_obj->ID)) {
            $menu_obj = wp_setup_nav_menu_item($menu_obj);
        }

        $items = $this->custom_nav_menu_items();

        if (!$settings = $items[$menu_obj->quadmenu]['panels'][$panel]['settings']) {
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

        $items = $this->custom_nav_menu_items();

        $this->panels = $items[$menu_obj->quadmenu]['panels'];
        ?>
        <ul role="tablist" id="settings_<?php echo esc_attr($menu_obj->ID); ?>" class="quadmenu-tabs vertical">
            <li class="active"><a href="#setting_default_<?php echo esc_attr($menu_obj->ID); ?>" data-quadmenu="tab"><i class="dashicons dashicons-menu"></i><span class="title"><?php echo esc_html('Default', 'quadmenu'); ?></span></a></li>
            <?php foreach ($this->panels as $key => $panel) : ?>
                <li data-menu_item_id="<?php echo esc_attr($menu_obj->ID); ?>" data-menu_item_panel="<?php echo esc_attr($key); ?>"><a class="<?php echo esc_attr($key); ?>" href="#setting_<?php echo esc_attr($key); ?>_<?php echo esc_attr($menu_obj->ID); ?>" data-quadmenu="tab"><i class="<?php echo esc_attr($panel['icon']); ?>"></i><span class="title"><?php echo esc_html($panel['title']); ?></span></a></li>
                <?php $this->panels[$key] = $panel['settings']; ?>
            <?php endforeach; ?>
            <?php do_action('quadmenu_modal_panels_tab', $menu_item_depth, $menu_obj, $menu_id); ?>
        </ul>
        <div class="quadmenu-tabs-content">
            <div role="tabpanel" class="quadmenu-tab-pane quadmenu-tab-pane-default active" id="setting_default_<?php echo esc_attr($menu_obj->ID); ?>">
                <?php echo $this->form($menu_obj, 0, array('url', 'title', 'attr-title', 'classes', 'xfn', 'description')); ?>
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
