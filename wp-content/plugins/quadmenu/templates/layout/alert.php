<?php
global $_wp_registered_nav_menus;
?>  
<nav id="quadmenu">
    <div class="quadmenu-alert">
        <h4><?php esc_html_e('Something went wrong', 'quadmenu'); ?></h4>
        <?php if (!isset($args->theme_location) || empty(!empty($_wp_registered_nav_menus[$args->theme_location]))): ?>  
            <p><?php esc_html_e('Please include a valid theme location in your php functions. For example:', 'quadmenu'); ?></p>
            <p><?php printf('<code>&lt;?php quadmenu(array(theme_location => &quot;%1$s&quot;)); ?&gt;</code>', key($_wp_registered_nav_menus)); ?></p>
        <?php else: ?>  
            <p><?php printf(__('This theme location is not active. Please go to <a href="%1$s" target="_blank">Configuration</a> tab and activate the <b>%2$s</b> theme location.', 'quadmenu'), QuadMenu::taburl('0'), $_wp_registered_nav_menus[$args->theme_location]); ?></p>
        <?php endif; ?>  
    </div>
</nav>
