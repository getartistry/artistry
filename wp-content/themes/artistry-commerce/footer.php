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
<!-- end user info -->
<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

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

					echo et_get_footer_credits();
				?>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div> <!-- #page-container -->

	<?php wp_footer(); ?>
</body>
</html>
