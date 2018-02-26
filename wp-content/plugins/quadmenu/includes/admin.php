<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Admin {

    public function __construct() {

        add_action('admin_enqueue_scripts', array($this, 'register'), -1);

        add_action('admin_notices', array($this, 'notices'));

        add_action('admin_footer-nav-menus.php', array($this, 'icons'));

        add_filter('pre_delete_post', array($this, 'pre_delete_post'), 10, 3);

        //add_action('admin_footer-nav-menus.php', array($this, 'modal'));
    }

    public function pre_delete_post($delete, $post, $force) {

        if (!isset($_POST['nav-menu-data']))
            return $delete;

        if (!is_nav_menu_item($post->ID))
            return $delete;

        $menu_obj = wp_setup_nav_menu_item($post);

        if (!empty($menu_obj->quadmenu_allowed_parents) && in_array(sanitize_key($menu_obj->quadmenu_menu_item_parent), (array) $menu_obj->quadmenu_allowed_parents))
            return false;

        return $delete;
    }

    public function remove_children_nav_menu_items($menu_item_id, $menu_id = 0) {

        $deleted = array();

        if ($menu_item_id > 0 && is_nav_menu_item($menu_item_id)) {

            $delete_menu_items_id = $this->get_children_nav_menu_items($menu_item_id, $menu_id);

            $delete_menu_items_id[] = array('id' => $menu_item_id);

            if (count($delete_menu_items_id)) {
                foreach ($delete_menu_items_id as $item) {

                    $id = absint($item['id']);

                    do_action('quadmenu_remove_nav_menu_item', $id, $menu_id);

                    if (wp_delete_post($id, true)) {

                        $deleted[] = $id;
                    }
                }
            }
        }

        return $deleted;
    }

    public function get_children_nav_menu_items($parent_menu_item_id, $menu_id) {

        $childrens = array();

        // check we're using a valid menu ID
        if (!is_nav_menu($menu_id)) {
            return $childrens;
        }

        $menu = wp_get_nav_menu_items($menu_id);

        if (count($menu)) {

            foreach ($menu as $item) {

                // find the child menu items
                if ($parent_menu_item_id == $item->menu_item_parent) {

                    $childrens[$item->ID] = array(
                        'id' => $item->ID,
                        'title' => $item->title,
                    );
                }
            }
        }

        return $childrens;
    }

    public function register() {

        wp_register_style('quadmenu-modal', QUADMENU_URL . 'assets/backend/css/modal' . QuadMenu::isMin() . '.css', array(), '', 'all');

        wp_register_style('quadmenu-admin', QUADMENU_URL . 'assets/backend/css/quadmenu-admin' . QuadMenu::isMin() . '.css', array('quadmenu-modal', _QuadMenu()->selected_icons()->ID), '', 'all');

        wp_register_script('quadmenu-admin-core', QUADMENU_URL . 'assets/backend/js/quadmenu-admin-core' . QuadMenu::isMin() . '.js', array('jquery'), '', false);

        wp_register_script('quadmenu-admin-init', QUADMENU_URL . 'assets/backend/js/quadmenu-admin-init' . QuadMenu::isMin() . '.js', array('jquery', 'jquery-ui-sortable', 'quadmenu-admin-core'), '', false);
    }

    public function notices() {

        if ($notices = get_option('quadmenu_admin_notices', false)) {
            foreach ($notices as $notice) {

                if (empty($notice['class']) || empty($notice['notice']))
                    continue;
                ?>
                <div class="<?php echo esc_attr($notice['class']); ?>">
                    <p><?php esc_html_e($notice['notice']); ?></p>
                </div>
                <?php
            }
            delete_option('quadmenu_admin_notices');
        }
    }

    static function add_notification($class = 'updated', $notice = false) {

        if (!$notice)
            return;

        $notices = get_option('quadmenu_admin_notices', array());

        $notices[] = array(
            'class' => $class,
            'notice' => $notice,
        );

        update_option('quadmenu_admin_notices', $notices);
    }

    public function icons() {

        global $quadmenu_locations;
        ?>
        <script>
            jQuery(document).ready(function () {
                var $list = jQuery('.menu-theme-locations'),
                        locations = <?php echo json_encode(array_keys($quadmenu_locations)); ?>,
                        icon = '<img src="<?php echo esc_url(QUADMENU_URL_ASSETS . '/backend/images/q2.png'); ?>" style="width: 1em; height: auto; margin: 1px 0 -1px 0; "/>';

                jQuery.each(locations, function (index, item) {
                    $list.find('input#locations-' + item).after(icon);
                });

            });
        </script>
        <?php
    }

    public function modal() {
        ?>
        <div class="modal fade" id="quadmenu" tabindex="-1" role="dialog" aria-labelledby="quadmenuLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="quadmenu_close button-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}

new QuadMenu_Admin();
