<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Form Builder Class
 *
 * @class WooShip_Form_Builder
 * @package WooShip
 * @author RightPress
 */
if (!class_exists('WooShip_Form_Builder')) {

class WooShip_Form_Builder
{

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Render text field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function text($params, $context = 'admin')
    {
        self::input('text', $params, array('value', 'maxlength', 'placeholder'), $context);
    }

    /**
     * Render hidden field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function hidden($params, $context = 'admin')
    {
        self::input('hidden', $params, array('value'), $context);
    }

    /**
     * Render text area field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function textarea($params, $context = 'admin')
    {
        // Get attributes
        $attributes = self::attributes($params, array('value', 'maxlength', 'placeholder'), 'textarea', $context);

        // Get value
        $value = !empty($params['value']) ? $params['value'] : '';

        // Generate field html
        $field_html = '<textarea ' . $attributes . '>' . $value . '</textarea>';

        // Render field
        self::output($params, $field_html, $context, 'textarea');
    }

    /**
     * Render password field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function password($params, $context = 'admin')
    {
        $params['autocomplete'] = 'off';
        self::input('password', $params, array('value', 'maxlength', 'placeholder'), $context);
    }

    /**
     * Render email field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function email($params, $context = 'admin')
    {
        // Display as regular text field, will do our own validation
        self::input('text', $params, array('value', 'maxlength', 'placeholder'), $context);
    }

    /**
     * Render number field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function number($params, $context = 'admin')
    {
        // Display as regular text field, will do our own validation
        self::input('text', $params, array('value', 'maxlength', 'placeholder'), $context);
    }

    /**
     * Render date field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function date($params, $context = 'admin')
    {
        // Disable autocomplete
        $params['autocomplete'] = 'off';

        // Display as regular text field, will initialize jQuery UI Datepicker based on object's class
        self::input('text', $params, array('value'), $context, true);
    }

    /**
     * Render select field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @param bool $is_multiple
     * @param bool $is_grouped
     * @return void
     */
    public static function select($params, $context = 'admin', $is_multiple = false, $is_grouped = false)
    {
        // Get attributes
        $attributes = self::attributes($params, array(), 'select', $context);

        // Get options
        $options = self::options($params, $is_grouped);

        // Check if it's multiselect
        $multiple_html = $is_multiple ? 'multiple' : '';

        // Generate field html
        $field_html = '<select ' . $multiple_html . ' ' . $attributes . '>' . $options . '</select>';

        // Render field
        $field_type = $is_multiple ? 'multiselect' : ($is_grouped ? 'grouped_select' : 'select');
        self::output($params, $field_html, $context, $field_type);
    }

    /**
     * Render grouped select field (for internal use only)
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function grouped_select($params, $context = 'admin')
    {
        self::select($params, $context, false, true);
    }

    /**
     * Render multiselect field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function multiselect($params, $context = 'admin')
    {
        self::select($params, $context, true);
    }

    /**
     * Render checkbox field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function checkbox($params, $context = 'admin')
    {
        self::checkbox_or_radio('checkbox', $params, $context);
    }

    /**
     * Render radio field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function radio($params, $context = 'admin')
    {
        self::checkbox_or_radio('radio', $params, $context);
    }

    /**
     * Render checkbox or radio field
     *
     * @access public
     * @param string $type
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function checkbox_or_radio($type, $params, $context = 'admin')
    {
        $field_html = '';

        // Single field?
        if (empty($params['options'])) {
            $attributes = self::attributes($params, array('value', 'checked'), $type, $context);
            $field_html .= '<input type="' . $type . '" ' . $attributes . '>';
        }

        // Set of fields - iterate over options and generate field for each option
        else {

            // Open list
            $field_html .= '<ul>';

            // Iterate over field options and display as individual items
            foreach ($params['options'] as $key => $label) {

                // Customize params
                $custom_params = $params;
                $custom_params['id'] = $custom_params['id'] . '_' . $key;

                // Get attributes
                $attributes = self::attributes($custom_params, array(), $type, $context);

                // Check if this item needs to be checked
                if (isset($params['value'])) {
                    $values = (array) $params['value'];
                    $checked = in_array($key, $values) ? 'checked="checked"' : '';
                }
                else {
                    $checked = (isset($params['checked']) && in_array($key, $params['checked']) ? 'checked="checked"' : '');
                }

                // Generate HTML
                $field_html .= '<li><input type="' . $type . '" value="' . $key . '" ' . $checked . ' ' . $attributes . '>' . (!empty($label) ? ' ' . $label : '') . '</li>';
            }

            // Close list
            $field_html .= '</ul>';
        }

        // Render field
        self::output($params, $field_html, $context, $type);
    }

    /**
     * Render file field
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return void
     */
    public static function file($params, $context = 'admin')
    {
        self::input('file', $params, array('accept'), $context);
    }

    /**
     * Render generic input field
     *
     * @access public
     * @param string $type
     * @param array $params
     * @param array $custom_attributes
     * @param string $context
     * @param bool $is_date
     * @return void
     */
    private static function input($type, $params, $custom_attributes = array(), $context = 'admin', $is_date = false)
    {
        // Get attributes
        $attributes = self::attributes($params, $custom_attributes, $type, $context);

        // Generate field html
        $field_html = '<input type="' . $type . '" ' . $attributes . '>';

        // Render field
        self::output($params, $field_html, $context, $type, $is_date);
    }

    /**
     * Render attributes
     *
     * @access public
     * @param array $params
     * @param array $custom
     * @param string $type
     * @param string $context
     * @return void
     */
    private static function attributes($params, $custom = array(), $type = 'text', $context = 'admin')
    {
        $html = '';

        // Get full list of attributes
        $attributes = array_merge(array('type', 'name', 'id', 'class', 'autocomplete', 'style'), $custom);

        // Additional attributes for admin ui
        if (is_admin()) {
            $attributes[] = 'required';
        }

        // Extract attributes and append to html string
        foreach ($attributes as $attribute) {
            if (!empty($params[$attribute])) {
                $html .= $attribute . '="' . $params[$attribute] . '" ';
            }
        }

        return $html;
    }

    /**
     * Get options for select field
     *
     * @access public
     * @param array $params
     * @param bool $is_grouped
     * @return string
     */
    private static function options($params, $is_grouped = false)
    {
        $html = '';
        $selected = array();

        // Get selected option(s)
        if (isset($params['value'])) {
            $selected = (array) $params['value'];
        }
        else if (!empty($params['selected'])) {
            $selected = (array) $params['selected'];
        }

        // Extract options and append to html string
        if (!empty($params['options']) && is_array($params['options'])) {

            // Fix array depth if options are not grouped
            if (!$is_grouped) {
                $params['options'] = array(
                    'not_grouped' => array(
                        'options' => $params['options'],
                    ),
                );
            }

            // Iterate over option groups
            foreach ($params['options'] as $group_key => $group) {

                // Option group start
                if ($is_grouped) {
                    $html .= '<optgroup label="' . $group['label'] . '">';
                }

                // Iterate over options
                foreach ($group['options'] as $option_key => $option) {

                    // Get option key
                    $option_key = ($is_grouped ? $group_key . '_' . $option_key : $option_key);

                    // Check if option is selected
                    $selected_html = in_array($option_key, $selected) ? 'selected="selected"' : '';

                    // Format option html
                    $html .= '<option value="' . $option_key . '" ' . $selected_html . '>' . $option . '</option>';
                }

                // Option group end
                if ($is_grouped) {
                    $html .= '</optgroup>';
                }
            }
        }

        return $html;
    }

    /**
     * Render field label
     *
     * @access public
     * @param array $params
     * @return string
     */
    private static function label($params)
    {
        echo self::label_html($params);
    }

    /**
     * Get field label html
     *
     * @access public
     * @param array $params
     * @return string
     */
    private static function label_html($params)
    {
        // Check if label needs to be displayed
        if (!empty($params['id']) && !empty($params['label'])) {

            // Field is required
            $required_html = !empty($params['required']) ? ' <abbr class="required" title="' . __('required', 'wooship') . '">*</abbr>' : '';

            // Return label html
            return '<label for="' . $params['id'] . '">' . $params['label'] . $required_html . '</label>';
        }

        return '';
    }

    /**
     * Render field description
     *
     * @access public
     * @param array $params
     * @param string $context
     * @return string
     */
    private static function description($params, $context)
    {
        echo self::description_html($params);
    }

    /**
     * Get field description html
     *
     * @access public
     * @param array $params
     * @return string
     */
    private static function description_html($params)
    {
        if (!empty($params['description'])) {
            return '<small>' . $params['description'] . '</small>';
        }

        return '';
    }

    /**
     * Output field based on context
     *
     * @access public
     * @param array $params
     * @param string $field_html
     * @param string $context
     * @param string $type
     * @param bool $is_date
     * @return void
     */
    private static function output($params, $field_html, $context, $type, $is_date = false)
    {
        // Open container
        self::output_begin($context, $type, $is_date);

        // Print label
        self::label($params);

        // Print field
        echo $field_html;

        // Print description after field
        self::description($params, $context, 'after');

        // Close container
        self::output_end($context, $type);
    }

    /**
     * Output container begin
     *
     * @access public
     * @param string $context
     * @param string $type
     * @param bool $is_date
     * @return void
     */
    private static function output_begin($context, $type, $is_date = false)
    {
        $date_class = $is_date ? 'wooship_date_container' : '';

        // Product Properties, Order Fields
        if (in_array($context, array())) {
            echo '<div class="' . $date_class . '">';
        }
    }

    /**
     * Output container end
     *
     * @access public
     * @param string $context
     * @param string $type
     * @return void
     */
    private static function output_end($context, $type)
    {
        if (in_array($context, array())) {
            echo '</div>';
        }
    }

    /**
     * Check if field type has options
     *
     * @access public
     * @param string $type
     * @return bool
     */
    public static function has_options($type)
    {
        return in_array($type, array('select', 'multiselect', 'checkbox', 'radio'));
    }

}
}
