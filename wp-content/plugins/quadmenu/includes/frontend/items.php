<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Items {

    function __construct() {

        add_filter('quadmenu_item_object_class', array($this, 'item_object_class'), 10, 4);

        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItem.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemMega.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemColumn.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemWidget.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemSearch.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemCart.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemPostType.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemDefault.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuItemIcon.class.php';
        require_once QUADMENU_PATH . 'includes/frontend/walker/QuadMenuWalker.class.php';
    }

    function item_object_class($class, $item, $id, $auto_child = '') {

        switch ($item->quadmenu) {

            case 'mega':
                $class = 'QuadMenuItemMega';
                break;

            case 'column';
                $class = 'QuadMenuItemColumn';
                break;

            case 'widget';
                $class = 'QuadMenuItemWidget';
                break;

            case 'icon';
                $class = 'QuadMenuItemIcon';
                break;

            case 'search';
                $class = 'QuadMenuItemSearch';
                break;

            case 'cart';
                $class = 'QuadMenuItemCart';
                break;

            case 'post_type';
                $class = 'QuadMenuItemPostType';
                break;

            //default:
                //$class = 'QuadMenuItemDefault';
                //break;
        }

        return $class;
    }

}

new QuadMenu_Items();

