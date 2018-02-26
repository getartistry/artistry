<?php

if (!defined('ABSPATH')) {
    die('-1');
}

global $submenu;

if (isset($submenu['quadmenu_welcome'])) {
    $welcome_menu_items = $submenu['quadmenu_welcome'];
}

if (is_array($welcome_menu_items)) {
    ?>
    <div class="wrap about-wrap quadmenu-wp-admin-header ">
        <h2 class="nav-tab-wrapper">
            <?php
            foreach ($welcome_menu_items as $welcome_menu_item) {
                ?>
                <a href="admin.php?page=<?php echo $welcome_menu_item[2] ?>" class="nav-tab <?php
                   if (isset($_GET['page']) and $_GET['page'] == $welcome_menu_item[2]) {
                       echo 'nav-tab-active';
                   }
                   ?> "><?php echo $welcome_menu_item[0] ?></a>
                   <?php
               }
               ?>
        </h2>
    </div>
    <?php
}

