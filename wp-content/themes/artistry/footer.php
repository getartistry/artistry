<div id="user-info">
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
  
<?php
/*
 * This Divi child theme file modifies Divi Theme v2.5 footer links starting at line 45 below.
 * Author:   Tim Russell http://timrussell.com
 * Creation date Sept 2, 2015
 */


if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>


		<?php
			if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'depth'          => '1',
								'menu_class'     => 'bottom-nav',
								'container'      => '',
								'fallback_cb'    => '',
							) );
						?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; ?>

				<div id="footer-bottom">
					<div class="container clearfix">
				<?php
					if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
						get_template_part( 'includes/social_icons', 'footer' );
					}
				?>

						<p id="footer-info">Copyright &copy; <?php echo date("Y") ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

</div> <!-- #page-container -->

<?php do_action('website_after'); ?>
<?php wp_footer(); ?>
</body>
</html>