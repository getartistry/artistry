<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Configuration {

    public function __construct() {

        // Ajax
        // ---------------------------------------------------------------------

        add_filter('wp_setup_nav_menu_item', array($this, 'default_values_nav_menu_items'), -10);

        add_filter('quadmenu_nav_menu_item_field_default', array($this, 'custom_default_values_nav_menu_items'), 10, 3);

        add_filter('quadmenu_compiler_files', array($this, 'files'));

        add_filter('quadmenu_register_icons', array($this, 'icons'), 1);

        add_filter('quadmenu_default_options', array($this, 'configuration'), 1);

        add_filter('quadmenu_default_options', array($this, 'responsive'), 1);

        add_filter('quadmenu_default_options', array($this, 'css'), 1);

        add_filter('quadmenu_default_options_themes', array($this, 'themes_options'), 1);

        add_filter('quadmenu_default_options_locations', array($this, 'locations_options'), 1);
    }

    static function custom_nav_menu_items() {

        $items = array();

        // QuadMenu
        // ---------------------------------------------------------------------

        $items['mega'] = array(
            'label' => esc_html__('QuadMenu Mega', 'quadmenu'),
            'title' => esc_html__('Mega', 'quadmenu'),
            'panels' => array(
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'hidden'),
                ),
                'icon' => array(
                    'title' => esc_html__('Icon', 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
                'background' => array(
                    'title' => esc_html__('Background', 'quadmenu'),
                    'icon' => 'dashicons dashicons-format-image',
                    'settings' => array('background'),
                ),
                'width' => array(
                    'title' => esc_html__('Width', 'quadmenu'),
                    'icon' => 'dashicons dashicons-align-left',
                    'settings' => array('dropdown', 'stretch', 'width'),
                ),
            ),
            'desc' => esc_html__('A menu which can wrap any type of widget.', 'quadmenu'),
            'parent' => 'main',
            'depth' => 0,
        );

        $items['icon'] = array(
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
                    'settings' => array('float', 'hidden', 'dropdown'),
                ),
            ),
            'desc' => esc_html__('Just an icon, no title.', 'quadmenu'),
            'depth' => 0,
        );
        $items['cart'] = array(
            'label' => esc_html__('QuadMenu Cart', 'quadmenu'),
            'title' => esc_html__('Cart', 'quadmenu'),
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
                'cart' => array(
                    'title' => esc_html__('Cart', 'quadmenu'),
                    'icon' => 'dashicons dashicons-cart',
                    'settings' => array('dropdown', 'title', 'cart'),
                ),
            ),
            'desc' => esc_html__('A cart widget for Woocommerce.', 'quadmenu'),
            'parent' => 'main',
            'depth' => 0,
        );

        $items['search'] = array(
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
        );
        $items['column'] = array(
            'label' => esc_html__('Column', 'quadmenu'),
            'title' => esc_html__('Column', 'quadmenu'),
            'settings' => array('columns'),
            'desc' => esc_html__('Column to organize the content.', 'quadmenu'),
            'depth' => 1,
            'parent' => array('panel', 'tab', 'mega'),
        );
        $items['widget'] = array(
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
        );

        // WordPress
        // ---------------------------------------------------------------------

        $items['custom'] = array(
            'panels' => array(
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
                ),
                'icon' => array(
                    'title' => esc_html__('Icon', 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
            'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
        );
        $items['taxonomy'] = array(
            'panels' => array(
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
                ),
                'icon' => array(
                    'title' => esc_html__('Icon', 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
            'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
        );
        $items['post_type'] = array(
            'panels' => array(
                'general' => array(
                    'title' => esc_html__('General', 'quadmenu'),
                    'icon' => 'dashicons dashicons-admin-settings',
                    'settings' => array('subtitle', 'badge', 'float', 'thumb', 'hidden', 'dropdown'),
                ),
                'icon' => array(
                    'title' => esc_html__('Icon', 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
            'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
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
                    'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
                ),
                'icon' => array(
                    'title' => esc_html__(esc_html__('Icon', 'quadmenu'), 'quadmenu'),
                    'icon' => 'dashicons dashicons-art',
                    'settings' => array('icon'),
                ),
            ),
            'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
        );

        return json_decode(json_encode(apply_filters('quadmenu_custom_nav_menu_items', $items)));
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
            'default' => array(),
        );

        $settings['target'] = array(
            'id' => 'target',
            'db' => 'target',
            'target' => 'target',
            'title' => esc_html__('Target'),
            'placeholder' => esc_html__('Open link in a new tab'),
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
            'depth' => 0,
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
            'default' => 'left',
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
            'depth' => array(1, 2, 3, 4),
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
            'default' => array(
                'thumbnail-id' => 0,
                'size' => '',
                'position' => '',
                'repeat' => '',
                'origin' => 'border-box',
                'opacity' => 1,
            ),
        );

        $settings['stretch'] = array(
            'id' => 'quadmenu-settings[stretch]',
            'db' => 'stretch',
            'title' => esc_html__('Stretch Dropdown', 'quadmenu'),
            'desc' => esc_html__('This controls the width of the dropdown and contents.', 'quadmenu'),
            'type' => 'select',
            'default' => '',
            'ops' => array(
                'boxed' => esc_html__('Dropdown boxed', 'quadmenu'),
                'dropdown' => esc_html__('Stretch dropdown', 'quadmenu'),
                //'content' => esc_html__('Stretch dropdown and content', 'quadmenu'),
                '' => esc_html__('Custom dropdown width', 'quadmenu'),
            ),
        );

        $settings['width'] = array(
            'id' => 'quadmenu-settings[columns]',
            'db' => 'columns',
            'type' => 'width',
            'default' => array(),
            'ops' => array(
                'icons' => array(
                    'md',
                    'lg'
                ),
                'columns' => array(
                    'md',
                    'lg'
                ),
            ),
        );

        $settings['columns'] = array(
            'id' => 'quadmenu-settings[columns]',
            'db' => 'columns',
            'type' => 'width',
            'default' => array(),
            'ops' => array(
                'icons' => array(
                    'xs',
                    'sm',
                    'md',
                    'lg'
                ),
                'columns' => array(
                    '',
                    'sm',
                    'md',
                    'lg'
                ),
                'hidden' => array(
                    '',
                    'sm',
                    'md',
                    'lg'
                ),
            ),
        );

        $settings['cart'] = array(
            'id' => 'quadmenu-settings[cart]',
            'db' => 'cart',
            'title' => esc_html__('Cart', 'quadmenu'),
            'type' => 'select',
            'default' => 'woo',
            'ops' => array(
                'woo' => esc_html__('WooCommerce Cart', 'quadmenu'),
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

    function default_values_nav_menu_items($item) {

        $defaults = $this->nav_menu_item_fields_defaults();

        foreach ($defaults as $key => $value) {

            if (property_exists($item, $key))
                continue;

            $item->{$key} = apply_filters('quadmenu_nav_menu_item_field_default', $value, $key, $item);
        }

        return $item;
    }

    function custom_default_values_nav_menu_items($value, $key, $item) {

        if ($key == 'icon') {

            if ($item->quadmenu == 'social') {
                $value = 'dashicons dashicons-share';
            }

            if ($item->quadmenu == 'cart') {
                $value = 'dashicons dashicons-cart';
            }

            if ($item->quadmenu == 'icon') {
                $value = 'dashicons dashicons-info';
            }
        }

        if ($key == 'columns') {

            if ($item->quadmenu == 'column') {
                $value = array(
                    'col-12',
                    'col-sm-4'
                );
            }
        }

        return $value;
    }

    function files($files) {

        $files[] = QUADMENU_URL_ASSETS . 'frontend/less/quadmenu-locations.less';
        $files[] = QUADMENU_URL_ASSETS . 'frontend/less/quadmenu-widgets.less';

        return $files;
    }

    function icons() {

        $register_icons = array(
            'dashicons' => array(
                'name' => 'Dashicons',
                'url' => false,
                'prefix' => '',
                'iconmap' => 'dashicons dashicons-menu,dashicons dashicons-admin-site,dashicons dashicons-dashboard,dashicons dashicons-admin-post,dashicons dashicons-admin-media,dashicons dashicons-admin-links,dashicons dashicons-admin-page,dashicons dashicons-admin-comments,dashicons dashicons-admin-appearance,dashicons dashicons-admin-plugins,dashicons dashicons-admin-users,dashicons dashicons-admin-tools,dashicons dashicons-admin-settings,dashicons dashicons-admin-network,dashicons dashicons-admin-home,dashicons dashicons-admin-generic,dashicons dashicons-admin-collapse,dashicons dashicons-filter,dashicons dashicons-admin-customizer,dashicons dashicons-admin-multisite,dashicons dashicons-welcome-write-blog,dashicons dashicons-welcome-add-page,dashicons dashicons-welcome-view-site,dashicons dashicons-welcome-widgets-menus,dashicons dashicons-welcome-comments,dashicons dashicons-welcome-learn-more,dashicons dashicons-format-aside,dashicons dashicons-format-image,dashicons dashicons-format-gallery,dashicons dashicons-format-video,dashicons dashicons-format-status,dashicons dashicons-format-quote,dashicons dashicons-format-chat,dashicons dashicons-format-audio,dashicons dashicons-camera,dashicons dashicons-images-alt,dashicons dashicons-images-alt2,dashicons dashicons-video-alt,dashicons dashicons-video-alt2,dashicons dashicons-video-alt3,dashicons dashicons-media-archive,dashicons dashicons-media-audio,dashicons dashicons-media-code,dashicons dashicons-media-default,dashicons dashicons-media-document,dashicons dashicons-media-interactive,dashicons dashicons-media-spreadsheet,dashicons dashicons-media-text,dashicons dashicons-media-video,dashicons dashicons-playlist-audio,dashicons dashicons-playlist-video,dashicons dashicons-controls-play,dashicons dashicons-controls-pause,dashicons dashicons-controls-forward,dashicons dashicons-controls-skipforward,dashicons dashicons-controls-back,dashicons dashicons-controls-skipback,dashicons dashicons-controls-repeat,dashicons dashicons-controls-volumeon,dashicons dashicons-controls-volumeoff,dashicons dashicons-image-crop,dashicons dashicons-image-rotate,dashicons dashicons-image-rotate-left,dashicons dashicons-image-rotate-right,dashicons dashicons-image-flip-vertical,dashicons dashicons-image-flip-horizontal,dashicons dashicons-image-filter,dashicons dashicons-undo,dashicons dashicons-redo,dashicons dashicons-editor-bold,dashicons dashicons-editor-italic,dashicons dashicons-editor-ul,dashicons dashicons-editor-ol,dashicons dashicons-editor-quote,dashicons dashicons-editor-alignleft,dashicons dashicons-editor-aligncenter,dashicons dashicons-editor-alignright,dashicons dashicons-editor-insertmore,dashicons dashicons-editor-spellcheck,dashicons dashicons-editor-expand,dashicons dashicons-editor-contract,dashicons dashicons-editor-kitchensink,dashicons dashicons-editor-underline,dashicons dashicons-editor-justify,dashicons dashicons-editor-textcolor,dashicons dashicons-editor-paste-word,dashicons dashicons-editor-paste-text,dashicons dashicons-editor-removeformatting,dashicons dashicons-editor-video,dashicons dashicons-editor-customchar,dashicons dashicons-editor-outdent,dashicons dashicons-editor-indent,dashicons dashicons-editor-help,dashicons dashicons-editor-strikethrough,dashicons dashicons-editor-unlink,dashicons dashicons-editor-rtl,dashicons dashicons-editor-break,dashicons dashicons-editor-code,dashicons dashicons-editor-paragraph,dashicons dashicons-editor-table,dashicons dashicons-align-left,dashicons dashicons-align-right,dashicons dashicons-align-center,dashicons dashicons-align-none,dashicons dashicons-lock,dashicons dashicons-unlock,dashicons dashicons-calendar,dashicons dashicons-calendar-alt,dashicons dashicons-visibility,dashicons dashicons-hidden,dashicons dashicons-post-status,dashicons dashicons-edit,dashicons dashicons-trash,dashicons dashicons-sticky,dashicons dashicons-external,dashicons dashicons-arrow-up,dashicons dashicons-arrow-down,dashicons dashicons-arrow-right,dashicons dashicons-arrow-left,dashicons dashicons-arrow-up-alt,dashicons dashicons-arrow-down-alt,dashicons dashicons-arrow-right-alt,dashicons dashicons-arrow-left-alt,dashicons dashicons-arrow-up-alt2,dashicons dashicons-arrow-down-alt2,dashicons dashicons-arrow-right-alt2,dashicons dashicons-arrow-left-alt2,dashicons dashicons-sort,dashicons dashicons-leftright,dashicons dashicons-randomize,dashicons dashicons-list-view,dashicons dashicons-exerpt-view,dashicons dashicons-grid-view,dashicons dashicons-share,dashicons dashicons-share-alt,dashicons dashicons-share-alt2,dashicons dashicons-twitter,dashicons dashicons-rss,dashicons dashicons-email,dashicons dashicons-email-alt,dashicons dashicons-facebook,dashicons dashicons-facebook-alt,dashicons dashicons-googleplus,dashicons dashicons-networking,dashicons dashicons-hammer,dashicons dashicons-art,dashicons dashicons-migrate,dashicons dashicons-performance,dashicons dashicons-universal-access,dashicons dashicons-universal-access-alt,dashicons dashicons-tickets,dashicons dashicons-nametag,dashicons dashicons-clipboard,dashicons dashicons-heart,dashicons dashicons-megaphone,dashicons dashicons-schedule,dashicons dashicons-wordpress,dashicons dashicons-wordpress-alt,dashicons dashicons-pressthis,dashicons dashicons-update,dashicons dashicons-screenoptions,dashicons dashicons-info,dashicons dashicons-cart,dashicons dashicons-feedback,dashicons dashicons-cloud,dashicons dashicons-translation,dashicons dashicons-tag,dashicons dashicons-category,dashicons dashicons-archive,dashicons dashicons-tagcloud,dashicons dashicons-text,dashicons dashicons-yes,dashicons dashicons-no,dashicons dashicons-no-alt,dashicons dashicons-plus,dashicons dashicons-plus-alt,dashicons dashicons-minus,dashicons dashicons-dismiss,dashicons dashicons-marker,dashicons dashicons-star-filled,dashicons dashicons-star-half,dashicons dashicons-star-empty,dashicons dashicons-flag,dashicons dashicons-warning,dashicons dashicons-location,dashicons dashicons-location-alt,dashicons dashicons-vault,dashicons dashicons-shield,dashicons dashicons-shield-alt,dashicons dashicons-sos,dashicons dashicons-search,dashicons dashicons-slides,dashicons dashicons-analytics,dashicons dashicons-chart-pie,dashicons dashicons-chart-bar,dashicons dashicons-chart-line,dashicons dashicons-chart-area,dashicons dashicons-groups,dashicons dashicons-businessman,dashicons dashicons-id,dashicons dashicons-id-alt,dashicons dashicons-products,dashicons dashicons-awards,dashicons dashicons-forms,dashicons dashicons-testimonial,dashicons dashicons-portfolio,dashicons dashicons-book,dashicons dashicons-book-alt,dashicons dashicons-download,dashicons dashicons-upload,dashicons dashicons-backup,dashicons dashicons-clock,dashicons dashicons-lightbulb,dashicons dashicons-microphone,dashicons dashicons-desktop,dashicons dashicons-tablet,dashicons dashicons-smartphone,dashicons dashicons-phone,dashicons dashicons-index-card,dashicons dashicons-carrot,dashicons dashicons-building,dashicons dashicons-store,dashicons dashicons-album,dashicons dashicons-palmtree,dashicons dashicons-tickets-alt,dashicons dashicons-money,dashicons dashicons-smiley,dashicons dashicons-thumbs-up,dashicons dashicons-thumbs-down,dashicons dashicons-layout'
            ),
            'eleganticons' => array(
                'name' => 'Elegant Icons',
                'url' => QUADMENU_URL_ASSETS . 'frontend/icons/eleganticons/style.min.css',
                'prefix' => '',
                'iconmap' => 'arrow_up,arrow_down,arrow_left,arrow_right,arrow_left-up,arrow_right-up,arrow_right-down,arrow_left-down,arrow-up-down,arrow_up-down_alt,arrow_left-right_alt,arrow_left-right,arrow_expand_alt2,arrow_expand_alt,arrow_condense,arrow_expand,arrow_move,arrow_carrot-up,arrow_carrot-down,arrow_carrot-left,arrow_carrot-right,arrow_carrot-2up,arrow_carrot-2down,arrow_carrot-2left,arrow_carrot-2right,arrow_carrot-up_alt2,arrow_carrot-down_alt2,arrow_carrot-left_alt2,arrow_carrot-right_alt2,arrow_carrot-2up_alt2,arrow_carrot-2down_alt2,arrow_carrot-2left_alt2,arrow_carrot-2right_alt2,arrow_triangle-up,arrow_triangle-down,arrow_triangle-left,arrow_triangle-right,arrow_triangle-up_alt2,arrow_triangle-down_alt2,arrow_triangle-left_alt2,arrow_triangle-right_alt2,arrow_back,icon_minus-06,icon_plus,icon_close,icon_check,icon_minus_alt2,icon_plus_alt2,icon_close_alt2,icon_check_alt2,icon_zoom-out_alt,icon_zoom-in_alt,icon_search,icon_box-empty,icon_box-selected,icon_minus-box,icon_plus-box,icon_box-checked,icon_circle-empty,icon_circle-slelected,icon_stop_alt2,icon_stop,icon_pause_alt2,icon_pause,icon_menu,icon_menu-square_alt2,icon_menu-circle_alt2,icon_ul,icon_ol,icon_adjust-horiz,icon_adjust-vert,icon_document_alt,icon_documents_alt,icon_pencil,icon_pencil-edit_alt,icon_pencil-edit,icon_folder-alt,icon_folder-open_alt,icon_folder-add_alt,icon_info_alt,icon_error-oct_alt,icon_error-circle_alt,icon_error-triangle_alt,icon_question_alt2,icon_question,icon_comment_alt,icon_chat_alt,icon_vol-mute_alt,icon_volume-low_alt,icon_volume-high_alt,icon_quotations,icon_quotations_alt2,icon_clock_alt,icon_lock_alt,icon_lock-open_alt,icon_key_alt,icon_cloud_alt,icon_cloud-upload_alt,icon_cloud-download_alt,icon_image,icon_images,icon_lightbulb_alt,icon_gift_alt,icon_house_alt,icon_genius,icon_mobile,icon_tablet,icon_laptop,icon_desktop,icon_camera_alt,icon_mail_alt,icon_cone_alt,icon_ribbon_alt,icon_bag_alt,icon_creditcard,icon_cart_alt,icon_paperclip,icon_tag_alt,icon_tags_alt,icon_trash_alt,icon_cursor_alt,icon_mic_alt,icon_compass_alt,icon_pin_alt,icon_pushpin_alt,icon_map_alt,icon_drawer_alt,icon_toolbox_alt,icon_book_alt,icon_calendar,icon_film,icon_table,icon_contacts_alt,icon_headphones,icon_lifesaver,icon_piechart,icon_refresh,icon_link_alt,icon_link,icon_loading,icon_blocked,icon_archive_alt,icon_heart_alt,icon_star_alt,icon_star-half_alt,icon_star,icon_star-half,icon_tools,icon_tool,icon_cog,icon_cogs,arrow_up_alt,arrow_down_alt,arrow_left_alt,arrow_right_alt,arrow_left-up_alt,arrow_right-up_alt,arrow_right-down_alt,arrow_left-down_alt,arrow_condense_alt,arrow_expand_alt3,arrow_carrot_up_alt,arrow_carrot-down_alt,arrow_carrot-left_alt,arrow_carrot-right_alt,arrow_carrot-2up_alt,arrow_carrot-2dwnn_alt,arrow_carrot-2left_alt,arrow_carrot-2right_alt,arrow_triangle-up_alt,arrow_triangle-down_alt,arrow_triangle-left_alt,arrow_triangle-right_alt,icon_minus_alt,icon_plus_alt,icon_close_alt,icon_check_alt,icon_zoom-out,icon_zoom-in,icon_stop_alt,icon_menu-square_alt,icon_menu-circle_alt,icon_document,icon_documents,icon_pencil_alt,icon_folder,icon_folder-open,icon_folder-add,icon_folder_upload,icon_folder_download,icon_info,icon_error-circle,icon_error-oct,icon_error-triangle,icon_question_alt,icon_comment,icon_chat,icon_vol-mute,icon_volume-low,icon_volume-high,icon_quotations_alt,icon_clock,icon_lock,icon_lock-open,icon_key,icon_cloud,icon_cloud-upload,icon_cloud-download,icon_lightbulb,icon_gift,icon_house,icon_camera,icon_mail,icon_cone,icon_ribbon,icon_bag,icon_cart,icon_tag,icon_tags,icon_trash,icon_cursor,icon_mic,icon_compass,icon_pin,icon_pushpin,icon_map,icon_drawer,icon_toolbox,icon_book,icon_contacts,icon_archive,icon_heart,icon_profile,icon_group,icon_grid-2x2,icon_grid-3x3,icon_music,icon_pause_alt,icon_phone,icon_upload,icon_download,social_facebook,social_twitter,social_pinterest,social_googleplus,social_tumblr,social_tumbleupon,social_wordpress,social_instagram,social_dribbble,social_vimeo,social_linkedin,social_rss,social_deviantart,social_share,social_myspace,social_skype,social_youtube,social_picassa,social_googledrive,social_flickr,social_blogger,social_spotify,social_delicious,social_facebook_circle,social_twitter_circle,social_pinterest_circle,social_googleplus_circle,social_tumblr_circle,social_stumbleupon_circle,social_wordpress_circle,social_instagram_circle,social_dribbble_circle,social_vimeo_circle,social_linkedin_circle,social_rss_circle,social_deviantart_circle,social_share_circle,social_myspace_circle,social_skype_circle,social_youtube_circle,social_picassa_circle,social_googledrive_alt2,social_flickr_circle,social_blogger_circle,social_spotify_circle,social_delicious_circle,social_facebook_square,social_twitter_square,social_pinterest_square,social_googleplus_square,social_tumblr_square,social_stumbleupon_square,social_wordpress_square,social_instagram_square,social_dribbble_square,social_vimeo_square,social_linkedin_square,social_rss_square,social_deviantart_square,social_share_square,social_myspace_square,social_skype_square,social_youtube_square,social_picassa_square,social_googledrive_square,social_flickr_square,social_blogger_square,social_spotify_square,social_delicious_square,icon_printer,icon_calulator,icon_building,icon_floppy,icon_drive,icon_search-2,icon_id,icon_id-2,icon_puzzle,icon_like,icon_dislike,icon_mug,icon_currency,icon_wallet,icon_pens,icon_easel,icon_flowchart,icon_datareport,icon_briefcase,icon_shield,icon_percent,icon_globe,icon_globe-2,icon_target,icon_hourglass,icon_balance,icon_rook,icon_printer-alt,icon_calculator_alt,icon_building_alt,icon_floppy_alt,icon_drive_alt,icon_search_alt,icon_id_alt,icon_id-2_alt,icon_puzzle_alt,icon_like_alt,icon_dislike_alt,icon_mug_alt,icon_currency_alt,icon_wallet_alt,icon_pens_alt,icon_easel_alt,icon_flowchart_alt,icon_datareport_alt,icon_briefcase_alt,icon_shield_alt,icon_percent_alt,icon_globe_alt,icon_clipboard'
            ),
            'elusive' => array(
                'name' => 'Elusive Icons',
                'url' => QUADMENU_URL_ASSETS . 'frontend/icons/elusive/css/elusive-icons.min.css',
                'iconmap' => 'el el-address-book-alt,el el-address-book,el el-adjust-alt,el el-adjust,el el-adult,el el-align-center,el el-align-justify,el el-align-left,el el-align-right,el el-arrow-down,el el-arrow-left,el el-arrow-right,el el-arrow-up,el el-asl,el el-asterisk,el el-backward,el el-ban-circle,el el-barcode,el el-behance,el el-bell,el el-blind,el el-blogger,el el-bold,el el-book,el el-bookmark-empty,el el-bookmark,el el-braille,el el-briefcase,el el-broom,el el-brush,el el-bulb,el el-bullhorn,el el-calendar-sign,el el-calendar,el el-camera,el el-car,el el-caret-down,el el-caret-left,el el-caret-right,el el-caret-up,el el-cc,el el-certificate,el el-check-empty,el el-check,el el-chevron-down,el el-chevron-left,el el-chevron-right,el el-chevron-up,el el-child,el el-circle-arrow-down,el el-circle-arrow-left,el el-circle-arrow-right,el el-circle-arrow-up,el el-cloud-alt,el el-cloud,el el-cog-alt,el el-cog,el el-cogs,el el-comment-alt,el el-comment,el el-compass-alt,el el-compass,el el-credit-card,el el-css,el el-dashboard,el el-delicious,el el-deviantart,el el-digg,el el-download-alt,el el-download,el el-dribbble,el el-edit,el el-eject,el el-envelope-alt,el el-envelope,el el-error-alt,el el-error,el el-eur,el el-exclamation-sign,el el-eye-close,el el-eye-open,el el-facebook,el el-facetime-video,el el-fast-backward,el el-fast-forward,el el-female,el el-file-alt,el el-file-edit-alt,el el-file-edit,el el-file-new-alt,el el-file-new,el el-file,el el-film,el el-filter,el el-fire,el el-flag-alt,el el-flag,el el-flickr,el el-folder-close,el el-folder-open,el el-folder-sign,el el-folder,el el-font,el el-fontsize,el el-fork,el el-forward-alt,el el-forward,el el-foursquare,el el-friendfeed-rect,el el-friendfeed,el el-fullscreen,el el-gbp,el el-gift,el el-github-text,el el-github,el el-glass,el el-glasses,el el-globe-alt,el el-globe,el el-googleplus,el el-graph-alt,el el-graph,el el-group-alt,el el-group,el el-guidedog,el el-hand-down,el el-hand-left,el el-hand-right,el el-hand-up,el el-hdd,el el-headphones,el el-hearing-impaired,el el-heart-alt,el el-heart-empty,el el-heart,el el-home-alt,el el-home,el el-hourglass,el el-idea-alt,el el-idea,el el-inbox-alt,el el-inbox-box,el el-inbox,el el-indent-left,el el-indent-right,el el-info-circle,el el-instagram,el el-iphone-home,el el-italic,el el-key,el el-laptop-alt,el el-laptop,el el-lastfm,el el-leaf,el el-lines,el el-link,el el-linkedin,el el-list-alt,el el-list,el el-livejournal,el el-lock-alt,el el-lock,el el-magic,el el-magnet,el el-male,el el-map-marker-alt,el el-map-marker,el el-mic-alt,el el-mic,el el-minus-sign,el el-minus,el el-move,el el-music,el el-myspace,el el-network,el el-off,el el-ok-circle,el el-ok-sign,el el-ok,el el-opensource,el el-paper-clip-alt,el el-paper-clip,el el-path,el el-pause-alt,el el-pause,el el-pencil-alt,el el-pencil,el el-person,el el-phone-alt,el el-phone,el el-photo-alt,el el-photo,el el-picasa,el el-picture,el el-pinterest,el el-plane,el el-play-alt,el el-play-circle,el el-play,el el-plurk-alt,el el-plurk,el el-plus-sign,el el-plus,el el-podcast,el el-print,el el-puzzle,el el-qrcode,el el-question-sign,el el-question,el el-quote-alt,el el-quote-right-alt,el el-quote-right,el el-quotes,el el-random,el el-record,el el-reddit,el el-redux,el el-refresh,el el-remove-circle,el el-remove-sign,el el-remove,el el-repeat-alt,el el-repeat,el el-resize-full,el el-resize-horizontal,el el-resize-small,el el-resize-vertical,el el-return-key,el el-retweet,el el-reverse-alt,el el-road,el el-rss,el el-scissors,el el-screen-alt,el el-screen,el el-screenshot,el el-search-alt,el el-search,el el-share-alt,el el-share,el el-shopping-cart-sign,el el-shopping-cart,el el-signal,el el-skype,el el-slideshare,el el-smiley-alt,el el-smiley,el el-soundcloud,el el-speaker,el el-spotify,el el-stackoverflow,el el-star-alt,el el-star-empty,el el-star,el el-step-backward,el el-step-forward,el el-stop-alt,el el-stop,el el-stumbleupon,el el-tag,el el-tags,el el-tasks,el el-text-height,el el-text-width,el el-th-large,el el-th-list,el el-th,el el-thumbs-down,el el-thumbs-up,el el-time-alt,el el-time,el el-tint,el el-torso,el el-trash-alt,el el-trash,el el-tumblr,el el-twitter,el el-universal-access,el el-unlock-alt,el el-unlock,el el-upload,el el-usd,el el-user,el el-viadeo,el el-video-alt,el el-video-chat,el el-video,el el-view-mode,el el-vimeo,el el-vkontakte,el el-volume-down,el el-volume-off,el el-volume-up,el el-warning-sign,el el-website-alt,el el-website,el el-wheelchair,el el-wordpress,el el-wrench-alt,el el-wrench,el el-youtube,el el-zoom-in,el el-zoom-out'
            ),
            'fontawesome' => array(
                'name' => 'FontAwsome Icons',
                'url' => QUADMENU_URL_ASSETS . 'frontend/icons/fontawesome/css/font-awesome.min.css',
                'iconmap' => 'fa fa-glass,fa fa-music,fa fa-search,fa fa-envelope-o,fa fa-heart,fa fa-star,fa fa-star-o,fa fa-user,fa fa-film,fa fa-th-large,fa fa-th ,fa fa-th-list ,fa fa-check ,fa fa-remove,fa fa-close,fa fa-times ,fa fa-search-plus ,fa fa-search-minus,fa fa-power-off,fa fa-signal,fa fa-gear,fa fa-cog,fa fa-trash-o,fa fa-home,fa fa-file-o,fa fa-clock-o,fa fa-road,fa fa-download,fa fa-arrow-circle-o-down ,fa fa-arrow-circle-o-up ,fa fa-inbox ,fa fa-play-circle-o ,fa fa-rotate-right,fa fa-repeat ,fa fa-refresh,fa fa-list-alt,fa fa-lock,fa fa-flag,fa fa-headphones,fa fa-volume-off,fa fa-volume-down,fa fa-volume-up,fa fa-qrcode,fa fa-barcode ,fa fa-tag ,fa fa-tags ,fa fa-book ,fa fa-bookmark ,fa fa-print ,fa fa-camera,fa fa-font,fa fa-bold,fa fa-italic,fa fa-text-height,fa fa-text-width,fa fa-align-left,fa fa-align-center,fa fa-align-right,fa fa-align-justify,fa fa-list ,fa fa-dedent,fa fa-outdent ,fa fa-indent ,fa fa-video-camera ,fa fa-photo,fa fa-image,fa fa-picture-o ,fa fa-pencil,fa fa-map-marker,fa fa-adjust,fa fa-tint,fa fa-edit,fa fa-pencil-square-o,fa fa-share-square-o,fa fa-check-square-o,fa fa-arrows,fa fa-step-backward,fa fa-fast-backward,fa fa-backward ,fa fa-play ,fa fa-pause ,fa fa-stop ,fa fa-forward ,fa fa-fast-forward,fa fa-step-forward,fa fa-eject,fa fa-chevron-left,fa fa-chevron-right,fa fa-plus-circle,fa fa-minus-circle,fa fa-times-circle,fa fa-check-circle,fa fa-question-circle,fa fa-info-circle ,fa fa-crosshairs ,fa fa-times-circle-o ,fa fa-check-circle-o ,fa fa-ban ,fa fa-arrow-left,fa fa-arrow-right,fa fa-arrow-up,fa fa-arrow-down,fa fa-mail-forward,fa fa-share,fa fa-expand,fa fa-compress,fa fa-plus,fa fa-minus,fa fa-asterisk,fa fa-exclamation-circle ,fa fa-gift ,fa fa-leaf ,fa fa-fire ,fa fa-eye ,fa fa-eye-slash,fa fa-warning,fa fa-exclamation-triangle,fa fa-plane,fa fa-calendar,fa fa-random,fa fa-comment,fa fa-magnet,fa fa-chevron-up,fa fa-chevron-down,fa fa-retweet,fa fa-shopping-cart ,fa fa-folder ,fa fa-folder-open ,fa fa-arrows-v ,fa fa-arrows-h ,fa fa-bar-chart-o,fa fa-bar-chart,fa fa-twitter-square,fa fa-facebook-square,fa fa-camera-retro,fa fa-key,fa fa-gears,fa fa-cogs,fa fa-comments,fa fa-thumbs-o-up,fa fa-thumbs-o-down,fa fa-star-half,fa fa-heart-o ,fa fa-sign-out ,fa fa-linkedin-square ,fa fa-thumb-tack ,fa fa-external-link ,fa fa-sign-in,fa fa-trophy,fa fa-github-square,fa fa-upload,fa fa-lemon-o,fa fa-phone,fa fa-square-o,fa fa-bookmark-o,fa fa-phone-square,fa fa-twitter,fa fa-facebook-f,fa fa-facebook ,fa fa-github ,fa fa-unlock ,fa fa-credit-card ,fa fa-rss ,fa fa-hdd-o ,fa fa-bullhorn ,fa fa-bell ,fa fa-certificate ,fa fa-hand-o-right ,fa fa-hand-o-left ,fa fa-hand-o-up ,fa fa-hand-o-down ,fa fa-arrow-circle-left ,fa fa-arrow-circle-right ,fa fa-arrow-circle-up,fa fa-arrow-circle-down,fa fa-globe,fa fa-wrench,fa fa-tasks,fa fa-filter ,fa fa-briefcase ,fa fa-arrows-alt ,fa fa-group,fa fa-users ,fa fa-chain,fa fa-link ,fa fa-cloud ,fa fa-flask ,fa fa-cut,fa fa-scissors ,fa fa-copy,fa fa-files-o ,fa fa-paperclip ,fa fa-save,fa fa-floppy-o ,fa fa-square ,fa fa-navicon,fa fa-reorder,fa fa-bars ,fa fa-list-ul,fa fa-list-ol,fa fa-strikethrough,fa fa-underline,fa fa-table,fa fa-magic ,fa fa-truck ,fa fa-pinterest ,fa fa-pinterest-square ,fa fa-google-plus-square ,fa fa-google-plus ,fa fa-money ,fa fa-caret-down ,fa fa-caret-up ,fa fa-caret-left ,fa fa-caret-right,fa fa-columns,fa fa-unsorted,fa fa-sort,fa fa-sort-down,fa fa-sort-desc,fa fa-sort-up,fa fa-sort-asc,fa fa-envelope ,fa fa-linkedin ,fa fa-rotate-left,fa fa-undo ,fa fa-legal,fa fa-gavel ,fa fa-dashboard,fa fa-tachometer ,fa fa-comment-o ,fa fa-comments-o ,fa fa-flash,fa fa-bolt ,fa fa-sitemap ,fa fa-umbrella ,fa fa-paste,fa fa-clipboard,fa fa-lightbulb-o,fa fa-exchange,fa fa-cloud-download,fa fa-cloud-upload,fa fa-user-md ,fa fa-stethoscope ,fa fa-suitcase ,fa fa-bell-o ,fa fa-coffee ,fa fa-cutlery ,fa fa-file-text-o ,fa fa-building-o ,fa fa-hospital-o ,fa fa-ambulance ,fa fa-medkit,fa fa-fighter-jet ,fa fa-beer,fa fa-h-square fa fa-plus-square,fa fa-angle-double-left,fa fa-angle-double-right,fa fa-angle-double-up,fa fa-angle-double-down,fa fa-angle-left,fa fa-angle-right,fa fa-angle-up,fa fa-angle-down,fa fa-desktop,fa fa-laptop,fa fa-tablet ,fa fa-mobile-phone,fa fa-mobile ,fa fa-circle-o ,fa fa-quote-left ,fa fa-quote-right ,fa fa-spinner,fa fa-circle,fa fa-mail-reply,fa fa-reply,fa fa-github-alt,fa fa-folder-o,fa fa-folder-open-o,fa fa-smile-o,fa fa-frown-o,fa fa-meh-o ,fa fa-gamepad ,fa fa-keyboard-o ,fa fa-flag-o ,fa fa-flag-checkered ,fa fa-terminal,fa fa-code,fa fa-mail-reply-all,fa fa-reply-all,fa fa-star-half-empty,fa fa-star-half-full,fa fa-star-half-o,fa fa-location-arrow,fa fa-crop,fa fa-code-fork,fa fa-unlink,fa fa-chain-broken,fa fa-question,fa fa-info,fa fa-exclamation ,fa fa-superscript ,fa fa-subscript ,fa fa-eraser ,fa fa-puzzle-piece ,fa fa-microphone,fa fa-microphone-slash,fa fa-shield,fa fa-calendar-o,fa fa-fire-extinguisher,fa fa-rocket,fa fa-maxcdn,fa fa-chevron-circle-left,fa fa-chevron-circle-right,fa fa-chevron-circle-up,fa fa-chevron-circle-down ,fa fa-html ,fa fa-css ,fa fa-anchor ,fa fa-unlock-alt ,fa fa-bullseye,fa fa-ellipsis-h,fa fa-ellipsis-v,fa fa-rss-square,fa fa-play-circle,fa fa-ticket,fa fa-minus-square,fa fa-minus-square-o,fa fa-level-up,fa fa-level-down,fa fa-check-square ,fa fa-pencil-square ,fa fa-external-link-square ,fa fa-share-square ,fa fa-compass ,fa fa-toggle-down,fa fa-caret-square-o-down,fa fa-toggle-up,fa fa-caret-square-o-up,fa fa-toggle-right,fa fa-caret-square-o-right,fa fa-euro,fa fa-eur,fa fa-gbp,fa fa-dollar,fa fa-usd,fa fa-rupee,fa fa-inr,fa fa-cny,fa fa-rmb,fa fa-yen,fa fa-jpy,fa fa-ruble,fa fa-rouble,fa fa-rub,fa fa-won,fa fa-krw,fa fa-bitcoin,fa fa-btc ,fa fa-file ,fa fa-file-text ,fa fa-sort-alpha-asc ,fa fa-sort-alpha-desc ,fa fa-sort-amount-asc,fa fa-sort-amount-desc,fa fa-sort-numeric-asc,fa fa-sort-numeric-desc,fa fa-thumbs-up,fa fa-thumbs-down,fa fa-youtube-square,fa fa-youtube,fa fa-xing,fa fa-xing-square,fa fa-youtube-play ,fa fa-dropbox ,fa fa-stack-overflow ,fa fa-instagram ,fa fa-flickr ,fa fa-adn,fa fa-bitbucket,fa fa-bitbucket-square,fa fa-tumblr,fa fa-tumblr-square,fa fa-long-arrow-down,fa fa-long-arrow-up,fa fa-long-arrow-left,fa fa-long-arrow-right,fa fa-apple,fa fa-windows ,fa fa-android ,fa fa-linux ,fa fa-dribbble ,fa fa-skype ,fa fa-foursquare,fa fa-trello,fa fa-female,fa fa-male,fa fa-gittip,fa fa-gratipay,fa fa-sun-o,fa fa-moon-o,fa fa-archive,fa fa-bug,fa fa-vk,fa fa-weibo ,fa fa-renren ,fa fa-pagelines ,fa fa-stack-exchange ,fa fa-arrow-circle-o-right ,fa fa-arrow-circle-o-left,fa fa-toggle-left,fa fa-caret-square-o-left,fa fa-dot-circle-o,fa fa-wheelchair,fa fa-vimeo-square,fa fa-turkish-lira,fa fa-try,fa fa-plus-square-o,fa fa-space-shuttle,fa fa-slack,fa fa-envelope-square,fa fa-wordpress ,fa fa-openid ,fa fa-institution,fa fa-bank,fa fa-university ,fa fa-mortar-board,fa fa-graduation-cap ,fa fa-yahoo ,fa fa-google ,fa fa-reddit ,fa fa-reddit-square ,fa fa-stumbleupon-circle ,fa fa-stumbleupon ,fa fa-delicious ,fa fa-digg ,fa fa-pied-piper ,fa fa-pied-piper-alt ,fa fa-drupal ,fa fa-joomla,fa fa-language,fa fa-fax,fa fa-building,fa fa-child,fa fa-paw ,fa fa-spoon ,fa fa-cube ,fa fa-cubes ,fa fa-behance ,fa fa-behance-square ,fa fa-steam ,fa fa-steam-square ,fa fa-recycle ,fa fa-automobile,fa fa-car ,fa fa-cab,fa fa-taxi,fa fa-tree,fa fa-spotify,fa fa-deviantart,fa fa-soundcloud,fa fa-database ,fa fa-file-pdf-o ,fa fa-file-word-o ,fa fa-file-excel-o ,fa fa-file-powerpoint-o ,fa fa-file-photo-o,fa fa-file-picture-o,fa fa-file-image-o ,fa fa-file-zip-o,fa fa-file-archive-o ,fa fa-file-sound-o,fa fa-file-audio-o ,fa fa-file-movie-o,fa fa-file-video-o ,fa fa-file-code-o ,fa fa-vine,fa fa-codepen,fa fa-jsfiddle,fa fa-life-bouy,fa fa-life-buoy,fa fa-life-saver,fa fa-support,fa fa-life-ring,fa fa-circle-o-notch,fa fa-ra,fa fa-rebel ,fa fa-ge,fa fa-empire ,fa fa-git-square ,fa fa-git ,fa fa-hacker-news ,fa fa-tencent-weibo ,fa fa-qq ,fa fa-wechat,fa fa-weixin ,fa fa-send,fa fa-paper-plane ,fa fa-send-o,fa fa-paper-plane-o ,fa fa-history,fa fa-genderless,fa fa-circle-thin,fa fa-header,fa fa-paragraph,fa fa-sliders,fa fa-share-alt ,fa fa-share-alt-square ,fa fa-bomb ,fa fa-soccer-ball-o,fa fa-futbol-o ,fa fa-tty ,fa fa-binoculars ,fa fa-plug ,fa fa-slideshare ,fa fa-twitch ,fa fa-yelp ,fa fa-newspaper-o,fa fa-wifi,fa fa-calculator,fa fa-paypal,fa fa-google-wallet,fa fa-cc-visa ,fa fa-cc-mastercard ,fa fa-cc-discover ,fa fa-cc-amex ,fa fa-cc-paypal ,fa fa-cc-stripe ,fa fa-bell-slash ,fa fa-bell-slash-o ,fa fa-trash ,fa fa-copyright ,fa fa-at,fa fa-eyedropper,fa fa-paint-brush,fa fa-birthday-cake,fa fa-area-chart,fa fa-pie-chart,fa fa-line-chart,fa fa-lastfm,fa fa-lastfm-square,fa fa-toggle-off,fa fa-toggle-on,fa fa-bicycle,fa fa-bus,fa fa-ioxhost,fa fa-angellist,fa fa-cc ,fa fa-shekel,fa fa-sheqel,fa fa-ils ,fa fa-meanpath ,fa fa-buysellads ,fa fa-connectdevelop ,fa fa-dashcube,fa fa-forumbee,fa fa-leanpub,fa fa-sellsy,fa fa-shirtsinbulk,fa fa-simplybuilt,fa fa-skyatlas,fa fa-cart-plus,fa fa-cart-arrow-down,fa fa-diamond,fa fa-ship ,fa fa-user-secret ,fa fa-motorcycle ,fa fa-street-view ,fa fa-heartbeat ,fa fa-venus,fa fa-mars,fa fa-mercury,fa fa-transgender,fa fa-transgender-alt,fa fa-venus-double,fa fa-mars-double,fa fa-venus-mars,fa fa-mars-stroke,fa fa-mars-stroke-v ,fa fa-mars-stroke-h ,fa fa-neuter ,fa fa-facebook-official,fa fa-pinterest-p,fa fa-whatsapp,fa fa-server,fa fa-user-plus,fa fa-user-times,fa fa-hotel,fa fa-bed,fa fa-viacoin,fa fa-train,fa fa-subway,fa fa-medium'
            ),
            'foundation' => array(
                'name' => 'Foundation Icons',
                'url' => QUADMENU_URL_ASSETS . 'frontend/icons/foundation/foundation-icons.min.css',
                'iconmap' => 'fi-address-book,fi-alert,fi-align-center,fi-align-justify,fi-align-left,fi-align-right,fi-anchor,fi-annotate,fi-archive,fi-arrow-down,fi-arrow-left,fi-arrow-right,fi-arrow-up,fi-arrows-compress,fi-arrows-expand,fi-arrows-in,fi-arrows-out,fi-asl,fi-asterisk,fi-at-sign,fi-background-color,fi-battery-empty,fi-battery-full,fi-battery-half,fi-bitcoin-circle,fi-bitcoin,fi-blind,fi-bluetooth,fi-bold,fi-book-bookmark,fi-book,fi-bookmark,fi-braille,fi-burst-new,fi-burst-sale,fi-burst,fi-calendar,fi-camera,fi-check,fi-checkbox,fi-clipboard-notes,fi-clipboard-pencil,fi-clipboard,fi-clock,fi-closed-caption,fi-cloud,fi-comment-minus,fi-comment-quotes,fi-comment-video,fi-comment,fi-comments,fi-compass,fi-contrast,fi-credit-card,fi-crop,fi-crown,fi-css3,fi-database,fi-die-five,fi-die-four,fi-die-one,fi-die-six,fi-die-three,fi-die-two,fi-dislike,fi-dollar-bill,fi-dollar,fi-download,fi-eject,fi-elevator,fi-euro,fi-eye,fi-fast-forward,fi-female-symbol,fi-female,fi-filter,fi-first-aid,fi-flag,fi-folder-add,fi-folder-lock,fi-folder,fi-foot,fi-foundation,fi-graph-bar,fi-graph-horizontal,fi-graph-pie,fi-graph-trend,fi-guide-dog,fi-hearing-aid,fi-heart,fi-home,fi-html5,fi-indent-less,fi-indent-more,fi-info,fi-italic,fi-key,fi-laptop,fi-layout,fi-lightbulb,fi-like,fi-link,fi-list-bullet,fi-list-number,fi-list-thumbnails,fi-list,fi-lock,fi-loop,fi-magnifying-glass,fi-mail,fi-male-female,fi-male-symbol,fi-male,fi-map,fi-marker,fi-megaphone,fi-microphone,fi-minus-circle,fi-minus,fi-mobile-signal,fi-mobile,fi-monitor,fi-mountains,fi-music,fi-next,fi-no-dogs,fi-no-smoking,fi-page-add,fi-page-copy,fi-page-csv,fi-page-delete,fi-page-doc,fi-page-edit,fi-page-export-csv,fi-page-export-doc,fi-page-export-pdf,fi-page-export,fi-page-filled,fi-page-multiple,fi-page-pdf,fi-page-remove,fi-page-search,fi-page,fi-paint-bucket,fi-paperclip,fi-pause,fi-paw,fi-paypal,fi-pencil,fi-photo,fi-play-circle,fi-play-video,fi-play,fi-plus,fi-pound,fi-power,fi-previous,fi-price-tag,fi-pricetag-multiple,fi-print,fi-prohibited,fi-projection-screen,fi-puzzle,fi-quote,fi-record,fi-refresh,fi-results-demographics,fi-results,fi-rewind-ten,fi-rewind,fi-rss,fi-safety-cone,fi-save,fi-share,fi-sheriff-badge,fi-shield,fi-shopping-bag,fi-shopping-cart,fi-shuffle,fi-skull,fi-social-500px,fi-social-adobe,fi-social-amazon,fi-social-android,fi-social-apple,fi-social-behance,fi-social-bing,fi-social-blogger,fi-social-delicious,fi-social-designer-news,fi-social-deviant-art,fi-social-digg,fi-social-dribbble,fi-social-drive,fi-social-dropbox,fi-social-evernote,fi-social-facebook,fi-social-flickr,fi-social-forrst,fi-social-foursquare,fi-social-game-center,fi-social-github,fi-social-google-plus,fi-social-hacker-news,fi-social-hi5,fi-social-instagram,fi-social-joomla,fi-social-lastfm,fi-social-linkedin,fi-social-medium,fi-social-myspace,fi-social-orkut,fi-social-path,fi-social-picasa,fi-social-pinterest,fi-social-rdio,fi-social-reddit,fi-social-skillshare,fi-social-skype,fi-social-smashing-mag,fi-social-snapchat,fi-social-spotify,fi-social-squidoo,fi-social-stack-overflow,fi-social-steam,fi-social-stumbleupon,fi-social-treehouse,fi-social-tumblr,fi-social-twitter,fi-social-vimeo,fi-social-windows,fi-social-xbox,fi-social-yahoo,fi-social-yelp,fi-social-youtube,fi-social-zerply,fi-social-zurb,fi-sound,fi-star,fi-stop,fi-strikethrough,fi-subscript,fi-superscript,fi-tablet-landscape,fi-tablet-portrait,fi-target-two,fi-target,fi-telephone-accessible,fi-telephone,fi-text-color,fi-thumbnails,fi-ticket,fi-torso-business,fi-torso-female,fi-torso,fi-torsos-all-female,fi-torsos-all,fi-torsos-female-male,fi-torsos-male-female,fi-torsos,fi-trash,fi-trees,fi-trophy,fi-underline,fi-universal-access,fi-unlink,fi-unlock,fi-upload-cloud,fi-upload,fi-usb,fi-video,fi-volume-none,fi-volume-strike,fi-volume,fi-web,fi-wheelchair,fi-widget,fi-wrench,fi-x-circle,fi-x,fi-yen,fi-zoom-in,fi-zoom-out'
            ),
            'themify' => array(
                'url' => QUADMENU_URL_ASSETS . 'frontend/icons/themify/themify-icons.min.css',
                'name' => 'Themify Icons',
                'iconmap' => 'ti-wand,ti-volume,ti-user,ti-unlock,ti-unlink,ti-trash,ti-thought,ti-target,ti-tag,ti-tablet,ti-star,ti-spray,ti-signal,ti-shopping-cart,ti-shopping-cart-full,ti-settings,ti-search,ti-zoom-in,ti-zoom-out,ti-cut,ti-ruler,ti-ruler-pencil,ti-ruler-alt,ti-bookmark,ti-bookmark-alt,ti-reload,ti-plus,ti-pin,ti-pencil,ti-pencil-alt,ti-paint-roller,ti-paint-bucket,ti-na,ti-mobile,ti-minus,ti-medall,ti-medall-alt,ti-marker,ti-marker-alt,ti-arrow-up,ti-arrow-right,ti-arrow-left,ti-arrow-down,ti-lock,ti-location-arrow,ti-link,ti-layout,ti-layers,ti-layers-alt,ti-key,ti-import,ti-image,ti-heart,ti-heart-broken,ti-hand-stop,ti-hand-open,ti-hand-drag,ti-folder,ti-flag,ti-flag-alt,ti-flag-alt-2,ti-eye,ti-export,ti-exchange-vertical,ti-desktop,ti-cup,ti-crown,ti-comments,ti-comment,ti-comment-alt,ti-close,ti-clip,ti-angle-up,ti-angle-right,ti-angle-left,ti-angle-down,ti-check,ti-check-box,ti-camera,ti-announcement,ti-brush,ti-briefcase,ti-bolt,ti-bolt-alt,ti-blackboard,ti-bag,ti-move,ti-arrows-vertical,ti-arrows-horizontal,ti-fullscreen,ti-arrow-top-right,ti-arrow-top-left,ti-arrow-circle-up,ti-arrow-circle-right,ti-arrow-circle-left,ti-arrow-circle-down,ti-angle-double-up,ti-angle-double-right,ti-angle-double-left,ti-angle-double-down,ti-zip,ti-world,ti-wheelchair,ti-view-list,ti-view-list-alt,ti-view-grid,ti-uppercase,ti-upload,ti-underline,ti-truck,ti-timer,ti-ticket,ti-thumb-up,ti-thumb-down,ti-text,ti-stats-up,ti-stats-down,ti-split-v,ti-split-h,ti-smallcap,ti-shine,ti-shift-right,ti-shift-left,ti-shield,ti-notepad,ti-server,ti-quote-right,ti-quote-left,ti-pulse,ti-printer,ti-power-off,ti-plug,ti-pie-chart,ti-paragraph,ti-panel,ti-package,ti-music,ti-music-alt,ti-mouse,ti-mouse-alt,ti-money,ti-microphone,ti-menu,ti-menu-alt,ti-map,ti-map-alt,ti-loop,ti-location-pin,ti-list,ti-light-bulb,ti-Italic,ti-info,ti-infinite,ti-id-badge,ti-hummer,ti-home,ti-help,ti-headphone,ti-harddrives,ti-harddrive,ti-gift,ti-game,ti-filter,ti-files,ti-file,ti-eraser,ti-envelope,ti-download,ti-direction,ti-direction-alt,ti-dashboard,ti-control-stop,ti-control-shuffle,ti-control-play,ti-control-pause,ti-control-forward,ti-control-backward,ti-cloud,ti-cloud-up,ti-cloud-down,ti-clipboard,ti-car,ti-calendar,ti-book,ti-bell,ti-basketball,ti-bar-chart,ti-bar-chart-alt,ti-back-right,ti-back-left,ti-arrows-corner,ti-archive,ti-anchor,ti-align-right,ti-align-left,ti-align-justify,ti-align-center,ti-alert,ti-alarm-clock,ti-agenda,ti-write,ti-window,ti-widgetized,ti-widget,ti-widget-alt,ti-wallet,ti-video-clapper,ti-video-camera,ti-vector,ti-themify-logo,ti-themify-favicon,ti-themify-favicon-alt,ti-support,ti-stamp,ti-split-v-alt,ti-slice,ti-shortcode,ti-shift-right-alt,ti-shift-left-alt,ti-ruler-alt-2,ti-receipt,ti-pin,ti-pin-alt,ti-pencil-alt,ti-palette,ti-more,ti-more-alt,ti-microphone-alt,ti-magnet,ti-line-double,ti-line-dotted,ti-line-dashed,ti-layout-width-full,ti-layout-width-default,ti-layout-width-default-alt,ti-layout-tab,ti-layout-tab-window,ti-layout-tab-v,ti-layout-tab-min,ti-layout-slider,ti-layout-slider-alt,ti-layout-sidebar-right,ti-layout-sidebar-none,ti-layout-sidebar-left,ti-layout-placeholder,ti-layout-menu,ti-layout-menu-v,ti-layout-menu-separated,ti-layout-menu-full,ti-layout-media-right-alt,ti-layout-media-right,ti-layout-media-overlay,ti-layout-media-overlay-alt,ti-layout-media-overlay-alt-2,ti-layout-media-left-alt,ti-layout-media-left,ti-layout-media-center-alt,ti-layout-media-center,ti-layout-list-thumb,ti-layout-list-thumb-alt,ti-layout-list-post,ti-layout-list-large-image,ti-layout-line-solid,ti-layout-grid4,ti-layout-grid3,ti-layout-grid2,ti-layout-grid2-thumb,ti-layout-cta-right,ti-layout-cta-left,ti-layout-cta-center,ti-layout-cta-btn-right,ti-layout-cta-btn-left,ti-layout-column4,ti-layout-column3,ti-layout-column2,ti-layout-accordion-separated,ti-layout-accordion-merged,ti-layout-accordion-list,ti-ink-pen,ti-info-alt,ti-help-alt,ti-headphone-alt,ti-hand-point-up,ti-hand-point-right,ti-hand-point-left,ti-hand-point-down,ti-gallery,ti-face-smile,ti-face-sad,ti-credit-card,ti-control-skip-forward,ti-control-skip-backward,ti-control-record,ti-control-eject,ti-comments-smiley,ti-brush-alt,ti-youtube,ti-vimeo,ti-twitter,ti-time,ti-tumblr,ti-skype,ti-share,ti-share-alt,ti-rocket,ti-pinterest,ti-new-window,ti-microsoft,ti-list-ol,ti-linkedin,ti-layout-sidebar-2,ti-layout-grid4-alt,ti-layout-grid3-alt,ti-layout-grid2-alt,ti-layout-column4-alt,ti-layout-column3-alt,ti-layout-column2-alt,ti-instagram,ti-google,ti-github,ti-flickr,ti-facebook,ti-dropbox,ti-dribbble,ti-apple,ti-android,ti-save,ti-save-alt,ti-yahoo,ti-wordpress,ti-vimeo-alt,ti-twitter-alt,ti-tumblr-alt,ti-trello,ti-stack-overflow,ti-soundcloud,ti-sharethis,ti-sharethis-alt,ti-reddit,ti-pinterest-alt,ti-microsoft-alt,ti-linux,ti-jsfiddle,ti-joomla,ti-html5,ti-flickr-alt,ti-email,ti-drupal,ti-dropbox-alt,ti-css3,ti-rss,ti-rss-alt'
            )
        );

        return $register_icons;
    }

// Default Options 
//------------------------------------------------------------------------------

    function configuration($defaults) {

        $defaults['viewport'] = 1;

        $defaults['styles'] = 1;

        $defaults['styles_normalize'] = 1;

        $defaults['styles_widgets'] = 1;

        $defaults['styles_icons'] = 'dashicons';

        $defaults['styles_pscrollbar'] = 1;

        $defaults['styles_owlcarousel'] = 1;

        return $defaults;
    }

    function responsive($defaults) {

        $defaults['gutter'] = '30';
        $defaults['screen_sm_width'] = '768';
        $defaults['screen_md_width'] = '992';
        $defaults['screen_lg_width'] = '1200';

        return $defaults;
    }

    function css($defaults) {

        $defaults['css'] = '';

        return $defaults;
    }

    function themes_options($defaults) {

        // Layout
        // ---------------------------------------------------------------------   
        $defaults['layout'] = 'collapse';
        $defaults['layout_offcanvas_float'] = 'right';
        $defaults['layout_align'] = 'right';
        $defaults['layout_sticky'] = 0;
        $defaults['layout_sticky_offset'] = '90';
        $defaults['layout_divider'] = 'hide';
        $defaults['layout_caret'] = 'show';
        $defaults['layout_trigger'] = 'hoverintent';
        $defaults['layout_current'] = 0;
        $defaults['layout_classes'] = '';
        $defaults['layout_breakpoint'] = '768';
        $defaults['layout_width'] = 0;
        $defaults['layout_width_inner'] = 0;
        $defaults['layout_width_inner_selector'] = '';
        $defaults['layout_hover_effect'] = '';
        $defaults['layout_dropdown_maxheight'] = 1;
        $defaults['layout_animation'] = 'quadmenu_btt';

        // Fonts
        // ---------------------------------------------------------------------
        $defaults['font'] = array(
            'font-family' => 'Verdana, Geneva, sans-serif',
            //'google' => true,
            'font-size' => '11',
            'font-style' => 'normal',
            'font-weight' => '400',
            'letter-spacing' => 'inherit',
        );

        $defaults['navbar_font'] = array(
            'font-family' => 'Verdana, Geneva, sans-serif',
            //'google' => true,
            'font-size' => '11',
            'font-weight' => '400',
            'font-style' => 'normal',
            'letter-spacing' => 'inherit',
        );

        $defaults['dropdown_font'] = array(
            'font-family' => 'Verdana, Geneva, sans-serif',
            //'google' => true,
            'font-size' => '11',
            'font-weight' => '400',
            'font-style' => 'normal',
            'letter-spacing' => 'inherit',
        );

        // Navbar
        // --------------------------------------------------------------------- 

        $defaults['navbar_logo'] = array(
            'url' => QUADMENU_URL . 'assets/frontend/images/logo.png'
        );
        $defaults['navbar_height'] = '60';
        $defaults['navbar_width'] = '260';
        $defaults['navbar_background'] = 'color';
        $defaults['navbar_background_color'] = '#333333';
        $defaults['navbar_background_to'] = '#000000';

        $defaults['navbar_background_deg'] = '17';

        $defaults['navbar_divider'] = $defaults['navbar_sharp'] = 'rgba(255,255,255,0.5)';

        $defaults['navbar_toggle_open'] = '#ffffff';
        $defaults['navbar_toggle_close'] = '#fb88dd';

        $defaults['navbar_mobile_border'] = 'rgba(255,255,255,0.1)';

        $defaults['navbar_text'] = '#aaaaaa';

        $defaults['navbar_logo_bg'] = 'transparent';

        $defaults['navbar_logo_height'] = '25';
        $defaults['navbar_link'] = '#f1f1f1';
        $defaults['navbar_link_hover'] = '#ffffff';
        $defaults['navbar_link_bg'] = 'transparent';
        $defaults['navbar_link_bg_hover'] = '#111111';
        $defaults['navbar_link_hover_effect'] = '#fb88dd';
        $defaults['navbar_link_margin'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
        $defaults['navbar_link_radius'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
        $defaults['navbar_link_transform'] = 'uppercase';
        $defaults['navbar_link_icon'] = '#eeeeee';
        $defaults['navbar_link_icon_hover'] = '#ffffff';
        $defaults['navbar_link_subtitle'] = '#eeeeee';
        $defaults['navbar_link_subtitle_hover'] = '#ffffff';
        $defaults['navbar_button'] = '#ffffff';
        $defaults['navbar_button_bg'] = '#fb88dd';
        $defaults['navbar_button_hover'] = '#ffffff';
        $defaults['navbar_button_bg_hover'] = '#383838';
        $defaults['navbar_badge'] = '#fb88dd';
        $defaults['navbar_badge_color'] = '#ffffff';
        $defaults['sticky_height'] = '60';
        $defaults['sticky_background'] = 'rgba(0,0,0,0.5)';
        $defaults['sticky_logo_height'] = '25';
        $defaults['navbar_scrollbar'] = '#fb88dd';
        $defaults['navbar_scrollbar_rail'] = '#ffffff';

        // Mobile
        // ---------------------------------------------------------------------

        $defaults['mobile_shadow'] = 'show';

        // Dropdown
        // ---------------------------------------------------------------------
        $defaults['dropdown_shadow'] = 'show';
        $defaults['dropdown_margin'] = 0;
        $defaults['dropdown_radius'] = array(
            'border-top' => '0',
            'border-right' => '0',
            'border-left' => '0',
            'border-bottom' => '0',
        );
        $defaults['dropdown_border'] = array(
            'border-top' => '0',
            'border-right' => '0',
            'border-left' => '0',
            'border-bottom' => '0',
            'border-color' => '#000000'
        );
        $defaults['dropdown_background'] = '#ffffff';
        $defaults['dropdown_scrollbar'] = '#fb88dd';
        $defaults['dropdown_scrollbar_rail'] = '#ffffff';
        $defaults['dropdown_title'] = '#444444';
        $defaults['dropdown_title_border'] = array('border-all' => '1', 'border-top' => '1', 'border-color' => '#fb88dd', 'border-style' => 'solid');
        $defaults['dropdown_link'] = '#444444';
        $defaults['dropdown_link_hover'] = '#333333';
        $defaults['dropdown_link_bg_hover'] = '#f4f4f4';
        $defaults['dropdown_link_border'] = array('border-all' => '1', 'border-top' => '1', 'border-color' => '#f4f4f4', 'border-style' => 'solid');
        $defaults['dropdown_link_transform'] = 'none';
        $defaults['dropdown_link_icon'] = '#fb88dd';
        $defaults['dropdown_link_icon_hover'] = '#a9a9a9';
        $defaults['dropdown_link_subtitle'] = '#a0a0a0';
        $defaults['dropdown_link_subtitle_hover'] = '#cccccc';
        $defaults['dropdown_button'] = '#ffffff';
        $defaults['dropdown_button_bg'] = '#fb88dd';
        $defaults['dropdown_button_hover'] = '#ffffff';
        $defaults['dropdown_button_bg_hover'] = '#000000';
        $defaults['dropdown_button_radius'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
        $defaults['dropdown_tab_bg'] = 'rgba(0,0,0,0.05)';
        $defaults['dropdown_tab_bg_hover'] = 'rgba(0,0,0,0.1)';

        return $defaults;
    }

    function locations_options($defaults) {

        $defaults['integration'] = 0;
        $defaults['unwrap'] = 1;
        $defaults['theme'] = 'default_theme';

        return $defaults;
    }

}

new QuadMenu_Configuration();
