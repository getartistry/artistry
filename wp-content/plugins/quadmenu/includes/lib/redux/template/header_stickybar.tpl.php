<div id="redux-sticky">
    <div id="info_bar">

        <a href="javascript:void(0);" class="expand_options<?php echo esc_attr(( $this->parent->args['open_expanded'] ) ? ' expanded' : ''); ?>"<?php echo $this->parent->args['hide_expand'] ? ' style="display: none;"' : '' ?>>
            <?php esc_attr_e('Expand', 'quadmenu'); ?>
        </a>

        <div class="redux-action_bar">
            <span class="spinner"></span>
            <!--<a id="quadmenu_duplicate_theme" href="#"><?php esc_html_e('Duplicate this theme', 'quadmenu'); ?></a>-->       
            <a id="quadmenu_add_theme" class="button button-secondary" href="#"><?php esc_html_e('Create Theme', 'quadmenu'); ?></a>
            <?php if (false === $this->parent->args['hide_save']) { ?>
                <?php submit_button(esc_attr__('Save', 'quadmenu'), 'primary', 'redux_save', false); ?>
            <?php } ?>

            <?php if (false === $this->parent->args['hide_reset']) { ?>
                <?php submit_button(esc_attr__('Reset Section', 'quadmenu'), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false, array('id' => 'redux-defaults-section')); ?>
                <?php submit_button(esc_attr__('Reset All', 'quadmenu'), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false, array('id' => 'redux-defaults')); ?>
            <?php } ?>
        </div>
        <div class="redux-ajax-loading" alt="<?php esc_attr_e('Working...', 'quadmenu') ?>">&nbsp;</div>
        <div class="clear"></div>
    </div>

    <!-- Notification bar -->
    <div id="redux_notification_bar">
        <?php $this->notification_bar(); ?>
    </div>


</div>