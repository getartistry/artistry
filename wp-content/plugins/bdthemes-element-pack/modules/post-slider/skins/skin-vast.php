<?php
namespace ElementPack\Modules\PostSlider\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Vast extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-vast';
	}

	public function get_title() {
		return __( 'Vast', 'bdthemes-element-pack' );
	}

	public function render_loop_item() {
		$settings         = $this->parent->get_settings();
		$slider_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		?>
		<div class="bdt-post-slider-item">
			<div class="bdt-position-relative">
				<img src="<?php echo esc_url($slider_thumbnail[0]); ?>" alt="<?php echo get_the_title(); ?>">
				<?php $this->render_navigation(); ?>
			</div>

			<div class="bdt-post-slider-content bdt-padding-large bdt-background-muted">

	            <?php if ($settings['show_tag']) : ?>
	        		<?php $tags_list = get_the_tag_list('<span class="bdt-background-primary">','</span> <span class="bdt-background-primary">','</span>'); ?>
	        		<?php if ($tags_list) : ?> 
	            		<div class="bdt-post-slider-tag-wrap" bdt-slider-parallax="y: -200,200"><?php  echo  wp_kses_post($tags_list); ?></div>
	            	<?php endif; ?>
	            <?php endif; ?>

				<?php $this->render_title(); ?>

				<?php if ($settings['show_meta']) : ?>
					<div class="bdt-post-slider-meta bdt-flex-inline bdt-flex-middile" bdt-slider-parallax="x: 250,-250">
						<div class="bdt-post-slider-author bdt-border-circle bdt-overflow-hidden bdt-visible@m"><?php echo get_avatar( get_the_author_meta( 'ID' ) , 28 ); ?></div>
						<span><?php echo esc_attr(get_the_author()); ?></span>
						<span><?php esc_html_e('On', 'bdthemes-element-pack'); ?> <?php echo esc_attr(get_the_date('M d, Y')); ?></span>
						<span><?php echo esc_attr(the_category(', ')); ?></span>


					</div>
				<?php endif; ?>
				
				<?php if ( 'yes' == $this->parent->get_settings( 'show_text' ) ) : ?> 
					<?php $this->render_excerpt(); ?>
					<?php $this->render_read_more_button(); ?>
				<?php else : ?>
					<?php $this->render_content(); ?>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}

	public function render_excerpt() {
		if ( ! $this->parent->get_settings( 'show_text' ) ) {
			return;
		}

		?>
		<div class="bdt-post-slider-text bdt-visible@m" bdt-slideshow-parallax="x: 500,-500">
			<?php echo \element_pack_helper::custom_excerpt(intval($this->parent->get_settings( 'excerpt_length' ))); ?>
		</div>
		<?php
	}

	public function render_header() {
		$settings        = $this->parent->get_settings();
		$id              = $this->parent->get_id();
		$slides_settings = [];

		$slider_settings['bdt-slider'] = json_encode(array_filter([
			'autoplay'          => $settings['autoplay'],
			'autoplay-interval' => $settings['autoplay_interval'],
			'pause-on-hover'    => $settings['pause_on_hover'],
	    ]));
	    
		?>
		<div id="bdt-post-slider-<?php echo $id;?>" class="bdt-post-slider bdt-post-slider-skin-vast bdt-position-relative" <?php echo \element_pack_helper::attrs($slider_settings); ?>>
			<div class="bdt-slider-items bdt-child-width-1-1">
		<?php
	}

	public function render_title() {
		if ( ! $this->parent->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->parent->get_settings( 'title_tag' );
		$classes = ['bdt-post-slider-title', 'bdt-margin-remove-bottom'];
		?>
		<div class="bdt-post-slider-title-wrap">
			<a href="<?php echo esc_url(get_permalink()); ?>">
				<<?php echo $tag ?> class="<?php echo implode(" ", $classes); ?>" bdt-slider-parallax="x: 200,-200">
					<?php the_title() ?>
				</<?php echo $tag ?>>
			</a>
		</div>
		<?php
	}

	public function render_footer() {
		?>
			</div>
			
		</div>
		
		<?php
	}

	public function render_navigation() {
		$settings = $this->parent->get_settings();
		$id       = $this->parent->get_id();

		?>
		<div id="<?php echo $id; ?>_nav"  class="bdt-post-slider-navigation">
			<a class="bdt-position-center-left bdt-position-small bdt-hidden-hover" href="#" bdt-slidenav-previous bdt-slider-item="previous"></a>
			<a class="bdt-position-center-right bdt-position-small bdt-hidden-hover" href="#" bdt-slidenav-next bdt-slider-item="next"></a>
		</div>
		<?php
	}

	public function render_content() {
		?>
		<div class="bdt-post-slider-text bdt-visible@m" bdt-slider-parallax="x: 500,-500">
			<?php the_content(); ?>
		</div>
		<?php
	}

	public function render_read_more_button() {
		if ( ! $this->parent->get_settings( 'show_button' ) ) {
			return;
		}
		$settings  = $this->parent->get_settings();
		$animation = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';
		?>
		<div class="bdt-post-slider-button-wrap" bdt-slider-parallax="y: 200,-200">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-post-slider-button bdt-display-inline-block<?php echo esc_attr($animation); ?>">
				<?php echo esc_attr($this->parent->get_settings( 'button_text' )); ?>

				<?php if ($settings['icon']) : ?>
					<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
						<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
					</span>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}

	public function render() {
		$this->parent->query_posts();

		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->render_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();
			$this->render_loop_item();
		}

		$this->render_footer();

		wp_reset_postdata();
	}
}