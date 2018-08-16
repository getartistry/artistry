<?php

if (!defined('ABSPATH')) {
    exit;
}
// Don't duplicate me!
if (!class_exists('ReduxFramework_rgba')) {

    class ReduxFramework_rgba {

        function __construct($field = array(), $value = '', $parent) {

            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if (isset($this->value['color']) && isset($this->value['alpha'])) {
                $this->value = Redux_Helpers::hex2rgba($this->value['color'], $this->value['alpha']);
            }

            if (isset($this->field['default']['color']) && isset($this->field['default']['alpha'])) {
                $this->field['default'] = Redux_Helpers::hex2rgba($this->field['default']['color'], $this->field['default']['alpha']);
            }

            if (empty(self::$_extension_dir)) {
                $this->_extension_dir = trailingslashit(str_replace('\\', '/', dirname(__FILE__)));
                $this->_extension_url = site_url(str_replace(trailingslashit(str_replace('\\', '/', ABSPATH)), '', $this->_extension_dir));
            }
        }

        public function render() {

            echo '<input data-alpha="true" data-id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" id="' . $this->field['id'] . '-color" class="redux-color redux-rgba redux-rgba-init ' . $this->field['class'] . '"  type="text" value="' . $this->value . '" data-oldcolor=""  data-default-color="' . ( isset($this->field['default']) ? $this->field['default'] : "" ) . '" />';
            echo '<input type="hidden" class="redux-saved-color" id="' . $this->field['id'] . '-saved-color' . '" value="">';

            if (!isset($this->field['transparent']) || $this->field['transparent'] !== false) {

                $tChecked = "";

                if ($this->value == "transparent") {
                    $tChecked = ' checked="checked"';
                }

                echo '<label for="' . $this->field['id'] . '-transparency" class="color-transparency-check"><input type="checkbox" class="checkbox color-transparency ' . $this->field['class'] . '" id="' . $this->field['id'] . '-transparency" data-id="' . $this->field['id'] . '-color" value="1"' . $tChecked . '> ' . __('Transparent', 'redux-framework') . '</label>';
            }
        }

        public function enqueue() {

            wp_enqueue_style('wp-color-picker');

            wp_register_script('wp-color-picker-alpha', $this->_extension_url . 'wp-color-picker-alpha' . Redux_Functions::isMin() . '.js', array('jquery', 'wp-color-picker'));

            wp_localize_script('wp-color-picker-alpha', 'et_pb_color_picker_strings', array(
                'legacy_pick' => esc_html__('Select', 'quadmenu'),
                'legacy_current' => esc_html__('Current Color', 'quadmenu'),
            ));

            wp_enqueue_script(
                    'redux-field-rgba-js', $this->_extension_url . 'field_rgba' . Redux_Functions::isMin() . '.js', array('jquery', 'wp-color-picker-alpha', 'redux-js'), time(), true
            );
        }

        public function output() {
            $style = '';

            if (!empty($this->value)) {
                $mode = ( isset($this->field['mode']) && !empty($this->field['mode']) ? $this->field['mode'] : 'color' );

                $style .= $mode . ':' . $this->value . ';';

                if (!empty($this->field['output']) && is_array($this->field['output'])) {
                    $css = Redux_Functions::parseCSS($this->field['output'], $style, $this->value);
                    $this->parent->outputCSS .= $css;
                }

                if (!empty($this->field['compiler']) && is_array($this->field['compiler'])) {
                    $css = Redux_Functions::parseCSS($this->field['compiler'], $style, $this->value);
                    $this->parent->compilerCSS .= $css;
                }
            }
        }

    }

}
    