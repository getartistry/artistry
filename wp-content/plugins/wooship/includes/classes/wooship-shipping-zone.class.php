<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shipping zone class
 *
 * @class WooShip_Shipping_Zone
 * @package WooShip
 * @author RightPress
 */
if (!class_exists('WooShip_Shipping_Zone')) {

class WooShip_Shipping_Zone
{
    // Define shipping zone properties
    public $id;
    public $title;
    public $conditions;
    public $sort_order;
    public $is_deleted;

    /**
     * Constructor class
     *
     * @access public
     * @param mixed $id
     * @param string $title
     * @param array $conditions
     * @param int $sort_order
     * @param bool $is_deleted
     * @return void
     */
    public function __construct($id = null, $title = '', $conditions = array(), $sort_order = 0, $is_deleted = false)
    {
        // Create new or load existing?
        if ($id === null) {

            // Title
            $this->title = $title;

            // Insert WP Post
            $this->id = wp_insert_post(array(
                'post_title'        => $this->title,
                'post_name'         => '',
                'post_status'       => 'publish',
                'post_type'         => 'wooship_ship_zone',
                'ping_status'       => 'closed',
                'comment_status'    => 'closed',
            ));

            // Add conditions as post meta
            $this->conditions = (array) $conditions;
            add_post_meta($this->id, 'conditions', $this->conditions);

            // Add sort order as post meta
            $this->sort_order = (int) $sort_order;
            add_post_meta($this->id, 'sort_order', $this->sort_order);

            // Add is deleted as post meta
            $this->is_deleted = (bool) $is_deleted;
            add_post_meta($this->id, 'is_deleted', $this->is_deleted);
        }
        else if ($id && is_numeric($id) && RightPress_Helper::post_exists($id)) {
            $this->id = $id;
            $this->populate();
        }
    }

    /**
     * Load existing shipping zone
     *
     * @access public
     * @return void
     */
    public function populate()
    {
        // Get title
        $this->title = get_the_title($this->id);

        // Get post meta
        $post_meta = RightPress_Helper::unwrap_post_meta(get_post_meta($this->id));

        // Load other properties from meta
        foreach (array('conditions', 'sort_order', 'is_deleted') as $property) {
            $this->$property = isset($post_meta[$property]) ? maybe_unserialize($post_meta[$property]) : null;
            $this->$property = $property === 'is_deleted' ? (bool) $this->$property : $this->$property;
        }
    }

    /**
     * Update single Shipping Zone field
     *
     * @access public
     * @return void
     */
    public function update_field($field, $value)
    {
        $this->$field = $value;

        switch ($field) {

            case 'title':
                wp_update_post(array(
                    'ID'            => $this->id,
                    'post_title'    => $value,
                ));
                break;

            default:
                update_post_meta($this->id, $field, $value);
                break;
        }
    }

    /**
     * Update shipping zone with new details
     *
     * @access public
     * @return void
     */
    public function update($shipping_zone)
    {
        $this->update_field('title', $shipping_zone['title']);
        $this->update_field('conditions', $shipping_zone['conditions']);
        $this->update_field('sort_order', $shipping_zone['sort_order']);
    }

    /**
     * Mark shipping zone as deleted
     *
     * @access public
     * @return void
     */
    public function delete()
    {
        $this->update_field('is_deleted', true);
    }

    /**
     * Update shipping zones
     *
     * @access public
     * @param array $shipping_zones
     * @return void
     */
    public static function update_shipping_zones($shipping_zones = array())
    {
        // Get existing shipping zones
        $existing_zones = self::get_shipping_zones(true);

        // Mark no longer existing shipping zones as deleted
        foreach ($existing_zones as $existing_zone) {
            if (!$existing_zone->is_deleted) {

                $match_found = false;

                foreach ($shipping_zones as $shipping_zone) {
                    if ($shipping_zone['id'] == $existing_zone->id) {
                        $match_found = true;
                    }
                }

                if (!$match_found) {
                    $existing_zone->delete();
                }
            }
        }

        // Iterate over shipping zones
        foreach ($shipping_zones as $shipping_zone_key => $shipping_zone)
        {
            // Existing shipping zone
            if (isset($shipping_zone['id']) && self::shipping_zone_exists($shipping_zone['id'], $existing_zones)) {
                $existing_zones[(int) $shipping_zone['id']]->update($shipping_zone);
            }
            // New shipping zone
            else {
                new WooShip_Shipping_Zone($shipping_zone['id'], $shipping_zone['title'], $shipping_zone['conditions'], $shipping_zone_key);
            }
        }
    }

    /**
     * Check if shipping zone exists
     *
     * @access public
     * @param int $id
     * @param array $shipping_zones
     * @return bool
     */
    public static function shipping_zone_exists($id, $shipping_zones = null)
    {
        // Shipping zones sent in
        if (isset($shipping_zones)) {

            foreach ($shipping_zones as $shipping_zone) {
                if ((int) $shipping_zone->id === (int) $id) {
                    return true;
                }
            }

            return false;
        }

        // Shipping zones not sent in
        return self::get_shipping_zone($id) ? true : false;
    }

    /**
     * Get all shipping zones
     *
     * @access public
     * @patam bool $include_deleted
     * @return array
     */
    public static function get_shipping_zones($include_deleted = false)
    {
        $shipping_zones = array();

        // Get all shipping zone IDs
        $query = new WP_Query(array(
            'post_type'     => 'wooship_ship_zone',
            'fields'        => 'ids',
        ));

        // Populate list of shipping zones
        foreach ($query->posts as $id) {

            // Retrieve shipping zone
            $shipping_zone = self::get_shipping_zone($id);

            // Skip non-existant zones and maybe skip zones that have been marked as deleted
            if (!$shipping_zone || ($shipping_zone->is_deleted && !$include_deleted)) {
                continue;
            }

            // Store current shipping zone
            $shipping_zones[(int) $id] = $shipping_zone;
        }

        // Sort shipping zones by sort order and return
        return self::sort($shipping_zones);
    }

    /**
     * Get shipping zones as associative arrays
     *
     * @access public
     * @param bool $include_deleted
     * @return array
     */
    public static function get_shipping_zones_array($include_deleted = false)
    {
        $shipping_zones = array();

        // Retrieve non-deleted shipping zones
        $existing_zones = self::get_shipping_zones($include_deleted);

        // Iterate over shipping zones and convert them
        foreach ($existing_zones as $existing_zone_key => $existing_zone) {
            $shipping_zones[] = (array) $existing_zone;
        }

        return $shipping_zones;
    }

    /**
     * Get single shipping zone
     *
     * @access public
     * @param int $id
     * @return mixed
     */
    public static function get_shipping_zone($id = 0)
    {
        $shipping_zone = new self($id);
        return isset($shipping_zone->id) ? $shipping_zone : false;
    }

    /**
     * Sort shipping zones
     *
     * @access public
     * @param array $shipping_zones
     * @return array
     */
    public static function sort($shipping_zones)
    {
        // Do not sort less than two items
        if (count($shipping_zones) < 2) {
            return empty($shipping_zones) ? array() : $shipping_zones;
        }

        // Sort and return value
        uasort($shipping_zones, array('WooShip_Shipping_Zone', 'sort_comparison'));
        return $shipping_zones;
    }

    /**
     * Comparison function for sort shipping zones
     *
     * @access public
     * @param object $a
     * @param object $b
     * @return array
     */
    public static function sort_comparison($a, $b)
    {
        return strcmp($a->sort_order, $b->sort_order);
    }

    /**
     * Get shipping zone by shipping address
     *
     * @access public
     * @return mixed
     */
    public static function get_zone_id_by_address($address)
    {
        // Retrieve zones
        $zones = self::get_shipping_zones();

        // Iterate over shipping zones
        foreach ($zones as $zone) {

            $matches_conditions = true;

            // No zone conditions set
            if (empty($zone->conditions) || !is_array($zone->conditions)) {
                return $zone->id;
            }

            // Iterate over zone conditions
            foreach ($zone->conditions as $condition) {

                // Check if address matches condition
                if (!self::condition_is_matched($condition, $address)) {
                    $matches_conditions = false;
                    continue;
                }
            }

            // Zone matches conditions
            if ($matches_conditions) {
                return $zone->id;
            }
        }

        return false;
    }

    /**
     * Check if single shipping zone condition is matched
     *
     * @access public
     * @param array $condition
     * @param array $address
     * @return bool
     */
    public static function condition_is_matched($condition, $address)
    {
        $method = 'condition_check_' . $condition['type'];
        return self::$method($condition, $address);
    }

    /**
     * Condition check: Shipping - Country
     *
     * @access public
     * @param array $condition
     * @param array $address
     * @return bool
     */
    public static function condition_check_shipping_country($condition, $address)
    {
        // Get address country
        $country = !empty($address['country']) ? $address['country'] : null;

        // Get condition countries
        $condition_countries = isset($condition['countries']) ? (array) $condition['countries'] : array();

        // Check condition
        return WooShip_Conditions::compare_in_list_not_in_list($condition['shipping_country_method'], $country, $condition_countries);
    }

    /**
     * Condition check: Shipping - State
     *
     * @access public
     * @param array $condition
     * @param array $address
     * @return bool
     */
    public static function condition_check_shipping_state($condition, $address)
    {
        // Country or state is not set
        if (empty($address['country']) || empty($address['state'])) {
            return false;
        }

        // Make state key
        $state_key = $address['country'] . '_' . $address['state'];

        // Get condition states
        $condition_states = isset($condition['states']) ? (array) $condition['states'] : array();

        // Check condition
        return WooShip_Conditions::compare_in_list_not_in_list($condition['shipping_state_method'], $state_key, $condition_states);
    }

    /**
     * Condition check: Shipping - City
     *
     * @access public
     * @param array $condition
     * @param array $address
     * @return bool
     */
    public static function condition_check_shipping_city($condition, $address)
    {
        // Get city name
        $city = !empty($address['city']) ? $address['city'] : '';

        // Get condition city
        $condition_city = !empty($condition['text']) ? $condition['text'] : '';

        // Check condition
        return WooShip_Conditions::compare_text_comparison($condition['shipping_city_method'], $city, $condition_city);
    }

    /**
     * Condition check: Shipping - Postcode
     *
     * @access public
     * @param array $condition
     * @param array $address
     * @return bool
     */
    public static function condition_check_shipping_postcode($condition, $address)
    {
        $method = $condition['shipping_postcode_method'];

        // At least one of the postcode to compare is not available
        if (empty($address['postcode']) || empty($condition['text'])) {
            return false;
        }

        // Prepare address postcode for comparison
        $address_postcode = strtoupper($address['postcode']);

        // Prepare condition postcode(s) for comparison
        $postcodes = explode(',', strtoupper($condition['text']));
        $postcodes = array_map('trim', $postcodes);
        $postcodes = array_diff($postcodes, array(''));

        // Condition contains no postcodes
        if (empty($postcodes)) {
            return false;
        }

        // Track if match was found
        $match_found = false;

        // Iterate over postcodes
        foreach ($postcodes as $postcode) {

            // Postcode with wildcards
            if (strpos($postcode, '@') !== false || strpos($postcode, '*') !== false) {

                // Prepare regex string
                $regex = '/^' . str_replace(array('\*', '@'), array('.', '.+?'), preg_quote($postcode)) . '$/i';

                // Compare
                if (preg_match($regex, $address_postcode) === 1 && $method === 'matches') {
                    return true;
                }
            }
            // Postcode range
            else if (strpos($postcode, '-') !== false) {

                // Split range
                $ranges = explode('-', $postcode);
                $ranges[0] = trim($ranges[0]);
                $ranges[1] = trim($ranges[1]);

                // Check if ranges are valid
                if (count($ranges) !== 2 || (empty($ranges[0]) && $ranges[0] !== '0') || (empty($ranges[1]) && $ranges[1] !== '0') || !is_numeric($ranges[0]) || !is_numeric($ranges[1]) || $ranges[0] >= $ranges[1]) {
                    continue;
                }

                // Check if post code is within ranges
                if ($ranges[0] <= $address_postcode && $address_postcode <= $ranges[1]) {
                    $match_found = true;
                    break;
                }
            }
            // Full postcode
            else if ($postcode === $address_postcode) {
                $match_found = true;
                break;
            }
        }

        // Check if match was found
        if ($match_found) {
            return $method === 'matches';
        }
        else {
            return $method !== 'matches';
        }
    }


}
}
