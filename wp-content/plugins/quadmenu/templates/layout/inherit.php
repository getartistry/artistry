<nav id="quadmenu" class="<?php echo esc_attr($args->navbar_class); ?> quadmenu-is-vertical" data-theme="<?php echo esc_attr($args->theme); ?>" data-unwrap="<?php echo esc_attr($args->unwrap); ?>" >
    <div class="quadmenu-container">
        <div id="<?php echo esc_attr($args->target_id); ?>">
            <?php quadmenu_get_template('logo.php', $args->navbar_logo); ?>
            <?php echo $args->menu_items; ?>
        </div>
    </div>
</nav>