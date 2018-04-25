<?php
namespace ElementPack\Modules\PostGallery\Skins;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

use Elementor\Utils;


use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Abetis extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-abetis';
	}

	public function get_title() {
		return __( 'Abetis', 'bdthemes-element-pack' );
	}

	public function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-post-gallery/section_design_layout/after_section_end', [ $this, 'register_abetis_overlay_animation_controls'   ] );

	}

	public function register_abetis_overlay_animation_controls( Widget_Base $widget ) {

		$this->parent = $widget;
		$this->start_controls_section(
			'section_style_abetis',
			[
				'label' => __( 'Abetis Style', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'desc_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-skin-abetis-desc' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-skin-abetis-desc *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'desc_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-skin-abetis-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'desc_alignment',
			[
				'label'       => __( 'Alignment', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-gallery-skin-abetis-desc' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render_overlay() {
		$settings                    = $this->parent->get_settings();
		$overlay_settings            = [];
		$overlay_settings['class']   = ['bdt-position-cover bdt-overlay bdt-overlay-default'];

		if ($settings['overlay_animation']) {
			$overlay_settings['class'][] = 'bdt-transition-'.$settings['overlay_animation'];
		}

		?>
		<div <?php echo \element_pack_helper::attrs($overlay_settings); ?>>
			<div class="bdt-post-gallery-content">
				<div class="bdt-gallery-content-inner">
					<?php 
					$lightbox_settings                                      = [];
					$lightbox_settings['class']                             = ['bdt-gallery-item-link'];
					$lightbox_settings['class'][]                           = 'elementor-clickable';
					$lightbox_settings['class'][]                           = ( 'icon' == $settings['link_type'] ) ? 'bdt-link-icon' : 'bdt-link-text';
					$lightbox_settings['data-elementor-lightbox-slideshow'] = $this->parent->get_id();
					$lightbox_settings['data-elementor-lightbox-index']     = $this->parent->lightbox_slide_index;
					$img_url                                                = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					$lightbox_settings['href']                              = $img_url[0];
					$this->parent->lightbox_slide_index++;
					
					?>
					<?php if ( 'none' !== $settings['show_link'])  : ?>
						<div class="bdt-flex-inline bdt-gallery-item-link-wrapper">
							<?php if (( 'lightbox' == $settings['show_link'] ) || ( 'both' == $settings['show_link'] )) : ?>
								<a <?php echo \element_pack_helper::attrs($lightbox_settings); ?>>
									<?php if ( 'icon' == $settings['link_type'] ) : ?>
										<span bdt-icon="icon: image"></span>
									<?php elseif ( 'text' == $settings['link_type'] ) : ?>
										<span><?php esc_html_e( 'ZOOM', 'bdthemes-element-pack' ); ?></span>
									<?php endif;?>
								</a>
							<?php endif;?>
							
							<?php if (( 'post' == $settings['show_link'] ) || ( 'both' == $settings['show_link'] )) : ?>
								<?php $link_type_class =  ( 'icon' == $settings['link_type'] ) ? ' bdt-link-icon' : ' bdt-link-text'; ?>
								<a class="bdt-gallery-item-link<?php echo esc_attr($link_type_class); ?>" href="<?php echo esc_attr(get_permalink()); ?>">
									<?php if ( 'icon' == $settings['link_type'] ) : ?>
										<span bdt-icon="icon: more"></span>
									<?php elseif ( 'text' == $settings['link_type'] ) : ?>
										<span><?php esc_html_e( 'VIEW', 'bdthemes-element-pack' ); ?></span>
									<?php endif;?>
								</a>
							<?php endif;?>
						</div>
					<?php endif;?>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_desc() {
		?>
		<div class="bdt-post-gallery-skin-abetis-desc bdt-padding-small">
			<?php
			$this->parent->render_title(); 
			$this->parent->render_categories_names();
			?>
			
		</div>
		<?php
	}
	public function render_post() {
		global $post;

		$settings = $this->parent->get_settings();
		$tilt     = ('yes' === $settings['tilt_show']) ? ' data-tilt' : '';

		$item_settigs = [];

		$item_settigs['class'] = [
			'bdt-gallery-item',
			'filtr-item',
		];


		$tags_classes = array_map( function( $tag ) {
			return $tag->slug;
		}, $post->tags );


		if ($this->parent->get_settings('show_filter_bar')) {
			$item_settigs['class'][] = implode(',', $tags_classes);
		} else {
			$item_settigs['class'][] = 'bdt-position-relative';
		}

		?>
		<div <?php echo \element_pack_helper::attrs($item_settigs); ?><?php echo esc_attr($tilt); ?>>
			<div class="bdt-gallery-item-inner bdt-transition-toggle bdt-position-relative">
				<?php
				$this->parent->render_thumbnail();
				$this->render_overlay();
				?>
			</div>
			<?php $this->render_desc(); ?>
		</div>

		<?php
	}

	public function render() {
		$settings = $this->parent->get_settings();
		$this->parent->query_posts();
		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->parent->get_posts_tags();

		$this->parent->render_loop_header('abetis');

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post();
		}

		$this->parent->render_loop_footer();

		wp_reset_postdata();
	}
}

