<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Customizer extends QuadMenu_Compiler {

    static $q_option = false;

    public function __construct() {
        //add_action('customize_controls_print_footer_scripts', array($this, 'print_templates'), -10);
        //add_action('customize_controls_print_footer_scripts', array($this, 'modal'));
    }

    /*
     * add_action('customize_preview_init', array($this, 'customize_preview_init'));
     * public function customize_preview_init() {
      add_action('wp_enqueue_scripts', array($this, 'customize_preview_enqueue_deps'));
      add_filter('wp_nav_menu_args', array($this, 'filter_wp_nav_menu_args'), 1000);
      add_filter('wp_nav_menu', array($this, 'filter_wp_nav_menu'), 10, 2);
      add_filter('wp_footer', array($this, 'export_preview_data'), 1);
      add_filter('customize_render_partials_response', array($this, 'export_partial_rendered_nav_menu_instances'));
      } */

    function ajaxurl() {
        ?>

        <?php

    }

    function controls() {
        
    }

    /*
     * Append navmenu admin functions to controls
     * 
     * function controls() {
      wp_enqueue_style('quadmenu-admin');

      wp_enqueue_script('quadmenu-admin');

      wp_localize_script('quadmenu-admin', 'quadmenu', array('nonce' => wp_create_nonce('quadmenu'), 'add_to_column' => esc_html('Add to Column', 'quadmenu'), 'add_background' => esc_html('Add Background', 'quadmenu')));

      wp_enqueue_script('quadmenu-customizer-controls', QUADMENU_URL . 'assets/backend/js/quadmenu-customizer-controls' . QuadMenu::isMin() . '.js', array('jquery'), '', 'all');
      } */



    /*
     * 
     * public function print_templates() {

      ?>
      <script type="text/html" id="tmpl-customize-control-nav_menu_item-content">
      <div class="menu-item-bar">
      <span class="quadmenu_modal" data-quadmenu="modal" data-target="#quadmenu_modal" data-backdrop="true"><?php echo QUADMENU_NAME; ?></span>
      <div class="menu-item-handle">
      <span class="item-type" aria-hidden="true">{{ data.item_type_label }}</span>
      <span class="item-title" aria-hidden="true">
      <span class="spinner"></span>
      <span class="menu-item-title<# if ( ! data.title && ! data.original_title ) { #> no-title<# } #>">{{ data.title || data.original_title || wp.customize.Menus.data.l10n.untitled }}</span>
      </span>
      <span class="item-controls">
      <button type="button" class="button-link item-edit" aria-expanded="false"><span class="screen-reader-text"><?php
      printf(__('Edit menu item: %1$s (%2$s)'), '{{ data.title || wp.customize.Menus.data.l10n.untitled }}', '{{ data.item_type_label }}');
      ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
      <button type="button" class="button-link item-delete submitdelete deletion"><span class="screen-reader-text"><?php
      printf(__('Remove Menu Item: %1$s (%2$s)'), '{{ data.title || wp.customize.Menus.data.l10n.untitled }}', '{{ data.item_type_label }}');
      ?></span></button>
      </span>
      </div>
      </div>

      <div class="menu-item-settings" id="menu-item-settings-{{ data.menu_item_id }}">
      <# if ( 'custom' === data.item_type ) { #>
      <p class="field-url description description-thin">
      <label for="edit-menu-item-url-{{ data.menu_item_id }}">
      <?php _e('URL'); ?><br />
      <input class="widefat code edit-menu-item-url" type="text" id="edit-menu-item-url-{{ data.menu_item_id }}" name="menu-item-url" />
      </label>
      </p>
      <# } #>
      <p class="description description-thin">
      <label for="edit-menu-item-title-{{ data.menu_item_id }}">
      <?php _e('Navigation Label'); ?><br />
      <input type="text" id="edit-menu-item-title-{{ data.menu_item_id }}" placeholder="{{ data.original_title }}" class="widefat edit-menu-item-title" name="menu-item-title" />
      </label>
      </p>
      <p class="field-link-target description description-thin">
      <label for="edit-menu-item-target-{{ data.menu_item_id }}">
      <input type="checkbox" id="edit-menu-item-target-{{ data.menu_item_id }}" class="edit-menu-item-target" value="_blank" name="menu-item-target" />
      <?php _e('Open link in a new tab'); ?>
      </label>
      </p>
      <p class="field-title-attribute field-attr-title description description-thin">
      <label for="edit-menu-item-attr-title-{{ data.menu_item_id }}">
      <?php _e('Title Attribute'); ?><br />
      <input type="text" id="edit-menu-item-attr-title-{{ data.menu_item_id }}" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title" />
      </label>
      </p>
      <p class="field-css-classes description description-thin">
      <label for="edit-menu-item-classes-{{ data.menu_item_id }}">
      <?php _e('CSS Classes'); ?><br />
      <input type="text" id="edit-menu-item-classes-{{ data.menu_item_id }}" class="widefat code edit-menu-item-classes" name="menu-item-classes" />
      </label>
      </p>
      <p class="field-xfn description description-thin">
      <label for="edit-menu-item-xfn-{{ data.menu_item_id }}">
      <?php _e('Link Relationship (XFN)'); ?><br />
      <input type="text" id="edit-menu-item-xfn-{{ data.menu_item_id }}" class="widefat code edit-menu-item-xfn" name="menu-item-xfn" />
      </label>
      </p>
      <p class="field-description description description-thin">
      <label for="edit-menu-item-description-{{ data.menu_item_id }}">
      <?php _e('Description'); ?><br />
      <textarea id="edit-menu-item-description-{{ data.menu_item_id }}" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description">{{ data.description }}</textarea>
      <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
      </label>
      </p>

      <div class="menu-item-actions description-thin submitbox">
      <# if ( ( 'post_type' === data.item_type || 'taxonomy' === data.item_type ) && '' !== data.original_title ) { #>
      <p class="link-to-original">
      <?php
      printf(__('Original: %s'), '<a class="original-link" href="{{ data.url }}">{{ data.original_title }}</a>');
      ?>
      </p>
      <# } #>

      <button type="button" class="button-link button-link-delete item-delete submitdelete deletion"><?php _e('Remove'); ?></button>
      <span class="spinner"></span>
      </div>
      <input type="hidden" name="menu-item-db-id[{{ data.menu_item_id }}]" class="menu-item-data-db-id" value="{{ data.menu_item_id }}" />
      <input type="hidden" name="menu-item-parent-id[{{ data.menu_item_id }}]" class="menu-item-data-parent-id" value="{{ data.parent }}" />
      </div><!-- .menu-item-settings-->
      <ul class="menu-item-transport"></ul>
      </script>

      <?php
      }

      public function modal() {
      ?>
      <div class="modal fade" id="quadmenu_modal" tabindex="-1" role="dialog" aria-labelledby="quadmenu" aria-hidden="true">
      <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-body">
      </div>
      <div class="modal-footer">
      <button type="button" class="quadmenu_close button-secondary" data-dismiss="modal"><?php esc_html_e('Close', 'quadmenu'); ?></button>
      </div>
      </div>
      </div>
      </div>
      <?php
      } */
}

new QuadMenu_Customizer();
