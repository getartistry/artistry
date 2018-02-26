<nav id="quadmenu" class="<?php echo esc_attr($args->navbar_class); ?> quadmenu-is-vertical" data-unwrap="<?php echo esc_attr($args->unwrap); ?>" data-breakpoint="<?php echo esc_attr($args->layout_breakpoint); ?>">
    <?php quadmenu_get_template('button/offcanvas.php', array('target' => '#' . $args->target_id)); ?>
    <div id="<?php echo esc_attr($args->target_id); ?>" class="navbar-offcanvas">
        <?php quadmenu_get_template('logo.php', $args->navbar_logo); ?>
        <?php echo $args->menu_items; ?>
    </div>
</nav>