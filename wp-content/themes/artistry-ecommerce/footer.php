<?php
/**
 * The template for displaying the footer.
 *
 * @package OceanWP WordPress theme
 */ ?>

 <!-- Add user info -->
 <div id="user-info" style="display: none;">
 <div id="user-email"><?php global $current_user;
     get_currentuserinfo();
     echo $current_user->user_email;
 ?>
 </div>
 <div id="user-first-name"><?php global $current_user;
     get_currentuserinfo();
   echo $current_user->user_firstname;
 ?>
 </div>
 <div id="user-last-name">
 <?php global $current_user;
     get_currentuserinfo();
     echo $current_user->user_lastname;
 ?>
 </div>
 <div id="user-phone">
 <?php global $current_user;
     get_currentuserinfo();
     echo $current_user->billing_phone;
 ?>
 </div>
 </div>

 <script>
 dataLayer.push({
  'color': 'red',
  'conversionValue': 50,
  'event': 'customizeCar'
});
</script>


 <!-- end user info -->

        </main><!-- #main -->

        <?php do_action( 'ocean_after_main' ); ?>

        <?php do_action( 'ocean_before_footer' ); ?>

        <?php
        // Elementor `footer` location
        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) { ?>

            <?php do_action( 'ocean_footer' ); ?>

        <?php } ?>

        <?php do_action( 'ocean_after_footer' ); ?>

    </div><!-- #wrap -->

    <?php do_action( 'ocean_after_wrap' ); ?>

</div><!-- #outer-wrap -->

<?php do_action( 'ocean_after_outer_wrap' ); ?>

<?php
// If is not sticky footer
if ( ! class_exists( 'Ocean_Sticky_Footer' ) ) {
    get_template_part( 'partials/scroll-top' );
} ?>

<?php
// Search overlay style
if ( 'overlay' == oceanwp_menu_search_style() ) {
    get_template_part( 'partials/header/search-overlay' );
} ?>

<?php
// If sidebar mobile menu style
if ( 'sidebar' == oceanwp_mobile_menu_style() ) {

    // Mobile panel close button
    if ( get_theme_mod( 'ocean_mobile_menu_close_btn', true ) ) {
        get_template_part( 'partials/mobile/mobile-sidr-close' );
    } ?>

    <?php
    // Mobile Menu (if defined)
    get_template_part( 'partials/mobile/mobile-nav' ); ?>

    <?php
    // Mobile search form
    if ( get_theme_mod( 'ocean_mobile_menu_search', true ) ) {
        get_template_part( 'partials/mobile/mobile-search' );
    }

} ?>

<?php
// If full screen mobile menu style
if ( 'fullscreen' == oceanwp_mobile_menu_style() ) {
    get_template_part( 'partials/mobile/mobile-fullscreen' );
} ?>

<?php wp_footer(); ?>
</body>
</html>
