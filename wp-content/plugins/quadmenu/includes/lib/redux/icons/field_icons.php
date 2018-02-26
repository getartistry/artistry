<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ReduxFramework_icons')) {

    add_action('admin_footer', array('ReduxFramework_icons', 'modal'));

    class ReduxFramework_icons {

        public $_extension_url;
        public $_extension_dir;

        function __construct($field = array(), $value = '', $parent) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if (empty(self::$_extension_dir)) {
                $this->_extension_dir = trailingslashit(str_replace('\\', '/', dirname(__FILE__)));
                $this->_extension_url = site_url(str_replace(trailingslashit(str_replace('\\', '/', ABSPATH)), '', $this->_extension_dir));
            }
        }

        static public function modal($icons = '') {
            ?>
            <!-- Modal Icon -->
            <div id="modal_icons" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_iconsLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="quadmenu-setting-icon">
                                <div class="quadmenu-icons-search">
                                    <span class="button-icon button-secondary" class="button"><i class="<?php //echo esc_html($value);                       ?>"></i><?php echo _QuadMenu()->selected_icons()->name; ?></span>        
                                    <input type="hidden" class="menu-item-icon" value="<?php //echo esc_html($value);                       ?>"/>
                                    <input type="search" value="" placeholder="<?php _e('Search icon', 'quadmenu') ?>"/>
                                </div>
                                <div class="quadmenu-icons-scroll">
                                    <?php foreach (explode(',', _QuadMenu()->selected_icons()->iconmap) as $icon) : ?>
                                        <a style="display: block;" class="icon _<?php echo esc_attr(str_replace(' ', '_', trim($icon))); ?>"><i class="<?php echo esc_attr($icon); ?>"></i></a>
                                    <?php endforeach; ?>
                                    <div class="clearfix"></div>
                                </div>                
                            </div>              
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="button button-secondary" data-dismiss="modal"><?php _e('Close', 'quadmenu') ?></button>
                            <button type="button" class="button button-primary save"><?php _e('Save', 'quadmenu') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Modal Icon -->
            <?php
        }

        public function render() {

            $defaults = array(
                'show' => array(
                    'title' => true,
                    'icon' => true,
                    //'key' => false,
                    'description' => true,
                    'url' => true,
                    'upload' => true,
                ),
                'content_title' => __('Slide', 'redux-framework')
            );

            $this->field = wp_parse_args($this->field, $defaults);

            echo '<div class="redux-icons-accordion" data-new-content-title="' . esc_attr(sprintf(__('New %s', 'redux-framework'), $this->field['content_title'])) . '">';

            $x = 0;

            $multi = ( isset($this->field['multi']) && $this->field['multi'] ) ? ' multiple="multiple"' : "";

            if (isset($this->value) && is_array($this->value) && !empty($this->value)) {

                $icons = $this->value;

                foreach ($icons as $slide) {

                    if (empty($slide)) {
                        continue;
                    }

                    $defaults = array(
                        'title' => '',
                        'icon' => '',
                        'description' => '',
                        'sort' => '',
                        'url' => '',
                        'image' => '',
                        'thumb' => '',
                        'attachment_id' => '',
                        'height' => '',
                        'width' => '',
                        'upload' => '',
                        'select' => array(),
                    );
                    $slide = wp_parse_args($slide, $defaults);

                    if (empty($slide['thumb']) && !empty($slide['attachment_id'])) {
                        $img = wp_get_attachment_image_src($slide['attachment_id'], 'full');
                        $slide['image'] = $img[0];
                        $slide['width'] = $img[1];
                        $slide['height'] = $img[2];
                    }

                    echo '<div class="redux-icons-accordion-group"><fieldset class="redux-field" data-id="' . $this->field['id'] . '"><h3><i id="' . $this->field['id'] . '-icon_' . $x . '_replace" class="' . $slide['icon'] . '"></i><span class="redux-icons-header">' . $slide['title'] . '</span></h3><div>';

                    if ($this->field['show']['upload']) {

                        $hide = '';

                        if (empty($slide['image'])) {
                            $hide = ' hidden';
                        }

                        echo '<div class="screenshot' . $hide . '">';
                        //echo '<a class="of-uploaded-image" href="' . $slide['image'] . '">';
                        echo '<img class="redux-option-image" id="image_image_id_' . $x . '" src="' . $slide['thumb'] . '" alt="" target="_blank" rel="external" />';
                        // echo '</a>';
                        echo '</div>';

                        echo '<div class="redux_icons_add_remove">';

                        echo '<span class="button media_upload_button" id="add_' . $x . '">' . __('Upload', 'redux-framework') . '</span>';

                        $hide = '';

                        if (empty($slide['image']) || $slide['image'] == '') {
                            $hide = ' hide';
                        }

                        echo '<span class="button remove-image' . $hide . '" id="reset_' . $x . '" rel="' . $slide['attachment_id'] . '">' . __('Remove', 'redux-framework') . '</span>';

                        echo '</div>' . "\n";
                    }

                    echo '<ul id="' . $this->field['id'] . '-ul" class="redux-icons-list">';

                    if ($this->field['show']['title']) {
                        $title_type = "text";
                    } else {
                        $title_type = "hidden";
                    }

                    $placeholder = ( isset($this->field['placeholder']['title']) ) ? esc_attr($this->field['placeholder']['title']) : __('Title', 'redux-framework');

                    echo '<li><input type="' . $title_type . '" id="' . $this->field['id'] . '-title_' . $x . '" name="' . $this->field['name'] . '[' . $x . '][title]' . $this->field['name_suffix'] . '" value="' . esc_attr($slide['title']) . '" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                    if ($this->field['show']['icon']) {
                        $placeholder = ( isset($this->field['placeholder']['icon']) ) ? esc_attr($this->field['placeholder']['icon']) : __('Icon', 'quadmenu');
                        echo '<li>';
                        echo '<a class="redux-icons-add-icon" name="' . $this->field['name'] . '[' . $x . '][icon]' . $this->field['name_suffix'] . '" href="#" data-quadmenu="modal" data-target="#modal_icons" data-backdrop="true" id="' . $this->field['id'] . '-icon_' . $x . '" value="' . esc_attr($slide['icon']) . '">' . __('+ Add Icon', 'quadmenu') . '</a>';
                        echo '<input type="text" name="' . $this->field['name'] . '[' . $x . '][icon]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-icon_' . $x . '" placeholder="' . $placeholder . '" value="' . esc_attr($slide['icon']) . '" placeholder="' . $placeholder . '" class="large-text slide-icon"></input>';
                        echo '</li>';
                    }

                    if ($this->field['show']['description']) {
                        $placeholder = ( isset($this->field['placeholder']['description']) ) ? esc_attr($this->field['placeholder']['description']) : __('Description', 'redux-framework');
                        echo '<li><textarea name="' . $this->field['name'] . '[' . $x . '][description]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-description_' . $x . '" placeholder="' . $placeholder . '" class="large-text" rows="6">' . esc_attr($slide['description']) . '</textarea></li>';
                    }

                    $placeholder = ( isset($this->field['placeholder']['url']) ) ? esc_attr($this->field['placeholder']['url']) : __('URL', 'redux-framework');
                    if ($this->field['show']['url']) {
                        $url_type = "text";
                    } else {
                        $url_type = "hidden";
                    }

                    echo '<li><input type="' . $url_type . '" id="' . $this->field['id'] . '-url_' . $x . '" name="' . $this->field['name'] . '[' . $x . '][url]' . $this->field['name_suffix'] . '" value="' . esc_attr($slide['url']) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';
                    echo '<li><input type="hidden" class="slide-sort" name="' . $this->field['name'] . '[' . $x . '][sort]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-sort_' . $x . '" value="' . $slide['sort'] . '" />';
                    echo '<li><input type="hidden" class="upload-id" name="' . $this->field['name'] . '[' . $x . '][attachment_id]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-image_id_' . $x . '" value="' . $slide['attachment_id'] . '" />';
                    echo '<input type="hidden" class="upload-thumbnail" name="' . $this->field['name'] . '[' . $x . '][thumb]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-thumb_url_' . $x . '" value="' . $slide['thumb'] . '" readonly="readonly" />';
                    echo '<input type="hidden" class="upload" name="' . $this->field['name'] . '[' . $x . '][image]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-image_url_' . $x . '" value="' . $slide['image'] . '" readonly="readonly" />';
                    echo '<input type="hidden" class="upload-height" name="' . $this->field['name'] . '[' . $x . '][height]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-image_height_' . $x . '" value="' . $slide['height'] . '" />';
                    echo '<input type="hidden" class="upload-width" name="' . $this->field['name'] . '[' . $x . '][width]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-image_width_' . $x . '" value="' . $slide['width'] . '" /></li>';
                    //echo '<input type="text" name="' . $this->field['name'] . '[' . $x . '][key]' . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-key_' . $x . '" value="' . esc_attr($slide['key'] ? $slide['key'] : $this->field['id'] . '-key_' . $x ) . '">';
                    echo '<li><a href="javascript:void(0);" class="button deletion redux-icons-remove">' . __('Delete', 'redux-framework') . '</a></li>';
                    echo '</ul></div></fieldset></div>';
                    $x ++;
                }
            }

            
            echo '</div>';

            echo '<a href="javascript:void(0);" class="redux-icons-accordion-group redux-icons-add " rel-id="' . $this->field['id'] . '-ul" rel-name="' . $this->field['name'] . '[title][]' . $this->field['name_suffix'] . '"><i class="dashicons dashicons-plus"></i></a>';
        }

        public function enqueue() {
            if (function_exists('wp_enqueue_media')) {
                wp_enqueue_media();
            } else {
                wp_enqueue_script('media-upload');
            }

            if ($this->parent->args['dev_mode']) {
                wp_enqueue_style('redux-field-media-css');
            }

            wp_enqueue_style(
                    'redux-field-icons-css', $this->_extension_url . 'field_icons' . Redux_Functions::isMin() . '.css', array(), time(), 'all'
            );

            wp_enqueue_script(
                    'redux-field-media-js', ReduxFramework::$_url . 'assets/js/media/media' . Redux_Functions::isMin() . '.js', array('jquery', 'redux-js'), time(), true
            );

            wp_enqueue_script(
                    'redux-field-icons-js', $this->_extension_url . 'field_icons' . Redux_Functions::isMin() . '.js', array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-sortable', 'redux-field-media-js'), time(), true
            );
        }

    }

}