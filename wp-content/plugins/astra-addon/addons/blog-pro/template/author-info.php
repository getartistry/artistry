<?php
/**
 * Author Info.
 *
 * @package     Astra Addon
 * @since       1.0.0
 */

do_action( 'astra_author_info_before' ); ?>

	<div class="ast-single-author-box" itemprop="author" itemscope itemtype="http://schema.org/Person">
		<div class="ast-author-meta">
			<div class="about-author-title-wrapper">
				<h3 class="about-author">
					<?php esc_html_e( 'About The Author', 'astra-addon' ); ?>
				</h3>
			</div>
			<div class="ast-author-details">
				<div class="post-author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'email' ), 100 ); ?></div>
				<div class="post-author-bio">
					<a class='url fn n' href='<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>' itemprop='url' rel='author'> <h4 class='author-title' itemprop="name"> <?php echo esc_html( get_the_author() ); ?> </h4> </a>
					<div class="post-author-desc" itemprop="description">
					<?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
do_action( 'astra_author_info_after' );
