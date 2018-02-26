<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Compatibility {

    public $settings = array();
    public $duplicated = array();

    public function __construct() {

        add_action('wp_ajax_quadmenu_compatibility_import', array($this, 'import'));

        add_action('admin_menu', array($this, 'panel'), 999);
    }

    function import() {
        check_ajax_referer('quadmenu', 'nonce');

        if (!empty($_REQUEST['plugin'])) {

            $plugin = sanitize_text_field($_REQUEST['plugin']);

            do_action('quadmenu_compatibility_import_' . $plugin);
        }
    }

    function panel() {
        add_submenu_page('quadmenu_welcome', 'Compatibility', 'Compatibility', 'edit_posts', 'quadmenu_compatibility', array($this, 'compatibility'));
    }

    function header() {
        require_once QUADMENU_PATH . 'includes/panel/header.php';
    }

    function menu($id = null, $name = null) {

        if (empty($id) || empty($name)) {
            return false;
        }

        $id = intval($id);

        $name = sanitize_text_field($name);

        $source = wp_get_nav_menu_object($id);

        $source_items = wp_get_nav_menu_items($id);

        if (!$new_id = get_term_by('name', $name, 'nav_menu')->term_id) {
            $new_id = wp_create_nav_menu($name);
        }

        if (!$new_id) {
            return false;
        }

// key is the original db ID, val is the new
        $rel = array();

        $i = 1;

        foreach ($source_items as $menu_item) {
            $args = array(
                'menu-item-db-id' => $menu_item->db_id,
                'menu-item-object-id' => $menu_item->object_id,
                'menu-item-object' => $menu_item->object,
                'menu-item-position' => $i,
                'menu-item-type' => $menu_item->type,
                'menu-item-title' => $menu_item->title,
                'menu-item-url' => $menu_item->url,
                'menu-item-description' => $menu_item->description,
                'menu-item-attr-title' => $menu_item->attr_title,
                'menu-item-target' => $menu_item->target,
                'menu-item-classes' => implode(' ', $menu_item->classes),
                'menu-item-xfn' => $menu_item->xfn,
                'menu-item-status' => $menu_item->post_status
            );
            $parent_id = wp_update_nav_menu_item($new_id, 0, $args);
            $rel[$menu_item->db_id] = $parent_id;
// did it have a parent? if so, we need to update with the NEW ID
            if ($menu_item->menu_item_parent) {
                $args['menu-item-parent-id'] = $rel[$menu_item->menu_item_parent];
                $parent_id = wp_update_nav_menu_item($new_id, $parent_id, $args);
            }
// allow developers to run any custom functionality they'd like
            do_action('duplicate_menu_item', $menu_id, $new_id, $menu_item, $parent_id);
            $i++;
        }

        $this->duplicated[$id] = $new_id;

        return $new_id;
    }

    function add_menus_locations() {

        $locations = get_theme_mod('nav_menu_locations');

        if (count($locations)) {

            foreach ($locations as $key => $menu_id) {

                if (isset($this->duplicated[$menu_id])) {
                    $locations[$key] = $this->duplicated[$menu_id];
                }
            }

            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    function compatibility() {
        $this->header();
        ?>
        <div class="about-wrap quadmenu-admin-wrap theme-browser">
            <h1><?php esc_html_e('Compatibility', 'quadmenu'); ?></h1>
            <div class="about-text">
                <?php printf(__('%s gives you the ability to import other mega menu content in one step. Choose the plugin you have installed and hit the Import button option.', 'quadmenu'), QUADMENU_NAME); ?>
                <?php printf(__('If you have doubts you can review your documentation here <a href="%s" target="_blank">read more</a>.'), QUADMENU_DOCUMENTATION); ?>
            </div>
            <hr/>
            <div class="quadmenu-admin-columns">
                <div class="theme" data-plugin="megamenu">
                    <div class="theme-screenshot">
                        <img src="<?php echo esc_url(QUADMENU_URL_ASSETS . 'backend/images/megamenu.jpg'); ?>" alt="">
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name" id="eduka-name">Max Mega Menu</h2>
                        <div class="theme-actions">          
                            <span class="spinner left"></span>                  
                            <!--<a class="button quadmenu_remove" href="#"><?php esc_html_e('Remove', 'quadmenu'); ?></a>-->
                            <a class="button button-primary quadmenu_import" href="#"><?php esc_html_e('Import', 'quadmenu'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="theme" data-plugin="lmm">
                    <div class="theme-screenshot">
                        <img src="<?php echo esc_url(QUADMENU_URL_ASSETS . 'backend/images/lmm.jpg'); ?>" alt="">
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name" id="eduka-name">LMM</h2>
                        <div class="theme-actions">          
                            <span class="spinner"></span>
                            <a class="button quadmenu_import" href="#"><?php esc_html_e('Import', 'quadmenu'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="theme" data-plugin="placeholder">
                    <div class="theme-screenshot">
                        <img src="<?php echo esc_url(QUADMENU_URL_ASSETS . 'backend/images/placeholder.jpg'); ?>" alt="">
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name" id="eduka-name">UberMenu</h2>
                        <div class="theme-actions">          
                            <span class="spinner"></span>
                            <a class="button" href="#"><?php esc_html_e('Import', 'quadmenu'); ?></a>
                        </div>
                    </div>
                </div>
                <!--<div class="theme" data-plugin="placeholder">
                    <div class="theme-screenshot">
                        <img src="<?php echo esc_url(QUADMENU_URL_ASSETS . 'backend/images/placeholder.jpg'); ?>" alt="">
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name" id="eduka-name">Mega Main Menu</h2>
                        <div class="theme-actions">          
                            <span class="spinner"></span>
                            <a class="button" href="#"><?php esc_html_e('Import', 'quadmenu'); ?></a>
                        </div>
                    </div>-->
            </div>
        </div>
        </div>
        <?php
    }

    function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    function rgb2rgba($string) {

        if (strpos($string, '#') !== false) {
            return $string;
        }

        if (strpos($string, 'rgb(') !== false) {
            $string = str_replace(')', ', 0)', $string);
            $string = str_replace('rgb(', 'rgba(', $string);
        }

        return $string;
    }

    function rgba2hex($color) {

        if (strpos($color, '#') !== false && (strlen($color) === 7 || strlen($color) === 4)) {
            return $color;
        }

        $color = $this->rgb2rgba($color);

        $rgba = array();

        $regex = '#\((([^()]+|(?R))*)\)#';

        if (preg_match_all($regex, $color, $matches)) {
            $rgba = explode(',', implode(' ', $matches[1]));
        } else {
            $rgba = explode(',', $color);
        }

        $r = intval($rgba['0']);
        $g = intval($rgba['1']);
        $b = intval($rgba['2']);

        $r = dechex($r < 0 ? 0 : ($r > 255 ? 255 : $r));
        $g = dechex($g < 0 ? 0 : ($g > 255 ? 255 : $g));
        $b = dechex($b < 0 ? 0 : ($b > 255 ? 255 : $b));

        $color = (strlen($r) < 2 ? '0' : '') . $r;
        $color .= (strlen($g) < 2 ? '0' : '') . $g;
        $color .= (strlen($b) < 2 ? '0' : '') . $b;

        return '#' . $color;
    }

    function rgba2alpha($color) {

        if (strpos($color, '#') !== false && (strlen($color) === 7 || strlen($color) === 4)) {
            return 1;
        }

        $color = $this->rgb2rgba($color);

        $rgba = array();

        $regex = '#\((([^()]+|(?R))*)\)#';

        if (preg_match_all($regex, $color, $matches)) {
            $rgba = explode(',', implode(' ', $matches[1]));
        } else {
            $rgba = explode(',', $color);
        }

        $alpha = isset($rgba['3']) ? intval($rgba['3']) : 1;

        return $alpha;
    }

}

new QuadMenu_Compatibility();
