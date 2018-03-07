<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WooShip shipping method conditions
 *
 * @class WooShip_Conditions
 * @package WooShip
 * @author RightPress
 */
if (!class_exists('WooShip_Conditions')) {

class WooShip_Conditions
{
    private static $conditions = array();
    private static $timeframes = array();

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Make sure some actions run after all classes are initiated
        add_action('init', array($this, 'on_init'));

        // Ajax handlers
        add_action('wp_ajax_wooship_load_multiselect_items', array($this, 'ajax_load_multiselect_items'));
    }

    /**
     * On init action
     *
     * @access public
     * @return void
     */
    public function on_init()
    {
        // Define conditions
        self::$conditions = array(

            // General
            'general' => array(
                'label'     => __('General', 'wooship'),
                'children'  => array(

                    // Subtotal
                    'subtotal' => array(
                        'label'         => __('Subtotal', 'wooship'),
                        'method'        => 'at_least_less_than',
                        'uses_fields'   => array('decimal'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Total weight
                    'weight' => array(
                        'label'         => __('Total weight', 'wooship'),
                        'method'        => 'at_least_less_than',
                        'uses_fields'   => array('decimal'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Sum of quantities
                    'sum_of_quantities' => array(
                        'label'         => __('Sum of quantities', 'wooship'),
                        'method'        => 'at_least_less_than',
                        'uses_fields'   => array('number'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Coupons applied
                    'coupons' => array(
                        'label'         => __('Coupons applied', 'wooship'),
                        'method'        => 'at_least_one_all_none',
                        'uses_fields'   => array('coupons'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Shipping class
                    'shipping_class' => array(
                        'label'         => __('Shipping class', 'wooship'),
                        'method'        => 'at_least_one_all_none',
                        'uses_fields'   => array('shipping_classes'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),
                ),
            ),

            // Products
            'products' => array(
                'label'     => __('Products', 'wooship'),
                'children'  => array(

                    // Products
                    'product' => array(
                        'label'         => __('Products', 'wooship'),
                        'method'        => 'at_least_one_all_none',
                        'uses_fields'   => array('products'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Product categories
                    'product_category' => array(
                        'label'         => __('Product categories', 'wooship'),
                        'method'        => 'at_least_one_all_none',
                        'uses_fields'   => array('product_categories'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Product attributes
                    'product_attribute' => array(
                        'label'         => __('Product attributes', 'wooship'),
                        'method'        => 'at_least_one_all_none',
                        'uses_fields'   => array('attributes'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Product tags
                    'product_tag' => array(
                        'label'         => __('Product tags', 'wooship'),
                        'method'        => 'at_least_one_all_none',
                        'uses_fields'   => array('tags'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),
                ),
            ),

            // Shipping Address
            'shipping' => array(
                'label'     => __('Shipping Address', 'wooship'),
                'children'  => array(

                    // Shipping zone
                    'shipping_zone' => array(
                        'label'         => __('Shipping zone', 'wooship'),
                        'method'        => 'in_list_not_in_list',
                        'uses_fields'   => array('shipping_zones'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Country
                    'country' => array(
                        'label'         => __('Country', 'wooship'),
                        'method'        => 'in_list_not_in_list',
                        'uses_fields'   => array('countries'),
                        'context'       => array('shipping_methods', 'additional_charges', 'shipping_zones'),
                    ),

                    // State
                    'state' => array(
                        'label'         => __('State', 'wooship'),
                        'method'        => 'in_list_not_in_list',
                        'uses_fields'   => array('states'),
                        'context'       => array('shipping_methods', 'additional_charges', 'shipping_zones'),
                    ),

                    // City
                    /* This one is ready to be used but there's a problem when used in combination with WooCommerce cart scripts -
                     * shipping methods are not updated when user inputs his City
                    'city' => array(
                        'label'         => __('City', 'wooship'),
                        'method'        => 'text_comparison',
                        'uses_fields'   => array('text'),
                        'context'       => array('shipping_methods', 'additional_charges', 'shipping_zones'),
                    ),*/

                    // Postcode
                    'postcode' => array(
                        'label'         => __('Postcode', 'wooship'),
                        'method'        => 'matches_does_not_match',
                        'uses_fields'   => array('text'),
                        'context'       => array('shipping_methods', 'additional_charges', 'shipping_zones'),
                    ),
                ),
            ),

            // Customer
            'customer' => array(
                'label'     => __('Customer', 'wooship'),
                'children'  => array(

                    // Is logged in
                    'is_logged_in' => array(
                        'label'         => __('Is logged in', 'wooship'),
                        'method'        => 'yes_no',
                        'uses_fields'   => array(),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Role
                    'role' => array(
                        'label'         => __('Role', 'wooship'),
                        'method'        => 'in_list_not_in_list',
                        'uses_fields'   => array('roles'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Capability
                    'capability' => array(
                        'label'         => __('Capability', 'wooship'),
                        'method'        => 'in_list_not_in_list',
                        'uses_fields'   => array('capabilities'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Specific customer
                    'customer' => array(
                        'label'         => __('Specific customer', 'wooship'),
                        'method'        => 'in_list_not_in_list',
                        'uses_fields'   => array('users'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Customer meta field
                    'customer_meta_field' => array(
                        'label'         => __('Customer meta field', 'wooship'),
                        'method'        => 'meta_field',
                        'uses_fields'   => array('meta_key', 'text'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),
                ),
            ),

            // Customer History
            'history' => array(
                'label'     => __('Customer History', 'wooship'),
                'children'  => array(

                    // Amount spent
                    'amount_spent' => array(
                        'label'         => __('Amount spent within', 'wooship'),
                        'method'        => 'at_least_less_than',
                        'uses_fields'   => array('timeframe_all_time', 'decimal'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Order count
                    'order_count' => array(
                        'label'         => __('Order count within', 'wooship'),
                        'method'        => 'at_least_less_than',
                        'uses_fields'   => array('timeframe_all_time', 'number'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Last order amount
                    'last_order_amount' => array(
                        'label'         => __('Last order amount', 'wooship'),
                        'method'        => 'at_least_less_than',
                        'uses_fields'   => array('decimal'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),

                    // Last order
                    'last_order_time' => array(
                        'label'         => __('Last order', 'wooship'),
                        'method'        => 'within_past_earlier_than',
                        'uses_fields'   => array('timeframe'),
                        'context'       => array('shipping_methods', 'additional_charges'),
                    ),
                ),
            ),
        );

        // Generate timeframes
        for ($i = 1; $i <= 24; $i++) {
            self::$timeframes[$i . '_month'] = array(
                'label' => $i . ' ' . _n('month', 'months', $i, 'wooship'),
                'value' => $i . ($i === 1 ? ' month' : ' months'),
            );
        }
        for ($i = 3; $i <= 10; $i++) {
            self::$timeframes[$i . '_year'] = array(
                'label' => $i . ' ' . _n('year', 'years', $i, 'wooship'),
                'value' => $i . ($i === 1 ? ' year' : ' years'),
            );
        }

    }

    /**
     * Return conditions for display in admin ui
     *
     * @access public
     * @return array
     */
    public static function conditions($context = 'shipping_methods')
    {
        $result = array();

        // Iterate over all conditions groups
        foreach (self::$conditions as $group_key => $group) {

            // Iterate over conditions
            foreach ($group['children'] as $condition_key => $condition) {

                // Check if this condition is used in given context
                if (!in_array($context, $condition['context'])) {
                    continue;
                }

                // Add group if needed
                if (!isset($result[$group_key])) {
                    $result[$group_key] = array(
                        'label'     => $group['label'],
                        'options'  => array(),
                    );
                }

                // Push condition to group
                $result[$group_key]['options'][$condition_key] = $condition['label'];
            }
        }

        return $result;
    }

    /**
     * Check if condition uses field
     *
     * @access public
     * @param string $group
     * @param string $condition
     * @param string $field
     * @return bool
     */
    public static function uses_field($group, $condition, $field)
    {
        return in_array($field, self::$conditions[$group]['children'][$condition]['uses_fields']);
    }

    /**
     * Get field size
     *
     * @access public
     * @param string $group
     * @param string $condition
     * @return string
     */
    public static function field_size($group, $condition)
    {
        // Special case for meta fields (width changed dynamically via JS)
        if (in_array($condition, array('customer_meta_field'))) {
            return 'double';
        }

        // All other cases
        switch (count(self::$conditions[$group]['children'][$condition]['uses_fields'])) {
            case 2:
                return 'single';
            case 1:
                return 'double';
            default:
                return 'triple';
        }
    }

    /**
     * Return methods of particular condition for display in admin ui
     *
     * @access public
     * @param string $group
     * @param string $condition
     * @return array
     */
    public static function methods($group, $condition)
    {
        switch (self::$conditions[$group]['children'][$condition]['method']) {

            // yes, no
            case 'yes_no':
                return array(
                    'yes'   => __('yes', 'wooship'),
                    'no'    => __('no', 'wooship'),
                );

            // in list, not in list
            case 'in_list_not_in_list':
                return array(
                    'in_list'       => __('in list', 'wooship'),
                    'not_in_list'   => __('not in list', 'wooship'),
                );

            // at least, less than
            case 'at_least_less_than':
                return array(
                    'less_than'     => __('less than', 'wooship'),
                    'not_more_than' => __('not more than', 'wooship'),
                    'at_least'      => __('at least', 'wooship'),
                    'more_than'     => __('more than', 'wooship'),
                );

            // at least one, all, none
            case 'at_least_one_all_none':
                return array(
                    'at_least_one'  => __('at least one of selected', 'wooship'),
                    'all'           => __('all of selected', 'wooship'),
                    'only'          => __('only selected', 'wooship'),
                    'none'          => __('none of selected', 'wooship'),
                );

            // within past, earlier than
            case 'within_past_earlier_than':
                return array(
                    'later'     => __('within past', 'wooship'),
                    'earlier'   => __('earlier than', 'wooship'),
                );

            // matches, does not match
            case 'matches_does_not_match':
                return array(
                    'matches'           => __('matches', 'wooship'),
                    'does_not_match'    => __('does not match', 'wooship'),
                );

            // equals, does not equal, contains, does not contain
            case 'text_comparison':
                return array(
                    'equals'            => __('equals', 'wooship'),
                    'does_not_equal'    => __('does not equal', 'wooship'),
                    'contains'          => __('contains', 'wooship'),
                    'does_not_contain'  => __('does not contain', 'wooship'),
                    'begins_with'       => __('begins with', 'wooship'),
                    'ends_with'         => __('ends with', 'wooship'),
                );

            // is empty, is not empty, contains, does not contain, equals, does not equal etc
            case 'meta_field':
                return array(
                    'is_empty'          => __('is empty', 'wooship'),
                    'is_not_empty'      => __('is not empty', 'wooship'),
                    'contains'          => __('contains', 'wooship'),
                    'does_not_contain'  => __('does not contain', 'wooship'),
                    'begins_with'       => __('begins with', 'wooship'),
                    'ends_with'         => __('ends with', 'wooship'),
                    'equals'            => __('equals', 'wooship'),
                    'does_not_equal'    => __('does not equal', 'wooship'),
                    'less_than'         => __('less than', 'wooship'),
                    'less_or_equal_to'  => __('less or equal to', 'wooship'),
                    'more_than'         => __('more than', 'wooship'),
                    'more_or_equal'     => __('more or equal to', 'wooship'),
                    'is_checked'        => __('is checked', 'wooship'),
                    'is_not_checked'    => __('is not checked', 'wooship'),
                );

            default:
                return array();
        }
    }

    /**
     * Return timeframes for display in admin ui
     *
     * @access public
     * @param bool $include_all_time
     * @return array
     */
    public static function timeframes($include_all_time = false)
    {
        $result = array();

        // Add all time timeframe for some conditions
        if ($include_all_time) {
            $result['all_time'] = __('all time', 'wooship');
        }

        // Iterate over all timeframes
        foreach (self::$timeframes as $timeframe_key => $timeframe) {
            $result[$timeframe_key] = $timeframe['label'];
        }

        return apply_filters('wooship_timeframes', $result);
    }

    /**
     * Load multiselect items
     *
     * @access public
     * @return void
     */
    public function ajax_load_multiselect_items()
    {
        // Define data types that we are aware of
        $types = array(
            'coupons', 'shipping_classes', 'product_categories',
            'products', 'attributes', 'tags', 'shipping_zones', 'countries',
            'states', 'roles', 'capabilities', 'users'

        );

        // Make sure we know the type which is requested and query is not empty
        if (!in_array($_POST['type'], $types) || empty($_POST['query'])) {
            $results[] = array(
                'id'        => 0,
                'text'      => __('No search query sent', 'wooship'),
                'disabled'  => 'disabled'
            );
        }
        else {

            // Get items
            $selected = isset($_POST['selected']) && is_array($_POST['selected']) ? $_POST['selected'] : array();
            $results = $this->get_items($_POST['type'], $_POST['query'], $selected);

            // No items?
            if (empty($results)) {
                $results[] = array(
                    'id'        => 0,
                    'text'      => __('Nothing found', 'wooship'),
                    'disabled'  => 'disabled'
                );
            }
        }

        // Return data and exit
        echo json_encode(array('results' => $results));
        exit;
    }

    /**
     * Load items for multiselect fields based on search criteria and item type
     *
     * @access public
     * @param string $type
     * @param string $query
     * @param array $selected
     * @return array
     */
    public function get_items($type, $query, $selected)
    {
        $items = array();

        // Get items by type
        $method = 'get_items_' . $type;
        $all_items = $this->$method(array(), $query);

        // Iterate over returned items
        foreach ($all_items as $item_key => $item) {

            // Filter items that match search criteria
            if (RightPress_Helper::string_contains_phrase($item['text'], $query)) {

                // Filter items that are not yet selected
                if (empty($selected) || !in_array($item['id'], $selected)) {
                    $items[] = $item;
                }
            }
        }

        return $items;
    }

    /**
     * Load already selected multiselect field items by their ids
     *
     * @access public
     * @param string $type
     * @param array $ids
     * @return array
     */
    public static function get_items_by_ids($type, $ids = array())
    {
        $method = 'get_items_' . $type;
        $wooship = new self();
        return $wooship->$method($ids);
    }

    /**
     * Load coupons for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_coupons($ids = array(), $query = '')
    {
        $items = array();

        // WC31: Coupons may no longer be posts

        // Get all coupon ids
        $args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'shop_coupon',
            'post_status'       => array('publish'),
            'fields'            => 'ids',
        );

        // Query passed in
        if (!empty($query)) {
            // WC31: This will no longer work if coupons are not posts
            $args['wooship_title_query'] = $query;
        }

        // Specific coupons requested
        if (!empty($ids)) {
            $args['post__in'] = $ids;
        }

        $posts_raw = get_posts($args);

        // Format results array
        foreach ($posts_raw as $post_id) {
            $items[] = array(
                'id'    => $post_id,
                'text'  => get_the_title($post_id)
            );
        }

        return $items;
    }

    /**
     * Load shipping classes for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_shipping_classes($ids = array(), $query = '')
    {
        $items = array();

        // WC31: Shipping classes may no longer be post terms

        $shipping_classes_raw = get_terms(array('product_shipping_class'), array('hide_empty' => 0));
        $shipping_classes_raw_count = count($shipping_classes_raw);

        foreach ($shipping_classes_raw as $shipping_class_key => $shipping_class) {
            $shipping_class_name = $shipping_class->name;

            if ($shipping_class->parent) {
                $parent_id = $shipping_class->parent;
                $has_parent = true;

                // Make sure we don't have an infinite loop here
                $found = false;
                $i = 0;

                while ($has_parent && ($i < $shipping_classes_raw_count || $found)) {

                    // Reset each time
                    $found = false;
                    $i = 0;

                    foreach ($shipping_classes_raw as $parent_shipping_class_key => $parent_shipping_class) {

                        $i++;

                        if ($parent_shipping_class->term_id == $parent_id) {
                            $shipping_class_name = $parent_shipping_class->name . ' → ' . $shipping_class_name;
                            $found = true;

                            if ($parent_shipping_class->parent) {
                                $parent_id = $parent_shipping_class->parent;
                            }
                            else {
                                $has_parent = false;
                            }

                            break;
                        }
                    }
                }
            }

            // Skip this item if we don't need it
            if (!empty($ids) && !in_array($shipping_class->term_id, $ids)) {
                continue;
            }

            // Add item
            $items[] = array(
                'id'    => $shipping_class->term_id,
                'text'  => $shipping_class_name
            );
        }

        return $items;
    }

    /**
     * Load product categories for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_product_categories($ids = array(), $query = '')
    {
        $items = array();

        // WC31: Product categories may no longer be post terms

        $post_categories_raw = get_terms(array('product_cat'), array('hide_empty' => 0));
        $post_categories_raw_count = count($post_categories_raw);

        foreach ($post_categories_raw as $post_cat_key => $post_cat) {
            $category_name = $post_cat->name;

            if ($post_cat->parent) {
                $parent_id = $post_cat->parent;
                $has_parent = true;

                // Make sure we don't have an infinite loop here (happens with some kind of "ghost" categories)
                $found = false;
                $i = 0;

                while ($has_parent && ($i < $post_categories_raw_count || $found)) {

                    // Reset each time
                    $found = false;
                    $i = 0;

                    foreach ($post_categories_raw as $parent_post_cat_key => $parent_post_cat) {

                        $i++;

                        if ($parent_post_cat->term_id == $parent_id) {
                            $category_name = $parent_post_cat->name . ' → ' . $category_name;
                            $found = true;

                            if ($parent_post_cat->parent) {
                                $parent_id = $parent_post_cat->parent;
                            }
                            else {
                                $has_parent = false;
                            }

                            break;
                        }
                    }
                }
            }

            // Skip this item if we don't need it
            if (!empty($ids) && !in_array($post_cat->term_id, $ids)) {
                continue;
            }

            // Add item
            $items[] = array(
                'id'    => $post_cat->term_id,
                'text'  => $category_name
            );
        }

        return $items;
    }

    /**
     * Load products for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_products($ids = array(), $query = '')
    {
        $items = array();

        // WC31: Products may no longer be posts

        // Get all product ids
        // TBD: optimize this by adding a $query contraint
        $args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'product',
            'post_status'       => array('publish', 'pending', 'draft', 'future', 'private', 'inherit'),
            'fields'            => 'ids',
        );

        // Query passed in
        if (!empty($query)) {
            // WC31: This will no longer work if products are not posts
            $args['wooship_title_query'] = $query;
        }

        // Specific products requested
        if (!empty($ids)) {
            $args['post__in'] = $ids;
        }

        $posts_raw = get_posts($args);

        // Format results array
        foreach ($posts_raw as $post_id) {
            $items[] = array(
                'id'    => $post_id,
                'text'  => '#' . $post_id . ' ' . get_the_title($post_id)
            );
        }

        return $items;
    }

    /**
     * Load product attributes for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_attributes($ids = array(), $query = '')
    {
        $items = array();
        global $wc_product_attributes;

        // WC31: Check if this still works correctly

        // Iterate over product attributes
        foreach ($wc_product_attributes as $attribute_key => $attribute) {

            $attribute_name = !empty($attribute->attribute_label) ? $attribute->attribute_label : $attribute->attribute_name;

            $subitems = array();

            $children_raw = get_terms(array($attribute_key), array('hide_empty' => 0));
            $children_raw_count = count($children_raw);

            foreach ($children_raw as $child_key => $child) {
                $child_name = $child->name;

                if ($child->parent) {
                    $parent_id = $child->parent;
                    $has_parent = true;

                    // Make sure we don't have an infinite loop here
                    $found = false;
                    $i = 0;

                    while ($has_parent && ($i < $children_raw_count || $found)) {

                        // Reset each time
                        $found = false;
                        $i = 0;

                        foreach ($children_raw as $parent_child_key => $parent_child) {

                            $i++;

                            if ($parent_child->term_id == $parent_id) {
                                $child_name = $parent_child->name . ' → ' . $child_name;
                                $found = true;

                                if ($parent_child->parent) {
                                    $parent_id = $parent_child->parent;
                                }
                                else {
                                    $has_parent = false;
                                }

                                break;
                            }
                        }
                    }
                }

                // Skip this item if we don't need it
                if (!empty($ids) && !in_array($child->term_id, $ids)) {
                    continue;
                }

                // Add item
                $subitems[] = array(
                    'id'    => $child->term_id,
                    'text'  => $child_name
                );
            }

            // Iterate over subitems and make a list of item/subitem pairs
            foreach ($subitems as $subitem) {
                $items[] = array(
                    'id'    => $subitem['id'],
                    'text'  => $attribute_name . ': ' . $subitem['text'],
                );
            }
        }

        return $items;
    }

    /**
     * Load product tags for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_tags($ids = array(), $query = '')
    {
        $items = array();

        $tags_raw = get_terms(array('product_tag'), array('hide_empty' => 0));

        // Iterate over all tags
        foreach ($tags_raw as $tag_key => $tag) {

            // Skip this item if we don't need it
            if (!empty($ids) && !in_array($tag->term_id, $ids)) {
                continue;
            }

            // Add item
            $items[] = array(
                'id'    => $tag->term_id,
                'text'  => $tag->name,
            );
        }

        return $items;
    }

    /**
     * Load shipping zones for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_shipping_zones($ids = array(), $query = '')
    {
        $items = array();

        // Check if proprietary shipping zones are used
        $use_proprietary_shipping_zones = WooShip::use_proprietary_shipping_zones();

        // Check if WooCommerce shipping zones can be used
        if (!$use_proprietary_shipping_zones) {

            // Track if Rest of the World shipping zone will be added
            $rest_of_the_world_added = false;

            // Iterate over shipping zones
            foreach (WC_Shipping_Zones::get_zones() as $shipping_zone) {

                // Add item
                $items[] = array(
                    'id'    => ('wc_' . $shipping_zone['zone_id']),
                    'text'  => $shipping_zone['zone_name'],
                );
            }

            // Add Rest of the World shipping zone
            if (!$rest_of_the_world_added) {

                // Get zone
                $shipping_zone = WC_Shipping_Zones::get_zone(0);

                // Add to array
                $items = array_merge(array(array(
                    'id'    => ('wc_' . RightPress_WC_Legacy::shipping_zone_get_id($shipping_zone)),
                    'text'  => $shipping_zone->get_zone_name(),
                )), $items);
            }
        }

        // Check if proprietary shipping zones need to be considered at all
        if ($use_proprietary_shipping_zones || !empty($ids)) {

            // Get proprietary shipping zones
            $shipping_zones = WooShip_Shipping_Zone::get_shipping_zones_array(!empty($ids));

            // Iterate over all shipping zones
            if (is_array($shipping_zones) && !empty($shipping_zones)) {
                foreach ($shipping_zones as $shipping_zone) {

                    // Check if current shipping zone needs to be added
                    if ($use_proprietary_shipping_zones || (!empty($ids) && $shipping_zone['is_deleted'])) {

                        // Add item
                        $items[] = array(
                            'id'    => $shipping_zone['id'],
                            'text'  => $shipping_zone['title'] . ($shipping_zone['is_deleted'] ? ' (' . __('deleted', 'wooship') . ')' : ''),
                        );
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Load countries for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_countries($ids = array(), $query = '')
    {
        $items = array();

        $countries = new WC_Countries();

        // Iterate over all countries
        if ($countries && is_array($countries->countries)) {
            foreach ($countries->countries as $country_code => $country_name) {

                // Add item
                $items[] = array(
                    'id'    => $country_code,
                    'text'  => $country_name,
                );
            }
        }

        return $items;
    }

    /**
     * Load states for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_states($ids = array(), $query = '')
    {
        $items = array();

        $countries = new WC_Countries();
        $all_states = $countries->get_states();

        // Iterate over all countries
        if ($countries && is_array($countries->countries) && is_array($all_states)) {
            foreach ($all_states as $country_key => $states) {
                if (is_array($states) && !empty($states)) {

                    // Get country name
                    $country_name = !empty($countries->countries[$country_key]) ? $countries->countries[$country_key] : $country_key;

                    // Iterate over all states
                    foreach ($states as $state_key => $state) {

                        // Add item
                        $items[] = array(
                            'id'    => $country_key . '_' . $state_key,
                            'text'  => $country_name . ': ' . $state,
                        );
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Load roles for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_roles($ids = array(), $query = '')
    {
        $items = array();

        // Get roles
        global $wp_roles;

        if (!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }

        // Iterate over roles and format results array
        foreach ($wp_roles->get_names() as $role_key => $role) {

            // Skip this item if we don't need it
            if (!empty($ids) && !in_array($role_key, $ids)) {
                continue;
            }

            // Add item
            $items[] = array(
                'id'    => $role_key,
                'text'  => $role . ' (' . $role_key . ')',
            );
        }

        return $items;
    }

    /**
     * Load capabilities for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_capabilities($ids = array(), $query = '')
    {
        $items = array();

        // Groups plugin active?
        if (class_exists('Groups_User') && class_exists('Groups_Wordpress') && function_exists('_groups_get_tablename')) {

            global $wpdb;
            $capability_table = _groups_get_tablename('capability');
            $all_capabilities = $wpdb->get_results('SELECT capability FROM ' . $capability_table);

            if ($all_capabilities) {
                foreach ($all_capabilities as $capability) {

                    // Skip this item if we don't need it
                    if (!empty($ids) && !in_array($capability, $ids)) {
                        continue;
                    }

                    // Add item
                    $items[] = array(
                        'id'    => $capability->capability,
                        'text'  => $capability->capability
                    );
                }
            }
        }

        // Get standard WP capabilities
        else {
            global $wp_roles;

            if (!isset($wp_roles)) {
                get_role('administrator');
            }

            $roles = $wp_roles->roles;

            $already_added = array();

            if (is_array($roles)) {
                foreach ($roles as $rolename => $atts) {
                    if (isset($atts['capabilities']) && is_array($atts['capabilities'])) {
                        foreach ($atts['capabilities'] as $capability => $value) {
                            if (!in_array($capability, $already_added)) {

                                // Skip this item if we don't need it
                                if (!empty($ids) && !in_array($capability, $ids)) {
                                    continue;
                                }

                                // Add item
                                $items[] = array(
                                    'id'    => $capability,
                                    'text'  => $capability
                                );
                                $already_added[] = $capability;
                            }
                        }
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Load users for multiselect fields based on search criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function get_items_users($ids = array(), $query = '')
    {
        $items = array();

        // Get users
        $users = get_users(array(
            'fields' => array('ID', 'user_email'),
        ));

        // Iterate over users
        foreach ($users as $user) {

            // Add item
            $items[] = array(
                'id'    => $user->ID,
                'text'  => '#' . $user->ID . ' ' . $user->user_email,
            );
        }

        return $items;
    }

    /**
     * Get condition group and option from group_option string
     *
     * @access public
     * @param string $group_and_option
     * @return mixed
     */
    public static function extract_group_and_option($group_and_option)
    {
        $group_key = null;

        foreach (self::$conditions as $potential_group_key => $potential_group) {
            if (strpos($group_and_option, $potential_group_key) === 0) {
                $group_key = $potential_group_key;
            }
        }

        if ($group_key === null) {
            return false;
        }

        $option_key = preg_replace('/^' . $group_key . '_/i', '', $group_and_option);

        return array($group_key, $option_key);
    }

    /**
     * Check if method matches conditions
     *
     * @access public
     * @param array $method
     * @param array $package
     * @param string $based_on
     * @return bool
     */
    public static function method_matches_conditions($method, $package, $based_on)
    {
        $matches = true;

        // Iterate over conditions
        if (!empty($method['conditions']) && is_array($method['conditions'])) {
            foreach ($method['conditions'] as $condition) {
                if (!self::condition_is_matched($condition, $package, $based_on)) {
                    return false;
                }
            }
        }

        return $matches;
    }

    /**
     * Check if additional charge matches conditions
     *
     * @access public
     * @param array $method
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @param bool $subset_is_cart
     * @return bool
     */
    public static function charge_matches_conditions($method, $package, $based_on, $subset, $subset_is_cart = false)
    {
        $matches = true;

        // If subset is the same as cart, proceed in the same way as with shipping methods
        if ($subset_is_cart) {
            $subset = null;
        }

        // Iterate over conditions
        if (!empty($method['conditions']) && is_array($method['conditions'])) {
            foreach ($method['conditions'] as $condition) {
                if (!self::condition_is_matched($condition, $package, $based_on, $subset)) {
                    return false;
                }
            }
        }

        return $matches;
    }

    /**
     * Check if condition is matched
     *
     * @access public
     * @param array $condition
     * @param array $package (currently ignored, cart is used directly)
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_is_matched($condition, $package, $based_on, $subset = null)
    {
        $method = 'condition_check_' . $condition['type'];
        return self::$method($condition, $package, $based_on, $subset);
    }

    /**
     * Condition check: General - Subtotal
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_general_subtotal($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;

        // Get subtotal
        if ($subset) {
            $subtotal = self::get_subset_property($subset, 'subtotal', $based_on);
        }
        else {
            $subtotal = $based_on === 'incl' ? $woocommerce->cart->subtotal : $woocommerce->cart->subtotal_ex_tax;
        }

        // Allow currency conversion for condition value
        $condition_value = RightPress_Helper::get_amount_in_currency($condition['decimal']);

        // Check condition
        return self::compare_at_least_less_than($condition['general_subtotal_method'], $subtotal, $condition_value);
    }

    /**
     * Condition check: General - Weight
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_general_weight($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;

        // Get weight
        if ($subset) {
            $weight = self::get_subset_property($subset, 'weight', $based_on);
        }
        else {
            $weight = $woocommerce->cart->cart_contents_weight;
        }

        // Check condition
        return self::compare_at_least_less_than($condition['general_weight_method'], $weight, $condition['decimal']);
    }

    /**
     * Condition check: General - Sum of quantities
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_general_sum_of_quantities($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;

        // Get sum of quantities
        if ($subset) {
            $sum_of_quantities = self::get_subset_property($subset, 'sum_of_quantities', $based_on);
        }
        else {
            $sum_of_quantities = $woocommerce->cart->cart_contents_count;
        }

        // Check condition
        return self::compare_at_least_less_than($condition['general_sum_of_quantities_method'], $sum_of_quantities, $condition['number']);
    }

    /**
     * Condition check: General - Coupons
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_general_coupons($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;

        // Get applied coupon ids
        $applied_coupons = array();

        if (isset($woocommerce->cart->applied_coupons) && is_array($woocommerce->cart->applied_coupons)) {
            foreach ($woocommerce->cart->applied_coupons as $applied_coupon) {
                $coupon_id = RightPress_Helper::get_wc_coupon_id_from_code($applied_coupon);

                if (!in_array($coupon_id, $applied_coupons)) {
                    $applied_coupons[] = $coupon_id;
                }
            }
        }

        // Check condition
        return self::compare_at_least_one_all_none($condition['general_coupons_method'], $applied_coupons, $condition['coupons']);
    }

    /**
     * Condition check: General - Shipping class
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_general_shipping_class($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;
        $shipping_classes = array();
        $condition_classes = array();

        // Select proper list of items
        if ($subset) {
            $items = $subset;
        }
        else {
            $items = $woocommerce->cart->cart_contents;
        }

        // Iterate over cart items
        foreach($items as $item) {

            // Check if variation id is set
            $variation_id = !empty($item['variation_id']) ? $item['variation_id'] : null;

            // Get shipping class
            if ($shipping_class_id = RightPress_Helper::get_wc_product_shipping_class_id($item['product_id'], $variation_id)) {
                if (!in_array($shipping_class_id, $shipping_classes)) {
                    $shipping_classes[] = $shipping_class_id;
                }
            }
        }

        // Get condition shipping classes including child classes
        if (!empty($condition['shipping_classes']) && is_array($condition['shipping_classes'])) {
            foreach ($condition['shipping_classes'] as $class_id) {
                $current_classes = RightPress_Helper::get_term_with_children($class_id, 'product_shipping_class');
                $condition_classes = array_unique(array_merge($condition_classes, $current_classes));
            }
        }

        // Check condition
        return self::compare_at_least_one_all_none($condition['general_shipping_class_method'], $shipping_classes, $condition_classes);
    }

    /**
     * Condition check: Products - Product
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_products_product($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;
        $products = array();

        // Select proper list of items
        if ($subset) {
            $items = $subset;
        }
        else {
            $items = $woocommerce->cart->cart_contents;
        }

        // Iterate over items and pick product ids
        foreach ($items as $item) {

            // Get product
            $product = $item['data'];

            // Get product id (parent id in case of variation)
            $product_id = $product->is_type('variation') ? RightPress_WC_Legacy::product_variation_get_parent_id($product) : RightPress_WC_Legacy::product_get_id($product);

            if (!in_array($product_id, $products)) {
                $products[] = $product_id;
            }
        }

        // Check condition
        return self::compare_at_least_one_all_none($condition['products_product_method'], $products, $condition['products']);
    }

    /**
     * Condition check: Products - Product category
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_products_product_category($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;
        $product_categories = array();
        $condition_categories_split = array();
        $condition_categories = array();

        // Select proper list of items
        if ($subset) {
            $items = $subset;
        }
        else {
            $items = $woocommerce->cart->cart_contents;
        }

        // Get list of category ids
        foreach ($items as $item) {

            $product = $item['data'];
            $product_id = $product->is_type('variation') ? RightPress_WC_Legacy::product_variation_get_parent_id($product) : RightPress_WC_Legacy::product_get_id($product);

            // WC31: Products will no longer be posts
            $item_categories = wp_get_post_terms($product_id, 'product_cat');

            foreach ($item_categories as $category) {
                if (!in_array($category->term_id, $product_categories)) {
                    $product_categories[] = $category->term_id;
                }
            }
        }

        // Check if condition categories are set
        if (!empty($condition['product_categories']) && is_array($condition['product_categories'])) {

            // Get condition product categories including child categories split by parent
            foreach ($condition['product_categories'] as $category_id) {
                $condition_categories_split[$category_id] = RightPress_Helper::get_term_with_children($category_id, 'product_cat');
            }

            // Get condition product categories
            $condition_categories = self::merge_all_children($condition_categories_split);
        }

        // Check condition
        return self::compare_at_least_one_all_none($condition['products_product_category_method'], $product_categories, $condition_categories, $condition_categories_split);
    }

    /**
     * Condition check: Products - Product attribute
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_products_product_attribute($condition, $package, $based_on, $subset = null)
    {
        global $wpdb;
        global $woocommerce;
        $attributes = array();
        $condition_attributes_split = array();
        $condition_attributes = array();

        // Select proper list of items
        if ($subset) {
            $items = $subset;
        }
        else {
            $items = $woocommerce->cart->cart_contents;
        }

        // Iterate over cart items
        foreach($items as $item) {

            // Get selected variable product attributes
            $selected = (!empty($item['variation'])) ? $item['variation'] : array();

            // Get attribute ids
            if ($attribute_ids = RightPress_Helper::get_wc_product_attribute_ids($item['product_id'], $selected)) {
                $attributes = array_unique(array_merge($attributes, $attribute_ids));
            }
        }

        // Get condition product atrributes including child atrributes
        if (!empty($condition['attributes']) && is_array($condition['attributes'])) {
            foreach ($condition['attributes'] as $attribute_id) {

                // Determine taxonomy of this attribute
                $taxonomy = $wpdb->get_var('SELECT taxonomy FROM ' . $wpdb->term_taxonomy . ' WHERE term_id = ' . absint($attribute_id));

                // No taxonomy?
                if (!is_string($taxonomy) && !empty($taxonomy)) {
                    continue;
                }

                // Retrieve and store attributes
                $condition_attributes_split[$attribute_id] = RightPress_Helper::get_term_with_children($attribute_id, $taxonomy);
            }

            // Get condition attributes
            $condition_attributes = self::merge_all_children($condition_attributes_split);
        }

        // Check condition
        return self::compare_at_least_one_all_none($condition['products_product_attribute_method'], $attributes, $condition_attributes, $condition_attributes_split);
    }

    /**
     * Condition check: Products - Product tag
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_products_product_tag($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;
        $tags = array();

        // Select proper list of items
        if ($subset) {
            $items = $subset;
        }
        else {
            $items = $woocommerce->cart->cart_contents;
        }

        // Iterate over cart items
        foreach($items as $item) {

            // Get tag ids
            if ($tag_ids = RightPress_Helper::get_wc_product_tag_ids($item['product_id'])) {
                $tags = array_unique(array_merge($tags, $tag_ids));
            }
        }

        // Check condition
        return self::compare_at_least_one_all_none($condition['products_product_tag_method'], $tags, $condition['tags']);
    }

    /**
     * Condition check: Shipping - Shipping zone
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_shipping_shipping_zone($condition, $package, $based_on, $subset = null)
    {
        global $woocommerce;

        // Check if proprietary shipping zones are used
        if (WooShip::use_proprietary_shipping_zones()) {

            // Get shipping address
            $address = $package['destination'];

            // Get shipping zone applicable to current shipping address
            $zone = WooShip_Shipping_Zone::get_zone_id_by_address($address);
        }
        else {

            // Get WC Shipping Zone
            $zone = WC_Shipping_Zones::get_zone_matching_package($package);

            // Get shipping zone id
            $zone = 'wc_' . RightPress_WC_Legacy::shipping_zone_get_id($zone);
        }

        // Check condition
        return self::compare_in_list_not_in_list($condition['shipping_shipping_zone_method'], $zone, $condition['shipping_zones']);
    }

    /**
     * Condition check: Shipping - Country
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_shipping_country($condition, $package, $based_on, $subset = null)
    {
        // Get shipping address
        $address = $package['destination'];

        // Call corresponding method
        return WooShip_Shipping_Zone::condition_check_shipping_country($condition, $address);
    }

    /**
     * Condition check: Shipping - State
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_shipping_state($condition, $package, $based_on, $subset = null)
    {
        // Get shipping address
        $address = $package['destination'];

        // Call corresponding method
        return WooShip_Shipping_Zone::condition_check_shipping_state($condition, $address);
    }

    /**
     * Condition check: Shipping - City
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_shipping_city($condition, $package, $based_on, $subset = null)
    {
        // Get shipping address
        $address = $package['destination'];

        // Call corresponding method
        return WooShip_Shipping_Zone::condition_check_shipping_city($condition, $address);
    }

    /**
     * Condition check: Shipping - Postcode
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_shipping_postcode($condition, $package, $based_on, $subset = null)
    {
        // Get shipping address
        $address = $package['destination'];

        // Call corresponding method
        return WooShip_Shipping_Zone::condition_check_shipping_postcode($condition, $address);
    }

    /**
     * Condition check: Customer - Is logged in
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_customer_is_logged_in($condition, $package, $based_on, $subset = null)
    {
        return $condition['customer_is_logged_in_method'] === 'no' ? !is_user_logged_in() : is_user_logged_in();
    }

    /**
     * Condition check: Customer - Role
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_customer_role($condition, $package, $based_on, $subset = null)
    {
        // Get condition roles
        $condition_roles = isset($condition['roles']) ? (array) $condition['roles'] : array();

        // Check condition
        return self::compare_in_list_not_in_list($condition['customer_role_method'], RightPress_Helper::current_user_roles(), $condition_roles);
    }

    /**
     * Condition check: Customer - Capability
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_customer_capability($condition, $package, $based_on, $subset = null)
    {
        // Get condition capabilities
        $condition_capabilities = isset($condition['capabilities']) ? (array) $condition['capabilities'] : array();

        // Check condition
        return self::compare_in_list_not_in_list($condition['customer_capability_method'], RightPress_Helper::current_user_capabilities(), $condition_capabilities);
    }

    /**
     * Condition check: Customer - Customer
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_customer_customer($condition, $package, $based_on, $subset = null)
    {
        // Get condition users
        $condition_users = isset($condition['users']) ? (array) $condition['users'] : array();

        // Check condition
        return self::compare_in_list_not_in_list($condition['customer_customer_method'], get_current_user_id(), $condition_users);
    }

    /**
     * Condition check: Customer - Customer meta field
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_customer_customer_meta_field($condition, $package, $based_on, $subset = null)
    {
        // No meta key set
        if (empty($condition['meta_key'])) {
            return false;
        }

        // Get method
        $method = $condition['customer_customer_meta_field_method'];

        // Proceed if user is logged in
        if (is_user_logged_in()) {

            // Get user meta
            // WC31: customers may no longer be WP users
            $meta = RightPress_Helper::unwrap_post_meta(get_user_meta(get_current_user_id(), $condition['meta_key']));

            // Iterate over post meta
            if (!empty($meta) && is_array($meta)) {
                foreach ($meta as $single) {

                    // Proceed depending on condition method
                    switch ($method) {

                        // Is Empty
                        case 'is_empty':
                            return self::is_empty($single);

                        // Is Not Empty
                        case 'is_not_empty':
                            return !self::is_empty($single);

                        // Contains
                        case 'contains':
                            return self::contains($single, $condition['text']);

                        // Does Not Contain
                        case 'does_not_contain':
                            return !self::contains($single, $condition['text']);

                        // Begins with
                        case 'begins_with':
                            return self::begins_with($single, $condition['text']);

                        // Ends with
                        case 'ends_with':
                            return self::ends_with($single, $condition['text']);

                        // Equals
                        case 'equals':
                            return self::equals($single, $condition['text']);

                        // Does Not Equal
                        case 'does_not_equal':
                            return !self::equals($single, $condition['text']);

                        // Less Than
                        case 'less_than':
                            return self::less_than($single, $condition['text']);

                        // Less Or Equal To
                        case 'less_or_equal_to':
                            return !self::more_than($single, $condition['text']);

                        // More Than
                        case 'more_than':
                            return self::more_than($single, $condition['text']);

                        // More Or Equal
                        case 'more_or_equal':
                            return !self::less_than($single, $condition['text']);

                        // Is Checked
                        case 'is_checked':
                            return self::is_checked($single);

                        // Is Not Checked
                        case 'is_not_checked':
                            return !self::is_checked($single);

                        default:
                            return true;
                    }
                }
            }
        }

        // Nothing matched, proceed depending on condition method
        return in_array($method, array('is_empty', 'does_not_contain', 'does_not_equal', 'is_not_checked')) ? true : false;
    }

    /**
     * Condition check: Customer history - Amount spent
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_history_amount_spent($condition, $package, $based_on, $subset = null)
    {
        $amount_spent = 0;

        // Amount is not specified in condition or user is not logged in
        if ((empty($condition['decimal']) && $condition['decimal'] !== '0') || !is_user_logged_in()) {
            return $condition['history_amount_spent_method'] === 'at_least' ? false : true;
        }

        // Get order ids
        $all_order_ids = self::get_matching_order_ids($condition['timeframe']);

        // Iterate over all order ids and sum up totals
        foreach ($all_order_ids as $order_id) {
            $order = wc_get_order($order_id);
            $amount_spent += (float) RightPress_WC_Legacy::order_get_total($order);
        }

        // Check condition
        return self::compare_at_least_less_than($condition['history_amount_spent_method'], $amount_spent, $condition['decimal']);
    }

    /**
     * Condition check: Customer history - Order count
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_history_order_count($condition, $package, $based_on, $subset = null)
    {
        $order_count = 0;

        // Count is not specified in condition or user is not logged in
        if ((empty($condition['number']) && $condition['number'] !== '0') || !is_user_logged_in()) {
            return $condition['history_order_count_method'] === 'at_least' ? false : true;
        }

        // Get order ids
        $all_order_ids = self::get_matching_order_ids($condition['timeframe']);

        // Get order count
        if ($all_order_ids && is_array($all_order_ids)) {
            $order_count = count($all_order_ids);
        }

        // Check condition
        return self::compare_at_least_less_than($condition['history_order_count_method'], $order_count, $condition['number']);
    }

    /**
     * Condition check: Customer history - Last order amount
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_history_last_order_amount($condition, $package, $based_on, $subset = null)
    {
        $last_order_amount = 0;

        // Amount is not specified in condition or user is not logged in
        if ((empty($condition['decimal']) && $condition['decimal'] !== '0') || !is_user_logged_in()) {
            return $condition['history_last_order_amount_method'] === 'at_least' ? false : true;
        }

        // WC31: Orders will no longer be posts

        // Get last customer paid order id
        $config = array(
            'numberposts'   => 1,
            'meta_key'      => '_customer_user',
            'meta_value'    => get_current_user_id(),
            'post_type'     => 'shop_order',
            'fields'        => 'ids',
        );

        // Only load orders that are marked processing or completed (i.e. paid)
        $config['post_status'] = array('wc-processing', 'wc-completed');

        // Retrieve order ids
        $all_order_ids = get_posts($config);

        // Get last order amount
        if (!empty($all_order_ids) && is_array($all_order_ids)) {
            $order_id = array_shift($all_order_ids);
            $last_order_amount = (float) get_post_meta($order_id, '_order_total', true);
        }

        // Check condition
        return self::compare_at_least_less_than($condition['history_last_order_amount_method'], $last_order_amount, $condition['decimal']);
    }

    /**
     * Condition check: Customer history - Last order time
     *
     * @access public
     * @param array $condition
     * @param array $package
     * @param string $based_on
     * @param array $subset
     * @return bool
     */
    public static function condition_check_history_last_order_time($condition, $package, $based_on, $subset = null)
    {
        // User is not logged in
        if (!is_user_logged_in()) {
            return $condition['history_last_order_time_method'] === 'later' ? false : true;
        }

        // WC31: Orders will no longer be posts

        // Get last customer paid order id
        $config = array(
            'numberposts'   => 1,
            'meta_key'      => '_customer_user',
            'meta_value'    => get_current_user_id(),
            'post_type'     => 'shop_order',
            'fields'        => 'ids',
        );

        // Only load orders that are marked processing or completed (i.e. paid)
        $config['post_status'] = array('wc-processing', 'wc-completed');

        // Retrieve order ids
        $all_order_ids = get_posts($config);

        // Check if customer has any orders
        if (!empty($all_order_ids) && is_array($all_order_ids)) {

            // Get last order date
            $order_id = array_shift($all_order_ids);
            $order_date = get_the_date('Y-m-d', $order_id);

            // Check if we have such timeframe
            if (!isset(self::$timeframes[$condition['timeframe']])) {
                return false;
            }

            // Check if date is within timeframe
            if (self::date_is_within_timeframe($order_date, self::$timeframes[$condition['timeframe']])) {
                return $condition['history_last_order_time_method'] === 'later' ? true : false;
            }
            else {
                return $condition['history_last_order_time_method'] === 'later' ? false : true;
            }
        }

        return $condition['history_last_order_time_method'] === 'later' ? false : true;
    }

    /**
     * Check if value is empty (but not zero)
     *
     * @access public
     * @param mixed $value
     * @return bool
     */
    public static function is_empty($value)
    {
        return ($value === '' || $value === null || count($value) === 0);
    }

    /**
     * Check if value contains string
     *
     * @access public
     * @param mixed $value
     * @param string $string
     * @return bool
     */
    public static function contains($value, $string)
    {
        if (gettype($value) === 'array') {
            return in_array($string, $value);
        }
        else {
            return (strpos($value, $string) !== false);
        }

        return false;
    }

    /**
     * Check if value begins with string
     *
     * @access public
     * @param mixed $value
     * @param string $string
     * @return bool
     */
    public static function begins_with($value, $string)
    {
        if (gettype($value) === 'array') {
            $first = array_shift($value);
            return $first == $string;
        }
        else {
            return RightPress_Helper::string_begins_with_substring($value, $string);
        }

        return false;
    }

    /**
     * Check if value ends with string
     *
     * @access public
     * @param mixed $value
     * @param string $string
     * @return bool
     */
    public static function ends_with($value, $string)
    {
        if (gettype($value) === 'array') {
            $last = array_pop($value);
            return $last == $string;
        }
        else {
            return RightPress_Helper::string_ends_with_substring($value, $string);
        }

        return false;
    }

    /**
     * Check if value equals string
     *
     * @access public
     * @param mixed $value
     * @param string $string
     * @return bool
     */
    public static function equals($value, $string)
    {
        if (gettype($value) === 'array') {
            foreach ($value as $single_value) {
                if ($single_value === $string) {
                    return true;
                }
            }
        }
        else {
            return ($value === $string);
        }

        return false;
    }

    /**
     * Check if value is less than number
     *
     * @access public
     * @param mixed $value
     * @param string $number
     * @return bool
     */
    public static function less_than($value, $number)
    {
        if (gettype($value) === 'array') {
            foreach ($value as $single_value) {
                if ($single_value < $number) {
                    return true;
                }
            }
        }
        else {
            return ($value < $number);
        }

        return false;
    }

    /**
     * Check if value is more than number
     *
     * @access public
     * @param mixed $value
     * @param string $number
     * @return bool
     */
    public static function more_than($value, $number)
    {
        if (gettype($value) === 'array') {
            foreach ($value as $single_value) {
                if ($single_value > $number) {
                    return true;
                }
            }
        }
        else {
            return ($value > $number);
        }

        return false;
    }

    /**
     * Check if value represents field being checked
     *
     * @access public
     * @param mixed $value
     * @return bool
     */
    public static function is_checked($value)
    {
        if (gettype($value) === 'array') {
            foreach ($value as $single_value) {
                if ($single_value) {
                    return true;
                }
            }
        }
        else if ($value) {
            return true;
        }

        return false;
    }

    /**
     * Get matching order ids
     *
     * @access public
     * @param array $timeframe
     * @return array
     */
    public static function get_matching_order_ids($timeframe)
    {
        // WC31: Orders will no longer be posts

        // Get customer paid order ids
        $config = array(
            'numberposts'   => -1,
            'meta_key'      => '_customer_user',
            'meta_value'    => get_current_user_id(),
            'post_type'     => 'shop_order',
            'fields'        => 'ids',
        );

        // Only load orders that are marked processing or completed (i.e. paid)
        $config['post_status'] = array('wc-processing', 'wc-completed');

        // Check if timeframe was set
        if ($timeframe !== 'all_time') {

            // Check if we have such timeframe
            if (!isset(self::$timeframes[$timeframe])) {
                return false;
            }

            // Get year, month and day
            list($year, $month, $day) = self::get_timeframe_date(self::$timeframes[$timeframe]);

            // Update config
            $config['date_query'] = array(
                'after' => array(
                    'year'  => $year,
                    'month' => $month,
                    'day'   => $day,
                ),
                'inclusive' => true,
            );
        }

        // Get order ids
        $all_order_ids = get_posts($config);

        return ($all_order_ids && !is_wp_error($all_order_ids) && is_array($all_order_ids)) ? $all_order_ids : array();
    }

    /**
     * Get timeframe date
     *
     * @access public
     * @param array $timeframe
     * @return array
     */
    public static function get_timeframe_date($timeframe)
    {
        // Subtract time
        $timestamp = self::get_timeframe_timestamp($timeframe);

        // Return year, month and day
        return array(
            date('Y', $timestamp),
            date('m', $timestamp),
            date('d', $timestamp),
        );
    }

    /**
     * Get timeframe timestamp
     *
     * @access public
     * @param array $timeframe
     * @return int
     */
    public static function get_timeframe_timestamp($timeframe)
    {
        return strtotime('-' . $timeframe['value']);
    }

    /**
     * Check if date is within timeframe
     *
     * @access public
     * @param string $date
     * @param array $timeframe
     * @return bool
     */
    public static function date_is_within_timeframe($date, $timeframe)
    {
        // Get timestamps
        $date_timestamp = strtotime($date);
        $condition_timestamp = self::get_timeframe_timestamp($timeframe);

        // Compare timestamps
        return $date_timestamp >= $condition_timestamp ? true : false;
    }

    /**
     * Get subset property
     * Note: currently this function is only suitable for numbers and decimals
     *
     * @access public
     * @param array $subset
     * @param string $property
     * @param string $based_on
     * @return mixed
     */
    public static function get_subset_property($subset, $property, $based_on)
    {
        $value = 0;

        // Iterate over subset items
        foreach ($subset as $item_key => $item)
        {
            // Subtotal
            if ($property === 'subtotal') {

                // Add line subtotal without tax
                $value += (float) $item['line_subtotal'];

                // Maybe add tax
                if ($based_on === 'incl') {
                    $value += (float) $item['line_subtotal_tax'];
                }
            }

            // Weight
            if ($property === 'weight') {

                // Get product properties
                $product = $item['data'];
                $product_weight = (float) $product->get_weight();
                $product_quantity = (int) $item['quantity'];

                // Add to total value
                $value += (float) ($product_weight * $product_quantity);
            }

            // Sum of quantities
            if ($property === 'sum_of_quantities') {
                $value += (int) $item['quantity'];
            }
        }

        return $value;
    }

    /**
     * Compare list of items with list of elements in conditions
     *
     * @access public
     * @param string $method
     * @param array $items
     * @param array $condition_items
     * @param array $condition_items_split
     * @return bool
     */
    public static function compare_at_least_one_all_none($method, $items, $condition_items, $condition_items_split = array())
    {
        // Make sure items was passed as array
        $items = (array) $items;

        // None
        if ($method === 'none') {
            if (count(array_intersect($items, $condition_items)) == 0) {
                return true;
            }
        }

        // All - regular check
        else if ($method === 'all' && empty($condition_items_split)) {
            if (count(array_intersect($items, $condition_items)) == count($condition_items)) {
                return true;
            }
        }

        // All - special case
        // Check with respect to parent items (e.g. parent categories)
        // This is a special case - we can't simply compare against
        // $condition_items which include child items since this would
        // require for them to also be present in $items
        else if ($method === 'all') {

            // Iterate over all condition items split by parent
            foreach ($condition_items_split as $parent_with_children) {

                // At least one item must match at least one item in parent/children array
                if (count(array_intersect($items, $parent_with_children)) == 0) {
                    return false;
                }
            }

            return true;
        }

        // Only
        else if ($method === 'only') {

            // Iterate over items and check if all of them exists in condition items
            foreach ($items as $item) {
                if (!in_array($item, $condition_items)) {
                    return false;
                }
            }

            return true;
        }

        // At least one
        else if (count(array_intersect($items, $condition_items)) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check if item is in list of items
     *
     * @access public
     * @param string $method
     * @param mixed $items
     * @param array $condition_items
     * @return bool
     */
    public static function compare_in_list_not_in_list($method, $items, $condition_items)
    {
        // Make sure items was passed as array
        $items = (array) $items;

        // Proceed depending on method
        if ($method === 'not_in_list') {
            if (count(array_intersect($items, $condition_items)) == 0) {
                return true;
            }
        }
        else if (count(array_intersect($items, $condition_items)) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Text comparison
     *
     * @access public
     * @param string $method
     * @return bool
     */
    public static function compare_text_comparison($method, $text, $condition_text)
    {
        // Text must be set, otherwise there's nothing to compare against
        if (empty($text)) {
            return false;
        }

        // No text set in conditions
        if (empty($condition_text)) {
            return in_array($method, array('equals', 'does_not_contain')) ? false : true;
        }

        // Proceed depending on condition method
        switch ($method) {

            // Equals
            case 'equals':
                return self::equals($text, $condition_text);

            // Does Not Equal
            case 'does_not_equal':
                return !self::equals($text, $condition_text);

            // Contains
            case 'contains':
                return self::contains($text, $condition_text);

            // Does Not Contain
            case 'does_not_contain':
                return !self::contains($text, $condition_text);

            // Begins with
            case 'begins_with':
                return self::begins_with($text, $condition_text);

            // Ends with
            case 'ends_with':
                return self::ends_with($text, $condition_text);

            default:
                return true;
        }
    }

    /**
     * Compare number with another number
     *
     * @access public
     * @param string $method
     * @param int $number
     * @param int $condition_number
     * @return bool
     */
    public static function compare_at_least_less_than($method, $number, $condition_number)
    {
        if ($method === 'less_than' && $number < $condition_number) {
            return true;
        }
        else if ($method === 'not_more_than' && $number <= $condition_number) {
            return true;
        }
        else if ($method === 'at_least' && $number >= $condition_number) {
            return true;
        }
        else if ($method === 'more_than' && $number > $condition_number) {
            return true;
        }

        return false;
    }

    /**
     * Merge all child taxonomy terms from a list split by parent
     *
     * @access public
     * @param array $items_split
     * @return array
     */
    public static function merge_all_children($items_split)
    {
        $items = array();

        // Iterate over parents
        foreach ($items_split as $parent_id => $children) {

            // Add parent to children array
            $children[] = (int) $parent_id;

           // Add unique parent/children to main array
            $items = array_unique(array_merge($items, $children));
        }

        return $items;
    }



}

new WooShip_Conditions();

}
