<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ReduxFramework_animation')) {

    class ReduxFramework_animation {

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

            // No errors please
            $defaults = array(
                'speed' => false,
                'action' => false,
                'options' => '',
                'mode' => array(
                    'speed' => false,
                    'action' => false,
                ),
            );

            $this->field = wp_parse_args($this->field, $defaults);

            $defaults = array(
                'speed' => '',
                'action' => '',
                'options' => ''
            );

            $this->value = wp_parse_args($this->value, $defaults);
        }

        function render() {

            echo '<fieldset id="' . $this->field['id'] . '" class="redux-animation-container" data-id="' . $this->field['id'] . '">';

            if (isset($this->field['select2'])) {

                $select2_params = json_encode($this->field['select2']);

                $select2_params = htmlspecialchars($select2_params, ENT_QUOTES);

                echo '<input type="hidden" class="select2_params" value="' . $select2_params . '">';
            }

            if (isset($this->field['options']) && is_array($this->field['options'])) {

                echo '<input type="hidden" class="field-options" value="' . $this->value['options'] . '">';

                echo '<div class="select_wrapper animation-options" original-title="' . __('options', 'quadmenu') . '">';

                echo '<select data-id="' . $this->field['id'] . '" data-placeholder="' . __('options', 'quadmenu') . '" class="redux-animation redux-animation-options select ' . $this->field['class'] . '" original-title="' . __('options', 'quadmenu') . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[options]' . '">';

                if (isset($this->field['options']) && is_array($this->field['options'])) {

                    foreach ($this->field['options'] as $animation => $name) {
                        echo '<option value="' . $animation . '" ' . selected($this->value['options'], $animation, false) . '>' . $name . '</option>';
                    }
                }

                echo '</select>';
                echo '</div>';
            };

            if (isset($this->field['action']) && is_array($this->field['action'])) {

                echo '<input type="hidden" class="field-action" value="' . $this->value['action'] . '">';

                echo '<div class="select_wrapper animation-action" original-title="' . __('action', 'quadmenu') . '">';

                echo '<select data-id="' . $this->field['id'] . '" data-placeholder="' . __('action', 'quadmenu') . '" class="redux-animation redux-animation-action select ' . $this->field['class'] . '" original-title="' . __('action', 'quadmenu') . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[height]' . '">';

                if (isset($this->field['action']) && is_array($this->field['action'])) {

                    foreach ($this->field['action'] as $action => $name) {
                        echo '<option value="' . $action . '" ' . selected($this->value['action'], $action, false) . '>' . $name . '</option>';
                    }
                }

                echo '</select>';
                echo '</div>';
            };

            if (isset($this->field['speed']) && is_array($this->field['speed'])) {

                echo '<input type="hidden" class="field-speed" value="' . $this->value['speed'] . '">';

                echo '<div class="select_wrapper animation-speed" original-title="' . __('speed', 'quadmenu') . '">';

                echo '<select data-id="' . $this->field['id'] . '" data-placeholder="' . __('speed', 'quadmenu') . '" class="redux-animation redux-animation-speed select ' . $this->field['class'] . '" original-title="' . __('speed', 'quadmenu') . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[width]' . '">';

                if (isset($this->field['speed']) && is_array($this->field['speed'])) {

                    foreach ($this->field['speed'] as $speed => $name) {
                        echo '<option value="' . $speed . '" ' . selected($this->value['speed'], $speed, false) . '>' . $name . '</option>';
                    }
                }

                echo '</select>';
                echo '</div>';
            };

            echo "</fieldset>";
        }

        function enqueue() {
            wp_enqueue_style('select2-css');

            wp_enqueue_style(
                    'redux-field-animation-css', $this->_extension_url . 'field_animation' . Redux_Functions::isMin() . '.css', array(), time(), 'all'
            );

            wp_enqueue_script(
                    'redux-field-animation-js', $this->_extension_url . 'field_animation' . Redux_Functions::isMin() . '.js', array('jquery', 'select2-js', 'redux-js'), time(), true
            );
        }

        public function output() {

            // if field options has a value and IS an array, then evaluate as needed.
            if (isset($this->field['options']) && !is_array($this->field['options'])) {

                //if options fields has a value but options value does not then make options value the field value
                if (isset($this->field['options']) && !isset($this->value['options']) || $this->field['options'] == false) {
                    $this->value['options'] = $this->field['options'];

                    // If options field does NOT have a value and options value does NOT have a value, set both to blank (default?)
                } else if (!isset($this->field['options']) && !isset($this->value['options'])) {
                    $this->field['options'] = 'px';
                    $this->value['options'] = 'px';

                    // If options field has NO value but options value does, then set unit field to value field
                } else if (!isset($this->field['options']) && isset($this->value['options'])) {
                    $this->field['options'] = $this->value['options'];

                    // if unit value is set and unit value doesn't equal unit field (coz who knows why)
                    // then set unit value to unit field
                } elseif (isset($this->value['options']) && $this->value['options'] !== $this->field['options']) {
                    $this->value['options'] = $this->field['options'];
                }

                // do stuff based on unit field NOT set as an array
            } elseif (isset($this->field['options']) && is_array($this->field['options'])) {
                // nothing to do here, but I'm leaving the construct just in case I have to debug this again.
            }

            $options = isset($this->value['options']) ? $this->value['options'] : "";

            if (!is_array($this->field['mode'])) {
                $height = isset($this->field['mode']) && !empty($this->field['mode']) ? $this->field['mode'] : 'action';
                $width = isset($this->field['mode']) && !empty($this->field['mode']) ? $this->field['mode'] : 'speed';
            } else {
                $height = $this->field['mode']['action'] != false ? $this->field['mode']['action'] : 'action';
                $width = $this->field['mode']['speed'] != false ? $this->field['mode']['speed'] : 'speed';
            }

            $cleanValue = array(
                $height => isset($this->value['action']) ? filter_var($this->value['action'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : '',
                $width => isset($this->value['speed']) ? filter_var($this->value['speed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : '',
            );

            $style = "";

            foreach ($cleanValue as $key => $value) {
                // Output if it's a numeric entry
                if (isset($value) && is_numeric($value)) {
                    $style .= $key . ':' . $value . $options . ';';
                }
            }

            if (!empty($style)) {
                if (!empty($this->field['output']) && is_array($this->field['output'])) {
                    $keys = implode(",", $this->field['output']);
                    $this->parent->outputCSS .= $keys . "{" . $style . '}';
                }

                if (!empty($this->field['compiler']) && is_array($this->field['compiler'])) {
                    $keys = implode(",", $this->field['compiler']);
                    $this->parent->compilerCSS .= $keys . "{" . $style . '}';
                }
            }
        }

    }

}