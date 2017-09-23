<?php
// Create new locations for widgets areas.
function pbe_push_widgets() { ?>
    <!-- Above the header -->
    <div id="pbe-above-header-wa-wrap">
        <?php dynamic_sidebar('pbe-above-header-wa'); ?>
    </div>
    <!-- Below the header -->
    <div id="pbe-below-header-wa-wrap">
        <?php dynamic_sidebar('pbe-below-header-wa'); ?>
    </div>
    <!-- Footer -->
    <div id="pbe-footer-wa-wrap">
        <?php dynamic_sidebar('pbe-footer-wa'); ?>
    </div>
    <div id="pbe-above-content-wa-wrap">
        <?php dynamic_sidebar('pbe-above-content-wa'); ?>
    </div>
    <div id="pbe-below-content-wa-wrap">
        <?php dynamic_sidebar('pbe-below-content-wa'); ?>
    </div>
    <!-- Push new widget areas into place -->
    <script>
        jQuery(function($){
            // Above header - Added inside #main-header wrap
            $("#main-header").prepend($("#pbe-above-header-wa-wrap"));
            $("#pbe-above-header-wa-wrap").show();
            // Below header - Added inside #main-header wrap
            $("#main-header").append($("#pbe-below-header-wa-wrap"));
            $("#pbe-below-header-wa-wrap").show();
            // Footer - Added before #main-footer
            $("#main-footer").before($("#pbe-footer-wa-wrap"));
            $("#pbe-below-header-wa-wrap").show();
            // Above Content - Added before #main-content
            $("#main-content").prepend($("#pbe-above-content-wa-wrap"));
            $("#pbe-above-content-wa-wrap").show();
            // Below Content - Added after #main-content
            $("#main-content").append($("#pbe-below-content-wa-wrap"));
            $("#pbe-below-content-wa-wrap").show();
        });
    </script>
<?php 
} 
add_action('wp_footer', 'pbe_push_widgets');

// Adjust the layout so that it fits into the header better
function pbe_push_widgets_css() { ?>
    <style>


    </style>
<?php
}
add_action('wp_head', 'pbe_push_widgets_css');
?>