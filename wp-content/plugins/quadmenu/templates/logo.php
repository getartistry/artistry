<?php if (!empty($url)): ?>
    <a class="quadmenu-navbar-brand img" href="<?php echo esc_url(home_url('/')); ?>"><?php printf('<img height="60" width="160" src="%1$s" alt="%2$s"/>', esc_url($url), esc_attr(get_bloginfo('name'))); ?></a>
<?php endif; ?>