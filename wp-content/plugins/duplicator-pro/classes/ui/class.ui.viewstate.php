<?php

/**
 * Gets the view state of UI elements to remember its viewable state
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package DUP_PRO
 * @subpackage classes/ui
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.3.0
 *
 */
class DUP_PRO_UI_ViewState
{
    /**
     * The key used in the wp_options table
     */
    private static $optionsTableKey = 'duplicator_pro_ui_view_state';

    /**
     * Save the view state of UI elements
     *
     * @param string $key A unique key to define the ui element
     * @param string $value A generic value to use for the view state
     *
     * @return bool Returns true if the value was succesfully saved
     */
    public static function save($key, $value)
    {
        $view_state       = array();
        $view_state       = get_option(self::$optionsTableKey);
        $view_state[$key] = $value;
        $success          = update_option(self::$optionsTableKey, $view_state);
        return $success;
    }

    /**
     * Saves the state of a UI element via post params
     *
     * @return json result string
     *
     * <code>
     * //JavaScript Ajax Request
     * DupPro.UI.SaveViewStateByPost('dup-pack-archive-panel', 1);
     *
     * //Call PHP Code
     * $view_state       = DUP_PRO_UI_ViewState::getValue('dup-pack-archive-panel');
     * $ui_css_archive   = ($view_state == 1)   ? 'display:block' : 'display:none';
     * </code>
     *
     * @todo: Move this method to a controller see dlite (ctrl)
     */
    public static function saveByPost()
    {
        DUP_PRO_U::hasCapability('read');

        $post    = stripslashes_deep($_POST);
        $key     = esc_html($post['key']);
        $value   = esc_html($post['value']);
        $success = self::save($key, $value);

        //Show Results as JSON
        $json                   = array();
        $json['key']            = $key;
        $json['value']          = $value;
        $json['update-success'] = $success;
        die(json_encode($json));
    }

    /**
     * 	Gets all the values from the settings array
     *
     *  @return array Returns and array of all the values stored in the settings array
     */
    public static function getArray()
    {
        return get_option(self::$optionsTableKey);
    }

    /**
     * Return the value of the of view state item
     *
     * @param type $searchKey The key to search on
     * 
     * @return string Returns the value of the key searched or null if key is not found
     */
    public static function getValue($searchKey)
    {
        $view_state = get_option(self::$optionsTableKey);
        if (is_array($view_state)) {
            foreach ($view_state as $key => $value) {
                if ($key == $searchKey) {
                    return $value;
                }
            }
        }
        return null;
    }
}